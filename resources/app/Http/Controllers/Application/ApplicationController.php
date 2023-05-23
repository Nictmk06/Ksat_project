<?php
namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\DisposedApplicationModel;
use Session;
use App\ModuleAndOptions;//use model Module & Options
use App\IANature;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationController extends Controller
{

	public function __construct()
	{
    $this->IANature = new IANature();
	  $this->case = new CaseManagementModel();  
    $this->disposedApplication =  new DisposedApplicationModel();
  }

	 public function index(Request $request)
   {
     $establishcode = $request->session()->get('EstablishCode');
     $data['establishname'] = $this->case->getEstablishName($establishcode);
      $data['actDetails'] = $this->case->getActDetails();
      $data['sectionDetails'] = $this->case->getSectionDetails();
      $data['applicationType'] = $this->case->getApplType();
   	  
      $data['applCategory'] = $this->case->getApplCategory();
      $data['district'] = $this->case->getDistrict();
      $data['taluka'] = $this->case->getTaluka($distCode='');
   	 return view('Application.FreshApplication',$data);
   }

   public function saveFreshApplication(Request $request)
    {
       $request->validate([
                'applTypeName' => 'required',
                'applYear' => 'required|numeric',
                'noOfAppl' => 'required|numeric',
                'noOfRes' => 'required|numeric',
                'actSectionName' => 'required',
                'applCatName' => 'required',
                'applnSubject' => 'required',
                'dateOfAppl' => 'required',
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
             if($arr[1]!='OA')
                  {
                    $applagain['createdby']=$request->session()->get('userName');
                   $applagain['applicationid']=$applnStore['applicationid'];
                   $applagain['referapplid']=$revappl;
                    $value = $this->case->addApplreferType($applagain);
                     }          
           }
           DB::commit();
             if( $value == true)
           {
            return redirect()->route('freshApplication')->with('success', 'Application No. alloted '.$applicationid);
            }
         else{
          return redirect()->route('freshApplication')->with('error', 'Someting went wrong, Application not saved !! ');
            }
        }catch (\Exception $e) {
                DB::rollback();
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
           // $data['modules_options'] = $this->module->getModulesAndOtions();
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
		      $value = $this->case->addGroupApplication($groupStore);
             if( $value == true)
                  {
                      DB::commit();  
                      return response()->json([
                      'status' => "sucess",
                      'message' => "Group Application Added Successfully"
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
       // $establishcode = $request->session()->get('EstablishCode');
        $applType =  explode('-',$request->get('applTypeName'));
        $referapplType =  explode('-',$request->get('referapplTypeName'));
       
        $applicationid = $applType[1].'/'.$request->get('applicationId');
        $referapplid = $referapplType[1].'/'.$request->get('referapplicationId');
          
        if($this->case->updateapplagainst($applicationid,$referapplid))
                    {
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
        $data['benchjudge'] = $this->IANature->getbenchjudge();
        $data['ordertype'] =  $this->IANature->getOrderType();
        $data['purpose'] =  $this->IANature->getListPurpose();
       return view('Application.DisposedApplication',$data);
 
     }  

  public function getDisposedApplicationDetails(Request $request)
    {

       $applicationid = $_POST['applicationid'];
       $data['applicationDetails'] = $this->disposedApplication->getDisposedApplicantDetails($applicationid);
       echo json_encode($data['applicationDetails']); 
    }



      public function saveDisposedApplication(Request $request)
    {
       $request->validate([
                'applTypeName' => 'required',
                'applYear' => 'required|numeric',
                'noOfAppl' => 'required|numeric',
                'noOfRes' => 'required|numeric',
                'actSectionName' => 'required',
                'applCatName' => 'required',
                'applnSubject' => 'required',
                'dateOfAppl' => 'required',
                'applnRegDate' => 'required',
                'applnDisposeDate' => 'required',
                'applStartNo' => 'required|numeric',
                'applEndNo' => 'required|numeric',
                'applicantName' => 'required',
                'gender' => 'required',
                'applDeptType' => 'required',
                'applnameOfDept' => 'required',
                'respondantName' => 'required',
                'resGender' => 'required',
                'resDeptType' => 'required',
                'resnameofDept' => 'required',
                'lastorderPassed' => 'required',
                'lastpostedfor' => 'required' 
                
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
        $applnStore['applicationid']= $arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
        $applnStore['connectedcase']= $request->input('connectedappl');
        $applnStore['createdby']= $request->session()->get('userName');
        $applnStore['createdon']= date('Y-m-d H:i:s') ;
        $applnStore['establishcode'] = $establishcode;
       $applicationid =  $arr[1].'/'.$applnStore['applicationsrno'].'/'.$applnStore['applicationyear'];
      //  $applicationid =  $arr[1].'/'.$applicationno.'/'.$applnStore['applicationyear'];
       if($request->input('sbmt_adv') == "A")
        {  

         $appcount = $this->disposedApplication->getDisposedApplicantExist($applicationid, $applnStore['applicationsrno'],$arr[0], $applnStore['applicationyear'],$request->input('applEndNo'));

            $appcount= $appcount[0]->applcount;
           
          if($appcount>0)
           {
                return response()->json([
                'status' => "exists",
                'message' => "Application Already Exists"

                ]);
           }
        else{

        if($this->disposedApplication->addDisposedApplDetails($applnStore))
               {
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
          }

        // DB::beginTransaction();
        // try{
        //   if($this->disposedApplication->addDisposedApplDetails($applnStore))
        //     {   
        //      if($arr[1]!='OA')
        //           {
        //             $applagain['createdby']=$request->session()->get('userName');
        //             $applagain['applicationid']=$applicationid;
        //             $applagain['referapplid']=$revappl;
        //             $this->case->addApplreferType($applagain);
                   
        //              }
        //          DB::commit();
        //         return redirect()->route('applicationDisposed')->with('success', 'Application Saved Successfully');
        //          }
        //        }catch (\Exception $e) {
        //         DB::rollback();
        //         throw $e;
        //         return redirect()->route('applicationDisposed')->with('error', 'Someting wrong, Application not saved !!');
        //     } catch (\Throwable $e) {
        //         DB::rollback();
        //         throw $e;
        //         return redirect()->route('applicationDisposed')->with('error', 'Someting wrong, Application not saved !!');
        //     }   
          }
        
         else if($request->input('sbmt_adv') == "U"){
          
        if($this->disposedApplication->updateDisposedApplDetails($applnStore,$applicationid))
               {
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
          // try {
          //       $this->disposedApplication->updateDisposedApplDetails($applnStore,$applicationid);
          //       return redirect()->route('applicationDisposed')->with('success', 'Application updated Successfully');
               
          //       }catch (\Exception $e) {
          //       throw $e;
          //       return redirect()->route('applicationDisposed')->with('error', 'Someting wrong, Application not updated !!');
          //   } catch (\Throwable $e) {
          //       throw $e;
          //       return redirect()->route('applicationDisposed')->with('error', 'Someting wrong, Application not updated !!');
          //   }   
          }
          
    }


}