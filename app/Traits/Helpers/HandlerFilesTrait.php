<?php

namespace App\Traits\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Exception;
use InvalidArgumentException;
use RuntimeException;

trait HandlerFilesTrait
{
    /**
     * Upload a file or content to a specific folder and disk. If the fileOrContent variable is an instance of UploadedFile and its mime type is neither 'image/svg+xml' nor starts with 'image/', handle it as non-image file. Otherwise, handle it as file or content. Check if the file already exists on the disk. If it does, and if allowRename is true, generate a new filename until the file does not exist on the disk. If allowRename is false, throw a RuntimeException. Then, put the content to the path on the disk.
     *
     * @param string|UploadedFile $fileOrContent
     * @param string $folder
     * @param string $disk
     * @param string|null $filename
     * @param bool $allowRename
     * @throws RuntimeException
     * @return string the path where the file is uploaded
     */
    public function upload(string|UploadedFile $fileOrContent, string $folder, string $disk = 'public', string $filename = null, bool $allowRename = true): string
    {
        $this->validateDisk($disk); // Validate the disk variable

        try {
            if ($fileOrContent instanceof UploadedFile && !Str::startsWith($fileOrContent->getMimeType(), 'image/')) {
                // Handle the file as a non-image file
                list($filename, $content) = $this->handleNonImageFile($fileOrContent, $filename);
                $folder = 'files/'.$folder; // Set the folder to 'files' for non-image files
            } else {
                // Handle the file or content as an image
                list($filename, $content) = $this->handleFileOrContent($fileOrContent, $filename);
                $folder = 'images/'.$folder; // Set the folder to 'img' for image files
            }

            $path = $folder . '/' . $filename; // Generate the path

            if (Storage::disk($disk)->exists($path)) { // If the file already exists on the disk
                if ($allowRename) { // If renaming is allowed
                    do {
                        $filename = Str::uuid() . strrchr($filename, '.'); // Generate a new filename
                        $path = $folder . '/' . $filename; // Generate a new path
                    } while (Storage::disk($disk)->exists($path)); // Repeat until the file does not exist on the disk
                } else { // If renaming is not allowed
                    throw new RuntimeException('File already exists on the disk.'); // Throw a RuntimeException
                }
            }

            Storage::disk($disk)->put($path, $content); // Put the content to the path on the disk

            if (app()->environment('local')) { // If the environment is local
                Log::channel('fileOperations')->debug(json_encode([ // Log a debug message
                    'message' => 'File uploaded successfully',
                    'path' => $path,
                ]));
            }

            return $path; // Return the path where the file is uploaded
        } catch (Exception $e) {
            Log::channel('fileOperations')->error(json_encode([ // Log an error message
                'message' => 'File upload failed',
                'error' => $e->getMessage(),
            ]));

            throw new RuntimeException('Failed to upload file.', 0, $e); // Throw a RuntimeException
        }
    }

    /**
     * Delete a file if it exists on the disk.
     *
     * @param string $path
     * @param string $disk
     * @return void
     */
    public function deleteFileIfExists(string $path, $disk = 'public'): void
    {
        $this->validateDisk($disk); // Validate the disk variable

        if ($path && Storage::disk($disk)->exists($path)) { // If the path is not null and the file exists on the disk
            Storage::disk($disk)->delete($path); // Delete the file
        }
    }

    /**
     * Validate the disk variable. It should be a string and it should match with the disk names specified in the config/filesystems.php file.
     *
     * @param string $disk
     * @throws InvalidArgumentException if the disk variable is not configured in config/filesystems.php
     * @return void
     */
    protected function validateDisk(string $disk)
    {
        $availableDisks = array_keys(config('filesystems.disks')); // Get the available disks from the config

        if (!in_array($disk, $availableDisks)) {
            throw new InvalidArgumentException('The provided disk is not configured.');
        }
    }

    /**
     * Process the image file. Convert it into webp format, fit it into 1000x1000 size without upsizing, and return the binary data.
     *
     * @param mixed $file
     * @return string the processed image data
     */
    protected function processImage(mixed $file)
    {
        return (string) Image::make($file)
            ->encode('webp', 100) // Convert to webp format with quality 100
            ->fit(1000, 1000, function ($constraint) {
                $constraint->upsize(); // Fit to 1000x1000 size without upsizing
            })
            ->encode(); // Return the binary data
    }

    /**
     * Handle SVG content. Return an array with the filename and the content.
     *
     * @param mixed $content
     * @param string|null $filename
     * @return array the filename and the content
     */
    protected function handleSVGContent(mixed $content, string $filename = null)
    {
        $filename = $filename ? $filename . '.svg' : Str::uuid() . '.svg'; // Use the provided filename if it exists. Otherwise, generate a random filename.
        return [$filename, $content]; // Return the filename and the content
    }

