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

class JudgementController extends Controller
{
  private $path ;
	
  public function __construct()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		//	echo 'This is a server using Windows!';
			  $this->path ="C:/Judgements";
		} else {
		//	echo 'This is a server not using Windows!';
			 $this->path ="/var/www/data/ksat/judgements";
		}
  //  $this->path ="C:/Judgements";
    $this->case = new CaseManagementModel();  
    $this->disposedApplication =  new DisposedApplicationModel();
    $this->Judgement =  new JudgementModel();
    $this->UserActivityModel = new UserActivityModel();
	//when accessing by httpserver
    //$this->pathdownload ="http://10.10.28.84:9000/judgements";
  }
	
public static function getJudgementApplicantDetails(Request $request)
    {      
	 $request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
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
       
        $arr=explode('-',$request->input('applTypeName'));
        $applicationid=$arr[1].'/'.$request->applicationId;
        $ordertemplate=$request->ordertemplate;

        $applicationdisposeddtls =  $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);

        $judgementdate=$applicationdisposeddtls[0]->disposeddate;        
        $judgementdate =  date("jS", strtotime($judgementdate)) . " Day Of" . date(" F Y ", strtotime($judgementdate))  ;
      
        $benchjudge = DB::select("select * from benchjudgeview where benchcode = ". $applicationdisposeddtls[0]->benchcode );
      
       $judgename ="";
       if ($benchjudge[0]->judgescount == 1)
       {
        $judgename = $benchjudge[0]->judgename.'  ,'.$benchjudge[0]->judgedesigname;
       } else {
       for ($i=0;$i<$benchjudge[0]->judgescount;$i++)
       {
         $judgename = $judgename."\n" .$benchjudge[$i]->judgename.'  ,'.$benchjudge[0]->judgedesigname;
       }
       }
       $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($judgename,1));
       $judgename=  '<w:br />'.$dd.'<w:br />'; 
		
		
	   $application = $this->case->getApplicationId($applicationid,$user='');
		
       $applicant_details = DB::select("select * from causetitleapplicant where applicationid = '". $applicationid ."'");
       $respondent_details = DB::select("select * from causetitlerespondent where applicationid = '". $applicationid ."'");
      
	  $count_a = count($applicant_details);
	  $count_r = count($respondent_details);
	   
                     $applicant = "";
                     $respondent = "";
					 $applicantadvocate = $applicant_details[0]->advocatename;
			if($applicant_details){
                           for($i=0; $i<$count_a;  $i++){                       
                              $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($applicant_details[$i]->applicant_address,1));
                              $applicant.=  '<w:br /><w:br />'.$applicant_details[$i]->applicantsrno. ' . '. htmlspecialchars($applicant_details[$i]->applicantname,1).'<w:br />'.$dd.'<w:br />';
                           }

                      }
		  $respondantadvocate = $respondent_details[0]->advocatename;
		  if($respondent_details){
                       for($j=0; $j<$count_r;  $j++){                         
				   $dd1 = preg_replace('/[\n]/','<w:br />',htmlspecialchars($respondent_details[$j]->respondent_address,1));
				   $respondent.=  '<w:br /><w:br />'. $respondent_details[$j]->respondsrno .' . '.htmlspecialchars($respondent_details[$j]->respondname,1).'<w:br />'.$dd1.'<w:br />';
			  }
            }

         $path= "causetitle";
         $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
         $my_template->setValue("judgementdate",  $judgementdate);
         $my_template->setValue("judgename",$judgename); 
         $my_template->setValue("TYPENAME", $application[0]->appltypedisplay);  
         $my_template->setValue("APPLICATIONNO", $application[0]->applicationsrno);
         $my_template->setValue("APPLICATIONYEAR", $application[0]->applicationyear);
         $my_template->setValue("applicant_details",  $applicant);
		 $my_template->setValue("applicantadvocate",$applicantadvocate);
         $my_template->setValue("respondent_details",$respondent); 
         $my_template->setValue("respondantadvocate",$respondantadvocate);
		$my_template->setValue("judge",$benchjudge[0]->judgename); 
		$my_template->setValue("judgedesig",$benchjudge[0]->judgedesigname); 
        $my_template->saveAs(storage_path("causetitle.docx"));
        $file= storage_path("causetitle.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"causetitle.docx", $headers)->deleteFileAfterSend(true);
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
						'regex:/^[0-9a-zA-Z-\/]+$/',
						'max:20'
					),  
                'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z\/]+$/',
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
            $path=$this->path.'/'.$judgement_path;
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
						'regex:/^[0-9a-zA-Z_\/]+$/',
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
						'regex:/^[0-9a-zA-Z_\/]+$/',
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
						'regex:/^[0-9a-zA-Z-\/]+$/',
						'max:20'
					),  
                'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z\/]+$/',
						'max:20'
					),   
	     'judgement' => 'required|mimes:pdf|max:20000',
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
						'regex:/^[0-9a-zA-Z_\/]+$/',
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
						'regex:/^[0-9a-zA-Z_\/]+$/',
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