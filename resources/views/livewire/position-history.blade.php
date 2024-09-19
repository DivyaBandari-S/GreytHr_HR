<div style="color:#778899">
 <style>


    </style>


    <div class="tab-container">
        <!-- Tab buttons -->
        <div class="tab-buttons">
            <button class="tab-button active" onclick="showTab(0)">Main</button>
            <button class="tab-button" onclick="showTab(1)">Activity</button>
          
        </div>

        <!-- Tab contents -->
        <div class="tab-content active">
        <div class="row justify-content-center"  >
                        <div class="col-md-10 custom-container d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <p class="main-text mb-0">This page allows you to add/edit the profile details of an employee. The page helps you to keep the employee information up to date.</p>
                                <p style="font-size: 12px; cursor: pointer;color:blue;font-weight:500;" wire:click="toggleDetails">
        {{ $showDetails ? 'Hide Details' : 'Info' }}
    </p>
                            </div>
                            @if ($showDetails)
                                
                           
                            <div class="secondary-text">
    Explore greytHR by 
    <span style="color:blue">Help-Doc</span>, watching How-to 
    <span style="color:blue">Videos</span> and 
    <span style="color:blue">FAQ</span>
</div>
@endif

                        </div>
                    </div>
                 

                <div class="row justify-content-center mt-2 "  >
                <div class="col-md-10 custom-container d-flex flex-column bg-white">
    <div class="row justify-content-center mt-3 flex-column m-0" style="border-radius: 5px; font-size:12px; width:88%;">
        <div class="col-md-9">
            <div class="row " style="display:flex;">
                <div class="col-md-11 m-0">
                    <b>Start searching to see specific employee details here</b>
                    <div class="col mt-3" style="display: flex;">
             
                        <p style="font-size: 12px; font-weight:260">Employee Type:</p>
                        <p>Current Employees</p>
                    </div>
                 
                    <div class="profile" style="margin-top: 10px;">
    <div class="col m-0">
     
            <div class="row  ">
            <div>
    <p style="cursor: pointer;" wire:click="NamesSearch">
        Search Employee:
      
            @foreach($selectedPeopleData as $personData)
                <div class=" align-items-center" style="margin-right: 15px;display:flex">
                    <img class="profile-image" src="{{ $personData['image'] }}" style="border-radius:50%; height:30px; width:30px;" alt="Employee Image">
                    <p style="margin-left: 10px; line-height: 30px; font-size:10px;">{{ $personData['name'] }}</p>
                </div>
            @endforeach
      
    </p>
</div>




               
       
        </div>
       <div class="col-md-6 col-12"> 

        @if($isNames)
        <div class="col-md-6" style="border-radius: 5px; background-color: grey; padding: 8px; width: 330px; margin-top: 10px; height: 250px; overflow-y: auto;">
        <div class="input-group4" style="display: flex; align-items: center; width: 100%;">
    <input 
        wire:model="searchTerm" 
        style="font-size: 10px; cursor: pointer; border-radius: 5px 0 0 5px; width: 250px; height: 30px; padding: 5px;" 
        type="text" 
        class="form-control" 
        placeholder="Search for Emp.Name or ID" 
        aria-label="Search" 
        aria-describedby="basic-addon1"
    >
    <div class="input-group-append" style="display: flex; align-items: center;">
        <button 
            wire:click="filter" 
            style="height: 30px; border-radius: 0 5px 5px 0; background-color: rgb(2, 17, 79); color: #fff; border: none; padding: 0 10px;" 
            class="btn" 
            type="button"
        >
            <i style="text-align: center;" class="fa fa-search"></i>
        </button>

        <button 
            wire:click="closePeoples"  
            type="button" 
            class="close rounded px-1 py-0" 
            aria-label="Close" 
            style="background-color: rgb(2,17,79); height: 30px; width: 30px; margin-left: 5px; display: flex; align-items: center; justify-content: center;"
        >
            <span aria-hidden="true" style="color: white; font-size: 24px; line-height: 0;">×</span>
        </button>
    </div>
