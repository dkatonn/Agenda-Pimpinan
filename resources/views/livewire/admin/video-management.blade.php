<div class="p-6 md:p-8 bg-gray-900 min-h-screen text-white">

    {{-- TITLE --}}
    <h2 class="text-2xl font-bold mb-6">Manajemen Video</h2>

    {{-- ALERT --}}
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-600 rounded-lg text-white">
            {{ session('message') }}
        </div>
    @endif

    {{-- ================= FORM ================= --}}
    <div class="bg-gray-800 p-6 rounded-xl shadow mb-10 border border-gray-700">

        <h3 class="text-lg font-semibold mb-6">
            {{ $editingId ? 'Edit Video' : 'Tambah Video Baru' }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- JUDUL --}}
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-300 mb-1">
                    Judul Video
                </label>
                <input
                    type="text"
                    wire:model.defer="title"
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-white focus:ring focus:ring-blue-500">
                @error('title')
                    <span class="text-red-400 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- UPLOAD --}}
            <div>
                <label class="block text-sm text-gray-300 mb-1">
                    Upload Video
                </label>

                <div class="flex items-center gap-3">
                    {{-- Hidden input --}}
                    <input
                        type="file"
                        wire:model="video_file"
                        accept="video/*"
                        id="videoUpload"
                        class="hidden">

                    {{-- Button --}}
                    <label
                        for="videoUpload"
                        wire:loading.attr="disabled"
                        wire:target="video_file"
                        class="cursor-pointer bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg text-sm
                               disabled:opacity-50 disabled:cursor-not-allowed">
                        Pilih Video
                    </label>

                    {{-- Filename --}}
                    @if ($video_file)
                        <span class="text-sm text-green-400 truncate max-w-xs">
                            {{ $video_file->getClientOriginalName() }}
                        </span>
                    @elseif ($current_video_path)
                        <span class="text-sm text-blue-400">
                            Video sudah ada
                        </span>
                    @else
                        <span class="text-sm text-gray-400 italic">
                            Belum ada file
                        </span>
                    @endif
                </div>

                @error('video_file')
                    <span class="text-red-400 text-sm">{{ $message }}</span>
                @enderror

                <div
                    wire:loading
                    wire:target="video_file"
                    class="text-xs text-yellow-400 mt-2">
                    Mengupload video...
                </div>
            </div>

            {{-- PREVIEW --}}
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-300 mb-2">
                    Preview Video
                </label>

                <div class="bg-black rounded-xl overflow-hidden border border-gray-700">

                    @if ($video_file)
                        <video controls class="w-full max-h-[360px]">
                            <source src="{{ $video_file->temporaryUrl() }}">
                        </video>

                    @elseif ($current_video_path)
                        <video controls class="w-full max-h-[360px]">
                            <source src="{{ asset('storage/'.$current_video_path) }}">
                        </video>

                    @else
                        <div class="text-center text-gray-400 py-16 text-sm">
                            Belum ada video untuk ditampilkan
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ACTION --}}
        <div class="mt-8 flex gap-3">
            <button
                wire:click="save"
                class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg font-semibold">
                {{ $editingId ? 'Update' : 'Simpan' }}
            </button>

            @if ($editingId)
                <button
                    wire:click="resetForm"
                    class="bg-gray-600 hover:bg-gray-700 px-6 py-2 rounded-lg">
                    Batal
                </button>
            @endif
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-gray-800 p-6 rounded-xl shadow border border-gray-700">

        <h3 class="text-lg font-semibold mb-4">
            Daftar Video
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left">Judul</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-700">
                    @forelse ($videos as $video)
                        <tr class="hover:bg-gray-700/40">

                            <td class="px-4 py-3 font-semibold">
                                {{ $video->title }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($video->is_active)
                                    <span class="bg-green-600 text-xs px-3 py-1 rounded-full">
                                        Aktif
                                    </span>
                                @else
                                    <button
                                        wire:click="setActive({{ $video->id }})"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-xs px-3 py-1 rounded">
                                        Aktifkan
                                    </button>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center space-x-2">
                                <button
                                    wire:click="edit({{ $video->id }})"
                                    class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs">
                                    Edit
                                </button>

                                <button
                                    wire:click="$set('videoIdToDelete', {{ $video->id }})"
                                    class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-400 py-8">
                                Belum ada video.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= MODAL DELETE ================= --}}
    @if ($videoIdToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 w-full max-w-sm">
                <h3 class="text-lg font-bold mb-4">
                    Konfirmasi Hapus
                </h3>

                <p class="text-gray-400 mb-6">
                    Yakin ingin menghapus video ini? Tindakan tidak bisa dibatalkan.
                </p>

                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('videoIdToDelete', null)"
                        class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700">
                        Batal
                    </button>

                    <button
                        wire:click="deleteVideo"
                        class="px-4 py-2 bg-red-600 rounded-lg hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('refresh-page', () => {
            setTimeout(() => window.location.reload(), 1500);
        });
    });
</script>