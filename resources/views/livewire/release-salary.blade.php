<div class="main " style="margin: 10px;background-color:var(--light); height:100%">

    <style>
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

        .emp-sal1-table tbody tr:nth-child(odd) {
            background-color: rgb(228, 223, 223) !important;
            /* Light gray background for odd rows */
        }

        .emp-sal1-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
            /* Light gray background for odd rows */
        }


        .Employee-select-leave {
            font-weight: 500;
            font-size: var(--main-headings-font-size);
            color: var(--main-heading-color);
        }

        .Employee-details-hr {
            border: 1px solid rgb(80, 80, 218);
            align-items: center;
            border-radius: 30px;
            padding: 5px 10px;
            margin-bottom: 10px;
        }

        .Employee-details-img-details-hr {
            width: fit-content;
            align-items: center;
        }

        .profile-image {
            height: 32px;
            width: 32px;
            background-color: lightgray;
            border-radius: 50%;
        }

        .Emp-name-leave-details {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 0px;
            color: var(--sub-heading-color);
        }

        .Emp-id-leave-details {
            font-size: 10px;
            color: var(--label-color);
            margin-bottom: 0px;
        }

        .analytic-view-all-search-bar {
            display: flex;
            padding: 10px 0px;
            justify-content: space-between;
            /* Adjust spacing between items */
            align-items: center;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .analytic-view-all-search-bar input[type="text"] {
            width: 200px;
            padding: 6px 28px 6px 10px;
            /* Adjust padding for right space */
            border: 1px solid #ccc;
            border-radius: 18px;
            position: relative;
        }

        .search-icon {
            position: absolute;
            right: 10px;
            color: #666;
            pointer-events: none;
        }

        .custom-border {
            background-color: rgb(245, 246, 248);
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid aliceblue;
            width: 260px;
            margin-left: 5px;
        }

        .emp-datails-table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 5px;
        }

        .emp-datails-table td {

            border: 1px solid silver;
            padding: 5px;
            /* text-align: left;     */
            font-size: 13px;
            width: 50%;

        }

        .emp-table-values {
            font-weight: bold;
            width: 50%;
            color: #3b4452;

        }

        .detail-items {
            display: flex;
        }

        .label-value {
            width: 50%;
            margin-right: 10px;
        }

        .release-labels {
            text-align: end;

        }

        .release-input-rows {
            margin-bottom: 20px;
        }

        .form-select {
            color: #3b4452;
            font-size: 13px;
            font-weight: 500;
        }

        .form-control {
            color: #3b4452;
            font-size: 13px;
            font-weight: 500;
        }
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items">Release Salary </span> page enables you to view the employees' salary that has been released after being on hold. You can also view the reasons for being on hold and other details.</p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif
    @if($isPageOne)
    <div class=" d-flex  mt-4" style="width: 100%; ">
        <div class="d-flex" style="gap: 20px;align-items:center">
            <div style="height:fit-content">
                <input type="search" wire:model="searchtable" wire:input="getTableData" placeholder="search....." class="form-control" name="" id="">
            </div>
            <div class=" d-flex" style="width: fit-content;gap:20px;align-items:center">
                <label for="">Status</label>
                <select name="" wire:model="isReleasedFilter" wire:change="getTableData" class="form-select" id="">
                    <option value="">All</option>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
        </div>
        @if($uniqueEmployees)
        <button wire:click="addHoldSalaryProcessing" class="btn btn-primary border-primary float-end " style="margin-left: auto;font-size:15px">Release Salary</button>
        @endif
    </div>
    <div class="table-responsive mt-2" style="width: 100%;">
        <table class="table table-bordered emp-sal1-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee No</th>
                    <th>Name</th>
                    <th>Hold Payroll</th>
                    <th>Hold Salary Payout </th>
                    <th>Hold Reason </th>
                    <th>Release Month </th>
                    <th>Release Reason </th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if($holdedPayoutEmployees)
                @foreach($holdedPayoutEmployees as $index => $holdedEmployee)
                <tr>
                    <td>{{ $index+1}}</td>
                    <td>{{$holdedEmployee->emp_id}}</td>
                    <td style="text-transform: capitalize;">{{$holdedEmployee->first_name}} {{$holdedEmployee->last_name}} </td>
                    <td>{{$holdedEmployee->payout_month}}</td>
                    <td> Rs {{number_format($holdedEmployee->payout,2)}}</td>
                    <td>{{$holdedEmployee->hold_reason}}</td>
                    <td>{{$holdedEmployee->release_month}}</td>
                    <td>{{$holdedEmployee->release_reason}}</td>
                    <td>@if($holdedEmployee->is_released==0)
                        False
                        @else
                        True
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if($holdedEmployee->is_released==0)
                        <i class="fa fa-pen" wire:click="releaseEmployeeSalary('{{$holdedEmployee->emp_id}}','{{$holdedEmployee->payout_month}}','{{$holdedEmployee->hold_reason}}','{{$holdedEmployee->payout}}')" style="cursor: pointer;"></i>
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    @else
    <div style="padding-bottom: 30px;">

        @if($holdedPayoutEmployees )
        <div class="mt-3" style="padding-bottom: 10px; align-items:center">

            <div class="col-md-6 mt-3">
                <div class="row release-input-rows">
                    <div class="col-md-6 release-labels">
                        <label for="hold_reason">Employee</label>
                    </div>
                    <div class="col-md-6">
                        <select wire:model="selectedEmployee" style="text-transform: capitalize;" class="form-select" wire:change="getPayoutMonths(null)">
                            @foreach($uniqueEmployees as $employee)
                            <option  value="{{$employee['emp_id']}}"> {{ ucfirst(strtolower($employee ['first_name'])) }} {{ ucfirst(strtolower($employee ['last_name'])) }} [{{$employee['emp_id']}}] </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row release-input-rows">
                    <div class="col-md-6 release-labels">
                        <label for="hold_reason">Hold Month</label>
                    </div>
                    <div class="col-md-6">
                        <select wire:model="selectedHoldMonth" class="form-select" wire:change="getSalary">
                            @foreach($uniquePayoutMonths as $month)
                            <option value="{{$month['payout_month']}}">{{$month['payout_month']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row release-input-rows">
                    <div class="col-md-6 release-labels">
                        <label for="hold_reason">Hold Reason</label>
                    </div>
                    <div class="col-md-6">
                        <p class="align-baseline" style="color: #3b4452;font-size:13px;margin:0px 0px 0px 5px;font-weight: 500;"> {{$selectedHoldReason}}</p>
                    </div>
                </div>
                <div class="row release-input-rows">
                    <div class="col-md-6 release-labels">
                        <label for="hold_reason">Release Salary</label>
                    </div>
                    <div class="col-md-6">
                        <p class="align-baseline" style="color: #3b4452;font-size:13px;margin:0px 0px 0px 5px;font-weight: 500;"> Rs {{ number_format(round($releaseSalary, 2), 2) }}</p>
                    </div>
                </div>
                <div class="row release-input-rows">
                    <div class="col-md-6 release-labels">
                        <label for="hold_reason">Release Reason</label>
                    </div>
                    <div class="col-md-6">
                        <select wire:model="selectedReleaseReason" class="form-select">
                            <option value="">Select Release Reason</option>
                            @foreach($holdReasons as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('selectedReleaseReason')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row release-input-rows">
                    <div class="col-md-6 release-labels">
                        <label for="hold_reason">Remarks</label>
                    </div>
                    <div class="col-md-6">
                        <textarea wire:model='remarks' style="height: 80px;" class="form-control"></textarea>
                        @error('remarks')
                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="d-flex col-md-6 " style="justify-content: center;gap:10px ;padding:20px">
            <button class="btn btn-primary" wire:click="processSalaryRelease">Release</button>
            <button class="btn bg-white text-primary border-primary" wire:click="addHoldSalaryProcessing">Cancel</button>
        </div>

        @if($isConfirm)
        <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Release Status</h6>
                    </div>
                    <div class="modal-body text-center" style="font-size: 14px;color:var( --main-heading-color);">
                        Do you want to release <span style="color:red">{{\Carbon\Carbon::parse($selectedHoldMonth)->format('M Y')}}</span> Salary?
                    </div>
                    <div class="d-flex gap-3 justify-content-center p-3">
                        <button type="button" class="submit-btn " wire:click="confirmSalaryRevision">Confirm</button>
                        <button type="button" class="cancel-btn" wire:click="hideModel">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        @endif
    </div>
    @endif

</div>
