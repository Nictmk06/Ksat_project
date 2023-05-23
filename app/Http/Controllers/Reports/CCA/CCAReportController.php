<?php

namespace App\Http\Controllers\Reports\CCA;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class CCAReportController extends Controller
{
  public static function listofccareceived()
  {

          return view('Reports.CCA.listofccareceived');
    }


      public static function applicationwihfee()
      {
          return view('Reports.CCA.applicationwihfee');
      }

      public static function listofccastatus()
      {
         $data['status'] = DB::select("SELECT ccastatuscode,ccastatusdesc FROM ccastatus order by ccastatuscode");
          return view('Reports.CCA.listofccastatus',$data);
      }

      public static function ccadelivery()
      {
          return view('Reports.CCA.ccadelivery');
      }


      public static function ccadeficit()
     {
     return view('Reports.CCA.ccadeficit');
      }

      public static function ccapaid()
     {
     return view('Reports.CCA.ccapaid');
     }

      public function listofccareceivedfunction(Request $request) {
                  $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');
        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));
               $request->validate([
               'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
               'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
       ]);
     

        //$applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
      //dd($fromdate); 
       $result=DB::select("SELECT applicationid,caapplicationdate,ccaapplicationno,documenttype,documentname,cano,caapplicantname,receiptdate
    FROM copyapplication Where
    caapplicationdate >='$fromdate' and caapplicationdate <='$todate'
    and establishcode='$establishcode' order by caapplicationdate");



        return view('Reports.CCA.listofccareceivedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }

      public function applicationwihfeefunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);

        $result=DB::select("SELECT applicationid,caapplicationdate,ccaapplicationno,documenttype,cano,caapplicantname,receiptno,totamount,receiptdate
FROM copyapplication Where
caapplicationdate >='$fromdate' and caapplicationdate <=' $todate' and
establishcode='$establishcode' and totamount >0 order by caapplicationdate
");




        return view('Reports.CCA.applicationwihfeeReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }

      public function listofccastatusfunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);
          $type1 = $request->get('applTypeName');


        if($type1!=-1)
        {
          $statusname=DB::select("SELECT ccastatusdesc from ccastatus where ccastatuscode='$type1'");
       }
       else {
         $statusname=DB::select("SELECT 'ALL STATUS' as ccastatusdesc ");
        }

       $condition = "";

       if( $type1 <> "-1")
       {
       $condition =  " AND ccastatuscode='$type1' ";
       }
        $result=DB::select("SELECT applicationid,caapplicationdate,ccaapplicationno,documenttype,documentname,
      cano,caapplicantname
      FROM copyapplication
      Where caapplicationdate >='$fromdate'
      and caapplicationdate <=' $todate' and
      establishcode='$establishcode'".$condition."order by caapplicationdate");






        return view('Reports.CCA.listofccastatusReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname,'statusname'=>$statusname]);
      }

      public function ccadeliveryfunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);

        $result=DB::select("SELECT CA.caapplicationdate,CA.ccaapplicationno,CA.applicationid,CA.documenttype,CA.documentname,
        CA.deliveredon,CA.deliveredby,CA.deliverycode,CA.deliveredto,CA.cano,dm.deliverydesc
        from copyapplication as CA INNER JOIN deliverymode as dm ON CA.deliverycode=dm.deliverycode
         Where CA.deliveredon >='$fromdate' and CA.deliveredon <='$todate'
        and CA.establishcode='$establishcode' order by CA.deliveredon");




        return view('Reports.CCA.ccadeliveryReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }

      public function ccadeficitfunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);

        $result=DB::select("SELECT caapplicationdate,
        applicationid,
        caapplicantname,
        documentname,
        documenttype,
        cano,
        ccaapplicationno,
        receiptamount,
        totamount,
        totamount-(coalesce(receiptamount,0) + coalesce(deficitamount,0)) as damount,
        deficitnotifieddate,
        deficitreceiptdate from copyapplication where caapplicationdate >='$fromdate' and
        caapplicationdate<=' $todate' and totamount-(coalesce(receiptamount,0) +coalesce(deficitreceiptamount,0))>0
        and ccastatuscode='1' and establishcode='$establishcode'");





        return view('Reports.CCA.ccadeficitReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }


      public function ccapaidfunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);

        $result=DB::select("SELECT caapplicationdate,
applicationid,
caapplicantname,
documentname,
documenttype,
cano,
ccaapplicationno,
totamount,
receiptamount,
deficitamount,
deficitnotifieddate,
deficitreceiptdate from copyapplication where caapplicationdate >='$fromdate' and
caapplicationdate<='$todate' and totamount - (receiptamount + deficitamount) = 0
and ccastatuscode='1' and establishcode='$establishcode'");




        return view('Reports.CCA.ccapaidReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }

}

?>
