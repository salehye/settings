<?php

namespace Salehye\Settings\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHandler
{
    /**
     * Upload an image and return the metadata.
     *
     * @param  UploadedFile  $file
     * @param  string  $directory
     * @param  string|null  $disk
     * @return array<string, mixed>
     */
    public function upload(
        UploadedFile $file,
        string $directory = 'settings/images',
        ?string $disk = null
    ): array {
        $disk ??= config('settings.upload.disk', 'public');
        
        // Generate unique filename
        $filename = $this->generateFilename($file);
        
        // Store the file
        $path = $file->storeAs($directory, $filename, $disk);
        
        // Get image details
        $imageData = $this->getImageData($file, $path, $disk);
        
        return $imageData;
    }

    /**
     * Upload a base64 encoded image.
     *
     * @param  string  $base64Image
     * @param  string  $filename
     * @param  string  $directory
     * @param  string|null  $disk
     * @return array<string, mixed>
     */
    public function uploadBase64(
        string $base64Image,
        string $filename,
        string $directory = 'settings/images',
        ?string $disk = null
    ): array {
        $disk ??= config('settings.upload.disk', 'public');
        
        // Decode base64
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        
        // Get extension from mime type
        $mimeType = finfo_buffer(finfo_open(), $imageData, FILEINFO_MIME_TYPE);
        $extension = explode('/', $mimeType)[1] ?? 'png';
        
        // Generate full filename
        $fullFilename = "{$filename}.{$extension}";
        
        // Store the file
        Storage::disk($disk)->put($directory . '/' . $fullFilename, $imageData);
        
        return [
            'path' => Storage::disk($disk)->url($directory . '/' . $fullFilename),
            'original_name' => $filename . '.' . $extension,
            'mime_type' => $mimeType,
            'size' => strlen($imageData),
            'disk' => $disk,
            'directory' => $directory,
            'filename' => $fullFilename,
        ];
    }

    /**
     * Delete an image.
     *
     * @param  string|array  $imageData
     * @param  string|null  $disk
     * @return bool
     */
    public function delete(string|array $imageData, ?string $disk = null): bool
    {
        if (is_string($imageData)) {
            // Just a path string
            $disk ??= config('settings.upload.disk', 'public');
            return Storage::disk($disk)->delete($imageData);
        }
        
        // Array with metadata
        $disk = $disk ?? ($imageData['disk'] ?? config('settings.upload.disk', 'public'));
        $path = $imageData['directory'] ?? dirname($imageData['path']) . '/' . ($imageData['filename'] ?? basename($imageData['path']));
        
        return Storage::disk($disk)->delete($path);
    }

    /**
     * Get image data from uploaded file.
     *
     * @param  UploadedFile  $file
     * @param  string  $path
     * @param  string  $disk
     * @return array<string, mixed>
     */
    protected function getImageData(UploadedFile $file, string $path, string $disk): array
    {
        $imageData = [
            'path' => Storage::disk($disk)->url($path),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'disk' => $disk,
            'directory' => dirname($path),
            'filename' => basename($path),
        ];
        
        // Try to get image dimensions
        $imagePath = Storage::disk($disk)->path($path);
        if (file_exists($imagePath)) {
            $dimensions = @getimagesize($imagePath);
            if ($dimensions) {
                $imageData['width'] = $dimensions[0];
                $imageData['height'] = $dimensions[1];
            }
        }
        
        return $imageData;
    }

