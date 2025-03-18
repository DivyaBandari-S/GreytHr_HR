<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
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
            Auth::logout();
            session()->invalidate();
            FlashMessageHelper::flashSuccess("You are logged out successfully!");

            // Flash success message
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
