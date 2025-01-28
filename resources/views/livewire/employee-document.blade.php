<div>

    <div class="container mt-3">

        <div class="tab-content">
            <div class="tab-pane fade show active" style="margin-top:10px;">
                <di class="container mt-3">
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
        <div class="container mt-3" style="margin-left:20px">
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab" aria-controls="main" aria-selected="true">Main</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="activity-tab" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">Activity</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">Documents</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="letters-tab" data-toggle="tab" href="#letters" role="tab" aria-controls="letters" aria-selected="false">Letters</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="payslip-tab" data-toggle="tab" href="#payslip" role="tab" aria-controls="payslip" aria-selected="false">Payslip</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="main-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <div class="row justify-content-center">
                <div class="row mt-3">
    <!-- First Dropdown -->
    <div class="col-md-2">
        <div class="dropdown">
            <button class="btn btn dropdown-toggle" type="button" id="category1Dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius:5px;border:1px solid black;font-size:12px">
                Category: All
            </button>
            <div class="dropdown-menu" aria-labelledby="category1Dropdown">
                <a class="dropdown-item" href="#">Option 1</a>
                <a class="dropdown-item" href="#">Option 2</a>
                <a class="dropdown-item" href="#">Option 3</a>
            </div>
        </div>
    </div>

    <!-- Second Dropdown -->
    <div class="col-md-2">
        <div class="dropdown">
            <button class="btn btn dropdown-toggle" type="button" id="category2Dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius:5px;border:1px solid black;font-size:12px;margin-left:-15px">
               Filters : All
            </button>
            <div class="dropdown-menu" aria-labelledby="category2Dropdown">
                <a class="dropdown-item" href="#">Option 1</a>
                <a class="dropdown-item" href="#">Option 2</a>
                <a class="dropdown-item" href="#">Option 3</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary" style="font-size:12px">Add Documents</button>
    </div>
</div>
</div>

                <div class="col-md-10 custom-container d-flex flex-column mt-5">
                      
                    <div class="d-flex align-items-center mb-2">
                            <p class="main-text mb-0">There are no documents available!</p>
               
                        </div>
                        <div id="details" style="display:none;">
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Activity.</p>
            </div>
        </div>
        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Documents.</p>
            </div>
        </div>
        <div class="tab-pane fade" id="letters" role="tabpanel" aria-labelledby="letters-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Letters.</p>
            </div>
        </div>
        <div class="tab-pane fade" id="payslip" role="tabpanel" aria-labelledby="payslip-tab" style="margin-top:10px;">
            <div class="container mt-3">
                <p>Content for Payslip.</p>
            </div>
        </div>
    </div>
</div>

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
        <script>
    $(document).ready(function () {
        $('#main-tab').tab('show');
    });

    function toggleDetails() {
        var details = document.getElementById("details");
        if (details.style.display === "none") {
            details.style.display = "block";
        } else {
            details.style.display = "none";
        }
    }
    
</script>

</div>
