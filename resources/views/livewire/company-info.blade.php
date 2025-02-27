<div x-data="{ showHelp: false }">
    <div class="p-4 rounded bg-blue-50">
        <div class="flex justify-content-end items-center ">
            <button @click="showHelp = !showHelp" class="btn btn-primary fw-semibold shadow-sm px-2 py-1 rounded">
                <span x-text="showHelp ? 'Hide Help' : 'Show Help'"></span>
            </button>
        </div>

        <div x-show="showHelp" class="mt-2 p-3 bg-white border rounded shadow">
            <p>
                The <strong>Company Settings</strong> page enables you to configure various options such as Company
                Name,
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
    <div class="container mt-4 d-flex">
        <!-- Left side: Form -->
        <div class="card shadow-lg col-md-6 col-lg-5 col-xl-4">
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                <form wire:submit.prevent="saveCompanyInfo">
                    <div class="mb-3">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="company_name"
                            {{ $viewMode ? 'disabled' : '' }}>
                        @error('company_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Industry <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="industry"{{ $viewMode ? 'disabled' : '' }}>
                            <option value="">Select an Industry</option>
                            <option value="IT">IT</option>
                            <option value="Manufacturing">Manufacturing</option>
                        </select>
                        @error('industry')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="country"{{ $viewMode ? 'disabled' : '' }}>
                            <option value="">Select Country</option>
                            <option value="USA">USA</option>
                            <option value="India">India</option>
                        </select>
                        @error('country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">States <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="state"{{ $viewMode ? 'disabled' : '' }}>
                            <option value="">Select Country</option>
                            <option value="USA">USA</option>
                            <option value="India">India</option>
                        </select>
                        @error('country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Time Zone <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="time_zone"{{ $viewMode ? 'disabled' : '' }}>
                                <option value="">Select a TimeZone</option>
                                <option value="GMT">GMT</option>
                                <option value="EST">EST</option>
                            </select>
                            @error('time_zone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="currency"{{ $viewMode ? 'disabled' : '' }}>
                                <option value="">Select Currency</option>
                                <option value="USD">USD</option>
                                <option value="INR">INR</option>
                            </select>
                            @error('currency')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Present Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" wire:model="company_present_address" {{ $viewMode ? 'disabled' : '' }}></textarea>
                    </div>

                    <div class="mb-3">
                        <input type="checkbox" wire:model.live="same_as_present" id="same_as_present"
                            {{ $viewMode ? 'disabled' : '' }}>
                        <label for="same_as_present">Same as Present Address</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Permanent Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" wire:model="company_permanent_address"
                            {{ $same_as_present || $viewMode ? 'disabled' : '' }}>
                        </textarea>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">PF No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                wire:model="pf_no"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">TAN No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                wire:model="tan_no"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">PAN No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                wire:model="pan_no"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ESI No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                wire:model="esi_no"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">LIN No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                wire:model="lin_no"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">GST No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                wire:model="gst_no"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                wire:model="registration_no"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Website</label>
                            <input type="text" class="form-control"
                                wire:model="company_website"{{ $viewMode ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Company Logo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control"
                            wire:model="company_logo"{{ $viewMode ? 'disabled' : '' }}>
                        @error('company_logo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3 mt-3 text-center">
                        @if ($viewMode)
                            <button type="button" class="btn btn-secondary" wire:click="resetForm">Close</button>
                        @else
                            <button type="submit"
                                class="btn btn-primary">{{ $company_id ? 'Update' : 'Save' }}</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Right side: Saved Companies (Only Show When List is Not Empty) -->
        @if ($savedCompanies->count() > 0)
            <div class="card shadow-lg col-md-6 col-lg-5 col-xl-8 ms-3" style="max-height: 400px; overflow: hidden;">
                <div class="card-body">
                    <h5 class="mb-3">Saved Companies</h5>
                    <div class="w-25">
                        <input type="text" class="form-control form-control-sm mb-1" placeholder="Search..."
                            wire:model="search">
                    </div>
                    <!-- Table Format -->
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered table-striped company-table">
                            <thead class="table-primary position-sticky top-0">
                                <tr>
                                    <th class="text-nowrap">S.NO</th>
                                    <th class="text-nowrap">Company Logo</th>
                                    <th class="text-nowrap">Company Name</th>
                                    <th class="text-nowrap">Company Type</th>
                                    <th class="text-nowrap">Registration No</th>
                                    <th class="text-nowrap">P.F No</th>
                                    <th class="text-nowrap">TAN No</th>
                                    <th class="text-nowrap">LIN No</th>
                                    <th class="text-nowrap">GST No</th>
                                    <th class="text-nowrap">PAN No</th>
                                    <th class="text-nowrap">ESI No</th>
                                    <th class="text-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($savedCompanies->take(3) as $key => $company)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($company->company_logo)
                                                @php
                                                    $mimeType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $company->company_logo);
                                                @endphp
                                                <img src="data:{{ $mimeType }};base64,{{ $company->company_logo }}" alt="Company Logo" width="100" height="40">
                                            @else
                                                <span>No Logo</span>
                                            @endif
                                        </td>

                                        <td>{{ $company->company_name }}</td>
                                        <td>{{ $company->company_type }}</td>
                                        <td>{{ $company->registration_no }}</td>
                                        <td>{{ $company->pf_no }}</td>
                                        <td>{{ $company->tan_no }}</td>
                                        <td>{{ $company->lin_no }}</td>
                                        <td>{{ $company->gst_no }}</td>
                                        <td>{{ $company->pan_no }}</td>
                                        <td>{{ $company->esi_no }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <i class="bi bi-eye text-primary" wire:click="viewCompanyInfo({{ $company->id }})" style="cursor: pointer;"></i>
                                                <i class="bi bi-pencil text-warning" wire:click="editCompanyInfo({{ $company->id }})" style="cursor: pointer;"></i>
                                                <i class="bi bi-trash text-danger" wire:click="deleteCompanyInfo({{ $company->id }})" style="cursor: pointer;"></i>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <!-- Pagination Links -->
                    <div class="mt-3">
                        {{ $savedCompanies->links() }}
                    </div>
                </div>
            </div>
        @endif



    </div>
</div>
