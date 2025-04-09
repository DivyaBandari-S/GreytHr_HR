<div class="container">

  <h5 class="mt-3">Add Signatory</h5>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-md-6" style="margin-left: 90px;">
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-md-3">
                        <label>First Name</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="first_name" wire:keyup="validateInputChange('first_name')">
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
                        <input type="text" class="form-control" wire:model="last_name" wire:keyup="validateInputChange('last_name')">
                        @error('last_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Designation Field -->
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-md-3">
                        <label>Designation</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="designation" wire:keyup="validateInputChange('designation')">
                        @error('designation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Company Field -->
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-md-3">
                        <label>Company</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="company" wire:keyup="validateInputChange('company')">
                        @error('company')
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
                        <input type="checkbox" class="form-check-input" wire:model="is_active" wire:click="validateInputChange('is_active')">
                        @error('is_active')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Signature Upload Field -->
            <div class="form-group mt-3">
                <div class="row">
                    <div class="col-md-3">
                        <label>Signature</label>
                    </div>
                    <div class="col-md-6">
                        <input type="file" class="form-control" wire:model="signature"  wire:change="validateInputChange('signature')">
                        @error('signature')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        </div>
<div style="margin-left: 200px; margin-top: 25px">
    <button type="submit" class="submit-btn">Save</button>
    <button type="button" onclick="window.location='{{ route('authorize-signatory.page') }}'"
        class="cancel-btn">
        Cancel
    </button>
</div>
        
    </form>
</div>
