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
    background: #e2e3e3;
    font-family: system-ui, sans-serif;
}

.slide { display: none; }
.slide.active { display: grid; }

.agenda-slide { display: none; }
.agenda-slide.active { display: table-row-group; }

.profile-slide { display: none; }
.profile-slide.active { display: flex; }

.fade { animation: fade .4s ease; }
@keyframes fade {
    from { opacity: 0; transform: translateY(6px); }
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

/* EMPTY STATE TANPA ANIMASI */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-align: center;
    opacity: .7;
}
.empty-state.light { color: #6b7280; }
.empty-icon { font-size: 42px; }

/* BADGE NOTIF PROFIL */
.profile-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #16a34a;
    color: #fff;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 999px;
    box-shadow: 0 2px 6px rgba(0,0,0,.2);
    opacity: 0;
    transition: opacity .3s ease;
}
.profile-badge.show { opacity: 1; }
</style>
</head>

<body>

<div class="h-screen w-screen p-3 flex flex-col gap-3">

{{-- HEADER --}}
<div class="grid grid-cols-3 items-center">
    <div></div>
    <h1 class="text-2xl font-bold text-center">
        <span class="bg-[#0f2c5c] text-yellow-300 px-8 py-2 rounded-lg tracking-wide">
            AGENDA PIMPINAN
        </span>
    </h1>
    <div class="flex justify-end">
        <div id="tvClock"
             class="tv-clock bg-[#0f2c5c] text-yellow-300 px-6 py-3 rounded-xl shadow-lg">
            00:00:00
        </div>
    </div>
</div>

<div class="flex gap-2" style="height:50vh">

{{-- PROFIL --}}
<div class="w-1/2 card p-2 flex flex-col relative">
    <div class="section-title mb-2">Profil Pimpinan</div>

    <div class="flex-1 flex items-center justify-center overflow-hidden">
        @forelse($profileCarousel as $i => $profile)
            <div class="profile-slide flex flex-col items-center gap-4 fade {{ $i === 0 ? 'active' : '' }}">
                <img class="w-40 h-40 object-cover rounded-2xl shadow"
                     src="{{ $profile['photo'] ? asset('storage/'.$profile['photo']) : asset('images/default-user.png') }}">
                <div class="text-center">
                    <p class="text-xl font-bold">{{ $profile['name'] }}</p>
                    <p class="text-sm {{ $profile['type'] === 'pimpinan' ? 'text-blue-700 font-semibold' : 'text-gray-600' }}">
                        {{ ucfirst($profile['type']) }}
                    </p>
                </div>
            </div>
        @empty
            <div class="empty-state light">
                <div class="empty-icon">👤</div>
                <div class="text-lg font-semibold">Belum ada profil</div>
            </div>
        @endforelse
    </div>
</div>

{{-- VIDEO --}}
<div class="flex-1 card p-2 flex flex-col relative">
    <div class="section-title mb-2">Video Kegiatan</div>
    <div class="flex-1 bg-grey-200 rounded-lg overflow-hidden relative flex items-center justify-center">
        @if($videos->count())
            <video id="tvVideo" class="w-full h-full object-cover fade-in" autoplay muted playsinline>
                <source src="{{ asset('storage/'.$videos[0]->video_path) }}" type="video/mp4">
            </video>
            <div id="muteIcon" class="mute-indicator">🔇</div>
        @else
            <div class="empty-state" style="color:grey;">
                <div class="empty-icon">🎬</div>
                <div class="text-lg font-semibold">Belum ada video kegiatan</div>
            </div>
        @endif
    </div>
</div>

</div>

