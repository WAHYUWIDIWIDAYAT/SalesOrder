@extends('layouts.admin')

@section('title')
    <title>Profile</title>
@endsection

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account /</span> Create</h4>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="row">
      <div class="col-md-12">
      <form id="form" action="{{ route('account.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
          <h5 class="card-header">Profile Details</h5>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img
                src="{{ asset('admin/assets/img/backgrounds/no-image.jpg') }}"
                alt="user-avatar"
                class="d-block rounded"
                height="100"
                width="100"
                id="images"
              />
              <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                <span class="d-none d-sm-block">Upload new photo</span>
                <i class="bx bx-upload d-block d-sm-none"></i>
                <input type="file" name="image" id="upload" style="display: none;" />
                </label>
                <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                <i class="bx bx-reset d-block d-sm-none"></i>
                <span class="d-none d-sm-block" id="reset">Reset</span>
                </button>
                <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
            </div>
            </div>
          </div>
          <hr class="my-0" />
          <div class="card-body">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="firstName" class="form-label">Name</label>
                  <input
                    class="form-control"
                    type="text"
                    id="firstName"
                    name="name"
                    autofocus
                  />
                  <p class="text-danger">{{ $errors->first('name') }}</p>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    class="form-control"
                    type="text"
                    id="email"
                    name="email"  
                    placeholder="john.doe@example.com"
                  />
                  <p class="text-danger">{{ $errors->first('email') }}</p>
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="phoneNumber">Phone Number</label>
                  <div class="input-group input-group-merge">
                    <span class="input-group-text">ID (+62)</span>
                    <input
                      type="text"
                      id="phoneNumber"
                      name="phone"
                      class="form-control"
                      placeholder="202 555 0111"
                    />
                    <p class="text-danger">{{ $errors->first('phone') }}</p>
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="is_admin" class="form-label">Role</label>
                  <select class="form-select" id="is_admin" name="is_admin">
                    <option value="0">Sales</option>
                    <option value="1">Supervisor</option>
                    
                  </select>
                  <p class="text-danger">{{ $errors->first('is_admin') }}</p>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="password" class="form-label">Password</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Password"
                  />
                  <p class="text-danger">{{ $errors->first('password') }}</p>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="password_confirmation" class="form-label">Password Confirmation</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Password Confirmation"
                  />
                  <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                </div>
              <div class="mt-2">      
                <button type="submit" class="btn btn-primary me-2">Create Account</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
              </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  @endsection

  @section('js')

    <script>
        $(document).ready(function () {
        $("#upload").change(function () {
            readURL(this);
        });
        });
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            $("#images").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
        }
    </script>
    <script>
        $(document).ready(function () {
        $("#reset").click(function () {
            $("#images").attr("src", "{{ asset('images/'.Auth::user()->image) }}");
        });
        });
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            $("#images").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
        }
    </script>

    
@endsection