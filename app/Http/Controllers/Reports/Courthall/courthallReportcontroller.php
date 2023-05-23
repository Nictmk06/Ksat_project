<?php

namespace App\Http\Controllers\Reports\Courthall;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class courthallReportcontroller extends Controller
{
    
     public static function courtproceedingsnotentered()

     {
      // $data['applicationType'] = DB::select("SELECT * FROM applicationtype order by appltypecode");
       return view('Reports.Courthall.courtproceedingsnotentered');
     }

     public static function courtproceedingsentered()
     {
           return view('Reports.Courthall.courtproceedingsentered');
     }

  public function courtproceedingsnotenteredfunction(Request $request)
  {
    $establishcode=Session::get('EstablishCode');
    $establishmentname=Session::get('establishfullname');

    $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
     $todate=date('Y-m-d',strtotime($request->get('todate')));


        $request->validate([
              'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
              'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
      ]);




 /*   $result=DB::select( "SELECT dh.hearingdate,dh.business,dh.benchcode,b.judgeshortname,dh.courthallno,ch.courthalldesc,
dh.listno,dh.purposecode,lp.listpurpose,dh.causelistsrno,dh.applicationid
from  dailyhearing as dh INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall as ch on dh.courthallno=ch.courthallno
INNER JOIN listpurpose as lp ON dh.purposecode=lp.purposecode
where dh.hearingdate>='$fromdate' and dh.hearingdate<='$todate' and dh.establishcode='$establishcode'
and (business is NULL or business='N') order by  dh.hearingdate");
*/
 $result=DB::select ("SELECT dh.hearingdate,dh.business,dh.benchcode,b.judgeshortname,dh.courthallno,ch.courthalldesc,
                      dh.dictationby,
                      dh.listno,dh.purposecode,lp.listpurpose,dh.causelistsrno,dh.applicationid
                      from  dailyhearing as dh INNER JOIN bench as b ON dh.benchcode=b.benchcode
                      INNER JOIN courthall as ch on dh.courthallno=ch.courthallno
                      INNER JOIN listpurpose as lp ON dh.purposecode=lp.purposecode
                      where dh.hearingdate>='$fromdate' and dh.hearingdate<='$todate' and dh.establishcode='$establishcode'
                      and (business is NULL or business='N') order by  dh.hearingdate");

      return view('Reports.Courthall.courtproceedingsnotenteredReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

    }

public function courtproceedingsenteredfunction(Request $request)
{
  $establishcode=Session::get('EstablishCode');
  $establishmentname=Session::get('establishfullname');

  $fromdate= date('Y-m-d',strtotime($request->get('hearingdate')));
  $benchcode=$request->get('applTypeName');
  $benchname=DB::select("SELECT ' Bench Type: ' || benchtypename || ' ,Judge Name: ' || judgeshortname as bench  from bench where benchcode='$benchcode' ");

      $request->validate([
            'hearingdate' => 'required|date_format:d-m-Y'

    ]);




  $result=DB::select( "SELECT dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
  dh.caseremarks,dh.nextdate,dh.ordertypecode,ot.ordertypedesc,dh.casestatus
  from  dailyhearing as dh INNER JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
  where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
  order by  dh.hearingdate");



    return view('Reports.Courthall.courtproceedingsenteredReport', ['result'=>$result],['fromdate'=>$fromdate,'benchname'=>$benchname,'establishmentname'=>$establishmentname]);

  }

  public function findJudgeWithBenchCode($hearingdate)
      {   $establishcode=Session::get('EstablishCode');
          $fromdate= date('Y-m-d',strtotime($hearingdate));
          $applTypeName = DB::select("SELECT DISTINCT dh.benchcode,b.judgeshortname
from  dailyhearing as dh INNER JOIN bench as b ON dh.benchcode=b.benchcode
where dh.hearingdate='$fromdate' and dh.establishcode='$establishcode'  order by benchcode");


          return response()->json($applTypeName);
      }
}
