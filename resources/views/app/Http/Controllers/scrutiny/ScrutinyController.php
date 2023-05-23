<?php
namespace App\Http\Controllers\Scrutiny;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\Scrutiny;
use App\UserActivityModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

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

  public function scrutinyCheckSlip(Request $request)
   {
     $data['applicationType'] = $this->case->getApplType();
     return view('Scrutiny.ScrutinyCheckSlip',$data);
   }

   public function showApplicationScrutiny(Request $request)
   { 
   	 $request->validate([
                'applicationType' => 'required',
                'applicationNo' => 'required'
             ]);  
   	  $applicationNo = $request->input('applicationNo');
	    $applicationType = explode("-",$request->input('applicationType'));
   		$application_id = $applicationType[1].'/'.$applicationNo;
   		$establishcode = $request->session()->get('EstablishCode');
        
   	$data['applicationNo'] = $request->input('applicationNo');
    $application= DB::select("select * from application where applicationid =:applicationid and establishcode = :establishcode",['applicationid' => $application_id,'establishcode' => $establishcode]);
 
    if (count($application)==0)
        {  
         return redirect()->route('scrutiny')->with('error', ' Application doesnot exists');
     }else{
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
             
              $data['applicantDetails'] = $this->case->getTopApplicantDetails($application_id)[0];
              $data['respondantDetails'] = $this->case->getTopRespondantDetails($application_id)[0];

              $data['scrutinychklist'] = DB::select("select * from scrutinychklist where appltypecode =:appltypecode order by chklistsrno",['appltypecode' => $applicationType[0]]);
              return view('Scrutiny.ApplicationScrutiny', $data);
         }
        else{
        // Update Scrutiny
          $data['insertUpdateflag'] = 'U';
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
          $data['applicantDetails'] = $this->case->getTopApplicantDetails($application_id)[0];
          $data['respondantDetails'] = $this->case->getTopRespondantDetails($application_id)[0];
          $data['scrutiny'] = $this->scrutiny->getScrutiny($application_id)[0];
          $data['scrutinydetails'] = $this->scrutiny->getScrutinyDetails($application_id);
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
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
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
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('scrutinydetails')->insert($scrutinychklist1);
                $j++;
                $extraObjectionchklistsrno++;
                }
            }

              if($request->input('applicationComplied')=='Y'){
                  $objectionflag = '0';
                  $scrutinyflag  = 'Y';   
              }else{
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
                DB::delete('delete from scrutiny where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]); 
                DB::delete('delete from scrutinydetails where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]);
                DB::table('scrutiny')->insert($scrutinySave);  
                 $i=0;
                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']= $_POST['applicationid'];
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
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
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('scrutinydetails')->insert($scrutinychklist1);
                $j++;
                $extraObjectionchklistsrno++;
                }
            }
           if($request->input('applicationComplied')=='Y'){
               $objectionflag = '0';
               $scrutinyflag  = 'Y';   
            }else{
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
    $data['applicationNo'] = $request->input('applicationNo');
    $application= DB::select("select * from application where applicationid =:applicationid and establishcode = :establishcode",['applicationid' => $application_id,'establishcode' => $establishcode]);
 
     if (count($application)==0)
        {  
         return redirect()->route('scrutinyCompliance')->with('error', ' Application doesnot exists');
     }else{

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
          $data['applicantDetails'] = $this->case->getTopApplicantDetails($application_id)[0];
          $data['respondantDetails'] = $this->case->getTopRespondantDetails($application_id)[0];
          $data['scrutiny'] = $this->scrutiny->getScrutiny($application_id)[0];
          $data['scrutinydetails'] = $this->scrutiny->getScrutinyDetails($application_id);
          $data['scrutinydetails11'] = $this->scrutiny->getScrutinyDetailsObjection($application_id);
          return view('Scrutiny.ApplicationScrutinyCompliance', $data);
        } 
      } 
   }


    public function saveScrutinyCompliance(Request $request){
             $scrutinySave['applicationid']  = $request->input('applicationid');
             $scrutinySave['scrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['lastcompliancedate']  = date('Y-m-d',strtotime($request->input('lastcompliancedate')));
             $scrutinySave['acceptreject']  = $request->input('applicationComplied');
             $scrutinySave['accrejdate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $scrutinySave['reason']  = trim($request->input('rejectReason'));
             $scrutinySave['createdon']    = date('Y-m-d H:i:s') ;
             $scrutinySave['createdby']    = $request->session()->get('username');
             $scrutinySave['tobecomplieddate']  = date('Y-m-d',strtotime($request->input('tobecomplieddate')));
           
            try {
              DB::beginTransaction();
                DB::insert('insert into scrutinyhistory select * from scrutiny where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]); 
                DB::insert('insert into scrutinydetailshistory select * from scrutinydetails where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]); 
                DB::delete('delete from scrutiny where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]); 
                DB::delete('delete from scrutinydetails where applicationid= :applicationid',['applicationid' => $request->input('applicationid')]); 

                DB::table('scrutiny')->insert($scrutinySave);    
                
                 $i=0;
               
                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']= $_POST['applicationid'];
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
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
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('scrutinydetails')->insert($scrutinychklist1);
                $j++;
                $extraObjectionchklistsrno++;
                }
            }

              if($request->input('applicationComplied')=='Y'){
                 // $objectionflag = '0';
                  $scrutinyflag  = 'Y'; 
                  DB::table('application')->where(['applicationid'=>$_POST['applicationid']])->update(['scrutinyflag' => $scrutinyflag]); 

              }else{
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
            } catch (\Exception $e) {
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
       $data['applicantDetails'] = $this->case->getTopApplicantDetails($application_id)[0];
       $data['respondantDetails'] = $this->case->getTopRespondantDetails($application_id)[0];
       $data['scrutiny'] = $this->scrutiny->getScrutiny($application_id)[0];
       $data['scrutinydetails'] = $this->scrutiny->getScrutinyDetails($application_id);
       $data['scrutinydetails11'] = $this->scrutiny->getScrutinyDetailsObjection($application_id);    
       $itemNo = "";
       foreach ( $data['scrutinydetails'] as $scrutinydetails) {
              if($scrutinydetails->objectedflag =="N")
               $itemNo .= $scrutinydetails->chklistsrno.",";
             }

           $itemNo = substr($itemNo, 0, -1);
      foreach ( $data['scrutinydetails11'] as $scrutinydetails11) {
              if($scrutinydetails11->objectedflag =="N")
               $itemNo .= " and Other objections";
             }

             //print_r($itemNo);
       $data['itemNo'] = $itemNo;
      //  return view('Scrutiny.ScrutinyCheckSlipPDF', $data);
       $pdf = PDF::LoadView('scrutiny.ScrutinyCheckSlipPDF',$data); 
  



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