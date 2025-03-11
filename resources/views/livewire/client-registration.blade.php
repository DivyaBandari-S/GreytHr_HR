<div class="container mt-5">
    <h2 style="font-size: 20px;font-weight: bold; margin-top: -20px;">{{ $client_id ? 'Edit Client' : 'Client Registration' }}</h2>

    <!-- Display success message -->
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submitForm">
        <div class="row">
            <div class="col-md-6" style="margin-left: 90px;">
                <!-- Client Name -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Client Name</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="client_name" wire:keyup="validateInputChange('client_name')"
                            @if($client_id) readonly @endif>
                            @error('client_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Line 1 -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Address Line 1</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="client_address1" wire:keyup="validateInputChange('client_address1')">
                            @error('client_address1') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Line 2 -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Address Line 2</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="client_address2" wire:keyup="validateInputChange('client_address2')">
                        </div>
                    </div>
                </div>

                <!-- Logo Upload -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Client Logo (Optional)</label>
                        </div>
                        <div class="col-md-6">
                            <input type="file" class="form-control" wire:model="client_logo" wire:change="updateClientLogo">
                            @error('client_logo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @if ($client_logo)
                            
                      
                                <div class="mt-3">
                                    <!-- If the signature is base64 encoded, display the image -->
                                    <img src="data:image/jpeg;base64,{{ $client_logo }}" alt="Signature"
                                        style="width:150px; height:auto;">
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                

                <!-- Password -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            @if($client_id) 
                            
                            @else
                            <label>Password</label>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($client_id) 
                                {{-- <input 
                                    type="password" 
                                    class="form-control" 
                                    wire:model="password" 
                                    wire:keyup="validateInputChange('password')"
                                    placeholder="Leave empty to keep current password"
                                > --}}
                            @else
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    wire:model="password" 
                                    wire:keyup="validateInputChange('password')"
                                >
                            @endif
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                

                <!-- Contact Email -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Contact Email</label>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" wire:model="contact_email" wire:keyup="validateInputChange('contact_email')">
                            @error('contact_email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Phone -->
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Contact Phone</label>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="contact_phone" wire:keyup="validateInputChange('contact_phone')">
                            @error('contact_phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit and Cancel Buttons -->
                <div style="margin-left: 200px; margin-top: 25px">
                    <button type="submit" class="submit-btn">{{ $client_id ? 'Update' : 'Save' }}</button>
                    <button type="button"  class="cancel-btn" onclick="window.location='{{ route('clientList.page') }}'">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
