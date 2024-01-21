@extends('layouts.admin')

@section('title')
    <title>List Product</title>
@endsection

@section('content')
<main class="main">
<br><br>
    <div class="container-fluid">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Task \</span> List</h4>
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @if (session('success'))
                                <div class="alert alert-primary">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                           
                        </div>
                        <div class="card-body">
                            <form action="#" method="get">
                                <div class="input-group mb-3 col-md-3 float-right">
                                    <input type="text" name="q" class="form-control rounded mr-2" placeholder="Cari..." value="{{ request()->q }}" style="margin-right: 20px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Cari</button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                    <table class="table table-hover" style="border-left: 0px; border-right: 0px; padding: 10px; overflow-x: scroll;">
                                    <thead>
                                    <tr style="border-bottom: 2px solid #e3e3e3; padding: 10px;">
                                           
                                            <th>Task Name</th>
                                            <th>Status</th>
                                            <th>Address</th>
                                            <th>Created At</th>
                                            <th>Sales Name</th>
                                            <th>Supervisor</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    @forelse ($tasks as $task)
        <tr style="border-bottom: 2px solid #e3e3e3; padding: 10px;">
            <td>
                <strong>{{ $task->task_name }}</strong>
            </td>
            <td>
                @if($task->task_status == 0)
                    <span class="badge badge-warning" style="background-color: #ffc107; color: #fff;">Pending</span>
                @elseif($task->task_status == 1)
                    <span class="badge badge-success" style="background-color: #28a745; color: #fff;">Completed</span>
                @else
                    <span class="badge badge-danger" style="background-color: #dc3545; color: #fff;">Canceled</span>
                @endif
            </td>
            <td>{{ $task->destination_address }}</td>
            <td>{{ $task->created_at->format('d-m-Y') }}</td>
            <td>{{ $task->user->name }}</td>
            <td>
                @foreach($users as $user)
                    @if($user->id == $task->assign_from)
                        {{ $user->name }}
                    @endif
                @endforeach
            </td>
            <td>
                <a href="{{ route('sales.task', $task->id) }}" class="btn btn-primary btn-sm">Lihat</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">Tidak ada data</td>
        </tr>
    @endforelse
</tbody>

                                </table>
                            </div>
                        </div>
                        <div class="float-right" style="margin-left: 80%; margin-bottom: 30px;">
                        
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</main>
@endsection

@section('js')
    <script>
        //setting pagination
        $('.pagination').addClass('float-right');
    </script>

@endsection
