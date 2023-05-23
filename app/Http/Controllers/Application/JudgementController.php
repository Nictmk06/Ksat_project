<?php
namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\DisposedApplicationModel;
use App\JudgementModel;
use App\UserActivityModel;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use File;
class JudgementController extends Controller
{
  private $path ;
  private $path_dsc;	
  public function __construct()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		//	echo 'This is a server using Windows!';
			//  $this->path ="C:/Judgements";
                            $this->path ="/usr/local/apache24/htdocs/Judgements";

		} else {
		//	echo 'This is a server not using Windows!';
			 //$this->path ="/var/www/data/ksat/judgements";
                            $this->path ="/usr/local/apache24/htdocs/Judgements";

	                 $this->path_dsc="/var/www/data/ksat/dscjudgement";
                    	}
  //  $this->path ="C:/Judgements";
    $this->case = new CaseManagementModel();  
    $this->disposedApplication =  new DisposedApplicationModel();
    $this->Judgement =  new JudgementModel();
    $this->UserActivityModel = new UserActivityModel();
	//when accessing by httpserver
    //$this->pathdownload ="http://10.10.28.84:9000/judgements";
  }


public function deletejudgement(Request $request)
    {

      $data['applications'] = db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedisplay')->orderBy('appltypecode', 'asc')->get();
$data['judge'] = db::table('judge')->select('*')->orderBy('judgecode', 'asc')->get();

  $data['benchjudge'] = db::table('benchjudgeview')->select('*')->orderBy('benchcode', 'asc')->get();
  $data['applcategory'] = db::table('applcategory')->select('*')->orderBy('applcatname', 'asc')->get();
  return view('Judgement.deletejudgement',$data);
    }

	
