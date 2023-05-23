<?php

namespace App\Http\Controllers;
use App\IANature;
use App\CaseManagementModel;
use App\IADocument as IADocumentModel;
use App\Dailyhearing;
use Illuminate\Http\Request;
use App\ModuleAndOptions;
use App\UserActivityModel;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IADocument extends Controller
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
		$this->UserActivityModel = new UserActivityModel();
        $this->module= new ModuleAndOptions();
    }
    public function index(Request $request)
    {
        $user = $request->session()->get('userName');

         $establishcode = $request->session()->get('EstablishCode');
//        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['documenttype'] = $this->IANature->getDocumentType();
        $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
        $data['IANature'] =  $this->IANature->getIANature();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['CourtHalls'] = $this->IANature->getCourthalls($establishcode);
        $data['Status'] =  DB::SELECT("SELECT * from status where statuscode !='2'");
        $data['purpose'] =  $this->IANature->getListPurpose();
        $data['ordertype'] =  $this->IANature->getOrderType();
        $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);
//        $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
        $data['title'] = 'IADocument';
        return view('IADocument.create',$data)->with('user',$request->session()->get('userName'));
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
            'IAFillingDate'=>'required|date',
            'documentTypeCode'=>'required',
            //'IASrNo'=>'numeric|between:1,10000',
           // 'IANatureCode'=>'required',
            'IARegistrationDate'=>'required|date',
            //'IANo'=>'required|numeric',
            'IAPrayer'=>'required'      
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {

            //Add IA Document
           $filliedbyname =  $request->get('filledbyname');
            $filledname  = explode('-',$filliedbyname);
            $type1 = explode('-',$request->get('applTypeName'));
            $applicationid = $type1[1].'/'.$request->get('applicationId');
          $type = explode('-',$request->get('documentTypeCode'));
            $sbmt_val= $request->get('sbmt_ia');

        if ($sbmt_val=='A')
            {
            
            $newiano = DB::table('iadocument')->select('iasrno')->where('applicationid',$applicationid)->where('documenttypecode',$type[0])->orderBy('iasrno', 'DESC')->first();
          if(!empty($newiano))
             $iasrno = $newiano->iasrno + 1;
           else
             $iasrno = 1;
            }
        else
          $iasrno = $request->get('IASrNo');  
  
           $iano = $type[1].'/'.$iasrno;

       if ($sbmt_val=='A')
            {
              $value = DB::table('applicationindex')->select('documentno')->where('applicationid',$applicationid)->orderby('documentno','desc')->first();
            
            if(!empty($value))
            {
              $doc = $value->documentno;
              $document = ($doc)+1;
           }
           else{
              $document =1;
           }
        }
        else
          $document = $request->get('documentno');

            $IADocument = new IADocumentModel([
            'applicationid' =>$type1[1].'/'.$request->get('applicationId'),
            'iafillingdate'=> date('Y-m-d',strtotime($request->get('IAFillingDate'))),
            'iaregistrationdate'=>date('Y-m-d',strtotime($request->get('IARegistrationDate'))),
            'iaprayer'=>$request->get('IAPrayer'),
            'ianaturecode'=> $request->get('IANatureCode'),
            'createdby'=>$request->session()->get('userName'),
            'createdon'=>date('Y-m-d H:i:s'),
            'iasrno'=>$iasrno,
            'iastatus'=>1,
            'documenttypecode'=>$type[0],
            'iano'=> $iano,
            'partysrno'=>$filledname[1],
            'appindexref'=> $document,
            'filledby'=> $filledname[0]
            ]);
           
		   
            //application index 
            $applIndex['applicationid']=$type1[1].'/'.$request->get('applicationId');
            $applIndex['documentname']=$request->get('IAPrayer');
             $applIndex['documentno']=$document;
            

            $applIndex['documentdate']=date('Y-m-d',strtotime($request->get('IAFillingDate')));
            if($sbmt_val=='A'){
            $applIndex['createdby']=$request->session()->get('userName');
            $applIndex['createdon']=date('Y-m-d H:i:s');
            }
            else
            {
            $applIndex['updatedby']=$request->session()->get('userName');
            $applIndex['updatedon']=date('Y-m-d H:i:s');
            }
            $applIndex['startpage']=$request->get('startpage');
            $applIndex['endpage']=$request->get('endpage');

			$useractivitydtls['applicationid_receiptno'] =$type1[1].'/'.$request->get('applicationId');
            $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		    $useractivitydtls['activity'] ='Legacy - Add IA Document' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			
            if($sbmt_val=='A')
            {
               // if($IADocument->save() && $this->case->addApplicationIndex($applIndex))
                 if($IADocument->save())
                {
					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                    return response()->json([
                        'status' => "sucess",
                        'message' => "IA Document Added Successfully"

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
            else
            {   //update iA Document
                
                    $IADocuments = IADocumentModel::find($iano);
                    $IADocuments->iafillingdate= date('Y-m-d',strtotime($request->get('IAFillingDate')));
                    $IADocuments->iaregistrationdate=date('Y-m-d',strtotime($request->get('IARegistrationDate')));
                    $IADocuments->iaprayer=$request->get('IAPrayer');
                    $IADocuments->ianaturecode= $request->get('IANatureCode');
                    $IADocuments->createdby=$request->session()->get('userName');
                    $IADocuments->createdon=date('Y-m-d H:i:s');
                    $IADocuments->iasrno=$iasrno;
                    $IADocuments->iastatus=1;
                    $IADocuments->documenttypecode=$type[0];
                    $IADocuments->partysrno=$filledname[1];
                    $IADocuments->filledby=$filledname[0];
                    $IADocuments->appindexref = $document;
                   
                    $iano = $request->get('IANo');
                  
                    $IADocuments->iano=$iano;


                   //if($IADocuments->save() && $this->case->updateApplicationIndex($applIndex,$applIndex['applicationid'],$applIndex['documentno']))
                if($IADocuments->save())
                    {
					  $useractivitydtls['applicationid_receiptno'] =$type1[1].'/'.$request->get('applicationId');
						$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						$useractivitydtls['activity'] ='Legacy - Update IA Document' ;
						$useractivitydtls['userid'] = $request->session()->get('username');
						$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
						$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
                         $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
						 return response()->json([
                        'status' => "sucess",
                        'message' => "IA Document Updated Successfully"

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
            /**/
        }
    }

    public function dailyHearingstore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
           
          
            
            'hearingDate'=>'required|date',
            'benchCode'=>'required',
            'postedfor'=>'required',
            'courtDirection'=>'required',
            //'caseRemarks'=>'required',
           
            'officenote'=>'required',
            //'nextHrDate'=>'required|date',
            //'nextBench'=>'required',
            //'nextPostfor'=>'required'

        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
            
            //add daily hearing
           
            if($request->get('isnexthearing')=='Y')
            {
              $nexthrdate = date('Y-m-d',strtotime($request->get('nextHrDate')));
              $nextbench = $request->get('nextbenchJudge');
              $nextpurpose = $request->get('nextPostfor');
              $nextbenchtype = $request->get('nextBench');
            }
            else
            {
               $nexthrdate = null;
              $nextbench = null;
              $nextpurpose =null;
              $nextbenchtype = null;
            }
            
            if($request->get('applStatus')==2||$request->get('applStatus')==4)
            {
                 $IAStore['disposeddate'] = date('Y-m-d',strtotime($request->get('disposedDate')));
                 $disposeddate = date('Y-m-d',strtotime($request->get('disposedDate')));
                 
            }
            else 
            {
                  $IAStore['disposeddate'] =null;
                  $disposeddate = null;
                 
           
            }
            

          
             $DailyHearing = new Dailyhearing([
            'applicationid' => $request->get('applicationId'),
            'hearingdate'=>date('Y-m-d',strtotime($request->get('hearingDate'))),
            'benchcode'=>$request->get('benchJudge'),
            'courthallno'=>$request->get('courthall'),
            'purposecode'=>$request->get('postedfor'),
            'courtdirection'=>$request->get('courtDirection'),
            'caseremarks'=>$request->get('caseRemarks'),
            'ordertypecode'=>$request->get('ordertypecode'),
            'casestatus'=> $request->get('applStatus'),
            'disposeddate'=>$disposeddate,
            'business'=>'Y',
            'officenote'=>$request->get('officenote'),
            'nextdate'=> $nexthrdate ,
            'nextbenchcode'=>$nextbench ,
            'nextpurposecode'=>$nextpurpose,
            'benchtypename'=>$request->get('benchCode'),
            'nextbenchtypename'=> $nextbenchtype,
            'enteredfrom' =>'Legacy',
            'establishcode' => $request->session()->get('EstablishCode')
            ]);


            //$applicationid = $request->get('applicationId');


           
            $IAStore['hearingdate'] = date('Y-m-d',strtotime($request->get('hearingDate')));
            $IAStore['benchcode'] =$request->get('benchJudge');
            $IAStore['purposecode'] = $request->get('postedfor');
            //$IAStore['iastatus'] = $request->get('hearingStatus');

            $IAStore['remark']= $request->get('caseRemarks');

            $IAStore['courthallno'] = $request->get('courthall');

           
       
             //updating ia array

//print_r($IAStore);
//print_r($DailyHearing);

              $sbmt_val= $request->get('sbmt_da');
            if($sbmt_val=='A')
            {
			 $useractivitydtls['applicationid_receiptno'] =$request->get('applicationId');
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Legacy - Add Daily Hearing ' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
                if($DailyHearing->save())
                { // trigger is used to update application table
                  //  if(IADocumentModel::updateApplication($request->get('applicationId'),$request->get('nextHrDate'),$request->get('applStatus'),$IAStore))
                    
				
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                        return response()->json([
                        'status' => "sucess",
                        'message' => "Daily Hearing Added Successfully"

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
            else
            {

                 $HearingDoc = Dailyhearing::find($request->get('hearingCode'));
                     $HearingDoc->applicationid = $request->get('applicationId');
                     $HearingDoc->hearingdate=date('Y-m-d',strtotime($request->get('hearingDate')));
                     $HearingDoc->benchcode=$request->get('benchJudge');
                     $HearingDoc->purposecode=$request->get('postedfor');
                     $HearingDoc->courtdirection=$request->get('courtDirection');
                     $HearingDoc->caseremarks=$request->get('caseRemarks');
                     $HearingDoc->business='Y';
                     $HearingDoc->casestatus= $request->get('applStatus');
                     $HearingDoc->disposeddate=$disposeddate;
                     $HearingDoc->officenote=$request->get('officenote');
                     $HearingDoc->nextdate=$nexthrdate;
                     $HearingDoc->nextbenchcode=$nextbench;
                     $HearingDoc->nextpurposecode=$nextpurpose;
                     $HearingDoc->ordertypecode=$request->get('ordertypecode');
                     $HearingDoc->benchtypename=$request->get('benchCode');
                     $HearingDoc->nextbenchtypename=$nextbenchtype;
					  
                     $useractivitydtls['applicationid_receiptno'] =$request->get('applicationId');
					 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
					 $useractivitydtls['activity'] ='Legacy - Update Daily Hearing ' ;
					 $useractivitydtls['userid'] = $request->session()->get('username');
					 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
					 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');

					if($HearingDoc->save())
                      {

//                        if(IADocumentModel::updateApplication($request->get('applicationId'),$request->get('nextHrDate'),$request->get('applStatus'),$IAStore))
                     $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                        return response()->json([
                        'status' => "sucess",
                        'message' => "Daily Hearing Updated Successfully"

                        ]); }
                    
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
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function show(request $request)
    {
       $request->validate([
		'application_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
        ]);
       $applicationId = $_POST['application_id'];
       $flag = $_POST['flag'];
       $data['iadoc'] = IADocumentModel::getIADocAppl($applicationId,$flag);
       echo json_encode($data['iadoc']);
    }

    public function getIADocumentSerialWise(request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
        ]);
       $IASrrno = $_POST['IASrrno'];
       $applicationid = $_POST['applicationid'];
       $doctype = $_POST['doctype'];
      
       $data['iadocserial'] = IADocumentModel::getIADocSerial($IASrrno,$applicationid,$doctype);
       echo json_encode($data['iadocserial']);
    }
    
    public function getPendingIADocuments(request $request)
    {
		$request->validate([
        'application_id' => array(
                        'required',
                        'regex:/^[0-9a-zA-Z_.\/]+$/',
                        'max:20'
                    ),  
        ]);
      $docType = $_POST['docType'];
       $application_id = $_POST['application_id'];
       $data['iadocument'] = IADocumentModel::getPendingIA($docType,$application_id);
        echo json_encode($data['iadocument']);
    }
    public function getHearingDocument(request $request)
    {
		$request->validate([
		'application_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
        ]);
        $applicationId = $_POST['application_id'];

        $data['hearingDoc'] = IADocumentModel::getHearingDoc($applicationId);
        echo json_encode($data['hearingDoc']);
    }
    public function getHearingCodeDet(Request $request)
    {
		$request->validate([
   	     'HearingCodeno' => 'required|numeric',
        ]);
        $HearingCodeno = $_POST['HearingCodeno'];
        $data['hearingCodedet'] = IADocumentModel::getHearingCodeDetails($HearingCodeno);
        echo json_encode($data['hearingCodedet']);
    }
   /* public function checkIAExistance(Request $request)
    {
        
        $doctypecode =  $_POST['documenttypecode'];
        $doctype =  $_POST['documentname'];
        echo $iano =$_POST['iano'];
        exit;
        $applicationId =$_POST['applicationId'];
        $data = IADocumentModel::getIAExistance($doctypecode,$iano,$applicationId);
        echo $data;
    }*/

    public function getPrevIARegDate(Request $request)
    {
        $request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
        ]);
         $applicationId = $_POST['applicationId'];
         $data['iadata'] = IADocumentModel::getRegOfPrevIA($applicationId);
         echo json_encode($data['iadata']);
    }
    public function getBenchjudges(Request $request)
    {
      $establishcode=$request->session()->get('EstablishCode');
        $benchtype =  $_POST['benchtype'];
        $display =  $_POST['display'];
        $data['benches'] = IADocumentModel::getBenchJudges($benchtype,$display,$establishcode);
        echo json_encode($data['benches']);
    }
    public function getApplicationIndexDetails(Request $request)
    {
		$request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
        ]);
        $applicationid =  $_POST['applicationId'];
        $user =  $request->session()->get('userName');
        $data['applIndex'] =  $this->case->getApplicationIndex($applicationid,$user);
        echo json_encode($data['applIndex']);
    }
    public function getApplRespondants(Request $request)
    {
		$request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
        'flag' => 'required|in:A,R',
        ]);
        $applicationid =  $_POST['applicationId'];
        $flag = $_POST['flag'];
        $data['filledby'] = $this->case->getApplRespDetails($applicationid,$flag);
       echo json_encode($data['filledby']);
    }
    public function getStartPage(Request $request)
    {
		$request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),  
        ]);
        $applicationid =  $_POST['applicationId'];
        $data['startpage'] = $this->case->getStartPageofApll($applicationid);
        echo json_encode($data['startpage']);
    }
    public function updateIAStatusDetails(Request $request)
    {
        //$_POST['benchjudge'];
		$request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					), 
		 'courthallno'=> 'required|numeric|max:10',
	     'hearingdate' => 'required | date', 
		 'benchjudge' => 'required|numeric',		 
        ]);
        $applicationid = $_POST['applicationid'];
        $iano = $_POST['iasrno'];
        $IAUp['hearingdate'] = date('Y-m-d',strtotime($_POST['hearingdate']));
        $IAUp['benchcode'] = $_POST['benchjudge'];
        $IAUp['courthallno'] = $_POST['courthallno'];
        $IAUp['remark'] = $_POST['caseremarks'];

        $IAUp['iastatus'] = $_POST['status'];

         if(IADocumentModel::updateIA($applicationid,$iano,$IAUp))
         {
            return response()->json([
                    'status' => "success"

                    ]);
         }
         else
         {
            return response()->json([
                    'status' => "fail"

                    ]);
         }


    }
    public function getIANoDet(Request $request)
    {
		$request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					), 
		 'docType'=> 'required|numeric',
	      ]);
        $applicationid = $_POST['applicationid'];
        $docType = $_POST['docType'];
        $data['lastia'] = IADocumentModel::getIANO($applicationid, $docType);
        //print_r($data['lastia']);
        echo json_encode($data['lastia']);
    }
    public function getHearingDet(Request $request)
    {
	   $request->validate([
		 'hearingdate' => 'required | date', 
		 'benchjudge' => 'required | numeric',		 
        ]);
       $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
       $benchcode = $_POST['benchjudge'];
       $applicationid = $_POST['applicationid'];
       $data['hearingdet'] = IADocumentModel::getHearingDetails($hearingdate,$benchcode,$applicationid);
        echo json_encode($data['hearingdet']);
    }
    public function getIAOnHearing(Request $request)
    {  
	   $request->validate([
		'applicationid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					), 
		 'hearingdate' => 'required | date', 
		 'benchjudge' => 'required | numeric',		 
        ]);
         $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
         $applicationid = $_POST['applicationid'];
         $benchcode = $_POST['benchjudge'];
         $data['IADetails']= IADocumentModel::getIABasedHearing($hearingdate,$applicationid,$benchcode);
         echo json_encode($data['IADetails']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function edit($IADocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$IADocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function destroy($IADocument)
    {
        //
    }
}
