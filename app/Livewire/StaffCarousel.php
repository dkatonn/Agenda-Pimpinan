<?php

namespace App\Livewire;

use Livewire\Component;

class StaffCarousel extends Component
{
    public $staffProfiles = [];
    public $currentIndex = 0;

    protected $listeners = ['refreshStaff' => 'nextStaff'];

    public function nextStaff()
    {
        if(count($this->staffProfiles) > 1){
            $this->currentIndex = ($this->currentIndex + 1) % count($this->staffProfiles);
        }
    }

    public function mount($staffProfiles = [])
    {
        $this->staffProfiles = $staffProfiles;
    }

    public function render()
    {
        return view('livewire.staff-carousel');
    }
}
