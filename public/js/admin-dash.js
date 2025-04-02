const allSidebarSubmenu = document.querySelectorAll('#sidebar .sidebar__submenu');
const mainSection = document.querySelector('#main');
const hamburgerMenu = document.querySelector('.menuIcon');

const checkbox = document.querySelector('#checkbox');
// const mainSection = document.querySelector('#main');

allSidebarSubmenu.forEach(item => {
    const a = item.previousElementSibling;

    a.addEventListener('click', function(e) {
        e.preventDefault();
        checkbox.checked = true;

        // if (this.classList.contains('clicked')) {
        //     this.classList.remove('clicked');
        //     item.classList.remove('active');
        //     item.classList.remove('activeDiv');
        //     mainSection.classList.remove('openLeftSubMenu');
        // } else {
            allSidebarSubmenu.forEach(i => {
                i.previousElementSibling.classList.remove('clicked');
                i.classList.remove('active');
                i.classList.remove('activeDiv');
            });

            this.classList.add('clicked');
            item.classList.add('active');
            item.classList.add('activeDiv');
            mainSection.classList.add('openLeftSubMenu');
        // }
    });
});

const menuIcon = document.querySelector('.menuIcon');
checkbox.addEventListener('change', function () {
    const isChecked = this.checked;
    const isAnySubmenuActive = [...allSidebarSubmenu].some(item => item.classList.contains('active'));

    if (isChecked) {
        if (isAnySubmenuActive) {
            // Show only the currently active submenus
            allSidebarSubmenu.forEach(item => {
                if (item.classList.contains('active')) {
                    item.previousElementSibling.classList.add('clicked');
                    item.classList.add('activeDiv');
                }
            });
            mainSection.classList.add('openLeftSubMenu');
        } else {
            // Open the first submenu if none are active
            if (allSidebarSubmenu.length > 0) {
                const firstSubmenu = allSidebarSubmenu[0];
                firstSubmenu.previousElementSibling.classList.add('clicked');
                firstSubmenu.classList.add('active');
                firstSubmenu.classList.add('activeDiv');
                mainSection.classList.add('openLeftSubMenu');
            }
        }
    } else {
        // Close everything when checkbox is unchecked
        allSidebarSubmenu.forEach(item => {
            // item.previousElementSibling.classList.remove('clicked');
            item.classList.remove('activeDiv');
        });
        mainSection.classList.remove('openLeftSubMenu');
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
                        width: 200,
                        height: 200
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
    allSubmenuTriggers.forEach(trigger => trigger.classList.remove('clicked', 'active', 'active-border-left'));
    allSubmenus.forEach(submenu => submenu.classList.remove('active'));
    allDropdownTriggers.forEach(trigger => trigger.classList.remove('active'));
    allDropdownMenus.forEach(menu => menu.classList.remove('active'));
    
    // Remove openLeftSubMenu class from main section
    mainSection.classList.remove('openLeftSubMenu');
    
    // Find all sidebar links
    const sidebarLinks = document.querySelectorAll('#sidebar .sidebar__submenu a, #sidebar .sidebar__dropdown-menu a');
    
    // Loop through all links
    let foundMatch = false;
    checkbox.checked = true;
    
    sidebarLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        // Skip links that are just "#"
        if (linkHref === '#') return;
        
        // Get the last part of the link href
        const linkLastPart = linkHref.split('/').filter(Boolean).pop();
        
        // Check if the last part of the link matches the last part of the current path
        if (linkLastPart && linkLastPart === currentPathLastPart) {
            foundMatch = true;

            // Add active border-left class to the current active link
            link.classList.add('active-border-left');

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
                submenu.classList.add('activeDiv');
                
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
                submenu.classList.add('activeDiv');
                
                // Activate the submenu trigger
                const submenuTrigger = submenu.previousElementSibling;
                submenuTrigger.classList.add('clicked');
                
                // Add the openLeftSubMenu class to the main section
                mainSection.classList.add('openLeftSubMenu');
            }
        }
    });
    
    // If no match was found, open the first submenu as a default
    if (!foundMatch && allSidebarSubmenu.length > 0) {
        const firstSubmenu = allSidebarSubmenu[0];
        firstSubmenu.classList.add('active');
        firstSubmenu.classList.add('activeDiv');
        firstSubmenu.previousElementSibling.classList.add('clicked');
        mainSection.classList.add('openLeftSubMenu');
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
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const contentContainer = document.querySelector('.homeBannerSec'); // Select the content container
  
    // Store the original content
    const originalContent = contentContainer.innerHTML;
  
    // Create the skeleton loader HTML
    const skeletonLoaderHTML = `
      <div class="row m-0 mb-4">
          <div class="col-md-7 m-auto" style="text-align: center; line-height: 2;">
              <div class="skeleton-loader" style="width: 70%; height: 30px; margin: 10px auto;"></div>
              <div class="skeleton-loader" style="width: 90%; height: 16px; margin: 5px auto;"></div>
              <div class="skeleton-loader" style="width: 80%; height: 16px; margin: 5px auto;"></div>
          </div>
          <div class="col-md-5">
              <div class="skeleton-loader" style="width: 100%; height: 200px;"></div>
          </div>
      </div>
    `;
  
    // Apply the skeleton loader immediately
    contentContainer.innerHTML = skeletonLoaderHTML;
  
    // Set a timeout to replace the skeleton loader with the original content after 5 seconds
    setTimeout(function() {
      contentContainer.innerHTML = originalContent;
    }, 5000); // 5000 milliseconds = 5 seconds
  });
  
  // Add the CSS for skeleton loader (if not already present)
  const style = document.createElement('style');
  style.textContent = `
  .skeleton-loader {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite linear;
      border-radius: 4px;
  }
  
  @keyframes shimmer {
      0% {
          background-position: 100% 0;
      }
      100% {
          background-position: -100% 0;
      }
  }
  `;
  document.head.appendChild(style);

  document.addEventListener('DOMContentLoaded', function() {
    const contentContainer = document.querySelector('.sec-2'); // Select the main content container
  
    // Store the original content
    const originalContent = contentContainer.innerHTML;
  
    // Create the skeleton loader HTML
    const skeletonLoaderHTML = `
  
          <div class="container-sec">
              <div class="d-flex justify-content-between align-items-center">
                  <div class="skeleton-loader" style="width: 250px; height: 20px; border-radius: 4px;"></div>
                  <div class="navigation">
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; display: inline-block;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; display: inline-block; margin-left: 5px;"></div>
                  </div>
              </div>
              <div class="scroll-container" id="scrollContainer">
                  <div class="skeleton-loader" style="width: 80px; height: 100px; border-radius: 4px; display: inline-block; margin-right: 10px;"></div>
                  <div class="skeleton-loader" style="width: 150px; height: 100px; border-radius: 4px; display: inline-block; margin-right: 10px;"></div>
                  <div class="skeleton-loader" style="width: 150px; height: 100px; border-radius: 4px; display: inline-block; margin-right: 10px;"></div>
                  <div class="skeleton-loader" style="width: 150px; height: 100px; border-radius: 4px; display: inline-block; margin-right: 10px;"></div>
                  <div class="skeleton-loader" style="width: 150px; height: 100px; border-radius: 4px; display: inline-block; margin-right: 10px;"></div>
                  <div class="skeleton-loader" style="width: 150px; height: 100px; border-radius: 4px; display: inline-block; margin-right: 10px;"></div>
                  <div class="skeleton-loader" style="width: 150px; height: 100px; border-radius: 4px; display: inline-block;"></div>
              </div>
          </div>
  
          <div class="row m-0 mb-3">
              <div class="col-md-4">
                  <div class="card-stat">
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                      <div class="skeleton-loader" style="width: 50px; height: 50px; border-radius: 50%; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                  </div>
              </div>
  
              <div class="col-md-4">
                  <div class="card-stat">
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                      <div class="skeleton-loader" style="width: 50px; height: 50px; border-radius: 50%; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                  </div>
              </div>
  
              <div class="col-md-4">
                  <div class="card-stat">
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                      <div class="skeleton-loader" style="width: 50px; height: 50px; border-radius: 50%; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                  </div>
              </div>
          </div>
  
          <div class="row m-0">
              <div class="col-md-6">
                  <div class="card-stat">
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                      <div class="skeleton-loader" style="width: 50px; height: 50px; border-radius: 50%; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                  </div>
              </div>
  
              <div class="col-md-6">
                  <div class="card-stat">
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                      <div class="skeleton-loader" style="width: 50px; height: 50px; border-radius: 50%; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 30px; height: 30px; border-radius: 4px; margin: 10px auto;"></div>
                      <div class="skeleton-loader" style="width: 100%; height: 30px; border-radius: 4px;"></div>
                  </div>
              </div>
          </div>
  
    `;
  
    // Apply the skeleton loader immediately
    contentContainer.innerHTML = skeletonLoaderHTML;
  
    // Set a timeout to replace the skeleton loader with the original content after 5 seconds
    setTimeout(function() {
      contentContainer.innerHTML = originalContent;
    }, 5000); // 5000 milliseconds = 5 seconds
  });
  
  // Add the CSS for skeleton loader (if not already present)
  const style1 = document.createElement('style');
  style1.textContent = `
  .skeleton-loader {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite linear;
      border-radius: 4px;
  }
  
  @keyframes shimmer {
      0% {
          background-position: 100% 0;
      }
      100% {
          background-position: -100% 0;
      }
    }
    `;
  document.head.appendChild(style1);

  document.addEventListener('DOMContentLoaded', function() {
    const contentContainer = document.querySelector('.sec-3'); // Select the main content container
  
    // Store the original content
    const originalContent = contentContainer.innerHTML;
  
    // Create the skeleton loader HTML
    const skeletonLoaderHTML = `
          <div class="m-0 mb-3 row text-center" style="border-radius: 10px; border: 1px solid #ff3c6c;">
              <div class="row m-0 p-0 mb-3" style="border-radius: 10px;">
                  <div class="skeleton-loader" style="width: 100%; height: 150px; border-radius: 10px;"></div>
              </div>
              <div class="skeleton-loader" style="width: 100px; height: 20px; margin: 10px auto;"></div>
              <div class="skeleton-loader" style="width: 80%; height: 16px; margin: 5px auto;"></div>
              <div class="m-0 mb-3">
                  <div class="skeleton-loader" style="width: 100px; height: 30px; margin: 10px auto;"></div>
              </div>
          </div>
  
          <div class="border mb-3 rounded row m-0">
              <div class="border-bottom m-0 mt-3 row">
                  <div class="col-md-12">
                      <div class="skeleton-loader" style="width: 150px; height: 20px; margin-bottom: 5px;"></div>
                      <div class="skeleton-loader" style="width: 100px; height: 16px;"></div>
                  </div>
              </div>
              <div class="table-responsive p-0">
                  <table class="table fs12">
                      <thead class="table-secondary">
                          <tr>
                              <th scope="col"><div class="skeleton-loader" style="width: 30px; height: 16px;"></div></th>
                              <th scope="col"><div class="skeleton-loader" style="width: 50px; height: 16px;"></div></th>
                              <th scope="col"><div class="skeleton-loader" style="width: 40px; height: 16px;"></div></th>
                              <th scope="col"><div class="skeleton-loader" style="width: 40px; height: 16px;"></div></th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td><div class="skeleton-loader" style="width: 40px; height: 16px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 60px; height: 16px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 80px; height: 30px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 50px; height: 20px;"></div></td>
                          </tr>
                          <tr>
                              <td><div class="skeleton-loader" style="width: 40px; height: 16px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 60px; height: 16px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 80px; height: 30px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 50px; height: 20px;"></div></td>
                          </tr>
                          <tr>
                              <td><div class="skeleton-loader" style="width: 40px; height: 16px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 60px; height: 16px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 80px; height: 30px;"></div></td>
                              <td><div class="skeleton-loader" style="width: 50px; height: 20px;"></div></td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
    `;
  
    // Apply the skeleton loader immediately
    contentContainer.innerHTML = skeletonLoaderHTML;
  
    // Set a timeout to replace the skeleton loader with the original content after 5 seconds
    setTimeout(function() {
      contentContainer.innerHTML = originalContent;
    }, 5000); // 5000 milliseconds = 5 seconds
  });
  
  // Add the CSS for skeleton loader (if not already present)
  const style2 = document.createElement('style');
  style2.textContent = `
  .skeleton-loader {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite linear;
      border-radius: 4px;
  }
  
  @keyframes shimmer {
      0% {
          background-position: 100% 0;
      }
      100% {
          background-position: -100% 0;
      }
  }
  `;
  document.head.appendChild(style2);


