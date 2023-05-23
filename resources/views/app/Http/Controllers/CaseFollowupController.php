<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\CaseFollowUpModel as CaseFollowUpModel;
use App\IADocument as IADocumentModel;
use App\IANature;
use App\CaseManagementModel;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\UserActivityModel;

class CaseFollowupController extends Controller
{
 
  public function __construct()
	{
     $this->case = new CaseManagementModel();
     $this->caseFollowUpModel = new CaseFollowUpModel();
     $this->IANature = new IANature();
	 $this->UserActivityModel = new UserActivityModel();
    }
	

  
  public function officenote(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
         return view('casefollowup.officenote',$data)->with('user',$request->session()->get('userName'));
      
     }  
     
  public function getofficenoteDetails(request $request)
    {

	    $request->validate([
		'application_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
               
              ]);

       $applicationId = $_POST['application_id'];
       $data['officenoteDetails'] = CaseFollowUpModel::getofficenoteDetails($applicationId,'','');
       echo json_encode($data['officenoteDetails']);
    }


public  function saveofficenote(Request $request)
{
       $validator = \Validator::make($request->all(), [
          'officenote' => 'required|max:2000',
          'officenoteDate' => 'required|date',
        ]);
         
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
  
         $applType =  explode('-',$request->get('applTypeName'));
         $applicationid = $applType[1].'/'.$request->get('applicationId');
         $establishcode = $request->session()->get('EstablishCode');
         $officenoteStore['applicationid']=$applicationid;
         $officenoteStore['officenote']=$request->input('officenote');
         $officenoteStore['officenotedate']= date('Y-m-d',strtotime($request->input('officenoteDate')));
		 
		 $useractivitydtls['applicationid_receiptno'] =$applicationid;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Add Office Note' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
        if( $this->caseFollowUpModel->addOfficeNote($officenoteStore))
                {
					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);		  
                    return response()->json([
                        'status' => "sucess",
                        'message' => "Office Note Added Successfully."

                        ]);
                }
                else
                {
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"

                    ]);
                } 
          }}


public  function generateordersheet(Request $request)
{       
     $request->validate([
          'applId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
		  'fromdate' => 'date', 
	      'todate' => 'date|after_or_equal:fromdate'	
              ]);


        $applicationId=$request->input('applId');
       // $fromDate='';
       // $toDate='';
       $fromDate=date('Y-m-d',strtotime($request->input('fromDate')));
       $toDate=date('Y-m-d',strtotime($request->input('toDate')));
     // print_r($fromDate);
    //  print_r($toDate);
       $officenoteDetails = CaseFollowUpModel::getofficenoteDetails($applicationId,$fromDate,$toDate);
    //   print_r("mini");
		$count_a = count($officenoteDetails);       
	   $officenotes = "";
         $courtdirection = ""; 
	   if($officenoteDetails){
           for($i=0; $i<$count_a;  $i++){
			   $officenoteDate = explode("-",$officenoteDetails[$i]->date);
				$yy = $officenoteDate[0];
				$mm = $officenoteDate[1];
				$dd = $officenoteDate[2];
				$officenoteDate = $dd.'-'.$mm.'-'.$yy;
			if($officenoteDetails[$i]->officenote != '')			   
			{				
 			  $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->officenote,1));
              $officenotes.=  '<w:br /><w:br />'.$officenoteDate. '<w:br />'.$dd.'<w:br />';
		   }
		   else if ($officenoteDetails[$i]->courtdirection != ''){
			   $cd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->courtdirection,1));
               $courtdirection.=  '<w:br /><w:br />'.$officenoteDate. '<w:br />'.$cd.'<w:br />';
		   
		   }
		   }
                      }
		$data['applicantDetails'] = $this->case->getTopApplicantDetails($applicationId)[0];
        $data['respondantDetails'] = $this->case->getTopRespondantDetails($applicationId)[0];
        $data['applicantadvocateDetails'] = $this->case->getAdvDetails($data['applicantDetails']->advocateregno)[0];

        $data['respondantadvocateDetails'] = $this->case->getAdvDetails($data['respondantDetails']->advocateregno)[0];

        $applicantname= $data['applicantDetails']->applicantname;
       $applicantadvocate=$data['applicantadvocateDetails'] ->advocatename;  
        $respondantname=$data['respondantDetails']->respondname;
        $respondantadvocate= $data['respondantadvocateDetails'] ->advocatename;          

       $path= "ordersheet";
       $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
        $my_template->setValue("applicationId",  $applicationId);
        $my_template->setValue("officenotes",$officenotes); 
        $my_template->setValue("courtdirection",$courtdirection); 
       $my_template->setValue("applicantname",$applicantname);
       $my_template->setValue("applicantadvocate",  $applicantadvocate);
       $my_template->setValue("respondantname",$respondantname); 
       $my_template->setValue("respondantadvocate",$respondantadvocate);
        $my_template->saveAs(storage_path("order.docx"));
        $file= storage_path("order.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);
  }
     
