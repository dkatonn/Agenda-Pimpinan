<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
        }

        return view('livewire.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();
            $roleUser    = $user->role; 
            $roleProfile = optional($user->profile)->role; 


            // SUPERADMIN
            if ($roleUser === 'Superadmin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // ADMIN
            if ($roleUser === 'Admin') {
                return redirect()->intended(route('admin.agenda'));
            }

            // PIMPINAN
            if ($roleProfile === 'Pimpinan') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // STAFF
            if ($roleProfile === 'Staff') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // DEFAULT
            return redirect()->intended(route('admin.dashboard'));

        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
public function logout()
{
    Auth::logout();

    Session::invalidate();
    Session::regenerateToken();

    return view('livewire.auth.login');
}
}

