<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Agenda as AgendaModel;

class Agenda extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public $showModal = false;
    public $editMode = false;
    public $agendaId;
    public $nama_kegiatan;
    public $tanggal;
    public $tempat;
    public $keterangan;
    public $disposisi;
    public $agendaIdToDelete = null;

    protected $rules = [
        'nama_kegiatan' => 'required|string|max:255',
        'tanggal'       => 'required|date',
        'tempat'        => 'required|string|max:255',
        'keterangan'    => 'nullable|string',
        'disposisi'     => 'nullable|string|max:255',
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->agendaId     = null;
        $this->nama_kegiatan = '';
        $this->tanggal       = '';
        $this->tempat        = '';
        $this->keterangan    = '';
        $this->disposisi     = '';
    }

    public function save()
    {
        $this->validate();

        AgendaModel::create([
            'nama_kegiatan' => $this->nama_kegiatan,
            'tanggal'       => $this->tanggal,
            'tempat'        => $this->tempat,
            'keterangan'    => $this->keterangan,
            'disposisi'     => $this->disposisi,
        ]);

        session()->flash('success', 'Agenda berhasil ditambahkan.');
        $this->closeModal();
        
        // refresh halaman
        $this->dispatch('refresh-page');
    }

    public function edit($id)
    {
        $agenda = AgendaModel::findOrFail($id);

        $this->agendaId       = $agenda->id;
        $this->nama_kegiatan  = $agenda->nama_kegiatan;
        $this->tanggal        = $agenda->tanggal;
        $this->tempat         = $agenda->tempat;
        $this->keterangan     = $agenda->keterangan;
        $this->disposisi      = $agenda->disposisi;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        AgendaModel::findOrFail($this->agendaId)->update([
            'nama_kegiatan' => $this->nama_kegiatan,
            'tanggal'       => $this->tanggal,
            'tempat'        => $this->tempat,
            'keterangan'    => $this->keterangan,
            'disposisi'     => $this->disposisi,
        ]);

        session()->flash('success', 'Agenda berhasil diperbarui.');
        $this->closeModal();
        
        // refresh
        $this->dispatch('refresh-page');
    }

    public function confirmDelete($id)
    {
        $this->agendaIdToDelete = $id;
    }

    public function deleteAgenda()
    {
        AgendaModel::findOrFail($this->agendaIdToDelete)->delete();
        $this->agendaIdToDelete = null;

        session()->flash('success', 'Agenda berhasil dihapus.');
        $this->resetPage();
        
        // refresh halaman
        $this->dispatch('refresh-page');
    }

    public function render()
    {
        return view('livewire.admin.agenda', [
            'agendas' => AgendaModel::orderBy('tanggal', 'asc')->paginate(10),
        ]);
    }
}