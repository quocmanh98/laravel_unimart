<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Phan 24 bai 274 : khai bao softDeletes de xoa tam thoi
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use Notifiable;
    // Phan 24 bai 274 : khai bao softDeletes de xoa tam thoi
    use softDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Lay 1 user co nhung quyen nao
    function roles(){
        // Lấy tất cả các quyền do của 1 user, bảng roles chứa khóa ngoại là user_id: nhieu quyền
        return $this->hasmany('App\role');
    }
}
