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
        // membuat running text
        $runningTexts = RunningText::where('is_active', 1)
            ->orderBy('created_at')
            ->pluck('text')
            ->toArray();

        return view('tv.display', [
            // agenda
            'agendas' => Agenda::whereDate('tanggal', '>=', Carbon::today())
                ->orderBy('tanggal')
                ->get(),

            // pimpinan
            'leaders' => Profile::where('category', 'Pimpinan')
                ->limit(2)
                ->get(),

            // staff
            'staffs' => Profile::where('category', 'Staff')->get(),

            // running text
            'runningText' => count($runningTexts)
                ? implode('   •   ', $runningTexts)
                : 'Tidak ada informasi ',

            // video aktif
            'video' => Video::where('is_active', 1)->first(),
        ]);
    }
}
