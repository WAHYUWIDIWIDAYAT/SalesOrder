<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use DB;
//query exception
use Illuminate\Database\QueryException;
use App\Models\Task;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;

//tes


class AccountController extends Controller
{
    public function index()
    {
        $user = User::where('id', auth()->user()->id)->first();
        if (!$user) {
            return redirect(route('login'));
        }
        if ($user->id != auth()->user()->id) {
            return redirect(route('login'));
        }
        return view('account.index', compact('user'));
    }

    public function edit()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return view('account.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|max:100',
            'email' => 'email|unique:users,email,' . auth()->user()->id,
        ]);

        $user = User::where('id', auth()->user()->id)->first();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $filename);

            File::delete(storage_path('app/public/images/' . $user->image));

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'image' => $filename,
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
              
                'image' => $user->image,
          
            ]);
        }
        if (auth()->user()->is_admin == 1) {
            return redirect(route('profile'))->with(['success' => 'Profile Berhasil Diupdate']);
        } else {
            return redirect(route('sales.profile'))->with(['success' => 'Profile Berhasil Diupdate']);
        }

        return redirect(route('profile'))->with(['success' => 'Profile Berhasil Diupdate']);
    }

    public function editPassword()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return view('account.edit-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',
            'old_password' => 'required|string|min:4',
            'password_confirmation' => 'required|string|min:6'
        ]);

        if ($request->password != $request->password_confirmation) {
            return redirect()->back()->with(['error' => 'Password Baru Tidak Sama Dengan Konfirmasi Password']);
        }

        $user = User::where('id', auth()->user()->id)->first();
        $hashedPassword = $user->password;
        if (Hash::check($request->old_password, $hashedPassword)) {
            if (!Hash::check($request->password, $hashedPassword)) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                Auth::logout();
                return redirect(route('login'))->with(['success' => 'Password Berhasil Diupdate']);
            } else {
                return redirect()->back()->with(['error' => 'Password Baru Tidak Boleh Sama Dengan Password Lama']);
            }
        } else {
            return redirect()->back()->with(['error' => 'Password Lama Tidak Sesuai']);
        }  
    }

    public function getAdmin()
    {
        $users = User::where('is_admin','!=', 0)->orderBy('id', 'DESC')->get();

        if (request()->q != '') {
            $users = $users->where('email', 'LIKE', '%' . request()->q . '%');
        }
        $users = User::where('is_admin','!=', 0)->paginate(10);
        return view('account.admin', compact('users'));
    }

    public function getUser()
    {
        $users = User::where('is_admin', 0);
    
        if (request()->has('q') && request()->q != '') {
            $users->where('email', 'LIKE', '%' . request()->q . '%');
        }
    
        $users = $users->paginate(10);
    
        return view('account.user', compact('users'));
    }
    public function destroy($id)
    {
        try {
            // Begin transaction
            DB::beginTransaction();
    
            $user = User::findOrFail($id);
    
            // Check if the user is used in task or purchaseorder
            $task = Task::where('user_id', $id)->first();
            $purchaseorder = PurchaseOrder::where('user_id', $id)->first();
    
            // If user is used in task or purchaseorder
            if ($task || $purchaseorder) {
                // Rollback the transaction
                DB::rollBack();
    
                // Return error message
                return redirect()->back()->with(['error' => 'User Tidak Dapat Dihapus Karena Masih Digunakan']);
            }
    
            // If user is not used in task or purchaseorder
            $user->delete();
    
            // Commit the transaction
            DB::commit();
    
            // Return success message
            return redirect()->back()->with(['success' => 'User Berhasil Dihapus']);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollBack();
    
            // Return error message
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function addUsers()
    {
        return view('account.add-users');
    }

    public function storeUsers(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
            'is_admin' => 'required',
            'image' => 'nullable|image|mimes:png,jpeg,jpg'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $filename);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_admin' => $request->is_admin,
                'password' => Hash::make($request->password),
                'image' => $filename
            ]);
        } else {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_admin' => $request->is_admin,
                'password' => Hash::make($request->password),
            ]);
        }
        if ($request->is_admin == 1) {
            return redirect(route('account.admin'))->with(['success' => 'User Berhasil Ditambahkan']);
        } else {
            return redirect(route('account.user'))->with(['success' => 'User Berhasil Ditambahkan']);
        }
    }

    public function deleteAccount()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user->delete();
        Auth::logout();
        return redirect(route('login'))->with(['success' => 'Akun Berhasil Dihapus']);
    }

    public function deleteUsers($id)
    {

        try{
            $user = User::findOrFail($id);

            $task = Task::where('user_id', $id)->first();
            $purchaseorder = PurchaseOrder::where('user_id', $id)->first();

            if($task || $purchaseorder){
                return redirect()->back()->with(['error' => 'User Tidak Dapat Dihapus Karena Masih Digunakan']);
            }


            $user->delete();
            return redirect()->back()->with(['success' => 'User Berhasil Dihapus']);
        }
        catch(\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    public function editUsers($id)
    {
        $user = User::where('id', $id)->first();
        return view('account.edit-users', compact('user'));
    }
    public function updateUsers(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'is_admin' => 'required', 
            'image' => 'nullable|image|mimes:png,jpeg,jpg'
        ]);

        $user = User::where('id', $id)->first();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $filename);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_admin' => $request->is_admin,
                'image' => $filename
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_admin' => $request->is_admin,
                
                'image' => $user->image
            ]);
        }
        if ($request->is_admin == 1) {
            return redirect(route('account.admin'))->with(['success' => 'User Berhasil Diupdate']);
        } else {
            return redirect(route('account.user'))->with(['success' => 'User Berhasil Diupdate']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'))->with(['success' => 'Berhasil Logout']);
    }

    public function updateLocation(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $user->latitude = $latitude;
            $user->longitude = $longitude;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Update Lokasi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Update Lokasi'
            ]);
        }
    }

}