//   document.addEventListener('DOMContentLoaded', function() {
//     const sidebarMenu = document.querySelector('.sidebar__menu');
//     const originalSidebar = sidebarMenu.innerHTML;
  
//     const skeletonSidebar = `
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//               <ul class="sidebar__submenu" style="opacity: 1; visibility: visible;">
//                   <li class="title skeleton-loader" style="width: 50px; height: 16px;"></li>
//                   <li><a href="#" class="skeleton-loader" style="width: 80px; height: 16px;"></a></li>
//                   <li><a href="#" class="skeleton-loader" style="width: 80px; height: 16px;"></a></li>
//                   <li><a href="#" class="skeleton-loader" style="width: 80px; height: 16px;"></a></li>
//               </ul>
//           </li>
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//               <ul class="sidebar__submenu">
//                   <li class="title skeleton-loader" style="width: 50px; height: 16px;"></li>
//                   <li><a href="#" class="skeleton-loader" style="width: 80px; height: 16px;"></a></li>
//               </ul>
//           </li>
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//               <ul class="sidebar__submenu">
//                   <li class="title skeleton-loader" style="width: 50px; height: 16px;"></li>
//                   <li><a href="#" class="skeleton-loader" style="width: 80px; height: 16px;"></a></li>
//               </ul>
//           </li>
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//               <ul class="sidebar__submenu">
//                   <li class="title skeleton-loader" style="width: 50px; height: 16px;"></li>
//                   <li>
//                       <a href="#" class="skeleton-loader" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//                   <li>
//                       <a href="#" class="skeleton-loader mb-3" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//                   <li>
//                       <a href="#" class="skeleton-loader" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//                   <li>
//                       <a href="#" class="skeleton-loader mb-3" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//                   <li>
//                       <a href="#" class="skeleton-loader mb-3" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//               </ul>
//           </li>
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//               <ul class="sidebar__submenu">
//                   <li class="title skeleton-loader" style="width: 50px; height: 16px;"></li>
//                   <li>
//                       <a href="#" class="skeleton-loader mb-3" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//                   <li>
//                       <a href="#" class="skeleton-loader mb-3" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//                   <li>
//                     <a href="#" class="skeleton-loader mb-3" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                            <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//                    <li>
//                     <a href="#" class="skeleton-loader mb-3" style="width: 100px; height: 16px;"></a>
//                       <ul class="sidebar__dropdown-menu">
//                           <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                            <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                             <li><a href="#" class="skeleton-loader" style="width: 120px; height: 16px;"></a></li>
//                       </ul>
//                   </li>
//               </ul>
//           </li>
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//               <ul class="sidebar__submenu">
//                   <li class="title skeleton-loader" style="width: 50px; height: 16px;"></li>
//                   <li><a href="#" class="skeleton-loader" style="width: 80px; height: 16px;"></a></li>
//                   <li><a href="#" class="skeleton-loader" style="width: 80px; height: 16px;"></a></li>
//               </ul>
//           </li>
//           <li class="divider skeleton-loader" style="height: 1px; margin: 10px 0; margin: .5rem auto;"></li>
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//           </li>
//           <li>
//               <a href="#" class="skeleton-loader mb-3" style="width: 30px; height: 30px; border-radius: 50%;"></a>
//           </li>
//     `;
  
