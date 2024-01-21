@extends('layouts.admin')
@section('title')
    <title>Tambah Task</title>
@endsection

@section('content')
<div class="content-wrapper">

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Task /</span> Tambah</h4>
    <form action="{{ route('task.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Tambah Task</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
                <div class="mb-3">
                <label class="form-label" for="basic-default-name">Name Task</label>
                <input
                    type="text"
                    class="form-control"
                    id="task_name"
                    name="task_name"
                    placeholder="Enter Name Task"
                />
                <p class="text-danger">{{ $errors->first('task_name') }}</p>
                </div>
                <div class="mb-3">
                <label class="form-label" for="basic-default-description">Description Task</label>
                <textarea
                    class="form-control"
                    id="task_description"
                    name="task_description"
                    placeholder="Enter Description Task"
                    required
                ></textarea>
                <p class="text-danger">{{ $errors->first('task_description') }}</p>
                </div>
                <div class="mb-3">
                <label class="form-label" for="basic-default-name">Receiver Name</label>
                <input
                    type="text"
                    class="form-control"
                    id="receiver_name"
                    name="receiver_name"
                    placeholder="Enter Receiver Name"
                />
                <p class="text-danger">{{ $errors->first('receiver_name') }}</p>
                </div>
                <div class="mb-3">
                <label class="form-label" for="basic-default-name">Phone Number</label>
                <input
                    type="number"
                    class="form-control"
                    id="receiver_phone"
                    name="receiver_phone"
                    placeholder="Enter Phone Number"
                />
                <p class="text-danger">{{ $errors->first('receiver_phone') }}</p>
                </div> 
                <div class="mb-3">
                <label class="form-label" for="basic-default-name">Email Address</label>
                <input
                    type="email"
                    class="form-control"
                    id="receiver_email"
                    name="receiver_email"
                    placeholder="Enter Email Address"
                />
                <p class="text-danger">{{ $errors->first('receiver_email') }}</p>
                </div> 
            </div>
        </div>
        </div>

        <div class="col-md-6">
        <div class="card mb-4">
            <h5 class="card-header">Address</h5>
            <div class="card-body demo-vertical-spacing demo-only-element">
            <div class="mb-3">
            <div class="input-group">
                <select class="form-select" id="province_id" name="province_id">
                <option selected>Choose Province...</option>
                @foreach ($provinces as $row)
                <option value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
                </select>
                <label class="input-group-text" for="province">Province</label>
                <p class="text-danger">{{ $errors->first('province_id') }}</p>
            </div>
            </div>
            <br>
            <div class="mb-3">
            <div class="input-group">
                <select class="form-select" id="city_id" name="city_id">
                <option selected>Choose City...</option>
            
                </select>
                <label class="input-group-text" for="city">City</label>
                <p class="text-danger">{{ $errors->first('city_id') }}</p>
            </div>

            <br>

            <div class="mb-3">
            <div class="input-group">
                <select class="form-select" id="district_id" name="district_id">
                <option selected>Choose District...</option>
               
                </select>
                <label class="input-group-text" for="district">District</label>
                <p class="text-danger">{{ $errors->first('district_id') }}</p>
            </div>

            <br>
            <div class="mb-3">
                <textarea
                    class="form-control"
                    id="receiver_address"
                    name="receiver_address"
                    placeholder="Enter Address"
                    required
                ></textarea>
            </div>
            <br>
            <div class="mb-3">
            <div class="input-group">
                <select class="form-select" id="sales" name="sales_id">
                <option selected>Choose Sales...</option>
                @foreach ($users as $row)
                <option value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
                </select>
                <p class="text-danger">{{ $errors->first('sales_id') }}</p>
                <label class="input-group-text" for="sales">Sales</label>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
    
        </div>
        <div class="mb-3">
            <div class="card mb-4">
                <h5 class="card-header">Action Button</h5>
                <br>
                <div class="card-body demo-vertical-spacing demo-only-element">
                <center>
                <button type="submit" class="btn btn-primary">Tambah</button>
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="button" class="btn btn-danger" onclick="window.history.back()">Kembali</button>
                </center>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    </form>
</div>
<br><br>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            $('#file-input').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            if ($('#preview-image').attr('src') == '') {    
                $('#preview-image').attr('src', '{{ asset('admin/assets/img/backgrounds/no-image.jpg') }}').attr('width', '185').attr('height', '185');
            }
        });
    </script>
    <script>
        CKEDITOR.replace('task_description');
    </script>
    <script>
    $(document).ready(function () {
        $('#province_id').on('change', function () {
            var province_id = $(this).val();
            if (province_id) {
                $.ajax({
                    url: '/api/city', 
                    type: "GET",
                    data: {
                        "province_id": province_id
                    },
                    success: function (data) {
                        $('#city_id').empty();
                        $.each(data.cities, function (key, value) {
                            $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                $('#city_id').empty();
            }
        });
    });
</script>
<script>
        $(document).ready(function () {
            $('#city_id').on('change', function () {
                var city_id = $(this).val();
                if (city_id) {
                    $.ajax({
                        url: '/api/district',
                        type: "GET",
                        data: {
                            "city_id": city_id
                        },
                        success: function (data) {
                            $('#district_id').empty();
                            $.each(data.districts, function (key, value) {
                                $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                        },
                    });
                } else {
                    $('#district_id').empty();
                }
            });
        });
    </script>
<script>
@endsection

