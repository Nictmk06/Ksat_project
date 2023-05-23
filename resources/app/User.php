<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
//namespace App;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function verifyLoginDetails($password,$username,$establishCode)
    {
       
        try {
        $users = DB::table('userdetails')
                ->join('userpassword', 'userpassword.userid', '=', 'userdetails.userid')
                ->where('userdetails.userid',$username)
                ->where('userpassword.password',$password)
                ->where('userdetails.establishcode',$establishCode)
                ->exists();
        return $users;
        }catch(\Exception $e){
            return false;
            }

    }
    public function getUserName($password,$username,$establishCode)
    {
        try {
        $username = DB::table('userdetails')
                ->join('userpassword', 'userpassword.userid', '=', 'userdetails.userid')
                ->where('userdetails.userid',$username)
                ->where('userpassword.password',$password)
                ->where('userdetails.establishcode',$establishCode)
                ->select('username')
                ->get();
        return $username;
          }catch(\Exception $e){
            return false;
            }

    }

    public function getUserDetails($username,$establishCode)
    {
        try {
        $userDetails = DB::table('userdetails')
                ->where('userdetails.userid',$username)
                ->where('userdetails.establishcode',$establishCode)
                ->select('*')
                ->get();
        return $userDetails;
          }catch(\Exception $e){
            return false;
            }

    }
}
