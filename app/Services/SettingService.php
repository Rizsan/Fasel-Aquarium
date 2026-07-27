<?php

namespace App\Services;

use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Http\UploadedFile;

class SettingService
{
    protected SupabaseStorageService $supabase;

    public function __construct(SupabaseStorageService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Upload file ke Supabase
     */
    public function uploadFile(
        UploadedFile $file,
        string $directory = 'uploads',
        ?string $oldPath = null
    ): string
    {
        try {

            if ($oldPath) {
                $this->deleteFile($oldPath);
            }

            return $this->supabase->upload(
                $file,
                env('SUPABASE_WEBSITE_BUCKET'),
                $directory
            );

        } catch (\Exception $e) {

            \Log::error('Upload gagal: '.$e->getMessage());

            throw $e;
        }
    }

    /**
     * Hapus file dari Supabase
     */
    public function deleteFile(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        return $this->supabase->delete(
            env('SUPABASE_WEBSITE_BUCKET'),
            $path
        );
    }

    /**
     * Ambil URL file
     */
    public function getFileUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return $this->supabase->websiteAssetUrl($path);
    }
}