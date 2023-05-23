<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\DisposedApplicationModel;
use App\IADocument as IADocumentModel;
use Session;
use App\ModuleAndOptions;//use model Module & Options
use App\IANature;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;




class CaseFollowupController extends Controller
{
 
  public function __construct()
	{
      $this->IANature = new IANature();
      $this->case = new CaseManagementModel();
      $this->module= new ModuleAndOptions();  
  }
	
public function viewordergenerationform(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
         $data['IANature'] =  $this->IANature->getIANature();
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
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);;
}

public function viewgeneratenotice(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
         $data['IANature'] =  $this->IANature->getIANature();
         $data['Status'] =  $this->IANature->getStatus();
         $data['purpose'] =  $this->IANature->getListPurpose();
         $data['OrderType'] =  $this->IANature->getOrderType();

         $data['benchjudge'] = $this->IANature->getbenchjudge();
         $data['Benches'] =  $this->IANature->getBenches();
        return view('casefollowup.generatenotice',$data)->with('user',$request->session()->get('userName'));
      
     }  

  public function getHearingDetailsByApplication(Request $request)
    {
       $applicationId=$request->applicationId;
       $data['hearingDetails'] = IADocumentModel::getHearingDetailsByApplication($applicationId);
       $ordertypecode = $data['hearingDetails'][0] ->ordertypecode;
       $noticetype = $this->IANature->getNoticeType($ordertypecode);
       echo json_encode(array($data['hearingDetails'], $noticetype));
    //   echo json_encode($data['hearingDetails']); 
     }


}