<div>
    @switch($activePage)
        @case('dashboard')
            <p>Dashboard</p>
            @break
        @case('agenda')
            @livewire('admin.agenda', key('agenda'))
            @break
        @case('video')
            @livewire('admin.video-management', key('video'))
            @break
        @case('profile')
            @livewire('admin.profile-settings', key('profile'))
            @break
        @case('users')
            @livewire('admin.users', key('users'))
            @break
        @case('running-text')
            @livewire('admin.running-text-edit', key('running-text'))
            @break
        @default
            <p>Page not found.</p>
    @endswitch
</div>
