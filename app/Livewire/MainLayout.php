<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class MainLayout extends Component
{
    public $showLogoutModal = false;

    public $companiesLogo;
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

    //company logo for login admin
    public function getCompanyLogo()
    {

    }


    public function render()
    {
        $loggedInEmpID = auth()->guard('hr')->user()->emp_id;

        // Step 2: Find the employee's details
        $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();

        if (!$employeeDetails) {
            return response()->json(['error' => 'Employee not found.'], 404);
        }

        // Step 3: Decode the company_id if it's in JSON format
        $companyIds = $employeeDetails->company_id;
        if (is_array($companyIds)) {
            // Step 4: Fetch company logo and name for each company_id
            $this->companiesLogo = Company::whereIn('company_id', $companyIds)->select('company_logo')
            ->where('is_parent','yes')
            ->select('company_logo')
            ->first();
        }

        //login admin profile
        $loginAdminDetails = EmployeeDetails::where('emp_id', $loggedInEmpID)->first();

        return view('livewire.main-layout', [
            'companiesLogo' => $this->companiesLogo,
            'loginAdminDetails' => $loginAdminDetails
        ]);
    }
}
