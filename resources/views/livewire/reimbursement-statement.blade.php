<div>
<style>


   .table-container {
            width: auto;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            background: #98cae0; /* Updated header background */
            color: white;
            width:auto;
            border-bottom: 2px solid #ddd;
        }

        .table-container th, .table-container td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .table-container th {
            font-size: 14px;
        }

        .no-data {
            text-align: center;
            font-weight: bold;
            padding: 15px;
            color: #888;
        }

        /* Custom class for tabs */
        .custom-tab-btn {
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            background: #f1f1f1;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

        .custom-tab-btn.active {
            background: #007bff;
            color: white;
        }
        /* Row container */
/* General Container Styles */
.reimbursment {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    width:80%
}

h5 {
    font-size: 20px;
    font-weight: bold;
    color: #333;
}

/* Balance Container Styling */
.balance-container {
    display: flex;
    align-items: center;
    gap: 20px;
}

.balance-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.Balance-btn {
    color: #f1f1f1;
    background: #888;
    border-radius: 5px;
    padding: 3px 3px;
    margin-top: 60px;
    border: none;
    cursor: pointer;
    font-size: 10px;
      transition: background-color 0.3s ease;
}

.Balance-btn:hover {
    background: #666;
}

/* Details Button Styling */
.cancel-btn.pdf-download {
    background-color: white;
    color: var(--main-button-color, #007bff);
    font-weight: 500;
    border-radius: 5px;
    padding: 6px 12px;
    font-size: var(--normal-font-size, 14px);
    border: 1px solid var(--main-button-color, #007bff);
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.cancel-btn.pdf-download:hover {
    background-color: #f1f1f1;
}

/* Dropdown Arrow */
.fas.fa-caret-down {
    font-size: 12px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .reimbursment {
        flex-direction: column;
        align-items: flex-start;
    }

    .balance-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .Balance-btn {
        margin-top: 5px;
        font-size: 12px;
       
    }

    .cancel-btn.pdf-download {
        margin-top: 10px;
    }
}

</style>
<div class="row" style="margin-top:-20px;">
<ul class="nav custom-nav-tabs" role="tablist" >
    <li class="nav-item" role="presentation">
        <a class="nav-link active custom-nav-link" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">Main</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link custom-nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">Activity</a>
    </li>
</ul>
</div>


<div class="tab-content pt-5" id="tab-content">
  <div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0" style="overflow-x: hidden;">
    <div class="row justify-content-center"  >
                        <div class="col-md-11 custom-container d-flex flex-column">
                        <div class="row d-flex align-items-center mb-2">
    <div class="col-10">
        <p class="main-text mb-0">
        The Reimbursement Statement page displays all Reimbursement components applicable to an employee and details of entitlements, claims paid out, and the balance amount. 
        </p>
    </div>
    <div class="col-2 text-end">
        <p class="hide-text mb-0" style="cursor: pointer;" wire:click="toggleDetails">
            {{ $showDetails ? 'Hide Details' : 'Info' }}
        </p>
    </div>
</div>


                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore HR Xpert by 
    <span class="hide-text">Help-Doc</span>, watching How-to 
    <span class="hide-text">Videos</span> and 
    <span class="hide-text">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-11 custom-container d-flex flex-column bg-white" >
    <div class="row justify-content-center mt-3 flex-column m-0 employee-details-main" >
        <div class="col-md-10">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <p class="emp-heading" >Start searching to see specific employee details here</p>
                    <div class="col mt-3" style="display: flex;">
             
             <p class="main-text mt-1">Employee Type:</p>
            
             <div class="dropdown mt-1">
<button class="btn dropdown-toggle dp-info" type="button" data-bs-toggle="dropdown" style="font-size:12px">
{{ ucfirst($selectedOption) }} 
</button>
<ul class="dropdown-menu" style="font-size:12px;">
<li class="updated-dropdown">
 <a href="#" wire:click.prevent="updateSelected('all')" class="dropdown-item custom-info-item">All Employees</a>
</li>
<li class="updated-dropdown">
 <a href="#" wire:click.prevent="updateSelected('current')" class="dropdown-item custom-info-item">Current Employees</a>
</li>
<li class="updated-dropdown">
 <a href="#" wire:click.prevent="updateSelected('past')" class="dropdown-item custom-info-item">Resigned Employees</a>
</li>
<li class="updated-dropdown">
 <a href="#" wire:click.prevent="updateSelected('intern')" class="dropdown-item custom-info-item">Intern</a>
</li>
</ul>
</div>



           
         </div>
                 
                    <div class="profile">
    <div class="col m-0">
        <div class="row d-flex align-items-center">
            <!-- Search Input -->
            <div class="input-group4 d-flex align-items-center">
                <!-- Input Field with Profile Image -->
                <div class="position-relative">
                    @if($selectedEmployeeId && $selectedEmployeeFirstName)
                    <img 
        src="{{ $selectedEmployeeImage ? 'data:image/jpeg;base64,' . $selectedEmployeeImage : asset('images/user.jpg') }}" 
        alt="Profile Image" 
        class="profile-image-input"
        style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); width: 30px; height: 30px; border-radius: 50%;" 
    />
                    @else
                        <img 
                         src="{{ asset('images/user.jpg') }}" alt="Default Image"
                            
                            class="profile-image-input"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); width: 30px; height: 30px; border-radius: 50%;"
                        />
                    @endif

               
                  <!-- Search Input Field -->
<input
    wire:model.debounce.500ms="searchTerm"
    aria-label="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
    placeholder="{{ $selectedEmployeeFirstName ? ucfirst(strtolower($selectedEmployeeFirstName)) . ' ' . ucfirst(strtolower($selectedEmployeeLastName)) : 'Search for an employee...' }}"
    type="text"
    class="form-control search-term"
    style="padding-left: 50px; padding-right: 35px;" 
/>

<!-- Display Close Icon if Employee is Selected -->
@if($selectedEmployeeId)
    <svg class="close-icon-person"  
         wire:click="removePerson('{{ $selectedEmployeeId }}')" 
         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
         style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
        <path d="M6 18L18 6M6 6l12 12" stroke="#3b4452" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
@else
    <!-- Display Search Icon if No Employee is Selected -->
    <i 
        class="bx bx-search search-icon position-absolute" width="20" height="20"
        wire:click="searchforEmployee"
        style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
    </i>
@endif

                </div>
            </div>

            <!-- Conditional Display of Search Results -->
            @if($searchTerm)
                @if($employees->isEmpty())
                    <div class="search-container">
                        <p>No People Found</p>
                    </div>
                @else
                    <div class="search-container" style="max-height: 250px; overflow-y: auto;">
                        @foreach($employees as $employee)
                            @if(stripos($employee->first_name . ' ' . $employee->last_name, $searchTerm) !== false)
                                <label wire:click="selectEmployee('{{ $employee->emp_id }}')" style="cursor: pointer;">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <input 
                                                type="checkbox" 
                                                id="employee-{{ $employee->emp_id }}"
                                                wire:click="updateselectedEmployee('{{ $employee->emp_id }}')"
                                                value="{{ $employee->emp_id }}"
                                                class="form-check-input custom-checkbox-information"
                                                {{ in_array($employee->emp_id, $selectedPeople) || $employee->isChecked ? 'checked' : '' }}>
                                        </div>
                                        <div class="col-auto">
                                            @if($employee->image && $employee->image !== 'null')
                                                <img class="profile-image" src="data:image/jpeg;base64,{{ $employee->image }}">
                                            @else
                                                <img class="profile-image" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                            @endif
                                        </div>
                                        <div class="col">
                                            <h6 class="name" style="font-size: 12px; color: black;">
                                                {{ ucfirst(strtolower($employee->first_name)) }} {{ ucfirst(strtolower($employee->last_name)) }}
                                            </h6>
                                            <p style="font-size: 12px; color: grey;">(#{{ $employee->emp_id }})</p>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>


                    </div>
             
                <div class="col-md-1">
    <!-- Modified image container to have a fixed height -->
    <div class="image-container d-flex align-items-end" >
        <img src="{{ asset('images/employeeleave.png') }}"  alt="Employee Image" style="height: 180px; width:280px;align-items:end">
    </div>
</div>

            </div>
        </div>
        
  

</div>

 
                
        </div>
    </div>
    <div class="container">
    <!-- Accordion 1 -->
    <div class="reimbursement" style="display: flex; justify-content: space-between; align-items: center; width: 80%;">
        <h5>MEDICAL REIMBURSEMENT</h5>
        <div class="balance-container" style="display: flex; align-items: center;">
            <div class="balance-info" style="display: flex; flex-direction: column;">
                <button class="Balance-btn">Balance</button><br>
                <p style="margin-top: -10px;">11250</p>
            </div>
            <button class="cancel-btn pdf-download details-btn" data-target="#accordion-content-1" style="display: inline-block; margin-top: 5px;">
                Details <i class="fas fa-caret-down chevron-icon"></i>
            </button>
        </div>
    </div>

    <div id="accordion-content-1" class="accordion-content" style="display: none;">
        <div class="tab-container">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#claims1"  style="font-size: 12px;">Claims</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payouts1"  style="font-size: 12px;">Payouts</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="claims1" class="tab-pane fade show active">
                <table class="table-container" style="width: 90%;">
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Claim No</th>
                            <th style="width: 15%">Claim Date</th>
                            <th style="width: 15%">Claim Amount</th>
                            <th style="width: 20%">Proof Status</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="5" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="payouts1" class="tab-pane fade">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Sl No</th>
                            <th style="width: 20%">Date</th>
                            <th style="width: 20%">Amount</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="4" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>

    <!-- Accordion 2 -->
    <div class="reimbursement" style="display: flex; justify-content: space-between; align-items: center; width: 80%;margin-top:10px">
        <h5>TRAVEL REIMBURSEMENT</h5>
        <div class="balance-container" style="display: flex; align-items: center;">
            <div class="balance-info" style="display: flex; flex-direction: column;">
                <button class="Balance-btn">Balance</button><br>
                <p style="margin-top: -10px;">11250</p>
            </div>
            <button class="cancel-btn pdf-download details-btn" data-target="#accordion-content-2" style="display: inline-block; margin-top: 5px;">
                Details <i class="fas fa-caret-down chevron-icon"></i>
            </button>
        </div>

    </div>
    <div id="accordion-content-2" class="accordion-content" style="display: none;">
        <div class="tab-container">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#claims2"  style="font-size: 12px;">Claims</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payouts2"  style="font-size: 12px;">Payouts</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="claims2" class="tab-pane fade show active">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Claim No</th>
                            <th style="width: 15%">Claim Date</th>
                            <th style="width: 15%">Claim Amount</th>
                            <th style="width: 20%">Proof Status</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="5" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="payouts2" class="tab-pane fade">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Sl No</th>
                            <th style="width: 20%">Date</th>
                            <th style="width: 20%">Amount</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="4" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<hr>
    <!-- Accordion 3 -->
    <div class="reimbursement" style="display: flex; justify-content: space-between; align-items: center; width: 80%;margin-top:10px">
        <h5>FOOD ALLOWANCE</h5>
        <div class="balance-container" style="display: flex; align-items: center;">
            <div class="balance-info" style="display: flex; flex-direction: column;">
                <button class="Balance-btn">Balance</button><br>
                <p style="margin-top: -10px;">11250</p>
            </div>
            <button class="cancel-btn pdf-download details-btn" data-target="#accordion-content-3" style="display: inline-block; margin-top: 5px;">
                Details <i class="fas fa-caret-down chevron-icon"></i>
            </button>
        </div>
    
    </div>
    <div id="accordion-content-3" class="accordion-content" style="display: none;">
        <div class="tab-container">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#claims3" style="font-size: 12px;">Claims</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payouts3"  style="font-size: 12px;">Payouts</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="claims3" class="tab-pane fade show active">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Claim No</th>
                            <th style="width: 15%">Claim Date</th>
                            <th style="width: 15%">Claim Amount</th>
                            <th style="width: 20%">Proof Status</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="5" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="payouts3" class="tab-pane fade">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Sl No</th>
                            <th style="width: 20%">Date</th>
                            <th style="width: 20%">Amount</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="4" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<hr>
    <!-- Accordion 4 -->
    <div class="reimbursement" style="display: flex; justify-content: space-between; align-items: center; width: 80%;margin-top:10px">
        <h5>CAR ALLOWANCE</h5>
        <div class="balance-container" style="display: flex; align-items: center;">
            <div class="balance-info" style="display: flex; flex-direction: column;">
                <button class="Balance-btn">Balance</button><br>
                <p style="margin-top: -10px;">11250</p>
            </div>
            <button class="cancel-btn pdf-download details-btn" data-target="#accordion-content-4" style="display: inline-block; margin-top: 5px;">
                Details <i class="fas fa-caret-down chevron-icon"></i>
            </button>
        </div>
     
    </div>
    <div id="accordion-content-4" class="accordion-content" style="display: none;">
        <div class="tab-container">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#claims4"  style="font-size: 12px;">Claims</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payouts4"  style="font-size: 12px;">Payouts</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="claims4" class="tab-pane fade show active">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Claim No</th>
                            <th style="width: 15%">Claim Date</th>
                            <th style="width: 15%">Claim Amount</th>
                            <th style="width: 20%">Proof Status</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="5" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="payouts4" class="tab-pane fade">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Sl No</th>
                            <th style="width: 20%">Date</th>
                            <th style="width: 20%">Amount</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="4" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<hr>
    <!-- Accordion 5 -->
    <div class="reimbursement" style="display: flex; justify-content: space-between; align-items: center; width: 80%;margin-top:10px">
        <h5>LEAVE ALLOWANCE</h5>
        <div class="balance-container" style="display: flex; align-items: center;">
            <div class="balance-info" style="display: flex; flex-direction: column;">
                <button class="Balance-btn">Balance</button><br>
                <p style="margin-top: -10px;">11250</p>
            </div>
            <button class="cancel-btn pdf-download details-btn" data-target="#accordion-content-5" style="display: inline-block; margin-top: 5px;">
                Details <i class="fas fa-caret-down chevron-icon"></i>
            </button>
        </div>

    </div>
    <div id="accordion-content-5" class="accordion-content" style="display: none;">
        <div class="tab-container">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#claims5"  style="font-size: 12px;">Claims</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payouts5"  style="font-size: 12px;">Payouts</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="claims5" class="tab-pane fade show active">
                <table class="table-container" >
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Claim No</th>
                            <th style="width: 15%">Claim Date</th>
                            <th style="width: 15%">Claim Amount</th>
                            <th style="width: 20%">Proof Status</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="5" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="payouts5" class="tab-pane fade">
                <table class="table-container" style="width: 90%;">
                    <thead class="table-header">
                        <tr>
                            <th style="width: 15%">Sl No</th>
                            <th style="width: 20%">Date</th>
                            <th style="width: 20%">Amount</th>
                            <th style="width: 30%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="no-data-row">
                            <td colspan="4" style="font-size: 12px;">No transactions found...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function () {
    $(".details-btn").click(function () {
        var target = $(this).attr("data-target");
        var chevron = $(this).find(".chevron-icon");

        if ($(target).is(":visible")) {
            $(target).slideUp();
            chevron.removeClass("fa-caret-up").addClass("fa-caret-down");
        } else {
            $(target).slideDown();
            chevron.removeClass("fa-caret-down").addClass("fa-caret-up");
        }
    });
});
</script>
</div>
</div>