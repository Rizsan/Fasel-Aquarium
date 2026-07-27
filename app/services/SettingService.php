<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingService
{
    protected $disk = 'public';

    /**
     * Upload file dan return path
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads', ?string $oldPath = null): string
    {
        try {
            // Delete old file if exists
            if ($oldPath) {
                $this->deleteFile($oldPath);
            }

            // Generate unique filename
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Store file
            $path = $file->storeAs($directory, $filename, $this->disk);

            return $path;

        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('File delete failed: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Get file URL
     */
    public function getFileUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Check if file exists
     */
    public function fileExists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }
}
