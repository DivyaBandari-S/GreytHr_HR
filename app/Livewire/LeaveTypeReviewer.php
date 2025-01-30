<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\AddLeaveTypeReviewer;
use App\Models\EmployeeDetails;
use App\Models\LeavePolicySetting;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LeaveTypeReviewer extends Component
{
    public $activeTab = 'nav-reviewers';
    public $showAddReviewerModal = false;
    public $leaveTypes = [];
    public $employeeIds = [];
    public $slecetedReviewer_1;
    public $slecetedReviewer_2;

    public $leave_scheme = 'general'; // default value
    public $leave_type;
    public $reviewer_1;
    public $reviewer_2;
    public $searchTerm;
    public $addedLeaveReviewersData;
    public $openEmployeeContainer1 = false;
    public $openEmployeeContainer2 = false;
    public $slecetedReviewerName_1;
    public $reviewer_1_combined;
    public $slecetedReviewerName_2;
    public $reviewer_2_combined;
    protected $rules = [
        'leave_scheme' => 'required|string',
        'leave_type' => 'required|string',
        'reviewer_1' => 'nullable|string',
        'reviewer_2' => 'nullable|string',
    ];

    protected $messages = [
        'leave_scheme.required' => 'Leave scheme is required',
        'leave_type.required' => 'Leave type is required',
    ];
    public function mount()
    {
        $this->getLeaveTypes();
        $this->getLeaveTypeReviewerData();
        $this->searchTerm = null;
        if (request()->has('tab')) {
            $this->activeTab = request()->get('tab');
        }
    }
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function getLeaveTypeReviewerData()
    {
        $this->addedLeaveReviewersData = AddLeaveTypeReviewer::leftJoin('employee_details as ed1', 'ed1.emp_id', '=', 'add_leave_type_reviewers.reviewer_1')
            ->leftJoin('employee_details as ed2', 'ed2.emp_id', '=', 'add_leave_type_reviewers.reviewer_2')
            ->select(
                'add_leave_type_reviewers.*',
                'ed1.first_name as reviewer1_first_name',
                'ed1.last_name as reviewer1_last_name',
                'ed2.first_name as reviewer2_first_name',
                'ed2.last_name as reviewer2_last_name'
            )
            ->get();
    }


    public function getLeaveTypes()
    {
        $this->leaveTypes = LeavePolicySetting::pluck('leave_name');
    }

    public $showDeleteReviewerModal = false;
    public function toggleAddReviewer()
    {
        $this->showAddReviewerModal = true;
    }

    public function cancelModal()
    {
        $this->showAddReviewerModal = false;
    }


    public function getEmployeeData($searchTerm = null)
    {
        $searchTerm = $this->searchTerm;
        try {
            $loggedInEmpId = auth()->guard('hr')->user()->emp_id;
            $employee = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();

            if ($employee) {
                $companyId = $employee->company_id;
                // Ensure company_id is decoded if it's a JSON string
                $companyIdsArray = is_array($companyId) ? $companyId : json_decode($companyId, true);
            }

            // Check if companyIdsArray is an array and not empty
            if (empty($companyIdsArray)) {
                // Handle the case where companyIdsArray is empty or invalid
                return 0;  // or handle as needed
            }

            // Step 1: Get all employees' emp_ids that belong to the company or companies in companyIdsArray
            $query = EmployeeDetails::whereJsonContains('company_id', $companyIdsArray)
                ->whereNotIn('employee_status', ['resigned', 'terminated'])
                ->select('emp_id', 'first_name', 'last_name'); // Get a collection of emp_ids

            // Step 2: Apply search if a search term is provided
            if ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('emp_id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                });
            }
            // Step 3: Execute the query and get the results
            $this->employeeIds = $query->get(); // Convert to an array
            return $this->employeeIds;
        } catch (\Exception $e) {
            // Log the exception message (can use a logging library, or log to a file)
            Log::error('Error fetching employee data: ' . $e->getMessage());
            FlashMessageHelper::flashWarning('Something went wrong while fetching employee data.');
        }
    }


    public function toggleEmployeeContainer()
    {
        $this->openEmployeeContainer2 = false;
        $this->searchTerm = null;
        $this->openEmployeeContainer1 = !$this->openEmployeeContainer1;
    }
    public function toggleEmployeeContainer2()
    {
        $this->openEmployeeContainer1 = false;
        $this->searchTerm = null;
        $this->openEmployeeContainer2 = !$this->openEmployeeContainer2;
    }
    public function getSelecetedReviewer($empId)
    {
        // Set the selected reviewer ID
        $this->slecetedReviewer_1 = $empId;

        // Loop through the employeeIds to find the matching employee
        foreach ($this->employeeIds as $data) {
            if ($this->slecetedReviewer_1 === $data->emp_id) {
                // When a match is found, set the name (e.g., combining first and last name)
                $this->slecetedReviewerName_1 = $data->first_name . ' ' . $data->last_name;
                $this->reviewer_1_combined = $this->slecetedReviewerName_1 . ' (' . $this->slecetedReviewer_1 . ')';
                break; // Exit the loop once we find the match
            }
        }
        // Close the employee container and reset the search term
        $this->openEmployeeContainer1 = false;
        $this->searchTerm = null;
        $this->getEmployeeData($this->searchTerm = null);
    }
    public function getSelecetedReviewer2($empId)
    {
        $this->slecetedReviewer_2 = $empId;
        // Loop through the employeeIds to find the matching employee
        foreach ($this->employeeIds as $data) {
            if ($this->slecetedReviewer_2 === $data->emp_id) {
                // When a match is found, set the name (e.g., combining first and last name)
                $this->slecetedReviewerName_2 = $data->first_name . ' ' . $data->last_name;
                $this->reviewer_2_combined = $this->slecetedReviewerName_2 . ' (' . $this->slecetedReviewer_2 . ')';
                break; // Exit the loop once we find the match
            }
        }
        $this->openEmployeeContainer2 = false;
        $this->searchTerm = null;
        $this->getEmployeeData($this->searchTerm = null);
    }

    public function addLeaveReviewer()
    {
        // Perform the validation
        $this->validate($this->rules);
        try {
            if ($this->slecetedReviewer_1 && $this->slecetedReviewer_2) {
                if ($this->slecetedReviewer_1 === $this->slecetedReviewer_2) {
                    FlashMessageHelper::flashError('Reviewer1 and Reviewer2 should not be same. Please select different Reviewer.');
                    return;
                }
            }
            // Create a new LeaveReviewer entry in the database
            AddLeaveTypeReviewer::create([
                'leave_scheme' => $this->leave_scheme ?? 'general', // Default to 'general' if not provided
                'leave_type' => $this->leave_type, // Can be null if not provided
                'reviewer_1' => $this->slecetedReviewer_1, // Can be null if not provided
                'reviewer_2' => $this->slecetedReviewer_2, // Can be null if not provided
            ]);

            // Optionally, provide a success message after storing
            FlashMessageHelper::flashSuccess('Leave reviewer added successfully!');
            // Optionally, reset the form fields after submission
            $this->resetFields();
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error in addLeaveReviewer: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Optionally, flash an error message for the user
            FlashMessageHelper::flashError('Something went wrong. Please try again.');
        }
    }

    public function resetFields()
    {
        $this->leave_scheme;
        $this->leave_type = null;
        $this->reviewer_1_combined = null;
        $this->reviewer_2_combined = null;
    }

    public $getDeletedId;
    public $showEditReviewerModal = false;
    public function openDeleteModal($id)
    {
        $this->showDeleteReviewerModal = true;
        $this->getDeletedId = $id;
    }
    public function cancelDeleteModal()
    {
        $this->showDeleteReviewerModal = false;
    }

    public $findId;
    //delete reviewer
    public function confirmDelete()
    {
        if ($this->getDeletedId) {
            $ID = $this->getDeletedId;
            $this->findId = AddLeaveTypeReviewer::find($ID);
            // Check if the ID was found and delete it
            if ($this->findId) {
                $this->findId->delete();
            }
            $this->showDeleteReviewerModal = false;
            FlashMessageHelper::flashSuccess('Leave type reviewer deleted successfully!');
        }
    }
    public $getEditedId;
    public $findEditId;
    public function openEditModal($id)
    {
        $this->showEditReviewerModal = true;
        $this->getEditedId = $id;
        $this->editReviewer();
    }

    public function closeEditModal()
    {
        $this->showEditReviewerModal = false;
    }

    public $reviewer1;
    public $reviewer2;
    public function editReviewer()
    {
        if ($this->getEditedId) {
            $this->findEditId = AddLeaveTypeReviewer::find($this->getEditedId);

            if ($this->findEditId) {
                $this->leave_scheme = $this->findEditId->leave_scheme;
                $this->leave_type = $this->findEditId->leave_type;

                // Fetch reviewer 1 data
                $this->reviewer1 = EmployeeDetails::find($this->findEditId->reviewer_1);
                Log::info('Reviewer 1:', ['reviewer1' => $this->reviewer1]);

                if ($this->reviewer1) {
                    $this->reviewer_1_combined = $this->reviewer1->first_name . ' ' . $this->reviewer1->last_name . ' (' . $this->reviewer1->emp_id . ')';
                }

                // Fetch reviewer 2 data
                $this->reviewer2 = EmployeeDetails::find($this->findEditId->reviewer_2);
                Log::info('Reviewer 2:', ['reviewer2' => $this->reviewer2]);

                if ($this->reviewer2) {
                    $this->reviewer_2_combined = $this->reviewer2->first_name . ' ' . $this->reviewer2->last_name . ' (' . $this->reviewer2->emp_id . ')';
                }
            }
        }
    }


    public function saveLeaveReviewer()
    {
        if ($this->findEditId) {
            // If reviewer_1_combined is set, extract emp_id from it
            if ($this->reviewer_1_combined) {
                // Regular expression to match the emp_id within parentheses, e.g., (XSS-0478)
                preg_match('/\((.*?)\)/', $this->reviewer_1_combined, $matches);
                if (isset($matches[1])) {
                    // $matches[1] contains the emp_id (e.g., XSS-0478)
                    $emp_id_1 = $matches[1];
                }
            }
            // If reviewer_2_combined is set, extract emp_id from it
            if ($this->reviewer_2_combined) {
                // Regular expression to match the emp_id within parentheses, e.g., (XSS-0478)
                preg_match('/\((.*?)\)/', $this->reviewer_2_combined, $matches);
                if (isset($matches[1])) {
                    // $matches[1] contains the emp_id (e.g., XSS-0478)
                    $emp_id_2 = $matches[1];
                }
            }

            // If emp_id was extracted, save it to reviewer_1
            if (isset($emp_id_1)) {
                $this->findEditId->reviewer_1 = $emp_id_1;
            }
            // If emp_id was extracted, save it to reviewer_2
            if (isset($emp_id_2)) {
                $this->findEditId->reviewer_2 = $emp_id_2;
            }
            // Now save reviewer_1 and reviewer_2 emp_ids
            $this->findEditId->leave_scheme = $this->leave_scheme;
            $this->findEditId->leave_type = $this->leave_type;
            $this->findEditId->save();
            $this->showEditReviewerModal = false;
        }
    }


    public function render()
    {
        return view('livewire.leave-type-reviewer', [
            'leaveTypes' => $this->leaveTypes,
            'employeeIds' => $this->employeeIds,
            'slecetedReviewer_1' => $this->slecetedReviewer_1,
            'slecetedReviewer_2' => $this->slecetedReviewer_2,
            'addedLeaveReviewersData' => $this->addedLeaveReviewersData,
            'reviewer_1_combined' => $this->reviewer_1_combined
        ]);
    }
}
