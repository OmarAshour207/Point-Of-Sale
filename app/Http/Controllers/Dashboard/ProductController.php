<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::when($request->search, function ($query) use ($request){
            return $query->whereTranslationLike( '%' . 'name' . '%', $request->search);
        })->when($request->category_id, function ($q) use ($request) {
            return $q->where('category_id', $request->category_id);
        })->latest()->paginate(5);

        return view('dashboard.products.index', compact(['categories', 'products']));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'category_id'       => 'required|numeric|min:1'
        ];

        foreach (config('translatable.locales') as $locale){
            $rules+= [$locale . '.name'     => 'required|unique:product_translations,name'];
            $rules+= [$locale . '.description'     => 'required'];
        }

        $rules+= [
            'image'             => 'nullable|image',
            'purchase_price'    => 'required|numeric',
            'sale_price'        => 'required|numeric',
            'stock'             => 'required|numeric'
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if($request->image) {
            Image::make($request->image)->resize(300, null, function ($constraint){
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }

        $product = Product::create($request_data);
        session()->flash('success', __('added_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit', compact(['product', 'categories']));
    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'category_id'       => 'required|numeric|min:1'
        ];

        foreach (config('translatable.locales') as $locale){
            $rules+= [$locale . '.name'             => ['required', Rule::unique('product_translations', 'name')->ignore($product->id, 'product_id')]];
            $rules+= [$locale . '.description'      => 'required'];
        }

        $rules+= [
            'image'             => 'nullable|image',
            'purchase_price'    => 'required|numeric',
            'sale_price'        => 'required|numeric',
            'stock'             => 'required|numeric'
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if($request->image) {
            if($product->image != 'default.png') {
                Storage::disk('public_uploads')->delete('/product_images/' . $product->image);
            }
            Image::make($request->image)->resize(300, null, function ($constraint){
                $constraint->aspectRatio();
            })->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }

        $product->update($request_data);
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    public function destroy(Product $product)
    {
        if($product->image != 'default.png') {
            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);
        }

        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }
}
