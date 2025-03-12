<?php

namespace App\Livewire;

use App\Models\GenerateLetter;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\EmployeeDetails;
use App\Models\AuthorizedSignatory;

class LetterPreparePage extends Component
{
    public $currentStep = 1;
    public $showHelp = false;
    public $template_name;
    public $serial_no;
    public $authorized_signatory;
    public $remarks;
    public $showContainer = false;

    public $showSearch = true;
    public $search = '';
    public $ctc;
    public $signatories = [];

    protected $rules = [
        'template_name' => 'required',
    ];

    protected function getValidationRules()
    {
        // Validate step 1
        if ($this->currentStep == 1) {
            return [
                'template_name' => 'required',
                'authorized_signatory' => 'required',
            ];
        }
    
        // Validate step 2
        if ($this->currentStep == 2) {
            // Validation for single employee
            if ($this->generateFor == 'single') {
                return [
                    'selectedEmployee' => 'required|exists:employee_details,emp_id', // Ensure employee exists
                    'ctc' => 'required',
                ];
            }
    
            // Validation for multiple employees
            if ($this->generateFor == 'multiple') {
                return [
                    'selectedEmployees' => 'required|array|min:1', // Ensure it's an array and at least one employee is selected
                    'selectedEmployees.*' => 'exists:employee_details,emp_id', // Ensure each employee exists
                 
                ];
            }
            if($this->template_name == 'Appointment Order'){
                return [
                'ctc' => 'required|numeric|min:0',
                ];
            }
    
            // Additional validation for "Appointment Order" template
            
        }
    
        return []; // Default empty array if no other conditions match
    }
    

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function  updateTemplateName()
    {
        $this->template_name = $this->template_name;
    }
    public function  updateAuthorizedSignatory()
    {
        $this->authorized_signatory = $this->authorized_signatory;
    }

    public function updatedTemplateName()
    {
        // Get the last generated letter
       
        $lastLetter = GenerateLetter::orderBy('id', 'desc')->first();

    
        if (!empty($lastLetter->serial_no)) {
           
            // Extract the 5-digit number from the serial number
            $extractedNumber = substr($lastLetter->serial_no, 6, 5);
            $cleanedNumber = str_replace('#', '', $extractedNumber);
           
            
            // Ensure extracted number is valid
            $intNumber = is_numeric($cleanedNumber) ? (int) $cleanedNumber : 0;
           
    
            // Increment the number
            $nextNumber = $intNumber + 1;
        } else {
            // If no existing serial number, start from 1
            $nextNumber = 1;
        }
    
        // Generate the serial number
        $this->serial_no = 'LETTER' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . '#';
    }
    

