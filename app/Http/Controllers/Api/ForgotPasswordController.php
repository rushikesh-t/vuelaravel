<?php

/**
* Controller
*
* PHP Version 7
* It contains all cruds definition
*
* @category Password
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Str;
use DB;
use App\Model\user;

/**
* ClassName: ForgotPasswordController
* FunctionName: forgotpassword, resetpassword
*
* @category Password
* @package  Controller
* @author   Rushikesh <rushikesh.talekar@neosoftmail.com>
* @license  http://neosofttech.com/  Neosoft
* @link     NA
*/

class ForgotPasswordController extends Controller
{
    /**
    * Send Mail to Reset Password.
    *
    * @param  Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */

    public function forgotpassword(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        if(!user::where('email','=',$email)->first()){
            return response()->json(['status' => 'emailNotFound'],200);
        }
        $token =Str::random(10);

        Mail::send('Mail.Forgot',['token' => $token], function($message) use ($email) {
            $message->to($email);
            $message->subject("Reset Your Password");
        });

        DB::table("password_resets")->insert([
            "email" => $email,
            "token" => $token
        ]);

        return response()->json(['status' => 'success'],200);
    }

    /**
    * Update Database.
    *
    * @param  Illuminate\Http\Request $request
    * @author Rushikesh <rushikesh.talekar@neosoftmail.com>
    * @return response
    */
    public function resetpassword(Request $request){
        $request->validate([
            'password' => 'required|min:6|max:15',
            'confirmPassword' => 'required|same:password',
        ]);

        $token = $request->input('token');
        $password = $request->input('password');
        if($passwordReset = DB::table("password_resets")->where('token',$token)->first()){
            if($user  = user::where('email',$passwordReset->email)->first()){
                $user->password = bcrypt($password);
                $user->save();

                DB::table('password_resets')->where('token',$token)->delete();

                return response()->json(['status' => 'success'],200);
            }
            else{
                return response(['massage' => 'User does not exist'], 400);
            }
        }
        else{
            return response(['massage' => 'Invalid Token'], 400);
        }

    }
}
