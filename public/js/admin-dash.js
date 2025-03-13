const allSidebarSubmenu = document.querySelectorAll('#sidebar .sidebar__submenu');
const mainSection = document.querySelector('#main');
const hamburgerMenu = document.querySelector('.menuIcon');

const checkbox = document.querySelector('#checkbox');
// const mainSection = document.querySelector('#main');




allSidebarSubmenu.forEach(item => {
    const a = item.previousElementSibling;

    a.addEventListener('click', function(e) {
        e.preventDefault();

        if (this.classList.contains('clicked')) {
            this.classList.remove('clicked');
            item.classList.remove('active');
            mainSection.classList.remove('openLeftSubMenu');
        } else {
            allSidebarSubmenu.forEach(i => {
                i.previousElementSibling.classList.remove('clicked');
                i.classList.remove('active');
            });

            this.classList.add('clicked');
            item.classList.add('active');
            mainSection.classList.add('openLeftSubMenu');
        }
    });
});

const menuIcon = document.querySelector('.menuIcon');

checkbox.addEventListener('change', function () {
    const isChecked = this.checked;
    const isAnySubmenuActive = [...allSidebarSubmenu].some(item => item.classList.contains('active'));

    if (isChecked) {
        if (isAnySubmenuActive) {
            // Close all submenus
            allSidebarSubmenu.forEach(item => {
                item.previousElementSibling.classList.remove('clicked');
                item.classList.remove('active');
            });
            mainSection.classList.remove('openLeftSubMenu');
        } else {
            // Open the first submenu if none are active
            if (allSidebarSubmenu.length > 0) {
                const firstSubmenu = allSidebarSubmenu[0];
                firstSubmenu.previousElementSibling.classList.add('clicked');
                firstSubmenu.classList.add('active');
                mainSection.classList.add('openLeftSubMenu');
            }
        }
    } else {
        // Close everything when checkbox is unchecked
        allSidebarSubmenu.forEach(item => {
            item.previousElementSibling.classList.remove('clicked');
            item.classList.remove('active');
        });
        mainSection.classList.remove('openLeftSubMenu');
    }
});

// hamburgerMenu.addEventListener('click', function(event) {
//     event.stopPropagation();
//     const isAnySubmenuActive = [...allSidebarSubmenu].some(item => item.classList.contains('active'));

//     if (isAnySubmenuActive) {
//         // Close all submenus
//         allSidebarSubmenu.forEach(item => {
//             item.previousElementSibling.classList.remove('clicked');
//             item.classList.remove('active');
//         });
//         mainSection.classList.remove('openLeftSubMenu');
//         // menuIcon.classList.replace('fa-arrow-right', 'fa-bars'); // Change back to hamburger
//     } else {
//         // Open the first submenu if none are active
//         if (allSidebarSubmenu.length > 0) {
//             const firstSubmenu = allSidebarSubmenu[0];
//             firstSubmenu.previousElementSibling.classList.add('clicked');
//             firstSubmenu.classList.add('active');
//             mainSection.classList.add('openLeftSubMenu');
//             // menuIcon.classList.replace('fa-bars', 'fa-arrow-right'); // Change to arrow icon
//         }
//     }
// });



// SIDEBAR: DROPDOWN MENU
const allSidebarDropdownMenu = document.querySelectorAll('#sidebar .sidebar__dropdown-menu')

allSidebarDropdownMenu.forEach(item => {
    const a = item.previousElementSibling

    a.addEventListener('click', function(e) {
        e.preventDefault()

        if (item.classList.contains('active')) {
            item.classList.remove('active')
            this.classList.remove('active')
        } else {
            allSidebarDropdownMenu.forEach(i => {
                i.previousElementSibling.classList.remove('active')
                i.classList.remove('active')
            })

            item.classList.add('active')
            this.classList.add('active')
        }
    })
})


// SIDEBAR MOBILE: TOGGLE SIDEBAR
const toggleSidebar1 = document.querySelector('#sidebar-mobile .toggle-sidebar')
const sidebar = document.querySelector('#sidebar')

toggleSidebar1.addEventListener('click', function() {
    sidebar.classList.add('active')
})

