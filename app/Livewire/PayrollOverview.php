<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class PayrollOverview extends Component
{
    public $isShowHelp = true;
    public $months = [];
    public $selectedMonth;
    public $selectedYear;
    public $selectedMonthName;
    public $cutoffStart;
    public $cutoffEnd;
    public $chartData = [];
    public $activeTab1 ='lock';
    public $activeTab2 ='hold';
    public $activeTab3 ='release';
    public $activeTab4 ='lock';

    public function mount()
    {
        $this->chartData = [
        'labels' => ['Net Pay','Gross Pay', 'Deductions',],
        'values' => [3232007,3588547,  356540, 31],

    ];
        $this->initializeFinancialYear();
    }

    private function initializeFinancialYear()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Determine financial year
        if ($currentMonth < 4) {
            $this->selectedYear = $currentYear - 1; // Financial year starts in the previous year
        } else {
            $this->selectedYear = $currentYear;
        }

        // Ensure the correct year is selected for Jan, Feb, Mar
        $this->selectedMonth = $currentMonth-1;
        if ($currentMonth < 4) {
            $this->selectedYear = $currentYear; // Fix: Use the current year for Jan, Feb, Mar
        }
        $this->selectedMonthName = Carbon::create(  $this->selectedYear,$this->selectedMonth, 1)->format('F');
        $this->cutoffStart = Carbon::create($this->selectedYear, $this->selectedMonth, 26)->subMonth()->format('d M, Y');
        $this->cutoffEnd = Carbon::create($this->selectedYear, $this->selectedMonth, 25)->format('d M, Y');

        $this->generateFinancialYearMonths();
    }

    public function generateFinancialYearMonths()
    {
        $this->months = [];

        // April to March
        for ($i = 4; $i <= 12; $i++) {
            $this->months[] = [
                'name' => Carbon::create(null, $i, 1)->format('M'),
                'year' => $this->selectedYear-1,
                'number' => $i,
            ];
        }

        for ($i = 1; $i <= 3; $i++) {
            $this->months[] = [
                'name' => Carbon::create(null, $i, 1)->format('M'),
                'year' => $this->selectedYear ,
                'number' => $i,
            ];
        }
    }

    public function selectMonth($month, $year)
    {

        $this->selectedMonth = $month;
        $this->selectedYear = $year;
        $this->selectedMonthName = Carbon::create($year, $month, 1)->format('F');

        $this->cutoffStart = Carbon::create($year, $month, 26)->subMonth()->format('d M, Y');
        $this->cutoffEnd = Carbon::create($year, $month, 25)->format('d M, Y');
    }

    public function previousFinancialYear()
    {
        $this->selectedYear--;
        $this->generateFinancialYearMonths();
    }

    public function nextFinancialYear()
    {
        $this->selectedYear++;
        $this->generateFinancialYearMonths();
    }

    public function toogleHelp()
    {
        $this->isShowHelp = !$this->isShowHelp;
    }
    public function render()
    {
        // dd( $this->cutoffStart, $this->cutoffEnd);
        return view('livewire.payroll-overview');
    }
}
