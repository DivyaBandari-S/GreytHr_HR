<div>

<style>

            .empTable {
                border-collapse: collapse;
                background-color: white;
                border: 1px solid #e2e8f0;
                font-size: 12px;
                overflow-x: auto;
                width: 100%;
            }

            .empTable td {
                border: 1px solid #e2e8f0;
                padding: 0.35rem;
                text-align: center;
            }

            .empTable th {
                border: 1px solid #e2e8f0;
                padding: 0.35rem;
                text-align: center;
                background-color: #306cc6;
                color: white;
                font-weight: bold;
                /* white-space: nowrap; */

            }

            .empTable tbody tr:hover {
                background-color: #f0f4f8;
            }

            .whitespace-nowrap {
                text-transform: capitalize;
                text-align: center;
            }
</style>
   <h4 class="text-2xl font-bold mb-2  text-center mt-2" style="color:rgb(2, 17, 79);">Resignation Requests</h4>
   <div class="mt-3" style="overflow-x: auto;">
            @if (count($hrRequests)<=0 )
                <div style="text-align: center; margin: 20px;">
                <p style="font-size: 18px; color: #555;">No records found.</p>
        </div>
        @else
        <table class="mt-3 p-2 sortable empTable table-responsive">

            <thead>
                <tr>
                    <th class="">S.NO </th>
                    <th class="">Emp ID</th>
                    <th class="" >Employee Name </th>
                    <th class="" >Applied On </th>
                    <th class="" >Resignation Date</th>
                    <th class="" >Reason </th>
                    <th class="" >Attachment</th>
                    <th class="">Actions</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            @foreach($hrRequests as $index => $hrRequest)
            <tbody>
                <td>{{$index}}</td>
                <td>{{$hrRequest->emp_id}}</td>
                <td class="whitespace-nowrap">{{$hrRequest->first_name}} {{$hrRequest->last_name}}</td>
                <td>{{\Carbon\Carbon::parse($hrRequest->created_at)->format('d M Y')}}</td>
                <td>{{\Carbon\Carbon::parse($hrRequest->resignation_date)->format('d M Y')}}</td>
                <td>{{$hrRequest->reason}}</td>
                <td style="padding: 10px; font-size: 12px; text-align: center;">



                            @if ($record->file_path)
                            @if(strpos($record->mime_type, 'image') !== false)
                            <a href="#" class="anchorTagDetails" wire:click.prevent="showImage('{{ $record->getImageUrlAttribute() }}')">
                                View Image
                            </a>
                            @else
                            <a class="anchorTagDetails" href="{{ route('file.show', $record->id) }}" download="{{ $record->file_name }}" style="margin-top: 10px;">
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
                                            <h5 class="modal-title">View File</h5>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ $imageUrl }}" src="data:image/jpeg;base64,{{ ($imageUrl) }}" class="img-fluid" alt="Image preview" style="width:50%;height:50%">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="submit-btn" wire:click.prevent="downloadImage">Download</button>
                                            <button type="button" class="cancel-btn1" wire:click="closeImageDialog">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show"></div>
                            @endif
                        </td>
                <td></td>

            </tbody>
            @endforeach
        </table>
        @endif
</div>
