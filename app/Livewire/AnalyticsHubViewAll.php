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
    public function mount()
    {
        // Load folders from the database when the component mounts
        $this->folders = AnalyticHubCreateFolder::all()->toArray();
    }
    public function openModal()
    {
        $this->isModalOpen = true; // Open the modal
    }

    public function closeModal()
    {
        $this->isModalOpen = false; // Close the modal
    }
    public $starredFiles = [];

    // Other properties...

    public function toggleStar($fileName,$folderName)
    {
        if (in_array($fileName, $this->starredFiles)) {

            // Remove from starred files
            $this->starredFiles = array_diff($this->starredFiles, [$fileName]);
        } else {
            // Add to starred files
            $this->starredFiles[] = $fileName;
        }
    }

    public function isStarred($fileName)
    {
        return in_array($fileName, $this->starredFiles);
    }

    public function saveFolder()
    {
        if ($this->folderName && $this->fileName) {
            // Check if folder already exists
            $folder = AnalyticHubCreateFolder::where('name', $this->folderName)->first();

            if ($folder) {
                // If folder exists, add the file to the existing folder
                $files = $folder->files ?? [];
                $files[] = $this->fileName;

                // Update the folder in the database
                $folder->update(['files' => $files]);
            } else {
                // If folder does not exist, create a new folder
                AnalyticHubCreateFolder::create([
                    'name' => $this->folderName,
                    'files' => [$this->fileName], // Initialize with the new file
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
   
    public function goBack()
    {
        return redirect()->route('analytics-hub'); 
    }
   
    public function render()
    {
        return view('livewire.analytics-hub-view-all');
    }
}
