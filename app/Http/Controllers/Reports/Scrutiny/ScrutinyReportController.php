<?php

namespace App\Http\Controllers\Reports\Scrutiny;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScrutinyReportController extends Controller
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


   public function scrutinypendingapplication()
      {  
	   $data['applicationType'] = DB::select("SELECT * FROM applicationtype order by appltypecode");
        return view('Reports.Scrutiny.scrutinypendingapplication',$data);
      }

    public static function scrutinizedandnotpostedtoch()
      {
        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');
		$result=DB::select("SELECT applicationid,to_date(registerdate,'DD/MM/YYYY') as registerdate,applcatname,applicationo,subject,advocatename,advocateregno,respondentname,applicantname1
			,respondentadvcode,respondentadvocate,remarks FROM applicationsummary1 where establishcode='$establishcode' AND scrutinyflag ='Y' and firstlistdate is NULL Order by registerdate");
        return view('Reports.Scrutiny.scrutinizedandnotpostedtochReport', ['result'=>$result],['establishmentname'=>$establishmentname]);
      }

    public function scrutinypendingapplicationfunction(Request $request) {
        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');
        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
        $todate=date('Y-m-d',strtotime($request->get('todate')));
        $request->validate([
               'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
               'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
       ]);
       $type1 = $request->get('applTypeName');
       $condition = "";
       if( $type1 <> "-1")
	   {
		  $condition =  " AND (Left(applicationid,2)='$type1' ) ";
	   }
       $result=DB::select("SELECT applicationid,to_date(registerdate,'DD/MM/YYYY') as registerdate,applcatname,applicationo,subject,advocatename,advocateregno,respondentname,applicantname1
		  ,respondentadvcode,respondentadvocate,remarks FROM applicationsummary1 where establishcode='$establishcode' AND to_date(registerdate,'DD/MM/YYYY')>='$fromdate'
		  AND to_date(registerdate,'DD/MM/YYYY')<='$todate'AND scrutinyflag ='N' and objectionflag is null " . $condition .  "Order by registerdate");
        return view('Reports.Scrutiny.scrutinypendingapplicationReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }


}
