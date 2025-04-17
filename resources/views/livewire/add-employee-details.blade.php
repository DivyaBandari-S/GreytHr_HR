<div>
    <div style="position: relative; ">
        <style>
            .onboardingtable {
                width: 100%;
                border-collapse: collapse;
            }

            .onboardingtable th {
                background-color: #ecf9ff;
                /* Header background color */
                color: #778899;
                /* Text color for headers */
                padding: 8px;
                font-weight: 500;
                font-size: 0.8rem;
                text-align: left;
            }

            .onboardingtable td {
                background-color: #fff;
                /* Body background color */
                color: #3b4452;
                font-size: 13;
                /* Text color for body cells */
                padding: 8px;
                border: 1px solid #ddd;
                /* Optional: border for table cells */
            }

            .onboardingtable tbody tr td {
                font-size: 13px;
            }

            .onboardingtable tbody tr:nth-child(even) td {
                background-color: #fcfcfc;
                /* Optional: alternate row color */
            }

            /* .onboardinputs {
                width: 80%;
            } */
            .select-inputs {
                display: flex;
                align-items: center;
            }

            .form-check-inline {
                align-items: center;
            }
        </style>
        @if (Session::has('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" style="
            height: 30px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            align-items: center;
            display: flex;
            justify-content: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: lightgreen;
            color: white;
            margin-bottom: 5px;
            font-size: 12px;">
            {{ Session::get('success') }}
        </div>
        @endif
        <div>
            <div class="container-fluid" style="padding:0px;margin: 0;">
                <div id="alert-container" class="d-flex justify-content-center alert-container " wire:poll.20s="hideAlert" style="position: sticky; top: 13%; z-index: 10; width: 100%;">
                    <!-- wire:poll.5s="hideAlert" -->
                    @if ($showAlert)
                    <p class="alert alert-success" role="alert" style=" font-weight: 400;width:fit-content;padding:10px;border-radius:5px;margin-bottom:0px">
                        {{ session('emp_success') }}
                        <span style="font-weight:500;margin:0px 10px; cursor: pointer; " wire:click='hideAlert'>x</span>
                    </p>
                    @endif
                </div>

                <div class="container-fluid main-container " style="background:#f2f2f2;margin:0;padding:0;">
                    <div class="d-flex justify-content-between p-3">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <!-- <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
                            </ol> -->
                        </nav>
                        <div>
                            <button class="ilynn-btn"><a href="/hr/update-employee-details" style="text-decoration: none;color:white;font-size:14px;">Employee List</a></button>
                        </div>
                    </div>
                    <!-- multistep form -->
                    <form id="msform" wire:submit.prevent="register" enctype="multipart/form-data" class="row m-0">
                        <div class="row m-0 mt-3 mb-3" style="text-align: center">
                            <h5 class="fs-title">Employee Onboarding Form</h5>
                        </div>
                        <!-- progressbar -->
                        <div class="row m-0" style="text-align: center">
                            <!-- <div class="col-md-2"></div> -->
                            <div class="col-md-12">
                                <ul id="progressbar">
                                    <li class="{{ $currentStep >= 1 ? 'active' : '' }}">Employee Details</li>
                                    <li class="{{ $currentStep >= 2 ? 'active' : '' }}">Employee Job Details</li>
                                    <li class="{{ $currentStep >= 3 ? 'active' : '' }}">Employee Personal Details</li>
                                    <li class="{{ $currentStep >= 4 ? 'active' : '' }}">Employee Address Details</li>
                                    <li class="{{ $currentStep >= 5 ? 'active' : '' }}">Employee Parents Details</li>
                                    <li class="{{ $currentStep >= 6 ? 'active' : '' }}">Employee Spouse Details</li>
                                    <li class="{{ $currentStep >= 7 ? 'active' : '' }}">Employee Bank Details</li>
                                    <li class="{{ $currentStep >= 8 ? 'active' : '' }}">Employee Educational Details</li>
                                    <li class="{{ $currentStep >= 9 ? 'active' : '' }}">Employee Experience Details</li>
                                </ul>
                            </div>
                            <!-- <div class="col-md-2"></div> -->
                        </div>

                        <!-- fieldsets -->
                        @if($currentStep==1)
                        <fieldset>
                            <div class=" m-0 row">
                                <div class="row m-0 mb-2 mt-3 p-0" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Details</h2>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="first_name">First Name <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter first name" wire:model="first_name" pattern="[A-Za-z ]+" title="Only letters and spaces are allowed">
                                    @error('first_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="last_name">Last Name <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter last name" wire:model="last_name">
                                    @error('last_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="com_id">Company Name <span class="text-danger onboard-Valid">*</span></label>
                                    <select wire:change="selectedCompany" wire:model="com_id" class="form-control onboardinputs custom-select placeholder-small m-0" style="margin-bottom: 10px;color: #2c3e50">
                                        <option class="onboard-select-default" disabled value="">Select Company</option>
                                        @foreach ($companieIds as $id)
                                        <option value="{{ $id->company_id }}">{{ $id->company_name }}</option>
                                        @endforeach
                                    </select>

                                    @error('com_id')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="mobile_number">Mobile Number <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter mobile number" wire:model="mobile_number">
                                    @error('mobile_number')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="email">Company Email <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="email" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter company email" wire:model="company_email">
                                    @error('company_email')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="input-group onboarding-check-boxes">
                                        <label class="mt-1">Gender <span class="text-danger onboard-Valid">*</span> </label>
                                        <div class="select-inputs">
                                            <div class="form-check form-check-inline mb-0 mx-2">
                                                <input type="radio" class="form-check-input" wire:model="gender" value="MALE" id="maleRadio" name="gender">
                                                <label class="form-check-label-options mt-1 mb-0" for="maleRadio">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0 mx-2">
                                                <input type="radio" class="form-check-input" wire:model="gender" value="FEMALE" id="femaleRadio" name="gender">
                                                <label class="form-check-label-options mt-1 mb-0" for="femaleRadio">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0 mx-2">
                                                <input type="radio" class="form-check-input" wire:model="gender" value="OTHER" id="othersRadio" name="gender">
                                                <label class="form-check-label-options mt-1 mb-0" for="femaleRadio">Others</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div>
                                        @error('gender')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group col-md-6 d-flex" style="flex-direction: column;">
                                    <label class="mt-1" for="image">Employee Image <span class="text-danger onboard-Valid">*</span></label>
                                    <input class="onboardinputs" type="file" wire:model="image" accept=".png, .jpg, .jpeg" style="font-size:12px;border:none; width:200px; margin-bottom: 0px;">
                                    @if($imageValidation==false)
                                    @error('imageBinary')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                    @endif
                                </div>
                                <div class="form-group col-md-6"></div>
                                <div class="form-group col-md-6  " style="display: flex;justify-content:start">
                                    @if($imageValidation!=true)
                                    @if($image)
                                    <img src="{{ is_string($image) ? asset('storage/' . $image) : $image->temporaryUrl() }}" alt="Preview" style='height:100px;width:100px' class="img-thumbnail" />
                                    @else
                                    @if(strlen($imageBinary)>10)
                                    <img src="data:image/jpeg;base64,{{ $imageBinary }}" alt="" style='height:100px;width:100px' class="img-thumbnail" />
                                    @endif
                                    @endif
                                    @else
                                    <span class="text-danger onboard-Valid">{{$imageValidationmsg}}</span>
                                    @endif
                                </div>
                            </div>

                            <hr class="hr-wizard" />
                            <button type="button" name="next" class="next ilynn-btn" value="Next" wire:click="nextPage">Next</button>
                        </fieldset>
                        @endif
                        @if($currentStep==2)
                        <fieldset>
                            <div class=" m-0 row">
                                <div class="row m-0 mb-2 mt-3 p-0" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Job Details</h2>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="hire_date">Hire Date <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="date" class="form-control onboardinputs  placeholder-small m-0" wire:model="hire_date" max="{{ date('Y-m-d') }}">
                                    @error('hire_date')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">

                                    <div class="input-group onboarding-check-boxes">
                                        <label class="mt-1" for="employee_type">Employee Type</label>
                                        <div class="select-inputs">
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input  mb-2" type="radio" id="full-time" value="full-time" wire:model="employee_type" checked>
                                                <label class="form-check-label-options mt-1 " for="full-time">Full-Time</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input  mb-2" type="radio" id="part-time" value="part-time" wire:model="employee_type">
                                                <label class="form-check-label-options mt-1 " for="part-time">Part-Time</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input  mb-2" type="radio" id="contract" value="contract" wire:model="employee_type">
                                                <label class="form-check-label-options mt-1" for="contract">Contract</label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('employee_type')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="form-group col-md-12">
                                        <label class="mt-1" for="job_title">Job Title <span class="text-danger onboard-Valid">*</span></label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter job title" wire:model="job_title">
                                        @error('job_title')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="mt-1" for="job_location">Job Location <span class="text-danger onboard-Valid">*</span></label>
                                        <!-- <div class="d-flex" style="gap: 5px;"> -->
                                        <select class="form-control onboardinputs custom-select   placeholder-small m-0" wire:model="job_location" style="margin-bottom: 10px;">
                                            @if($locations)
                                            <option disabled value=''>Select job location</option>
                                            @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{$location->name}}</option>
                                            @endforeach
                                            @else
                                            <option disabled value="">No job location found</option>
                                            @endif
                                        </select>
                                        <!-- <button type="button" wire:click='openLocationModel' style="background-color: #306cc6; font-weight:400;padding:0px 6px;font-size: 13px !important;border-radius: 5px;color:#f2f2f2;border:none">Add Location</button>
                                        </div> -->
                                        @error('job_location')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-6 ">
                                    <div class="form-group col-md-12">
                                        <label>Select Domains / Cost-Centers <span class="text-danger onboard-Valid">*</span></label>
                                        <div class="checkbox-list onboardinputs" style="max-height: 100px; height: auto; overflow-y: auto;border:1px solid  #ccc;border-radius:5px;padding:5px;">
                                            @if($Projects && count($Projects))
                                            @foreach($Projects as $project)
                                            <div style="height: 30px;">
                                                <input type="checkbox" style="margin-left: 5px; margin-bottom: 5px;"
                                                    id="project{{ $project->id }}"
                                                    value="{{ $project->project_name }}"
                                                    wire:model="emp_domain"
                                                    class="form-check-input"
                                                    @if(in_array($project->id, $emp_domain)) checked @endif>
                                                <label class="form-check-label" for="project{{ $project->id }}">
                                                    {{ $project->project_name }}
                                                </label>
                                            </div>
                                            @endforeach
                                            @else
                                            <div style="justify-content: space-between;display:flex;align-items:center">
                                                <label for="">No projects available</label>
                                                <button type="button" wire:click='openProjectModel' style="background-color: #306cc6; font-weight:400;padding:0px 6px;font-size: 13px !important;border-radius: 5px;color:#f2f2f2;border:none;height:35px">Add Project</button>
                                            </div>

                                            @endif

                                        </div>
                                        @error('emp_domain')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="department">Department <span class="text-danger onboard-Valid">*</span></label>
                                    <select wire:change="selectedDepartment($event.target.value)" class="form-control onboardinputs custom-select  placeholder-small m-0" wire:model="department_id">
                                        <option disabled selected value="">Select Department</option>
                                        @foreach ($departments as $department)
                                        <option value="{{ $department->dept_id }}">{{ $department->department }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="department"> Sub Department <span class="text-danger onboard-Valid">*</span></label>
                                    <select class="form-control onboardinputs custom-select  placeholder-small m-0" wire:model="sub_department_id" style="margin-bottom: 10px;">
                                        <option disabled selected value="">Select sub department</option>
                                        @foreach ($sub_departments as $sub_department)
                                        <option value="{{ $sub_department->sub_dept_id }}">{{ $sub_department->sub_department }}</option>
                                        @endforeach
                                    </select>
                                    @error('sub_department_id')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="manager_id">Reporting To <span class="text-danger ">*</span></label>
                                    <div class="d-flex" style="gap:5px">
                                        <select wire:change="fetchReportTo" class="form-control onboardinputs custom-select placeholder-small m-0" wire:model="manager_id" style="margin-bottom: 10px; width:60%">
                                            <option value="">Select manager</option>
                                            <!-- Add a default option -->
                                            @if ($managerIds)
                                            @foreach ($managerIds as $id)
                                            <option value="{{ $id->emp_id }}">{{ucwords(strtolower( $id->first_name)) }} {{ ucwords(strtolower($id->last_name)) }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <button type="button" wire:click='openEMployeeListModel' style="background-color: #306cc6; font-weight:400;padding:0px 6px;font-size: 13px !important;border-radius: 5px;color:#f2f2f2;border:none">Add Manager</button>
                                    </div>
                                    @error('manager_id')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="shift_name">Shift Type<span class="text-danger ">*</span></label>
                                    <select class="form-control onboardinputs custom-select placeholder-small m-0" wire:model="shift_name" style="margin-bottom: 10px;">
                                        <option value="">Select shift type</option>
                                        <!-- Add a default option -->

                                        @if($shift_details!=null)
                                        @foreach ($shift_details as $shift)
                                        <option value="{{ $shift->shift_name }}">
                                            {{ $shift->shift_name }} ({{ \Carbon\Carbon::parse($shift->shift_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->shift_end_time)->format('H:i') }})
                                        </option> @endforeach
                                        @endif
                                    </select>
                                    @error('shift_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="referrer">Referrer </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter referrer " wire:model="referrer">
                                    @error('referrer')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="referrer">Probation Period </label>
                                    <!-- <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter probation period " wire:model="probation_period"> -->
                                    @if ($probation_periods != null)
                                    <select class="form-control onboardinputs custom-select placeholder-small m-0" wire:model="probation_period" style="margin-bottom: 10px;">
                                        <option value="" disabled>Select probation period</option>
                                        <!-- Add a default option -->

                                        @foreach ($probation_periods as $id)
                                        <option value="{{ $id}}">{{$id }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                    @error('probation_period')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if(2==3)
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="confirmation_date">Confirmation Date </label>
                                    <input type="date" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter confirmation date  " wire:model="confirmation_date" max="{{ date('Y-m-d') }}">
                                    @error('confirmation_date')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif
                                @if(2==3)
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="notice_period">Notice Period </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter notice period " wire:model="notice_period">
                                    @error('notice_period')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif
                                @if(2==3)
                                <div class="form-group col-md-6">

                                    <div class="input-group onboarding-check-boxes">
                                        <label class="mt-1" for="employee_status">Employee Status</label>
                                        <div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input  mb-2" type="radio" id="active" value="active" wire:model="employee_status" checked>
                                                <label class="form-check-label-options mt-1 " for="active">Active</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input  mb-2" type="radio" id="on-leave" value="on-leave" wire:model="employee_status">
                                                <label class="form-check-label-options mt-1 " for="on-leave">On Leave</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input gender mb-2" type="radio" id="terminated" value="terminated" wire:model="employee_status">
                                                <label class="form-check-label-options mt-1 " for="terminated">Terminated</label>
                                            </div>
                                            <div class="form-check form-check-inline custom-radio">
                                                <input class="form-check-input  mb-2 " type="radio" id="resigned" value="resigned" wire:model="employee_status">
                                                <label class="form-check-label-options mt-1 " for="resigned">Resigned</label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('employee_status')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif

                                <div class="form-group col-md-6">
                                    <div class="input-group onboarding-check-boxes">
                                        <div>
                                            <label class="mt-1">International Employee </label><br>
                                            <div class="select-inputs">

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input  mb-2" type="radio" wire:model="inter_emp" value="yes" id="yesRadio" name="inter_emp">
                                                    <label class="form-check-label-options mt-1 " for="yesRadio">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input  mb-2" type="radio" wire:model="inter_emp" value="no" id="noRadio" name="inter_emp" checked>
                                                    <label class="form-check-label-options mt-1 " for="noRadio">No</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div>
                                        @error('inter_emp')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="input-group onboarding-check-boxes">
                                        <label class="mt-1">Job Mode </label>
                                        <div class="select-inputs">
                                            <div class="form-check form-check-inline mb-0 mx-2 ">
                                                <input class="form-check-input " type="radio" wire:model="job_mode" value="Office" id="OfficeRadio" name="job_mode">
                                                <label class="form-check-label-options mt-1 mb-0" for="officeRadio">Office</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0 mx-2">
                                                <input class="form-check-input " type="radio" wire:model="job_mode" value="Remote" id="RemoteRadio" name="job_mode">
                                                <label class="form-check-label-options mt-1 mb-0" for="remoteRadio">Remote</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0 mx-2">
                                                <input class="form-check-input " type="radio" wire:model="job_mode" value="Hybrid" id="HybridRadio" name="job_mode">
                                                <label class="form-check-label-options mt-1 mb-0" for="hybridRadio">Hybrid</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        @error('job_mode')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <hr class="hr-wizard" />
                            <button type="button" name="next" class="next ilynn-btn" value=" Save & Next" wire:click="nextPage">Save & Next</button>
                            <button type="button" name="previous" class="previous ilynn-btn" value="Back" wire:click="previousPage">Back</button>
                        </fieldset>
                        @endif
                        @if($currentStep==3)
                        <fieldset>
                            <div class=" m-0 row">
                                <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Personal Details</h2>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="date_of_birth">Date of Birth <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="date" class="form-control onboardinputs  placeholder-small m-0" wire:model="date_of_birth" max="{{ date('Y-m-d') }}">
                                    @error('date_of_birth')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="blood_group">Blood Group <span class="text-danger onboard-Valid">*</span></label>
                                    <select class="form-control onboardinputs custom-select  placeholder-small m-0" wire:model="blood_group">
                                        <option disabled selected value="">Select blood group</option>

                                        @foreach ($blood_groups as $blood)
                                        <option value="{{ $blood }}">{{ $blood }}</option>
                                        @endforeach
                                    </select>
                                    @error('blood_group')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="adhar_no">Aadhar Number <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter Aadhar number" wire:model="adhar_no">
                                    @error('adhar_no')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="pan_no">PAN Number <span class="text-danger onboard-Valid">*</span> </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Ex- ABCDE1234F" wire:model="pan_no" oninput="this.value = this.value.toUpperCase();">
                                    @error('pan_no')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="alternate_mobile_number">Emergency Phone Number </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter emergency phone number" wire:model="alternate_mobile_number">
                                    @error('alternate_mobile_number')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="email">Personal Email <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="email" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter personal email" wire:model="email">
                                    @error('email')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="passport_no">Passport Number </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter passport number " wire:model="passport_no">
                                    @error('passport_no')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="pf_no">PF Number </label>
                                    <input type="number" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter PF number " wire:model="pf_no">
                                    @error('pf_no')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="religion">Religion <span class="text-danger onboard-Valid">*</span> </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter religion" wire:model="religion">
                                    @error('religion')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="nationality">Nationality <span class="text-danger onboard-Valid">*</span> </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter nationality" wire:model="nationality">
                                    @error('nationality')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="input-group onboarding-check-boxes">
                                        <label class="mt-1">Martial Status <span class="text-danger onboard-Valid">*</span></label>
                                        <div class="select-inputs">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input mb-2" type="radio" wire:model="marital_status" value="unmarried" id="unmarriedRadio" name="marital_status_group">
                                                <label class="form-check-label-options mt-1 " for="unmarriedRadio">Single</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input mb-2" type="radio" wire:model="marital_status" value="married" id="marriedRadio" name="marital_status_group">
                                                <label class="form-check-label-options mt-1 " for="marriedRadio">Married</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        @error('marital_status')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="input-group onboarding-check-boxes">
                                        <label class="mt-1">Physically Challenge</label>
                                        <div class="select-inputs">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input  mb-2" type="radio" wire:model="physically_challenge" value="yes" id="yesRadio" name="physically_challenge_group">
                                                <label class="form-check-label-options mt-1" for="yesRadio">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input  mb-2" type="radio" wire:model="physically_challenge" value="no" id="noRadio" name="physically_challenge_group" checked>
                                                <label class="form-check-label-options mt-1" for="noRadio">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    @error('physically_challenge')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <hr class="hr-wizard" />
                            <button type="button" name="next" class="next ilynn-btn" value="Next" wire:click="nextPage">Next</button>
                            <!-- <input type="button" name="previous" class="previous ilynn-btn" value="Back" wire:click="previousPage" /> -->
                        </fieldset>
                        @endif

                        @if($currentStep==4)
                        <fieldset>
                            <div class=" m-0 row ">
                                <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Address Details</h2>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="address"> Permanent Address <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter Permanent address" wire:model="address">
                                    @error('address')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="city">City </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter city" wire:model="city">
                                    @error('city')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="state">State <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter state" wire:model="state">
                                    @error('state')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="postal_code">Pin Code </label>
                                    <input type="number" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter pin code" wire:model="postal_code">
                                    @error('postal_code')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="country">Country</label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter country" wire:model="country">
                                    @error('country')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="present_address"> Present Address <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter present address" wire:model="present_address">
                                    @error('present_address')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="facebook"> Facebook Url </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter facebook url" wire:model="facebook">
                                    @error('facebook')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="twitter"> Twitter Url </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter facebook url" wire:model="twitter">
                                    @error('twitter')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="linked_in"> Linked-In Url </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter facebook url" wire:model="linked_in">
                                    @error('linked_in')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>



                            <hr class="hr-wizard" />

                            <!-- If employeeId is not set, it means we're adding a new employee -->
                            <button type="button" name="next" class="next ilynn-btn" value=" Save & Next" wire:click="nextPage">Save & Next</button>
                            <!-- <input type="button" name="next" class="next ilynn-btn" value="Next" onclick="getChecked()" /> -->
                            <!-- <button type="button" name="previous" class="previous ilynn-btn" wire:click='previousPage' value="Back">Back</button> -->
                        </fieldset>
                        @endif

                        @if($currentStep==5)
                        @if($parentscurrentStep==1)
                        <fieldset>
                            <div class=" m-0 row">
                                <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Parents Details</h2>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="father_first_name">Father First Name <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter father first name" wire:model="father_first_name">
                                    @error('father_first_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="father_last_name">Father Last Name <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter father last name" wire:model="father_last_name">
                                    @error('father_last_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="mother_first_name">Mother First Name <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter mother first name" wire:model="mother_first_name">
                                    @error('mother_first_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="mother_last_name">Mother Last Name <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter mother last name" wire:model="mother_last_name">
                                    @error('mother_last_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="father_dob">Father DOB </label>
                                    <input type="date" class="form-control onboardinputs  placeholder-small m-0" wire:model="father_dob" max="{{ date('Y-m-d') }}">
                                    @error('father_dob')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="mother_dob">Mother DOB </label>
                                    <input type="date" class="form-control onboardinputs  placeholder-small m-0" wire:model="mother_dob" max="{{ date('Y-m-d') }}">
                                    @error('mother_dob')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="father_blood_group"> Father Blood Group <span class="text-danger onboard-Valid">*</span></label>
                                    <select class="form-control onboardinputs custom-select  placeholder-small m-0" wire:model="father_blood_group">
                                        <option disabled selected value="">Select blood group</option>

                                        @foreach ($blood_groups as $blood)
                                        <option value="{{ $blood }}">{{ $blood }}</option>
                                        @endforeach
                                    </select>
                                    @error('father_blood_group')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="mother_blood_group"> Mother Blood Group <span class="text-danger onboard-Valid">*</span></label>
                                    <select class="form-control onboardinputs custom-select  placeholder-small m-0" wire:model="mother_blood_group">
                                        <option disabled selected value="">Select blood group</option>

                                        @foreach ($blood_groups as $blood)
                                        <option value="{{ $blood }}">{{ $blood }}</option>
                                        @endforeach
                                    </select>
                                    @error('mother_blood_group')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="father_phone">Father Phone Number</label>
                                    <input type="number" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter father phone number" wire:model="father_phone">
                                    @error('father_phone')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="mother_phone">Mother Phone Number</label>
                                    <input type="number" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter mother phone number" wire:model="mother_phone">
                                    @error('mother_phone')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <hr class="hr-wizard" />
                            <button type="button" name="next" class="next ilynn-btn" value="Next" wire:click="nextPage">Next</button>
                            <button type="button" name="next" class="next ilynn-btn" value="Next" wire:click="skipPage">Skip</button>
                            <!-- <input type="button" name="previous" class="previous ilynn-btn" value="Back" wire:click="previousPage" /> -->
                        </fieldset>
                        @elseif($parentscurrentStep==2)
                        <div>
                            <div class="d-flex justify-content-center">
                                <div class=" m-0 row" style="width: 75%;">
                                    <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                        <h2 class="fs-subtitle">Employee Parents Optional Details</h2>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="father_email">Father Email </label>
                                        <input type="email" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter father email" wire:model="father_email">
                                        @error('father_email')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="mother_email">Mother Email </label>
                                        <input type="email" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter mother email" wire:model="mother_email">
                                        @error('mother_email')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="father_religion">Father Religion </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter father religion" wire:model="father_religion">
                                        @error('father_religion')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="mother_religion">Mother Religion </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter mother religion" wire:model="mother_religion">
                                        @error('mother_religion')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="father_nationality">Father Nationality </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter father nationality" wire:model="father_nationality">
                                        @error('father_nationality')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="mother_nationality">Mother Nationality </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter mother nationality" wire:model="mother_nationality">
                                        @error('mother_nationality')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="father_occupation">Father Occupation </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter father occupation" wire:model="father_occupation">
                                        @error('father_occupation')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="mother_occupation">Mother Occupation </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter mother occupation" wire:model="mother_occupation">
                                        @error('mother_occupation')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="father_address">Father Address </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter father address" wire:model="father_address">
                                        @error('father_address')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="mt-1" for="mother_address">Mother Address </label>
                                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter mother address" wire:model="mother_address">
                                        @error('mother_address')
                                        <span class="text-danger onboard-Valid">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group col-md-12">
                                            <label class="mt-1" for="father_image">Father Image</label>
                                            <input type="file" wire:model="father_image" accept=".png, .jpg, .jpeg" style="font-size:12px;border:none;width:100%;margin-bottom: 0px;">
                                            @error('father_image')
                                            <span class="text-danger onboard-Valid">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12" style="display: flex;justify-content:start">
                                            @if($father_image)

                                            <img src="{{ is_string($father_image) ? asset('storage/' . $father_image) : $father_image->temporaryUrl() }}" alt="Preview" style='height:100px;width:100px' class="img-thumbnail" />
                                            @else
                                            @if(strlen($father_image_binary)>10)
                                            <img src="data:image/jpeg;base64,{{ $father_image_binary }}" alt="" style='height:100px;width:100px' class="img-thumbnail" />
                                            @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group col-md-12">
                                            <label class="mt-1" for="image">Mother Image</label>
                                            <input type="file" wire:model="mother_image" accept=".png, .jpg, .jpeg" style="font-size:12px;border:none;width:100%; margin-bottom: 0px;">
                                            @error('mother_image')
                                            <span class="text-danger onboard-Valid">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12" style="display: flex;justify-content:start">
                                            @if($mother_image)
                                            <img src="{{ is_string($mother_image) ? asset('storage/' . $mother_image) : $mother_image->temporaryUrl() }}" alt="Preview" style='height:100px;width:100px' class="img-thumbnail" />
                                            @else
                                            @if(strlen($mother_image_binary)>10)
                                            <img src="data:image/jpeg;base64,{{ $mother_image_binary }}" alt="" style='height:100px;width:100px' class="img-thumbnail" />
                                            @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr class="hr-wizard" />
                            <button type="button" name="next" class="next ilynn-btn" value=" Skip & Next" wire:click="parentsnextPage"> Save & Next</button>
                            <button type="button" name="previous" class="previous ilynn-btn" value="Back" wire:click="parentspreviousPage">Back </button>

                        </div>
                        @endif
                        @endif
                        @if($currentStep==6)
                        <fieldset>
                            <div class=" m-0 row ">
                                <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Spouse Details</h2>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_first_name"> Spouse First Name </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter spouse first name" wire:model="spouse_first_name">
                                    @error('spouse_first_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_last_name">Spouse Last Name </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter last name" wire:model="spouse_last_name">
                                    @error('spouse_last_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="email">Spouse Email </label>
                                    <input type="spouse_email" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter spouse email" wire:model="spouse_email">
                                    @error('spouse_email')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="  input-group onboarding-check-boxes ">
                                        <label class="mt-1">Spouse Gender </label>
                                        <div class="select-inputs">
                                            <div class="form-check form-check-inline mb-0 mx-2 ">
                                                <input class="form-check-input " type="radio" wire:model="spouse_gender" value="Male" id="maleRadio" name="gender">
                                                <label class="form-check-label-options mt-1 mb-0" for="spouse_gender">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline mb-0 mx-2">
                                                <input class="form-check-input " type="radio" wire:model="spouse_gender" value="Female" id="femaleRadio" name="gender">
                                                <label class="form-check-label-options mt-1 mb-0" for="spouse_gender">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_adhar_no">Spouse Aadhar Number </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter spouse Aadhar number" wire:model="spouse_adhar_no">
                                    @error('spouse_adhar_no')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_pan_no">Spouse PAN Number </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Ex- ABCDE1234F" wire:model="spouse_pan_no" oninput="this.value = this.value.toUpperCase();">
                                    @error('spouse_pan_no')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_qualification">Spouse Qualification </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter spouse qualification" wire:model="spouse_qualification">
                                    @error('spouse_qualification')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_profession">Spouse Profession </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter spouse profession" wire:model="spouse_profession">
                                    @error('spouse_profession')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_dob"> Spouse Date of Birth </label>
                                    <input type="date" class="form-control onboardinputs  placeholder-small m-0" wire:model="spouse_dob" max="{{ date('Y-m-d') }}">
                                    @error('spouse_dob')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_bld_group"> Spouse Blood Group </label>
                                    <select class="form-control onboardinputs custom-select  placeholder-small m-0" wire:model="spouse_bld_group">
                                        <option disabled selected value="">Select blood group</option>

                                        @foreach ($blood_groups as $blood)
                                        <option value="{{ $blood }}">{{ $blood }}</option>
                                        @endforeach
                                    </select>
                                    @error('spouse_bld_group')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_religion"> Spouse Religion </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter spouse religion" wire:model="spouse_religion">
                                    @error('spouse_religion')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_nationality">Spouse Nationality </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter spouse nationality" wire:model="spouse_nationality">
                                    @error('spouse_nationality')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="spouse_address"> Spouse Address</label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter spouse address" wire:model="spouse_address">
                                    @error('spouse_address')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row m-0 mb-2">
                                    <h2 class="fs-subtitle mt-3">Children Details :</h2>

                                    <div class="row m-0 " style="border:1px solid #dad3d3; padding-bottom:10px">

                                        <div class="row m-0">
                                            <div class="form-group col-md-6">
                                                <label class="mt-1">Child-Name</label>
                                                <input type="text" class="form-control onboardinputs placeholder-small m-0" placeholder="Enter child name" wire:model="newChildren.name">
                                                @error('newChildren.name')
                                                <span class="text-danger onboard-Valid">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label class="mt-1">Child-Date-Of-Birth</label>
                                                <input type="date" class="form-control onboardinputs placeholder-small m-0" placeholder="Enter child gender" wire:model="newChildren.dob">
                                                @error('newChildren.dob')
                                                <span class="text-danger onboard-Valid">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="input-group onboarding-check-boxes">
                                                    <label class="mt-1">Child-Gender </label>
                                                    <div class="select-inputs">
                                                        <div class="form-check form-check-inline mb-0 mx-2 ">
                                                            <input class="form-check-input " type="radio" wire:model="newChildren.gender" value="Male" id="maleRadio" name="gender">
                                                            <label class="form-check-label-options mt-1 mb-0" for="maleRadio">Male</label>
                                                        </div>
                                                        <div class="form-check form-check-inline mb-0 mx-2">
                                                            <input class="form-check-input " type="radio" wire:model="newChildren.gender" value="Female" id="femaleRadio" name="gender">
                                                            <label class="form-check-label-options mt-1 mb-0" for="femaleRadio">Female</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    @error('newChildren.gender')
                                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>



                                    </div>

                                    <div class="form-group col-md-12  mt-2" style="display: flex;justify-content: end;">
                                        <button type="button" class="ilynn-btn" wire:click="addChildren">Add </button>
                                    </div>

                                    <!-- <div class="form-group col-md-12">
                                    <button type="button" class="btn btn-success" wire:click="saveChildren">Save Children Details</button>
                                </div> -->
                                </div>
                                @if(count($children)>0)
                                <table class="onboardingtable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Child Name</th>
                                            <th>Child Gender</th>
                                            <th>Child Date of Birth </th>
                                            <th>Options </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($children as $index => $child)
                                        <tr>
                                            <td>{{$index +1 }}</td>
                                            <td>{{$child['name']}}</td>
                                            <td>{{$child['gender']}}</td>
                                            <td>{{$child['dob']}}</td>
                                            <td>
                                                <div class="col-md-12 d-flex justify-content-around ">
                                                    <button type="button" class="btn btn-link p-0" wire:click="editEducation({{ $index }})" style="color: #306cc6; text-decoration:none">
                                                        ✎
                                                    </button>
                                                    <button type="button" class="btn btn-link p-0" wire:click="removeEducation({{ $index }})" style="color: red; font-size: 1.5rem;text-decoration:none">
                                                        &times;
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif

                            </div>

                            <hr class="hr-wizard" />

                            <!-- If employeeId is not set, it means we're adding a new employee -->
                            <button type="button" name="next" class="next ilynn-btn" value=" Save & Next" wire:click="nextPage">Save & Next</button>
                            <!-- <input type="button" name="next" class="next ilynn-btn" value="Next" onclick="getChecked()" /> -->
                            <button type="button" name="next" class="next ilynn-btn" value="Next" wire:click="skipPage">Skip</button>
                        </fieldset>
                        @endif
                        @if($currentStep==7)
                        <fieldset>
                            <div class=" m-0 row ">
                                <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Bank Details</h2>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="bank_name"> Bank Name <span class="text-danger onboard-Valid">*</span> </label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small  m-0" placeholder="Enter bank name" wire:model="bank_name">
                                    @error('bank_name')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="bank_branch">Branch Name <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter branch name" wire:model="bank_branch">
                                    @error('bank_branch')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="account_number">Account No <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="number" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter account number" wire:model="account_number">
                                    @error('account_number')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="ifsc_code">IFSC Code <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" maxlength="11" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter IFSC code" oninput="this.value = this.value.toUpperCase();" wire:model="ifsc_code">
                                    @error('ifsc_code')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="mt-1" for="bank_address"> Bank Address <span class="text-danger onboard-Valid">*</span></label>
                                    <input type="text" class="form-control onboardinputs  placeholder-small m-0" placeholder="Enter bank address" wire:model="bank_address">
                                    @error('bank_address')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>



                            <hr class="hr-wizard" />

                            <!-- If employeeId is not set, it means we're adding a new employee -->
                            <button type="button" name="next" class="next ilynn-btn" value=" Save & Next" wire:click="nextPage"> Save & Next</button>
                            <!-- <input type="button" name="next" class="next ilynn-btn" value="Next" onclick="getChecked()" /> -->
                            <!-- <input type="button" name="previous" class="previous ilynn-btn" wire:click='previousPage' value="Back" /> -->
                        </fieldset>
                        @endif
                        @if($currentStep==8)
                        <fieldset>
                            <div class=" m-0 row">
                                <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Educational Details</h2>
                                </div>
                                <div class="row m-0 mb-2">
                                    @error('education') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Educational Level <span class="text-danger onboard-Valid">*</span></label>
                                        <select class="form-control onboardinputs custom-select placeholder-small m-0" wire:model="newEducation.level">
                                            <option value="">Select Level</option>
                                            <option value="Masters">Master's</option>
                                            <option value="Bachelors">Bachelor's</option>
                                            <option value="Intermediate">Intermediate</option>
                                        </select>
                                        @error('newEducation.level') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Institution <span class="text-danger onboard-Valid">*</span></label>
                                        <input type="text" class="form-control onboardinputs placeholder-small m-0" placeholder="Enter institution name" wire:model="newEducation.institution">
                                        @error('newEducation.institution') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Course name <span class="text-danger onboard-Valid">*</span></label>
                                        <input type="text" class="form-control onboardinputs placeholder-small m-0" placeholder="Enter course name" wire:model="newEducation.course_name">
                                        @error('newEducation.course_name') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Year of Passing <span class="text-danger onboard-Valid">*</span></label>
                                        <select class="form-control onboardinputs custom-select placeholder-small m-0" wire:model="newEducation.year_of_passing">
                                            <option value="">Select Year</option>
                                            @for ($year = date('Y'); $year >= date('Y')-50; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                        @error('newEducation.year_of_passing') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Percentage/CGPA <span class="text-danger onboard-Valid">*</span></label>
                                        <input type="number" class="form-control onboardinputs placeholder-small m-0" placeholder="Enter percentage or CGPA" wire:model="newEducation.percentage">
                                        @error('newEducation.percentage') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-12" style="display: flex; justify-content:end; gap:10px">
                                        <button class="ilynn-btn" type="button" class="btn btn-primary" wire:click="addEducation">
                                            Add
                                        </button>
                                    </div>
                                </div>
                                @if(count($education)>0)
                                <table class="onboardingtable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Level</th>
                                            <th>Institution</th>
                                            <th>Course Name</th>
                                            <th>Year of Passing</th>
                                            <th>Percentage</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($education as $index => $edu)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$edu['level']}}</td>
                                            <td>{{$edu['institution']}}</td>
                                            <td>{{$edu['course_name']}}</td>
                                            <td>{{$edu['year_of_passing']}}</td>
                                            <td>{{$edu['percentage']}}</td>
                                            <td>
                                                <div class="col-md-12 d-flex justify-content-around ">
                                                    <button type="button" class="btn btn-link p-0" wire:click="editEducation({{ $index }})" style="color: #306cc6; text-decoration:none">
                                                        ✎
                                                    </button>
                                                    <button type="button" class="btn btn-link p-0" wire:click="removeEducation({{ $index }})" style="color: red; font-size: 1.5rem;text-decoration:none">
                                                        &times;
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif

                            </div>
                            <hr class="hr-wizard" />

                            <!-- If employeeId is not set, it means we're adding a new employee -->

                            <button type="button" name="next" class="next ilynn-btn" value=" Save & Next" wire:click="nextPage"> Save & Next</button>

                            <!--  <input type="button" name="next" class="next ilynn-btn" value="Next" onclick="getChecked()" /> -->
                            <!-- <input type="button" name="previous" class="previous ilynn-btn" wire:click='previousPage' value="Back" />-->

                        </fieldset>


                        @endif
                        @if($currentStep==9)
                        <fieldset>
                            <div class=" m-0 row">
                                <div class="row m-0 mb-2 mt-3" style="text-align: center">
                                    <h2 class="fs-subtitle">Employee Experience Details</h2>
                                </div>
                                <div class="row m-0 mb-2">
                                    @error('experience') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Company Name </label>
                                        <input type="text" class="form-control onboardinputs placeholder-small m-0" placeholder="Enter company name" wire:model="newExperience.company_name">
                                        @error('newExperience.company_name') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Skills </label>
                                        <input type="text" class="form-control onboardinputs placeholder-small m-0" placeholder="Enter technologies worked on" wire:model="newExperience.skills">
                                        @error('newExperience.skills') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Start Date </label>
                                        <input type="date" class="form-control onboardinputs placeholder-small m-0" wire:model="newExperience.start_date">
                                        @error('newExperience.start_date') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">End Date </label>
                                        <input type="date" class="form-control onboardinputs placeholder-small m-0" wire:model="newExperience.end_date">
                                        @error('newExperience.end_date') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="mt-1">Job Description </label>
                                        <textarea class="form-control onboardinputs placeholder-small m-0" placeholder="Enter job description" wire:model="newExperience.description"></textarea>
                                        @error('newExperience.description') <span class="text-danger onboard-Valid">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-md-12 mt-2" style="display: flex; justify-content: end; gap: 10px;">
                                        <button class="ilynn-btn btn btn-primary" type="button" wire:click="addExperience">
                                            Add
                                        </button>
                                    </div>
                                </div>



                                @if(count($experience)>0)
                                <table class="onboardingtable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Company Name</th>
                                            <th>Skills</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Job Description</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($experience as $index => $exp)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{$exp['company_name']}}</td>
                                            <td>{{$exp['skills']}}</td>
                                            <td>{{$exp['start_date']}}</td>
                                            <td>{{$exp['end_date']}}</td>
                                            <td>{{$exp['description']}}</td>
                                            <td>
                                                <div class="col-md-12 d-flex justify-content-around ">
                                                    <button type="button" class="btn btn-link p-0" wire:click="editExperience({{ $index }})" style="color: #306cc6; text-decoration:none">
                                                        ✎
                                                    </button>
                                                    <button type="button" class="btn btn-link p-0" wire:click="removeExperience({{ $index }})" style="color: red; font-size: 1.5rem;text-decoration:none">
                                                        &times;
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif

                            </div>
                            <hr class="hr-wizard" />

                            <!-- If employeeId is not set, it means we're adding a new employee -->
                            <button type="button" name="next" class="next ilynn-btn" value=" Save & Next" wire:click="nextPage">Complete</button>
                            <!-- <input type="button" name="next" class="next ilynn-btn" value="Next" onclick="getChecked()" /> -->
                            <!-- <input type="button" name="previous" class="previous ilynn-btn" wire:click='previousPage' value="Back" /> -->
                        </fieldset>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($successModal)
    <div class="modal show d-block " tabindex="-1" role="dialog" style="display: block; ">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header alert alert-success w-100">
                    <h5 class="modal-title ">Success</h5>
                    <a href="/hr/update-employee-details" style="margin-left: auto;"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button></a>
                </div>
                <div class="modal-body d-flex" style="justify-content: center; flex-direction:column;align-items:center">

                    @if($action=='add')
                    <p style="width: fit-content;" class="text-align-center "><strong>{{ucfirst(strtolower($this->first_name))}} {{ucfirst(strtolower($this->last_name))}}</strong> Onboarded successful! </p>
                    <p style="width: fit-content;" class="text-align-cente">with <strong>{{$emp_id}} </strong> Employee Id</p>
                    @elseif($action=='edit')
                    <p style="width: fit-content;" class="text-align-center "><strong>{{ucfirst(strtolower($this->first_name))}} {{ucfirst(strtolower($this->last_name))}}</strong> details updated successful! </p>
                    @elseif($action=='reJoin')
                    <p style="width: fit-content;" class="text-align-center "><strong>{{ucfirst(strtolower($this->first_name))}} {{ucfirst(strtolower($this->last_name))}}</strong> Re-Joined successful! </p>
                    <p style="width: fit-content;" class="text-align-cente">with <strong>{{$emp_id}} </strong> Employee Id</p>
                    @endif

                </div>
                <div class="modal-footer justify-content-center">
                    <a href="/hr/update-employee-details"> <button type="button" class="ilynn-btn" data-bs-dismiss="modal" wire:click="closeModal">Close</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    @if($employeeListModal)
    <div class="modal show d-block " tabindex="-1" role="dialog" style="display: block; ">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="">
                <div class="modal-header alert alert-success m-0">
                    <!-- <h5 class="modal-title ">Success</h5> -->
                    <h5 class="modal-title ">Select Reporting Manager</h5>
                    <a style="margin-left: auto;"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeEmployeeList"></button></a>
                </div>
                <div class="modal-body d-flex" style=" flex-direction:column;align-items:center ;max-height:350px;overflow-y: auto;">
                    <div>
                        <h6 for="manager_id">Employee List :</h6>
                        <!-- <input type="text" wire:model="searchedEmployee" wire:input="searchEmployees" placeholder="Search"> -->
                        <input wire:input="searchEmployees" wire:model="searchedEmployee" type="text" placeholder="Search by Name / Emp ID" class="search-input form-control" style="border-radius: 5px;padding: 3px 5px;border: 1px solid #ccc;outline:none;height:30px;">
                        @foreach ($employeeList as $employee)
                        <div class="form-check mt-2">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="manager_id"
                                id="emp_{{ $employee->emp_id }}"
                                value="{{ $employee->emp_id }}"
                                wire:model="selectedManager">

                            <label class="form-check-label" for="emp_{{ $employee->emp_id }}">
                                {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}
                            </label>
                        </div>
                        @endforeach
                    </div>


                </div>
                <div class="modal-footer justify-content-center">
                    <a> <button type="button" class="ilynn-btn" data-bs-dismiss="modal" wire:click="SelectReportingTo">Continue</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    @if($islocationModel)
    <div class="modal show d-block " tabindex="-1" role="dialog" style="display: block; ">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="">
                <div class="modal-header alert alert-success m-0">
                    <!-- <h5 class="modal-title ">Success</h5> -->
                    <h5 class="modal-title ">Add Job Location</h5>
                    <a style="margin-left: auto;"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeLocationModel"></button></a>
                </div>
                <div class="modal-body d-flex" style=" flex-direction:column;align-items:center ;max-height:350px;overflow-y: auto;">
                    <div style="justify-content: center;">
                        <h6 for="manager_id">Job Location</h6>
                        <input type="text" class="form-control onboardinputs  placeholder-small  m-0" wire:model="add_job_location" placeholder="Enter job location" style="width: 100%;">
                    </div>
                    @error('add_job_location')
                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                    @enderror


                </div>
                <div class="modal-footer justify-content-center">
                    <a> <button type="button" class="ilynn-btn" data-bs-dismiss="modal" wire:click="addJobLocation">Save</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    @if($isProjectModel)
    <div class="modal show d-block " tabindex="-1" role="dialog" style="display: block; ">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="">
                <div class="modal-header alert alert-success m-0">
                    <!-- <h5 class="modal-title ">Success</h5> -->
                    <h5 class="modal-title ">Add Project Details</h5>
                    <a style="margin-left: auto;"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeProjectModel"></button></a>
                </div>
                <div class="modal-body d-flex" style=" flex-direction:column;align-items:center ;max-height:350px;overflow-y: auto;">
                    <div class="row w-100">
                        <div class="col-md-6">
                            <label for="">Project Name</label>
                            <input type="text" wire:model="project_name" class="form-control" name="" id="">
                            @error('project_name')
                            <span class="text-danger onboard-Valid">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="">Client Name</label>
                            <input type="text" wire:model="client_name" class="form-control" name="" id="">
                            @error('client_name')
                            <span class="text-danger onboard-Valid">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row w-100">
                        <div class="col-md-6">
                            <label for="">Start Date</label>
                            <input type="date" wire:model="proj_start_date" class="form-control" name="" id="">
                            @error('proj_start_date')
                            <span class="text-danger onboard-Valid">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="">End Date</label>
                            <input type="date" wire:model="proj_end_date" class="form-control" name="" id="">
                            @error('proj_end_date')
                            <span class="text-danger onboard-Valid">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <a> <button type="button" class="ilynn-btn" data-bs-dismiss="modal" wire:click="addProjects">Save</button></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
