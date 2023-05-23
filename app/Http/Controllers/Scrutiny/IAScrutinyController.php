<?php
namespace App\Http\Controllers\Scrutiny;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\IADocument;
use App\IANature;
use App\IAScrutiny;
use App\UserActivityModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class IAScrutinyController extends Controller
{

  public function __construct()
	{
	 $this->IANature = new IANature();
	 $this->case = new CaseManagementModel();  
	 $this->iadocument = new IADocument();  
     $this->iascrutiny =  new IAScrutiny();
	 $this->UserActivityModel = new UserActivityModel();
	}

  public function index(Request $request)
   {
   	 $data['applicationType'] = $this->case->getApplType();
   	 return view('Scrutiny.IAScrutinyForm',$data);
   }

  public function iascrutinycompliance(Request $request)
   {
     $data['applicationType'] = $this->case->getApplType();
     return view('Scrutiny.IAScrutinyCompliance',$data);
   }

  public function iascrutinycheckslip(Request $request)
   {
     $data['applicationType'] = $this->case->getApplType();
     return view('Scrutiny.IAScrutinyCheckSlip',$data);
   }

   public function showiascrutiny(Request $request)
   { 
   	 $request->validate([
                'applTypeName' => 'required',
                'applicationId' => 'required',
				'iano' => 'required'
             ]);  
   	    $applicationId = $request->input('applicationId');
	    $applicationType = explode("-",$request->input('applTypeName'));
   		$application_id = $applicationType[1].'/'.$applicationId;
   		$iano = $request->input('iano');
		$establishcode = $request->session()->get('EstablishCode');
        
   	$data['applicationNo'] = $request->input('applicationId');
    $application= DB::select("select * from application where applicationid =:applicationid and establishcode = :establishcode",['applicationid' => $application_id,'establishcode' => $establishcode]);
	$iaDetails = $this->iadocument->getDtlsByIANo($iano,$application_id,1); 
    if (count($application)==0)
     {  
         return redirect()->route('iascrutiny')->with('error', ' Application doesnot exists');
     }else{
        $postedtocourt = DB::select("select count(*) as count from iascrutiny where postedtocourt is not null and applicationid=:applicationid and iano=:iano ",['applicationid' => $application_id,'iano' => $iano])[0];
        $postedtocourt = $postedtocourt->count;
      //  $scrutinyflag=$application[0]->scrutinyflag;
       if($postedtocourt > 0 ){
          return redirect()->route('iascrutiny')->with('error', 'Scrutiny cannot be performed');
           }
       else{
		    $objectionflag= $iaDetails[0]->objectionflag;
      	if($objectionflag == null || $objectionflag==''){
        // New IA Scrutiny
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
			  $data['IANature'] =  $this->IANature->getIANature();
			  $data['iaDetails'] = $this->iadocument->getDtlsByIANo($iano,$application_id,1)[0];  
			  $data['scrutinychklist'] = DB::select("select * from iascrutinychklist order by chklistsrno");
              return view('Scrutiny.IAApplicationScrutiny', $data);
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
		  $data['IANature'] =  $this->IANature->getIANature();
	      $data['iaDetails'] = $this->iadocument->getDtlsByIANo($iano,$application_id,1)[0];         
		  $data['iascrutiny'] = $this->iascrutiny->getIAScrutiny($application_id,$iano)[0];
          
		  $data['iascrutinydetails'] = $this->iascrutiny->getIAScrutinyDetails($application_id,$iano);
          $data['iascrutinydetails11'] = $this->iascrutiny->getIAScrutinyDetailsObjection($application_id,$iano);
          return view('Scrutiny.IAApplicationScrutiny', $data);
         }
       }
      } 
   }


 public function saveiascrutiny(Request $request){
     if($request->input('sbmt_adv') == "A")
        {  
          DB::beginTransaction();
             $iascrutinySave['applicationid']  = $request->input('applicationid');
			 $iascrutinySave['iano']  = $request->input('iano');
			  
             $iascrutinySave['iascrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
             $iascrutinySave['lastcompliancedate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $iascrutinySave['acceptreject']  = $request->input('applicationComplied');
             $iascrutinySave['accrejdate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $iascrutinySave['tobecomplieddate']  = date('Y-m-d',strtotime($request->input('tobecomplieddate')));
           
             $iascrutinySave['reason']  = trim($request->input('rejectReason'));
             $iascrutinySave['createdon']    = date('Y-m-d H:i:s') ;
             $iascrutinySave['createdby']    = $request->session()->get('username');
            try {
              //$id  =  DB::table('iascrutiny')->insertGetId($iascrutinySave);   
			   DB::table('iascrutiny')->insert($iascrutinySave);  
			   $i=0;
                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']  = $request->input('applicationid');
			    $scrutinychklist['iano']  = $request->input('iano');
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
                $scrutinychklist['remarks'] = trim($_POST['remarks'][$i]);
                $update =  DB::table('iascrutinydetails')->insert($scrutinychklist);
                 $i++;
              }
            if($request->has('extraObjection'))
            {
              $j=0;
              $extraObjectionchklistsrno =101;
              foreach ($request->input("extraObjection") as $key) {
                $scrutinychklist1['applicationid']  = $request->input('applicationid');
			    $scrutinychklist1['iano']  = $request->input('iano');
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('iascrutinydetails')->insert($scrutinychklist1);
                $j++;
                $extraObjectionchklistsrno++;
                }
            }

              if($request->input('applicationComplied')=='Y'){
                  $objectionflag = '0';
                  $iascrutinyflag  = 'Y';   
              }else{
                 $objectionflag = '1';
                 $iascrutinyflag  = 'N';   
              }
             
               DB::table('iadocument')->where(['applicationid'=>$_POST['applicationid']])->where(['iano'=>$_POST['iano']])->update(['objectionflag' =>$objectionflag , 'iascrutinyflag' => $iascrutinyflag]); 
			   
			     $useractivitydtls['applicationid_receiptno'] = $_POST['applicationid'];
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			     $useractivitydtls['activity'] ='Save IA Application Scrutiny' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
			   
               DB::commit();
                 return redirect()->route('iascrutiny')->with('success', 'IA Scrutiny Saved successfully');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('iascrutiny')->with('error', 'Someting wrong, IA Scrutiny not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('iascrutiny')->with('error', 'Someting wrong, IA Scrutiny not saved !!');
            }
        }
          else if($request->input('sbmt_adv') == "U"){
            DB::beginTransaction();
			 $applicationid = $request->input('applicationid');
             $iano  = $request->input('iano');
             $iascrutinySave['iascrutinydate']   = date('Y-m-d',strtotime($request->input('accrejdate')));
             $iascrutinySave['lastcompliancedate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $iascrutinySave['acceptreject']  = $request->input('applicationComplied');
             $iascrutinySave['accrejdate']  = date('Y-m-d',strtotime($request->input('accrejdate')));
             $iascrutinySave['tobecomplieddate']  = date('Y-m-d',strtotime($request->input('tobecomplieddate')));
             $iascrutinySave['reason']  = trim($request->input('rejectReason'));
             $iascrutinySave['updatedon']    = date('Y-m-d H:i:s') ;
             $iascrutinySave['updatedby']    = $request->session()->get('username');

            try {
                DB::table('iascrutiny')->where('applicationid',$applicationid)->where('iano',$iano)->update($iascrutinySave);
                DB::delete('delete from iascrutinydetails where applicationid=:applicationid and iano=:iano',['applicationid' => $applicationid,'iano'=>$iano]);
				
                $i=0;
                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']  = $request->input('applicationid');
			    $scrutinychklist['iano']  = $request->input('iano');
                $scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
                $scrutinychklist['remarks'] = trim($_POST['remarks'][$i]);
                $update =  DB::table('iascrutinydetails')->insert($scrutinychklist);
                $i++;
              }
            if($request->has('extraObjection'))
            {
              $j=0;
              $extraObjectionchklistsrno =101;
              foreach ($request->input("extraObjection") as $key) {
                $scrutinychklist1['applicationid']  = $request->input('applicationid');
			    $scrutinychklist1['iano']  = $request->input('iano');
                $scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('iascrutinydetails')->insert($scrutinychklist1);
                $j++;
                $extraObjectionchklistsrno++;
                }
            }
           if($request->input('applicationComplied')=='Y'){
               $objectionflag = '0';
               $iascrutinyflag  = 'Y';   
            }else{
               $objectionflag  = '1';
               $iascrutinyflag  = 'N';   
            }
                    
              DB::table('iadocument')->where(['applicationid'=>$_POST['applicationid']])->where(['iano'=>$_POST['iano']])->update(['objectionflag' =>$objectionflag , 'iascrutinyflag' => $iascrutinyflag]);
			     $useractivitydtls['applicationid_receiptno'] = $_POST['applicationid'];
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			     $useractivitydtls['activity'] ='Update IA Application Scrutiny' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
              DB::commit();
              return redirect()->route('iascrutiny')->with('success', 'IA Scrutiny Updated successfully');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('iascrutiny')->with('error', 'Someting wrong,IA Scrutiny not Updated !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('iascrutiny')->with('error', 'Someting wrong,IA Scrutiny not Updated !!');
            }
           }
          }

  public function showIAScrutinyCompliance(Request $request)
     { 
     $request->validate([
                'applTypeName' => 'required',
                'applicationId' => 'required',
				'iano' => 'required'
             ]);  
			 
		$applicationId = $request->input('applicationId');
	    $applicationType = explode("-",$request->input('applTypeName'));
   		$application_id = $applicationType[1].'/'.$applicationId;
   		$iano = $request->input('iano');
		$establishcode = $request->session()->get('EstablishCode');
        
   	$data['applicationNo'] = $request->input('applicationId');
			 
			    
    //getApplicationDetails
    $data['applicationNo'] = $request->input('applicationId');
    $application= DB::select("select * from application where applicationid =:applicationid and establishcode = :establishcode",['applicationid' => $application_id,'establishcode' => $establishcode]);
    $iaDetails = $this->iadocument->getDtlsByIANo($iano,$application_id,1); 
    if(count($application)==0)
      {  
         return redirect()->route('iascrutinycompliance')->with('error', ' Application doesnot exists');
     }else{
	    $scrutiny = $this->iascrutiny->getIAScrutiny($application_id,$iano);
    if(count($scrutiny)==0)
        {  
		return redirect()->route('iascrutinycompliance')->with('error', ' No Scrutiny exists for the Application  '.$application_id.' and IA'. $iano);
        }
    $iascrutinyflag = $iaDetails[0]->iascrutinyflag;
    $objectionflag = $iaDetails[0]->objectionflag;
    $postedtocourt = $scrutiny[0]->postedtocourt;
    if($iascrutinyflag == "Y" || $objectionflag == null || $postedtocourt != null){
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
         
		$data['IANature'] =  $this->IANature->getIANature();
	      $data['iaDetails'] = $this->iadocument->getDtlsByIANo($iano,$application_id,1)[0];         
		  $data['iascrutiny'] = $this->iascrutiny->getIAScrutiny($application_id,$iano)[0];
          
		  $data['iascrutinydetails'] = $this->iascrutiny->getIAScrutinyDetails($application_id,$iano);
          $data['iascrutinydetails11'] = $this->iascrutiny->getIAScrutinyDetailsObjection($application_id,$iano);

		  return view('Scrutiny.IAApplicationScrutinyCompliance', $data);
        } 
      } 
   }


    public function saveIAScrutinyCompliance(Request $request){
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
                DB::insert('insert into iascrutinyhistory select * from scrutiny where applicationid= :applicationid and iano=:iano',['applicationid' => $request->input('applicationid'),'iano' => $request->input('iano')]); 
                DB::insert('insert into iascrutinydetailshistory select * from scrutinydetails where applicationid= :applicationid and iano=:iano',['applicationid' => $request->input('applicationid'),'iano' => $request->input('iano')]); 
                DB::delete('delete from iascrutiny where applicationid= :applicationid and iano=:iano',['applicationid' => $request->input('applicationid'),'iano' => $request->input('iano')]); 
                DB::delete('delete from iascrutinydetails where applicationid= :applicationid and iano=:iano',['applicationid' => $request->input('applicationid'),'iano' => $request->input('iano')]); 

                DB::table('iascrutiny')->insert($scrutinySave);    
                
                 $i=0;
               
                foreach ($request->input("chklistsrno") as $key) {
                $scrutinychklist['applicationid']= $_POST['applicationid'];
                $scrutinychklist['iano']  = $request->input('iano');
				$scrutinychklist['chklistsrno']= $_POST['chklistsrno'][$i];
                $scrutinychklist['observation']= "";
                $scrutinychklist['objectedflag']= $_POST['compliance'][$i];
                $scrutinychklist['remarks'] = trim($_POST['remarks'][$i]);
              
                $update =  DB::table('iascrutinydetails')->insert($scrutinychklist);
                            
                $i++;
               
            }
            if($request->has('extraObjection'))
            {
              $j=0;
              $extraObjectionchklistsrno =101;
              foreach ($request->input("extraObjection") as $key) {
                $scrutinychklist1['applicationid']= $_POST['applicationid'];
                $scrutinychklist1['iano']  = $request->input('iano');
				$scrutinychklist1['chklistsrno']= $extraObjectionchklistsrno;
                $scrutinychklist1['observation']= $_POST['extraObjection'][$j];
                $scrutinychklist1['objectedflag']= $_POST['extraCompliance'][$j];
                $scrutinychklist1['remarks'] = trim($_POST['extraRemarks'][$j]);
                $update =  DB::table('iascrutinydetails')->insert($scrutinychklist1);
                $j++;
                $extraObjectionchklistsrno++;
                }
            }

              if($request->input('applicationComplied')=='Y'){
                 // $objectionflag = '0';
                  $iascrutinyflag  = 'Y'; 
                  DB::table('iadocument')->where(['applicationid'=>$_POST['applicationid']])->where(['iano'=>$_POST['iano']])->update(['iascrutinyflag' => $iascrutinyflag]); 
				}else{
                // $objectionflag = '1';
                $iascrutinyflag  = 'N';   
				DB::update('update iadocument set objectionflag= CAST(objectionflag AS INT)+1 , iascrutinyflag=:iascrutinyflag where applicationid= :applicationid and iano=:iano',['iascrutinyflag'=> $iascrutinyflag,'applicationid' => $request->input('applicationid'),'iano' => $request->input('iano')]); 
             }

                 $useractivitydtls['applicationid_receiptno'] = $_POST['applicationid'];
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			     $useractivitydtls['activity'] ='Save IA Scrutiny Compliance' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
               DB::commit();
                 return redirect()->route('iascrutinycompliance')->with('success', 'IA Scrutiny Compliance Saved successfully');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('iascrutinycompliance')->with('error', 'Someting wrong, Scrutiny Compliance not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('iascrutinycompliance')->with('error', 'Someting wrong, Scrutiny Compliance not saved !!');
            }
      
           }

public function printIAScrutinyCheckSlip(Request $request)
    {
      $establishcode = $request->session()->get('EstablishCode');
      $request->validate([
                'applTypeName' => 'required',
                'applicationId' => 'required',
				'iano' => 'required'
             ]);  
       $applicationNo = $request->input('applicationId');
       $applicationType = explode("-",$request->input('applTypeName'));
       $application_id = $applicationType[1].'/'.$applicationNo;
       $iano = $request->input('iano');
	   $data['title'] = DB::select("select * from establishment where establishcode = ". Session()->get('EstablishCode'));
      
       $application= DB::select("select * from application where applicationid =:applicationid and establishcode = :establishcode",['applicationid' => $application_id,'establishcode' => $establishcode]);
 
        if (count($application)==0)
        {  
         return redirect()->route('iascrutinycheckslip')->with('error', ' Application doesnot exists');
        }else{
          $scrutiny = $this->iascrutiny->getIAScrutiny($application_id,$iano);
        if (count($scrutiny)==0)
        {  
          return redirect()->route('iascrutinycheckslip')->with('error', ' No Scrutiny exists for the Application  '.$application_id.' and IA'. $iano);
        }else{
       
       $data['applicationDetails'] = $this->case->getApplicationId($application_id,$user='')[0];
       $applcatcode=$data['applicationDetails']->applcategory;
       $data['applCategoryName'] = $this->case->getApplCategoryName($applcatcode)[0]; 
       if($data['applicationDetails']->subject == '-'){
                $data['applSubject']= $data['applCategoryName']->applcatname;
              }else{
                $data['applSubject']= $data['applCategoryName'] ->applcatname.' - ' .$data['applicationDetails']->subject;
              }
      // $data['applicantDetails'] = $this->case->getTopApplicantDetails($application_id)[0];
	   $data['applicantDetails']  = DB::select("select * from causetitleapplicant where applicationid = '". $application_id ."' and applicantsrno=1")[0];     
	// $data['respondantDetails'] = $this->case->getTopRespondantDetails($application_id)[0];
        $data['respondantDetails']  = DB::select("select * from causetitlerespondent where applicationid = '". $application_id ."' and respondsrno=1")[0];
	   $data['iascrutiny'] = $this->iascrutiny->getIAScrutiny($application_id,$iano)[0];
       $data['iascrutinydetails'] = $this->iascrutiny->getIAScrutinyDetails($application_id,$iano);
       $data['iascrutinydetails11'] = $this->iascrutiny->getIAScrutinyDetailsObjection($application_id,$iano);
       $itemNo = "";
       foreach ( $data['iascrutinydetails'] as $scrutinydetails) {
              if($scrutinydetails->objectedflag =="N")
               $itemNo .= $scrutinydetails->chklistsrno.",";
             }

        $itemNo = substr($itemNo, 0, -1);
      foreach ( $data['iascrutinydetails11'] as $scrutinydetails11) {
              if($scrutinydetails11->objectedflag =="N")
               $itemNo .= " and Other objections";
             }

             //print_r($itemNo);
       $data['itemNo'] = $itemNo;
      //  return view('Scrutiny.ScrutinyCheckSlipPDF', $data);
       $pdf = PDF::LoadView('Scrutiny.IAScrutinyCheckSlipPDF',$data); 
  



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

     return $pdf->download('IACheckSlip.pdf');
      }   }   
 } 

}