//     sidebarMenu.innerHTML = skeletonSidebar;
  
//     setTimeout(function() {
//       sidebarMenu.innerHTML = originalSidebar;
//     }, 5000);
//   });
  
//   const style3 = document.createElement('style');
//   style3.textContent = `
//   .skeleton-loader {
//       background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
//       background-size: 200% 100%;
//       animation: shimmer 1.5s infinite linear;
//       border-radius: 4px;
//       margin: 4px 0;
//   }
  
//   @keyframes shimmer {
//       0% {
//           background-position: 100% 0;
//       }
//       100% {
//           background-position: -100% 0;
//       }
//   }
//   `;
//   document.head.appendChild(style3);
document.addEventListener('DOMContentLoaded', function() {
    const employeeStatusDiv = document.querySelector('.empStatus'); // Assuming this is the outer container
  
    if (!employeeStatusDiv) return; // Exit if the container isn't found
  
    const originalContent = employeeStatusDiv.innerHTML;
  
    const skeletonHTML = `
      <div class="border-bottom m-0 mt-3 row">
        <div class="col-md-6">
          <p class="fw-bold skeleton-loader" style="height: 20px; width: 150px;"></p>
        </div>
        <div class="col-md-6 text-end mb-3">
          <button class="btn btn-outline-primary btn-sm skeleton-loader" style="height: 30px; width: 100px;"></button>
        </div>
      </div>
  
      <div class="m-0 mt-3 row">
        <div class="col-md-6">
          <p class="skeleton-loader" style="height: 20px; width: 120px;"></p>
        </div>
        <div class="col-md-6 text-end">
          <p class="skeleton-loader" style="height: 20px; width: 50px;"></p>
        </div>
      </div>
  
      <div class="m-0 px-4 row">
        <div class="p-0 progress-stacked">
          <div class="progress">
            <div class="progress-bar skeleton-loader" style="width: 15%; height: 10px;"></div>
          </div>
          <div class="progress">
            <div class="progress-bar bg-success skeleton-loader" style="width: 30%; height: 10px;"></div>
          </div>
          <div class="progress">
            <div class="progress-bar bg-info skeleton-loader" style="width: 20%; height: 10px;"></div>
          </div>
        </div>
      </div>
  
      <div class="m-0 mt-3 px-4 row">
        <div class="border col-md-6 pt-3">
          <p class="mb-0 skeleton-loader" style="height: 20px; width: 100px;"></p>
          <p class="fs-1 fw-bold mb-1 skeleton-loader" style="height: 30px; width: 80px;"></p>
        </div>
        <div class="border-bottom border-end border-top col-md-6 pt-3 text-end">
          <p class="mb-0 skeleton-loader" style="height: 20px; width: 100px;"></p>
          <p class="fs-1 fw-bold mb-1 skeleton-loader" style="height: 30px; width: 80px;"></p>
        </div>
      </div>
  
      <div class="m-0 px-4 row">
        <div class="border-bottom border-end border-start col-md-6 pt-3">
          <p class="mb-0 skeleton-loader" style="height: 20px; width: 100px;"></p>
          <p class="fs-1 fw-bold mb-1 skeleton-loader" style="height: 30px; width: 80px;"></p>
        </div>
        <div class="border-bottom border-end col-md-6 pt-3 text-end">
          <p class="mb-0 skeleton-loader" style="height: 20px; width: 100px;"></p>
          <p class="fs-1 fw-bold mb-1 skeleton-loader" style="height: 30px; width: 80px;"></p>
        </div>
      </div>
  
      <div class="row m-0 mt-3">
        <p class="mb-1 skeleton-loader" style="height: 20px; width: 120px;"></p>
        <div class="row m-0">
          <div class="row m-0 p-2 rounded-2 performerDiv">
            <div class="col-md-1 p-0 m-auto">
              <p class="mb-0">
                <i class="fa-solid fa-award fs-3 me-3 perfColor skeleton-loader" style="height: 30px; width: 30px; border-radius: 50%; vertical-align: middle;"></i>
                <span class="skeleton-loader" style="width: 2em; height: 2em; border-radius: 50%;"></span>
              </p>
            </div>
            <div class="col-md-11 p-0">
              <div class="m-0 row">
                <div class="col-md-6 p-0">
                  <p class="fw-bold mb-0 fs14 skeleton-loader" style="height: 20px; width: 100px;"></p>
                  <p class="fs12 mb-0 skeleton-loader" style="height: 20px; width: 80px;"></p>
                </div>
                <div class="col-md-6 text-end p-0">
                  <p class="mb-0 fs14 skeleton-loader" style="height: 20px; width: 90px;"></p>
                  <p class="fs12 fw-bold mb-0 perfColor skeleton-loader" style="height: 20px; width: 50px;"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <div class="row m-0 my-3">
        <div class="d-grid gap-2">
          <button class="btn btn-outline-secondary btn-sm skeleton-loader" style="height: 30px; width: 100px;"></button>
        </div>
      </div>
    `;
  
    employeeStatusDiv.innerHTML = skeletonHTML;
  
    setTimeout(function() {
      employeeStatusDiv.innerHTML = originalContent;
    }, 5000); // Simulate loading time (adjust as needed)
  });
  
