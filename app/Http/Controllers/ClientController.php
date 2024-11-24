<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(){
        $products = Product::with('category')->paginate(8);
        return view('client.home.home',compact('products'));
    }
    public function shop($id = null){
        if($id == null){
            $products = Product::with('category')->get();
        }else {
            $products = Product::with('category')->where('category_id',$id)->get();
        }
        // dd($products);
        return view('client.home.shop',compact('products'));
    }
    public function shopDetail($id){
        $product = Product::with('category')->find($id);
        // dd($product);
        return view('client.home.product-detail',compact('product'));
    }
}
