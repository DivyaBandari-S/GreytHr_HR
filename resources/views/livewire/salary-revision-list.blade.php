<div class="main " style="margin: 10px;background-color:var(--light); height:100%">

    <style>
        .bold-items {
            font-weight: bold;
        }

        .emp-sal1-table th {
            text-align: center;
            vertical-align: middle;
            color: #3b4452;
            padding: 3px;
            font-size: 12px;
            background-color: #EBEFF7;
        }

        .emp-sal1-table td {
            border-style: none;
            font-size: 12px;
            color: #394657;

        }
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items">Salary Revisions </span> page lists all the employees whose salary has been revised or updated. You can approve or reject the <span class="bold-items">Salary Revisions </span> for employees. In case you have enabled the <span class="bold-items">Salary Revision Approval </span> option, and the user doing the entry is different from the user who approves the revision. The updated salary needs to be approved. This page enables you to view a list of all salary revisions/updates, and you can review them (Approve/Reject).</p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    @if($isPageOne)
    <div class=" d-flex  mt-4">
        <div class="d-flex" style="gap:20px;align-items: center;">
            <input type="search" wire:model="search" wire:input="getSalaryRevisions" placeholder="search....." class="form-control" name="" id="">
            <div class="d-flex" style="align-items: center;gap:5px">
                <label for="">Status:</label>
                <select name="" style="width: fit-content;" wire:model='status' wire:change="getSalaryRevisions" class="form-select" placeholder="Select Status" id="">
                    <option value="">All</option>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                    <option value="2">Rejected</option>
                </select>
            </div>


        </div>
        <div class="d-flex" style="gap: 10px;margin-left:auto">
            <button wire:click="approveSalaryRevisions" class="btn bg-white text-primary border-primary float-end " style="margin-left: auto;font-size:15px">Approve </button>
            <button wire:click="rejectSalaryRevisions" class="btn bg-white text-primary border-primary float-end " style="margin-left: auto;font-size:15px">Reject </button>
        </div>
    </div>
    <div class="table-responsive mt-2">
        <table class="table table-bordered emp-sal1-table">
            <thead>
                <tr>
                    <th> <input type="checkbox" wire:model="selectAll" wire:change="updateSelectAll(event.target.value)"></th>
                    <th>#</th>
                    <th>Employee Number</th>
                    <th>Employee Name</th>
                    <th>Revised Date</th>
                    <th>Effective Date</th>
                    <th>Arrear Effective Date</th>
                    <th>Payout Date</th>
                    <th>Revised Salary</th>
                    <th>%</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($salaryRevisions)
                @foreach($salaryRevisions as $index => $employee)
                <tr>
                    <td class="text-center"> <input type="checkbox" wire:model="selectedEmployees" value="{{ $employee['id']}}" wire:change="updateSelectedEmployees"></td>
                    <td>{{ $index+1}}</td>
                    <td>{{$employee['emp_id']}}</td>
                    <td style="text-transform: capitalize;">{{$employee['first_name']}} {{$employee['last_name']}} </td>
                    <td>{{\carbon\carbon::parse($employee['created_at'])->format('d M, Y')}}</td>
                    <td>{{\carbon\carbon::parse($employee['revision_date'])->format('d M, Y')}}</td>
                    <td></td>
                    <td>{{\carbon\carbon::parse($employee['revision_date'])->format('M, Y')}}</td>
                    <td>{{$employee['revised_ctc']}}</td>
                    @php
                    $Percentage=\App\Models\EmpSalaryRevision::percentageChange($employee['current_ctc'],$employee['revised_ctc']);
                    $status=\App\Models\EmpSalaryRevision::changeStatus($employee['status']);
                    @endphp
                    <td>{{round($Percentage,2)}} </td>

                    <td>{{$status}}</td>


                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
    </div>
    @endif

</div>
