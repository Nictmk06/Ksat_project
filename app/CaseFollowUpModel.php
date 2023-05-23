<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CaseFollowUpModel extends Model
{


	public static function getofficenoteDetails($applicationid, $startdate,$enddate)
    {
      if($startdate == '' &&  $enddate == '')
       {
       // print_r("cdfdfdf");
        $value = DB::select("
       	     (SELECT applicationid,hearingdate as date,'' as officenote,courtdirection,'0' as officenotecode  FROM public.dailyhearing where applicationid=:applicationid  and courtdirection !=''
			)
			UNION
			(
			SELECT applicationid, officenotedate as date, officenote,'',officenotecode	FROM public.officenote  where applicationid=:applicationid
			) order by 2",['applicationid' => $applicationid]);
            return $value;

			}else{
       //   print_r("mini");
       $value = DB::select("
                (SELECT applicationid,hearingdate as date,'' as officenote,courtdirection,null
                FROM public.dailyhearing where applicationid= :applicationid and courtdirection !='' and hearingdate >= '".$startdate."' and hearingdate <=  '".$enddate."'
            )
            UNION
            (
            SELECT applicationid, officenotedate as date, officenote,'',officenotecode
            FROM public.officenote  where applicationid=:applicationid and  officenotedate >= '".$startdate."' and officenotedate <= '".$enddate."'
            ) order by 2",['applicationid' => $applicationid]);
             return $value;
        }

}






    	 public static function addOfficeNote($officenoteStore)
        {
                $value=DB::table('officenote')->insert($officenoteStore);
                return $value;
        }
    }
