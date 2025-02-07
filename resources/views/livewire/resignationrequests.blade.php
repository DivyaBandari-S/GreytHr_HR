<div>

    <h4 class="text-2xl font-bold mb-4   text-center mt-2" style="color:rgb(2, 17, 79);">Resignation Requests</h4>

    <div>
        <div class="tabs">
            <div class="tabButtons">
                <button wire:click="changeTab('pending')" class="tab-button {{ $activeTab === 'pending' ? 'active' : '' }}">
                    Pending
                </button>
                <button wire:click="changeTab('completed')" class="tab-button {{ $activeTab === 'completed' ? 'active' : '' }}">
                    Completed
                </button>
            </div>
        </div>
    </div>




    <div class="mt-1" style="overflow-x: auto;">
        @if (count($hrRequests)<=0 )
            <div style="text-align: center; margin: 20px;">
            <p style="font-size: 18px; color: #555;">No records found.</p>

            @else
            <table class="mt-2 p-2 sortable empTable table-responsive">

                <thead>
                    <tr>
                        <th class="">S.NO </th>
                        <th class="">Emp ID</th>
                        <th class="">Employee Name </th>
                        <th class="">Applied On </th>
                        <th class="">Resignation Date</th>
                        <th class="">Reason </th>
                        <th class="">Attachment</th>
                        @if($activeTab=='pending')
                        <th class="">Actions</th>
                        @else
                        <th class="">Approved Date</th>
                        @endif
                        <!-- Add more table headers as needed -->
                    </tr>
                </thead>
                @foreach($hrRequests as $index => $hrRequest)
                <tbody>
                    <td>{{$index+1}}</td>
                    <td>{{$hrRequest->emp_id}}</td>
                    <td class="whitespace-nowrap">{{$hrRequest->employee->first_name}} {{$hrRequest->employee->last_name}}</td>
                    <td>{{\Carbon\Carbon::parse($hrRequest->created_at)->format('d M Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($hrRequest->resignation_date)->format('d M Y')}}</td>
                    <td>{{$hrRequest->reason}}</td>
                    <td style="padding: 10px; font-size: 12px; text-align: center;">



                        @if ($hrRequest->signature)
                        @if(strpos($hrRequest->mime_type, 'image') !== false)
                        <a href="#" class="" wire:click="showImage('{{ $hrRequest->getImageUrlAttribute() }}')">
                            View Image
                        </a>
                        @else
                        <a class="" href="{{ route('file.show', $hrRequest->id) }}" download="{{ $hrRequest->file_name }}" style="margin-top: 10px;">
                            Download file
                        </a>
                        @endif
                        @else
                        {{-- Show this message if no file is attached --}}
                        <p style="color: gray;">-</p>
                        @endif
                        @if ($showImageDialog)
                        <div class="modal fade show d-block" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        @if(!$resignId)
                                        <h5 class="modal-title">View File</h5>
                                        @else
                                        <h5 class="modal-title">Approve Resignation Request </h5>
                                        @endif
                                    </div>
                                    <div class="modal-body text-center">
                                        @if(!$resignId)
                                        <img src="{{ $imageUrl }}" src="data:image/jpeg;base64,{{ ($imageUrl) }}" class="img-fluid" alt="Image preview" style="width:50%;height:50%">
                                        @else
                                        <div class="row justify-content-center mb-2">
                                            <div class="col-md-6" style="display: flex;flex-direction:column;align-items: baseline; ">
                                                <label for="lastWorkingDate">Last Working Date<span class="text-danger onboard-Valid">*</span></label>
                                                <input type="date" class="form-control onboardinputs  placeholder-small m-0 w-100" wire:model='lastWorkingDate' min="{{ date('Y-m-d') }}">
                                                @error('lastWorkingDate')
                                                <span class="text-danger onboard-Valid">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6  " style="display: flex;flex-direction:column;align-items: baseline; ">
                                                <label for="noticePeriod">Notice Period(In days)<span class="text-danger onboard-Valid">*</span></label>
                                                <input class="form-control onboardinputs  placeholder-small m-0 w-100" type="number" min="1" placeholder="Enter Notice period in days" wire:model='noticePeriod'>
                                                @error('noticePeriod')
                                                <span class="text-danger onboard-Valid">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        @if(!$resignId)
                                        <button type="button" class="submit-btn" wire:click.prevent="downloadImage">Download</button>
                                        <button type="button" class="submit-btn" wire:click="closeImageDialog">Close</button>
                                        @else
                                        <button type="button" class="submit-btn" wire:click="submitResignation">Submit</button>
                                        <button type="button" class="submit-btn" wire:click="closeImageDialog">Cancel</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show"></div>
                        @endif
                    </td>
                    @if($activeTab=='pending')
                    <td><button type="button" wire:click='approveResignation({{$hrRequest->id}})' class="approve-btn">Accept</button></td>
                    @else
                    <td>
                    {{\Carbon\Carbon::parse($hrRequest->approved_date)->format('d M Y')}}
                    </td>
                    @endif
                </tbody>
                @endforeach
            </table>
            @endif
    </div>
</div>
