<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

trait ImageProcessingTrait
{
    /**
     * একটি মিডিয়া টাইপের জন্য সঠিক ডাইমেনশন config ফাইল থেকে রিটার্ন করে।
     */
    private function getDimensionsForType(string $type): array
    {
        $dimensions = config("media-settings.dimensions.{$type}", config('media-settings.dimensions.default'));
        return [$dimensions['width'] ?? null, $dimensions['height'] ?? null];
    }

    /**
     * ছবি প্রসেস এবং সেভ করার হেল্পার মেথড।
     */
    private function processAndSaveImage(?string $tempPath, string $baseFolder, string $subFolder, ?int $width, ?int $height): ?string
    {
        if (!$tempPath || !Storage::disk('public')->exists($tempPath)) {
            Log::warning("Temporary file not found for processing: " . $tempPath);
            return null;
        }
        try {
            $manager = new ImageManager(new Driver());
            $imageContent = Storage::disk('public')->get($tempPath);
            $image = $manager->read($imageContent);

            if ($width && $height) {
                $image->cover($width, $height);
            } else if ($width) {
                $image->scaleDown(width: $width);
            }

            $encodedImage = $image->toJpeg(80);
            $fileName = Str::uuid() . '.jpg';
            $finalPath = "{$baseFolder}/{$subFolder}/{$fileName}";

            Storage::disk('public')->put($finalPath, (string) $encodedImage);
            Storage::disk('public')->delete($tempPath);

            Log::info("Image processed and saved to: " . $finalPath);
            return $finalPath;
        } catch (\Throwable $e) {
            Log::error("Image processing failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return null;
        }
    }
}
