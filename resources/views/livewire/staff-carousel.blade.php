<div class="flex justify-center items-center">
    @if(count($staffProfiles) > 0)
        @php
            $staff = $staffProfiles[$currentIndex];
        @endphp
        <div class="text-center">

            <!-- PAKSA UKURAN -->
            <div class="w-40 h-40 !w-40 !h-40 mx-auto rounded-full overflow-hidden border-4 border-gray-300 shadow-md">
                @if($staff['photo_path'])
                    <img src="{{ asset('storage/' . $staff['photo_path']) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-5xl text-gray-500"></i>
                    </div>
                @endif
            </div>

            <p class="text-base font-semibold mt-3">{{ $staff['full_name'] }}</p>
        </div>
    @endif
</div>


<script>
    setInterval(() => {
        Livewire.emit('refreshStaff');
    }, 5000);
</script>
