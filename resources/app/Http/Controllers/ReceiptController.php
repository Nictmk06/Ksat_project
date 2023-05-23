<?php



namespace App\Http\Controllers;

use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use session;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $establishcode = $request->session()->get('EstablishCode');
            $user  = $request->session()->get('username');
            $tdate = date('Y-m-d');
            $receipts = DB::table('receipt')->select('*')->where('receiptdate', '=', $tdate)->where('establishcode', '=', $establishcode)->orderby('receiptdate')->orderby('receiptsrno')->simplePaginate(12);
	        return view('Receipts.index', compact('receipts')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['feepurposes']  = DB::table('feepurpose')->select('*')->orderby('purposecode')->get();
        $data['bankbranches'] = DB::table('bankbranch')->select('*')->orderby('bankdesc')->get();
        $data['rlcd']         = DB::select("select receiptdate from receiptsummary order by receiptdate desc limit 1");
        return view('Receipts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 

        $request->validate([
            'receiptDate' => 'required | date | after:receipt_Last_Closed_Date',
            'advocateComplainant' => 'required',
            'feePurpose' => 'required',
            'applTitle' => 'required',
            'applicantName' => 'required',
            'feeAmount' => 'required|numeric|max:9999.99',
            'paymentMode' => 'required',
            'ddNumber' => 'required_if:paymentMode,D',
            'ddDate' => 'required_if:paymentMode,D',
            'drawnBank' => 'required_if:paymentMode,D',

        ]);  
         $cur_year = date("Y");
         $establishcode = $request->session()->get('EstablishCode');
         $nextno =  DB::select("SELECT f_receiptsrno('$establishcode','$cur_year','R') as nin");
        
         $nextno = $nextno[0]->nin;
         list($yr, $receiptsrno) = explode('/', $nextno);

         $receiptno = $establishcode . '/' . $yr . '/' . $receiptsrno;

         // Construct input data array
         $receiptSave['receiptno']            = $receiptno;
         $receiptSave['receiptsrno']          = $receiptsrno;
         $receiptSave['receiptdate']          = date('Y-m-d',strtotime($request->input('receiptDate')));
         $receiptSave['applicantadvocate']    = $request->input('advocateComplainant');

         list($feecode, $paycode)             = explode(':', $request->input('feePurpose'));

         $receiptSave['feepurposecode']       = intval($feecode);
         $receiptSave['paymentcode']          = intval($paycode);
         $receiptSave['titlename']            = $request->input('applTitle');
         $receiptSave['name']                 = $request->input('applicantName');
         $receiptSave['otherdetails']         = $request->input('otherDetails');
         $receiptSave['amount']               = $request->input('feeAmount');
         $receiptSave['modeofpayment']        = $request->input('paymentMode');
         if ($request->input('paymentMode') == 'D')
         {
         $receiptSave['ddchqno']              = $request->input('ddNumber');
         $receiptSave['ddchqdate']            = date('Y-m-d',strtotime($request->input('ddDate')));
         $receiptSave['bankcode']             = $request->input('drawnBank');
         }
         else
         {
         $receiptSave['ddchqno']              = null;
         $receiptSave['ddchqdate']            = null; 
         $receiptSave['bankcode']             = null;
         }
         $receiptSave['createdby']            = $request->session()->get('username');
         $receiptSave['createdon']            = date('Y-m-d H:i:s') ;
         $receiptSave['establishcode']        = $establishcode ;
         if(DB::table('receipt')->insert($receiptSave))
         {
            return redirect()->route('receiptCrudIndex')->with('success', 'Receipt saved successfully, Receipt No.:- '. $receiptno );
         }
         else
         {
            return redirect()->route('receiptCrudIndex')->with('error', 'Someting wrong, Receipt not saved !!');
         }
    }

    public function showdtrange(Request $request)
    {
        $data['feepurposes']  = DB::table('feepurpose')->select('*')->orderby('purposecode')->get();
        return view('Receipts.showdtrange', $data);
    }
      /**
     * Display the specified resource.
     *
     * @param  \App\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    
 
    public function show(Request $request)
    {

        $request->validate([
            'fromdate' => 'required | date',
            'todate' => 'required | date | after_or_equal:fromdate',
            ]);  
            $user     = $request->session()->get('username');
            $fdate    = date('Y-m-d',strtotime($request->input('fromdate')));
            $tdate    = date('Y-m-d',strtotime($request->input('todate')));
            $feepurp  = intval($request->input('feepurpose'));
            $zeroamt  = $request->input('zeroamount');
            $establishcode = $request->session()->get('EstablishCode');
        

         //  $cond = " createdby = '$user' and receiptdate >= '$fdate' and receiptdate <= '$tdate' ";
              $cond = " receiptdate >= '$fdate' and receiptdate <= '$tdate' and establishcode=$establishcode ";
            if ($feepurp > 0)
            $cond = $cond." and feepurposecode = '$feepurp' ";
            if ($zeroamt == 'zero')
            $cond = $cond." and amount = 0 ";
           

            $receiptshow = DB::select("select * from receipt where ($cond) order by receiptdate, receiptsrno");
           // $receiptshow = DB::table('receipt')->select('*')->where('receiptdate', '>=', $fdate)->where('receiptdate', '<=', $tdate)->where('createdby', '=', $user)->orderby('receiptsrno')->get();
	        return view('Receipts.show', compact('receiptshow'));

    } 
    public function editlist(Request $request)
    {
         $establishcode = $request->session()->get('EstablishCode');       
         $user   = $request->session()->get('username');
         $result = DB::select("select receiptdate from receiptsummary order by receiptdate desc limit 1");
         $rlcd   = $result[0]->receiptdate;
         $receipts = DB::table('receipt')->select('*')->where('applicationid', '=', null)->where('receiptdate', '>', $rlcd)->where('establishcode', '=', $establishcode)->orderby('receiptdate')->orderby('receiptsrno')->simplePaginate(12);
	    return view('Receipts.editlist', compact('receipts')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user  = $request->session()->get('username');
        $data['feepurposes']  = DB::table('feepurpose')->select('*')->orderby('purposecode')->get();
        $data['bankbranches'] = DB::table('bankbranch')->select('*')->orderby('bankdesc')->get();
        $data['receipts']     = DB::table('receipt')->select('*')->where('receiptno', '=', $request->rptno)->orderby('receiptsrno')->get();
        return view('Receipts.edit', $data);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        $request->validate([
            'advocateComplainant' => 'required',
            'feePurpose' => 'required',
            'applTitle' => 'required',
            'applicantName' => 'required',
            'feeAmount' => 'required|numeric|max:9999.99',
            'paymentMode' => 'required',
            'ddNumber' => 'required_if:paymentMode,D',
            'ddDate' => 'required_if:paymentMode,D',
            'drawnBank' => 'required_if:paymentMode,D',

        ]); 
        
         // Construct input data array
         $receiptno                             = $request->input('receiptNo');
         $receiptUpdate['receiptdate']          = date('Y-m-d',strtotime($request->input('receiptDate')));
         $receiptUpdate['applicantadvocate']    = $request->input('advocateComplainant');

         list($feecode, $paycode)               = explode(':', $request->input('feePurpose'));

         $receiptUpdate['feepurposecode']       = intval($feecode);
         $receiptUpdate['paymentcode']          = intval($paycode);
         $receiptUpdate['titlename']            = $request->input('applTitle');
         $receiptUpdate['name']                 = $request->input('applicantName');
         $receiptUpdate['otherdetails']         = $request->input('otherDetails');
         $receiptUpdate['amount']               = $request->input('feeAmount');
         $receiptUpdate['modeofpayment']        = $request->input('paymentMode');
         if ($request->input('paymentMode') == 'D')
         {
         $receiptUpdate['ddchqno']              = $request->input('ddNumber');
         $receiptUpdate['ddchqdate']            = date('Y-m-d',strtotime($request->input('ddDate')));
         $receiptUpdate['bankcode']             = $request->input('drawnBank');
         }
         else
         {
         $receiptUpdate['ddchqno']              = null;
         $receiptUpdate['ddchqdate']            = null;
         $receiptUpdate['bankcode']             = null;
         }
         $receiptUpdate['updatedby']            = $request->session()->get('username');
         $receiptUpdate['updatedon']            = date('Y-m-d H:i:s') ;
       

         if(DB::table('receipt')->where('receiptno', $receiptno)->update($receiptUpdate))
         {
            return redirect()->route('receiptCrudEditlist')->with('success', 'Receipt Updated successfully.');
         }
         else
         {
            return redirect()->route('receiptCrudEditlist')->with('error', 'Some thing wrong, update not successfull !!');
         }   

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    
    public function getfeeamount(Request $request)
    {
          $purposecode = $_GET['feepurposecode']; 
          $data['feeamount'] = DB::table('feepurpose')->select('feeamount')->where('purposecode', '=', $purposecode)->get();
          echo json_encode($data['feeamount']);
    }

    public function receiptgen(Request $request)
    {
        $data['receipts'] = DB::select("select rp.*, bk.bankdesc as bankname, fp.purposename as purposename from receipt as rp left join bankbranch as bk on (rp.bankcode=bk.bankcode), feepurpose as fp where (rp.receiptno='".$request->rptno."' and rp.feepurposecode=fp.purposecode)");
        return view('Receipts.receiptgen', $data);
        
    }

    // Receipt day account close methods

    public function receiptclosedtget(Request $request)
    {

        // Check for beginning of account closing, insert beginning record, if table is empty
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
            $sumbegin['remarkscounter']      = "Account closing table was empmty";
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

        $result = DB::select("select receiptdate, closingbalcounter from receiptsummary order by receiptdate desc limit 1");
        $prevclosedt = $result[0]->receiptdate;
        $closingamt  = $result[0]->closingbalcounter;

        return view('Receipts.dayclosedtget', ['pclosedt' => $prevclosedt, 'pcloseamt' => $closingamt]);
   
    }

    public function receiptclosestore(Request $request)
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
         $pclosedtnext      = date('Y-m-d', $date);

         $result = DB::select("select sum(amount) as tamount from receipt where (receiptdate >= '$pclosedtnext' and receiptdate <= '$tclosedt')");

         $recordSave['receiptdate']        = $tclosedt;
         $recordSave['openingbalcounter']  = doubleval($pcloseamt);
         $recordSave['daytotalcounter']    = doubleval($result[0]->tamount);
         $tclosingamt = doubleval($pcloseamt) + doubleval($result[0]->tamount);
         $recordSave['closingbalcounter']  = $tclosingamt;
         $recordSave['adjustmentcounter']  = $feeadjust;
         $recordSave['remarkscounter']     = $closeremarks;

         $day_total = $daycollection + $feeadjust;
         $sys_total = doubleval($result[0]->tamount);

            if ($sys_total != $day_total)
            {
              return redirect()->route('receiptCrudCloseDtget')->with('error', 'System calculated day amount (Rs. '.$sys_total.') and your entered total amount (Rs. '.$day_total.') are not matching, thus account is not closed');
            }
         
            if(DB::table('receiptsummary')->insert($recordSave))
            {
              $recordSave['entereddaytotal']     = $daycollection;
              return view('Receipts.dayclosestore', $recordSave);
            }
            else
            {
              return redirect()->route('receiptCrudCloseDtget')->with('error', 'Someting went wrong, Receipt account not closed !!');
            }

    }

    // Receipt reports

    public function receiptreport(Request $request)
    {
        return view('Receipts.reports');
    }

    public function receiptrepdailygetdt(Request $request)
    {
        $data['feepurposes']  = DB::table('feepurpose')->select('*')->orderby('purposecode')->get();
        $data['paymentheads']  = DB::table('paymentheads')->select('*')->orderby('paymentcode')->get();

        return view('Receipts.repdailygetdt', $data);
    }

    
    public function receiptrepdaily(Request $request)
    {
        $request->validate([
            'fromdate' => 'required | date',
            'todate' => 'required | date | after_or_equal:fromdate',
            ]);  
            $user     = $request->session()->get('username');
            $fdate    = date('Y-m-d',strtotime($request->input('fromdate')));
            $tdate    = date('Y-m-d',strtotime($request->input('todate')));
            //$feepurp  = intval($request->input('feepurpose'));   
            $paymentcode  = intval($request->input('paymentheads'));
            $zeroamt  = $request->input('zeroamount');

            $cond = " r.receiptdate >= '$fdate' and r.receiptdate <= '$tdate' and r.feepurposecode=p.purposecode and r.paymentcode=h.paymentcode ";
            if ($paymentcode > 0)
            $cond = $cond." and r.paymentcode = '$paymentcode' ";
            if ($zeroamt == 'zero')
            $cond = $cond." and r.amount = 0 ";
           
            $receiptshow = DB::select("select r.*, p.purposename as purposename, h.headofaccount as headofaccount, b.bankdesc as bankname from receipt as r left join bankbranch as b on (r.bankcode=b.bankcode), feepurpose as p, paymentheads as h where ($cond) order by r.paymentcode, r.receiptdate, r.receiptsrno");
            return view('Receipts.repdaily', compact('receiptshow'));
    }

    public function receiptrepsummgetdt(Request $request)
    {
       return view('Receipts.repsummgetdt');
    }

    public function receiptrepsummary(Request $request)
    {
            $request->validate([
            'fromdate' => 'required | date',
            'todate' => 'required | date | after_or_equal:fromdate',
            ]);  
            $fdate    = date('Y-m-d',strtotime($request->input('fromdate')));
            $tdate    = date('Y-m-d',strtotime($request->input('todate')));
            
            $cond = " receiptdate >= '$fdate' and receiptdate <= '$tdate' ";
                       
            $receiptshow = DB::select("select * from receiptsummary  where ($cond) order by receiptdate");
            return view('Receipts.repsummary', compact('receiptshow'));
    }


    public function getReceiptDetails(Request $request) {
         $establishcode = $request->session()->get('EstablishCode');        
         $receiptNo = $request->input('receiptNo');
         $data['receipt']= DB::select("select * from receipt where receiptno =:receiptno and applicationid is null and establishcode=$establishcode",['receiptno' => $receiptNo]);
          echo json_encode($data['receipt']);
     }

}
