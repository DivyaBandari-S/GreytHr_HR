<?php

namespace App\Livewire;

use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Livewire\Component;

class MyFlowchart extends Component
{
    public function render()
    {
        $columnChartModel = (new ColumnChartModel())
        ->setTitle('Flowchart Example')
        ->addColumn('Step 1', 100, '#f6ad55')
        ->addColumn('Step 2', 200, '#fc8181')
        ->addColumn('Step 3', 300, '#90cdf4');
       

    return view('livewire.my-flowchart', [
        'columnChartModel' => $columnChartModel,
    ]);
        
    }
}
