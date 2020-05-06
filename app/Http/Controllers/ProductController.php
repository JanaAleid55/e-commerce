<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Store;
use App\Product;
use App\Cart;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $store = Store::find($request->id);
        $products = $store->product;
        return response($products);
    }

    public function addProduct(Request $request)
    {  
        $this->validate($request,['name'=>'required','description'=>'required','price'=>'required']);
        $product = new Product();
        $product->store_id = $request->id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price=$request->price;
        if($product->save())
        {
            return response(['message'=>"The product was added successfully",'status'=>200]);
        }else
        {
            return response(['message'=>"Oops, Something went wrong. Please try again",'status'=>500]);
        }
    }


    public function addToCart(Request $request)
    {  
        if(!Auth::guest())
        {
            $cart=Cart::where('product_id', $request->id)->where('user_id', Auth::id())->first();
            if($cart)
            {
              $product = Product::find($cart->product_id);
              $cart->quantity=$request->quantity; 
              $cart->total_price=$product->price*$request->quantity;             
              $this->saveCartInfo($cart);
            }else{
                $this->saveCartInfo($this->createCart($request));
            }
         
        }else{
            $product = Product::find($request->id);
            $cart = Session::get('cart');     
            // empty cart is 
            if(!$cart) {
                $cart = [
                    $request->id=> [
                            "name" => $product->name,
                            "quantity" => $request->quantity,
                            "total_price" => $product->price*$request->quantity,
                        ]
                ];
                Session::put('cart', $cart);
            }
     
            // if product is exist 
            if(isset($cart[$request->id])) {
                $cart[$request->id]['quantity']=$request->quantity;
                $cart[$request->id]['total_price']=$product->price*$request->quantity;
                Session::put('cart', $cart);
            }

            // if product not exist 
            $cart[$request->id] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "total_price" => $product->price*$request->quantity,
            ];
            Session::put('cart', $cart);
        }
    }

    public function createCart($request)
    {
        $product = Product::find($request->id);
        $cart=new Cart();
        $cart->user_id=Auth::id();
        $cart->store_id=$product->store_id;
        $cart->product_id=$product->id;
        $cart->quantity=$request->quantity;
        $cart->total_price=$product->price*$request->quantity;
        return $cart;
    }
    public function saveCartInfo($cart)
    {
        if($cart->save())
        {
            return response(['message'=>"The product was added successfully",'status'=>200]);
        }else
        {
            return response(['message'=>"Oops, Something went wrong. Please try again",'status'=>500]);
        } 
    }





    
}
