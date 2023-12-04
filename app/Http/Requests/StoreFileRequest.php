<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFileRequest extends ParenIdBaseRequest
{
    /**
     * Prepares the data for validation.
     *
     * This method filters the relative paths array, removing any null values.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $paths = array_filter($this->relative_paths ?? [], fn ($path) => $path != null);

        $this->merge([
            'file_paths' => $paths,
            'folder_name' => $this->getFolderName($paths),
        ]);
    }

    /**
     * Override the passed validation.
     * 
     * Will only run when the validation is passed.
     * 
     * @return void
     */
    protected function passedValidation()
    {
        $data = $this->validated();

        $this->replace([
            'file_tree' => $this->buildFileTree($this->file_paths, $data['files']),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'files.*' => [
                    'required',
                    'file',
                    function ($attribute, $value, $fail) {
                        if (!$this->folder_name) {
                            /** @var \Illuminate\Http\UploadedFile $value */
                            $file = File::where('name', $value->getClientOriginalName())
                                ->where('created_by', Auth::user()->id)
                                ->where('parent_id', $this->parent_id)
                                ->whereNull('deleted_at')
                                ->exists();

                            if ($file) {
                                $fail('File "' . $value->getClientOriginalName() . '" already exists');
                            }
                        }
                    }
                ],

                'folder_name' => [
                    'nullable',
                    'string',
                    function ($attribute, $value, $fail) {
                        if ($value) {
                            /** @var \Illuminate\Http\UploadedFile $value */
                            $file = File::where('name', $value)
                                ->where('created_by', Auth::user()->id)
                                ->where('parent_id', $this->parent_id)
                                ->whereNull('deleted_at')
                                ->exists();

                            if ($file) {
                                $fail('Folder "' . $value . '" already exists');
                            }
                        }
                    }

                ],
            ]
        );
    }

    private function getFolderName($paths)
    {
        if (!$paths) {
            return null;
        }

        $parts = explode('/', $paths[0]);
        return $parts[0];
    }

    private function buildFileTree($paths, $files)
    {
        $paths = array_slice($paths, 0, count($files));
        $paths = array_filter($paths, fn ($path) => $path != null);
        $tree = [];
        /**
         * exp: path = 'folder1/folder2/img.png'
         * 
         * $tree = [
         *   'folder1' => [
         *     'folder2' => [
         *       'img.png'
         *     ]
         *   ]
         * ]
         */

        foreach ($paths as $index => $path) {
            $parts = explode('/', $path);
            $currentNode = &$tree;

            foreach ($parts as $i => $part) {
                if(!isset($currentNode[$part])) {
                    $currentNode[$part] = [];
                }

                if ($i == count($parts) - 1) {
                    $currentNode[$part] = $files[$index];
                } else {
                    $currentNode = &$currentNode[$part];
                }
            }
        }
        
        return $tree;
    }
}
