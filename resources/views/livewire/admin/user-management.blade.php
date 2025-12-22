<div class="p-4 sm:p-8 bg-gray-900 min-h-screen text-white">

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- FORM --}}
        <div class="lg:col-span-1">
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">

                <h2 class="text-lg sm:text-xl font-bold mb-4">
                    {{ $editingId ? 'Edit User' : 'Tambah User' }}
                </h2>

                {{-- 🔥 DUMMY INPUT (ANTI AUTOFILL CHROME) --}}
                <input type="text" name="fakeusernameremembered" class="hidden">
                <input type="password" name="fakepasswordremembered" class="hidden">

                <form
                    wire:submit.prevent="save"
                    class="space-y-4"
                    autocomplete="off"
                >

                    {{-- EMAIL --}}
                    <div>
                        <input
                            type="email"
                            wire:model.defer="email"
                            placeholder="Masukkan Email"
                            autocomplete="off"
                            class="w-full p-2 bg-gray-900 border border-gray-700 rounded text-sm">

                        @error('email')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="relative">
                        <input
                            type="{{ $showPassword ? 'text' : 'password' }}"
                            wire:model.defer="password"
                            placeholder="{{ $editingId ? 'Kosongkan jika tidak diubah' : 'Masukkan Passowrd' }}"
                            autocomplete="new-password"
                            class="w-full p-2 pr-24 bg-gray-900 border border-gray-700 rounded text-sm">

                        <button
                            type="button"
                            wire:click="togglePassword"
                            class="absolute right-3 top-2 text-xs text-gray-300">
                            {{ $showPassword ? 'Sembunyikan' : 'Tampilkan' }}
                        </button>
                    </div>

                    @error('password')
                        <p class="text-xs text-red-400">{{ $message }}</p>
                    @enderror

                    {{-- ROLE --}}
                    <div>
                        <select
                            wire:model.defer="role"
                            autocomplete="off"
                            class="w-full p-2 bg-gray-900 border border-gray-700 rounded text-sm">
                            <option value="Admin">Admin</option>
                            <option value="Superadmin">Superadmin</option>
                        </select>

                        @error('role')
                            <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <div class="flex justify-end gap-3 pt-2">
                        @if($editingId)
                            <button
                                type="button"
                                wire:click="resetForm"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded text-sm">
                                Batal
                            </button>
                        @endif

                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded text-sm">
                            {{ $editingId ? 'Update' : 'Simpan' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="lg:col-span-2">
            <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-x-auto">

                <div class="p-5">
                    <h3 class="text-lg font-semibold">Daftar User</h3>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3 text-center">Role</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-700">
                        @forelse($items as $user)
                            <tr class="hover:bg-gray-700">
                                <td class="px-6 py-4">{{ $user->email }}</td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs
                                        {{ $user->role === 'Superadmin'
                                            ? 'bg-red-600'
                                            : 'bg-blue-600' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button
                                            wire:click="edit({{ $user->id }})"
                                            class="px-3 py-1 text-xs bg-blue-600 hover:bg-blue-700 rounded">
                                            Edit
                                        </button>

                                        <button
                                            wire:click="confirmDelete({{ $user->id }})"
                                            class="px-3 py-1 text-xs bg-red-600 hover:bg-red-700 rounded">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-400 py-8">
                                    Belum ada user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- MODAL DELETE --}}
    @if($userIdToDelete)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded w-full max-w-sm">
                <h3 class="text-lg font-bold mb-4">Konfirmasi Hapus</h3>
                <p class="text-gray-400 mb-6 text-sm">
                    Yakin ingin menghapus user ini?
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('userIdToDelete', null)"
                        class="px-4 py-2 bg-gray-600 rounded text-sm">
                        Batal
                    </button>
                    <button
                        wire:click="deleteUser"
                        class="px-4 py-2 bg-red-600 rounded text-sm">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
