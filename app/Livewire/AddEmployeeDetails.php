<?php

// File Name                       : AddEmployeeDetails.php
// Description                     : This file contains the implementation of the Adding employee details (onboarding process).....,
// Creator                         : Bandari Divya
// Email                           : bandaridivya1@gmail.com
// Organization                    : PayG.
// Date                            : 2024-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : EmployeeDetails,Company -->

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\EmpDepartment;
use App\Models\EmpSubDepartments;
use App\Models\EmpBankDetail;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AddEmployeeDetails extends Component
{
    use WithFileUploads;

    public $emp_id;
    public $first_name;
    public $last_name;
    public $date_of_birth;
    public $gender;
    public $email;
    public $company_name = '';
    public $company_email;
    public $mobile_number;
    public $alternate_mobile_number;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $country;
    public $hire_date;
    public $employee_type;
    public $departments;
    public $sub_departments = [];
    public $department_id = '';
    public $sub_department_id = '';
    public $job_title;
    public $job_mode;
    public $manager_id;
    public $report_to;

    public $emergency_contact;
    public $password;
    public $image;
    public $blood_groups = [
        'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'
    ];
    public $blood_group = '';
    public $nationality;
    public $religion;
    public $marital_status;
    public $spouse;
    public $physically_challenge;
    public $inter_emp;
    public $job_location;
    public $education;
    public $experience;
    public $pan_no;
    public $aadhar_no;
    public $pf_no;
    public $employee_status;

    public $nick_name;
    public $time_zone;
    public $biography;
    public $facebook;
    public $twitter;
    public $linked_in;
    public $company_id = '';
    public $is_starred;
    public $skill_set;
    public $savedImage;

    public $companies;
    public $hrDetails;
    public $managerIds = [];
    public $managerName;
    public $companyName;
    public $employeeId;
    public $imagePath;
    public $empId;
    public $reportTos, $selectedEmployee, $employee;
    public $selectedId;
    public $companieIds;
    public $referrer;
    public $probation_period;
    public $confirmation_date;
    public $notice_period;
    public $emp_domain;
    public $shift_type;
    public $shift_start_time;
    public $shift_end_time;


    public  $selectedEmployees;
    public $currentStep = 1;

    protected function validationRules()
    {
        return $this->rules[$this->currentStep];
    }

    public function nextPage()

    {
        $this->validate($this->validationRules());
        if($this->currentStep==1 || $this->currentStep==2){
           $this->registerEmployeeDetails();
        }elseif($this->currentStep==3){
            $this->registerEmployeeJobDetails();
        }else{

        }
        // $this->currentStep++;


    }

    public function previousPage()
    {
        $this->currentStep--;
    }

    protected $rules = [
        1 => [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'company_email' => 'required|email',
            'gender' => 'required|in:Male,Female',
        ],
        2 => [
            'hire_date' => 'required|date',
            'employee_type' => 'required|in:full-time,part-time,contract',
            'employee_status' => 'required|in:active,on-leave,terminated',
            'department_id' => 'required|string|max:255',
            'sub_department_id' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'job_location' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,company_id',
            'manager_id' => 'required',
        ],
        3 =>
        [
            'date_of_birth' => 'required|date',
            'blood_group' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'marital_status' => 'required',
            'email' => 'required|email|unique:employee_details',
        ],
        4 => [
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
        ],
    ];

    public function registerEmployeeDetails()
    {

        try{


            $this->validate($this->validationRules());

            if ($this->image) {
                $imageBinary  =base64_encode(file_get_contents($this->image->getRealPath())) ;
            } else {
                $imageBinary = 'Null';
            }



            $report_to = EmployeeDetails::where('emp_id', $this->manager_id)->value('manager_id');
            $employee = EmployeeDetails::updateOrCreate(
                ['emp_id' => $this->emp_id],
                [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'gender' => $this->gender,
                    'email' => $this->company_email,
                    'hire_date' => $this->hire_date,
                    'employee_type' => $this->employee_type,
                    'manager_id' => $this->manager_id,
                    'dept_head' => $report_to,
                    'dept_id' => $this->department_id,
                    'sub_dept_id' => $this->sub_department_id,
                    'job_role' => $this->job_title,
                    'employee_status' => $this->employee_status,
                    'emergency_contact' => $this->mobile_number,
                    'inter_emp' => $this->inter_emp,
                    'job_location' => $this->job_location,
                    // 'image' => $imageBinary,
                    'emp_domain'=>$this->emp_domain,
                    'referral'=>$this->referrer,
                    'probation_Period'=>$this->probation_period,
                    'confirmation_date' =>$this->confirmation_date,
                    'shift_type'=>$this->shift_type,
                    'shift_start_time'=>$this->shift_start_time,
                    'shift_end_time'=>$this->shift_end_time,

                ]
            );

            $this->emp_id = $employee->emp_id;
            dd($this->emp_id);

            session()->flash('emp_success', 'Employee registered successfully!');

            // Clear the form fields
            $this->reset();
            $this->currentStep++;
            // return redirect('/update-employee-details');

        }
        catch(\Exception $e ){
            Log::error('Error in getTubeColor method: ' . $e->getMessage());
            throw $e;
        }


    }
    public function registerEmployeeJobDetails(){
        // 'nationality' => $this->nationality,
                // 'religion' => $this->religion,
                // 'marital_status' => $this->marital_status,
                // 'physically_challenge' => $this->physically_challenge,
                // 'education' => $this->education,
                // 'experience' => $this->experience,
                // 'pan_no' => $this->pan_no,
                // 'aadhar_no' => $this->aadhar_no,
                // 'pf_no' => $this->pf_no,
                // 'nick_name' => $this->nick_name,
                // 'time_zone' => $this->time_zone,
                // 'biography' => $this->biography,
                // 'facebook' => $this->facebook,
                // 'twitter' => $this->twitter,
                // 'linked_in' => $this->linked_in,
                // 'company_id' => $this->company_id,
                // 'is_starred' => $this->is_starred,
                 // 'mobile_number' => $this->mobile_number,
                // 'alternate_mobile_number' => $this->alternate_mobile_number,
                // 'address' => $this->address,
                // 'city' => $this->city,
                // 'state' => $this->state,
                // 'postal_code' => $this->postal_code,
                // 'country' => $this->country,
                  // 'email' => $this->email,
                // 'company_name' => $this->company_name,
                 // 'blood_group' => $this->blood_group,
                 // 'date_of_birth' => $this->date_of_birth,
    }

    public function logout()
    {
        auth()->guard('com')->logout();
        return redirect('/Login&Register');
    }


    public function selectedCompany()
    {
        if ($this->company_id) {
            $this->selectedId = Company::find($this->company_id);
            $this->company_name = $this->selectedId->company_name;
            $this->companyName = $this->company_name;
            // $this->selectedEmployees = EmployeeDetails::where('company_id', $this->company_id)->get();
            // dd( $this->selectedEmployees);
            // if ($this->selectedEmployees->isNotEmpty()) {
            //     foreach ($this->selectedEmployees as $employee) {

            //         if (!in_array($employee->manager_id, $this->managerIds)) {
            //             $this->managerIds[] = $employee->manager_id;
            //         }
            //     }
            // }
            $managers = EmployeeDetails::where('dept_id', $this->department_id)
                ->pluck('manager_id')
                ->unique()
                ->toArray();

            $this->managerIds = EmployeeDetails::whereIn('emp_id', $managers)
                ->get(['emp_id', 'first_name', 'last_name', 'manager_id']);

            //  dd( $this->managerIds);

        }
    }

    public function selectedDepartment($value)
    {
        if ($value !== '') {
            $this->sub_departments = EmpSubDepartments::where('dept_id', $value)->get();

            $managers = EmployeeDetails::where('dept_id', $this->department_id)
                ->pluck('manager_id')
                ->unique()
                ->toArray();

            $this->managerIds = EmployeeDetails::whereIn('emp_id', $managers)
                ->get(['emp_id', 'first_name', 'last_name', 'manager_id']);
        }
    }

    public function fetchReportTo()
    {
        // Code to fetch report_to based on the selected manager_id
        if ($this->manager_id) {
            $managerDetails = EmployeeDetails::where('emp_id', $this->manager_id)->first();

            if ($managerDetails) {
                $this->managerName = $managerDetails->first_name . ' ' . $managerDetails->last_name;
            } else {
                $this->managerName = null;
            }
        } else {
            $this->managerName = null;
        }

        $this->report_to = $this->managerName; // Set report_to property as managerName
    }


    public function mount()
    {
        // Set default values if the properties are not set
        $this->job_mode = $this->job_mode ?? 'Office';
        $this->employee_status = $this->employee_status ?? 'active';
        $this->employee_type = $this->employee_type ?? 'full-time';
        $this->physically_challenge = $this->physically_challenge ?? 'No';
        $this->inter_emp = $this->inter_emp ?? 'no';
        $empId = request()->query('emp_id');
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeCompanyName = Company::find($empCompanyId)->company_name;


        $this->companieIds = Company::where('company_id', $empCompanyId)->get();
        $companieIdsLength = count($this->companieIds);
        if ($companieIdsLength == 1) {
            $this->company_id = $this->companieIds->first()->company_id;
        }
        $this->departments = EmpDepartment::where('company_id', $empCompanyId)->get();

        if ($empId) {
            try {
                $decryptedEmpId = Crypt::decrypt($empId);
                //dd($decryptedEmpId);
                $this->editEmployee($decryptedEmpId);
            } catch (DecryptException $e) {
                // Handle the error appropriately
                session()->flash('error_message', 'Invalid Employee ID.');
                return redirect()->route('some-error-route'); // Or handle the error as needed
            }
        }
    }
    public function editEmployee($employeeId)
    {
        $employee = EmployeeDetails::find($employeeId);

        if ($employee) {
            // Assign fetched employee details to the respective properties
            $this->first_name = $employee->first_name;
            $this->last_name = $employee->last_name;
            $this->date_of_birth = $employee->date_of_birth;
            $this->gender = $employee->gender;
            $this->email = $employee->email;
            $this->company_name = $employee->company_name;
            $this->company_email = $employee->company_email;
            $this->mobile_number = $employee->mobile_number;
            $this->alternate_mobile_number = $employee->alternate_mobile_number;
            $this->address = $employee->address;
            $this->city = $employee->city;
            $this->state = $employee->state;
            $this->postal_code = $employee->postal_code;
            $this->country = $employee->country;
            $this->hire_date = $employee->hire_date;
            $this->employee_type = $employee->employee_type;
            $this->manager_id = $employee->manager_id;
            $this->report_to = $employee->report_to;
            $this->department_id = $employee->department_id;
            $this->sub_department_id = $employee->sub_department_id;
            $this->job_title = $employee->job_title;
            $this->employee_status = $employee->employee_status;
            $this->emergency_contact = $employee->emergency_contact;
            $this->password = $employee->password;
            $this->image = $employee->image;  // Example storage for image upload
            $this->blood_group = $employee->blood_group;
            $this->nationality = $employee->nationality;
            $this->religion = $employee->religion;
            $this->marital_status = $employee->marital_status;
            $this->spouse = $employee->spouse;
            $this->physically_challenge = $employee->physically_challenge;
            $this->inter_emp = $employee->inter_emp;
            $this->job_location = $employee->job_location;
            $this->education = $employee->education;
            $this->experience = $employee->experience;
            $this->pan_no = $employee->pan_no;
            $this->aadhar_no = $employee->adhar_no;
            $this->pf_no = $employee->pf_no;
            $this->nick_name = $employee->nick_name;
            $this->time_zone = $employee->time_zone;
            $this->biography = $employee->biography;
            $this->facebook = $employee->facebook;
            $this->twitter = $employee->twitter;
            $this->linked_in = $employee->linked_in;
            $this->company_id = $employee->company_id;
            $this->is_starred = $employee->is_starred;
            $this->employeeId = $employeeId;
        }
    }
    public function updateEmployee()
    {
        $this->validate($this->validationRules());
        $employee = EmployeeDetails::find($this->employeeId);
        // Update the employee details
        if ($employee) {
            if ($this->image instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $this->image->store('employee_image', 'public');
            } else {
                $imagePath = $this->image; // Already a path, use as is
            }

            $employee->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'date_of_birth' => $this->date_of_birth,
                'gender' => $this->gender,
                'email' => $this->email,
                'company_name' => $this->company_name,
                'company_email' => $this->company_email,
                'mobile_number' => $this->mobile_number,
                'alternate_mobile_number' => $this->alternate_mobile_number,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'hire_date' => $this->hire_date,
                'employee_type' => $this->employee_type,
                'manager_id' => $this->manager_id,
                'report_to' => $this->report_to,
                'department' => $this->department,
                'job_title' => $this->job_title,
                'employee_status' => $this->employee_status,
                'emergency_contact' => $this->emergency_contact,
                'password' => $this->password,
                'image' => $imagePath,
                'blood_group' => $this->blood_group,
                'nationality' => $this->nationality,
                'religion' => $this->religion,
                'marital_status' => $this->marital_status,
                'spouse' => $this->spouse,
                'physically_challenge' => $this->physically_challenge,
                'inter_emp' => $this->inter_emp,
                'job_location' => $this->job_location,
                'education' => $this->education,
                'experience' => $this->experience,
                'pan_no' => $this->pan_no,
                'aadhar_no' => $this->aadhar_no,
                'pf_no' => $this->pf_no,
                'nick_name' => $this->nick_name,
                'time_zone' => $this->time_zone,
                'biography' => $this->biography,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'linked_in' => $this->linked_in,
                'company_id' => $this->company_id,
                'is_starred' => $this->is_starred,
            ]);
            // Show success message
            session()->flash('emp_success', 'Employee updated successfully!');

            // Redirect to the employee list or any other desired route
            return redirect('/update-employee-details');
        }
    }

    public function render()
    {
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $hrCompanies = Company::where('company_id',  $empCompanyId)->get();
        // $hrDetails = Company::where('company_id', $hrEmail)->first();
        $this->companies = $hrCompanies;
        // $this->hrDetails = $hrDetails;
        return view('livewire.add-employee-details', [
            'managerName' => $this->managerName,
            'companyName' => $this->company_name,
        ]);
    }
}