// MAIN: DROPDOWN
const allMainDropdown = document.querySelectorAll('#main .main__top .main__top__menu .main__dropdown')

allMainDropdown.forEach(item => {
    const a = item.previousElementSibling

    a.addEventListener('click', function(e) {
        e.preventDefault()

        if (item.classList.contains('active')) {
            item.classList.remove('active')
        } else {
            allMainDropdown.forEach(i => {
                i.classList.remove('active')
            })

            item.classList.add('active')
        }
    })
})

// MAIN: MAIN BODY MENU
const allMainBodyMenu = document.querySelectorAll('#main .main__body :is(.members__menu, .sales-summary__menu) .menu')

allMainBodyMenu.forEach(item=> {
    const icon = item.previousElementSibling

    icon.addEventListener('click', function () {
        if(item.classList.contains('active')) {
            item.classList.remove('active')
        } else {
            allMainBodyMenu.forEach(i=> {
                i.classList.remove('active')
            })

            item.classList.add('active')
        }
    })
})

// CHART: APEXCHART
var options = {
    series: [{
        name: 'series1',
        data: [31, 40, 28, 51, 42, 109, 100]
    }, {
        name: 'series2',
        data: [11, 32, 45, 32, 34, 52, 41]
    }],
    chart: {
        height: 350,
        type: 'area'
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        type: 'datetime',
        categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    },
};

// var chart = new ApexCharts(document.querySelector("#chart"), options);
// chart.render();

// start: Tab
function tabToggle () {
    const panes = document.querySelectorAll("[data-tab-pane]");
    const pages = document.querySelectorAll("[data-tab-page]");
    panes.forEach(function (item, i) {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(
                '[data-tab-page="' + item.dataset.tabPane + '"]'
            );
            const active = document.querySelector("[data-tab-pane].active");
            if (active) {
                const activeIndex = Array.from(panes).indexOf(active);
                panes.forEach(function (el, x) {
                    el.classList.remove("active");
                    el.classList.toggle("before", x < i);
                    el.classList.toggle("after", x > i);
                    el.classList.toggle(
                        "slow",
                        Math.abs(activeIndex - x) > 0 && item !== el
                    );
                    el.style.setProperty(
                        "--delay",
                        `${
                            active === el
                                ? 0
                                : (Math.abs(activeIndex - x) - 1) * 150
                        }ms`
                    );
                });
            }
            if (target) {
                pages.forEach(function (el) {
                    el.classList.remove("active");
                });
                target.classList.add("active");
            }
            item.classList.add("active");
        });
    });
};
// end: Tab

    const scrollContainer = document.getElementById("scrollContainer");
    const prevButton = document.getElementById("prev");
    const nextButton = document.getElementById("next");
