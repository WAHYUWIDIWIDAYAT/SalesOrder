<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <!-- <link rel="stylesheet" href="fonts/icomoon/style.css"> -->
    <link rel="stylesheet" href="{{ asset('form-login/fonts/icomoon/style.css') }}">

    <!-- <link rel="stylesheet" href="css/owl.carousel.min.css"> -->
    <link rel="stylesheet" href="{{ asset('form-login/css/owl.carousel.min.css') }}">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="{{ asset('form-login/css/bootstrap.min.css') }}">
    
    <!-- Style -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link rel="stylesheet" href="{{ asset('form-login/css/style.css') }}">

    <title>Login</title>
  </head>
  <body>
  

  <div class="half">
    <div class="bg order-1 order-md-2" style="background-color: #F5EEE6;">
      <div class="hsCont">
        <div class="img" style="background-image: url('{{ asset('form-login/images/bg_1.jpg') }}');"></div>
        <div class="img" style="background-image: url('{{ asset('form-login/images/bg_2.jpg') }}');"></div>
        <div class="img" style="background-image: url('{{ asset('form-login/images/bg_3.jpg') }}');"></div>
        <div class="img" style="background-image: url('{{ asset('form-login/images/bg_4.jpg') }}');"></div>
      </div>
    </div>
    <div class="contents order-2 order-md-1">

  
      <div class="container">
        
        <div class="row align-items-center justify-content-center">
          <div class="col-md-6">
            <div class="form-block">
              <div class="text-center mb-5">
              <h3><strong>PT. Karya Abadi Jaya</strong></h3>
        
              <!-- <p class="mb-4">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p> -->
              </div>
              
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="latitude" id="latitude" value="">
                <input type="hidden" name="longitude" id="longitude" value="">
                <div class="form-group first">
                  <label for="username">Email</label>
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                <div class="form-group last mb-3">
                  <label for="password">Password</label>
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
                
                
                <div class="d-sm-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-3 mb-sm-0"><span class="caption">Remember me</span>
                    <input type="checkbox" checked="checked"/>
                    <div class="control__indicator"></div>
                </label>
                  @if (Route::has('password.request'))
                  <span class="ml-auto"><a href="{{ route('password.request') }}" class="forgot-pass">Forgot Password</a></span> 
                  @endif
                </div>

                <button type="submit" class="btn btn-primary">
                  {{ __('Login') }}
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div>


    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Check if geolocation is supported in the browser
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Set the latitude and longitude values in the hidden input fields
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
        });
    } else {
        console.log("Geolocation is not supported in this browser.");
    }
</script>
  </body>
</html>