    /**
     * Generate a unique filename.
     *
     * @param  UploadedFile  $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::random(40) . '.' . $extension;
    }

    /**
     * Validate an image file.
     *
     * @param  UploadedFile  $file
     * @param  array<string, mixed>  $rules
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(UploadedFile $file, array $rules = []): void
    {
        $defaultRules = config('settings.upload.default_rules', [
            'max_size' => 2048, // KB
            'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
        ]);
        
        $rules = array_merge($defaultRules, $rules);
        
        // Check file size
        if (isset($rules['max_size'])) {
            $maxSizeBytes = $rules['max_size'] * 1024;
            if ($file->getSize() > $maxSizeBytes) {
                throw new \Illuminate\Validation\ValidationException(
                    null,
                    [],
                    null,
                    __('The image size must be less than :max MB.', ['max' => $rules['max_size'] / 1024])
                );
            }
        }
        
        // Check MIME type
        if (isset($rules['allowed_types'])) {
            if (!in_array($file->getMimeType(), $rules['allowed_types'])) {
                throw new \Illuminate\Validation\ValidationException(
                    null,
                    [],
                    null,
                    __('The image type is not allowed.')
                );
            }
        }
        
        // Check dimensions if specified
        if (isset($rules['max_width']) || isset($rules['max_height'])) {
            $imagePath = $file->getRealPath();
            $dimensions = @getimagesize($imagePath);
            
            if ($dimensions) {
                if (isset($rules['max_width']) && $dimensions[0] > $rules['max_width']) {
                    throw new \Illuminate\Validation\ValidationException(
                        null,
                        [],
                        null,
                        __('The image width must not exceed :max pixels.', ['max' => $rules['max_width']])
                    );
                }
                
                if (isset($rules['max_height']) && $dimensions[1] > $rules['max_height']) {
                    throw new \Illuminate\Validation\ValidationException(
                        null,
                        [],
                        null,
                        __('The image height must not exceed :max pixels.', ['max' => $rules['max_height']])
                    );
                }
            }
        }
    }

    /**
     * Resize an image.
     *
     * @param  string  $imagePath
     * @param  int  $width
     * @param  int  $height
     * @param  string|null  $disk
     * @return string
     */
    public function resize(
        string $imagePath,
        int $width,
        int $height,
        ?string $disk = null
    ): string {
        $disk ??= config('settings.upload.disk', 'public');
        
        if (!function_exists('imagecreatetruecolor')) {
            throw new \RuntimeException('GD extension is not installed.');
        }
        
        $sourcePath = Storage::disk($disk)->path($imagePath);
        $imageInfo = @getimagesize($sourcePath);
        
        if (!$imageInfo) {
            throw new \RuntimeException('Invalid image file.');
        }
        
        $sourceMime = $imageInfo['mime'];
        $sourceWidth = $imageInfo[0];
        $sourceHeight = $imageInfo[1];
        
        // Create source image
        $sourceImage = $this->createImageFromPath($sourcePath, $sourceMime);
        
        // Create new image with specified dimensions
        $resizedImage = imagecreatetruecolor($width, $height);
        
        // Preserve transparency for PNG and GIF
        if (in_array($sourceMime, ['image/png', 'image/gif'])) {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefilledrectangle($resizedImage, 0, 0, $width, $height, $transparent);
        }
        
        // Resize
        imagecopyresampled(
            $resizedImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $sourceWidth,
            $sourceHeight
        );
        
        // Generate new filename
        $pathInfo = pathinfo($imagePath);
        $resizedPath = ($pathInfo['dirname'] ?? '') . '/' . $pathInfo['filename'] . "_{$width}x{$height}." . ($pathInfo['extension'] ?? 'png');
        
        // Save resized image
        $this->saveImage($resizedImage, Storage::disk($disk)->path($resizedPath), $sourceMime);
        
        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);
        
        return $resizedPath;
    }

    /**
     * Create image resource from path.
     *
     * @param  string  $path
     * @param  string  $mime
     * @return resource
     */
    protected function createImageFromPath(string $path, string $mime)
    {
        return match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/gif' => imagecreatefromgif($path),
            'image/webp' => imagecreatefromwebp($path),
            default => throw new \RuntimeException("Unsupported image format: {$mime}"),
        };
    }

    /**
     * Save image resource to path.
     *
     * @param  resource  $image
     * @param  string  $path
     * @param  string  $mime
     * @return void
     */
    protected function saveImage($image, string $path, string $mime): void
    {
        match ($mime) {
            'image/jpeg' => imagejpeg($image, $path, 90),
            'image/png' => imagepng($image, $path, 9),
            'image/gif' => imagegif($image, $path),
            'image/webp' => imagewebp($image, $path, 90),
            default => throw new \RuntimeException("Unsupported image format: {$mime}"),
        };
    }

    /**
     * Get the URL for an image.
     *
     * @param  string|array  $imageData
     * @return string|null
     */
    public function url(string|array $imageData): ?string
    {
        if (is_string($imageData)) {
            return $imageData;
        }
        
        return $imageData['path'] ?? null;
    }

    /**
     * Check if image data is valid.
     *
     * @param  mixed  $imageData
     * @return bool
     */
    public function isValid(mixed $imageData): bool
    {
        if (is_string($imageData)) {
            return !empty($imageData);
        }
        
        if (is_array($imageData)) {
            return isset($imageData['path']) && !empty($imageData['path']);
        }
        
        return false;
    }
}
