<?php

namespace App\Livewire;

use Barryvdh\DomPDF\PDF;
use Livewire\Component;

class ExportOrganisationChart extends Component
{
    public function exportToPDF()
    {
        // Example data to pass to the PDF template
        $data = [
            'chartTitle' => 'HR Organisation Chart',
            'employees' => [
                ['name' => 'John Doe', 'position' => 'Manager'],
                ['name' => 'Jane Smith', 'position' => 'Assistant Manager'],
                ['name' => 'Sam Johnson', 'position' => 'HR Executive'],
            ],
        ];

        // Load the Blade view and generate the PDF
        $pdf = PDF::loadView('exports.hr-organisation-chart-pdf', $data);

        // Stream the PDF for download
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'hr_organisation_chart.pdf'
        );
    }
    public function render()
    {
        return view('livewire.export-organisation-chart');
    }
}