if(prevButton && nextButton) {
    function updateButtons() {
        prevButton.disabled = scrollContainer.scrollLeft === 0;
        nextButton.disabled = scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth;
    }

    prevButton.addEventListener("click", () => {
        scrollContainer.scrollBy({ left: -220, behavior: "smooth" });
    });

    nextButton.addEventListener("click", () => {
        scrollContainer.scrollBy({ left: 220, behavior: "smooth" });
    });

    scrollContainer.addEventListener("scroll", updateButtons);
}


    window.onload = function() {
        var options = {
            series: [44, 55, 41, 17, 15],
            chart: {
                type: 'donut',
            },
            plotOptions: {
                pie: {
                    startAngle: -90,
                    endAngle: 90,
                    offsetY: 10
                }
            },
            grid: {
                padding: {
                    bottom: -100
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var options2 = {
            series: [{
            data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
          }],
            chart: {
            type: 'bar',
            height: 250
          },
          plotOptions: {
            bar: {
              borderRadius: 4,
              borderRadiusApplication: 'end',
              horizontal: true,
            }
          },
          dataLabels: {
            enabled: false
          },
          xaxis: {
            categories: ['UI/UX', 'Development', 'Management', 'HR', 'Testing', 'Marketing'
            ],
          }
        };
  
        var chart2 = new ApexCharts(document.querySelector("#employeeByDep"), options2);
        chart2.render();
    
        var chart = new ApexCharts(document.querySelector("#attendanceDiv"), options);
        chart.render();

        var chart1 = new ApexCharts(document.querySelector("#taskDiv"), options);
        chart1.render();
    };

    // Function to set active sidebar item based on current URL
function setActiveSidebarItemFromURL() {
    // Get the current URL path
    const currentPath = window.location.pathname;
    // Get the last part of the path (everything after the last slash)
    const currentPathLastPart = currentPath.split('/').filter(Boolean).pop();
    
    // Reset all active states first
    const allSubmenuTriggers = document.querySelectorAll('#sidebar .sidebar__menu > li > a');
    const allSubmenus = document.querySelectorAll('#sidebar .sidebar__submenu');
    const allDropdownTriggers = document.querySelectorAll('#sidebar .sidebar__submenu a[href="#"]');
    const allDropdownMenus = document.querySelectorAll('#sidebar .sidebar__dropdown-menu');
    
    // Remove active/clicked classes
    allSubmenuTriggers.forEach(trigger => trigger.classList.remove('clicked', 'active'));
    allSubmenus.forEach(submenu => submenu.classList.remove('active'));
    allDropdownTriggers.forEach(trigger => trigger.classList.remove('active'));
    allDropdownMenus.forEach(menu => menu.classList.remove('active'));
    
    // Remove openLeftSubMenu class from main section
    mainSection.classList.remove('openLeftSubMenu');
    
    // Find all sidebar links
    const sidebarLinks = document.querySelectorAll('#sidebar .sidebar__submenu a, #sidebar .sidebar__dropdown-menu a');
    
    // Loop through all links
    let foundMatch = false;
    sidebarLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        // Skip links that are just "#"
        if (linkHref === '#') return;
        
        // Get the last part of the link href
        const linkLastPart = linkHref.split('/').filter(Boolean).pop();
        
        // Check if the last part of the link matches the last part of the current path
        if (linkLastPart && linkLastPart === currentPathLastPart) {
            foundMatch = true;
            
            // If the link is in a dropdown menu
            if (link.closest('.sidebar__dropdown-menu')) {
                // Activate the dropdown menu
                const dropdownMenu = link.closest('.sidebar__dropdown-menu');
                dropdownMenu.classList.add('active');
                
                // Activate the dropdown trigger
                const dropdownTrigger = dropdownMenu.previousElementSibling;
                dropdownTrigger.classList.add('active');
                
                // Activate the submenu that contains this dropdown
                const submenu = dropdownMenu.closest('.sidebar__submenu');
                submenu.classList.add('active');
                
                // Activate the submenu trigger
                const submenuTrigger = submenu.previousElementSibling;
                submenuTrigger.classList.add('clicked');
                
                // Add the openLeftSubMenu class to the main section
                mainSection.classList.add('openLeftSubMenu');
            } 
            // If the link is directly in a submenu
            else if (link.closest('.sidebar__submenu')) {
                // Activate the submenu
                const submenu = link.closest('.sidebar__submenu');
                submenu.classList.add('active');
                
                // Activate the submenu trigger
                const submenuTrigger = submenu.previousElementSibling;
                submenuTrigger.classList.add('clicked');
                
                // Add the openLeftSubMenu class to the main section
                mainSection.classList.add('openLeftSubMenu');
            }
        }
    });
    
    // If no match was found, you could open the first submenu as a default
    if (!foundMatch && allSidebarSubmenu.length > 0) {
        // This part is optional and depends on your design requirements
        // Uncomment if you want a default menu to open when no match is found
        
        const firstSubmenu = allSidebarSubmenu[0];
        // firstSubmenu.classList.add('active');
        // firstSubmenu.previousElementSibling.classList.add('clicked');
        // mainSection.classList.add('openLeftSubMenu');
        
    }
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', function() {
    setActiveSidebarItemFromURL();
    
    // If you have a tab function, make sure it's also initialized
    if (typeof tabToggle === 'function') {
        tabToggle();
    }
    
    // Update the scroll buttons if they exist
    if (scrollContainer) {
        updateButtons();
    }a
});