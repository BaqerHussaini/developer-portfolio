<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Images
{
    /**
     * The disk uploads are written to (public locally, supabase in production).
     */
    public static function disk(): string
    {
        return config('filesystems.upload_disk', 'public');
    }

    /**
     * Store an uploaded image and delete the file it replaces.
     */
    public static function replace(?UploadedFile $file, ?string $currentPath, string $folder): ?string
    {
        if (! $file) {
            return $currentPath;
        }

        $path = $file->store($folder, static::disk());

        static::delete($currentPath);

        return $path;
    }

    public static function delete(?string $path): void
    {
        if ($path && Storage::disk(static::disk())->exists($path)) {
            Storage::disk(static::disk())->delete($path);
        }
    }

    public static function url(?string $path): ?string
    {
        return $path ? Storage::disk(static::disk())->url($path) : null;
    }
}
