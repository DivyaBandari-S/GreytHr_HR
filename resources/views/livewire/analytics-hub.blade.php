<div>
    <div class="analytic-main-container">
        <div class="analytic-header">
            <span class="analytic-left">Recent</span>
            <span class="analytic-right" wire:click="analyticsHubList">View All</span>
        </div>
        <div class="analytic-content-row">
            <div class="analytic-content-box @if ($selectedCard == 'Basic Information') analytic-highlighted @endif"
                wire:click="selectCard('Basic Information')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">Basic Information</span>
            </div>
            <div class="analytic-content-box @if ($selectedCard == 'Personal Information(PII Data)') analytic-highlighted @endif"
                wire:click="selectCard('Personal Information(PII Data)')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">Personal Information(PII Data)</span>
            </div>
            <div class="analytic-content-box @if ($selectedCard == 'All Employee Info') analytic-highlighted @endif"
                wire:click="selectCard('All Employee Info')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">All Employee Info</span>
            </div>
            <div class="analytic-content-box @if ($selectedCard == 'Gender-wise Headcount') analytic-highlighted @endif"
                wire:click="selectCard('Gender-wise Headcount')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">Gender-wise Headcount</span>
            </div>
            <div class="analytic-content-box @if ($selectedCard == 'Recent Resignees') analytic-highlighted @endif"
                wire:click="selectCard('Recent Resignees')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">Recent Resignees</span>
            </div>
        </div>
    </div>
    <div class="analytic-container1">
        <div class="analytic-header1">
            <div class="analytic-left">
                {{ $selectedCard }}
            </div>
            <div class="analytic-header-right">
                <button class="submit-btn" wire:click="addEmployee">Add Employee</button>
            </div>
        </div>
        <hr>
        

        <div >
            @if ($selectedCard == 'Basic Information')
            <div class="analytic-search-bar">
                <div class="analytic-search-wrapper">
                    <input type="text" wire:input="filterBasicInformation"  class="analytic-search" wire:model="basicSearch" placeholder="Search...">
                    <i class="analytic-search-icon bx bx-search" wire:click="filterBasicInformation"></i>
                </div>
                <div class="d-flex justify-content-center">
                    <span class="analytic-restore-text">Restore</span>
                    <i class="analytic-restore-icon bx bxs-cloud"></i>
                </div>
            </div>
            <div class="analytic-table-container">
                <table class="analytic-table">
                    <thead>
                        <tr>
                            <th>Emp ID <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                            <th>Emp Name</th>
                            <th>DOJ</th>
                            <th>Gender</th>
                            <th>Email ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($employeesData->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <img class="analytic-no-items-found"
                                            src="https://media.istockphoto.com/id/1357284048/vector/no-item-found-vector-flat-icon-design-illustration-web-and-mobile-application-symbol-on.jpg?s=612x612&w=0&k=20&c=j0V0ww6uBl1LwQLH0U9L7Zn81xMTZCpXPjH5qJo5QyQ="
                                            alt="No items found">
                                    </td>
                                </tr>

                        <!-- Rows will be dynamically generated -->
                        @else
                        @foreach ($employeesData as $employee)
                            <tr>
                                <td class="analytic-grey-text">{{ $employee->emp_id }}</td>
                                <td class="analytic-blue-text"> {{ ucfirst(strtolower($employee->first_name)) }}
                                    {{ ucfirst(strtolower($employee->last_name)) }}</td>
                                <td class="analytic-grey-text">
                                    @if ($employee->hire_date)
                                        {{ \Carbon\Carbon::parse($employee->hire_date)->format('d M Y') }}
                                    @else
                                    @endif
                                </td>
                                <td class="analytic-grey-text">{{ $employee->gender }}</td>
                                <td class="analytic-grey-text">{{ $employee->email }}</td>
                            </tr>
                        @endforeach
                        @endif
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </div>
            @elseif($selectedCard == 'Personal Information(PII Data)')
            <div class="analytic-search-bar">
                <div class="analytic-search-wrapper">
                    <input type="text" wire:input="filterPersonalInformation"  class="analytic-search" wire:model="piSearch" placeholder="Search...">
                    <i class="analytic-search-icon bx bx-search" wire:click="filterPersonalInformation"></i>
                </div>
                <div class="d-flex justify-content-center">
                    <span class="analytic-restore-text">Restore</span>
                    <i class="analytic-restore-icon bx bxs-cloud"></i>
                </div>
            </div>
            <div class="analytic-table-container">
                <table class="analytic-table">
                    <thead>
                        <tr>
                            <th>Emp Id <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                            <th>Emp Name</th>
                            <th>DOJ</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Age Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($personalInformationData->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">
                                <img class="analytic-no-items-found"
                                    src="https://media.istockphoto.com/id/1357284048/vector/no-item-found-vector-flat-icon-design-illustration-web-and-mobile-application-symbol-on.jpg?s=612x612&w=0&k=20&c=j0V0ww6uBl1LwQLH0U9L7Zn81xMTZCpXPjH5qJo5QyQ="
                                    alt="No items found">
                            </td>
                        </tr>

                @else
                        <!-- Rows will be dynamically generated -->
                        @foreach ($personalInformationData as $employee)
                            @php
                                $personalInfo = $employee->empPersonalInfo; // Access the relationship once
                            @endphp
                            <tr>
                                <td class="analytic-grey-text">{{ $employee->emp_id }}</td>
                                    <td class="analytic-blue-text">{{ ucfirst(strtolower($employee->first_name)) }}
                                        {{ ucfirst(strtolower($employee->last_name)) }}</td>
                                <td class="analytic-grey-text">
                                    @if ($employee->hire_date)
                                        {{ \Carbon\Carbon::parse($employee->hire_date)->format('d M Y') }}
                                    @else
                                    @endif
                                </td>
                                <td class="analytic-grey-text">{{ $employee->gender ?? '' }}</td>
                                <td class="analytic-grey-text">
                                    @if ($personalInfo && $personalInfo->date_of_birth)
                                        {{ \Carbon\Carbon::parse($personalInfo->date_of_birth)->format('d M Y') }}
                                    @else
                                    @endif
                                </td>
                                
                                <td class="analytic-grey-text">
                                    @if ($personalInfo && $personalInfo->date_of_birth)
                                        @php
                                            $dateOfBirth = \Carbon\Carbon::parse($personalInfo->date_of_birth);
                                            $age = $dateOfBirth->age; // Calculate age
                                
                                            // Determine age range
                                            if ($age >= 55) {
                                                $ageRange = '>= 55';
                                            } elseif ($age >= 45) {
                                                $ageRange = '>= 45';
                                            } elseif ($age >= 35) {
                                                $ageRange = '>= 35';
                                            } elseif ($age >= 25) {
                                                $ageRange = '>= 25';
                                            } else {
                                                $ageRange = '< 25';
                                            }
                                        @endphp
                                
                                        {{ $ageRange }}
                                    @else
                                        
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @endif
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </div>
            @elseif($selectedCard == 'All Employee Info')
            <div class="analytic-search-bar">
                <div class="analytic-search-wrapper">
                    <input type="text" wire:input="filterAllInfo" class="analytic-search" wire:model="allInfoSearch" placeholder="Search...">
                    <i class="analytic-search-icon bx bx-search" wire:click="filterAllInfo"></i>
                </div>
                <div class="d-flex justify-content-center">
                    <span class="analytic-restore-text">Restore</span>
                    <i class="analytic-restore-icon bx bxs-cloud"></i>
                </div>
            </div>
            <div class="analytic-table-container">
            <table class="analytic-table">
                <thead>
                    <tr>
                        <th>Emp ID <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                        <th>Emp Name</th>
                        <th>DOJ</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Email ID</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($allInfoData->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center">
                            <img class="analytic-no-items-found"
                                src="https://media.istockphoto.com/id/1357284048/vector/no-item-found-vector-flat-icon-design-illustration-web-and-mobile-application-symbol-on.jpg?s=612x612&w=0&k=20&c=j0V0ww6uBl1LwQLH0U9L7Zn81xMTZCpXPjH5qJo5QyQ="
                                alt="No items found">
                        </td>
                    </tr>

            <!-- Rows will be dynamically generated -->
            @else

                    @foreach ($allInfoData as $employee)
                    @php
                        $personalInfo = $employee->empPersonalInfo; // Access the relationship once
                    @endphp
                        <tr>
                            <td class="analytic-grey-text">{{ $employee->emp_id }}</td>
                            <td class="analytic-blue-text"> {{ ucfirst(strtolower($employee->first_name)) }}
                                {{ ucfirst(strtolower($employee->last_name)) }}</td>
                            <td class="analytic-grey-text">
                                @if ($employee->hire_date)
                                    {{ \Carbon\Carbon::parse($employee->hire_date)->format('d M Y') }}
                                @else
                                @endif
                            </td>
                            <td class="analytic-grey-text">{{ $employee->gender }}</td>
                            <td class="analytic-grey-text">
                                @if ($personalInfo && $personalInfo->date_of_birth)
                                    {{ \Carbon\Carbon::parse($personalInfo->date_of_birth)->format('d M Y') }}
                                @else
                                @endif
                            </td>
                            <td class="analytic-grey-text">{{ $employee->email }}</td>
                        </tr>
                    @endforeach
                    @endif
                    <!-- More rows as needed -->
                </tbody>
            </table>
        </div>
            @elseif($selectedCard == 'Gender-wise Headcount')
            <div class="analytic-search-bar">
                <div class="analytic-search-wrapper">
                    <input type="text" wire:input="filterGenderWise" class="analytic-search" wire:model="genderSearch" placeholder="Search...">
                    <i class="analytic-search-icon bx bx-search" wire:click="filterGenderWise"></i>
                </div>
                <div class="d-flex justify-content-center">
                    <span class="analytic-restore-text">Restore</span>
                    <i class="analytic-restore-icon bx bxs-cloud"></i>
                </div>
            </div>
            <div class="analytic-table-container">
            <table class="analytic-table">
                <thead>
                    <tr>
                        <th>Group <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                        <th>Count(Emp ID) <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="analytic-grey-text">Others</td>
                        <td class="analytic-grey-text">{{ $genderCounts['Others'] }}</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">Male</td>
                        <td class="analytic-grey-text">{{ $genderCounts['Male'] }}</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">Female</td>
                        <td class="analytic-grey-text">{{ $genderCounts['Female'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
            @elseif($selectedCard == 'Recent Resignees')
            <div class="analytic-search-bar">
                <div class="analytic-search-wrapper">
                    <input type="text" wire:input="filterResignees" class="analytic-search" wire:model="resigneesSearch" placeholder="Search...">
                    <i class="analytic-search-icon bx bx-search" wire:click="filterResignees"></i>
                </div>
                <div class="d-flex justify-content-center">
                    <span class="analytic-restore-text">Restore</span>
                    <i class="analytic-restore-icon bx bxs-cloud"></i>
                </div>
            </div>
            <div class="analytic-table-container">
            <table class="analytic-table">
                <thead>
                    <tr>
                        <th>Emp ID <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                        <th>Emp Name</th>
                        <th>DOJ</th>
                        <th>Gender</th>
                        <th>LWD</th>
                        <th>Status</th>
                        <th>Manager ID</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($resigneesData->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">
                            <img class="analytic-no-items-found"
                                src="https://media.istockphoto.com/id/1357284048/vector/no-item-found-vector-flat-icon-design-illustration-web-and-mobile-application-symbol-on.jpg?s=612x612&w=0&k=20&c=j0V0ww6uBl1LwQLH0U9L7Zn81xMTZCpXPjH5qJo5QyQ="
                                alt="No items found">
                        </td>
                    </tr>

            <!-- Rows will be dynamically generated -->
            @else

                    @foreach ($resigneesData as $employee)
                    @php
                        $empResignations = $employee->empResignations; // Access the relationship once
                    @endphp
                        <tr>
                            <td class="analytic-grey-text">{{ $employee->emp_id }}</td>
                            <td class="analytic-red-text"> {{ ucfirst(strtolower($employee->first_name)) }}
                                {{ ucfirst(strtolower($employee->last_name)) }}</td>
                            <td class="analytic-grey-text">
                                @if ($employee->hire_date)
                                    {{ \Carbon\Carbon::parse($employee->hire_date)->format('d M Y') }}
                                @else
                                @endif
                            </td>
                            <td class="analytic-grey-text">{{ $employee->gender }}</td>
                            <td class="analytic-grey-text">
                                @if ($empResignations && $empResignations->last_working_day)
                                    {{ \Carbon\Carbon::parse($empResignations->last_working_day)->format('d M Y') }}
                                @else
                                @endif
                            </td>
                            <td class="analytic-grey-text">{{ $empResignations->status }}</td>
                            <td class="analytic-grey-text">{{ $employee->manager_id }}</td>
                        </tr>
                    @endforeach
                    <!-- More rows as needed -->
                    @endif
                </tbody>
            </table>
        </div>
            @endif
        </div>
    </div>
</div>
