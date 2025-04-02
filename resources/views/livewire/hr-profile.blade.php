<div class="p-2 m-0 main-overview-container">
    <style>
        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .hr-profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }

        .settings-custom-placeholder::placeholder {
            font-size: var(--normal-font-size);
        }

        .setting-nickname-input {
            font-size: var(--normal-font-size);
            margin-top: 5px;
        }

        .setting-nickname-value {
            color: var(--sub-heading-color);
            font-size: var(--normal-font-size);
            font-weight: 500;
        }

        .setting-timezone-container {
            font-size: var(--normal-font-size);
        }

        .setting-timezone-text {
            color: var(--label-color);
        }

        .setting-timezone-icons-container {
            margin-top: 10px;
        }

        .setting-timezone-select {
            width: 150px;
            font-size: var(--normal-font-size);
        }

        .setting-timezone-value {
            color: var(--sub-heading-color);
            font-size: var(--normal-font-size);
            font-weight: 500;
        }

        .setting-biography-textarea {
            width: 100%;
            font-size: var(--normal-font-size);
        }

        .setting-password-modal-input {
            font-size: var(--normal-font-size);
        }

        .table th {
            /* background-color: #f8f9fa; */
            background-color: var(--main-table-heading-bg-color);
            font-weight: 600;
        }
    </style>
    <div>
        @if ($showHelp == false)
            <div class="row main-overview-help">
                <div class="col-md-11 col-10 d-flex flex-column">
                    <p class="main-overview-text">You can setup your account from this page. </p>
                    <p class="main-overview-text">Explore greytHR by <span class="main-overview-highlited-text">
                            Help-Doc</span>, watching<span class="main-overview-highlited-text"> How-to Videos</span>
                        and<span class="main-overview-highlited-text"> FAQ</span>.</p>
                </div>
                <div class="hide-main-overview-help col-md-1 col-2 d-flex align-items-start">
                    <span wire:click="hideHelp">Hide Help</span>
                </div>
            </div>
        @else
            <div class="row main-overview-help">
                <div class="col-11 d-flex flex-column">
                    <p class="main-overview-text">You can setup your account from this page. </p>

                </div>
                <div class="hide-main-overview-help col-1">
                    <span wire:click="showhelp">Show Help</span>
                </div>
            </div>
        @endif
    </div>
    <div style="margin: 20px 8px 0px 8px; background: #fff;">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'Profile' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'Profile')" id="Profile-tab" data-bs-toggle="tab" href="#Profile"
                    role="tab" aria-controls="Profile" aria-selected="true" style="font-size: 14px;">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'Password' ? 'active' : '' }}" id="Password-tab"
                    data-bs-toggle="tab" href="#Password" role="tab" aria-controls="Password" aria-selected="false"
                    style="font-size: 14px;">Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'Login History' ? 'active' : '' }}" id="LoginHistory-tab"
                    data-bs-toggle="tab" href="#LoginHistory" role="tab" aria-controls="LoginHistory"
                    aria-selected="false" style="font-size: 14px;">Login History</a>
            </li>
        </ul>
    </div>

    <div class="tab-content" style="margin: 0px 8px;" id="myTabContent">
        <div class="tab-pane fade show {{ $activeTab === 'Profile' ? 'active' : '' }}" id="Profile" role="tabpanel"
            aria-labelledby="Profile-tab" style="overflow-x: hidden;">
            <div class="container m-4 ">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group col-md-4" style="display: flex;justify-content:start">
                            @if ($imageValidation != true)

                                @if ($image)
                                    <img src="{{ is_string($image) ? asset('storage/' . $image) : $image->temporaryUrl() }}"
                                        alt="Preview" style='height:100px;width:100px' class="img-thumbnail" />
                                @else
                                    @if (strlen($imageBinary) > 10)
                                        <img src="data:image/jpeg;base64,{{ $imageBinary }}" alt=""
                                            style='height:100px;width:100px' class="img-thumbnail" />
                                    @endif
                                @endif
                            @else
                                <span class="text-danger onboard-Valid">{{ $imageValidationmsg }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6 d-flex" style="flex-direction: column;">
                            <label class="mt-1" for="image">Employee Image <span
                                    class="text-danger onboard-Valid">*</span></label>
                            <input class="onboardinputs" type="file" wire:model="image" accept=".png, .jpg, .jpeg"
                                style="font-size:12px;border:none; width:200px; margin-bottom: 0px;">
                            @if ($imageValidation == false)
                                @error('imageBinary')
                                    <span class="text-danger onboard-Valid">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                        <button class="submit-btn mt-2" wire:click="updateImage">Upload</button>
                    </div>
                    <div class="col-md-6">
                        <p><strong style="margin-right: 60px; font-weight: normal;">Name:</strong>
                            {{ $employees->first_name }} {{ $employees->last_name }}
                        </p>
                        <p><strong style="margin-right: 60px; font-weight: normal;">Email:</strong>
                            {{ $employees->email }}
                        </p>

                        <div class="d-flex">
                            <p class="d-flex"><strong
                                    style="margin-right: 30px; font-weight: normal;">Nickname:</strong>
                                @if ($editingNickName)
                                    <input type="text" class="form-control" wire:model="nickName"
                                        placeholder="Enter Nick Name">
                                @else
                                    <span class="value-text">
                                        {{ $employees->empPersonalInfo ? ucwords(strtolower($employees->empPersonalInfo->nick_name ?? '-')) : '-' }}
                                    </span>
                                @endif
                            </p>
                            <div class="d-flex" style="margin-left: 30px; cursor: pointer;">
                                @if ($editingNickName)
                                    <div>
                                        <i wire:click="cancelProfile" class="fas fa-times me-3"></i>
                                        <i wire:click="saveProfile" class="fa fa-save"></i>
                                    </div>
                                @else
                                    <i wire:click="editProfile" class="fas fa-edit"></i>
                                @endif
                            </div>

                        </div>

                        <div class="d-flex">
                            <p class="d-flex"><strong
                                    style="margin-right: 20px; font-weight: normal;">Timezone:</strong>
                            <div class="row m-0 setting-timezone-icons-container">
                                <div class="col-md-12 mb-3">
                                    @if ($editingTimeZone)
                                        <select id="time_zone" name="time_zone" wire:model="selectedTimeZone"
                                            class="form-control setting-timezone-select">
                                            @foreach ($timeZones as $tz)
                                                <option value="{{ $tz }}">{{ $tz }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div>{{ $employees->time_zone ?? '-' }}</div>
                                    @endif
                                </div>
                            </div>
                            </p>
                            <div class="d-flex" style="margin-left: 20px; cursor: pointer;">
                                @if ($editingTimeZone)
                                    <div style="margin-top: 5px;">
                                        <i wire:click="cancelTimeZone" class="fas fa-times me-3"></i>
                                        <i wire:click="saveTimeZone" class="fa fa-save"></i>
                                    </div>
                                @else
                                    <i wire:click="editTimeZone" class="fas fa-edit"></i>
                                @endif
                            </div>
                        </div>


                        <div class="d-flex">
                            <p class="d-flex"><strong
                                    style="margin-right: 30px; font-weight: normal;">Biography:</strong>
                                @if ($editingBiography)
                                    <div class="row m-0 setting-timezone-icons-container">
                                        <div class="col-md-12 mb-3">
                                            <textarea wire:model="biography" id="biography" class="form-control setting-biography-textarea"
                                                placeholder="Enter Biography" rows="4"></textarea>
                                        </div>
                                    </div>
                                @else
                                    <div class="row m-0 setting-timezone-icons-container">
                                        <div class="col-md-12 mb-3">
                                            {{ $employees->empPersonalInfo && !empty($employees->empPersonalInfo->biography)
                                                ? ucwords(strtolower($employees->empPersonalInfo->biography))
                                                : '-' }}

                                        </div>
                                    </div>
                                @endif
                            </p>



                            <div class="d-flex" style="margin-left: 20px; cursor: pointer;">
                                @if ($editingBiography)
                                    <i wire:click="cancelBiography" class="fas fa-times me-3"></i>
                                    <i wire:click="saveBiography" class="fa fa-save"></i>
                                @else
                                    <i wire:click="editBiography" class="fas fa-edit"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="tab-pane fade {{ $activeTab === 'Password' ? 'show active' : '' }}" id="Password"
            role="tabpanel" aria-labelledby="Password-tab">
            <form wire:submit.prevent="changePassword">
                <div class="row justify-content-start">
                    <div class="col-md-6">
                        <div class="p-3">
                            <div class="form-group d-flex align-items-center mb-3">
                                <label class="setting-password-modal-label me-3" for="oldPassword"
                                    style="width: 40%;">Current Password</label>
                                <div>
                                    <input class="form-control setting-password-modal-input" type="password"
                                        id="oldPassword" name="oldPassword" placeholder="Enter your current password"
                                        wire:model.defer="oldPassword">
                                    @error('oldPassword')
                                        <p class="pt-1 text-danger setting-password-error-msg">
                                            {{ str_replace('oldPassword', 'Password', $message) }}</p>
                                    @enderror
                                </div>

                            </div>


                            <div class="form-group d-flex align-items-center mb-3">
                                <label class="setting-password-modal-label me-3" for="newPassword"
                                    style="width: 40%;">New Password</label>
                                <div>
                                    <input class="form-control setting-password-modal-input" type="password"
                                        id="newPassword" name="newPassword" placeholder="Enter your new password"
                                        wire:model.defer="newPassword">
                                    @error('newPassword')
                                        <p class="pt-1 text-danger setting-password-error-msg">
                                            {{ str_replace('newPassword', 'Password', $message) }}</p>
                                    @enderror
                                </div>

                            </div>


                            <div class="form-group d-flex align-items-center mb-3">
                                <label class="setting-password-modal-label me-3" for="confirmNewPassword"
                                    style="width: 40%;">Confirm Password</label>
                                <div>
                                    <input class="form-control setting-password-modal-input" type="password"
                                        id="confirmNewPassword" name="confirmNewPassword"
                                        placeholder="Confirm new password" wire:model.defer="confirmNewPassword">
                                    @error('confirmNewPassword')
                                        <p class="pt-1 text-danger setting-password-error-msg">
                                            {{ str_replace('confirmNewPassword', 'Password', $message) }}</p>
                                    @enderror
                                </div>

                            </div>


                            <div class="setting-password-submit-container text-center mt-3">
                                <button class="submit-btn" type="submit" wire:loading.attr="disabled"
                                    wire:loading.class="btn-loading" aria-disabled="true">
                                    <span wire:loading.remove wire:target='changePassword'>Save Password</span>
                                    <span wire:loading wire:target='changePassword'>
                                        <i class="fa fa-spinner fa-spin"></i> Verifying...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


        </div>

        <div class="tab-pane fade {{ $activeTab === 'Login History' ? 'show active' : '' }}" id="LoginHistory"
            role="tabpanel" aria-labelledby="LoginHistory-tab">

            <div class="container mt-4 p-0">
                <div class="row text-center mb-3">
                    <div class="col-md-3"><b
                            style="font-size: 14px; color: var(--main-table-heading-text-color);">Last Login
                            Date</b><br>{{ $lastLogin }}</div>
                    <div class="col-md-3"><b
                            style="font-size: 14px; color: var(--main-table-heading-text-color);">Last Login Failure
                            Date</b><br>{{ $lastLoginFailure }}</div>
                    <div class="col-md-3"><b
                            style="font-size: 14px; color: var(--main-table-heading-text-color);">Password Changed
                            Date</b><br>{{ $lastPasswordChanged }}</div>
                </div>

                <div class="table-responsive" style="width: 820px; margin-left: 10px;">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        @if (!empty($loginHistory) && is_iterable($loginHistory))
                            @foreach ($loginHistory as $history)
                                <tr>
                                    <td>{{ $history->date ?? '-' }}</td>
                                    <td>{{ $history->ip_address ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="text-center">No login history available</td>
                            </tr>
                        @endif

                    </table>
                </div>

            </div>





        </div>
    </div>
</div>
