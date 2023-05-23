<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Mail;
use Session;

class DailyReportViewController extends Controller
{

  public static function dailystatusreport(Request $request) {
      return view('Reports.Judgement.DailyReportView');
  }

  public function findJudgeWithBenchCode($hearingdate) {

      $establishcode = Session::get('EstablishCode');
      $fromdate = date('Y-m-d', strtotime($hearingdate));


      $applTypeName = DB::select("SELECT DISTINCT dh.benchcode,b.judgeshortname,dh.listno,dh.courthallno,ch.courthalldesc
                                  from  dailyhearing as dh INNER JOIN bench as b ON dh.benchcode=b.benchcode
                                  INNER JOIN courthall ch on dh.courthallno=ch.courthallno
                                  where dh.hearingdate='$fromdate' and dh.establishcode='$establishcode'  order by benchcode");


      return response()->json($applTypeName);
   }


   public function buttonsubmit(Request $request){
       $fromdate = date('Y-m-d', strtotime($request->hearingdate));//server
    //  $fromdate = date('m-d-Y', strtotime($request->hearingdate));//server
    //$hearingdate = date('m-d-Y',strtotime($request->hearingdate));//server
      $hearingdate=$request->hearingdate;
      $benchname=$request->benchname;
      $benchorg = explode(":",$benchname)[0];
      // $path ="/var/www/dailystatusreport";
      $path ="C:\\xampp\\htdocs\\vasanta\\dailystatusreport";
      $establishcode=Session::get('EstablishCode');
      $data['ename']=Session::get('establishfullname');
      $data['hearingdate'] = $request->hearingdate;

      $data['jug_wise_bench']=DB::select("select distinct(judgecode),judgename from dailyhearing a
                                          inner join public.benchjudgeview as b on a.benchcode=b.benchcode
                                          where b.display='Y' and a.benchcode = '$benchorg' and a.hearingdate='$hearingdate' order by judgecode");

    if(!empty($data['jug_wise_bench'])){
        $data['jug_name'] =  $data['jug_wise_bench'][0]->judgename;
        $name=str_replace("'","", $data['jug_wise_bench'][0]->judgename);

          $data['hearingdetails']=DB::select("select count(*) as bench_jud_name,a.benchcode,judgename,judgecode,a.benchtypename,judgeshortname
                                             from dailyhearing a inner join public.benchjudgeview as b on a.benchcode=b.benchcode
                                             where a.hearingdate='$hearingdate' and a.benchcode = '$benchorg'
                                             group by a.benchcode,judgename,judgecode,a.benchtypename,judgeshortname
                                             order by benchcode ");

          if(!empty($data['hearingdetails'])){ //if bench  not avaialble for today any judge
              $bench=null;
              foreach($data['hearingdetails'] as $hearingdetails){
                  $bench=$bench. "'".$hearingdetails->benchcode."',";
             }

             $bench=substr($bench, 0,-1);

             $data['case_posted'] =DB::select("select count(*) as caseposted,a.benchcode from dailyhearing a
                                               where a.hearingdate='$hearingdate' and a.benchcode in  (".$bench.") and a.causelistsrno is not  null
                                               group by a.benchcode order by a.benchcode ");

              //  echo "<pre>";
               //print_r( $data['case_posted'][0]->caseposted);
              // die();

             $data['case_posted_connected'] =DB::select("select count(*) as  con_caseposted,a.benchcode from dailyhearing a
                                                         where a.hearingdate='$hearingdate' and a.benchcode in (".$bench.")
                                                        and  a.causelistsrno is null group by a.benchcode order by a.benchcode ");

             $data['case_disposed']=DB::select("select count(*) as totcasedisposed fROM dailyhearing as a where  a.hearingdate='$hearingdate' and
                                                a.benchcode in (".$bench.") and casestatus=2
                                                group by a.benchcode order by a.benchcode");

             $data['case_des']=DB::select("select benchcode,string_agg(case_string , '; ') as order_string1 from (
                                          select concat(c.ordertypedesc,': ',count(*)  ) as case_string,a.benchcode from dailyhearing a
                                          inner join public.ordertype c on a.ordertypecode=c.ordertypecode
                                          where  a.hearingdate='$hearingdate' and a.benchcode in (".$bench.") group by a.benchcode,c.ordertypedesc
                                          order by a.benchcode) a group by benchcode order by benchcode");

             $data['IAcount']=DB::select("select count(*) as iacount from dailyhearing a inner join public.iadocument b
                                           on a.benchcode=b.benchcode and a.applicationid=b.applicationid
                                           where a.benchcode in (".$bench.") and a.hearingdate='$hearingdate'
                                           group by a.benchcode order by a.benchcode ");

             $data['status']=DB:: select("select count(*) as status_not_updated ,a.benchcode,a.benchcode,a.business from dailyhearing a
                                                         where a.hearingdate='$hearingdate' and a.benchcode in (".$bench.")
                                                         and business = 'N' group by a.benchcode,a.business order by a.benchcode");

              $data['jud_uploaded'] =DB::select("select row_number() over ( PARTITION BY a.benchcode order by a.benchcode) as  srno,a.applicationid,
                                                 d.registerdate, concat(d.applicantname1,'/',d.respondentname) as pet_res,b.ordertypedesc , a.benchcode
                                                 fROM dailyhearing as a inner join ordertype b on a.ordertypecode=b.ordertypecode
                                                 inner join public.applicationsummary1 as d on a.applicationid=d.applicationid
                                                 where a.benchcode in(".$bench.") and   a.hearingdate='$hearingdate'
                                                 and a.casestatus=2 ");

                                                 // echo "<pre>";
                                                 // print_r($data['jud_uploaded']);
                                                 // die();

    } //end if jug bench not available


        $data['res_for_order']=DB::select("select distinct a.applicationid,b.benchcode,
                                            c.judgeshortname,a.registerdate,To_char(b.hearingdate,'DD/MM/YYYY') as last_her_date,
                                            extract(day from now()-b.hearingdate) as pending_d from  applicationsummary1 as a
                                           inner join dailyhearing as b on a.applicationid=b.applicationid
                                            inner join benchjudgeview as c
                                           on  c.benchcode=b.benchcode where a.statusid=1 and c.benchcode ='$benchorg'
                                           and  b.ordertypecode=20 and b.hearingdate<='$hearingdate'
                                            order by pending_d desc");



  //===========date is not applying in below query==================




    // $data['jud_not_uploaded_table']=DB::select("select DISTINCT b.judgeshortname, a.applicationid,to_char(a.disposeddate,'DD/MM/YYYY') as disposeddate,
    //                                           a.authorby,jd.judgedesigshort as jshortname,
    //                                         extract(day from now() -a.disposeddate)as pending,c.ordertypedesc fROM applicationdisposed as a
    //                                        left join judgement j on a.applicationid=j.applicationid inner join public.benchjudgeview as b
    //                                        on b.benchcode=a.benchcode inner join ordertype as c on a.ordertypecode=c.ordertypecode
    //                                         inner join judgedesignation as jd on jd.judgedesigcode::character=a.authorby
    //                                         WHERE  j.applicationid IS not  NULL and  b.benchcode = '$benchorg' order by pending desc");

    // $data['jud_not_uploaded_table']=DB::select("select DISTINCT b.judgeshortname, a.applicationid,to_char(a.disposeddate,'DD/MM/YYYY') as disposeddate,
    //                                                   concat(je.judgeshortname,'(', jd.judgedesigshort,')') as jshortname,
    //                                                   a.authorby, extract(day from now() -a.disposeddate)as pending,c.ordertypedesc fROM applicationdisposed as a
    //                                                   join judgement j on a.applicationid=j.applicationid
    //                                                   inner join public.benchjudgeview as b on b.benchcode=a.benchcode
    //                                                   inner join ordertype as c on a.ordertypecode=c.ordertypecode
    //                                                   join judge as je on je.judgecode=a.authorby
    //                                                   join judgedesignation as jd on jd.judgedesigcode = je.judgedesigcode
    //                                                   WHERE  j.applicationid IS not  NULL and  b.benchcode = '40' order by pending desc");


    $data['jud_not_uploaded_table']=DB::select("select DISTINCT b.judgeshortname, a.applicationid,to_char(a.disposeddate,'DD/MM/YYYY') as disposeddate,
                                            be.judgeshortname as jshortname,
                                            a.authorby, extract(day from now() -a.disposeddate)as pending,c.ordertypedesc fROM applicationdisposed as a
                                            join judgement j on a.applicationid=j.applicationid
                                            inner join public.benchjudgeview as b on b.benchcode=a.benchcode
                                            inner join ordertype as c on a.ordertypecode=c.ordertypecode
                                            left join bench as be on be.benchcode::character=a.authorby
                                            WHERE  j.applicationid IS not  NULL and  b.benchcode = '$benchorg' order by pending desc");


// echo "<pre>";
// print_r($data['jud_not_uploaded_table']);
// die();

}


  return view('Reports.Judgement.DailyReportView1',  $data);

}




}
