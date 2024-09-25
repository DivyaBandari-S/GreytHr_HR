<div class="employee-leave-hr-main">
    <div class="row employee-leave-searchrow ">
        <p class="employee-leave-start-para">Start searching to see specific employee details here</p>
        <div class="employee-search-hr d-flex">
            <label for="" class="search-employee-type-leave">Employee Type:</label>
            <select   class=" custom-select   Employee-select-leave" >
                <option   value="active">Current Employees</option>
                <option  value="non-active">Past Employees</option>


            </select>
        </div>
        <div>
            <label class="search-employee-type-leave" for="">Search Employee</label>
        </div>
        <div class="d-flex Employee-details-hr">
            <div class="d-flex  Employee-details-img-details-hr">
                <img style="width: 30px; height:30px;border-radius: 50%;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBrGPJ2q7Abf54iQOe8H_w11p07aS1mN11YXa9AJTfO3i_mPSSu3P5sR-VGxruGswg5s8&usqp=CAU" alt="">
                <div style="margin-left: 8px; color:var(--label-color)">
                    <p class="Emp-name-leave-details">Bandari Divya</p>
                    <p class="Emp-id-leave-details">#XSS-0480 </p>
                </div>
            </div>
            <div style="margin-left: auto;">
                <p style="margin-bottom: 0px;cursor:pointer; font-weight: 500;font-size:20px">x</p>
            </div>
        </div>
        <div class="d-flex Employee-details-hr">
            <div class="d-flex  Employee-details-img-details-hr">
                <img style="width: 30px; height:30px;border-radius: 50%;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBrGPJ2q7Abf54iQOe8H_w11p07aS1mN11YXa9AJTfO3i_mPSSu3P5sR-VGxruGswg5s8&usqp=CAU" alt="">
                <div style="margin-left: 8px; color:var(--label-color)">
                    <p class="Emp-name-leave-details">Bandari Divya</p>
                    <p class="Emp-id-leave-details">#XSS-0480 </p>
                </div>
            </div>
            <div style="margin-left: auto;">
                <p style="margin-bottom: 0px;cursor:pointer; font-weight: 500;font-size:20px">x</p>
            </div>
        </div>
    </div>
    <div class="row mt-4 Employee-leave-table-row">

            <div style="background-color:white;height:30px;align-items:start" class="d-flex">
            <ul class="d-flex leave-balance-lave-types">
                <button wire:click="tabs('overview')">Overview</button>
                <button wire:click="tabs('lop')">LOP</button>
                <button wire:click="tabs('cl')">ML</button>
                <button wire:click="tabs('cl')">CL</button>
                <button wire:click="tabs('el')">EL</button>
                <button wire:click="tabs('sl')">SL</button>
                <button wire:click="tabs('mal')">MAL</button>
                <button wire:click="tabs('all')">All</button>
            </ul>
            <div class="Leave-balance-buttons mt-3" >
                <button>Post Leave Transaction</button>
                <button>Aply On Behalf</button>
                <button>Download</button>
            </div>
            </div>


    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-9 Employee-leave-table-maindiv">
            Scheme Name - General Scheme
        </div>
        <div class="col-md-1"></div>
    </div>
</div>
