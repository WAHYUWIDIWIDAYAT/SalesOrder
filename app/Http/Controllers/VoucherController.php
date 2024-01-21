<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Voucher;
use illuminate\Support\Facades\DB;
use illuminate\Support\Facades\Validator;
use illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Hash;
use illuminate\Support\Facades\Storage;
use illuminate\Support\Str;
use illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;

class VoucherController extends Controller
{
    //menampilkan semua data voucher
    public function index()
    {
        try {
            //ambil semua data voucher
            $vouchers = Voucher::all();
            //carikan data voucher berdasarkan nama
            if (request()->search) {
                $vouchers = Voucher::where('name', 'like', '%' . request()->search . '%')->get();
            }

            //kembalikan ke view index dengan compact data products
            return view('voucher.index', compact('vouchers'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menambahkan voucher ke table voucher menggunakan db transaction
    public function store(Request $request)
    {
        //mulai db transaction
        DB::beginTransaction();
        try {
            //validasi data yang dikirim
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'discount' => 'required|numeric|min:1',
                'expired_date' => 'required|date',
            ]);

            //jika validasi gagal kembalikan ke halaman sebelumnya dengan error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            //simpan data voucher ke table voucher
            $voucher = Voucher::create([
                'name' => $request->name,
                'discount' => $request->discount,
                'expired_date' => $request->expired_date,
            ]);

            //jika simpan berhasil commit db transaction
            DB::commit();
            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Voucher berhasil ditambahkan');
        } catch (QueryException $e) {
            //jika gagal rollback db transaction
            DB::rollback();
            //kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menampilkan halaman tambah voucher
    public function create()
    {
        try {
            //kembalikan ke halaman create
            return view('voucher.create');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menampilkan halaman edit voucher
    public function edit($id)
    {
        try {
            //ambil data voucher berdasarkan id
            $voucher = Voucher::findOrFail($id);
            //kembalikan ke halaman edit dengan compact data voucher
            return view('voucher.edit', compact('voucher'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk mengupdate voucher ke database voucher dengan db transaction
    public function update(Request $request, $id)
    {
        try {
            //validasi data yang dikirim
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'discount' => 'required|numeric|min:1',
                'expired_date' => 'required|date',
            ]);

            //jika validasi gagal, kembalikan ke halaman sebelumnya dengan error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            //mulai db transaction
            DB::beginTransaction();

            //ambil data voucher berdasarkan id
            $voucher = Voucher::findOrFail($id);

            //update data voucher
            $voucher->update([
                'name' => $request->name,
                'discount' => $request->discount,
                'expired_date' => $request->expired_date,
            ]);

            //jika update berhasil commit db transaction
            DB::commit();

            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Voucher berhasil diupdate');
        } catch (QueryException $e) {
            //jika update gagal rollback db transaction
            DB::rollback();
            //kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menghapus voucher dari database voucher dengan db transaction
    public function destroy($id)
    {
        try {
            //mulai db transaction
            DB::beginTransaction();

            //ambil data voucher berdasarkan id
            $voucher = Voucher::findOrFail($id);

            //hapus data voucher
            $voucher->delete();

            //jika berhasil commit db transaction
            DB::commit();

            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Voucher berhasil dihapus');
        } catch (QueryException $e) {
            //jika gagal rollback db transaction
            DB::rollback();
            //kembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //checkvoucher if is active and the date is not expired
    public function checkVoucher(Request $request)
    {
        try {
            //get voucher by name
            $voucher = Voucher::where('code', $request->voucher)->first();

            //if voucher is not found
            if (!$voucher) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Voucher tidak ditemukan',
                ]);
            }

            //if voucher is found
            //check if voucher is active
            if (!$voucher->is_active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Voucher tidak aktif',
                ]);
            }

            //check if voucher is expired
            if ($voucher->expired_date < now()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Voucher sudah kadaluarsa',
                ]);
            }

            //if voucher is active and not expired
            return response()->json([
                'status' => 'success',
                'message' => 'Voucher berhasil digunakan',
                'data' => $voucher,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errorInfo,
            ]);
        }
    }

}
