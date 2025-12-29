<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Agenda as AgendaModel;
use Carbon\Carbon;
use App\Events\TvUpdated;

class Agenda extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $successMessage = null;

    public $agendaId;
    public $agendaIdToDelete = null;

    public $nama_kegiatan;
    public $tanggal;
    public $jam;
    public $tempat;
    public $keterangan;
    public $disposisi;

    protected $rules = [
        'nama_kegiatan' => 'required',
        'tanggal'       => 'required|date',
        'jam'           => 'required',
        'tempat'        => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $agendas = AgendaModel::query()
            ->when($this->search, fn($q) =>
                $q->where('nama_kegiatan', 'like', '%' . $this->search . '%')
            )
            ->orderByRaw("
                CASE
                    WHEN CONCAT(tanggal, ' ', jam) >= ? THEN 1
                    ELSE 2
                END
            ", [$now])
            ->orderByRaw("
                CASE
                    WHEN CONCAT(tanggal, ' ', jam) >= ? 
                        THEN CONCAT(tanggal, ' ', jam)
                    ELSE NULL
                END ASC
            ", [$now])
            ->orderByRaw("
                CASE
                    WHEN CONCAT(tanggal, ' ', jam) < ?
                        THEN CONCAT(tanggal, ' ', jam)
                    ELSE NULL
                END DESC
            ", [$now])
            ->paginate(10);

        return view('livewire.admin.agenda', compact('agendas'));
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->agendaIdToDelete = null;
    }

    public function resetForm()
    {
        $this->agendaId = null;
        $this->nama_kegiatan = '';
        $this->tanggal = '';
        $this->jam = '';
        $this->tempat = '';
        $this->keterangan = '';
        $this->disposisi = '';
        $this->resetValidation();
    }

    public function edit($id)
    {
        $agenda = AgendaModel::findOrFail($id);

        $this->agendaId = $agenda->id;
        $this->nama_kegiatan = $agenda->nama_kegiatan;
        $this->tanggal = $agenda->tanggal;
        $this->jam = $agenda->jam;
        $this->tempat = $agenda->tempat;
        $this->keterangan = $agenda->keterangan;
        $this->disposisi = $agenda->disposisi;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        AgendaModel::create($this->only([
            'nama_kegiatan',
            'tanggal',
            'jam',
            'tempat',
            'keterangan',
            'disposisi'
        ]));

        event(new TvUpdated());

        $this->successMessage = 'Agenda berhasil ditambahkan';
        $this->resetForm();
        $this->showModal = false;

        $this->dispatch('admin-refresh');
    }

    public function update()
    {
        $this->validate();

        AgendaModel::findOrFail($this->agendaId)
            ->update($this->only([
                'nama_kegiatan',
                'tanggal',
                'jam',
                'tempat',
                'keterangan',
                'disposisi'
            ]));

        event(new TvUpdated());

        $this->successMessage = 'Agenda berhasil diperbarui';
        $this->resetForm();
        $this->showModal = false;

        $this->dispatch('admin-refresh');
    }

    public function confirmDelete($id)
    {
        $this->agendaIdToDelete = $id;
    }

    public function deleteAgenda()
    {
        AgendaModel::findOrFail($this->agendaIdToDelete)->delete();
        $this->agendaIdToDelete = null;

        event(new TvUpdated());

        $this->successMessage = 'Agenda berhasil dihapus';

        $this->dispatch('admin-refresh');
    }
}
