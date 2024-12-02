<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\Request;
use Livewire\Component;

class Requests extends Component
{
    public $isOpen = false;
    public $short_description;
    public $rejection_reason;
    public $full_name;
    public $selectedCategory = [];

    public $activeCategory = '';
    public $pendingCategory = '';
    public $closedCategory = '';
    public $searchTerm = '';
    public $showViewFileDialog = false;
    public $LapRequestaceessDialog=false;
    public $IDRequestaceessDialog=false;
    public $showModal = false;
    public $search = '';
    public $isRotated = false;
    public $requestId;

    public $requestCategories = '';
    public $selectedPerson = null;
    public $peoples;
    public $filteredPeoples;
    public $showserviceViewFileDialog = false;
    public $peopleFound = true;
    public $category;
    public $ccToArray = [];
    public $request;
    public $subject;
    public $description;
    public $file_path;
    public $cc_to;
    public $priority;
    public $servicerecords;
    public $records;
    public $image;
    public $mobile;
    public $mail;
    public $selectedPeopleNames = [];
    public $employeeDetails;
    public $showDialog = false;
    public $fileContent, $file_name, $mime_type;

    public $showDialogFinance = false;
    public $record;
    public $peopleData = '';
    public $filterData;
    public $activeTab = 'active';
    public $selectedPeople = [];
    public $activeSearch = [];
    public $pendingSearch = '';
    public $closedSearch = '';

    public function mount()
    {
        // Fetch unique requests with their categories
        $requestCategories = Request::select('Request', 'category')->get();

        $employeeId = auth()->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
        $companyId = auth()->user()->company_id;
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])->get();

        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->peoples;
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';



        if ($this->employeeDetails) {
            // Combine first and last names
            $this->full_name = $this->employeeDetails->first_name . ' ' . $this->employeeDetails->last_name;
        }

        $this->filterData = [];
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
      
    }
    public function LapRequest()
    {



        $this->LapRequestaceessDialog = true;
        $this->showModal = true;
        $this->category = 'Service Request';
    }

    public function IDRequest()
    {

        $this->IDRequestaceessDialog = true;
        $this->showModal = true;
        $this->category = 'Incident Request';
    }
    public function updatedActiveTab()
    {
        $this->loadHelpDeskData(); // Reload data when the tab is updated
    }
    
  
    public function searchHelpDesk($status_code, $searchTerm,$selectedCategory)
    {
        $employeeId = auth()->guard('emp')->user()->emp_id;
    
        // Start the base query based on status and employee ID or cc_to
        $query = Request::where(function ($query) use ($employeeId) {
            $query->where('emp_id', $employeeId)->orWhere('cc_to', 'like', "%$employeeId%");
        });
        if (is_array($status_code)) {
            $query->whereIn('status_code', $status_code);  // Multiple statuses (array)
        } else {
            $query->where('status_code', $status_code);    // Single status (string)
        }// Apply status filter dynamically
    
        // If a category is selected, apply category filtering
        if ($selectedCategory) {
            logger('Selected Category: ' . $selectedCategory);
            $query->whereIn('category', Request::where('Request', $selectedCategory)->pluck('category'));
        }
    
        // If there's a search term, apply search filtering
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('emp_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('category', 'like', '%' . $searchTerm . '%')
                    ->orWhere('subject', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('emp', function ($query) use ($searchTerm) {
                        $query->where('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }
    
        // Get results
        $results = $query->orderBy('created_at', 'desc')->get();
     
        $this->filterData = $results;
        $this->peopleFound = count($this->filterData) > 0;
    }
    
    
    public function searchActiveHelpDesk()
    {
        $this->searchHelpDesk([8,10], $this->activeSearch,$this->activeCategory);
    }
    
    public function searchPendingHelpDesk()
    {
        $this->searchHelpDesk(6, $this->pendingSearch,$this->pendingCategory);
    }
    
    public function searchClosedHelpDesk()
    {
        $this->searchHelpDesk([12,4], $this->closedSearch,$this->closedCategory);
    }
    public function closecatalog()
    {
        $this->showModal = false;
        $this->resetErrorBag(); // Reset validation errors if any
        $this->resetValidation(); // Reset validation state
        $this->reset([
            'subject',
            'mail',
            'mobile',
            'description',
      
            'cc_to',
            'category',
            'file_path',
        
            'selectedPeopleNames',
            'image',
            'selectedPeople',
            'selectedPeople',
        ]);
    }
    public function resetDialogs()
    {
        $this->LapRequestaceessDialog = false; 
        $this->IDRequestaceessDialog = false;
    }
public function setActiveTab($tab) {
    $this->activeTab = $tab;
}
    public function render()
    {
        $requestCategories = Request::select('Request', 'category')->get();

        $employeeId = auth()->user()->emp_id;
        $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
        $companyId = auth()->user()->company_id;
        $this->peoples = EmployeeDetails::whereJsonContains('company_id', $companyId)->whereNotIn('employee_status', ['rejected', 'terminated'])->get();

        $this->peopleData = $this->filteredPeoples ? $this->filteredPeoples : $this->peoples;
        $this->selectedPeople = [];
        $this->selectedPeopleNames = [];
        $employeeName = auth()->user()->first_name . ' #(' . $employeeId . ')';
        $searchData = $this->filterData ?: $this->records;


        if ($this->employeeDetails) {
            // Combine first and last names
            $this->full_name = $this->employeeDetails->first_name . ' ' . $this->employeeDetails->last_name;
        }
        return view('livewire.requests',[ 'searchData' => $this->filterData ?: $this->records,]);
    }
}
