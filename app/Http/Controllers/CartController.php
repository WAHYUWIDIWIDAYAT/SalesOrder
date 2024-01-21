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
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;


class CartController extends Controller
{
    //menampilkan semua data cart berdasarkan user yang sedang login dan berelasi dengan product
    public function index()
    {
        try {
            //ambil semua data cart berdasarkan user yang sedang login dan berelasi dengan product
            $carts = Cart::with('product')->where('user_id', Auth::user()->id)->get();
            //kembalikan ke view index dengan compact data carts
            return view('cart.index', compact('carts'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menambahkan product ke cart dengan db transaction
    public function store(Request $request)
    {
        //mulai db transaction
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|numeric',
                'quantity' => 'required|numeric|min:1',
            ]);

            //jika validasi gagal kembalikan ke halaman sebelumnya dengan error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            //ambil data product berdasarkan id
            $product = Product::findOrFail($request->product_id);

            //cek apakah product tersedia
            if ($product->stock < $request->quantity) {
                return redirect()->back()->with('error', 'Stock tidak mencukupi');
            }

            //cek apakah product sudah ada di cart
            $cart = Cart::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->first();

            //jika product sudah ada di cart
            if ($cart) {
                //update quantity product di cart
                $cart->update([
                    'quantity' => $cart->quantity + $request->quantity,
                ]);
            } else {
                //simpan data product ke cart
                Cart::create([
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);
            }

            //commit db transaction
            DB::commit();

            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Product berhasil ditambahkan ke cart');
        } catch (QueryException $e) {
            //rollback db transaction
            DB::rollback();
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menghapus product dari cart
    public function destroy($id)
    {
        try {
            //ambil data cart berdasarkan id
            $cart = Cart::findOrFail($id);
            //hapus data cart
            $cart->delete();
            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Product berhasil dihapus dari cart');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk mengupdate quantity product di cart
    public function update(Request $request, $id)
    {
        try {
            //ambil data cart berdasarkan id
            $cart = Cart::findOrFail($id);
            //update quantity product di cart
            $cart->update([
                'quantity' => $request->quantity,
            ]);
            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Quantity berhasil diupdate');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk menghapus semua product di cart
    public function clear()
    {
        try {
            //hapus semua data cart berdasarkan user yang sedang login
            Cart::where('user_id', Auth::user()->id)->delete();
            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Cart berhasil dikosongkan');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    //method untuk checkout cart dengan db transaction and pengecekan voucher dimana jika voucher digunakan maka akan mengurangi total harga
    public function checkout(Request $request)
    {
        //mulai db transaction
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'voucher' => 'nullable|exists:vouchers,code',
            ]);

            //jika validasi gagal kembalikan ke halaman sebelumnya dengan error
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }

            //ambil semua data cart berdasarkan user yang sedang login dan berelasi dengan product
            $carts = Cart::with('product')->where('user_id', Auth::user()->id)->get();

            //jika cart kosong kembalikan ke halaman sebelumnya dengan error
            if ($carts->isEmpty()) {
                return redirect()->back()->with('error', 'Cart kosong');
            }

            //hitung total harga
            $total = $carts->sum(function ($cart) {
                return $cart->product->price * $cart->quantity;
            });

            //jika ada voucher
            if ($request->voucher) {
                //ambil data voucher berdasarkan code
                $voucher = Voucher::where('code', $request->voucher)->first();

                //jika voucher tidak ditemukan kembalikan ke halaman sebelumnya dengan error
                if (!$voucher) {
                    return redirect()->back()->with('error', 'Voucher tidak ditemukan');
                }

                //jika voucher sudah digunakan kembalikan ke halaman sebelumnya dengan error
                if ($voucher->is_used) {
                    return redirect()->back()->with('error', 'Voucher sudah digunakan');
                }

                //jika total harga kurang dari minimum pembelian voucher kembalikan ke halaman sebelumnya dengan error
                if ($total < $voucher->min_purchase) {
                    return redirect()->back()->with('error', 'Total harga kurang dari minimum pembelian voucher');
                }

                //jika total harga lebih dari maksimum diskon voucher kembalikan ke halaman sebelumnya dengan error
                if ($total > $voucher->max_discount) {
                    return redirect()->back()->with('error', 'Total harga lebih dari maksimum diskon voucher');
                }

                //hitung total harga setelah diskon
                $total = $total - $voucher->discount;

                //update voucher menjadi sudah digunakan
                $voucher->update([
                    'is_used' => true,
                ]);

                //simpan data purchase order
                $purchaseOrder = PurchaseOrder::create([
                    'user_id' => Auth::user()->id,
                    'voucher_id' => $voucher->id,
                    'total' => $total,
                ]);
            } else {
                //simpan data purchase order
                $purchaseOrder = PurchaseOrder::create([
                    'user_id' => Auth::user()->id,
                    'total' => $total,
                ]);
            }

            //looping data cart
            foreach ($carts as $cart) {
                //simpan data purchase order detail
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);

                //update stock product
                $cart->product->update([
                    'stock' => $cart->product->stock - $cart->quantity,
                ]);
            }

            //hapus semua data cart berdasarkan user yang sedang login
            Cart::where('user_id', Auth::user()->id)->delete();

            //commit db transaction
            DB::commit();

            //kembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Checkout berhasil');
        } catch (QueryException $e) {
            //rollback db transaction
            DB::rollback();
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

}
