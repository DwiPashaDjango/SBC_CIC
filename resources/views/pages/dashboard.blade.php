@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{asset('')}}modules/fullcalendar/fullcalendar.min.css">
@endpush

@section('title')
    Dashboard
@endsection

@section('content')
    @role('Mahasiswa')
        <div class="alert alert-primary alert-has-icon">
            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
            <div class="alert-body">
                <div class="alert-title">Info Penting Adik Adik</div>
                Pengajuan Jadwal Pada Sistem Student Busines Corner UCIC Hanya Bisa Di 
                Lakukan Pada Hari Senin - Jumat. Silakan Klik 
                <a href="{{route('mhs.jadwals.index')}}"><b><u>Ajukan Jadwal</u></b></a>
                Untuk Melakukan Pengajuan Jadwal Penjualan
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="myEvent"></div>
            </div>
        </div>
    @endrole

    @role('Admin')
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-2">
                <div class="card-stats">
                    <div class="card-stats-title">
                        Jumlah Pengajuan Penjualan 
                    </div>
                    <div class="card-stats-items">
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{$confirmation}}</div>
                            <div class="card-stats-item-label">Menunggu Konfirmasi</div>
                        </div>
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{$completed}}</div>
                            <div class="card-stats-item-label">Bisa Berjualan</div>
                        </div>
                        <div class="card-stats-item">
                            <div class="card-stats-item-count">{{$canceled}}</div>
                            <div class="card-stats-item-label">Tidak Bisa Berjualan</div>
                        </div>
                    </div>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa-calendar"></i>
                </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pengajuan Penjualan</h4>
                        </div>
                        <div class="card-body">
                            {{ $confirmation + $completed + $canceled }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-2">
                    <div class="card-chart">
                        <canvas id="balance-chart" height="80"></canvas>
                    </div>
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Jumlah Mahasiswa</h4>
                        </div>
                        <div class="card-body">
                            {{$mahasiswa}}
                        </div>
                    </div>
                </div>
            </div>
            <canvas id="sales-chart" class="d-none" height="80"></canvas>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan</h6>
                        <div class="dropdown no-arrow">
                        <a class="dropdown-toggle btn btn-primary btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Filter 
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Pilih Tahun</div>
                                <form id="form-masuk">
                                    <select name="years" id="years" class="form-control">
                                        @for ($tahun = date('Y'); $tahun >= date('Y') - 5; $tahun--)
                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                        @endfor
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="chart-penjualan" height="280"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Kalender Penjualan</h6>
                    </div>
                    <div class="card-body">
                        <div id="myEvent"></div>
                    </div>
                </div>
            </div>
        </div>
    @endrole
@endsection

@push('modal')
    <x-modal id="action" size="lg">
        <div class="modal-body">
            <div class="table-responsive" id="table-append">
                
            </div>
        </div>
    </x-modal> 
@endpush

@push('js')
    <script src="{{asset('')}}modules/fullcalendar/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js"></script>
    <script src="https://www.sman1empatlawang.sch.id/assets/fullcalendar/locale/id.js"></script>
    <script src="{{asset('')}}modules/jquery.sparkline.min.js"></script>
    <script src="{{asset('')}}modules/chart.min.js"></script>

    <!-- Page Specific JS File -->
    <script src="{{asset('')}}js/page/index.js"></script>
    <script>
        $("#myEvent").fullCalendar({
            height: 'auto',
            locale: 'id',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            eventSources: [
                {
                    url: "{{route('fetchJadwal')}}"
                }
            ],
            eventRender: function(event, element) {
                const day = event.start.date();
                if (day % 3 === 0) {
                    element.addClass('bg-danger');
                } else if (day % 3 === 1) {
                    element.addClass('bg-warning');
                } else {
                    element.addClass('bg-primary');
                }

                element.qtip({ 
                    content: {
                        title: event.title,
                        nim: event.nim,
                        kios: event.kios,
                        kategori: event.kategori,
                        stand: event.stand,
                        tgl_penjualan: event.tgl_penjualan,
                        tgl_akhir: event.tgl_akhir,
                    },
                    style: {
                        classes: 'qtip-dark qtip-shadow qtip-rounded'
                    }
                });
            },
            eventClick: function(event) {
                $('#action').modal('show');
                $("#actionLabel").html(event.title);
                let html = '';
                html += `<table class="table table-bordered table-striped">
                            <tr>
                                <td style="width: 30%">Tanggal Penjualan</td>
                                <td style="width: 5%">:</td>
                                <td>${event.tgl_penjualan} - ${event.tgl_akhir}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%">Nama Mahasiswa</td>
                                <td style="width: 5%">:</td>
                                <td>${event.name}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%">Nomor Induk Mahasiswa</td>
                                <td style="width: 5%">:</td>
                                <td>${event.nim}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%">Nama Kios</td>
                                <td style="width: 5%">:</td>
                                <td>${event.kios}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%">Kategori Makanan</td>
                                <td style="width: 5%">:</td>
                                <td>${event.kategori}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%">Stand/Both</td>
                                <td style="width: 5%">:</td>
                                <td>${event.stand}</td>
                            </tr>
                        </table>`;
                $("#table-append").html(html)
            }
        });
    </script>
    @role('Admin')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var currentYear = new Date().getFullYear();
            $('#years').val(currentYear);
            fetchJadwalMonth(currentYear);

            $("#years").change(function(e) {
                e.preventDefault();
                var years = $(this).val();
                fetchJadwalMonth(years);
            })

            function fetchJadwalMonth(years) {
                $.ajax({
                    url: "{{route('getJadwalPengajuan')}}",
                    method: 'POST',
                    data: {years: years},
                    dataType: 'json',
                    success: function(data) {
                        if(window.myChart instanceof Chart)
                        {
                            window.myChart.destroy();
                        }

                        const monthsSales = data.sales.map(entry => entry.month);
                        const countsSales = data.sales.map(entry => entry.count);
                        const countsNotSales = data.not_sales.map(entry => entry.count);

                        var ctx = document.getElementById('chart-penjualan').getContext('2d');
                        window.myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: monthsSales.map(month => getMonthName(month)),
                                datasets: [
                                        {
                                            label: 'Jumlah Mahasiswa Berjualan',
                                            data: countsSales,
                                            backgroundColor: 'rgba(63,82,227,.8)',
                                            borderWidth: 0,
                                            borderColor: 'transparent',
                                            pointBorderWidth: 0,
                                            pointRadius: 3.5,
                                            pointBackgroundColor: 'transparent',
                                            pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
                                        },
                                        {
                                            label: 'Jumlah Mahasiswa Tidak Bisa Berjualan',
                                            data: countsNotSales,
                                            borderWidth: 2,
                                            backgroundColor: 'rgba(254,86,83,.7)',
                                            borderWidth: 0,
                                            borderColor: 'transparent',
                                            pointBorderWidth: 0,
                                            pointRadius: 3.5,
                                            pointBackgroundColor: 'transparent',
                                            pointHoverBackgroundColor: 'rgba(254,86,83,.7)',
                                        }
                                    ]
                            },
                        options: {
                                animations: {
                                    tension: {
                                        duration: 1000,
                                        easing: 'linear',
                                        from: 1,
                                        to: 0,
                                        loop: true
                                    }
                                    },
                                scales: {
                                    y: { 
                                        min: 0,
                                        max: 100,
                                    },
                                    yAxes: [{
                                        display: true,
                                        ticks: {
                                            steps: 10,
                                            stepValue: 5,
                                            max: data.mhs
                                        }
                                    }]
                                }
                            }
                        });
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }

            function getMonthName(month) {
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                return monthNames[month - 1];
            }
        </script>
    @endrole
@endpush