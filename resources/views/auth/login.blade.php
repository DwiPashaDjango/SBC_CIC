<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; SBC</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{asset('')}}modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('')}}modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{asset('')}}modules/bootstrap-social/bootstrap-social.css">

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
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 mt-5">
                        <div class="login-brand">
                            <img src="{{asset('')}}logo.jpg" alt="logo" width="200" class="">
                        </div>

                        @if (session()->has('message'))
                            <div class="alert alert-danger">
                                {{session()->get('message')}}
                            </div>
                        @endif

                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Student Busines Corner</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{route('login.post')}}" novalidate="">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Nomor Induk Mahasiswa/Email</label>
                                        <input id="username" type="text" value="{{old('username')}}" class="form-control @error('username') is-invalid @enderror" name="username" tabindex="1" required>
                                        @error('username')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                        </div>
                                        <input id="password" type="password" class="form-control @error('username') is-invalid @enderror" name="password" tabindex="2" required>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                                            <label class="custom-control-label" for="remember-me">Remember Me</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mt-5 text-muted text-center">
                            Belum Mempunyai Akun Kios? <a href="{{route('register')}}">Daftar</a>
                        </div>
                        <div class="simple-footer">
                            Copyright &copy; Studen Busines Corner UCIC {{date('Y')}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

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
</body>

</html>