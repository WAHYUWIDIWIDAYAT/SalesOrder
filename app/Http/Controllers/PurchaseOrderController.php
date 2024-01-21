<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Models\Voucher;
//exception
use Illuminate\Database\QueryException;
use Validator;
use PDF;

class PurchaseOrderController extends Controller
{
  

    public function select_product()

    {
        try {
            // Ambil semua data product
            $query = Product::query();

            if (request()->has('q')) {
                // /case-insensitive lowercase SELECT * FROM countries WHERE LOWER( countries.title ) LIKE '%kosovo%'
                $query->where('name', 'like', '%' . strtolower(request()->q) . '%');

    
            }
    

            // Ambil semua produk
            $products = $query->get();

            if (!request()->has('q')) {
                $products = $products->take(4);
            }

            $limit = 4;
            //limit 4
            $products = $products->take($limit);

            // Kembalikan ke view index dengan compact data products
            return response()->json([
         
                'data' => $products
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'message' => $e->errorInfo
            ], 500);
        }
    }

    public function index()
    {
        try {
            //get customer all with exception
            $customers = Customer::all();
            
            return view('pembelian.index', compact('customers'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }


    public function checkout(Request $request)
    {
        try {
            // Mendapatkan data dari request
            $dataProduct = $request->input('data_product');
            $customerID = $request->input('customer_id');
            
            $address = $request->input('address');
            $email = $request->input('email');
            $phone = $request->input('phone');

            // Mendekode data product dari JSON
            $idArray = json_decode($dataProduct, true);

            // Memulai transaksi database
            DB::beginTransaction();

            // Membuat purchase order
            $purchaseOrder = new PurchaseOrder([
                'customer_id' => $customerID,
                'user_id' => $request->user()->id,
                'discount' => $request->input('discount'),
                'subtotal' => $request->input('subtotal'),
                'address' => $address,
                'code' => 'INV-' . time() . '-' . rand(10000, 99999),
                'email' => $email,
                'phone' => $phone,
                'total' => $request->input('subtotal') - $request->input('discount'),

                // Mungkin ada field lain yang perlu ditambahkan
            ]);
            $purchaseOrder->save();

            // Iterasi melalui produk yang akan dicheckout
            foreach ($idArray as $item) {
                $id = $item['id'];
                $quantity = $item['quantity'];
                $product = Product::findOrFail($id);

                // Membuat purchase order detail
                $purchaseOrderDetail = new PurchaseOrderDetail([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total_price' => $quantity * $product->price,
                ]);
                $purchaseOrderDetail->save();
            }

            DB::commit();

            return redirect()->route('product')->with('success', 'Checkout berhasil');

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function list_order()
    {
        try {

            $purchaseOrders = PurchaseOrder::with('purchaseOrderDetail', 'customer', 'user')->where('user_id', auth()->user()->id)->get();

            return view('pembelian.order_list', compact('purchaseOrders'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    public function detail_order($id)
    {
        try {
            $purchaseOrder = PurchaseOrder::with('purchaseOrderDetail.product', 'customer', 'user')->findOrFail($id);

            return view('pembelian.show', compact('purchaseOrder'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }

    public function invoice($type)
    {
        $pdf = app('dompdf.wrapper')->loadView('pembelian.invoice');
        
            if ($type == 'stream') {
                return $pdf->stream('invoice.pdf');
            }
        
            if ($type == 'download') {
                return $pdf->download('invoice.pdf');
            }
    }
}
