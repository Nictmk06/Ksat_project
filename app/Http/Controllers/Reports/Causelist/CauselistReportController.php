<?php

namespace App\Http\Controllers\Reports\Causelist;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class CauselistReportController extends Controller
{
public static function furtherdiary()
  {
     return view('Reports.Causelist.furtherdiary');
    }  

 public function furtherdiaryfunction(Request $request) {

     $establishcode=Session::get('EstablishCode');
     $establishmentname=Session::get('establishfullname');
     $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
      $todate=date('Y-m-d',strtotime($request->get('todate')));

      $request->validate([
            'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
            'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
    ]);



  $result=DB::select("SELECT nextdate,nextbenchtypename,nextbenchname,nextlistpurpose,courtdirection,applicationid  from
   hearingsummary1  where to_date(nextdate,'DD/MM/YYYY')>='$fromdate'
   and to_date(nextdate,'DD/MM/YYYY') <='$todate' order by to_date(nextdate,'DD/MM/YYYY')");



  return view('Reports.Causelist.furtherdiaryReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
   }

}
