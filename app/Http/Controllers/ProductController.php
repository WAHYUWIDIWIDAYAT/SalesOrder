<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Voucher;
use illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Hash;
use illuminate\Support\Facades\Storage;
use illuminate\Support\Str;
use Illuminate\Database\QueryException;
//Class "illuminate\Support\Facades\Validator" not found
use Validator;
use File;
use DB;

class ProductController extends Controller
{
    //

    public function index()
    {
        try {
            //ambil semua data product
            $products = Product::all();
            if (request()->q) {
                $products = Product::where('name', 'like', '%' . request()->q . '%')->get();
            }

            //kembalikan ke view index dengan compact data products
            
            return view('product.index', compact('products'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    public function show($id)
    {
        try {
            //ambil data product berdasarkan id
            $product = Product::findOrFail($id);
            //kembalikan ke view show dengan compact data product
            return view('show', compact('product'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menambahkan product ke cart
    public function addToCart(Request $request)
    {
        try {
            //validasi data yang dikirim
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|numeric|min:1',
            ]);

            //jika validasi gagal, kembalikan ke halaman sebelumnya dengan error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            //ambil data product berdasarkan id
            $product = Product::findOrFail($request->product_id);

            //cek apakah ada cart dengan product_id dan user_id yang sama
            $cart = Cart::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->first();

            //jika ada cart dengan product_id dan user_id yang sama
            if ($cart) {
                //update quantity dan total_price
                $cart->update([
                    'quantity' => $cart->quantity + $request->quantity,
                    'total_price' => $cart->total_price + ($request->quantity * $product->price),
                ]);
            } else {
                //jika tidak ada cart dengan product_id dan user_id yang sama
                //maka buat cart baru
                Cart::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'total_price' => $request->quantity * $product->price,
                ]);
            }

            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Product berhasil ditambahkan ke cart');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menambahkan produk ke database produk dengan db transaction
    public function store(Request $request)
    {
        try {
            //validasi data yang dikirim
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:3|max:255',
                'price' => 'required|numeric|min:1',
                'stock' => 'required|numeric|min:1',
                
            ]);

            //jika validasi gagal, kembalikan ke halaman sebelumnya dengan error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            //mulai db transaction
            DB::beginTransaction();

            //ambil file image yang dikirim
            $image = $request->file('image');
            //ubah nama file image
            $filename = time() . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            //simpan file image ke folder public/images
            $image->storeAs('public/images', $filename);

            //simpan data product ke database
            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $filename,
            ]);

            //commit db transaction
            DB::commit();

            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->route('product')->with('success', 'Product berhasil ditambahkan');
        } catch (QueryException $e) {
            //rollback db transaction
            DB::rollback();
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }
    

    //method untuk menampilkan halaman tambah product
    public function create()
    {
        try {
            //kembalikan ke halaman create
            return view('product.create');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menampilkan halaman edit product
    public function edit($id)
    {
        try {
            //ambil data product berdasarkan id
            $product = Product::findOrFail($id);
            //kembalikan ke halaman edit dengan compact data product
            return view('product.edit', compact('product'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk mengupdate product ke database product dengan db transaction
    public function update(Request $request, $id)
    {
        try {
            //validasi data yang dikirim
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3|max:255',
                'description' => 'required|string|min:3|max:255',
                'price' => 'required|numeric|min:1',
                'stock' => 'required|numeric|min:1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            //jika validasi gagal, kembalikan ke halaman sebelumnya dengan error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            //mulai db transaction
            DB::beginTransaction();

            //ambil data product berdasarkan id
            $product = Product::findOrFail($id);

            //jika ada file image yang dikirim
            if ($request->hasFile('image')) {
                //hapus file image yang lama
                File::delete(storage_path('app/public/images/' . $product->image));
                //ambil file image yang dikirim
                $image = $request->file('image');
                //ubah nama file image
                $filename = time() . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
                //simpan file image ke folder public/images
                $image->storeAs('public/images', $filename);
                //update data product ke database
                $product->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'image' => $filename,
                ]);
            } else {
                //jika tidak ada file image yang dikirim
                //update data product ke database
                $product->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock' => $request->stock,
                ]);
            }

            //commit db transaction
            DB::commit();

            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->route('product')->with('success', 'Product berhasil diupdate');
        } catch (QueryException $e) {
            //rollback db transaction
            DB::rollback();
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menghapus product
    public function destroy($id)
    {
        try {
            //ambil data product berdasarkan id
            $product = Product::findOrFail($id);
            //hapus file image yang lama
            File::delete(storage_path('app/public/images/' . $product->image));
            //hapus product
            $product->delete();
            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Product berhasil dihapus');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menampilkan halaman cart
    public function cart()
    {
        try {
            //ambil semua data cart berdasarkan user_id
            $carts = Cart::where('user_id', Auth::user()->id)->get();
            //kembalikan ke halaman cart dengan compact data carts
            return view('cart', compact('carts'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }
}
