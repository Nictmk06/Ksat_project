<?php

namespace App\Http\Controllers\Reports\CCA;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class CCAReportonlineController extends Controller
{
 


      public static function ccaonlinepaid(Request $request)
     {
     //return view('Reports.CCA.ccaonlinepaid');

      return view('Reports.CCA.ccaonlinepaid')->with('user',$request->session()->get('userName'));
     }

  public function onlineccapaidfunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);
            $total=DB::select("select sum(a.totamount) as totalpaid
      from copyapplication as a
      inner join payment_details as b on (a.ccaapplicationno=b.ccaapplicationno )and (a.receiptno =b.challan_ref_no)
    where ( a.ccastatuscode=1 and a.apply_type=1 and a.establishcode='".$establishcode."') 
    and (a.caapplicationdate between '".$fromdate."' and '".$todate."') ");

    //dd($total[0]->totalpaid);

        $result=DB::select("select a.ccaapplicationno,a.applicationid,a.caapplicantname,to_char(a.caapplicationdate,'DD/MM/YYYY') as caapplicationdate1,
      challan_ref_no,apply_type,ccastatuscode,b.mobile as mobile,a.totamount,a.receiptamount,b.bank_transaction_no,a.establishcode
              from copyapplication as a
        inner join payment_details as b on (a.ccaapplicationno=b.ccaapplicationno )and (a.receiptno =b.challan_ref_no)
        where ( a.ccastatuscode=1 and a.apply_type=1 and a.establishcode='".$establishcode."'  )and (a.caapplicationdate between '".$fromdate."' and '".$todate."')" );




        return view('Reports.CCA.ccapaidonlineReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname,'total'=>$total[0]->totalpaid]);
      }

     

}

?>
