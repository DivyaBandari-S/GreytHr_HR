<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use Livewire\Component;
use App\Models\EmployeeDetails;
use App\Models\EmpResignations;
use App\Models\Hr;
use Carbon\Carbon;

class Resignationrequests extends Component
{
    public $hrRequests;
    public $loginEmployee;
    public $imageUrl;
    public $resignId;
    public $noticePeriod;
    public $lastWorkingDate;
    public $resignationDate;
    public $showImageDialog = false;


    public $activeTab = 'pending'; // Default active tab

    protected $rules = [
        'noticePeriod' => 'required|integer|gt:0',
        'lastWorkingDate' => 'required|date|after_or_equal:today',
    ];
    protected $messages = [
        'noticePeriod.required' => 'Notice period is required.',
        'noticePeriod.integer' => 'Notice period must be an integer.',
        'noticePeriod.gt' => 'Notice period must be a positive value.',
        'lastWorkingDate.required' => 'Last working date is required.',
        'lastWorkingDate.after_or_equal' => 'Last working date must be after or equal to today.',

    ];

    public function changeTab($tab)
    {
        $this->activeTab = $tab;

        $this->getHrRequests();
    }
    public function updatedLastWorkingDate($value)
    {
        // $this->calculateNoticePeriod();
    }

    // Triggered when 'noticePeriod' is updated
    public function updatedNoticePeriod($value)
    {
        // $this->calculateLastWorkingDate();
    }
    public function calculateNoticePeriod()
    {
        if ($this->lastWorkingDate) {
            $resignationDate = now();  // Today's date as the resignation date
            $lastWorkingDate = Carbon::parse($this->lastWorkingDate);

            // Calculate the difference in days between the resignation date and the last working date
            $diffDays = $lastWorkingDate->diffInDays($resignationDate);

            // Set the notice period based on the difference
            $this->noticePeriod = max(1, $diffDays);  // Ensure the notice period is at least 1 day
        }
    }
    public function calculateLastWorkingDate()
    {
        if ($this->noticePeriod) {
            $resignationDate = now();  // Today's date as the resignation date

            // Calculate the last working date by subtracting the notice period from the resignation date
            $lastWorkingDate = Carbon::parse($resignationDate)->subDays($this->noticePeriod);

            // Set the last working date
            $this->lastWorkingDate = $lastWorkingDate->format('Y-m-d');
        }
    }


    public function submitResignation()
    {
        $this->validate();
        try {
            // dd($this->resignId);
            $resignationRequest = EmpResignations::findOrFail($this->resignId);
            $resignationRequest->status='2';
            $empId = $resignationRequest->emp_id;
            $resignationDate= $resignationRequest->resignation_date;
            $resignIdRecord = EmployeeDetails::findOrFail($empId);

            $resignIdRecord->notice_period = $this->noticePeriod;
            $resignIdRecord->resignation_date = $resignationDate;
            $resignIdRecord->last_working_date = $this->lastWorkingDate;
            // dd($this->noticePeriod,$resignationDate,$this->lastWorkingDate);
            $resignIdRecord->save();
            $resignationRequest->save();
            $this->getHrRequests();
            $this->resignId='';
            $this->showImageDialog = false;

            FlashMessageHelper::flashSuccess('Resignation accepted successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            FlashMessageHelper::flashError('An error occured while accepting resignation, Please try after sometime');
        }
    }
    public function approveResignation($resignId)
    {

        $this->resignId = $resignId;

        $this->showImageDialog = true;
    }

    public function showImage($url)
    {
        // dd( $this->hrRequests);
        $this->imageUrl = $url;
        $this->showImageDialog = true;
    }

    public function closeImageDialog()
    {
        $this->showImageDialog = false;
        $this->imageUrl = null;
        $this->resignId = null;
    }
    public function downloadImage()
    {
        if ($this->imageUrl) {
            // Decode the Base64 data if necessary
            $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->imageUrl));

            // Determine MIME type and file extension
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $fileData);
            finfo_close($finfo);

            $extension = '';
            switch ($mimeType) {
                case 'image/jpeg':
                    $extension = 'jpg';
                    break;
                case 'image/png':
                    $extension = 'png';
                    break;
                case 'image/gif':
                    $extension = 'gif';
                    break;
                default:
                    return abort(415, 'Unsupported Media Type');
            }

            // Prepare file name and response
            $fileName = 'image-' . time() . '.' . $extension;
            return response()->streamDownload(
                function () use ($fileData) {
                    echo $fileData;
                },
                $fileName,
                [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                ]
            );
        }
        return abort(404, 'Image not found');
    }
    public function mount()
    {

        $this->getHrRequests();
    }

    public function render()
    {
        return view('livewire.resignationrequests');
    }

    public function getHrRequests()

    {
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // $this->loginEmployee = Hr::where('emp_id', $employeeId)->select('emp_id', 'employee_name')->first();
        $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');
        // Retrieve HR requests where the company_id contains any of the given company IDs
        $this->hrRequests = EmpResignations::with('employee')
            ->join('employee_details', 'employee_details.emp_id', '=', 'emp_resignations.emp_id')
            ->where(function ($query) use ($companyIds) {
                foreach ($companyIds as $companyId) {
                    $query->orWhereRaw('JSON_CONTAINS(employee_details.company_id, ?)', [json_encode($companyId)]);
                }
            })
            ->select(
                'emp_resignations.emp_id',
                'emp_resignations.id',
                'emp_resignations.resignation_date',
                'emp_resignations.reason',
                'emp_resignations.mime_type',
                'emp_resignations.file_name',
                'emp_resignations.signature',
                'emp_resignations.created_at'
            );

        // Apply the status filter based on the active tab before fetching results
        if ($this->activeTab == 'pending') {
            $this->hrRequests = $this->hrRequests->where('emp_resignations.status', '5');
        } else {
            $this->hrRequests = $this->hrRequests->where('emp_resignations.status', '2');
        }

        // Get the results
        $this->hrRequests = $this->hrRequests->get();
        // dd( $this->hrRequests);
        // Count the number of HR requests
        // $this->hrRequestsCount = $this->hrRequests->count();
    }
}
