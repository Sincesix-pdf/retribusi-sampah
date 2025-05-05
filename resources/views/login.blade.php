<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/style.css') }}">
  </head>
  <body>

  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
        <img src="/gambar/login.png" alt="logo" class="img-fluid">
        </div>
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">

              <div class="mb-4">
                <h3>Login</h3>
                <p class="mb-4">Silakan masuk dengan akun yang sudah terdaftar.</p>
              </div>

              {{-- Error message --}}
              @if (session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif
              
              {{-- Success message --}}
              @if (session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif

              <form action="{{ route('login.process') }}" method="POST">
                @csrf
                <div class="form-group first">
                  <label for="email">Email</label>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                         id="email" value="{{ old('email') }}" required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group last mb-4">
                  <label for="password">Password</label>
                  <input type="password" name="password" class="form-control" id="password" required>
                </div>

                <!-- <div class="d-flex mb-5 align-items-center">
                  <label class="control control--checkbox mb-0">
                    <span class="caption">Ingat saya</span>
                    <input type="checkbox" name="remember"/>
                    <div class="control__indicator"></div>
                  </label>
                  <span class="ml-auto"><a href="#" class="forgot-pass">Lupa Password?</a></span> 
                </div> -->

                <input type="submit" value="Login" class="btn btn-block btn-login">

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/login/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('js/login/popper.min.js') }}"></script>
  <script src="{{ asset('js/login/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/login/main.js') }}"></script>
  </body>
</html>
