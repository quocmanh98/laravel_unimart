<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable=['masp','thumbnail','name','qty','color','status','price','description','the_firm','product_speak','product_selling','creator','repairer','disabler','productcat_id'];
}
