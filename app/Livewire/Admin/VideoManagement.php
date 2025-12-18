<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoManagement extends Component
{
    use WithFileUploads;

    public $videos = [];

    public $title;
    public $video_file;
    public $current_video_path;

    public $editingId = null;
    public $videoIdToDelete = null;

    #[Title('Kelola Konten Video')]
    public function mount()
    {
        $this->loadVideos();
    }

    public function loadVideos()
    {
        $this->videos = Video::orderByDesc('created_at')->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'video_file' => $this->editingId
                ? 'nullable|file|mimes:mp4,mov,avi|max:51200'
                : 'required|file|mimes:mp4,mov,avi|max:51200',
        ]);
        $videoPath = $this->current_video_path;

        if ($this->video_file) {
            if ($this->editingId && $this->current_video_path) {
                Storage::disk('public')->delete($this->current_video_path);
            }

            $videoPath = $this->video_file->store('videos', 'public');
        }
        if ($this->editingId) {
            Video::findOrFail($this->editingId)->update([
                'title'      => $this->title,
                'video_path' => $videoPath,
            ]);

            session()->flash('message', 'Video berhasil diperbarui!');
        } else {
            Video::create([
                'title'      => $this->title,
                'video_path' => $videoPath,
                'is_active'  => false,
            ]);

            session()->flash('message', 'Video baru berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->loadVideos();

        // refresh page
        $this->dispatch('refresh-page');
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);

        $this->editingId = $video->id;
        $this->title = $video->title;
        $this->current_video_path = $video->video_path;

        $this->video_file = null;
    }

    public function resetForm()
    {
        $this->reset([
            'title',
            'video_file',
            'editingId',
            'current_video_path',
        ]);
    }

    public function deleteVideo()
    {
        $video = Video::findOrFail($this->videoIdToDelete);

        if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
            Storage::disk('public')->delete($video->video_path);
        }

        $video->delete();

        $this->videoIdToDelete = null;
        $this->loadVideos();

        session()->flash('message', 'Video berhasil dihapus!');

        //  REFRESH
        $this->dispatch('refresh-page');
    }

    public function setActive($id)
    {
        Video::where('is_active', true)->update(['is_active' => false]);
        Video::findOrFail($id)->update(['is_active' => true]);

        $this->loadVideos();
        session()->flash('message', 'Video aktif berhasil diubah!');

        // REFRESH
        $this->dispatch('refresh-page');
    }

    public function render()
    {
        return view('livewire.admin.video-management');
    }
}
