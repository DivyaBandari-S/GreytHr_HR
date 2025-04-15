<div class="container mt-3">
    <div class="d-flex justify-content-end">

        <input type="text" class="search-input" wire:model="search" wire:input="searchProjects" placeholder="Search...">

    </div>

    <style>
        .container {
            max-width: 1200px;
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

       

        .form-control {
            border-radius: 8px;
        }

        .form-group label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .table {
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            /* White background for the table */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Shadow effect for the table */
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
            font-weight: 600;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        /* Adding a container with a background and padding to form and table */
        .form-container,
        .table-container1 {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            height: 400px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            margin-bottom: 30px;
        }


        .search-input {
            padding: 5px 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 180px;
            font-size: 16px;
            margin-right: 55px;
            margin-bottom: 10px;
        }
    </style>
    <div class="row">
        <!-- Left side: Heading and Form -->
        <div class="col-md-4 form-container mx-3">
            <h2 class="mb-4">{{ $selectedProjectId ? 'Edit Project for Client: ' . $client_id : 'Add New Project for Client: ' . $client_id }}
            </h2>

            <!-- Success Message -->
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="{{ $selectedProjectId ? 'updateProject' : 'submitForm' }}">
                <!-- Client Dropdown (this should be hidden or prefilled with client_id) -->
                <input type="hidden" wire:model="client_id">

                <!-- Project Name -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Project Name</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" wire:model="project_name"
                                wire:keyup="validateInputChange('project_name')">
                            @error('project_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Project Description -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Project Description</label>
                        </div>
                        <div class="col-md-7">
                            <textarea class="form-control" wire:model="project_description"></textarea>
                            @error('project_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Start Date -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Start Date</label>
                        </div>
                        <div class="col-md-7">
                            <input type="date" class="form-control" wire:model="project_start_date">
                            @error('project_start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- End Date -->
                <div class="form-group mt-3 mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label>End Date</label>
                        </div>
                        <div class="col-md-7">
                            <input type="date" class="form-control" wire:model="project_end_date">
                            @error('project_end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group text-center">
                    <button type="submit"
                        class="submit-btn">{{ $selectedProjectId ? 'Update Project' : 'Save Project' }}</button>
                </div>
            </form>
        </div>

        <!-- Right side: Project List -->
        <div class="col-md-7 table-container1">
            <h2>Projects List</h2>
            <!-- Project Table -->
            <table class="table table-striped mt-3" style="max-height: 300px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width: 20%;">Project Name</th>
                        <th style="width: 14%">Description</th>
                        <th style="width: 19%;">Start Date</th>
                        <th style="width: 19%;">End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($projects->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">
                                <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}" alt="No items found">
                                <p>No Data Found</p>
                            </td>
                        </tr>
                    @else
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucwords($project->project_name) }}</td>
                                <td style="text-align: center">{{ $project->project_description ?: '-' }}</td>
                                <td style="text-align: center">{{ $project->project_start_date ?: '-' }}</td>
                                <td style="text-align: center">{{ $project->project_end_date ?: '-' }}</td>
                
                                <td>
                                    <!-- Action Icons: Edit, View, Delete -->
                                    <a href="#" wire:click="viewProject({{ $project->id }})" title="View">
                                        <i class="fas fa-eye text-secondary"></i>
                                    </a>
                                    <a href="#" wire:click="editProject({{ $project->id }})" title="Edit"
                                        class="mx-2">
                                        <i class="fas fa-edit text-info"></i>
                                    </a>
                                    <a href="#" wire:click="deleteProject({{ $project->id }})" title="Delete">
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
    <!-- Edit Project Modal -->


    <!-- View Project Modal -->
    @if ($showViewModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header alert alert-success m-0">
                        <!-- <h5 class="modal-title ">Success</h5> -->
                        <h5 class="modal-title ">View Project</h5>
                        <a style="margin-left: auto;"><button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close" wire:click="$set('showViewModal', false)"></button></a>
                    </div>
                    <div class="modal-body">
                        <p><strong style="margin-right: 20px;">Project Name:</strong> {{ ucwords($project_name) }}</p>
                        <p><strong>Description:</strong> {{ $project_description }}</p>
                        <p><strong>Start Date:</strong> {{ $project_start_date }}</p>
                        <p><strong>End Date:</strong> {{ $project_end_date }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

</div>
