<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Store;


class StoreContoller extends Controller
{
    //

    public function createStroe(Request $request)
    {
        $this->validate($request,['name'=>'required']);
        $store = new Store();
        $store->user_id = Auth::id();
        $store->name = $request->name;
        if($store->save())
        {
            return response(['message'=>"The store was created successfully",'status'=>200]);
        }else
        {
            return response(['message'=>"Oops, Something went wrong. Please try again",'status'=>500]);
        }
    }
}
