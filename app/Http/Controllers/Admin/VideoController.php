<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Video;
use Livewire\Attributes\On; 

class VideoController extends Component
{
    // Properti untuk menampung data video aktif
    public $activeVideo; 
    
    public function mount()
    {
        $this->loadActiveVideo();
    }

    public function loadActiveVideo()
    {
        $this->activeVideo = Video::getActive(); //
    }


    #[On('video-updated')]
    public function refreshVideo()
    {
        $this->loadActiveVideo(); 
        // Livewire akan otomatis me-render ulang template (view)
    }

    public function render()
    {
        return view('livewire.settings.video-display-tv');
    }
}