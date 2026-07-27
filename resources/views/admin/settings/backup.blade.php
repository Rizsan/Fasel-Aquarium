<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- BACKUP SECTION --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-download mr-2 text-green-600"></i> Download Backup
            </h2>
            <p class="text-sm text-gray-600 mt-1">Buat backup database Anda secara manual</p>
        </div>

        <div class="p-6">
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>✓ Aman:</strong> File backup akan disimpan dalam format SQL terenkripsi.
                </p>
            </div>

            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Informasi Backup</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <p><strong>Database:</strong> {{ config('database.connections.mysql.database') }}</p>
                    <p><strong>Host:</strong> {{ config('database.connections.mysql.host') }}</p>
                    <p><strong>Tanggal:</strong> <span id="currentDate"></span></p>
                </div>
            </div>

            <form action="{{ route('admin.backup.download') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-cloud-download-alt"></i> Download Backup Sekarang
                </button>
            </form>

            <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-600">
                <p><strong>Tips:</strong></p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Buat backup secara berkala (minimal 1x seminggu)</li>
                    <li>Simpan file backup di tempat yang aman</li>
                    <li>Verifikasi backup Anda sebelum membutuhkannya</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- RESTORE SECTION --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-upload mr-2 text-blue-600"></i> Restore Database
            </h2>
            <p class="text-sm text-gray-600 mt-1">Kembalikan database dari file backup</p>
        </div>

        <div class="p-6">
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">
                    <strong>⚠️ PERHATIAN:</strong> Restore akan menggantikan semua data database dengan data dari file backup. Proses ini tidak dapat dibatalkan!
                </p>
            </div>

            <form action="{{ route('admin.restore.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf

                <div class="mb-4">
                    <label for="database_file" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File Backup (.sql atau .zip)
                    </label>
                    <input
                        type="file"
                        id="database_file"
                        name="database_file"
                        accept=".sql,.zip"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">File maksimal 50MB</p>
                    @error('database_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center justify-center gap-2">
                    <i class="fas fa-cloud-upload-alt"></i> Upload & Persiapkan Restore
                </button>
            </form>

            {{-- Konfirmasi Restore (Jika ada file yang sudah di-upload) --}}
            @if(session('show_restore_confirm') || session('restore_file_name'))
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4">
                    <p class="text-sm text-yellow-800 mb-3">
                        <strong>File Siap Restore:</strong> {{ session('restore_file_name') }}
                    </p>
                    <form action="{{ route('admin.restore.confirm') }}" method="POST" onsubmit="return confirm('APAKAH ANDA YAKIN? Semua data akan diganti dengan data dari backup. Tindakan ini TIDAK DAPAT DIBATALKAN!')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                            <i class="fas fa-exclamation-triangle mr-2"></i> KONFIRMASI RESTORE
                        </button>
                    </form>
                </div>
            @endif

            <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-600">
                <p><strong>Langkah Restore:</strong></p>
                <ol class="list-decimal list-inside mt-2 space-y-1">
                    <li>Pilih file backup (.sql atau .zip)</li>
                    <li>Klik "Upload & Persiapkan Restore"</li>
                    <li>Konfirmasi dengan klik tombol restore (jika muncul)</li>
                    <li>Tunggu proses selesai</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update current date
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
});
</script>
@endpush
