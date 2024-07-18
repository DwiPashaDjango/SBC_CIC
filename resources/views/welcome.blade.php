<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Student Busines Corner &mdash; UCIC</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{asset('')}}modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/fullcalendar/fullcalendar.min.css">

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('')}}css/style.css">
    <link rel="stylesheet" href="{{asset('')}}css/components.css">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
    <style>
        body {
            background-color: #f7f8fd
        }
    </style>
    <style>
        .navbar-nav .nav-link {
            color: #747474
        }
        .navbar-nav .nav-link:hover {
            color: #6777ef
        }

        @media (max-width: 991.98px) {
            .navbar-nav {
                width: 100%;
                display: block;
                background-color: #ffff; 
                padding-top: 20px;
                border-radius: 10px;
                box-shadow: rgba(103, 119, 239, 0.2) rgba(0, 0, 0, 0.03);
            }
            .navbar-nav .nav-item {
                width: 100%;
                padding: 10px;
            }
            .navbar-nav .nav-link {
                width: 100%;
                text-align: left;
                padding-left: 1rem;
                color: #747474
            }
        }
    </style>
</head>

<body class="layout-3">
    <div id="app">
        <nav class="navbar navbar-expand-lg bg-white shadow">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{asset('logo.jpg')}}" alt="Logo" style="height: 40px;">
                </a>
                <button class="navbar-toggler" style="border: none; outline:none;" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars" style="font-size: 20px"></i>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item {{request()->is('/') ? 'active-nav' : ''}}">
                            <a href="{{route('home')}}" class="nav-link"><i class="fas fa-home" style="margin-right: 10px"></i><span>Beranda</span></a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a href="{{route('dashboard')}}" class="nav-link"><i class="fas fa-fire" style="margin-right: 10px"></i><span>Dashboard</span></a>
                            </li>
                        @endauth @guest
                            <li class="nav-item">
                                <a href="{{route('register')}}" class="nav-link"><i class="fas fa-users" style="margin-right: 10px"></i><span>Pendaftaran SBC</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('login')}}" class="nav-link"><i class="fas fa-sign-in-alt" style="margin-right: 10px"></i><span>Login SBC</span></a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-wrapper container">
            <div class="main-content container" style="padding-top:80px;">
                <section class="section mt-3">
                    <div class="section-body">
                        <div class="alert alert-primary alert-has-icon">
                            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                            <div class="alert-body">
                                <div class="alert-title">Studen Businses Corner &mdash; UCIC</div>
                                Selamat Datang Di Student Busines Corner Universitas Catur Insan Cendikia
                            </div>
                        </div>
                        <div class="card shadow">
                            <div class="card-header">
                                <h4>Kalender Penjualan</h4>
                            </div>
                            <div class="card-body">
                                <div id="myEvent"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <x-modal id="action" size="lg">
        <div class="modal-body">
            <div class="table-responsive" id="table-append">

            </div>
        </div>
    </x-modal>

    <!-- General JS Scripts -->
    <script src="{{asset('')}}modules/jquery.min.js"></script>
    <script src="{{asset('')}}modules/popper.js"></script>
    <script src="{{asset('')}}modules/tooltip.js"></script>
    <script src="{{asset('')}}modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{asset('')}}modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="{{asset('')}}modules/moment.min.js"></script>
    <script src="{{asset('')}}js/stisla.js"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{asset('')}}js/scripts.js"></script>
    <script src="{{asset('')}}js/custom.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('')}}modules/fullcalendar/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.js"></script>
    <script src="https://www.sman1empatlawang.sch.id/assets/fullcalendar/locale/id.js"></script>
    <script>
        $("#myEvent").fullCalendar({
            height: 'auto',
            locale: 'id',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            eventSources: [{
                url: "{{route('fetchJadwal')}}"
            }],
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

        document.addEventListener('contextmenu', event => event.preventDefault());

        document.addEventListener('keydown', function(event) {
            if (event.key == 'F12' || (event.ctrlKey && event.shiftKey && event.key == 'I') || (event.ctrlKey && event.key == 'U')) {
                event.preventDefault();
                Swal.fire({
                    imageUrl: "{{asset('amba.jpg')}}",
                    imageHeight: 500,
                    imageAlt: "A tall image"
                });
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'u') {
                event.preventDefault();
                Swal.fire({
                    imageUrl: "{{asset('amba.jpg')}}",
                    imageHeight: 500,
                    imageAlt: "A tall image"
                });
            }
        });
    </script>
</body>

</html>