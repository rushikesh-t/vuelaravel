<?php

/**
* Controller
*
* PHP Version 7
* It contains all cruds definition
*
* @category Cart & Order
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Model\user;
use App\Model\cart;
use App\Model\product;
use App\Model\order;
use App\Model\order_detail;
use Illuminate\Support\Facades\File;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/**
* ClassName: CartOrderController
* FunctionName: cart, getcart, cartproduct, changequantity, placeorder, getorder, getorderdetails, changestatus
*
* @category User
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

class CartOrderController extends BaseController
{

    /**
    * Add OR remove Data in Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function cart(Request $request){
        $user = $request->input('user');
        $productId = $request->input('id');

        $user = user::all()->where('username',$user)->first()->id;

        if(cart::where('user_id','=',$user)->where('product_id','=',$productId)->first()){
            DB::table('carts')->where('user_id','=',$user)->where('product_id','=',$productId)->delete();
            $update = ['status'=>'removed'];
        }
        else{
            $cart=array(
                "user_id"=> $user,
                "product_id"=> $productId,
                "quantity"=> 1,
            );

            cart::insert($cart);
            $update = ['status'=>'added'];
        }
        return response()->json($update,200);
    }

    /**
    * Get Specific Data from Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */
    public function getcart(Request $request){
        $user = $request->input('user');
        $user = user::all()->where('username',$user)->first()->id;
        
        return cart::all()->where('user_id',$user)->pluck('product_id');
    }

    /**
    * Get Specific Data from Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function cartproduct(Request $request){
        $user = $request->input('user');
        $user = user::all()->where('username',$user)->first()->id;
        
        $cart = product::where('carts.user_id','=',$user)
        ->join('categories','categories.id','=','products.category_id')
        ->join('carts', 'carts.product_id', '=', 'products.id')
        ->select('products.*','categories.category_name','carts.quantity')->get();

        return $cart;
    }

    /**
    * Update Specific Data in Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    */

    public function changequantity(Request $request){
        $user = $request->input('user');
        $user = user::all()->where('username',$user)->first()->id;
        
        $productId = $request->input('id');
        $update = ['quantity'=> $request->input('quantity')];

        DB::table('carts')->where('user_id', $user)->where('product_id', $productId)->update($update);
    }

    /**
    * Update Specific Data in Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return responce
    */

    public function placeorder(Request $request){
        $user = $request->input('user');
        $price = $request->input('price');
        $user = user::all()->where('username',$user)->first()->id;

        $order=array(
            "user_id"=> $user,
            "status"=> "Place",
            "total_price"=> $price
        );

        order::insert($order);

        $order= order::where('user_id',$user)->orderBy('created_at','DESC')->first();

        $products = $request->input('products');
        foreach ($products as $product) {
            $order_detail=array(
                "order_id"=> $order['id'],
                "product_id"=> $product['id'],
                "quantity"=> $product['quantity'],
            );

            order_detail::insert($order_detail);
        }

        DB::table('carts')->where('user_id','=',$user)->delete();

        return response()->json('success',200);
    }

    /**
    * Get Data from Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function getorder(Request $request){
        $user = $request->input('user');
        $user = user::where('username',"=",$user)->first();
        
        if($user['type'] != 0){
            return order::all();
        }
        else{
            return order::all()->where('user_id',$user['id']);
        }
    }

    /**
    * Get Specific Data from Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function getorderdetails(Request $request){
        $user = $request->input('user');
        $user = user::where('username',"=",$user)->first();

        $orderId = $request->input('id');

        if($user['type'] != 0){
            $order = order::where('id',$orderId)->get()->first();
            $order_details = order_detail::where('order_id','=',$orderId)
            ->join('products','products.id','=','order_details.product_id')
            ->get();
        }
        else{
            if(order::where('user_id',$user['id'])->where('id',$orderId)->get()){
                $order = order::where('user_id',$user['id'])->where('id',$orderId)->get()->first();
                $order_details = order_detail::where('order_id','=',$orderId)
                ->join('products','products.id','=','order_details.product_id')
                ->get();
            }
        }
        $data = ['order' => $order];
        $data += ['order_details'=> $order_details];
        return $data;
    }

    /**
    * Update Specific Data in Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function changestatus(Request $request){
        $id= $request->input('id');
        $status= $request->input('status');

        $update= array();
        if($status == "Deliverd"){
            date_default_timezone_set('Asia/Kolkata');
            $date =  date('Y-m-d H:i:s');

            $update += ["delivered_at"=>$date];
        }
        $update += ['status'=>$status];

        DB::table('orders')->where('id', $id)->update($update);

        return response()->json('success',200);
    }
}
