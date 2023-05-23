<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\Scrutiny;
use Session;
use App\UserActivityModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;
use App\Services\PayUService\Exception;

class ScrutinyController extends Controller
{

  public function __construct()
	{
	 $this->case = new CaseManagementModel();
     $this->scrutiny =  new Scrutiny();
	 $this->UserActivityModel = new UserActivityModel();
	}

  public function index(Request $request)
   {
   	 $data['applicationType'] = $this->case->getApplType();
   	 return view('Scrutiny.ScrutinyForm',$data);
   }

  public function scrutinyCompliance(Request $request)
   {
     $data['applicationType'] = $this->case->getApplType();
     return view('Scrutiny.ScrutinyCompliance',$data);
   }
   public function scrutinyhistory(Request $request)
    {
$data['applicationType'] = $this->case->getApplType();
      return view('Scrutiny.scrutinyhistory',$data);
    }

  public function scrutinyCheckSlip(Request $request)
   {
     $data['applicationType'] = $this->case->getApplType();
     return view('Scrutiny.ScrutinyCheckSlip',$data);
   }
   public function getScrutinyDetailsForExtraQuestions(Request $request)
   {
    $applicationid=$request->get('applicationid');
   $data['value']=DB::SELECT(" SELECT * from scrutinydetails where applicationid='$applicationid' and chklistsrno >='501' and chklistsrno <='558' order by chklistsrno");
   echo json_encode($data['value']);
   }

