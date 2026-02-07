<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\MediaLang;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::with('translations')->orderBy('created_at', 'desc')->paginate(24);
        return view('admin.media.index', compact('media'));
    }

    public function create()
    {
        $languages = Language::online()->orderBy('ordering')->get();
        return view('admin.media.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate(['file' => 'required|file|max:10240']);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $media = Media::create([
            'type' => $this->getMediaType($file->getMimeType()),
            'file_name' => $file->getClientOriginalName(),
            'path' => 'storage/' . $path,
            'base_path' => 'storage/media',
            'date' => now(),
        ]);

        foreach (Language::online()->get() as $lang) {
            MediaLang::create([
                'id_media' => $media->id_media,
                'lang' => $lang->lang,
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', 'Dosya yüklendi.');
    }

    public function edit($id)
    {
        $media = Media::with('translations')->findOrFail($id);
        $languages = Language::online()->orderBy('ordering')->get();
        return view('admin.media.edit', compact('media', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        foreach (Language::online()->get() as $lang) {
            MediaLang::updateOrCreate(
                ['id_media' => $media->id_media, 'lang' => $lang->lang],
                [
                    'title' => $request->input("title_{$lang->lang}"),
                    'alt' => $request->input("alt_{$lang->lang}"),
                    'description' => $request->input("description_{$lang->lang}"),
                ]
            );
        }

        return redirect()->route('admin.media.index')->with('success', 'Medya güncellendi.');
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        Storage::disk('public')->delete(str_replace('storage/', '', $media->path));
        $media->translations()->delete();
        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Medya silindi.');
    }

    private function getMediaType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) return 'picture';
        if (str_starts_with($mimeType, 'video/')) return 'video';
        if (str_starts_with($mimeType, 'audio/')) return 'music';
        return 'file';
    }

    /**
     * JSON endpoint for media picker
     */
    public function json()
    {
        $media = Media::where('type', 'picture')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get(['id_media', 'type', 'file_name', 'path']);

        return response()->json($media);
    }

    /**
     * AJAX upload for TinyMCE
     */
    public function uploadAjax(Request $request)
    {
        $request->validate(['file' => 'required|image|max:5120']);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $media = Media::create([
            'type' => 'picture',
            'file_name' => $file->getClientOriginalName(),
            'path' => 'storage/' . $path,
            'base_path' => 'storage/media',
            'date' => now(),
        ]);

        foreach (Language::online()->get() as $lang) {
            MediaLang::create([
                'id_media' => $media->id_media,
                'lang' => $lang->lang,
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            ]);
        }

        return response()->json([
            'location' => '/' . $media->path
        ]);
    }

    /**
     * Get media details for AJAX panel
     */
    public function details($id)
    {
        $media = Media::findOrFail($id);
        
        $dimensions = null;
        if ($media->type === 'picture') {
            $fullPath = public_path($media->path);
            if (file_exists($fullPath)) {
                $size = @getimagesize($fullPath);
                if ($size) {
                    $dimensions = $size[0] . ' × ' . $size[1] . ' px';
                }
            }
        }

        return response()->json([
            'id' => $media->id_media,
            'name' => $media->file_name,
            'type' => $media->type,
            'url' => asset($media->path),
            'size' => $media->file_size ?? 0,
            'sizeFormatted' => $media->file_size ? number_format($media->file_size / 1024, 1) . ' KB' : 'N/A',
            'dimensions' => $dimensions,
            'created' => $media->created_at ? $media->created_at->format('d.m.Y H:i') : 'N/A',
        ]);
    }

    /**
     * Create folder (virtual folder support)
     */
    public function createFolder(Request $request)
    {
        $name = $request->input('name');
        
        if (empty($name)) {
            return response()->json(['success' => false, 'error' => 'Folder name required']);
        }

        // Sanitize folder name
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
        
        $path = storage_path('app/public/media/' . $name);
        
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        return response()->json(['success' => true, 'path' => $name]);
    }

    /**
     * Move file to another folder
     */
    public function moveFile(Request $request)
    {
        $mediaId = $request->input('media_id');
        $targetFolder = $request->input('target_folder', '');
        
        $media = Media::findOrFail($mediaId);
        
        // Current file path
        $currentFullPath = public_path($media->path);
        if (!file_exists($currentFullPath)) {
            return response()->json(['success' => false, 'error' => 'File not found']);
        }
        
        // Build new path
        $basePath = 'storage/media';
        $targetFolder = trim($targetFolder, '/');
        $newPath = $targetFolder ? "{$basePath}/{$targetFolder}/{$media->file_name}" : "{$basePath}/{$media->file_name}";
        $newFullPath = public_path($newPath);
        
        // Create target directory if needed
        $targetDir = dirname($newFullPath);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Move file
        if (rename($currentFullPath, $newFullPath)) {
            $media->update([
                'path' => $newPath,
                'base_path' => $targetFolder ? "storage/media/{$targetFolder}" : 'storage/media',
            ]);
            
            return response()->json(['success' => true, 'new_path' => $newPath]);
        }
        
        return response()->json(['success' => false, 'error' => 'Failed to move file']);
    }

    /**
     * Toplu silme işlemi
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'error' => 'No files selected']);
        }

        $deleted = 0;
        $failed = 0;

        foreach ($ids as $id) {
            try {
                $media = Media::find($id);
                if ($media) {
                    // Dosyayı sil
                    $filePath = public_path($media->path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    
                    // Veritabanından sil
                    $media->translations()->delete();
                    $media->delete();
                    $deleted++;
                }
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'deleted' => $deleted,
            'failed' => $failed,
            'message' => "{$deleted} file(s) deleted" . ($failed > 0 ? ", {$failed} failed" : "")
        ]);
    }

    /**
     * Toplu taşıma işlemi
     */
    public function bulkMove(Request $request)
    {
        $ids = $request->input('ids', []);
        $targetFolder = $request->input('target_folder', '');
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'error' => 'No files selected']);
        }

        $moved = 0;
        $failed = 0;
        $basePath = 'storage/media';
        $targetFolder = trim($targetFolder, '/');

        foreach ($ids as $id) {
            try {
                $media = Media::find($id);
                if ($media) {
                    $currentFullPath = public_path($media->path);
                    
                    if (file_exists($currentFullPath)) {
                        $newPath = $targetFolder 
                            ? "{$basePath}/{$targetFolder}/{$media->file_name}" 
                            : "{$basePath}/{$media->file_name}";
                        $newFullPath = public_path($newPath);
                        
                        // Hedef klasör oluştur
                        $targetDir = dirname($newFullPath);
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0755, true);
                        }
                        
                        if (rename($currentFullPath, $newFullPath)) {
                            $media->update([
                                'path' => $newPath,
                                'base_path' => $targetFolder ? "storage/media/{$targetFolder}" : 'storage/media',
                            ]);
                            $moved++;
                        } else {
                            $failed++;
                        }
                    } else {
                        $failed++;
                    }
                }
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'moved' => $moved,
            'failed' => $failed,
            'message' => "{$moved} file(s) moved" . ($failed > 0 ? ", {$failed} failed" : "")
        ]);
    }

    /**
     * Toplu indirme (ZIP)
     */
    public function bulkDownload(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'error' => 'No files selected']);
        }

        $zipFileName = 'media_download_' . now()->format('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Temp klasörü oluştur
        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return response()->json(['success' => false, 'error' => 'Could not create ZIP file']);
        }

        $addedFiles = 0;
        
        foreach ($ids as $id) {
            $media = Media::find($id);
            if ($media) {
                $filePath = public_path($media->path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $media->file_name);
                    $addedFiles++;
                }
            }
        }

        $zip->close();

        if ($addedFiles === 0) {
            @unlink($zipPath);
            return response()->json(['success' => false, 'error' => 'No valid files to download']);
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Toplu yeniden adlandırma (prefix/suffix ekleme)
     */
    public function bulkRename(Request $request)
    {
        $ids = $request->input('ids', []);
        $prefix = $request->input('prefix', '');
        $suffix = $request->input('suffix', '');
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'error' => 'No files selected']);
        }

        if (empty($prefix) && empty($suffix)) {
            return response()->json(['success' => false, 'error' => 'Please provide prefix or suffix']);
        }

        $renamed = 0;
        $failed = 0;

        foreach ($ids as $id) {
            try {
                $media = Media::find($id);
                if ($media) {
                    $currentFullPath = public_path($media->path);
                    
                    if (file_exists($currentFullPath)) {
                        $ext = pathinfo($media->file_name, PATHINFO_EXTENSION);
                        $name = pathinfo($media->file_name, PATHINFO_FILENAME);
                        $newFileName = $prefix . $name . $suffix . '.' . $ext;
                        
                        $newPath = dirname($media->path) . '/' . $newFileName;
                        $newFullPath = public_path($newPath);
                        
                        if (rename($currentFullPath, $newFullPath)) {
                            $media->update([
                                'file_name' => $newFileName,
                                'path' => $newPath,
                            ]);
                            $renamed++;
                        } else {
                            $failed++;
                        }
                    }
                }
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'success' => true,
            'renamed' => $renamed,
            'failed' => $failed,
            'message' => "{$renamed} file(s) renamed" . ($failed > 0 ? ", {$failed} failed" : "")
        ]);
    }
}

