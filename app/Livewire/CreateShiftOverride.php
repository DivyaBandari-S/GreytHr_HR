<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Mail\ShiftOverrideMail;
use App\Models\EmployeeDetails;
use App\Models\ShiftOverride;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateShiftOverride extends Component
{
    public $searchEmployee=0;

    public $employeeEmail;
    public $abbreviatedshift;
    public $validationErrors = [];
    public $reason;

    public $to_date;
    public $shift;
    public $from_date;
    public $searchTerm = '';
    public $employees;

    public $index=0;
    public $selectedEmployeeId='';

    public $existingJson;
    public $selectedEmployeeFirstName;

    public $messageContent;
    public $selectedEmployeeLastName;
    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }
    public function updatefromDate()
    {
        $this->from_date=$this->from_date;
    }

    public function closeEmployeeBox()
    {
        $this->searchEmployee=0;
       
       
    }
    // public function sendNotifications()
    // {
    //     // Get employees who have emergency contact and a valid phone number
    //     $employees = EmployeeDetails::whereIn('emp_id',['XSS-0477','XSS-0478']) // Ensure phone number is present
    //         ->get();
        
    //     foreach ($employees as $employee) {
    //         // You may need to check the carrier to choose the right gateway
    //         $gateway = $this->getSmsGateway($employee->emergency_contact);
           
    //         if ($gateway) {
    //             $this->sendSmsViaEmail($gateway, $employee->emergency_contact, $this->messageContent);
    //         }
    //     }

    //     session()->flash('message', 'SMS notifications sent to all employees with emergency contacts.');
    // }

    // Function to find the carrier's email-to-SMS gateway based on the phone number
    // public function getSmsGateway($phoneNumber)
    // {
    //     // Example: Find the carrier based on the phone number's first digits
    //     // This is just a simple example, you may need a more sophisticated lookup system
    //     $prefix = substr($phoneNumber, 0, 3);

    //     if ($prefix == '960') {
    //         return 'tmomail.net'; // Example for T-Mobile
    //     } elseif ($prefix == '888') {
    //         return 'txt.att.net'; // AT&T (Example)
    //     } elseif ($prefix == '999') {
    //         return 'vtext.com'; // Verizon (Example)
    //     } else {
    //         return null; // Return null if no match is found
    //     }
      
    // }

    // Function to send SMS through the email-to-SMS gateway
    // public function sendSmsViaEmail($gateway, $phoneNumber, $message)
    // {

    //     $carrierGateway = $this->getSmsGateway($phoneNumber);
        
    //     if ($carrierGateway) {
    //         // Construct the email address for the SMS gateway
    //         $email = $phoneNumber . '@' . $carrierGateway;
            
    //         // Make sure $message contains valid content
    //         if (empty($message)) {
    //             // Provide a default message if empty
                
    //             $message = 'You have an emergency contact message.';
    //         }
            
    //         // Send the message using Laravel's Mail facade
    //         dd(Mail::raw('This is a test email', function ($message) {
    //             $message->to('your-email@example.com')->subject('Test Email');
    //         }));
    //         Mail::raw('This is a test email', function ($message) {
    //             $message->to('your-email@example.com')->subject('Test Email');
    //         });
    //     } else {
    //         // Handle the case where no carrier is found
    //         return 'Carrier not found for phone number.';
    //     }
    // }
    public function updateShift()
    {
        $this->shift=$this->shift;
        if($this->shift=='Afternoon Shift')
        {
            $this->abbreviatedshift='AS';
        }
        elseif($this->shift=='Evening Shift')
        {
            $this->abbreviatedshift='ES';
        }
        elseif($this->shift=='General Shift')
        {
            $this->abbreviatedshift='GS';
        }
    }

    public function updateReason()
    {
        $this->reason=$this->reason;
    }
    public function updatetoDate()
    {
        $this->to_date=$this->to_date;
    }
    public function updated($propertyName)
    {
        // Remove validation message as soon as user enters value
        $this->resetErrorBag($propertyName);
    }
    public function submitAttendanceException()
    {
        
            Log::info('Welcome to submitAttendanceException method');
            // Log when the method is called
            Log::info('submitAttendanceException called', [
                'selectedEmployeeId' => $this->selectedEmployeeId,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'shift' => $this->shift,
                'reason' => $this->reason,
            ]);

            if (empty($this->selectedEmployeeId)) {
                FlashMessageHelper::flashError('Please select the employee.');
                Log::warning('No employee selected');
                return;
            }

            // Validation rules and messages
            $rules = [
                'from_date' => 'required|date|before_or_equal:to_date',
                'to_date' => 'required|date|after_or_equal:from_date',
                'shift' => 'required|string',
                'reason' => 'required|string|max:255',
                'selectedEmployeeId' => 'required',
            ];

            $messages = [
                'from_date' => 'From Date is required',
                'to_date' => 'To Date is required',
                'shift.required' => 'The shift field is required.',
                'reason.required' => 'Please provide a reason.',
                'reason.max' => 'The reason may not exceed 255 characters.',
            ];

            // Only validate `from_date` if it's entered
            if (!empty($this->from_date)) {
                $rules['from_date'] = 'required|date|before_or_equal:to_date';
                $messages['from_date.required'] = 'The From Date field is required.';
                $messages['from_date.date'] = 'The From Date must be a valid date.';
                $messages['from_date.before_or_equal'] = 'The From Date must be before or equal to the To Date.';
            }

            // Only validate `to_date` if it's entered
            if (!empty($this->to_date)) {
                $rules['to_date'] = 'required|date|after_or_equal:from_date';
                $messages['to_date.required'] = 'The To Date field is required.';
                $messages['to_date.date'] = 'The To Date must be a valid date.';
                $messages['to_date.after_or_equal'] = 'The To Date must be after or equal to the From Date.';
            }

            // Log validation rules
            Log::info('Validation Rules', $rules);
            Log::info('Validation Messages', $messages);

            // Perform validation
            try {
                $this->validate($rules, $messages);
                Log::info('Validation passed');
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Validation failed', ['errors' => $e->errors()]);
                FlashMessageHelper::flashError('Validation failed: ' . implode(', ', $e->errors()));
                return;
            }

            // Creating ShiftOverride entry
            try {
                ShiftOverride::create([
                    'emp_id' => $this->selectedEmployeeId,
                    'from_date' => $this->from_date,
                    'to_date' => $this->to_date,
                    'shift_type' => $this->shift,
                    'reason' => $this->reason,
                ]);
                Log::info('ShiftOverride entry created successfully', [
                    'emp_id' => $this->selectedEmployeeId,
                    'from_date' => $this->from_date,
                    'to_date' => $this->to_date,
                    'shift_type' => $this->shift,
                    'reason' => $this->reason,
                ]);
            } catch (\Exception $e) {
                Log::error('Error creating ShiftOverride', ['exception' => $e->getMessage()]);
                FlashMessageHelper::flashError('Error creating ShiftOverride');
                return;
            }

            // Fetch employee data for updating shift_entries_from_hr
            try {
                $employee = DB::table('employee_details')->where('emp_id', $this->selectedEmployeeId)->first();
                $this->existingJson = json_decode($employee->shift_entries ?? '{}', true);

                // Log the existing JSON data
                Log::debug('Existing shift entries', ['existingJson' => $this->existingJson]);

                $newIndex = count($this->existingJson);
                $this->existingJson[$newIndex] = [
                    'from_date' => $this->from_date,
                    'to_date' => $this->to_date,
                    'shift_type' => $this->abbreviatedshift,
                ];

                DB::table('employee_details')
                    ->where('emp_id', $this->selectedEmployeeId)
                    ->update(['shift_entries' => json_encode($this->existingJson)]);

                Log::info('Employee shift entries updated successfully', [
                    'emp_id' => $this->selectedEmployeeId,
                    'shift_entries' => $this->existingJson
                ]);
            } catch (\Exception $e) {
                Log::error('Error updating employee shift entries', ['exception' => $e->getMessage()]);
                FlashMessageHelper::flashError('Error updating employee shift entries');
                return;
            }

            
            // Success message
            $successMessage = "Shift Override is saved successfully for the employee(s) over a period from " . Carbon::parse($this->from_date)->format('jS F Y') . " to " . Carbon::parse($this->to_date)->format('jS F Y') . " for the shift " . $this->shift;
            FlashMessageHelper::flashSuccess($successMessage);
            $employeeDetails=EmployeeDetails::where('emp_id',$this->selectedEmployeeId)->first();
            $this->employeeEmail=$employeeDetails->email;
            $details = [
                'shiftEntries' => $this->existingJson,
                'employee_id' => $this->selectedEmployeeId,
                'employee_name' => $employeeDetails->first_name . ' ' . $employeeDetails->last_name,
            ];
            Mail::to($this->employeeEmail)->send(new ShiftOverrideMail($details));
            // Log the success message
            Log::info($successMessage);

            return redirect()->route('shift-override');
            
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }
    public function closeAttendanceException()
    {
        return redirect()->route('shift-override');
    }
    public function updateselectedEmployee($empId)
    {
    
        $this->selectedEmployeeId=$empId;
      
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchEmployee=0;
    }

    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
    }
    public function getEmployeesByType()
    {
       
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
        
        if (!empty($this->searchTerm)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            });
        }
    
        // Get the filtered employees
        return $query->get();
    
    }
    public function render()
    {
        $this->employees = $this->getEmployeesByType();
        return view('livewire.create-shift-override');
    }
}
