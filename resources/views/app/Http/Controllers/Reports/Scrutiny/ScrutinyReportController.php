<?php

namespace App\Http\Controllers\Reports\Scrutiny;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class scrutinyReportController extends Controller
{

public static function scrutinizedapplication()
     {
    return view('Reports.Scrutiny.scrutinizedapplication');
      }

    public static function objectedapplication()
       {
        return view('Reports.Scrutiny.objectedapplication');
      }

        public static function applicationnotcomplied()
        {
        return view('Reports.Scrutiny.applicationnotcomplied');
        }

        public static function objectionraised()
        {

    return view('Reports.Scrutiny.objectionraised');
        }




public function scrutinizedapplicationfunction(Request $request) {

  $establishcode=Session::get('EstablishCode');
  $establishmentname=Session::get('establishfullname');
  $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
   $todate=date('Y-m-d',strtotime($request->get('todate')));


   $request->validate([
         'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
         'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
 ]);


   $type1 = $request->get('status');


  $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
  $result=DB::select("SELECT S.scrutinydate,S.applicationid,AP.applicationdate,AP.applicantname1,
AP.advocatename,
CASE WHEN (S.acceptreject='N') THEN 'Rejected' ELSE 'Accepted' END as status,S.Reason from scrutiny S
LEFT join applicationsummary1 AP on S.applicationid=AP.applicationid
where  S.scrutinydate>='$fromdate' and S.scrutinydate<='$todate' AND (S.acceptreject='$type1' OR '-1'='$type1')");



  return view('Reports.Scrutiny.scrutinizedapplicationreport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
}

public function objectedapplicationfunction(Request $request) {

  $establishcode=Session::get('EstablishCode');
  $establishmentname=Session::get('establishfullname');
  $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
   $todate=date('Y-m-d',strtotime($request->get('todate')));


   $request->validate([
         'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
         'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
 ]);


   $type1 = $request->get('status');


  $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
  $result=DB::select("SELECT S.applicationid,AP.applicationdate,AP.applicantname1,
AP.advocatename,S.accrejdate,
S.Reason from scrutiny S
LEFT join applicationsummary1 AP on S.applicationid=AP.applicationid
where  S.scrutinydate>='$fromdate' and S.scrutinydate<='$todate' AND (S.acceptreject='N')");



  return view('Reports.Scrutiny.objectedapplicationReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
}

public function applicationnotcompliedfunction(Request $request) {

  $establishcode=Session::get('EstablishCode');
  $establishmentname=Session::get('establishfullname');
  $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
   $todate=date('Y-m-d',strtotime($request->get('todate')));


   $request->validate([
         'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
         'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
 ]);


   $type1 = $request->get('status');


  $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
  $result=DB::select("SELECT S.scrutinydate,S.tobecomplieddate,S.accrejdate,S.applicationid,AP.applicationdate,AP.applicantname1,AP.advocatename
from scrutiny S INNER join applicationsummary1 AP on S.applicationid=AP.applicationid
where  S.tobecomplieddate>='$fromdate'
and S.tobecomplieddate <='$todate' and S.acceptreject='N'");



  return view('Reports.Scrutiny.applicationnotcompliedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
}

public function objectionraisedfunction(Request $request) {

  $establishcode=Session::get('EstablishCode');
  $establishmentname=Session::get('establishfullname');
  $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
   $todate=date('Y-m-d',strtotime($request->get('todate')));


   $request->validate([
         'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
         'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
 ]);


   $type1 = $request->get('status');


  $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
  $result=DB::select("SELECT applicationid,scrutinydate,chklistdesc_observation,remarks from rep_scrutinyobjections where scrutinydate>='$fromdate' AND scrutinydate<='$todate' ");



  return view('Reports.Scrutiny.objectionraisedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
}


}
