<?php

namespace App\Http\Requests;

use App\Http\Requests\ParenIdBaseRequest;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFolderRequest extends ParenIdBaseRequest
{
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
                'name' => [
                    'required', 'string', 'max:255',
                    Rule::unique(File::class, 'name')
                        ->where('created_by', Auth::user()->id)
                        ->where('parent_id', $this->parent_id)
                        ->whereNull('created_at')
                ],
            ]
        );
    }

    public function messages()
    {
        return [
            'name.unique' => 'Folder ":input" already exists.',
        ];
    }
}
