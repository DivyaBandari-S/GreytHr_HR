<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class HrOrganisationChart extends Component
{
    public $lower_authorities;

    public $selected_higher_authorities;
    public $unassigned_manager;

    public $selectedMassTransferManagerinString;
    public $assignTopLevelManager=false;

    public $selected_higher_authority_emp_ids;
    public $selected_higher_authorities1;

    public $assignManagerPopup=false;
    public $selected_higher_authorities_ID;

    public $selectedMassTransferNewManager;
    public $selectedEmployee;
    public $shiftSummary;
    public $searching=1;

    public $employeeForMassTransfer;
    public $notFound;
    public $search='';

    public $assignmanager;
    public $selected_lower_authorities;

    public $newAssignManager;
    public $massTransferDialog=false;
    public $managers;

    public $scale = 1;
    public $selectedManagers=[];
    
    public $managerDetails;
    public $selectedManager;

    public $selectedMassTransferManager;
    public $primary_lower_authorities;

    public $manager_id;
    public function mount()
    {
        // Initialize with any preselected employee IDs if necessary
        $this->shiftSummary = []; // Example: [1, 2, 3]
        $this->selectedManagers=[];
    }
    public function updateselectedMassTransferManager()
    {
        $this->selectedMassTransferManager=$this->selectedMassTransferManager;
    }
    public function updateselectedMassTransferNewManager()
    {
        $this->selectedMassTransferNewManager=$this->selectedMassTransferNewManager;
    }
    public function checkMassTransfer()
    {
        $employees=EmployeeDetails::where('manager_id',$this->selectedMassTransferManager)->pluck('emp_id')->toArray();
        $manager = EmployeeDetails::where('emp_id',$this->selectedMassTransferNewManager)->first();
        if ($manager) {
            $reportTo = ucwords(strtolower($manager->first_name)) . ' ' . ucwords(strtolower($manager->last_name));
   
            // Update the employee_details table
            EmployeeDetails::whereIn('emp_id', $employees)->update([
                'manager_id' => $this->selectedMassTransferNewManager,
                'report_to' => $reportTo
            ]);
   
           
   
            // Optionally, provide feedback to the user
            session()->flash('message', 'Employee Manager updated successfully.');
        } else {
            // Handle the case where the manager is not found
            session()->flash('error', 'Manager not found.');
        }
        $this->massTransferDialog=false;  
    }
    public function updateselectedEmployee()
    {
        $this->selectedEmployee=$this->selectedEmployee;
    }
    public function updateselectedManager()
    {
        $this->selectedManager=$this->selectedManager;
    }
    public function updateassignmanager()
    {
        $this->assignmanager=$this->assignmanager;
    }
    public function updateselectedManagers($managerId)
    {
       $employees1=EmployeeDetails::whereIn('emp_id',$this->selectedManagers)->select('emp_id', 'first_name', 'last_name')->get();   
       $this->manager_id=$managerId;
    }
    
    public function searchFilters()
    {
        $unassigned_manager1 = EmployeeDetails::where(function ($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%')
            ->orWhere('emp_id', 'like', '%' . $this->search . '%');
    
        })->whereNull('manager_id')->get();
        $nameFilter = $this->search; 
        $filteredEmployees = $unassigned_manager1->filter(function ($unassigned_manager1) use ($nameFilter) {
            return stripos($unassigned_manager1->first_name, $nameFilter) !== false ||
                stripos($unassigned_manager1->last_name, $nameFilter) !== false ||
                stripos($unassigned_manager1->emp_id, $nameFilter) !== false;
        });

        if ($filteredEmployees->isEmpty()) {
            $this->notFound = true; 
        } else {
            $this->notFound = false;
        }
       
     
    }
    public function check()
    {
        
        $manager = EmployeeDetails::where('emp_id',$this->selectedManager)->first();

        if ($manager) {
            $reportTo = ucwords(strtolower($manager->first_name)) . ' ' . ucwords(strtolower($manager->last_name));
    
            // Update the employee_details table
            EmployeeDetails::where('emp_id', $this->selectedEmployee)->update([
                'manager_id' => $this->selectedManager,
                'report_to' => $reportTo
            ]);
    
            
    
            // Optionally, provide feedback to the user
            session()->flash('message', 'Employees updated successfully.');
        } else {
            // Handle the case where the manager is not found
            session()->flash('error', 'Manager not found.');
        }
        $this->assignManagerPopup=false;
    }
    public function updateshiftSummary()
    {
       
    }
    public function assigntoplevelmanager()
    {
        $this->assignTopLevelManager=true;
    }
    public function openAssignManagerPopup()
    {
        $this->assignManagerPopup=true;
    }
    public function closeAssignManager()
    {
        $this->assignManagerPopup=false;
        $this->selectedEmployee='';
        $this->selectedManager='';


    }
    public function closeAssignTopLevelManager()
    {
        $this->assignTopLevelManager=false;
    }
    public function masstransfer()
    {
        $this->massTransferDialog=true;
    }
    public function zoomIn()
    {
        $this->scale += 0.1; // Increase the scale
    }

    // Method to zoom out
    public function zoomOut()
    {
        if ($this->scale > 0.5) {
            $this->scale -= 0.1; // Decrease the scale
        }
    }

    public function closeMassTransferDialog()
    {
        $this->massTransferDialog=false;
        $this->selectedMassTransferManager='';
        $this->selectedMassTransferNewManager='';
    }
        public function render()
    {
        if($this->searching==1)
        {
            $this->unassigned_manager = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('emp_id', 'like', '%' . $this->search . '%')
                ->orWhere('job_role', 'like', '%' . $this->search . '%');
        
            })->whereNull('manager_id')->where('employee_status','active')->get();
            $nameFilter = $this->search; 
            $filteredEmployees = $this->unassigned_manager->filter(function ($unassigned_manager) use ($nameFilter) {
                return stripos($unassigned_manager->first_name, $nameFilter) !== false ||
                    stripos($unassigned_manager->last_name, $nameFilter) !== false ||
                    stripos($unassigned_manager->emp_id, $nameFilter) !== false||
                    stripos($unassigned_manager->job_role, $nameFilter) !== false;
            });
    
            if ($filteredEmployees->isEmpty()) {
                $this->notFound = true; 
            } else {
                $this->notFound = false;
            }
           
            
        }
        else
        {
            $this->unassigned_manager=EmployeeDetails::where('manager_id',null)->where('employee_status','active')->get();
            
        }
        $unassigned_manager_count=EmployeeDetails::where('manager_id',null)->where('employee_status','active')->count();
        if($this->manager_id)
        {
          $this->selected_higher_authorities=EmployeeDetails::where('emp_id',$this->manager_id)->get();
          $employee=EmployeeDetails::find($this->manager_id);
          $employeeId = $employee->emp_id;
          $this->selected_lower_authorities=EmployeeDetails::where('manager_id',$employeeId)->get();
          
          
        }
        $higher_authorities=EmployeeDetails::where('job_role','Chairman')->orWhere('job_role','Founder')->get();
        $higher_authorities=EmployeeDetails::where('job_role','Chairman')->orWhere('job_role','Founder')->get();
        $higher_authorities_ID=EmployeeDetails::where('job_role','Chairman')->select('emp_id');
        $this->lower_authorities=EmployeeDetails::where('manager_id',$higher_authorities_ID)->get();
        $this->managerDetails=EmployeeDetails::where('emp_id',$this->selectedMassTransferManager)->select('first_name','emp_id','last_name')->first();
        $this->employeeForMassTransfer=EmployeeDetails::where('manager_id',$this->selectedMassTransferManager)->get();
        if($this->selectedMassTransferManager!=null)
        {
                $this->selectedMassTransferManagerinString = explode(',', $this->selectedMassTransferManager);
                $this->newAssignManager=DB::table('employee_details as e1')
                ->join('employee_details as e2', 'e1.manager_id', '=', 'e2.emp_id')
                ->select('e2.emp_id as manager_id', 'e2.first_name', 'e2.last_name')
                ->distinct()
                ->whereNotIn('e2.emp_id', $this->selectedMassTransferManagerinString)
                ->get();
        }
        $this->managers = DB::table('employee_details as e1')
        ->join('employee_details as e2', 'e1.manager_id', '=', 'e2.emp_id')
        ->select('e2.emp_id as manager_id', 'e2.first_name', 'e2.last_name')
        ->distinct()
        ->get();
        $lower_authorities_ID=EmployeeDetails::where('manager_id',$higher_authorities_ID)->select('emp_id');
        return view('livewire.hr-organisation-chart',['HigherAuthorities'=>$higher_authorities,'UnAssignedManagerCount'=>$unassigned_manager_count]);
    }
}