</div>

        
        
 

@foreach($employeeIds as $emp_id)
                            @php
                                $employee = $employees->firstWhere('emp_id', $emp_id);
                            @endphp
                           
                            <label wire:click="selectPerson('{{ $emp_id }}')"
 class="container" style="cursor: pointer; background-color: darkgrey; padding: 5px; margin-bottom: 8px; width: 300px; border-radius: 5px; margin-top:5px">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <input type="checkbox" wire:model="selectedPeople" value="{{ $emp_id }}" {{ in_array($emp_id, $selectedPeople) ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-auto">
                                        @if($employee && $employee->image && $employee->image !== 'null')
                                        <img class="profile-image" src="{{ 'data:image/jpeg;base64,' . base64_encode($employee->image) }}" style="border-radius:50%">
                                        @else
                                            @if($employee && $employee->gender == "Male")
                                                <img style="border-radius: 50%;" height="50" width="50" src="{{ asset('images/male-default.png') }}" alt="Default Male Image">
                                            @elseif($employee && $employee->gender == "Female")
                                                <img style="border-radius: 50%;" height="50" width="50" src="{{ asset('images/female-default.jpg') }}" alt="Default Female Image">
                                            @else
                                                <img style="border-radius: 50%;" height="50" width="50" src="{{ asset('images/user.jpg') }}" alt="Default Image">
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col">
                                        <h6 class="username" style="font-size: 12px; color: white;">
                                            
                                            {{ ucwords(strtolower($employee->first_name)) }} {{ ucwords(strtolower($employee->last_name)) }}
                                        </h6>
                                        <p class="mb-0" style="font-size: 12px; color: white;">(#{{ $emp_id }})</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach

      
        </div>
        @endif
        </div>
 
    </div>
</div>

                    </div>
             
                <div class="col-md-1">
    <!-- Modified image container to have a fixed height -->
    <div class="image-container d-flex align-items-end" >
        <img src="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcTrb080MeuXwgT6ZB-x7qWZ3i_xQks-9xsRz5F9wWIyKbEEbGzL" alt="Employee Image" style="height: 180px; width:300px;align-items:end">
    </div>
</div>

            </div>
        </div>
        
  

</div>

 
                
        </div>
    </div>
 
    @if(!empty($selectedPeople))
   

    @foreach($selectedPeople as $emp_id)
                @php
                    $employee = $employees->firstWhere('emp_id', $emp_id);
                @endphp
            



@if($employee)
<div class="container-fluid">
    <div class="row justify-content-center" style="margin-top:10px">
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Division</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-top:-5px;margin-left:10px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="division" placeholder="Division">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->department??'-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                @if($editingProfile )
                    <i wire:click="cancelProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">CostCenter</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -5px;margin-left:10px">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; color: darkcyan; margin-bottom: 0;">Social App</p>
    </div>
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
</div>

             
         
             <i  class="fas fa-edit ml-auto" style="cursor: pointer;font-size:12px"></i>


             </div>
             
            </div>
          
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Grade</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -5px;margin-left:10px;">
        <p class="m-0" style="color: blue;">add</p>
    </button>






             </div>
        </div>
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Designation</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;;margin-top:-5px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
    @if($currentEditingdesignationProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="designation" placeholder="designation">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-10 mb-3" style="color: black; font-size: 12px;">{{$employee->empPersonalInfo->designation ??'-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>              @if($currentEditingdesignationProfileId == $employee->emp_id)
                    <i wire:click="canceldesignationProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="savedesignationProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editdesignationProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
            </div>
          
        </div>
    </div>
    <div class="row justify-content-center" style="margin-top:10px">
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Location</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;;margin-top:-5px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
    @if($currentEditinglocationProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Location" placeholder="Location">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->job_location??'-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>          @if($currentEditinglocationProfileId == $employee->emp_id)
                    <i wire:click="cancellocationProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="savelocationProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editlocationProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-lg-3">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Department</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left:10px;;margin-top:-5px">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
    @if($currentEditingProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="department" placeholder="Department">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->department ??'-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                       @if($currentEditingProfileId == $employee->emp_id)
                    <i wire:click="cancelProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
            </div>
          
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Job Mode</p>
        
    <button type="button" class="btn btn-sm ml-auto align-items-end mb-3" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left: 10px;;margin-top:-5px">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
    @if($currentEditingJobProfileId == $employee->emp_id)
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Jobmode" placeholder="Jobmode">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employee->job_mode ?? '-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                  @if($currentEditingJobProfileId == $employee->emp_id)
                    <i wire:click="cancelProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-lg-3">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Resident</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-left:10px;margin-top:-5px"><p style="color:blue">add</p></button>


             </div>
          
             
            </div>
          
        </div>
    </div>
    <div class="row justify-content-center " style="margin-top:10px">
    <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Sub Department</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left:10px;;margin-top:-5px"">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
    @if($currentEditingPersonalSubProfileId == $employee->emp_id)
    <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
        <input style="font-size: 12px" type="text" class="form-control" wire:model="subDepartment" placeholder="Sub Department">
    </div>
@else
    <div class="row m-0" style="margin-top: 10px;">
      

            <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">
            {{$employee->empSubDepartment->sub_department ?? '-'}}
            </div>
       

        
    </div>
@endif

                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                @if($currentEditingPersonalSubProfileId == $employee->emp_id)
                    <i wire:click="cancelSubDepartmentProfile('{{ $employee->emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveSubDepartmentProfile('{{ $employee->emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editSubDepartmentProfile('{{ $employee->emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
             
            </div>
          
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Company Name</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-left:10px;;margin-top:-5px"">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
    @if($currentEditingPersonalCompanyProfileId == $employee->emp_id)
    <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
        <input style="font-size: 12px" type="text" class="form-control" wire:model="companyname" placeholder="Company Name">
    </div>
@else
    <div class="row m-0" style="margin-top: 10px;">
      

            <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">
            {{$employee->empPersonalInfo->company_name ?? '-'}}
            </div>
       

        
    </div>
@endif

                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                @if($currentEditingPersonalCompanyProfileId == $employee->emp_id)
                    <i wire:click="cancelCompanyProfile('{{ $employee->emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveCompanyProfile('{{ $employee->emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editCompanyProfile('{{ $employee->emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif
            </i> 

             
           

         


             </div>
             
             
            </div>
          
        </div>
        <div class="col-lg-3  ">
            </div>
            <div class="col-lg-3 ">
            </div>
    </div>
</div>


            </div>
            <div class="col-md-2"></div>
        </div>

       
    
            </div>
            <div class="col-md-2"></div>
        </div>


@endif

    @endforeach
@endif





                    </div>
              
           

          

        <div class="tab-content">
        <div class="row mt-3 ml-3" style="font-size:12px">
      
        <div id="employee-container">
        @foreach($employeess as $employee)
    <span style="font-weight:600">
        Added New Employee: ({{ $employee->emp_id }}) {{ $employee->first_name }} {{ $employee->last_name }}
    </span>
    @if (!$loop->first)<br>@endif
    <p>
        Hire Date: 
        @if($employee->hire_date)
            @php
                $hireDate = \Carbon\Carbon::parse($employee->hire_date);
            @endphp
            ({{ $hireDate->format('M d, Y') }})
        @else
            N/A
        @endif
    </p>
    @if (!$loop->first)<br>@endif
@endforeach

</div>





      
      
      
              <br>
          
      
      
       
                  </div>
      
        </div>

    </div>

    <script>
        // JavaScript to handle tab switching
        function showTab(index) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(button => button.classList.remove('active'));

            // Show the selected tab content and mark the button as active
            contents[index].classList.add('active');
            buttons[index].classList.add('active');
        }
    </script>


    </div>