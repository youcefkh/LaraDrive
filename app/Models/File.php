<?php

namespace App\Models;

use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;
use App\Traits\HasCreatorAndUpdater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory, HasCreatorAndUpdater, NodeTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'path',
        'is_folder',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent()
    {
        return $this->belongsTo(File::class, 'parent_id');
    }

    public function isOwnedBy($userId): bool
    {
        return $this->created_by == $userId;
    }

    public function isRoot()
    {
        return $this->parent_id === null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (File $file) {
            if($file->isRoot()) {
                return;
            }
            $file->path = (!$file->parent->isRoot() ? $file->parent->path . '/' : '') . Str::slug($file->name);
        });
    }
}
