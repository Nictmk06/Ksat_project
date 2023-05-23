<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class ReceiptIssuedandnotRealizedController extends Controller
{

public static function ReceiptIssuedandnotRealized()
{





        return view('Reports.ReceiptIssuedandnotRealized');



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


  $result=DB::select("SELECT receiptdate,receiptno,amount,name as mname from  receipt where receiptdate >='$fromdate' and receiptdate  <='$todate' and establishcode='$establishcode' and feepurposecode in (1,2,3)  and receiptuseddate  is NULL order by receiptdate,receiptno ");


  return view('Reports.ReceiptIssuedandnotRealizedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
}

}
