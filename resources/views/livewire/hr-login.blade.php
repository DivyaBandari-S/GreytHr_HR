<div class="auth-page-wrapper">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-2 text-white-50">
                        <div>
                            <a routerLink="/" class="d-inline-block auth-logo">
                                <img src="{{ asset('images/hr_new_white.png') }}" alt="" style="width: 10em;">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium text-white-50">Premium HR Solutions</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                @if (Session::has('success'))
                <div style="height: 30px; display: flex; align-items: center;" class="mb-4">
                    <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center"
                        role="alert" style="font-size: 15px;">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                            style="border: 0;"></button>
                    </div>
                </div>
                @endif
                @if (session('sessionExpired'))
                <div class="alert alert-danger">
                    {{ session('sessionExpired') }}
                </div>
                @endif
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="sign-in-heading"><strong>Sign In</strong></h5>
                            </div>
                            <div class="p-2 mt-2">
                                <form wire:submit.prevent="empLogin">
                                    @csrf
                                    @if ($error)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong style="font-size: 12px;">{{ $error }}</strong>
                                        <button type="button" class="btn-close alertCloseBtn"
                                            data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label" for="username">Email</label>
                                        <input type="text" class="form-control" id="email"
                                            formControlName="email" placeholder="ID / Mail" wire:model="form.emp_id">
                                        @error('form.emp_id')
                                        <div class="invalid-feedback">
                                            <div>{{ str_replace('form.emp id', 'Employee ID', $message) }}</div>
                                        </div>
                                        @enderror

                                    </div>

                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a style="font-size: 12px;" wire:click="show" class="text-muted">Forgot
                                                password?</a>
                                        </div>
                                        <label class="form-label" for="password-input">Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5" placeholder="Password"
                                                wire:model="form.password" id="password-input">
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                type="button" id="password-addon"><i
                                                    class="fa-regular fa-eye"></i></button>
                                            @error('form.password')
                                            <div class="invalid-feedback">
                                                <span>{{ str_replace('form.password', 'Password', $message) }}</span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="auth-remember-check">
                                        <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                    </div> -->

                                    <div class="mt-4 login-sec">
                                        <button class="login-btn px-4" data-bs-toggle="modal"
                                            data-bs-target="#loginLoader" type="submit" style="font-size: 12px;">Log
                                            In</button>
                                    </div>

                                    <!-- <div class="mt-4 text-center">
                                        <div class="signin-other-title">
                                            <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                        </div>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-facebook-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i class="ri-github-fill fs-16"></i></button>
                                            <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-twitter-fill fs-16"></i></button>
                                        </div>
                                    </div> -->
                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center pt-4">
                        <small>
                            © Xsilica Software Solutions Pvt.Ltd |
                            <a href="/privacy-and-policy" target="_blank" style="color: rgb(2, 17, 79);">Privacy Policy</a> |
                            <a href="/Terms&Services" target="_blank" style="color: rgb(2, 17, 79);">Terms of Service</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->


    @if ($showDialog)
    <div class="modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered" style="justify-content: center;display: flex;"
            role="document">
            <div class="modal-content" style="width: 80%;">
                <div class="modal-header" style="background-color: rgb(2, 17, 79);">
                    <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title">
                        <b>{{ $verified ? 'Create New Password' : 'Verify Email and DOB' }}</b>
                    </h5>
                    <button wire:click="remove" type="button" class="btn-close text-white " aria-label="Close"
                        style=" background-color:white;"></button>
                </div>

                <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                    @if ($verified)
                    <!-- Form for creating a new password -->
                    <form wire:submit.prevent="createNewPassword">
                        @csrf
                        <!-- Add input fields for new password and confirmation -->
                        @if ($pass_change_error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong style="font-size: 10px;">{{ $pass_change_error }}</strong>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" id="newPassword" name="newPassword" class="form-control"
                                placeholder="Enter your new password" wire:model="newPassword">
                            @error('newPassword')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="newPassword_confirmation">Confirm New Password</label>
                            <input type="password" id="newPassword_confirmation"
                                name="newPassword_confirmation" class="form-control"
                                placeholder="Enter your new password again"
                                wire:model="newPassword_confirmation">
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
                    <form wire:submit.prevent="verifyEmailAndDOB"
                        style="display: flex;justify-content:center;flex-direction:column">
                        @csrf
                        <!-- Add input fields for email and DOB verification -->
                        @if ($verify_error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong style="font-size: 10px;">{{ $verify_error }}</strong>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Enter your email/company email" wire:model="email">
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="dob">Date of Birth</label>
                            <div class="input-group">
                                <input type="date" id="dob" name="dob" class="form-control"
                                    wire:model="dob" max="{{ date('Y-m-d') }}">
                            </div>
                            @error('dob')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display: flex;justify-content:center;padding:10px">
                            <button type="submit" style="width: 100px;"
                                class="btn btn-primary">Verify</button>

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
                    <button type="button" style="margin-left: auto;background-color: rgb(2, 17, 79);border:0px; "
                        class="close" data-dismiss="modal" aria-label="Close" wire:click="closeSuccessModal">
                        <span aria-hidden="true"
                            style="color: white;font-size:20px;background-color: rgb(2, 17, 79); ">x</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                    <p>Verification successful! Do you want to change your password?</p>
                    <div style="display: flex;justify-content:center;gap:5px;">
                        <button type="button" class="btn btn-primary"
                            wire:click="showPasswordChangeModal">Change
                            Password</button>
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeSuccessModal">Cancel</button>
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
                    <button type="button" style="background-color: rgb(255, 0, 0);margin-left:auto;border:0px;"
                        class="close" data-dismiss="modal" aria-label="Close" wire:click="closeErrorModal">
                        <span aria-hidden="true"
                            style="color: white; font-size:20px;background-color:rgb(255, 0, 0);">x</span>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        wire:click="closePasswordChangedModal">
                        <span aria-hidden="true" style="color: white;">x</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #f0f0f0; padding: 20px;">
                    <p>Password Changes Successfully...</p>
                    <button type="button" class="btn btn-danger"
                        wire:click="closePasswordChangedModal">Close</button>
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
    <div class="modal fade backdropModal" id="loginLoader" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="loginLoaderLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color : transparent; border : none">
                <!-- <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginLoaderLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
                <div class="modal-body">
                    <div class="logo text-center mb-1" style="padding-top: 20px;">
                        <img src="https://xsilica.com/images/xsilica_broucher_final_modified_05082016-2.png"
                            alt="Company Logo" width="200">
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