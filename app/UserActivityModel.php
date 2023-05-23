<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class UserActivityModel extends Model
{
    protected $fillable = [ 'id','applicationid_receiptno','createdon','activity','userid','establishcode','login_session_id' ];
    protected $table = 'user_activity_history;';
    public $timestamps = false;
    protected $primaryKey = 'id';
   
   public static function insertUserActivityDtls($useractivitydtls)
        {
			  $value=DB::table('user_activity_history')->insert($useractivitydtls); 
           //  $id = DB::getPdo()->lastInsertId();
           return $value;   
            }



     public static function getUserActivityDtls($applicationid,$establishcode)
     {              
        
         }

     public static function updateUserActivityDtls($userlogoutdtls,$id)
       {
      try {
        $value= DB::table('user_activity_history')->where('id', $id)->update($userlogoutdtls);
         return $value;
      }catch(\Exception $e){
      return false;
      }      
    }
  }
