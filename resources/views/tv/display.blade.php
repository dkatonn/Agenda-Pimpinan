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
    background: #eef2f7;
    font-family: system-ui, sans-serif;
}

.slide { display: none; }
.slide.active { display: grid; }

.agenda-slide { display: none; }
.agenda-slide.active { display: table-row-group; }

.fade { animation: fade .4s ease; }
@keyframes fade {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}

.section-title {
    background: #0f2c5c;
    color: #ffffff;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 700;
}

#tvVideo { transition: opacity .3s ease-in-out; }
#tvVideo.fade-out { opacity: 0; }
#tvVideo.fade-in { opacity: 1; }

.mute-indicator {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(0,0,0,.65);
    color: white;
    font-size: 18px;
    padding: 6px 10px;
    border-radius: 8px;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s ease;
}
.mute-indicator.show { opacity: 1; }

.tv-clock {
    font-family: monospace;
    font-size: 20px;
    font-weight: 700;
}
</style>
</head>

<body>

<div class="h-screen w-screen p-3 flex flex-col gap-3">

<div class="grid grid-cols-3 items-center">
    <div></div>
    <h1 class="text-2xl font-bold text-center">
        <span class="bg-[#0f2c5c] text-yellow-300 px-8 py-2 rounded-lg tracking-wide">
            AGENDA PIMPINAN
        </span>
    </h1>
    <div class="flex justify-end">
        <div id="tvClock"
             class="tv-clock text-xl bg-[#0f2c5c] text-yellow-300 px-6 py-3 rounded-xl shadow-lg">
            00:00:00
        </div>
    </div>
</div>

<div class="flex gap-2" style="height:50vh">

<div class="w-1/2 card p-2 flex flex-col">
    <div class="section-title mb-2">Profil Pimpinan</div>

    <div class="grid grid-cols-2 gap-3 mb-3">
        @foreach($leaders as $leader)
        <div class="flex items-center flex-col gap-2 bg-slate-100 rounded-lg p-2">
            <img class="w-24 h-24 object-cover rounded-lg shadow"
                 src="{{ $leader->photo_path ? asset('storage/'.$leader->photo_path) : asset('images/default-user.png') }}">
            <div>
                <p class="text-sm font-bold text-center">{{ $leader->full_name }}</p>
                <p class="text-xs text-gray-600 text-center">Pimpinan</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex-1 overflow-hidden">
        @foreach($staffs->chunk(4) as $i => $chunk)
        <div class="slide grid grid-cols-2 grid-rows-2 gap-2 fade {{ $i === 0 ? 'active' : '' }}">
            @foreach($chunk as $staff)
            <div class="flex flex-col items-center bg-slate-100 rounded-lg p-1">
                <img class="w-16 h-16 object-contain bg-white p-1 rounded-lg shadow"
                     src="{{ $staff->photo_path ? asset('storage/'.$staff->photo_path) : asset('images/default-user.png') }}">
                <p class="text-xs font-semibold mt-1 text-center">{{ $staff->full_name }}</p>
                <p class="text-[10px] text-gray-500">Staff</p>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

<div class="flex-1 card p-2 flex flex-col relative">
    <div class="section-title mb-2">Video Kegiatan</div>
    <div class="flex-1 bg-black rounded-lg overflow-hidden relative">
        @if($videos->count())
        <video id="tvVideo" class="w-full h-full object-cover fade-in" autoplay muted playsinline>
            <source src="{{ asset('storage/'.$videos[0]->video_path) }}" type="video/mp4">
        </video>
        <div id="muteIcon" class="mute-indicator">🔇</div>
        @endif
    </div>
</div>

</div>

