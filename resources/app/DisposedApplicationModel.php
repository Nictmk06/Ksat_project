<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DisposedApplicationModel extends Model
{
       protected $fillable = [ 'applicationid','applicationdate','applicationyear','appltypecode','applcategory','applicantcount','respondentcount'
    ];
    protected $table = 'applicationdisposed';
    public $timestamps = false;
    //protected $primaryKey = 'applicationid';
   
   public static function addDisposedApplDetails($applnStore)
        {
                $value=DB::table('applicationdisposed')->insert($applnStore); 
                return $value;           
        }

  public function getDisposedApplicantExist($application_id,$startno,$applType,$applYear,$endno)
    {
      $value1 = DB::select("select count(*) as applcount from applicationdisposed where ((applicationsrno between '".$startno."' and '".$endno."') or  (applicationtosrno between '".$startno."' and '".$endno."')) and applicationyear='".$applYear."' and appltypecode='".$applType."'");
        return $value1;
      
    }

     public static function getDisposedApplicantDetails($applicationid)
     {              
        $enteredfrom="";        
        $applicationdisposed= DB::table('applicationdisposed')->where('applicationid', $applicationid)->get();
        if (count($applicationdisposed)>0)
         {
           $enteredfrom=$applicationdisposed[0]->enteredfrom;
           if($enteredfrom=='Legacy'){  
                $value = DB::table('applicationdisposed')
                    ->join('applicationtype', 'applicationdisposed.appltypecode', '=', 'applicationtype.appltypecode')
                    ->select('applicationdisposed.*','applicationtype.*')
                    ->where('applicationid','=',$applicationid)
                    ->get();
                    return $value;
            }else{
                $value = DB::table('application')
                ->join('applicationtype', 'application.appltypecode', '=', 'applicationtype.appltypecode')
                ->select('application.*','applicationtype.*')
                ->where('applicationid','=',$applicationid)
                ->get();
                return $value;
          }    
           }   else{
            return $value='';
           }  
         }

     public static function updateDisposedApplDetails($applnStore,$applicationid)
       {
      try {
          $value= DB::table('applicationdisposed')->where('applicationid', $applicationid)->update($applnStore);
      return $value;
      }catch(\Exception $e){
      return false;
      }      
    }
  }
