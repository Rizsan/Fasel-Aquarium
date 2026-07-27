<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DateTime;

class BackupService
{
    protected $backupPath = 'backups';
    protected $databaseName;
    protected $databaseUser;
    protected $databasePassword;
    protected $databaseHost;

    public function __construct()
    {
        $this->databaseName = config('database.connections.mysql.database');
        $this->databaseUser = config('database.connections.mysql.username');
        $this->databasePassword = config('database.connections.mysql.password');
        $this->databaseHost = config('database.connections.mysql.host');
    }

    /**
     * Create database backup
     */
    public function backup(): ?string
    {
        try {
            // Generate filename
            $filename = $this->generateFilename();
            $backupFile = storage_path('app/' . $this->backupPath . '/' . $filename);

            // Create backup directory
            $this->ensureBackupDirectory();

            // Generate SQL dump
            $this->generateSqlDump($backupFile);

            if (file_exists($backupFile)) {
                // Log backup
                \Log::info('Database backup created', [
                    'filename' => $filename,
                    'size' => filesize($backupFile),
                    'path' => $backupFile,
                ]);

                return $backupFile;
            }

            return null;

        } catch (\Exception $e) {
            \Log::error('Backup failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate SQL dump using mysqldump
     */
    protected function generateSqlDump(string $backupFile): void
    {
        $password = $this->databasePassword ? '-p' . $this->databasePassword : '';
        
        $command = sprintf(
            'mysqldump -h %s -u %s %s %s > %s 2>&1',
            escapeshellarg($this->databaseHost),
            escapeshellarg($this->databaseUser),
            $password,
            escapeshellarg($this->databaseName),
            escapeshellarg($backupFile)
        );

        // Execute mysqldump
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception('Backup gagal: ' . implode('\n', $output));
        }

        // Verify backup file was created
        if (!file_exists($backupFile) || filesize($backupFile) === 0) {
            throw new \Exception('File backup kosong atau tidak terbuat');
        }
    }

    /**
     * Generate backup filename
     */
    protected function generateFilename(): string
    {
        $date = new DateTime();
        return sprintf(
            '%s_backup_%s.sql',
            $this->databaseName,
            $date->format('Y-m-d_His')
        );
    }

    /**
     * Ensure backup directory exists
     */
    protected function ensureBackupDirectory(): void
    {
        $path = storage_path('app/' . $this->backupPath);
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Get list of backups
     */
    public function getBackups(): array
    {
        $path = storage_path('app/' . $this->backupPath);
        
        if (!is_dir($path)) {
            return [];
        }

        $files = scandir($path);
        $backups = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $filePath = $path . '/' . $file;
            if (is_file($filePath)) {
                $backups[] = [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'date' => filemtime($filePath),
                    'path' => $filePath,
                ];
            }
        }

        // Sort by date descending
        usort($backups, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return $backups;
    }

    /**
     * Delete old backups (keep only last N backups)
     */
    public function cleanOldBackups(int $keep = 10): void
    {
        $backups = $this->getBackups();
        
        if (count($backups) > $keep) {
            $toDelete = array_slice($backups, $keep);
            
            foreach ($toDelete as $backup) {
                if (file_exists($backup['path'])) {
                    unlink($backup['path']);
                    \Log::info('Old backup deleted: ' . $backup['name']);
                }
            }
        }
    }
}
