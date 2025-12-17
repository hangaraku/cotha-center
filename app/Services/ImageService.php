<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Generate a thumbnail version of an image
     * 
     * @param string $imagePath The original image path relative to uploads directory
     * @param int $width The desired thumbnail width
     * @param int $height The desired thumbnail height
     * @return string The thumbnail path or original path if thumbnail generation fails
     */
    public static function getThumbnail($imagePath, $width = 400, $height = 300)
    {
        if (!$imagePath) {
            return null;
        }

        // Check if original image exists
        $originalPath = public_path('uploads/' . $imagePath);
        if (!file_exists($originalPath)) {
            return $imagePath; // Return original path if file doesn't exist
        }

        // Create thumbnails directory if it doesn't exist
        $thumbnailDir = public_path('uploads/thumbnails');
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }

        // Generate thumbnail filename
        $pathInfo = pathinfo($imagePath);
        $thumbnailName = $pathInfo['filename'] . '_thumb_' . $width . 'x' . $height . '.' . $pathInfo['extension'];
        $thumbnailPath = 'thumbnails/' . $thumbnailName;
        $fullThumbnailPath = public_path('uploads/' . $thumbnailPath);

        // Return existing thumbnail if it exists and is newer than original
        if (file_exists($fullThumbnailPath) && filemtime($fullThumbnailPath) >= filemtime($originalPath)) {
            return $thumbnailPath;
        }

        try {
            // Generate thumbnail using Intervention Image
            $image = Image::make($originalPath);
            
            // Resize and optimize
            $image->fit($width, $height, function ($constraint) {
                $constraint->upsize(); // Prevent upscaling
            });
            
            // Optimize quality based on file type
            if ($pathInfo['extension'] === 'jpg' || $pathInfo['extension'] === 'jpeg') {
                $image->save($fullThumbnailPath, 75); // 75% quality for JPG
            } else if ($pathInfo['extension'] === 'png') {
                $image->save($fullThumbnailPath, 80); // 80% quality for PNG
            } else {
                $image->save($fullThumbnailPath, 75);
            }

            return $thumbnailPath;
        } catch (\Exception $e) {
            // If thumbnail generation fails, return original path
            \Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return $imagePath;
        }
    }

    /**
     * Get optimized profile picture thumbnail
     * 
     * @param string $imagePath The original image path
     * @return string The thumbnail path
     */
    public static function getProfileThumbnail($imagePath)
    {
        return self::getThumbnail($imagePath, 100, 100);
    }

    /**
     * Get optimized project thumbnail
     * 
     * @param string $imagePath The original image path
     * @return string The thumbnail path
     */
    public static function getProjectThumbnail($imagePath)
    {
        return self::getThumbnail($imagePath, 400, 300);
    }
}
