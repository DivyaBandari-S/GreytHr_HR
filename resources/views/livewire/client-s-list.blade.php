<div class="container mt-3">
    <!-- Add Client Button -->
    <div style="display: flex; justify-content: end;">
        <button class="btn btn-primary mb-3" style=" margin-right: 10px;" onclick="window.location='{{ route('client.create') }}'">
            Add Client
        </button>
        <button class="btn btn-secondary mb-3 ml-2" onclick="window.location='{{ route('assign.project') }}'">
            Assign Project
        </button>
       
    </div>

    <!-- Clients Table -->
    <div class="analytic-table-container mt-3">
        <table class="analytic-table">
            <thead>
                <tr>
                    <th style="width: 12%">Client Name</th>
                    <th style="width: 12%">HR Name</th>
                    <th>Registration Date</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Logo</th>
                    <th style="width: 22%;">Action</th> <!-- Add Action Column -->
                </tr>
            </thead>
            <tbody>
                @forelse ($clients as $client)
                    <tr>
                        <td class="analytic-grey-text">{{ ucwords(strtolower($client->client_name)) }}</td>
                        <td class="analytic-grey-text">{{ ucwords(strtolower($client->hr_name)) }}</td>
                        <td class="analytic-grey-text">{{ \Carbon\Carbon::parse($client->client_registration_date)->format('d M, Y') }}</td>
                        <td class="analytic-grey-text">{{ $client->contact_email }}</td>
                        <td class="analytic-grey-text">{{ $client->contact_phone }}</td>
                        <td class="analytic-grey-text">
                            @if($client->client_logo)
                            <img src="data:image/png;base64,{{ base64_encode($client->client_logo) }}" alt="Client Logo" width="50" height="50">

                            @else
                                No Logo
                            @endif
                        </td>
                        <td>
                            <!-- Edit Button (always enabled) -->
                            <button class="btn btn-sm btn-secondary" onclick="window.location='{{ route('client.edit', ['clientId' => $client->client_id]) }}'" {{ $client->status == 1 ? 'disabled' : '' }}>
                                Edit
                            </button>
                            
                                                    
                        
                            <!-- Delete Button (disabled if status is 1) -->
                            <button class="btn btn-sm btn-info" wire:click="delete({{ $client->client_id }})" {{ $client->status == 1 ? 'disabled' : '' }}>
                                Delete
                            </button>
                        
                            <!-- Add Project Button (disabled if status is 1) -->
                            <button class="btn btn-sm btn-info" onclick="window.location='{{ route('project.create', ['client_id' => $client->client_id]) }}'" {{ $client->status == 1 ? 'disabled' : '' }}>
                                Add Project
                            </button>
                        </td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}" alt="No items found">
                            <p>No Data Found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