public static function getJudgementApplicantDetails(Request $request)
    {      
	 $request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),                 
              ]);
        $establishcode = $request->session()->get('EstablishCode');        
        $applicationid = $request->get('applicationid');
           //  print_r($applicationid);
        $value = DB::table('application')
                ->join('applicationtype', 'application.appltypecode', '=', 'applicationtype.appltypecode')
                ->join('judgement', 'judgement.applicationid', '=', 'application.applicationid')
                ->select('application.*','applicationtype.*')
                ->where('application.applicationid','=',$applicationid)
                ->where('application.establishcode','=',$establishcode)
                ->get();
                 return $value;
          }

 public function getAllJudgementByApplNo(Request $request)
    {      
      $request->validate([
                'applicationId' => array(
            'required',
            'regex:/^[0-9a-zA-Z\/]+$/'
          ),
          ]);
        $applicationid = $request->get('applicationId');
        $establishcode = $request->session()->get('EstablishCode');     
       //  print_r($applicationid);
        $judgementDetails = $this->Judgement->getAllJudgementByApplNo($applicationid,$establishcode); 
       if (count($judgementDetails)==0)
        {  
          return response()->json([
                'status' => "fail",
                  'message' => "Judgement doesnot exists "
                ]);
        }else{ 
         
           echo json_encode($judgementDetails);
       
       }
    }
 public function getAllJudgementByApplNoCCA(Request $request)
    {
      $request->validate([
                'applicationId' => array(
            'required',
            'regex:/^[0-9a-zA-Z\/]+$/'
          ),
          ]);
        $applicationid = $request->get('applicationId');
        $establishcode = $request->session()->get('EstablishCode');
       //  print_r($applicationid);
        $judgementDetails = DB::SELECT("SELECT * from judgement where applicationid='$applicationid'
                and establishcode='$establishcode' and verified_by is not null");
       if (count($judgementDetails)==0)
        {
          return response()->json([
                'status' => "fail",
                  'message' => "Judgement doesnot exists or it is not verified "
                ]);
        }else{

           echo json_encode($judgementDetails);

       }
    }

    public static function getJudgementByApplNo(Request $request)
   {
     $request->validate([
               'applicationId' => array(
           'required',
           'regex:/^[0-9a-zA-Z\/]+$/'
         ),
     //    'applyear' => 'required|numeric|max:2030|min:1950',
        ]);
       $applicationid = $request->get('applicationId');
       $establishcode =$request->session()->get('EstablishCode');

      $var=explode('/',$applicationid);
   $appltypeshort=$var[0];
   $appnum1= $var[1];
   $applyear= $var[2];

   $applications1 = db::table('applicationtype')->select('appltypecode')->where('appltypeshort', $appltypeshort)->get();
   $appltypecode = $applications1[0]->appltypecode;
   //$mainapplId= DB::select("select applicationid from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");

  //$data['applicationNo'] = $request->input('applicationNo');
 $application= DB::select("select applicationid from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");
 if($application==null)
 {
   $application=DB::SELECT("select applicationid from applicationdisposed where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");
 }
 if(count($application)==0)
     {
       return response()->json([
             'status' => "fail",
               'message' => "Judgement doesnot exists "
             ]);
     }
     else{
      if($application[0]->applicationid != $applicationid)
      {
      // $find_appnum="";
      $applicationid = $application[0]->applicationid;
      }
     else
      {
      $applicationid = $application[0]->applicationid;
      }

 $mainapplicationid=DB::SELECT("SELECT c.applicationid from applicationsummary1 as ap
 inner join connected1  c on ap.applicationid=c.conapplid
 where ap.applicationid='$applicationid'");

 if($mainapplicationid!=null)
 {
 $applicationid=$mainapplicationid[0]->applicationid;
 }

        $judgementDetails = DB::table('applicationdisposedsummary')
                ->join('judgement', 'judgement.applicationid', '=', 'applicationdisposedsummary.applicationid')
               ->select('applicationdisposedsummary.*','judgement.*')
               ->where('applicationdisposedsummary.applicationid','=',$applicationid)
               ->where('establishcode',$establishcode)->get();

   if (count($judgementDetails)==0)
       {
         return response()->json([
               'status' => "fail",
                 'message' => "Judgement doesnot exists "
               ]);
       }else{
        $enteredfrom=$judgementDetails[0]->enteredfrom;
          if($enteredfrom == "Legacy"){
          echo json_encode($judgementDetails);
        }else{
          $data['Judgement'] = DB::table('applicationsummary1')
               ->join('judgement', 'judgement.applicationid', '=', 'applicationsummary1.applicationid')
                ->join('applicationdisposedsummary', 'applicationdisposedsummary.applicationid', '=', 'judgement.applicationid')
        ->select('applicationsummary1.*','judgement.*','applicationdisposedsummary.enteredfrom')
               ->where('applicationsummary1.applicationid','=',$applicationid)
               ->get();
       echo json_encode($data['Judgement']);
     }
      }
     }
   }


 public function deletejudgementfunction(Request $request){
$applicationid =   $_POST['applicationid'];
$judgementdate=    $_POST['judgementdate'];
$destinationpath=DB::SELECT("SELECT  * from judgement where applicationid='$applicationid' and judgementdate='$judgementdate'");
$user = $request->session()->get('userName');
$userlevel=DB::SELECT("SELECT userlevel from userdetails where userid='$user'");
if($userlevel!=null)
{
$userlevel=$userlevel[0]->userlevel;
}
else{
  $userlevel="";
}

 if($destinationpath!=null)
      {
        $judgementpath=$destinationpath[0]->judgement_path;
        $verified_by=$destinationpath[0]->verified_by;
      }
      else{
        return response()->json([
                        'status' => "error",
                        'message' => "Judgement  Does not exist or it is already deleted",
                        ]);
        }
      $path=$this->path;
    if($verified_by!=null){
      if($userlevel=='5')
      {
        if (file_exists($path.'/'.$judgementpath)) {
          File::delete($path.'/'.$judgementpath);
          DB::SELECT("DELETE from judgement where applicationid='$applicationid' and judgementdate='$judgementdate'");
          return response()->json([
                          'status' => "success",
                          'message' => "Judgement Deleted Successfully",
                          ]);

   } else {
     return response()->json([
                     'status' => "error",
                     'message' => "PDF does not exist in Database",
                     ]);
   }

     }
  else{
    return response()->json([
                    'status' => "error",
                    'message' => "You dont have privileage to delete verified judgement.Please Contact Computer Section",
                    ]);
  }
}
else{
        if (file_exists($path.'/'.$judgementpath)) {
           File::delete($path.'/'.$judgementpath);
          DB::SELECT("DELETE from judgement where applicationid='$applicationid' and judgementdate='$judgementdate'");
            return response()->json([
                                      'status' => "success",
                                      'message' => "Judgement Deleted Successfully",
                                      ]);

                } else {
                 return response()->json([
                                 'status' => "error",
                                 'message' => "PDF does not exist in Database",
                                 ]);
                }


}


  //$value = DB::table('applrelief')->where('judgementdate',$_POST['judgementdate'])->where('applicationid',$_POST['applicationid'])->delete()
}


public function DownloadJudgement_delete(Request $request)
         {
              $request->validate([
                  'applicationid' => 'required',
              ]);
          $temp =  explode('_', trim($_GET['applicationid']));
          $applicationid = $temp[0];
          $judgementdate = $temp[1];

          $judgementdata =   DB::table('judgement')->select('judgement.*')
                     ->where('applicationid','=',$applicationid)
                     ->where('judgementdate','=',$judgementdate)
                     ->get();

          $judgement_path=$judgementdata[0]->judgement_path;
         
          $path=$this->path.'/'.$judgement_path;
         
              $fileName=$applicationid.'.pdf';
               $main_url =$path;
               header("Content-Type: application/pdf");
               header("Content-disposition:attachment; filename=$fileName");
               readfile($main_url);

         }

   public function downloadJudgementByDate(Request $request)
    {
     /*$request->validate([
                'applicationid' => array(
            'required',
            'regex:/^[0-9a-zA-Z_-\/]+$/'
          ),
              ]);*/
        $request->validate([
             'applicationid' => 'required',              
         ]); 
     $temp =  explode('_', trim($_GET['applicationid']));
     $applicationid = $temp[0];
     $judgementdate = $temp[1];
       print_r($judgementdate);
     $judgementdata = $this->Judgement->getJudgementDtlsByDate($applicationid,$judgementdate);
     $judgement_path=$judgementdata[0]->judgement_path;
     $path=$this->path.'/'.$judgement_path;
     return response()->download($path);
            
    } 

  public function uploadjudgements(Request $request)
     {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        return view('Judgement.uploadjudgements',$data)->with('user',$request->session()->get('userName'));
      
     }  

     public function showVerifyJudgements(Request $request)
     {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        return view('Judgement.verifyJudgements',$data)->with('user',$request->session()->get('userName'));
       }  


    public function Causetitlegeneration(Request $request){
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
         return view('Judgement.Causetitlegeneration',$data)->with('user',$request->session()->get('userName'));
}


public  function Causetitlegenerate(Request $request)
    {

$request->validate([
        'applTypeName' => 'required',
    'applicationId' => 'required'
          ]);
       $establishcode = $request->session()->get('EstablishCode');
$data['causelisttitle']=DB::table('establishment')->select('causelist_title')->where('establishcode',$establishcode)->get()[0];
  $causelisttitle=$data['causelisttitle'];

        $arr=explode('-',$request->input('applTypeName'));
        $applicationid=$arr[1].'/'.$request->applicationId;
        $ordertemplate=$request->ordertemplate;

        $applicationdisposeddtls =  $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);

$judgementdate='';
$judgename ='';
$judge = '';
$judgedesig = '';
//print_r('$applicationdisposeddtls=='.$applicationdisposeddtls);
if($applicationdisposeddtls!='')
{
$judgementdate=$applicationdisposeddtls[0]->disposeddate;
         $judgementdate =  date("jS", strtotime($judgementdate)) . " DAY OF" .strtoupper(date(" F Y ", strtotime($judgementdate)))  ;
        if($applicationdisposeddtls[0]->benchcode==null)
       {
       $benchcode= DB::SELECT("SELECT benchcode from dailyhearing where applicationid='$applicationid' and disposeddate is not null order by hearingdate DESC LIMIT 1")[0]->benchcode;
        $benchjudge = DB::select("select * from benchjudgeview where benchcode = ". $benchcode );
       }
   else{
       $benchjudge = DB::select("select * from benchjudgeview where benchcode = ". $applicationdisposeddtls[0]->benchcode );
       }
         $judgename ="";
         if ($benchjudge[0]->judgescount == 1)
         {
        $judgename = $benchjudge[0]->judgename.'  ,'.$benchjudge[0]->judgedesigname;
         } else {
         for ($i=0;$i<$benchjudge[0]->judgescount;$i++)
         {
         if($i < $benchjudge[0]->judgescount -1){
           $judgename = $judgename."\n" .$benchjudge[$i]->judgename.",".$benchjudge[$i]->judgedesigname."\n\n AND";
      }
         else{
         $judgename = $judgename."\n\n" .$benchjudge[$i]->judgename.",".$benchjudge[$i]->judgedesigname;
}
         /*if($i < $benchjudge[0]->judgescount -1){
          $and="AND";
          }
          dd($judgename."\n".$and);  */
//          dd($judgename);
         }
         }
        // dd($and);
         $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($judgename,1));
        // dd($dd);
         $judgename=  '<w:br />'.$dd.'<w:br />';
         $judge = $benchjudge[0]->judgename;
         $judgedesig = $benchjudge[0]->judgedesigname;
         $user = $request->session()->get('userName');


}

  $application = $this->case->getApplicationId($applicationid,$user='');

     $appltypeshort=$application[0]->appltypeshort;
     $apptypedesc=DB::SELECT("SELECT appltypedesc from applicationtype where appltypeshort='$appltypeshort'");
//dd($apptypedesc);
if($apptypedesc[0]->appltypedesc=='Original Application')
{
  $apptypedesc='Application Number ';
}
else{
  $apptypedesc=$apptypedesc[0]->appltypedesc;
}
$connectedapplications=DB::SELECT("SELECT * from public.connecetdappldtls where applicationid='$applicationid' ");
$count_connect=count($connectedapplications);
if($connectedapplications!=null){
    for($i=0;$i<count($connectedapplications);$i++)
    {

     $conapplid[]=$connectedapplications[$i]->connapplno;

    }
    if($conapplid){
      if ($count_connect == 1)
      {
     $conapplid_1 = $conapplid;
      } else {
      for ($i=0;$i<$count_connect;$i++)
      {
      if($i < $count_connect -1){
        $conapplid_1[] = $conapplid[$i]." , ";
       }
      else{
      $conapplid_1[] = $conapplid[$i];
        }

      }
       }


  $cw="";
       for($i=0; $i<$count_connect;  $i++){
            $dd = preg_replace('/[\n]/','<w:br />',$conapplid_1[$i]);
            $cw.=  $dd;
      }
    }

$cw='C/W'."\n\n".$cw;
$cw=preg_replace('/[\n]/','<w:br />',$cw);
}
else{
  $cw="";
}
   $applicationo= DB::SELECT("SELECT applicationo from applicationsummary1 where  applicationid='$applicationid'");
    $applicant_details = DB::select("select * from causetitleapplicant where applicationid = '". $applicationid ."'");
    $respondent_details = DB::select("select * from causetitlerespondent where applicationid = '". $applicationid ."'");

   $count_a = count($applicant_details);
   $count_r = count($respondent_details);

                     $applicant = "";
                     $respondent = "";
 $applicantadvocate = $applicant_details[0]->advocatename;
  if($applicant_details){
                           for($i=0; $i<$count_a;  $i++){
                                $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars(strtoupper($applicant_details[$i]->petcausetitle),1));
                              $applicant.=  '<w:br /><w:br />'.strtoupper($applicant_details[$i]->applicantsrno). ' . '.$dd.'<w:br /><w:br />';
                         }
                         if($applicationo!=null){
                          if($connectedapplications!=null){
                          $replace=htmlspecialchars("<B>BETWEEN</B>");}
                          else{
                            $replace=htmlspecialchars("BETWEEN");
                          }
                          $applicationo=$applicationo[0]->applicationo;
                          $applicant=$applicationo.'<w:br /><w:br /><w:br />'.$replace.'<w:br /><w:br />'.$applicant;

                         }
                       }

 $respondantadvocate = $respondent_details[0]->advocatename;
 if($respondent_details){
                       for($j=0; $j<$count_r;  $j++){
  $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars(strtoupper($respondent_details[$j]->rescausetitle),1));

  $respondent.=  '<w:br /><w:br />'. strtoupper($respondent_details[$j]->respondsrno) .' . '.$dd.'<w:br /><w:br />';

 }
            }
        $applicantadvocate=  strtoupper($applicantadvocate);
        $respondantadvocate=  strtoupper($respondantadvocate);

