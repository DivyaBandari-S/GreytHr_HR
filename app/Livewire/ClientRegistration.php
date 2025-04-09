<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ClientDetails;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Helpers\FlashMessageHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientRegistration extends Component
{
    use WithFileUploads;

    // Client data properties
    public $client_id, $client_name, $hr_name, $client_address1, $client_address2;
    public $client_registration_date, $client_logo, $password, $contact_email, $contact_phone;
    protected $messages = [
        'client_name.unique' => 'This client name has already been taken. Please choose another name.',
    ];
    

    // Validation rules
    protected $rules = [
       'client_name' => 'required|string|max:100|unique:client_details,client_name',

        // 'hr_name' => 'required|string|max:100',
        'client_address1' => 'required|string',
        'client_address2' => 'nullable|string',
        // 'client_registration_date' => 'required|date',
        'password' => 'required|string|min:8',
        'contact_email' => 'required|email|max:100',
        'contact_phone' => 'required|string|max:20',
        'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Logo validation
    ];
    public function updateClientLogo()
    {
    
        if( $this->client_logo ) {
           
        
            $this->validate([
                'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $this->client_logo = base64_encode(file_get_contents($this->client_logo->getRealPath())); // If it's a file upload
    
        }
    }
   // In the submitForm() method, you can build the rules dynamically
   public function submitForm()
   {
       // Dynamically set validation rules for client_name if it's an update or create
       $rules = [
           'client_name' => 'required|string|max:100',
           'client_address1' => 'required|string',
           'client_address2' => 'nullable|string',
           'password' => 'nullable|string|min:8', // Optional for edit
           'contact_email' => 'required|email|max:100',
           'contact_phone' => 'required|string|max:20',
           'client_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Logo validation
       ];
   
       // Add unique rule for client_name if it's a create operation
       if (!$this->client_id) {
           $rules['client_name'] .= '|unique:client_details,client_name';
       }
   
       // Validate the input data
       $this->validate($rules);
   
       try {
           // Check if it's an update or create operation
           if ($this->client_id) {
               $client = ClientDetails::find($this->client_id);
   
               if ($this->client_logo) {
                   $logoBinary = base64_decode($this->client_logo);
               }
   
               $client->update([
                   'client_name' => $this->client_name,
                   'client_address1' => $this->client_address1,
                   'client_address2' => $this->client_address2,
                   'client_logo' => isset($logoBinary) ? $logoBinary : $client->client_logo,
                   'password' => $this->password ? bcrypt($this->password) : $client->password, // Only update password if provided
                   'contact_email' => $this->contact_email,
                   'contact_phone' => $this->contact_phone,
               ]);
   
               if ($client->client_logo) {
                   $this->client_logo = base64_encode($client->client_logo); // Convert the new signature to base64 for display
               }
   
               FlashMessageHelper::flashSuccess('Client updated successfully.');
           } else {
               // Handle logo upload for create operation
               if ($this->client_logo) {
                   $clientBinaryData = file_get_contents($this->client_logo->getRealPath());
               }
   
               // Create new client
               $hremployeeId = auth()->guard('hr')->user()->hr_emp_id;
               $employeeName = DB::table('hr_employees')->where('hr_emp_id', $hremployeeId)->value('employee_name');
   
               $lastClient = ClientDetails::latest('client_id')->first();
               $newClientId = $lastClient ? $lastClient->client_id + 1 : 1000;
   
               ClientDetails::create([
                   'client_id' => $newClientId,
                   'client_name' => $this->client_name,
                   'hr_name' => $employeeName,
                   'client_address1' => $this->client_address1,
                   'client_address2' => $this->client_address2,
                   'client_registration_date' => now(),
                   'client_logo' => isset($clientBinaryData) ? $clientBinaryData : null,
                   'password' => bcrypt($this->password),
                   'contact_email' => $this->contact_email,
                   'contact_phone' => $this->contact_phone,
               ]);
   
               FlashMessageHelper::flashSuccess('Client added successfully.');
           }
   
           // Redirect to the client list page
           return redirect()->route('clientList.page');
   
       } catch (\Exception $e) {
           // Log the exception error
           Log::error('Error in submitting the client form: ' . $e->getMessage(), [
               'trace' => $e->getTraceAsString(),
           ]);
   
           // Flash an error message to the user
           FlashMessageHelper::flashError('An error occurred while processing your request. Please try again.');
   
           // Optionally, you could redirect to a different page or render the form with the error messages
           return redirect()->back()->withInput();
       }
   }
   


    public function validateInputChange($field)
    {
        
        $this->validateOnly($field);
    }
    public function mount($clientId = null)
    {
        if ($clientId) {
            $client = ClientDetails::find($clientId);
            if ($client) {
                $this->client_id = $client->client_id;
                $this->client_name = $client->client_name;
                $this->client_address1 = $client->client_address1;
                $this->client_address2 = $client->client_address2;
                $this->contact_email = $client->contact_email;
                $this->contact_phone = $client->contact_phone;
                
                // Load the client logo only when mounting for the first time
                if (!$this->client_logo && $client->client_logo) {
                    $this->client_logo = base64_encode($client->client_logo);
                }
            }
        }
    }
    


    public function render()
    {
        return view('livewire.client-registration');
    }
}

