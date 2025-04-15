<div>
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="nav-buttons mt-2">
                <ul class="nav custom-nav-tabs border">
                    <li class="custom-item m-0 p-0 flex-grow-1 mbl-dev-active">
                        <div class="reviewActiveButtons custom-nav-link {{ $activeSection === 'All' ? 'active' : '' }}" wire:click.prevent="toggleSection('All')">Active</div>
                    </li>
                    <li class="pendingCustomStyles custom-item m-0 p-0 flex-grow-1">
                        <a href="#" class="custom-nav-link {{ $activeSection === 'closed' ? 'active' : '' }}" wire:click.prevent="toggleSection('closed')">Closed</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Content Tabs -->
        <div class="tab-content mt-3" style="overflow: auto; max-height: 70vh;">
            <div class="tab-pane {{ $activeSection === 'All' ? 'active' : '' }}" id="apply-section">
                <div class="row m-0 px-2">
                    <div class="activeTab">
                        <table class="leave-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Resignation date</th>
                                    <th>Resignation Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            @if($totalResignReq && $totalResignReq->count())
                            <tbody>
                                @foreach($totalResignReq as $index => $request)
                                @if($request->status == 5)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ ucwords(strtolower($request->employee->first_name)) }} {{ ucwords(strtolower($request->employee->last_name)) }}</td>
                                    <td>{{ Carbon\Carbon::parse($request->resignation_date)->format('d M, Y') }}</td>
                                    <td>{{ ucfirst(strtolower($request->reason)) }}</td>
                                    <td>
                                        <span class="pedningColor">Pending</span>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <button class="submit-btn" type="button" wire:click="toggleApproveModal('approve', {{ $request->id }})">Approve</button>
                                            <button class="submit-btn" type="button" wire:click="toggleApproveModal('reject', {{ $request->id }})">Reject</button>
                                        </div>
                                    </td>

                                </tr>
                                @else
                                <tr>
                                    <td colspan="6 " class="text-center">No data found</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane {{ $activeSection === 'closed' ? 'active' : '' }}" id="pending-section">
                <div class="row m-0 mb-3">
                    <div class="d-flex justify-content-end">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('employee-separation') }}" class="submit-btn">Employee Separation</a>
                        </div>
                    </div>
                </div>
                <div class="row m-0 px-2">
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Resignation date</th>
                                <th>Resignation Reason</th>
                                <th>Last working day</th>
                                <th>Approved date</th>
                                <th>Approved by</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($totalResignReq && $totalResignReq->count())
                            @foreach($totalResignReq as $index => $request)
                            @if($request->status != 5)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ ucwords(strtolower($request->employee->first_name)) }} {{ ucwords(strtolower($request->employee->last_name)) }}</td>
                                <td>{{ Carbon\Carbon::parse($request->resignation_date)->format('d M, Y') }}</td>
                                <td>{{ ucfirst(strtolower($request->reason)) }}</td>
                                <td>{{ Carbon\Carbon::parse($request->last_working_date)->format('d M, Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($request->action_date)->format('d M, Y') }}</td>
                                <td>{{ $request->action_by }}</td>
                                <td>
                                    <span class="approveColor">Approved</span>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="8 " class="text-center">
                                    No data
                                </td>
                            </tr>
                            @endif

                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- //approve modal -->
    @if ($showApproveModal)
    <div class="modal" id="actionModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h6 class="modal-title" style="align-items: center;">
                        Confirm {{ ucfirst($actionType) }}
                    </h6>
                </div>

                <div class="modal-body" style="font-size: 14px; color: var(--main-heading-color);">
                    <p class="text-center">Are you sure you want to {{ $actionType }} this request?</p>

                    <div class="form-group">
                        <label for="comment">Comment (optional)</label>
                        <input type="text" id="comment" class="form-control" wire:model.defer="comment" placeholder="Enter comment...">
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-center p-3">
                    <button type="button" class="submit-btn mr-3" wire:click="confirmAction">Yes, {{ ucfirst($actionType) }}</button>
                    <button type="button" class="cancel-btn" wire:click="closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>