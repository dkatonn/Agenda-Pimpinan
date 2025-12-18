<div class="p-6 md:p-8">
    <div class="grid grid-cols-12 gap-6">
        
        {{-- KOLOM KIRI: TAMPILAN PIMPINAN (4/12 Kolom) --}}
        <div class="col-span-12 md:col-span-4">
            <div class="full-box p-4">
                <h2 class="text-xl font-bold mb-4 text-center text-gray-800">Dua Pimpinan</h2>
                
                @if (count($leaderProfiles) > 0)
                    @foreach($leaderProfiles as $leaderProfile)
                        <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-indigo-200 shadow-md">
                            @if ($leaderProfile->photo_path)
                                <img 
                                    src="{{ Storage::url($leaderProfile->photo_path) }}"
                                    class="w-full h-full object-cover"
                                    alt="Foto Pimpinan {{ $loop->iteration }}"
                                >
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-3xl text-gray-500"></i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="p-4 border rounded-lg text-center text-gray-500 bg-white">
                        Data Pimpinan Belum Tersedia.
                    </div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: TAMPILAN AGENDA (8/12 Kolom) --}}
        <div class="col-span-12 md:col-span-8">
            <div class="full-box">
                <div class="blue-header flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i> Agenda Kegiatan Hari Ini
                </div>
                <div class="p-4 content-box">
                    {{-- Tabel Agenda --}}
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Kegiatan</th>
                                <th>Tempat</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($agendas as $agenda)
                                <tr class="border-b border-gray-100">
                                    <td class="whitespace-nowrap font-medium">{{ \Carbon\Carbon::parse($agenda['tanggal'])->translatedFormat('d M') }} / {{ $agenda['waktu'] }}</td>
                                    <td>{{ $agenda['nama_kegiatan'] }}</td>
                                    <td>{{ $agenda['tempat'] }}</td>
                                    <td>{{ $agenda['keterangan'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-gray-500">
                                        Tidak ada agenda yang akan datang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- RUNNING TEXT --}}
    <div class="running-text-container mt-6">
        <marquee class="font-bold text-lg">{{ $runningText }}</marquee>
    </div>

    <script>
        setInterval(() => {
            Livewire.dispatch('refreshComponent');
        }, 30000);
    </script>
</div>