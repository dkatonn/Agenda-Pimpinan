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
            ->get();  // ❌ Hapus ->toArray()

        // Semua video
        $this->videos = Video::orderBy('id','desc')->get();
    }

    public function setActiveVideo($videoId)
    {
        // Nonaktifkan semua video
        Video::query()->update(['is_active' => 0]);

        // Aktifkan video yang dipilih
        $video = Video::find($videoId);

        if ($video) {
            $video->is_active = 1;
            $video->save();
        }

        // Reload data agar UI terupdate
        $this->loadData();

        // Optional: tampilkan notifikasi
        session()->flash('message', 'Video berhasil diaktifkan.');
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
