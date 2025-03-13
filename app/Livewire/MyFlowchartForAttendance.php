<?php

namespace App\Livewire;

use Asantibanez\LivewireCharts\Models\PieChartModel;
use Livewire\Component;

class MyFlowchartForAttendance extends Component
{
    public $notyetin;

    public $latein;

    public $ontime;
    public function render()
    {
                $pieChartModel = (new PieChartModel())
                ->asDonut() // Set the chart as a doughnut chart
                ->addSlice('Not Yet In', $this->notyetin, 'rgb(184, 208, 221)')
                ->addSlice('Late In', $this->latein, 'rgb(192, 238, 249)')
                ->addSlice('Early Out', $this->ontime, color: 'rgb(255, 221, 189)')
                ->setJsonConfig([
                        'chart' => [
                'width' => 600, // Set the width of the chart
                'height' => 400, // Set the height of the chart
            ],
            
        
            ]);
           
        return view('livewire.my-flowchart-for-attendance', [
            'pieChartModel' => $pieChartModel,
        ]);
    }
}
