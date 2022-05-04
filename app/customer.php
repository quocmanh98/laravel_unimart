<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $fillable=['fullname','email','address','phone','note','subtotal','status','payment_method','MaKH','disabler'];
    function products(){
        // Lấy tất cả san pham cua don hang da dat
        // return $this->hasmany('App\order','customers_id');
        return $this->hasmany('App\order');
    }
}
