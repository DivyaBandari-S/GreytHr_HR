<div class="main " style="margin: 10px;background-color:var(--light);">

    <style>
        .bold-items {
            font-weight: bold;
        }

        .noUi-connect {
            background: cornflowerblue !important;
        }

        /* Make the slider handles circular */
        .noUi-handle {
            width: 20px !important;
            height: 20px !important;
            border-radius: 50% !important;
            /* Makes it circular */
            background: cornflowerblue !important;
            border: 2px solid white !important;
            box-shadow: 0px 0px 5px rgba(0, 0, 255, 0.5);
            cursor: pointer;
            top: -12px !important;
            /* Adjust to center vertically */
            transform: translateY(50%);
            /* Ensures perfect centering */
        }

        /* Adjust the position of the handles */
        .noUi-handle:before,
        .noUi-handle:after {
            display: none;
            /* Removes default square indicators */
        }

        .median-longest td {
            border: 1px solid silver;
            color: black;
            font-size: 12px;
            padding: 4px;
            padding-right: 30px;
        }
        .emp-sal1-table th,
        .emp-sal1-table td {
            text-align: center;
            vertical-align: middle;
            color: #3b4452;
            padding: 3px;
            font-size: 12px;
        }

        .emp-sal1-table th {
            font-size: 12px;
            padding: 5px;
            background-color: #EBEFF7;
        }
    </style>
    @if($isShowHelp)
    <div class="help-section d-flex " style="padding: 10px;font-size:11px;background-color:#f5feff">
        <p>The <span class="bold-items"> Salary Revision Analytics </span> page lets you quickly see which employees did <span class="bold-items">NOT</span> get a salary revision in the last 1 to 30 months. You can easily flip the question and ask who are the people who had a salary revision in a given period by using the <span class="bold-items">Revised</span> and <span class="bold-items">Not Revised </span> buttons. Use the slider control to adjust the period for which you want the revision data. </p>
        <span><button style="border: none;color:cornflowerblue;width:max-content;background-color:#f5feff;margin-left:15px;font-weight:bold" wire:click="toogleHelp">Hide Help </button></span>
    </div>
    @else
    <div style="padding:10px;background-color:var(--light);text-align:end">
        <span style="margin-left: auto;"><button style="background-color:white;padding:5px;border:none;font-size:11px;font-weight:bold;color:cornflowerblue;border-radius:5px" wire:click="toogleHelp">Show Help</button></span>
    </div>
    @endif

    <div class="bg-white mt-3" style="padding: 10px;">
        <div class="col-md-12">
            <div class="row d-flex">
                <div style="width:50%">
                    <div class=" d-flex" style="width:100%;justify-content:space-between;align-items:center">
                        <label>Revision not done between
                            <span class="text-primary fw-bold" id="rangeValue">
                                {{ $selectedRange[0] }} to {{ $selectedRange[1] }} months
                            </span>
                        </label>

                        <div class="tabs" style="margin-left:auto ; height:fit-content;margin-bottom:0px">
                            <div class="tabButtons">
                                <button wire:click="changeTab('pending')" class="tab-button {{ $activeTab === 'pending' ? 'active' : '' }}" title="Not Revised">
                                    <img src="{{asset('images/stairs-block.png')}}" alt="">
                                </button>
                                <button wire:click="changeTab('completed')" class="tab-button {{ $activeTab === 'completed' ? 'active' : '' }}" title="Revised">
                                    <img style="height: 10px;" src="{{asset('images/stairs-revised.png')}}" alt="">
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="">
                        <div wire:ignore style="width: 90%;" id="revisionRangeSlider" class="my-3"></div>
                    </div>
                </div>
                <div class="" style="width:fit-content;margin-left:auto">
                    <table class="median-longest">
                        <tr>
                            <td>Longest Revision </td>
                            <td>Median Revision</td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold;">14 months</td>
                            <td style="font-weight: bold;">12 months</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2">
            <div class="table-responsive">
                <table class="table table-bordered emp-sal1-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Number</th>
                            <th>Name</th>
                            <th>Experience</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Revised salary</th>
                            <th>Prior Salary</th>
                            <th>Difference</th>
                            <th>Percet</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
    <!-- noUiSlider JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var slider = document.getElementById('revisionRangeSlider');
            noUiSlider.create(slider, {
        start: [{{$selectedRange[0]}}, {{$selectedRange[1]}}],
        connect: true,
        range: {'min': {{$minMonths}}, 'max': {{$maxMonths}}},
        step: 1,
    });

            slider.noUiSlider.on('update', function(values) {
                console.log(values);
                // Update the displayed range value on the page
                document.getElementById('rangeValue').innerText =
                    Math.round(values[0]) + " to " + Math.round(values[1]) + " months";

                // Use Livewire.dispatch to emit the event to Livewire
                Livewire.dispatch('updateRange', {
                    range: [
                        Math.round(values[0]),
                        Math.round(values[1])
                    ]
                });

            });

        });
    </script>

</div>
