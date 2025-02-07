<div>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Salary Revision</title>
    </head>

    <body>
        <div>

            @if ($salaryRevisions->isEmpty())

                <div class="revisionnorecords">
                    <img class="revisionnorecordsimage"
                        src="https://static.vecteezy.com/system/resources/thumbnails/007/872/974/small/file-not-found-illustration-with-confused-people-holding-big-magnifier-search-no-result-data-not-found-concept-can-be-used-for-website-landing-page-animation-etc-vector.jpg"
                        alt="">
                    <p>No data found</p>
                </div>
            @else
                <div class="col revisioncard">
                    <div class="last-revision-duration">
                        <p class="text-muted text-secondary">Duration since last revision</p>
                        <p>
                            <b>{{ $decryptedData[0]['time_gap'] }}</b>
                        </p>
                        <hr>
                    </div>

                <div class="row last-revision-content">
                    <div class="col-md-6 ">
                        <p class="text-muted text-secondary ">Last Revision Period</p>
                        <p><b>{{ \Carbon\Carbon::parse($decryptedData[0]['revision_date'])->format('d-m-Y') }}</b></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-yellow text-secondary ">Last Revision Percentage</p>
                        <p>
                            @if($decryptedData[0]['percentage_change']>0)
                            <b class="posSalaryPercentage">+{{$decryptedData[0]['percentage_change']}}%</b>
                            @else
                            <b class="negSalaryPercentage">{{$decryptedData[0]['percentage_change']}}%</b>
                            @endif
                        </p>
                    </div>
                </div>
            </div>



                <br>

                <!-- line chart -->

                <!-- Blade view file -->
                <div class="revisionareachart">
                    <canvas height="100" id="salaryRevisionChart"></canvas>
                </div>

            <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const chartData = @json($this->getChartData());

                        const ctx = document.getElementById('salaryRevisionChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line', // Use 'line' for area effect
                            data: {
                                labels: chartData.labels,
                                datasets: [{
                                    label: 'Revised Salary',
                                    data: chartData.data,
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Area color with transparency
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1,
                                    fill: true, // To create the area effect
                                }]
                            },
                            options: {
                                scales: {
                                    x: {
                                        grid: {
                                            display: false, // Hide x-axis grid lines
                                        },
                                        title: {
                                            display: true,
                                            text: 'Revision Date'
                                        }
                                    },
                                    y: {
                                        grid: {
                                            display: false, // Hide x-axis grid lines
                                        },
                                        title: {
                                            display: true,
                                            text: 'Revised Salary'
                                        }
                                    }
                                },
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    }
                                }
                            }
                        });
                    });
                </script>

                <br><br>



                <div class="row">
                    <table class="salaryRevisionStable">
                        <thead>
                            <tr>
                                <th class=" CTCRevisionDetails">
                                    CTC Revision Details
                                </th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <th class="text-right RevisionDifference">
                                    Revision Difference
                                </th>
                            </tr>
                        </thead>

                    </table>
                    <table class="salaryRevisionStable  table-responsive">
                        <thead>
                            <tr>
                                <th>Last Revision</th>
                                <th>Payout Month</th>
                                <th>Revised Monthly CTC </th>
                                <th>Previous Monthly CTC</th>
                                <th>Duration between revisions</th>
                                <th>Amount in ₹</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($decryptedData as $revisionData)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('d M, Y') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($revisionData['revision_date'])->format('M, Y') }}</td>
                                    <td>{{ number_format($revisionData['revised_ctc'] / 12, 2) }}</td>
                                    <td>{{ number_format($revisionData['current_ctc'] / 12, 2) }}</td>
                                    <td>{{ $revisionData['time_gap'] }}</td>
                                    @if ($loop->last)
                                        <td>0.00</td>
                                    @else
                                        <td>{{ number_format($revisionData['revised_ctc'] / 12 - $revisionData['current_ctc'] / 12, 2) }}
                                        </td>
                                    @endif
                                    @if ($revisionData['percentage_change'] > 0)
                                        <td class="posSalaryPercentage">+{{ $revisionData['percentage_change'] }}%</td>
                                    @elseif($revisionData['percentage_change'] < 0)
                                        <td class="negSalaryPercentage">{{ $revisionData['percentage_change'] }}%</td>
                                    @else
                                        <td class="salaryPercentage0">-</td>
                                    @endif
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>

            @endif
        </div>

    </body>

    </html>

</div>
