<div
    x-data
    x-on:agenda-refresh-delayed.window="
        setTimeout(() => {
            $wire.$refresh()
        }, 3000)
    "
    class="p-6 md:p-8 bg-gray-900 min-h-screen text-white"
>

<div class="p-6 md:p-8 bg-gray-900 min-h-screen text-white">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Agenda Kegiatan</h1>

        {{-- ACTION RIGHT --}}
        <div class="flex gap-3">
            {{-- SEARCH --}}
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Cari agenda..."
                    class="w-64 pl-10 pr-4 py-2 rounded-lg
                           bg-gray-800 border border-gray-600
                           text-white placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-blue-500
                           focus:border-blue-500 transition"
                >

                <svg
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                </svg>
            </div>

            {{-- ADD --}}
            <button
                wire:click="openModal"
                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded">
                Tambah Agenda
            </button>
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if ($successMessage)
        <div
            class="mb-4 bg-green-700 p-3 rounded"
            x-data
            x-init="setTimeout(() => $wire.successMessage = null, 2000)"
        >
            {{ $successMessage }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-gray-800 rounded shadow">
        <table class="min-w-full text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Tanggal & Jam</th>
                    <th class="px-4 py-3">Tempat</th>
                    <th class="px-4 py-3">Disposisi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

<tbody>
@php
    $now = \Carbon\Carbon::now();
    $today = $now->toDateString();
    $tomorrow = $now->copy()->addDay()->toDateString();
@endphp

@forelse ($agendas as $item)
    @php
        $agendaTime = \Carbon\Carbon::parse($item->tanggal);

        if ($agendaTime->format('H:i:s') === '00:00:00' && $item->jam) {
            $agendaTime->setTimeFromTimeString($item->jam);
        }

        $isPast = $agendaTime->lt($now);
        $isToday = $agendaTime->toDateString() === $today;
        $isTomorrow = $agendaTime->toDateString() === $tomorrow;
    @endphp

    <tr class="border-t border-gray-700
        {{ $isPast ? 'bg-red-900/20' : ($isToday ? 'bg-yellow-900/20' : ($isTomorrow ? 'bg-green-900/20' : '')) }}"
        wire:key="agenda-{{ $item->id }}"
    >
        <td class="px-4 py-3">{{ $item->nama_kegiatan }}</td>

        <td class="px-4 py-3">
            <span class="
                {{ $isPast ? 'text-red-400 font-semibold' :
                   ($isToday ? 'text-green-300 font-semibold' :
                   ($isTomorrow ? 'text-yellow-300 font-semibold' : '')) }}
            ">
                {{ $agendaTime->format('d M Y') }}
            </span><br>

            <span class="text-sm
                {{ $isPast ? 'text-red-300' :
                   ($isToday ? 'text-green-200' :
                   ($isTomorrow ? 'text-yellow-200' : 'text-gray-300')) }}
            ">
                {{ $agendaTime->format('H:i') }}
            </span>
        </td>

        <td class="px-4 py-3">{{ $item->tempat }}</td>
        <td class="px-4 py-3">{{ $item->disposisi ?? '-' }}</td>

        <td class="px-4 py-3 text-center space-x-2">
            <button wire:click="edit({{ $item->id }})" class="px-3 py-1 bg-blue-600 rounded">
                Edit
            </button>
            <button wire:click="confirmDelete({{ $item->id }})" class="px-3 py-1 bg-red-600 rounded">
                Hapus
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-4 py-4 text-center text-gray-400">
            Data agenda tidak ditemukan
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

    {{-- MODAL ADD / EDIT --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
            <div class="bg-gray-800 w-full max-w-xl p-6 rounded-lg">

                <h2 class="text-xl font-bold mb-4">
                    {{ $editMode ? 'Edit Agenda' : 'Tambah Agenda' }}
                </h2>

                <div class="space-y-4">
                    <input type="text" wire:model="nama_kegiatan" placeholder="Nama Kegiatan"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600">

                    <input type="date" wire:model="tanggal"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600">

                    <input type="time" wire:model="jam"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600">

                    <input type="text" wire:model="tempat" placeholder="Tempat"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600">

                    <textarea wire:model="keterangan" rows="3" placeholder="Keterangan"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600"></textarea>

                    <input type="text" wire:model="disposisi" placeholder="Disposisi"
                        class="w-full p-2 rounded bg-gray-700 border border-gray-600">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-gray-600 rounded">
                        Batal
                    </button>

                    <button wire:click="{{ $editMode ? 'update' : 'save' }}"
                        class="px-4 py-2 bg-green-600 rounded">
                        {{ $editMode ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DELETE --}}
    @if ($agendaIdToDelete)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
            <div class="bg-gray-800 p-6 rounded w-full max-w-sm">
                <h3 class="text-xl font-bold mb-4">Konfirmasi Hapus</h3>
                <p class="mb-6">Yakin ingin menghapus agenda ini?</p>

                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('agendaIdToDelete', null)"
                        class="px-4 py-2 bg-gray-600 rounded">
                        Batal
                    </button>

                    <button
                        wire:click="deleteAgenda"
                        class="px-4 py-2 bg-red-600 rounded">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('admin-refresh', () => {
        setTimeout(() => {
            window.location.reload();
        }, 3000);
    });
});
</script>