public function viewordergenerationform(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
         return view('casefollowup.ordergeneration',$data)->with('user',$request->session()->get('userName'));
      
     }  

public  function ordergenerate(Request $request)
{
      $arr=explode('-',$request->input('applTypeName'));
        $applicationId=$arr[1].'/'.$request->applicationId;
        $ordertemplate=$request->ordertemplate;
        $data['applicantDetails'] = $this->case->getTopApplicantDetails($applicationId)[0];
        $data['respondantDetails'] = $this->case->getTopRespondantDetails($applicationId)[0];
        $data['applicantadvocateDetails'] = $this->case->getAdvDetails($data['applicantDetails']->advocateregno)[0];

        $data['respondantadvocateDetails'] = $this->case->getAdvDetails($data['respondantDetails']->advocateregno)[0];

        $applicantname= $data['applicantDetails']->applicantname;
        $applicantadvocate=$data['applicantadvocateDetails'] ->advocatename;  
        $respondantname=$data['respondantDetails']->respondname;
        $respondantadvocate= $data['respondantadvocateDetails'] ->advocatename;          

       $path= "ordersheet";
       $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
        $my_template->setValue("applicationId",  $request->applicationId);
        $my_template->setValue("ordertemplate",$ordertemplate); 
        $my_template->setValue("applicantname",$applicantname);
        $my_template->setValue("applicantadvocate",  $applicantadvocate);
        $my_template->setValue("respondantname",$respondantname); 
        $my_template->setValue("respondantadvocate",$respondantadvocate);
        $my_template->saveAs(storage_path("order.docx"));
        $file= storage_path("order.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);
}

public function viewgeneratenotice(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
         
         $data['OrderType'] = $this->IANature->getOrderType();
         $data['benchjudge'] = $this->IANature->getbenchjudge();
         $data['Benches'] =  $this->IANature->getBenches();
        return view('casefollowup.generatenotice',$data)->with('user',$request->session()->get('userName'));
      
     }  




public  function generatenoticedocument(Request $request)
{     

  $request->validate([
            'applTypeName' => 'required',
          'applicationId' => 'required|max:20',
        //  'respondantDetails' => 'required',
          'noticetype' => 'required'
            ]);  
 
      $arr=explode('-',$request->input('applTypeName'));
      $applicationId=$arr[1].'/'.$request->applicationId;
      $noticetypecode = $request->input('noticetype');
      $respondantSrNo = $request->input('respondantDetails');
      $noticetypeDetails = $this->IANature->getNoticeTypeByID($noticetypecode);
      if($noticetypeDetails[0]->template_name == ""){
            return redirect()->back()->with('response-error','No template found');
           }else{
               $path = $noticetypeDetails[0]->template_name;
               $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/notices/'.$path.'.docx'));
           }

       $application = $this->case->getApplicationId($applicationId,$user='');
	   $applicant_details = DB::select("select * from causetitleapplicant where applicationid = '". $applicationId ."'");
       $respondent_details = DB::select("select * from causetitlerespondent where applicationid = '". $applicationId ."'");
      
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


        // $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/notices/'.$path.'.docx'));
     //    $my_template->setValue("TYPENAME", $application[0]->appltypedisplay);  
         $my_template->setValue("APPLICATIONNO", $application[0]->applicationsrno);
         $my_template->setValue("APPLICATIONYEAR", $application[0]->applicationyear);
         $my_template->setValue("applicant_details",  $applicant);
		 $my_template->setValue("applicantadvocate",$applicantadvocate);
         $my_template->setValue("respondent_details",$respondent); 
         $my_template->setValue("respondantadvocate",$respondantadvocate);
		
        //$my_template->saveAs(storage_path("notice.docx"));
		$my_template->saveAs(storage_path($path));
		
        //$file= storage_path("notice.docx");
		$file= storage_path($path);
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,$path.".docx", $headers)->deleteFileAfterSend(true);
  }
     





  public function getHearingDetailsByApplication(Request $request)
    {
		$request->validate([
         'applicationId' => 'required|max:20',
      
            ]);  
       $applicationId=$request->applicationId;
       $data['hearingDetails'] = IADocumentModel::getHearingDetailsByApplication($applicationId);
        $noticetype='';
       if (count($data['hearingDetails']) > 0)
      {
       $ordertypecode = $data['hearingDetails'][0] ->ordertypecode;
       $data['noticetype'] = $this->IANature->getNoticeType($ordertypecode);
       }
       echo json_encode(array($data['hearingDetails'], $data['noticetype']));
    //   echo json_encode($data['hearingDetails']); 
     }


}