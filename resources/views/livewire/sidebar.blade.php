<div class="main ">
    <div class="row">
        <div class="col1">
            <div>
                <ul>
                    <li wire:click.prevent="setActiveIcon('home')"
                        class="list {{ $activeIcon === 'home' || request()->is('*' . 'dashboard' . '*') ? 'active' : '' }}">
                        <a href="/hr/dashboard " wire:navigate class="fa fa-home mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('feed')"
                        class="list {{ $activeIcon === 'feed' || request()->is('*' . 'feed' . '*') ? 'active' : '' }}">
                        <a href="/hrFeeds"  wire:navigate class="fa fa-rss mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('clipboard')"
                        class="list {{ $activeIcon === 'clipboard' || request()->is('*' . 'clipboard' . '*') ? 'active' : '' }}">
                        <a class="fa fa-clipboard-list mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('user')"
                        class="list {{ $activeIcon === 'user' || request()->is('*' . 'user' . '*') ? 'active' : '' }}">
                        <a class="fa fa-users mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('fund')"
                        class="list {{ $activeIcon === 'fund' || request()->is('*' . 'fund' . '*') ? 'active' : '' }}">
                        <a class="fa fa-hand-holding-usd mainmenu-icons  " style=" font-size:20px"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('calendar')"
                        class="list {{ $activeIcon === 'calendar' || request()->is('*' . 'calendar' . '*') ? 'active' : '' }}">
                        <a class="fa fa-calendar mainmenu-icons  "></a>
                    </li>
                </ul>

                <ul>
                    <li wire:click.prevent="setActiveIcon('tv')"
                        class="list {{ $activeIcon === 'tv' || request()->is('*' . 'tv' . '*') ? 'active' : '' }}">
                        <a class="fa fa-tv mainmenu-icons"></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col2">
            <div class="home">
                <h6 class="heading">Home</h6>
                <p class="text">it's where the heart is!</p>
            </div>
            <hr class="horizontal-line">
            <!-- Home  -->
            @if ($activeIcon === 'home')
                <div class="home ">
                    <h6 class="heading">Hello there &#128512</h6>
                    <p class="text">You can navigate between modules by selecting different icons on the left</p>
                </div>

                <!-- Employee Menus -->
            @elseif($activeIcon === 'user' || request()->is('*' . 'user' . '*'))
                <h6 class="main-heading">EMPLOYEE</h6>
                <div class="main-menus">
                    <!-- Main -->
                    <div class="row main-menu"wire:key="row-1">
                        <div class="col">
                            <div wire:click="toggleSubmenu('row-1')"
                                style="display: flex;justify-content: space-between; align-items: center;">
                                <h6 class="sub-heading {{isset($showSubmenu['row-1']) && $showSubmenu['row-1']? 'active' : ''}}" wire:click='testing'>Main</h6>
                                <div class="arrows">
                                    @if (isset($showSubmenu['row-1']) && $showSubmenu['row-1'])
                                        <a class="fa fa-chevron-down arrow-icon-active"> </a>
                                    @else
                                        <a class="fa fa-chevron-right arrow-icon"> </a>
                                    @endif
                                </div>

                            </div>

                            @if (isset($showSubmenu['row-1']) && $showSubmenu['row-1'])
                                <div id="Submenus" class="submenu-container">

                                    <!-- Main SubMenues -->

                                    <p class="p-0"><a href="/hr/user/overview"  class="submenu" wire:navigate>Overview</a></p>

                                    <p class="p-0"><a href="/hr/user/analytics-hub" class="submenu" wire:navigate>Analytics Hub</a>
                                    </p>

                                    <p class="p-0"><a href="/hr/user/hremployeedirectory" class="submenu" wire:navigate>Employee
                                            Directory</a></p>

                                    <p class="p-0"><a href="/user" class="submenu" wire:navigate>Organization
                                            Chart</a></p>

                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Information -->
                    <div class="row  main-menu" wire:key="row-2">
                        <div class="col">

                            <div wire:click="toggleSubmenu('row-2')" style="display: flex;">
                                <h6 class="sub-heading {{ isset($showSubmenu['row-2']) && $showSubmenu['row-2'] ? 'active' : '' }}">Information</h6>
                                <div class="arrows">
                                    @if (isset($showSubmenu['row-2']) && $showSubmenu['row-2'])
                                        <a class="fa fa-chevron-down arrow-icon-active"> </a>
                                    @else
                                        <a class="fa fa-chevron-right arrow-icon"> </a>
                                    @endif
                                </div>
                            </div>
                            @if (isset($showSubmenu['row-2']) && $showSubmenu['row-2'])
                            <div id="Submenus" class="submenu-container">

                                <!-- Main SubMenues -->
    
                                <p class="p-0"><a href="/information" class="submenu" wire:navigate>Employee Profile</a></p>
                                <p class="p-0"><a href="/asset" class="submenu" wire:navigate>Employee Asset</a></p>
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Bank/PF/ESI</a></p>
    
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Family Details</a></p>
    
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Organization Chart</a></p>
    
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- Admin -->
                    <div class="row  main-menu" wire:key="row-3">
                        <div class="col">

                            <div wire:click="toggleSubmenu('row-3')" style="display: flex;">
                                <h6 class="sub-heading {{ isset($showSubmenu['row-3']) && $showSubmenu['row-3'] ? 'active' : '' }}">Admin</h6>
                                <div class="arrows">
                                    @if (isset($showSubmenu['row-3']) && $showSubmenu['row-3'])
                                        <a class="fa fa-chevron-down arrow-icon-active"> </a>
                                    @else
                                        <a class="fa fa-chevron-right arrow-icon"> </a>
                                    @endif
                                </div>
                            </div>
                            @if (isset($showSubmenu['row-3']) && $showSubmenu['row-3'])
                            <div id="Submenus" class="submenu-container">

                                <!-- Main SubMenues -->
    
                                <p class="p-0"><a href="/home" class="submenu" wire:navigate>Overview</a></p>
    
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Analytics Hub</a></p>
    
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Employee Directory</a></p>
    
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Organization Chart</a></p>
    
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- Setup -->
                    <div class="row  main-menu" wire:key="row-4">
                        <div class="col">

                            <div wire:click="toggleSubmenu('row-4')" style="display: flex;">
                                <h6 class="sub-heading {{ isset($showSubmenu['row-4']) && $showSubmenu['row-4'] ? 'active' : '' }}">Setup</h6>
                                <div class="arrows">
                                    @if (isset($showSubmenu['row-4']) && $showSubmenu['row-4'])
                                        <a class="fa fa-chevron-down arrow-icon-active"> </a>
                                    @else
                                        <a class="fa fa-chevron-right arrow-icon"> </a>
                                    @endif
                                </div>
                            </div>
                            @if (isset($showSubmenu['row-4']) && $showSubmenu['row-4'])
                            <div id="Submenus" class="submenu-container">

                                <!-- Main SubMenues -->
    
                                <p class="p-0"><a href="/home" class="submenu" wire:navigate>Overview</a></p>
    
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Analytics Hub</a></p>
    
                                <p class="p-0"><a href="/hremployeedirectory" class="submenu" wire:navigate>Employee Directory</a></p>
    
                                <p class="p-0"><a href="/user" class="submenu" wire:navigate>Organization Chart</a></p>
    
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            @elseif($activeIcon === 'fa-envelope')

            @elseif($activeIcon === 'fa-cog')

            @elseif($activeIcon === 'fa-bell')
            @else
            @endif
        </div>
    </div>

</div>
