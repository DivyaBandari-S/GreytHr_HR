<div>
    <style>
        .custom-container {
            border-radius: 5px;
            background-color: #F1F9FB;
            border: 1px solid silver;
            padding: 15px;
        }

        .main-text {
            font-size: 12px;
            width: 85%;
        }

        .help-text {
            font-size: 12px;
            color: blue;
            cursor: pointer;
            margin-left: auto;
        }

        .secondary-text {
            font-size: 12px;
            margin-top: 10px;
            font-weight: 200;
        }
        
        /* Added CSS for the image container */
        .image-container {
            height: 180px; /* Fixed height to prevent image movement */
        }
    </style>
    <div class="container mt-3">
        <div class="row">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true">Main</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">Activity</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab" style="margin-top:10px;">
                <div class="container mt-3">
                    <div class="row justify-content-center">
                        <div class="col-md-10 custom-container d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <p class="main-text mb-0">This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.</p>
                                <p style="font-size: 12px; cursor: pointer;color:deepskyblue;font-weight:500;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
                            </div>
                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore greytHR by 
    <span style="color:darkturquoise">Help-Doc</span>, watching How-to 
    <span style="color:darkturquoise">Videos</span> and 
    <span style="color:darkturquoise">FAQ</span>
</div>
@endif

                        </div>
                    </div>
              
       
       
        <div class="container justify-content-center d-flex">
    <div class="row justify-content-center mt-3 flex-column bg-white" style="border-radius: 5px; font-size:12px; width:88%;">
        <div class="col-md-9">
            <div class="row" style="display:flex;">
                <div class="col-md-8 mt-2">
                    <b>Start searching to see specific employee details here</b>
                    <div class="col mt-3" style="display: flex;">
                        <p style="font-size: 12px; font-weight:260">Employee Type:</p>
                        <p>Current Employees</p>
                    </div>
                    <p style="margin-left:15px;cursor: pointer;" wire:click="NamesSearch">Search Employee</p>
                    <div class="row m-0">
                    <div class="row m-0">
    @if(!empty($selectedPeopleNames))
        <div class="container" style="height:auto;border:10px;width:250px;border:1px solid blue;border-radius:15px">
            {!! implode(';', array_unique($selectedPeopleNames)) !!}
        </div>
    @endif
</div>



                            @if($isNames)
                            <div style="border-radius:5px;background-color:grey;padding:8px;width:400px;margin-top:10px; height: auto;">
                                <div class="input-group" style="margin-bottom: 10px;">
                                    <input wire:model="searchTerm" style="font-size: 10px;cursor: pointer; border-radius: 5px 0 0 5px;" type="text" class="form-control" placeholder="Search for Emp.Name or ID" aria-label="Search" aria-describedby="basic-addon1">
                                    <div class="input-group-append">
                                        <button wire:click="filter" style="border-radius: 0 5px 5px 0; background-color: #007BFF; color: #fff; border: none;" class="btn me-3" type="button">
                                            <i style="text-align: center;" class="fa fa-search"></i>
                                        </button>
                                        <button wire:click="closePeoples" type="button" style="margin-top: -7px;" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="color: white; font-size: 24px; font-weight: 300">x</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row" style="max-height: 250px; overflow-y: auto;">
                                    @if ($peopleData->isEmpty())
                                    <div class="container" style="text-align: center; color: white;font-size:12px;">
                                        No People Found
                                    </div>
                                    @else
                                    @foreach($peopleData as $people)
                                    <div wire:model="cc_to" wire:click="selectPerson('{{ $people->emp_id }}')" class="container" style="cursor: pointer; background-color: darkgrey; padding: 5px; margin-bottom: 8px; width: 300px; border-radius: 5px;">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <input type="checkbox" wire:model="selectedPeople" value="{{ $people->emp_id }}" wire:click="selectPerson({{ $people->emp_id }})">
                                            </div>
                                            <div class="col-auto p-0">
                                            <img style="border-radius: 50%; margin-left: 10px" height="50" width="50" src="{{ asset($people['image']) }}" >
                                        </div>
                                            <div class="col">
                                                <h6 class="username" style="font-size: 12px; color: white;">
                                                    {{ $people->first_name }}
                                                    {{ $people->last_name }}
                                                </h6>
                                                <p class="mb-0" style="font-size: 12px; color: white;">
                                                    (#{{ $people->emp_id }})
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
             
                <div class="col-md-4">
    <!-- Modified image container to have a fixed height -->
    <div class="image-container d-flex align-items-end" >
        <img src="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTrb080MeuXwgT6ZB-x7qWZ3i_xQks-9xsRz5F9wWIyKbEEbGzL" alt="Employee Image" style="height: 180px; width:300px;margin-left:250px">
    </div>
</div>

            </div>
        </div>
        
  

</div>

 
                
        </div>
    </div>
   
    @if(!empty($selectedPeopleNames))
    @foreach($selectedPeopleNames as $emp_id => $selectedPersonName)
        @if(isset($employeeDetails[$emp_id]))
            <div class="card mx-auto" style="margin-top: 20px; height:auto; width:80%;">
                <div class="card-header" style="font-size: 15px; background:white; width:100%; font-weight: 900; display:flex; justify-content:space-between;">
                    <p>Parent Details</p>  
                    <p style="text-align: end; font-size: 14px">
                        <i>
                            @if($editingParentProfile)
                                <i wire:click="cancelParentProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                                <i wire:click="saveParentProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                            @else
                                <i wire:click="editParentProfile('{{ $emp_id }}')" class="fas fa-edit" style="cursor: pointer;"></i>
                            @endif
                        </i>
                    </p> 
                </div>
                <div class="row m-0" style="color: #778899;margin-top: 5px;height:auto">
                    <div class="col-md-2" style="font-size: 12px;">Father First Name</div>
                    <div class="col-md-2" style="font-size: 12px;">Father Last Name</div>
                    <div class="col-md-2" style="font-size: 12px;">Date of Birth</div>
                    <div class="col-md-2" style="font-size: 12px;">Blood Group</div>
                    <div class="col-md-2" style="font-size: 12px;">Father Address</div>
                </div>
                @if($editingParentProfile)
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="FatherFirstName" placeholder="First Name">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="FatherLastName" placeholder="Last Name">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="date" class="form-control" wire:model="FatherDateOfBirth" placeholder="Father DOB">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="FatherBloodGroup" placeholder="Blood Group">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="FatherAddress" placeholder="Address">
                        </div>
                    </div>
                @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{ $employeeDetails[$emp_id]->parentDetails->father_first_name ?? '-' }}
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{$employeeDetails[$emp_id]->parentDetails->father_last_name ?? '-' }}
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
    {{ isset($employeeDetails[$emp_id]->parentDetails->father_dob) ? ($employeeDetails[$emp_id]->parentDetails->father_dob) : '-' }}
