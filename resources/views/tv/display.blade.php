<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="300">
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

/* SLIDE */
.slide { display: none; }
.slide.active { display: grid; }
.agenda-slide { display: none; }
.agenda-slide.active { display: table-row-group; }

/* ANIMASI */
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

        {{-- garis pemisah --}}
        <div class="border-t-2 border-blue-900 mt-4 mb-3"></div>

        {{-- STAFF --}}
        <div class="flex-1 overflow-hidden">
            @php $staffChunks = $staffs->chunk(4); @endphp

            @foreach($staffChunks as $i => $chunk)
            <div class="slide grid grid-cols-4 gap-1 fade {{ $i === 0 ? 'active' : '' }}">
                @foreach($chunk as $staff)
                <div class="text-center py-4">
                    <img class="w-26 h-26 mx-auto object-cover rounded shadow"
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
<div class="w-1/2 bg-white rounded p-2 flex flex-col">
    <div class="bg-blue-900 text-yellow-300 px-3 py-1 rounded text-sm font-semibold">
        Video Kegiatan
    </div>

    <div class="flex-1 mt-3 bg-black rounded overflow-hidden">
        @if($video?->youtube_url)
            <iframe class="w-full h-full"
                src="{{ $video->youtube_url }}?autoplay=1&mute=1&loop=1"
                allow="autoplay"
                frameborder="0"></iframe>
        @elseif($video?->video_path)
            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                <source src="{{ asset('storage/'.$video->video_path) }}" type="video/mp4">
            </video>
        @endif
    </div>
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
    <th class="p-1.5 border border-gray-300 w-[14%]">Tanggal / Jam</th>
    <th class="p-1.5 border border-gray-300 w-[30%]">Kegiatan</th>
    <th class="p-1.5 border border-gray-300 w-[14%]">Disposisi</th>
    <th class="p-1.5 border border-gray-300 w-[22%]">Keterangan</th>
    <th class="p-1.5 border border-gray-300 w-[20%]">Tempat</th>
</tr>
</thead>

@php $agendaChunks = $agendas->chunk(5); @endphp

@foreach($agendaChunks as $i => $chunk)
<tbody class="agenda-slide {{ $i === 0 ? 'active' : '' }}">
@foreach($chunk as $agenda)
@php
    $today = \Carbon\Carbon::today();
    $tomorrow = \Carbon\Carbon::tomorrow();
    $tanggal = \Carbon\Carbon::parse($agenda->tanggal)->startOfDay();

    $bgColor = 'bg-gray-200';
    if ($tanggal->equalTo($today)) $bgColor = 'bg-green-300';
    elseif ($tanggal->equalTo($tomorrow)) $bgColor = 'bg-yellow-300';
@endphp

<tr class="{{ $bgColor }}">
    <td class="p-1.5 border border-gray-300 text-center font-semibold whitespace-nowrap">
        {{ $tanggal->format('d M Y') }}
        {{ $agenda->jam ? substr($agenda->jam, 0, 5) : '' }}
    </td>
    <td class="p-1.5 border border-gray-300">{{ $agenda->nama_kegiatan }}</td>
    <td class="p-1.5 border border-gray-300 text-center">{{ $agenda->disposisi ?? '-' }}</td>
    <td class="p-1.5 border border-gray-300">{{ $agenda->keterangan ?? '-' }}</td>
    <td class="p-1.5 border border-gray-300 text-center">{{ $agenda->tempat ?? '-' }}</td>
</tr>
@endforeach

@for($j = count($chunk); $j < 5; $j++)
<tr>
    <td colspan="5" class="p-1.5 border border-gray-300">&nbsp;</td>
</tr>
@endfor
</tbody>
@endforeach

</table>
</div>

{{-- RUNNING TEXT --}}
<div class="bg-blue-900 text-white py-1.5 px-4 rounded overflow-hidden">
    <marquee scrollamount="6" class="text-sm">{{ $runningText }}</marquee>
</div>

</div>

<script>
let staffIndex = 0;
const staffSlides = document.querySelectorAll('.slide');
if (staffSlides.length > 0) {
    setInterval(() => {
        staffSlides[staffIndex].classList.remove('active');
        staffIndex = (staffIndex + 1) % staffSlides.length;
        staffSlides[staffIndex].classList.add('active');
    }, 5000);
}

let agendaIndex = 0;
const agendaSlides = document.querySelectorAll('.agenda-slide');
if (agendaSlides.length > 0) {
    setInterval(() => {
        agendaSlides[agendaIndex].classList.remove('active');
        agendaIndex = (agendaIndex + 1) % agendaSlides.length;
        agendaSlides[agendaIndex].classList.add('active');
    }, 10000);
}
</script>

</body>
</html>
