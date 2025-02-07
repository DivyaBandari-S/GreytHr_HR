<?php

namespace App\Livewire;

use App\Models\GenerateLetter;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\EmployeeDetails;

class LetterPreparePage extends Component
{
    public $currentStep = 1;
    public $showHelp = false;
    public $template_name;
    public $serial_no;
    public $authorized_signatory;
    public $employees = [];
    public $remarks;
    public $showContainer = false;
    public $selectedEmployee = null; 
    public $showSearch = true;
    public $search = '';

    protected $rules = [
        'template_name' => 'required',
    ];
    protected function getValidationRules()
{
    if ($this->currentStep == 1) {
        return [
            'template_name' => 'required',
        ];
    } elseif ($this->currentStep == 2) {
        return [
            'selectedEmployee' => 'required',
           
        ];
    }

    return []; // Default empty array if needed
}

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function  updateTemplateName()
    {
        $this->template_name = $this->template_name;
    }

    public function updatedTemplateName()
    {
        if ($this->template_name) {
            // Generate serial number format LETTER000XX#
            $lastLetter = GenerateLetter::latest()->first();
            $nextNumber = $lastLetter ? (int) substr($lastLetter->serial_no, 6, 3) + 1 : 1;
            $this->serial_no = 'LETTER' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT) . '#';
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
    public $previewLetter = null;
    public function nextStep()
    {
        $this->validate($this->getValidationRules());
        if ($this->currentStep == 2) {
            // Fetch selected employee details
            $employee = EmployeeDetails::with('personalInfo')->where('emp_id', $this->selectedEmployee)->first();
    
            if (!$employee) {
                $this->addError('selectedEmployee', 'Employee not found.');
                return;
            }
            $employeeAddress = optional($employee->personalInfo)->permanent_address ?? 'Address not available';
    
            // Generate preview content
            $this->previewLetter = [
                'template_name' => $this->template_name,
                'serial_no' => $this->serial_no,
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'employee_id' => $employee->emp_id,
                'employee_address' => $employeeAddress,
                'joining_date' => $employee->hire_date,
                'confirmation_date' => now()->format('d M Y'),
                'remarks' => $this->remarks,
                'letter_content' => $this->generateLetterContent($employee),
            ];
        }

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }
    private function generateLetterContent($employee)
{
    $date = now()->format('d M Y');

    switch ($this->template_name) {
        case 'Confirmation Letter':
            return "
                <p>{$date}</p>
                <p>To<br><strong>{$employee->first_name} {$employee->last_name}</strong><br>
                Employee ID: {$employee->emp_id}<br>
                {$employee->address}</p>
                <p><strong>Subject: Confirmation Letter</strong></p>
                <p>Dear {$employee->first_name},</p>
                <p>Further to your appointment dated {$employee->joining_date}, your employment with us is confirmed w.e.f {$date}.</p>
                <p>We wish you all the best!</p>
                <p>Regards,<br><strong>XSilica Software Solutions Pvt. Ltd.</strong></p>
            ";

        case 'Experience Letter':
            return "
                <p>{$date}</p>
                <p>To<br><strong>{$employee->first_name} {$employee->last_name}</strong><br>
                Employee ID: {$employee->emp_id}<br>
                {$employee->address}</p>
                <p><strong>Subject: Experience Certificate</strong></p>
                <p>Dear {$employee->first_name},</p>
                <p>This is to certify that {$employee->first_name} {$employee->last_name} worked with us from {$employee->joining_date} to {$date}.</p>
                <p>We appreciate your contributions and wish you success.</p>
                <p>Regards,<br><strong>XSilica Software Solutions Pvt. Ltd.</strong></p>
            ";

        case 'Offer Letter':
            return "
                <p>{$date}</p>
                <p>To<br><strong>{$employee->first_name} {$employee->last_name}</strong><br>
                Employee ID: {$employee->emp_id}<br>
                {$employee->address}</p>
                <p><strong>Subject: Offer Letter</strong></p>
                <p>Dear {$employee->first_name},</p>
                <p>We are pleased to offer you the position at our company. Your joining date is {$employee->joining_date}.</p>
                <p>Looking forward to your association with us.</p>
                <p>Regards,<br><strong>XSilica Software Solutions Pvt. Ltd.</strong></p>
            ";

        default:
            return "<p>Invalid template selected.</p>";
    }
}


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
      
        $this->loadEmployees();
    }
    public function loadEmployees()
    {

        
            $this->employees = EmployeeDetails::whereIn('employee_status', ['active', 'on-probation'])->get();
        
    }
    public function saveLetter()
    {
        if (!$this->previewLetter) {
            $this->addError('previewLetter', 'Please generate a letter before saving.');
            return;
        }
    
        GenerateLetter::create([
            'template_name' => $this->previewLetter['template_name'],
            'serial_no' => $this->serial_no,
            'authorized_signatory' => $this->authorized_signatory, 
            'employee_name' => $this->previewLetter['employee_name'],
            'employee_id' => $this->previewLetter['employee_id'],
            'employee_address' => $this->previewLetter['employee_address'],
            'joining_date' => $this->previewLetter['joining_date'],
            'confirmation_date' => $this->previewLetter['confirmation_date'],
            'letter_content' => $this->previewLetter['letter_content'],
            'remarks' => $this->remarks,
        ]);
    
        session()->flash('success', 'Letter saved successfully.');
        return redirect()->route('letter.prepare');
    }
    
    public function selectEmployee($employeeId)
    {
        // Check if the employee is already selected
        $this->selectedEmployee = $employeeId; // Change here
        Log::info('Selected Employee: ', [$this->selectedEmployee]);
        if (is_null($employeeId)) {
            $this->showSearch = true;
            $this->search = '';
            // $this->showContainer = true;
            // $this->showSearch = false;
            $this->selectedEmployee = null;
        } else {
            $this->showSearch = false;
            $this->showContainer = false;
        }
    }
    public function render()
    {
        $selectedEmployeesDetails = $this->selectedEmployee ? 
        EmployeeDetails::where('emp_id', $this->selectedEmployee)->get() : [];

  
        return view('livewire.letter-prepare-page',[
            'selectedEmployeesDetails' => $selectedEmployeesDetails,
        ]);
    }
}
