<?php

namespace App\Http\Controllers\Reports\Judgement;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class JudgementReportController extends Controller
{

     public static function judgmentuploaded()

     {
      // $data['applicationType'] = DB::select("SELECT * FROM applicationtype order by appltypecode");
       return view('Reports.Judgement.judgementuploaded');
     }

     public static function disposedapplication()

     {

       return view('Reports.Judgement.disposedapplication');
     }

     public static function legacydisposal(){

       return view('Reports.Judgement.legacydisposal');
     }


     public static function  reservedfororder()
     {

       return view('Reports.Judgement.reservedfororder');
     }


public function disposedapplication1(Request $request){
      $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
      $todate=date('Y-m-d',strtotime($request->get('todate')));
      $establishcode=Session::get('EstablishCode');
      $establishmentname=Session::get('establishfullname');

      $request->validate([
                'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
      ]);


      $result=DB::select("SELECT ap.appltypedesc, count( a.applicationid) as applicationcount, sum(a.applicantcount) as applicantcount,
 sum(a.applicationtosrno-a.applicationsrno+1) +
 coalesce(sum(groupnocount),0) as group_Applicant_count
   from applicationdisposed a left join (select g.applicationid, sum(g.applicationtosrno-g.applicationsrno+1) as groupnocount
   from groupno g group by g.applicationid ) as groupno using (applicationid)
  left join applicationtype ap on (ap.appltypeshort = substring(a.applicationid,1,2))
  where a.establishcode = '$establishcode' and a.disposeddate >= '$fromdate' and a.disposeddate <= '$todate'
                        group by ap.appltypedesc");
             // print_r($result);
             // die();

     return view('Reports.Judgement.disposedapplication1',['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

    }



  public static function judgmentnotuploaded()
  {

    $establishcode=Session::get('EstablishCode');
    $establishmentname=Session::get('establishfullname');



    $result=DB::select("SELECT applicationdate, actsectioncode, ad.applicationid, applicationyear,
    appltypecode, applicationsrno, applicationtosrno, applcategory, appl.applcatname,subject,
    registerdate, disposeddate, statusid, ad.benchcode, benchtypename,
    purposecode, ordertypecode, ad.establishcode, ad.enteredfrom
      FROM public.applicationdisposed as ad left join judgement j
      on ad.applicationid=j.applicationid
      INNER JOIN applcategory appl
           On ad.applcategory=appl.applcatcode
          WHERE  j.applicationid IS NULL and ad.establishcode='$establishcode'
         ORDER BY disposeddate");





    return view('Reports.Judgement.judgmentnotuploadedReport', ['result'=>$result],['establishmentname'=>$establishmentname]);
  }




  public function judgmentuploadedfunction(Request $request)
  {
    $establishcode=Session::get('EstablishCode');
    $establishmentname=Session::get('establishfullname');

    $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
     $todate=date('Y-m-d',strtotime($request->get('todate')));


        $request->validate([
              'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
              'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
      ]);

      $type1 = $request->get('applTypeName');



$condition = "";

if( $type1 <> "-1")
{
   $condition =  " AND j.verified_by is $type1 ";
}


    $result=DB::select( "SELECT applicationdate, actsectioncode, ad.applicationid, applicationyear,
appltypecode, applicationsrno, applicationtosrno, applcategory, appl.applcatname,subject,
registerdate, disposeddate, statusid, ad.benchcode, benchtypename,
purposecode, ordertypecode, ad.establishcode, ad.enteredfrom ,j.verifieddate,j.createdon,j.verified_by
  FROM public.applicationdisposed as ad left join judgement j
  on ad.applicationid=j.applicationid
  INNER JOIN applcategory appl
       On ad.applcategory=appl.applcatcode
      WHERE date(j.createdon)>='$fromdate' AND date(j.createdon)<='$todate'".$condition." AND ad.establishcode='$establishcode'
     ORDER BY createdon");

      return view('Reports.Judgement.judgementuploadedReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

    }

    public function disposedapplicationfunction(Request $request)
    {
      $establishcode=Session::get('EstablishCode');
      $establishmentname=Session::get('establishfullname');

      $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
       $todate=date('Y-m-d',strtotime($request->get('todate')));


          $request->validate([
                'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
        ]);

      //  $type1 = $request->get('applTypeName');



  /*$condition = "";

  if( $type1 <> "-1")
  {
     $condition =  " AND (Left(R.applicationid,2)='$type1' ) ";
  }
  */

    /*  $result=DB::select(" SELECT b.judgeshortname,ad.applicationid,ad.registerdate,ad.disposeddate ,ad.subject ,
          appl.applcatname,ad.applicantcount,ad.respondentcount,ot.ordertypedesc
           from public.applicationdisposed
           as ad INNER JOIN bench as b ON ad.benchcode=b.benchcode
           LEFT JOIN applcategory as appl ON ad.applcategory=appl.applcatcode
           LEFT JOIN ordertype as ot on ad.ordertypecode=ot.ordertypecode
            where ad.disposeddate>='$fromdate' and ad.disposeddate<='$todate'
          AND ad.establishcode='$establishcode' order by ad.disposeddate");

*/

/*  $result=DB::SELECT("SELECT  distinct on(ad.applicationid) ad.benchtypename,ad.benchcode,dh.benchname as judgeshortname1,ad.applicationid
,ap.total_additionalno,
     STRING_AGG(DISTINCT c1.conapplid::TEXT,',' ) as conapplid,ad.authorby,
        ad.registerdate,ad.disposeddate ,ad.subject ,
     appl.applcatname,ad.applicantcount,ad.respondentcount,ot.ordertypedesc,bt.judgeshortname as judgeby,dh.courthallno,to_date(dh.hearingdate,'DD/MM/YYYY') as hearingdate

           from public.applicationdisposed
           as ad LEFT OUTER  JOIN bench as b ON ad.benchcode=b.benchcode
           LEFT   JOIN ordertype as ot ON ad.ordertypecode=ot.ordertypecode
           Left  Join  applcategory as appl ON ad.applcategory=appl.applcatcode
           Left  Join  connecetdappldtls c1 on ad.applicationid=c1.applicationid
           Left  Join  additionalno ap on ad.applicationid=ap.applicationid
           LEFT   JOIN bench as bt on CAST(ad.authorby as integer)=bt.benchcode
           LEFT  Join  hearingsummary1  as dh on ad.applicationid=dh.applicationid
           where ad.disposeddate>='$fromdate' and ad.disposeddate<='$todate'
           AND ad.establishcode='$establishcode' and (dh.casestatus='2' or dh.casestatus='1' or dh.casestatus is null or  dh.authorby is not null)
         group by ad.benchtypename,ad.benchcode,judgeshortname1,ad.applicationid,
         ap.total_additionalno,ad.authorby,ad.registerdate,ad.disposeddate ,ad.subject ,
     appl.applcatname,ad.applicantcount,ad.respondentcount,ot.ordertypedesc,judgeby,dh.courthallno,hearingdate
order by ad.applicationid,ad.benchtypename,judgeshortname1,ad.disposeddate ASC");  */

 $result=DB::SELECT("SELECT  distinct on(ad.applicationid) ad.benchtypename,ad.benchcode,CASE
 WHEN  dh.benchname is null  THEN (SELECT judgeshortname from bench where benchcode=ad.benchcode)
 ELSE dh.benchname
END as judgeshortname1,ad.applicationid
,ap.total_additionalno,
     STRING_AGG(DISTINCT c1.conapplid::TEXT,',' ) as conapplid,ad.authorby,
        ad.registerdate,ad.disposeddate ,ad.subject ,
     appl.applcatname,ad.applicantcount,ad.respondentcount,dh.ordertypedesc,bt.judgeshortname as judgeby,dh.courthallno,to_date(dh.hearingdate,'DD/MM/YYYY') as hearingdate

           from public.applicationdisposed
           as ad LEFT OUTER  JOIN bench as b ON ad.benchcode=b.benchcode
           LEFT   JOIN ordertype as ot ON ad.ordertypecode=ot.ordertypecode
           Left  Join  applcategory as appl ON ad.applcategory=appl.applcatcode
           Left  Join  connecetdappldtls c1 on ad.applicationid=c1.applicationid
           Left  Join  additionalno ap on ad.applicationid=ap.applicationid
           LEFT   JOIN bench as bt on CAST(ad.authorby as integer)=bt.benchcode
           LEFT  Join  hearingsummary1  as dh on ad.applicationid=dh.applicationid
           where ad.disposeddate>='$fromdate' and ad.disposeddate<='$todate'
           AND ad.establishcode='$establishcode' and (dh.casestatus='2' or dh.casestatus='1' or dh.casestatus is null or  dh.authorby is not null)
         group by ad.benchtypename,ad.benchcode,judgeshortname1,ad.applicationid,
         ap.total_additionalno,ad.authorby,ad.registerdate,ad.disposeddate ,ad.subject ,
     appl.applcatname,ad.applicantcount,ad.respondentcount,dh.ordertypedesc,judgeby,dh.courthallno,hearingdate
order by ad.applicationid,hearingdate DESC");


      return view('Reports.Judgement.disposedapplicationReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

      }
   public function reservedfororderfunction(Request $request){
    $establishcode=Session::get('EstablishCode');
    $establishmentname=Session::get('establishfullname');

    $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
     $todate=date('Y-m-d',strtotime($request->get('todate')));


        $request->validate([
              'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
              'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
      ]);


$result=DB::SELECT("SELECT ad.applicationid,dh.courthallno,dh.benchtypename ,dh.benchcode,b.judgeshortname as
   judgeshortname1,ot.ordertypedesc,ad.purposelast,ad.lastlistdate,ad.total_additionalno,
   STRING_AGG(DISTINCT c1.conapplid::TEXT,',' ) as conapplid,
  ad.subject,ad.applcatname,ad.applicantcount,ad.respondentcount,ad.remarks,dh.hearingdate
from applicationsummary1 as ad
left Join  dailyhearing dh on ad.applicationid=dh.applicationid
left join bench b on dh.benchcode=b.benchcode
left join ordertype ot on dh.ordertypecode=ot.ordertypecode
left join connecetdappldtls c1 on ad.applicationid=c1.applicationid
where dh.hearingdate>='$fromdate' and dh.hearingdate<='$todate'
and ad.establishcode='$establishcode' and (dh.ordertypecode='20' or dh.ordertypecode='33') and ad.statusid='1'
group by ad.applicationid,dh.courthallno,dh.benchtypename ,dh.benchcode,judgeshortname1,ot.ordertypedesc,
ad.purposelast,ad.lastlistdate,ad.total_additionalno,ad.subject,ad.applcatname,ad.applicantcount,ad.respondentcount,ad.remarks,
dh.hearingdate
order by ad.lastlistdate DESC");

return view('Reports.Judgement.reservedfororderReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

}

      public function legacydisposalfunction(Request $request)
      {
        $establishcode=Session::get('EstablishCode');
        $establishmentname=Session::get('establishfullname');

        $fromdate= date('Y-m-d',strtotime($request->get('fromdate')));
         $todate=date('Y-m-d',strtotime($request->get('todate')));


            $request->validate([
                  'fromdate' => 'required|date_format:d-m-Y|before_or_equal:'.$todate,
                  'todate' => 'required|date_format:d-m-Y|after_or_equal:'.$fromdate
          ]);

      

        $result=DB::select("SELECT
          applicationid,registerdate,applcatname,subject,applicantcount,respondentcount,
          judgeshortname,disposeddate from
          public.report_legacy_disposal where disposeddate>='$fromdate' and disposeddate<='$todate'
          and establishcode='$establishcode' order by disposeddate");





        return view('Reports.Judgement.legacydisposalReport', ['result'=>$result],['fromdate'=>$fromdate,'todate'=>$todate,'establishmentname'=>$establishmentname]);

        }

}
