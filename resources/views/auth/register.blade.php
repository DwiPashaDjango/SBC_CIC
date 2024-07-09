<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Pendaftaran Akun Kios &mdash; SBC</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('')}}modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('')}}modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{asset('')}}modules/jquery-selectric/selectric.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('')}}css/style.css">
  <link rel="stylesheet" href="{{asset('')}}css/components.css">
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
             <img src="{{asset('')}}logo.jpg" alt="logo" width="200" class="">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Pendaftaran Akun Kios Student Busines Corner UCIC</h4></div>

              <div class="card-body">
                <form method="POST" action="{{route('register.post')}}">
                    @csrf
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="nim">Nomor Induk Mahasiswa <span class="text-danger">*</span></label>
                      <input id="nim" type="number" class="form-control @error('nim') is-invalid @enderror" name="nim" value="{{old('nim')}}">
                      @error('nim')
                          <span class="invalid-feedback">
                            {{$message}}
                          </span>   
                      @enderror
                    </div>
                    <div class="form-group col-6">
                      <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                      <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}">
                        @error('name')
                            <span class="invalid-feedback">
                            {{$message}}
                            </span>   
                        @enderror
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}">
                    @error('email')
                        <span class="invalid-feedback">
                        {{$message}}
                        </span>   
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="email">Prodi/Hima <span class="text-danger">*</span></label>
                    <input id="text" type="text" class="form-control @error('prodi') is-invalid @enderror" name="prodi" value="{{old('prodi')}}">
                    @error('prodi')
                        <span class="invalid-feedback">
                        {{$message}}
                        </span>   
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="email">Nama Kios <span class="text-danger">*</span></label>
                    <input id="kios" type="text" class="form-control @error('kios') is-invalid @enderror" name="kios" value="{{old('kios')}}">
                    @error('kios')
                        <span class="invalid-feedback">
                        {{$message}}
                        </span>   
                    @enderror
                  </div>

                    <div class="form-group">
                        <label>Jenis Makana Yang Di Jual <span class="text-danger">*</span></label>
                        <select class="form-control @error('kategori_products') is-invalid @enderror" name="kategori_products">
                            <option value="">- Pilih -</option>
                            @foreach ($kategori as $item)
                                <option value="{{$item->id}}" {{old('kategori_products') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('kategori_products')
                            <span class="invalid-feedback">
                            {{$message}}
                            </span>   
                        @enderror
                    </div>

                  <div class="row">
                    <div class="form-group col-6">
                      <label for="password" class="d-block">Password <span class="text-danger">*</span></label>
                      <input id="password" type="password" class="form-control pwstrength @error('password') is-invalid @enderror" data-indicator="pwindicator" name="password">
                      <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                      </div>
                        @error('password')
                            <span class="invalid-feedback">
                            {{$message}}
                            </span>   
                        @enderror
                    </div>
                    <div class="form-group col-6">
                      <label for="password2" class="d-block">Konfirmasi Password <span class="text-danger">*</span></label>
                      <input id="password2" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                        @error('password_confirmation')
                            <span class="invalid-feedback">
                            {{$message}}
                            </span>   
                        @enderror
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" name="agree" class="custom-control-input" id="agree">
                      <label class="custom-control-label" for="agree">Saya Menyetujui Persyaratan Pendaftaran Ini</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                      Daftar
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="mt-5 text-muted text-center">
                Sudah Punya Akun Kios? <a href="{{route('login')}}">Login</a>
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
  <script src="{{asset('')}}modules/jquery-pwstrength/jquery.pwstrength.min.js"></script>
  <script src="{{asset('')}}modules/jquery-selectric/jquery.selectric.min.js"></script>

  <!-- Page Specific JS File -->
  <script src="{{asset('')}}js/page/auth-register.js"></script>
  
  <!-- Template JS File -->
  <script src="{{asset('')}}js/scripts.js"></script>
  <script src="{{asset('')}}js/custom.js"></script>
</body>
</html>