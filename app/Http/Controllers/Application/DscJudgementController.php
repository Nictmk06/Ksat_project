<?php
namespace App\Http\Controllers\Application;

 
//use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
use App\Http\Requests;
use PDF;
use FPDF;
use FPDI;
use setasign\Fpdi\Tfpdf;
//use setasign\fpdf;
use setasign\fpdi\src;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\DisposedApplicationModel;
use App\JudgementModel;
use App\UserActivityModel;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use App\Http\Controllers\waterController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DscJudgementController extends Controller
{
  private $path ;
	
  public function __construct()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		//	echo 'This is a server using Windows!';
			  $this->path ="C:/Judgements";
          $this->path1 ="C:/dscjudgement";
		} else {
		//	echo 'This is a server not using Windows!';
			 $this->path ="/var/www/data/ksat/judgements";
       $this->path1 ="/var/www/data/ksat/dscjudgement";
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
        return view('Judgement.Dscuploadjudgements',$data)->with('user',$request->session()->get('userName'));
      
     }  
  // ========================dsc validation function ==================================
      public function getdscvalidate(Request $request)
     {
  
             $json=array();
            
            
   request()->validate([
         'file'  => 'required|mimes:doc,docx,pdf,txt|max:40000',
       ]);
 
        if ($files = $request->file('file')) {
            
            //store file into document folder
          $applicationid=$request->input('applicationid');
           $fileName = str_replace("/","_",$applicationid);
           $filejudgementdate= $request->input('applnJudgementDate');
           $fileName = $fileName.'_'.$filejudgementdate.'.'.request()->file->getClientOriginalExtension();
            //to upload in upload folder in storage\app\upload
           //  $file = $request->imgUpload1->storeAs('upload',$fileName);
           $judgementyear = date('Y',strtotime($request->input('applnJudgementDate')));
       
     
           $msg=self::isStringInFile($request->file('file'), 'pkcs7.detached');
           
           $json[]=array(
               'msg' =>$msg
          );
           echo json_encode($json); 

     } 
}
     

//======================= get water mark PDF file =============================================

 public function getWatermark(Request $request)
     {
  
             $json=array();
            $a=new waterController();

            
   request()->validate([
         'file'  => 'required|mimes:doc,docx,pdf,txt|max:40000',
       ]);
 
       
          $filename=request()->file->getClientOriginalName();//.request()->file->getClientOriginalExtension();
      
          $judgementpath=$this->path;
    

          //PlaceWatermark($request);
           $msg=null;
         // print_r($request->file('file'));
         //$files=$request->file('file');
     if ($files = $request->file('file')) {

     // $msg=$a->PlaceWatermark($file['file'],$filename,$judgementpath) ;
      $msg=$a->PlaceWatermark($request->file('file'),$filename,$judgementpath,"0") ;
      }
    $json[]=array(
               'msg' =>$filename
          );
          // dd($msg);
          
           echo json_encode($json); 
    // } 
}
     
//======================= get water mark PDF file=================================


//============================= function cheking dsc string -================================= 
     public function isStringInFile($file,$string){
       //  print_r($file);
        $handle = fopen($file, 'r');
        $valid = FALSE; // init as false
        while (($buffer = fgets($handle)) !== false) {
            if (strpos($buffer, $string) !== false) {
                $valid = TRUE;
                break; // Once you find the string, you should break out the loop.
            }

        else
        {
          $valid = 0;
        }
        }
        fclose($handle);

        return $valid;

    }

//=================================== get dispose date using application id ================================

     public function getdisposedate(Request $request){
     //$user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
       $applicationid=$request->input('applicationid');

     
$json=array();

        //$applTypeName = $request->session()->get('applTypeName');
         
              $arr1=explode('-',$request->get('applTypeName'));
           $application=$arr1[1].'/'.$applicationid;
            $disposeddate=$this->case->getDisposedate($application);

       
 $json[]=array(
               'msg' =>   $disposeddate
              
          );
       
           echo json_encode($json); 

        
}


//==================== dispose date using application id ==============================


//==================release date using application id ==============================

