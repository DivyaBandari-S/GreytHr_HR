<div class="container mt-3">
    <div style="display: flex; justify-content: end;">
        <button class="submit-btn" onclick="window.location='{{ route('signatory.create') }}'">
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
                            <button class="btn btn-sm btn-secondary" onclick="window.location='{{ route('signatory.edit', $signatory->id) }}'">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-info" wire:click="delete({{ $signatory->id }})">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <img class="task-no-items-found" src="{{ asset('images/nodata.png') }}"
                                alt="No items found">
                            <p>No Data Found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
