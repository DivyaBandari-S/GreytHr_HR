<nav class="navbar navbar-expand-lg bg-body-tertiary p-0 ">
    <div class="navbar-row ">
        <div style="display: flex; align-items:center;gap:10px">
            <img src="{{ asset('images/hr_new_blue.png') }}" height="50px" width="150px" alt="">
            <span class=" navbar-toggler-icon  " onclick="toggleSidebar()"></span>
        </div>
        <div class=" navbar-collapse d-flex">
            <!-- <form class="d-flex" role="search">
                            <input class="form-control " type="search" placeholder="Search" aria-label="Search">
                            <button class=" btn btn-outline-primary " type="submit"><i class="fas fa-search"></i></button>
                        </form> -->
            <ul class="navbar-nav "
                style="margin-left: 10px; display:flex;flex-direction:row; align-items:center ; padding-right:10px">
                <li class="nav-item">
                    <i class="fa fa-bell" style="color: var(--main-button-color);"></i>
                </li>
                <li class="nav-item" style="margin-left: 10px; ">
                    <button class="btn btn-outline-primary" type="submit" wire:click="handleLogout"><i
                            class="fas fa-sign-out-alt"></i> Logout</button>
                </li>
            </ul>

            <!-- Logout Modal -->
            @if ($showLogoutModal)
                <div class="modal" id="logoutModal" tabindex="-1" style="display: block;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-white" style=" background-color: var(--main-button-color);">
                                <h6 class="modal-title " id="logoutModalLabel" style="align-items: center;">Confirm
                                    Logout</h6>
                            </div>
                            <div class="modal-body text-center"
                                style="font-size: 14px;color:var( --main-heading-color);">
                                Are you sure you want to logout?
                            </div>
                            <div class="d-flex gap-3 justify-content-center p-3">
                                <button type="button" class="submit-btn mr-3"
                                    wire:click="confirmLogout">Logout</button>
                                <button type="button" class="cancel-btn" wire:click="cancelLogout">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-backdrop fade show"></div>
            @endif
        </div>
    </div>
</nav>
