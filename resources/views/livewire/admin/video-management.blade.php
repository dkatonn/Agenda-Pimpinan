<div class="p-6 bg-gray-900 min-h-screen text-white">

    <h2 class="text-2xl font-bold mb-6">Manajemen Video</h2>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-600 rounded">
            {{ session('message') }}
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-gray-800 p-6 rounded-xl mb-10">

        <h3 class="text-lg font-semibold mb-4">
            {{ $editingId ? 'Edit Video' : 'Tambah Video' }}
        </h3>

        <div class="space-y-4">
            <input
                type="text"
                wire:model.defer="title"
                placeholder="Judul Video"
                class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-2">

            <input
                type="file"
                wire:model="video_file"
                accept="video/*"
                class="text-sm">

            @if ($video_file)
                <video controls class="w-full mt-3">
                    <source src="{{ $video_file->temporaryUrl() }}">
                </video>
            @elseif ($current_video_path)
                <video controls class="w-full mt-3">
                    <source src="{{ asset('storage/'.$current_video_path) }}">
                </video>
            @endif
        </div>

        <div class="mt-6 flex gap-3">
            <button
                wire:click="save"
                class="bg-green-600 px-6 py-2 rounded">
                {{ $editingId ? 'Update' : 'Simpan' }}
            </button>

            @if ($editingId)
                <button
                    wire:click="resetForm"
                    class="bg-gray-600 px-6 py-2 rounded">
                    Batal
                </button>
            @endif
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-gray-800 p-6 rounded-xl">

        <h3 class="text-lg font-semibold mb-4">Daftar Video</h3>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-700">
                    <th class="py-2">Judul</th>
                    <th class="py-2 text-center">Status</th>
                    <th class="py-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($videos as $video)
                    <tr class="border-b border-gray-700">
                        <td class="py-2">{{ $video->title }}</td>

                        <td class="py-2 text-center">
                            <button
                                wire:click="toggleActive({{ $video->id }})"
                                class="px-3 py-1 rounded text-xs
                                    {{ $video->is_active ? 'bg-green-600' : 'bg-gray-600' }}">
                                {{ $video->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>

                        <td class="py-2 text-center space-x-2">
                            <button
                                wire:click="edit({{ $video->id }})"
                                class="bg-blue-600 px-3 py-1 rounded text-xs">
                                Edit
                            </button>

                            <button
                                wire:click="$set('videoIdToDelete', {{ $video->id }})"
                                class="bg-red-600 px-3 py-1 rounded text-xs">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL DELETE --}}
    @if ($videoIdToDelete)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center">
            <div class="bg-gray-800 p-6 rounded">
                <p class="mb-4">Yakin hapus video ini?</p>

                <div class="flex gap-3 justify-end">
                    <button
                        wire:click="$set('videoIdToDelete', null)"
                        class="bg-gray-600 px-4 py-2 rounded">
                        Batal
                    </button>

                    <button
                        wire:click="deleteVideo"
                        class="bg-red-600 px-4 py-2 rounded">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