//   const style = document.createElement('style');
//   style.textContent = `
//   .skeleton-loader {
//       background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
//       background-size: 200% 100%;
//       animation: shimmer 1.5s infinite linear;
//       border-radius: 4px;
//       margin: 4px 0;
//   }
  
//   @keyframes shimmer {
//       0% {
//           background-position: 100% 0;
//       }
//       100% {
//           background-position: -100% 0;
//       }
//   }
//   `;
//   document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', function() {
    const employeesSectionDiv = document.querySelector('.empTab'); // Select the main container
  
    if (!employeesSectionDiv) return; // Exit if the element is not found.
  
    const originalContent = employeesSectionDiv.innerHTML; // Store original HTML
  
    const skeletonHTML = `
      <div class="m-0 mt-3 row">
        <div class="col-md-6">
          <p class="fw-bold skeleton-loader" style="height: 20px; width: 100px;"></p>
        </div>
        <div class="col-md-6 text-end mb-3">
          <button class="btn btn-outline-primary btn-sm skeleton-loader" style="height: 30px; width: 100px;"></button>
        </div>
      </div>
  
      <div class="row m-0 py-2 bg-light">
        <div class="col-md-6">
          <p class="mb-0 fw-bold fs14 skeleton-loader" style="height: 20px; width: 80px;"></p>
        </div>
        <div class="col-md-6 text-end">
          <p class="mb-0 fw-bold fs14 skeleton-loader" style="height: 20px; width: 100px;"></p>
        </div>
      </div>
  
      <div class="row m-0 mt-3">
        <div class="m-0 mb-3 p-2 row border-bottom">
          <div style="display: flex; align-items: center;">
            <span class="skeleton-loader" style="width: 2em; height: 2em; border-radius: 50%; margin-right: 8px;"></span>
            <div style="display: flex; flex-direction: column;">
              <p class="skeleton-loader" style="font-weight: bold; margin: 0; font-size: 14px; height: 20px; width: 120px;"></p>
              <p class="skeleton-loader" style="margin: 0; font-size: 12px; color: #666; height: 18px; width: 100px;"></p>
            </div>
            <span class="skeleton-loader" style="background-color: #1E7E34; color: white; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 5px; margin-left: auto; height: 24px; width: 80px;"></span>
          </div>
        </div>
        <div class="m-0 mb-3 p-2 row border-bottom">
          <div style="display: flex; align-items: center;">
            <span class="skeleton-loader" style="width: 2em; height: 2em; border-radius: 50%; margin-right: 8px;"></span>
            <div style="display: flex; flex-direction: column;">
              <p class="skeleton-loader" style="font-weight: bold; margin: 0; font-size: 14px; height: 20px; width: 120px;"></p>
              <p class="skeleton-loader" style="margin: 0; font-size: 12px; color: #666; height: 18px; width: 100px;"></p>
            </div>
            <span class="skeleton-loader" style="background-color: #1E7E34; color: white; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 5px; margin-left: auto; height: 24px; width: 80px;"></span>
          </div>
        </div>
        <div class="m-0 mb-3 p-2 row border-bottom">
          <div style="display: flex; align-items: center;">
            <span class="skeleton-loader" style="width: 2em; height: 2em; border-radius: 50%; margin-right: 8px;"></span>
            <div style="display: flex; flex-direction: column;">
              <p class="skeleton-loader" style="font-weight: bold; margin: 0; font-size: 14px; height: 20px; width: 120px;"></p>
              <p class="skeleton-loader" style="margin: 0; font-size: 12px; color: #666; height: 18px; width: 100px;"></p>
            </div>
            <span class="skeleton-loader" style="background-color: #1E7E34; color: white; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 5px; margin-left: auto; height: 24px; width: 80px;"></span>
          </div>
        </div>
      </div>
    `;
  
    employeesSectionDiv.innerHTML = skeletonHTML; // Replace content with skeleton
  
    setTimeout(function() {
      employeesSectionDiv.innerHTML = originalContent; // Restore original content after delay
    }, 5000); // 3-second delay (adjust as needed)
  });
  
  