if($connectedapplications!=null)
{
  $conrespondent=[];
   $conapplicantadvocate=[];
   $conrespondantadvocate=[];
   for($k=0;$k<count($connectedapplications);$k++)
        {
              $conapplid=        $connectedapplications[$k]->conapplid;
            $applicationo= DB::SELECT("SELECT applicationo from applicationsummary1 where  applicationid='$conapplid'");
            $applicant_details = DB::select("select * from causetitleapplicant where applicationid = '". $conapplid ."'");
            $respondent_details = DB::select("select * from causetitlerespondent where applicationid = '". $conapplid ."'");

           $count_a = count($applicant_details);
           $count_r = count($respondent_details);

                             $applicant1="";
                             $conrespondent1 = "";

                             $conapplicantadvocate1="";
                             $conrespondantadvocate1="";
         $applicantadvocate = $applicant_details[0]->advocatename;
         $respondantadvocate = $respondent_details[0]->advocatename;

          if($applicant_details){
                                   for($i=0; $i<$count_a;  $i++){
                                        $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars(strtoupper($applicant_details[$i]->petcausetitle),1));
                                      $applicant1.=  '<w:br /><w:br />'.strtoupper($applicant_details[$i]->applicantsrno). ' . '.$dd.'<w:br /><w:br />';
                                 }

                                 if($respondent_details){
                                                       for($j=0; $j<$count_r;  $j++){
                                  $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars(strtoupper($respondent_details[$j]->rescausetitle),1));

                                  $conrespondent1.=  '<w:br /><w:br />'. strtoupper($respondent_details[$j]->respondsrno) .' . '.$dd.'<w:br /><w:br />';

                                 }
 

//                                  $conrespondent[]=$conrespondent1;
                                            }

                                                              for($l=0;$l<1;$l++){
                                                            $conapplicantadvocate1.=  strtoupper($applicantadvocate);
                                                            $conrespondantadvocate1.=  strtoupper($respondantadvocate);
                                                          }

                                          //  $conapplicantadvocate[]=$conapplicantadvocate1;
                                        //    $conrespondantadvocate[]=$conrespondantadvocate1;
                                 if($applicationo!=null){

                                  $repl=htmlspecialchars("<B>BETWEEN</B>");
                                  $advocateapp=htmlspecialchars("<B>(BY SRI  ,".$conapplicantadvocate1." ADVOCATE FOR APPLICANT)</B>");
                                  $advocateresp=htmlspecialchars("<B>( ".$conrespondantadvocate1." )</B>");
                                  $divider=htmlspecialchars("<B>--AND--</B>");
                                  $app=htmlspecialchars("<B>                                                                                                                                             .... APPLICANT</B>");
                                  $resp=htmlspecialchars("<B>                                                                                                                                            .... RESPONDENT</B>");
                                  $applicationo=$applicationo[0]->applicationo;
                                  $end='************************************************************';
                                  $conapplicant[]= '<w:br /><w:br />'.$end.'<w:br /><w:br /><w:br /><w:br />'.$applicationo.'<w:br /><w:br /><w:br />'.$repl.'<w:br /><w:br />'.$applicant1.'<w:br /><w:br />'.$app.'<w:br /><w:br />'.$advocateapp.'<w:br/><w:br/><w:br/>'.$divider.'<w:br/><w:br/>'.$conrespondent1.'<w:br/><w:br/>'.$resp.'<w:br/><w:br/>'.$advocateresp;

                                 }

                               }


        }
        $path= "causetitleconn";
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
        $my_template->setValue("cw",  $cw);
        $my_template->setValue("judgementdate",  $judgementdate);
        $my_template->setValue("judgename",$judgename);
        $my_template->setValue("TYPENAME", $apptypedesc);
        $my_template->setValue("APPLICATIONNO", $application[0]->applicationsrno);
        $my_template->setValue("APPLICATIONYEAR", $application[0]->applicationyear);
        $my_template->setValue("applicant_details",  $applicant);
        $my_template->setValue("applicantadvocate",$applicantadvocate);
        $my_template->setValue("respondent_details",$respondent);
        $my_template->setValue("respondantadvocate",$respondantadvocate);
        $my_template->setValue("causelisttitle",$causelisttitle->causelist_title);
        $my_template->setValue("judge",$judge);
        $my_template->setValue("judgedesig",$judgedesig);
        $tags = $my_template->getVariables();
        $tag1=$tags[0];

         $conapplicant= implode("",$conapplicant);

         $my_template->setValue($tag1, $conapplicant);


       $my_template->saveAs(storage_path("causetitle.docx"));
       $file= storage_path("causetitle.docx");
       $headers = array(
                        'Content-Type: application/msword',
                         );


       return response()->download($file,"causetitle.docx", $headers)->deleteFileAfterSend(true);


}
else{
         $path= "causetitle";
         $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
         $my_template->setValue("judgementdate",  $judgementdate);
         $my_template->setValue("judgename",$judgename);
         $my_template->setValue("TYPENAME", $apptypedesc);
         $my_template->setValue("APPLICATIONNO", $application[0]->applicationsrno);
         $my_template->setValue("APPLICATIONYEAR", $application[0]->applicationyear);
         $my_template->setValue("applicant_details",  $applicant);
         $my_template->setValue("applicantadvocate",$applicantadvocate);
         $my_template->setValue("respondent_details",$respondent);
         $my_template->setValue("respondantadvocate",$respondantadvocate);
         $my_template->setValue("causelisttitle",$causelisttitle->causelist_title);
         $my_template->setValue("judge",$judge);
         $my_template->setValue("judgedesig",$judgedesig);
         $my_template->saveAs(storage_path("causetitle.docx"));
         $file= storage_path("causetitle.docx");
         $headers = array(
                         'Content-Type: application/msword',
                          );
        return response()->download($file,"causetitle.docx", $headers)->deleteFileAfterSend(true);
  }
}




 
public function SearchJudgements(Request $request)
     {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        return view('Judgement.SearchJudgements',$data)->with('user',$request->session()->get('userName'));
      
     }  

