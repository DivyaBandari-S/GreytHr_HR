<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{
    public $showLogoutModal = false;

    public function handleLogout()
    {
        $this->showLogoutModal = true;
    }

    public function cancelLogout()
    {
        $this->showLogoutModal = false;
    }
    public function confirmLogout()
    {

        try {
            // Logout the user from all guards
        Auth::logout();

            // Clear session data
            session()->flush();
            Session::invalidate();
            Session::regenerateToken();

            // Flash success message
            session()->flash('success', "You are logged out successfully!");

            // Redirect to the login page
            return redirect()->route('hrlogin');
        } catch (\Exception $exception) {
            // Handle exceptions
            session()->flash('error', "An error occurred while logging out.");
            return redirect()->back(); // Redirect back with an error message
        }
    }

    public function render()
    {
        return view('livewire.header');
    }
}
