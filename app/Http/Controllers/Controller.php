<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Model\user;
use App\Model\role;
use App\Model\category;
use App\Model\product;
use App\Model\cart;
use App\Model\order;
use App\Model\order_detail;
use Illuminate\Support\Facades\File;
use Storage;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return 'in';
    }
    public function get(){
        return user::all();
    }
}
