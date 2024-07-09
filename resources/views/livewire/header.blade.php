<nav class="navbar navbar-expand-lg bg-body-tertiary p-0 " >
                <div class="navbar-row " >
                    <div style="display: flex; align-items:center;gap:10px">
                        <img src="https://bsmedia.business-standard.com/_media/bs/img/article/2024-02/19/full/20240219171029.jpg" height="50px" width="100px" alt="" >
                        <span class=" navbar-toggler-icon  " onclick="toggleSidebar()"></span>
                    </div>
                    <div class=" navbar-collapse d-flex">
                        <!-- <form class="d-flex" role="search">
                            <input class="form-control " type="search" placeholder="Search" aria-label="Search">
                            <button class=" btn btn-outline-primary " type="submit"><i class="fas fa-search"></i></button>
                        </form> -->
                        <ul class="navbar-nav " style="margin-left: 10px; display:flex;flex-direction:row; align-items:center ; padding-right:10px">
                            <li class="nav-item">
                                <i class="fa fa-bell" style="color: #0d6efd;"></i>
                            </li>
                            <li class="nav-item" style="margin-left: 10px; ">
                                <button class="btn btn-outline-primary" type="submit" wire:click="confirmLogout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
