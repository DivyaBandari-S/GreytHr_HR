<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\EmployeeDetails;
use App\Models\EmpPersonalInfo;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FlashMessageHelper;
use Livewire\WithFileUploads;
use App\Models\Company;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class HrProfile extends Component
{

    use WithFileUploads;
    public $emp_id;
    public $name, $email, $nickName, $biography, $image;
    public $timeZones;
    public $editingNickName = false;
    public $showHelp = false;
    public $activeTab = 'Profile';
    public $employees;
    public $employeeDetails;
    public $editingTimeZone = false;
    public $editingBiography = false;
    public $selectedTimeZone;
    public $imageValidation = false;
    public $imageValidationmsg = '';
    public $imageBinary = '';
    public $imageerror = 'Preview not available for this file type.';
    public $isLoading = false;
    public $companyName;
    public $oldPassword;
    public $newPassword;
    public $confirmNewPassword;

    public function updatedImage()
    {
        $this->validateImageType();
    }

    public function validateImageType()
    {


        $extension = $this->image->extension();

        // Custom validation logic to check for valid image types
        $validExtensions = ['jpg', 'jpeg', 'png'];

        if (!in_array(strtolower($extension), $validExtensions)) {
            $this->imageValidation = true;
            $this->imageBinary = '';
            $this->imageValidationmsg = 'Please select only an image file.';
        } else {
            if ($this->image->getSize() > 1048576) {
                $this->imageBinary = '';

                $this->imageValidationmsg = 'Please select only an image size less than 1MB.';
                $this->image = null; // Clear the file
            } else {
                $this->imageValidationmsg = '';

                $this->imageBinary = base64_encode(file_get_contents($this->image->getRealPath()));
                $this->imageValidation = false;

                $this->validateOnly('imageBinary', $this->validationRules());
         
            }
        }
    }

    public function editProfile()
    {
        try {

            $employeeId = auth()->guard('hr')->user()->emp_id;

            $this->employeeDetails = EmployeeDetails::with(['empPersonalInfo'])
                ->where('emp_id', $employeeId)->first();
            $this->nickName = $this->employeeDetails->empPersonalInfo->nick_name ?? '';
            $this->editingNickName = true;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Error in editProfile method: ');
        }
    }


    public function cancelProfile()
    {
        $this->editingNickName = false;
    }
    public function saveProfile()
    {
        try {
            $employeeId = auth()->guard('hr')->user()->emp_id;

            $empPersonalInfo = EmpPersonalInfo::where('emp_id', $employeeId)->first();
            if ($empPersonalInfo) {
                $empPersonalInfo->nick_name = !empty($this->nickName) ? $this->nickName : $empPersonalInfo->nick_name;
                $empPersonalInfo->save();
            } else {
                $empPersonalInfo = EmpPersonalInfo::create([
                    'emp_id' => $employeeId,
                    'nick_name' => !empty($this->nickName) ? $this->nickName : null,
                    'email' => null,
                    'alternate_mobile_number' => null,
                ]);
            }

            $this->editingNickName = false;
        } catch (\Exception $e) {
            // Log::error('Error in saveProfile method for employee ID: ' . $employeeId . ' - ' . $e->getMessage());
            FlashMessageHelper::flashError('Error in saveProfile method: ');
        }
    }
    public function editTimeZone()
    {
        try {
            $employeeId = auth()->guard('hr')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $this->editingTimeZone = true;
            $this->selectedTimeZone = $this->employeeDetails->time_zone ?? '';
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Error in editTimeZone method: ');
        }
    }


    public function cancelTimeZone()
    {
        $this->editingTimeZone = false;
    }


    public function saveTimeZone()
    {
        try {
            $employeeId = auth()->guard('hr')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();

            $this->employeeDetails->time_zone = $this->selectedTimeZone;
            $this->employeeDetails->save();

            $this->editingTimeZone = false;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Error in saveTimeZone method: ');
        }
    }
    public function editBiography()
    {
        try {
            $employeeId = auth()->guard('hr')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::with(['empPersonalInfo'])
                ->where('emp_id', $employeeId)->first();
            $this->biography = $this->employeeDetails->empPersonalInfo->biography ?? '';
            $this->editingBiography = true;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Error in editBiography method:');
        }
    }


    public function cancelBiography()
    {
        $this->editingBiography = false;
    }


    public function saveBiography()
    {
        try {
            $employeeId = auth()->guard('hr')->user()->emp_id;
            $empPersonalInfo = EmpPersonalInfo::where('emp_id', $employeeId)->first();
            if ($empPersonalInfo) {
                $empPersonalInfo->biography = !empty($this->biography) ? $this->biography : $empPersonalInfo->biography;
                $empPersonalInfo->save();
            } else {
                $empPersonalInfo = EmpPersonalInfo::create([
                    'emp_id' => $employeeId,
                    'biography' => $this->biography,
                    'first_name' => '',
                    'last_name' => '',
                    'gender' => '',
                    'email' => null,
                    'mobile_number' => null,
                    'alternate_mobile_number' => null,
                ]);
            }
            $this->editingBiography = false;
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Error in saveBiography method: ');
        }
    }
    protected $rules = [
            'image' => 'nullable',
    ];

    protected $messages = [

        'image.max' => ' Selected image exceeds 1MB and cannot be uploaded.',
    ];
    protected function validationRules()
    {
        return [
            'image' => 'nullable|mimes:jpeg,png,jpg|max:1024', // Ensure proper validation
        ];
    }

    public function updateImage()
    {
        try {
            // Validate only if image is uploaded
            if ($this->image) {
                $this->validate($this->validationRules());
    
                // Ensure an employee exists before updating
                $employee = EmployeeDetails::where('emp_id', auth()->guard('hr')->user()->emp_id)->first();
    
                $this->imageBinary = base64_encode(file_get_contents($this->image->getRealPath()));
    
                if ($employee) {
                    $employee->update(['image' => $this->imageBinary]); // Update existing employee image
                }
                $this->image = null;

                // Dispatch an event to refresh the component
                $this->imageBinary = EmployeeDetails::where('emp_id', auth()->guard('hr')->user()->emp_id)->value('image');
    
                FlashMessageHelper::flashSuccess('Image uploaded successfully.');
            } else {
                FlashMessageHelper::flashWarning('No new image uploaded. Nothing to update.');
            }
        } catch (\Exception $e) {
            FlashMessageHelper::flashError('Error uploading image: ' . $e->getMessage());
        }
    }

    protected $passwordRules = [
        'oldPassword' => 'required|current_password',  // Validates that oldPassword is the user's current password
        'newPassword' => [
            'required',
            'string',
            'min:8',               // At least 8 characters
            'regex:/[A-Z]/',       // Must contain at least one uppercase letter
            'regex:/[a-z]/',       // Must contain at least one lowercase letter
            'regex:/[0-9]/',       // Must contain at least one digit
            'regex:/[@$!%*#?&]/',  // Must contain at least one special character
            'different:oldPassword', // Must be different from oldPassword
        ],
        'confirmNewPassword' => 'required|same:newPassword',  // Confirms that confirmNewPassword matches newPassword
    ];
    protected function messages()
    {
        return [
            'oldPassword.required' => 'Please enter your current password.',
            'newPassword.required' => 'Please enter your new password.',
            'newPassword.string' => 'The new password must be a valid string.',
            'newPassword.min' => 'Your password must be at least 8 characters long.',
            'newPassword.regex' => 'Your password must contain at least one capital letter, one lowercase letter, one digit, and one special character.',
            'newPassword.different' => 'The new password must be different from the old password.',
            'confirmNewPassword.required' => 'Please enter your confirm new password.',
            'confirmNewPassword.same' => 'The new password and confirmation do not match.',
        ];
    }

    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->oldPassword = '';
        $this->newPassword = '';
        $this->confirmNewPassword = '';
    }

    public function changePassword()
    {
        $this->isLoading = true; // Set loading state to true
        $this->validate();

        try {

            $employeeId = auth()->guard('hr')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $companyId = $this->employeeDetails->company_id;
            // Fetch the company details using company_id
            $company = Company::where('company_id', $companyId)->first();
            $this->companyName = $company->company_name;
            // Check if company details were found
            if (!Hash::check($this->oldPassword, $this->employeeDetails->password)) {
                $this->addError('oldPassword', 'The old password is incorrect.');
                return;
            }

            // Update the password
            $this->employeeDetails->password = Hash::make($this->newPassword);
            $this->employeeDetails->save();
            // Send password change notification
            if ($this->employeeDetails && !empty($this->employeeDetails->email)) {
                $this->employeeDetails->notify(new \App\Notifications\PasswordChangedNotification($this->companyName));
            }

            FlashMessageHelper::flashSuccess('Your Password changed successfully.');
            $this->resetForm();
        }catch (\Exception $e) {
            FlashMessageHelper::flashError('Error in changePassword method: ' . $e->getMessage());
            Log::info('Error in changePassword method', ['exception' => $e->getMessage()]);
        }
        finally {
            $this->isLoading = false; // Set loading state to false after processing
        }
    }
    public function updated($propertyName)
    {
        if (in_array($propertyName, array_keys($this->rules))) {
            $this->validateOnly($propertyName);
        } elseif (in_array($propertyName, array_keys($this->passwordRules))) {
            $this->validateOnly($propertyName, $this->passwordRules);
        }
    }
    


    public function hideHelp()
    {
        $this->showHelp = true;
    }
    public function showhelp()
    {
        $this->showHelp = false;
    }
    public function render()
    {
        try {
            $this->timeZones = timezone_identifiers_list();
            $this->employees = EmployeeDetails::with(['empPersonalInfo', 'empDepartment'])
                ->where('emp_id', auth()->guard('hr')->user()->emp_id)->first();
                $this->imageBinary =$this->employees ->image;

            return view('livewire.hr-profile', ['employees' => $this->employees]);
        } catch (\Exception $e) {

            FlashMessageHelper::flashError('Error in render method: ');
            return view('livewire.hr-profile')->withErrors(['error' => 'An error occurred while loading the data. Please try again later.']);
        }
    }
}
