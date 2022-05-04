<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
     protected $fillable=['masp','thumbnail','name','price','qty','color','subtotal','payment','status','customer_id','MaKH','disabler'];
}
