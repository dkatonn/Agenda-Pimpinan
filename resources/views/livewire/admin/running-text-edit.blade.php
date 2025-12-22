<div class="p-4 sm:p-8 bg-gray-900 min-h-screen text-white">

    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl sm:text-3xl font-bold">
                Manajemen Running Text
            </h1>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session()->has('message'))
            <div class="bg-green-600 text-white px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- FORM --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow">

                    <form wire:submit.prevent="save" class="p-5 sm:p-6 space-y-5">

                        <h2 class="text-lg sm:text-xl font-bold">
                            {{ $editingId ? 'Edit Running Text' : 'Tambah Running Text' }}
                        </h2>

                        <div>
                            <label class="block text-sm mb-1">Isi Running Text</label>
                            <textarea
                                wire:model.defer="text"
                                rows="5"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm"
                                placeholder="Masukkan running text..."></textarea>

                            @error('text')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ACTION --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            @if ($editingId)
                                <button
                                    type="button"
                                    wire:click="resetForm"
                                    class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg text-sm">
                                    Batal
                                </button>
                            @endif

                            <button
                                type="submit"
                                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg text-sm">
                                {{ $editingId ? 'Simpan Perubahan' : 'Tambah Running Text' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow overflow-x-auto">

                    <div class="p-5 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold">
                            Daftar Running Text
                        </h3>
                    </div>

                    <table class="w-full min-w-[640px] text-sm">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 w-12">#</th>
                                <th class="px-6 py-3">Isi Text</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-700">
                            @forelse ($items as $i => $item)
                                <tr class="hover:bg-gray-700">
                                    <td class="px-6 py-4">{{ $i + 1 }}</td>

                                    <td class="px-6 py-4 break-words">
                                        {{ $item->text }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <button
                                            wire:click="toggleActive({{ $item->id }})"
                                            class="px-3 py-1 rounded-full text-xs
                                                {{ $item->is_active ? 'bg-green-600' : 'bg-gray-600' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col sm:flex-row justify-center gap-2">
                                            <button
                                                wire:click="edit({{ $item->id }})"
                                                class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs">
                                                Edit
                                            </button>

                                            <button
                                                wire:click="$set('textIdToDelete', {{ $item->id }})"
                                                class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-400 py-8">
                                        Belum ada running text
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DELETE --}}
    @if ($textIdToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 p-6 rounded-xl w-full max-w-sm">
                <h3 class="text-lg font-bold mb-4">Konfirmasi Hapus</h3>
                <p class="text-gray-400 mb-6 text-sm">
                    Yakin ingin menghapus running text ini?
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('textIdToDelete', null)"
                        class="bg-gray-600 px-4 py-2 rounded text-sm">
                        Batal
                    </button>
                    <button
                        wire:click="deleteText"
                        class="bg-red-600 px-4 py-2 rounded text-sm">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
