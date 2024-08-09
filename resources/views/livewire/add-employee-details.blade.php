<div>
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
        <div class="container" style="padding:0px;margin: 0;">
            <div id="alert-container" class="alert-container">
                <!-- <span id="close-btn" class="close-btn">&times;</span> -->
                @if (session()->has('emp_success'))
                {{ session('emp_success') }}
                @endif
            </div>
            <script>
                // Wait for the document to be ready (if using jQuery)
                $(document).ready(function() {
                    // Show the alert container
                    $('#alert-container').fadeIn();

                    // Set a timeout to hide the alert after a certain duration (e.g., 5000 milliseconds)
                    setTimeout(function() {
                        $('#alert-container').fadeOut();
                    }, 5000); // Adjust the duration as needed

                    // Close the alert on close button click
                    $('#close-btn').on('click', function() {
                        $('#alert-container').fadeOut();
                    });
                });
            </script>

            <div class="container " style="background:#f2f2f2;margin:0;padding:0;">
                <div class="d-flex justify-content-between p-3">
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
                        </ol>
                    </nav>
                    <div>
                        <button style="width:200px; border-radius: 5px; background-color: rgb(2, 17, 79); color: white;"><a href="/update-employee-details" style="text-decoration: none;color:white;font-size:14px;">Employee
                                List</a></button>
                    </div>
                </div>

                <!-- multistep form -->
                <form id="msform" wire:submit.prevent="register" enctype="multipart/form-data" class="row m-0">
                    <div class="row m-0 mt-3 mb-3" style="text-align: center">
                        <h5 class="fs-title">Employee Registration Form</h5>
                    </div>
                    <!-- progressbar -->
                    <div class="row m-0" style="text-align: center">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <ul id="progressbar">
                                <li class="{{ $currentStep >= 1 ? 'active' : '' }}">Employee Details</li>
                                <li class="{{ $currentStep >= 2 ? 'active' : '' }}">Employee Job Details</li>
                                <li class="{{ $currentStep >= 3 ? 'active' : '' }}"> Employee Personal Details</li>
                                <li class="{{ $currentStep == 4 ? 'active' : '' }}">Other Details</li>
                            </ul>
                        </div>
                        <div class="col-md-2"></div>

                    </div>

                    <!-- fieldsets -->
                    @if($currentStep==1)
                    <fieldset>
                        <div class="bg-light m-0 row">
                            <div class="row m-0 mb-2 mt-3 p-0" style="text-align: center">
                                <h2 class="fs-subtitle">Employee Details</h2>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="mt-1" for="first_name">First Name  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control  placeholder-small  m-0" placeholder="Enter first name" wire:model="first_name" style="width:100%;">
                                @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="mt-1" for="last_name">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter last name" wire:model="last_name">
                                @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="mt-1" for="mobile_number">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter phone number" wire:model="mobile_number">
                                @error('mobile_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="mt-1" for="email">Company Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control  placeholder-small m-0" placeholder="Enter company email" wire:model="company_email">
                                @error('company_email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <!-- Password -->
                            {{-- <div class="form-group col-md-6">
                                    <label  class="mt-1" for="password">Password </label>
                                    <input type="password" class="form-control  placeholder-small m-0" placeholder="Enter passowrd" wire:model="password"
                                       >
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="form-group col-md-6">
                            <div class="input-group onboarding-check-boxes">
                                <label class="mt-1">Gender <span class="text-danger">*</span> </label>
                                <div>
                                    <div class="form-check form-check-inline mb-0 mx-2 ">
                                        <input class="form-check-input " type="radio" wire:model="gender" value="Male" id="maleRadio" name="gender">
                                        <label class="form-check-label-options mt-1 mb-0" for="maleRadio">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0 mx-2">
                                        <input class="form-check-input " type="radio" wire:model="gender" value="Female" id="femaleRadio" name="gender">
                                        <label class="form-check-label-options mt-1 mb-0" for="femaleRadio">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                        {{-- Upload Employee Image --}}
                        <div class="form-group col-md-6">
                            <label class="mt-1" for="image">Employee Image</label>
                            <input type="file" wire:model="image" style="font-size:12px;border:none;">
                            @error('image')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6"></div>
                        <div class="form-group col-md-6  " style="display: flex;justify-content:end">
                            @if($image)
                            <img src="{{ is_string($image) ? asset('storage/' . $image) : $image->temporaryUrl() }}" alt="Preview" style='height:100px;width:100px' class="img-thumbnail" />
                            @elseif($employee && $employee->image)
                            <img src="{{ asset('storage/' . $employee->image) }}" alt="Preview" style='height:100px;width:100px' class="img-thumbnail" />
                            @endif
                        </div>

            </div>
            <hr class="hr-wizard" />
            <input type="button" name="next" class="next ilynn-btn" value="Next" wire:click="nextPage" />
            </fieldset>
            @endif
            @if($currentStep==2)
            <fieldset>
                <h2 class="fs-title">Job Details</h2>
                <div class="bg-light m-0 row">
                    <div class="row m-0 mb-2 mt-3" style="text-align: center">
                        <h2 class="fs-subtitle">Job Details</h2>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="hire_date">Hire Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control  placeholder-small m-0" wire:model="hire_date" max="{{ date('Y-m-d') }}">
                        @error('hire_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">

                        <div class="input-group onboarding-check-boxes">
                            <label class="mt-1" for="employee_type">Employee Type</label>
                            <div>
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
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label class="mt-1" for="company_id">Company Name <span class="text-danger">*</span></label>
                        <select wire:click="selectedCompany" class="form-control custom-select   placeholder-small m-0" wire:model="company_id" style="margin-bottom: 10px;">
                            <option disabled value="">Select Company</option>
                            <!-- Add a default option -->
                            @foreach ($companieIds as $id)
                            <option value="{{ $id->company_id }}">{{ $id->company_name }}</option>
                            @endforeach
                        </select>

                        @error('company_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-6">
                        <label class="mt-1" for="job_title">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter job title" wire:model="job_title">
                        @error('job_title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="emp_domain">Employee Domain </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter employee domain " wire:model="emp_domain">
                        @error('emp_domain')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="job_location">Job Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter job location " wire:model="job_location">
                        @error('job_location')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-6">
                        <label class="mt-1" for="department">Department <span class="text-danger">*</span></label>
                        <select wire:change="selectedDepartment($event.target.value)" class="form-control  placeholder-small m-0" wire:model="department_id">
                            <option disabled selected value="">Select Department</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->dept_id }}">{{ $department->department }}</option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-6">
                        <label class="mt-1" for="department"> Sub Department <span class="text-danger">*</span></label>
                        <select class="form-control  placeholder-small m-0" wire:model="sub_department_id" style="margin-bottom: 10px;">
                            <option disabled selected value="">Select sub department</option>
                            @foreach ($sub_departments as $sub_department)
                            <option value="{{ $sub_department->sub_dept_id }}">{{ $sub_department->sub_department }}</option>
                            @endforeach
                        </select>
                        @error('sub_department_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label class="mt-1" for="manager_id">Reporting To <span class="text-danger">*</span></label>
                        <select wire:change="fetchReportTo" class="form-control  placeholder-small m-0"  wire:model="manager_id" style="margin-bottom: 10px;">
                            <option value="">Select manager</option>
                            <!-- Add a default option -->
                            @if ($managerIds != null)
                            @foreach ($managerIds as $id)
                            <option value="{{ $id->emp_id }}">{{ucwords(strtolower( $id->first_name)) }} {{ ucwords(strtolower($id->last_name)) }}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('manager_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="department">Shift Type </label>
                        <select  class="form-control  placeholder-small m-0" wire:model="shift_type">
                            <option  selected value="GS">General Shift</option>
                            <option   value="AS">Afternoon Shift</option>
                            <option   value="ES">Evening Shift</option>

                        </select>
                        @error('shift_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="hire_date">Shift Start Time </label>
                        <input type="time" class="form-control  placeholder-small m-0" wire:model="shift_start_time" >
                        @error('shift_start_time')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="hire_date">Shift End Time </label>
                        <input type="time" class="form-control  placeholder-small m-0" wire:model="shift_end_time" >
                        @error('shift_end_time')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label class="mt-1" for="referrer">Referrer </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter referrer " wire:model="referrer">
                        @error('referrer')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="referrer">Probation Period </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter probation period "   wire:model="probation_period">
                        @error('probation_period')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="confirmation_date">Confirmation Date </label>
                        <input type="date" class="form-control  placeholder-small m-0"  placeholder="Enter confirmation date  " wire:model="confirmation_date" max="{{ date('Y-m-d') }}">
                        @error('confirmation_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="notice_period">Notice Period </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter notice period "   wire:model="notice_period">
                        @error('notice_period')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
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
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <div class="input-group onboarding-check-boxes">
                            <div>
                                <label class="mt-1">International Employee </label><br>
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
                        <div>
                            @error('inter_emp')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="input-group onboarding-check-boxes">
                            <label class="mt-1">Job Mode </label>
                            <div>
                                <div class="form-check form-check-inline mb-0 mx-2 ">
                                    <input class="form-check-input " type="radio" wire:model="job_mode" value="Office" id="OfficeRadio" name="job_mode">
                                    <label class="form-check-label-options mt-1 mb-0" for="maleRadio">Office</label>
                                </div>
                                <div class="form-check form-check-inline mb-0 mx-2">
                                    <input class="form-check-input " type="radio" wire:model="job_mode" value="Remote" id="RemoteRadio" name="job_mode">
                                    <label class="form-check-label-options mt-1 mb-0" for="femaleRadio">Remote</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            @error('job_mode')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>

                <hr class="hr-wizard" />
                <input type="button" name="next" class="next ilynn-btn" value="Next" wire:click="nextPage" />
                <input type="button" name="previous" class="previous ilynn-btn" value="Back" wire:click="previousPage" />
            </fieldset>
            @endif

            @if($currentStep==3)
            <fieldset>
                <div class="bg-light m-0 row">
                    <div class="row m-0 mb-2 mt-3" style="text-align: center">
                        <h2 class="fs-subtitle">Employee Personal Details</h2>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="mt-1" for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control  placeholder-small m-0" wire:model="date_of_birth" max="{{ date('Y-m-d') }}">
                        @error('date_of_birth')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label class="mt-1" for="blood_group">Blood Group <span class="text-danger">*</span></label>
                        <select class="form-control  placeholder-small m-0" wire:model="blood_group">
                            <option disabled selected value="">Select blood group</option>

                            @foreach ($blood_groups as $blood)
                            <option value="{{ $blood }}">{{ $blood }}</option>
                            @endforeach
                        </select>
                        @error('blood_group')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="religion">Religion <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter religion" wire:model="religion">
                        @error('religion')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="nationality">Nationality <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter nationality" wire:model="nationality">
                        @error('nationality')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="aadhar_no">Aadhar Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter adhar number" wire:model="aadhar_no">
                        @error('aadhar_no')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="alternate_mobile_number">Alternate Phone Number </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter alternate phone number" wire:model="alternate_mobile_number">
                        @error('alternate_mobile_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="email">Personal Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control  placeholder-small m-0" placeholder="Enter email" wire:model="email">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <div class="input-group onboarding-check-boxes">
                            <label class="mt-1">Martial Status <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input  mb-2" type="radio" wire:model="marital_status" value="unmarried" id="unmarriedRadio" name="marital_status_group">
                                    <label class="form-check-label-options mt-1 " for="unmarriedRadio">Single</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input  mb-2" type="radio" wire:model="marital_status" value="married" id="marriedRadio" name="marital_status_group">
                                    <label  class="form-check-label-options mt-1 " for="marriedRadio">Married</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            @error('marital_status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <div class="input-group onboarding-check-boxes">
                            <label class="mt-1">Physically Challenge</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input  mb-2" type="radio" wire:model="physically_challenge" value="Yes" id="yesRadio" name="physically_challenge_group">
                                    <label  class="form-check-label-options mt-1" for="yesRadio">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input  mb-2" type="radio" wire:model="physically_challenge" value="No" id="noRadio" name="physically_challenge_group" checked>
                                    <label  class="form-check-label-options mt-1" for="noRadio">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        @error('physically_challenge')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <hr class="hr-wizard" />
                <input type="button" name="next" class="next ilynn-btn" value="Next" wire:click="nextPage" />
                <input type="button" name="previous" class="previous ilynn-btn" value="Back" wire:click="previousPage" />
            </fieldset>
            @endif

            @if($currentStep==4)
            <fieldset>
                <div class="bg-light m-0 row ">
                    <div class="row m-0 mb-2 mt-3" style="text-align: center">
                        <h2 class="fs-subtitle">Other Details</h2>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="address">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter address" wire:model="address">
                        @error('address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="city">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter city" wire:model="city">
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="state">State <span class="text-danger">*</span></label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter state" wire:model="state">
                        @error('state')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="postal_code">Pin Code </label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter pin code" wire:model="postal_code">
                        @error('postal_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="country">Country :</label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter country" wire:model="country">
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="education">Education :</label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter education" wire:model="education">
                        @error('education')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="experience">Experience :</label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter experience" wire:model="experience">
                        @error('experience')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mt-1" for="spouse">Spouse:</label>
                        <input type="text" class="form-control  placeholder-small m-0" placeholder="Enter spouse name" wire:model="spouse">
                        @error('spouse')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <hr class="hr-wizard" />
                @if($employeeId)
                <button wire:click="updateEmployee" class="btn btn-primary" wire:loading.attr="disabled" style="float: inline-end">Update</button>
                @else
                <!-- If employeeId is not set, it means we're adding a new employee -->
                <button wire:click="register" class="btn btn-primary" wire:loading.attr="disabled" style="float: inline-end">Save</button>
                @endif
                <!-- <input type="button" name="next" class="next ilynn-btn" value="Next" onclick="getChecked()" /> -->
                <input type="button" name="previous" class="previous ilynn-btn" wire:click='previousPage' value="Back" />
            </fieldset>
            @endif
            </form>



        </div>
    </div>
</div>
</div>
