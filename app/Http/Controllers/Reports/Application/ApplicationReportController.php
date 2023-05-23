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
public static function pendingapplication()
{
$data['applicationType'] = DB::select("SELECT * FROM applicationtype order by appltypecode");
$data['applicationYear'] = DB::select("SELECT distinct applicationyear from application order by applicationyear asc");

	return view('Reports.Application.pendingapplication',$data);
}
public  function pendingapplication1(Request $request){

   $applTypeName=$request->applTypeName;
   $establishcode=Session::get('EstablishCode');
   $appltypedesc=$request->appltypedesc;
   $applicationyear=$request->applicationYear;

   if($request->registerdate) {
    $registerdate=date('Y-m-d', strtotime($request->registerdate));
   } else {
    $registerdate="";
   }





   $str1="SELECT  apt.appltypedesc,applicationyear,substring(a.applicationid,1,2) as shortname, count( a.applicationid) as applicationcount, sum(a.applicantcount) as applicantcount,
sum(a.applicationtosrno-a.applicationsrno+1) + coalesce(sum(groupnocount),0) as group_Applicant_count
from application a
left join (select g.applicationid, sum(g.applicationtosrno-g.applicationsrno+1) as groupnocount
from groupno g group by g.applicationid ) as groupno
using (applicationid)
left join applicationtype apt on apt.appltypecode = a.appltypecode
where (statusid='1' or statusid is null) and a.establishcode = '$establishcode'";

	$str2="";
$str3=" group by apt.appltypedesc,applicationyear,substring(a.applicationid,1,2) order by applicationyear DESC ,apt.appltypedesc asc";

   if($applicationyear != 1){
		 $str2=" and applicationyear='$applicationyear'";
	 }
	 if($applTypeName !=1){
		 $str2=$str2." and Left(a.applicationid,2)='$applTypeName'";
	 }

	 if($registerdate!=''){
		 $str2=$str2." and registerdate<='$registerdate'";

	 }

	$resultquery =$str1.$str2.$str3;
   $result=DB::select($resultquery);
  return view('Reports.Application.pendingapplication1',['result'=>$result],['registerdate'=>$registerdate]);

}


public function pendingapplication2(Request $request){

      $id=$request->id;
      $applicationyear= $request->applicationyear;
      $appltypecode= $request->appltypecode;

      $result=DB::select("SELECT applicationyear,appltypecode from application where applicationyear='$id'");

      return view('Reports.Application.pendingapplication2',['result'=>$result]);
}

