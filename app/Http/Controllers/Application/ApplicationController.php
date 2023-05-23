<?php
namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\DisposedApplicationModel;
use App\RestoreApplicationModel;
use Session;
use App\IANature;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\UserActivityModel;
use Illuminate\Database\QueryException;
class ApplicationController extends Controller
{

	public function __construct()
	{
		$this->IANature = new IANature();
		$this->case = new CaseManagementModel();
		$this->disposedApplication =  new DisposedApplicationModel();
		$this->restoreApplication =  new RestoreApplicationModel();
		$this->UserActivityModel = new UserActivityModel();
  }

  public function Circular(Request $request)
  {
        return view('circular');

  }
  public function registerUnnumber(Request $request){
        $establishcode = $request->session()->get('EstablishCode');

    $data['applicationType1'] =DB::SELECT("SELECT * from applicationtype where appltypecode>='11' and appltypecode <='14' order by appltypecode");
    $data['establishname'] = $this->case->getEstablishName($establishcode);
    $data['actDetails'] = $this->case->getActDetails();
    $data['sectionDetails'] = $this->case->getSectionDetails();
    $data['applicationType'] =DB::SELECT("SELECT * from applicationtype where appltypecode>='11' and appltypecode <='14' order by appltypecode");
   
    $data['applCategory'] = $this->case->getApplCategory();
    $data['district'] = $this->case->getDistrict();
    $data['taluka'] = $this->case->getTaluka($distCode='');
    return view('Application.ConvertRegularNumber',$data);
  }

