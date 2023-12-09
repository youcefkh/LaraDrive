<?php

namespace App\Helpers;

use App\Models\File;

class FileSize
{
    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function folderSize($folder)
    {
        $descendants = $folder->descendants()->where('is_folder', false)->get()->toArray();
        $totalBytes = array_reduce($descendants, fn ($sum, $file) => $sum + $file['size'], 0);

        return self::bytesToHuman($totalBytes);
    }
}
