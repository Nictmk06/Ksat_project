<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class RestoreApplicationModel extends Model
{
    protected $fillable = [ 'applicationid','orderno','orderdate','restorefrom','restorefromapplicationid'
    ];
    protected $table = 'applicationrestore';
    public $timestamps = false;
    //protected $primaryKey = 'applicationid';
   
   public static function addRestoreApplDetails($applnStore)
        {
                $value=DB::table('applicationrestore')->insert($applnStore); 
                return $value;           
        }

  

     public static function getRestoreApplicationDetails($applicationid)
     {              
        // $enteredfrom="";        
        // $applicationrestore= DB::table('applicationrestore')->where('applicationid', $applicationid)->get();
        // if (count($applicationrestore)>0)
        //  {
        //    $enteredfrom=$applicationdisposed[0]->enteredfrom;
        //    if($enteredfrom=='Legacy'){  
        //         $value = DB::table('applicationdisposed')
        //             ->join('applicationtype', 'applicationdisposed.appltypecode', '=', 'applicationtype.appltypecode')
        //             ->select('applicationdisposed.*','applicationtype.*')
        //             ->where('applicationid','=',$applicationid)
        //             ->get();
        //             return $value;
        //     }else{
        //         $value = DB::table('application')
        //         ->join('applicationtype', 'application.appltypecode', '=', 'applicationtype.appltypecode')
        //         ->select('application.*','applicationtype.*')
        //         ->where('applicationid','=',$applicationid)
        //         ->get();
        //         return $value;
        //   }    
        //    }   else{
        //     return $value='';
        //    }  
         }

  }
