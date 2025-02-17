// SIDEBAR: SUBMENU
// const allSidebarSubmenu = document.querySelectorAll('#sidebar .sidebar__submenu')
// const mainSection = document.querySelector('#main')

// allSidebarSubmenu.forEach(item => {
//     const a = item.previousElementSibling

//     a.addEventListener('click', function(e) {
//         e.preventDefault()

//         if (this.classList.contains('clicked')) {
//             this.classList.remove('clicked')
//             item.classList.remove('active')
//             mainSection.classList.remove('openLeftSubMenu')
//         } else {
//             allSidebarSubmenu.forEach(i => {
//                 i.previousElementSibling.classList.remove('clicked')
//                 i.classList.remove('active')
//             })

//             this.classList.add('clicked')
//             item.classList.add('active')
//             mainSection.classList.add('openLeftSubMenu')
//         }
//     })
// })


const allSidebarSubmenu = document.querySelectorAll('#sidebar .sidebar__submenu');
const mainSection = document.querySelector('#main');
const hamburgerMenu = document.querySelector('#hamburgerMenu');

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

hamburgerMenu.addEventListener('click', function() {
    const isAnySubmenuActive = [...allSidebarSubmenu].some(item => item.classList.contains('active'));

    if (isAnySubmenuActive) {
        // Close all submenus
        allSidebarSubmenu.forEach(item => {
            item.previousElementSibling.classList.remove('clicked');
            item.classList.remove('active');
        });
        mainSection.classList.remove('openLeftSubMenu');
        menuIcon.classList.replace('fa-arrow-right', 'fa-bars'); // Change back to hamburger
    } else {
        // Open the first submenu if none are active
        if (allSidebarSubmenu.length > 0) {
            const firstSubmenu = allSidebarSubmenu[0];
            firstSubmenu.previousElementSibling.classList.add('clicked');
            firstSubmenu.classList.add('active');
            mainSection.classList.add('openLeftSubMenu');
            menuIcon.classList.replace('fa-bars', 'fa-arrow-right'); // Change to arrow icon
        }
    }
});










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







// DOCUMENT EVENT
// document.addEventListener('click', function(e) {
//     if (!e.target.matches('#sidebar, #sidebar *')) {
//         allSidebarSubmenu.forEach(item => {
//             item.previousElementSibling.classList.remove('clicked')
//             item.classList.remove('active')
//             mainSection.classList.remove('openLeftSubMenu')
//         })
//     }

//     if (!e.target.matches('#sidebar, #sidebar *, #sidebar-mobile .toggle-sidebar')) {
//         sidebar.classList.remove('active')
//     }

//     if (!e.target.matches('#main .main__top .main__top__menu, #main .main__top .main__top__menu *')) {
//         allMainDropdown.forEach(item => {
//             item.classList.remove('active')
//         })
//     }

//     if (!e.target.matches('#main .main__body :is(.members__menu, .sales-summary__menu), #main .main__body :is(.members__menu, .sales-summary__menu) *')) {
//         allMainBodyMenu.forEach(item => {
//             item.classList.remove('active')
//         })
//     }
// })







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