    public function hideHelp()
    {
        $this->showHelp = true;
    }
    public function showhelp()
    {
        $this->showHelp = false;
    }
    public $previewLetter = [];
    public $selected_signatory;
    public $selectedEmployeesDetails = [];
    public $selectedEmployees = [];
    public $currentEmployeeIndex = 0;
    public function nextStep()
    {
        // Log the method entry
        Log::info('Next step started.', [
            'currentStep' => $this->currentStep,
            'generateFor' => $this->generateFor,
            'selectedEmployees' => $this->selectedEmployees,
            'authorized_signatory'=> $this->authorized_signatory,
        ]);
     
    
        try {
            // Validate form data before proceeding
           
            $this->validate($this->getValidationRules());
            if ($this->currentStep == 2) {
                
                // Collect employee IDs based on the selected employee or employees
                $employeeIds = $this->generateFor === 'single' ? [$this->selectedEmployee] : $this->selectedEmployees;
    
                // Log the selected employees' IDs
                Log::info('Fetching employee details.', [
                    'employeeIds' => $employeeIds
                ]);
    
                // Fetch employee details for the selected employee(s) using a uniform query
                $employees = EmployeeDetails::with('personalInfo')
                    ->whereIn('emp_id', $employeeIds)
                    ->get(['emp_id', 'first_name', 'last_name', 'hire_date']);
    
                // Check if employees are found
                if ($employees->isEmpty()) {
                    Log::error('No employee(s) found based on selection.', [
                        'employeeIds' => $employeeIds
                    ]);
                    $this->addError('selectedEmployee', 'Employee(s) not found.');
                    return;
                }
    
                // Log the fetched employee details
                Log::info('Employee details fetched.', [
                    'employees' => $employees->toArray()
                ]);
    
                // Prepare employee details in a unified format
                $this->selectedEmployeesDetails = $employees->map(function ($emp) {
                    return [
                        'id' => $emp->emp_id,
                        'name' => $emp->first_name . ' ' . $emp->last_name,
                        'address' => optional($emp->personalInfo)->permanent_address ?? 'Address not available',
                    ];
                })->toArray();
    
                // Log the formatted employee details
                Log::info('Formatted employee details.', [
                    'selectedEmployeesDetails' => $this->selectedEmployeesDetails
                ]);
    
                // Fetch authorized signatory details
                Log::info('Fetching authorized signatory details.');
                // dd($this->authorized_signatory);
                // $authorizedDetails = AuthorizedSignatory::get();
                $authorizedDetails = AuthorizedSignatory::whereRaw(
                    "CONCAT(first_name, ' ', last_name, ' ', '(', designation, ')') = ?", [$this->authorized_signatory]
                )->first();
                
               
                $this->selected_signatory = $authorizedDetails;
    
                // Log the authorized signatory selection
                Log::info('Authorized signatory selected.', [
                    'selected_signatory' => $this->selected_signatory
                ]);
    
                // Generate preview content
                $this->previewLetter = [
                    'template_name' => $this->template_name,
                    'serial_no' => $this->serial_no,
                    'authorized_signatory' => $this->selected_signatory,
                    'employees' => json_encode($this->selectedEmployeesDetails),
                    'joining_date' => $employees->first()->hire_date ?? 'N/A',  // Use first employee's hire date
                    'confirmation_date' => now()->format('d M Y'),
                    'remarks' => $this->remarks,
                    'ctc'=>$this->ctc,
                    // 'letter_content' => $this->generateLetterContent($employees->first()),
                ];
    
                // Log the generated preview content
                Log::info('Preview content generated.', [
                    'previewLetter' => $this->previewLetter
                ]);
            }
    
            // Log before moving to next step
            Log::info('Moving to next step.', [
                'currentStep' => $this->currentStep
            ]);
            if ($this->currentStep == 3) {
              
                $this->previewLetter['employees'] = json_decode($this->previewLetter['employees'], true); 
                return view('livewire.letter-preview', [
                    'previewLetter' => $this->previewLetter,
                    'currentEmployee' => $this->previewLetter['employees'][$this->currentEmployeeIndex] ?? null
                ]); // Pass data to Blade view
            }
    
            // Move to next step if the current step is less than 3
            if ($this->currentStep < 3) {
                $this->currentStep++;
            }
    
            // Log the step change
            Log::info('Step changed.', [
                'newStep' => $this->currentStep
            ]);
        } catch (\Exception $e) {
            // Catch any exceptions and log the error
            Log::error('An error occurred in nextStep:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    
    
    public function generateForChanged($value)
    {
       $this->generateFor = $value;
       if ($this->generateFor === 'multiple') {
        
        $this->selectedEmployee = null;
        $this->selectedEmployeeDetails = []; // Clear single employee details
        $this->mount();
    }
    }
    
    

    public $generateFor = 'single';

public $employees = [];




//     private function generateLetterContent($employee)
//     {
//         $date = now()->format('d M Y');
//         $signatoryName = $this->selected_signatory->first_name . ' ' . $this->selected_signatory->last_name ?? 'Signatory Name';
//         $signatoryDesignation = $this->selected_signatory->designation ?? 'Designation';
//         $signatureImage = '';
//         if (!empty($this->selected_signatory->signature)) {
//             $signatureImage = 'data:image/png;base64,' . base64_encode($this->selected_signatory->signature);
//         }
       

//         switch ($this->template_name) {

//             case 'Appointment Order':
//                 $formattedCtc = number_format($this->ctc);
//                 return "
//                 <div class='container'>
//                     <div class='header' style='display:flex; flex-direction: column; text-align: center;'>
//                         <p>Xsilica Software Solutions Pvt. Ltd.</p>
//                         <p>Unit No - 4, Kapil Kavuri Hub, 3rd floor, Nanakramguda, <br>
//                         Serilingampally, Ranga Reddy, Telangana-500032.</p>
//                     </div>
                    
//                     <p style='text-align: left;'>{$date}</p>
                    
//                     <p>To,<br>
//                     {$employee->first_name} {$employee->last_name}<br>
//                     Employee ID: {$employee->emp_id}<br>
//                     {$employee->address}</p>
        
//                     <p class='text-align-center' style='font-size: 16px;'> <strong>Sub: Appointment Order</strong></p>
        
//                     <p><strong>Dear</strong> {$employee->first_name},</p>
        
//                     <p>We are pleased to offer you the position of <strong>Software Engineer I</strong> at Xsilica Software Solutions Pvt. Ltd., as per the discussion we had with you. Below are the details of your appointment:</p>
        
//                     <p><strong>1. Start Date:</strong> 02 Jan 2023 (Your appointment will be considered withdrawn if you do not report to our office on this date.)</p>
//                     <p><strong>2. Compensation:</strong> Your Annual Gross Compensation is Rs.<strong> {$formattedCtc}/-</strong> (subject to statutory deductions).</p>
//                     <p><strong>3. Probation Period:</strong> You will be under probation for six calendar months from your date of joining.</p>
//                     <p><strong>4. Confirmation of Employment:</strong> Upon successful completion of probation, you will be confirmed in employment.</p>
//                     <p><strong>5. Performance Reviews:</strong> You will undergo annual performance reviews and appraisals.</p>
//                     <p><strong>6. Absence from Duty:</strong> Unauthorized absence for 8 consecutive days will lead to termination of service.</p>
//                     <p><strong>7. Leave Policy:</strong> You are entitled to leave as per law and company policy, including one sick leave per month.</p>
//                     <p><strong>8. Confidentiality:</strong> Any products or materials developed during your employment will remain the property of Xsilica.</p>
//                     <p><strong>9. Termination of Employment:</strong> Voluntary resignation requires a 60-day notice period. Immediate termination can occur for consistent underperformance or providing incorrect information.</p>
        
//                     <p><strong>We are excited to have you as a part of our team and look forward to your contribution!</strong></p>
        
//                     <p>Thank you.</p>
        
//                     <div class='signature'>
//                         <p>Yours faithfully,</p>
//                         <p>For <strong>Xsilica Software Solutions Pvt. Ltd.</strong></p>
//                        <p style='font-size: 12px;'><strong>{$signatoryName}</strong></p>
//                         " . (!empty($signatureImage) ? "<img src='{$signatureImage}' alt='Signature' style='width:150px; height:auto;'>" : "") . "
//                          <p style='font-size: 12px;'> <strong>{$signatoryDesignation}</strong></p>
//             </div>

//             <div class='cc'>
           
//                     </div>
        
//                     <div class='cc'>
//                         <p>I accept the above terms and conditions.
// </p>
//                         <p>Signature: </p>
//                         <p>Date: </p>
//                     </div>
//                 </div>
//             ";






//             case 'Confirmation Letter':
//                 return "
//             <div class='container'>
//                         <div class='header' style='display:flex; flex-direction: column; text-align: center;'>
//                             <p>Xsilica Software Solutions Pvt. Ltd.</p>
//                             <p>Unit No - 4, Kapil Kavuri Hub, 3rd floor, Nanakramguda, <br>
//                             Serilingampally, Ranga Reddy, Telangana-500032.</p>
                            
//                         </div>
                        
//                         <p style='text-align: left;'>{$date}</p>
                        
//                         <p>To,<br>
//                         {$employee->first_name} {$employee->last_name}<br>
//                         Employee ID: {$employee->emp_id}<br>
//                         {$employee->address}</p>
        
//                         <p class='text-align-center' style='font-size: 16px;'> <strong>Sub: Confirmation Letter</strong></p>
        
//                         <p><strong>Dear</strong> {$employee->first_name},</p>
        
//                         <p>Further to your appointment/joining dated <strong>{$employee->joining_date}</strong>, 
//                         your employment with us is confirmed with effect from <strong>{$date}</strong>.</p>
        
//                         <p>All the terms mentioned in the Offer/Appointment letter will remain unaltered.</p>
        
//                         <p>We thank you for your contribution so far and hope that you will continue to 
//                         perform equally well in the future.</p>
        
//                         <p><strong>We wish you the best of luck!</strong></p>
                        
        
//                         <p>Thank you.</p>
        
//                         <div class='signature'>
//                         <p>Yours faithfully,</p>
//                         <p>For <strong>Xsilica Software Solutions Pvt. Ltd.</strong></p>
//                        <p style='font-size: 12px;'><strong>{$signatoryName}</strong></p>
//                         " . (!empty($signatureImage) ? "<img src='{$signatureImage}' alt='Signature' style='width:150px; height:auto;'>" : "") . "
//                          <p style='font-size: 12px;'> <strong>{$signatoryDesignation}</strong></p>
//             </div>
        
//                         <div class='cc'>
//                             <p><strong>Cc:</strong></p>
//                             <p>1. Reporting Manager</p>
//                             <p>2. Personal File</p>
//                         </div>
//                     </div>
//                 ";

//             default:
//                 return "<p>Invalid template selected.</p>";
//         }
//     }


    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
    public function searchFilter()
    {
        if ($this->search !== '') {
            $this->showContainer = true; // Show the container when the search is triggered

            // Search with the existing term
            $this->employees = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('emp_id', 'like', '%' . $this->search . '%'); // Ensure `like` for partial match
            })
                // ->where(function ($query) {
                //     if ($this->employeeType === 'active') {
                //         $query->whereIn('employee_status', ['active', 'on-probation']);
                //     } else {
                //         $query->whereIn('employee_status', ['terminated', 'resigned']);
                //     }
                // })
                ->get();

            // If no results found, the container should still be shown to display the message
            if (empty($this->employees)) {
                // Handle case where no employees are found
            }
        } else {
            // If search term is empty, hide the container and reload the employees
            $this->showContainer = false; // Hide the container
            $this->loadEmployees(); // Reload current employees
        }
    }
    public function mount()
    {
        $this->signatories = AuthorizedSignatory::where('is_active', 1)->get(); // Fetch active signatories
        $this->loadEmployees();
        $this->employees = EmployeeDetails::with('personalInfo')
        ->whereNotIn('employee_status', ['terminated', 'resigned'])
        ->get()->map(function ($employee) {
            return [
                'id' => $employee->emp_id,
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'address' => optional($employee->personalInfo)->permanent_address, // Avoids errors if address is null
            ];
        })->toArray();
        
        
     
        
    }
    public function nextEmployee()
    {
        // Navigate to the next employee letter
        if ($this->currentEmployeeIndex < count($this->previewLetter['employees']) - 1) {
            $this->currentEmployeeIndex++;
        }
    }

    public function previousEmployee()
    {
        // Navigate to the previous employee letter
        if ($this->currentEmployeeIndex > 0) {
            $this->currentEmployeeIndex--;
        }
    }
    public function loadEmployees()
    {

        
        $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();

    }
    public function saveLetter()
    {
        // Check if previewLetter is available
        if (!$this->previewLetter) {
            $this->addError('previewLetter', 'Please generate a letter before saving.');
            return;
        }
    
        // Get the authorized signatory from the database based on the selection
        $signatory = AuthorizedSignatory::whereRaw("CONCAT(first_name, ' ', last_name, ' (', designation, ')') = ?", [$this->authorized_signatory])->first();
    
        if (!$signatory) {
            $this->addError('authorized_signatory', 'Invalid authorized signatory selected.');
            return;
        }
    
        // Prepare the authorized signatory details in JSON format
        $authorizedSignatoryData = [
            'name' => $signatory->first_name . ' ' . $signatory->last_name,
            'designation' => $signatory->designation,
            'signature' => base64_encode($signatory->signature), // Convert BLOB to Base64 for storage
        ];
    
        // Decode the employees JSON string into an array
        $employees = json_decode($this->previewLetter['employees'], true); // Decode as an associative array
    
        // Check if the decoding was successful and if it's an array
        if (!is_array($employees)) {
            $this->addError('employees', 'Invalid employee data format.');
            return;
        }
    
        // Get the last generated letter to determine the next serial number
        $lastLetter = GenerateLetter::orderBy('id', 'desc')->first();

        if (!empty($lastLetter->serial_no)) {
            // Extract the 4-digit number from the serial number
            $extractedNumber = substr($lastLetter->serial_no, 6, 4);
            $cleanedNumber = str_replace('#', '', $extractedNumber);
            $intNumber = is_numeric($cleanedNumber) ? (int) $cleanedNumber : 0;
            // Increment the number by 1
            $nextNumber = $intNumber + 1;
        } else {
            // If no existing serial number, start from 1
            $nextNumber = 1;
        }
    
        // Loop through each employee and save a separate record with the incremented serial number
        foreach ($employees as $index => $employee) {
            // Generate the serial number for each employee
            $serialNumber = 'LETTER' . str_pad($nextNumber + $index, 4, '0', STR_PAD_LEFT) . '#';
    
            // Save a separate letter record for each employee
            GenerateLetter::create([
                'template_name' => $this->previewLetter['template_name'],
                'serial_no' => $serialNumber, // Incremented serial number for each employee
                'authorized_signatory' => json_encode($authorizedSignatoryData), // Store as JSON
                'employees' => json_encode([$employee]), // Store each employee as a separate entry
                'joining_date' => $this->previewLetter['joining_date'],
                'confirmation_date' => \Carbon\Carbon::parse($this->previewLetter['confirmation_date'])->format('Y-m-d'), // Format confirmation date
                'remarks' => $this->remarks, // Remarks field
                'ctc' => $this->ctc, // CTC field
                'status' => 'Not Published', // Set initial status to 'Not Published'
            ]);
        }
    
        // Flash a success message and redirect to the generate letter page
        session()->flash('success', 'Letters saved successfully.');
        return redirect()->route('generate-letter');
    }
    
    public $selectedEmployee;
    public $selectedEmployeeDetails = []; // Store selected employee details
    
    public function selectEmployee($employeeId)
    {
        // Log the employee ID for debugging
        Log::info('Selecting Employee: ', [$employeeId]);
    
        if (is_null($employeeId)) {
          
            // If the employee is deselected, clear the selected employee details
            $this->selectedEmployee = null;
            $this->selectedEmployeeDetails = [];
            $this->showSearch = true;
            $this->showContainer = true;
        } else {
            // If an employee is selected, store the details in the array
            $this->selectedEmployee = $employeeId;
            $this->showSearch = false;
            $this->showContainer = false;
    
            // Fetch employee details and format them
            $employee = EmployeeDetails::with('personalInfo')
                ->where('emp_id', $this->selectedEmployee)
                ->first(); // Fetch first match based on emp_id
               
    
            // Check if the employee is found
            if ($employee) {
                
                $this->selectedEmployeeDetails = [
                    'id' => $employee->emp_id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'address' => optional($employee->personalInfo)->permanent_address ?? 'Address not available',
                ];
               
            } else {
                Log::error('Employee not found: ', [$employeeId]);
                $this->selectedEmployeeDetails = []; // Clear the details if not found
            }
        }
    
        // Log the selected employee details for debugging
        Log::info('Selected Employee Details:', [$this->selectedEmployeeDetails]);
    }
    
    public function render()
    {
        // $selectedEmployeesDetails = $this->selectedEmployee ?
        //     EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];


        return view('livewire.letter-prepare-page', [
            // 'selectedEmployeesDetails' => $selectedEmployeesDetails,
            'selectedEmployeeDetails' => $this->selectedEmployeeDetails,
                ]);
    }
}
