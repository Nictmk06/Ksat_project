<?php

namespace App\Http\Controllers\Reports\Caveat;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class CaveatReportController extends Controller
{

     public static function caveatfiled()

     {
      // $data['applicationType'] = DB::select("SELECT * FROM applicationtype order by appltypecode");
       return view('Reports.Caveat.caveatfiled');
     }


      public static function caveatmatched()
    {

      return view('Reports.Caveat.caveatmatched');

    }

   public static function caveatfiledagainstdepartment()
 {

   return view('Reports.Caveat.caveatfiledagainstdepartment');

 }

  public function caveatfiledfunction(Request $request)
  {
    $establishcode=Session::get('EstablishCode');
    $establishmentname=Session::get('establishfullname');

    $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
     $todate=date('Y-m-d',strtotime($request->get('todate')));


        $request->validate([
              'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
              'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
      ]);




    $result=DB::select( "SELECT CAV.caveatid,CAV.caveatfiledate,CAV.subject,CAV.caveatorcount,CAV.caveateecount,CAV.applcatcode,
CAV.goorders,CAT.applcatname from
caveat as CAV inner join applcategory as CAT ON
CAV.applcatcode=CAT.applcatcode
where CAV.caveatfiledate >='$fromdate' and CAV.caveatfiledate <='$todate'
and CAV.establishcode='$establishcode' ORDER by CAV.caveatfiledate");




      return view('Reports.Caveat.caveatfiledReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

    }

public function caveatmatchedfunction(Request $request)
{
  $establishcode=Session::get('EstablishCode');
  $establishmentname=Session::get('establishfullname');

  $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
   $todate=date('Y-m-d',strtotime($request->get('todate')));


      $request->validate([
            'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
            'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
    ]);

$result=DB::select("SELECT CAV.caveatid,CAV.caveatfiledate,CAV.caveatorcount,CAV.caveateecount,
CAV.applicationid,CAV.matchdate,CAV.subject,CAT.applcatname from caveat as CAV inner join
applcategory as CAT ON CAV.applcatcode=CAT.applcatcode
where CAV.matchdate >='$fromdate' and CAV.matchdate<='$todate'
and CAV.caveatmatched='Y'
and CAV.establishcode='$establishcode' ORDER BY CAV.matchdate");




    return view('Reports.Caveat.caveatmatchedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

}

 public function caveatfiledagainstdepartmentfunction(Request $request)
 {
   $establishcode=Session::get('EstablishCode');
   $establishmentname=Session::get('establishfullname');

   $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
    $todate=date('Y-m-d',strtotime($request->get('todate')));


       $request->validate([
             'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
             'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
     ]);




   $result=DB::select( "SELECT C.caveatid,C.caveatfiledate,C.subject,C.caveatorcount,C.caveateecount,CV.caveateedeptname
   ,C.subject,CAT.applcatname,CV.caveateename from
   caveat as C INNER JOIN caveateeview as CV on C.caveatid =CV.caveatid
   INNER JOIN applcategory as CAT ON C.applcatcode=CAT.applcatcode
   where C.caveatfiledate >='$fromdate' and
   C.caveatfiledate <='$todate' and C.establishcode='$establishcode' order by C.caveatfiledate");




     return view('Reports.Caveat.caveatfiledagainstdepartmentReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

   }

}
