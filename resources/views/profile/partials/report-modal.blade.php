<!-- Report Upload Modal -->
<div id="reportModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div
            class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="text-xl font-bold text-gray-900">Upload Laporan Akhir</h3>
            <button onclick="closeModal('reportModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('profile.report.upload') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <!-- File Upload Area -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    File Laporan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="file" name="report_file" id="report_file" accept=".pdf,.doc,.docx" required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-emerald-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    Format: PDF, DOC, DOCX (Maks. 10MB)
                </p>
                @error('report_file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Preview -->
            <div id="filePreview" class="hidden mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="fileName" class="text-sm font-medium text-gray-900 truncate"></p>
                        <p id="fileSize" class="text-xs text-gray-500"></p>
                    </div>
                    <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Panduan Upload Laporan:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Pastikan laporan sudah final dan lengkap</li>
                            <li>File dalam format PDF lebih direkomendasikan</li>
                            <li>Periksa kembali isi laporan sebelum upload</li>
                            <li>Laporan yang sudah diupload tidak dapat dihapus dan diupload ulang</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('reportModal')"
                    class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-emerald-800 text-white rounded-lg hover:bg-emerald-900 font-medium transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // File input handler
    document.getElementById('report_file')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const filePreview = document.getElementById('filePreview');

            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.classList.remove('hidden');
        }
    });

    function clearFile() {
        document.getElementById('report_file').value = '';
        document.getElementById('filePreview').classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
</script>
