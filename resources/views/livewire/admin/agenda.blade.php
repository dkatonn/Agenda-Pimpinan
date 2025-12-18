<div class="p-6 md:p-8 bg-gray-900 min-h-screen text-white">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Agenda</h1>

        <button wire:click="openModal"
            class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
            Tambah Agenda
        </button>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session()->has('success'))
        <div class="mb-4 bg-green-700 text-white p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-gray-800 rounded shadow">
        <table class="min-w-full text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3">Nama Kegiatan</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Tempat</th>
                    <th class="px-4 py-3">Disposisi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agendas as $item)
                    <tr class="border-t border-gray-700">
                        <td class="px-4 py-3">{{ $item->nama_kegiatan }}</td>
                        <td class="px-4 py-3">{{ $item->tanggal }}</td>
                        <td class="px-4 py-3">{{ $item->tempat }}</td>
                        <td class="px-4 py-3">{{ $item->disposisi ?? '-' }}</td>

                        <td class="px-4 py-3 text-center space-x-2">
                            <button wire:click="edit({{ $item->id }})"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded">
                                Edit
                            </button>

                            <button wire:click="confirmDelete({{ $item->id }})"
                                class="px-3 py-1 bg-red-600 hover:bg-red-700 rounded">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-400">
                            Tidak ada data agenda
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $agendas->links() }}
    </div>

    {{-- MODAL ADD/EDIT --}}
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50">
            <div class="bg-gray-800 w-full max-w-xl p-6 rounded-lg shadow-lg">

                <h2 class="text-xl font-bold mb-4">
                    {{ $editMode ? 'Edit Agenda' : 'Tambah Agenda' }}
                </h2>

                <div class="space-y-4">

                    <div>
                        <label class="block mb-1">Nama Kegiatan</label>
                        <input type="text" wire:model="nama_kegiatan"
                            class="w-full p-2 rounded bg-gray-700 border border-gray-600">
                        @error('nama_kegiatan')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Tanggal</label>
                        <input type="date" wire:model="tanggal"
                            class="w-full p-2 rounded bg-gray-700 border border-gray-600">
                        @error('tanggal')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Tempat</label>
                        <input type="text" wire:model="tempat"
                            class="w-full p-2 rounded bg-gray-700 border border-gray-600">
                        @error('tempat')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-1">Keterangan</label>
                        <textarea wire:model="keterangan" rows="3"
                            class="w-full p-2 rounded bg-gray-700 border border-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="block mb-1">Disposisi</label>
                        <input type="text" wire:model="disposisi"
                            class="w-full p-2 rounded bg-gray-700 border border-gray-600">
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded">
                        Batal
                    </button>

                    <button wire:click="{{ $editMode ? 'update' : 'save' }}"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded">
                        {{ $editMode ? 'Update' : 'Simpan' }}
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- MODAL DELETE --}}
    @if($agendaIdToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded-lg w-full max-w-sm shadow-xl">
                <h3 class="text-xl font-bold mb-4 text-white">Konfirmasi Hapus</h3>
                <p class="text-gray-300 mb-6">Apakah Anda yakin ingin menghapus agenda ini?</p>

                <div class="flex justify-end gap-3">
                    <button 
                        wire:click="$set('agendaIdToDelete', null)" 
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded">
                        Batal
                    </button>

                    <button 
                        wire:click="deleteAgenda" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- refresh --}}
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('refresh-page', () => {
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        });
    });
</script>