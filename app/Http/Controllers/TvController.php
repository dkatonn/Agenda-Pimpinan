<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Profile;
use App\Models\Video;
use App\Models\RunningText;
use Carbon\Carbon;

class TvController extends Controller
{
    public function display()
    {
        $today = Carbon::today();

        // Running text
        $runningText = RunningText::where('is_active', 1)
            ->orderBy('created_at', 'asc')
            ->pluck('text')
            ->implode('   •   ');

        // Agenda
        $agendas = Agenda::whereDate('tanggal', '>=', $today)
            ->orderBy('tanggal')
            ->orderByRaw("COALESCE(jam, '00:00:00')")
            ->get();

        // Profil
        $leaders = Profile::where('category', 'Pimpinan')->get();
        $staffs  = Profile::where('category', 'Staff')->get();

        // Gabung: pimpinan dulu, lalu staff
        $profileCarousel = collect()
            ->merge($leaders->map(fn($p) => [
                'type'  => 'pimpinan',
                'name'  => $p->full_name,
                'photo' => $p->photo_path,
            ]))
            ->merge($staffs->map(fn($s) => [
                'type'  => 'staff',
                'name'  => $s->full_name,
                'photo' => $s->photo_path,
            ]));

        // Video aktif
        $videos = Video::where('is_active', 1)->get();

        // Mode dari env/config
        $leaderMode = config('tv.leader_mode', 'single');

        return view('tv.display', [
            'agendas'          => $agendas,
            'leaders'          => $leaders,
            'staffs'           => $staffs,
            'profileCarousel'  => $profileCarousel,
            'videos'           => $videos,
            'leaderMode'       => $leaderMode,
            'runningText'      => $runningText ?: 'Belum Ada Informasi Lanjutan',
        ]);
    }

    public function status()
{
    $today = \Carbon\Carbon::today();

    return response()->json([
        'agenda_count' => \App\Models\Agenda::whereDate('tanggal', '>=', $today)->count(),
        'video_count'  => \App\Models\Video::where('is_active', 1)->count(),
    ]);
}

}
