<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    //
    public function product()
    {
        return $this->hasMany('App\Model\product');
    }
}
