<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HelpDesks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class PositionHistory extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $editingJobProfile=false;
    public $editingCompanyProfile=false;
    public $Jobmode;
    public $Designation;
    public $selected_equipment;
    public $BloodGroup;
    public $MaritalStatus;
    public $ItRequestaceessDialog = false;
    public $closeItRequestaccess = false;
    public $openItRequestaccess = false;
    public $isNames = false;
    public $record;
    public $subject;
    public $Mobile;
    public $hrempid;
    public $description;
    public $companyname;
    public $priority;
    public $activeTab = 'active';
    public $image;
    public $selectedPerson = null;
    public $cc_to;
    public $peoples;
    public $filteredPeoples;
    public $selectedPeopleNames = [];
    public $selectedPeople = [];
    public $records;
    public $peopleFound = true;
    public $file_path;
    public $justification;
    public $information;
    public $department;
    public $nickName;
    public $editingPersonalProfile=false;
    public $editingLocationProfile=false;
    public $gender;
    public $name;
    public $showSuccessMessage = false;
   
    public $wishMeOn;
    public $selectedTimeZone;
    public $timeZones;
    public $biography;
    public $faceBook;
    public $twitter;
    public $Email;
    public $Location;
    public $recentHires = [];
    public $linkedIn;
    public $editingProfile = false;
    public $editingTimeZone = false;
    public $editingBiography = false;
    public $editingSocialMedia = false;
    public $employees;
    public $oldPassword;
    public $newPassword;
    public $confirmNewPassword;
    public $passwordChanged = false;
    public $employeeDetails = [];
    
    public $showDetails = true;
    public $editingField = false;
    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
    public function filter()
    {
        $companyId = Auth::user()->company_id;
        $trimmedSearchTerm = trim($this->searchTerm);

        $this->filteredPeoples = EmployeeDetails::where('company_id', $companyId)
            ->where(function ($query) use ($trimmedSearchTerm) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $trimmedSearchTerm . '%')
                    ->orWhere('emp_id', 'like', '%' . $trimmedSearchTerm . '%');
            })
            ->get();

        $this->peopleFound = count($this->filteredPeoples) > 0;
    }

    public function updatedSelectedPeople()
    {
        $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
    }

    public function NamesSearch()
    {
        $this->isNames = true;
        $this->selectedPeopleNames = [];
        $this->cc_to = '';
    }

    public function closePeoples()
    {
        $this->isNames = false;
    }

    public function selectPerson($emp_id)
    {
        $selectedPerson = EmployeeDetails::find($emp_id);
    
        if ($selectedPerson) {
            $this->employeeDetails[$emp_id] = $selectedPerson;
    
            if (!in_array($emp_id, array_keys($this->selectedPeopleNames))) {
                $this->selectedPeopleNames[$emp_id] = '<img src="' . asset($selectedPerson->image) . '" alt="' . $selectedPerson->first_name . ' ' . $selectedPerson->last_name . '" height="50" width="50">' . ' #(' . $selectedPerson->emp_id . ')';
            } else {
                unset($this->selectedPeopleNames[$emp_id]);
            }
    
            $this->cc_to = implode(', ', array_unique($this->selectedPeopleNames));
          // Set the editingField property
        }
    }
    
    public function editProfile($emp_id)
    {
       
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingProfile= true;
      
        if ($selectedPerson) {
            $this->department = $selectedPerson->department ?? '';
         
          
        }
     
       
    }
  

    public function cancelProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingProfile= false;
        }
       
    }
    
    public function saveProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
            $selectedPerson->departmet = $this->department;
    
            $selectedPerson->save();
         
            $this->editingProfile = false;
        }
    }
    public function editJobProfile($emp_id)
    {
       
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingJobProfile= true;
      
        if ($selectedPerson) {
            $this->Jobmode = $selectedPerson->job_mode ?? '';
         
          
        }
     
       
    }
  

    public function cancelJobProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingJobProfile= false;
        }
       
    }
    
    public function saveJobProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
            $selectedPerson->job_mode= $this->Jobmode;
    
            $selectedPerson->save();
         
            $this->editingJobProfile = false;
        }
    }
    public function editCompanyProfile($emp_id)
    {
       
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingCompanyProfile= true;
      
        if ($selectedPerson) {
            $this->companyname = $selectedPerson->company_name ?? '';
         
          
        }
     
       
    }
  

    public function cancelCompanyProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingCompanyProfile= false;
        }
       
    }
    
    public function saveCompanyProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
            $selectedPerson->company_name= $this->companyname;
    
            $selectedPerson->save();
         
            $this->editingCompanyProfile = false;
        }
    }
    public function editLocationProfile($emp_id)
    {
       
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingLocationProfile= true;
      
        if ($selectedPerson) {
            $this->Location = $selectedPerson->job_location ?? '';
         
          
        }
     
       
    }
  

    public function cancelLocationProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingLocationProfile= false;
        }
       
    }
    
    public function saveLocationProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
            $selectedPerson->job_location = $this->Location;
    
            $selectedPerson->save();
         
            $this->editingLocationProfile = false;
        }
    }
    public function editpersonalProfile($emp_id)
    {
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        $this->editingPersonalProfile= true;
      
        if ($selectedPerson) {
          
           $this->Designation = $selectedPerson->job_title ?? '';
         
        }
      
       
    }
    
    public function cancelpersonalProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
        if ($selectedPerson) {
        $this->editingPersonalProfile= false;
        }
       
    }
    public function mount()
    {
        $hrempid = auth()->guard('hr')->user()->hr_emp_id;
       
        $companyId = auth()->guard('hr')->user()->company_id;
       
        
       
        $this->peoples = EmployeeDetails::where('company_id', $companyId)
        ->orderBy('hire_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->pluck('hire_date');
      
    }
    public function updateProfile($emp_id)
    {
        $selectedPerson = EmployeeDetails::find($emp_id);
    
        if ($selectedPerson) {
            $this->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024', // 1024 kilobytes = 1 megabyte
            ]);
    
            if ($this->image) {
                if ($this->image instanceof \Illuminate\Http\UploadedFile) {
                    $imagePath = $this->image->store('employee_image', 'public');
                } else {
                    $imagePath = $this->image;
                }
                $selectedPerson->image = $imagePath;
                $selectedPerson->save();
            }
    
            $this->showSuccessMessage = true;
        }
    }
    public function savepersonalProfile($emp_id)
    {
        // No need to query for $selectedPerson here
        $selectedPerson = EmployeeDetails::find($emp_id);
        $selectedPerson = $this->employeeDetails[$emp_id] ?? null;
    
        if ($selectedPerson) {
        
              $selectedPerson->job_title = $this->Designation;
           
            $selectedPerson->save();
         
            $this->editingPersonalProfile = false;
        }
    }
    
    public function render()
    {
        $hrempid = auth()->guard('hr')->user()->hr_emp_id;
        $companyId = auth()->guard('hr')->user()->company_id;

        $this->peoples = EmployeeDetails::where('company_id', $companyId)->get();
        $peopleData = $this->filteredPeoples ?: $this->peoples;

        $this->record = HelpDesks::all();
        $employeeName = auth()->guard('hr')->user()->employee_name . ' #(' . $hrempid . ')';

        $this->records = HelpDesks::with('emp')
            ->where(function ($query) use ($hrempid, $employeeName) {
                $query->where('emp_id', $hrempid)
                    ->orWhere('cc_to', 'LIKE', "%$employeeName%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.position-history', [
            'peopleData' => $peopleData,
            'records' => $this->records
        ]);
    }

}