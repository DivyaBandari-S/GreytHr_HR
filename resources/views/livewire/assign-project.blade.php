<div class="container mt-5">
    <style>
        .profile-image {
            height: 32px;

            width: 32px;

            background-color: lightgray;

            border-radius: 50%;
        }
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            color: #778899;
            font-size: 12px;
            font-weight: 500;
        }
        .table th {
            /* background-color: #f8f9fa; */
            background-color: var(--main-table-heading-bg-color);
            font-weight: 500;
            color: #778899;
            font-size: 12px;
        }
         /* Adding a container with a background and padding to form and table */
         .form-container, .table-container1 {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-height: 400px;
            height: 500px;
            overflow-y: scroll;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            margin-bottom: 30px;
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
        h2 {
            font-size: var(--main-headings-font-size);
            /* Decreased the font size */
            font-weight: 500;
            color: var(--main-heading-color);
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
    <div class="row">
        <!-- Left side: Heading and Form -->
        <div class="col-md-4 form-container mx-3">
            <h2>Assign Project to Employee</h2>

            <!-- Display success message if any -->
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Form for assigning project -->
            <form wire:submit.prevent="submit">
                
                   
                        <!-- Client Selection Dropdown -->
                        <div class="form-group mt-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Select Client</label>
                                </div>
                                <div class="col-md-6">
                                    <select wire:model="selectedClient" class="form-control"
                                        wire:change="handleClientSelection('selectedClient')">
                                        <option value="">-- Select Client --</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->client_id }}">{{ $client->client_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedClient')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <!-- Project Name Dropdown -->
                        <div class="form-group mt-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Select Project</label>
                                </div>
                                <div class="col-md-6">
                                    <select wire:model="selectedProject" class="form-control"
                                        wire:change="clearValidationMessages('selectedProject')">
                                        <option value="">-- Select Project --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->project_name }}">{{ $project->project_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedProject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Employee Search -->
                        <div class="form-group mt-3 mb-3">
                            <div class="d-flex">


                                <span class="normalTextValue">
                                    Select Employee
                                </span>
                                <div class="control-wrapper d-flex align-items-center"
                                    style="margin-bottom: 8px; margin-left: 42px;">
                                    <a class="text-3 text-secondary no-underline control" aria-haspopup="true"
                                        wire:click="openCcRecipientsContainer">
                                        <div class="icon-container">
                                            <i class="fa fa-plus"></i>
                                        </div>
                                    </a>
                                    <!-- Blade Template: your-component.blade.php -->
                                    <span class="addText" wire:click="openCcRecipientsContainer">Add</span>

                                    @if (count($selectedCCEmployees) > 0)
                                        @php
                                            $employeesCollection = collect($selectedCCEmployees);
                                            $visibleEmployees = $employeesCollection->take(3);
                                            $hiddenEmployees = $employeesCollection->slice(3);
                                        @endphp

                                        <ul class="d-flex align-items-center list-unstyled mb-0 gap-3 employee-list">
                                            @foreach ($visibleEmployees as $recipient)
                                                <li class="employee-item">
                                                    <div class="px-2 py-1 d-flex justify-content-between align-items-center rounded-pill gap-3"
                                                        style=" border: 2px solid #adb7c1;"
                                                        title="{{ ucwords(strtolower($recipient['first_name'])) }} {{ ucwords(strtolower($recipient['last_name'])) }}">
                                                        <span class="text-container selecetdCcName font-weight-normal">
                                                            {{ ucwords(strtolower($recipient['first_name'])) }}
                                                            {{ ucwords(strtolower($recipient['last_name'])) }}
                                                        </span>
                                                        <i class="fas fa-times-circle cancel-icon d-flex align-items-center justify-content-end"
                                                            style="cursor: pointer; color:#adb7c1;"
                                                            wire:click="removeFromCcTo('{{ $recipient['emp_id'] }}')"></i>
                                                    </div>
                                                </li>
                                            @endforeach

                                            @if (count($selectedCCEmployees) > 3)
                                                <li>
                                                    <span type="button" wire:click="openModal"
                                                        class="anchorTagDetails">View
                                                        More</span>
                                                </li>
                                            @endif
                                        </ul>

                                        <!-- Popup Modal -->
                                        @if ($showCCEmployees)
                                            <div class="modal d-block" tabindex="-1" role="dialog">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">CC To</h5>
                                                            <button type="button" class="btn-close btn-primary"
                                                                data-dismiss="modal" aria-label="Close"
                                                                wire:click="openModal">
                                                            </button>
                                                        </div>
                                                        <div class="modal-body d-flex align-items-center"
                                                            style="max-width:100%;overflow-x:auto;">
                                                            <ul
                                                                class="d-flex align-items-center mb-0 list-unstyled gap-3">
                                                                @foreach ($hiddenEmployees as $recipient)
                                                                    <li class="employee-item">
                                                                        <div class="px-2 py-1 d-flex justify-content-between align-items-center rounded-pill gap-3"
                                                                            style=" border: 2px solid #adb7c1; "
                                                                            title="{{ ucwords(strtolower($recipient['first_name'])) }} {{ ucwords(strtolower($recipient['last_name'])) }}">
                                                                            <span
                                                                                class="text-container selecetdCcName font-weight-normal">
                                                                                {{ ucwords(strtolower($recipient['first_name'])) }}
                                                                                {{ ucwords(strtolower($recipient['last_name'])) }}
                                                                            </span>
                                                                            <i class="fas fa-times-circle cancel-icon d-flex align-items-center justify-content-end"
                                                                                style="cursor: pointer; color:#adb7c1;"
                                                                                wire:click="removeFromCcTo('{{ $recipient['emp_id'] }}')"></i>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-backdrop fade show"></div>
                                        @endif
                                    @endif
                                </div>


                            </div>
                            @if ($showCcRecipents)
                                <div class="ccContainer" x-data="{ open: @entangle('showCcRecipents') }" x-cloak @click.away="open = false">
                                    <div class="m-0 p-0 d-flex align-items-center justify-content-between">
                                        <div class="cctosearch m-0 p-0">
                                            <div class="input-group">
                                                <input wire:model.debounce.500ms="searchTerm" id="searchInput"
                                                    type="text" class="form-control placeholder-small"
                                                    placeholder="Search..." aria-label="Search"
                                                    aria-describedby="basic-addon1"
                                                    wire:keydown.enter.prevent="handleEnterKey">
                                                <div class="input-group-append searchBtnBg d-flex align-items-center">
                                                    <button type="button" wire:click="searchCCRecipients"
                                                        class="search-btn-leave">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cctobtn ms-2 m-0 p-0 d-flex justify-content-end">
                                            <button wire:click="closeCcRecipientsContainer" type="button"
                                                class="close rounded px-1 py-0" aria-label="Close">
                                                <span aria-hidden="true" class="closeIcon"><i
                                                        class="fas fa-times "></i></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="scrollApplyingTO mb-2 mt-2 ">
                                        @if ($ccRecipients && count($ccRecipients) > 0)
                                            @foreach ($ccRecipients as $employee)
                                                <div class="borderContainer px-2 mb-2 rounded"
                                                    wire:click="toggleSelection('{{ $employee->emp_id }}')">
                                                    <div class="downArrow d-flex align-items-center text-capitalize"
                                                        wire:click.prevent>
                                                        <label class="custom-checkbox">
                                                            <input type="checkbox"
                                                                wire:model="selectedPeople.{{ $employee->emp_id }}" />
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div>
                                                                @if (!empty($employee->image) && $employee->image !== 'null')
                                                                    <div class="employee-profile-image-container">
                                                                        <img class="navProfileImg rounded-circle"
                                                                            src="data:image/jpeg;base64,{{ $employee->image }}">
                                                                    </div>
                                                                @else
                                                                    <div class="employee-profile-image-container">
                                                                        <img src="{{ $employee->gender === 'MALE' ? asset('images/male-default.png') : ($employee->gender === 'FEMALE' ? asset('images/female-default.jpg') : asset('images/user.jpg')) }}"
                                                                            class="employee-profile-image-placeholder rounded-circle"
                                                                            height="33" width="33">
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="mb-2 mt-2">
                                                                <p class="mb-0 empCcName">
                                                                    {{ ucwords(strtolower($employee->full_name)) }}</p>
                                                                <p class="mb-0 ">#{{ $employee->emp_id }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mb-0 normalTextValue">
                                                No data found
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endif
                            @if (count($selectedCCEmployees) === 0)
                                @error('selectedPeople')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif

                            {{-- @error('selectedPeople') <span class="text-danger">{{ $message }}</span> @enderror --}}
                        </div>


                        <!-- Start Date -->
                        <div class="form-group mt-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Start Date</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" wire:model="startDate" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="form-group mt-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>End Date</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" wire:model="endDate" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Submit and Cancel Buttons -->
                        <div style="margin-left: 60px; margin-top: 25px; margin-bottom: 20px;">
                            <button type="submit" class="submit-btn">Assign Project</button>
                            <button type="button" class="cancel-btn"
                                onclick="window.location='{{ route('assign.project') }}'">
                                Cancel
                            </button>
                        </div>
              
            </form>
        </div>
        <div class="col-md-7">
            <div class="table-container1">
                <h2>Assigned Projects</h2>
                <table class="table table-striped mt-3" style="max-height: 300px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client Name</th>
                            <th style="width: 10%">Project Name</th>
                            <th>Employees</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th style="width: 18%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($assignedProjects->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">
                                    <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}" alt="No items found">
                                    <p>No Data Found</p>
                                </td>
                            </tr>
                        @else
                            @foreach($assignedProjects as $project)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    
                                    <!-- Display Client Name -->
                                    <td>{{ ucwords($project->client->client_name) ?? "-" }}</td>
                    
                                    <!-- Display Project Name -->
                                    <td style="width: 10%">{{ ucwords($project->project_name) }}</td>
                    
                                    <!-- Display Employees -->
                                    <td>
                                        @if($project->emp_id)
                                            @php
                                                $employees = json_decode($project->emp_id);  // Decoding the JSON string
                                            @endphp
                                            @foreach($employees as $employee)
                                                <div>{{ $employee->full_name }}</div>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                    
                                    <!-- Display Start Date -->
                                    <td>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '-' }}</td>
                    
                                    <!-- Display End Date -->
                                    <td>{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '-' }}</td>
                    
                                    <!-- Actions -->
                                    <td>
                                        <!-- Action Icons: Edit, View, Delete -->
                                        <a href="#" wire:click="viewProject({{ $project->client->client_id }})" title="View">
                                            <i class="fas fa-eye text-secondary"></i>
                                        </a>
                                        <a href="#" title="Edit" class="mx-2">
                                            <i class="fas fa-edit text-info"></i>
                                        </a>
                                        <a href="#" title="Delete">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    
                </table>
            </div>
        </div>
        
    </div>
