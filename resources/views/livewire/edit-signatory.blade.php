<div class="container mt-3">
    <h4>Edit Signatory</h4>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="update">
        <div class="row">
            <div class="col-md-6" style="margin-left: 90px;">
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>First Name</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="first_name"
                                value="{{ $first_name }}">
                            @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Last Name</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="last_name"
                                value="{{ $last_name }}">
                            @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Add other fields for Designation, Company, Is Active, etc. -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Designation</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="designation"
                                value="{{ $designation }}">
                            @error('designation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Is Active Checkbox -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Is Active</label>
                        </div>
                        <div class="col-md-6">
                            <input type="checkbox" class="form-check-input" wire:model="is_active"
                                {{ $is_active ? 'checked' : '' }}>
                            @error('is_active')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Signature Upload -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Signature</label>
                        </div>
                        <div class="col-md-6">
                            <input type="file" class="form-control" wire:model="signature">
                            @error('signature')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @if ($signature)
                      
                                <div class="mt-3">
                                    <!-- If the signature is base64 encoded, display the image -->
                                    <img src="data:image/jpeg;base64,{{ $signature }}" alt="Signature"
                                        style="width:150px; height:auto;">
                                </div>
                            {{-- @elseif ($this->signature)
                                <!-- If signature exists but hasn't been changed yet, show the old one -->
                                <div class="mt-3">
                                    <img src="data:image/jpeg;base64,{{ $this->signature }}" alt="Previous Signature"
                                        style="width:150px; height:auto;">
                                </div> --}}
                            @endif
                        </div>
                    </div>
                </div>



                <div style="margin-left: 200px; margin-top: 25px">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" onclick="window.location='{{ route('authorize-signatory.page') }}'"
                        class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>
