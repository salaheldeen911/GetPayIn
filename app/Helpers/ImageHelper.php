<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Store an image and return its storage path
     */
    public static function store(UploadedFile $image, string $path = 'posts'): string
    {
        try {
            $extension = $image->getClientOriginalExtension();
            $filename = Str::random(40).'.'.$extension;
            $relativePath = 'storage/'.$path.'/'.$filename;

            // Ensure the public disk directory exists
            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }

            // Store the file using Storage facade
            $stored = Storage::disk('public')->putFileAs(
                $path,
                $image,
                $filename
            );

            if (! $stored) {
                throw new \Exception('Failed to store image');
            }

            Log::info('Image stored successfully', [
                'filename' => $filename,
                'path' => $relativePath,
                'stored' => $stored,
                'full_path' => Storage::disk('public')->path($relativePath),
            ]);

            return $relativePath;
        } catch (\Exception $e) {
            Log::error('Failed to store image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attempted_path' => $path ?? 'unknown',
            ]);
            throw $e;
        }
    }

    /**
     * Delete an image using its storage path
     */
    public static function delete(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        try {
            // Remove 'storage/' prefix if it exists
            $path = str_replace('storage/', '', $path);

            // Delete from storage
            $deleted = Storage::disk('public')->delete($path);

            Log::info('Image deletion attempt', [
                'path' => $path,
                'deleted' => $deleted,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Failed to delete image', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get public URL for a storage path
     */
    public static function getUrl(string $path): string
    {
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/'.str_replace('storage/', '', $path));
    }

    /**
     * Get storage path from public URL
     */
    private static function getPathFromUrl(string $url): string
    {
        $parsedPath = parse_url($url, PHP_URL_PATH);

        return str_replace('/storage/', '', $parsedPath);
    }
}
