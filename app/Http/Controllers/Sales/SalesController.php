<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\City;
use App\Models\District;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use GuzzleHttp\Client;

use App\Models\Province;

class SalesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
    
        try{
            $confirmed_tasks = Task::where('task_status', 1)->where('user_id', auth()->user()->id)->get()->count();
            $tasks = Task::where('user_id', auth()->user()->id)->get()->count();
            $pending_tasks = Task::where('task_status', 0)->where('user_id', auth()->user()->id)->get()->count();
            $canceled_tasks = Task::where('task_status', 2)->where('user_id', auth()->user()->id)->get()->count();

            return view('salesHome', compact('confirmed_tasks', 'tasks', 'pending_tasks', 'canceled_tasks'));
        }

        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {

        $tasks = Task::with('user')
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        $assignFromValues = $tasks->pluck('assign_from')->unique();
        $users = User::whereIn('id', $assignFromValues)->get();

        if (request()->q != '') {
            
            $tasks = Task::with('user')->whereHas('user', function ($query) {
                $query->where('name', 'LIKE', '%' . request()->q . '%');
            })->orderBy('created_at', 'DESC')->get();
        }
    
        return view('sales.task.index', compact('tasks', 'users'));
    }
    
    public function show($id)
    {
        try {
           
            $task = Task::with('user', 'district', 'district.city', 'district.city.province')->find($id);
           
            if ($task->user_id != auth()->user()->id) {
                return redirect()->back()->with('error', 'You are not authorized to view this task');
            }
            
            $user = User::find($task->assign_from);
            return view('sales.task.view', compact('task', 'user'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
    public function confirmTask($id)
    {
        try {
            $task = Task::find($id);
            if ($task->user_id != auth()->user()->id) {
                return redirect()->back()->with('error', 'You are not authorized to view this task');
            }
            $task->task_status = 1;
            $task->save();
            return redirect()->route('sales.home')->with('success', 'Task has been confirmed');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function storeProff(Request $request)
    {
        try {
            $task = Task::find($request->id);
            if ($request->hasFile('proff')) {
                $file = $request->file('proff');
                $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/proff', $filename);
                $task->proff = $filename;
                $task->save();


            }
            return redirect()->route('sales.task', $task->id)->with('success', 'Proff has been uploaded');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }


    public function getSalesLocation($id)
    {
        try {
            $user = User::find($id);
            return response()->json(['long' => $user->longitude, 'lat' => $user->latitude]);
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getAllSales()
    {
        try {

            $users = User::where('is_admin', 0);

            if (request()->has('q') && request()->q != '') {
                $users->where('email', 'LIKE', '%' . request()->q . '%');
            }
        
            $users = $users->paginate(10);
            return view('task.sales_task', compact('users'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getSalesTask($id)
    {
        try {
            $tasks = Task::with('user')
                ->where('user_id', $id)
                ->orderBy('created_at', 'DESC')
                ->get();
            $assignFromValues = $tasks->pluck('assign_from')->unique();
            $users = User::whereIn('id', $assignFromValues)->get();

            if (request()->q != '') {
                
                $tasks = Task::with('user')->whereHas('user', function ($query) {
                    $query->where('task_name', 'LIKE', '%' . request()->q . '%');
                })->orderBy('created_at', 'DESC')->get();
            }
        
            return view('task.sales.task', compact('tasks', 'users'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
