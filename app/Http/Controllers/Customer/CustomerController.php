<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    //

    public function index()
    {
        try{
            $customers = Customer::orderBy('created_at', 'DESC')->paginate(10);

            if(request()->q != ''){
                $customers = Customer::where('name', 'LIKE', '%' . request()->q . '%')->orderBy('created_at', 'DESC')->get();
            }
            return view('customer.index', compact('customers'));
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('customer.store');
    }

    public function store(Request $request)
    {
        try{
            $this->validate($request, [
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:100',
            ]);
    
            Customer::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
    
            return redirect(route('customer'))->with('success', 'Customer created successfully');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Customer $customer, $id)
    {
        try{
            $customer = Customer::find($id);
            return view('customer.edit', compact('customer'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Customer $customer)
    {
        try{
            $this->validate($request, [
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:100',
            ]);
    
            $customer->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
    
            return redirect(route('customer'))->with('success', 'Customer updated successfully');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}
