<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    public $items = [];

    public $editingId = null;

    public $email = '';
    public $password = '';
    public $role = 'Admin';

    public $showPassword = false;
    public $userIdToDelete = null;

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
        $this->items = User::orderBy('created_at', 'desc')->get();
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
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

    public function save()
    {
        $rules = [
            'email' => 'required|email|unique:users,email' . ($this->editingId ? ',' . $this->editingId : ''),
            'role'  => 'required|in:Admin,Superadmin',
        ];

        $rules['password'] = $this->editingId
            ? 'nullable|min:5'
            : 'required|min:5';

        $this->validate($rules);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);

            $user->update([
                'email' => $this->email,
                'role'  => $this->role,
            ]);

            if ($this->password) {
                $user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['role' => $this->role, 'full_name' => '-']
            );
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
        }

        $this->resetForm();
        $this->loadItems();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->editingId = $id;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role;
    }

    public function confirmDelete($id)
    {
        $this->userIdToDelete = $id;
    }

    public function deleteUser()
    {
        User::findOrFail($this->userIdToDelete)->delete();
        $this->userIdToDelete = null;
        $this->loadItems();
    }
}
