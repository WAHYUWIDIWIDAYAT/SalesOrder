<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Customer;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index()
    {
        try {
            //count users
            //get data all user where latitute and longitude is not null
            $sales_location = User::where('latitude', '!=', null)->where('longitude', '!=', null)->where('is_admin', 0)->get();

            $sales = User::where('is_admin', 0)->get()->count();
            $users = User::where('is_admin', 1)->get()->count();
            $all_users = User::all()->count();
            //count tasks
            $tasks = Task::all()->count();
            //count confirmed tasks
            $confirmed_tasks = Task::where('task_status', 1)->get()->count();
            //count pending tasks
            $pending_tasks = Task::where('task_status', 0)->get()->count();
            //count canceled tasks
            $canceled_tasks = Task::where('task_status', 2)->get()->count();

            //get precentage of customer in task example customer A has 2 task and customer B has 1 task and make it in percentage in array
            $customers = [];
            $customer = Customer::all();
            foreach ($customer as $c) {
                $customer_task = Task::where('customer_id', $c->id)->get()->count();
                $customer_name = $c->name;
                $customer_percentage = ($customer_task / $tasks) * 100;
                $customers[] = [
                    'customer_name' => $customer_name,
                    'customer_task' => $customer_task,
                    'customer_percentage' => $customer_percentage,
                ];
            }
            

            return view('adminHome', compact('sales', 'users', 'all_users', 'tasks', 'confirmed_tasks', 'pending_tasks', 'canceled_tasks', 'sales_location', 'customers'));
            // return response()->json([
            //     'sales' => $sales,
            //     'users' => $users,
            //     'all_users' => $all_users,
            //     'tasks' => $tasks,
            //     'confirmed_tasks' => $confirmed_tasks,
            //     'pending_tasks' => $pending_tasks,
            //     'canceled_tasks' => $canceled_tasks,
            //     'customers' => $customers,
            //     'sales_location' => $sales_location,
            // ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

            
    }

    public function salesLocation(){
        try{
            $sales_location = User::where('latitude', '!=', null)->where('longitude', '!=', null)->where('is_admin', 0)->get();
            return response()->json($sales_location);
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
