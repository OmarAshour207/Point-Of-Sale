<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Category;
use App\Client;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orders = Order::when($request->search, function ($query) use ($request){
           return $query->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(5);
        return view('dashboard.clients.orders.index', compact('orders'));
    }

    public function create(Client $client)
    {
        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.create', compact('client', 'categories', 'orders'));
    }

    public function store(Request $request, Client $client)
    {
//        dd($request->products);
        $request->validate([
            'products'          => 'required|array'
        ]);

        $this->attach_order($request, $client);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
    }

    public function edit(Client $client, Order $order)
    {
        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.edit', compact('client','order', 'categories', 'orders'));
    }

    public function update(Request $request, Client $client, Order $order)
    {
        $request->validate([
            'products'          => 'required|array'
        ]);

        $this->deattach_order($order);
        $this->attach_order($request, $client);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');
    }

    public function destroy(Order $order)
    {
        $this->deattach_order($order);
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');
    }

    private function attach_order($request, $client)
    {
        // get the client id and add to the orders table
        $order = $client->orders()->create([]);

        // attach the products with the quantity and add it in product_order table
        $order->products()->attach($request->products);

        $total_price = 0;

        foreach ($request->products as $id => $quantity) {

            $product = Product::findOrFail($id);
            $total_price += $product->sale_price * $quantity['quantity'];

            $product->update([
                'stock'     => $product->stock - $quantity['quantity'],
            ]);
        } //end of foreach

        $order->update([
            'total_price'       =>  $total_price
        ]);

    }

    private function deattach_order($order)
    {
        foreach ($order->products as $product) {
            $product->update([
                'stock'     => $product->stock + $product->pivot->quantity,
            ]);
        }
        $order->delete();
    }
}
