<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmployeeDetails;
use App\Models\EmpResignations;
use App\Models\Hr;

class Resignationrequests extends Component
{
    public $hrRequests;
    public $loginEmployee;
    public $imageUrl;
    public $showImageDialog=false;

    public function showImage($url)
    {
        // dd( $this->hrRequests);
        $this->imageUrl = $url;
        $this->showImageDialog = true;
    }

    public function closeImageDialog(){
        $this->showImageDialog = false;
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
        $employeeId = auth()->guard('hr')->user()->emp_id;

        // $this->loginEmployee = Hr::where('emp_id', $employeeId)->select('emp_id', 'employee_name')->first();
        $companyId = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

        $this->getHrRequests($companyId);
    }

    public function render()
    {
        return view('livewire.resignationrequests');
    }

    public function getHrRequests($companyIds)
    {
        // Retrieve HR requests where the company_id contains any of the given company IDs
        $this->hrRequests = EmpResignations::with('employee')->join('employee_details', 'employee_details.emp_id', '=', 'emp_resignations.emp_id')
            ->whereIn('emp_resignations.status', ['5','2'])
            ->where(function ($query) use ($companyIds) {
                foreach ($companyIds as $companyId) {
                    $query->orWhereRaw('JSON_CONTAINS(employee_details.company_id, ?)', [json_encode($companyId)]);
                }
            }) ->select(
                'emp_resignations.emp_id',
                'emp_resignations.id',
                'emp_resignations.resignation_date',
                'emp_resignations.reason',
                'emp_resignations.mime_type',
                'emp_resignations.file_name',
                'emp_resignations.signature',
                'emp_resignations.created_at',
            )
            ->get();
        // dd( $this->hrRequests);
        // Count the number of HR requests
        // $this->hrRequestsCount = $this->hrRequests->count();
    }

}
