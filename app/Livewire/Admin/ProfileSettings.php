<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileSettings extends Component
{
    use WithFileUploads;

    public $profiles = [];
    public $showModal = false;
    public $editingId = null;

    public $full_name;
    public $category = 'Staff';
    public $photo;
    public $current_photo_path;
    public $profileIdToDelete = null;
    public $pimpinanPenuh = false;


    protected $rules = [
        'full_name' => 'required|string|max:255',
        'category'  => 'required|in:Staff,Pimpinan',
        'photo'     => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'full_name.required' => 'Nama wajib diisi.',
        'category.required'  => 'Kategori wajib dipilih.',
        'category.in'        => 'Kategori tidak valid.',
        'photo.image'        => 'File harus berupa gambar.',
        'photo.max'          => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount()
    {
        $this->loadProfiles();
    }

    public function render()
    {
        return view('livewire.admin.profile-settings');
    }

    public function loadProfiles()
    {
        $this->profiles = Profile::whereIn('category', ['Staff', 'Pimpinan'])
            ->latest()
            ->get();

        // cek apakah pimpinan sudah 2
        $this->pimpinanPenuh = Profile::where('category', 'Pimpinan')->count() >= 2;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->full_name = '';
        $this->category = 'Staff';
        $this->photo = null;
        $this->current_photo_path = null;
        $this->resetErrorBag();

        // jika sudah 2 pimpinan
        $this->category = $this->pimpinanPenuh ? 'Staff' : 'Staff';
    }

    /**
     * ================================
     * VALIDASI LIMIT PIMPINAN (MAX 2)
     * ================================
     */
    private function pimpinanSudahPenuh()
    {
        return Profile::where('category', 'Pimpinan')
            ->when($this->editingId, function ($query) {
                // abaikan data yang sedang diedit
                $query->where('id', '!=', $this->editingId);
            })
            ->count() >= 2;
    }

    public function save()
    {
        $this->validate();

        // CEK LIMIT PIMPINAN
        if ($this->category === 'Pimpinan' && $this->pimpinanSudahPenuh()) {
            $this->addError('category', 'Pimpinan maksimal hanya 2 orang.');
            return;
        }

        $photoPath = $this->current_photo_path;

        if ($this->photo) {
            if ($this->current_photo_path) {
                Storage::disk('public')->delete($this->current_photo_path);
            }
            $photoPath = $this->photo->store('profiles', 'public');
        }

        if ($this->editingId) {
            Profile::findOrFail($this->editingId)->update([
                'full_name'  => $this->full_name,
                'category'   => $this->category,
                'photo_path' => $photoPath,
            ]);

            session()->flash('message', 'Profil berhasil diperbarui!');
        } else {
            Profile::create([
                'full_name'  => $this->full_name,
                'category'   => $this->category,
                'photo_path' => $photoPath,
            ]);

            session()->flash('message', 'Profil baru berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->loadProfiles();

        $this->dispatch('refresh-page');
    }

    public function edit($id)
    {
        $profile = Profile::findOrFail($id);

        $this->editingId = $profile->id;
        $this->full_name = $profile->full_name;
        $this->category = $profile->category;
        $this->current_photo_path = $profile->photo_path;

        
        if ($profile->category === 'Pimpinan') {
            $this->pimpinanPenuh = false;
        }

        $this->resetErrorBag();

    }

    public function confirmDelete($id)
    {
        $this->profileIdToDelete = $id;
    }

    public function deleteProfile()
    {
        $profile = Profile::findOrFail($this->profileIdToDelete);

        if ($profile->photo_path) {
            Storage::disk('public')->delete($profile->photo_path);
        }

        $profile->delete();

        $this->profileIdToDelete = null;
        session()->flash('message', 'Profil berhasil dihapus!');

        $this->loadProfiles();
        $this->dispatch('refresh-page');
    }
}
