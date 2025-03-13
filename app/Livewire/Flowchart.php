<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Flowchart extends Component
{

    public $chartData = [];
    public $ceoData = [];
    public $hierarchyData = [];


    public $selectedEmployeeId;
    public $chairman;
    public function mount($selectedEmployeeId)
    {
        $this->selectedEmployeeId=$selectedEmployeeId;
        if(!empty($this->selectedEmployeeId))
        {
           
            $this->chairman = EmployeeDetails::where('emp_id', $this->selectedEmployeeId)
            ->select('emp_id', 'first_name', 'last_name', 'job_role','image','gender')
            ->first();
        }
        else
        {
            $this->chairman = EmployeeDetails::where('job_role', 'Chairman')
            ->select('emp_id', 'first_name', 'last_name', 'job_role','image','gender')
            ->first();
        }
        
        $this->loadFlowchartData();
     
    }

    private function loadFlowchartData()
    {
        Log::info('Fetching CEO (Chairman) data...');

        if(!empty($this->selectedEmployeeId))
        {
            $this->ceoData = EmployeeDetails::where('emp_id', $this->selectedEmployeeId)
            ->select('emp_id', 'first_name', 'last_name', 'job_role','image','gender')
            ->get();
        }
        else
        {
            $this->ceoData = EmployeeDetails::where('job_role', 'Chairman')
            ->select('emp_id', 'first_name', 'last_name', 'job_role','image','gender')
            ->get();
        }
       

        if (!empty($this->ceoData)) {
            Log::info('CEO data fetched successfully.', ['count' => count($this->ceoData)]);
            
            foreach ($this->ceoData as $ceo) {
                $this->hierarchyData[$ceo->emp_id] = [  // ✅ Correct! Using object notation
                    'info' => $ceo,
                    'subordinates' => $this->loadSubordinates($ceo->emp_id),
                ];
            }
        }
       
               
    }

    private function loadSubordinates($managerId)
    {
        $employees = EmployeeDetails::where('manager_id', $managerId)
            ->select('emp_id', 'first_name', 'last_name', 'job_role','image','gender')
            ->get();

        $subordinates = [];
        foreach ($employees as $employee) {
            $subordinates[$employee['emp_id']] = [
                'info' => $employee,
                'subordinates' => $this->loadSubordinates($employee['emp_id']),
            ];
        }

        return $subordinates;
    }

   

   
    public function render()
    {
        
      
        
        return view('livewire.flowchart', [
            'hierarchy' => $this->hierarchyData,
        ]);
       
    }
}
