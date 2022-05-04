<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\postcat;
class post extends Model
{
    // Model page
    protected $fillable=['name','thumbnail','content','description','creator','repairer','disabler','postcat_id'];
  //   Lay bai viet nay cua danh muc nao
    function postcat(){
     //   Lay bai viet nay cua danh muc nao
      return $this->belongsTo('App\postcat');
  }
}
