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

        // Running text (aktif saja)
        $runningText = RunningText::where('is_active', 1)
            ->orderBy('created_at', 'asc')
            ->pluck('text')
            ->implode('   •   ');

        // Agenda hari ini dan ke depan
        $agendas = Agenda::whereDate('tanggal', '>=', $today)
            ->orderBy('tanggal')
            ->orderByRaw("COALESCE(jam, '00:00:00')")
            ->get();

        // Profil
        $leaders = Profile::where('category', 'Pimpinan')
            ->limit(2)
            ->get();

        $staffs = Profile::where('category', 'Staff')->get();

        // Video aktif
        $videos = Video::where('is_active', 1)->get();

        return view('tv.display', [
            'agendas'     => $agendas,
            'leaders'     => $leaders,
            'staffs'      => $staffs,
            'videos'       => $videos,
            'runningText' => $runningText ?: 'Belum Ada Informasi Lanjutan',
        ]);
    }
}
