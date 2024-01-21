@extends('layouts.admin')

@section('title')
    <title>Profile</title>
@endsection

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Profile Settings /</span> Account</h4>
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
      <form id="form" action="{{ route('account.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
          <h5 class="card-header">Profile Details</h5>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
            <!--if user has image then show image else show default image-->
                <img
                    src="{{ $user->image ? asset('admin/assets/img/backgrounds/no-image.jpg') : asset('admin/assets/img/backgrounds/no-image.jpg') }}"
                  
                    src
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
                  <label for="firstName" class="form-label">First Name</label>
                  <input
                    class="form-control"
                    type="text"
                    id="firstName"
                    name="name"
                    value="{{ $user->name }}"
                    autofocus
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    class="form-control"
                    type="text"
                    id="email"
                    name="email"
                    value="{{ $user->email }}"
                    placeholder="john.doe@example.com"
                  />
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
                        value="{{ $user->phone }}"
                    />
                  </div>
                </div>
            
    
                <div class="mb-3 col-md-6">
                  <label for="is_admin" class="form-label">Role</label>
                  <select class="form-select" id="is_admin" name="is_admin">
                    <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>Sales</option>
                    <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>Supervisor</option>
                  </select>
                </div>
              <div class="mt-2">      
                <button type="button" class="btn btn-primary me-2" id="btnConfirm">Edit</button>
                <button type="submit" class="btn btn-primary me-2" id="btnSave" style="display: none;">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary" id="btnCancel">Cancel</button>
              </div>
          </div>
          <!-- /Account -->
        </div>
        </form>
      </div>
    </div>
  </div>
  @endsection

  @section('js')

    <script>
        $(document).ready(function () {
        $("#btnConfirm").click(function () {
            $("#btnConfirm").hide();
            $("#btnSave").show();
            $("#form input").removeAttr("readonly");
        });
        });
    </script>
    <script>
        $(document).ready(function () {
        $("#btnCancel").click(function () {
            $("#btnConfirm").show();
            $("#btnSave").hide();
            $("#form input").attr("readonly", "readonly");
        });
        });
    </script>
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
    <script>
      $(document).ready(function () {
        $("#accountActivation").click(function () {
          if ($(this).is(":checked")) {
            $("#deactivate").removeAttr("disabled");
          } else {
            $("#deactivate").attr("disabled", "disabled");
          }
        });
      });
    </script>

    
@endsection