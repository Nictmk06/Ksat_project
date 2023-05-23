<?php

namespace App\Http\Controllers;
use App\IANature;
use App\extraadvocate as Extraadvocate;
use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\UserActivityModel;
use App\ModuleAndOptions;
class ExtraAdvocateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
    {
        $this->IANature = new IANature();
        $this->case = new CaseManagementModel();
        $this->module= new ModuleAndOptions();
		$this->UserActivityModel = new UserActivityModel();
    }
    public function index(Request $request)
    {
        $user = $request->session()->get('userName');

         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
          $data['adv']=$this->case->getAdv();
         $data['establishname'] = $this->case->getEstablishName($establishcode);
         $data['title'] = 'Extra Advocate';
         return view('case.extraAdvocate',$data)->with('user',$request->session()->get('userName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'applicationId' => 'required|max:10',
              
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
            $type1 = explode('-',$request->get('applTypeName'));

            $filliedbyname =  $request->get('filledbyname');
            $filledname  = explode('-',$filliedbyname);
               $Extraadv = new Extraadvocate([
                     'applicationid' =>$type1[1].'/'.$request->get('applicationId'),
                    'advocatetype'=>$request->get('filledby'),
                    'partysrno'=>$filledname[1],
                    'enrollmentno'=>$request->get('advBarRegno'),
                    'display'=>$request->get('isCauseList'),
                    'active'=>$request->get('advocateStatus'),
                    'remarks'=>$request->get('remarks'),
                    'name'=>$filledname[2],
                    'enrolleddate'=>date('Y-m-d',strtotime($request->get('enrolleddate'))),
                    'createdby'=>$request->session()->get('userName'),
                    'createdon'=>date('Y-m-d H:i:s')
                    ]);
               $sbmt_val = $request->get('sbmt_adv');
               $applicationid =$type1[1].'/'.$request->get('applicationId'); 
            if($sbmt_val=='A')
            {
               // echo $filledname[0];
                
                $advcount = Extraadvocate::getExtAdvExist($filledname[0],$filledname[1],$request->get('advBarRegno'), $applicationid);
                //echo "Asd".$advcount;
              if($advcount==true)
              {
                return response()->json([
                        'status' => "exists",
                        'message' => "Extra Advocate Already Exists"

                        ]);
                 
              }
              else
              {
                   if($Extraadv->save())
                    {
                        return response()->json([
                            'status' => "sucess",
                            'message' => "Extra Advocate Added Successfully"

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

            /* $appcount= $appcount[0]->applcount;*/
               /* */
            }
            else
            {
               
              
                 $filliedbyname =  $request->get('filledbyname');
                $filledname  = explode('-',$filliedbyname);
                 $Extraadv = Extraadvocate::find($request->get('extraadvcode'));

                   $Extraadv->applicationid =$type1[1].'/'.$request->get('applicationId');
                    $Extraadv->advocatetype=$request->get('filledby');
                    $Extraadv->partysrno=$filledname[1];
                    $Extraadv->enrollmentno=$request->get('advBarRegno');
                    $Extraadv->display=$request->get('isCauseList');
                    $Extraadv->active=$request->get('advocateStatus');
                    $Extraadv->remarks=$request->get('remarks');
                    $Extraadv->name=$filledname[2];
                    $Extraadv->enrolleddate=date('Y-m-d',strtotime($request->get('enrolleddate')));
                    $Extraadv->updatedby=$request->session()->get('userName');
                    $Extraadv->updatedon=date('Y-m-d H:i:s');
                   
                   if($Extraadv->save())
                    {
                         return response()->json([
                        'status' => "sucess",
                        'message' => "Extra Advocate Updated Successfully"

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

    /**
     * Display the specified resource.
     *
     * @param  \App\extraadvocate  $extraadvocate
     * @return \Illuminate\Http\Response
     */
    public function getExtraAdvocateDet(Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
        ]);
        $applicationid = $_POST['applicationid'];
        $data['extradet'] =Extraadvocate::getExtraAdvDetails($applicationid);
        echo json_encode($data['extradet']);
    }
    public function getEachExtraAdvDet(Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
        ]);
       $applicationid = $_POST['applicationid'];
       $id = $_POST['id'];
       $enrollno= $_POST['enrollno'];
       $data['extraadvdet']= Extraadvocate::getEachAdvDet($applicationid,$id);
       echo json_encode($data['extraadvdet']);
    }

    public function getUniqueAdvDet(Request $request)
    {
		$request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
        ]);
        $applicationid = $_POST['applicationId'];
        $data['uniqueadv']= Extraadvocate::getUniqueAdvList($applicationid);
        echo json_encode($data['uniqueadv']);

    }
    public function show(Request $request)
    {
        
        $user = $request->session()->get('userName');

         $establishcode = $request->session()->get('EstablishCode');
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
         $data['applicationType'] = $this->case->getApplType();
         $data['partystatus'] = $this->case->getPartyStatus();
         $data['applCategory'] = $this->case->getApplCategory();
          $data['district'] = $this->case->getDistrict();
           $data['appldesig'] =  $this->case->getDesignation();
            $data['taluka'] = $this->case->getTaluka($distCode='');
          $data['adv']=$this->case->getAdv();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
//        $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
         $data['title'] = 'AR Status';
         return view('case.ARStatus',$data)->with('user',$request->session()->get('userName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\extraadvocate  $extraadvocate
     * @return \Illuminate\Http\Response
     */
    public function statusupdate(Request $request)
    {$request->validate([
		'modal_appl_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					), 
      'modal_srno' => 'required|numeric',
      'modal_flag' => 'required|alpha',
        ]);
        $applicationid = $_POST['modal_appl_id'];
        $applsrno = $_POST['modal_srno'];
        if($_POST['modal_flag']=='A')
        {
          $applUp['applicantstatus'] = $_POST['applStatus'];
          $applUp['partystatus'] = $_POST['partystatus']; 
          $applUp['ismainparty'] = $_POST['isMainParty']; 
          $applUp['statuschangedate'] = date('Y-m-d',strtotime($_POST['statusdate']));   
          $applUp['remarks'] = $_POST['remarks'];  
          
		 $useractivitydtls['applicationid_receiptno'] =$applicationid;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Update Applicant Status' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		  if($this->case->updateApplicant($applUp,$applicationid,$applsrno))
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
        else
        {
          $resUp['respondstatus'] = $_POST['applStatus'];

          $resUp['partystatus'] = $_POST['partystatus']; 
          $resUp['ismainrespond'] = $_POST['isMainParty']; 
          $resUp['statuschangedate'] = date('Y-m-d',strtotime($_POST['statusdate']));   
          $resUp['remarks'] = $_POST['remarks'];
          
		 $useractivitydtls['applicationid_receiptno'] =$applicationid;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Update Respondent Status' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		  if($this->case->updateRespondant($resUp,$applicationid,$applsrno))
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
               // 'message' => "Something Went Wrong!!"

                ]); 
          }
          
        }
        

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\extraadvocate  $extraadvocate
     * @return \Illuminate\Http\Response
     */

    public function showgroup(Request $request)
    {
        $user = $request->session()->get('userName');

         $establishcode = $request->session()->get('EstablishCode');
          
            $data['applicationType'] = $this->case->getApplType();
            $data['applCategory'] = $this->case->getApplCategory();
            
        $data['establishname'] = $this->case->getEstablishName($establishcode);
//         $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
        $data['title'] = 'Additional No';
         return view('case.groupApplication',$data)->with('user',$request->session()->get('userName'));
    }
    
    public function storegroupno(Request $request)
    {
         $applType =  explode('-',$request->get('applTypeName'));

       $groupStore['applicationid'] = $applType[1].'/'.$request->get('applicationId');
       $groupStore['applicationsrno'] = $request->get('additionalstno');
       $groupStore['applicationtosrno'] = $request->get('additionalendno');

       $date = date('Y-m-d',strtotime($request->get('additionalDate')));
       $newdate =explode('-',$date);

      $applicationsrno =  $request->get('additionalno');
      $groupStore['applicationyear'] =$newdate[0];
      // $groupStore['applicationyear'] =$request->get('applYear') ;
       $groupStore['applicationdate'] =$date ;
       $groupStore['createdon'] =date('Y-m-d') ;
       $groupStore['createdby'] = $request->session()->get('userName');
       $groupStore['appltypecode'] = $applType[0];
       $groupStore['remarks'] = $request->get('additionalremark');
       $sbmt_val = $request->get('sbmt_additional');
      if($sbmt_val=='A')
      {
          $appcount = $this->case->getApplicationExistanceCount( $groupStore['applicationid'], $request->get('additionalstno'),$applType[0], $request->get('applYear'),$request->get('additionalendno'));
          $appcount= $appcount[0]->applcount;

			$useractivitydtls['applicationid_receiptno'] =$applType[1].'/'.$request->get('applicationId');
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Legacy - Add Additional No' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
           if($appcount>0)
           {
                return response()->json([
                'status' => "exists",
                'message' => "Application Already Exists"

                ]);
           }
           else
           {
                    if($this->case->addGroupApplication($groupStore))
                    {
					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
                    return response()->json([
                    'status' => "sucess",
                    'message' => "Group Application Added Successfully"

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
      else
      {
		  $useractivitydtls['applicationid_receiptno'] =$applType[1].'/'.$request->get('applicationId');
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Legacy - Update Additional No' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		  
         if($this->case->updateGroupApplication($groupStore,$groupStore['applicationid'] ,$groupStore['applicationsrno'],$groupStore['applicationtosrno']))
        {
			$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
            return response()->json([
                'status' => "sucess",
               'message' => "Group Application Updated Successfully"

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
    public function getGroupApplications(Request $request)
    {
	 $request->validate([
		'application_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),  
        ]);
        $applicationid = $_POST['application_id'];
        $data['groupdet'] = $this->case->getGroupApp($applicationid);
       
        echo json_encode($data['groupdet']);

    }
    public function update(Request $request, extraadvocate $extraadvocate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\extraadvocate  $extraadvocate
     * @return \Illuminate\Http\Response
     */
    public function destroy(extraadvocate $extraadvocate)
    {
        //
    }

}
