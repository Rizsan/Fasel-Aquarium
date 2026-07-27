<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Download backup database
     */
    public function download(Request $request)
    {
        try {
            // Log activity
            \Log::info('Database backup initiated by user: ' . auth()->id());

            // Generate backup
            $file = $this->backupService->backup();

            if (!$file || !file_exists($file)) {
                return back()->with('error', 'Gagal membuat backup database.');
            }

            // Download file
            return response()->download($file, basename($file), [
                'Content-Type' => 'application/octet-stream',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('Backup failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat backup: ' . $e->getMessage());
        }
    }
}
