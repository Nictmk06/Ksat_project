<?php

namespace App\Http\Controllers;
use App\UserActivityModel;
use Illuminate\Http\Request;
use App\CaseManagementModel;
use Session;
use App\ModuleAndOptions;//use model Module & Options
use App\IANature;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CaseManagementController extends Controller
{

	public function __construct()
	{
	 $this->case = new CaseManagementModel();
	$this->module= new ModuleAndOptions();
    $this->IANature = new IANature();
   	$this->UserActivityModel = new UserActivityModel();
	}
   public function index(Request $request)
   {
          
            /*$establishcode = $request->session()->get('EstablishCode');
            $data['establishname'] = $this->case->getEstablishName($establishcode);
            print_r($data['establishname']);*/
           // establishfullname
              //$data['options'] = $this->module->getOptions();
      //$data['Modules'] = $this->module->getModules();
            $establishcode = $request->session()->get('EstablishCode');
            $data['establishcode'] = $establishcode;
            $data['establishname'] = $this->case->getEstablishName($establishcode);
            $data['actDetails'] = $this->case->getActDetails();
            $data['sectionDetails'] = $this->case->getSectionDetails();
            $data['applicationType'] = $this->case->getApplType();
            $data['applCategory'] = $this->case->getApplCategory();
            $data['district'] = $this->case->getDistrict();
            $data['taluka'] = $this->case->getTaluka($distCode='');
           
            $data['nameTitle'] = $this->case->getNameTitle();
           // print_r($data['nameTitle']);
            $data['relationTitle'] = $this->case->getRelationTitle();
            $data['deptType'] = $this->case->getDeptType();
            $data['deptName'] = $this->case->getDeptName();
            $data['adv']=$this->case->getAdv();
            //$data['resadv']=$this->case->getResAdv();
             $data['appldesig'] =  $this->case->getDesignation();
            $user = $request->session()->get('username');
            $data['Temp'] = $this->case->getApplicationId($applicationid='',$user);

          //  print_r($data['Temp']);
            
            if( count($data['Temp'])>0)
            {
                 $data['taluka3'] = $this->case->getTaluka($data['Temp'][0]->servicedistrict); 
                 
            }
            else
            {
                    $data['taluka3'] = $this->case->getTaluka($distCode='');
                
            }
           
            

            $data['Temprelief'] = $this->case->getRelief($applicationId='',$newSrno='',$user);

            //print_r($data['Temprelief']);
            $data['TempReceipt'] = $this->case->getReceiptDetails($applicationid='',$user);
            $data['TempApplicant'] = $this->case->getApplicantDetails($applicationid='',$user);
            $data['TempRespondant'] = $this->case->getRespondantDetails($applicationid='',$user);
            $data['TempApplTypeRefer'] = $this->case->getApplTypeRefer($applicationid='',$user);
            $data['ApplicationIdex'] = $this->case->getApplicationIndex($applicationid='',$user);
            $data['nameTitle'] = $this->case->getNameTitle();
            $data['IANature'] =  $this->IANature->getIANature();
            $data['appldesig'] =  $this->case->getDesignation();
//             $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//              $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
             //$data['modules_options'] =  $this->module->getModulesAndOtions();
            // print_r($data['modules_options']);
              $data['title']='Case Form';
             return view('case/CaseForm',$data)->with('user',$request->session()->get('userName'));
   }

    public function store(Request $request)
    {
		 $establishcode = $request->session()->get('EstablishCode');
        $arr=explode('-',$request->input('applTypeName'));
       //ia nature add     
        $IAStore['iaprayer']=$request->input('IAprayer');
              $sbmt_case =  $request->input('sbmt_case');
             $relief_value2=  $request->input('relief_value2');
              if( $relief_value2=='Edit')
              {
                $IAStore['applicationid']=$request->input('Originalapplid');                 
              }
            else   if($sbmt_case=='A'  )
              {
                  $IAStore['applicationid']='T'.$arr[1].'/'.$request->input('applStartNo').'/'.$request->input('applYear');
              }
              else if ($sbmt_case=='U') 
              {
                 $IAStore['applicationid']= $request->input('Originalapplid');
              }
           
          /*chnaged on 15-07-2019*/ 
        if($request->input('interimPrayer')=="Y")
        {
            $applnStore['interimprayer'] = $request->input('interimOrder');
             $applnStore['isinterimrelief'] = "Y";
      }
      else
      {
            $applnStore['interimprayer'] = "";
            $applnStore['isinterimrelief'] = "N";
      }

      if($request->get('IANature')!='' && $request->get('IAprayer')!='' )
      {

           $IAStore['iafillingdate']=date('Y-m-d',strtotime($request->input('dateOfAppl')));
            $IAStore['ianaturecode']= $request->input('IANature');
            $IAStore['iaregistrationdate']= date('Y-m-d',strtotime($request->input('applnRegDate')));
            $IAStore['iastatus']=1;
            $IAStore['iano']='IA/1';
            $IAStore['iasrno']='1';
            $IAStore['documenttypecode']='1';
            $IAStore['createdon']=date('Y-m-d');
            $IAStore['createdby']=$request->session()->get('userName');
            $IAStore['appindexref'] = '0';
      }
      else
      {
        $IAStore='';
      }
       /*end of a=change 15-07-2019*/
         $applnStore['actcode']= 1;
         $applnStore['establishcode'] = $establishcode;
        $applnStore['actsectioncode'] = $request->input('actSectionName');
        $applnStore['appltypecode'] = $arr[0];
        $applnStore['applicationyear'] = $request->input('applYear');
        $applnStore['applicationsrno'] = $request->input('applStartNo');
      //  $applnStore['IANature'] = $request->input('IANature');

        $applnStore['applicationid']= 'T'.$arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
        $applnStore['applicationtosrno'] = $request->input('applEndNo');
        $applnStore['applicationdate'] = date('Y-m-d',strtotime($request->input('dateOfAppl')));
        $applnStore['registerdate'] = date('Y-m-d',strtotime($request->input('applnRegDate')));
        
        $applnStore['applcategory'] = $request->input('applCatName');
        $applnStore['subject'] = $request->input('applnSubject');
        /*$applnStore['againstorderno'] = $request->input('orderNo');
        $applnStore['againstorderdate'] = date('Y-m-d',strtotime($request->input('orderDate')));
        $applnStore['againstorderissuedby'] = $request->input('applnIssuedBy');*/
        $applnStore['respondentcount']=$request->input('noOfRes');
        $applnStore['applicantcount']=$request->input('noOfAppl');
        $applnStore['serviceaddress']=$request->input('addrForService');
        $applnStore['servicepincode']=$request->input('rPincode');
        $applnStore['servicetaluk']=$request->input('rTaluk');
        $applnStore['servicedistrict']=$request->input('rDistrict');
        $applnStore['againstorders']= $request->input('multiorder');
        $applnStore['remarks']=$request->input('caseremarks');
            $sbmt_case =  $request->input('sbmt_case');

                 


        if($sbmt_case=='A')
        {
           $applnStore['createdby']= $request->session()->get('userName');
           $applnStore['createdon']= date('Y-m-d H:i:s') ;

        }
        else
        {
           $applnStore['applicationid']= $arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];

           $applnStore['updatedby']= $request->session()->get('userName');
           $applnStore['updatedon']= date('Y-m-d H:i:s') ;
        }
        
        if($request->input('relief_value2')=='Edit')
        {
            $applId = $request->input('Originalapplid');
            $applnStore['applicationid']= $applId;
             }
            else   if($sbmt_case=='A' )
              {
                   $applId='T'.$arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
              }
              else if ($sbmt_case == 'U')
                {  $applId= $request->input('Originalapplid'); 
                   $applnStore['applicationid']= $request->input('Originalapplid');
          }
       
          $revappl = $request->input('reviewApplId1');
          $relief = $request->input('reliefsought');

        if($sbmt_case=='A')
        { 
            $application_id = ltrim($applnStore['applicationid'],'T');
            $appcount = $this->case->getApplExist($application_id, $applnStore['applicationsrno'],$arr[0], $applnStore['applicationyear'],$request->input('applEndNo'));

            $appcount= $appcount[0]->applcount;
           
           if($appcount>0)
           {
                return response()->json([
                'status' => "exists",
                'message' => "Application Already Exists"

                ]);
           }
           else
           {
             $useractivitydtls['applicationid_receiptno'] =$applId;
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Legacy - Add Application' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
             if($this->case->addApplDetails($applnStore,$IAStore))
                {
                        if($arr[1]!='OA')
                        {
                        $applagain['createdby']=$request->session()->get('userName');
                        $applagain['applicationid']=$applId;
                        $applagain['referapplid']=$revappl;

                        $this->case->addApplreferType($applagain);
                        }
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
                return response()->json([
                'status' => "sucess",
                'message' => "Application Added Successfully"

                ]);

                }
                else
                {

                return response()->json([
                'status' => "fail",
                'message' => "Something Went Wrong!!!"

                ]);
                }
           }
            
        }
        else if($sbmt_case=='U')
        { 

//         print_r($sbmt_case);
//print_r($applId); 


            if($this->case->updateApplDet($applnStore,$applId,$IAStore,$sbmt_case))
            {
				$useractivitydtls['applicationid_receiptno'] =$applId;
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				 $useractivitydtls['activity'] ='Legacy - Update Application' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
                return response()->json([
                'status' => "sucess",
                'message' => "Application Updated Successfully"

                ]);

            }
            else
            {

                return response()->json([
                'status' => "fail",
                'message' => "Something Went Wrong!!!"

                ]);
            }
        }
     // $this->case->updateApplDet($applnStore, $applId,$IAStore,$sbmt_case);
    	
   	
    }
    public function receiptStore(Request $request)
    {
		// mini for updating application no for new receipts
      $sbmt_val = $request->input('sbmt_value');
	  $addreceipt = $request->input('addreceipt');
      if($sbmt_val=='A' && $addreceipt == 'U'){
          $receiptStore['applicationid']=$request->input('recAppId');
		  $receiptStore['receiptuseddate']= date('Y-m-d H:i:s') ;
		  $receiptStore['receiptno'] = $request->input('receiptNo');
          $receiptSrNo = explode('/',$request->input('receiptNo'));
		  $receiptStore['receiptsrno'] = $receiptSrNo[2];
			if($this->case->updateReceiptDetails($receiptStore, $receiptStore['receiptsrno'],$receiptStore['receiptno']))
                {
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Receipt Updated Successfully",
                    ]);
                }
                else
                {
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went wrong!!"
                    ]);
                }
      } 
    else{
	  $establishcode = $request->session()->get('EstablishCode');
        $receiptStore['receiptno'] =Session::get('EstablishCode').'/'.$request->input('recpApplYear')."/".$request->input('receiptNo');
        $sbmt_val = $request->input('sbmt_value');

      //echo $sbmt_val;

         if($sbmt_val=='A')
        {
           $receiptStore['createdby']=  $request->session()->get('userName');
           $receiptStore['createdon']= date('Y-m-d H:i:s') ;
        }
        else
        {
           $receiptStore['updatedby']=  $request->session()->get('userName');
           $receiptStore['updatedon']= date('Y-m-d H:i:s') ;
        }

	$receiptStore['establishcode'] = $establishcode;
        $receiptStore['receiptdate'] = date('Y-m-d',strtotime($request->input('receiptDate')));
        $receiptStore['titlename'] = $request->input('applTitle');
        $receiptStore['receiptsrno']=$request->input('receiptNo');
        $receiptStore['feepurposecode']=1;
        $receiptStore['applicationid']=$request->input('recAppId');
        $receiptStore['name'] = $request->input('applName');
        $receiptStore['amount'] = $request->input('recpAmount');
         if($receiptStore['amount']>500)
        {
            $receiptStore['modeofpayment'] = 'D';
        }
        else
        {
            $receiptStore['modeofpayment'] = 'C';
        }
        $recpNewNo =  $receiptStore['receiptno'];

        $recpCount= $this->case->getReceiptExistanceCount($recpNewNo);
         
         if($sbmt_val=='A')
            {
                if($recpCount>0)
                {
                     return response()->json([
                        'status' => "exits",
                        'message' => "Receipt ".$recpNewNo." Already Exists"

                        ]);
                }
                else{
                 
                   
                            if($this->case->addReceiptDetails($receiptStore))
                            {
                                return response()->json([
                                'status' => "sucess",
                                'message' => "Receipt Added Successfully"
                                ]);
                            }
                            else
                            {
                                return response()->json([
                                'status' => "fail",
                                'message' => "Something Went wrong!!"
                                ]);
                            }
                        }
            }
            else if($sbmt_val=='U')
            {

                if($this->case->updateReceiptDetails($receiptStore, $receiptStore['receiptsrno'],$receiptStore['receiptno']))
                {
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Receipt Updated Successfully",
                    ]);
                }
                else
                {
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went wrong!!"
                    ]);
                }

            }   
      
     }

    }
    public function receiptDetails(Request $request)
    {
		$request->validate([
		'receiptno' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
               
              ]);
			
        $receiptno = $request->get('receiptno');
    	$data['receiptDetails']=$this->case->getReceipts($receiptno);
        
    	echo json_encode($data['receiptDetails']);
    }
    public function receiptDet(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
               
              ]);
			
        $application_id = $request->post('application_id');

     //    $user = $request->session()->get('userName');
        $data['receiptDet']=$this->case->getReceiptsApplication($application_id);
        
       echo json_encode($data['receiptDet']);
    }

    public function applicantStore(Request $request)
    {
      
      $sbmt_applicant = $request->input('sbmt_applicant');


      

//print_r( $value); - raj
     if ($sbmt_applicant=='A')
        {
         $value=DB::table('applicant')->select('applicantsrno')->where('applicationid',$request->input('applApplId'))->orderBy('applicantsrno', 'desc')->first();

        if(! empty($value))
        {
          $applicantStore['applicantsrno'] = $value->applicantsrno+1;
        }
        else
        {
          $applicantStore['applicantsrno']= 1 ;
        }
      }
      else
    //      $applicantStore['applicantsrno']= $request->input('serialcount');
        $applicantStore['applicantsrno']= $request->input('applicantStartSrNo');
        
//echo $applicantStore['applicantsrno'];
        
        $applicantStore['applicationid'] = $request->input('applApplId');
        $applicantStore['depttype']=$request->input('applDeptType');
        $applicantStore['departcode']=$request->input('nameOfDept');
        $applicantStore['applicantname']=$request->input('applicantName');
        $applicantStore['nametitle']=$request->input('applicantTitle');
       
        $applicantStore['applicantage']=$request->input('applAge');
        $applicantStore['desigcode']=$request->input('desigAppl');
        $applicantStore['applicantaddress']=$request->input('addressAppl');
        $applicantStore['applicantpincode']=$request->input('pincodeAppl');
        $applicantStore['talukcode']=$request->input('talukAppl');
        $applicantStore['districtcode']=$request->input('districtAppl');
        $applicantStore['applicantmobileno']=$request->input('applMobileNo');
        $applicantStore['applicantemail']=$request->input('applEmailId');
        $applicantStore['applicantemail']=$request->input('applEmailId');
       
        //$applicantStore['applicantsrno']=$request->input('applicantStartSrNo');
        $applicantStore['partyinperson']=$request->input('partyInPerson');
       // $arr = explode('-',$request->input('relationType'));
      
        $applicantStore['relation']=$request->input('relationType');
        $applicantStore['applicantstatus']='Y';
        $applicantStore['partystatus']='Accepted';
        $applicantStore['statuschangedate']=date('Y-m-d');
        $applicantStore['petcausetitle']=$request->input('textcausetitle');
 
       //print_r($$applicantStore);
       
        
        if($applicantStore['applicantsrno']==1)
        {
            $applicantStore['ismainparty']='Y';
        }
        else
        {
             $applicantStore['ismainparty']='N';
        }

        if($request->input('partyInPerson')=='N')
        {
             $applicantStore['advocateregno'] =$request->input('advBarRegNo'); 
             $applicantStore['advocatecode'] =$request->input('appadvcode'); 
           
        }
        else
        {
             $applicantStore['advocateregno'] = '';
        }
        $applicantStore['issingleadv']='N';
        $relationtitle = $request->input('relationTitle');
        $applicantStore['relationtitle']=$relationtitle;
        $applicantStore['relationname']=$request->input('relationName');
        
       
        $applicantStore['gender']=$request->input('gender');
        $applicantStore['isgovtdept'] ='N';
        

         if($sbmt_applicant=='A')
        {
           $applicantStore['createdby']=  $request->session()->get('userName');
           $applicantStore['createdon']= date('Y-m-d H:i:s') ;
        }
        else
        {
           $applicantStore['updatedby']=  $request->session()->get('userName');
           $applicantStore['updatedon']= date('Y-m-d H:i:s') ;
        }


        
         $application_id = $request->input('applApplId');
       
     //   echo ('raj '.$sbmt_applicant);
     
        
       if($sbmt_applicant=='A')
        {  
        
                if($this->case->addApplicantDetails($applicantStore))
                {
                     $advdet['advdet']= array($request->input('applicantStartSrNo'),$request->input('advBarRegNo'),$request->input('advName'),$request->input('advTitle'),$request->input('advRegAdrr'),$request->input('advRegDistrict'), $request->input('advRegTaluk'),'Y',$request->input('advRegPin'));
                    
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Applicant Added Successfully",
                    'data'=>$advdet['advdet'],
                    'data2'=>'Y',

                    ]);
                    
                    
                 
                            
                }
                else
                {
                     return response()->json([
                        'status' => "fail",
                        'message' => "Please Try After Some Time",
                        ]);
                }
                   
        }
        else if($sbmt_applicant=='U')
        {   
            
//print_r($applicantStore);


                if($this->case->updateApplicantDetials($applicantStore,$applicantStore['applicantsrno']))
                {
                    
                            return response()->json([
                        'status' => "sucess",
                        'message' => "Applicant Updated Successfully",
                        
                        ]);
                }
                else
                {   
                     return response()->json([
                        'status' => "fail",
                        'message' => "Please Try After Some Time",
                        ]);
               }
        }
        
       // print_r($applicantStore);
        /**/
    }
    public function respondantStore(Request $request)
    {
        $repondantStore['applicationid'] = $request->input('resApplId');
        $repondantStore['respontdepttype']=$request->input('resDeptType');
        $repondantStore['respontdeptcode']=$request->input('resnameofDept');
        $repondantStore['respondname']=$request->input('respondantName');
        $repondantStore['respondtitle']=$request->input('respondantTitle');
        $repondantStore['respondantage']=$request->input('resAge');
        $repondantStore['desigcode']=$request->input('resDesig');
        $repondantStore['respondaddress']=$request->input('resAddress2');
        $repondantStore['respondpincode']=$request->input('respincode2');
        $repondantStore['respondtaluk']=$request->input('resTaluk');
        $repondantStore['responddistrict']=$request->input('resDistrict');
        $repondantStore['respondmobileno']=$request->input('resMobileNo');
        $repondantStore['respondemail']=$request->input('resEmailId');
        $repondantStore['ismainrespond']= 'N';
        $repondantStore['isgovtadvocate']=$request->input('isGovtAdv');
        $repondantStore['advocateregno']=$request->input('resadvBarRegNo');
        $repondantStore['respondsrno']=$request->input('resStartNo');
        $repondantStore['relationtitle']=$request->input('resRelTitle');
        $repondantStore['relationname']=$request->input('resRelName');
        $repondantStore['issingleadvocate'] = $request->input('isResAdvocate');
        // $respondantStore['isgovtadvocate'] = $request->input('isResAdvocate');
       // $repondantStore['relation']=$request->input('resRelName');
        $repondantStore['respondstatus']='Y';
        $repondantStore['partystatus']='Accepted';
        $repondantStore['statuschangedate']=date('Y-m-d');
        
        $repondantStore['rescausetitle']=$request->input('textcausetitle1');
 
        $arr = explode('-',$request->input('resReltaion'));
     
        $repondantStore['relation']=$arr[0];
        $repondantStore['gender']=$request->input('resGender');
        $sbmt_respondant = $request->input('sbmt_respondant');
                 

         if($sbmt_respondant=='A')
        {
           $repondantStore['createdby']=  $request->session()->get('userName');
           $repondantStore['createdon']= date('Y-m-d H:i:s') ;
        }
        else
        {
           $repondantStore['updatedby']=  $request->session()->get('userName');
           $repondantStore['updatedon']= date('Y-m-d H:i:s') ;
        }


      
      // print_r($repondantStore);
        
        if($sbmt_respondant=='A')
        {     
            if($this->case->addRespondantDetails($repondantStore))
            {
                $resadvdet['advdet']= array($request->input('resStartNo'),$request->input('resadvBarRegNo'),$request->input('respAdvName'),$request->input('respAdvTitle'),$request->input('resadvaddr'),$request->input('resadvdistrict'), $request->input('resadvtaluk'),'Y',$request->input('resadvpincode'));
                    /*$advdet['advdet']= $request->input('advTitle');
                    $advdet['advdet']= $request->input('advRegAdrr');
                    $advdet['advdet']= $request->input('advRegPin');
                    $advdet['advdet']= $request->input('advRegDistrict');
                    $advdet['advdet']= $request->input('advRegTaluk');
                    $advdet['advdet']= 'Y';*/
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Respondant Added Successfully",
                    'data'=>$resadvdet['advdet'],
                    'data2'=>'Y',
                   
                    ]);

                    
               
            }
            else
            {
                return response()->json([
                        'status' => "fail",
                        'message' => "Something Went wrong!!,Please Try Again",
                        ]);
            }
      
        }
        else if($sbmt_respondant=='U')
        {
           // echo $repondantStore['respondSrNo'];
            if($this->case->UpdateRespondantDetails($repondantStore,$repondantStore['respondsrno']))
            {
                return response()->json([
                        'status' => "sucess",
                        'message' => "Respondant Updated Successfully",
                        ]);
            }
            else
            {
                return response()->json([
                        'status' => "fail",
                        'message' => "Something Went wrong!!,Please Try Again",
                        ]);
            }
        }
       

       
    }


 public function extrarespondantStore(Request $request)
    {
        $repondantStore['applicationid'] = $request->input('resApplId');
        $repondantStore['respontdepttype']=$request->input('resDeptType');
        $repondantStore['respontdeptcode']=$request->input('resnameofDept');
        $repondantStore['respondname']=$request->input('respondantName');
        $repondantStore['respondtitle']=$request->input('respondantTitle');
        $repondantStore['respondantage']=$request->input('resAge');
        $repondantStore['desigcode']=$request->input('resDesig');
        $repondantStore['respondaddress']=$request->input('resAddress2');
        $repondantStore['respondpincode']=$request->input('respincode2');
        $repondantStore['respondtaluk']=$request->input('resTaluk');
        $repondantStore['responddistrict']=$request->input('resDistrict');
        $repondantStore['respondmobileno']=$request->input('resMobileNo');
        $repondantStore['respondemail']=$request->input('resEmailId');
        $repondantStore['ismainrespond']= 'N';
        $repondantStore['isgovtadvocate']=$request->input('isGovtAdv');
        $repondantStore['advocateregno']=$request->input('resadvBarRegNo');
        $repondantStore['respondsrno']=$request->input('resStartNo');
        $repondantStore['relationtitle']=$request->input('resRelTitle');
        $repondantStore['relationname']=$request->input('resRelName');
        $repondantStore['issingleadvocate'] = $request->input('isResAdvocate');
        // $respondantStore['isgovtadvocate'] = $request->input('isResAdvocate');
       // $repondantStore['relation']=$request->input('resRelName');
        $repondantStore['respondstatus']='Y';
        $repondantStore['partystatus']='Accepted';
        $repondantStore['statuschangedate']=date('Y-m-d');
        $arr = explode('-',$request->input('resReltaion'));
     
        $repondantStore['relation']=$arr[0];
        $repondantStore['gender']=$request->input('resGender');
        $sbmt_respondant = $request->input('sbmt_respondant');

         if($sbmt_respondant=='A')
        {
           $repondantStore['createdby']=  $request->session()->get('userName');
           $repondantStore['createdon']= date('Y-m-d H:i:s') ;
        }
        else
        {
           $repondantStore['updatedby']=  $request->session()->get('userName');
           $repondantStore['updatedon']= date('Y-m-d H:i:s') ;
        }


      $application_id = $request->input('resApplId');
      // print_r($repondantStore);

        if($sbmt_respondant=='A')
        {
		 $useractivitydtls['applicationid_receiptno'] =$application_id;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Added Extra Respondent' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
         $value = DB::transaction(function () use($application_id,$repondantStore,$useractivitydtls) {
                     $this->case->addRespondantDetails($repondantStore);
                     DB::table('application')->where('applicationid', $application_id)->increment('respondentcount',1);
					 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
                return true;
                });
              if($value==true)
                  {


            // if($this->case->addRespondantDetails($repondantStore))
            // {
                $resadvdet['advdet']= array($request->input('resStartNo'),$request->input('resadvBarRegNo'),$request->input('respAdvName'),$request->input('respAdvTitle'),$request->input('resadvaddr'),$request->input('resadvdistrict'), $request->input('resadvtaluk'),'Y',$request->input('resadvpincode'));
                    /*$advdet['advdet']= $request->input('advTitle');
                    $advdet['advdet']= $request->input('advRegAdrr');
                    $advdet['advdet']= $request->input('advRegPin');
                    $advdet['advdet']= $request->input('advRegDistrict');
                    $advdet['advdet']= $request->input('advRegTaluk');
                    $advdet['advdet']= 'Y';*/
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Respondant Added Successfully",
                    'data'=>$resadvdet['advdet'],
                    'data2'=>'Y',
                   
                    ]);

                    
               
            }
            else
            {
                return response()->json([
                        'status' => "fail",
                        'message' => "Something Went wrong!!,Please Try Again",
                        ]);
            }
      
        }
        else if($sbmt_respondant=='U')
        {
         $useractivitydtls['applicationid_receiptno'] =$application_id;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Update Extra Respondent' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
            if($this->case->UpdateRespondantDetails($repondantStore,$repondantStore['respondsrno']))
            {
				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                return response()->json([
                        'status' => "sucess",
                        'message' => "Respondant Updated Successfully",
                        ]);
            }
            else
            {
                return response()->json([
                        'status' => "fail",
                        'message' => "Something Went wrong!!,Please Try Again",
                        ]);
            }
        }
       

       
    }

   public function extraapplicantStore(Request $request)
    {
      
      $sbmt_applicant = $request->input('sbmt_applicant');


      

//print_r( $value); - raj
     if ($sbmt_applicant=='A')
        {
         $value=DB::table('applicant')->select('applicantsrno')->where('applicationid',$request->input('applApplId'))->orderBy('applicantsrno', 'desc')->first();

        if(! empty($value))
        {
          $applicantStore['applicantsrno'] = $value->applicantsrno+1;
        }
        else
        {
          $applicantStore['applicantsrno']= 1 ;
        }
      }
      else
    //      $applicantStore['applicantsrno']= $request->input('serialcount');
        $applicantStore['applicantsrno']= $request->input('applicantStartSrNo');
        
//echo $applicantStore['applicantsrno'];
        
        $applicantStore['applicationid'] = $request->input('applApplId');
        $applicantStore['depttype']=$request->input('applDeptType');
        $applicantStore['departcode']=$request->input('nameOfDept');
        $applicantStore['applicantname']=$request->input('applicantName');
        $applicantStore['nametitle']=$request->input('applicantTitle');
       
        $applicantStore['applicantage']=$request->input('applAge');
        $applicantStore['desigcode']=$request->input('desigAppl');
        $applicantStore['applicantaddress']=$request->input('addressAppl');
        $applicantStore['applicantpincode']=$request->input('pincodeAppl');
        $applicantStore['talukcode']=$request->input('talukAppl');
        $applicantStore['districtcode']=$request->input('districtAppl');
        $applicantStore['applicantmobileno']=$request->input('applMobileNo');
        $applicantStore['applicantemail']=$request->input('applEmailId');
        $applicantStore['applicantemail']=$request->input('applEmailId');
       
        //$applicantStore['applicantsrno']=$request->input('applicantStartSrNo');
        $applicantStore['partyinperson']=$request->input('partyInPerson');
       // $arr = explode('-',$request->input('relationType'));
      
        $applicantStore['relation']=$request->input('relationType');
        $applicantStore['applicantstatus']='Y';
        $applicantStore['partystatus']='Accepted';
        $applicantStore['statuschangedate']=date('Y-m-d');

       //print_r($$applicantStore);
       
        
        if($applicantStore['applicantsrno']==1)
        {
            $applicantStore['ismainparty']='Y';
        }
        else
        {
             $applicantStore['ismainparty']='N';
        }

        if($request->input('partyInPerson')=='N')
        {
             $applicantStore['advocateregno'] =$request->input('advBarRegNo'); 
             $applicantStore['advocatecode'] =$request->input('appadvcode'); 
           
        }
        else
        {
             $applicantStore['advocateregno'] = '';
        }
        $applicantStore['issingleadv']='N';
        $relationtitle = $request->input('relationTitle');
        $applicantStore['relationtitle']=$relationtitle;
        $applicantStore['relationname']=$request->input('relationName');
        
       
        $applicantStore['gender']=$request->input('gender');
        $applicantStore['isgovtdept'] ='N';
        

         if($sbmt_applicant=='A')
        {
           $applicantStore['createdby']=  $request->session()->get('userName');
           $applicantStore['createdon']= date('Y-m-d H:i:s') ;
        }
        else
        {
           $applicantStore['updatedby']=  $request->session()->get('userName');
           $applicantStore['updatedon']= date('Y-m-d H:i:s') ;
        }


        
         $application_id = $request->input('applApplId');
       
     //   echo ('raj '.$sbmt_applicant);
     
        if($sbmt_applicant=='A')
        {  
		 $useractivitydtls['applicationid_receiptno'] =$application_id;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Added Extra Applicant' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
          $value = DB::transaction(function () use($application_id,$applicantStore,$useractivitydtls) {
                     $this->case->addApplicantDetails($applicantStore);
                     DB::table('application')->where('applicationid', $application_id)->increment('applicantcount',1);
					 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
                return true;
                });
              if($value==true)
                  {
                     $advdet['advdet']= array($request->input('applicantStartSrNo'),$request->input('advBarRegNo'),$request->input('advName'),$request->input('advTitle'),$request->input('advRegAdrr'),$request->input('advRegDistrict'), $request->input('advRegTaluk'),'Y',$request->input('advRegPin'));
                                   return response()->json([
                                      'status' => "sucess",
                                      'message' => "Applicant Added Successfully",
                                      'data'=>$advdet['advdet'],
                                      'data2'=>'Y',
                                      ]);
                  }

        
                // if($this->case->addApplicantDetails($applicantStore))
                // {
                //      $advdet['advdet']= array($request->input('applicantStartSrNo'),$request->input('advBarRegNo'),$request->input('advName'),$request->input('advTitle'),$request->input('advRegAdrr'),$request->input('advRegDistrict'), $request->input('advRegTaluk'),'Y',$request->input('advRegPin'));
                    
                //     return response()->json([
                //     'status' => "sucess",
                //     'message' => "Applicant Added Successfully",
                //     'data'=>$advdet['advdet'],
                //     'data2'=>'Y',

                //     ]);
                    
                    
                 
                            
                // }
                else
                {
                     return response()->json([
                        'status' => "fail",
                        'message' => "Please Try After Some Time",
                        ]);
                }
                   
        }
        else if($sbmt_applicant=='U')
        {   
            
//print_r($applicantStore);


                if($this->case->updateApplicantDetials($applicantStore,$applicantStore['applicantsrno']))
                {
                    
                            return response()->json([
                        'status' => "sucess",
                        'message' => "Applicant Updated Successfully",
                        
                        ]);
                }
                else
                {   
                     return response()->json([
                        'status' => "fail",
                        'message' => "Please Try After Some Time",
                        ]);
               }
        }
        
       // print_r($applicantStore);
        /**/
    }






    public function applIndexStore(Request $request)
    {
       $sbmt_index = $request->input('applIndex_up_value');
            $i=0;
            $j=0;
            $msg='';
           // echo "asd";
            foreach ($request->input("partOfDoc") as $key) {

                $apllIndexStore['documentname']= $_POST['partOfDoc'][$i];
                $apllIndexStore['startpage']= $_POST['start'][$i];
                $apllIndexStore['endpage']= $_POST['endPage'][$i];
                $k=$i-1;$l=$i+1;
                if($i==0)
                {
                    if($_POST['start'][$i]!=1)
                    {
                        $msg="start no should be 1 in fisrt row\n";
                        $msg =  nl2br($msg);
                    }

                }
                else
                {
                    //echo "in else";
                    
                    
                    if($_POST['start'][$i]!=$_POST['endPage'][$k]+1)
                    {
                        $endPage =  $_POST['endPage'][$k]+1;

                       $msg1= "start no should be ".$endPage." in row".$l."\n"; 
                       $msg1 =  nl2br($msg1);
                       $msg=$msg."\n".$msg1;
                    }
                }


                if($_POST['endPage'][$i]<$_POST['start'][$i])
                {
                    $msg1="end no should be greater than start no in row ".$l."\n";
                    $msg1 =  nl2br($msg1);
                    $msg=$msg."\n".$msg1;
                }
                $my_url = $_POST['partOfDoc'][$i];
                $numpattern='/\A[-\w .,!()`,\n\r]+\z/';
                ///^([0-9]+)$
                if (!preg_match( $numpattern, $my_url))
                {
                    $msg1="Invalid Part of Document ".$l."\n";
                    $msg1 =  nl2br($msg1);
                    $msg=$msg."\n".$msg1;
                }

                $i++;
                $j++;
                


                /*$apllIndexStore['documentdate'] = date('Y-m-d');
                $apllIndexStore['documentno'] =$j+1;
                $apllIndexStore['applicationid'] =  $new_appl_id;
                $apllIndexStore['createdby']=$request->session()->get('userName');
                $update2=  $this->case->addApplicationIndex($apllIndexStore);*/
                
                
                    
            }
            if($msg!='')
            {
                return response()->json([
                'status' => "errormsg",
                'message' => $msg
                ]);
                return false;
            }
            

         $application_id =$request->input('applIndexId');
         $sbmt_index = $request->input('applIndex_up_value');
         $user = $request->session()->get('userName');
         if($sbmt_index=='U')
        {
                $new_appl_id =ltrim($application_id, 'T');
        }
        else
        {
           $establishcode = $request->session()->get('EstablishCode');
           $new_appl_id = ltrim($application_id, 'T');
             $reviewApplId= $request->input('reviewApplId');
             $flag='application';
            $app['application'] = $this->case->getAllApplicationDetails($application_id,$user,$flag,$establishcode);
            $applDet['applicationid']= $new_appl_id;
            $applDet['applicationdate'] = $app['application'][0]->applicationdate;
            $applDet['applicationyear'] =  $app['application'][0]->applicationyear;
            $applDet['appltypecode'] =  $app['application'][0]->appltypecode;
            $applDet['applicationsrno'] =  $app['application'][0]->applicationsrno;
            $applDet['applicationtosrno'] =  $app['application'][0]->applicationtosrno;
            $applDet['serviceaddress'] =  $app['application'][0]->serviceaddress;
            $applDet['servicepincode'] =  $app['application'][0]->servicepincode;
            $applDet['servicetaluk'] =  $app['application'][0]->servicetaluk;
            $applDet['servicedistrict'] =  $app['application'][0]->servicedistrict;
            $applDet['advocateregnno'] =  $app['application'][0]->advocateregnno;
            $applDet['actcode'] =  $app['application'][0]->actcode;
            $applDet['actsectioncode'] =  $app['application'][0]->actsectioncode;
            $applDet['totalamount'] =  $app['application'][0]->totalamount;
            $applDet['applcategory'] =  $app['application'][0]->applcategory;
            $applDet['subject'] =  $app['application'][0]->subject;
            $applDet['interimprayer'] =  $app['application'][0]->interimprayer;
            $applDet['registerdate']=$app['application'][0]->registerdate;
            $applDet['isinterimrelief'] =  $app['application'][0]->isinterimrelief;
            $applDet['advocatesingle'] =  $app['application'][0]->advocatesingle;
            $applDet['applicantcount'] =  $app['application'][0]->applicantcount;
            $applDet['respondentcount'] =  $app['application'][0]->respondentcount;
            $applDet['resadvsingle'] =  $app['application'][0]->resadvsingle;
            $applDet['rserviceaddress'] =  $app['application'][0]->rserviceaddress;
            $applDet['rservicetaluk'] =  $app['application'][0]->rservicetaluk;
            $applDet['rservicedistrict'] =  $app['application'][0]->rservicedistrict;
            $applDet['againstorders']= $app['application'][0]->againstorders;
            $applDet['remarks']=$app['application'][0]->remarks;
            $applDet['createdby'] = $request->session()->get('userName');
            $applDet['createdon']= $app['application'][0]->createdon;
			$applDet['establishcode'] = $establishcode;
        }  
        
          
         
         
        // echo $application_id;
          DB::table('applicationindex')->where('applicationid',$application_id)->delete();
         if($sbmt_index=='U')
         {
            $i=0;
            $j=0;
            foreach ($request->input("partOfDoc") as $key) {
                $apllIndexStore['documentname']= $_POST['partOfDoc'][$i];
                $apllIndexStore['startpage']= $_POST['start'][$i];
                $apllIndexStore['endpage']= $_POST['endPage'][$i];
                $apllIndexStore['documentdate'] = date('Y-m-d');
                $apllIndexStore['documentno'] =$j+1;
                $apllIndexStore['applicationid'] =  $new_appl_id;

                  
       
           $apllIndexStore['updatedby']=  $request->session()->get('userName');
           $apllIndexStore['updatedon']= date('Y-m-d H:i:s') ;
       


                //$apllIndexStore['createdby']=$request->session()->get('userName');
                 
                $update = $this->case->updateApplIndex($application_id,$apllIndexStore);
                            
                $i++;
                $j++;
            }
                    if($update==true )
                    {
                    return response()->json([
                        'status' => "sucess",
                        'message' => "Application  Updated Successfully"
                        ]);
                    }
                    else
                    {
                    return response()->json([
                        'status' => "fail",
                        'message' => "Something Went wrong!!"
                        ]);
                    }
         
         }
         else
         {
            $i=0;
            $j=0;
           // echo "asd";
            foreach ($request->input("partOfDoc") as $key) {

                $apllIndexStore['documentname']= $_POST['partOfDoc'][$i];
                $apllIndexStore['startpage']= $_POST['start'][$i];
                $apllIndexStore['endpage']= $_POST['endPage'][$i];
                $apllIndexStore['documentdate'] = date('Y-m-d');
                $apllIndexStore['documentno'] =$j+1;
                $apllIndexStore['applicationid'] =  $new_appl_id;
                 $apllIndexStore['createdby']=  $request->session()->get('userName');
            $apllIndexStore['createdon']= date('Y-m-d H:i:s') ;
                $update2=  $this->case->addApplicationIndex($apllIndexStore);
                
                $i++;
                  $j++;
                    
            }
            if($update2==true )
            {
                    if($this->case->addApplIndexStore($applDet,$application_id,$new_appl_id,$reviewApplId))
                    {
                     return response()->json([
                    'status' => "sucess",
                    'message' => "Application Added Successfully"
                    ]);
                    }
                    else
                    {
                         return response()->json([
                    'status' => "fail",
                    'message' => "Something Went wrong!!"
                    ]);
                    }
                    }
                   
         }
         
        
           /* foreach ($request->input("partOfDoc") as $key) {
                $apllIndexStore['documentname']= $_POST['partOfDoc'][$i];
                $apllIndexStore['startpage']= $_POST['start'][$i];
                $apllIndexStore['endpage']= $_POST['endPage'][$i];
                $apllIndexStore['documentdate'] = date('Y-m-d');
                $apllIndexStore['documentno'] =$_POST['count'][$i];
                $apllIndexStore['applicationid'] =  $new_appl_id;
                $apllIndexStore['createdby']=$request->session()->get('userName');
                
                    if($sbmt_index=='U')
                    {

                        DB::table('applicationindex')->where('applicationid',$application_id)->delete();
                           if($this->case->updateApplIndex($application_id,$apllIndexStore))
                            {
                                return response()->json([
                                    'status' => "sucess",
                                    'message' => "Application Index Updated Successfully"
                                    ]);
                            }
                            else
                            {
                                return response()->json([
                                    'status' => "fail",
                                    'message' => "Something Went wrong!!"
                                    ]);
                            }
                           
                    }
                    else
                    {
                        $this->case->addApplicationIndex($apllIndexStore);
                      
                    }
              $i++
            }*/
            /*if($sbmt_index!='U')
            {
                echo "asd";
                if($this->case->addApplIndexStore($applDet,$application_id,$new_appl_id,$reviewApplId))
                {
                return response()->json([
                'status' => "fail",
                'message' => "Something Went wrong!!"
                ]);
                }
                else
                {
                return response()->json([
                'status' => "sucess",
                'message' => "Application Index Added Successfully"
                ]);



                }
            }*/
         

       

    }
    public function getAdvRegNo(Request $request)
    {
        $advbarregNo = $_POST['value'];
        $data['advocateDetails'] = $this->case->getAdvDetails($advbarregNo);
        echo json_encode($data['advocateDetails']);
    }
    public function getResAdvReg(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),                 
              ]);		
        $application_id = $_POST['application_id'];
        $data['resadvdetails'] = $this->case->getResAdv($application_id);
        echo json_encode($data['resadvdetails']);
    }


     public function ApplicantDetails(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),                 
              ]);	
      // $applSrNo = $_POST['newSrno'];
        $application_id = $_POST['application_id'];
        $data['ApplserailDet'] = $this->case->getApplicantSerialNoDet($application_id);
       // print_r($data['ApplserailDet']);
        echo json_encode($data['ApplserailDet']);
    }
    public function RespondantDetails(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),                 
              ]);	
        $application_id = $_POST['application_id'];
        $data['ResserailDet'] = $this->case->getRespondantSerialNoDet($application_id);
        echo json_encode($data['ResserailDet']);
    }
    public function RespondantSerialDetails(Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),                 
              ]);	
        $newSrno = $_POST['newSrno'];
         $applicationid = $_POST['applicationid'];
        $data['ResserailDet'] = $this->case->getRespondantSerialNoDetails($newSrno,$applicationid);
        echo json_encode($data['ResserailDet']);
    }
    public function ApplicantSerialDetails(Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),                 
              ]);	
        $newSrno = $_POST['newSrno'];
        $applicationid = $_POST['applicationid'];
        $data['ApplserailNoDet'] = $this->case->getApplicantSerialDetails($newSrno,$applicationid);
       // print_r($data['ApplserailNoDet']);
        echo json_encode($data['ApplserailNoDet']);
    }
    
    public function ApplicationDetails(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
		'flag' => array(
            'required'
            // 'regex:/^[a-zA-Z_\/]+$/',
            // 'max:20'
          ), 				
              ]);
       $establishcode = $request->session()->get('EstablishCode');    
       $application_id = $_POST['application_id'];
       $flag=$_POST['flag'];
       $user = $request->session()->get('userName');
      
       $data['ApplicationDet'] = $this->case->getAllApplicationDetails($application_id,$user,$flag,$establishcode);
       echo json_encode($data['ApplicationDet']);
    }
	
	 public function getApplicationDetailsDisposed(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
		 				
              ]);
       $establishcode = $request->session()->get('EstablishCode');    
       $application_id = $_POST['application_id'];
       $user = $request->session()->get('userName');
      
       $data['ApplicationDet'] = $this->case->getAllApplicationDetailsDisposed($application_id,$user,$establishcode);
       echo json_encode($data['ApplicationDet']);
    }

 public function getApplTypeReferDetails(Request $request)
    {
       $establishcode = $request->session()->get('EstablishCode');
    
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
	          
              ]);
       $application_id = $_POST['application_id'];
       $user = $request->session()->get('userName');
       $ApplTypeRefer = $this->case->getApplTypeRefer($application_id,$user);
       $data['ApplTypeReferDet'] = $this->case->getAllApplicationDetailsDisposed($ApplTypeRefer[0]->referapplid,$user,$establishcode);
       echo json_encode($data['ApplTypeReferDet']);
    }



   public function ApplicationDetailsCauseList(Request $request)
      {
		  
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
		 'flag' => array(
            'required'
            // 'regex:/^[a-zA-Z_\/]+$/',
            // 'max:20'
          ),        
              ]);
         $application_id = $_POST['application_id'];
         $flag=$_POST['flag'];
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');    
         $data['ApplicationDet'] = $this->case->getApplicationDetailsCauseList($application_id,$user,$flag,$establishcode);
         echo json_encode($data['ApplicationDet']);
      }


      public function ApplicationSrCount(Request $request)
      {
      $request->validate([
      'application_id' => array(
              'required'
              // 'regex:/^[0-9a-zA-Z_\/]+$/',
              // 'max:20'
            ),  
        ]);
          $application_id = $_POST['application_id'];

          $SrCount = $this->case->getSerialCount($application_id);
          echo $SrCount; 
      }

      public function LastApplicantSrNo(Request $request)
      {
      $request->validate([
      'application_id' => array(
              'required'
              // 'regex:/^[0-9a-zA-Z_\/]+$/',
              // 'max:20'
            ),  
        ]);
          $application_id = $_POST['application_id'];
          $data['srno'] = $this->case->getLastSrno($application_id);        
        
          if($data['srno']!='')
          {
              echo json_encode($data['srno']);
          }
          else
          {
              echo "null";
          }
          
       
      }
     public function RespondantSrCount(Request $request)
    {
       // echo "in sr";
	  $request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
		  ]);
        $application_id = $_POST['application_id'];
        $ResSrCount = $this->case->getRespSerialCount($application_id);
        echo $ResSrCount; 
    }
    public function LastRespondantSrNo(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
		  ]);
        $application_id = $_POST['application_id'];
        $data['ressrno'] = $this->case->getRespLastSrno($application_id);
      echo json_encode($data['ressrno']);
    }
