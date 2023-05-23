<?php

namespace App\Http\Controllers\Reports\Application;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class ApplicationReportController extends Controller
{
	public static function ApplicationReceived()
	{
		$data['applicationType'] = DB::select("SELECT * FROM applicationtype order by appltypecode");
          return view('Reports.Application.ApplicationReceived',$data);
    }

    public static function Applicationwithfees()
      {
          return view('Reports.Application.Applicationwithfees');
      }

    public static function ReceiptIssuedandnotRealized()
      {
          return view('Reports.Application.ReceiptIssuedandnotRealized');
      }


    public function ApplicationReceivedfunction(Request $request) {
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
		


        $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
        $result=DB::select("SELECT  AP.applicationid,AP.registerdate1,AP.applicantname1,
      AP.advocatename,AP.applcatname,R.applicationid,R.receiptdate,R.amount,R.receiptno
      from applicationsummary1 AP left join receipt R on AP.applicationid=R.applicationid
      where AP.registerdate1>='$fromdate' and AP.registerdate1 <='$todate'
      and AP.establishcode='$establishcode'and R.feepurposecode in (1,2,3) AND (Left(R.applicationid,2)='$type1'  OR '$type1'='-1')
      Order by AP.registerdate1, AP.applicationid");

$subtitle= "";

switch ($type1) {
  case 'OA':
    $subtitle="List of Original Applications received from ".$request->get('fromdate')." to " .$request->get('todate');
    break;
  case 'CA':
    $subtitle="List of Contempt Applications received from ".$request->get('fromdate')." to " .$request->get('todate');
    break;
 case 'RA':
    $subtitle="List of Review Applications received from ".$request->get('fromdate')." to ".$request->get('todate');
    break;
	case 'MA':
    $subtitle="List of Miscellaneous Applications received from ".$request->get('fromdate')." to ".$request->get('todate');
    break;
  default:
    $subtitle="List of Applications received received from ".$request->get('fromdate')." to ".$request->get('todate');
}

    return view('Reports.Application.ApplicationReceivedReport', ['result'=>$result],['subtitle'=>$subtitle,'fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }

      public function Applicationwithfeesfunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);

        $result=DB::select("SELECT AP.applicationo,AP.registerdate1,R.receiptno,R.receiptdate,R.amount  from applicationsummary1 AP left join receipt R on AP.applicationid=R.applicationid
        where AP.registerdate1>='$fromdate' and AP.registerdate1 <='$todate' and R.amount>0 and AP.establishcode ='$establishcode' and R.feepurposecode in (1,2,3) Order By AP.registerdate1,AP.applicationo");
        return view('Reports.Application.ApplicationwithFeesReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }

      public function ReceiptIssuedandnotRealizedfunction(Request $request) {

        $establishcode=Session::get('EstablishCode');
      $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);


        $result=DB::select("SELECT receiptdate,receiptno,amount,name as mname from  receipt where receiptdate >='$fromdate' and receiptdate  <='$todate' and establishcode='$establishcode' and feepurposecode in (1,2,3)  and receiptuseddate  is NULL order by receiptdate,receiptno ");


        return view('Reports.Application.ReceiptIssuedandnotRealizedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);
      }

   public function detailedentryreport()
   {
     $establishcode=Session::get('EstablishCode');
     $establishmentname=Session::get('establishfullname');

	$result=DB::select(" select distinct * from rep_incompleteapplication where establishcode=$establishcode  order by applicationid");
	/*$result=DB::select("select distinct * from 
						( select as1.applicationid,as1.applicationdate,ac.applcatname,as1.subject,as1.applicantcount,
						 as1.respondentcount, 
						(select count(a.applicationid) from applicant a where as1.applicationid = a.applicationid) as appcount,
						(select count(r.applicationid) from respondant r where as1.applicationid = r.applicationid) as rescount
						from application as1 inner join applcategory ac on ac.applcatcode = as1.applcategory
						where establishcode=$establishcode  order by as1.applicationid) as temp 
						where appcount <> applicantcount or respondentcount <> rescount");

	*/

 

     return view('Reports.Application.detailedentryreport', ['result'=>$result],['establishmentname'=>$establishmentname]);

   }
   
    public static function departmentapplicant()
      {
          $data['depttypee'] = DB::select("SELECT * FROM departmenttype order by depttype");
          $data['namedept']=DB::select("SELECT departmentcode,departmentname,depttypecode from department order by LTRIM(departmentname)");
          return view('Reports.Application.departmentapplicant',$data);

      }

      public static function departmentrespondent()
       {
             $data['depttypee1'] = DB::select("SELECT * FROM departmenttype order by depttype");
             $data['namedept']=DB::select("SELECT departmentcode,departmentname,depttypecode from department order by LTRIM(departmentname)");
             return view('Reports.Application.departmentrespondent',$data);

       }



      public function departmentapplicantfunction(Request $request) {
		$establishcode=Session::get('EstablishCode');
		$establishmentname=Session::get('establishfullname');
		$fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
		$todate=date('Y-m-d',strtotime($request->get('todate')));
        $request->validate([
					'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
					'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
			]);
		$type1 = $request->get('applTypeName');
		$depcode=$request->get('depname');
	    if($depcode!=-1)
		   {
			$departmentname=DB::select("SELECT departmentname from department where departmentcode= '$depcode'");
		  }
		  else {
			$departmentname=DB::select("SELECT 'ALL DEPARTMENTS' as departmentname ");
		  }

		$condition = "";

		if( $depcode <> "-1")
		{
		   $condition =  " AND (DP.departmentcode='$depcode' ) ";
		}

    // $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
     $result=DB::select("SELECT  AP.applicationid,to_date(AP.registerdate,'DD/MM/YYYY') as registerdate,
     DPT.depttypecode,DPT.depttype,dp.departmentname,
     AP.applicantname1,AP.subject,AP.advocateregno,AP.advocatename,AP.applcatname
     from applicationsummary1 AP
     LEFT JOIN department DP ON dp.departmentcode=ap.applicantdeptcode
     INNER JOIN departmenttype DPT ON DPT.depttypecode=AP.applicantdepttype
     Where to_date(AP.registerdate,'DD/MM/YYYY') >='$fromdate'
     and to_date(AP.registerdate,'DD/MM/YYYY')<='$todate' AND AP.applicantdepttype='$type1'
      " . $condition . " order by registerdate");
	return view('Reports.Application.departmentapplicantReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'departmentname'=>$departmentname,'establishmentname'=>$establishmentname]);

   }

   public function departmentrespondentfunction(Request $request) {
	 $establishcode=Session::get('EstablishCode');
     $establishmentname=Session::get('establishfullname');
     $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
     $todate=date('Y-m-d',strtotime($request->get('todate')));
      $request->validate([
            'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
            'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
    ]);
    $type1 = $request->get('applTypeName');
	$depcode=$request->get('depname');
     if($depcode!=-1)
     {
      $departmentname=DB::select("SELECT departmentname from department where departmentcode= '$depcode'");
		}
    else {
      $departmentname=DB::select("SELECT 'ALL DEPARTMENTS' as departmentname ");
    }
	 $condition = "";

	if( $depcode <> "-1")
	{
	   $condition =  " AND ( DP.departmentcode='$depcode' ) ";
	}

     $result=DB::select("SELECT  AP.applicationid,to_date(AP.registerdate,'DD/MM/YYYY') as registerdate,
     DPT.depttypecode,DPT.depttype,dp.departmentname,AP.applicantname1,AP.applcatname,
          AP.respondentname,AP.subject,AP.respondentadvcode,AP.respondentadvocate
          from applicationsummary1 AP
          LEFT JOIN department DP ON dp.departmentcode=ap.respontdeptcode
          INNER JOIN departmenttype DPT ON DPT.depttypecode=AP.respontdepttype
          Where to_date(AP.registerdate,'DD/MM/YYYY') >='$fromdate'
          and to_date(AP.registerdate,'DD/MM/YYYY')<='$todate' AND AP.respontdepttype='$type1'
           " . $condition ."order by registerdate");


     return view('Reports.Application.departmentrespondentReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'departmentname'=>$departmentname,'establishmentname'=>$establishmentname]);

   }
   public function findDepWithDeptype($deptypecode)
     {
           $depname = DB::select("SELECT departmentcode,departmentname FROM department WHERE depttypecode='$deptypecode' order by LTRIM(departmentname)");


           return response()->json($depname);
       }


}
?>
