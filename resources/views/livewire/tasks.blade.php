<div >
    <div class="position-relative"  >
        <div class="position-absolute" wire:loading
            wire:target="setActiveTab,show,toggleAccordion,openAddCommentModal,openForTasks,showViewFile,close,forAssignee,closeAssignee,selectPerson,forFollowers,closeFollowers,togglePersonSelection,submit,downloadImage,closeViewFile,closeModal,openEditCommentModal,deleteComment,updateComment,cancelEdit,addComment,updateFilterDropdown,closeForTasks">
            <div class="loader-overlay">
                <div class="loadera">
                    <div></div>
                </div>

            </div>
        </div>

        <div class="container task-first-container">
            <div class="nav-buttons d-flex justify-content-center task-tab-button-container">
                <ul class="nav custom-nav-tabs border">
                    <li class="custom-item m-0 p-0 flex-grow-1 mbl-dev-active">
                        <div class="task-open-tab-container custom-nav-link {{ $activeTab === 'open' ? 'active' : '' }}"
                            wire:click.prevent="setActiveTab('open')">Open</div>
                    </li>
                    <li class="custom-item m-0 p-0 flex-grow-1 mbl-dev-closed">
                        <a href="#"
                            class="task-closed-tab-container custom-nav-link {{ $activeTab === 'completed' ? 'active' : '' }}"
                            wire:click.prevent="setActiveTab('completed')">Closed</a>
                    </li>
                </ul>
            </div>


            @if ($activeTab == 'open')
            <div class="task-filter-container d-flex justify-content-end">
                <div class="task-search-container">
                    <div class="input-group task-input-group-container">
                        <input wire:input="searchActiveTasks" wire:model="search" type="text"
                            class="form-control task-search-input" placeholder="Search..." aria-label="Search"
                            aria-describedby="basic-addon1">
                        <div>
                            <button wire:click="searchActiveTasks" class="task-search-btn" type="button">
                                <i class="fa fa-search task-search-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="task-dropdown">
                    <div class="form-group">

                        <select class="form-select task-custom-select-width" wire:model="filterPeriod"
                            wire:change="updateFilterDropdown">
                            <option value="all" selected>All</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_year">This Year</option>
                        </select>

                    </div>
                </div>
                <div>
                    <button wire:click="show" class="task-add-new-task-button">Add
                        New Task</button>
                </div>

            </div>

            <div class="card-body task-open-card-body-container">

                <div class="table-cresponsive">
                    <table class="task-open-table">
                        <thead>
                            <tr class="task-open-table-row">
                                <th class="task-open-table-1-th">
                                    <i class="fa fa-angle-down task-arrow-icon"></i>
                                </th>
                                <th class="task-open-table-2-th">
                                    Task ID
                                </th>
                                <th class="task-open-table-3-th">
                                    Task Name
                                </th>
                                <th class="task-open-table-4-th" style="">
                                    Assigned By
                                </th>
                                <th class="task-open-table-5-th">
                                    Assignee
                                </th>

                                <th class="task-open-table-6-th">
                                    Assigned
                                    Date
                                </th>
                                <th class="task-open-table-7-th">
                                    Due Date
                                </th>
                                <th class="task-open-table-8-th">
                                    Re-Opened Date
                                </th>
                                <th class="task-open-table-9-th">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($searchData->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center">
                                    <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}"
                                        alt="No items found">
                                    <p>No Data Found</p>
                                </td>
                            </tr>
                            @else

                            @foreach ($searchData as $index => $record)
                            @if ($record->status == 10)

                            <tr>
                                <td class="task-open-table-1-td">
                                    <div class="arrow-btn"
                                        wire:click="toggleAccordion('{{ $record->id }}')">
                                        <i
                                            class="{{ in_array($record->id, $openAccordions) ? 'fa fa-angle-up' : 'fa fa-angle-down' }}"></i>
                                    </div>
                                </td>
                                <td class="task-open-table-2-td">
                                    T-{{ $record->id }}
                                </td>

                                <td class="task-open-table-3-td">
                                    {{ ucfirst($record->task_name) }}
                                </td>
                                <td class="task-open-table-4-td">

                                    @php
                                    $loggedInEmpName =
                                    ucwords(strtolower(auth()->user()->first_name)) .
                                    ' ' .
                                    ucwords(strtolower(auth()->user()->last_name));
                                    $recordEmpName =
                                    ucwords(strtolower($record->emp->first_name)) .
                                    ' ' .
                                    ucwords(strtolower($record->emp->last_name));
                                    @endphp

                                    @if ($loggedInEmpName === $recordEmpName)
                                    me
                                    @else
                                    {{ $recordEmpName }}
                                    @endif

                                </td>
                                <td class="task-open-table-5-td">

                                    {{ ucwords(strtolower($record->assignee)) }}
                                </td>
                                <td class="task-open-table-6-td">
                                    {{ \Carbon\Carbon::parse($record->created_at)->format('d M, Y') }}
                                </td>
                                <td class="task-open-table-7-td">
                                    {{ \Carbon\Carbon::parse($record->due_date)->format('d M, Y') }}
                                </td>
                                <td class="task-open-table-8-td">
                                    @if ($record->reopened_date)
                                    {{ \Carbon\Carbon::parse($record->reopened_date)->format('d M, Y') }}
                                    @else
                                    <span>-</span>
                                    @endif
                                </td>
                                <td class="task-open-table-9-td">
                                    @foreach ($record->comments ?? [] as $comment)
                                    {{ $comment->comment }}
                                    @endforeach
                                    <!-- Add Comment link to trigger modal -->
                                    <button type="button"
                                        wire:click.prevent="openAddCommentModal('{{ $record->id }}')"
                                        class="submit-btn" data-toggle="modal"
                                        data-target="#exampleModalCenter">Comment</button>
                                    <button wire:click="openForTasks('{{ $record->id }}')"
                                        class="cancel-btn task-open-close-button">Close</button>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="9" class="m-0 p-0 bg-white">
                                    <div
                                        class="accordion-content mt-0 task-accordion-content {{ in_array($record->id, $openAccordions) ? 'open' : '' }}">
                                        <!-- Content for accordion body -->
                                        <table class="rounded border task-accordion-open-table">
                                            <thead class="py-0 task-accordion-open-table-header">
                                                <tr class="task-accordion-open-table-tr">
                                                    <th class="task-accordion-open-table-1-th">
                                                        Priority</th>

                                                    <th class="task-accordion-open-table-2-th">
                                                        Followers</th>
                                                    <th class="task-accordion-open-table-3-th">
                                                        Subject</th>
                                                    <th class="task-accordion-open-table-4-th">
                                                        Description</th>
                                                    <th class="task-accordion-open-table-5-th">
                                                        Attach</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="task-accordion-open-table-1-td">
                                                        {{ $record->priority }}
                                                    </td>

                                                    <td class="task-accordion-open-table-2-td">
                                                        {{ ucfirst($record->followers) ?: '-' }}
                                                    </td>
                                                    <td class="task-accordion-open-table-3-td">
                                                        {{ ucfirst($record->subject ?? '-') }}
                                                    </td>
                                                    <td class="task-accordion-open-table-4-td">
                                                        {{ ucfirst($record->description ?? '-') }}
                                                    </td>
                                                    <td class="task-accordion-open-table-5-td">

                                                        @if (!empty($record->file_paths))
                                                        @php
                                                       

                                                            // Check if $leaveRequest->file_paths is a string or an array
                                                            $fileDataArray = is_string(
                                                                $record->file_paths,
                                                            )
                                                                ? json_decode(
                                                                    $record->file_paths,
                                                                    true,
                                                                )
                                                                : $record->file_paths;
                                                               

                                                            // Separate images and files
                                                            $images = array_filter(
                                                                $fileDataArray,
                                                                fn($fileData) => strpos(
                                                                    $fileData['mime_type'],
                                                                    'image',
                                                                ) !== false,
                                                            );
                                                            $files = array_filter(
                                                                $fileDataArray,
                                                                fn($fileData) => strpos(
                                                                    $fileData['mime_type'],
                                                                    'image',
                                                                ) === false,
                                                            );

                                                        @endphp


                                                        {{-- view file popup --}}
                                                        @if ($showViewImageDialog)
                                                            <div class="modal custom-modal"
                                                                tabindex="-1" role="dialog"
                                                                style="display: block;">
                                                                <div class="modal-dialog custom-modal-dialog custom-modal-dialog-centered custom-modal-lg"
                                                                    role="document">
                                                                    <div
                                                                        class="modal-content custom-modal-content">
                                                                        <div
                                                                            class="modal-header custom-modal-header">
                                                                            <h5
                                                                                class="modal-title view-file">
                                                                                View Image</h5>
                                                                        </div>
                                                                        <div
                                                                            class="modal-body custom-modal-body">
                                                                            <div
                                                                                class="swiper-container">
                                                                                <div
                                                                                    class="swiper-wrapper">

                                                                                    @foreach ($images as $image)
                                                                                        @php
                                                                                            $base64File =
                                                                                                $image[
                                                                                                    'data'
                                                                                                ];
                                                                                            $mimeType =
                                                                                                $image[
                                                                                                    'mime_type'
                                                                                                ];
                                                                                        @endphp
                                                                                        <div
                                                                                            class="swiper-slide">
                                                                                            <img src="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                                                                class="img-fluid"
                                                                                                alt="Image">
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div
                                                                            class="modal-footer custom-modal-footer">
                                                                            <button type="button"
                                                                                class="submit-btn"
                                                                                wire:click.prevent="downloadImage({{ $record->id }})">Download</button>
                                                                            <button type="button"
                                                                                class="cancel-btn"
                                                                                wire:click="closeViewImage">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="modal-backdrop fade show blurred-backdrop">
                                                            </div>
                                                        @endif
                                                        @if ($showViewFileDialog)
                                                            <div class="modal" tabindex="-1"
                                                                role="dialog"
                                                                style="display: block;">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5
                                                                                class="modal-title view-file">
                                                                                View Files</h5>
                                                                        </div>
                                                                        <div class="modal-body"
                                                                            style="max-height: 400px; overflow-y: auto;">
                                                                            <ul
                                                                                class="list-group list-group-flush">

                                                                                @foreach ($files as $file)
                                                                                    @php

                                                                                        $base64File =
                                                                                            $file[
                                                                                                'data'
                                                                                            ];

                                                                                        $mimeType =
                                                                                            $file[
                                                                                                'mime_type'
                                                                                            ];

                                                                                        $originalName =
                                                                                            $file[
                                                                                                'original_name'
                                                                                            ];

                                                                                    @endphp

                                                                                    <li>

                                                                                        <a href="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                                                            download="{{ $originalName }}"
                                                                                            class="anchorTagDetails">

                                                                                            {{ $originalName }}

                                                                                        </a>

                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="cancel-btn"
                                                                                wire:click="closeViewFile">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="modal-backdrop fade show blurred-backdrop">
                                                            </div>
                                                        @endif
                                                        <!-- Trigger Links -->
                                                        @if (!empty($images) && count($images) > 1)
                                                            <a href="#"
                                                                wire:click.prevent="showViewImage"
                                                                class="anchorTagDetails">
                                                                View Images
                                                            </a>
                                                        @elseif(!empty($images) && count($images) == 1)
                                                            <a href="#"
                                                                wire:click.prevent="showViewImage"
                                                                class="anchorTagDetails">
                                                                View Image
                                                            </a>
                                                        @endif

                                                        @if (!empty($files) && count($files) > 1)
                                                            <a href="#"
                                                                wire:click.prevent="showViewFile"
                                                                class="anchorTagDetails">
                                                                Download Files
                                                            </a>
                                                        @elseif(!empty($files) && count($files) == 1)
                                                            @foreach ($files as $file)
                                                                @php
                                                                    $base64File = trim(
                                                                        $file['data'] ?? '',
                                                                    );
                                                                    $mimeType =
                                                                        $file['mime_type'] ??
                                                                        'application/octet-stream'; // Default MIME type
                                                                    $originalName =
                                                                        $file['original_name'] ??
                                                                        'download.pdf'; // Default file name
                                                                @endphp

                                                                <a href="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                                    download="{{ $originalName }}"
                                                                    class="anchorTagDetails">
                                                                    Download File
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                    @else
                                                        {{-- Show this message if no file is attached --}}
                                                        <p class="text-grey">N/A</p>
                                                    @endif
                                                    </td>


                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- Modal Structure (updated to include record ID) -->


                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>



            @endif

            @if ($activeTab == 'completed')
            <div class="task-filter-container d-flex justify-content-end">
                <div class="task-search-container">
                    <div class="input-group task-input-group-container">
                        <input wire:input="searchCompletedTasks" wire:model="closedSearch" type="text"
                            class="form-control task-search-input" placeholder="Search..." aria-label="Search"
                            aria-describedby="basic-addon1">
                        <div>
                            <button wire:click="searchCompletedTasks" class="task-search-btn" type="button">
                                <i class="fa fa-search task-search-icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="task-dropdown">
                    <div class="form-group">
                        <select class="form-select task-custom-select-width" wire:model="filterPeriod"
                            wire:change="updateFilterDropdown">
                            <option value="all" selected>All</option>
                            <option value="this_week">This Week</option>
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_year">This Year</option>
                        </select>
                    </div>
                </div>
                <div>
                    <button wire:click="show" class="task-add-new-task-button">Add
                        New Task</button>
                </div>

            </div>

            <div class="card-body task-closed-card-body">

                <div class="table-responsive">
                    <table class="task-open-table">
                        <thead>
                            <tr class="task-closed-table-row">
                                <th class="task-closed-table-1-th">
                                    <i class="fa fa-angle-down task-arrow-icon"></i>
                                </th>
                                <th class="task-closed-table-2-th">
                                    Task ID
                                </th>
                                <th class="task-closed-table-3-th">
                                    Task Name
                                </th>


                                <th class="task-closed-table-4-th">
                                    Assigned By
                                </th>
                                <th class="task-closed-table-5-th">
                                    Assignee
                                </th>
                                <th class="task-closed-table-6-th">
                                    Assigned
                                    Date</th>
                                <th class="task-closed-table-7-th">
                                    Due Date
                                </th>

                                <th class="task-closed-table-8-th">
                                    Closed
                                    Date</th>
                                <th class="task-closed-table-9-th">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($searchData->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center">
                                    <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}"
                                        alt="No items found">
                                    <p>No Data Found</p>
                                </td>
                            </tr>
                            @else
                            @foreach ($searchData as $record)
                            @if ($record->status == 11)
                            <tr>

                                <td class="task-closed-table-1-td">
                                    <div class="arrow-btn"
                                        wire:click="toggleAccordion('{{ $record->id }}')">
                                        <i
                                            class="{{ in_array($record->id, $openAccordions) ? 'fa fa-angle-up' : 'fa fa-angle-down' }}"></i>
                                    </div>
                                </td>
                                <td class="task-closed-table-2-td">
                                    T-{{ $record->id }}
                                </td>
                                <td class="task-closed-table-3-td">
                                    {{ ucfirst($record->task_name) }}
                                </td>

                                <td class="task-closed-table-4-td">
                                    @php
                                    $loggedInEmpName =
                                    ucwords(strtolower(auth()->user()->first_name)) .
                                    ' ' .
                                    ucwords(strtolower(auth()->user()->last_name));
                                    $recordEmpName =
                                    ucwords(strtolower($record->emp->first_name)) .
                                    ' ' .
                                    ucwords(strtolower($record->emp->last_name));
                                    @endphp

                                    @if ($loggedInEmpName === $recordEmpName)
                                    me
                                    @else
                                    {{ $recordEmpName }}
                                    @endif
                                </td>

                                <td class="task-closed-table-5-td">
                                    {{ ucwords(strtolower($record->assignee)) }}
                                </td>

                                <td class="task-closed-table-6-td">
                                    {{ \Carbon\Carbon::parse($record->created_at)->format('d M, Y') }}
                                </td>
                                @php
                                $isOverdue = \Carbon\Carbon::parse($record->updated_at)
                                ->startOfDay()
                                ->gt(\Carbon\Carbon::parse($record->due_date)->startOfDay());
                                @endphp
                                <td class="task-closed-table-7-td {{ $isOverdue ? 'overdue' : '' }}">
                                    {{ \Carbon\Carbon::parse($record->due_date)->format('d M, Y') }}
                                </td>
                                <td class="task-closed-table-8-td">
                                    {{ \Carbon\Carbon::parse($record->updated_at)->format('d M, Y') }}
                                </td>
                                <td class="task-closed-table-9-td">
                                    @foreach ($record->comments ?? [] as $comment)
                                    {{ $comment->comment }}
                                    @endforeach
                                    <!-- Add Comment link to trigger modal -->
                                    <button type="button"
                                        wire:click.prevent="openAddCommentModal('{{ $record->id }}')"
                                        class="submit-btn" data-toggle="modal"
                                        data-target="#exampleModalCenter">Comment</button>
                                    <button wire:click="closeForTasks('{{ $record->id }}')"
                                        class="cancel-btn task-closed-reopen-button">Reopen</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="9" class="m-0 p-0 bg-white">
                                    <div
                                        class="accordion-content mt-0 task-accordion-content {{ in_array($record->id, $openAccordions) ? 'open' : '' }}">
                                        <!-- Content for accordion body -->
                                        <table class="rounded border task-accordion-closed-table">
                                            <thead class="py-0 task-accordion-closed-table-header">
                                                <tr class="task-accordion-closed-row">
                                                    <th class="task-accordion-closed-table-1-th">
                                                        Priority</th>
                                                    <th class="task-accordion-closed-table-2-th">
                                                        Followers</th>
                                                    <th class="task-accordion-closed-table-3-th"
                                                        style="">
                                                        Subject</th>
                                                    <th class="task-accordion-closed-table-4-th">
                                                        Description</th>
                                                    <th class="task-accordion-closed-table-5-th">
                                                        Attach</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="task-accordion-closed-table-1-td">
                                                        {{ $record->priority }}
                                                    </td>
                                                    @php
                                                    $textAlignClass = $record->followers
                                                    ? 'task-accordion-text-start'
                                                    : 'task-accordion-text-center';
                                                    @endphp
                                                    <td class="task-accordion-closed-table-2-td">
                                                        {{ ucfirst($record->followers) ?: '-' }}
                                                    </td>
                                                    <td class="task-accordion-closed-table-3-td">
                                                        {{ ucfirst($record->subject ?? '-') }}
                                                    </td>
                                                    <td class="task-accordion-closed-table-4-td">
                                                        {{ ucfirst($record->description ?? '-') }}
                                                    </td>
                                                    <td class="task-accordion-closed-table-5-td">

                                                        @if (!empty($record->file_paths))
                                                                        @php
                                                                       

                                                                            // Check if $leaveRequest->file_paths is a string or an array
                                                                            $fileDataArray = is_string(
                                                                                $record->file_paths,
                                                                            )
                                                                                ? json_decode(
                                                                                    $record->file_paths,
                                                                                    true,
                                                                                )
                                                                                : $record->file_paths;
                                                                               

                                                                            // Separate images and files
                                                                            $images = array_filter(
                                                                                $fileDataArray,
                                                                                fn($fileData) => strpos(
                                                                                    $fileData['mime_type'],
                                                                                    'image',
                                                                                ) !== false,
                                                                            );
                                                                            $files = array_filter(
                                                                                $fileDataArray,
                                                                                fn($fileData) => strpos(
                                                                                    $fileData['mime_type'],
                                                                                    'image',
                                                                                ) === false,
                                                                            );

                                                                        @endphp


                                                                        {{-- view file popup --}}
                                                                        @if ($showViewImageDialog)
                                                                            <div class="modal custom-modal"
                                                                                tabindex="-1" role="dialog"
                                                                                style="display: block;">
                                                                                <div class="modal-dialog custom-modal-dialog custom-modal-dialog-centered custom-modal-lg"
                                                                                    role="document">
                                                                                    <div
                                                                                        class="modal-content custom-modal-content">
                                                                                        <div
                                                                                            class="modal-header custom-modal-header">
                                                                                            <h5
                                                                                                class="modal-title view-file">
                                                                                                View Image</h5>
                                                                                        </div>
                                                                                        <div
                                                                                            class="modal-body custom-modal-body">
                                                                                            <div
                                                                                                class="swiper-container">
                                                                                                <div
                                                                                                    class="swiper-wrapper">

                                                                                                    @foreach ($images as $image)
                                                                                                        @php
                                                                                                            $base64File =
                                                                                                                $image[
                                                                                                                    'data'
                                                                                                                ];
                                                                                                            $mimeType =
                                                                                                                $image[
                                                                                                                    'mime_type'
                                                                                                                ];
                                                                                                        @endphp
                                                                                                        <div
                                                                                                            class="swiper-slide">
                                                                                                            <img src="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                                                                                class="img-fluid"
                                                                                                                alt="Image">
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div
                                                                                            class="modal-footer custom-modal-footer">
                                                                                            <button type="button"
                                                                                                class="submit-btn"
                                                                                                wire:click.prevent="downloadImage({{ $record->id }})">Download</button>
                                                                                            <button type="button"
                                                                                                class="cancel-btn"
                                                                                                wire:click="closeViewImage">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="modal-backdrop fade show blurred-backdrop">
                                                                            </div>
                                                                        @endif
                                                                        @if ($showViewFileDialog)
                                                                            <div class="modal" tabindex="-1"
                                                                                role="dialog"
                                                                                style="display: block;">
                                                                                <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                                    role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5
                                                                                                class="modal-title view-file">
                                                                                                View Files</h5>
                                                                                        </div>
                                                                                        <div class="modal-body"
                                                                                            style="max-height: 400px; overflow-y: auto;">
                                                                                            <ul
                                                                                                class="list-group list-group-flush">

                                                                                                @foreach ($files as $file)
                                                                                                    @php

                                                                                                        $base64File =
                                                                                                            $file[
                                                                                                                'data'
                                                                                                            ];

                                                                                                        $mimeType =
                                                                                                            $file[
                                                                                                                'mime_type'
                                                                                                            ];

                                                                                                        $originalName =
                                                                                                            $file[
                                                                                                                'original_name'
                                                                                                            ];

                                                                                                    @endphp

                                                                                                    <li>

                                                                                                        <a href="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                                                                            download="{{ $originalName }}"
                                                                                                            class="anchorTagDetails">

                                                                                                            {{ $originalName }}

                                                                                                        </a>

                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button"
                                                                                                class="cancel-btn"
                                                                                                wire:click="closeViewFile">Close</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="modal-backdrop fade show blurred-backdrop">
                                                                            </div>
                                                                        @endif
                                                                        <!-- Trigger Links -->
                                                                        @if (!empty($images) && count($images) > 1)
                                                                            <a href="#"
                                                                                wire:click.prevent="showViewImage"
                                                                                class="anchorTagDetails">
                                                                                View Images
                                                                            </a>
                                                                        @elseif(!empty($images) && count($images) == 1)
                                                                            <a href="#"
                                                                                wire:click.prevent="showViewImage"
                                                                                class="anchorTagDetails">
                                                                                View Image
                                                                            </a>
                                                                        @endif

                                                                        @if (!empty($files) && count($files) > 1)
                                                                            <a href="#"
                                                                                wire:click.prevent="showViewFile"
                                                                                class="anchorTagDetails">
                                                                                Download Files
                                                                            </a>
                                                                        @elseif(!empty($files) && count($files) == 1)
                                                                            @foreach ($files as $file)
                                                                                @php
                                                                                    $base64File = trim(
                                                                                        $file['data'] ?? '',
                                                                                    );
                                                                                    $mimeType =
                                                                                        $file['mime_type'] ??
                                                                                        'application/octet-stream'; // Default MIME type
                                                                                    $originalName =
                                                                                        $file['original_name'] ??
                                                                                        'download.pdf'; // Default file name
                                                                                @endphp

                                                                                <a href="data:{{ $mimeType }};base64,{{ $base64File }}"
                                                                                    download="{{ $originalName }}"
                                                                                    class="anchorTagDetails">
                                                                                    Download File
                                                                                </a>
                                                                            @endforeach
                                                                        @endif
                                                                    @else
                                                                        {{-- Show this message if no file is attached --}}
                                                                        <p class="text-grey">N/A</p>
                                                                    @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
            @endif
            @if ($showReopenDialog)
            <div class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Re-Open the Task</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label style="margin-bottom: 10px;">Due Date <span class="text-danger">*</span></label>
                                <br>

                                <input type="date" wire:model="newDueDate" wire:change="validateDueDate" style="    width: 50%;
    font-size: 0.75rem;
    padding: 5px;
    outline: none;
    border: 1px solid #ccc;
    border-radius: 5px;"
                                    class="placeholder-small task-duedate-input" min="<?= date('Y-m-d') ?>"
                                    value="<?= date('Y-m-d') ?>" required>


                            </div>
                            @error('newDueDate')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="submit-btn"
                                wire:click.prevent="submitReopen">Submit</button>
                            <button type="button" class="cancel-btn" wire:click="closeReopen">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show blurred-backdrop"></div>
            @endif
            @if ($showDialog)
            <div class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><b>Add Task</b></h5>
                            <button type="button" class="btn-close btn-primary" data-dismiss="modal"
                                aria-label="Close" wire:click="close">
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="task-container">
                                <!-- Task Name -->
                                <div class="form-group task-modal-name-container">
                                    <label for="task_name" class="task-modal-name-label">Task Name<span
                                            class="task-star-icon">*</span></label>
                                    <input type="text" wire:model.debounce.0ms="task_name"
                                        wire:input="autoValidate" class="placeholder-small task-modal-name-value"
                                        placeholder="Enter task name">
                                    @error('task_name')
                                    <span class="text-danger">Task name is required</span>
                                    @enderror
                                </div>


                                <!-- Assignee -->
                                <div class="form-group task-modal-assignee-container">
                                    <label for="assignee" class="task-modal-assignee-label">Assignee<span
                                            style="color: var(--requiredAlert);">*</span></label>
                                    <br>
                                    <i wire:click="forAssignee" wire:change="autoValidate" class="fa fa-user icon"
                                        id="profile-icon"></i>
                                    @if ($showRecipients == true)
                                    <strong class="col-4 task-modal-selected-assignee">Selected assignee:
                                    </strong><input class="col-8" type="text"
                                        style="border: none; background: transparent"
                                        value="{{ $selectedPeopleName }}" wire:model="selectedPeopleName"
                                        disabled wire:input="autoValidate">
                                    @else
                                    <a wire:click="forAssignee" class="hover-link task-modal-add-assignee"> Add
                                        Assignee</a>
                                    @endif <br>
                                    @error('assignee')
                                    <span class="text-danger">Assignee is required</span>
                                    @enderror
                                </div>

                                @if ($assigneeList)
                                <div class="task-modal-assignee-list-container">
                                    <div class="input-group d-flex task-modal-filter-container">
                                        <div
                                            class="input-group task-input-group-container task-modal-search-container">
                                            <input wire:input="filter" wire:model.debounce.0ms="searchTerm"
                                                type="text" class="form-control task-search-input"
                                                placeholder="Search employee name / Id" aria-label="Search"
                                                aria-describedby="basic-addon1">
                                            <div>
                                                <button wire:click="filter" class="task-search-btn"
                                                    type="button">
                                                    <i class="fa fa-search task-search-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div wire:click="closeAssignee" aria-label="Close">
                                            <i class="fa fa-times task-modal-close-assignee"
                                                aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    @if ($peopleAssigneeData->isEmpty())
                                    <div class="container task-modal-no-assignee">No People
                                        Found
                                    </div>
                                    @else
                                    @foreach ($peopleAssigneeData as $people)
                                    <div wire:click="selectPerson('{{ $people->emp_id }}')"
                                        class="container task-assignee-people-container">
                                        <div class="row align-items-center">
                                            <label for="person-{{ $people->emp_id }}"
                                                class="task-assignee-people-label">
                                                <div class="col-auto">
                                                    <input type="radio"
                                                        id="person-{{ $people->emp_id }}"
                                                        wire:change="autoValidate" wire:model="assignee"
                                                        value="{{ $people->emp_id }}">
                                                </div>
                                                <div class="col-auto">
                                                    @if ($people->image !== null && $people->image != 'null' && $people->image != 'Null' && $people->image != '')
                                                    <!-- It's binary, convert to base64 -->
                                                    <img src="data:image/jpeg;base64,{{ $people->image }}"
                                                        alt="base"
                                                        class="profile-image task-assignee-people-img" />
                                                    @else
                                                    @if ($people && $people->gender == 'Male')
                                                    <img class="profile-image task-assignee-people-img"
                                                        src="{{ asset('images/male-default.png') }}"
                                                        alt="Default Male Image">
                                                    @elseif($people && $people->gender == 'Female')
                                                    <img class="profile-image task-assignee-people-img"
                                                        src="{{ asset('images/female-default.jpg') }}"
                                                        alt="Default Female Image">
                                                    @else
                                                    <img class="profile-image task-assignee-people-img"
                                                        src="{{ asset('images/user.jpg') }}"
                                                        alt="Default Image">
                                                    @endif
                                                    @endif

                                                </div>
                                                <div class="col">
                                                    <h6 class="username task-assignee-people-username">
                                                        {{ ucwords(strtolower($people->first_name)) }}
                                                        {{ ucwords(strtolower($people->last_name)) }}
                                                    </h6>
                                                    <p class="mb-0 task-assignee-people-empid">
                                                        (#{{ $people->emp_id }})
                                                    </p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        @if (empty($selectedPersonClients) ||
                                        (is_array($selectedPersonClients) && !count($selectedPersonClients)) ||
                                        ($selectedPersonClients instanceof \Illuminate\Support\Collection && $selectedPersonClients->isEmpty()))
                                        @else
                                        <div class="task-modal-client-container">
                                            <label class="task-client-label" for="clientSelect">Select
                                                Client<span class="text-danger">*</span></label>
                                            <select wire:change="showProjects"
                                                class="task-client-select-container" id="clientSelect"
                                                wire:model="client_id">
                                                <option value="">Select client</option>
                                                @foreach ($selectedPersonClients as $client)
                                                <option class="task-client-select-options"
                                                    value="{{ $client->client->client_id }}">
                                                    {{ $client->client->client_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('client_id')
                                            <span class="text-danger">Client ID is required</span>
                                            @enderror
                                        </div>
                                        @endif

                                    </div>
                                    <div class="col-md-6">
                                        @if (is_null($selectedPersonClientsWithProjects) ||
                                        empty($selectedPersonClientsWithProjects) ||
                                        ($selectedPersonClientsWithProjects instanceof \Illuminate\Support\Collection &&
                                        $selectedPersonClientsWithProjects->isEmpty()))
                                        @else
                                        <div class="task-modal-project-container">
                                            <label class="task-project-label" for="clientSelect">Select
                                                Project<span class="text-danger">*</span></label>
                                            <select wire:change="autoValidate"
                                                class="task-project-select-container" id="clientSelect"
                                                wire:model="project_name">
                                                <option value="">Select project</option>
                                                @foreach ($selectedPersonClientsWithProjects as $project)
                                                <option class="task-project-select-options"
                                                    value="{{ $project->project_name }}">
                                                    {{ $project->project_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('project_name')
                                            <span class="text-danger">Project name is
                                                required</span>
                                            @enderror

                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Priority -->
                                <div class="priority-container task-modal-priority-container">
                                    <div class="row ">
                                        <div class="col-md-4">
                                            <label for="priority" class="task-priority-label">Priority</label>
                                        </div>
                                        <div class="col-md-8 mt-1">
                                            <div id="priority" class="task-priority-options-container">
                                                <div class="priority-option task-priority-low-container">
                                                    <input type="radio" id="low-priority" name="priority"
                                                        wire:model="priority" value="Low">
                                                    <span class="task-priority-options-value"
                                                        class="text-xs">Low</span>
                                                </div>
                                                <div
                                                    class="priority-option task-priority-options-medium-high-containers">
                                                    <input type="radio" id="medium-priority" name="priority"
                                                        wire:model="priority" value="Medium">
                                                    <span class="task-priority-options-value"
                                                        class="text-xs">Medium</span>
                                                </div>
                                                <div
                                                    class="priority-option task-priority-options-medium-high-containers">
                                                    <input type="radio" id="high-priority" name="priority"
                                                        wire:model="priority" value="High">
                                                    <span class="task-priority-options-value"
                                                        class="text-xs">High</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Due Date -->
                                <div class="row task-modal-duedate-container">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label task-duedate-label">Due
                                                Date<span class="text-danger">*</span></label>
                                            <br>
                                            <input wire:change="autoValidate" type="date" wire:model="due_date"
                                                class="placeholder-small task-duedate-input"
                                                min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                                            @error('due_date')
                                            <span class="text-danger">Due date is required</span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col">
                                        <!-- Tags -->
                                        <div class="form-group">
                                            <label for="tags" class="task-tags-label">Tags</label>

                                            <input wire:change="autoValidate" type="text" wire:model="tags"
                                                placeholder="Enter tags" class="placeholder-small task-tags-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group task-modal-followers-container">
                                    <label for="assignee" class="task-follower-label">Followers</label>
                                    <br>
                                    <i wire:click="forFollowers" wire:change="autoValidate"
                                        class="fas fa-user icon task-follower-user-icon" id="profile-icon"></i>

                                    @if ($selectedFollowers > 0)

                                    <strong class="task-selected-follower">Selected Followers:
                                    </strong>
                                    <textarea style="border: none; width: 100%; height: 100%; resize: none; background: transparent" disabled
                                        wire:model="followers" wire:input="autoValidate">{{ implode(', ', array_unique($selectedPeopleNamesForFollowers)) }}</textarea>
                                    @else

                                    <a wire:click="forFollowers" class="hover-link task-add-followers"> Add
                                        Followers</a>
                                    @endif
                                </div>
                                @if ($followersList)
                                <div class="task-modal-followerlist-container">
                                    <div class="input-group d-flex task-follower-filter-container">
                                        <div
                                            class="input-group task-input-group-container task-follower-search-container">
                                            <input wire:input="filterFollower"
                                                wire:model.debounce.0ms="searchTermFollower" type="text"
                                                class="form-control task-search-input"
                                                placeholder="Search employee name / Id" aria-label="Search"
                                                aria-describedby="basic-addon1">
                                            <div>
                                                <button wire:change="autoValidate" wire:click="filterFollower"
                                                    class="task-search-btn" type="button">
                                                    <i class="fa fa-search task-search-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div wire:change="autoValidate" wire:click="closeFollowers"
                                            aria-label="Close">
                                            <i class="fa fa-times task-follower-close-icon"
                                                aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    @if ($peopleFollowerData->isEmpty())
                                    <div class="container task-no-follower">No People
                                        Found
                                    </div>
                                    @else
                                    @foreach ($peopleFollowerData as $people)
                                    <div
                                        class="container task-modal-follower-peoples-container">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <label class="custom-checkbox">
                                                    <input type="checkbox" value="{{ $people->emp_id }}"
                                                        id="checkbox-{{ $people->emp_id }}"
                                                        {{ in_array($people->emp_id, $selectedPeopleForFollowers) ? 'checked' : '' }}
                                                        wire:click="togglePersonSelection('{{ $people->emp_id }}')">
                                                    <span class="checkmark"></span>
                                                </label>
                                                {{-- {{ count($selectedPeopleForFollowers) >= $maxFollowers && !in_array($people->emp_id, $selectedPeopleForFollowers) ? 'disabled' : '' }} --}}
                                            </div>
                                            <div class="col-auto">
                                                @if ($people->image !== null && $people->image != 'null' && $people->image != 'Null' && $people->image != '')
                                                <!-- It's binary, convert to base64 -->
                                                <img src="data:image/jpeg;base64,{{ $people->image }}"
                                                    alt="base" class="profile-image" />
                                                @else
                                                @if ($people && $people->gender == 'Male')
                                                <img class="profile-image"
                                                    src="{{ asset('images/male-default.png') }}"
                                                    alt="Default Male Image">
                                                @elseif($people && $people->gender == 'Female')
                                                <img class="profile-image"
                                                    src="{{ asset('images/female-default.jpg') }}"
                                                    alt="Default Female Image">
                                                @else
                                                <img class="profile-image"
                                                    src="{{ asset('images/user.jpg') }}"
                                                    alt="Default Image">
                                                @endif
                                                @endif
                                            </div>
                                            <div class="col">
                                                <h6 class="username task-follower-people-username">
                                                    {{ ucwords(strtolower($people->first_name)) }}
                                                    {{ ucwords(strtolower($people->last_name)) }}
                                                </h6>
                                                <p class="mb-0 task-follower-people-empid">
                                                    (#{{ $people->emp_id }})
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    @endif
                                </div>
                                @endif

                                @if ($validationFollowerMessage)
                                <div class="alert alert-danger">
                                    {{ $validationFollowerMessage }}
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="Subject" class="task-modal-subject-label">Subject</label>
                                    <br>
                                    <input wire:change="autoValidate" wire:model="subject"
                                        class="placeholder-small task-subject-input" placeholder="Enter subject"
                                        rows="4"></input>
                                </div>
                                <!-- Description -->
                                <div class="form-group mb-2">
                                    <label for="description" class="task-description-label">Description<span
                                            class="text-danger">*</span></label>
                                    <br>
                                    <textarea wire:input="autoValidate" wire:model="description" class="placeholder-small task-description-textarea"
                                        placeholder="Add description" rows="4"></textarea>
                                    @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- File Input -->

                                <div class="row">

                                    <div class="col">
                                        <label for="file_paths" class="task-file-input-label">
                                            <i class="fa fa-paperclip"></i> Attach Image
                                        </label>
                                    </div>

                                </div>

                                <div>
                                    <input type="file" wire:model="file_paths" id="file_paths"
                                        wire:change="fileSelected"  wire:keydown="validateField('file_paths')" class="form-control task-modal-filepath" multiple>
                                    @error('file_paths')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>


                                <div class="task-modal-save-container">
                                    <button wire:click="submit" class="submit-btn task-modal-save-button"
                                        type="button" name="link">Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif
      
        <!-- Add Comment Modal -->
        @if ($showModal)
        <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLongTitle">Add Comment</h6>
                        <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close"
                            wire:click="closeModal">
                    </div>

                    <div class="modal-body">
                        <form wire:submit.prevent="addComment">
                            <div class="form-group">
                                <label for="comment" class="task-comment-label">Comment:</label>
                                <p>
                                    <textarea class="form-control" id="comment" wire:model.lazy="newComment"
                                        wire:keydown.debounce.500ms="validateField('newComment')"></textarea>
                                </p>
                                @error('newComment')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="submit-btn task-comment-submit-btn">Submit</button>
                            </div>
                        </form>
                        <div class="task-comment-list-container">
                            @if ($taskComments->count() > 0)
                            @foreach ($taskComments as $comment)
                            <div class="comment mb-4 mt-2">
                                <div class="d-flex align-items-center gap-5">
                                    <div class="col-md-4 p-0 comment-details">
                                        <p class="truncate-text task-comment-emp-name"
                                            title="{{ $comment->employee->full_name }}">
                                            {{ $comment->employee->full_name }}
                                        </p>
                                    </div>
                                    <div class=" col-md-3 p-0 comment-time">
                                        <span
                                            class="task-comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if (Auth::guard('hr')->user()->emp_id == $comment->emp_id)
                                    <div class="col-md-2 p-0 comment-actions">
                                        <button class="comment-btn"
                                            wire:click="openEditCommentModal({{ $comment->id }})">
                                            <i class="fas fa-edit task-comment-edit-icon"></i>
                                        </button>
                                        <button class="comment-btn"
                                            wire:click="deleteComment({{ $comment->id }})">
                                            <i class="fas fa-trash task-comment-trash-icon"></i>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                <div class="col p-0 comment-content">
                                    @if ($editCommentId == $comment->id)
                                    <!-- Input field for editing -->
                                    <input class="form-control" wire:model.defer="editingComment"
                                        type="text">
                                    <!-- Button to update comment -->
                                    <button class="update-btn p-1"
                                        wire:click="updateComment({{ $comment->id }})">Update</button>
                                    <button class="btn btn-secondary p-1 m-0 task-comment-edit-cancel-btn"
                                        wire:click="cancelEdit">Cancel</button>
                                    @else
                                    <!-- Display comment content -->
                                    <p class="task-comment-text-value">
                                        {{ ucfirst($comment->comment) }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        @endif
    </div>

    </div>

</div>