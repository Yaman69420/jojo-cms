<?php

namespace App\Console\Commands;

use App\Models\Episode;
use App\Models\Media;
use App\Models\Part;
use App\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompressExistingMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:compress {--force : Force compression even if it looks like it was already compressed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress all existing posters and thumbnails to WebP';

    /**
     * Execute the console command.
     */
    public function handle(ImageService $imageService)
    {
        $savedBytes = 0;

        // 1. Process Media Model items
        $this->info('Checking Media records...');
        $mediaItems = Media::all();
        $bar = $this->output->createProgressBar($mediaItems->count());
        $bar->start();

        foreach ($mediaItems as $media) {
            if (! $this->shouldProcess($media->path)) {
                $bar->advance();

                continue;
            }

            $oldSize = $media->size ?: $this->getFileSize($media->path);
            $result = $imageService->compressExisting($media->path);

            if ($result) {
                $oldPath = $media->path;
                $media->update([
                    'path' => $result['path'],
                    'original_name' => $result['original_name'],
                    'mime_type' => $result['mime_type'],
                    'size' => $result['size'],
                ]);
                if ($oldPath !== $result['path']) {
                    Storage::disk('public')->delete($oldPath);
                }
                $savedBytes += ($oldSize - $result['size']);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        // 2. Process Part poster_path
        $this->info('Checking Part posters...');
        $parts = Part::whereNotNull('poster_path')->get();
        $bar = $this->output->createProgressBar($parts->count());
        $bar->start();
        foreach ($parts as $part) {
            if (! $this->shouldProcess($part->poster_path)) {
                $bar->advance();

                continue;
            }
            $oldSize = $this->getFileSize($part->poster_path);
            $result = $imageService->compressExisting($part->poster_path);
            if ($result) {
                $oldPath = $part->poster_path;
                $part->update(['poster_path' => $result['path']]);
                if ($oldPath !== $result['path']) {
                    Storage::disk('public')->delete($oldPath);
                }
                $savedBytes += ($oldSize - $result['size']);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        // 3. Process Episode thumbnail_url
        $this->info('Checking Episode thumbnails...');
        $episodes = Episode::whereNotNull('thumbnail_url')->get();
        $bar = $this->output->createProgressBar($episodes->count());
        $bar->start();
        foreach ($episodes as $episode) {
            if (! $episode->thumbnail_url) {
                $bar->advance();

                continue;
            }

            // If it's an external URL, download and compress it
            if (str_starts_with($episode->thumbnail_url, 'http')) {
                $result = $imageService->downloadAndCompress($episode->thumbnail_url, 'media/episodes');
                if ($result) {
                    $episode->update(['thumbnail_url' => $result['path']]);
                    // No size saving to report easily since it was remote
                }
            } else {
                if (! $this->shouldProcess($episode->thumbnail_url)) {
                    $bar->advance();

                    continue;
                }
                $oldSize = $this->getFileSize($episode->thumbnail_url);
                $result = $imageService->compressExisting($episode->thumbnail_url);
                if ($result) {
                    $oldPath = $episode->thumbnail_url;
                    $episode->update(['thumbnail_url' => $result['path']]);
                    if ($oldPath !== $result['path']) {
                        Storage::disk('public')->delete($oldPath);
                    }
                    $savedBytes += ($oldSize - $result['size']);
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();

        $savedMb = round($savedBytes / 1024 / 1024, 2);
        $this->info("Compression complete! Saved approximately {$savedMb} MB.");
    }

    private function shouldProcess($path): bool
    {
        if (! $path) {
            return false;
        }
        if (! $this->option('force') && str_ends_with($path, '.webp')) {
            return false;
        }

        return (bool) preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path);
    }

    private function getFileSize($path): int
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->size($path);
        }

        return 0;
    }
}
