<div>
    <div class="analytic-main-container">
        <div class="analytic-header">
            <span class="analytic-left">Recent</span>
            <span class="analytic-right" wire:click="analyticsHubList">View All</span>
        </div>
        <div class="analytic-content-row">
            <div class="analytic-content-box @if($selectedCard == 'Basic Information') analytic-highlighted @endif" wire:click="selectCard('Basic Information')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">Basic Information</span>
            </div>
            <div class="analytic-content-box @if($selectedCard == 'Personal Information(PII Data)') analytic-highlighted @endif" wire:click="selectCard('Personal Information(PII Data)')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">Personal Information(PII Data)</span>
            </div>
            <div class="analytic-content-box @if($selectedCard == 'All Employee Info') analytic-highlighted @endif" wire:click="selectCard('All Employee Info')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">All Employee Info</span>
            </div>
            <div class="analytic-content-box @if($selectedCard == 'Gender-wise Headcount') analytic-highlighted @endif" wire:click="selectCard('Gender-wise Headcount')">
                <span class="analytic-icon">&#9734;</span>
                <span class="analytic-text">Gender-wise Headcount</span>
            </div>
        </div>
    </div>
    <div class="analytic-container1">
        <div class="analytic-header1">
            <div class="analytic-left">
                {{ $selectedCard }}
            </div>
            <div class="analytic-header-right">
                <button class="submit-btn">Add Employee</button>
            </div>
        </div>
        <hr>
        <div class="analytic-search-bar">
            <div class="analytic-search-wrapper">
                <input type="text" placeholder="Search...">
                <i class="analytic-search-icon fas fa-search"></i>
            </div>
            <div>
                <span class="analytic-restore-text">Restore</span>
                <i class="analytic-restore-icon fas fa-cloud"></i>
            </div>
        </div>

        <div class="analytic-table-container">
            @if($selectedCard == 'Basic Information')
            <table class="analytic-table">
                <thead>
                    <tr>
                        <th>Emp ID <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                        <th>Emp Name</th>
                        <th>Date of Joining</th>
                        <th>Gender</th>
                        <th>Email ID</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically generated -->
                    <tr>
                        <td class="analytic-grey-text">T0001</td>
                        <td class="analytic-blue-text">John Doe</td>
                        <td class="analytic-grey-text">2023-01-01</td>
                        <td class="analytic-grey-text">Male</td>
                        <td class="analytic-grey-text">john.doe@example.com</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">T00012</td>
                        <td class="analytic-blue-text">Jane Smith</td>
                        <td class="analytic-grey-text">2023-02-15</td>
                        <td class="analytic-grey-text">Female</td>
                        <td class="analytic-grey-text">jane.smith@example.com</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">T00012</td>
                        <td class="analytic-blue-text">Jane Smith</td>
                        <td class="analytic-grey-text">2023-02-15</td>
                        <td class="analytic-grey-text">Female</td>
                        <td class="analytic-grey-text">jane.smith@example.com</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">T00012</td>
                        <td class="analytic-blue-text">Jane Smith</td>
                        <td class="analytic-grey-text">2023-02-15</td>
                        <td class="analytic-grey-text">Female</td>
                        <td class="analytic-grey-text">jane.smith@example.com</td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
            @elseif($selectedCard == 'Personal Information(PII Data)')
            <table class="analytic-table">
                <thead>
                    <tr>
                        <th>Name <i class="fa-sharp fa-solid fa-arrow-up"></i></th>
                        <th>Date of Birth</th>
                        <th>Mobile No</th>
                        <th>Address</th>
                        <th>Blood Group</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be dynamically generated -->
                    <tr>
                        <td class="analytic-grey-text">John Doe</td>
                        <td class="analytic-blue-text">1999-01-09</td>
                        <td class="analytic-grey-text">9876543210</td>
                        <td class="analytic-grey-text">Hyderabad</td>
                        <td class="analytic-grey-text">o+</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">John Doe</td>
                        <td class="analytic-blue-text">1999-01-09</td>
                        <td class="analytic-grey-text">9876543210</td>
                        <td class="analytic-grey-text">Hyderabad</td>
                        <td class="analytic-grey-text">AB+</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">John Doe</td>
                        <td class="analytic-blue-text">1999-01-09</td>
                        <td class="analytic-grey-text">9876543210</td>
                        <td class="analytic-grey-text">Hyderabad</td>
                        <td class="analytic-grey-text">B+</td>
                    </tr>
                    <tr>
                        <td class="analytic-grey-text">John Doe</td>
                        <td class="analytic-blue-text">1999-01-09</td>
                        <td class="analytic-grey-text">9876543210</td>
                        <td class="analytic-grey-text">Hyderabad</td>
                        <td class="analytic-grey-text">o+</td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
            @elseif($selectedCard == 'All Employee Info')
            <div class="p-3">
                <p>This is All Employee Info Section</p>
            </div>
            @elseif($selectedCard == 'Gender-wise Headcount')
            <div class="p-3">
                <p>This is Gender-wise Headcount section</p>
            </div>
            @endif
        </div>
    </div>
</div>