@if ($errors->any())
<div x-show="showErrorAlert" x-transition class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-sm shadow-md relative" role="alert">
    <button type="button" @click="showErrorAlert = false" class="absolute top-3 right-3 text-red-500 hover:text-red-800 p-1" aria-label="Tutup notifikasi">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <div class="flex items-start pr-8">
        <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
            <p class="font-bold mb-2">Terjadi kesalahan pada formulir. Periksa kolom yang ditandai:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