/*    public function getAppIDExistance(Request $request){
    $application_id = $_POST['application_id'];
    $startno = $_POST['startno'];
     $applType = $_POST['applType'];
    $applYear = $_POST['applYear'];
    $endno = $_POST['endno'];
    $appCount= $this->case->getApplicationExistanceCount($application_id,$startno,$applType,$applYear,$endno);
      echo $appCount;
    }*/
 /*   public function getReceiptExistance(Request $request)
    {
       $recpNewNo = $_POST['recpNewNo'];
       $recpCount= $this->case->getReceiptExistanceCount($recpNewNo);
      echo $recpCount;
    }*/
    public function AddreliefDetails(Request $request)
    {

        $relief_value = $_POST['relief_value'];
        $reliefStore['applicationid'] =$_POST['applId'];
        $reliefStore['relief'] = $_POST['reliefsought'];
        $user = $request->session()->get('userName');
        $reliefsrno_1 =$request->input('reliefcount');

      
        if($relief_value=='A')
        {
           $reliefStore['createdby']= $user;
           $reliefStore['createdon']= date('Y-m-d H:i:s') ;
        }
        else
        {
           $reliefStore['updatedby']= $user;
           $reliefStore['updatedon']= date('Y-m-d H:i:s') ;
        }
       // $reliefStore['createdby'] = $user;
        if($relief_value=='A')
        {
                $value = DB::table('applrelief')->select('reliefsrno')->where('applicationid',$reliefStore['applicationid'])->orderBy('reliefsrno', 'asc')->get()->last();


                if($value=='')
                {

                $reliefStore['reliefsrno'] = 1;
                }
                else
                {

                $reliefStore['reliefsrno'] = ($value->reliefsrno)+1; 

                }
        }
        else
        {
             $reliefStore['reliefsrno'] = $reliefsrno_1;
        }
       
        
   
       $relief_value = $_POST['relief_value'];
      
       if($relief_value=='A')
       {
          if($this->case->addReliefDetails($reliefStore))
          {
        return response()->json([
        'status' => "sucess",
        'message' => "Relief  Added Successfully"
        ]);
        }
        else
        {
        return response()->json([
        'status' => "fail",
        'message' => "Something Went wrong!!"
        ]);
        }
       }
       else
       {
            if($this->case->updateReliefDetails($reliefStore,$reliefsrno_1, $reliefStore['applicationid']))
            {
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Relief Updated Successfully"
                    ]);
            }
            else
            {   
                return response()->json([
        'status' => "fail",
        'message' => "Something Went wrong!!"
        ]);

            }
       }
     
    }
    public function getReliefSought(Request $request)
    {
       /* if($_POST['relief_value']=='Edit')
        {
            $applicationId = ltrim($_POST['applId'],'T');
        }
        else
        {
            $applicationId = $_POST['applId'];
        }*/
		$request->validate([
		'applId' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
               
              ]);
        $applicationId = $_POST['applId'];
        $user = $request->session()->get('userName');
        $data['relief']  = $this->case->getRelief($applicationId,$newSrno='',$user);
       
        echo json_encode($data['relief']);
    }
    public function getReliefSrSought(Request $request)
    {
		$request->validate([
		'applId' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
         ]);
        $applicationId = $_POST['applId'];
        $newSrno = $_POST['newSrno'];
         $user = $request->session()->get('userName');
         $data['reliefsr']  = $this->case->getRelief($applicationId,$newSrno,$user);
        echo json_encode($data['reliefsr']);
    }

 public function causetitletext(Request $request)
{    $applicantTitle=$_POST['applicantTitle'];
  $applicantTitle=strtoupper($applicantTitle);
$applicantname = $_POST['applicantname'];
$applicantname=strtoupper($applicantname);
$relationtype=$_POST['relationtype'];
$relationtype=strtoupper($relationtype);
$releationshipname=$_POST['releationshipname'];
$releationshipname=strtoupper($releationshipname);
$departmentaddress=$_POST['departmentaddress'];
$pincodeAppl=$_POST['pincodeAppl'];
$age=$_POST['age'];
$deptcode=$_POST['deptcode'];
$designationcode=$_POST['designation'];
$distcode=$_POST['distcode'];
$talukcode=$_POST['talukcode'];
$aged="AGED ABOUT ".$age." YEARS";
// $respondantTitle=$_POST['respondantTitle'];
// $var=explode('-',$respondantTitle);
// $respondantTitle=$var[0];
//         $user = $request->session()->get('userName');
$designation=DB::SELECT("SELECT designame from designation where desigcode='$designationcode'")[0]->designame;
$distname=DB::SELECT("SELECT distname from district where distcode='$distcode'")[0]->distname;
$talukname=DB::SELECT("SELECT talukname from taluk where talukcode='$talukcode' and distcode='$distcode'")[0]->talukname;
//   $data['reliefsr']  = $this->case->getRelief($applicationId,$newSrno,$user);
$departmentname=DB::SELECT("SELECT departmentname from department where departmentcode='$deptcode'")[0]->departmentname;
$departmentname=strtoupper($departmentname);
$departmentname = str_replace("\n", '', $departmentname);

$departmentaddress=strtoupper($departmentaddress);
$releationshipname1=DB::SELECT("SELECT relationname from relationtitle where relationtitle='$relationtype'")[0]->relationname;
$data['causetitle']=pg_escape_string($applicantTitle.' '.$applicantname."\n".$releationshipname1.' '.$releationshipname."\n".$aged."\n".$designation."\n".$departmentname."\n".$departmentaddress."\n".$pincodeAppl);
echo json_encode($data['causetitle']);
}

