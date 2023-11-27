<?php

namespace App\Http\Controllers;

use App\Models\File;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Resources\FileResource;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function myFiles(string $folder = null)
    {
        if($folder) {
            $folder = File::where('created_by', Auth::user()->id)
                ->where('path', $folder)
                ->firstOrFail();
        }else {
            $folder = $this->getRoot();
        }
        $files = File::where('created_by', Auth::user()->id)
            ->where('parent_id', $folder->id)
            ->orderBy('is_folder', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $files = FileResource::collection($files);
        return Inertia::render('MyFiles', compact('files', 'folder'));
    }

    public function createFolder(StoreFolderRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        if (!$parent) {
            $parent = $this->getRoot();
        }

        $file = new File();
        $file->is_folder = true;
        $file->name = $data['name'];

        $parent->appendNode($file);
    }

    private function getRoot()
    {
        return File::whereIsRoot()->where('created_by', Auth::user()->id)->firstOrFail();
    }
}
