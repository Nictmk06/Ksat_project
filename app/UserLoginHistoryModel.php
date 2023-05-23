<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class UserLoginHistoryModel extends Model
{
    protected $fillable = [ 'id','userid','ipaddress','logintime','logouttime','establishcode','sessionid' ];
	
    protected $table = 'user_login_history';
    public $timestamps = false;
    protected $primaryKey = 'id';
   
   public static function insertUserLoginDtls($userlogindtls)
        {
			  $value=DB::table('user_login_history')->insert($userlogindtls); 
        $id = DB::getPdo()->lastInsertId();
                //  print_r($id);
                  return $id;   
            }



     public static function getUserLoginDtls($applicationid,$establishcode)
     {              
        
         }

     public static function updateUserLoginDtls($userlogoutdtls,$id)
       {
      try {
        $value= DB::table('user_login_history')->where('id', $id)->update($userlogoutdtls);
         return $value;
      }catch(\Exception $e){
      return false;
      }      
    }
  }
