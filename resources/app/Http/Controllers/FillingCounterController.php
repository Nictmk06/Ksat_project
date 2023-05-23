<?php

namespace App\Http\Controllers;
use Session;
use App\ConnectedApplication as ConnectedModel;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\IADocument as IADocumentModel;
use App\Dailyhearing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Input;
use Carbon;
use App\ConnectedApplication1 as ConnectedModel1;
use App\ModuleAndOptions;

class FillingCounterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

  // use AddNewDepartment;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
   protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

    }
    public function FillingCounter(Request $request){

      $receiptsummary=DB::select("select * from receiptsummary");
      $result = DB::select("select receiptdate from receiptsummary");
      if ( count($result) == 0 )
      {
          $sumbegin['receiptdate']         = date('Y-m-d', strtotime(' -1 day'));
          $sumbegin['openingbalcounter']   = 0;
          $sumbegin['daytotalcounter']     = 0;
          $sumbegin['closingbalcounter']   = 0;
          $sumbegin['openingbalfiling']    = 0;
          $sumbegin['daytotalfiling']      = 0;
          $sumbegin['closingbalfiling']    = 0;
          $sumbegin['adjustmentcounter']   = 0;
          $sumbegin['adjustmentfiling']    = 0;
          $sumbegin['remarkscounter']      = "Account closing table was empty";
          $sumbegin['remarksfiling']       = "So beginning record inserted by system";

          if(DB::table('receiptsummary')->insert($sumbegin))
          {
            echo "Summary beginning record inserted..";
          }
          else
          {
              echo "Problem in beginning closing record insertion..";
          }
      }

      //$result = DB::select("SELECT min(receiptdate) as receiptdate from receiptsummary where openingbalfiling is null order  by receiptdate");
    //  $result = DB::select("SELECT max(receiptdate) as receiptdate from receiptsummary where openingbalfiling is not null or openingbalfiling != 0 order  by receiptdate");
      $result=DB::select("SELECT min(receiptdate) as receiptdate  from receiptsummary where openingbalfiling is  null ");//start date
      if(date('Y-m-d',strtotime($result[0]->receiptdate)==NULL))
      {
      $prevclosedt =  date('Y-m-d',strtotime($result[0]->receiptdate));
    //  print_r($prevclosedt);
         if($prevclosedt=='1970-01-01'){
           $todaydate =Carbon\Carbon::now();
           $prevclosedt=$todaydate->toDateTimeString();
         }

      }

      else
      {
        $todaydate =Carbon\Carbon::now();
        $prevclosedt=$todaydate->toDateTimeString();
      }

      $result=DB::select("SELECT max(receiptdate) as receiptdate,closingbalfiling  from receiptsummary where closingbalfiling is not null group by receiptdate order by receiptdate DESC LIMIT 1");// closing balance of previous day which will become opening balanc of Previous account closed date
      if ($result==NULL)
      {
      $closingamt  = 0;
      }
   else
     {
       $closingamt=$result[0]->closingbalfiling;

     }
    $result = DB::SELECT("SELECT MIN(receiptdate) as receiptdate from receiptsummary WHERE receiptdate > '$prevclosedt'");//next date
      if ($result==NULL)
      {
        $todaydate =Carbon\Carbon::now();
        $pclosedt=$todaydate->toDateTimeString();
 //print_r($pclosedt);

      }
      else {

        $pclosedt= date('Y-m-d',strtotime($result[0]->receiptdate));
        if($pclosedt=='1970-01-01')
      {
  $todaydate =Carbon\Carbon::now();
  $pclosedt=$todaydate->toDateTimeString();
       }

      }

  //  if(date('Y-m-d',strtotime($result[0]->receiptdate)==NULL))
/*      {
       $pclosedt= date('Y-m-d',strtotime($result[0]->receiptdate));

      }
      else
      {
        $todaydate =Carbon\Carbon::now();
        $pclosedt=$todaydate->toDateTimeString();
      }
*/
    $data['receipt']=DB::select("SELECT * from receipt where receiptuseddate='$pclosedt'");
    $data['receiptamount']=DB::select("SELECT SUM(amount) as tamount from receipt where receiptuseddate = '$pclosedt'");
    return view('Application.FillingCounter', ['pclosedt' => $prevclosedt,'tclosedt'=>$pclosedt,'pcloseamt' => $closingamt],$data);
    }



    public function FillingCounterSave(Request $request)
{
  $request->validate([
      'today_close_date' => 'required | date | after:prev_close_date',

  ]);
       $pclosedt          = date('Y-m-d',strtotime($request->input('prev_close_date')));

       $pcloseamt         = $request->input('prev_close_amt');
       $tclosedt          = date('Y-m-d',strtotime($request->input('today_close_date')));
       $daycollection     = doubleval($request->input('daycollection'));
       $feeadjust         = doubleval($request->input('feeadjust'));
       $closeremarks      = $request->input('closeremarks');

       $date = $request->input('prev_close_date');
       $date = strtotime($date);
       $date = strtotime("+1 day", $date);
       $pclosedtnext = date('Y-m-d', $date);

       $result = DB::select("SELECT SUM(amount) as tamount from receipt where receiptuseddate = '$tclosedt'");


       $recordSave['receiptdate'] = $tclosedt;
       $receiptdate=$recordSave['receiptdate'];

       $recordSave['openingbalfiling']= doubleval($pcloseamt);
       $openingbalfiling=$recordSave['openingbalfiling'] ;

       $recordSave['daytotalfiling']= doubleval($result[0]->tamount);
       $daytotalfiling=$recordSave['daytotalfiling'];

       $tclosingamt = doubleval($pcloseamt) + doubleval($result[0]->tamount);
       $recordSave['closingbalfiling'] = $tclosingamt;
       $closingbalfiling= $recordSave['closingbalfiling'];

       $recordSave['adjustmentfiling'] = $feeadjust;
       $adjustfilling=$recordSave['adjustmentfiling'];

       $recordSave['remarksfiling']= $closeremarks;
       $remarksfiling= $recordSave['remarksfiling'];

     $day_total = $daycollection + $feeadjust;
     $sys_total = doubleval($result[0]->tamount);

        if ($sys_total != $day_total)
        {
          return redirect()->route('FillingCounter')->with('error', 'Amount entered(Rs. '.$day_total.') is not matching with system total');//System calculated day amount (Rs. '.$sys_total.') and your entered total amount (Rs. '.$day_total.') are not matching, thus Receipt account is not closed
        }

        if(DB::table('receiptsummary'))
        {
           $recordSave['userdetails']=DB::SELECT("UPDATE receiptsummary SET openingbalfiling='$openingbalfiling',closingbalfiling='$closingbalfiling',daytotalfiling=' $daytotalfiling',adjustmentfiling='$adjustfilling',remarksfiling=' $remarksfiling' where receiptdate='$pclosedt' ");
           $recordSave['entereddaytotal']= $daycollection;
           $recordSave['pclosedtnext']        = $pclosedt;
           $recordSave['openingbalcounter']  = doubleval($pcloseamt);
           $recordSave['daytotalcounter']    = doubleval($result[0]->tamount);
           $tclosingamt = doubleval($pcloseamt) + doubleval($result[0]->tamount);
           $recordSave['closingbalcounter']  = $tclosingamt;
           $recordSave['adjustmentcounter']  = $feeadjust;
           $recordSave['remarkscounter']     = $closeremarks;




          return view('Receipts.dayclosestore', $recordSave);
        }

        else
        {
          return redirect()->route('FillingCounter')->with('error', 'Someting went wrong, Receipt account not closed !!');
        }

   }






  }
