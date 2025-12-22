<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Profile;
use App\Models\Video;
use App\Models\Agenda;
use App\Models\User;


class Dashboard extends Component
{
    public $leaderProfiles = [];
    public $staffProfiles = [];
    public $videoUrl = null;
    public $videoType = null;
    public $agendas = [];
    public $videos = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Pimpinan
        $this->leaderProfiles = Profile::where('category', 'Pimpinan')
            ->select('id', 'full_name', 'photo_path')
            ->orderBy('id', 'asc')
            ->limit(2)
            ->get(); 

        // Staff
        $this->staffProfiles = Profile::where('category', 'Staff')
            ->select('id', 'full_name', 'photo_path')
            ->orderBy('id', 'asc')
            ->get(); 

        // Video aktif
        $video = Video::where('is_active', 1)->first();

        if ($video) {
            if ($video->youtube_url) {
                $this->videoUrl = $video->youtube_url;
                $this->videoType = 'youtube';
            } elseif ($video->video_path) {
                $this->videoUrl = asset('storage/' . $video->video_path);
                $this->videoType = 'file';
            }
        }

        // Agenda
        $this->agendas = Agenda::with('profile:id,full_name')
            ->orderBy('tanggal', 'asc')
            ->limit(10)
            ->get();  

        // Semua video
        $this->videos = Video::orderBy('id','desc')->get();
    }

    public function toggleActiveVideo($videoId)
    {
        $video = Video::findOrFail($videoId);

        $video->update([
            'is_active' => ! $video->is_active
        ]);

        $this->loadData();

        session()->flash('message', 'Status video diperbarui.');
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'leaderProfiles' => $this->leaderProfiles,
            'staffProfiles' => $this->staffProfiles,
            'videos' => $this->videos,
            'videoUrl' => $this->videoUrl,
            'videoType' => $this->videoType,
            'agendas' => $this->agendas,
            'users' => User::orderBy('id', 'desc')->limit(10)->get(),
        ]);
    }
}
