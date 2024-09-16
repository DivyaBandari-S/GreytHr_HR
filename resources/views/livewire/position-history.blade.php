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

        <div class="tab-content">
            <div class="tab-pane fade show active" style="margin-top:10px;">
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
        <div class="container-fluid">
    <div class="row justify-content-center" style="margin-top:10px">
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Division</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-top:-10px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="department" placeholder="Department">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->department}}</div>
                       
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
                @endif</i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">CostCenter</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -10px;">
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
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -10px;">
        <p class="m-0" style="color: blue;">add</p>
    </button>






             </div>
        </div>
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Designation</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-top:-10px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingPersonalProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Designation" placeholder="Designation">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-10 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->job_title}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                @if($editingPersonalProfile )
                    <i wire:click="cancelpersonalProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="savepersonalProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editpersonalProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif</i> 

             
           

         


             </div>
            </div>
          
        </div>
    </div>
    <div class="row justify-content-center" style="margin-top:10px">
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Location</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-top:-10px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingLocationProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Location" placeholder="Location">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->job_location}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                @if($editingLocationProfile )
                    <i wire:click="cancelLocationProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveLocationProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editLocationProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif</i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-lg-3">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Department</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -10px;">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="department" placeholder="Department">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->department}}</div>
                       
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
                @endif</i> 

             
           

         


             </div>
            </div>
          
        </div>
        <div class="col-lg-3 ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Job Mode</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -10px;">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingJobProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="Jobmode" placeholder="Jobmode">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->job_mode ?? '-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                @if($editingJobProfile )
                    <i wire:click="cancelJobProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveJobProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editJobProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif</i> 

             
           

         


             </div>
             
            </div>
          
        </div>
        <div class="col-lg-3">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Resident</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-top:-10px"><p style="color:blue">add</p></button>


             </div>
          
             
            </div>
          
        </div>
    </div>
    <div class="row justify-content-center " style="margin-top:10px">
        <div class="col-lg-3  ">
            <div class="bg-white p-3 mb-3" style="border-radius: 5px; border: 1px solid silver;height:auto">
             <div class="column" style="display:flex">
             <p style="font-size:12px;font-weight:600">Sub Department</p>
          
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue;height:28px;margin-top:-10px"><p style="color:blue">add</p></button>


             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; color: darkcyan; margin-bottom: 0;">{{$employeeDetails[$emp_id]->Department}}</p>
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
             <p style="font-size:12px;font-weight:600">Company Name</p>
        
    <button type="button" class="btn btn-sm ml-auto" style="border-radius: 5px; border: 1px solid blue; height: 28px; margin-top: -10px;">
        <p class="m-0" style="color: blue;">add</p>
    </button>




             </div>
             <div class="column" style="display: flex;">
             <div class="row" style="margin-right: -5px; margin-left: -5px;">
    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
 
          @if ($editingCompanyProfile )
          <div class="col-md-12 mb-3" style="color: black; font-size: 12px;"> 
          <input style="font-size:12px" type="text" class="form-control" wire:model="companyname" placeholder="Company Name">
                        </div>
                    
                    @else
                    <div class="row m-0" style="margin-top: 10px;">
                        <div class="col-md-3 mb-3" style="color: black; font-size: 12px;">{{$employeeDetails[$emp_id]->company_name ?? '-'}}</div>
                       
                    </div>
                    @endif
                    <div class="col-auto" style="padding-right: 5px; padding-left: 5px;">
        <p style="font-size: 12px; margin-bottom: 0;">14 Apr 2023</p>
    </div>
    </div>


</div>
<i>                @if($editingCompanyProfile )
                    <i wire:click="cancelCompanyProfile('{{ $emp_id }}')" class="fas fa-times me-3" style="cursor: pointer;"></i>
                    <i wire:click="saveCompanyProfile('{{ $emp_id }}')" class="fa fa-save" style="cursor: pointer;"></i>
                @else
                    <i wire:click="editCompanyProfile('{{ $emp_id }}')" class="fas fa-edit ml-auto" style="cursor: pointer;"></i>
                @endif</i> 

             
           

         


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
    </div>
    @endif

</div>


        </div>

</div>
