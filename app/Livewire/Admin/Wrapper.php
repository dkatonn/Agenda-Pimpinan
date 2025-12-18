<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Wrapper extends Component
{
    public $activePage = 'dashboard'; // default

    // PENTING: Gunakan array untuk listeners di Livewire 3
    protected $listeners = [
        'goToDashboard' => 'showDashboard',
        'goToAgenda' => 'showAgenda',
        'goToVideo' => 'showVideo',
        'goToProfile' => 'showProfile',
        'goToUsers' => 'showUsers',
        'goToRunningText' => 'showRunningText',
    ];

    public function mount()
    {
        // Set default page saat component pertama kali dimuat
        $this->activePage = 'dashboard';
    }

    public function showDashboard() 
    { 
        $this->activePage = 'dashboard';
        $this->dispatchBrowserEvent('page-changed', ['page' => 'dashboard']);
    }

    public function showAgenda() 
    { 
        $this->activePage = 'agenda';
        $this->dispatchBrowserEvent('page-changed', ['page' => 'agenda']);
    }

    public function showVideo() 
    { 
        $this->activePage = 'video';
        $this->dispatchBrowserEvent('page-changed', ['page' => 'video']);
    }

    public function showProfile() 
    { 
        $this->activePage = 'profile';
        $this->dispatchBrowserEvent('page-changed', ['page' => 'profile']);
    }

    public function showUsers() 
    { 
        $this->activePage = 'users';
        $this->dispatchBrowserEvent('page-changed', ['page' => 'users']);
    }

    public function showRunningText() 
    { 
        $this->activePage = 'running-text';
        $this->dispatchBrowserEvent('page-changed', ['page' => 'running-text']);
    }

    public function render()
    {
        return view('livewire.admin.wrapper');
    }
}