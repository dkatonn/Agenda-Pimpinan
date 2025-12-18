<div class="p-6 bg-gray-900 min-h-screen text-white">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen User</h1>
        <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded text-white">+ Tambah User</button>
    </div>

    @if($successMessage)
        <div class="mb-4 p-4 bg-green-600 rounded">
            {{ $successMessage }}
        </div>
    @endif

    <table class="w-full text-left divide-y divide-gray-700">
        <thead class="bg-gray-800">
            <tr>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($items as $user)
            <tr class="hover:bg-gray-700/40">
                <td class="px-4 py-2">{{ $user->email }}</td>
                <td class="px-4 py-2">
                    <span class="px-3 py-1 rounded text-xs
                        {{ $user->role === 'Superadmin' ? 'bg-red-600' : 'bg-blue-600' }}">
                        {{ $user->role }}
                    </span>
                </td>

                <td class="px-4 py-2 space-x-2">
                    <button wire:click="edit({{ $user->id }})" class="bg-blue-600 px-2 py-1 rounded">Edit</button>
                    <button wire:click="confirmDelete({{ $user->id }})" class="bg-red-600 px-2 py-1 rounded">Hapus</button>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center py-4">Belum ada user.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-6 rounded w-96">
            <h2 class="text-lg font-bold mb-4">{{ $editingId ? 'Edit User' : 'Tambah User' }}</h2>
            <form wire:submit.prevent="save" class="space-y-4">
                <input type="email" wire:model="email" placeholder="Email" class="w-full p-2 bg-gray-700 rounded text-white"/>
                <div class="relative">
                    <input 
                        type="{{ $showPassword ? 'text' : 'password' }}"
                        wire:model="password"
                        placeholder="Password"
                        class="w-full p-2 pr-24 bg-gray-700 rounded text-white"
                    />

                    <button 
                        type="button"
                        wire:click="togglePassword"
                        class="absolute right-3 top-2 text-sm text-gray-300 hover:text-white"
                    >
                        {{ $showPassword ? 'Sembunyikan' : 'Tampilkan' }}
                    </button>
                </div>
                <select wire:model="role" class="w-full p-2 bg-gray-700 rounded text-white">
                    <option value="Admin">Admin</option>
                    <option value="Superadmin">Superadmin</option>
                </select>
                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-600 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 rounded text-white">{{ $editingId ? 'Update' : 'Simpan' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation --}}
    @if($userIdToDelete)
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-gray-800 p-6 rounded w-80 text-center">
            <p class="mb-4">Yakin ingin menghapus user ini?</p>
            <div class="flex justify-center gap-2">
                <button wire:click="$set('userIdToDelete', null)" class="px-4 py-2 bg-gray-600 rounded">Batal</button>
                <button wire:click="deleteUser" class="px-4 py-2 bg-red-600 rounded text-white">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {

    Livewire.on('refresh-page-delayed', () => {
        setTimeout(() => window.location.reload(), 1500);
    });

    Livewire.on('refresh-page', () => {
        window.location.reload();
    });

});
</script>