document.addEventListener('DOMContentLoaded', function() {
    const employeesSectionDiv = document.querySelector('.empTab2'); // Select the main container
  
    if (!employeesSectionDiv) return; // Exit if the element is not found.
  
    const originalContent = employeesSectionDiv.innerHTML; // Store original HTML
  
    const skeletonHTML = `
      <div class="m-0 mt-3 row">
        <div class="col-md-6">
          <p class="fw-bold skeleton-loader" style="height: 20px; width: 100px;"></p>
        </div>
        <div class="col-md-6 text-end mb-3">
          <button class="btn btn-outline-primary btn-sm skeleton-loader" style="height: 30px; width: 100px;"></button>
        </div>
      </div>
  
      <div class="row m-0 py-2 bg-light">
        <div class="col-md-6">
          <p class="mb-0 fw-bold fs14 skeleton-loader" style="height: 20px; width: 80px;"></p>
        </div>
        <div class="col-md-6 text-end">
          <p class="mb-0 fw-bold fs14 skeleton-loader" style="height: 20px; width: 100px;"></p>
        </div>
      </div>
  
      <div class="row m-0 mt-3">
        <div class="m-0 mb-3 p-2 row border-bottom">
          <div style="display: flex; align-items: center;">
            <span class="skeleton-loader" style="width: 2em; height: 2em; border-radius: 50%; margin-right: 8px;"></span>
            <div style="display: flex; flex-direction: column;">
              <p class="skeleton-loader" style="font-weight: bold; margin: 0; font-size: 14px; height: 20px; width: 120px;"></p>
              <p class="skeleton-loader" style="margin: 0; font-size: 12px; color: #666; height: 18px; width: 100px;"></p>
            </div>
            <span class="skeleton-loader" style="background-color: #1E7E34; color: white; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 5px; margin-left: auto; height: 24px; width: 80px;"></span>
          </div>
        </div>
        <div class="m-0 mb-3 p-2 row border-bottom">
          <div style="display: flex; align-items: center;">
            <span class="skeleton-loader" style="width: 2em; height: 2em; border-radius: 50%; margin-right: 8px;"></span>
            <div style="display: flex; flex-direction: column;">
              <p class="skeleton-loader" style="font-weight: bold; margin: 0; font-size: 14px; height: 20px; width: 120px;"></p>
              <p class="skeleton-loader" style="margin: 0; font-size: 12px; color: #666; height: 18px; width: 100px;"></p>
            </div>
            <span class="skeleton-loader" style="background-color: #1E7E34; color: white; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 5px; margin-left: auto; height: 24px; width: 80px;"></span>
          </div>
        </div>
        <div class="m-0 mb-3 p-2 row border-bottom">
          <div style="display: flex; align-items: center;">
            <span class="skeleton-loader" style="width: 2em; height: 2em; border-radius: 50%; margin-right: 8px;"></span>
            <div style="display: flex; flex-direction: column;">
              <p class="skeleton-loader" style="font-weight: bold; margin: 0; font-size: 14px; height: 20px; width: 120px;"></p>
              <p class="skeleton-loader" style="margin: 0; font-size: 12px; color: #666; height: 18px; width: 100px;"></p>
            </div>
            <span class="skeleton-loader" style="background-color: #1E7E34; color: white; font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 5px; margin-left: auto; height: 24px; width: 80px;"></span>
          </div>
        </div>
      </div>
    `;
  
    employeesSectionDiv.innerHTML = skeletonHTML; // Replace content with skeleton
  
    setTimeout(function() {
      employeesSectionDiv.innerHTML = originalContent; // Restore original content after delay
    }, 5000); // 3-second delay (adjust as needed)
  });
  
  // Include the CSS for the skeleton effect (usually in a separate <style> tag or CSS file)
//   const style = document.createElement('style');
//   style.textContent = `
//   .skeleton-loader {
//     background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
//     background-size: 200% 100%;
//     animation: shimmer 1.5s infinite linear;
//     border-radius: 4px;
//     margin: 4px 0;
//   }
  
//   @keyframes shimmer {
//     0% {
//       background-position: 100% 0;
//     }
//     100% {
//       background-position: -100% 0;
//     }
//   }
//   `;
//   document.head.appendChild(style);
  
  e