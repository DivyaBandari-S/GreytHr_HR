<div class="container mt-3">
 
<div style="display: flex; justify-content: end;">
    <button class="btn btn-primary mb-3" onclick="window.location='{{ route('signatory.create') }}'">
        Add Signatory
    </button>
</div>
    
    <div class="analytic-table-container mt-3">
        <table class="analytic-table">
            <thead>
                <tr>

                    <th>First Name</th>
                    <th class="text-start">Last Name</th>
                    <th>Designation</th>
                    <th>Company</th>
                    <th>isActive</th>

                    <th style="width: 22%;">Action</th> <!-- Add Action Column -->
                </tr>
            </thead>
            <tbody>

                @forelse ($signatories as $signatory)
                    <tr>
                        <td class="analytic-grey-text"> {{ ucwords(strtolower($signatory->first_name)) }}</td>
                        <td class="analytic-grey-text">{{ ucwords(strtolower($signatory->last_name)) }}</td>
                        <td class="analytic-grey-text">{{ ucwords(strtolower($signatory->designation)) }}</td>
                        <td class="analytic-grey-text">{{ $signatory->company }}</td>
                        <td class="analytic-grey-text">{{ $signatory->is_active ? 'Yes' : 'No' }}</td>

                        <td>
                            {{-- <a href="" class="btn btn-sm btn-secondary">
                                View
                            </a> --}}
                            <button class="btn btn-sm btn-secondary" 
                                data-bs-toggle="modal" data-bs-target="#letterModal">
                                View
                            </button>

                            <!-- Download Button -->
                            <button class="btn btn-sm btn-info" >
                                Download
                            </button>



                            {{-- <button class="btn btn-sm btn-info" wire:click="downloadLetter({{ $letter->id }})">
                                Download
                            </button> --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <img class="analytic-no-items-found"
                                src="https://media.istockphoto.com/id/1357284048/vector/no-item-found-vector-flat-icon-design-illustration-web-and-mobile-application-symbol-on.jpg?s=612x612&w=0&k=20&c=j0V0ww6uBl1LwQLH0U9L7Zn81xMTZCpXPjH5qJo5QyQ="
                                alt="No items found">
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>





</div>
