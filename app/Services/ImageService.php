<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    /**
     * Download an external image, compress it, and store it.
     */
    public function downloadAndCompress(string $url, string $directory = 'media', int $maxWidth = 1200, int $quality = 80): ?array
    {
        try {
            $response = Http::get($url);
            if (! $response->successful()) {
                return null;
            }

            $tempPath = tempnam(sys_get_temp_dir(), 'img');
            file_put_contents($tempPath, $response->body());

            ini_set('memory_limit', '512M');
            $manager = new ImageManager(new Driver);
            $image = $manager->read($tempPath);

            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            $encoded = $image->toWebp($quality);

            $urlPath = parse_url($url, PHP_URL_PATH);
            $nameWithoutExt = pathinfo($urlPath, PATHINFO_FILENAME);
            $filename = $directory.'/'.uniqid().'.webp';

            Storage::disk('public')->put($filename, (string) $encoded);
            unlink($tempPath);

            return [
                'path' => $filename,
                'original_name' => $nameWithoutExt.'.webp',
                'mime_type' => 'image/webp',
                'size' => Storage::disk('public')->size($filename),
            ];
        } catch (\Throwable $e) {
            Log::error('Download & Compression Error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Compress and store an image.
     */
    public function compressAndStore(UploadedFile $file, string $directory = 'media', int $maxWidth = 1200, int $quality = 80): array
    {
        try {
            ini_set('memory_limit', '512M');
            $manager = new ImageManager(new Driver);
            $image = $manager->read($file->getRealPath());

            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            $encoded = $image->toWebp($quality);

            $originalName = $file->getClientOriginalName();
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            $filename = $directory.'/'.uniqid().'.webp';

            Storage::disk('public')->put($filename, (string) $encoded);

            return [
                'path' => $filename,
                'original_name' => $nameWithoutExt.'.webp',
                'mime_type' => 'image/webp',
                'size' => Storage::disk('public')->size($filename),
            ];
        } catch (\Throwable $e) {
            Log::error('Image Compression Error: '.$e->getMessage());

            // Fallback to normal storage if compression fails
            $path = $file->store($directory, 'public');

            return [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ];
        }
    }

    /**
     * Compress an existing file on disk.
     */
    public function compressExisting(string $path, int $maxWidth = 1200, int $quality = 80): ?array
    {
        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        try {
            ini_set('memory_limit', '512M');
            $fullPath = Storage::disk('public')->path($path);

            // Only compress if it's an image
            $mime = Storage::disk('public')->mimeType($path);
            if (! str_starts_with($mime, 'image/')) {
                return null;
            }

            $manager = new ImageManager(new Driver);
            $image = $manager->read($fullPath);

            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            $encoded = $image->toWebp($quality);

            $pathInfo = pathinfo($path);
            $newPath = $pathInfo['dirname'].'/'.$pathInfo['filename'].'_compressed.webp';

            Storage::disk('public')->put($newPath, (string) $encoded);

            return [
                'path' => $newPath,
                'original_name' => $pathInfo['filename'].'.webp',
                'mime_type' => 'image/webp',
                'size' => Storage::disk('public')->size($newPath),
            ];
        } catch (\Throwable $e) {
            Log::error('Existing Image Compression Error: '.$e->getMessage());

            return null;
        }
    }
}
