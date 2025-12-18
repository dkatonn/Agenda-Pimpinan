<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Video;
use Livewire\Attributes\On; // PENTING: Untuk Livewire 3/4

class VideoDisplayTV extends Component
{
    public $activeVideo; 
    
    public function mount()
    {
        $this->loadActiveVideo();
    }

    // Mengambil data video yang is_active = true dari Model Video Anda
    public function loadActiveVideo()
    {
        $this->activeVideo = Video::getActive(); 
    }

    /**
     * PENTING: Mendengarkan event dari komponen admin
     * Ketika event 'video-updated' dipancarkan, fungsi ini dieksekusi,
     * dan komponen Livewire akan me-render ulang.
     */
    #[On('video-updated')]
    public function refreshVideo()
    {
        $this->loadActiveVideo(); 
    }

    public function render()
    {
        return view('livewire.settings.video-display-tv');
    }
}