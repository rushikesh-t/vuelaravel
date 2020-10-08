<?php

/**
* Controller
*
* PHP Version 7
* It contains all cruds definition
*
* @category Product
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
use App\Model\category;
use App\Model\product;
use Illuminate\Support\Facades\File;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
* ClassName: ProductController
* FunctionName: index, addproduct, getproduct, editproduct, deleteproduct
*
* @category Product
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

class ProductController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * Display a listing of the Resource from Database.
    *
    * @param  null
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function index(){
        return category::join('products','products.category_id', 'categories.id')->get()->toArray();
    }

    /**
    * Add Data in Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function addproduct(Request $request){
        $request->validate([
            'product.productName' => 'required|min:5|max:15',
            'product.categoryId' => 'required',
            'product.productDescription' => 'required',
            'product.productPrice' => 'required|numeric',

        ]);
        $data = $request->all('product');
        $data = $data['product'];
        $productName = $data['productName'];
        $categoryId = $data['categoryId'];
        $productDescription = $data['productDescription'];
        $productPrice = $data['productPrice'];
        $productImage = $data['productImage'];
        
        $filename = Null;
        if($productImage != ""){
            $encodeImage= explode(',',$productImage);
            $image= base64_decode($encodeImage[1]);
            if(str_contains($encodeImage[0], 'jpeg')){
                $extension ='.jpg';
            }
            else{
                $extension ='.png';
            }
    
            $filename = time(). $productName. $extension;
            $path = 'upload/image/product/'. $filename;
            file_put_contents($path, $image);
        }
        if(product::where('product_name','=',$productName)->first()){
            return response()->json('productNameTaken',200);
        }

        $product=array(
            "product_name"=> $productName,
            "category_id"=> $categoryId,
            "product_description"=> $productDescription,
            "product_price"=> $productPrice,
            "product_image"=> $filename,
        );
        // return $product;
        product::insert($product);

        return response()->json('success',200);
    }
    
    /**
    * Get specific Data From Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function getproduct(Request $request){
        $id = $request->input('id');
        return product::find($id);
    }
    
    /**
    * Update specific Data In Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function editproduct(Request $request){
        $request->validate([
            'product.productName' => 'required|min:5|max:15',
            'product.categoryId' => 'required',
            'product.productDescription' => 'required',
            'product.productPrice' => 'required|numeric',

        ]);

        $data = $request->input('product');
        $id = $data['id'];
        $productName = $data['productName'];
        $categoryId = $data['categoryId'];
        $productDescription = $data['productDescription'];
        $productPrice = $data['productPrice'];
        $productImage = $data['image'];

        $product = product::find($id);

        $update= array();
        if($productName != '' && $productName != $product['product_name']){
            if(product::where('product_name','=',$productName)->first()){
                return response()->json('productNameTaken',200);
            }
            $update += ['product_name'=>$productName];
        }

        if($categoryId != '' && $categoryId != $product['category_id']){
            $update += ['category_id'=>$categoryId];
        }

        if($productDescription != '' && $productDescription != $product['product_description']){
            $update += ['product_description'=>$productDescription];
        }

        if($productPrice != '' && $productPrice != $product['product_price']){
            $update += ['product_price'=>$productPrice];
        }

        if($productImage != ''){
            if($product['product_image'] != null){
                $path = 'upload/image/product/';
                File::delete(public_path($path . $product['product_image']));
            }

            $encodeImage= explode(',',$productImage);
            $image= base64_decode($encodeImage[1]);
            if(str_contains($encodeImage[0], 'jpeg')){
                $extension ='.jpg';
            }
            else{
                $extension ='.png';
            }
    
            $filename = time(). $productName. $extension;
            $path = 'upload/image/product/'. $filename;
            file_put_contents($path, $image);
            
            $update += ['product_image'=>$filename];
        }


        $response = ['status'=> 'success'];
        if (!empty($update)){
            DB::table('products')->where('id', $id)->update($update);
            $response = ['status'=> 'updated'];
        }
        return response()->json($response, 200);
    }

    /**
    * Delete Specific Data from Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function deleteproduct(Request $request){
        $id = $request->input('id');
        $image = product::where('id', $id)->first()->product_image;
        if($image != ""){
            $path = 'upload/image/product/';
            File::delete(public_path($path . $image));
        }
        DB::table('products')->delete($id);

        $update = ['status'=> 'success'];
        return response()->json($update, 200);
    }
    
}
