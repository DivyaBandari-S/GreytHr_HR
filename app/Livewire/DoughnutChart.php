<?php

namespace App\Livewire;

use Livewire\Component;

class DoughnutChart extends Component
{
    public $labels = [];
    public $data = [];
    public $backgroundColor = [];

    public function mount()
    {
        // Example data for the chart
        $this->labels = ['Red', 'Blue', 'Yellow', 'Green', 'Purple'];
        $this->data = [12, 19, 3, 5, 2];
        $this->backgroundColor = [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)'
        ];
    }

    public function render()
    {
        return view('livewire.doughnut-chart');
    }
}
