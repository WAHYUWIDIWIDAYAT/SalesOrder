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
            $query = Product::query()->where('stock', '>', 0);
    
            if (request()->has('q')) {
                $query->where('name', 'like', '%' . strtolower(request()->q) . '%');
            }

            $products = $query->get();
    
            if (!request()->has('q')) {
                $limit = 4;
                $products = $products->take($limit);
            }
         
            if (request()->has('q') && request()->q == '') {
                $limit = 4;
                $products = $products->take($limit);
            }

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

            $validator = Validator::make($request->all(), [
                'data_product' => 'required',
                'customer_id' => 'required|numeric',
                'address' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
            ]);
        
            if ($validator->fails()) {
                //error redirect back with session error
                return redirect()->back()->with('error', $validator->errors()->first());
            }

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

                if ($product->stock < $quantity) {
                    return redirect()->back()->with('error', 'Stock ' . $product->name . ' tidak mencukupi');
                }
                
                $product->stock = $product->stock - $quantity;
                $product->save();

            }

            DB::commit();

            return redirect()->route('list_order')->with('success', 'Checkout berhasil');

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

            // $purchaseOrders = PurchaseOrder::with('purchaseOrderDetail', 'customer', 'user')->where('user_id', auth()->user()->id)->get();
            //if admin  
            if (auth()->user()->is_admin == 1) {
                $purchaseOrders = PurchaseOrder::with('purchaseOrderDetail', 'customer', 'user')->get();
            } else {
                $purchaseOrders = PurchaseOrder::with('purchaseOrderDetail', 'customer', 'user')->where('user_id', auth()->user()->id)->get();
            }

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

    public function invoice($id)
    {

        try{
            $purchaseOrder = PurchaseOrder::with('purchaseOrderDetail.product', 'customer', 'user')->findOrFail($id);

            $html = view('pembelian.invoice', compact('purchaseOrder'))->render();

            $pdf = PDF::loadHtml($html);

            $pdf->setPaper('a4', 'potrait');
            //the name of pdf is random
            $name = 'SO-' . time() . '-' . rand(10000, 99999) . '.pdf';

            return $pdf->download($name);
            

        }catch(QueryException $e){
            return redirect()->back()->with('error', $e->errorInfo);

        }
    }

    public function delivery_invoice($id)
    {

        try{
            $purchaseOrder = PurchaseOrder::with('purchaseOrderDetail.product', 'customer', 'user')->findOrFail($id);

            $html = view('pembelian.delivery_invoice', compact('purchaseOrder'))->render();

            $pdf = PDF::loadHtml($html);

            $pdf->setPaper('a4', 'potrait');
            //the name of pdf is random
            $name = 'SD-' . time() . '-' . rand(10000, 99999) . '.pdf';

            return $pdf->download($name);
            

        }catch(QueryException $e){
            return redirect()->back()->with('error', $e->errorInfo);

        }
    }

    public function accept_order($id)
    {
        DB::beginTransaction();
        try {
            if (request()->status == 'accept') {
                $purchaseOrder = PurchaseOrder::findOrFail($id);
                $purchaseOrder->status = 1;
                $purchaseOrder->delivery_code = null;
                $purchaseOrder->save();
            } elseif (request()->status == 'delivery') {
                $purchaseOrder = PurchaseOrder::findOrFail($id);
                $purchaseOrder->delivery_code = 'SD-' . time() . '-' . rand(10000, 99999);
                $purchaseOrder->status = 2;
                $purchaseOrder->save();
            }
            DB::commit();

            return redirect()->back()->with('success', 'Order berhasil diterima');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->errorInfo);
        }
    }
}
