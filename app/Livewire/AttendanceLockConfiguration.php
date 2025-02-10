<?php

namespace App\Livewire;

use App\Models\LockConfiguration;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceLockConfiguration extends Component
{
    use WithPagination;

    public $currentPage=1;

    public $limit = 5;
    protected $paginationTheme = 'bootstrap';
    public function addLockConfiguration()
    {
        return redirect()->route('create-new-lock-configuration-page');
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
        $totalCount = LockConfiguration::count();
        $totalPages = (int) ceil($totalCount / $this->limit);

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
        }
    }
    public function setPage($page)
    {
        $this->currentPage = $page;
    }
    public function render()
    {
        $offset = ($this->currentPage - 1) * $this->limit;
        $totalCount = LockConfiguration::count();
        $lockConfiguration = LockConfiguration::skip($offset)
        ->take($this->limit)
        ->get();
        $totalPages = (int) ceil($totalCount / $this->limit);
        return view('livewire.attendance-lock-configuration', [
            'lockConfiguration' => $lockConfiguration, // Only pass the data (records) part
            'totalCount'=>$totalCount,
            'totalPages'=>$totalPages,
            'pagination' => $lockConfiguration // Pass the whole paginator for pagination links
        ]);
    }
}
