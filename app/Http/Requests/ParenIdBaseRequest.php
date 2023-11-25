<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ParenIdBaseRequest extends FormRequest
{
    public ?File $parent = null;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->parent = File::find($this->input('parent_id'));
        if($this->parent && !$this->parent->isOwnedBy(Auth::user()->id)) {
            return false;
            
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => [
                Rule::exists(File::class, 'id')
                    ->where(function (Builder $query) {
                        $query->where('is_folder', 1)
                            ->where('created_by', Auth::user()->id);
                    })
            ]
        ];
    }
}
