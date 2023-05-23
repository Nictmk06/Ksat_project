<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class OrderModel extends Model
{
    protected $fillable = [ 'applicationid','judgement_path'
    ];
    protected $table = 'judgement';
    public $timestamps = false;
    //protected $primaryKey = 'applicationid';

   public static function addOrder($orderStore)
        {
                $value=DB::table('orderupload')->insert($orderStore);
                return $value;
        }

 public function applicationDetails($applicationid,$establishcode){


 $value1=DB::SELECT("SELECT application.*,applicationtype.*
from application
left join applicationtype on applicationtype.appltypecode=application.appltypecode
where application.applicationid='$applicationid' and
application.establishcode='$establishcode'
order by application.applicationid");
       return $value1;

     }


 public function getOrderexist($application_id,$establishcode,$orderdate)
    {
      if($orderdate =="")
     {
      $value1 = DB::select("select count(*) as ordercount from orderupload where  applicationid='".$application_id."' and establishcode=:establishcode",['establishcode' => $establishcode]);
        return $value1;
        }else{
         // $value1 = DB::table('judgement')
           // ->where('applicationid', '=', $application_id)
           // ->where('establishcode', '=', $establishcode)
           // ->where('judgementdate', '=', $judgementdate)
          //  ->count();
         $value1 = DB::select("select count(*) as ordercount from orderupload where  applicationid='".$application_id."' and establishcode=$establishcode and orderdate=:orderdate",['orderdate' => $orderdate]);
        return $value1;
        }
    }

    public function orderDetails($applicationid,$establishcode){
       
    
      $value1=DB::SELECT("SELECT application.*,applicationtype.*,orderupload.*
from application
left join applicationtype on applicationtype.appltypecode=application.appltypecode
left join orderupload on orderupload.applicationid=application.applicationid
where application.applicationid='$applicationid' and (application.statusid='1' or application.statusid is null)  and
application.establishcode='$establishcode'
order by application.applicationid");
       return $value1; 
    
     } 

      public static function updateOrderDetails($orderStore,$orderdate,$applicationid)
    {

$value=DB::table('orderupload')->where('orderdate', $orderdate)->where('applicationid', $applicationid)->update($orderStore);
      return $value;
    } 

}
