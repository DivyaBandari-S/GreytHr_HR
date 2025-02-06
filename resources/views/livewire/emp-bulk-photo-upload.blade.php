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
                                <tr>
                                    <td>22 Mar, 2024</td>
                                    <td>zip</td>
                                    <td>Pending</td>
                                    <td>View file</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @if($showUploadContent)
                <div>
                    <div class="progress-container">
                        <div class="progress-line"></div>

                        <!-- Step 1 -->
                        <div class="progress-step">
                            <div class="circle {{ $currentStep >= 1 ? ($currentStep == 1 ? 'active' : 'completed') : 'pending' }}">1</div>
                            <div class="label">General</div>
                        </div>

                        <!-- Step 2 -->
                        <div class="progress-step">
                            <div class="circle {{ $currentStep >= 2 ? ($currentStep == 2 ? 'active' : 'completed') : 'pending' }}">2</div>
                            <div class="label">Select Employees</div>
                        </div>
                    </div>
                    <div>
                        @if($currentStep == 1)
                        <div>Step 1: Upload

                            You can upload photos of multiple employees in one shot by uploading a zip file containing all photos.

                            If photos are named according to employee numbers (for example, 01290.jpg), the assignment is automatically done. If not, you can
                            easily associate an employee with the photos in the next step.

                        </div>
                        <div class="bg-grey p-4">
                            <div class="form-group">
                                <input type="file" wire:model="zip_file" class="form-control" accept=".zip">
                            </div>
                        </div>

                        <div class="buttons-container d-flex gap-3">
                            <button class="cancel-btn">Previous</button>
                            <button class="cancel-btn" wire:click="UploadBulkZipFile">Next</button>
                            <button class="cancel-btn">cancel</button>
                        </div>
                        @elseif($currentStep == 2)
                        <div>
                            <h3>Assign profile photos to employees</h3>
                            @if($imagePaths && count($imagePaths) > 0)
                            <div class="image-gallery">

                                @foreach($imagePaths as $path)
                                <div class="image-item">
                                    <img src="{{ asset($path) }}" alt="Extracted Image" style="max-width: 150px; max-height: 150px;">
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p>No images extracted from the ZIP file.</p>
                            @endif
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