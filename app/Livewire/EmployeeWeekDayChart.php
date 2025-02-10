<?php

namespace App\Livewire;

use App\Helpers\FlashMessageHelper;
use App\Models\EmployeeDetails;
use App\Models\EmployeeWeekDay;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeWeekDayChart extends Component
{
    use WithPagination;
    public $currentYear;

    public $employeeweekdayid;
    public $employeeweekdayfirstname;

    public $employeeweekdaylastname;
    public $openViewPage=false;
    protected $paginationTheme = 'bootstrap';
    public $searchEmployee=0;

    public $employeeweekdayfromdate;
    
    public $employeeweekdaytodate;

    public $employeeweekdaythursday;

    public $employeeweekdayfriday;

 
    public $currentPage = 1;
    public $limit = 5;

    public $employeeweekdaysunday;

    public $deleteModal=false;
    public $employeeweekdaysaturday;
    public $employeeweekdaymonday;

    public $editemployeeweekdayid;

    public $editemployeeweekdayfirstname;

    public $editemployeeweekdayfromdate;

    public $editemployeeweekdaytodate;

    public $editemployeeweekdaysunday;

    public $editemployeeweekdaymonday;

    public $editemployeeweekdaytuesday;

    public $editemployeeweekdaywednesday;

    public $editemployeeweekdaythursday;

    public $editemployeeweekdayfriday;

    public $editemployeeweekdaysaturday;

    public $editemployeeweekdaymodifiedDate;

    public $editemployeeId;

    public $editemployeeweekdaylastname;
    public $employeeId;
    public $idfordeletingrecord;

    public $openEditPage=false;
    public $employeeweekdaymodifiedDate;
    public $employeeweekdaytuesday;

    public $employeeweekdaywednesday;
       public $selectedOption='All';
    public $previousYear;

    public $selectedYear;
  
    public $nextYear;
    
    public function mount()
    {
        $this->currentYear = Carbon::now()->year;
        $this->previousYear = $this->currentYear - 1;
        $this->nextYear = $this->currentYear + 1;
        $this->selectedYear=$this->currentYear;
    }

    public function updateSelectedYear()
    {
        $this->selectedYear=$this->selectedYear;
      
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
    }
    public function openDeleteModal($id)
    {

        $this->deleteModal=true;
        $this->idfordeletingrecord=$id;
    }

    public function cancelEmployeeWeekDetails()
    {
        $this->deleteModal=false;
    }

    public function deleteEmployeeWeekDetails($id)
    {
       
       $employeeweekdayChart=EmployeeWeekDay::find($this->idfordeletingrecord);
       $employeeweekdayChartfirstName=EmployeeDetails::where('emp_id',$employeeweekdayChart->emp_id)->value('first_name');
       $employeeweekdayChartlastName=EmployeeDetails::where('emp_id',$employeeweekdayChart->emp_id)->value('last_name');
       $this->deleteModal=false;
       FlashMessageHelper::flashSuccess("Employee Week Day Chart is deleted successfully for the employee {$employeeweekdayChartfirstName} {$employeeweekdayChartlastName} ({$employeeweekdayChart->emp_id}) over a period from {$employeeweekdayChart->from_date} to {$employeeweekdayChart->to_date}");  
       $employeeweekdayChart->delete();
    }
    public function callCreateWeekDay()
    {
        return redirect()->route('create-employee-weekday-chart');
    }

    public function viewEmployeeWeekDetails($id)
    {
        $this->openViewPage=true;
        $employeeweekday=EmployeeWeekDay::find($id);
        $this->employeeweekdayid=$employeeweekday->emp_id;
        $this->employeeweekdayfirstname=EmployeeDetails::where('emp_id',$this->employeeweekdayid)->value('first_name');
        $this->employeeweekdaylastname=EmployeeDetails::where('emp_id',$this->employeeweekdayid)->value('last_name');
        $this->employeeweekdayfromdate=$employeeweekday->from_date;
        $this->employeeweekdaytodate=$employeeweekday->to_date;
        $this->employeeweekdaysunday=$employeeweekday->sunday;
        $this->employeeweekdaymonday=$employeeweekday->monday;
        $this->employeeweekdaytuesday=$employeeweekday->tuesday;
        $this->employeeweekdaywednesday=$employeeweekday->wednesday;
        $this->employeeweekdaythursday=$employeeweekday->thursday;
        $this->employeeweekdayfriday=$employeeweekday->friday;
        $this->employeeweekdaysaturday=$employeeweekday->saturday;
        $this->employeeweekdaymodifiedDate=$employeeweekday->created_at;
    }
    public function closeWeekDayChart()
    {
        $this->openViewPage=false;
    }

    public function closeSWIPESR()
    {
        $this->openViewPage=false;
    }
    
    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    // Move to the next page if possible
    public function nextPage()
    {
        // Calculate total count and total pages
        $totalCount = EmployeeWeekDay::whereYear('from_date', $this->selectedYear)
            ->whereYear('to_date', $this->selectedYear)
            ->count();
        $totalPages = (int) ceil($totalCount / $this->limit);

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
        }
    }

    public function saveChanges()
    {
        // Optionally add validation here
    
        

        // Fetch the record using the stored employeeId
        $employeeWeekDay = EmployeeWeekDay::find($this->editemployeeId);

        $editemployeefname=EmployeeDetails::where('emp_id',$employeeWeekDay->emp_id)->value('first_name');
        $editemployeelname=EmployeeDetails::where('emp_id',$employeeWeekDay->emp_id)->value('last_name');
        if ($employeeWeekDay) {
            // Update the record with new values
            $employeeWeekDay->update([
                'from_date'    => $this->editemployeeweekdayfromdate,
                'to_date'      => $this->editemployeeweekdaytodate,
                'sunday'       => $this->editemployeeweekdaysunday,
                'monday'       => $this->editemployeeweekdaymonday,
                'tuesday'      => $this->editemployeeweekdaytuesday,
                'wednesday'    => $this->editemployeeweekdaywednesday,
                'thursday'     => $this->editemployeeweekdaythursday,
                'friday'       => $this->editemployeeweekdayfriday,
                'saturday'     => $this->editemployeeweekdaysaturday,
                'modified_date'=> Carbon::now(), // or use $this->employeeweekdaymodifiedDate if editing this field
            ]);
        }

        // Close the modal after saving
        $this->openEditPage = false;
        FlashMessageHelper::flashSuccess("Employee Week Day Chart for the employee {$editemployeefname} {$editemployeelname} ({$employeeWeekDay->emp_id}) has been updated successfully");
    }

    public function cancelEdit()
    {
        $this->openEditPage=false;
    }
    public function editEmployeeWeekDetails($id)
    {
        $employeeWeekDay = EmployeeWeekDay::find($id);
      
        if ($employeeWeekDay) {
            // Assign record values to component properties
            $this->editemployeeId = $employeeWeekDay->id;
            
            $this->editemployeeweekdayid = $employeeWeekDay->emp_id;
            $this->editemployeeweekdayfirstname = EmployeeDetails::where('emp_id',$this->editemployeeweekdayid)->value('first_name'); 
            $this->editemployeeweekdaylastname = EmployeeDetails::where('emp_id',$this->editemployeeweekdayid)->value('last_name'); // adjust field name as needed
            $this->editemployeeweekdayfromdate = $employeeWeekDay->from_date;
            $this->editemployeeweekdaytodate = $employeeWeekDay->to_date;
            $this->editemployeeweekdaysunday = $employeeWeekDay->sunday;
            $this->editemployeeweekdaymonday = $employeeWeekDay->monday;
            $this->editemployeeweekdaytuesday = $employeeWeekDay->tuesday;
            $this->editemployeeweekdaywednesday = $employeeWeekDay->wednesday;
            $this->editemployeeweekdaythursday = $employeeWeekDay->thursday;
            $this->editemployeeweekdayfriday = $employeeWeekDay->friday;
            $this->editemployeeweekdaysaturday = $employeeWeekDay->saturday;
            $this->editemployeeweekdaymodifiedDate = Carbon::parse($employeeWeekDay->created_at)->format('Y-m-d');
           
            
        }

        $this->openEditPage=true;
          
    }
    public function updateSelected($option)
    {
        $this->selectedOption = $option;
    }
    public function render()
    {
        $offset = ($this->currentPage - 1) * $this->limit;

        $totalCount = EmployeeWeekDay::whereYear('from_date', $this->selectedYear)
            ->whereYear('to_date', $this->selectedYear)
            ->count();
        $employeeWeekdayChart = EmployeeWeekDay::whereYear('from_date', $this->selectedYear)
        ->whereYear('to_date', $this->selectedYear)
        ->orderByDesc('created_at')
        ->skip($offset)
        ->take($this->limit)
        ->get(); // Change 5 to your preferred per-page limit

        // Fetch employee details efficiently (Avoids fetching inside the Blade loop)
        $employeeIds = $employeeWeekdayChart->pluck('emp_id');
        $employeeNames = EmployeeDetails::whereIn('emp_id', $employeeIds)
            ->get()
            ->keyBy('emp_id');

        $totalPages = (int) ceil($totalCount / $this->limit);
        return view('livewire.employee-week-day-chart', [
            'employeeWeekdayChart' => $employeeWeekdayChart,
            'totalCount'=>$totalCount,
            'totalPages'=>$totalPages,
            'employeeNames' => $employeeNames
        ]);
    }
}