    /**
     * Handle image content. Process the image, and then return an array with the filename and the processed content.
     *
     * @param mixed $content
     * @param string|null $filename
     * @return array the filename and the processed content
     */
    protected function handleImageContent(mixed $content, string $filename = null)
    {
        $filename = $filename ? $filename . '.webp' : Str::uuid() . '.webp'; // Use the provided filename if it exists. Otherwise, generate a random filename.
        $processedContent = $this->processImage($content); // Process the image content
        return [$filename, $processedContent]; // Return the filename and the processed content
    }

    /**
     * Handle non-image file. Return an array with the filename and the content.
     *
     * @param UploadedFile $file
     * @param string|null $filename
     * @return array the filename and the content
     */
    protected function handleNonImageFile(UploadedFile $file, string $filename = null)
    {
        $extension = $file->getClientOriginalExtension(); // Get the extension of the file
        $filename = $filename ? $filename . '.' . $extension : Str::uuid() . '.' . $extension; // Use the provided filename if it exists. Otherwise, generate a random filename.
        $content = file_get_contents($file); // Get the content of the file
        return [$filename, $content]; // Return the filename and the content
    }

    /**
     * Handle file or content. It should be either a string (like a URL) or an instance of UploadedFile.
     * If it's a URL, get the content from the URL. If the content is an SVG image, handle it as SVG content. Otherwise, handle it as image content.
     * If it's not a URL, check if it's a string starting with '<svg'. If yes, handle it as SVG content. If not, it should be an instance of UploadedFile. If the file extension is 'svg', handle it as SVG content. Otherwise, handle it as image content.
     *
     * @param string|UploadedFile $fileOrContent
     * @param string|null $filename
     * @return array the filename and the content
     */
    protected function handleFileOrContent(string|UploadedFile $fileOrContent, string $filename = null)
    {
        if (filter_var($fileOrContent, FILTER_VALIDATE_URL)) {
            $headers = get_headers($fileOrContent, 1); // Get the headers from the URL
            if (strpos($headers["Content-Type"], "image/svg+xml") !== false) { // If the content type is SVG image, handle it as SVG content
                $content = file_get_contents($fileOrContent); // Get the content from the URL
                return $this->handleSVGContent($content, $filename);
            } else { // If the content type is not SVG image, handle it as image content
                $content = file_get_contents($fileOrContent); // Get the content from the URL
                $image = Image::make($content); // Create an instance of Image

                if ($image->mime() === 'image/svg+xml') { // If the mime type of the image is SVG, handle it as SVG content
                    return $this->handleSVGContent($content, $filename);
                } else { // If the mime type of the image is not SVG, handle it as image content
                    return $this->handleImageContent($content, $filename);
                }
            }
        } else {
            if (is_string($fileOrContent) && Str::startsWith(trim($fileOrContent), '<svg')) { // If it's a string starting with '<svg', handle it as SVG content
                return $this->handleSVGContent($fileOrContent, $filename);
            } else { // If it's not a string starting with '<svg', it should be an instance of UploadedFile
                $file = $fileOrContent; // Get the file from the fileOrContent variable
                $extension = $file->getClientOriginalExtension(); // Get the extension of the file
                $content = file_get_contents($file); // Get the content of the file

                if ($extension === 'svg') { // If the extension is 'svg', handle it as SVG content
                    return $this->handleSVGContent($content, $filename);
                } else { // If the extension is not 'svg', handle it as image content
                    return $this->handleImageContent($file, $filename);
                }
            }
        }
    }

    /**
     * Generate a temporary URL for the file.
     *
     * @param string $path
     * @param string $disk
     * @param int $expiration the expiration time in minutes. The default value is 5 minutes.
     * @return string the temporary URL
     */
    public function getTemporaryUrl(string $path, string $disk = 'public', int $expiration = 5): string
    {
        $this->validateDisk($disk); // Validate the disk variable

        // Generate the temporary URL for the file. This URL will be valid for the specified expiration time.
        return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes($expiration));
    }

    /**
     * Get the file as a response.
     *
     * @param string $path
     * @param string $disk
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getResponse(string $path, string $disk = 'public')
    {
        $this->validateDisk($disk); // Validate the disk variable

        // Get the file as a response
        return Storage::disk($disk)->response($path);
    }

    /**
     * Get the URL of the file.
     *
     * @param string $path
     * @param string $disk
     * @return string the URL of the file
     */
    public function getUrl(string $path, string $disk = 'public'): string
    {
        $this->validateDisk($disk); // Validate the disk variable

        // Get the URL of the file
        return Storage::disk($disk)->url($path);
    }
}