public function causetitletext1(Request $request)
{
$respondantName = $_POST['respondantName'];
$respondantName=strtoupper($respondantName);
$resReltaion=$_POST['resReltaion'];
$resReltaion=strtoupper($resReltaion);
$releationshipname=$_POST['resRelName'];
$releationshipname=strtoupper($releationshipname);
$resAddress2=strtoupper($_POST['resAddress2']);
$respincode2=$_POST['respincode2'];
$resAge1=$_POST['resAge'];
$deptcode=$_POST['deptcode'];
$designationcode=$_POST['designation'];
//dd($designationcode);
$distcode=$_POST['distcode'];
$talukcode=$_POST['talukcode'];

$resAge="AGED ABOUT ".$resAge1." YEARS";
//         $user = $request->session()->get('userName');
$designation=DB::SELECT("SELECT designame from designation where desigcode='$designationcode'")[0]->designame;
$designation=strtoupper($designation);
$distname=DB::SELECT("SELECT distname from district where distcode='$distcode'")[0]->distname;
$talukname=DB::SELECT("SELECT talukname from taluk where talukcode='$talukcode' and distcode='$distcode'")[0]->talukname;
//   $data['reliefsr']  = $this->case->getRelief($applicationId,$newSrno,$user);
$departmentname=DB::SELECT("SELECT departmentname from department where departmentcode='$deptcode'")[0]->departmentname;
$departmentname=strtoupper($departmentname);
$departmentname = str_replace("\n", '', $departmentname);

$departmentaddress=strtoupper($resAddress2);
$releationshipname1=DB::SELECT("SELECT relationname from relationtitle where relationtitle='$resReltaion'")[0]->relationname;
$releationshipname1=strtoupper($releationshipname1);
if($resAge1==null or $resAge1=='0')
{
$data['causetitle']=pg_escape_string($respondantName."\n".$releationshipname1.' '.$releationshipname."\n".$designation."\n".$departmentname."\n".$resAddress2."\n".$respincode2);

}
else{
$data['causetitle']=pg_escape_string($respondantName."\n".$releationshipname1.' '.$releationshipname."\n".$resAge."\n".$designation."\n".$departmentname."\n".$resAddress2."\n".$respincode2);

}

echo json_encode($data['causetitle']);
}







    public function getSinglApplAdv(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
         ]);
         $applicationid = $_POST['application_id'];
       
         $singleadv = $this->case->getSingleAdvCount($applicationid);
         echo $singleadv; 
    }
    public function getResSinglApplAdv(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
         ]);
         $applicationid = $_POST['application_id'];
       
         $ressingleadv = $this->case->getResSingleAdvCount($applicationid);
         echo $ressingleadv; 
    }
    public function getSingleAdvDetails(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
         ]);
         $applicationid = $_POST['application_id'];
       
         $data['singleadv2'] = $this->case->getSingleAdvData($applicationid);
         echo  json_encode($data['singleadv2']); 
    }
    public function getResSingleAdvDetails(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
         ]);
         $applicationid = $_POST['application_id'];
       
         $data['ressingleadv2'] = $this->case->getResSingleAdvData($applicationid);
         echo  json_encode($data['ressingleadv2']); 
    }



  public function getApplBasedOnIDFreshApplication(Request $request)
    {
		/*$request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
         ]);  */
        $applicationid = $_POST['applicationid'];
        $user = $_POST['user'];
        $data['flag']='E';
        $data['Temp'] = $this->case->getApplicationId($applicationid,$user);
       
            if(count($data['Temp'])==0)
            {
               return response()->json([
            'status' => 'success',
            'message'=>'Application Does Not Exist']);
            }
           
        if( count($data['Temp'])>0)
            {
                 $data['taluka3'] = $this->case->getTaluka($data['Temp'][0]->servicedistrict); 
                 
            }
            else
            {
                    $data['taluka3'] = $this->case->getTaluka($distCode='');
                
            }

           
        $data['Temprelief'] = $this->case->getRelief($applicationid,$newsrno='',$user);
         $data['TempReceipt'] = $this->case->getReceiptDetails($applicationid,$user);
        $data['TempApplicant'] = $this->case->getApplicantDetails($applicationid,$user);
        $data['TempRespondant'] = $this->case->getRespondantDetails($applicationid,$user);
        $data['TempApplTypeRefer'] = $this->case->getApplTypeRefer($applicationid,$user);
         $data['ApplicationIdex'] = $this->case->getApplicationIndex($applicationid,$user);

        $options = view('Application.caseData',$data)->render();
        echo json_encode($options);

    }

    public function getApplBasedOnID(Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required'
						//'regex:/^[0-9a-zA-Z_\/]+$/',
					//	'max:20'
					),  
         ]);
        $applicationid = $_POST['applicationid'];
        $user = $_POST['user'];
        $data['flag']='E';
        $data['Temp'] = $this->case->getApplicationId($applicationid,$user);
       
            if(count($data['Temp'])==0)
            {
               return response()->json([
            'status' => 'success',
            'message'=>'Application Does Not Exist']);
            }
           
        if( count($data['Temp'])>0)
            {
                 $data['taluka3'] = $this->case->getTaluka($data['Temp'][0]->servicedistrict); 
                 
            }
            else
            {
                    $data['taluka3'] = $this->case->getTaluka($distCode='');
                
            }

           
        $data['Temprelief'] = $this->case->getRelief($applicationid,$newsrno='',$user);
         $data['TempReceipt'] = $this->case->getReceiptDetails($applicationid,$user);
        $data['TempApplicant'] = $this->case->getApplicantDetails($applicationid,$user);
        $data['TempRespondant'] = $this->case->getRespondantDetails($applicationid,$user);
        $data['TempApplTypeRefer'] = $this->case->getApplTypeRefer($applicationid,$user);
         $data['ApplicationIdex'] = $this->case->getApplicationIndex($applicationid,$user);

        $options = view('case/caseData',$data)->render();
        echo json_encode($options);

    }
    public function deleteApplication(Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),  
         ]);
        $applicationid = $_POST['applicationid'];
        $user = $request->session()->get('userName');
        $response = $this->case->deleteApplicationId($applicationid,$user);
        if($response)
        {
            return response()->json([
            'status' => 'success',
            'message'=>'Application Deleted Successfully']);
        }
        else
        {
            return response()->json([
            'status' => 'fail',
            'message'=>'Something Went Wrong!!']);
        }

    }
     public function getDepartmentNames(Request $request)
    {
		$request->validate([
             'typeval' => 'required|numeric',          
         ]);  
        $typeval = $request->input('typeval');
       $data['depNames'] = $this->case->getDeptNames($typeval);
       echo json_encode($data['depNames']);
    }

    public function getDesignation(Request $request)
    {
       $data['appldesig'] =  $this->case->getDesignation();
       echo json_encode($data['appldesig']);
    }
    
   public function getDesignationByDepartment(Request $request)
    {
	   $request->validate([
             'typeval' => 'required|numeric',          
         ]);  
       $typeval = $request->input('typeval');
       $data['appldesig'] =  $this->case->getDesignationByDepartment($typeval);
       echo json_encode($data['appldesig']);
    }

    public function getAdvocate()
    {
         $data['adv']=$this->case->getAdv();
         echo json_encode($data['adv']);
    }
    public function getSections(Request $request)
    {
      $request->validate([
             'typeval' => 'required|numeric',          
         ]);  
        $typeid = $_POST['typeval'];
        $data['sections'] = $this->case->getSections($typeid);
        echo json_encode($data['sections']);
    }
    public function getUserApplCount(Request $request)
    {
      $userid = $_POST['userid'];
      $data['userappcount2'] = $this->case->getUserAppcount($userid);
     // print_r($data['userappcount2']);
      echo json_encode($data['userappcount2']);
    }

    public function getApplicantRespondantDetails(Request $request)
    {
		$request->validate([
		'application_id' => array(
						'required'
						// 'regex:/^[0-9a-zA-Z_\/]+$/',
						// 'max:20'
					),                 
              ]);		
       $application_id = $_POST['application_id'];
       $user = $request->session()->get('userName');
       $data['applicantDetails'] = $this->case->getApplicantDetails($application_id,$user);
       $data['respondantDetails'] = $this->case->getRespondantDetails($application_id,$user);
       echo json_encode(array($data['applicantDetails'], $data['respondantDetails'])); 
       //echo json_encode($data['applicantDetails']);
       }
    
}