<div class="card overflow-hidden">
    <div class="section-title text-5xl px-12 py-4 font-extrabold rounded-none mb-2">
        Agenda Kegiatan
    </div>

    <table class="w-full table-fixed text-base border-collapse mt-0">
        <thead class="bg-[#0f2c5c] text-white">
        <tr>
            <th class="p-2 w-[14%] text-xs font-semibold">Tanggal / Jam</th>
            <th class="p-2 w-[30%] text-xs font-semibold">Kegiatan</th>
            <th class="p-2 w-[20%] text-xs font-semibold">Tempat</th>
            <th class="p-2 w-[14%] text-xs font-semibold">Disposisi</th>
            <th class="p-2 w-[22%] text-xs font-semibold">Keterangan</th>
        </tr>
        </thead>

@foreach($agendas->chunk(5) as $i => $chunk)
<tbody class="agenda-slide {{ $i === 0 ? 'active' : '' }}">
@foreach($chunk as $agenda)
@php $tanggal = \Carbon\Carbon::parse($agenda->tanggal); @endphp
<tr class="@if($tanggal->isToday()) bg-green-200
           @elseif($tanggal->isTomorrow()) bg-yellow-200
           @else bg-slate-200 @endif">
    <td class="p-2 font-semibold text-center">
        {{ $tanggal->format('d M Y') }} {{ \Carbon\Carbon::parse($agenda->jam)->format('H:i') }}
    </td>
    <td class="p-2">{{ $agenda->nama_kegiatan }}</td>
    <td class="p-2 text-center">{{ $agenda->tempat ?? '-' }}</td>
    <td class="p-2 text-center">{{ $agenda->disposisi ?? '-' }}</td>
    <td class="p-2">{{ $agenda->keterangan ?? '-' }}</td>
</tr>
@endforeach
</tbody>
@endforeach
</table>
</div>

<div class="fixed bottom-8 left-0 w-full bg-[#0f2c5c] text-yellow-300 py-2 px-4 z-50">
    <marquee scrollamount="6">{{ $runningText }}</marquee>
</div>

</div>

<script>
function pad(n){ return n.toString().padStart(2,'0'); }
function updateClock() {
    const d = new Date();
    tvClock.innerText = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
}
setInterval(updateClock,1000); updateClock();

// STAFF
let staffIndex = 0;
const staffSlides = document.querySelectorAll('.slide');
setInterval(() => {
    if(staffSlides.length){
        staffSlides[staffIndex].classList.remove('active');
        staffIndex = (staffIndex + 1) % staffSlides.length;
        staffSlides[staffIndex].classList.add('active');
    }
}, 5000);

// AGENDA
let agendaIndex = 0;
const agendaSlides = document.querySelectorAll('.agenda-slide');
setInterval(() => {
    if(agendaSlides.length){
        agendaSlides[agendaIndex].classList.remove('active');
        agendaIndex = (agendaIndex + 1) % agendaSlides.length;
        agendaSlides[agendaIndex].classList.add('active');
    }
}, 7000);

// VIDEO
const videos = @json($videos->pluck('video_path')->map(fn($v)=>asset('storage/'.$v)));
const video = document.getElementById('tvVideo');
const muteIcon = document.getElementById('muteIcon');
let index = 0, isMuted = true, muteTimeout;

function showMuteIcon(){
    muteIcon.innerText = isMuted ? '🔇' : '🔊';
    muteIcon.classList.add('show');
    clearTimeout(muteTimeout);
    muteTimeout = setTimeout(()=>muteIcon.classList.remove('show'),1500);
}
function toggleMute(){
    isMuted = !isMuted;
    video.muted = isMuted;
    video.play();
    showMuteIcon();
}
function playVideo(idx){
    video.classList.add('fade-out');
    setTimeout(()=>{
        video.src = videos[idx];
        video.load();
        video.onloadeddata = ()=>{
            video.classList.remove('fade-out');
            video.classList.add('fade-in');
            video.muted = isMuted;
            video.play();
        };
    },1800);
}
if(video){
    video.addEventListener('ended',()=>{
        index = (index+1)%videos.length;
        playVideo(index);
    });
    video.addEventListener('click',toggleMute);
}
</script>

</body>
</html>
