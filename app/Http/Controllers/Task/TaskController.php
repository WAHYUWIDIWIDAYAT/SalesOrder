<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
//guzzle
use GuzzleHttp\Client;
use App\Models\Customer;


class TaskController extends Controller
{
    //
    public function index()
    {
        $tasks = Task::with('user','customer')->orderBy('created_at', 'DESC')->get();
        $assignFromValues = $tasks->pluck('assign_from')->unique();
        $users = User::whereIn('id', $assignFromValues)->get();
   
        
        if (request()->q != '') {
            
            $tasks = Task::with('user')->whereHas('user', function ($query) {
                $query->where('name', 'LIKE', '%' . request()->q . '%');
            })->orderBy('created_at', 'DESC')->get();
        }
        
        
        return view('task.index', compact('tasks', 'users'));
    }

    public function task()
    {

        $users = User::where('is_admin', 0)->get();
        if($users){

            $provinces = Province::orderBy('created_at', 'DESC')->get();

            $customers = Customer::orderBy('created_at', 'DESC')->get();

            return view('task.create', compact('users', 'provinces', 'customers'));
        }
        else{
            return redirect()->back()->with('error', 'No user found');
        }
        
    }
    public function getCity(Request $request)
    {
        $cities = City::where('province_id', request()->province_id)->get();
        return response()->json(['cities' => $cities]);
    }

    public function getDistrict(Request $request)
    {
        
        $districts = District::where('city_id', request()->city_id)->get();
        return response()->json(['districts' => $districts]);
        
    }

    public function store(Request $request)
    {
        $request->validate([

            // 'task_name' => 'required|string|max:100',
            // 'origin_address' => 'required|string|max:100',
            // 'destination_address' => 'required|string|max:100',
            // 'origin_latitude' => 'required|string|max:100',
            // 'origin_longitude' => 'required|string|max:100',
            // 'destination_latitude' => 'required|string|max:100',
            // 'destination_longitude' => 'required|string|max:100',
            // 'task_description' => 'required|string|max:100',
            
        ]);
        $user = User::where('is_admin', 1)->first();
        
        //split to id longitude and latitude   <option value="{{ $row->id }} {{ $row->longitude }} {{ $row->latitude }}">{{ $row->name }}</option>
        $user_id = explode(" ", $request->user_id)[0];
        $origin_longitude = explode(" ", $request->user_id)[1];
        $origin_latitude = explode(" ", $request->user_id)[2];

        try {
            $task = Task::create([
                'task_name' => $request->task_name,
                'user_id' => $user_id,
                'origin_address' => $request->origin_address,
                'destination_address' => $request->destination_address,
                'origin_latitude' => $origin_latitude,
                'customer_id' => $request->customer_id,
                'origin_longitude' => $origin_longitude,
                'destination_latitude' => $request->destination_latitude,
                'destination_longitude' => $request->destination_longitude,
                'task_status' => 0,
                'assign_from' => $request->assign_from,
                'task_description' => $request->task_description,
            ]);
           
            return redirect()->route('task')->with('success', 'Task created successfully');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
           
            $task = Task::with('user','customer')->find($id);
            $users = User::where('is_admin', 0)->get();
            $customers = Customer::orderBy('created_at', 'DESC')->get();
            return view('task.edit', compact('task', 'users', 'customers'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function show($id)
    {
        try {
           
            $task = Task::with('user')->find($id);
            $users = User::where('is_admin', 0)->get();
            return view('task.view', compact('task', 'users'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        $user_id = explode(" ", $request->user_id)[0];
        $origin_longitude = explode(" ", $request->user_id)[1];
        $origin_latitude = explode(" ", $request->user_id)[2];

        try {
            $task = Task::find($id);
            $task->update([
                'task_name' => $request->task_name,
                'user_id' => $user_id,
                'origin_address' => $request->origin_address,
                'destination_address' => $request->destination_address,
                'origin_latitude' => $origin_latitude,
                'customer_id' => $request->customer_id,
                'origin_longitude' => $origin_longitude,
                'destination_latitude' => $request->destination_latitude,
                'destination_longitude' => $request->destination_longitude,
                'task_description' => $request->task_description,
            ]);
           
            return redirect()->route('task')->with('success', 'Task updated successfully');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    

    public function getEstimation($id)
{
    try {
        $task = Task::find($id);
        //get user_id 
        $user_id = $task->user_id;
        //get user location
        $user = User::find($user_id);
        $userLat = $user->latitude;
        $userLng = $user->longitude;

        $originLat = $userLat;
        $originLng = $userLng;
        $destinationLat = $task->destination_latitude;
        $destinationLng = $task->destination_longitude;

        $api_key = '5b3ce3597851110001cf624839a91d7cc7f24b859452ce1ad5fa25c2';
        $url = "https://api.openrouteservice.org/v2/directions/driving-car?api_key=$api_key&start=$originLng,$originLat&end=$destinationLng,$destinationLat";

        $response = file_get_contents($url);
        $data = json_decode($response);
        $distance = $data->features[0]->properties->segments[0]->distance / 1000;
        $durationInSeconds = $data->features[0]->properties->segments[0]->duration;
        $hours = floor($durationInSeconds / 3600);
        $minutes = floor(($durationInSeconds % 3600) / 60);
        if ($hours > 0 && $minutes > 0) {
            $formattedDuration = "$hours hour $minutes minute";
        } elseif ($hours > 0) {
            $formattedDuration = "$hours hour";
        } elseif ($minutes > 0) {
            $formattedDuration = "$minutes minute";
        } else {
            $formattedDuration = "Less than 1 minute";
        }

        return response()->json(['status' => 'success', 'distance' => $distance, 'duration' => $formattedDuration]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

    

    

}
