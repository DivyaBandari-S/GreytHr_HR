<?php

namespace App\Livewire;

use App\Models\AnalyticHubCreateFolder;
use Livewire\Component;

class AnalyticsHubViewAll extends Component
{
    public $activeTab = 'all';
    public $isOpenEventList = false;
    public $isOpenMySheets = false;
    public $isOpenEmployeeList = false;
    public $folderName;
    public $fileName;
    public $folders = [];
    public $isModalOpen = false;

    public function openModal()
    {
        $this->isModalOpen = true; // Open the modal
    }

    public function closeModal()
    {
        $this->isModalOpen = false; // Close the modal
    }


    // Other properties...

    public function toggleStar($fileName, $folderName)
    {
        // Find the folder by name
        $folder = AnalyticHubCreateFolder::where('name', $folderName)->first();
    
        if ($folder) {
            // Get the files array from the folder
            $files = $folder->files;
    
            // Toggle the starred status
            foreach ($files as &$file) {
                if ($file['name'] === $fileName) {
                    $file['isStarred'] = !$file['isStarred']; // Toggle the starred status
                    break;
                }
            }
    
            // Save the updated files back to the folder
            $folder->update(['files' => $files]);
    
            // Update the local state directly instead of reloading from the database
            $this->folders = AnalyticHubCreateFolder::all();
         // Consider removing this line for better state management
        }
    }


    
    
    
    

   
   public function autoValidate(){
    $this->validate([
        'folderName' => 'required|string|max:255',
        'fileName' => 'required|string|max:255',
    ], [
        'folderName.required' => 'Folder Name is required.',
        'fileName.required' => 'File Name is required.',
    ]);
   }
   public function resetFields()
   {
       $this->folderName = null;
       $this->fileName = null;
   }

   public function saveFolder()
{
    $this->autoValidate();
    if ($this->folderName && $this->fileName) {
        // Check if folder already exists
        $folder = AnalyticHubCreateFolder::where('name', $this->folderName)->first();

        // Create the new file structure
        $newFile = [
            'name' => $this->fileName,
            'isStarred' => false, // Default value
        ];

        if ($folder) {
            // If folder exists, add the file to the existing folder
            $files = $folder->files ?? [];
            $files[] = $newFile; // Add the new file structure

            // Update the folder in the database
            $folder->update(['files' => $files]);
        } else {
            // If folder does not exist, create a new folder
            AnalyticHubCreateFolder::create([
                'name' => $this->folderName,
                'files' => [$newFile], // Initialize with the new file
            ]);
        }

        // Clear input fields
        $this->folderName = '';
        $this->fileName = '';
        $this->closeModal(); // Close the modal after saving

        // Reload folders from the database to reflect the changes
        $this->folders = AnalyticHubCreateFolder::all();
    }
}


    public function toggleAccordion($index)
    {
        // Toggle the open state of the specified folder
        $this->isOpenEventList = $this->isOpenEventList === $index ? false : $index;
    }
    public $isOpenStarred = false; // Add this property to manage the open state for starred files

public function toggleAccordionStarred($index)
{
    $this->isOpenStarred = $this->isOpenStarred === $index ? false : $index;
}
   
    public function goBack()
    {
        return redirect()->route('analytics-hub'); 
    }
   
    public function render()
    {
       
        return view('livewire.analytics-hub-view-all');
    }
    public function mount(){
        $this->folders = AnalyticHubCreateFolder::all()->toArray();
    }
}