public function pendingapplicationReport2(Request $request) {
  	$establishcode=Session::get('EstablishCode');
  	$establishmentname=Session::get('establishfullname');
    $appltypedesc=$request->appltypedesc;
    $shortname= $request->shortname;
    $applicationyear=$request->id;
    $registerdate= $request->registerdate;

   $subtitle="List of Pending ".$appltypedesc."  during the year ". $applicationyear;

  $str1="SELECT a.applicationid, apt.applcatname,  a.registerdate,
  count(a.applicationtosrno - a.applicationsrno+1) as groupapplicationcount, count(a.applicationid) as totalapplicants
  FROM application a
  left join applicant app using (applicationid)
  LEFT JOIN applcategory apt ON a.applcategory=apt.applcatcode
  where (a.establishcode=".$establishcode." and a.applicationyear=".$applicationyear."";

 $str3=" and Left(a.applicationid,2)='$shortname') and (a.statusid='1' or a.statusid is null)
       GROUP BY a.applicationid, apt.applcatname,a.applicationtosrno - a.applicationsrno+1";

		if($registerdate == ''){
		$str2="";
		}
		if($registerdate != ''){
		$str2= " and registerdate<='$registerdate'";
	        }



 $resultquery=$str1.$str2.$str3;
 $result=DB::select($resultquery);


    return view('Reports.Application.pendingapplicationReport2',['result'=>$result],['establishmentname'=> $establishmentname,'subtitle'=>$subtitle,'registerdate'=>$registerdate]);
}

  

   public function  ApplicationReceived1(Request $request){

$establishcode=Session::get('EstablishCode');
$establishmentname=Session::get('establishfullname');

$applTypeName=$request->applTypeName;
$fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
$todate=date('Y-m-d',strtotime($request->get('todate')));


 $request->validate([
	 'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
	 'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate,
	 'applTypeName' => 'required'
			 ]);

        
$str1="select apt.appltypedesc,substring(a.applicationid,1,2), count( a.applicationid) as applicationcount,sum(a.applicantcount) as applicantcount, 
sum(a.applicationtosrno-a.applicationsrno+1) +  coalesce(sum(groupnocount),0) as group_Applicant_count  from application a 
left join (select g.applicationid, sum(g.applicationtosrno-g.applicationsrno+1) as groupnocount   from groupno g group by g.applicationid ) as groupno using (applicationid)
 LEFT JOIN applicationtype apt ON substring(a.applicationid,1,2) = apt.appltypeshort
where a.establishcode = '$establishcode' and a.registerdate >= '$fromdate' and a.registerdate <= '$todate' ";

$str2="";
$str3=" group by apt.appltypedesc,substring(a.applicationid,1,2)";



$resultquery=$str1.$str2.$str3;
$result=DB::SELECT($resultquery);
return view('Reports.Application.applicationreceived1',['result'=>$result,'fromdate'=>$fromdate,'todate'=>$todate,'applTypeName'=>$applTypeName]);

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
		


      /*  $result=DB::select("SELECT  AP.applicationid, AP.registerdate1,AP.applicantname1,
  AP.advocatename,AP.applcatname, STRING_AGG(R.amount::TEXT,',') AS AMOUNT, STRING_AGG(R.receiptno,',') AS RECEIPTNO  from applicationsummary1 AP left join receipt R on AP.applicationid=R.applicationid  where AP.registerdate1>='$fromdate' and AP.registerdate1 <='$todate' and AP.establishcode='$establishcode' and R.feepurposecode in (1,2,3)
AND (Left(R.applicationid,2)='$type1'  OR '$type1'='-1')
 GROUP BY AP.applicationid,AP.registerdate1,AP.applicantname1,
 AP.advocatename,AP.applcatname
 Order by AP.applicationid");
*/

/*$result=DB::select("SELECT  AP.applicationid, AP.registerdate1,AP.applicantname1,ap.applicantcount,ap.respondentcount,
 ap.subject,ap.respondentname,STRING_AGG(c1.conapplid::TEXT,',') as conapplid,
  AP.advocatename,AP.applcatname,R.amount,R.receiptno  from applicationsummary1 AP
  left join receipt R on AP.applicationid=R.applicationid
  left join connecetdappldtls c1 on AP.applicationid=c1.applicationid
  where AP.registerdate1>='$fromdate' and AP.registerdate1 <='$todate' and
  AP.establishcode='$establishcode'
  and R.feepurposecode in (1,2,3)
  AND (Left(R.applicationid,2)='$type1'  OR '$type1'='-1')
  GROUP BY AP.applicationid,AP.registerdate1,AP.applicantname1,ap.applicantcount,ap.respondentcount,
  ap.subject,ap.respondentname,
  AP.advocatename,AP.applcatname,R.amount,R.receiptno
  Order by AP.applicationid");
*/
/*$result=DB::SELECT("SELECT  AP.applicationid, AP.registerdate1,AP.applicantname1,ap.applicantcount,ap.respondentcount,
 ap.subject,ap.respondentname,
   coalesce(STRING_AGG(c1.conapplid,','),'-') as conapplid
  ,
  AP.advocatename,AP.applcatname,STRING_AGG(R.amount::TEXT,',') as amount,
  STRING_AGG(R.receiptno::TEXT,',') as receiptno from applicationsummary1 AP
  left join receipt R on AP.applicationid=R.applicationid
  left join connecetdappldtls c1 on AP.applicationid=c1.applicationid
  where  AP.registerdate1>='$fromdate' and AP.registerdate1 <='$todate' and
  AP.establishcode='$establishcode'
  and R.feepurposecode in (1,2,3)
  AND (Left(R.applicationid,2)='$type1'  OR '$type1'='-1') 
  GROUP BY  AP.applicationid,AP.registerdate1,AP.applicantname1,ap.applicantcount,ap.respondentcount,
  ap.subject,ap.respondentname,
  AP.advocatename,AP.applcatname

  Order by AP.applicationid");

*/
     DB::SELECT("CREATE TEMPORARY TABLE temp_details(
     applicationid varchar(50),conapplid varchar(500),receiptno varchar(500),amount varchar(500))");

DB::SELECT("INSERT INTO temp_details(SELECT  ap.applicationid,
STRING_AGG(DISTINCT c1.conapplid::TEXT,',' ) as conapplid,STRING_AGG(DISTINCT R.receiptno,',' ) AS RECEIPT ,
(select STRING_AGG( amount::TEXT,',') AS AMOUNT from
receipt where ap.applicationid=applicationid) as amount
from applicationsummary1 AP
left join receipt R on AP.applicationid=R.applicationid
left join connecetdappldtls c1 on AP.applicationid=c1.applicationid
where AP.registerdate1>='$fromdate' and AP.registerdate1 <='$todate' and
AP.establishcode='$establishcode'

AND (Left(R.applicationid,2)='$type1'  OR '$type1'='-1')
GROUP BY ap.applicationid
Order by  ap.applicationid)");

$result=DB::SELECT("SELECT t.applicationid,t.conapplid,t.receiptno,t.amount,AP.registerdate1,AP.applicantname1,ap.applicantcount,ap.respondentcount,
 ap.subject,ap.respondentname, AP.advocatename,AP.applcatname,AP.total_additionalno
 from temp_details t left join applicationsummary1 as AP on t.applicationid=ap.applicationid order by Ap.registerdate1,t.applicationid");

$temp=DB::select("DROP TABLE  temp_details");

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
	   $condition =  " AND (d.departmentcode='$depcode' ) ";
	}

	$result=DB::select("SELECT DISTINCT
	as1.departmentname, as1.applicationid,a.registerdate,ac.applcatname,A.subject
	from applicantsummary1 as1
	inner join department d on as1.departmentname=d.departmentname
        inner join application a on a.applicationid = as1.applicationid
	inner join applcategory ac on a.applcategory = ac.applcatcode
	Where a.establishcode='$establishcode' AND a.registerdate >='$fromdate'
	and a.registerdate<='$todate' " . $condition . " order by registerdate");

    return view('Reports.Application.departmentapplicantReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'depcode'=>$depcode,'departmentname'=>$departmentname,'establishmentname'=>$establishmentname]);
 
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
	   $condition =  " AND ( rs1.departmentcode='$depcode' ) ";
	}

     $result=DB::select("SELECT DISTINCT
     rs1.departmentname, rs1.applicationid, a.registerdate as registerdate,ac.applcatname,A.subject
     from respondantsummary1 rs1 inner join application a on a.applicationid = rs1.applicationid
     inner join applcategory ac on a.applcategory = ac.applcatcode
     Where a.establishcode='$establishcode' AND a.registerdate >='$fromdate'
     and a.registerdate<='$todate' " . $condition . " order by registerdate");

     return view('Reports.Application.departmentrespondentReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'departmentname'=>$departmentname,'depcode'=>$depcode,'establishmentname'=>$establishmentname]);
   }
   
   public function findDepWithDeptype($deptypecode)
     {
           $depname = DB::select("SELECT departmentcode,departmentname FROM department WHERE depttypecode='$deptypecode' order by LTRIM(departmentname)");


           return response()->json($depname);
       }


 public static function listofdocumentsreceived()
    {
           $data['documentcode']=DB::select("SELECT documenttypecode,documentname from documenttype order by documentname");
          return view('Reports.Application.listofdocumentsreceived',$data);
         }
		 
	 public function listofdocumentsreceivedfunction(Request $request) {
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
		   $documentname=DB::select("SELECT documentname from documenttype where documenttypecode= '$type1'");
		 }
		 else {
		   $documentname=DB::select("SELECT 'ALL Documents' as documentname ");
			}
		$condition = "";
		if( $type1 <> "-1")
		{
		   $condition =  " AND documenttypecode='$type1' ";
		}
		$result=DB::select("SELECT R.applicationid,documentname,documenttypecode,iano,ianaturedesc,iafillingdate,
			   iaregistrationdate,statusname,AP1.establishcode from  rep_documentsreceived R INNER JOIN applicationsummary1 AP1
			  ON R.applicationid=AP1.applicationid
			  where AP1.establishcode='$establishcode'
			 AND iafillingdate>='$fromdate' and iafillingdate <='$todate' ".$condition.
	    	" order by iafillingdate ");
     return view('Reports.Application.listofdocumentsreceivedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'type1'=>$type1,'documentname'=>$documentname,'establishmentname'=>$establishmentname]);
   
}

public function pendingapplicationfunction(Request $request) {
			 $establishcode=Session::get('EstablishCode');
			 $establishmentname=Session::get('establishfullname');


				$request->validate([
										'applTypeName' => 'required'
			]);


			 $type1 = $request->get('applTypeName');


			

			// $applicationid=DB::select("SELECT applicationid from applicationsummary1  order by applicationid");
			 $result=DB::select("SELECT a.applicationid, apt.appltypedesc, date_part('year'::text, a.registerdate) AS regyear,
	 		 a.appltypecode, s.statusname, appl.applcatname, s.statuscode, l.listpurpose, a.establishcode ,
	 		 es.establishname,count(a.applicationtosrno - a.applicationsrno) as count
	 		 FROM applicationtype apt LEFT JOIN application a ON a.appltypecode = apt.appltypecode
	 		 LEFT JOIN listpurpose l ON l.purposecode = a.purposelast
	 		 LEFT JOIN applicant app USING (applicationid) LEFT JOIN status s ON a.statusid = s.statuscode
	 		 LEFT JOIN applcategory appl ON a.applcategory=appl.applcatcode
			 LEFT JOIN establishment es ON a.establishcode=es.establishcode where a.establishcode='$establishcode'
			 and (Left(a.applicationid,2)='$type1'  OR '$type1'='-1') and (s.statuscode='1' or s.statuscode is null)
	 		 GROUP BY a.applicationid, apt.appltypedesc, a.appltypecode, (date_part('year'::text, a.registerdate)),
	 		 s.statuscode, s.statusname, appl.applcatname, l.listpurpose,
	 		 a.establishcode,es.establishname ORDER BY a.appltypecode, (date_part('year'::text, a.registerdate))");

$subtitle= "";

switch ($type1) {
 case 'OA':
	 $subtitle="List of Pending Original Applications   ";
	 break;
 case 'CA':
	 $subtitle="List of Pending Contempt Applications   ";
	 break;
case 'RA':
	 $subtitle="List of Pending Review Applications   ";
	 break;
 case 'MA':
	 $subtitle="List of Pending Miscellaneous Applications  ";
	 break;
 default:
	 $subtitle="List of all Pending Applications  ";
}
return view('Reports.Application.pendingapplicationReport', ['result'=>$result],['establishmentname'=>$establishmentname,'subtitle' =>$subtitle]);

}

}
?>
