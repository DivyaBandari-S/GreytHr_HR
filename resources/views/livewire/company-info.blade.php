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
                            wire:key="company_name_{{ now()->timestamp }}" {{ $viewMode ? 'disabled' : '' }}
                            placeholder="Enter Company Name">
                        @error('company_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company CEO Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="ceo_name"
                            wire:key="ceo_name{{ now()->timestamp }}" {{ $viewMode ? 'disabled' : '' }}
                            placeholder="Enter CEO Name">
                        @error('ceo_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" wire:model="contact_email"
                            wire:key="contact_email{{ now()->timestamp }}" {{ $viewMode ? 'disabled' : '' }}
                            placeholder="Enter Company Email">
                        @error('contact_email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 d-flex gap-3">
                        <!-- Company Phone Number -->
                        <div class="flex-grow-1">
                            <label class="form-label">Company Ph.No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="contact_phone"
                                placeholder="Enter Company Phone Number" wire:key="contact_phone{{ now()->timestamp }}"
                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('contact_phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Company Email Domain -->
                        <div class="flex-grow-1">
                            <label class="form-label">Company Email Domain <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control" wire:model="email_domain"
                                    placeholder="Enter Company Email Domain"
                                    wire:key="email_domain{{ now()->timestamp }}" {{ $viewMode ? 'disabled' : '' }}>
                            </div>
                            @error('email_domain')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Industry <span class="text-danger">*</span></label>
                        <select class="form-select selectIndustry" wire:model="company_industry">
                            <option value="">Select type of industry</option>
                            @foreach ($industries as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('company_industry')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Type <span class="text-danger">*</span></label>
                        <select class="form-select selectCompanyType" wire:model="company_type">
                            <option value="">Select type of company</option>
                            @foreach ($companyTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('company_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row mt-3">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" wire:model.live="is_parent" id="is_parent" class="me-2"
                                {{ $viewMode ? 'disabled' : '' }}>
                            <label for="is_parent" class="me-1">Parent Company?</label>

                            @if (!$is_parent)
                                <select wire:model="parent_company_id" id="parent_company" class="form-select w-50 ms-2"
                                    {{ $viewMode ? 'disabled' : '' }}>
                                    <option value="">Select Company</option>
                                    @foreach ($companyList as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <!-- Country Dropdown -->
                    <div class="mb-3">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-select selectCountry" wire:model.live="country"
                            {{ $viewMode ? 'disabled' : '' }}>
                            <option value="">Select Country</option>
                            @foreach ($countries as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- States Dropdown -->
                    <div class="mb-3">
                        <label class="form-label">States <span class="text-danger">*</span></label>

                        <div class="position-relative">
                            <div class="form-control d-flex flex-wrap align-items-center p-1"
                                @if (!$viewMode) wire:click="toggleStateDropdown" @endif
                                style="min-height: 35px; font-size: 14px; {{ $viewMode ? 'background: #e9ecef; cursor: not-allowed;' : '' }}">

                                @if (!empty($selectedStatesData))
                                    @foreach ($selectedStatesData as $state)
                                        <span class="badge px-2 py-1 me-1 d-flex align-items-center"
                                            style="background: #f0f0f0; color: #333; font-size: 12px; border-radius: 5px;">
                                            {{ $state['name'] }}
                                            @if (!$viewMode)
                                                <span wire:click.stop="removeState({{ $state['id'] }})"
                                                    class="ms-1"
                                                    style="cursor: pointer; font-weight: bold;">×</span>
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Select States</span>
                                @endif
                            </div>

                            @if ($stateDropdownOpen && !$viewMode)
                                <div class="position-absolute bg-white border rounded w-100 mt-1 shadow-sm"
                                    style="max-height: 180px; overflow-y: auto; z-index: 1000; font-size: 14px;">
                                    @foreach ($states as $s)
                                        <div class="p-2"
                                            wire:click="selectState({{ $s->id }}, '{{ $s->name }}')"
                                            style="cursor: pointer; {{ in_array($s->id, $selectedStates) ? 'background: #e0e0e0; color: #333;' : '' }}">
                                            {{ $s->name }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @error('selectedStates')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Cities Dropdown (Multi-select for Branch Locations) -->
                    <div class="mb-3">
                        <label class="form-label">Branch Locations (Cities) <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <div class="form-control d-flex flex-wrap align-items-center p-1"
                                @if (!$viewMode) wire:click="toggleCityDropdown" @endif
                                style="min-height: 35px; font-size: 14px; {{ $viewMode ? 'background: #e9ecef; cursor: not-allowed;' : '' }}">

                                @if (!empty($selectedCitiesData))
                                    @foreach ($selectedCitiesData as $city)
                                        <span class="badge px-2 py-1 me-1 d-flex align-items-center"
                                            style="background: #f0f0f0; color: #333; font-size: 12px; border-radius: 5px;">
                                            {{ $city['name'] }}
                                            @if (!$viewMode)
                                                <span wire:click.stop="removeCity({{ $city['id'] }})"
                                                    class="ms-1"
                                                    style="cursor: pointer; font-weight: bold;">×</span>
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Select Branches</span>
                                @endif
                            </div>

                            @if ($cityDropdownOpen && !$viewMode)
                                <div class="position-absolute bg-white border rounded w-100 mt-1 shadow-sm"
                                    style="max-height: 180px; overflow-y: auto; z-index: 1000; font-size: 14px;">
                                    @foreach ($cities as $c)
                                        <div class="p-2"
                                            wire:click="selectCity({{ $c->id }}, '{{ $c->name }}')"
                                            style="cursor: pointer; {{ in_array($c->id, $selectedCities) ? 'background: #e0e0e0; color: #333;' : '' }}">
                                            {{ $c->name }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @error('selectedCities')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Time Zone & Currency -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Time Zone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="time_zone" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Currency <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="currency" readonly>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Company Present Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" wire:model="company_present_address" {{ $viewMode ? 'disabled' : '' }}></textarea>
                        @error('company_present_address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
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
                        @error('company_permanent_address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">PF No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pf_no" wire:model="pf_no"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                pattern="[A-Z0-9]*" maxlength="15" placeholder="Enter PF No "
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('pf_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">TAN No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tan_no" wire:model="tan_no"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                pattern="[A-Z]{4}[0-9]{5}[A-Z]{1}" maxlength="10" placeholder="Enter TAN No"
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('tan_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">PAN No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pan_no" wire:model="pan_no"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10" placeholder="Enter PAN No"
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('pan_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ESI No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="esi_no" wire:model="esi_no"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" pattern="[0-9]*"
                                maxlength="17" placeholder="Enter ESI No " {{ $viewMode ? 'disabled' : '' }}>
                            @error('esi_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="row mt-3">
                        <!-- LIN No -->
                        <div class="col-md-6">
                            <label class="form-label">LIN No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lin_no" wire:model="lin_no"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" pattern="[0-9]{8}"
                                maxlength="8" placeholder="Enter LIN No " {{ $viewMode ? 'disabled' : '' }}>
                            @error('lin_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- GST No -->
                        <div class="col-md-6">
                            <label class="form-label">GST No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="gst_no" wire:model="gst_no"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                                pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{1}Z[0-9A-Z]{1}" maxlength="15"
                                placeholder="Enter GST No" {{ $viewMode ? 'disabled' : '' }}>
                            @error('gst_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="company_registration_no"
                                wire:model="company_registration_no"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-\/]/g, '')"
                                pattern="[A-Z0-9\-\/]+" maxlength="20" placeholder="Enter Registration No"
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('company_registration_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Reg Date <span class="text-danger">*</span></label>
                            <input type="date" id="company_registration_date" class="form-control"
                                wire:model="company_registration_date" max="{{ date('Y-m-d') }}"
                                {{ $viewMode ? 'disabled' : '' }}>
                            @error('company_registration_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Company Website</label>
                        <input type="text" class="form-control"
                            wire:model="company_website"{{ $viewMode ? 'disabled' : '' }}>
                        @error('company_website')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-label">Company Logo <span class="text-danger">*</span></label>

                        <!-- File Input -->
                        <input type="file" class="form-control" wire:model="company_logo"
                            {{ $viewMode ? 'disabled' : '' }}>

                        <!-- Preview Image -->
                        @if ($company_logo)
                            <div class="mt-2">
                                @php
                                    $mimeType =
                                        $company_logo instanceof \Illuminate\Http\UploadedFile
                                            ? $company_logo->getMimeType()
                                            : 'image/jpeg'; // Default to JPEG if stored in DB
                                @endphp

                                @if ($company_logo instanceof \Illuminate\Http\UploadedFile)
                                    <img src="data:{{ $mimeType }};base64,{{ base64_encode(file_get_contents($company_logo->getRealPath())) }}"
                                        alt="Company Logo" class="img-thumbnail" width="150">
                                @else
                                    <img src="data:{{ $mimeType }};base64,{{ $company_logo }}"
                                        alt="Company Logo" class="img-thumbnail" width="150">
                                @endif
                            </div>
                        @endif

                        <!-- Validation Error -->
                        @error('company_logo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3 mt-3 text-center">
                        @if ($viewMode)
                            <button type="button" class="btn btn-secondary" wire:click="resetForm">Close</button>
                        @else
                            <button type="submit" class="btn btn-primary">
                                <span>{{ $company_id ? 'Update' : 'Save' }}</span>
                            </button>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        <!-- Right side: Saved Companies (Only Show When List is Not Empty) -->
        @if ($savedCompanies->count() > 0 || !empty($search))
            <div class="card shadow-lg col-md-6 col-lg-5 col-xl-8 ms-3" style="max-height: 400px; overflow: hidden;">
                <div class="card-body">
                    <h5 class="mb-3">Saved Companies</h5>
                    <div class="w-25">
                        <input type="text" class="form-control form-control-sm mb-1" placeholder="Search..."
                            wire:model.live="search">
                    </div>

                    <!-- Table -->
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered table-striped company-table">
                            <thead class="table-primary position-sticky top-0">
                                <tr>
                                    <th class="text-nowrap" wire:click="sortBy('id')" style="cursor: pointer;">
                                        S.NO
                                        @if ($sortField == 'id')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap">Company Logo</th>
                                    <th class="text-nowrap" wire:click="sortBy('company_name')"
                                        style="cursor: pointer;">
                                        Company Name
                                        @if ($sortField == 'company_name')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap" wire:click="sortBy('company_registration_no')"
                                        style="cursor: pointer;">
                                        Registration No
                                        @if ($sortField == 'company_registration_no')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap" wire:click="sortBy('pf_no')" style="cursor: pointer;">
                                        P.F No
                                        @if ($sortField == 'pf_no')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap" wire:click="sortBy('tan_no')" style="cursor: pointer;">
                                        TAN No
                                        @if ($sortField == 'tan_no')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap" wire:click="sortBy('lin_no')" style="cursor: pointer;">
                                        LIN No
                                        @if ($sortField == 'lin_no')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap" wire:click="sortBy('gst_no')" style="cursor: pointer;">
                                        GST No
                                        @if ($sortField == 'gst_no')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap" wire:click="sortBy('pan_no')" style="cursor: pointer;">
                                        PAN No
                                        @if ($sortField == 'pan_no')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap" wire:click="sortBy('esi_no')" style="cursor: pointer;">
                                        ESI No
                                        @if ($sortField == 'esi_no')
                                            <i
                                                class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                        @endif
                                    </th>
                                    <th class="text-nowrap">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($savedCompanies as $key => $company)
                                    <tr wire:key="company-{{ $company->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if (!empty($company->company_logo))
                                                @php
                                                    $decodedLogo = base64_decode($company->company_logo);
                                                    $mimeType = $decodedLogo
                                                        ? (new finfo(FILEINFO_MIME_TYPE))->buffer($decodedLogo)
                                                        : 'image/jpeg';
                                                @endphp
                                                <img src="data:{{ $mimeType }};base64,{{ $company->company_logo }}"
                                                    alt="Company Logo" class="company-logo">
                                            @else
                                                <span>No Logo</span>
                                            @endif
                                        </td>
                                        <td>{{ $company->company_name }}</td>
                                        <td>{{ $company->company_registration_no }}</td>
                                        <td>{{ $company->pf_no }}</td>
                                        <td>{{ $company->tan_no }}</td>
                                        <td>{{ $company->lin_no }}</td>
                                        <td>{{ $company->gst_no }}</td>
                                        <td>{{ $company->pan_no }}</td>
                                        <td>{{ $company->esi_no }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <i class="bi bi-eye text-primary"
                                                    wire:click="viewCompanyInfo({{ $company->id }})"
                                                    wire:key="view-{{ $company->id }}" style="cursor: pointer;"></i>

                                                <i class="bi bi-pencil text-warning"
                                                    wire:click="editCompanyInfo({{ $company->id }})"
                                                    wire:key="edit-{{ $company->id }}"
                                                    style="cursor: pointer;"></i>

                                                <i class="bi bi-trash text-danger"
                                                    wire:click="confirmDelete({{ $company->id }})"
                                                    wire:key="delete-{{ $company->id }}"
                                                    style="cursor: pointer;"></i>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    @if (!empty($search))
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">
                                                No results found for "{{ $search }}"
                                            </td>
                                        </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-3">
                        {{ $savedCompanies->links() }}
                    </div>
                </div>
            </div>
        @else
            <!-- No Data Available Box -->
            <div class="card shadow-lg col-md-6 col-lg-5 col-xl-8 ms-3" style="max-height: 200px; overflow: hidden;">
                <div class="card text-center shadow p-4">
                    <div class="card-body">
                        <i class="bi bi-database-x text-danger" style="font-size: 50px;"></i>
                        <h5 class="mt-3 text-muted">No Data Available</h5>
                        <p class="text-muted">There are no saved companies yet. Add new companies to see the list here.
                        </p>
                    </div>
                </div>
            </div>
        @endif



    </div>

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="modal-title mb-0">Confirm Deletion</h5>
                        <button type="button" wire:click="$set('showDeleteModal', false)"
                            style="font-size: 20px; font-weight: bold; background: none; border: none; color: white; cursor: pointer; padding: 0 10px;">
                            &times;
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="modal-body text-center">
                        <p class="mb-0">Are you sure you want to delete this company?</p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="cancel-btn" wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>

                        <button type="button" class="submit-btn" wire:click="deleteCompanyInfo">
                            Yes, Delete
                        </button>

                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
<script>
    // function initSelect2(selector, placeholderText) {
    //     $(selector).select2({
    //         placeholder: placeholderText, // Dynamic placeholder
    //         allowClear: false, // No "X" button
    //         minimumResultsForSearch: 0, // Always show search box
    //     }).on('change', function() {
    //         @this.set($(this).attr('wire:model'), $(this).val()); // Sync with Livewire dynamically
    //     });
    // }

    // document.addEventListener('DOMContentLoaded', function() {
    //     initSelect2('.selectIndustry', 'Select an industry');
    //     initSelect2('.selectCompanyType', 'Select a Company Type');
    //     initSelect2('.selectCountry', 'Select a Country');
    //     initSelect2('.selectStates', 'Select States');
    // });



    // document.addEventListener('DOMContentLoaded', function() {
    //     flatpickr("#company_registration_date", {
    //         enableTime: false,
    //         dateFormat: "Y-m-d",
    //         maxDate: "today", // Prevents selecting future dates
    //         onChange: function(selectedDates, dateStr) {
    //             @this.set('company_registration_date', dateStr);
    //         }
    //     });
    // });
</script>
