<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Images
{
    /**
     * Store an uploaded image and delete the file it replaces.
     */
    public static function replace(?UploadedFile $file, ?string $currentPath, string $folder): ?string
    {
        if (! $file) {
            return $currentPath;
        }

        $path = $file->store($folder, 'public');

        static::delete($currentPath);

        return $path;
    }

    public static function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public static function url(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
