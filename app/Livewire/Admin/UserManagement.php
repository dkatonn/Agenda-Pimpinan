<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    public $items = [];

    public $showModal = false;
    public $editingId = null;

    public $email = '';
    public $password = '';
    public $role = 'Admin';

    public $userIdToDelete = null;
    public $showPassword = false;

    public $successMessage = null;

    /* =========================
        BASIC
    ========================= */

    public function mount()
    {
        $this->loadItems();
    }

    public function render()
    {
        return view('livewire.admin.user-management');
    }

    public function loadItems()
    {
        $this->items = User::with('profile')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    /* =========================
        MODAL
    ========================= */

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->email = '';
        $this->password = '';
        $this->role = 'Admin';
        $this->showPassword = false;
        $this->resetValidation();
    }

    /* =========================
        SAVE (CREATE & UPDATE)
    ========================= */

    public function save()
    {
        $rules = [
            'email' => 'required|email|unique:users,email' . ($this->editingId ? ',' . $this->editingId : ''),
            'role'  => 'required|in:Admin,Superadmin',
        ];

        $rules['password'] = $this->editingId
            ? 'nullable|min:5'
            : 'required|min:5';

        $this->validate($rules, [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah terdaftar.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min'   => 'Password minimal 5 karakter.',
            'role.required'  => 'Role harus dipilih.',
        ]);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);

            $user->update([
                'email' => $this->email,
                'role'  => $this->role,
            ]);

            if (!empty($this->password)) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            if ($user->profile) {
                $user->profile->update([
                    'role' => $this->role,
                ]);
            } else {
                Profile::create([
                    'user_id' => $user->id,
                    'role' => $this->role,
                    'full_name' => '-',
                ]);
            }

            $this->successMessage = 'User berhasil diperbarui!';
        } else {
            $autoName = strstr($this->email, '@', true);

            $user = User::create([
                'name'     => $autoName,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
                'role'     => $this->role,
            ]);

            Profile::create([
                'user_id'   => $user->id,
                'role'      => $this->role,
                'full_name' => '-',
            ]);

            $this->successMessage = 'User baru berhasil ditambahkan!';
        }

        $this->loadItems();
        $this->showModal = false;

        // refresh
        $this->dispatch('refresh-page-delayed');
    }

    /* =========================
        EDIT
    ========================= */

    public function edit($id)
    {
        $user = User::with('profile')->findOrFail($id);

        $this->editingId = $id;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role;

        $this->showModal = true;
    }

    /* =========================
        DELETE
    ========================= */

    public function confirmDelete($id)
    {
        $this->userIdToDelete = $id;
    }

    public function deleteUser()
    {
        if (!$this->userIdToDelete) return;

        $user = User::find($this->userIdToDelete);
        if (!$user) {
            $this->successMessage = 'User tidak ditemukan!';
            $this->userIdToDelete = null;
            return;
        }

        $user->delete();

        $this->successMessage = 'User berhasil dihapus!';
        $this->userIdToDelete = null;

        $this->loadItems();

        $this->dispatch('refresh-page-delayed');
    }
}