{{-- AGENDA --}}
<div class="card overflow-hidden">
    <div class="section-title text-4xl px-10 py-3 font-extrabold rounded-none mb-2">
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

        @if($agendas->count())
            @foreach($agendas->chunk(5) as $i => $chunk)
            <tbody class="agenda-slide {{ $i === 0 ? 'active' : '' }}">
                @foreach($chunk as $agenda)
                @php $tanggal = \Carbon\Carbon::parse($agenda->tanggal); @endphp
                <tr class="@if($tanggal->isToday()) bg-green-300
                           @elseif($tanggal->isTomorrow()) bg-yellow-300
                           @else bg-slate-400 @endif">
                    <td class="p-2 font-semibold text-center">
                        {{ $tanggal->format('d M Y') }}
                        {{ $agenda->jam ? \Carbon\Carbon::parse($agenda->jam)->format('H:i') : '' }}
                    </td>
                    <td class="p-2">{{ $agenda->nama_kegiatan }}</td>
                    <td class="p-2 text-center">{{ $agenda->tempat ?? '-' }}</td>
                    <td class="p-2 text-center">{{ $agenda->disposisi ?? '-' }}</td>
                    <td class="p-2">{{ $agenda->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            @endforeach
        @else
            <tbody class="agenda-slide active">
                <tr>
                    <td colspan="5" class="p-6">
                        <div class="empty-state light">
                            <div class="empty-icon">📅</div>
                            <div class="text-lg font-semibold">Belum ada agenda hari ini</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>

{{-- RUNNING TEXT --}}
<div class="fixed bottom-4 left-0 w-full bg-[#0f2c5c] text-yellow-300 py-2 px-4 z-50">
    <marquee scrollamount="6">{{ $runningText }}</marquee>
</div>

</div>

<script>
const initialAgendaCount = {{ $agendas->count() }};
const initialVideoCount  = {{ $videos->count() }};
</script>

<script>
function pad(n){ return n.toString().padStart(2,'0'); }
function updateClock() {
    const d = new Date();
    tvClock.innerText = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
}
setInterval(updateClock,1000); updateClock();

// PROFILE carousel + notif badge
let profileIndex = 0;
const profileSlides = document.querySelectorAll('.profile-slide');
const profileBadge = document.getElementById('profileBadge');

setInterval(() => {
    if (profileSlides.length > 1) {
        profileSlides[profileIndex].classList.remove('active');
        profileIndex = (profileIndex + 1) % profileSlides.length;
        profileSlides[profileIndex].classList.add('active');

        if(profileBadge){
            profileBadge.classList.add('show');
            setTimeout(()=>profileBadge.classList.remove('show'),1500);
        }
    }
}, 5000);

// AGENDA carousel
let agendaIndex = 0;
const agendaSlides = document.querySelectorAll('.agenda-slide');
setInterval(() => {
    if(agendaSlides.length > 1){
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
    if(!muteIcon) return;
    muteIcon.innerText = isMuted ? '🔇' : '🔊';
    muteIcon.classList.add('show');
    clearTimeout(muteTimeout);
    muteTimeout = setTimeout(()=>muteIcon.classList.remove('show'),1500);
}

function toggleMute(){
    if(!video) return;
    isMuted = !isMuted;
    video.muted = isMuted;
    video.play();
    showMuteIcon();
}

function playVideo(idx){
    if(!video || videos.length === 0) return;
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
    },300);
}

if(video && videos.length){
    video.addEventListener('ended',()=>{
        index = (index+1)%videos.length;
        playVideo(index);
    });
    video.addEventListener('click',toggleMute);
}

// AUTO REFRESH IF DATA CHANGED (fallback polling)
setInterval(async () => {
    try {
        const res = await fetch("{{ route('tv.status') }}");
        const data = await res.json();
        if (
            data.agenda_count !== initialAgendaCount ||
            data.video_count !== initialVideoCount
        ) {
            location.reload();
        }
    } catch (e) {
        console.error('Gagal cek status', e);
    }
}, 30000);
</script>

{{-- Load Echo & app.js --}}
@vite(['resources/js/app.js'])

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.Echo) {
        console.log('Echo loaded, listening tv.updated...');

        window.Echo.channel('tv-channel')
            .listen('.tv.updated', () => {
                console.log('Realtime refresh');

                setTimeout(() => {
                    location.reload();
                }, 0); 
            });
    }
});
</script>

</body>
</html>
