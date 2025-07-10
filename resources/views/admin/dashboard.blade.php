@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />
@endsection

@push('page-js')
    @include('layouts.script_datatables')
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- View sales -->
        <div class="col-xl-4 mb-4 col-lg-5 col-12">
            <div class="card">
              <div class="d-flex align-items-end row">
                <div class="col-7">
                  <div class="card-body text-nowrap">
                    <h5 class="card-title mb-0">Congratulations John! 🎉</h5>
                    <p class="mb-2">Best seller of the month</p>
                    <h4 class="text-primary mb-1">$48.9k</h4>
                    <a href="javascript:;" class="btn btn-primary">View Sales</a>
                  </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                  <div class="card-body pb-0 px-0 px-md-4">
                    <img
                      src="../../assets/img/illustrations/card-advance-sale.png"
                      height="140"
                      alt="view sales"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- View sales -->

          <!-- Statistics -->
          <div class="col-xl-8 mb-4 col-lg-7 col-12">
            <div class="card h-100">
              <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                  <h5 class="card-title mb-0">Statistics</h5>
                  <small class="text-muted">Updated 1 month ago</small>
                </div>
              </div>
              <div class="card-body">
                <div class="row gy-3">
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                      <div class="badge rounded-pill bg-label-primary me-3 p-2">
                        <i class="ti ti-chart-pie-2 ti-sm"></i>
                      </div>
                      <div class="card-info">
                        <h5 class="mb-0">{{ $summary['todayOrders'] }}</h5>
                        <small>Today Orders</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-danger me-3 p-2">
                            <i class="ti ti-shopping-cart ti-sm"></i>
                          </div>
                      <div class="card-info">
                        <h5 class="mb-0">{{ $summary['ongoingOrders'] }}</h5>
                        <small>Ongoing Orders</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                        <div class="badge rounded-pill bg-label-info me-3 p-2">
                            <i class="ti ti-users ti-sm"></i>
                        </div>
                      
                      <div class="card-info">
                        <h5 class="mb-0">{{ $summary['availableDrivers'] }}</h5>
                        <small>Available Drivers</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                      <div class="badge rounded-pill bg-label-success me-3 p-2">
                        <i class="ti ti-currency-dollar ti-sm"></i>
                      </div>
                      <div class="card-info">
                        <h5 class="mb-0">{{ number_format($summary['todayRevenue'], 2) }}</h5>
                        <small>Today Revenue</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--/ Statistics -->
    </div>

    <div class="row mt-2">
        <!-- Order Trend Chart -->
        
        <div class="col-xl-7 col-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Trend</h5>
                </div>
                <div class="card-body">
                    <div id="order-trend-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-5">
            <div class="card">
                <div class="card-header">
                  <h5 class="card-title mb-0">Driver Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-3">
                                    <span class="badge bg-label-success p-2 rounded-circle">
                                        <i class="ti ti-user-check fs-4"></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $driverStatus['available'] }}</h4>
                                    <small class="text-muted">Available</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-3">
                                    <span class="badge bg-label-primary p-2 rounded-circle">
                                        <i class="ti ti-car fs-4"></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $driverStatus['on_duty'] }}</h4>
                                    <small class="text-muted">On Duty</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-3">
                                    <span class="badge bg-label-danger p-2 rounded-circle">
                                        <i class="ti ti-user-x fs-4"></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $driverStatus['unavailable'] }}</h4>
                                    <small class="text-muted">Unavailable</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="table-responsive card-datatable" style="padding-bottom: 0% !important;">
                    <table class="table border-top">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td><span class="truncate">{{ $order->order_number }}</span></td>
                                    <td>{{ $order->created_at->translatedFormat('l, d F Y, H:i') }}</td>
                                    <td class="text-center">
                                        <a class="text-body" href="{{ route('admin.orders.show', $order) }}"><i class="ti ti-edit ti-sm me-2"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.send_notif') }}" id="form_data" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="description" class="form-label">Contoh Pesan</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-primary me-2" onclick="joss()">
                        <i class='bx bx-save me-1'></i> Joss!
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('vendor-js')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@push('page-js')
    <script>
        let cardColor, headingColor, labelColor, borderColor, legendColor;

        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            headingColor = config.colors_dark.headingColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            headingColor = config.colors.headingColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            borderColor = config.colors.borderColor;
        }
         // Line Chart
        // --------------------------------------------------------------------
        const lineChartEl = document.querySelector('#order-trend-chart'),
            lineChartConfig = {
            chart: {
                height: 400,
                type: 'line',
                parentHeightOffset: 0,
                zoom: {
                enabled: false
                },
                toolbar: {
                show: false
                }
            },
            series: [
                {
                    data: {!! json_encode($orderTrend['data']) !!}
                }
            ],
            markers: {
                strokeWidth: 7,
                strokeOpacity: 1,
                strokeColors: [cardColor],
                colors: [config.colors.warning]
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            colors: [config.colors.warning],
            grid: {
                borderColor: borderColor,
                xaxis: {
                lines: {
                    show: true
                }
                },
                padding: {
                top: -20
                }
            },
            tooltip: {
                custom: function ({ series, seriesIndex, dataPointIndex, w }) {
                return '<div class="px-3 py-2">' + '<span>' + series[seriesIndex][dataPointIndex] + '%</span>' + '</div>';
                }
            },
            xaxis: {
                categories: {!! json_encode($orderTrend['categories']) !!},
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '13px'
                    }
                }
            },
            yaxis: {
                labels: {
                style: {
                    colors: labelColor,
                    fontSize: '13px'
                }
                }
            }
            };
        if (typeof lineChartEl !== undefined && lineChartEl !== null) {
            const lineChart = new ApexCharts(lineChartEl, lineChartConfig);
            lineChart.render();
        }

        function joss(){
            $.ajax({
                type: "POST",
                url: "{{ route('admin.send_notif') }}",
                data: $('#form_data').serialize(),
                // beforeSend: function(){
                //     small_loader_open('form_data');
                // },
                success: function (s) {
                    console.log(s);
                },
                error: function(e){
                    sw_multi_error(e);
                    // small_loader_close('form_data');
                },
                complete: function(){
                    // small_loader_close('form_data');
                }
            });
        }

    </script>
@endpush
