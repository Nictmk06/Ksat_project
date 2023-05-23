<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class ApplicationReceivedController extends Controller
{

public static function ApplicationReceived()
{

$data['applicationType'] = DB::select("SELECT * FROM applicationtype order by appltypecode");



        return view('Reports.ApplicationReceived',$data);



    }




public function details(Request $request) {

  $establishcode=Session::get('EstablishCode');
  $establishmentname=Session::get('establishfullname');
  $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
   $todate=date('Y-m-d',strtotime($request->get('todate')));


   $request->validate([
         'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
         'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
 ]);


   $type1 = $request->get('applTypeName');


  $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
  $result=DB::select("SELECT  AP.applicationid,AP.registerdate1,AP.applicantname1,
AP.advocatename,AP.applcatname,R.applicationid,R.receiptdate,R.amount,R.receiptno
from applicationsummary1 AP left join receipt R on AP.applicationid=R.applicationid
where R.receiptuseddate>='$fromdate' and R.receiptuseddate <='$todate'
and R.establishcode='$establishcode'and R.feepurposecode in (1,2,3) AND (Left(R.applicationid,2)='$type1'  OR '$type1'='-1')
Order by AP.registerdate1, AP.applicationid");

  return view('Reports.ApplicationReceivedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
}

}