/*public function getJudgementfiledetails(Request $request)
     {
       $establishcode = $request->session()->get('EstablishCode');
     
       $applicationid = $request->input('applicationid');

        $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
 $json=array();
foreach($judgementDetails as $data){
            $releasedt = $data->releasedate;
            $judgpath = $data->judgement_path;
            $dscjugpath = $data->dscjudgement_path;
            $acceptreject=$data->acceptreject;
       
             $json[]=array(
               'releasedate' =>   $releasedt,
                'judgement_path' =>   $judgpath,
                'path'=>$this->path,
                'path1'=>$this->path1,
                 'dscjudgement'=> $dscjugpath,
                   'acceptreject'=> $acceptreject,
                 
              
          );
     }
      

        
       
           echo json_encode($json); 
    
     } */ 



public function getJudgementfiledetails(Request $request)
     {
       $establishcode = $request->session()->get('EstablishCode');

       $applicationid = $request->input('applicationid');

       $applicationdisposeddtls =  $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);
       $disposeddate=$applicationdisposeddtls[0]->disposeddate;

       $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
       $judgementdate=$judgementDetails[0]->judgementdate;

        if($disposeddate==$judgementdate)
      {
 $json=array();
foreach($judgementDetails as $data){
            $releasedt = $data->releasedate;
            $judgpath = $data->judgement_path;
            $dscjugpath = $data->dscjudgement_path;
            $acceptreject=$data->acceptreject;

             $json[]=array(
               'releasedate' =>   $releasedt,
                'judgement_path' =>   $judgpath,
                'path'=>$this->path,
                'path1'=>$this->path1,
                 'dscjudgement'=> $dscjugpath,
                   'acceptreject'=> $acceptreject,


          );
     }




           echo json_encode($json);
    }

    else{
      $json=array();
      foreach($judgementDetails as $data){
                 $releasedt = $data->releasedate;
                 $judgpath = $data->judgement_path;
                 $dscjugpath = $data->dscjudgement_path;
                 $acceptreject=$data->acceptreject;

                  $json[]=array(
                    'releasedate' =>   $releasedt,
                     'judgement_path' =>   $judgpath,
                     'path'=>$this->path,
                     'path1'=>$this->path1,
                      'dscjudgement'=> $dscjugpath,
                        'acceptreject'=> 'R',


               );
          }




                echo json_encode($json);

       }
  }

