<div class="main ">
    <div class="row">
        <div class="col1">
            <div>
                <ul>
                    <li wire:click.prevent="setActiveIcon('home')" class="list {{ ($activeIcon === 'home' || request()->is('*' . 'home' . '*')) ? 'active' : '' }}">
                        <a href="/home " wire:navigate class="fa fa-home mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('feed')" class="list {{ ($activeIcon === 'feed' || request()->is('*' .'feed' . '*')) ? 'active' : '' }}">
                        <a class="fa fa-rss mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('clipboard')" class="list {{ ($activeIcon === 'clipboard' || request()->is('*' .'clipboard'. '*')) ? 'active' : '' }}">
                        <a class="fa fa-clipboard-list mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('user')" class="list {{ ($activeIcon === 'user' || request()->is('*' .'user'. '*')) ? 'active' : '' }}">
                        <a class="fa fa-users mainmenu-icons"></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('fund')" class="list {{ ($activeIcon === 'fund' || request()->is('*' .'fund'. '*')) ? 'active' : '' }}">
                        <a class="fa fa-hand-holding-usd mainmenu-icons  " style=" font-size:20px" ></a>
                    </li>
                </ul>
                <ul>
                    <li wire:click.prevent="setActiveIcon('calendar')" class="list {{ ($activeIcon === 'calendar' || request()->is('*' .'calendar'. '*')) ? 'active' : '' }}">
                        <a class="fa fa-calendar mainmenu-icons  "></a>
                    </li>
                </ul>

                <ul>
                    <li wire:click.prevent="setActiveIcon('tv')" class="list {{ ($activeIcon === 'tv' || request()->is('*' .'tv'. '*')) ? 'active' : '' }}">
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
            <!-- Home  -->
            @if($activeIcon === 'home')
            <div class="home ">
                <h6 class="heading">Hello there  &#128512</h6>
                <p class="text">You can navigate between modules by selecting different icons on the left</p>
            </div>

            <!-- User Menus -->
            @elseif($activeIcon === 'user')
            <div class="main-menus">
                <!-- User Main-Menu1 -->
                <div class="row main-menu">
                    <div class="col">
                        <div wire:click="toggleSubmenu('row-1')" style="display: flex;justify-content: space-between; align-items: center;">
                            <h6  wire:click="yourFunction">User 1</h6>
                            <div class="arrows">
                                @if(isset($showSubmenu['row-1']) && $showSubmenu['row-1'])
                                <a class="fa fa-chevron-down "> </a>
                                @else
                                <a class="fa fa-chevron-right"> </a>
                                @endif
                            </div>

                        </div>

                        @if(isset($showSubmenu['row-1']) && $showSubmenu['row-1'])
                        <div id="Submenus">
                            <!-- User SubMenues -->
                            <a class="submenu" href="/home"  wire:navigate>View Profile</a>
                            <a class="submenu"  href="/user"  wire:navigate>Edit Profile</a>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- User Main-Menu2 -->
                <div class="row  main-menu" wire:key="row-2">
                    <div class="col">

                        <div wire:click="toggleSubmenu('row-2')" style="display: flex;">
                            <h6>User 2</h6>
                            <div class="arrows">
                                @if(isset($showSubmenu['row-2']) && $showSubmenu['row-2'])
                                <a class="fa fa-chevron-down"> </a>
                                @else
                                <a class="fa fa-chevron-right"> </a>
                                @endif
                            </div>
                        </div>
                        @if(isset($showSubmenu['row-2']) && $showSubmenu['row-2'])
                        <div id="Submenus">
                            <a class="submenu" href="/" wire:navigate>View Profile</a>
                            <a class="submenu" href="/user" wire:navigate>Edit Profile</a>
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
