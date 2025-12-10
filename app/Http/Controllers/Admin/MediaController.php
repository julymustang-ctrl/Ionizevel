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
}
