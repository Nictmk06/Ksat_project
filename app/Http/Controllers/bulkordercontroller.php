<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CaseFollowUpModel as CaseFollowUpModel;
use App\IANature;
use App\DisposedApplicationModel;
use App\CaseManagementModel;
//use App\DisposedApplicationModel;
use App\JudgementModel;
use App\UserActivityModel;
//use App\case;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class bulkordercontroller extends Controller
{

     public function index()
     {
//
    }

    public function __construct()
    {
        $this->disposedApplication =  new DisposedApplicationModel();
        $this->IANature = new IANature();
        $this->UserActivityModel = new UserActivityModel();
        $this->case = new CaseManagementModel();
        $this->Judgement = new JudgementModel();
        //$this->disposedApplication = new DisposedApplicationModel();
        $this->UserActivityModel = new UserActivityModel();
        //when accessing by httpserver
        //$this->pathdownload ="http://10.10.28.84:9000/judgements";
   }

    public static function courtproceedingsentered()
    {
        return view('casefollowup.bulkoffice');
    }
    public static function cancelcourtdirection()
    {
        return view('casefollowup.cancelcourtdirection');
    }

    public function courtproceedingsenteredfunction(Request $request)
  {    $request->validate([
    'hearingdate' => 'required|date',
    'benchname' => 'required',
   ]);



      $establishcode = Session::get('EstablishCode');
      $username = $request->session()->get('username');
      $user_realname=DB::SELECT("SELECT userid from userdetails where userid='$username'")[0]->userid;
      $courthallno_userdetails=DB::SELECT("SELECT courthallno from userdetails where userid='$username'");
      $courhallno_userlevel=DB::SELECT("SELECT userlevel from userdetails where userid='$username'")[0]->userlevel;
      if($courthallno_userdetails==null)
      {
       $courthallno_userdetails='';
      }
      else{
      $courthallno_userdetails=$courthallno_userdetails[0]->courthallno;
      }
      $establishmentname = Session::get('establishfullname');
      $Benches=DB::Table('benchtype')->select('*')->get();
      $benchjudge=DB::Table('bench')->select('*')->where('establishcode','=',$establishcode)->where('display' ,'=','Y' )->orderBy('judgeshortname', 'ASC')->get();
     
 $purpose=DB::Table('listpurpose')->select('*')->orderby('listpurpose','asc')->get();
      $Status=DB::SELECT("SELECT * from status");
     $fromdate = date('Y-m-d', strtotime($request->get('hearingdate')));
     $benchtypename = $request->get('benchname');
     $var=explode(':',$benchtypename);
     $benchcode=$var[0];

     $judgeshortname=DB::SELECT("SELECT judgeshortname from bench where benchcode='$benchcode'")[0]->judgeshortname;
     $temp=explode('+',$judgeshortname);
     $temp_length=count($temp);

   if($temp_length=='1')
     {
    $judgeshortname2=[];
    $benchcode3=[];
     $judgeshortname2[]=$temp[0];
    $data['judgeshortname2']=$judgeshortname2;

    $benchcode3[]=DB::SELECT("SELECT benchcode from bench where judgeshortname='$temp[0]' and establishcode ='$establishcode'")[0]->benchcode;
    $data['benchcode3']=$benchcode3;

     }
     else
     {
      //  $judgeshortname=array();
        $judgeshortname2 = [];
        $benchcode3=[];

             for($i=0;$i<$temp_length;$i++)
        {
          $judgeshortname2[]=$temp[$i];

          $benchcode3[]= DB::table('bench')->select('benchcode')->where('judgeshortname',$temp[$i])->first();

          if($benchcode3[0]==null){
                return Redirect::back()->withErrors(['msg' => 'Please Create a Single Bench for '.$judgeshortname2[$i].' in '.$establishmentname.' if want to provide author name for '.$judgeshortname2[$i].' in the bench of '.$judgeshortname
               ]);
          }
          else{
          $benchcode4[]=$benchcode3[$i]->benchcode;
          }
        }

          $data['judgeshortname2']=$judgeshortname2;
          $data['benchcode3']=$benchcode4;
     }


     $listno=$var[1];
     if($listno == 'null')
     {
       $benchname = DB::select("SELECT ' Bench Type: ' || benchtypename || ' ,Judge Name: ' || judgeshortname as bench  from bench where benchcode='$benchcode' ");
       $order=DB::SELECT("SELECT * from ordertype order by ordertypedesc");
       $request->validate(['hearingdate' => 'required|date_format:d-m-Y']);
       $result = DB::select("SELECT dh.hearingcode,ud.username,dh.courthallno,dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
      dh.caseremarks,dh.nextdate,dh.publish,dh.ordertypecode,ot.ordertypedesc,dh.casestatus,ch.courthalldesc,b.judgeshortname,dh.hearingdate
      from  dailyhearing as dh LEFT JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
      INNER JOIN bench as b ON dh.benchcode=b.benchcode
      INNER JOIN courthall ch on dh.courthallno=ch.courthallno
     LEFT JOIN userdetails ud on dh.dictationby=ud.userid
      where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
      and dh.listno is null  order by  dh.causelistsrno,dh.hearingdate");
 $courthallno=$result[0]->courthallno;
 $courthalldesc=$result[0]->courthalldesc;
 if($courthallno_userdetails == $courthallno || $courhallno_userlevel =='5')
  {
    $true='true';
    $result1 = DB::select("SELECT dh.hearingcode,ud.username,dh.courthallno,dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
dh.caseremarks,dh.nextdate,dh.publish,dh.ordertypecode,ot.ordertypedesc,dh.casestatus,ch.courthalldesc,b.judgeshortname,dh.hearingdate
from  dailyhearing as dh LEFT JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall ch on dh.courthallno=ch.courthallno
LEFT JOIN userdetails ud on dh.dictationby=ud.userid
where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
and dh.listno is null  order by  dh.causelistsrno,dh.hearingdate");
  }
  else
  {
    $true='false';
    $result1 = DB::select("SELECT dh.hearingcode,ud.username,dh.courthallno,dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
dh.caseremarks,dh.nextdate,dh.publish,dh.ordertypecode,ot.ordertypedesc,dh.casestatus,ch.courthalldesc,b.judgeshortname,dh.hearingdate
from  dailyhearing as dh LEFT JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall ch on dh.courthallno=ch.courthallno
LEFT JOIN userdetails ud on dh.dictationby=ud.userid
where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
and dh.listno is null and dh.dictationby='$user_realname' order by  dh.causelistsrno,dh.hearingdate");
  }
   $users=DB::SELECT("SELECT userid,username from userdetails  where establishcode ='$establishcode' and  (userdesigcode ='9' or  userdesigcode='10' or userdesigcode='14') order by username");
    return view('casefollowup.bulkofficelist', ['result1' => $result1], ['temp_length'=>$temp_length,'data'=>$data,'fromdate' => $fromdate,'true'=>$true,'courthalldesc'=>$courthalldesc,'users'=>$users, 'benchname' => $benchname, 'establishmentname' => $establishmentname,'benchcode'=> $benchcode,'order'=>$order,'Status'=>$Status,'Benches'=>$Benches,'judgeshortname2'=>$judgeshortname2,'benchjudge'=>$benchjudge,'purpose'=>$purpose]);
}
else{
      $benchname = DB::select("SELECT ' Bench Type: ' || benchtypename || ' ,Judge Name: ' || judgeshortname as bench  from bench where benchcode='$benchcode' ");
      $order=DB::SELECT("SELECT * from ordertype order by ordertypedesc");
      $request->validate(['hearingdate' => 'required|date_format:d-m-Y']);
      $result = DB::select("SELECT dh.hearingcode,ud.username,dh.courthallno,dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
dh.caseremarks,dh.nextdate,dh.publish,dh.ordertypecode,ot.ordertypedesc,dh.casestatus,ch.courthalldesc,b.judgeshortname,dh.hearingdate
from  dailyhearing as dh LEFT JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
 INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall ch on dh.courthallno=ch.courthallno
LEFT JOIN userdetails ud on dh.dictationby=ud.userid
where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
and dh.listno='$listno'  order by  dh.causelistsrno,dh.hearingdate");
$courthallno=$result[0]->courthallno;
$courthalldesc=$result[0]->courthalldesc;

if($courthallno_userdetails == $courthallno ||  $courhallno_userlevel =='5')
{
 $true='true';
 $result1 = DB::select("SELECT dh.hearingcode,ud.username,dh.courthallno,dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
dh.caseremarks,dh.nextdate,dh.publish,dh.ordertypecode,ot.ordertypedesc,dh.casestatus,ch.courthalldesc,b.judgeshortname,dh.hearingdate
from  dailyhearing as dh LEFT JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall ch on dh.courthallno=ch.courthallno
LEFT JOIN userdetails ud on dh.dictationby=ud.userid
where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
and dh.listno='$listno'  order by  dh.causelistsrno,dh.hearingdate");
}
else
{
 $true='false';
 $result1 = DB::select("SELECT dh.hearingcode,ud.username,dh.courthallno,dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
dh.caseremarks,dh.nextdate,dh.publish,dh.ordertypecode,ot.ordertypedesc,dh.casestatus,ch.courthalldesc,b.judgeshortname,dh.hearingdate
from  dailyhearing as dh LEFT JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall ch on dh.courthallno=ch.courthallno
LEFT JOIN userdetails ud on dh.dictationby=ud.userid
where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
and dh.listno='$listno' and dh.dictationby='$user_realname'  order by  dh.causelistsrno,dh.hearingdate");
}
$users=DB::SELECT("SELECT username,userid from userdetails  where establishcode ='$establishcode' and (userdesigcode ='9' or userdesigcode='10' or userdesigcode='14') order by username ");


 }
 return view('casefollowup.bulkofficelist', ['result1' => $result1], ['temp_length'=>$temp_length,'data'=>$data,'fromdate' => $fromdate,'courthalldesc'=>$courthalldesc,'true'=>$true,'users'=>$users, 'benchname' => $benchname, 'establishmentname' => $establishmentname,'benchcode'=> $benchcode,'order'=>$order,'Status'=>$Status,'Benches'=>$Benches,'judgeshortname2'=>$judgeshortname2,'benchjudge'=>$benchjudge,'purpose'=>$purpose]);
}

    public function getapphearing(Request $request)
    {
      $estcode  = $request->session()->get('EstablishCode');
      $hdate=$request->get('hearingDate1');
      $appId=$request->get('applicationID');

      $benchcode=$request->get('benchcode');

      $json=DB::select("SELECT a.applicationid,a.hearingcode,a.benchcode,a.nextdate,a.authorby,a.nextbenchtypename,a.nextbenchcode,a.nextpurposecode, a.courthallno,a.listno,a.establishcode,b.ordertypedesc,*
        ,a.casestatus,
       CASE when a.casestatus =1 then 'Pending' else 'Disposed'
       END as status
        from dailyhearing  as a
       left join ordertype as b
        on a.ordertypecode=b.ordertypecode
          where a.applicationid='".$appId."' and a.hearingdate='".$hdate."' and a.benchcode='".$benchcode."'
          order by a.applicationid ");
      
       $iapending= DB::select("select * from iadocument where (applicationid='$appId') order by iasrno");
       $m_status     = DB::select("select * from status");
       $m_ordertype  = DB::select("select * from ordertype order by ordertypedesc");

       $data = array();
       $data['json']=$json;
       $data['iapending']=$iapending;
       $data['m_status']     = $m_status;
       $data['m_ordertype']=$m_ordertype ;
       echo json_encode($data);
     
    }
    public function   courtproceedingsenteredrollbackfunction(Request $request)
    {
          $establishcode = Session::get('EstablishCode');
          $establishmentname = Session::get('establishfullname');
          $applicationid=$request->get('applicationid');
          $result = DB::select("SELECT hearingcode,hearingdate,publish,courthallno,courtdirection ,applicationid from dailyhearing
    where applicationid='$applicationid' and publish ='Y' and establishcode='$establishcode'");
          return view('casefollowup.cancelcourtdirectionlist', ['result' => $result], ['applicationid' => $applicationid,'establishmentname' => $establishmentname]);

    }

    public function findJudgeWithBenchCode($hearingdate)
    {
        $establishcode = Session::get('EstablishCode');
        $fromdate = date('Y-m-d', strtotime($hearingdate));
        $applTypeName = DB::select("SELECT DISTINCT dh.benchcode,b.judgeshortname,dh.listno,dh.courthallno,ch.courthalldesc
from  dailyhearing as dh INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall ch on dh.courthallno=ch.courthallno
where dh.hearingdate='$fromdate' and dh.establishcode='$establishcode'  order by benchcode");
        return response()->json($applTypeName);
    }
public function getHearingDetailsSelectedCheckbox(Request $request)
{
  $establishcode = Session::get('EstablishCode');
  $establishmentname = Session::get('establishfullname');

  $fromdate = date('Y-m-d', strtotime($request->get('fromdate')));
  $benchcode = $request->get('benchcode');
  $benchname = DB::select("SELECT ' Bench Type: ' || benchtypename || ' ,Judge Name: ' || judgeshortname as bench  from bench where benchcode='$benchcode' ");

  $request->validate(['hearingdate' => 'required|date_format:d-m-Y']);
  $result = DB::select("SELECT dh.hearingcode,dh.listno,dh.causelistsrno,dh.applicationid,dh.courtdirection,
  dh.caseremarks,dh.nextdate,dh.publish,dh.ordertypecode,ot.ordertypedesc,dh.casestatus,ch.courthalldesc,b.judgeshortname,dh.hearingdate
  from  dailyhearing as dh INNER JOIN ordertype as ot ON dh.ordertypecode=ot.ordertypecode
  INNER JOIN bench as b ON dh.benchcode=b.benchcode
  INNER JOIN courthall ch on dh.courthallno=ch.courthallno
  where dh.hearingdate='$fromdate'and dh.benchcode='$benchcode' and  dh.establishcode='$establishcode'
  order by  dh.causelistsrno,dh.hearingdate");

  echo json_encode($data['result']);
}

public function cancelcourdirectionrollback(Request $request)
{
  $ids=$request->get('ids');
  //  dd($data['hearingcode']);

  //print_r($ids);
  //$answer = $request->get('answer');


        if($ids==null)
        {
          return response()->json([
              'status' => "error",
              'message' => "Please check checkbox to publish court directions"
            ]);
        }

        //$ids[]=$data['hearingcode'];
      //  dd($ids);
      //  dd($ids);
        //$applicationid = $request->get('applicationid');
//count(array($ids))
//dd($dd);

           try{
               for($i=0;$i<count($ids);$i++)
              {
            // dd($ids);
             //dd($id);
           // dd($id);
              DB::SELECT("UPDATE dailyhearing set publish='N' where hearingcode='$ids[$i]' ");
          //    DB::table('dailyhearing')->insert($data);
              DB::commit();
             }
            return response()->json([
                'status' => "sucess",
                'message' => "Publish Flag Updated Successfully"
              ]);

          }
          catch (\Throwable $e) {
              DB::rollback();
              throw $e;
              return response()->json([
                  'status' => "error",
                  'message' => "Enter remarks for selected values"
                ]);
          } catch (\Throwable $e) {
                 DB::rollback();
                 throw $e;
                 return response()->json([
                     'status' => "error",
                     'message' => "Enter remarks for selected values"
                   ]);
             }



}
public function saveProceeding(Request $request){


   $estcode  = $request->session()->get('EstablishCode');

   $hdate = $request->input('hdate');
   $appid = $request->input('appId');
   $hearingCode=$request->get('hearingcode');
  //dd($hearingCode);
   //dd($hearingCode);
   //$cno = $request->input('cno');
   //$bno = $request->input('bno');
  // $lno = $request->input('lno');
   $cdir = pg_escape_string($request->get('courtDirection'));
   $cremarks = pg_escape_string($request->get('remarksIfAny'));
   $offnote = pg_escape_string($request->get('officeNote'));
   $orderpass = $request->get('orderPassed');
   $cstatus = $request->get('applStatus');
$iaselected = $request->input('pendingIa');
//dd($cstatus);

   if($orderpass == null){
  return response()->json([
    'status' => "error",
    'message' => "Select Order Type"
  ]);
}
   if($cstatus == NULL){
     return response()->json([
       'status' => "error",
       'message' => "Select Status"
     ]);
   }

  //$benchJudge=$request->get('benchJudge');
  //$postedfor =$request->get('postedfor');
  $isnexthearing=$request->get('isnexthearing');

//dd($isnexthearing);
  //$nextHrDate=$request->get('nextHrDate');
  //$nextbenchJudge=$request->get('nextbenchJudge');
  //$benchCode=$request->get('benchCode');

  // dd($cstatus);

   if($request->get('isnexthearing')=='Y')
    {
      $nexthrdate = $request->get('nextHrDate');
      if(strtotime($nexthrdate)==false)
      {
        return response()->json([
          'status' => "error",
          'message' => "Enter Next Hearing Date"
        ]);

      }
       $hdate1=date('d-m-Y',strtotime($request->get('hdate')));

    if(strtotime($nexthrdate)<strtotime($hdate))
    {
      return response()->json([
        'status' => "error",
        'message' => "Next Hearing Date must be greater than ".$hdate1
      ]);
     }
      if($nexthrdate=='')
      {
        return response()->json([
          'status' => "error",
          'message' => "Enter Next Hearing Date"
        ]);
      }

      $nexthrdate=date('Y-m-d',strtotime($request->get('nextHrDate')));

      $nextbench = $request->get('nextbenchJudge');
      if($nextbench=='')
      {
        return response()->json([
          'status' => "error",
          'message' => "Enter Judge"
        ]);

      }

      $nextpurpose = $request->get('nextPostfor');
      if($nextpurpose=='')
       {
        return response()->json([
          'status' => "error",
          'message' => "Enter List Purpose"
        ]);

       }

      $nextbenchtype = $request->get('nextBench');
      if($nextbenchtype=='')
      {
       return response()->json([
         'status' => "error",
         'message' => "Enter Bench Type"
       ]);
      }
    }
    else
    {
      $nexthrdate = NULL;
      $nextbench = NULL;
      $nextpurpose =NULL;
      $nextbenchtype = NULL;


    }

try{
   DB::beginTransaction();
     
     
        if ( $iaselected != "0:0")
        {
           list($applicationid, $iano)   = explode(':', $iaselected);
            $chpupia['benchcode']         = $request->input('benchcode');
            $chpupia['courthallno']       = $request->input('courthallno');
            $chpupia['iaprayer']          = $request->input('iaPrayer');
            $chpupia['remark']            = $request->input('iaRemarks');
            $chpupia['ordertypecode']     = $request->input('iaOrderPassed');
            $chpupia['hearingdate']     =  date('Y-m-d',strtotime($hdate));
            if($request->input('iaStatus')== '2' ){
            $chpupia['disposeddate']     =  date('Y-m-d',strtotime($hdate));
            }
            $chpupia['iastatus']          = $request->input('iaStatus');
            DB::table('iadocument')->where('applicationid', $applicationid)->where('iano', $iano)->update($chpupia);
        }

    if($request->get('applStatus')==2)
    {

    //  $var4=DB::SELECT("SELECT * from judgement where applicationid='$appid'");
    //  if(count($var4)>0 and $cstatus=='1')
    //    {
      //    return response()->json([
      //      'status' => "error",
      //      'message' => "Judgement is already uploaded for this application.So status can't be changed"
      //    ]);
      //  }
        $disposeddate = date('Y-m-d',strtotime($request->get('disposedDate')));
        if($disposeddate=='' || $disposeddate == '1970-01-01')
        {
         return response()->json([
           'status' => "error",
           'message' => "Enter Disposed Date"
         ]);
        }
        $authorby=$request->get('authorby');

        if($authorby=='')
        {
         return response()->json([
           'status' => "error",
           'message' => "Enter Author By"
         ]);
        }
    //  dd($cstatus);
      DB::update("UPDATE dailyhearing set courtdirection='$cdir',caseremarks='$cremarks',officenote='$offnote',ordertypecode='$orderpass',business='Y',nextdate=NULL,
      casestatus='$cstatus',authorby='$authorby',nextpurposecode=NULL,nextbenchcode=NULL,nextbenchtypename =NULL,disposeddate='$disposeddate' where applicationid='$appid' and hearingdate='$hdate' ");

    //$var1= DB::SELECT("SELECT * from application where applicationid='$appid'");
  //  if(count($var1)>0)
  //  {
      DB::update("UPDATE application set statusid='2' where applicationid='$appid'");
  //  }

/*$conid=DB::SELECT("SELECT * from connected1 where applicationid='$appid'");
//dd($conid);
if($conid!=null)
 {
   $num=count($conid);
   for($i=0;$i<$num;$i++){
    $connid=$conid[$i]->conapplid;
     DB::update("UPDATE dailyhearing set courtdirection='$cdir',caseremarks='$cremarks',officenote='$offnote',ordertypecode='$orderpass',business='Y',nextdate=NULL,
     casestatus='$cstatus',authorby='$authorby',nextpurposecode=NULL,nextbenchcode=NULL,nextbenchtypename =NULL,
     disposeddate='$disposeddate' where applicationid='$connid' and hearingdate='$hdate' ");

  }
} */
}
    else
    {
      $disposeddate = 'NULL';

    //  $var5=DB::SELECT("SELECT * from judgement where applicationid='$appid'");
      //if(count($var5)>0 and $cstatus=='2' )
      //  {
      //    return response()->json([
      //      'status' => "error",
      //      'message' => "Judgement is already uploaded for this application.So status can't be changed"
      //    ]);
      //  }
     if($request->get('isnexthearing')!='Y' ){
               // dd('1');
                DB::update("UPDATE dailyhearing set courtdirection='$cdir',caseremarks='$cremarks',
                officenote='$offnote',ordertypecode='$orderpass',business='Y',nextdate=NULL,
                nextpurposecode=NULL,casestatus='$cstatus',nextbenchcode=NULL,nextbenchtypename =NULL,disposeddate=NULL where applicationid='$appid' and hearingdate='$hdate' ");


                $conid=DB::SELECT("SELECT * from connected1 where applicationid='$appid'");
                //dd($conid);
                if($conid!=null)
                 {
                   $num=count($conid);
                   for($i=0;$i<$num;$i++){
                    $connid=$conid[$i]->conapplid;
                    DB::update("UPDATE dailyhearing set courtdirection='$cdir',caseremarks='$cremarks',
                    officenote='$offnote',ordertypecode='$orderpass',business='Y',nextdate=NULL,
                    nextpurposecode=NULL,casestatus='$cstatus',nextbenchcode=NULL,nextbenchtypename =NULL,disposeddate=NULL where applicationid='$connid' and hearingdate='$hdate' ");

                  }
                 }


         }
         //elseif($nexthrdate == NULL or $nextbench== NULL or $nextpurpose==NULL or $nextbenchtype ==NULL ){
          // return response()->json([
        //     'status' => "error",
        //     'message' => "Enter required values of Hearing Details"
        //   ]);
         //}
      else{
      DB::update("UPDATE dailyhearing set courtdirection='$cdir',caseremarks='$cremarks',
      officenote='$offnote',ordertypecode='$orderpass',business='Y',nextdate='$nexthrdate',
      nextpurposecode='$nextpurpose',casestatus='$cstatus',nextbenchcode='$nextbench',nextbenchtypename ='$nextbenchtype',disposeddate=$disposeddate where applicationid='$appid' and hearingdate='$hdate' ");

      $conid=DB::SELECT("SELECT * from connected1 where applicationid='$appid'");

      if($conid!=null)
       {
         $num=count($conid);
         for($i=0;$i<$num;$i++){
          $connid=$conid[$i]->conapplid;
          DB::update("UPDATE dailyhearing set courtdirection='$cdir',caseremarks='$cremarks',
          officenote='$offnote',ordertypecode='$orderpass',business='Y',nextdate='$nexthrdate',
          nextpurposecode='$nextpurpose',casestatus='$cstatus',nextbenchcode='$nextbench',nextbenchtypename ='$nextbenchtype',disposeddate=$disposeddate where applicationid='$connid' and hearingdate='$hdate' ");

        }
       }



      }
    //  $var2= DB::SELECT("SELECT * from application where applicationid='$appid'");
    //  if(count($var2)>0)
    //  {
        DB::update("UPDATE application set statusid='1' where applicationid='$appid'");
    //  }
    $conid=DB::SELECT("SELECT * from connected1 where applicationid='$appid'");
    if($conid!=null)
     {
       $num=count($conid);
       for($i=0;$i<$num;$i++){
        $connid=$conid[$i]->conapplid;
        DB::update("UPDATE application set statusid='1' where applicationid='$connid'");

      }
     }

      $var3=DB::SELECT("SELECT * from applicationdisposed where applicationid='$appid'");
      if(count($var3)>0)
      {
        DB::SELECT("DELETE from applicationdisposed where applicationid='$appid'");

        $conid=DB::SELECT("SELECT * from connected1 where applicationid='$appid'");

        if($conid!=null)
         {
           $num=count($conid);
           for($i=0;$i<$num;$i++){
            $connid=$conid[$i]->conapplid;
            DB::SELECT("DELETE from applicationdisposed where applicationid='$connid'");

          }
         }
      }


    }

    if($request->get('applStatus')==2)
{
          $appldetail  = DB::select("select d.*, p.listpurpose as postedfor from dailyhearing as d left join listpurpose as p on (d.purposecode=p.purposecode) where (hearingcode='$hearingCode') limit 1");

          $applTypeRefer = $this->case->getApplTypeRefer($appid,'');

          if( count($applTypeRefer)>0)
          {
            $applnStore['referapplid'] = $applTypeRefer[0]->referapplid;
          }
        $ApplicationDet =DB::SELECT("select  a.applicationid,a.applicationdate,a.registerdate,a.subject,
  				a.applcategory,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode,a.applicationyear,a.appltypecode,a.applicationsrno,a.applicationtosrno,a.serviceaddress,a.servicepincode,a.servicetaluk,a.servicedistrict,a.advocateregnno,a.actcode,a.actsectioncode,a.totalamount,a.applcategory,a.subject,a.interimprayer,a.isinterimrelief,a.advocatesingle,a.applicantcount,a.respondentcount,a.resadvsingle,a.rserviceaddress,a.rservicetaluk,a.rservicedistrict,a.rservicedistrict,a.againstorders,a.remarks,a.createdon
  				from application as a
  				left join iadocument as b on b.applicationid=a.applicationid
  				left join applicationtype as c on c.appltypecode=a.appltypecode
  				where a.applicationid='".$appid."'
  				group by a.applicationid,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode
  				order by b.iasrno desc limit 1");


        $applnStore['applicationdate']=$ApplicationDet[0]->applicationdate;
        $applnStore['actsectioncode']=$ApplicationDet[0]->actsectioncode;
        $applnStore['applicationyear']=$ApplicationDet[0]->applicationyear;
        $applnStore['appltypecode']=$ApplicationDet[0]->appltypecode;
        $applnStore['applicationsrno']=$ApplicationDet[0]->applicationsrno;
        $applnStore['applicationtosrno']=$ApplicationDet[0]->applicationtosrno;
        $applnStore['registerdate']=$ApplicationDet[0]->registerdate;
        $applnStore['applcategory']=$ApplicationDet[0]->applcategory;
        $applnStore['subject']=$ApplicationDet[0]->subject;
        $applnStore['applicantcount']=$ApplicationDet[0]->applicantcount;
        $applnStore['respondentcount']=$ApplicationDet[0]->respondentcount;
        $applnStore['authorby']=$authorby;

        $applnStore['disposeddate'] = $disposeddate;
        $applnStore['applicationid']= $appid;
        $applnStore['statusid']=$request->input('applStatus');
        $applnStore['benchcode']=$appldetail[0]->benchcode;
        $applnStore['purposecode']=$appldetail[0]->purposecode;
        $applnStore['benchtypename']=$appldetail[0]->benchtypename;
        $applnStore['ordertypecode']=$request->input('orderPassed');
        $applnStore['createdby']= $request->session()->get('userName');
        $applnStore['createdon']= date('Y-m-d H:i:s') ;
        $applnStore['establishcode']= $estcode ;
        $var=DB::SELECT("SELECT * from applicationdisposed where applicationid='$appid'");
        if(count($var)>0)
          {
          DB::SELECT("DELETE from applicationdisposed where applicationid='$appid'");
          $value = $this->disposedApplication->addDisposedApplDetails($applnStore);
          }
          else
          {
          $value = $this->disposedApplication->addDisposedApplDetails($applnStore);
          }
      /*  if(count($var)>0)
        {
        DB::SELECT("DELETE from applicationdisposed where applicationid='$appid'");
        $value = $this->disposedApplication->addDisposedApplDetails($applnStore);

        $conid=DB::SELECT("SELECT * from connected1 where applicationid='$appid'");

        if($conid!=null)
         {
           $num=count($conid);
           for($i=0;$i<$num;$i++){
            $connid=$conid[$i]->conapplid;
            $ApplicationDet =DB::SELECT("select  a.applicationid,a.applicationdate,a.registerdate,a.subject,
      				a.applcategory,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode,a.applicationyear,a.appltypecode,a.applicationsrno,a.applicationtosrno,a.serviceaddress,a.servicepincode,a.servicetaluk,a.servicedistrict,a.advocateregnno,a.actcode,a.actsectioncode,a.totalamount,a.applcategory,a.subject,a.interimprayer,a.isinterimrelief,a.advocatesingle,a.applicantcount,a.respondentcount,a.resadvsingle,a.rserviceaddress,a.rservicetaluk,a.rservicedistrict,a.rservicedistrict,a.againstorders,a.remarks,a.createdon
      				from application as a
      				left join iadocument as b on b.applicationid=a.applicationid
      				left join applicationtype as c on c.appltypecode=a.appltypecode
      				where a.applicationid='".$connid."'
      				group by a.applicationid,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode
      				order by b.iasrno desc limit 1");

              $applnStore['applicationdate']=$ApplicationDet[0]->applicationdate;
              $applnStore['actsectioncode']=$ApplicationDet[0]->actsectioncode;
              $applnStore['applicationyear']=$ApplicationDet[0]->applicationyear;
              $applnStore['appltypecode']=$ApplicationDet[0]->appltypecode;
              $applnStore['applicationsrno']=$ApplicationDet[0]->applicationsrno;
              $applnStore['applicationtosrno']=$ApplicationDet[0]->applicationtosrno;
              $applnStore['registerdate']=$ApplicationDet[0]->registerdate;
              $applnStore['applcategory']=$ApplicationDet[0]->applcategory;
              $applnStore['subject']=$ApplicationDet[0]->subject;
              $applnStore['applicantcount']=$ApplicationDet[0]->applicantcount;
              $applnStore['respondentcount']=$ApplicationDet[0]->respondentcount;
              $applnStore['authorby']=$authorby;

              $applnStore['disposeddate'] = $disposeddate;
              $applnStore['applicationid']= $connid;
              $applnStore['statusid']=$request->input('applStatus');
              $applnStore['benchcode']=$appldetail[0]->benchcode;
              $applnStore['purposecode']=$appldetail[0]->purposecode;
              $applnStore['benchtypename']=$appldetail[0]->benchtypename;
              $applnStore['ordertypecode']=$request->input('ordertypecode');
              $applnStore['createdby']= $request->session()->get('userName');
              $applnStore['createdon']= date('Y-m-d H:i:s') ;
              $applnStore['establishcode']= $estcode ;

               DB::SELECT("DELETE from applicationdisposed where applicationid='$connid'");
              $value = $this->disposedApplication->addDisposedApplDetails($applnStore);


          }
         }
       }
       else
        {
        $value = $this->disposedApplication->addDisposedApplDetails($applnStore);

        $conid=DB::SELECT("SELECT * from connected1 where applicationid='$appid'");

        if($conid!=null)
         {
           $num=count($conid);
           for($i=0;$i<$num;$i++){
            $connid=$conid[$i]->conapplid;
            $ApplicationDet =DB::SELECT("select  a.applicationid,a.applicationdate,a.registerdate,a.subject,
      				a.applcategory,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode,a.applicationyear,a.appltypecode,a.applicationsrno,a.applicationtosrno,a.serviceaddress,a.servicepincode,a.servicetaluk,a.servicedistrict,a.advocateregnno,a.actcode,a.actsectioncode,a.totalamount,a.applcategory,a.subject,a.interimprayer,a.isinterimrelief,a.advocatesingle,a.applicantcount,a.respondentcount,a.resadvsingle,a.rserviceaddress,a.rservicetaluk,a.rservicedistrict,a.rservicedistrict,a.againstorders,a.remarks,a.createdon
      				from application as a
      				left join iadocument as b on b.applicationid=a.applicationid
      				left join applicationtype as c on c.appltypecode=a.appltypecode
      				where a.applicationid='".$connid."'
      				group by a.applicationid,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode
      				order by b.iasrno desc limit 1");

              $applnStore['applicationdate']=$ApplicationDet[0]->applicationdate;
              $applnStore['actsectioncode']=$ApplicationDet[0]->actsectioncode;
              $applnStore['applicationyear']=$ApplicationDet[0]->applicationyear;
              $applnStore['appltypecode']=$ApplicationDet[0]->appltypecode;
              $applnStore['applicationsrno']=$ApplicationDet[0]->applicationsrno;
              $applnStore['applicationtosrno']=$ApplicationDet[0]->applicationtosrno;
              $applnStore['registerdate']=$ApplicationDet[0]->registerdate;
              $applnStore['applcategory']=$ApplicationDet[0]->applcategory;
              $applnStore['subject']=$ApplicationDet[0]->subject;
              $applnStore['applicantcount']=$ApplicationDet[0]->applicantcount;
              $applnStore['respondentcount']=$ApplicationDet[0]->respondentcount;
              $applnStore['authorby']=$authorby;

              $applnStore['disposeddate'] = $disposeddate;
              $applnStore['applicationid']= $connid;
              $applnStore['statusid']=$request->input('applStatus');
              $applnStore['benchcode']=$appldetail[0]->benchcode;
              $applnStore['purposecode']=$appldetail[0]->purposecode;
              $applnStore['benchtypename']=$appldetail[0]->benchtypename;
              $applnStore['ordertypecode']=$request->input('ordertypecode');
              $applnStore['createdby']= $request->session()->get('userName');
              $applnStore['createdon']= date('Y-m-d H:i:s') ;
              $applnStore['establishcode']= $estcode ;

               DB::SELECT("DELETE from applicationdisposed where applicationid='$connid'");
              $value = $this->disposedApplication->addDisposedApplDetails($applnStore);
          }
         }
      }*/
}
        $useractivitydtls['applicationid_receiptno'] =$appid;
        $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
        $useractivitydtls['activity'] ='Update Daily Hearing' ;
        $useractivitydtls['userid'] = $request->session()->get('username');
        $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
        $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
        $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
         DB::commit();
        return response()->json([
          'status' => "success",
          'message' => "Updated!!"
        ]);
      }

    catch (\Exception $e) {
      dd($e->getMessage());

			   DB::rollback();
         return response()->json([
                  'status' => "fail",
                  'message' => "Something Went Wrongss !!"
                   ]);
			}
		    	catch (\Throwable $e) {
//            dd($e->getMessage());

                  DB::rollback();
                  return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong gg!!"
                    ]);
			}


}

      //dd( $offnote )  ;
    //  dd($cdir and $cremarks and $offnote and $orderpass == null);


/*DB::update("UPDATE dailyhearing set courtdirection='$cdir',caseremarks='$cremarks',
officenote='$offnote',ordertypecode='$orderpass',business='Y',nextdate='$nexthrdate',
nextpurposecode='$nextpurpose',nextbenchcode='$nextbench',nextbenchtypename ='$nextbenchtype',disposeddate=$disposeddate where applicationid='$appid' and hearingdate='$hdate' ");
*/
public function assignuser(Request $request)
{

   $user=$request->get('userid');
   if($user==null)
   {
     return response()->json([
         'status' => "error",
         'message' => "Please Select Username"
       ]);
   }
   $ids=$request->get('ids');
       if($ids==null)
         {
           return response()->json([
               'status' => "error",
               'message' => "Please check Checkbox to assign  user"
             ]);
         }
        try{
                for($i=0;$i<count($ids);$i++)
              {
             DB::SELECT("UPDATE dailyhearing set dictationby='$user' where hearingcode='$ids[$i]'");
            
             $applid=DB::SELECT("SELECT * from dailyhearing where  hearingcode='$ids[$i]'");
             $hearingdate=$applid[0]->hearingdate;
             $applid=$applid[0]->applicationid;
             $conapplid=DB::SELECT("SELECT * from connecetdappldtls where applicationid='$applid'");

             if($conapplid!=null)
             {

              for($j=0;$j<count($conapplid);$j++){
              $connapplid=$conapplid[$j]->conapplid;
              DB::SELECT("UPDATE dailyhearing set dictationby='$user' where applicationid='$connapplid' and
                hearingdate='$hearingdate'");
               }
             }

              DB::commit();
              }
             return response()->json([
                 'status' => "sucess",
                 'message' => "User  Assigned Successfully"
               ]);

           }
           catch (\Throwable $e) {
               DB::rollback();
               throw $e;
               return response()->json([
                   'status' => "error",
                   'message' => "Someting went Wrong"
                 ]);
           } catch (\Throwable $e) {
                  DB::rollback();
                  throw $e;
                  return response()->json([
                      'status' => "error",
                      'message' => "Someting went Wrong"
                    ]);
              }




}

    public function generateordersheet1(Request $request)
    {


        if ($request->input('printordersheet') != null)
        {
            $printdailyorder = $request->input('printordersheet');
            $var = explode(':', $printdailyorder);
            $applicationId = $var[0];
            $fromDate = '';
            $toDate = '';
            $judgeshortname = $var[3];

            $hearingdate = $var[4];
            $user = $request->session()->get('userName');
            $establishcode = $request->session()->get('EstablishCode');

                $bench=DB::SELECT("SELECT * from bench where judgeshortname='$judgeshortname'");
            $benchcode=$bench[0]->benchcode;
            $benchjudge = DB::select("select * from benchjudgeview where benchcode = ". $benchcode );

            $judgename ='';




            $benchjudge = DB::select("SELECT * from benchjudgeview where benchcode ='$benchcode' " );


            if ($benchjudge[0]->judgescount == 1)
            {
            $judgename = $judgeshortname;
            //  dd($judgename);
            }
            if ($benchjudge[0]->judgescount == 1)
              {
              $judgeshortname1 = $judgeshortname;
              $dd = $judgeshortname1;
             $judgename=  '<w:br />'.$dd.'<w:br />';
             $judgeshortname_withoutplus  =$judgeshortname;
              //  dd($judgename);
              }
              else {
             $judgeshortname1=str_replace("+","\n\n",$judgeshortname);
              // dd($judgeshortname);
             $judgeshortname_withoutplus=str_replace("+","/",$judgeshortname);
             $dd = preg_replace('/[\n]/','<w:br /><w:br />',htmlspecialchars($judgeshortname1,1));
            $judgename=  '<w:br />'.$dd.'<w:br />';
              }
              // dd($and);

        //    $judge = $benchjudge[0]->judgename;
        //    $judgedesig = $benchjudge[0]->judgedesigname;
            $user = $request->session()->get('userName');


            $courtdirection = DB::SELECT("SELECT courtdirection from dailyhearing where applicationid='$applicationId' and hearingdate='$hearingdate'");
            $courtdirections=htmlspecialchars($courtdirection[0]->courtdirection, ENT_QUOTES);
            $courtdirections=  preg_replace('/[\n]/','<w:br /><w:br />',htmlspecialchars($courtdirections,1));


           $hearingdates = date('d/m/Y', strtotime($hearingdate));
            $officenotes = "";
            $establishcode = $request->session()
                ->get('EstablishCode');
            $data['causelisttitle'] = DB::table('establishment')->select('causelist_title')
                ->where('establishcode', $establishcode)->get();
            $causelisttitle = $data['causelisttitle'][0]->causelist_title;
          

            $var=explode('/',$hearingdates);
            $year=$var[2];

            $applicationid=str_replace('/', '_', $applicationId);
            $hearingdate=str_replace('/', '-', $hearingdates);

             $name=$applicationid.'|'.$year.'|'.$hearingdate;
  
            $path = "ordersheet2";
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/' . $path . '.docx'));
            $my_template->setValue("applicationId", $applicationId);
            $my_template->setValue("officenotes", $officenotes);
            $my_template->setValue("courtdirection", htmlspecialchars_decode($courtdirections));
            $my_template->setValue("causelisttitle", $causelisttitle);
            $my_template->setValue("hearingdate", $hearingdates);
            $my_template->setValue("judgename", $judgename);
            $my_template->setValue("judgeshortname", $judgeshortname_withoutplus);


            $my_template->saveAs(storage_path("order.docx"));
            $file = storage_path("order.docx");
            $headers = array(
                'Content-Type: application/msword',
            );
            return response()->download($file,$name.".docx", $headers)->deleteFileAfterSend(true);
        }
         if ($request->input('printnewsheet') != null)
        {
            $printdailyorder = $request->input('printnewsheet');
            $var = explode(':', $printdailyorder);
            $applicationId = $var[0];
            $fromDate = '';
            $toDate = '';
            $judgeshortname = $var[3];

            $hearingdate = $var[4];
            $user = $request->session()->get('userName');
            $establishcode = $request->session()->get('EstablishCode');

                $bench=DB::SELECT("SELECT * from bench where judgeshortname='$judgeshortname'");
            $benchcode=$bench[0]->benchcode;
            $benchjudge = DB::select("select * from benchjudgeview where benchcode = ". $benchcode );

            $judgename ='';

            $benchjudge = DB::select("SELECT * from benchjudgeview where benchcode ='$benchcode' " );

            if ($benchjudge[0]->judgescount == 1)
            {
            $judgename = $judgeshortname;
            //  dd($judgename);
            }
            if ($benchjudge[0]->judgescount == 1)
              {
              $judgeshortname1 = $judgeshortname;
              $dd = $judgeshortname1;
             $judgename=  '<w:br />'.$dd.'<w:br />';
             $judgeshortname_withoutplus  =$judgeshortname;
              //  dd($judgename);
              }
              else {
             $judgeshortname1=str_replace("+","\n\n",$judgeshortname);
              // dd($judgeshortname);
             $judgeshortname_withoutplus=str_replace("+","/",$judgeshortname);
             $dd = preg_replace('/[\n]/','<w:br /><w:br />',htmlspecialchars($judgeshortname1,1));
            $judgename=  '<w:br />'.$dd.'<w:br />';
              }
              // dd($and);

        //    $judge = $benchjudge[0]->judgename;
        //    $judgedesig = $benchjudge[0]->judgedesigname;
            $user = $request->session()->get('userName');


            $courtdirection = DB::SELECT("SELECT courtdirection from dailyhearing where applicationid='$applicationId' and hearingdate='$hearingdate'");
            $courtdirections=htmlspecialchars($courtdirection[0]->courtdirection, ENT_QUOTES);
            $courtdirections=  preg_replace('/[\n]/','<w:br /><w:br />',htmlspecialchars($courtdirections,1));


           $hearingdates = date('d/m/Y', strtotime($hearingdate));
            $officenotes = "";
            $establishcode = $request->session()
                ->get('EstablishCode');
            $data['causelisttitle'] = DB::table('establishment')->select('causelist_title')
                ->where('establishcode', $establishcode)->get();
            $causelisttitle = $data['causelisttitle'][0]->causelist_title;
             
           $var=explode('/',$hearingdates);
            $year=$var[2];

            $applicationid=str_replace('/', '_', $applicationId);
            $hearingdate=str_replace('/', '-', $hearingdates);

             $name=$applicationid.'|'.$year.'|'.$hearingdate;

            $path = "ordersheet5";
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/' . $path . '.docx'));
            $my_template->setValue("applicationId", $applicationId);
            $my_template->setValue("officenotes", $officenotes);
            $my_template->setValue("courtdirection", htmlspecialchars_decode($courtdirections));
            $my_template->setValue("causelisttitle", $causelisttitle);
            $my_template->setValue("hearingdate", $hearingdates);
            $my_template->setValue("judgename", $judgename);
            $my_template->setValue("judgeshortname", $judgeshortname_withoutplus);


            $my_template->saveAs(storage_path("order.docx"));
            $file = storage_path("order.docx");
            $headers = array(
                'Content-Type: application/msword',
            );
            return response()->download($file, $name.".docx", $headers)->deleteFileAfterSend(true);
        }

         if ($request->input('printdailyorder') != null)

        {
            $printdailyorder = $request->input('printdailyorder');

            $var = explode(':', $printdailyorder);
           
            $applicationId = $var[0];
            $fromDate = '';
            $toDate = '';
            $judgeshortname = $var[3];
            $hearingdate=$var[4];
          
            $listno=$var[2];
            $variable=explode('/',$applicationId);
            $appltypeshort=$variable[0];
            
            $courthallno=DB::SELECT("SELECT courthallno from dailyhearing where applicationid='$applicationId'and hearingdate='$hearingdate'");
           $causelistsrno=DB::SELECT("SELECT causelistsrno from dailyhearing where applicationid='$applicationId'and hearingdate='$hearingdate'");
           $purposecode=DB::SELECT("SELECT purposecode from dailyhearing where applicationid='$applicationId'and hearingdate='$hearingdate'");
         $conapplid=DB::SELECT("SELECT string_agg(connapplno, ', ') as conapplid FROM connecetdappldtls WHERE applicationid='$applicationId'");

$iano=DB::SELECT("SELECT iano from iadocument where applicationid='$applicationId' and iastatus='1' and documenttypecode='1' order by iano ");

$ianaturedesc=DB::SELECT("SELECT  ia.ianaturedesc
     from iadocument  as id inner join 
ianature ia on id.ianaturecode=ia.ianaturecode where id.applicationid='$applicationId' and id.iastatus='1' and id.documenttypecode='1' order by id.iano");

        if($iano==null)
         {
          $ia_ianature="";
         }
         else
         { 
          $count_ia= count($iano);
         
          $ia[]="";
          for($i=0;$i<$count_ia;$i++)
          {
            $ia[]='['.$iano[$i]->iano.' | '.$ianaturedesc[$i]->ianaturedesc.']'."\n";
          }
           $ia_ianature= implode($ia);
  
  $dd_ia_ianature = preg_replace('/[\n]/', '<w:br />', htmlspecialchars($ia_ianature, 1));
  $ia_ianature =  $dd_ia_ianature;
         }
      
          if($conapplid[0]->conapplid==null)
         {
         $conapplid="";
         }
         else{
           $conapplid='C/w:  '.$conapplid[0]->conapplid;
            } 
        
          if($courthallno!=null)
         {
          $courthallno=$courthallno[0]->courthallno;
         }
         else
         {
           $courthallno="";
         }
         if($causelistsrno!=null)
         {
           $causelistsrno=$causelistsrno[0]->causelistsrno;
         }
         else
         {
            $causelistsrno="";
         }
          if($purposecode!=null)
         {
           $purposecode=$purposecode[0]->purposecode;
           $purposedesc=DB::SELECT("SELECT listpurpose from listpurpose where purposecode='$purposecode'")[0]->listpurpose;
         }
         else
         {
            $purposecode="";
         }

          
           $courtdirection = DB::SELECT("SELECT courtdirection from dailyhearing where applicationid='$applicationId' and hearingdate='$hearingdate'");
           $courtdirections=htmlspecialchars($courtdirection[0]->courtdirection, ENT_QUOTES);
           $courtdirections=  preg_replace('/[\n]/','<w:br /><w:br />',htmlspecialchars($courtdirections,1));

        //$courtdirections = htmlspecialchars($courtdirection, ENT_QUOTES);
        //$courtdirections=str_replace('&lt;/w:t&gt;&lt;w:br/&gt;&lt;w:t&gt;', '</w:t><w:br/><w:t>',$courtdirections);


    
          //  dd($hearingdate);
            $hearing_date=$hearingdate;

            $bench = DB::SELECT("SELECT * from bench where judgeshortname='$judgeshortname'");
            

            $benchcode = $bench[0]->benchcode;
            $benchjudge = DB::select("select * from benchjudgeview where benchcode = " . $benchcode);
            
            $judgename = "";
           
     if (!empty($benchjudge))
      { 
      if ($benchjudge[0]->judgescount == 1)
      {  
       $judgeshortname_3=$benchjudge[0]->judgeshortname; 
      }
        else {
      for ($i=0;$i<$benchjudge[0]->judgescount;$i++)
       { 
        $judgeshortname_1=$benchjudge[$i]->judgeshortname;

         $var1=explode('+',$judgeshortname_1);
        
         $judgeshortname_2[]=$var1[$i]."\n\n\n\n";
    
        }
         $judgeshortname_2= implode($judgeshortname_2);
           $dd1 = preg_replace('/[\n]/', '<w:br />', htmlspecialchars($judgeshortname_2, 1));
  $judgeshortname_3 =  $dd1;
      }
    }
   
  


            if ($benchjudge[0]->judgescount == 1)
            {
                $judgename = $benchjudge[0]->judgename . '  ,' . $benchjudge[0]->judgedesigname;
            }
            else
            {
                for ($i = 0;$i < $benchjudge[0]->judgescount;$i++)
                {
                    if($i+1==$benchjudge[0]->judgescount){
        $judgename = $judgename.$benchjudge[$i]->judgename ." ".$benchjudge[$i]->judgedesigname ."            ";
                    }
                    else{
     $judgename = $judgename.$benchjudge[$i]->judgename ." ".$benchjudge[$i]->judgedesigname . "\n"."            ";
                    }

                }
            }


            $dd = preg_replace('/[\n]/', '<w:br />', htmlspecialchars($judgename, 1));
            $judgename =  $dd;
            $judge = $benchjudge[0]->judgename;
            $judgedesig = $benchjudge[0]->judgedesigname;
            $user = $request->session()
                ->get('userName');
            $data['applicantDetails'] = CaseManagementModel::getTopApplicantDetails($applicationId)[0];
          // dd($data['applicantDetails']);
            $data['respondantDetails'] = CaseManagementModel::getTopRespondantDetails($applicationId)[0];

            if ($data['applicantDetails']->advocateregno == "")
            {
                $data['applicantadvocateDetails'] = "";
                $applicantadvocate = $data['applicantadvocateDetails'];
            }
            else
            {
                $data['applicantadvocateDetails'] = CaseManagementModel::getAdvDetails($data['applicantDetails']->advocateregno) [0];
                $applicantadvocate = $data['applicantadvocateDetails']->advocatename;
            }
            if ($data['respondantDetails']->advocateregno == "")
            {
                $data['respondantadvocateDetails'] = "";
                $respondantadvocate = $data['respondantadvocateDetails'];
            }
            else
            {
                $data['respondantadvocateDetails'] = CaseManagementModel::getAdvDetails($data['respondantDetails']->advocateregno) [0];
                $respondantadvocate = $data['respondantadvocateDetails']->advocatename;
            }

            $establishcode = $request->session()
                ->get('EstablishCode');
            $applicantcount = DB::SELECT("SELECT COUNT(*) as count from applicant where applicationid='$applicationId'");
            $respondantcount = DB::SELECT("SELECT COUNT(*) as count from respondant where applicationid='$applicationId'");
            if ($applicantcount[0]->count == '1')
            {
                $applicantcount1 = ' and Anr';
            }
            elseif ($applicantcount[0]->count > 1)
            {
                $applicantcount = DB::SELECT("SELECT 'And '||COUNT(*)-1 ||' Others' as count from applicant where applicationid='$applicationId'");
                $applicantcount1 = 'and Ors';
            }

            if ($respondantcount[0]->count == '1')
            {
                $respondantcount1 = ' and Anr';
            }
            elseif ($respondantcount[0]->count > 1)
            {
                $respondantcount = DB::SELECT("SELECT 'And '||COUNT(*)-1 ||' Others' as count from respondant where applicationid='$applicationId'");
                $respondantcount1 = ' and Ors';
            }
            $data['causelisttitle'] = DB::table('establishment')->select('causelist_title')
                ->where('establishcode', $establishcode)->get() [0];
            $query_hearings =   date('d/m/Y', strtotime($hearingdate));


              $var=explode('/',$query_hearings);
            $year=$var[2];

            $applicationid=str_replace('/', '_', $applicationId);
            $hearingdate=str_replace('/', '-', $query_hearings);

             $name=$applicationid.'|'.$year.'|'.$hearingdate;

            $applicantname = $data['applicantDetails']->applicantname;
            $applicantadvocate = $data['applicantadvocateDetails']->advocatename;
            $respondantname = $data['respondantDetails']->respondname;
            $respondantadvocate = $data['respondantadvocateDetails']->advocatename;
            $causelisttitle = $data['causelisttitle']->causelist_title;
            $path = "ordersheet3";
  DB::SELECT("CREATE temporary table count_hearing as SELECT COUNT(*) as count,
  date_trunc('day', hearingdate) as day
FROM dailyhearing
WHERE hearingdate<='$hearing_date' and applicationid='$applicationId'
GROUP BY  day");
           $count_hearing=DB::SELECT("SELECT count(*) as c from count_hearing")[0]->c;
           
            $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/' . $path . '.docx'));
  $applicationno=DB::SELECT("SELECT applicationo from applicationsummary1 where applicationid='$applicationId'")[0]->applicationo;
            
  if($appltypeshort=='CA')
  {
    $app='...Complanant';
    $resp='...Accused';
  }
  else
  {
    $app='...Applicant';
    $resp='...Respondant';
  }
            $my_template->setValue("app", $app);
            $my_template->setValue("resp", $resp);
            $my_template->setValue("applicationid", $applicationno);
            $my_template->setValue("courtdirection",  htmlspecialchars_decode($courtdirections));
            $my_template->setValue("applicantname", $applicantname);
            $my_template->setValue("applicantadvocate", $applicantadvocate);
            $my_template->setValue("respondantname", $respondantname);
            $my_template->setValue("respondantadvocate", $respondantadvocate);
            $my_template->setValue("causelisttitle", $causelisttitle);
            $my_template->setValue("hearing", $query_hearings);
            $my_template->setValue("judgeshortname", $judgeshortname_3);
            $my_template->setValue("applicantcount", $applicantcount1);
            $my_template->setValue("respondantcount", $respondantcount1);
            $my_template->setValue("judgename", $judgename);
            $my_template->setValue("judgedesig",$judgedesig);
            $my_template->setValue("causelistsrno",$causelistsrno);
            $my_template->setValue("courthallno",$courthallno);
            $my_template->setValue("hno",$count_hearing);
            $my_template->setValue("lno",$listno);
            $my_template->setValue("purposedesc",$purposedesc);
            $my_template->setValue("conapplid",$conapplid);
            $my_template->setValue("ia",$ia_ianature);
            $my_template->saveAs(storage_path("order.docx"));
            $file = storage_path("order.docx");
            $headers = array(
                'Content-Type: application/msword',
                 );
            return response()->download($file,$name.".docx", $headers)->deleteFileAfterSend(true);
        }

    $ids=$request->get('ids');
    //  dd($data['hearingcode']);

    //print_r($ids);
    //$answer = $request->get('answer');


          if($ids==null)
          {
            return response()->json([
                'status' => "error",
                'message' => "Please check checkbox to publish court directions"
              ]);
          }

          //$ids[]=$data['hearingcode'];
        //  dd($ids);
        //  dd($ids);
          //$applicationid = $request->get('applicationid');
//count(array($ids))
//dd($dd);

             try{
                 for($i=0;$i<count($ids);$i++)
                {
              // dd($ids);
               //dd($id);
             // dd($id);
                DB::SELECT("UPDATE dailyhearing set publish='Y' where hearingcode='$ids[$i]' and (publish is null or publish='N') ");
            //    DB::table('dailyhearing')->insert($data);


  $applicationid_log=DB::SELECT("SELECT applicationid from dailyhearing where hearingcode='$ids[$i]'")[0]->applicationid;
                $useractivitydtls['applicationid_receiptno'] =$applicationid_log;
        $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
        $useractivitydtls['activity'] ='Publish Daily Hearing' ;
        $useractivitydtls['userid'] = $request->session()->get('username');
        $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
        $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
        $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                DB::commit();
               }
              return response()->json([
                  'status' => "sucess",
                  'message' => "Publish Flag Updated Successfully"
                ]);

            }
            catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return response()->json([
                    'status' => "error",
                    'message' => "Enter remarks for selected values"
                  ]);
            } catch (\Throwable $e) {
                   DB::rollback();
                   throw $e;
                   return response()->json([
                       'status' => "error",
                       'message' => "Enter remarks for selected values"
                     ]);
               }


    }
}
