<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    //
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
