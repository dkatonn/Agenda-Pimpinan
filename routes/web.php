<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TvController;
use App\Models\Agenda;
use App\Models\Video;

// =======================
// PUBLIC ROUTES
// =======================

// Redirect root ke TV
Route::get('/', function () {
    return redirect()->route('tv.display');
});

// TV Display
Route::get('/tv', [TvController::class, 'display'])
    ->name('tv.display');

    Route::get('/tv/status', function () {
    return response()->json([
        'agenda_count' => Agenda::count(),
        'video_count'  => Video::count(),
    ]);
})->name('tv.status');



// =======================
// AUTH ROUTES (LOGIN)
// =======================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);
});


// =======================
// LOGOUT (HANDLE GET & POST)
// =======================

Route::post('/admin/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

Route::get('/admin/logout', function () {
    return redirect()->route('login');
});


// =======================
// PROTECTED ADMIN AREA
// =======================
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Agenda (Admin & Superadmin)
        Route::get('/agenda', \App\Livewire\Admin\Agenda::class)
            ->name('agenda');

        // =======================
        // SUPERADMIN ONLY
        // =======================
        Route::middleware(['role:superadmin'])->group(function () {

            Route::get('/dashboard', \App\Livewire\Dashboard::class)
                ->name('dashboard');

            Route::get('/profile-settings', \App\Livewire\Admin\ProfileSettings::class)
                ->name('profile-settings');

            Route::get('/video-management', \App\Livewire\Admin\VideoManagement::class)
                ->name('video-management');

            Route::get('/running-text-edit', \App\Livewire\Admin\RunningTextEdit::class)
                ->name('running-text-edit');

            Route::get('/users', \App\Livewire\Admin\UserManagement::class)
                ->name('users');
        });
    });
