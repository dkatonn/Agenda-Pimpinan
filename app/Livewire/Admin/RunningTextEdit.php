<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\RunningText;

class RunningTextEdit extends Component
{
    public $items = [];

    public $showModal = false;
    public $editingId = null;
    public $text = '';

    public $textIdToDelete = null;

    public function mount()
    {
        $this->loadItems();
    }

    public function render()
    {
        return view('livewire.admin.running-text-edit');
    }

    public function loadItems()
    {
        $this->items = RunningText::orderBy('created_at', 'desc')->get();
    }

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
        $this->text = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'text' => 'required|string|max:500',
        ]);

        if ($this->editingId) {
            RunningText::findOrFail($this->editingId)->update([
                'text' => $this->text,
            ]);

            session()->flash('success', 'Running text berhasil diperbarui!');
        } else {
            RunningText::create([
                'text' => $this->text,
                'is_active' => false,
            ]);

            session()->flash('success', 'Running text berhasil ditambahkan!');
        }

        $this->closeModal();
        $this->loadItems();

        $this->dispatch('refresh-page');
    }

    public function edit($id)
    {
        $data = RunningText::findOrFail($id);

        $this->editingId = $id;
        $this->text = $data->text;
        $this->showModal = true;
    }

    public function deleteText()
    {
        if (!$this->textIdToDelete) return;

        RunningText::findOrFail($this->textIdToDelete)->delete();

        $this->textIdToDelete = null;
        $this->loadItems();

        session()->flash('success', 'Running text berhasil dihapus!');
        $this->dispatch('refresh-page');
    }
    public function toggleActive($id)
    {
        $item = RunningText::findOrFail($id);
        $item->update([
            'is_active' => !$item->is_active
        ]);

        session()->flash('success', 'Status running text diperbarui!');
        $this->loadItems();
        $this->dispatch('refresh-page');
    }
    public function setSingleActive($id)
    {
        RunningText::where('is_active', true)->update(['is_active' => false]);
        RunningText::findOrFail($id)->update(['is_active' => true]);

        session()->flash('success', 'Running text aktif tunggal diperbarui!');
        $this->loadItems();
        $this->dispatch('refresh-page');
    }
}
