<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class RestoreService
{
    protected $databaseName;
    protected $databaseUser;
    protected $databasePassword;
    protected $databaseHost;
    protected $allowedMimes = ['application/octet-stream', 'application/sql', 'text/plain', 'application/zip'];

    public function __construct()
    {
        $this->databaseName = config('database.connections.mysql.database');
        $this->databaseUser = config('database.connections.mysql.username');
        $this->databasePassword = config('database.connections.mysql.password');
        $this->databaseHost = config('database.connections.mysql.host');
    }

    /**
     * Validate backup file
     */
    public function validateFile(UploadedFile $file): array
    {
        // Check mime type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['sql', 'zip'])) {
            return [
                'valid' => false,
                'message' => 'Tipe file tidak didukung. Gunakan .sql atau .zip'
            ];
        }

        // Check file size (max 50MB)
        if ($file->getSize() > 52428800) {
            return [
                'valid' => false,
                'message' => 'Ukuran file melebihi batas maksimal (50MB)'
            ];
        }

        // Check if file is readable
        $tmpPath = $file->getRealPath();
        if (!is_readable($tmpPath)) {
            return [
                'valid' => false,
                'message' => 'File tidak dapat dibaca'
            ];
        }

        return ['valid' => true];
    }

    /**
     * Restore database from backup file
     */
    public function restore(string $filePath): bool
    {
        try {
            // Verify file exists
            if (!file_exists($filePath)) {
                throw new \Exception('File backup tidak ditemukan');
            }

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($extension === 'zip') {
                return $this->restoreFromZip($filePath);
            } else {
                return $this->restoreFromSql($filePath);
            }

        } catch (\Exception $e) {
            \Log::error('Restore failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Restore from SQL file
     */
    protected function restoreFromSql(string $filePath): bool
    {
        try {
            // Read SQL file
            $sqlContent = file_get_contents($filePath);
            
            if ($sqlContent === false) {
                throw new \Exception('Tidak dapat membaca file SQL');
            }

            // Split queries
            $queries = array_filter(
                array_map('trim', explode(';', $sqlContent)),
                fn($q) => !empty($q) && !preg_match('/^--/', trim($q))
            );

            // Execute queries
            foreach ($queries as $query) {
                if (!empty(trim($query))) {
                    DB::statement($query);
                }
            }

            \Log::info('Database restored from SQL file', ['file' => $filePath]);
            return true;

        } catch (\Exception $e) {
            \Log::error('Restore from SQL failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Restore from ZIP file
     */
    protected function restoreFromZip(string $filePath): bool
    {
        $tempDir = storage_path('app/temp_restore_' . time());
        
        try {
            // Create temp directory
            if (!mkdir($tempDir, 0755, true) && !is_dir($tempDir)) {
                throw new \Exception('Tidak dapat membuat direktori sementara');
            }

            // Extract ZIP
            $zip = new ZipArchive();
            if ($zip->open($filePath) !== true) {
                throw new \Exception('Tidak dapat membuka file ZIP');
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // Find SQL file in extracted contents
            $files = scandir($tempDir);
            $sqlFile = null;

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $sqlFile = $tempDir . '/' . $file;
                    break;
                }
            }

            if (!$sqlFile || !file_exists($sqlFile)) {
                throw new \Exception('Tidak ditemukan file SQL dalam ZIP');
            }

            // Restore from SQL file
            $result = $this->restoreFromSql($sqlFile);

            \Log::info('Database restored from ZIP file', ['file' => $filePath]);
            return $result;

        } catch (\Exception $e) {
            \Log::error('Restore from ZIP failed: ' . $e->getMessage());
            throw $e;
        } finally {
            // Clean up temp directory
            $this->deleteDirectory($tempDir);
        }
    }

    /**
     * Delete directory recursively
     */
    protected function deleteDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $files = scandir($path);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $path . '/' . $file;
            
            if (is_dir($filePath)) {
                $this->deleteDirectory($filePath);
            } else {
                @unlink($filePath);
            }
        }

        @rmdir($path);
    }
}
