@extends('layouts.admin')

@section('title')
    <title>Customer</title>
@endsection

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Customer /</span> Edit</h4>
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
      <form id="form" action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
          <h5 class="card-header">Customer Details</h5>
          <div class="card-body">
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
                    value="{{ $customer->name }}"
                  />
                  <p class="text-danger">{{ $errors->first('name') }}</p>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">Description</label>
                  <input
                    class="form-control"
                    type="text"
                    id="description"
                    name="description"
                    value="{{ $customer->description }}"
                  
                  />
                  <p class="text-danger">{{ $errors->first('description') }}</p>
                </div>
                
              <div class="mt-2">      
                <button type="submit" class="btn btn-primary me-2">Edit Customer</button>
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