   public function AddExtraQuestionaries(Request $request)
   {
     if($request->input('sbmt_ques') == "A")
  {
$data['scrutinychklist']=$request->get('ids');
//print_r($ids);
$ids=$data['scrutinychklist'];
$applicationid = $request->get('applicationid');
$username=Session::get('username');
$var=  explode('/',$applicationid);
$appltype=$var[0];
$remarks=$request->get('remarksQuestionaries');
//dd($remarks);
$remarksNoEmptyOrNull = array_filter($remarks, function($v){
 return !is_null($v) && $v !== '';
});
//dd($remarksNoEmptyOrNull1);
//dd($ids);
$remarksNoEmptyOrNull1=array_values($remarksNoEmptyOrNull );

if($ids==null and $remarksNoEmptyOrNull1==null)
{
    return response()->json([
     'status' => "null",
     'message' => "No checkbox and remarks selected"
   ]);
 // echo "alert('Please select checkbox and remarks ');";
}
$count_remarks=count($remarksNoEmptyOrNull1);
$count_ids=count($ids);
if($count_remarks!=$count_ids)
{
   return response()->json([
     'status' => "error",
     'message' => "Remarks must be entered with checked checkbox"
   ]);
}
//dd($remarksNoEmptyOrNull1);
//dd($ids);
//$dd= $ids+$remarksNoEmptyOrNull;
//dd($dd);
//dd($remarksNoEmptyOrNull);
//dd($remarks);
//dd($remarksNoEmptyOrNull1);
$data['appltypecode']=DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$appltype'")[0]->appltypecode;
//dd($data['appltypecode']);
$appltypecode=$data['appltypecode'];
$flag='Y';
$observation='';

   try{
       for($i=0;$i<count($ids);$i++)
      {
       $data=[
       'chklistsrno'=>$ids[$i],
       'remarks'=>$remarksNoEmptyOrNull1[$i]
      ];
      $data['applicationid']=$applicationid;
      $data['appltypecode']=$appltypecode;
      $data['observation']='';
      $data['objectedflag']='Y';
      $data['updatedby']=$username;
      $data['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
      DB::table('scrutinydetails')->insert($data);
      DB::commit();
     }
    return response()->json([
        'status' => "sucess",
        'message' => "Questionaries Added Successfully."
      ]);

  }
     catch (\Exception $e) {
        dd($e->getMessage());

       if($e=='ErrorException')
         DB::rollback();
          return response()->json([
             'status' => "error",
             'message' => "Remarks must be entered with checked checkbox"
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

 else
 {
   $data['scrutinychklist']=$request->get('ids');

   $scrutinydate=$request->get('acceptrejectdate');
   //print_r($ids);
   $ids=$data['scrutinychklist'];

   $username=Session::get('username');
   $applicationid = $request->get('applicationid');

   $var=  explode('/',$applicationid);
   $appltype=$var[0];
   $remarks=$request->get('remarksQuestionaries');
   //dd($remarks);
   $remarksNoEmptyOrNull = array_filter($remarks, function($v){
    return !is_null($v) && $v !== '';
   });
   //dd($remarksNoEmptyOrNull1);
   //dd($ids);
   $remarksNoEmptyOrNull1=array_values($remarksNoEmptyOrNull );

//   if($ids==null and $remarksNoEmptyOrNull1==null)
//   {


   /*DB::SELECT("UPDATE scrutinydetails SET objectedflag='N',remarks=null
   where applicationid='$applicationid'
    and chklistsrno >500 and chklistsrno <559"); */
 //DB::SELECT("DELETE from scrutinydetails where applicationid='$applicationid' and chklistsrno >500 and chklistsrno <559");
// return response()->json([
//  'status' => "null",
//  'message' => "No checkbox and remarks selected"
// ]);
    // echo "alert('Please select checkbox and remarks ');";
 //  }

  $ids1=$request->get('ids1');

   $flag=$request->get('flag');

   for($i=0;$i<count($flag);$i++){
         if($flag[$i]=='N'){
           $flag1[]=$flag[$i];

         }
           else{
              $flag1[]=$flag[$i];
           }

       }

       for( $i = 0 ; $i < count($flag1) ; $i++ )
    {
      if ( $flag1[$i] == 'N' ){
       if($ids==null){
          $ids[]=0;
          }
      if($ids1==null){
       $ids1[]=0;      
       }
       if($ids!=$ids1){
        $ids_mix = array_diff($ids1, $ids);
        $ids2=array_values($ids_mix );

        for($k=0;$k<count($ids2);$k++)
        {
          $updatedby_check=DB::SELECT("SELECT updatedby from scrutinydetails where
            applicationid='$applicationid' and chklistsrno='$ids2[$k]'");
          $remarks_fromdatabase=DB::SELECT("SELECT remarks from scrutinydetails where
              applicationid='$applicationid' and chklistsrno='$ids2[$k]'");
         //dd($updatedby_check);

       if($updatedby_check!=null){
          if($username==$updatedby_check[0]->updatedby){

          DB::SELECT("UPDATE scrutinydetails set objectedflag='N',
          remarks=null where applicationid='$applicationid' and chklistsrno='$ids2[$k]'");
        }
        else{
          DB::SELECT("UPDATE scrutinydetails set updatedby=updatedby||','||'$username',objectedflag='N',
          remarks=null where applicationid='$applicationid' and chklistsrno='$ids2[$k]'");
        }
      }
      else{
        DB::SELECT("UPDATE scrutinydetails set updatedby='$username',objectedflag='N',
        remarks=null where applicationid='$applicationid' and chklistsrno='$ids2[$k]'");
       }
      }
    }
  }
}

for( $i = 0 ; $i < count($flag1) ; $i++ )
{   if ( $flag1[$i] == 'Y' ){
  //dd($remarksNoEmptyOrNull1);
 //dd($ids);
 //$dd= $ids+$remarksNoEmptyOrNull;
 //dd($dd);
 //dd($remarksNoEmptyOrNull);
 //dd($remarks);
 $data['appltypecode']=DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$appltype'")[0]->appltypecode;
 //dd($data['appltypecode']);
 $appltypecode=$data['appltypecode'];
 $flag='Y';
 $observation='';
// dd($remarksNoEmptyOrNull1);

if($ids[0] == 0)
{
$remarksNoEmptyOrNull1=0;
if($remarksNoEmptyOrNull1==$ids[0])
 {
   return response()->json([
     'status' => "update",
     'message' => "Updated"
   ]);
 }

}

$count_remarks=count($remarksNoEmptyOrNull1);
$count_ids=count($ids);

if($count_remarks!=$count_ids)
{
   return response()->json([
     'status' => "error",
     'message' => "Remarks must be entered with checked checkbox"
   ]);
}


//DB::SELECT("DELETE from scrutinydetails where applicationid='$applicationid' and chklistsrno >500 and chklistsrno <559");
    try
    {
        for($i=0;$i<count($ids);$i++)
       {
        $data=[
        'chklistsrno'=>$ids[$i],
        'remarks'=>$remarksNoEmptyOrNull1[$i]
       ];

       $data['applicationid']=$applicationid;
       $data['appltypecode']=$appltypecode;
       $data['observation']='';
       $data['objectedflag']='Y';
       $data['updatedby']=$username;
       $data['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));

       $updatedby=$data['updatedby'];
       $observation=$data['observation'];
       $chklistsrno=$data['chklistsrno'];
       $remarks=$data['remarks'];
       $objectedflag=$data['objectedflag'];
       $appltypecode= $data['appltypecode'];
       $applicationid=$data['applicationid'];
       $scrutinydate=$data['scrutinydate'];
       $values=DB::SELECT("SELECT chklistsrno,objectedflag from scrutinydetails where applicationid='$applicationid' and chklistsrno='$chklistsrno'");

      if($values!=null)
      { /*$flag='N';
        if($flag==$values[0]->objectedflag)
        {
          DB::SELECT("UPDATE scrutinydetails set remarks='$remarks',objectedflag='Y' where applicationid='$applicationid' and chklistsrno='$chklistsrno'");
        }
        else{  */
        $remarks_fromdatabase=DB::SELECT("SELECT remarks from scrutinydetails where
            applicationid='$applicationid' and chklistsrno='$chklistsrno'");

          $updatedby_check=DB::SELECT("SELECT updatedby from scrutinydetails where applicationid='$applicationid' and chklistsrno='$chklistsrno'");
         //dd($updatedby_check);
       if($updatedby_check!=null){
         if($username==$updatedby_check[0]->updatedby){
          DB::SELECT("UPDATE scrutinydetails set remarks='$remarks',objectedflag='Y'
          where applicationid='$applicationid' and chklistsrno='$chklistsrno'");
        }
     else{
       if($remarks!=$remarks_fromdatabase[0]->remarks){
         DB::SELECT("UPDATE scrutinydetails set updatedby=updatedby||','||'$username',remarks='$remarks',objectedflag='Y'
         where applicationid='$applicationid' and chklistsrno='$chklistsrno'");
       }
      }
      }
      else{
          DB::SELECT("UPDATE scrutinydetails set  updatedby='$username', remarks='$remarks',objectedflag='Y'
          where applicationid='$applicationid' and chklistsrno='$chklistsrno'");

       }
      //}
    }
      else{
            DB::SELECT("INSERT INTO public.scrutinydetails(
applicationid, chklistsrno, observation, objectedflag, remarks, appltypecode, updatedby,scrutinydate)
VALUES('$applicationid','$chklistsrno','','$objectedflag','$remarks','$appltypecode','$updatedby','$scrutinydate') ON CONFLICT DO NOTHING");
      }

    // $username=$data['updatedby'];


       DB::commit();
      }

//      dd($data['remarks']);
//        dd($data['chklistsrno']);
       return response()->json([
         'status' => "update",
         'message' => "Questionaries Updated Successfully."
       ]);

   }
      catch (\Exception $e) {
        dd($e->getMessage());

        if($e=='ErrorException')
          DB::rollback();
           return response()->json([
              'status' => "error",
              'message' => "Remarks must be entered with checked checkbox"
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

}
}
   public function showApplicationScrutiny(Request $request)
   {
   	 $request->validate([
                'applicationType' => 'required',
                'applicationNo' => 'required',

             ]);
   	  $applicationNo = $request->input('applicationNo');
	    $applicationType = explode("-",$request->input('applicationType'));
   		$application_id = $applicationType[1].'/'.$applicationNo;
   		$establishcode = $request->session()->get('EstablishCode');

      $var=explode('/',$application_id);
      $appltypeshort=$var[0];
      $appnum1= $var[1];
      $applyear= $var[2];

      $applications1 = db::table('applicationtype')->select('appltypecode')->where('appltypeshort', $appltypeshort)->get();
      $appltypecode = $applications1[0]->appltypecode;
      //$mainapplId= DB::select("select applicationid from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");

     $data['applicationNo'] = $request->input('applicationNo');
    $application= DB::select("select applicationid,scrutinyflag,objectionflag from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");

    if (count($application)==0)
        {
         return redirect()->route('scrutiny')->with('error', ' Application doesnot exists');
        }
        else{
         if($application[0]->applicationid != $application_id)
         {
         // $find_appnum="";
         $application_id = $application[0]->applicationid;
         }
        else
         {
         $application_id = $application[0]->applicationid;
         }
         $postedtocourt = DB::select("select count(*) as count from scrutiny where postedtocourt is not null and applicationid=:applicationid",['applicationid' => $application_id])[0];
         $postedtocourt= $postedtocourt->count;
         $scrutinyflag=$application[0]->scrutinyflag;

         if($postedtocourt > 0 ){
           return redirect()->route('scrutiny')->with('error', 'Scrutiny cannot be performed');
            }
        else{
         $objectionflag= $application[0]->objectionflag;
         if($objectionflag == null || $objectionflag==''){
         // New Scrutiny
               $data['insertUpdateflag'] = 'I';
               $data['applicationType'] = $this->case->getApplType();
               $data['applCategory'] = $this->case->getApplCategory();
               $data['department'] = $this->case->getDeptNames("");
               $data['applicationDetails'] = $this->case->getApplicationId($application_id,$user='')[0];
               $applcatcode=$data['applicationDetails']->applcategory;
               $data['applCategoryName'] = $this->case->getApplCategoryName($applcatcode)[0];
               if($data['applicationDetails']->subject == '-'){
                 $data['applSubject']= $data['applCategoryName']->applcatname;
               }else{
                 $data['applSubject']= $data['applCategoryName'] ->applcatname.' - ' .$data['applicationDetails']->subject;
               }
               //print_r($data['applSubject']);

              $applicantDetails = $this->case->getTopApplicantDetails($application_id);
              if(count($applicantDetails) > 0 ){
                $data['applicantDetails'] = $applicantDetails[0];

              }else{
            return redirect()->route('scrutiny')->with('error', 'Scrutiny cannot be performed, Applicant Details not entered');
       }
              $respondantDetails = $this->case->getTopRespondantDetails($application_id);
              if(count($respondantDetails) > 0 ){
                $data['respondantDetails'] = $respondantDetails[0];

              }
           else{
            return redirect()->route('scrutiny')->with('error', 'Scrutiny cannot be performed, Respondent Details not entered');
       }

               $data['scrutinychklist'] = DB::select("select * from scrutinychklist where appltypecode =:appltypecode order by chklistsrno",['appltypecode' => $applicationType[0]]);
               $data['scrutinydetails'] = $this->scrutiny->getScrutinyDetails($application_id);
               $data['scrutinychklist_extra']=DB::SELECT("SELECT * from scrutinychklist where appltypecode='0'");

               $data['scrutinydetails11'] = $this->scrutiny->getScrutinyDetailsObjection($application_id);
               return view('Scrutiny.ApplicationScrutiny', $data);
          }
         else{
         // Update Scrutiny
         $establishcode = $request->session()->get('EstablishCode');

           $data['insertUpdateflag'] = 'U';
           $data['applicationType'] = $this->case->getApplType();
           $data['applCategory'] = $this->case->getApplCategory();
           $data['department'] = $this->case->getDeptNames("");
           $data['applicationDetails'] = $this->case->getApplicationId($application_id,$user='',$establishcode)[0];
           $applcatcode=$data['applicationDetails']->applcategory;
               $data['applCategoryName'] = $this->case->getApplCategoryName($applcatcode)[0];
               if($data['applicationDetails']->subject == '-'){
                 $data['applSubject']= $data['applCategoryName']->applcatname;
               }else{
                 $data['applSubject']= $data['applCategoryName'] ->applcatname.' - ' .$data['applicationDetails']->subject;
               }
           $applicantDetails = $this->case->getTopApplicantDetails($application_id);
              if(count($applicantDetails) > 0 ){
                $data['applicantDetails'] = $applicantDetails[0];

              }
              $respondantDetails = $this->case->getTopRespondantDetails($application_id);
              if(count($respondantDetails) > 0 ){
                $data['respondantDetails'] = $respondantDetails[0];

              }
           $data['scrutiny'] = $this->scrutiny->getScrutiny($application_id)[0];
           $data['scrutinydetails'] = $this->scrutiny->getScrutinyDetails($application_id);
           $data['scrutinychklist_extra']=DB::SELECT("SELECT * from scrutinychklist where appltypecode='0'");
           $data['scrutinydetails11'] = $this->scrutiny->getScrutinyDetailsObjection($application_id);
           return view('Scrutiny.ApplicationScrutiny', $data);
          }
        }

      }
   }


 public function saveApplicationScrutiny(Request $request){
     if($request->input('sbmt_adv') == "A")
        {
          DB::beginTransaction();

             $scrutinySave['applicationid']  = $request->input('applicationid');
             $scrutinySave['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['lastcompliancedate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['acceptreject']  = $request->input('applicationComplied');
             $scrutinySave['accrejdate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['tobecomplieddate']  = date('Y-m-d',strtotime($request->input('tobecomplieddate')));

             $scrutinySave['reason']  = trim($request->input('rejectReason'));
             $scrutinySave['createdon']    = date('Y-m-d H:i:s') ;
             $scrutinySave['createdby']    = $request->session()->get('username');
            try {
                DB::table('scrutiny')->insert($scrutinySave);
                $i=0;
                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']= $_POST['applicationid'];

                $scrutinychklist['appltypecode']= $_POST['appltypecode'];
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
                $applicationid=$scrutinychklist['applicationid'] ;
                $scrutinydate=$scrutinychklist['scrutinydate'] ;
               // $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
				        $scrutinychklist['objectedflag']= 'Y';
                $scrutinychklist['remarks'] = trim($_POST['remarks'][$i]);
                $update =  DB::table('scrutinydetails')->insert($scrutinychklist);

                $objections=DB::SELECT("SELECT * from scrutinydetails where applicationid='$applicationid' and chklistsrno>500 and chklistsrno<559");

                if($objections>0){
                  DB::SELECT ("UPDATE scrutinydetails set scrutinydate='$scrutinydate'
                    where applicationid='$applicationid' and chklistsrno>500 and chklistsrno<559");
                }
                $i++;
              }
            if($request->has('extraObjection'))
            {
              $j=0;
              $extraObjectionchklistsrno =101;
              foreach ($request->input("extraObjection") as $key) {
                $scrutinychklist1['applicationid']= $_POST['applicationid'];
                $scrutinychklist1['appltypecode']= $_POST['appltypecode'];
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
               // $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
				        $scrutinychklist1['objectedflag']= 'Y';
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('scrutinydetails')->insert($scrutinychklist1);
                $j++;
                $extraObjectionchklistsrno++;
                }
            }

              if($request->input('applicationComplied')=='Y'){
                  $objectionflag = '0';
                  $scrutinyflag  = 'Y';
              }
              else if($request->input('applicationComplied')=='NA')
                {
                  $objectionflag  = '1';
                  $scrutinyflag  = 'N';
                }
                else{
                 $objectionflag = '1';
                 $scrutinyflag  = 'N';
              }

               DB::table('application')->where(['applicationid'=>$_POST['applicationid']])->update(['objectionflag' =>$objectionflag , 'scrutinyflag' => $scrutinyflag]);

			     $useractivitydtls['applicationid_receiptno'] = $_POST['applicationid'];
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;

			     $useractivitydtls['activity'] ='Save Application Scrutiny' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);

               DB::commit();
                 return redirect()->route('scrutiny')->with('success', 'Scrutiny Saved successfully');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('scrutiny')->with('error', 'Someting wrong, Scrutiny not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('scrutiny')->with('error', 'Someting wrong, Scrutiny not saved !!');
            }
        }
          else if($request->input('sbmt_adv') == "U"){
            DB::beginTransaction();
             $applicationid  = $request->input('applicationid');
             $scrutinySave['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['lastcompliancedate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['acceptreject']  = $request->input('applicationComplied');
             $scrutinySave['accrejdate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['tobecomplieddate']  = date('Y-m-d',strtotime($request->input('tobecomplieddate')));
             $scrutinySave['reason']  = trim($request->input('rejectReason'));
             $scrutinySave['updatedon']    = date('Y-m-d H:i:s') ;
             $scrutinySave['updatedby']    = $request->session()->get('username');

            try {
                DB::table('scrutiny')->where('applicationid',$applicationid)->update($scrutinySave);

                DB::delete("DELETE from scrutinydetails where applicationid='$applicationid' and chklistsrno >0 aND chklistsrno <13");
                DB::delete("DELETE from scrutinydetails where applicationid='$applicationid' and chklistsrno>100 and chklistsrno <501");

                $i=0;
                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']= $_POST['applicationid'];
                $scrutinychklist['appltypecode']= $_POST['appltypecode'];
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
               // $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
                $scrutinychklist['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
			          $scrutinychklist['objectedflag']= 'Y';
                $scrutinychklist['remarks'] = trim($_POST['remarks'][$i]);

                $update =  DB::table('scrutinydetails')->insert($scrutinychklist);
                $i++;
              }
            if($request->has('extraObjection'))
            {
              $j=0;
              $extraObjectionchklistsrno =101;
              foreach ($request->input("extraObjection") as $key) {
                $scrutinychklist1['applicationid']= $_POST['applicationid'];
                $scrutinychklist1['appltypecode']= $_POST['appltypecode'];
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
               // $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
			        	$scrutinychklist1['objectedflag']= 'Y';

                $applicationid=$scrutinychklist1['applicationid'] ;
                $scrutinydate=$scrutinychklist1['scrutinydate'];
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('scrutinydetails')->insert($scrutinychklist1);
                $objections=DB::SELECT("SELECT * from scrutinydetails where applicationid='$applicationid' and chklistsrno>500 and chklistsrno<559");

                if($objections>0){
                  DB::SELECT ("UPDATE scrutinydetails set scrutinydate='$scrutinydate'
                    where applicationid='$applicationid' and chklistsrno>500 and chklistsrno<559");
                }
                $j++;
                $extraObjectionchklistsrno++;
                }
            }
           if($request->input('applicationComplied')=='Y'){
               $objectionflag = '0';
               $scrutinyflag  = 'Y';
            }
            else if($request->input('applicationComplied')=='NA')
              {
                $objectionflag  = '1';
                $scrutinyflag  = 'N';
              }
              else{
               $objectionflag  = '1';
               $scrutinyflag  = 'N';
            }

              DB::table('application')->where(['applicationid'=>$_POST['applicationid']])->update(['objectionflag' =>$objectionflag , 'scrutinyflag' => $scrutinyflag]);
			   $useractivitydtls['applicationid_receiptno'] = $_POST['applicationid'];
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			   $useractivitydtls['activity'] ='Update Application Scrutiny' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
              DB::commit();
              return redirect()->route('scrutiny')->with('success', 'Scrutiny Updated successfully');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('scrutiny')->with('error', 'Someting wrong, Scrutiny not Updated !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('scrutiny')->with('error', 'Someting wrong, Scrutiny not Updated !!');
            }
           }
          }

  public function showScrutinyCompliance(Request $request)
     {
     $request->validate([
                'applicationType' => 'required',
                'applicationNo' => 'required'
             ]);
      $applicationNo = $request->input('applicationNo');
      $applicationType = explode("-",$request->input('applicationType'));
      $application_id = $applicationType[1].'/'.$applicationNo;
        $establishcode = $request->session()->get('EstablishCode');
    //getApplicantDetails
    $var=explode('/',$application_id);
    $appltypeshort=$var[0];
    $appnum1= $var[1];
    $applyear= $var[2];

    $applications1 = db::table('applicationtype')->select('appltypecode')->where('appltypeshort', $appltypeshort)->get();
    $appltypecode = $applications1[0]->appltypecode;
    //$mainapplId= DB::select("select applicationid from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");

   $data['applicationNo'] = $request->input('applicationNo');
  $application= DB::select("select applicationid,scrutinyflag,objectionflag from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");


     if (count($application)==0)
        {
         return redirect()->route('scrutinyCompliance')->with('error', ' Application doesnot exists');
     }else
     {
      if($application[0]->applicationid != $application_id){

        $application_id = $application[0]->applicationid;

      }
      else
       {
       $application_id = $application[0]->applicationid;

       }
       $scrutiny= DB::select("select * from scrutiny where applicationid =:applicationid",['applicationid' => $application_id]);
      if (count($scrutiny)==0)
           {
            return redirect()->route('scrutinyCompliance')->with('error', ' No Scrutiny exists for the Application '.$application_id);
            }
       $scrutinyflag = $application[0]->scrutinyflag;
       $objectionflag = $application[0]->objectionflag;
       $postedtocourt = $scrutiny[0]->postedtocourt;
       if($scrutinyflag == "Y" || $objectionflag == null || $postedtocourt != null){
         return redirect()->route('scrutinyCompliance')->with('error', 'compliance cannot be performed');
          }
       else{

             $data['applicationType'] = $this->case->getApplType();
             $data['applCategory'] = $this->case->getApplCategory();
             $data['department'] = $this->case->getDeptNames("");
             $data['applicationDetails'] = $this->case->getApplicationId($application_id,$user='')[0];
             $applcatcode=$data['applicationDetails']->applcategory;
             $data['applCategoryName'] = $this->case->getApplCategoryName($applcatcode)[0];
             if($data['applicationDetails']->subject == '-'){
                   $data['applSubject']= $data['applCategoryName']->applcatname;
                 }else{
                   $data['applSubject']= $data['applCategoryName'] ->applcatname.' - ' .$data['applicationDetails']->subject;
                 }

              $applicantDetails = $this->case->getTopApplicantDetails($application_id);
                if(count($applicantDetails) > 0 ){
                  $data['applicantDetails'] = $applicantDetails[0];

                }
                $respondantDetails = $this->case->getTopRespondantDetails($application_id);
                if(count($respondantDetails) > 0 ){
                  $data['respondantDetails'] = $respondantDetails[0];

                }
             $data['scrutiny'] = $this->scrutiny->getScrutiny($application_id)[0];
             $data['scrutinydetails'] = $this->scrutiny->getScrutinyDetails($application_id);
             $data['scrutinychklist_extra']=DB::SELECT("SELECT * from scrutinychklist where appltypecode='0'");
             $data['scrutinydetails11'] = $this->scrutiny->getScrutinyDetailsObjection($application_id);
             $data['display_scrutinychkist_extra']=DB::SELECT("SELECT st.*,sc.chklistdesc from scrutinydetails as st
           inner join scrutinychklist sc on st.chklistsrno=sc.chklistsrno where sc.appltypecode='0'and
           st.applicationid='$application_id'");
             return view('Scrutiny.ApplicationScrutinyCompliance', $data);
           }

      }
   }
 public function findscrutinydate($applicationid){
$applicationid = $applicationid;
$applicationid=str_replace('-','/',$applicationid);

$scrutinydate=DB::SELECT("SELECT  DISTINCT to_char(scrutinydate, 'DD/MM/YYYY') as scrutinydate from scrutinydetailshistory where applicationid='$applicationid'");
  return response()->json($scrutinydate);

 }

   public function scrutinyhistoysearch(Request $request){
    $request->validate([
               'applicationType' => 'required',
               'applicationNo' => 'required'
       ]);
   	 $establishcode=Session::get('EstablishCode');
     $establishmentname=Session::get('establishfullname');
     $applicationType  = $request->input('applicationType');
     $scrutinydate=$request->input('scrutinydate');



     $appltypecode=DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$applicationType'")[0]->appltypecode;
     $applicationNo = $request->input('applicationNo');

     $applicationid=$applicationType.'/'.$applicationNo;
     $result=DB::SELECT ("SELECT DISTINCT sd.*,sc.chklistdesc from scrutinydetailshistory sd
left join scrutinychklist as sc  on sd.chklistsrno=sc.chklistsrno
where sd.applicationid='$applicationid' and sd.objectedflag='Y' and to_char(scrutinydate, 'DD/MM/YYYY')='$scrutinydate' order by chklistsrno");

     if($result==null)
     {
       return redirect()->route('scrutinyhistory')->with('error', 'Scrutiny History Details for '.$applicationid.' not found');


     }
        return view('Scrutiny.scrutinyhistorydetails', ['result'=>$result],['scrutinydate'=>$scrutinydate,'applicationid'=>$applicationid]);

    }


    public function saveScrutinyCompliance(Request $request){
             $scrutinySave['applicationid']  = $request->input('applicationid');
             $applicationid= $scrutinySave['applicationid'] ;
             $scrutinySave['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['lastcompliancedate']  = date('Y-m-d',strtotime($request->input('lastcompliancedate')));
             $scrutinySave['acceptreject']  = $request->input('applicationComplied');
             $scrutinySave['accrejdate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['reason']  = trim($request->input('rejectReason'));
             $scrutinySave['createdon']    = date('Y-m-d H:i:s') ;
             $scrutinySave['createdby']    = $request->session()->get('username');
             $scrutinySave['tobecomplieddate']  = date('Y-m-d',strtotime($request->input('tobecomplieddate')));

           DB::beginTransaction();
            try {

                DB::insert('insert into scrutinyhistory select * from scrutiny where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]);

               DB::select("insert into scrutinydetailshistory
               select * from scrutinydetails
                where applicationid='$applicationid'");

                DB::delete('delete from scrutiny where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]);


                DB::delete('delete from scrutinydetails where  chklistsrno >0 and chklistsrno <13 and  applicationid= :applicationid',['applicationid' => $request->input('applicationid')]  );
                DB::delete('delete from scrutinydetails where  chklistsrno>100 and chklistsrno <501 and applicationid= :applicationid',['applicationid' => $request->input('applicationid')]);


                DB::table('scrutiny')->insert($scrutinySave);

                 $i=0;

                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']= $_POST['applicationid'];
                $scrutinychklist['appltypecode']= $_POST['appltypecode'];
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['scrutinydate']=date('Y-m-d',strtotime($request->input('accrejdate')));

                //$scrutinychklist['objectedflag']= $_POST['compliance'][$i];
				        $scrutinychklist['objectedflag']= 'Y';
                $scrutinychklist['remarks'] = trim($_POST['remarks'][$i]);

                $update =  DB::table('scrutinydetails')->insert($scrutinychklist);

                $i++;

            }
            if($request->has('extraObjection'))
            {
              $j=0;
              $extraObjectionchklistsrno =101;
              foreach ($request->input("extraObjection") as $key) {
                $scrutinychklist1['applicationid']= $_POST['applicationid'];
                $scrutinychklist1['appltypecode']= $_POST['appltypecode'];
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
               // $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
        				$scrutinychklist1['objectedflag']= 'Y';
                $applicationid=$scrutinychklist1['applicationid'] ;
                $scrutinychklist1['scrutinydate']=date('Y-m-d',strtotime($request->input('accrejdate')));

                $scrutinydate=date('Y-m-d',strtotime($request->input('accrejdate')));
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);

                $update =  DB::table('scrutinydetails')->insert($scrutinychklist1);
                $objections=DB::SELECT("SELECT * from scrutinydetails where applicationid='$applicationid' and chklistsrno>500 and chklistsrno<559");

                if($objections>0){
                  DB::SELECT ("UPDATE scrutinydetails set scrutinydate='$scrutinydate'
                    where applicationid='$applicationid' and chklistsrno>500 and chklistsrno<559");
                }
                $j++;
                $extraObjectionchklistsrno++;
                }
            }

              if($request->input('applicationComplied')=='Y'){
                 // $objectionflag = '0';
                  $scrutinyflag  = 'Y';
                  DB::table('application')->where(['applicationid'=>$_POST['applicationid']])->update(['scrutinyflag' => $scrutinyflag]);

              }
              elseif($request->input('applicationComplied')=='NA')
              {
                $scrutinyflag  = 'N';
                DB::table('application')->where(['applicationid'=>$_POST['applicationid']])->update(['scrutinyflag' => $scrutinyflag]);
              }
              else{
                // $objectionflag = '1';
                 $scrutinyflag  = 'N';
               DB::update('update application set objectionflag= CAST(objectionflag AS INT)+1 , scrutinyflag=:scrutinyflag where applicationid= :applicationid',['scrutinyflag'=> $scrutinyflag,'applicationid' => $request->input('applicationid')]);
             }

         $useractivitydtls['applicationid_receiptno'] = $_POST['applicationid'];
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			   $useractivitydtls['activity'] ='Save Scrutiny Compliance' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
               DB::commit();

                 return redirect()->route('scrutinyCompliance')->with('success', 'Scrutiny Compliance Saved successfully');
            }
            catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('scrutinyCompliance')->with('error', 'Someting wrong, Scrutiny Compliance not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('scrutinyCompliance')->with('error', 'Someting wrong, Scrutiny Compliance not saved !!');
            }

           }

public function printScrutinyCheckSlip(Request $request)
    {
      $establishcode = $request->session()->get('EstablishCode');
       $request->validate([
                'applicationType' => 'required',
                'applicationNo' => 'required'
             ]);
       $applicationNo = $request->input('applicationNo');
       $applicationType = explode("-",$request->input('applicationType'));
       $application_id = $applicationType[1].'/'.$applicationNo;
       $data['title'] = DB::select("select * from establishment where establishcode = ". Session()->get('EstablishCode'));

       $application= DB::select("select * from application where applicationid =:applicationid and establishcode = :establishcode",['applicationid' => $application_id,'establishcode' => $establishcode]);

        if (count($application)==0)
        {
         return redirect()->route('scrutinyCheckSlip')->with('error', ' Application doesnot exists');
         }else{
          $scrutiny = $this->scrutiny->getScrutiny($application_id);
         if (count($scrutiny)==0)
        {
         return redirect()->route('scrutinyCheckSlip')->with('error', ' No Scrutiny exists for the Application '.$application_id);
         }else{

       $data['applicationDetails'] = $this->case->getApplicationId($application_id,$user='')[0];
       $applcatcode=$data['applicationDetails']->applcategory;
       $data['applCategoryName'] = $this->case->getApplCategoryName($applcatcode)[0];
       if($data['applicationDetails']->subject == '-'){
                $data['applSubject']= $data['applCategoryName']->applcatname;
              }else{
                $data['applSubject']= $data['applCategoryName'] ->applcatname.' - ' .$data['applicationDetails']->subject;
              }

      $applicantDetails = $this->case->getTopApplicantDetails($application_id);
             if(count($applicantDetails) > 0 ){
               $data['applicantDetails'] = $applicantDetails[0];

             }
             $respondantDetails = $this->case->getTopRespondantDetails($application_id);
             if(count($respondantDetails) > 0 ){
               $data['respondantDetails'] = $respondantDetails[0];

             }
       $data['scrutiny'] = $this->scrutiny->getScrutiny($application_id)[0];
       $data['scrutinydetails'] = $this->scrutiny->getScrutinyDetails($application_id);
       $data['scrutinydetails11'] = $this->scrutiny->getScrutinyDetailsObjection($application_id);
       $data['getScrutinyDetailsForExtraQuestions']=DB::SELECT(" SELECT sc.chklistdesc,st.remarks,st.chklistsrno,st.objectedflag from  scrutinydetails as st
     inner join scrutinychklist sc on st.chklistsrno=sc.chklistsrno
     where st.applicationid='$application_id'
     and st.chklistsrno >='501' and st.chklistsrno <='558' and st.objectedflag='Y' order by st.chklistsrno  ");

        //  dd($data['getScrutinyDetailsForExtraQuestions']);
       $itemNo = "";
       foreach ( $data['scrutinydetails'] as $scrutinydetails) {
              if($scrutinydetails->objectedflag =="Y")
               $itemNo .= $scrutinydetails->chklistsrno.",";
             }

           $itemNo = substr($itemNo, 0, -1);

          if(count($data['getScrutinyDetailsForExtraQuestions'])>0)
          {
           if($data['getScrutinyDetailsForExtraQuestions'][0]->objectedflag =="Y")
           $itemNo .= " ,Objection/Objections";
          }

          if(count($data['scrutinydetails11'])>0)
             {
             if($data['scrutinydetails11'][0]->objectedflag =="Y")
                      $itemNo .= " and Other objections";
             }



             //print_r($itemNo);
       $data['itemNo'] = $itemNo;
      //  return view('Scrutiny.ScrutinyCheckSlipPDF', $data);
       $pdf = PDF::LoadView('Scrutiny.ScrutinyCheckSlipPDF',$data);




 //First, get the correct document size.
    //$mpdf = new \Mpdf\Mpdf();

   // $pagecount = $mpdf->SetSourceFile('D:\1.pdf');


    //Open a new instance with specified width and height, read the file again
    // $mpdf = new \Mpdf\Mpdf();
    // $mpdf->SetSourceFile('D:\1.pdf');

    // //Write into the instance and output it
    // for ($i=1; $i <= $pagecount; $i++) {
    //     $tplId = $mpdf->ImportPage($i);
    //     $mpdf->addPage();
    //     $mpdf->UseTemplate($tplId);
    //     $mpdf->SetWatermarkText('ksat bengaluru');
    //     $mpdf->showWatermarkText = true;
    // }




    // return $mpdf->output();

     return $pdf->download('CheckSlip.pdf');
      }   }
 }

}