public function DownloadJudgements(Request $request)
    {
       
       $request->validate([
                'applTypeName' => array(
						'required',
						'regex:/^[0-9a-zA-Z-.\/]+$/',
						'max:20'
					),  
                'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z.\/]+$/',
						'max:20'
					),    
             ]);  
       $establishcode = $request->session()->get('EstablishCode');
       $applicationNo = $request->input('applicationId');
       $applicationType = explode("-",$request->input('applTypeName'));
       $applicationid = $applicationType[1].'/'.$applicationNo;      
       $appcount = $this->Judgement->getJudgementExist($applicationid,$establishcode,'');
       $appcount= $appcount[0]->judgementcount;
           //print_r($appcount);
       if($appcount==0)
       {       
          return back() ->with('error','Judgement doesnot exists');
         }else{         
            $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
            $judgement_path=$judgementDetails[0]->judgement_path;
            $dscjudgement_path=$judgementDetails[0]->dscjudgement_path;         
           
            if($dscjudgement_path!=null)
            {
            $path=$this->path_dsc.'/'.$dscjudgement_path;
            }
            else{
            $path=$this->path.'/'.$judgement_path;
            }
  

          //$path=$this->path.'/'.$judgement_path;
            // $path=$this->pathdownload.'/'.$judgement_path;
           //  $path= "http://ksat.kar.nic.in:8080/kat_act.pdf";
		 $fileName=$applicationid.'.pdf';
          $main_url =$path;
          header("Content-disposition:attachment; filename=$fileName");
          readfile($main_url);
       }     
 } 



  public function  getJudgementDetails (Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),                 
              ]);
		
        $establishcode = $request->session()->get('EstablishCode');
        $applicationid = $request->get('applicationid');
        $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
        if (count($judgementDetails)==0)
        {  
          return response()->json([
                'status' => "fail",
                  'message' => "Judgement doesnot exists "
                ]);
        }else{         
           $verified_by=$judgementDetails[0]->verified_by;
           if($verified_by != ""){
          return response()->json([
                'status' => "fail",
                  'message' => "Judgement already Verified "
                ]);
           }else{
               $data['applicationDetails'] = $this->Judgement->getJudgementDetailsByapplId($applicationid,$establishcode); 
           //   $data['applicationDetails'] = $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);

             echo json_encode($data['applicationDetails']); 
           }
        }
     }


  public function verifyJudgements(Request $request)
    {
		$request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),                 
              ]);
         $establishcode = $request->session()->get('EstablishCode');
         $applicationid = $request->get('applicationId');
         $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
         $judgementdate=$judgementDetails[0]->judgementdate;
           
         $judgementStore['verified_by'] = $request->session()->get('userName');;
         $judgementStore['verifieddate'] = date('Y-m-d');
		 
		 $useractivitydtls['applicationid_receiptno'] = $applicationid;
		 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		 $useractivitydtls['activity'] ='Verify Judgements' ;
		 $useractivitydtls['userid'] = $request->session()->get('username');
		 $useractivitydtls['establishcode'] = $establishcode ;
	     $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
         if($this->Judgement->updateJudgementDetails($judgementStore,$judgementdate,$applicationid))
          {			
			   $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
            return response()->json([
                'status' => "sucess",
                  'message' => "Judgement Verified Successfully"
                ]);
          }
          else
          {
            return response()->json([
                'status' => "fail",
                 'message' => "Something went wrong, Failed to verify Judgement"
                ]);
          }  
      }


 public function savejudgements(Request $request)
    {
		
		 $request->validate([
                'applTypeName' => array(
						'required',
						'regex:/^[0-9a-zA-Z-.\/]+$/',
						'max:20'
					),  
                'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z-.\/]+$/',
						'max:20'
					),   
	    'judgement' =>  'required|mimes:pdf|max:100000',
         'applnJudgementDate' => 'required|date',
             ]); 
        
         $judgementdate= date('Y-m-d',strtotime($request->input('applnJudgementDate')));
         $establishcode = $request->session()->get('EstablishCode');
         $applType =  explode('-',$request->get('applTypeName'));
         $applicationid = $applType[1].'/'.$request->get('applicationId');
         $appcount = $this->Judgement->getJudgementExist($applicationid,$establishcode,$judgementdate);
         $appcount= $appcount[0]->judgementcount;
        if($appcount>0) 
           {
            return back() ->with('error','Judgement already uploaded .');
           }
         else{

           $disposedApplication = $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);
           $benchcode= $disposedApplication[0]->benchcode;
           $fileName = str_replace("/","_",$applicationid);
           $filejudgementdate= $request->input('applnJudgementDate');
           $fileName = $fileName.'_'.$filejudgementdate.'.'.request()->judgement->getClientOriginalExtension();
            //to upload in upload folder in storage\app\upload
           //  $file = $request->imgUpload1->storeAs('upload',$fileName);
           $judgementyear = date('Y',strtotime($request->input('applnJudgementDate')));
		   
		  // $path=$this->pathdownload.'/'.$judgementyear;
           $path=$this->path.'/'.$judgementyear;
           if (!file_exists($path)) {
                mkdir($path);
            }
            $file = $request->judgement->move($path, $fileName);
            $mpdf = new \Mpdf\Mpdf();
            $pagecount = $mpdf->SetSourceFile($path.'/'.$fileName);
            $arr=explode('-',$request->input('applTypeName'));
            $appltypeshort = $arr[1];
            $judgementStore['applicationid']=$applicationid;
            $judgementStore['benchcode']=$benchcode;
            $judgementStore['judgement_path']=$judgementyear.'/'.$fileName;
            $judgementStore['judgementdate']= date('Y-m-d',strtotime($request->input('applnJudgementDate')));
            $judgementStore['pagecount']=$pagecount;
            $judgementStore['establishcode']=$establishcode;
            $judgementStore['createdby']= $request->session()->get('userName');
            $judgementStore['createdon']= date('Y-m-d H:i:s') ;
    

            $this->Judgement->addJudgement($judgementStore);
			
			$useractivitydtls['applicationid_receiptno'] = $applicationid;
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Upload Judgements' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $establishcode ;
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			  $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
            return back() ->with('success','Judgement uploaded Successfully.');
        }
       }

