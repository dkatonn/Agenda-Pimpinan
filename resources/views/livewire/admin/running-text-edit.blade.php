<div class="p-8 bg-gray-900 min-h-screen text-white">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Running Text</h1>

        <button
            wire:click="openModal"
            class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700">
            + Tambah Running Text
        </button>
    </div>

    {{-- NOTIF --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-600 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-zinc-800/40 p-6 rounded-lg border border-zinc-700">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-zinc-700">
                    <th class="px-4 py-3 w-12">#</th>
                    <th class="px-4 py-3">Isi Text</th>
                    <th class="px-4 py-3 text-center w-32">Status</th>
                    <th class="px-4 py-3 text-right min-w-[300px]">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items as $i => $item)
                    <tr class="border-b border-zinc-700 hover:bg-zinc-800/60">
                        <td class="px-4 py-3">{{ $i+1 }}</td>
                        <td class="px-4 py-3">{{ $item->text }}</td>

                        <td class="px-4 py-3 text-center">
                            @if($item->is_active)
                                <span class="px-3 py-1 text-xs bg-emerald-600 rounded-full">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs bg-zinc-600 rounded-full">
                                    Nonaktif
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2 flex-wrap">

                                <button
                                    wire:click="toggleActive({{ $item->id }})"
                                    class="px-3 py-1 rounded
                                    {{ $item->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                    {{ $item->is_active ? 'Matikan' : 'Aktifkan' }}
                                </button>

                                <button
                                    wire:click="edit({{ $item->id }})"
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded">
                                    Edit
                                </button>

                                <button
                                    wire:click="$set('textIdToDelete', {{ $item->id }})"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 rounded">
                                    Hapus
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-zinc-400">
                            Belum ada running text
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL FORM --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-zinc-900 w-full max-w-lg p-6 rounded-lg">

            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">
                    {{ $editingId ? 'Edit Running Text' : 'Tambah Running Text' }}
                </h2>
                <button wire:click="closeModal">✕</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <textarea
                    wire:model="text"
                    rows="4"
                    class="w-full bg-zinc-800 border border-zinc-600 rounded p-3"
                    placeholder="Masukkan running text..."></textarea>

                @error('text')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 bg-zinc-700 rounded">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 rounded">
                        {{ $editingId ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
    @endif

    {{-- MODAL DELETE --}}
    @if($textIdToDelete)
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-zinc-900 p-6 rounded max-w-sm w-full">

            <h3 class="text-lg font-bold mb-4">Hapus Running Text</h3>
            <p class="text-zinc-400 mb-6">Yakin ingin menghapus data ini?</p>

            <div class="flex justify-end gap-3">
                <button
                    wire:click="$set('textIdToDelete', null)"
                    class="px-4 py-2 bg-zinc-700 rounded">
                    Batal
                </button>

                <button
                    wire:click="deleteText"
                    class="px-4 py-2 bg-red-600 rounded">
                    Hapus
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- AUTO REFRESH --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('refresh-page', () => {
                setTimeout(() => window.location.reload(), 1500);
            });
        });
    </script>
</div>