   public function getApplDetails(Request $request)
    {
    $request->validate([
    'applicationid' => array(
            'required',
            'regex:/^[0-9a-zA-Z_\/]+$/',
            'max:20'
          ),  
      
              ]);
       $establishcode = $request->session()->get('EstablishCode');    
       $application_id = $_POST['applicationid'];
       $var=explode('/',$application_id);
       $appltypeshort=$var[0];
       $appltypecode=DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$appltypeshort' ")[0]->appltypecode;
       $appnum1=$var[1];
       $applyear=$var[2];

     
    $data['ApplicationDet'] = DB::SELECT("SELECT * from application where applicationid='$application_id' and
         establishcode='$establishcode'");
    if($data['ApplicationDet']==null){
       return response()->json([
              'status' => "error",
              'message' => "Application  Does Not Exist or it is already converted to regular number "
                ]);

             }
     echo json_encode($data['ApplicationDet']);
    }

       
    
  

	public function index(Request $request)
   {
		$establishcode = $request->session()->get('EstablishCode');
		$data['establishname'] = $this->case->getEstablishName($establishcode);
		$data['actDetails'] = $this->case->getActDetails();
		$data['sectionDetails'] = $this->case->getSectionDetails();
		$data['applicationType'] =DB::SELECT("SELECT * from applicationtype where appltypecode>='11' and appltypecode <='14' order by appltypecode");
    $data['applicationType1'] =DB::SELECT("SELECT * from applicationtype where appltypecode>='1' and appltypecode <='4'");
		$data['applCategory'] = $this->case->getApplCategory();
		$data['district'] = $this->case->getDistrict();
		$data['taluka'] = $this->case->getTaluka($distCode='');
		return view('Application.FreshApplication',$data);
   }

public function conversionUnnumber(Request $request)
{
   $request->validate([
                'applTypeName' => 'required',
                'applno' => 'required ',
                'registerdate'=>'required|date',
            ]);

        $arr=explode('-',$request->input('applTypeName'));
       
        $establishcode = $request->session()->get('EstablishCode');
        $appltypecode1 = $arr[0];
        $appltypeshort = $arr[1];
        $applno=$request->input('applno');
        $registerdate=date('Y-m-d',strtotime($request->input('registerdate')));
        
        $var=explode('-',$registerdate);
        $regYear=$var[0];

        $unapplicationid=$appltypeshort.'/'.$applno;
        $temp=explode('/',$applno);
        $appnum1=$temp[0];
        $applyear=$temp[1]; 

        
        if($appltypeshort=='UA')
        {
          $appltypeshort='OA';
        }
        else if($appltypeshort=='UC')
        {
          $appltypeshort='CA';
        }
        else if($appltypeshort=='UR')
        {
          $appltypeshort='RA';
        }
        else{
          $appltypeshort='MA';
        }
       
        
         DB::beginTransaction();
    
           try{
       // DB::beginTransaction();

       $value=DB::SELECT("SELECT * from unapplication where  unapplicationid='$unapplicationid'");
        if($value==null){
         
            return redirect()->route('registerUnnumber')->with('error', 'No details Found');
          }
        

         
        
              
         
          if($value[0]->applicationid!='')
           {
           $value1=$value[0]->applicationid;
           return redirect()->route('registerUnnumber')->with('error', 'This Application is already Converted to '.$value1);
           }



      
         $appltypecode= DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$appltypeshort'");
         $appltypecode=$appltypecode[0]->appltypecode;
         $var=$value;

    
            
              $unapplicationtosrno=$var[0]->unapplicationtosrno;
              $unapplicationsrno=$var[0]->unapplicationsrno;
              $unapplicationyear=$var[0]->unapplicationyear;

              if($unapplicationsrno!=$unapplicationtosrno){
              $noOfAppl=($unapplicationtosrno-$unapplicationsrno)+1;
              }
              else{
                $noOfAppl=1;
              } 

              $applYear=$unapplicationyear;
              $applcount=$noOfAppl;

        $nextno =  DB::select("SELECT f_applicationsrno('$establishcode','$appltypeshort','$regYear','$applcount') as nin");
        $no= $nextno[0]->nin;
        $srno=explode(',',$no);

        $establishcode = $establishcode;
        $applicationsrno = $srno[0];
        $applicationtosrno = $srno[1];
        
        $applicationid= $appltypeshort.'/'.$applicationsrno.'/'.$regYear;
        $applicationno=$applicationsrno;
        
        if($applicationsrno!=$applicationtosrno){

        $applicationno .= '-'.$applicationtosrno;
        }

        $applicationno_display =  $appltypeshort.'/'.$applicationno.'/'.$regYear;
        
    DB::SELECT("UPDATE application set registerdate='$registerdate',applicationyear='$regYear', appltypecode='$appltypecode',applicationid='$applicationid',applicationsrno='$applicationsrno',applicationtosrno='$applicationtosrno' where applicationid='$unapplicationid'");
         $user_session=$request->session()->get('username');
         $upatedon=  date('Y-m-d H:i:s') ;
         $updatedon_registerdate=date('Y-m-d');
  DB::SELECT("UPDATE unapplication set applicationid='$applicationid',updatedon='$upatedon',updatedby='$user_session',registerdate='$updatedon_registerdate' where unapplicationid='$unapplicationid'");


       $value_groupno=DB::SELECT("SELECT * from groupno where  applicationid='$unapplicationid'");
        if($value_groupno!=null){
         
            $applicationsrno=$value_groupno[0]->applicationsrno;
            $applicationtosrno=$value_groupno[0]->applicationtosrno;

            $noOfGroupApp=($applicationtosrno-$applicationsrno)+1;
           
        $nextno =  DB::select("SELECT f_applicationsrno('$establishcode','$appltypeshort','$applyear','$noOfGroupApp') as nin");
        $no= $nextno[0]->nin;
        $srno=explode(',',$no);

        $establishcode = $establishcode;
        $applicationsrno = $srno[0];
        $applicationtosrno = $srno[1];
        
      // $applicationid= $appltypeshort.'/'.$applicationsrno.'/'.$regYear;
        $applicationno=$applicationsrno;
        
        if($applicationsrno!=$applicationtosrno){

        $applicationno .= '-'.$applicationtosrno;
        }

        $applicationno_display1 =  $appltypeshort.'/'.$applicationno.'/'.$regYear;
        DB::SELECT("UPDATE groupno set applicationid='$applicationid',applicationsrno='$applicationsrno',applicationtosrno='$applicationtosrno'
           where applicationid='$unapplicationid'");
      
       }
	 
//	 $user_session=$request->session()->get('username');
//         $upatedon=  date('Y-m-d H:i:s') ;
//         $updatedon_registerdate=date('Y-m-d');
//  DB::SELECT("UPDATE unapplication set applicationid='$applicationid',updatedon='$upatedon',updatedby='$user_session',registerdate='$updatedon_registerdate' where unapplicationid='$unapplicationid'");
        
        if($appltypeshort!='MA')
        {
         $applrelief=DB::SELECT("SELECT * from applagainst where applicationid='$unapplicationid' and reviewgovtapplicant='Y'");
      
        if($applrelief!=null)
        {
     
        }
        else{
          DB::SELECT("UPDATE receipt set applicationid='$applicationid' where applicationid='$unapplicationid' ");
           }
        }

        if($appltypeshort!='OA')
             {
              DB::SELECT("UPDATE applagainst set applicationid='$applicationid' where applicationid='$unapplicationid'");
             }
       
     
          DB::SELECT("UPDATE applicant set applicationid='$applicationid' where applicationid='$unapplicationid'");
    
             DB::SELECT("UPDATE respondant set applicationid='$applicationid' where applicationid='$unapplicationid'");
        
           DB::SELECT("UPDATE applicationindex set applicationid='$applicationid' where applicationid='$unapplicationid'");
        
           DB::SELECT("UPDATE applrelief set applicationid='$applicationid' where applicationid='$unapplicationid'");
      
         $useractivitydtls['applicationid_receiptno'] =$applicationid;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Filing Counter - Convert Unnumber' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
       $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
           DB::commit();
        if($value_groupno!=null)
         {
          return redirect()->route('registerUnnumber')->with('success', 'Application No. '.$applicationno_display . ' registered and also AdditionalNumber No. '.$applicationno_display1.' is also registered ');
         }
         else{
    return redirect()->route('registerUnnumber')->with('success', 'Application No. '.$applicationno_display.' is registered');
            }
       
        }catch (\Exception $e) {
           DB::rollback();
            dd($e);
                return redirect()->route('registerUnnumber')->with('error', 'Someting went wrong, Application not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                return redirect()->route('registerUnnumber')->with('error', 'Someting wrong, Application not saved !!');
            }

              
            

        
       

}

   public function saveFreshApplication(Request $request)
    {
       $request->validate([
                'applTypeName' => 'required',
                'applYear' => 'required|numeric',
                'noOfAppl' => 'required|numeric',
                'noOfRes' => 'required|numeric',
                'actSectionName' => 'required|numeric',
                'applCatName' => 'required|numeric',
                'applnSubject' => 'required',
                'dateOfAppl' => 'required |date',
                //'applnRegDate' => 'required'
            ]);



        $arr=explode('-',$request->input('applTypeName'));
        $applnStore['actcode']= 1;
        $establishcode = $request->session()->get('EstablishCode');
        $appltypeshort = $arr[1];
        $applYear = $request->input('applYear');
        if(trim($request->input('additionalNo')!==''))
        {  $applcount = $request->input('noOfAppl')+$request->input('additionalNo');
        }else{
           $applcount = $request->input('noOfAppl');
        }
        $applnStore['actsectioncode'] = $request->input('actSectionName');
        $applnStore['appltypecode'] = $arr[0];
        $applnStore['applicationyear'] = $request->input('applYear');
        $applnStore['applicationdate'] = date('Y-m-d',strtotime($request->input('dateOfAppl')));
        $applnStore['registerdate'] = date('Y-m-d',strtotime($request->input('dateOfAppl')));
        $applnStore['applcategory'] = $request->input('applCatName');
        $applnStore['subject'] = $request->input('applnSubject');
        $applnStore['respondentcount']=$request->input('noOfRes');
        $applnStore['applicantcount']=$request->input('noOfAppl');
        $applnStore['servicedistrict']=20;
        $revappl = $request->input('reviewApplId1');
        $applnStore['createdby']= $request->session()->get('userName');
        $applnStore['createdon']= date('Y-m-d H:i:s') ;
        try{
        DB::beginTransaction();
        $nextno =  DB::select("SELECT f_applicationsrno('$establishcode','$appltypeshort','$applYear','$applcount') as nin");
        $no= $nextno[0]->nin;
        $srno=explode(',',$no);

        $applnStore['establishcode'] = $establishcode;
        $applnStore['applicationsrno'] = $srno[0];
        $applnStore['applicationtosrno'] = $srno[1];


        $applnStore['applicationid']= $arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
        $applicationno=$srno[0];
        if($srno[0]!=$srno[1]){
        $applicationno .= '-'.$srno[1];
        }
        $applicationid =  $arr[1].'/'.$applicationno.'/'.$applnStore['applicationyear'];
        
          if($this->case->addApplDetails($applnStore,''))
            {
             $value = true;
             $unapplicationid=$arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
             $unapplyear=$request->input('applYear');
             $createdby= $request->session()->get('userName');
             $createdon= date('Y-m-d H:i:s') ;
            $registerdate= date('Y-m-d',strtotime($request->input('dateOfAppl')));
             DB::SELECT("INSERT into unapplication values('','$unapplicationid','$unapplyear','$arr[0]','$srno[0]','$srno[1]','$createdby','$createdon',null,null,'$registerdate','$establishcode')");
             if($arr[1]!='UA' )
              {
                  $applagain['reviewgovtapplicant']=$request->input('applicantgovt');
                  $applagain['suomotoapplication']=$request->input('suomotoappl');
                  $applagain['createdby']=$request->session()->get('userName');
                  $applagain['applicationid']=$applnStore['applicationid'];
                  $applagain['referapplid']=$revappl;
                  $value = $this->case->addApplreferType($applagain);
                  }
          if($request->input('suomotoappl')  || $request->input('applicantgovt') !='Y')
          { 
           
            if($arr[1]!="UM")
             {
              $j=0;
             if( $request->input('receiptno')!=null){
              foreach ($request->input("receiptno") as $key) {
                $receiptStore['applicationid']= $applnStore['applicationid'];
                $receiptStore['receiptuseddate']= date('Y-m-d H:i:s') ;
                $receiptStore['receiptno'] = $request->input('receiptno')[$j];
                $receiptSrNo = explode('/',$request->input('receiptno')[$j]);
                $receiptStore['receiptsrno'] = $receiptSrNo[2];
               $value = $this->case->updateReceiptDetails($receiptStore, $receiptStore['receiptsrno'],$receiptStore['receiptno']);
                $j++;
               }
               }
             }
          }
       }
	     $useractivitydtls['applicationid_receiptno'] =$applnStore['applicationid'];
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Filing Counter - New Application' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
	   	 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
           DB::commit();
         if( $value == true)
           {
           return redirect()->route('freshApplication')->with('success', 'Application No. alloted '.$applicationid);
            }
         else{
             DB::rollback();
          return redirect()->route('freshApplication')->with('error', 'Someting went wrong, Application not saved !! ');
            }
        }catch (\Exception $e) {
         
                DB::rollback();
                 dd($e);
                return redirect()->route('freshApplication')->with('error', 'Someting went wrong, Application not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                return redirect()->route('freshApplication')->with('error', 'Someting wrong, Application not saved !!');
            }

    }

	public function detailEntryApplication(Request $request)
		{

            $establishcode = $request->session()->get('EstablishCode');
            $data['establishname'] = $this->case->getEstablishName($establishcode);
            $data['actDetails'] = $this->case->getActDetails();
            $data['sectionDetails'] = $this->case->getSectionDetails();
            $data['applicationType'] = $this->case->getApplType();
            $data['applCategory'] = $this->case->getApplCategory();
            $data['district'] = $this->case->getDistrict();
            $data['taluka'] = $this->case->getTaluka($distCode='');
            $data['nameTitle'] = $this->case->getNameTitle();
            $data['relationTitle'] = $this->case->getRelationTitle();
            $data['deptType'] = $this->case->getDeptType();
            $data['deptName'] = $this->case->getDeptName();
            $data['adv']=$this->case->getAdv();
            $data['appldesig'] =  $this->case->getDesignation();
            $user = 'yyyy';
            $data['Temp'] = $this->case->getApplicationId($applicationid='',$user);
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
            $data['title']='Case Form';
            return view('Application/CaseForm',$data)->with('user',$request->session()->get('userName'));
   }


    public function additionalnumbers(Request $request)
    {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['title'] = 'Additional No';
         return view('Application.AdditionalNumber',$data)->with('user',$request->session()->get('userName'));
    }

    public function addAdditionalNumber(Request $request)
    {
		$request->validate([
   	     'applTypeName' => 'required',
         'additionalno' => 'required|numeric',
           ]);
        $establishcode = $request->session()->get('EstablishCode');
        $applType =  explode('-',$request->get('applTypeName'));
        $applcount = $request->get('additionalno');
        $currentyear = date("Y");
        $appltypeshort = $applType[1];
        $groupStore['createdon'] =date('Y-m-d') ;
        $groupStore['createdby'] = $request->session()->get('userName');
        $groupStore['appltypecode'] = $applType[0];
        $groupStore['remarks'] = $request->get('additionalremark');
	      $groupStore['applicationyear'] =date("Y");
	   	  $groupStore['applicationdate'] =date('Y-m-d');
     try{
         DB::beginTransaction();
          $nextno =  DB::select("SELECT f_applicationsrno('$establishcode','$appltypeshort','$currentyear','$applcount') as nin");
          $no= $nextno[0]->nin;
          $srno=explode(',',$no);
          $groupStore['applicationsrno'] = $srno[0];
          $groupStore['applicationtosrno'] = $srno[1];
          $groupStore['applicationid'] = $applType[1].'/'.$request->get('applicationId');
		  $applicationno=$srno[0];
         if($srno[0]!=$srno[1]){
			$applicationno .= '-'.$srno[1];
		 }
		 $count =$srno[1]-$srno[0]+1;
        $applicationid =  $applicationno.' ('.$count .' additional numbers) ';

          $value = $this->case->addGroupApplication($groupStore);
             if( $value == true)
                  {
					 $useractivitydtls['applicationid_receiptno'] = $groupStore['applicationid'];
					 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
					 $useractivitydtls['activity'] ='Filing Counter - Additional Numbers' ;
					 $useractivitydtls['userid'] = $request->session()->get('username');
					 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
					 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
					 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                      DB::commit();
                      return response()->json([
                      'status' => "sucess",
                      'message' => $applicationid." have been created for this application."
                      ]);
                  }
                 else
                  {
                      DB::rollback();
                      return response()->json([
                      'status' => "fail",
                      'message' => "Something Went Wrong!!"
			               	]);
                    }

        }catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
                      ]);
            } catch (\Throwable $e) {
                DB::rollback();
                return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
             ]);
					}
	}


     public function applagainst(Request $request)
    {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['title'] = 'Additional No';
         return view('Application.applagainst',$data)->with('user',$request->session()->get('userName'));
    }

  public function updateapplagainst(Request $request)
    {
		$request->validate([
   	     'applTypeName' => 'required',
         'referapplTypeName' => 'required',
		 'applicationId' => 'required',
         'referapplicationId' => 'required',
          ]);
        $applType =  explode('-',$request->get('applTypeName'));
        $referapplType =  explode('-',$request->get('referapplTypeName'));

        $applicationid = $applType[1].'/'.$request->get('applicationId');
        $referapplid = $referapplType[1].'/'.$request->get('referapplicationId');

		$useractivitydtls['applicationid_receiptno'] = $applicationid;
		$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		$useractivitydtls['activity'] ='Update Refer Application' ;
		$useractivitydtls['userid'] = $request->session()->get('username');
		$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
		$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');

        if($this->case->updateapplagainst($applicationid,$referapplid))
                    {
				    $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Refer Application Updated Successfully"

                    ]);
                    }
                    else
                    {
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"

                    ]);
                    }

      }

 public function restoreapplication(Request $request)
   {

       $establishcode = $request->session()->get('EstablishCode');
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['applicationType'] = $this->case->getApplType();
        $data['nameTitle'] = $this->case->getNameTitle();
        $data['applCategory'] = $this->case->getApplCategory();

       return view('Application.RestoreApplication',$data);
   }


    public function saveapplicationrestore(Request $request)
    {
       $request->validate([
                'applTypeName' => 'required ',
                'applicationId' => 'required',
                'orderno' => 'required',
                'orderdate' => 'required| date',
                'restorefrom' => 'required|in:MA,Highcourt,Others',
               'restoreapplicationId' => 'required_if:restorefrom,MA',

            ]);
       $establishcode = $request->session()->get('EstablishCode');
       $applType = explode('-',$request->get('applTypeName'));
       $applicationid = $applType[1].'/'.$request->get('applicationId');
       if($request->get('restorefrom')== "MA")
        {
           $restoreapplicationid = 'MA'.'/'.$request->get('restoreapplicationId');
           $applicationdisposed = $this->disposedApplication->getDisposedApplicationDetails($restoreapplicationid,$establishcode);
          if ($applicationdisposed != "")
         {
          $referapplid = $applicationdisposed[0]->referapplid;
          if($referapplid == $applicationid)
              {
                $applnStore['restorefromapplicationid']=$restoreapplicationid;
          }else{
            return back() ->with('error','Miscellaneous Application is not referred to '. $applicationid);
          }

         }else{
            return back() ->with('error','Miscellaneous Application is not disposed / not registered.');
         }

         }

        $applnStore['applicationid']=$applicationid;
        $applnStore['orderno'] = $request->input('orderno');
        $applnStore['orderdate']=date('Y-m-d',strtotime($request->input('orderdate')));
        $applnStore['restorefrom']=$request->input('restorefrom');
        $applnStore['createdby']= $request->session()->get('userName');
        $applnStore['createdon']= date('Y-m-d H:i:s') ;

		$useractivitydtls['applicationid_receiptno'] = $applicationid;
	    $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		$useractivitydtls['activity'] ='Application Restore' ;
		$useractivitydtls['userid'] = $request->session()->get('username');
		$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
		$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
       try{
            $value = DB::transaction(function () use($applicationid,$applnStore,$useractivitydtls) {
                  $this->restoreApplication->addRestoreApplDetails($applnStore);
                  DB::table('application')->where('applicationid', $applicationid)->update(['statusid'=>1]);
                  DB::insert("insert into applicationdisposed_history select * from applicationdisposed where applicationid=:applicationid",['applicationid' => $applicationid]);
                  DB::delete("delete from applicationdisposed where applicationid=:applicationid",['applicationid' => $applicationid]);
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                   return true;

            });
           if( $value == true)
           {
              return redirect()->route('restoreapplication')->with('success', 'Application Restored Successfully.');
           //  return back() ->with('success','Application Restored Successfully.');
            }
         else{
           return back() ->with('error', 'Someting went wrong dd');
            }
        }catch(\Exception $e){
          dd($e->getMessage());
          return back() ->with('error', 'Someting went wrongaadd');
            }
      }



     public function applicationDisposed(Request $request)
     {
       $establishcode = $request->session()->get('EstablishCode');

			  $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['actDetails'] = $this->case->getActDetails();
        $data['sectionDetails'] = $this->case->getSectionDetails();
        $data['applicationType'] = $this->case->getApplType();
        $data['nameTitle'] = $this->case->getNameTitle();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['district'] = $this->case->getDistrict();
        $data['taluka'] = $this->case->getTaluka($distCode='');
        $data['deptType'] = $this->case->getDeptType();
        $data['deptName'] = $this->case->getDeptName();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);
        $data['ordertype'] =  $this->IANature->getOrderType();
        $data['purpose'] =  $this->IANature->getListPurpose();
				$data['adv']=$this->case->getAdv();
       return view('Application.DisposedApplication',$data);

     }

  public function getDisposedApplicationDetails(Request $request)
    {
	  $request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),
              ]);
      $establishcode = $request->session()->get('EstablishCode');
      $applicationid = $_POST['applicationid'];
      $data['applicationDetails'] = $this->disposedApplication->getDisposedApplicationDetails($applicationid,$establishcode);
       echo json_encode($data['applicationDetails']);
    }



      public function saveDisposedApplication(Request $request)
    {
       $request->validate([
                'applTypeName' => 'required',
                'applYear' => 'required|numeric',
                'noOfAppl' => 'required|numeric',
                'noOfRes' => 'required|numeric',
                'actSectionName' => 'required|numeric',
                'applCatName' => 'required|numeric',
                'applnSubject' => 'required',
                'dateOfAppl' => 'required|date',
                'applnRegDate' => 'required|date',
                'applnDisposeDate' => 'required|date',
                'applStartNo' => 'required|numeric',
                'applEndNo' => 'required|numeric',
                'applicantName' => 'required',
                'gender' => 'required|alpha',
                'applDeptType' => 'required',
                'applnameOfDept' => 'required',
                'respondantName' => 'required',
                'resGender' => 'required|alpha',
                'resDeptType' => 'required',
                'resnameofDept' => 'required',
                'lastorderPassed' => 'required',
                'lastpostedfor' => 'required',


            ]);
        $arr=explode('-',$request->input('applTypeName'));
       // $applnStore['actcode']= 1;
        $establishcode = $request->session()->get('EstablishCode');
        $appltypeshort = $arr[1];
        $applYear = $request->input('applYear');
        $applcount = $request->input('noOfAppl');
        //$applnStore['actsectioncode'] = $request->input('actSectionName');
        $applnStore['appltypecode'] = $arr[0];
        $applnStore['actsectioncode'] = $request->input('actSectionName');
        $applnStore['applicationyear'] = $request->input('applYear');
        $applnStore['applicationdate'] = date('Y-m-d',strtotime($request->input('dateOfAppl')));
        $applnStore['registerdate'] = date('Y-m-d',strtotime($request->input('applnRegDate')));
        $applnStore['disposeddate'] = date('Y-m-d',strtotime($request->input('applnDisposeDate')));

        $applnStore['applcategory'] = $request->input('applCatName');
        $applnStore['subject'] = $request->input('applnSubject');
        $applnStore['respondentcount']=$request->input('noOfRes');
        $applnStore['applicantcount']=$request->input('noOfAppl');
        $applnStore['applicationsrno']=$request->input('applStartNo');
        $applnStore['applicationtosrno']=$request->input('applEndNo');
        $applnStore['statusid']=2;
				$applnStore['authorby']=$request->input('authorby');
        $applnStore['benchcode']=$request->input('benchcode');
        $applnStore['benchtypename']=$request->input('benchtypename');
        $applnStore['purposecode']=$request->input('lastpostedfor');
        $applnStore['ordertypecode']=$request->input('lastorderPassed');

        $applnStore['referapplid']=$request->input('reviewApplId1');
        //$revappl = $request->input('reviewApplId1');
        $applnStore['applicantnametitle']=$request->input('applicantTitle');
        $applnStore['applicantname'] = $request->input('applicantName');
        $applnStore['gender']=$request->input('gender');
        $applnStore['depttype']=$request->input('applDeptType');
        $applnStore['departcode']=$request->input('applnameOfDept');
        $applnStore['resnametitle']=$request->input('respondantTitle');
        $applnStore['respondname']=$request->input('respondantName');
        $applnStore['respondgender']=$request->input('resGender');
        $applnStore['responddepttype']=$request->input('resDeptType');
        $applnStore['responddeptcode']=$request->input('resnameofDept');
        $applnStore['enteredfrom']='Legacy';
        $applnStore['advocateregno']=$request->input('advBarRegNo');
        $applnStore['resadvocateregno'] =$request->input('resadvBarRegNo'); 


        $applnStore['applicationid']= $arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
        $applnStore['connectedcase']= $request->input('connectedappl');

        $applnStore['establishcode'] = $establishcode;
       $applicationid =  $arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
      //  $applicationid =  $arr[1].'/'.$applicationno.'/'.$applnStore['applicationyear'];
       if($request->input('sbmt_adv') == "A")
        {

          $appcount = $this->case->getApplExist($applicationid, $applnStore['applicationsrno'],$arr[0], $applnStore['applicationyear'],$request->input('applEndNo'));

            $appcount= $appcount[0]->applcount;

           if($appcount>0)
           {
                return response()->json([
                'status' => "exists",
                'message' => "Application Already Exists"

                ]);
           }
		   else{
         $appcount = $this->disposedApplication->getDisposedApplicantExist($applicationid, $applnStore['applicationsrno'],$arr[0], $applnStore['applicationyear'],$request->input('applEndNo'));
         if(count($appcount)>0)
           {
          //  print_r($appcount);
            $applicationid= $appcount[0]->applicationid;


                return response()->json([
                'status' => "exists",
                'message' => "Application Already Exists in other application (".$applicationid.")"

                ]);
           }
        else{
			$applnStore['createdby']= $request->session()->get('userName');
			$applnStore['createdon']= date('Y-m-d H:i:s') ;
			 $useractivitydtls['applicationid_receiptno'] =$applicationid;
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Legacy - Add Disposed Application' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
       if($this->disposedApplication->addDisposedApplDetails($applnStore))
               {

				   	$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                    return response()->json([
                        'status' => "sucess",
                        'message' => "Application Saved Successfully"

                        ]);
                }
                else
                {
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"

                    ]);
                }
          }   }
        }
        else if($request->input('sbmt_adv') == "U"){

			$applnStore['updatedby']= $request->session()->get('userName');
			$applnStore['updatedon']= date('Y-m-d H:i:s') ;
			$useractivitydtls['applicationid_receiptno'] =$applicationid;
  			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
  			 $useractivitydtls['activity'] ='Legacy - Update Disposed Application' ;
  			 $useractivitydtls['userid'] = $request->session()->get('username');
  			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
  			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
        if($this->disposedApplication->updateDisposedApplDetails($applnStore,$applicationid))
               {
                   $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
				   return response()->json([
                        'status' => "sucess",
                        'message' => "Application updated Successfully"

                        ]);
                }
                else
                {
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"

                    ]);
                }

          }

    }


}