//=========================================================== release date using application id ==============================



     public function showVerifyJudgements(Request $request)
     {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        return view('Judgement.dsc-verifyJudgements',$data)->with('user',$request->session()->get('userName'));
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
		
		$judgementdate='';
		$judgename ='';
		$judge = ''; 
		$judgedesig = '';
		//print_r('$applicationdisposeddtls=='.$applicationdisposeddtls);
		if($applicationdisposeddtls!='')
		 {
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
		   $judge = $benchjudge[0]->judgename;
		   $judgedesig = $benchjudge[0]->judgedesigname;
		}
		
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
		 $my_template->setValue("judge",$judge); 
	  	$my_template->setValue("judgedesig",$judgedesig); 
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
         $judgement_date = $request->input('applnJudgementDate'); 
        // dd($judgement_date);
        $fileName = str_replace("/","_",$applicationid);
      
        $j_date = date('Y-m-d',strtotime($judgement_date));
  $name=$fileName.'_'.$judgement_date;
 //dd($name);
       $appcount = $this->Judgement->getJudgementExist($applicationid,$establishcode,$j_date);
       $appcount= $appcount[0]->judgementcount;
           //dd($appcount);
       if($appcount==0)
       {       
          return back() ->with('error','Judgement doesnot exists');
         }else{

          if( $request->input('judgement_file')=="View Judgement"){
            $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
            $judgement_path=$judgementDetails[0]->judgement_path;
            $path=$this->path.'/'.$judgement_path;

            // $path=$this->pathdownload.'/'.$judgement_path;
           //  $path= "http://ksat.kar.nic.in:8080/kat_act.pdf";
		 $fileName=$name.'.pdf';
          $main_url =$path;
          header("Content-disposition:attachment; filename=$fileName");
          readfile($main_url);
        }else if( $request->input('water_mark_judgement_file')=="View water mark Judgement"){

            $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
            $judgement_path=$judgementDetails[0]->judgement_path;

              $judgementyear = date('Y',strtotime($request->input('applnJudgementDate')));
            $path=$this->path.'/'.$judgementyear.'/'.$name.'.pdf';

            // $path=$this->pathdownload.'/'.$judgement_path;
           //  $path= "http://ksat.kar.nic.in:8080/kat_act.pdf";
          $fileName='water_mark_'.$name.'.pdf';
          $main_url =$path;
          header("Content-disposition:attachment; filename=$fileName");
          readfile($main_url);
        }else if( $request->input('dsc_judgement_file')=="View digital signed Judgement"){
           $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
            $dscjudgement_path=$judgementDetails[0]->dscjudgement_path;
            $path=$this->path1.'/'.$dscjudgement_path;

            // $path=$this->pathdownload.'/'.$judgement_path;
           //  $path= "http://ksat.kar.nic.in:8080/kat_act.pdf";
     $fileName='dsc_'.$name.'.pdf';
          $main_url =$path;
          header("Content-disposition:attachment; filename=$fileName");
          readfile($main_url);
        }

        }

       }     
 //} 



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


           $acceptreject=$judgementDetails[0]->acceptreject;

           

           $data['applicationDetails'] = $this->Judgement->getJudgementDetailsByapplId($applicationid,$establishcode); 
           //   $data['applicationDetails'] = $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);

             echo json_encode($data['applicationDetails']); 
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
           $accept = $request->get('accept');

         $judgementDetails = $this->Judgement->getJudgementDetails($applicationid,$establishcode);
         $judgementdate=$judgementDetails[0]->judgementdate;
           
         $judgementStore['verified_by'] = $request->session()->get('userName');
         $judgementStore['verifieddate'] = date('Y-m-d');
         $judgementStore['acceptreject'] = $accept;
		  
		 $useractivitydtls['applicationid_receiptno'] = $applicationid;
		 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		 $useractivitydtls['activity'] ='Verify Judgements' ;
		 $useractivitydtls['userid'] = $request->session()->get('username');
		 $useractivitydtls['establishcode'] = $establishcode ;
	     $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
         if($this->Judgement->updateJudgementDetails($judgementStore,$judgementdate,$applicationid))
          {			
			   $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
         if($accept=='A'){
            return response()->json([
                'status' => "sucess",
                  'message' => "Judgement Verified Successfully and Accepted"
                ]);
          }else{
            return response()->json([
                'status' => "sucess",
                  'message' => "Judgement Verified Successfully and Rejected"
                ]);
          }
         }
        //  }
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
	     'pdfFile' => 'required|mimes:pdf|max:20000',
       // 'dscjudgement' => 'required|mimes:pdf|max:20000',
         'applnJudgementDate' => 'required|date',
         'signedPdfData' => 'required',
             ]); 
        
         $judgementdate= date('Y-m-d',strtotime($request->input('applnJudgementDate')));
         $establishcode = $request->session()->get('EstablishCode');
         $applType =  explode('-',$request->get('applTypeName'));
         $applicationid = $applType[1].'/'.$request->get('applicationId');
         $appcount = $this->Judgement->getJudgementExistupload($applicationid,$establishcode,$judgementdate);
         $appcount= $appcount[0]->judgementcount;
       //  $judgement = $request->file('judgement');
          $pdfFile = $request->file('pdfFile');
         //dd($judgement);
        if($appcount>0)
           {
            return back() ->with('error','Judgement already uploaded and verified.');
           }
         else{
              
               //$applType =  explode('-',$request->get('applTypeName'));
              // $applicationid = $applType[1].'/'.$request->get('applicationId');
               
          //  $deletejudgement = $this->Judgement->deleteJudgementExist($applicationid, $judgementdate);

           $disposedApplication = $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);
           $benchcode= $disposedApplication[0]->benchcode;
           $fileName = str_replace("/","_",$applicationid);
           $filejudgementdate= $request->input('applnJudgementDate');
           $fileName = $fileName.'_'.$filejudgementdate.'.'.request()->pdfFile->getClientOriginalExtension();
            //to upload in upload folder in storage\app\upload
           //  $file = $request->imgUpload1->storeAs('upload',$fileName);
           $judgementyear = date('Y',strtotime($request->input('applnJudgementDate')));
		   
		  // $path=$this->pathdownload.'/'.$judgementyear;
           $path=$this->path.'/'.$judgementyear;
          $path1=$this->path1.'/'.$judgementyear;
           if (!file_exists($path)) {
                mkdir($path);
            }
            //else{ /// ==============    if file already exist display in url to download and checked by the user=======================
            // return response()->download(public_path($path.'/'.$fileName));
           // }
            if (!file_exists($path1)) {
                mkdir($path1);
            }

           //dd($fileName.$path);
            $file = $request->pdfFile->move($path, $fileName);
           $a=new waterController();
           $w=$a->PlaceWatermark($path.'/'.$fileName,$fileName,$path.'/',"1");

           $b64=$request->get('signedPdfData');
           //dd($b64);
           $bin = base64_decode($b64, true);
           



