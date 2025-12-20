<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="300"> {{-- refresh tiap 5 menit --}}
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Agenda Pimpinan - TV</title>
@vite(['resources/css/app.css'])

<style>
html, body {
    height: 100%;
    margin: 0;
    overflow: hidden;
    background: #f3f4f6;
    font-family: system-ui, sans-serif;
}

.slide { display: none; }
.slide.active { display: grid; }

.agenda-slide { display: none; }
.agenda-slide.active { display: table-row-group; }

@keyframes fade {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}
.fade { animation: fade .5s ease-in-out; }
</style>
</head>

<body>

<div class="h-screen w-screen p-3 flex flex-col gap-3">

{{-- HEADER --}}
<h1 class="text-center text-2xl font-bold">
    <span class="bg-blue-900 text-yellow-300 px-6 py-2 rounded">
        AGENDA PIMPINAN
    </span>
</h1>

<div class="flex gap-3" style="height: 48vh;">

{{-- PROFIL --}}
<div class="w-1/2 bg-white rounded p-2 flex flex-col">
    <div class="bg-blue-900 text-yellow-300 px-3 py-1 rounded text-sm font-semibold">
        Profil Pimpinan & Staff
    </div>

    <div class="flex-1 mt-3 flex flex-col">

        {{-- PIMPINAN --}}
        <div class="grid grid-cols-2 gap-3 mb-3">
            @foreach($leaders as $leader)
            <div class="text-center">
                <img class="w-32 h-32 mx-auto object-cover rounded shadow-md"
                     src="{{ $leader->photo_path ? asset('storage/'.$leader->photo_path) : asset('images/default-user.png') }}">
                <p class="font-bold text-sm mt-2">{{ $leader->full_name }}</p>
            </div>
            @endforeach
        </div>

        <div class="border-t-2 border-blue-900 mb-3"></div>

        {{-- STAFF SLIDE --}}
        <div class="flex-1 overflow-hidden">
            @foreach($staffs->chunk(4) as $i => $chunk)
            <div class="slide grid grid-cols-4 gap-2 fade {{ $i === 0 ? 'active' : '' }}">
                @foreach($chunk as $staff)
                <div class="text-center">
                    <img class="w-24 h-24 mx-auto object-cover rounded shadow"
                         src="{{ $staff->photo_path ? asset('storage/'.$staff->photo_path) : asset('images/default-user.png') }}">
                    <p class="text-xs font-semibold mt-1">{{ $staff->full_name }}</p>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

    </div>
</div>

{{-- VIDEO --}}
<div class="flex-1 bg-white rounded p-2 flex flex-col">
    <div class="bg-blue-900 text-yellow-300 px-3 py-1 rounded text-sm font-semibold">
        Video Kegiatan
    </div>
<div class="flex-1 mt-2 bg-black rounded overflow-hidden">
    @if($videos->count())
        <video id="tvVideo" class="w-full h-full object-cover" autoplay muted playsinline>
            <source src="{{ asset('storage/'.$videos[0]->video_path) }}" type="video/mp4">
        </video>
    @endif
</div>

<script>
const videos = @json(
    $videos->pluck('video_path')->map(fn($v) => asset('storage/'.$v))
);

let videoIndex = 0;
const videoElement = document.getElementById('tvVideo');

if (videoElement && videos.length > 1) {
    videoElement.addEventListener('ended', () => {
        videoIndex = (videoIndex + 1) % videos.length;
        videoElement.src = videos[videoIndex];
        videoElement.play();
    });
}
</script>
    </div>
</div>

{{-- AGENDA --}}
<div class="bg-blue-900 text-yellow-300 px-3 py-1 rounded text-sm font-semibold">
    Agenda Kegiatan
</div>

<div class="bg-white rounded overflow-hidden">
<table class="w-full table-fixed border-collapse text-sm">

<thead class="bg-blue-600 text-white">
<tr>
    <th class="p-1.5 border w-[14%]">Tanggal / Jam</th>
    <th class="p-1.5 border w-[30%]">Kegiatan</th>
    <th class="p-1.5 border w-[14%]">Disposisi</th>
    <th class="p-1.5 border w-[22%]">Keterangan</th>
    <th class="p-1.5 border w-[20%]">Tempat</th>
</tr>
</thead>

@foreach($agendas->chunk(5) as $i => $chunk)
<tbody class="agenda-slide {{ $i === 0 ? 'active' : '' }}">
@foreach($chunk as $agenda)
@php
$tanggal = \Carbon\Carbon::parse($agenda->tanggal);
@endphp
<tr class="@if($tanggal->isToday()) bg-green-300
           @elseif($tanggal->isTomorrow()) bg-yellow-300
           @else bg-gray-100 @endif">
    <td class="border p-1 text-center font-semibold">
        {{ $tanggal->format('d M Y') }} {{ $agenda->jam }}
    </td>
    <td class="border p-1">{{ $agenda->nama_kegiatan }}</td>
    <td class="border p-1 text-center">{{ $agenda->disposisi ?? '-' }}</td>
    <td class="border p-1">{{ $agenda->keterangan ?? '-' }}</td>
    <td class="border p-1 text-center">{{ $agenda->tempat ?? '-' }}</td>
</tr>
@endforeach
</tbody>
@endforeach

</table>
</div>

{{-- RUNNING TEXT --}}
<div class="fixed bottom-10 left-0 w-full bg-blue-900 text-white py-1.5 px-4 overflow-hidden z-50">
    <marquee scrollamount="6">{{ $runningText }}</marquee>
</div>

</div>

<script>
let i = 0;
const slides = document.querySelectorAll('.slide');
setInterval(() => {
    slides[i].classList.remove('active');
    i = (i + 1) % slides.length;
    slides[i].classList.add('active');
}, 5000);

let a = 0;
const agendas = document.querySelectorAll('.agenda-slide');
setInterval(() => {
    agendas[a].classList.remove('active');
    a = (a + 1) % agendas.length;
    agendas[a].classList.add('active');
}, 10000);
</script>

</body>
</html>
