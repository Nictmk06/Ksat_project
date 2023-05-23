<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class ApplicationwithfeesController extends Controller
{

public static function Applicationwithfees()
{




        return view('Reports.Applicationwithfees');



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

  $result=DB::select("SELECT AP.applicationo,AP.registerdate1,R.receiptno,R.receiptdate,R.amount  from applicationsummary1 AP left join receipt R on AP.applicationid=R.applicationid
  where R.receiptuseddate>='$fromdate' and R.receiptuseddate <='$todate' and R.amount>0 and R.establishcode ='$establishcode' and R.feepurposecode in (1,2,3) Order By AP.registerdate1,AP.applicationo");




  return view('Reports.ApplicationwithFeesReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
}

}