</div>

                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{ $employeeDetails[$emp_id]->parentDetails->father_blood_group ?? '-' }}
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{ $employeeDetails[$emp_id]->parentDetails->father_address ?? '-' }} 
                        </div>
                    </div>
                @endif
                <div class="row m-0" style="color: #778899;margin-top: 5px;height:auto">
                    <div class="col-md-2" style="font-size: 12px;">Mother First Name</div>
                    <div class="col-md-2" style="font-size: 12px;">Mother Last Name</div>
                    <div class="col-md-2" style="font-size: 12px;">Date of Birth</div>
                    <div class="col-md-2" style="font-size: 12px;">Blood Group</div>
                    <div class="col-md-2" style="font-size: 12px;">Mother Address</div>
                </div>
                @if($editingParentProfile)
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="MotherFirstName" placeholder="MotherFirstName">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="MotherLastName" placeholder="MotherLastName">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="date" class="form-control" wire:model="MotherDateOfBirth" placeholder="Mother DOB">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="MotherBloodGroup" placeholder="Mother Blood Group">
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            <input style="font-size:12px" type="text" class="form-control" wire:model="MotherAddress" placeholder="Mother Address">
                        </div>
                    </div>
                @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{ $employeeDetails[$emp_id]->parentDetails->mother_first_name ?? '-' }}
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{ $employeeDetails[$emp_id]->parentDetails->mother_last_name ?? '-' }}
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
    {{ isset($employeeDetails[$emp_id]->parentDetails->mother_dob) ?($employeeDetails[$emp_id]->parentDetails->mother_dob) : '-' }}
</div>

                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{ $employeeDetails[$emp_id]->parentDetails->mother_blood_group ?? '-' }}
                        </div>
                        <div class="col-md-2 mb-3" style="color: black; font-size: 12px;">
                            {{ $employeeDetails[$emp_id]->parentDetails->mother_address ?? '-' }} 
                        </div>
                    </div>
                @endif
            </div>

        @endif
    @endforeach

    </div>
    @endif

</div>


<div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                <div class="container mt-3">
                    <b>Activity Stream</b>
                </div>
              
        <div class="row mt-3 ml-3" style="font-size:12px">
      
@foreach($peoples->take(5) as $employee)
    <span style="font-weight:600">Added New Employee:  ({{ $employee->emp_id }}) {{ $employee->first_name }} {{ $employee->last_name }}</span>@if (!$loop->first)<br>@endif
    <p>Hire Date: ({{ $employee->hire_date->format('M d, Y')  }}) </p>@if (!$loop->first)<br>@endif
    @endforeach


        <br>
    


 
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $('#main-tab').tab('show');
            });
        </script>
</div>
