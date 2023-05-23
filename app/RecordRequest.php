<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecordRequest extends Model
{
    protected $fillable = [];
    protected $table = 'recordrequest';
    public $timestamps = false;
	protected $primaryKey = 'requestid';	
	
		
	public static function addRecordRequest($recordApplStore)
    {
                $value=DB::table('recordrequest')->insert($recordApplStore); 
                return $value;           
        }

    public static function updateRecordRequestApplication($recordApplStore,$id)
    {
      try {
          $value= DB::table('recordrequest')->where('requestid', $id)->update($recordApplStore);
      return $value;
      }catch(\Exception $e){
      return false;
      }      
    }
		
	
	public static function getRecordsPendingForReceiving($establishcode)
    {              
        $value= DB::table('recordrequest')->select('*','usersection.usersecname')->Join('usersection', 'usersection.userseccode', '=', 'recordrequest.userseccode')->whereNull('recordreceiveddate')->where('establishcode', $establishcode)->get();
		  return $value;
    }
	
	


}
