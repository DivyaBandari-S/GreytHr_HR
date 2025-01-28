<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\AddFavoriteReport;
use Livewire\Component;

class ReportsManagement extends Component
{
    public $activeSection = 'All';
    public $reportsGallery;

    public function mount() {
        $this->getReportsData();
    }

    public function getReportsData()
    {
        $this->reportsGallery = AddFavoriteReport::all();
    }
    // Method to switch between tabs
    public function toggleSection($tab)
    {
        $this->activeSection = $tab;
    }

    public function toggleStarred($id)
    {
        try {
            // Find the report by ID
            $report = AddFavoriteReport::find($id);

            // Check if the report exists
            if (!$report) {
                FlashMessageHelper::flashError('Report not found');
            }

            if ($report->favorite == true) {
                $report->favorite = false;
            } elseif ($report->favorite == false) {
                $report->favorite = true;
            }
            $report->save();
            $this->getReportsData();
            FlashMessageHelper::flashSuccess('Added to favorite successfully!');
            // Flash a success message
        } catch (\Exception $e) {
            // Handle any errors that occur
            FlashMessageHelper::flashError('An error occurred: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.reports-management', [
            'reportsGallery' => $this->reportsGallery
        ]);
    }
}
