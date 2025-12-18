<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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
    public int $agendaPage = 0;
    public int $agendaPerPage = 3;
    public int $currentStaffIndex = 0;
    public int $staffPerSlide = 4;
    public function mount()
    {
        $this->loadData();
    }
    public function loadData()
    {
        $this->agendas = Agenda::whereDate('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal')
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
            ->orderBy('created_at')
            ->pluck('text')
            ->toArray();

        $this->runningText = count($texts)
            ? implode('   •   ', $texts)
            : 'Tidak ada informasi berjalan';
        $this->loadActiveVideo();
    }

    private function loadActiveVideo()
    {
        $video = Video::where('is_active', 1)->first();

        if (!$video) {
            $this->videoUrl = null;
            $this->videoType = null;
            return;
        }

        if ($video->youtube_url) {
            $this->videoUrl = $video->youtube_url;
            $this->videoType = 'youtube';
        } elseif ($video->video_path) {
            $this->videoUrl = asset('storage/' . $video->video_path);
            $this->videoType = 'file';
        }
    }

    #[On('next-staff')]
    public function nextStaff()
    {
        if (count($this->staffProfiles) > $this->staffPerSlide) {
            $this->currentStaffIndex =
                ($this->currentStaffIndex + $this->staffPerSlide)
                % count($this->staffProfiles);
        }
    }

    public function getCurrentStaffSlideProperty()
    {
        return array_slice(
            $this->staffProfiles,
            $this->currentStaffIndex,
            $this->staffPerSlide
        );
    }

    #[On('next-agenda')]
    public function nextAgendaSlide()
    {
        $total = count($this->agendas);

        if ($total <= $this->agendaPerPage) {
            $this->agendaPage = 0;
            return;
        }

        $maxPage = ceil($total / $this->agendaPerPage) - 1;

        $this->agendaPage = $this->agendaPage >= $maxPage
            ? 0
            : $this->agendaPage + 1;
    }

    public function getCurrentAgendaProperty()
    {
        return array_slice(
            $this->agendas,
            $this->agendaPage * $this->agendaPerPage,
            $this->agendaPerPage
        );
    }

    public function render()
    {
        return view('livewire.tv-display');
    }
}
