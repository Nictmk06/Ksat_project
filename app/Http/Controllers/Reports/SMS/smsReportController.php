<?php

namespace App\Http\Controllers\Reports\SMS;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class smsReportController  extends Controller
{
	public static function smssent()
	{
		$data['module'] = DB::select("SELECT * from module  where  sms='Y' order by modulecode");
          return view('Reports.SMS.smssent',$data);
    }

  public function smssentfunction(Request $request) {
        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');
        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
        $todate=date('Y-m-d',strtotime($request->get('todate')));

         $request->validate([
               'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
               'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate,
			   'applTypeName' => 'required'
       ]);


        $type1 = $request->get('applTypeName');
        $module=$request->get('module');
        $modulename=DB::SELECT("SELECT modulename from module where modulecode='$module'")[0]->modulename;

      //  $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
				$result=DB::select("SELECT s.applicationid, s.smscontent,s.sentondate,s.modulecode,m.modulename,
					s.deliverystatus,s.smspreparedate,s.mobileno
			from sms s inner join module m on s.modulecode=m.modulecode
			where s.sentondate>='$fromdate' and s.sentondate <='$todate'
			and  (s.advocateflag='$type1'  OR '$type1'='-1') and  (s.modulecode='$module'  OR '$module'='0')
			Order by s.sentondate, s.applicationid");

$subtitle= "";

switch ($type1) {
  case 'Y':
    $subtitle="List of sms sent to Advocates  from ".$request->get('fromdate')." to " .$request->get('todate');
    break;
  case 'N':
    $subtitle="List of sms sent to non Advocates from ".$request->get('fromdate')." to " .$request->get('todate');
    break;

   default:
    $subtitle="List of sms sent  from ".$request->get('fromdate')." to ".$request->get('todate');
}

    return view('Reports.SMS.smssentreport', ['result'=>$result],['modulename'=>$modulename,'subtitle'=>$subtitle,'fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }



}
?>
