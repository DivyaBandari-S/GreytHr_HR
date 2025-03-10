<div class="px-4">
    @if($showOverViewSection)
    <div class="overview-section">
        <div class="row main-overview-help py-3">
            <div class="col-md-11 col-10 d-flex flex-column">
                <p class="main-overview-text mb-1">The <span class="msgHeighlighter">Arrears</span> calculator determines the difference between the revised pay and actual pay for the arrears period and then updates the arrears components. Various concerns, such as <span class="msgHeighlighter">taxation</span> and <span class="msgHeighlighter">statutory deductions</span>, are automatically handled. Click <span class="msgHeighlighter">+Pay Arrears</span> to calculate arrears for a set of employees.</p>
            </div>
            <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                <span>Hide Help</span>
            </div>
        </div>
        <div class="row d-flex align-items-center m-0 p-0">
            <div class="col-md-9 py-2 px-0">
                <div class="filters">
                    <div class="form-group dropdown">
                        <select class="form-control">
                            <option value="All">Filter : Mar 2025</option>
                            <option value="Monthly">Apr 2025</option>
                            <option value="Yearly">May 2025</option>
                        </select>
                    </div>
                    <div class="form-group dropdown">
                        <select class="form-control">
                            <option value="All">Employee Type : All</option>
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="contract"> Contract</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3 py-2 px-0">
                <div class="d-flex justify-content-end">
                    <button class="cancel-btn" wire:click="toggleSection"> + Pay Arrears</button>
                </div>
            </div>
        </div>
        <div class="row m-0 p-0">
            <div class="table-responsive p-0 rounded border ">
                <table class="table arrears-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee No</th>
                            <th>Employee Name</th>
                            <th>Payroll</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>1234</td>
                            <td>abc</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <div class="d-flex gap-3">
                                    <i class="fas fa-edit text-info"></i>
                                    <i class="fas fa-trash text-danger"></i>
                                    <i class="fas fa-download text-secondary"></i>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>1234</td>
                            <td>abc</td>
                            <td>-</td>
                            <td>-</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex align-items-center justify-content-center gap-3 mt-3 mb-3">
                <button type="button" class="submit-btn">Process</button>
                <button type="button" class="submit-btn">Process All</button>
            </div>
        </div>
    </div>
    @endif

    @if($showPayArrearsSection)
    <div class="payArreares">
        <div class="progress-container">
            <div class="progress-line"></div>

            <!-- Step 1 -->
            <div class="progress-step">
                <div class="circle {{ $currentStep >= 1 ? ($currentStep == 1 ? 'active' : 'completed') : 'pending' }}"></div>
                <div class="label">Arrear Effective From</div>
            </div>

            <div class="progress-step">
                <div class="circle {{ $currentStep >= 2 ? ($currentStep == 2 ? 'active' : 'completed') : 'pending' }}"></div>
                <div class="label">Employees</div>
            </div>
            <!-- Step 2 -->
            <div class="progress-step">
                <div class="circle {{ $currentStep >= 3 ? ($currentStep == 3 ? 'active' : 'completed') : 'pending' }}"></div>
                <div class="label">Process and View</div>
            </div>
        </div>
        <div>
            @if($currentStep == 1)
            <div class="mb-2 mt-2">
                <strong class="mb-3"> Step 1: Arrear Effective From</strong>
            </div>
            <div>
                <div class="form-group form-grid">
                    <label for="payroll_month">Payroll Month</label>
                    <strong class="normalText" id="payroll_month">Jul 2024</strong>
                </div>
                <div class="form-group form-grid">
                    <label for="effective_date">Arrear Effective From</label>
                    <input type="date" class="form-control" id="effective_date">
                </div>
                <div class="form-group form-grid">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                </div>
            </div>
            <div class="buttons-container d-flex py-1 gap-2">
                <button class="cancel-btn" wire:click="toggleUploadBtn">Cancel</button>
                <button class="submit-btn px-3" wire:click="nextStep">Next</button>
            </div>
            @elseif($currentStep == 2)
            <div>
                <div class="mb-2">
                    <strong class="mb-3"> Step 2: Employees </strong>
                </div>
                <div class="form-group form-grid">
                    <label for="payroll_month">Payroll Month</label>
                    <strong class="normalText" id="payroll_month">Jul 2024</strong>
                </div>
                <div class="form-group form-grid">
                    <label for="effective_date">Arrear Effective From</label>
                    <input type="date" class="form-control" id="effective_date">
                </div>
                <div class="row d-flex align-items-center m-0 p-0">
                    <div class="col-md-9 py-2 px-0">
                        <div class="filters">
                            <div class="form-group dropdown">
                                <select class="form-control">
                                    <option value="All">Employee Type : All</option>
                                    <option value="full-time">Full Time</option>
                                    <option value="part-time">Part Time</option>
                                    <option value="contract"> Contract</option>
                                </select>
                            </div>
                            <div>
                                <input type="text" class="form-control py-2" placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 py-2 px-0">
                    </div>
                </div>
                <div class="row m-0 p-0">
                    <div class="col-md-5 p-0">
                        <table class="table table-bordered arrears-table table-responsive">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Employee No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example row 1 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>12345</td>
                                    <td>John Doe</td>
                                </tr>
                                <!-- Example row 2 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>67890</td>
                                    <td>Jane Smith</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-2 p-0 d-flex justify-content-center align-items-center">
                        <div class="send-emp-data gap-2 d-flex flex-column justify-content-center align-items-center">
                            <button type="button" class="cancel-btn">-></button>
                            <button type="button" class="cancel-btn"><-</button>
                        </div>
                    </div>
                    <div class="col-md-5 p-0">
                        <table class="table table-bordered arrears-table table-responsive">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Employee No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example row 1 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>12345</td>
                                    <td>John Doe</td>
                                </tr>
                                <!-- Example row 2 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>67890</td>
                                    <td>Jane Smith</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="buttons-container d-flex py-1 gap-3">
                <button class="cancel-btn" wire:click="gotoBack">Back</button>
                <button class="submit-btn px-3" wire:click="nextStep">Next</button>
            </div>
            @elseif($currentStep == 3)
            <div>
                <div class="mb-2">
                    <strong class="mb-3"> Step 3: Process and View </strong>
                </div>
                <div class="form-group form-grid">
                    <label for="payroll_month">Payroll Month</label>
                    <strong class="normalText">Jul 2024</strong>
                </div>
                <div class="form-group form-grid">
                    <label for="effective_date">Arrear Effective From</label>
                    <input type="date" class="form-control" id="effective_date">
                </div>
                <div class="row d-flex align-items-center m-0 p-0">
                    <div class="col-md-9 py-2 px-0">
                        <div class="filters">
                            <div class="form-group dropdown">
                                <select class="form-control">
                                    <option value="All">Employee Type : All</option>
                                    <option value="full-time">Full Time</option>
                                    <option value="part-time">Part Time</option>
                                    <option value="contract"> Contract</option>
                                </select>
                            </div>
                            <div>
                                <input type="text" class="form-control py-2" placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 py-2 px-0">
                    </div>
                </div>
                <div class="row m-0 p-0">
                    <div class="col-md-5 p-0">
                        <table class="table table-bordered arrears-table table-responsive">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Employee No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example row 1 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>12345</td>
                                    <td>John Doe</td>
                                </tr>
                                <!-- Example row 2 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>67890</td>
                                    <td>Jane Smith</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-2 p-0 d-flex justify-content-center align-items-center">
                        <div class="send-emp-data gap-2 d-flex flex-column justify-content-center align-items-center">
                            <button type="button" class="cancel-btn">-></button>
                            <button type="button" class="cancel-btn"><-</button>
                        </div>
                    </div>
                    <div class="col-md-5 p-0">
                        <table class="table table-bordered arrears-table table-responsive">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Employee No</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example row 1 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>12345</td>
                                    <td>John Doe</td>
                                </tr>
                                <!-- Example row 2 -->
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="emp-checkbox">
                                        </div>
                                    </td>
                                    <td>67890</td>
                                    <td>Jane Smith</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="buttons-container d-flex py-1 gap-3">
                <button class="cancel-btn" wire:click="gotoBack">Back</button>
                <button class="submit-btn" wire:click="gotoBack"><i class="fa-solid fa-check"></i>Finish</button>
                <button class="cancel-btn px-3">Cancel</button>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>