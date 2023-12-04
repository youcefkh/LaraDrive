<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
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
        if ($folder) {
            $folder = File::where('created_by', Auth::user()->id)
                ->where('path', $folder)
                ->firstOrFail();
        } else {
            $folder = $this->getRoot();
        }
        $files = File::where('created_by', Auth::user()->id)
            ->where('parent_id', $folder->id)
            ->orderBy('is_folder', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $files = FileResource::collection($files);
        $ancestors = FileResource::collection([...$folder->ancestors, $folder]);
        $folder = new FileResource($folder);

        return Inertia::render('MyFiles', compact('files', 'ancestors', 'folder'));
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

    public function store(StoreFileRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;
        $userId = $request->user()->id;
        $fileTree = $request->file_tree;

        if (!$parent) {
            $parent = $this->getRoot();
        }

        if (!empty($fileTree)) {
            $this->saveFileTree($fileTree, $parent, $userId);
        } else {
            foreach ($data['files'] as $file) {
                $this->saveFile($file, $parent, $userId);
            }
        }
    }

    private function saveFileTree($fileTree, $parent, $userId)
    {
        foreach ($fileTree as $name => $file) {
            if (is_array($file)) {
                $folder = new File();
                $folder->name = $name;
                $folder->is_folder = true;

                $parent->appendNode($folder);
                $this->saveFileTree($file, $folder, $userId);
            } else {
                $this->saveFile($file, $parent, $userId);
            }
        }
    }

    private function saveFile($file, $parent, $userId)
    {
        $path = $file->store('/files/' . $userId);
        $fileModel = new File();
        $fileModel->name = $file->getClientOriginalName();
        $fileModel->storage_path = $path;
        $fileModel->created_by = $userId;
        $fileModel->is_folder = false;
        $fileModel->mime = $file->getClientMimeType();
        $fileModel->size = $file->getSize();
        $parent->appendNode($fileModel);
    }
}
