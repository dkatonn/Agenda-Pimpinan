<style>
@media screen and (min-width: 1024px) {
    body { overflow: hidden; }
    .tv-scale {
        transform: scale(calc(100vw / 1600));
        transform-origin: top left;
    }
}

/* Animasi carousel staff */
@keyframes fade {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}
.animate-fade {
    animation: fade 0.6s ease-in-out;
}
</style>

<div class="tv-scale">
<div wire:poll.60s="loadData" class="min-h-screen w-screen bg-gray-100 fixed inset-0">

<div class="h-full w-full p-4 flex flex-col gap-4">

{{--  JUDUL  --}}
<div class="text-center">
    <h1 class="inline-block text-2xl font-bold bg-white border-2 border-black rounded px-6 py-2">
        AGENDA PIMPINAN
    </h1>
</div>

{{--  PROFIL + VIDEO  --}}
<div class="grid grid-cols-2 gap-4 flex-1 items-stretch">

{{--  PROFIL  --}}
<div class="flex flex-col border-2 border-black bg-white h-full">
    <div class="bg-gray-300 border-b-2 border-black font-semibold px-3 py-2">
        Profil Pimpinan & Staff
    </div>

    <div class="flex-1 p-4 flex flex-col">

        {{-- PIMPINAN --}}
        <div class="grid grid-cols-2 gap-4">
            @foreach($leaderProfiles as $leader)
            <div class="flex flex-col items-center">
                <div class="w-36 h-36 border-2 border-black bg-gray-200 mb-2">
                    <img class="w-full h-full object-cover"
                        src="{{ $leader['photo_path'] ? asset('storage/'.$leader['photo_path']) : asset('images/default-user.png') }}">
                </div>
                <p class="font-bold text-sm text-center">{{ $leader['full_name'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="my-3 border-t-4 border-black"></div>

        {{-- STAFF CAROUSEL --}}
        <div class="overflow-hidden h-[120px]">
            <div
                wire:key="staff-slide-{{ $staffSlide }}"
                class="grid grid-cols-4 gap-3 animate-fade">
                @foreach($this->currentStaffSlide as $staff)
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 border-2 border-black bg-gray-200 mb-1">
                        <img class="w-full h-full object-cover"
                            src="{{ $staff['photo_path'] ? asset('storage/'.$staff['photo_path']) : asset('images/default-user.png') }}">
                    </div>
                    <p class="text-xs text-center">{{ $staff['full_name'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{--  VIDEO  --}}
<div class="flex flex-col border-2 border-black bg-white h-full">
    <div class="bg-gray-300 border-b-2 border-black font-semibold px-3 py-2">
        Informasi Video
    </div>

    <div class="flex-1 p-4">
        <div class="w-full h-full border-2 border-black bg-black">
            @if($videoType === 'youtube')
                <iframe
                    class="w-full h-full"
                    src="{{ $videoUrl }}?autoplay=1&mute=1&loop=1"
                    allow="autoplay"
                    allowfullscreen>
                </iframe>
            @elseif($videoType === 'file')
                <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                    <source src="{{ $videoUrl }}" type="video/mp4">
                </video>
            @endif
        </div>
    </div>
</div>

</div>

{{--  AGENDA  --}}
<div class="border-2 border-black bg-white">
    <div class="bg-gray-300 border-b-2 border-black font-semibold px-3 py-2">
        Agenda Kegiatan
    </div>

    <div class="p-2 bg-gray-200 min-h-[240px]">
        <table class="w-full border-collapse table-fixed text-sm">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="border border-black p-2 w-[12%]">Tanggal</th>
                    <th class="border border-black p-2 w-[30%]">Kegiatan</th>
                    <th class="border border-black p-2 w-[14%]">Disposisi</th>
                    <th class="border border-black p-2 w-[24%]">Keterangan</th>
                    <th class="border border-black p-2 w-[20%]">Tempat</th>
                </tr>
            </thead>
            <tbody wire:key="agenda-page-{{ $agendaPage }}">
                @foreach($pageAgendas as $agenda)
                <tr class="h-12 align-middle
                    @if(\Carbon\Carbon::parse($agenda['tanggal'])->isToday())
                        bg-green-300 font-semibold
                    @elseif(\Carbon\Carbon::parse($agenda['tanggal'])->isTomorrow())
                        bg-yellow-300 font-semibold
                    @else bg-white @endif">

                    <td class="border border-black p-2 text-center align-middle">
                        {{ \Carbon\Carbon::parse($agenda['tanggal'])->format('d M Y') }}
                    </td>
                    <td class="border border-black p-2 align-middle break-words">
                        {{ $agenda['nama_kegiatan'] }}
                    </td>
                    <td class="border border-black p-2 text-center align-middle">
                        {{ $agenda['disposisi'] ?? '-' }}
                    </td>
                    <td class="border border-black p-2 align-middle break-words">
                        {{ $agenda['keterangan'] ?? '-' }}
                    </td>
                    <td class="border border-black p-2 text-center align-middle">
                        {{ $agenda['tempat'] ?? '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= RUNNING TEXT ================= --}}
<footer class="border-2 border-black bg-white">
    <div class="bg-gray-200 px-3 py-2 overflow-hidden">
        <marquee scrollamount="6">{{ $runningText }}</marquee>
    </div>
</footer>

</div>
</div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    setInterval(() => {
        Livewire.dispatch('next-staff');
    }, 5000);
});
</script>
