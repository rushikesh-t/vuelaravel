<?php

/**
* Controller
*
* PHP Version 7
* It contains all cruds definition
*
* @category User
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
use DB;
use App\Model\user;
use App\Model\cart;
use Illuminate\Support\Facades\File;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Laravel\Passport\Passport;


/**
* ClassName: UserController
* FunctionName: index, adduser, deleteuser, getuser, edituser, login, logout
*
* @category User
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * Display a listing of the resource from Database.
    *
    * @param  null
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function index(){
        return user::all();
    }

    /**
    * Add User in database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function adduser(Request $request){
        $request->validate([
            'user.userName' => 'required|min:5|max:15',
            'user.firstName' => 'required|min:3|max:15',
            'user.lastName' => 'required|min:5|max:12',
            'user.gender' => 'required',
            'user.email' => 'required|email',
            'user.age' => 'required|numeric',
            'user.password' => 'required|min:6|max:15',
            'user.confirmPassword' => 'required|same:user.password',
        ]);
        $data = $request->input('user');

        $username= $data['userName'];
        $email= $data['email'];
        
        if(user::where('username','=',$username)->first()){
            return response()->json('userNameTaken',200);
        }
        if(user::where('email','=',$email)->first()){
            return response()->json('emailTaken',200);
        }

        $name= $data['firstName'] .' ' . $data['lastName'];
        $age= $data['age'];
        $gender= $data['gender'];
        $password = bcrypt($data['password']);

        $filename = Null;
        if($data['image'] != ""){
            $encodeImage= explode(',',$data['image']);
            $image= base64_decode($encodeImage[1]);
            if(str_contains($encodeImage[0], 'jpeg')){
                $extension ='.jpg';
            }
            else{
                $extension ='.png';
            }
    
            $filename = time(). $username. $extension;
            $path = 'upload/image/user/'. $filename;
            file_put_contents($path, $image);
        }

        date_default_timezone_set('Asia/Kolkata');
        $date =  date('Y-m-d H:i:s');

        $user=array(
            "username"=> $username,
            "name"=> $name,
            "age"=> $age,
            "gender"=> $gender,
            "email"=> $email,
            "password"=> $password,
            'avatar'=> $filename,
            'created_at'=>$date,
            'updated_at'=>$date,
            'type'=>0
        );

        $tokenUser = user::create($user);

        $accessToken = $tokenUser->createToken('Token')->accessToken;

        return response()->json(['status' => 'success', 'accessToken' => $accessToken],200);
    }
    
    /**
    * Delete specific User from database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function deleteuser(Request $request){
        $id = $request->input('id');
        $user = $request->input('userlog');
        DB::table('users')->delete($id);
        $data= user::all('username')->toArray();

        $update= array();
        if(user::where('username','=',$user)->first()){
            $update += ['log'=> true];
        }
        else{
            $update += ['log'=> false];
        }
        $update += ['status'=> 'success'];
        return response()->json($update, 200);
    }
    
    /**
    * Get specific User from database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return object
    */

    public function getuser(Request $request){
        $user = $request->input('user');
        
        return user::where('username','=',$user)->first();
    }

    /**
    * Edit specific User in database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */
    public function edituser(Request $request){
        $request->validate([
            'user.userName' => 'nullable|min:5|max:15',
            'user.firstName' => 'nullable|min:3|max:15',
            'user.lastName' => 'nullable|min:5|max:12',
            'user.age' => 'nullable|numeric',
            'user.email' => 'nullable|email',
            'user.password' => 'nullable|min:6|max:15',
            'user.confirmPassword' => 'nullable|same:user.password',
        ]);

        $data = $request->input('user');
        $id= $data['id'];
        $username= $data['userName'];
        $name= $data['firstName'] .' ' . $data['lastName'];
        $age= $data['age'];
        $email= $data['email'];
        $gender= $data['gender'];
        $type = $data['type'];
        $password = $data['password'];

        date_default_timezone_set('Asia/Kolkata');
        $date =  date('Y-m-d H:i:s');

        $user = user::where('users.id','=',$id)->first();

        $update= array();
        if($username != '' && $username != $user['username']){
            if(user::where('username','=',$username)->first()){
                return response()->json('userNameTaken',200);
            }
            $update += ['username'=>$username];
        }
        if($email != '' && $email != $user['email']){
            if(user::where('email','=',$email)->first()){
                return response()->json('emailTaken',200);
            }
            $update += ['email'=>$email];
        }
        if($data['firstName'] != '' && $data['lastName'] != '' && $name != $user['name']){
            $update += ['name'=>$name];
        }
        if($age != '' && $age != $user['age']){
            $update += ['age'=>$age];
        }
        if($gender != '' && $gender != $user['gender']){
            $update += ['gender'=>$gender];
        }
        if($password != ''){
            if(Hash::check($password, $user['password'])){
                $password = Hash::make($password);
                $update += ['password'=>$password];
            }
            else{
                return response()->json('passwordNotMatch',200);
            }
        }

        if($data['image'] != ''){
            if($user['avatar'] != null){
                $path = 'upload/image/user/';
                File::delete(public_path($path . $user['avatar']));
            }

            $encodeImage= explode(',',$data['image']);
            $image= base64_decode($encodeImage[1]);
            if(str_contains($encodeImage[0], 'jpeg')){
                $extension ='.jpg';
            }
            else{
                $extension ='.png';
            }
    
            $filename = time(). $username. $extension;
            $path = 'upload/image/user/'. $filename;
            file_put_contents($path, $image);
            
            $update += ['avatar'=>$filename];
        }
        if($type != $user['type']){
            $update += ['type'=>$type];
        }

        $response = ['status'=> 'success'];
        
        if (!empty($update)){
            $update += ['updated_at'=>$date];
            DB::table('users')->where('id', $id)->update($update);
            $response = ['status'=> 'updated'];
        }
        return response()->json($response, 200);
    }

    /**
    * Check data in Database.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */
    public function login(Request $request){      
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['status'=> 'userNotFound'], 200);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        return response(['status'=> 'success','permission' => auth()->user()->type, 'accessToken' => $accessToken]);

        // $user = Auth::user();
        // $user = user::where('id',auth()->user()->id)->first();
        // $user->save();
        
        // $tokenResult = auth()->user()->createToken('Personal Access Token');
        // $token = $tokenResult->token;

        // $token->expires_at = Carbon::now()->addWeeks(1);

        // $token->save();
        
        // $permission = auth()->user()->type;
        // $update=array(
        //     'status'=> 'success',
        //     'permission'=> $permission,
        //     'user'=> auth()->user(),
        //     'access_token'=> $tokenResult->accessToken
        // );
        // return response()->json($update,200);

    }

    /**
    * Remove Credentials.
    *
    * @param  \Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */
    public function logout(Request $request) {
        auth()->user()->token()->revoke();

        return response()->json('success',200);
    }
}