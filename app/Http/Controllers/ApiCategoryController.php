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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Model\category;
use Illuminate\Support\Facades\File;

/**
* ClassName: Controller
* FunctionName: index,
*
* @category Category
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

class ApiCategoryController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * Display a listing of the resource from Database.
    *
    * @param  null
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

//     public function index(){
//         // return category::all();
//         return 'in category index';
//     }

    /**
    * Add Category in database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

//     public function addcaregory(Request $request){
//         $request->validate([
//             'categoryName' => 'required|min:5|max:15',
//         ]);
//         $categoryName = $request->input('categoryName');
//         if(category::where('category_name','=',$categoryName)->first()){
//             return response()->json('categoryNameTaken',200);
//         }

//         date_default_timezone_set('Asia/Kolkata');
//         $date =  date('Y-m-d H:i:s');

//         $user=array(
//             "category_name"=> $categoryName,
//             'created_at'=>$date,
//             'updated_at'=>$date
//         );
//         user::insert($user);

//         return response()->json('success',200);
//     }
    
}
