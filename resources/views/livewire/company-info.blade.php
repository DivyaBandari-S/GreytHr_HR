<div x-data="{ showHelp: false }">
    <div class="p-4 rounded bg-blue-50">
        <div class="flex justify-content-end items-center ">
        <button @click="showHelp = !showHelp" 
    class="btn btn-primary fw-semibold shadow-sm px-2 py-1 rounded">
    <span x-text="showHelp ? 'Hide Help' : 'Show Help'"></span>
</button>

        </div>

        <div x-show="showHelp" class="mt-2 p-3 bg-white border rounded shadow">
            <p>
                The <strong>Company Settings</strong> page enables you to configure various options such as Company Name, 
                Address, State of company branches, Time Zone, Location, Company IDs (PF, TAN, PAN, etc.), 
                Logo, and Signature Upload from this page. You can also update the <strong>GST No</strong> from here.
            </p>
            <p class="mt-2">
                Explore HrExpert by watching 
                <a href="#" class="text-blue-500">How-to Videos</a>, 
                <a href="#" class="text-blue-500">Help-Doc</a>, and 
                <a href="#" class="text-blue-500">FAQ</a>.
            </p>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card shadow-lg col-md-6 col-lg-5 col-xl-4 ms-5">
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <form wire:submit.prevent="save">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" wire:model="company_name">
                        @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Address</label>
                        <textarea class="form-control" wire:model="company_address"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <select class="form-select" wire:model="country">
                            <option value="">Select Country</option>
                            <option value="USA">USA</option>
                            <option value="India">India</option>
                        </select>
                        @error('country') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Industry</label>
                        <select class="form-select" wire:model="industry">
                            <option value="">Select an Industry</option>
                            <option value="IT">IT</option>
                            <option value="Manufacturing">Manufacturing</option>
                        </select>
                        @error('industry') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Time Zone</label>
                        <select class="form-select" wire:model="time_zone">
                            <option value="">Select a TimeZone</option>
                            <option value="GMT">GMT</option>
                            <option value="EST">EST</option>
                        </select>
                        @error('time_zone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Currency</label>
                        <select class="form-select" wire:model="currency">
                            <option value="">Select Currency</option>
                            <option value="USD">USD</option>
                            <option value="INR">INR</option>
                        </select>
                        @error('currency') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">PF No</label>
                            <input type="text" class="form-control" wire:model="pf_no">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">TAN No</label>
                            <input type="text" class="form-control" wire:model="tan_no">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">PAN No</label>
                            <input type="text" class="form-control" wire:model="pan_no">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ESI No</label>
                            <input type="text" class="form-control" wire:model="esi_no">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">LIN No</label>
                            <input type="text" class="form-control" wire:model="lin_no">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">GST No</label>
                            <input type="text" class="form-control" wire:model="gst_no">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration Certificate No</label>
                            <input type="text" class="form-control" wire:model="registration_no">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Twitter Handle</label>
                            <input type="text" class="form-control" wire:model="twitter_handle">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Company Logo</label>
                        <input type="file" class="form-control" wire:model="company_logo">
                        @error('company_logo') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 mt-3 text-center">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
