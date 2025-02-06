<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
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
    public $uploaded_by;
    public $uploaded_at;
    public $log;
    public $status;
    public $showUploadContent  = false;

    public function toggleUploadBtn()
    {
        $this->showHistory = false;
        $this->showUploadContent  = true;
    }
    public function nextStep()
    {

        if ($this->currentStep < 2) {
            $this->currentStep++;
        }
    }

    protected $rules = [
        'zip_file' => 'required|file|mimes:zip|max:10240',
    ];

    public function UploadBulkZipFile()
    {
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
                $upload = UploadBulkPhotos::create([
                    'status' => 'pending',
                    'uploaded_at' => now(),
                    'uploaded_by' => $loggedInEmpId,
                    'zip_file' => $zipFileContent,
                    'mime_type' => $mimeType,  // Store the MIME type
                    'file_name' => $fileName,  // Store the original file name
                    'log' => 'File uploaded successfully',
                ]);
                // Call the extractZipFile method after successful file upload
                $this->extractZipFile($upload);

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

            // Catch any exceptions and handle accordingly
            dd('Error: ' . $e->getMessage()); // You can log this error or return a user-friendly message
        }
    }
    public $imagePaths;
    public function extractZipFile($upload)
    {
        try {
            // Define the temporary extraction path
            $zip = new \ZipArchive;
            $tempExtractPath = storage_path('app/public/extracted_images/' . $upload->id);
            // Create the directory if it doesn't exist
            if (!file_exists($tempExtractPath)) {
                mkdir($tempEaxtractPath, 0777, true); // Ensure directory is created
            }

            // Open and extract the ZIP file
            if ($zip->open($this->zip_file->getRealPath()) === true) {
                $zip->extractTo($tempExtractPath);
                $zip->close();
            } else {
                throw new \Exception('Failed to extract ZIP file');
            }

            // Scan the extracted folder for images
            $extractedFiles = [];

            $files = scandir($tempExtractPath);

            foreach ($files as $file) {
                // Add only image files (JPG, PNG, etc.)
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $extractedFiles[] = $file;
                }
            }

            // Instead of storing extracted images in the database, we'll just pass the paths to frontend
            $imagePaths = [];
            foreach ($extractedFiles as $file) {
                // Add the file paths, pointing to the temporary directory
                $this->imagePaths[] = asset('storage/app/public/extracted_images/' . $upload->id . '/' . $file);
            }
            // Pass the image paths to frontend (you can also store them temporarily in a session if needed)
            session()->put('extracted_images_' . $upload->id, $imagePaths);

            FlashMessageHelper::flashSuccess('Images extracted successfully!');
        } catch (\Exception $e) {
            Log::error('Error extracting ZIP file', [
                'error_message' => $e->getMessage(),
                'upload_id' => $upload->id ?? 'N/A',
            ]);
            FlashMessageHelper::flashError('Failed to extract images.');
        }
    }

    // Helper function to delete the extracted files after use (optional)
    private function deleteExtractedFiles($path)
    {
        // Recursively delete the extracted files and directories
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $path . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    $this->deleteExtractedFiles($filePath);
                } else {
                    unlink($filePath);
                }
            }
            rmdir($path); // Remove the directory after deleting files
        }
    }



    public function render()
    {
        return view('livewire.emp-bulk-photo-upload', [
            'imagePaths' => $this->imagePaths
        ]);
    }
}
