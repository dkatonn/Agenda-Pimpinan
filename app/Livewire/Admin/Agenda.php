<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Agenda as AgendaModel;
use Carbon\Carbon;

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
        'tanggal' => 'required|date',
        'jam' => 'required',
        'tempat' => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
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
    }

    /* ================= EDIT ================= */
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

    /* ================= CREATE ================= */
    public function save()
    {
        $this->validate();

        AgendaModel::create([
            'nama_kegiatan' => $this->nama_kegiatan,
            'tanggal' => $this->tanggal,
            'jam' => $this->jam,
            'tempat' => $this->tempat,
            'keterangan' => $this->keterangan,
            'disposisi' => $this->disposisi,
        ]);

        $this->successMessage = 'Agenda berhasil ditambahkan';

        $this->resetForm();
        $this->resetPage();
        $this->showModal = false;

        $this->dispatch('agenda-refresh-delayed');
    }

    /* ================= UPDATE ================= */
    public function update()
    {
        $this->validate();

        AgendaModel::findOrFail($this->agendaId)->update([
            'nama_kegiatan' => $this->nama_kegiatan,
            'tanggal' => $this->tanggal,
            'jam' => $this->jam,
            'tempat' => $this->tempat,
            'keterangan' => $this->keterangan,
            'disposisi' => $this->disposisi,
        ]);

        $this->successMessage = 'Agenda berhasil diperbarui';

        $this->resetForm();
        $this->resetPage();
        $this->showModal = false;

        $this->dispatch('agenda-refresh-delayed');
    }

    /* ================= DELETE ================= */
    public function confirmDelete($id)
    {
        $this->agendaIdToDelete = $id;
    }

    public function deleteAgenda()
    {
        AgendaModel::findOrFail($this->agendaIdToDelete)->delete();

        $this->agendaIdToDelete = null;
        $this->successMessage = 'Agenda berhasil dihapus';

        $this->resetPage();
        $this->dispatch('agenda-refresh-delayed');
    }

    /* ================= RENDER ================= */
    public function render()
    {
        $now = Carbon::now()->format('Y-m-d H:i');

        $agendas = AgendaModel::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('nama_kegiatan', 'like', '%' . $this->search . '%')
                       ->orWhere('tempat', 'like', '%' . $this->search . '%')
                       ->orWhere('disposisi', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByRaw("
                CASE
                    WHEN CONCAT(tanggal, ' ', jam) >= ? THEN 0
                    ELSE 1
                END
            ", [$now])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->paginate(10);

        return view('livewire.admin.agenda', compact('agendas'));
    }
}
