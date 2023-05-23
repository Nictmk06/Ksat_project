<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class JudgementModel extends Model
{
     protected $fillable = [ 'applicationid','judgement_path'
    ];
    protected $table = 'judgement';
    public $timestamps = false;
    //protected $primaryKey = 'applicationid';
   
   public static function addJudgement($judgementStore)
        {
                $value=DB::table('judgement')->insert($judgementStore); 
                return $value;           
        }



 public function getJudgementExist($application_id)
    {
      $value1 = DB::select("select count(*) as judgementcount from judgement where  applicationid='".$application_id."'");
        return $value1;      
    }

 

  public static function getJudgementDetails($applicationid)
        { 
               $value = DB::table('judgement')->select('judgement.*')
                ->where('applicationid','=',$applicationid)
                ->get();
                return $value;
        }   

  public function getJudgementDeliveryMode()
      {
        $value = DB::table('deliverymode')->select('*')->get();
        return $value;
      }

 public function getFreeCopyApplRespondantStatus($applicationid,$flag)
    {
      if($flag=='A')
      {        
       $value = DB::select("Select a.applicationid,a.applicantsrno,a.ismainparty,applicantname,a.applicantstatus,b.deliveryon, b.deliverycode,b.remarks from applicant as a left join judgementdelivery as b on a.applicationid = b.applicationid and a.applicantsrno= b.partysrno and b.petitionerrespondent='A'  where a.applicationid='".$applicationid."'");
       return $value;
      }
      else if($flag='R')
      {   
        $value = DB::select("Select a.applicationid,a.respondsrno,a.ismainrespond,respondname,a.respondstatus,b.deliveryon, b.deliverycode,b.remarks from respondant as a left join judgementdelivery as b on a.applicationid = b.applicationid and a.respondsrno= b.partysrno and b.petitionerrespondent='R'  where a.applicationid='".$applicationid."'");     
      
        return $value;
      }
    }

 public static function addJudgementDelivery($judgementdelivery)
        {
                $value=DB::table('judgementdelivery')->insert($judgementdelivery); 
                return $value;           
        }
        
  }
