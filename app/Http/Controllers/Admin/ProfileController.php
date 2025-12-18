<style>
@media screen and (min-width: 1024px) {
    body {
        overflow: hidden;
    }

    .tv-scale {
        transform: scale(calc(100vw / 1600));
        transform-origin: top left;
    }
}

.staff-carousel {
    transition: opacity 0.5s ease-in-out;
}
</style>

<div class="tv-scale">
<div wire:poll.60s="loadData"
     class="min-h-screen w-screen bg-gray-100 fixed inset-0">

<div class="h-screen w-full p-4 flex flex-col gap-4">

{{-- JUDUL --}}
<div class="text-center">
    <h1 class="inline-block text-2xl font-bold bg-blue-900 text-yellow-300 rounded px-6 py-2">
        AGENDA PIMPINAN
    </h1>
</div>

{{-- PROFIL + VIDEO --}}
<div class="grid grid-cols-2 gap-4" style="height: 55vh;">

    {{-- PROFIL PIMPINAN & STAFF (KIRI) --}}
    <div class="flex flex-col border-2 border-black bg-white h-full">
        <div class="bg-blue-900 text-yellow-300 text-base font-semibold px-3 py-2 border-b-2 border-black">
            Profil Pimpinan & Staff
        </div>

        <div class="flex-1 p-4 flex flex-col overflow-hidden">
            {{-- PIMPINAN (2 foto besar di atas) --}}
            <div class="grid grid-cols-2 gap-4 pb-4">
                @foreach($leaderProfiles as $leader)
                <div class="flex flex-col items-center">
                    <div class="w-36 h-36 border-2 border-gray-300 bg-gray-100 mb-2">
                        <img class="w-full h-full object-cover"
                             src="{{ $leader['photo_path']
                                ? asset('storage/'.$leader['photo_path'])
                                : asset('images/default-user.png') }}"
                             alt="{{ $leader['full_name'] }}">
                    </div>
                    <p class="font-bold text-sm text-center">{{ $leader['full_name'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- GARIS PEMISAH --}}
            <div class="border-t-2 border-gray-300 mb-4"></div>

            {{-- STAFF (4 foto kecil di bawah dengan carousel) --}}
            <div class="flex-1">
                <div class="grid grid-cols-4 gap-3 staff-carousel" 
                     wire:key="staff-slide-{{ $staffPage ?? 0 }}">
                    @foreach($this->currentStaffSlide as $staff)
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 border-2 border-gray-300 bg-gray-100 mb-1">
                            <img class="w-full h-full object-cover"
                                 src="{{ $staff['photo_path']
                                    ? asset('storage/'.$staff['photo_path'])
                                    : asset('images/default-user.png') }}"
                                 alt="{{ $staff['full_name'] }}">
                        </div>
                        <p class="text-xs text-center leading-tight">{{ $staff['full_name'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- VIDEO KEGIATAN (KANAN) --}}
    <div class="flex flex-col border-2 border-black bg-white h-full">
        <div class="bg-blue-900 text-yellow-300 text-base font-semibold px-3 py-2 border-b-2 border-black">
            Video Kegiatan
        </div>

        <div class="flex-1 bg-gray-200 flex items-center justify-center overflow-hidden">
            @if($videoType === 'youtube')
                <iframe class="w-full h-full"
                        src="{{ $videoUrl }}?autoplay=1&mute=1&loop=1"
                        allow="autoplay"
                        frameborder="0"></iframe>
            @elseif($videoType === 'file')
                <video class="w-full h-full object-contain"
                       autoplay muted loop playsinline>
                    <source src="{{ $videoUrl }}" type="video/mp4">
                </video>
            @else
                <div class="text-gray-500 text-lg">Video Area</div>
            @endif
        </div>
    </div>

</div>

{{-- AGENDA KEGIATAN --}}
<div class="flex-1 border-2 border-black bg-white flex flex-col">
    <div class="bg-blue-900 text-yellow-300 text-base font-semibold px-3 py-2 border-b-2 border-black">
        Agenda Kegiatan
    </div>

    <div class="flex-1 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-blue-600 text-white sticky top-0">
                <tr>
                    <th class="border border-gray-400 p-2 w-32">Tanggal</th>
                    <th class="border border-gray-400 p-2">Kegiatan</th>
                    <th class="border border-gray-400 p-2 w-40">Disposisi</th>
                    <th class="border border-gray-400 p-2 w-40">Keterangan</th>
                    <th class="border border-gray-400 p-2 w-40">Tempat</th>
                </tr>
            </thead>

            <tbody wire:key="agenda-page-{{ $agendaPage }}">
                @php
                    $pageAgendas = collect($agendas)
                        ->slice($agendaPage * $agendaPerPage, $agendaPerPage);
                @endphp

                @forelse($pageAgendas as $agenda)
                <tr @class([
                    'bg-white',
                    'bg-green-300 font-semibold' =>
                        \Carbon\Carbon::parse($agenda['tanggal'])->isToday(),
                    'bg-yellow-300 font-semibold' =>
                        \Carbon\Carbon::parse($agenda['tanggal'])->isTomorrow(),
                ])>
                    <td class="border border-gray-400 p-2 text-center">
                        {{ \Carbon\Carbon::parse($agenda['tanggal'])->format('d M Y') }}
                    </td>
                    <td class="border border-gray-400 p-2">{{ $agenda['nama_kegiatan'] }}</td>
                    <td class="border border-gray-400 p-2">{{ $agenda['disposisi'] ?? '-' }}</td>
                    <td class="border border-gray-400 p-2">{{ $agenda['keterangan'] ?? '-' }}</td>
                    <td class="border border-gray-400 p-2">{{ $agenda['tempat'] ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border border-gray-400 p-4 text-center text-gray-500">
                        Tidak ada agenda
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- RUNNING TEXT --}}
<footer class="border-2 border-black bg-blue-900">
    <div class="text-white px-3 py-2 overflow-hidden">
        <marquee scrollamount="6" class="text-sm">{{ $runningText ?? 'Selamat datang di sistem agenda pimpinan' }}</marquee>
    </div>
</footer>

</div>
</div>
</div>

{{-- Js --}}
<script>
document.addEventListener('livewire:init', () => {
    // Carousel untuk staff setiap 5 detik
    setInterval(() => {
        Livewire.dispatch('next-staff');
    }, 5000);
});
</script>