$dscname='DSC_'.$fileName;
$file_path  = $path1.'/'.$dscname;
        $result = file_put_contents($file_path, $bin);

//dd($path1.$dscname);
           //$file1= $base64->move($path1, $dscname);

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
            $judgementStore['acceptreject']= 'R' ;
            $judgementStore['releasedate']= date('Y-m-d',strtotime($request->input('releaseDate')));
            $judgementStore['dscjudgement_path']=$judgementyear.'/'.$dscname;
            
          DB::beginTransaction();
           try
             {
                         $this->Judgement->addJudgement($judgementStore);
                          DB::commit();
                          $useractivitydtls['applicationid_receiptno'] = $applicationid;
                         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
                         $useractivitydtls['activity'] ='Upload Judgements' ;
                         $useractivitydtls['userid'] = $request->session()->get('username');
                         $useractivitydtls['establishcode'] = $establishcode ;
                         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
                         $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                        return back() ->with('success','Judgement uploaded Successfully.');
              }

             catch (\Throwable $e){
        //dd($e->getMessage());
        $error_code = $e->getCode();
        if($error_code == 23505){
          $msg="Judgement for applicationid: ". $applicationid ." already exist. Please Contact Computer Section to delete this judgement";
          return back()->with('error',$msg);
        } }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
          return back()->with('error', 'Someting went wrong');
        }
           
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


/*public function PlaceWatermark1($file) {
    print_r($file);
    $file= $file;
    $fname=$file->getClientOriginalName();
    //console.log($fname);
print_r($fname);
    $storing = $file->storeAs('pdf', $fname);
    $saving_name = 'w_'.$fname;
    
    //This page contains edit the existing file by using fpdi.
    require('../WatermarkPDF.php');
    
    $pdfFile = storage_path('app\\'.$storing);
    $watermarkText = "for reference";
    $pdf = new WatermarkPDF($pdfFile, $watermarkText);
    //$pdf = new FPDI();
    $pdf->AddPage();
    //$pdf->SetFont('Arial', '', 40);

    if($pdf->numPages>1) {
      for($i=2;$i<=$pdf->numPages;$i++) {
        $pdf->_tplIdx = $pdf->importPage($i);
        $pdf->AddPage();
      }
    }
    $pdfFilestore = storage_path('app\pdf\\'.$saving_name);
    //return $pdfFilestore;
    
    $pdf->Output('F',$pdfFilestore); //storing in public folder
    //$pdf->Output(); //If you Leave blank then it should take default "I" i.e. Browser
    //$pdf->Output("sampleUpdated.pdf", 'D'); //Download the file. open dialogue window in browser to save, not open with PDF browser viewer
    //$pdf->Output("save_to_directory_path.pdf", 'F'); //save to a local file with the name given by filename (may include a path)
    //$pdf->Output("sampleUpdated.pdf", 'I'); //I for "inline" to send the PDF to the browser
    //$pdf->Output("", 'S'); //return the document as a string. filename is ignored.
    
    return view('watermark');
  }*/
  


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