public function issuefreecopy(Request $request)
     {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['deliverymode'] = $this->Judgement->getJudgementDeliveryMode();
       
        return view('Judgement.issuefreecopy',$data)->with('user',$request->session()->get('userName'));
      
     }  

public function getFreeCopyApplRespondantStatus(Request $request)
    {
		$request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),                 
              ]);
        $applicationid =  $_POST['applicationId'];
        $flag = $_POST['flag'];
        $data['filledby'] = $this->Judgement->getFreeCopyApplRespondantStatus($applicationid,$flag);
       echo json_encode($data['filledby']);
    }

public function saveFreeCopyStatus(Request $request)
    {
		$request->validate([
		'modal_appl_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
		'modal_srno'  => 'numeric'
              ]);
          $applicationid = $_POST['modal_appl_id'];
          $applsrno = $_POST['modal_srno'];
          $judgementdelivery['applicationid'] = $applicationid;
          $judgementdelivery['partysrno'] =  $applsrno;
          $judgementdelivery['petitionerrespondent'] = $_POST['modal_flag'];
          $judgementdelivery['deliveryon'] = date('Y-m-d',strtotime($_POST['deliverydate']));
          $judgementdelivery['deliverycode'] = $_POST['deliverymode']; 
          $judgementdelivery['partyname'] = $_POST['partyname']; 
          $judgementdelivery['remarks'] = $_POST['remarks'];  
         
			 $useractivitydtls['applicationid_receiptno'] = $applicationid;
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Issue Judgement Free Copy' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $establishcode ;
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		 if($this->Judgement->addJudgementDelivery($judgementdelivery))
          {
		    $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
            return response()->json([
                'status' => "sucess"
               // 'message' => "Application Updated Successfully"

                ]);
          }
          else
          {
            return response()->json([
                'status' => "fail"
                //'message' => "Application Updated Successfully"

                ]);
          }      

    }


}
