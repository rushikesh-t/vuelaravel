<?php

/**
* Controller
*
* PHP Version 7
* It contains all cruds definition
*
* @category Category
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
use Illuminate\Support\Facades\File;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
* ClassName: CategoryController
* FunctionName: index,addcategory,deletecategory,getcategory,editcategory
*
* @category Category
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

class CategoryController extends BaseController
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
        return category::all();
    }

    /**
    * Add Data in Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function addcategory(Request $request){
        $request->validate([
            'categoryName' => 'required|min:5|max:15',
        ]);
        $categoryName = $request->input('categoryName');
        if(category::where('category_name','=',$categoryName)->first()){
            return response()->json('categoryNameTaken',200);
        }

        $category=array(
            "category_name"=> $categoryName,
        );
        category::insert($category);

        return response()->json('success',200);
    }

    /**
    * Delete specific Data from database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function deletecategory(Request $request){
        $id = $request->input('id');
        DB::table('categories')->delete($id);

        $update = ['status'=> 'success'];
        return response()->json($update, 200);
    }
    
    /**
    * Get specific Data From Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function getcategory(Request $request){
        $id = $request->input('id');
        return category::find($id);
    }
    
    /**
    * Update specific Data From Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */


    public function editcategory(Request $request){
        $request->validate([
            'category.categoryName' => 'required|min:5|max:15',
        ]);
        $data = $request->input('category');
        $id = $data['id'];
        $categoryName = $data['categoryName'];

        $category = category::find($id);

        $update= array();
        if($categoryName != '' && $categoryName != $category['category_name']){
            if(category::where('category_name','=',$categoryName)->first()){
                return response()->json('userNameTaken',200);
            }
            $update += ['category_name'=>$categoryName];
        }

        $response = ['status'=> 'success'];

        if (!empty($update)){
            DB::table('categories')->where('id', $id)->update($update);
            $response = ['status'=> 'updated'];
        }
        return response()->json($response, 200);
    }

}
