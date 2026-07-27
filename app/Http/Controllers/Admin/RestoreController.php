<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreDatabaseRequest;
use App\Services\RestoreService;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    protected $restoreService;

    public function __construct(RestoreService $restoreService)
    {
        $this->restoreService = $restoreService;
    }

    /**
     * Upload file untuk restore
     */
    public function upload(RestoreDatabaseRequest $request)
    {
        try {
            $file = $request->file('database_file');
            
            // Validasi file
            $validation = $this->restoreService->validateFile($file);
            if (!$validation['valid']) {
                return back()->with('error', $validation['message']);
            }

            // Simpan file sementara
            $tempPath = $file->store('temp_restore', 'local');
            
            // Store di session untuk konfirmasi
            session()->put('restore_file_path', storage_path('app/' . $tempPath));
            session()->put('restore_file_name', $file->getClientOriginalName());

            return redirect()->route('admin.settings.index', ['tab' => 'backup'])
                ->with('warning', 'File siap untuk restore. Klik tombol KONFIRMASI untuk melanjutkan.')
                ->with('show_restore_confirm', true);

        } catch (\Exception $e) {
            \Log::error('Restore upload failed: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi restore database
     */
    public function confirm(Request $request)
    {
        try {
            // Validasi session
            $filePath = session()->get('restore_file_path');
            if (!$filePath || !file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan. Silakan upload ulang.');
            }

            // Log activity
            \Log::warning('Database restore initiated by user: ' . auth()->id());

            // Perform restore
            $this->restoreService->restore($filePath);

            // Hapus file temporary
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Hapus session
            session()->forget(['restore_file_path', 'restore_file_name']);

            return back()->with('success', 'Database berhasil di-restore. Sistem akan restart...');

        } catch (\Exception $e) {
            \Log::error('Restore confirm failed: ' . $e->getMessage());
            return back()->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }
}
