
@extends('layouts.main')

@section('content')
    <h1>Performance - {{ $item->name }}</h1>

    <div class="row">
        <div class="col-sm-12 col-md-2 col-lg-2">
            <div class="card">
                <div class="card-body">
                    <div class='logo'>
                        <div class="p-3">
                            <small>Powered by</small>
                            <img src='{{ url('assets/omada-logo.png') }}' alt='' style="width: 80px !important">
                        </div>
                    </div>

                    <div class='table-responsive'>
                        <table class='table table-striped'>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href='{{ url('/omada-sites-statistics', $item->siteId) }}' class='{{ request()->is('omada-sites-statistics/*') ? 'fw-bold text-success text-decoration-none' : '' }} text-dark text-decoration-none'>
                                            <i class='fas fa-box'></i> Statistics
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href='{{ url('devices/'.$item->siteId) }}' class='{{ request()->is('devices/*', 'trash-devices', 'create-devices', 'show-devices/*', 'edit-devices/*', 'delete-devices/*', 'devices-search*') ? 'fw-bold text-success text-decoration-none' : '' }} text-dark text-decoration-none'>
                                            <i class="fas fa-desktop"></i> Devices
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href='{{ url('clients/'.$item->siteId) }}' class='{{ request()->is('clients/*', 'trash-clients', 'create-clients', 'show-clients/*', 'edit-clients/*', 'delete-clients/*', 'clients-search*') ? 'fw-bold text-success text-decoration-none' : '' }} text-dark text-decoration-none'>
                                            <i class="fas fa-users"></i> Clients
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href='{{ url('insights/'.$item->siteId) }}' class='{{ request()->is('insights/*', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'fw-bold text-success text-decoration-none' : '' }} text-dark text-decoration-none'>
                                            <i class="fas fa-chart-line"></i> Insights
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href='{{ url('logs/'.$item->siteId) }}' class='{{ request()->is('logs/*', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'fw-bold text-success text-decoration-none' : '' }} text-dark text-decoration-none'>
                                            <i class="fas fa-clipboard-list"></i> Logs
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href='{{ url('customers') }}' class='{{ request()->is('customers', 'trash-customers', 'create-customers', 'show-customers/*', 'edit-customers/*', 'delete-customers/*', 'customers-search*') ? 'fw-bold text-success text-decoration-none' : '' }} text-dark text-decoration-none'>
                                            <i class="fas fa-file-alt"></i> Reports
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-10 col-lg-10">
            <div class='card'>
                <div class='card-body'>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>SFP WAN/LAN1</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>SFP WAN/LAN2</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>WAN3</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>WAN/LAN4</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN5</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN6</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN7</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN8</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN9</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN10</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN11</button>
                            <button type='button' style="font-size: 12px" class='p-1 btn btn-outline-secondary'>LAN12</button>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <form action='{{ url('/customers-filter') }}' method='get'>
                                <div class='input-group'>
                                    <input type='date' class='form-control' id='from' name='from' required> 
                                    <b class='pt-2'>- to -</b>
                                    <input type='date' class='form-control' id='to' name='to' required>
                                    <div class='input-group-append'>
                                        <button type='submit' class='btn btn-primary form-control'><i class='fas fa-filter'></i> Filter</button>
                                    </div>
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
        
                    <div class="p-5">
                        <h5>User Counts</h5>
                        <div id="lineChartStatisticsUserCounts"></div>
                    </div>
        
                    <div class="p-5">
                        <h5>Usage (%)</h5>
                        <div id="lineChartStatisticsUsage"></div>
                    </div>
        
                    <div class="p-5">
                        <h5>Traffic (Bytes)</h5>
                        <div id="lineChartStatisticsTraffic"></div>
                    </div>
        
                    <div class="p-5">
                        <h5>Packets</h5>
                        <div id="lineChartStatisticsPackets"></div>
                    </div>
        
                </div>
            </div>
        </div>
    </div>

    <script src='{{ url('assets/jquery/jquery.min.js') }}'></script>
    <script>
        $(document).ready(function () {
            // Line Chart Statistics - User Counts
            var lineChartStatisticsUserCountsOptions = {
                chart: {
                    type: 'line',
                    height: 300,
                    background: '#ffffff',  // White background for the entire chart area
                    borderRadius: 10,        // Rounded corners for the chart
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.1
                    }
                },
                stroke: {
                    curve: 'smooth'  // This makes the lines smooth
                },
                colors: ["#5C78FB"],  // Consistent color for user counts chart
                series: [
                    {
                        name: 'Active Users',  // Series name
                        data: [50, 60, 55, 23, 65, 34, 90, 100, 130]  // Hardcoded data
                    }
                ],
                xaxis: {
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]  // X-axis categories
                },
                grid: {
                    show: true, // Show grid lines
                    borderColor: '#ddd',  // Light border color for grid lines
                    strokeDashArray: 0,   // Solid grid lines
                    position: 'back',      // Place the grid behind the chart
                    xaxis: {
                        lines: {
                            show: true,   // Show grid lines for x-axis
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true,   // Show grid lines for y-axis
                        }
                    }
                },
            }

            var lineChartStatisticsUserCounts = new ApexCharts(document.querySelector("#lineChartStatisticsUserCounts"), lineChartStatisticsUserCountsOptions);
            lineChartStatisticsUserCounts.render();

            // ==========================================================================================

            // Line Chart Statistics - Usage
            var lineChartStatisticsUsageOptions = {
                chart: {
                    type: 'line',
                    height: 300,
                    background: '#ffffff',
                    borderRadius: 10,
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.1
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ["#FFA01B", "#FFDB1B"],  // Consistent color for usage chart
                series: [
                    {
                        name: 'CPU',  // Series name
                        data: [20, 30, 35, 45, 55, 60, 65, 80, 95]  // Hardcoded data
                    },
                    {
                        name: 'Memory',  // Series name
                        data: [25, 40, 40, 60, 72, 52, 85, 92, 110]  // Hardcoded data
                    }
                ],
                xaxis: {
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]  // X-axis categories
                },
                grid: {
                    show: true, 
                    borderColor: '#ddd',
                    strokeDashArray: 0,   
                    position: 'back',      
                    xaxis: {
                        lines: {
                            show: true,  
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true,   
                        }
                    }
                }
            }

            var lineChartStatisticsUsage = new ApexCharts(document.querySelector("#lineChartStatisticsUsage"), lineChartStatisticsUsageOptions);
            lineChartStatisticsUsage.render();

            // ==========================================================================================

            // Line Chart Statistics - Traffic
            var lineChartStatisticsTrafficOptions = {
                chart: {
                    type: 'line',
                    height: 300,
                    background: '#ffffff',
                    borderRadius: 10,
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.1
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ["#FFD700", "#4B0082"],  // Consistent color for traffic chart
                series: [
                    {
                        name: 'Inbound Traffic',  // Series name
                        data: [334, 232, 343, 545, 444, 343, 223, 232, 343]  // Hardcoded data
                    },
                    {
                        name: 'Outbound Traffic',  // Series name
                        data: [444, 343, 343, 454, 343, 334, 343, 343, 343]  // Hardcoded data
                    }
                ],
                xaxis: {
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]  // X-axis categories
                },
                grid: {
                    show: true, 
                    borderColor: '#ddd',
                    strokeDashArray: 0,   
                    position: 'back',      
                    xaxis: {
                        lines: {
                            show: true,  
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true,   
                        }
                    }
                }
            }

            var lineChartStatisticsTraffic = new ApexCharts(document.querySelector("#lineChartStatisticsTraffic"), lineChartStatisticsTrafficOptions);
            lineChartStatisticsTraffic.render();

            // ==========================================================================================

            // Line Chart Statistics - Packets
            var lineChartStatisticsPacketsOptions = {
                chart: {
                    type: 'line',
                    height: 300,
                    background: '#ffffff',
                    borderRadius: 10,
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 3,
                        opacity: 0.1
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ["#00FF00", "#8A2BE2"],  // Consistent color for packets chart
                series: [
                    {
                        name: 'Sent Packets',  // Series name
                        data: [100, 300, 234, 111, 433, 343, 444, 232, 111]  // Hardcoded data
                    },
                    {
                        name: 'Received Packets',  // Series name
                        data: [112, 211, 233, 443, 334, 333, 232, 434, 544]  // Hardcoded data
                    }
                ],
                xaxis: {
                    categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999]  // X-axis categories
                },
                grid: {
                    show: true, 
                    borderColor: '#ddd',
                    strokeDashArray: 0,   
                    position: 'back',      
                    xaxis: {
                        lines: {
                            show: true,  
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true,   
                        }
                    }
                }
            }

            var lineChartStatisticsPackets = new ApexCharts(document.querySelector("#lineChartStatisticsPackets"), lineChartStatisticsPacketsOptions);
            lineChartStatisticsPackets.render();
        })
    </script>
@endsection
