<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') &mdash; SBC</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{asset('')}}modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    @stack('css')
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
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('layouts.topnav')
            <div class="main-sidebar sidebar-style-2">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>@yield('title')</h1>
                    </div>

                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; {{date('Y')}} Universitas UCIC
                </div>
                <div class="footer-right">
                    Version 1.0 (Beta)
                </div>
            </footer>
        </div>
    </div>

    @stack('modal')

    <!-- General JS Scripts -->
    <script src="{{asset('')}}modules/jquery.min.js"></script>
    <script src="{{asset('')}}modules/popper.js"></script>
    <script src="{{asset('')}}modules/tooltip.js"></script>
    <script src="{{asset('')}}modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{asset('')}}modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="{{asset('')}}modules/moment.min.js"></script>
    <script src="{{asset('')}}js/stisla.js"></script>

    <!-- JS Libraies -->
    @stack('js')
    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{asset('')}}js/scripts.js"></script>
    <script src="{{asset('')}}js/custom.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>

</html>