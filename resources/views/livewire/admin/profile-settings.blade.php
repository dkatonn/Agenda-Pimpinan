<div class="p-4 sm:p-8 bg-gray-900 min-h-screen text-white">

    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <h1 class="text-xl sm:text-3xl font-bold">
                Kelola Profil Pimpinan & Staff
            </h1>

            <button
                wire:click="resetForm"
                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm sm:text-base w-full sm:w-auto">
                + Tambah Profil
            </button>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session()->has('message'))
            <div class="bg-green-600 text-white px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ================= FORM ================= --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow">

                    <form wire:submit.prevent="save" class="p-5 sm:p-6 space-y-5">

                        <h2 class="text-lg sm:text-xl font-bold">
                            {{ $editingId ? 'Edit Profil' : 'Tambah Profil Baru' }}
                        </h2>

                        {{-- CATEGORY --}}
                        <div>
                            <label class="block text-sm mb-1">Kategori</label>
                            <select
                                wire:model="category"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm">

                                <option value="Staff">Staff</option>
                                <option value="Pimpinan"
                                    @if($pimpinanPenuh && !$editingId) disabled @endif>
                                    Pimpinan
                                </option>
                            </select>

                            @if($pimpinanPenuh && !$editingId)
                                <p class="text-xs text-yellow-400 mt-1">
                                    Pimpinan tidak bisa lebih dari 2
                                </p>
                            @endif

                            @error('category')
                                <p class="text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NAMA --}}
                        <div>
                            <label class="block text-sm mb-1">Nama Lengkap</label>
                            <input
                                type="text"
                                wire:model.defer="full_name"
                                class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm">
                            @error('full_name')
                                <p class="text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- FOTO --}}
                        <div>
                            <label class="block text-sm mb-1">Foto Profil</label>

                            <div class="flex flex-wrap items-center gap-3">
                                <input
                                    type="file"
                                    wire:model="photo"
                                    accept="image/*"
                                    id="photoUpload"
                                    class="hidden">

                                <label
                                    for="photoUpload"
                                    wire:loading.attr="disabled"
                                    wire:target="photo"
                                    class="cursor-pointer bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg text-xs sm:text-sm">
                                    Pilih Foto
                                </label>

                                @if ($photo)
                                    <span class="text-xs text-green-400 truncate max-w-xs">
                                        {{ $photo->getClientOriginalName() }}
                                    </span>
                                @elseif ($current_photo_path)
                                    <span class="text-xs text-blue-400">
                                        Foto sudah ada
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">
                                        Belum ada file
                                    </span>
                                @endif
                            </div>

                            @error('photo')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror

                            <div wire:loading wire:target="photo"
                                class="text-xs text-yellow-400 mt-2">
                                Mengupload foto...
                            </div>
                        </div>

                        {{-- PREVIEW --}}
                        <div>
                            <label class="block text-sm mb-2">Preview</label>
                            <div class="flex justify-center">
                                <div
                                    class="w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden border border-gray-600 bg-gray-700 flex items-center justify-center">

                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}"
                                            class="w-full h-full object-cover">
                                    @elseif ($current_photo_path)
                                        <img src="{{ asset('storage/'.$current_photo_path) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs text-gray-400">
                                            Tidak ada foto
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ACTION --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            <button
                                type="button"
                                wire:click="resetForm"
                                class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg text-sm">
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm">
                                {{ $editingId ? 'Simpan Perubahan' : 'Tambah Profil' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- ================= TABLE ================= --}}
            <div class="lg:col-span-2">
                <div class="bg-gray-800 border border-gray-700 rounded-xl shadow overflow-x-auto">

                    <div class="p-5 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-semibold">Daftar Profil</h3>
                    </div>

                    <table class="w-full min-w-[640px] text-sm">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3">Foto</th>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-700">
                            @forelse ($profiles as $profile)
                                <tr class="hover:bg-gray-700">
                                    <td class="px-6 py-4 text-center">
                                        <img
                                            src="{{ $profile->photo_path
                                                ? asset('storage/'.$profile->photo_path)
                                                : 'https://via.placeholder.com/56' }}"
                                            class="h-12 w-12 sm:h-14 sm:w-14 rounded-full object-cover mx-auto">
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $profile->full_name }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs
                                            {{ $profile->category === 'Pimpinan'
                                                ? 'bg-indigo-600'
                                                : 'bg-green-600' }}">
                                            {{ $profile->category }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col sm:flex-row justify-center gap-2">
                                            <button
                                                wire:click="edit({{ $profile->id }})"
                                                class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs">
                                                Edit
                                            </button>
                                            <button
                                                wire:click="confirmDelete({{ $profile->id }})"
                                                class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-400 py-8">
                                        Belum ada data profil
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
    @if ($profileIdToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 p-6 rounded-xl w-full max-w-sm">
                <h3 class="text-lg font-bold mb-4">Konfirmasi Hapus</h3>
                <p class="text-gray-400 mb-6 text-sm">
                    Yakin ingin menghapus profil ini?
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('profileIdToDelete', null)"
                        class="bg-gray-600 px-4 py-2 rounded text-sm">
                        Batal
                    </button>
                    <button
                        wire:click="deleteProfile"
                        class="bg-red-600 px-4 py-2 rounded text-sm">
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
