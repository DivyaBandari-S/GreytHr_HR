<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as PDF;
use Intervention\Image\ImageManagerStatic as Image;
use Imagick;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class HrOrganisationChart extends Component
{
    public $lower_authorities;

    public $selectedPeople = [];
    public $selectedEmployeeImage;
    public $selected_higher_authorities;
    public $unassigned_manager;

    public $selectedMassTransferManagerinString;
    public $assignTopLevelManager=false;

    public $SubordinateEmployees;
    public $selected_higher_authority_emp_ids;
    public $selected_higher_authorities1;

    public $assignManagerPopup=false;
    public $selected_higher_authorities_ID;

    public $selectedMassTransferNewManager;
    public $selectedEmployee;
    public $shiftSummary;
    public $searching=1;

    public $searchTerm = '';

    public $searchTermForFirstEmployee= '';
    public $employeeForMassTransfer;
    public $notFound;
    public $search='';

    public $assignmanager;
    public $selected_lower_authorities;

    public $newAssignManager;
    public $massTransferDialog=false;
    public $managers;

    public $searchEmployee=0;

    public $searchSecondEmployee=0;
    public $scale = 0.5;
    public $selectedManagers=[];
    
    public $EmployeeId=[];
    public $selectedEmployeeIdFor2ndManager;
    public $searchTermForManager='';
    public $managerDetails;
    public $selectedManager;

    public $searchTermForAssignManager;
    public $employees1;
    public $selectedEmployeeLastNameFor2ndManager;

    public $isupdateFilter=0;
    public $selectedDesignation;
    public $isOpen=false;
    public $employeesforassignedManager;
    public $selectedEmployeeFirstNameFor2ndManager;
    public $searchFromManagerTransfer=0;
    public $selectedEmployeeId;
    public $selectedMassTransferManager;
    public $primary_lower_authorities;

    public $topEmployee;
    public $subordinates;
    public $manager_id;

    public $selectedEmployeeLastName;
    public $selectedEmployeeFirstName;
    public $chartData;
    public function mount($selectedEmployeeId=null)
    {
        // Initialize with any preselected employee IDs if necessary
        $this->shiftSummary = []; // Example: [1, 2, 3]
        $this->selectedManagers=[];
        $this->chartData = $this->fetchChartData();
        $this->selectedEmployeeId=$selectedEmployeeId;
        $this->selectedEmployeeFirstName=EmployeeDetails::where('emp_id',$this->selectedEmployeeId)->value('first_name');
        $this->selectedEmployeeLastName=EmployeeDetails::where('emp_id',$this->selectedEmployeeId)->value('last_name');
        $this->topEmployee = EmployeeDetails::where('job_role', 'Chairman')->orWhere('job_role', 'Director')->first();

        // Fetch all employees working under the top-level employee
        if ($this->topEmployee) {
            $this->subordinates = EmployeeDetails::where('manager_id', $this->topEmployee->id)->get();
        } else {
            $this->subordinates = collect(); // Empty collection if no top-level employee
        }
        
    }

    public function closeSidebar()
    {
        $this->isOpen=false;
    }
    public function clearSelectedEmployee()
    {
        $this->selectedEmployeeId='';
        $this->selectedEmployeeFirstName='';
        $this->selectedEmployeeLastName='';
        $this->searchTerm='';
    }
    public function selectEmployee($empId)
    {
        
        $this->selectedEmployeeId = $empId;
        $this->selectedEmployeeFirstName = EmployeeDetails::where('emp_id', $empId)->value('first_name');
        $this->selectedEmployeeLastName = EmployeeDetails::where('emp_id', $empId)->value('last_name');
        $this->selectedEmployeeImage = EmployeeDetails::where('emp_id', $empId)->value('image');
        $this->searchTerm='';
    }
    public function searchForManagerTransfer()
    {
      $this->searchFromManagerTransfer=1;

    }
    public function updateselectedEmployeeForManager($empId)
    {
    
        $this->selectedEmployeeId=$empId;
      
        $this->selectedEmployeeFirstName= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastName= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->SubordinateEmployees=EmployeeDetails::where('manager_id',$this->selectedEmployeeId)->get();
        $this->searchEmployee=0;
    }

    public function updateselectedEmployeeForManager2nd($empId)
    {
    
        $this->selectedEmployeeIdFor2ndManager=$empId;
      
        $this->selectedEmployeeFirstNameFor2ndManager= EmployeeDetails::where('emp_id',$empId)->value('first_name');
        $this->selectedEmployeeLastNameFor2ndManager= EmployeeDetails::where('emp_id',$empId)->value('last_name');
        $this->searchSecondEmployee=0;
    }

    public function closeEmployeeBoxForMassTransfer()
    {
        $this->searchFromManagerTransfer=0;
        $this->searchTerm='';
        $this->managers=$this->getEmployeesByType();
       
       
    }
    public function closeEmployeeBox()
    {
        $this->searchFromManagerTransfer=0;
        $this->searchTerm='';
       
       
    }
    // public function updatesearchTerm()
    // {
    //     $this->searchTerm= $this->searchTerm;
    //     $this->searchforEmployee();
       
    // }
    public function getEmployeesBySecondType()
    {
        
        Log::info('Welcome to getEmployeesBySecondType Method'); 
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
       

            $query->where('employee_status', 'active');
        
        
        

        if (!empty($this->searchTermForAssignManager)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTermForAssignManager . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTermForAssignManager . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTermForAssignManager . '%');
            });
        }
       Log::info('Employees For Assigned Manager:',$query->get()->toArray());
        // Get the filtered employees
        return $query->get();
    
    }

    public function toggleSidebar()
    {
        $this->isOpen = !$this->isOpen; // Toggle sidebar visibility
    }

    public function updateselectedDesignation()
    {
       $this->selectedDesignation=$this->selectedDesignation;
    }

    
    public function getEmployeesByType()
    {
        $managerIds = EmployeeDetails::select('manager_id')
        ->distinct()
        ->pluck('manager_id')
        ->toArray();
       
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
       

            $query->where('employee_status', 'active')->whereIn('manager_id',$managerIds);
        
        
        

        if (!empty($this->searchTerm)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTerm . '%');
            });
        }
        elseif (!empty($this->searchTermForFirstEmployee)) {
            $query->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchTermForFirstEmployee . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchTermForFirstEmployee . '%')
                      ->orWhere('emp_id', 'like', '%' . $this->searchTermForFirstEmployee . '%');
            });
        }
        
        // Get the filtered employees
        return $query->get();
    
    }
    public function getEmployeesByAsssignManagerType()
    {
        
       
        $query = EmployeeDetails::query();
        // Example logic to fetch employees based on the selected type
       

            $query->where('employee_status', 'active');
        
        
        

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
   

    public function test()
    {
        dd('asdzfgchjm');
    }
    public function fetchChartData()
    {
        // Fetch your chart data from the database or any other source
        // For example:
        return [
            'title' => 'HR Organisation Chart',
            'content' => 'This is a sample content for the HR Organisation Chart.'
        ];
    }

    public function searchforEmployee()
    {
          $this->searchEmployee=1;
       
    }

    public function searchforSecondEmployee()
    {
        $this->searchSecondEmployee=1;
    }
    public function updateselectedMassTransferManager()
    {
        $this->selectedMassTransferManager=$this->selectedMassTransferManager;
    }
    public function updateselectedMassTransferNewManager()
    {
        $this->selectedMassTransferNewManager=$this->selectedMassTransferNewManager;
    }
    
    public function updateEmployeeId()
    {
        $this->EmployeeId=$this->EmployeeId;
    }
    public function checkMassTransfer()
    {
        if(!empty($this->EmployeeId))
        {
            $employees=EmployeeDetails::whereIn('emp_id',$this->EmployeeId)->pluck('emp_id')->toArray();
         
        }
        else
        {
            $employees=EmployeeDetails::where('manager_id',$this->selectedEmployeeId)->pluck('emp_id')->toArray();
        
        }
       
        $manager = EmployeeDetails::where('emp_id',$this->selectedEmployeeIdFor2ndManager)->first();
  
        if ($manager) {
            $reportTo = ucwords(strtolower($manager->first_name)) . ' ' . ucwords(strtolower($manager->last_name));
   
            // Update the employee_details table
            EmployeeDetails::whereIn('emp_id', $employees)->update([
                'manager_id' => $this->selectedEmployeeIdFor2ndManager,
            ]);
   
           
   
            // Optionally, provide feedback to the user
            FlashMessageHelper::flashSuccess( 'Employee Manager updated successfully.');
            $this->EmployeeId=[];
            $this->selectedEmployeeId=null;
            $this->selectedEmployeeIdFor2ndManager=null;
        } else {
            // Handle the case where the manager is not found
            FlashMessageHelper::flashError( 'Manager not found.');
            $this->EmployeeId=[];
            $this->selectedEmployeeId=null;
            $this->selectedEmployeeIdFor2ndManager=null;
        }
        $this->massTransferDialog=false;  
    }

   
    
    public function exportContent()
    {
          // Sample organization chart data
    $orgChart = [
        ['Name' => 'CEO', 'Position' => 'Chief Executive Officer'],
        ['Name' => 'CFO', 'Position' => 'Chief Financial Officer'],
        ['Name' => 'CTO', 'Position' => 'Chief Technology Officer'],
        ['Name' => 'CMO', 'Position' => 'Chief Marketing Officer'],
        // Add more entries as needed
    ];

    // Create a new SimpleExcelWriter object
    $writer = SimpleExcelWriter::factory(SimpleExcelWriter::XLSX);

    // Add headers
    $writer->addRow(['Name', 'Position']);

    // Add data rows
    foreach ($orgChart as $entry) {
        $writer->addRow([$entry['Name'], $entry['Position']]);
    }

    // Save the file
    $filename = 'organization_chart.xlsx';
    $writer->save($filename);

    // Output the file for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);

    // Clean up
    unlink($filename);
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
        $this->employees1=$this->getEmployeesBySecondType();
       
        
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
        $this->searchTerm='';
       
        return redirect()->route('hr-organisation-chart',['selectedEmployeeId'=>$this->selectedEmployeeId]);
         
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
    public function updatesearchTerm()
    {
        $this->searchTerm=$this->searchTerm;
    }

    public function updatesearchTermForFirstEmployee()
    {
        $this->searchTermForFirstEmployee=$this->searchTermForFirstEmployee;
    }

    public function updatesearchTermForAssignManager()
    {
     
        $this->searchTermForAssignManager=$this->searchTermForAssignManager;
        
    }

    public function closeMassTransferDialog()
    {
        $this->massTransferDialog=false;
        $this->selectedMassTransferManager='';
        $this->selectedEmployeeId=null;
        $this->selectedEmployeeIdFor2ndManager=null;
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
     
        $this->managers = $this->getEmployeesByType();
        
        $lower_authorities_ID=EmployeeDetails::where('manager_id',$higher_authorities_ID)->select('emp_id');
        return view('livewire.hr-organisation-chart',['HigherAuthorities'=>$higher_authorities,'UnAssignedManagerCount'=>$unassigned_manager_count]);
    }
}
