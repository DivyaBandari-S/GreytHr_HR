<div class="container mt-3">


    <!-- Projects Table -->
    <div class="analytic-table-container mt-3">
        <table class="analytic-table">
            <thead>
                <tr>
                    <th style="width: 12%">Project Name</th>
                    <th style="width: 12%">Client Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Description</th>
                    <th style="width: 22%;">Action</th> <!-- Add Action Column -->
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td class="analytic-grey-text">{{ ucwords(strtolower($project->project_name)) }}</td>
                        <td class="analytic-grey-text">
                            {{ ucwords(strtolower($project->client->client_name)) }}
                        </td>
                        <td class="analytic-grey-text">@if($project->project_start_date)
                            {{ \Carbon\Carbon::parse($project->project_start_date)->format('d M, Y') }}
                        @else
                            <!-- Empty string if start date is null -->
                            <span class="text-muted">N/A</span>
                        @endif</td>
                        <td class="analytic-grey-text">@if($project->project_end_date)
                            {{ \Carbon\Carbon::parse($project->project_end_date)->format('d M, Y') }}
                        @else
                            <!-- Empty string if end date is null -->
                            <span class="text-muted">N/A</span>
                        @endif</td>
                        <td class="analytic-grey-text"> {{ $project->project_description ?: 'No Description' }}</td>
                        <td>
                            {{-- <button class="btn btn-sm btn-secondary" onclick="window.location='{{ route('project.edit', ['project_id' => $project->id]) }}'">
                                Edit
                            </button> --}}
                            <button class="btn btn-sm btn-info" wire:click="delete({{ $project->id }})">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}" alt="No items found">
                            <p>No Projects Found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

