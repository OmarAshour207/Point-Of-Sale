<?php

namespace App\Http\Controllers\Dashboard;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
//        dd(Order::whereHas('client', function ($query) use ($request) {
//            return $query->where('name', 'like', '%' . $request->search . '%');
//        })->toSql());

        $orders = Order::whereHas('client', function ($query) use ($request){
            return $query->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(5);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function products(Order $order)
    {
        $products = $order->products;

        return view('dashboard.orders._products', compact('products', 'order'));
    }

    public function destroy(Order $order)
    {
        foreach ($order->products as $product) {
            $product->update([
                'stock'     => $product->stock + $product->pivot->quantity,
            ]);
        }
//        dd($order->products->first()->pivot->quantity);
        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');
    }
}
