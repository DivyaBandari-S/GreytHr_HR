<div>
    <div class="container-fluid px-1  rounded">
        <ul class="nav leave-grant-nav-tabs d-flex gap-3 py-1" id="myTab" role="tablist">

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-tab-pane" type="button" role="tab" aria-controls="summary-tab-pane" aria-selected="true">Main</button>

            </li>

            <li class="leave-grant-nav-item" role="presentation">

                <button class="leave-grant-nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="false">Activity</button>

            </li>

        </ul>
        <div class="tab-content " id="myTabContent" style="background:#fcfcfc;">
            <div class="tab-pane show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">
                @if($showHistory)
                <div class="row m-0 px-4 ">
                    <div class="main-overview-help d-flex px-3">
                        <div class="col-md-11 col-10 d-flex flex-column  ">
                            <p class="main-overview-text mb-1">greytHR is equipped to upload photos in bulk from the Bulk Photo Upload page. This saves time in the manual upload of each employee's photos and the errors that may creep in due to the repetitive nature of the task.
                            </p>
                        </div>
                        <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                            <span wire:click="hideHelp">Hide Help</span>
                        </div>
                    </div>
                </div>
                <div class="row m-0 pt-3 px-4">
                    <div class="d-flex justify-content-end mb-3 p-0">
                        <button type="button" class="submit-btn" wire:click="toggleUploadBtn">Upload Zip files</button>
                    </div>
                    <div class="table-responsive p-0">
                        <table id="typeReviewer">
                            <thead class="bg-white">
                                <tr colspan="4">
                                    <th colspan="4 bg-white">Uploaded files</th>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Logs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($uploadedHistory)
                                @foreach ($uploadedHistory as $history )
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($history->created_at)->format('d M, Y') }}</td>
                                    <td  wire:click="downloadZipFile('{{$history->id}}')"> <span class="anchorLink">{{ $history->file_name }}</span> </td>
                                    <td style="color: {{$history->status === 'Cancelled'? 'red' : ($history->status == 'Completed' ? 'green' : 'black')}}">{{ (strtoupper($history->status ))}}</td>
                                    <td> {{ $history->log }} </td>
                                </tr>
                                @endforeach
                                @else
                                <tr colspan="4">No data found</tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @if($showUploadContent)
                <div class="photoUpload">
                    <div class="main-overview-help w-100 m-auto mb-2 p-2">
                        <p class="mb-0">
                            Upload the zip file of employee photos here. Read the below instructions before you upload a set of photos.
                            <br>
                            Learn more about the process by watching the video here.
                        </p>
                    </div>
                    <div class="progress-container">
                        <div class="progress-line"></div>

                        <!-- Step 1 -->
                        <div class="progress-step">
                            <div class="circle {{ $currentStep >= 1 ? ($currentStep == 1 ? 'active' : 'completed') : 'pending' }}"></div>
                            <div class="label">Upload</div>
                        </div>

                        <!-- Step 2 -->
                        <div class="progress-step">
                            <div class="circle {{ $currentStep >= 2 ? ($currentStep == 2 ? 'active' : 'completed') : 'pending' }}"></div>
                            <div class="label">Associate</div>
                        </div>
                    </div>
                    <div>
                        @if($currentStep == 1)

                        <div class="mb-2">
                            <strong class="mb-3"> Step 1: Upload</strong>
                            <p class="textDesc mt-2">
                                You can upload photos of multiple employees in one shot by uploading a zip file containing all photos.
                            </p>
                            <p class="textDesc mb-0">
                                If photos are named according to employee numbers (for example, 01290.jpg), the assignment is automatically done. If not, you can
                                easily associate an employee with the photos in the next step.
                            </p>
                        </div>
                        <div class="bg-grey py-4 mb-2 ">
                            <div class="form-group px-3 py-4 rounded d-flex gap-2 align-items-center" style="background:#d8d8d8;border:1px solid gray;">
                                <input type="file" wire:model="zip_file" class="form-control w-25" accept=".zip">
                                <span class="diffColor">Only ZIP file containing JPG images is allowed. (Max size of .zip file that can be uploaded is 100 MB)</span>
                            </div>
                            @error('zip_file')
                            <span class="mt-1 text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="buttons-container d-flex py-1 gap-3">
                            <button class="cancel-btn" wire:click="toggleUploadBtn">Cancel</button>
                            <button class="submit-btn px-3" wire:click="UploadBulkZipFile">Next</button>
                        </div>
                        @elseif($currentStep == 2)
                        <div>
                            <h3>Assign profile photos to employees</h3>
                            @if($imagePaths && count($imagePaths) > 0)
                            <div class="image-gallery">
                                @foreach($imagePaths as $index => $path)
                                @php
                                // Extract the folder ID (33) from the path
                                $folderId = basename(dirname($path)); // Extracts the folder ID '33'

                                // Extract the filename from the path
                                $filename = basename($path);
                                @endphp
                                <div class="image-item rounded">
                                    <img src="{{ asset($path) }}" alt="Extracted Image" style="max-width: 100px; max-height: 100px;">
                                    <div class="d-flex flex-column">
                                        <div>
                                            <p class="mb-0 normalTextFile"> File name: {{ $filename }}</p>
                                        </div>
                                        <div class="form-group d-flex align-items-start mb-2 position-relative">
                                            <div class="input-group">
                                                <!-- Input field bound to the selected employee for this index -->
                                                <input type="text" class="form-control" id="selecetedEmployee_{{ $index }}"
                                                    wire:click="toggleEmployeeContainer('{{ $index }}')"
                                                    wire:model="selectedEmployees.{{ $index }}" value="{{ $selectedEmployees[$index] ?? '' }}" readonly>
                                                <div class="input-group-append bg-white border" wire:click="toggleEmployeeContainer('{{ $index }}')">
                                                    <span class="input-group-text" style="border:none; background:none;">
                                                        <i class="ph-caret-down-fill"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            @if(isset($this->openEmployeeContainers[$index]) && $this->openEmployeeContainers[$index])
                                            <div class="search-container position-absolute" style="top:100%;z-index:1;">
                                                <input type="text" wire:input="getEmployeeData" wire:model="searchTerm" class="form-control" id="employeeSearch" placeholder="Search for employee..." />
                                                @if(!is_null($employeeIds) && $employeeIds)
                                                <div>
                                                    @foreach ($employeeIds as $empData)
                                                    <div wire:click="getSelectedEmployee('{{ $empData->emp_id }}', '{{ $path }}', {{ $index }})"
                                                        class="empDiv mt-2 p-2 border rounded bg-white d-flex align-items-center gap-3">
                                                        <div class="rounded-circle name d-flex bg-grey align-items-center justify-content-center">
                                                            <span>
                                                                {{ substr($empData->first_name, 0, 1) }}{{ substr($empData->last_name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div class="d-flex flex-column align-items-start">
                                                            <div>
                                                                <span class="normalText">{{ ucwords(strtolower($empData->first_name)) }} {{ ucwords(strtolower($empData->last_name)) }}</span>
                                                            </div>
                                                            <span class="smallText">{{ $empData->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @else
                                                <p>No employees found matching the search criteria.</p>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p>No images extracted from the ZIP file.</p>
                            @endif
                            <button class="cancel-btn" type="button" wire:click="gotoBack">Back</button>
                            <button class="submit-btn" type="button" wire:click="storeImageOfEmployee">Finish</button>
                            <button class="cancel-btn" type="button" wire:click="cancelUpdating({{ $folderId }})">Cancel</button>
                        </div>

                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="tab-pane" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">
                <div>
                    activity review
                </div>

            </div>
        </div>

    </div>