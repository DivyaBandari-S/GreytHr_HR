<div class="main " style="margin: 10px;background-color:var(--light); height:100%">

    <style>
        .bold-items {
            font-weight: bold;
        }

        .emp-sal1-table th {
            text-align: center;
            vertical-align: middle;
            color: #3b4452;
            padding: 5px 3px;
            font-size: 12px;
            background-color: #EBEFF7;
        }

        .emp-sal1-table td {
            border-style: none;
            font-size: 12px;
            color: #394657;

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
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:13px;background-color:#f5feff">
        <p>The <span class="bold-items"> Settlement </span>wizard guides you through the settlement process when an employee separates from the organization. You can either select from the list of separated employees (employees marked as having left the organization) or from the list of regular employees.</p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    <div class=" d-flex  mt-4" style="width: 100%; ">
        <div>
            <input type="search" wire:model="searchtable" wire:input="" placeholder="search....." class="form-control" name="" id="">
        </div>
        <button wire:click="settleEmployee" class="btn bg-white text-primary border-primary float-end " style="margin-left: auto;font-size:15px">Settle Employee</button>
    </div>

    <div class="table-responsive mt-2" style="width: 100%;">
        <table class="table table-bordered emp-sal1-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Payout Month</th>
                    <th>Serial Number</th>
                    <th>Emp Id</th>
                    <th>Employee Name</th>
                    <th>Leaving Date</th>
                    <th>Settlement Date</th>
                    <th>Net Pay</th>
                    <th>Processed On</th>
                    <th>Lock/Unlock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @if(0)
                @foreach($holdedPayoutEmployees as $index => $holdedEmployee)
                <tr>
                    <td>{{ $index+1}}</td>
                    <td>{{$holdedEmployee->emp_id}}</td>
                    <td style="text-transform: capitalize;">{{$holdedEmployee->first_name}} {{$holdedEmployee->last_name}} </td>
                    <td> Rs {{number_format($holdedEmployee->payout,2)}}</td>
                    <td>{{$holdedEmployee->hold_reason}}</td>
                    <td>{{$holdedEmployee->remarks}}</td>
                    <td style="text-align: center;"><i class="fa fa-trash" wire:click="deleteHoldedEmployee({{$holdedEmployee->id}})" style="cursor: pointer;"></i></td>

                    @if($isDelete)
                    <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-white">
                                        <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirmation</h6>
                                    </div>
                                    <div class="modal-body text-center" style="font-size: 14px;color:var( --main-heading-color);">
                                        Are you sure you want to remove the Employee <span style="color: red;"> {{ucwords(strtolower($deleteEmpDetails->first_name))}} {{ ucwords(strtolower($deleteEmpDetails->last_name))}} [{{$deleteEmpDetails->emp_id}}] </span>from hold salary Payout?
                                    </div>
                                    <div class="d-flex gap-3 justify-content-center p-3">
                                        <button type="button" class="submit-btn " wire:click="confirmdeleteHoldedEmployee">Confirm</button>
                                        <button type="button" class="cancel-btn" wire:click="hideModel">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show"></div>
                    @endif
                </tr>
                @endforeach
                @endif

            </tbody>

        </table>
    </div>


</div>
