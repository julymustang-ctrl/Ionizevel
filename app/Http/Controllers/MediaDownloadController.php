<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Güvenli Medya İndirme Controller
 * 
 * SHA-1 hash doğrulama ile dosya indirme
 * Ionize CMS'in <media:download /> etiketini uygular
 */
class MediaDownloadController extends Controller
{
    /**
     * SHA-1 hash ile güvenli dosya indirme
     *
     * @param Request $request
     * @param int $id Media ID
     * @param string $hash SHA-1 hash
     * @return StreamedResponse|\Illuminate\Http\Response
     */
    public function download(Request $request, int $id, string $hash)
    {
        $media = Media::find($id);

        if (!$media) {
            abort(404, 'File not found');
        }

        // SHA-1 hash doğrulama
        $expectedHash = sha1($media->id_media . config('app.key'));
        
        if (!hash_equals($expectedHash, $hash)) {
            abort(403, 'Invalid download link');
        }

        $filePath = public_path($media->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }

        // İndirme sayacını artır (opsiyonel)
        $this->incrementDownloadCount($media);

        // Dosyayı stream olarak gönder
        return response()->download($filePath, $media->file_name, [
            'Content-Type' => $media->type ?? 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $media->file_name . '"',
        ]);
    }

    /**
     * Anlık görüntüleme (inline display)
     */
    public function view(Request $request, int $id, string $hash)
    {
        $media = Media::find($id);

        if (!$media) {
            abort(404, 'File not found');
        }

        // SHA-1 hash doğrulama
        $expectedHash = sha1($media->id_media . config('app.key'));
        
        if (!hash_equals($expectedHash, $hash)) {
            abort(403, 'Invalid view link');
        }

        $filePath = public_path($media->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }

        $mimeType = $media->type ?? mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * Geçici indirme linki oluştur (expiring link)
     */
    public static function generateDownloadUrl(Media $media, int $expiresInMinutes = 60): string
    {
        $hash = sha1($media->id_media . config('app.key'));
        
        return route('media.download', [
            'id' => $media->id_media,
            'hash' => $hash
        ]);
    }

    /**
     * Süreli indirme linki (zaman damgalı)
     */
    public static function generateTimedUrl(Media $media, int $expiresInMinutes = 60): string
    {
        $expires = now()->addMinutes($expiresInMinutes)->timestamp;
        $hash = sha1($media->id_media . $expires . config('app.key'));
        
        return route('media.download.timed', [
            'id' => $media->id_media,
            'expires' => $expires,
            'hash' => $hash
        ]);
    }

    /**
     * Süreli link ile indirme
     */
    public function downloadTimed(Request $request, int $id, int $expires, string $hash)
    {
        // Süre kontrolü
        if (now()->timestamp > $expires) {
            abort(410, 'Download link has expired');
        }

        $media = Media::find($id);

        if (!$media) {
            abort(404, 'File not found');
        }

        // Hash doğrulama
        $expectedHash = sha1($media->id_media . $expires . config('app.key'));
        
        if (!hash_equals($expectedHash, $hash)) {
            abort(403, 'Invalid download link');
        }

        $filePath = public_path($media->path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }

        return response()->download($filePath, $media->file_name);
    }

    /**
     * İndirme sayacını artır
     */
    protected function incrementDownloadCount(Media $media): void
    {
        // downloads sütunu varsa artır
        if (in_array('downloads', $media->getFillable())) {
            $media->increment('downloads');
        }
    }
}
