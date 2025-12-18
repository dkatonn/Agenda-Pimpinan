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

        $runningText = RunningText::where('is_active', 1)
            ->orderBy('created_at', 'asc')
            ->pluck('text')
            ->implode('   •   ');

        $agendas = Agenda::query()
            ->whereDate('tanggal', '>=', $today)
            ->orderByRaw("
                tanggal ASC,
                COALESCE(jam, '00:00:00') ASC
            ")
            ->get();

        $leaders = Profile::where('category', 'Pimpinan')
            ->limit(2)
            ->get();

        $staffs = Profile::where('category', 'Staff')->get();

        $video = Video::where('is_active', 1)->first();

        return view('tv.display', [
            'agendas'     => $agendas,
            'leaders'     => $leaders,
            'staffs'      => $staffs,
            'video'       => $video,
            'runningText' => $runningText ?: 'Tidak ada informasi',
        ]);
    }
}
