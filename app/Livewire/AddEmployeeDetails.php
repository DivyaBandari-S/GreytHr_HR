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
use App\Models\EmpPersonalInfo;
use App\Models\EmpParentDetails;
use App\Models\EmpSpouseDetails;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\EmpDepartment;
use App\Models\EmpSubDepartments;
use App\Models\EmpBankDetail;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AddEmployeeDetails extends Component
{
    use WithFileUploads;

    public $emp_id = 'XSS-0564';
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
    public $present_address;
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
        'A+',
        'A-',
        'B+',
        'B-',
        'AB+',
        'AB-',
        'O+',
        'O-'
    ];
    public $blood_group = '';
    public $nationality;
    public $religion;
    public $marital_status;
    public $spouse;
    public $physically_challenge;
    public $inter_emp;
    public $job_location;
    public $education = [
        ['level' => '', 'institution' => '', 'course_name' => '', 'year_of_passing' => '', 'percentage' => '']
    ];
    public $experience = [
        ['company_name' => '',  'start_date' => '', 'end_date' => '', 'skills' => '', 'description' => '']
    ];
    public $pan_no;
    public $aadhar_no;
    public $pf_no;
    public $passport_no;
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
    public $signature_image;
    public $father_first_name;
    public $father_last_name;
    public $mother_first_name;
    public $mother_last_name;
    public $father_dob;
    public $mother_dob;
    public $father_address;
    public $mother_address;
    public $father_city;
    public $mother_city;
    public $father_state;
    public $mother_state;
    public $father_country;
    public $mother_country;
    public $father_email;
    public $mother_email;
    public $father_phone;
    public $mother_phone;
    public $father_occupation;
    public $mother_occupation;
    public $father_image;
    public $father_image_binary;
    public $mother_image_binary;
    public $mother_image;
    public $father_blood_group = '';
    public $mother_blood_group = '';
    public $father_nationality;
    public $mother_nationality;
    public $father_religion;
    public $mother_religion;

    public $spouse_first_name;
    public $spouse_last_name;
    public $spouse_gender;
    public $spouse_qualification;
    public $spouse_profession;
    public $spouse_dob;
    public $spouse_nationality;
    public $spouse_bld_group = '';
    public $spouse_adhar_no;
    public $spouse_pan_no;
    public $spouse_religion;
    public $spouse_email;
    public $spouse_address;
    public $children = [
        ['name' => '', 'gender' => '']
    ];
    public $spouse_image;

    public $bank_name;
    public $bank_branch;
    public $account_number;
    public $ifsc_code;
    public $bank_address;

    public  $selectedEmployees;
    public $currentStep = 1;
    public $parentscurrentStep = 1;


    protected function validationRules()
    {
        return $this->rules[$this->currentStep];
    }

    public function nextPage()

    {
        if ($this->currentStep != 6 && $this->currentStep != 7) {
            $this->validate($this->validationRules());
        }

        if ($this->currentStep == 1) {
            $this->currentStep++;
        } elseif ($this->currentStep == 2) {
            $this->registerEmployeeDetails();
        } elseif ($this->currentStep == 3) {
            $this->currentStep++;
        } elseif ($this->currentStep == 4) {
            $this->registerEmployeeJobDetails();
        } elseif ($this->currentStep == 5) {
            $this->registerEmployeeParentsDetails();
        } elseif ($this->currentStep == 6) {

            $this->registerEmployeeSpouseDetails();
        } elseif ($this->currentStep == 7) {
            $this->registerEmployeeBankDetails();
        } elseif ($this->currentStep == 8) {

            $this->addEducationDetails();
        }elseif ($this->currentStep == 9) {

            $this->addExperienceDetails();
        }
       else {
        }
    }

    public function previousPage()
    {
        $this->currentStep--;
    }
    public function parentsnextPage()
    {
        $this->validate($this->validationRules());
        $this->parentscurrentStep++;
    }
    public function parentspreviousPage()
    {
        $this->parentscurrentStep--;
    }

    public function addChild()
    {
        $this->children[] = ['name' => '', 'dob' => '', 'gender' => ''];
    }

    public function removeChild($index)
    {
        unset($this->children[$index]);
        $this->children = array_values($this->children); // Reindex the array
    }


    public function addEducation()
    {
        $this->education[] = ['level' => '', 'institution' => '', 'course_name' => '', 'year_of_passing' => '', 'percentage_or_cgpa' => ''];
    }
    public function addExperience()
    {
        $this->experience[] = ['company_name' => '', 'skills' => '', 'start_date' => '', 'end_date' => '', 'description' => ''];
    }
    public function removeExperience($index)
{
    unset($this->experience[$index]);
    $this->experience = array_values($this->experience); // Reindex the array
}

    protected $rules = [
        1 => [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|size:10|different:alternate_mobile_number',
            'company_email' => 'required|email|different:email',
            'gender' => 'required|in:Male,Female',
            'image' => 'nullable|image|max:1024',
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
        3 =>[
            'date_of_birth' => 'required|date',
            'blood_group' => 'required',
            'aadhar_no' => 'required|unique:emp_personal_infos,adhar_no',
            'religion' => 'required',
            'nationality' => 'required',
            'marital_status' => 'required',
            'email' => 'required|email|different:company_email',
            'alternate_mobile_number' => '|different:mobile_number',
        ],
        4 => [
            'address' => 'required|string|max:255',
            'present_address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
        ],
        5 => [
            'father_first_name' => 'required|string|max:255',
            'father_last_name' => 'required|string|max:255',
            'father_blood_group' => 'required',
            'mother_first_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'mother_blood_group' => 'required',
            // 'father_image' => 'nullable|image|max:1024',
            // 'mother_image' => 'nullable|image|max:1024',
        ],
        8 => [
            'education.*.level' => 'required|string|in:Bachelors,Masters,Intermediate',
            'education.*.institution' => 'required|string|max:255',
            'education.*.year_of_passing' => 'required|digits:4|integer',
            'education.*.course_name' => 'required|string',
            'education.*.percentage' => 'nullable|numeric|min:0|max:100',
        ],
        9=>[
            'experience.*.company_name' => 'required|string|max:255',
            'experience.*.skills' => 'required|string|max:500',
            'experience.*.start_date' => 'required|date|before_or_equal:today',
            'experience.*.end_date' => 'nullable|date|after_or_equal:experience.*.start_date',
            'experience.*.description' => 'nullable|string|max:1000',
        ]
    ];

    protected $messages = [
        'first_name.required' => 'The first name is required.',
        'first_name.string' => 'The first name must be a valid string.',
        'last_name.required' => 'The last name is required.',
        'last_name.string' => 'The last name must be a valid string.',
        'mobile_number.required' => 'The mobile number is required.',
        'mobile_number.size' => 'The mobile number must be exactly 10 digits.',
        'mobile_number.different' => 'The mobile number must be different from the alternate mobile number.',
        'company_email.required' => 'The company email is required.',
        'company_email.email' => 'The company email must be a valid email address.',
        'company_email.different' => 'The company email must be different from the personal email.',
        'gender.required' => 'Please select a gender.',
        'gender.in' => 'The selected gender is invalid.',
        'hire_date.required' => 'The hire date is required.',
        'hire_date.date' => 'The hire date must be a valid date.',
        'employee_type.required' => 'Please select an employee type.',
        'employee_type.in' => 'The selected employee type is invalid.',
        'employee_status.required' => 'Please select an employee status.',
        'employee_status.in' => 'The selected employee status is invalid.',
        'department_id.required' => 'The department is required.',
        'department_id.string' => 'The department must be a valid string.',
        'sub_department_id.required' => 'The sub-department is required.',
        'sub_department_id.string' => 'The sub-department must be a valid string.',
        'job_title.required' => 'The job title is required.',
        'job_title.string' => 'The job title must be a valid string.',
        'job_location.required' => 'The job location is required.',
        'job_location.string' => 'The job location must be a valid string.',
        'company_id.required' => 'The company ID is required.',
        'company_id.exists' => 'The selected company ID does not exist.',
        'manager_id.required' => 'The manager ID is required.',
        'date_of_birth.required' => 'The date of birth is required.',
        'date_of_birth.date' => 'The date of birth must be a valid date.',
        'blood_group.required' => 'The blood group is required.',
        'aadhar_no.required' => 'The Aadhar number is required.',
        'aadhar_no.unique' => 'This Aadhar number is already taken.',
        'religion.required' => 'The religion is required.',
        'nationality.required' => 'The nationality is required.',
        'marital_status.required' => 'The marital status is required.',
        'email.required' => 'The email address is required.',
        'email.email' => 'The email address must be a valid email address.',
        'email.different' => 'The email address must be different from the company email.',
        'alternate_mobile_number.different' => 'The alternate mobile number must be different from the mobile number.',
        'address.required' => 'The address is required.',
        'present_address.required' => 'The present address is required.',
        'state.required' => 'The state is required.',
        'postal_code.required' => 'The postal code is required.',
        'postal_code.string' => 'The postal code must be a valid string.',
        'country.required' => 'The country is required.',
        'father_first_name.required' => 'The father\'s first name is required.',
        'father_last_name.required' => 'The father\'s last name is required.',
        'father_blood_group.required' => 'The father\'s blood group is required.',
        'mother_first_name.required' => 'The mother\'s first name is required.',
        'mother_last_name.required' => 'The mother\'s last name is required.',
        'mother_blood_group.required' => 'The mother\'s blood group is required.',
    ];


    public function registerEmployeeDetails()
    {
        try {
            $this->validate($this->validationRules());

            if ($this->image) {
                $imageBinary = base64_encode(file_get_contents($this->image->getRealPath()));
            } else {
                $imageBinary = 'Null';
            }

            $report_to = EmployeeDetails::where('emp_id', $this->manager_id)->value('manager_id');


            EmployeeDetails::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'gender' => $this->gender,
                    'email' => $this->company_email,
                    'company_id' => $this->company_id,
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
                    'image' => $imageBinary,
                    'emp_domain' => $this->emp_domain,
                    'referral' => $this->referrer,
                    'probation_Period' => $this->probation_period,
                    'confirmation_date' => $this->confirmation_date,
                    'shift_type' => $this->shift_type,
                    'shift_start_time' => $this->shift_start_time,
                    'shift_end_time' => $this->shift_end_time,
                ]
            );

            $this->emp_id = EmployeeDetails::where('email', $this->company_email)->value('emp_id');


            session()->flash('emp_success', 'Employee registered successfully!');

            // Clear the form fields
            $this->currentStep++;
        } catch (\Exception $e) {
            Log::error('Error in registerEmployeeDetails method: ' . $e->getMessage());
            throw $e;
        }
    }

    public function registerEmployeeJobDetails()
    {

        try {
            EmpPersonalInfo::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'date_of_birth' => $this->date_of_birth,
                    'gender' => $this->gender,
                    'blood_group' => $this->blood_group,
                    'signature' => $this->signature_image,
                    'nationality' => $this->nationality,
                    'religion' => $this->religion,
                    'marital_status' => $this->marital_status,
                    'physically_challenge' => $this->physically_challenge,
                    'email' => $this->email,
                    'mobile_number' => $this->mobile_number,
                    'alternate_mobile_number' => $this->alternate_mobile_number,
                    'city' => $this->city,
                    'state' => $this->state,
                    'postal_code' => $this->postal_code,
                    'country' => $this->country,
                    'company_name' => $this->company_name,
                    'present_address' => $this->present_address,
                    'permenant_address' => $this->address,
                    'passport_no' => $this->passport_no,
                    'pan_no' => strtoupper($this->pan_no),
                    'adhar_no' => $this->aadhar_no,
                    'pf_no' => $this->pf_no,
                    'nick_name' => $this->nick_name,
                    'facebook' => $this->facebook,
                    'twitter' => $this->twitter,
                    'linked_in' => $this->linked_in,
                    // 'qualification'=>json_encode($this->education),
                    // 'experience'=>json_encode($this->experience),
                ]
            );
            $this->currentStep++;
            session()->flash('emp_success', 'Employee personal details added successfully!');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function registerEmployeeParentsDetails()
    {
        try {
            if ($this->father_image) {
                $this->father_image_binary = base64_encode(file_get_contents($this->father_image->getRealPath()));
            } elseif ($this->mother_image) {
                $this->mother_image_binary = base64_encode(file_get_contents($this->mother_image->getRealPath()));
            } else {
                $this->father_image_binary = '';
                $this->mother_image_binary = '';
            }
            EmpParentDetails::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'father_first_name' => $this->father_first_name,
                    'father_last_name' => $this->father_last_name,
                    'mother_first_name' => $this->mother_first_name,
                    'mother_last_name' => $this->mother_last_name,
                    'father_dob' => $this->father_dob,
                    'mother_dob' => $this->mother_dob,
                    'father_address' => $this->father_address,
                    'mother_address' => $this->mother_address,
                    // 'father_city'=>$this->father_city,
                    // 'mother_city'=>$this->mother_city,
                    // 'father_state'=>$this->father_state,
                    // 'mother_state'=>$this->mother_state,
                    // 'father_country'=>$this->father_country,
                    // 'mother_country'=>$this->mother_country,
                    'father_email' => $this->father_email,
                    'mother_email' => $this->mother_email,
                    'father_phone' => $this->father_phone,
                    'mother_phone' => $this->mother_phone,
                    'father_occupation' => $this->father_occupation,
                    'mother_occupation' => $this->mother_occupation,
                    'father_image' => $this->father_image,
                    'mother_image' => $this->mother_image,
                    'father_blood_group' => $this->father_blood_group,
                    'mother_blood_group' => $this->mother_blood_group,
                    'father_nationality' => $this->father_nationality,
                    'mother_nationality' => $this->mother_nationality,
                    'father_religion' => $this->father_religion,
                    'mother_religion' => $this->mother_religion,
                ]
            );
            $this->currentStep++;
            session()->flash('emp_success', 'Employee parents details addeed successfully!');
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function registerEmployeeSpouseDetails()
    {
        try {
            // if ($this->father_image) {
            //     $this->father_image_binary = base64_encode(file_get_contents($this->father_image->getRealPath()));
            // } elseif ($this->mother_image) {
            //     $this->mother_image_binary = base64_encode(file_get_contents($this->mother_image->getRealPath()));
            // } else {
            //     $this->father_image_binary = '';
            //     $this->mother_image_binary = '';
            // }
            EmpSpouseDetails::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'name' => $this->spouse_first_name & $this->spouse_last_name,
                    'gender' => $this->spouse_gender,
                    'qualification' => $this->spouse_qualification,
                    'profession' => $this->spouse_profession,
                    'dob' => $this->spouse_dob,
                    'nationality' => $this->spouse_nationality,
                    'bld_group' => $this->spouse_bld_group,
                    'adhar_no' => $this->spouse_adhar_no,
                    'pan_no' => $this->spouse_pan_no,
                    'religion' => $this->spouse_religion,
                    'email' => $this->spouse_email,
                    'address' => $this->spouse_address,
                    'children' => json_encode($this->children),
                    // 'image'=>,
                ]
            );
            $this->currentStep++;
            session()->flash('emp_success', 'Employee Spouse details addeed successfully!');
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function registerEmployeeBankDetails()
    {

        try {

            EmpBankDetail::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'bank_name' => $this->bank_name,
                    'bank_branch' => $this->bank_branch,
                    'account_number' => $this->account_number,
                    'ifsc_code' => $this->ifsc_code,
                    'bank_address' => $this->bank_address,

                ]
            );
            // $this->currentStep++;
            session()->flash('emp_success', 'Employee Bank details addeed successfully!');
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function addEducationDetails()
    {
        try {
            EmpPersonalInfo::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    'qualification' => json_encode($this->education),

                ]
            );
            $this->currentStep++;
            session()->flash('emp_success', 'Employee Educational details added successfully!');
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function addExperienceDetails()
    {
        try {
            EmpPersonalInfo::updateorCreate(
                ['emp_id' => $this->emp_id],
                [
                    // 'qualification' => json_encode($this->education),
                    'experience'=>json_encode($this->experience),
                ]
            );
            // $this->currentStep++;
            session()->flash('emp_success', 'Employee Experience details added successfully!');
        } catch (Exception $e) {
            throw $e;
        }
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
        $hrId = auth()->guard('hr')->user()->emp_id;
        $employee = EmployeeDetails::find($hrId);
        $empCompanyId = $employee->company_id;
        $employeeCompanyName = Company::find($empCompanyId)->company_name;


        $this->companieIds = Company::where('company_id', $empCompanyId)->get();
        //    dd( $this->companieIds);
        $companieIdsLength = count($this->companieIds);
        if ($companieIdsLength == 1) {
            $this->company_id = $this->companieIds->first()->company_id;
        }
        $this->departments = EmpDepartment::where('company_id', $empCompanyId)->get();

        // if ($empId) {
        //     try {
        //         $decryptedEmpId = Crypt::decrypt($empId);

        //         $this->editEmployee($decryptedEmpId);
        //     } catch (DecryptException $e) {
        //         // Handle the error appropriately
        //         session()->flash('error_message', 'Invalid Employee ID.');
        //         return redirect()->route('some-error-route'); // Or handle the error as needed
        //     }
        // }
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
