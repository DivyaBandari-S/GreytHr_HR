<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LogOut extends Component
{
    public $showLogoutModal = false;

    public function handleLogout()
    {
        $this->showLogoutModal = true;
    }

    public function confirmLogout()
    {
        try {
            // List of guards
            $guards = [  'hr',];

            // Determine the authenticated guard and log out
            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    Auth::guard($guard)->logout();
                    break; // Exit loop once logged out
                }
            }

            session()->invalidate();
            session()->regenerateToken();
            session()->flush(); // Clear session data

            // Flash success message
            session()->flash('success', 'You are logged out successfully!');

            // Redirect to the login page
            return redirect()->route('hrlogin');
        } catch (\Exception $exception) {
            // Handle exceptions
            session()->flash('error', 'An error occurred while logging out.');
            return redirect()->route('hrlogin'); // Redirect back with an error message
        }
    }

    public function cancelLogout()
    {
        $this->showLogoutModal = false;
    }

    public function render()
    {
        return view('livewire.log-out');
    }
}
