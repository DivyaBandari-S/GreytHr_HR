<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\UploadBulkPhotos;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmpBulkPhotoUpload extends Component
{
    use WithFileUploads;
    public $showHistory = true;
    public $currentStep = 1;
    public $upload;
    public $zip_file;
    public $errorMessages = [];
    public $uploaded_by;
    public $uploaded_at;
    public $log;
    public $status;
    public $searchTerm;
    public $selecetedEmployee;
    public $showUploadContent  = false;

    public $folderId;
    public $employeeIds = [];
    public $perPage = 4;  // Define how many images per page
    public $currentPage = 1; // Default to page 1
    public $totalImages = 0; // Total images in the array
    public $totalPages;
    public $imagePaths;
    public $totalUploaded;
    public $paginatedData;
    public $searchQuery = '';
    public $currentPageUploaded = 1; // Ensure it's initialized properly
    public $totaluploadedPages;

    public $perpageUploaded = 10;

    public function mount()
    {
        $this->getUploadedZipFiles();
    }
    public function toggleUploadBtn()
    {
        $this->showHistory = !$this->showHistory;
        $this->showUploadContent  = !$this->showUploadContent;
    }
    public function nextStep()
    {

        if ($this->currentStep < 2) {
            $this->currentStep++;
        }
    }

    public function gotoBack()
    {
        if ($this->currentStep) {
            $this->currentStep--;
        }
    }

    public function toggleEmployeeContainer($index)
    {
        // Reset search term (if required)
        $this->searchTerm = null;

        // Initialize the array if it's not set
        if (!isset($this->openEmployeeContainers) || !is_array($this->openEmployeeContainers)) {
            $this->openEmployeeContainers = [];
        }

        // Make sure the specific index is set to a boolean value before toggling
        if (!isset($this->openEmployeeContainers[$index])) {
            // If it's not set, initialize the index to `false` (assuming it's a boolean toggle)
            $this->openEmployeeContainers[$index] = false;
        }

        // Toggle the visibility for the specific index (flip the boolean value)
        $this->openEmployeeContainers[$index] = !$this->openEmployeeContainers[$index];
    }

    public function updatedSearchQuery()
    {
        // This method will be called whenever $searchQuery is updated (due to wire:model.live)
        $this->getUploadedZipFiles();
    }
    public $uploadedHistory;
    //getUp,oaded zip file hostory
    public function getUploadedZipFiles()
    {
        try {
            // Build the query to fetch uploaded photos
            $query = UploadBulkPhotos::query();

            // Apply search filter based on searchEmployee (emp_id, first_name, last_name)
            if (!empty($this->searchQuery)) {
                $query
                      ->Where('status', 'like', "%{$this->searchQuery}%")
                      ->orWhere('file_name', 'like', "%{$this->searchQuery}%");
            }

            // Execute the query and get the result
            $this->uploadedHistory = $query->get();
    
            // Ensure that the data exists and calculate pagination
            $this->totalUploaded = count($this->uploadedHistory);
            $this->totaluploadedPages = ceil($this->totalUploaded / $this->perpageUploaded);
    
            // Pagination logic to fetch the current page's data
            $start = ($this->currentPageUploaded - 1) * $this->perpageUploaded;
            $end = $start + $this->perpageUploaded;
    
            // Ensure we don't exceed the bounds of the array
            $this->paginatedData = [];
            for ($i = $start; $i < $end && $i < $this->totalUploaded; $i++) {
                $this->paginatedData[] = $this->uploadedHistory[$i];
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the process
            Log::error('Error in getUploadedZipFiles: ' . $e->getMessage());
            // Optionally, set a user-friendly message for the user
            session()->flash('error', 'There was an error retrieving the uploaded zip files.');
    
            // Ensure paginated data is set to an empty array in case of an error
            $this->paginatedData = [];
        }
    }


    public function setPageUploaded($page)
    {
        try {
            // Ensure the page number is within the valid range
            $this->currentPageUploaded = max(1, min($page, $this->totaluploadedPages));

            // Get the paginated image paths
            $this->paginatedData = $this->getPaginatedUploads();
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('Error in setPageUploaded: ' . $e->getMessage());
            // Optionally, set a user-friendly message
            FlashMessageHelper::flashError('There was an error setting the page.');
        }
    }

    public function getPaginatedUploads()
    {
        try {
            // Ensure uploadedHistory is an array
            $this->uploadedHistory = $this->uploadedHistory ?? [];

            // Calculate the starting index based on the current page
            $start = ($this->currentPageUploaded - 1) * $this->perpageUploaded;
            $end = $start + $this->perpageUploaded;

            // Ensure we don't exceed the bounds of the array
            $this->paginatedData = [];
            for ($i = $start; $i < $end && $i < count($this->uploadedHistory); $i++) {
                $this->paginatedData[] = $this->uploadedHistory[$i];
            }

            return $this->paginatedData;
        } catch (\Exception $e) {
            // Handle any exceptions
            Log::error('Error in getPaginatedUploads: ' . $e->getMessage());
            // Optionally, set a user-friendly message
            FlashMessageHelper::flashError('There was an error fetching paginated uploads.');
        }
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

    public $selectedEmployees = [];
    public $openEmployeeContainers = [];
    public function getSelectedEmployee($empId, $imagePath, $index)
    {
        // Initialize selected employees array if it's not set
        if (!isset($this->selectedEmployees)) {
            $this->selectedEmployees = [];
        }

        // Check for duplicate assignments
        if (isset($this->selectedEmployees[$empId])) {
            // If the employee is already assigned an image, don't add again
            Log::warning('Employee already assigned an image: ' . $empId);
            return;
        }

        // Add the employee and their assigned image if not already added
        $this->selectedEmployees[$empId] = $imagePath;
        $this->openEmployeeContainers[$index] = false;
    }

    public function storeImageOfEmployee()
    {
        set_time_limit(300);
        // // Reset the error messages array before each validation
        $this->errorMessages = [];
        // Validate that all images are assigned to employees
        if (is_array($this->imagePaths) && count($this->selectedEmployees) < count($this->imagePaths)) {
            // Flash an error if some images are not assigned
            FlashMessageHelper::flashError('Please assign all images to employees before proceeding.');
            return;
        }
        try {

            if ($this->selectedEmployees) {
                foreach ($this->selectedEmployees as $empID => $imagePath) {
                    // Fetch employee data
                    $data = EmployeeDetails::where('emp_id', $empID)->first();

                    if ($data) {
                        try {
                            // Check if imagePath is a URL
                            if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                                $localPath = public_path(str_replace(url('/'), '', $imagePath));
                                // If it's a URL, fetch the image content from the URL directly
                                $imageBinary = base64_encode(file_get_contents($localPath));
                            } else {
                                // If it's a local file, we need to remove the URL part and use the relative path
                                $localPath = public_path(str_replace(url('/'), '', $imagePath));  // Remove the base URL

                                // Check if the file exists
                                if (file_exists($localPath)) {
                                    $imageBinary = base64_encode(file_get_contents($localPath));
                                } else {
                                    Log::error('File not found at ' . $localPath);
                                }
                            }

                            // Update the employee's image field
                            $data->image = $imageBinary;
                            // Save the updated record
                            $data->save();
                            // Log or notify that the directory has been deleted
                            Log::info('Successfully deleted extracted_images directory after saving the image for employee: ' . $empID);
                        } catch (\Exception $e) {
                            // Catch any error specific to image processing or saving
                            Log::error('Error processing image for employee ' . $empID . ': ' . $e->getMessage());
                        }
                    } else {
                        // Handle case when employee data is not found
                        Log::warning('Employee not found for emp_id: ' . $empID);
                    }
                }
                FlashMessageHelper::flashSuccess('Profile updated');
                // Delete the extracted files directory after saving the image
                $this->deleteExtractedFiles(public_path('extracted_images'));
            }
        } catch (\Exception $e) {
            // Catch any unexpected error in the main method
            Log::error('General error: ' . $e->getMessage());
        }
    }

    // Recursive method to delete files and directories
    public function deleteExtractedFiles($path)
    {
        // Process in chunks (if directory is large)
        $files = array_diff(scandir($path), ['.', '..']);
        $chunkedFiles = array_chunk($files, 100); // Process files in chunks of 100
        foreach ($chunkedFiles as $chunk) {
            foreach ($chunk as $file) {
                $filePath = $path . DIRECTORY_SEPARATOR . $file;
                if (is_file($filePath)) {
                    unlink($filePath);
                } elseif (is_dir($filePath)) {
                    $this->deleteExtractedFiles($filePath);
                }
            }
        }
        rmdir($path); // Remove the directory after processing
    }

    //cancel updating images
    public function cancelUpdating($index)
    {
        // Get the corresponding record ID from your source
        $recordId = UploadBulkPhotos::find($index);

        if ($recordId) {
            try {
                // Update the record using the update() method
                $updated = $recordId->update([
                    'status' => 'Cancelled',
                    'log' => 'File Association is cancelled.',
                    'updated_at' => now() // Ensure updated_at is also updated
                ]);

                // Log the success of the update
                if ($updated) {
                    Log::debug('Record updated successfully to Cancelled.');
                } else {
                    Log::debug('Failed to update the record.');
                }

                // Clear image paths and proceed with other actions
                $this->imagePaths = [];
                $this->gotoBack();

                // Flash success message
                FlashMessageHelper::flashSuccess('Status updated to Cancelled for record ID');
                $this->deleteExtractedFiles(public_path('extracted_images'));
                $this->toggleUploadBtn();
            } catch (\Exception $e) {
                // Log error if there's an issue
                Log::error('Error updating status to cancelled: ' . $e->getMessage());
                FlashMessageHelper::flashError('An error occurred while updating the status.');
            }
        } else {
            FlashMessageHelper::flashError('Record not found for the given ID');
        }
    }



    protected $rules = [
        'zip_file' => 'required|file|mimes:zip|max:10240',
    ];

    public function validateProperty($field)
    {
        $this->validateOnly($field);
    }
    public function UploadBulkZipFile()
    {
        // Validate the file based on the rules defined
        $this->validate([
            'zip_file' => 'required|file|mimes:zip|max:10240',
        ], [
            'zip_file.required' => 'Please upload a zip file.',
            'zip_file.mimes' => 'Only zip files are allowed.',
            'zip_file.max' => 'The file size must be less than 10MB.',
        ]);
        try {
            // Get logged-in employee ID
            $loggedInEmpId = auth()->guard('hr')->user()->emp_id;

            // Check if the zip file is uploaded and valid
            if ($this->zip_file && $this->zip_file->isValid()) {
                // Get MIME type and original file name
                $mimeType = $this->zip_file->getMimeType();  // MIME type of the file (e.g., application/zip)
                $fileName = $this->zip_file->getClientOriginalName();  // Original file name (e.g., myfile.zip)

                // Read the file content and store it as a binary blob
                $zipFileContent = file_get_contents($this->zip_file->getRealPath());
                // Create a new record in the database with the binary content, mime_type, and file_name
                $this->upload = UploadBulkPhotos::create([
                    'status' => 'Completed',
                    'uploaded_at' => now(),
                    'uploaded_by' => $loggedInEmpId,
                    'zip_file' => $zipFileContent,
                    'mime_type' => $mimeType,  // Store the MIME type
                    'file_name' => $fileName,  // Store the original file name
                    'log' => 'File uploaded successfully',
                ]);
                // Call the extractZipFile method after successful file upload
                $this->extractZipFile($this->upload);

                FlashMessageHelper::flashSuccess('Zip file uploaded and images extraction started!');
                $this->zip_file = null;
                $this->nextStep();
            } else {
                // If file is not valid, throw an exception
                throw new \Exception('File is not valid or not uploaded');
            }
        } catch (\Exception $e) {
            Log::error('Error uploading file', [
                'error_message' => $e->getMessage(),
                'emp_id' => auth()->guard('hr')->user()->emp_id ?? 'N/A',
                'file' => $this->zip_file ?? 'N/A',
            ]);
        }
    }
    public $paginatedImages;

    public function extractZipFile($upload)
    {
        try {
            // Define the public extraction path (inside the public folder)
            $zip = new \ZipArchive;
            $tempExtractPath = public_path('extracted_images/' . $upload->id); // Changed to public_path
            // Create the directory if it doesn't exist
            if (!file_exists($tempExtractPath)) {
                mkdir($tempExtractPath, 0777, true); // Ensure directory is created
            }

            // Open and extract the ZIP file
            if ($zip->open($this->zip_file->getRealPath()) === true) {
                $zip->extractTo($tempExtractPath);
                $zip->close();
            } else {
                throw new \Exception('Failed to extract ZIP file');
            }
            // Recursively scan the extracted folder for images
            $extractedFiles = [];
            $this->scanDirectoryForImages($tempExtractPath, $extractedFiles, $upload);

            // Instead of storing extracted images in the database, we'll just pass the paths to frontend
            $imagePaths = [];
            foreach ($extractedFiles as $file) {
                // Add the file paths, pointing to the public directory
                // Use the public URL for the extracted images (relative path from the public folder)
                $this->imagePaths[] = asset('extracted_images/' . $upload->id . '/' . $file);
            }
            // Pass the image paths to frontend (you can also store them temporarily in a session if needed)
            session()->put('extracted_images_' . $upload->id, $this->imagePaths);
            // Set total images count
            $this->totalImages = count($this->imagePaths);
            $this->totalPages = ceil($this->totalImages / $this->perPage);
            $this->paginatedImages = array_slice($this->imagePaths, ($this->currentPage - 1) * $this->perPage, $this->perPage);

            FlashMessageHelper::flashSuccess('Images extracted successfully!');
        } catch (\Exception $e) {
            Log::error('Error extracting ZIP file', [
                'error_message' => $e->getMessage(),
                'upload_id' => $upload->id ?? 'N/A',
            ]);
            FlashMessageHelper::flashError('Failed to extract images.');
        }
    }

    public function setPage($page)
    {
        // Ensure the page number is within the valid range
        $this->currentPage = max(1, min($page, ceil($this->totalImages / $this->perPage)));

        // Get the paginated image paths and store them in the component property
        $this->paginatedImages = $this->getPaginatedImages();
    }


    public function getPaginatedImages()
    {
        // Ensure imagePaths is not null and is an array
        $imagePaths = $this->imagePaths ?? [];

        // Use array_slice to paginate the image paths array
        return array_slice($imagePaths, ($this->currentPage - 1) * $this->perPage, $this->perPage);
    }


    private function scanDirectoryForImages($dir, &$extractedFiles, $upload)
    {
        $files = scandir($dir);

        foreach ($files as $file) {
            $filePath = $dir . '/' . $file;

            // Skip the current and parent directory references
            if ($file === '.' || $file === '..') {
                continue;
            }

            // If it's a directory, recurse into it
            if (is_dir($filePath)) {
                $this->scanDirectoryForImages($filePath, $extractedFiles, $upload);
            } elseif (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                // If it's an image, add it to the extractedFiles array
                $relativePath = str_replace(public_path('extracted_images/' . $upload->id . '/'), '', $filePath);
                $extractedFiles[] = $relativePath;
            }
        }
    }


    public function downloadZipFile($id)
    {
        try {
            // Retrieve the record by ID
            $downloadData = UploadBulkPhotos::find($id);

            // Check if the file exists
            if ($downloadData && $downloadData->zip_file) {
                // Get the binary content of the zip file
                $fileContent = $downloadData->zip_file;
                $fileName = $downloadData->file_name; // You can set a default name if needed

                // Debug: Check if fileContent is non-empty
                if (empty($fileContent)) {
                    Log::error("Error: File content is empty for file ID: {$id}");
                    FlashMessageHelper::flashError("File content is empty for file ID: {$id}");
                    return;
                }

                // Debug: Check file name
                Log::info("Preparing to download file: {$fileName}");

                // Return the response to download the file
                return response()->stream(function () use ($fileContent) {
                    echo $fileContent;
                }, 200, [
                    'Content-Type' => $downloadData->mime_type,  // Example: 'application/zip'
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                ]);
            } else {
                // Handle case if the file is not found
                FlashMessageHelper::flashError('File not found.');
            }
        } catch (\Exception $e) {
            // Catch any exceptions and log the error
            Log::error('Error downloading file', [
                'error_message' => $e->getMessage(),
                'file_id' => $id,
            ]);

            // Handle the error (could display a friendly message, etc.)
            FlashMessageHelper::flashError('An error occurred while downloading the file. Please try again later.');
        }
    }




    public function render()
    {
        return view('livewire.emp-bulk-photo-upload', [
            // 'imagePaths' => $this->imagePaths,
            'uploadedHistory' => $this->uploadedHistory,
            'upload' => $this->upload,
            'paginatedImages' => $this->paginatedImages,
            'currentPage' => $this->currentPage,
            'totalImages' => $this->totalImages,
            'totalPages' => $this->totalPages,
            'perpageUploaded' => $this->perpageUploaded,
            'currentPageUploaded' => $this->currentPageUploaded,
            'paginatedData' => $this->paginatedData,
            'totaluploadedPages' => $this->totaluploadedPages,
            'totalUploaded' => $this->totalUploaded

        ]);
    }
}
