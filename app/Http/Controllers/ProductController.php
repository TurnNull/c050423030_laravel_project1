<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index() {
        $products = Product::latest()->paginate(10);
        return view('product.index', [
            'products' => $products
        ]);
    }

    public function create() {
        return view('product.create');
    }

    public function store(Request $request) {
        $validasiData = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title' => 'required|min:5',
            'slug' => '',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $image = $request->file('image')->getClientOriginalName();
        $validasiData['image']->storeAs('productImages', $image);
        $validasiData['image'] = $image;
            
        $chrReplace = [',', '.', '/', ';', ':', '(', ')', 
                       '`', '~', '@', '#', '$', '%', '^',
                       '&', '*', '='];
        $validasiData['slug'] = Str::slug(str_replace($chrReplace, '', 
                                Str::words($validasiData['title'], '3')),
                                '-');
    
        Product::create($validasiData);
        return redirect()->route('products.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function show(Product $product) {
        return view('product.show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product) {
        return view('product.edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product) {
        $validasiData = $request->validate([
            'image' => 'image|mimes:jpeg,jpg,png|max:2048',
            'title' => 'required|min:5',
            'slug' => '',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        if($request->hasFile('image')) {
            if($request->oldImage) {
                $path = 'productImages/' . $product->image;
                Storage::delete($path);
            }
            $image = $request->file('image')->getClientOriginalName();
            $image = str_replace(' ', '_', $image);
            $validasiData['image']->storeAs('productImages', $image);
            $validasiData['image'] = $image;
        };

        $chrReplace = [',', '.', '/', ';', ':', '(', ')', 
                       '`', '~', '@', '#', '$', '%', '^',
                       '&', '*', '='];
        $validasiData['slug'] = Str::slug(str_replace($chrReplace, '', 
                                Str::words($validasiData['title'], '3')),
                                '-');

        Product::findOrFail($product->id)->update($validasiData);
        return redirect()->route('products.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Product $product) {
        $path = 'productImages/' . $product->image;
        Storage::delete($path);
        Product::destroy($product->id);
        return redirect()->route('products.index')->with('success', 'Data berhasil dihapus');
    }
}
