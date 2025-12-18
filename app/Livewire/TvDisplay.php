<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Agenda;
use App\Models\Profile;
use App\Models\Video;
use App\Models\RunningText;
use Carbon\Carbon;

#[Layout('layouts.tv')]
class TvDisplay extends Component
{
    public array $leaderProfiles = [];
    public array $staffProfiles = [];
    public array $agendas = [];
    public string $runningText = '';
    public ?string $videoUrl = null;
    public ?string $videoType = null;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->agendas = Agenda::whereDate('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get()
            ->toArray();

        $this->leaderProfiles = Profile::where('category', 'Pimpinan')
            ->select('id', 'full_name', 'photo_path')
            ->limit(2)
            ->get()
            ->toArray();

        $this->staffProfiles = Profile::where('category', 'Staff')
            ->select('id', 'full_name', 'photo_path')
            ->get()
            ->toArray();

        $texts = RunningText::where('is_active', 1)
            ->pluck('text')
            ->toArray();

        $this->runningText = count($texts)
            ? implode(' • ', $texts)
            : 'Tidak ada informasi berjalan';

        $this->loadActiveVideo();
    }

    private function loadActiveVideo()
    {
        $video = Video::where('is_active', 1)->first();

        if (!$video) return;

        $this->videoUrl = $video->youtube_url
            ? $video->youtube_url
            : asset('storage/' . $video->video_path);

        $this->videoType = $video->youtube_url ? 'youtube' : 'file';
    }

    public function render()
    {
        return view('livewire.tv-display');
    }
}
