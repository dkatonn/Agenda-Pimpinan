<div class="w-full space-y-6">

    {{--  ROW ATAS  --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{--  BOX PROFIL  --}}
        <div class="bg-[#1e293b] p-6 rounded-lg shadow-lg border border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-4">
                <h2 class="text-xl font-semibold text-white">Profil Pimpinan & Staff</h2>

                {{-- TOMBOL KELOLA PROFIL --}}
            <button 
                onclick="window.location.href='{{ route('admin.profile-settings') }}'"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm w-full sm:w-auto text-center transition-colors">
                Kelola Profil
            </button>

            </div>

            <div class="space-y-6">

                {{-- Pimpinan --}}
                <div>
                    <h3 class="text-sm font-semibold mb-3 text-gray-300">Pimpinan</h3>
                    <div class="flex flex-wrap gap-5">
                        @forelse ($leaderProfiles as $leader)
                            <div class="text-center">
                                <img src="{{ $leader->photo_path ? asset('storage/'.$leader->photo_path) : 'https://via.placeholder.com/80' }}"
                                     class="w-20 h-20 rounded-full object-cover mx-auto mb-2 ring-2 ring-blue-500">
                                <p class="text-xs font-medium text-gray-200">{{ $leader->full_name }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">Belum ada pimpinan</p>
                        @endforelse
                    </div>
                </div>

                {{-- Staff --}}
                <div>
                    <h3 class="text-sm font-semibold mb-3 text-gray-300">Staff</h3>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
                        @forelse ($staffProfiles as $item)
                            <div class="text-center">
                                <img src="{{ $item->photo_path ? asset('storage/'.$item->photo_path) : 'https://via.placeholder.com/60' }}"
                                     class="w-14 h-14 rounded-full object-cover mx-auto mb-2 ring-1 ring-gray-600">
                                <p class="text-[11px] leading-tight text-gray-200">{{ $item->full_name }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 col-span-5">Belum ada staff</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        {{--  BOX VIDEO LIST  --}}
        <div class="bg-[#1e293b] p-6 rounded-lg shadow-lg border border-gray-700">

            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-4">
                <h2 class="text-xl font-semibold text-white">Daftar Video</h2>

                {{-- TOMBOL KELOLA VIDEO --}}
            <button 
                onclick="window.location.href='{{ route('admin.video-management') }}'"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm w-full sm:w-auto text-center transition-colors">
                Kelola Video
            </button>

            </div>

            <div class="border border-gray-700 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[500px]">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">Judul</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-[#0f172a]">
                            @forelse ($videos as $video)
                                <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors">
                                    <td class="py-3 px-4 text-gray-200">{{ $video->title }}</td>

                                    <td class="py-3 px-4 text-right">
                                        @if (!$video->is_active)
                                            <button wire:click="setActiveVideo({{ $video->id }})"
                                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                                Aktifkan
                                            </button>
                                        @else
                                            <button disabled
                                                    class="px-3 py-1 bg-green-600 text-white rounded cursor-not-allowed opacity-75">
                                                Aktif
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-6 text-center text-gray-400">
                                        Belum ada video.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    {{--  ROW BAWAH: AGENDA  --}}
    <div class="bg-[#1e293b] p-6 rounded-lg shadow-lg border border-gray-700">

        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-4">
            <h2 class="text-xl font-semibold text-white">Agenda Kegiatan</h2>

            {{-- TOMBOL KELOLA AGENDA --}}
        <button 
            onclick="window.location.href='{{ route('admin.agenda') }}'"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm w-full sm:w-auto text-center transition-colors">
            Kelola Agenda
        </button>

        </div>

        <div class="border border-gray-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[650px]">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">Kegiatan</th>
                            <th class="py-3 px-4 text-left">Disposisi</th>
                            <th class="py-3 px-4 text-left">Keterangan</th>
                            <th class="py-3 px-4 text-left">Tempat</th>
                        </tr>
                    </thead>

                    <tbody class="bg-[#0f172a]">
                        @forelse ($agendas as $agenda)
                            <tr class="border-b border-gray-700 hover:bg-gray-800 transition-colors">
                                <td class="py-3 px-4 text-gray-200">{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $agenda->nama_kegiatan }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $agenda->disposisi ?? '-' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $agenda->keterangan ?? '-' }}</td>
                                <td class="py-3 px-4 text-gray-200">{{ $agenda->tempat ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400">
                                    Belum ada agenda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>