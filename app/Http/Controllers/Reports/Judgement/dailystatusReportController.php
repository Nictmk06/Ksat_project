<?php

namespace App\Http\Controllers\Reports\Judgement;

use Illuminate\Http\Request;

use Session;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Mail;


class dailystatusReportController extends Controller
{


  public static function dailystatusreport(Request $request)
  {
     $path ="/var/www/dailystatusreport";

    $establishcode=Session::get('EstablishCode');
 //  $data['ename']=Session::get('establishfullname');




     $data['jug_wise_bench']=DB::select("select distinct(judgecode),judgename,judgemail from dailyhearing a
                inner join public.benchjudgeview as b
                on a.benchcode=b.benchcode
                where b.display='Y'

                order by judgecode ");
    // dd($data['jug_wise_bench']);
     //=================not including hearing date all juge bench details
$ct=count($data['jug_wise_bench']);

     if(!empty($data['jug_wise_bench'])){  //if starts here if judge wise bench not there
//============ for loop all jug_wise_benches ======================
     foreach($data['jug_wise_bench'] as $jug){
$data['jug_name']=$jug->judgename;
$name=str_replace("'","",$jug->judgename);

    $data['today_date']=DB::select("
SELECT To_char( MAX (hearingdate),'DD/MM/YYYY') as todaydate
FROM dailyhearing
WHERE hearingdate NOT IN (SELECT Max (hearingdate) FROM dailyhearing)");

$date=DB::SELECT("SELECT MAX (hearingdate) as hearingdate
FROM dailyhearing
WHERE hearingdate NOT IN (SELECT Max (hearingdate) FROM dailyhearing)")[0]->hearingdate;
//dd($date);

    $data['hearingdetails']=DB::select("select count(*) as bench_jud_name,a.benchcode,judgename,judgecode,a.benchtypename,judgeshortname,establishcode   from dailyhearing a
                inner join public.benchjudgeview as b
                on a.benchcode=b.benchcode
                where a.hearingdate='$date' and
               b.judgecode ='".$jug->judgecode."'
                group by a.benchcode,judgename,judgecode,a.benchtypename,judgeshortname,establishcode
                order by benchcode  ");
    //dd($data['hearingdetails']);


   // dd($data['hearingdetails']);
if(empty($data['hearingdetails']))
{
$data['ename']='Karnataka State Administrative Tribunal-Bengaluru' ;
}
if(!empty($data['hearingdetails'])){ //if bench  not avaialble for today any judge
$bench=null;
$establishcode=$data['hearingdetails'][0]->establishcode;
$data['ename']=DB::SELECT("SELECT establishfullname from establishment where establishcode='$establishcode'")[0]->establishfullname;
  foreach($data['hearingdetails'] as $hearingdetails){
$bench=$bench. "'".$hearingdetails->benchcode."',";
}
$bench=substr($bench, 0,-1);
//dd($bench);



        $data['case_posted'] =DB::select("select count(*) as caseposted,a.benchcode  from   dailyhearing a

              where a.hearingdate='$date' and
              a.benchcode in  (".$bench.") and a.causelistsrno is not  null
 

              group by a.benchcode
              order by a.benchcode ");

        $data['case_posted_connected'] =DB::select("select count(*) as  con_caseposted,a.benchcode  from   dailyhearing a

              where a.hearingdate='$date' and
              a.benchcode in (".$bench.")

              and  a.causelistsrno is null
              group by a.benchcode
              order by a.benchcode ");


//dd($data['case_posted_connected']);

        $data['case_disposed']=DB::select(" select count(*) as totcasedisposed fROM dailyhearing as a

                      where  a.hearingdate='$date' and
                      a.benchcode in (".$bench.")

                     and casestatus=2
                     group by a.benchcode
                     order by a.benchcode");


          $data['case_des']=DB::select("select benchcode,string_agg(case_string , '; ') as order_string1 from (
                select concat(CASE 
WHEN c.ordertypedesc is null   THEN 'Order not Updated'
ELSE c.ordertypedesc
END,': ',count(*)  ) as case_string,a.benchcode from dailyhearing a
                  left join public.ordertype c
                  on a.ordertypecode=c.ordertypecode
                  where   a.hearingdate='$date' and a.benchcode in (".$bench.")
                 group by a.benchcode,c.ordertypedesc
                   order by a.benchcode) a
                   group by benchcode
                   order by benchcode");

      $data['IAcount']=DB::select(" select count(*) as iacount from dailyhearing a
                inner join public.iadocument b
                on a.benchcode=b.benchcode and a.applicationid=b.applicationid
               where a.benchcode in (".$bench.") and a.hearingdate='$date'
               group by a.benchcode
               order by a.benchcode ");

    $data['jud_uploaded'] =DB::select("select row_number() over ( PARTITION BY a.benchcode order by a.benchcode) as  srno,a.applicationid,d.registerdate,
            concat(d.applicantname1,'/',d.respondentname) as pet_res,b.ordertypedesc , a.benchcode fROM dailyhearing as a
                     inner join ordertype b
                     on a.ordertypecode=b.ordertypecode

                 inner join public.applicationsummary1 as d
                 on a.applicationid=d.applicationid
                     where a.benchcode in(".$bench.") and   a.hearingdate='$date'
                 and a.casestatus=2 ");
 } //end if jug bench not available

      $data['res_for_order']=DB::select("select b.benchcode,a.applicationid,
      c.judgeshortname,a.registerdate,To_char(b.hearingdate,'DD/MM/YYYY') as last_her_date, 
      extract(day from now()-b.hearingdate) as pending_d from  applicationsummary1 as a
             inner join dailyhearing as b
             on a.applicationid=b.applicationid
             inner join benchjudgeview as c
             on  c.benchcode=b.benchcode
             where a.statusid=1 and c.judgecode='".$jug->judgecode."'
             and  b.ordertypecode=20 and b.hearingdate>='2021-07-01' and b.hearingdate<='2021-07-31'
             order by pending_d desc");

//===========date is not applying in below query==================
       $data['jud_not_uploaded_table']=DB::select("  select b.judgeshortname, a.applicationid,to_char(a.disposeddate,'DD/MM/YYYY') as disposeddate
           ,extract(day from now() -a.disposeddate)as pending,c.ordertypedesc fROM applicationdisposed as a
                        left join judgement j
                        on a.applicationid=j.applicationid
            inner join public.benchjudgeview as b
                      on b.benchcode=a.benchcode
                 inner join ordertype as c
                 on a.ordertypecode=c.ordertypecode
                 WHERE  j.applicationid IS NULL  and b.judgecode='".$jug->judgecode."' and a.disposeddate >='2021-07-01' and a.disposeddate<='2021-07-31'
         order by pending desc
              ");
   //$path=$this->path3;


        $pdf = PDF::loadView('Reports.Judgement.dailystatusReport',$data);
       //$pdf = PDF::loadView('Reports.Judgement.TestdailystatusReport',$data);

       $pdf->save($path.'/dailyreport_'.$jug->judgecode.'_'.$name.'.pdf');
       $daily_rpt=$path.'/dailyreport_'.$jug->judgecode.'_'.$name.'.pdf';

  //====================auto email send========================================
      $ename1= $request->session()->get('establishfullname');
       $jname=$jug->judgename;
       $j_email=$jug->judgemail;
   
       $t_date= date('d/m/Y', strtotime($date));
//dd($t_date);
    $msg='Dear '.$jug->judgename.' ,
     Todays dailystaus report enclosed here with.';

     Mail::send([], [], function($message) use($ename1,$daily_rpt,$j_email,$t_date) {

       //$msg_content= $message->setBody($msg,'text/html');

         $message->to($j_email, 'KSAT Admin')->subject
            ('KSAT Daily status report ') ->setBody('Sir, <br/>
       <p style="padding-left:20px">Daily status report generated on '.$t_date.' is  enclosed herewith for your kind information.</p>
       Computer section<br/>Karnataka State Administrative Tribunal<br/><br/>
         Note: This is an auto generated email. Please do not reply or send queries to this e-mail','text/html');
         $message->attach($daily_rpt);
          //$message->attach($fac_dsc_file);

         $message->from('ksat.notification@karnataka.gov.in',$ename1);
      });

   //=================auto email send ===========================================




}
//dd($data['hearingdetails']);
//================end of jug_wise bench loop

     /*$data['jug_']=DB::select("select count(*) as   no_of_case,a.benchcode,judgecode from dailyhearing a
                inner join public.benchjudgeview as b
                on a.benchcode=b.benchcode
                where hearingdate='2021-01-18'
                group by a.benchcode,judgecode
                order by judgecode,benchcode ");*/
    // dd($data['jug_wise_bench']);
       //return view('Reports.Judgement.dailystatusReport',$data);
        //return view('Reports.Judgement.TestdailystatusReport',$data);
      //return view('Reports.Judgement.dailystatusReportShow',$data);


$data['alert_msg']= $ct.' Email Sent Successfully!';
$data['status'] ="success";
  //}
//==============if condition end judge wise bench not there
}else{

  $data['alert_msg']= 'No hearing found Today';
 $data['status'] ="warning";
}

return view('Reports.Judgement.TestdailystatusReport',$data);

}


}
