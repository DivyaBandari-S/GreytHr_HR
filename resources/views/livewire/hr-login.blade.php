<div class="container-fluid p-0 loginBGGradiant">
    <!-- <div class="m-0 pt-3 row">
        <div class="col-md-12" style="text-align: end;">
            <button class="btn btn-primary" wire:click="jobs" style="background-color: rgb(2, 17, 79);color:white;border-radius:5px;border:none">
                Recruitment</button>
        </div>
    </div> -->


    <div class="row m-0">
        <!-- Left Side (Login Form) -->
        <div class="col-md-6 loginform  ">
            @if (Session::has('success'))
            <div style="height: 30px; display: flex; align-items: center;" class="mb-4">
                <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert" style="font-size: 15px;">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="border: 0;"></button>
                </div>
            </div>
            @endif
            @if (session('sessionExpired'))
            <div class="alert alert-danger">
                {{ session('sessionExpired') }}
            </div>
            @endif

            <form wire:submit.prevent="empLogin" class="login-form-with-shadow" style="margin-top: 10px; background-color: #f2f2f6; backdrop-filter: blur(36px);">
                <div class="text-center mb-1" style="padding-top: 20px;">
                    <img src="{{ asset('images/hr_new_blue.png') }}" alt="Company Logo" style="width: 14em !important; height: auto !important; margin-bottom: 10px;">
                </div>

                <hr class="bg-white" />
                <header _ngcontent-hyf-c110="" class="mb-12 text-center">
                    <div _ngcontent-hyf-c110="" class="text-12gpx font-bold font-title-poppins-bold opacity-90 text-text-default justify-items-center">

                        Hello there! <span _ngcontent-hyf-c110="" class="font-emoji text-12gpx">👋</span>

                    </div>
                </header><br>
                @if ($error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong style="font-size: 12px;">{{ $error }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="ID / Mail" wire:model="form.emp_id" />
                    @error('form.emp_id')
                    <p class="pt-2 px-1 text-danger">{{ str_replace('form.emp id', 'Employee ID', $message) }}</p>
                    @enderror
                </div>
                <div class="form-group" style="margin-top: 20px;">
                    <input type="password" class="form-control" placeholder="Password" wire:model="form.password" />
                    @error('form.password')
                    <p class="pt-2 px-1 text-danger">{{ str_replace('form.password', 'Password', $message) }}</p>
                    @enderror
                </div>
                <div style="margin-left: 60%; text-align: center;" wire:click="show">
                    <span><a style="color: rgb(2, 17, 79);font-size:15px; cursor:pointer">Forgot
                            Password?</a></span>
                </div>
                <div class="form-group" style="text-align:center; margin-top:10px;">
                    <input data-bs-toggle="modal" data-bs-target="#loginLoader" style="background-color:rgb(2,17,79); font-size:20px; width:100px; margin: 0 auto;" type="submit" class="btn btn-primary btn-block" value="Login" />
                </div>
            </form>

        </div>
        <!-- Right Side (Carousel) -->
        <div class="col-md-6 p-0 loginanimation  " style="background-color:rgb(244 244 244);height:100vh; background-color:white">
            <!-- Carousel -->

            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style=" aspect-ratio: 16/9;border-radius:10px">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/communication.svg') }}" style="width: 85%;" alt="Los Angeles" class="d-block">


                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/task.svg') }}" style="width: 85%;" alt="Chicago" class="d-block">

                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/working.svg') }}" style="width: 85%;" alt="New York" class="d-block">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        @if ($showDialog)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" style="justify-content: center;display: flex;" role="document">
                <div class="modal-content" style="width: 80%;">
                    <div class="modal-header" style="background-color: rgb(2, 17, 79);">
                        <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title">
                            <b>{{ $verified ? 'Create New Password' : 'Verify Email and DOB' }}</b>
                        </h5>
                        <button wire:click="remove" type="button" class="btn-close text-white " aria-label="Close" style=" background-color:white;"></button>
                    </div>

                    <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                        @if ($verified)
                        <!-- Form for creating a new password -->
                        <form wire:submit.prevent="createNewPassword">
                            <!-- Add input fields for new password and confirmation -->
                            @if ($pass_change_error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong style="font-size: 10px;">{{ $pass_change_error }}</strong>
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="Enter your new password" wire:model="newPassword">
                                @error('newPassword')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="newPassword_confirmation">Confirm New Password</label>
                                <input type="password" id="newPassword_confirmation" name="newPassword_confirmation" class="form-control" placeholder="Enter your new password again" wire:model="newPassword_confirmation">
                                @error('newPassword_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div style="display:flex; justify-content:center;padding:10px">
                                <button type="submit" class="btn btn-success">Save Password</button>
                            </div>

                            <!-- Success or error message for password update -->
                            @if (session()->has('passwordMessage'))
                            <div class="alert alert-success mt-3">
                                {{ session('passwordMessage') }}
                            </div>
                            @endif
                        </form>
                        @else
                        <!-- Form for verifying email and DOB -->
                        <form wire:submit.prevent="verifyEmailAndDOB" style="display: flex;justify-content:center;flex-direction:column">
                            <!-- Add input fields for email and DOB verification -->
                            @if ($verify_error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong style="font-size: 10px;">{{ $verify_error }}</strong>
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email/company email" wire:model="email">
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" style="margin-bottom: 10px;">
                                <label for="dob">Date of Birth</label>
                                <div class="input-group">
                                    <input type="date" id="dob" name="dob" class="form-control" wire:model="dob" max="{{ date('Y-m-d') }}">
                                </div>
                                @error('dob')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div style="display: flex;justify-content:center;padding:10px">
                                <button type="submit" style="width: 100px;" class="btn btn-primary">Verify</button>

                            </div>

                            <!-- Success or error message for email and DOB verification -->
                            @if (session()->has('emailDobMessage'))
                            <div class="alert alert-{{ session('emailDobMessageType') }} mt-3">
                                {{ session('emailDobMessage') }}
                            </div>
                            @endif
                        </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop">
        </div>
        @endif

        @if ($showSuccessModal)
        <!-- Success Message and Password Change Modal -->
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: rgb(2, 17, 79);">
                        <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title">
                            <b>Success Message</b>
                        </h5>
                        <button type="button" style="margin-left: auto;background-color: rgb(2, 17, 79);border:0px; " class="close" data-dismiss="modal" aria-label="Close" wire:click="closeSuccessModal">
                            <span aria-hidden="true" style="color: white;font-size:20px;background-color: rgb(2, 17, 79); ">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                        <p>Verification successful! Do you want to change your password?</p>
                        <div style="display: flex;justify-content:center;gap:5px;">
                            <button type="button" class="btn btn-primary" wire:click="showPasswordChangeModal">Change
                                Password</button>
                            <button type="button" class="btn btn-secondary" wire:click="closeSuccessModal">Cancel</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif

        @if ($showErrorModal)
        <!-- Error Modal -->
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: rgb(255, 0, 0);">
                        <h5 style="padding: 5px; color: white; font-size: 12px;" class="modal-title">
                            <b>Error Message</b>
                        </h5>
                        <button type="button" style="background-color: rgb(255, 0, 0);margin-left:auto;border:0px;" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeErrorModal">
                            <span aria-hidden="true" style="color: white; font-size:20px;background-color:rgb(255, 0, 0);">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                        <p>Sorry You Are not Verified.... Please try again.</p>
                        <div style="display: flex;justify-content:center;">
                            <button type="button" class="btn btn-danger" wire:click="closeErrorModal">Close</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show blurred-backdrop"></div>
        @endif


        @if ($passwordChangedModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: rgb(9, 45, 206);">
                        <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title">
                            <b>Success Message</b>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closePasswordChangedModal">
                            <span aria-hidden="true" style="color: white;">x</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                        <p>Password Changes Successfully...</p>
                        <button type="button" class="btn btn-danger" wire:click="closePasswordChangedModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Button trigger modal -->
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginLoader">
        Launch static backdrop modal
        </button> -->
        @if ($showLoader)
        <!-- Modal -->
        <div class="modal fade backdropModal" id="loginLoader" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginLoaderLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color : transparent; border : none">
                    <!-- <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginLoaderLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
                    <div class="modal-body">
                        <div class="logo text-center mb-1" style="padding-top: 20px;">
                            <img src="https://xsilica.com/images/xsilica_broucher_final_modified_05082016-2.png" alt="Company Logo" width="200">
                        </div>
                        <div class="d-flex justify-content-center m-4">
                            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div> -->
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
