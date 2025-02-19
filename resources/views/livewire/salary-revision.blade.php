<div class="main " style="margin: 10px;background-color:var(--light); height:100%">

    <style>
        .bold-items {
            font-weight: bold;
        }
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items"> Salary Revision </span> page enables you to update a newly joined employee's salary or revise the salary of an existing employee. In case you have previously revised using this module, then the Current Salary values also appear along with the Revised Salary. After the new values are updated, remember to click on Process to <span class="bold-items"> process </span> the payroll.</p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    <div>
    @livewire('add-or-view-salary-revision')
    </div>




</div>
