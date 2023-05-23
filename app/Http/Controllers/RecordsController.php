<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\Records;
use App\RecordRequest;
use App\UserActivityModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class RecordsController extends Controller
{

  public function __construct()
	{
	 $this->case = new CaseManagementModel();  
     $this->records =  new Records();
	 $this->recordsrequest =  new RecordRequest();
	 $this->UserActivityModel = new UserActivityModel();
	}

  public function index(Request $request)
   {
	 $data['applCategory'] = $this->case->getApplCategory();
   	 $data['applType'] = $this->case->getApplType();
   	 $data['rackno'] = $this->records->getRackNo();
	 $data['roomno'] = $this->records->getRoomNo();
	 return view('Records.recordapplication',$data);
   }
   
   public function requestrecords(Request $request)
   {
	 $data['applCategory'] = $this->case->getApplCategory();
   	 $data['applType'] = $this->case->getApplType();
   	 $data['userSection'] = DB::table('usersection')->select('*')->orderby('userseccode')->get();
	 $data['roomno'] = $this->records->getRoomNo();
	 return view('Records.requestrecordapplication',$data);
   }
   
   public function receiverecords(Request $request)
   {
	 $establishcode = $request->session()->get('EstablishCode');
	 $data['applCategory'] = $this->case->getApplCategory();
   	 $data['applType'] = $this->case->getApplType();
   	 $data['userSection'] = DB::table('usersection')->select('*')->orderby('userseccode')->get();
	 $data['records'] = $this->recordsrequest->getRecordsPendingForReceiving($establishcode);
	 return view('Records.receiverecords',$data);
   }
   
   
   	public function getRecordsPendingForReceiving(Request $request)
	{
		$establishcode = $request->session()->get('EstablishCode');
	    $data['records'] = $this->recordsrequest->getRecordsPendingForReceiving($establishcode);
		echo json_encode($data['records']);
	}
	
	
   	public function getApplicationSummaryDtls(Request $request)
	{
		$establishcode = $request->session()->get('EstablishCode');
		$applicationId = $request->get('applicationId');
		$data['ApplicationDet']  = $this->records->getApplicationSummaryDtls($applicationId,$establishcode);
		echo json_encode($data['ApplicationDet']);
	}

 	public function getUsersDtlsBySection(Request $request)
	{
		$establishcode = $request->session()->get('EstablishCode');
		$userseccode = $request->get('sectioncode');
		$data['userdtls']  = $this->records->getUsersDtlsBySection($userseccode,$establishcode);
		echo json_encode($data['userdtls']);
	}


	
 public function saveRecordApplication(Request $request) 
    {
		$request->validate([
                'applicationId' => 'required',
                'applTypeName' => 'required',
                'part' => 'required|in:A,B',
				'documentname' => 'required',                
				'receiveddate' => 'required|date',
				'startPage' => 'required|numeric',
				'endPage' => 'required|numeric',
				'storedat' => 'required|numeric',
				'rackno' => 'required|numeric',
				'bundleno' => 'required',
				'docid' => 'required_if:sbmt_adv,U',
               
			]); 
		try{
			$applType = explode('-',$request->get('applTypeName'));
			$applicationid = $applType[1].'/'.$request->get('applicationId');
			$establishcode = $request->session()->get('EstablishCode');
			
			$applnStore['applicationid'] = $applicationid;
			$applnStore['documentname'] = $request->input('documentname');
			$applnStore['startpage']=$request->input('startPage');
			$applnStore['endpage']=$request->input('endPage');
			$applnStore['part']=$request->input('part');
			$applnStore['receiveddate'] = date('Y-m-d',strtotime($request->input('receiveddate')));
		    $applnStore['roomno']=$request->input('storedat');
			$applnStore['rackno']=$request->input('rackno');
			$applnStore['bundleno']=$request->input('bundleno');
			$applnStore['establishcode'] = $establishcode;
			
			
			
      	    if($request->input('sbmt_adv') == "A")
			{  
				$appcount = $this->records->getRecordApplSamePageNoExist($applicationid, $request->input('startPage'), $request->input('endPage'),$request->input('part'),'');
				if(count($appcount)>0)
			   {
				//print_r($appcount);
				//$applicationid= $appcount[0]->applicationid;
			   
         
                return response()->json([
                'status' => "fail",
                'message' => "Document Already Exists with this Page No in Part ".$request->input('part')."."

                ]);
				}
			else{
				$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				$useractivitydtls['activity'] ='Add Record Document Application' ;
				$useractivitydtls['userid'] = $request->session()->get('username');
				$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				$cur_year = date("Y");
				
				 DB::beginTransaction();
					
					$applnStore['createdby']= $request->session()->get('userName');
					$applnStore['createdon']= date('Y-m-d H:i:s') ;
					$useractivitydtls['applicationid_receiptno'] ='';
					$this->records->addRecordApplication($applnStore);
					
					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
					
				   DB::commit();
				   return response()->json([
							'status' => "sucess",
							'message' => " Record Application saved Successfully" 
							]);
						  
			 }
			}
			else if($request->input('sbmt_adv') == "U"){
				$docid = $request->input('docid');
			    $appcount = $this->records->getRecordApplSamePageNoExist($applicationid, $request->input('startPage'), $request->input('endPage'),$request->input('part'),$docid);
				if(count($appcount)>0)
			   {
				return response()->json([
                'status' => "fail",
                'message' => "Document Already Exists with this Page No in Part ".$request->input('part')."."

                ]);
			   }
			else{
				
				$applnStore['updatedby']= $request->session()->get('userName');
				$applnStore['updatedon']= date('Y-m-d H:i:s') ;
			   
				$useractivitydtls['applicationid_receiptno'] ='';
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				 $useractivitydtls['activity'] ='Update Record Application' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		         DB::beginTransaction();
				 $this->records->updateRecordApplication($applnStore,$docid);
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
				DB::commit();
			    return response()->json([
							'status' => "sucess",
							'message' => " Record Application Updated Successfully."
				]);		
			}
			}
			}
		
		catch(\Exception $e){
			
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
					]);
            } 
        
    }

	public function getRecordApplicationDetails(Request $request)
    {
		$request->validate([
         'applicationId' => 'required|max:20',
         ]);  
       $applicationId=$request->applicationId;
	   $establishcode = $request->session()->get('EstablishCode');
       $data['recordappldtls'] = $this->records->getRecordApplicationDetails($applicationId,$establishcode);
       echo json_encode($data['recordappldtls']);
     }

	public function getRecordDocumentDetails(Request $request)
    {
		$request->validate([
         'id' => 'required|numeric',
         ]);  
       $id=$request->id;
	   $establishcode = $request->session()->get('EstablishCode');
       $data['recordappldtls'] = $this->records->getRecordDocumentDetails($id,$establishcode);
       echo json_encode($data['recordappldtls']);
     }

	
 public function saveRecordRequest(Request $request) 
    {
		$request->validate([
                'applicationId' => 'required',
                'applTypeName' => 'required',
                'recordsentdate' => 'required|date',               
				'requestedDate' => 'required|date',
				'username1' => 'required',
				'userid' => 'required',
				'section' => 'required',
				   
			]); 
		try{
			$applType = explode('-',$request->get('applTypeName'));
			$applicationid = $applType[1].'/'.$request->get('applicationId');
			$establishcode = $request->session()->get('EstablishCode');
			
			$applnStore['applicationid'] = $applicationid;
			$applnStore['userseccode'] = $request->input('section');
			$applnStore['userid'] = $request->input('userid');
			$applnStore['username'] = $request->input('username1');
			$applnStore['requesteddate'] = date('Y-m-d',strtotime($request->input('requestedDate')));
		    $applnStore['recordsentdate'] = date('Y-m-d',strtotime($request->input('recordsentdate')));
		    $applnStore['establishcode'] = $establishcode;
			
			
			
      	    if($request->input('sbmt_adv') == "A")
			{  
				
				$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				$useractivitydtls['activity'] ='Add Record Request' ;
				$useractivitydtls['userid'] = $request->session()->get('username');
				$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				$cur_year = date("Y");
				
				 DB::beginTransaction();
					
					$applnStore['createdby']= $request->session()->get('userName');
					$applnStore['createdon']= date('Y-m-d H:i:s') ;
					$useractivitydtls['applicationid_receiptno'] ='';
					$this->recordsrequest->addRecordRequest($applnStore);
					
					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
					
				   DB::commit();
				   return response()->json([
							'status' => "sucess",
							'message' => " Record Request saved Successfully" 
							]);
						  
			 
			}
			else if($request->input('sbmt_adv') == "U"){
				$docid = $request->input('docid');
			    $appcount = $this->records->getRecordApplSamePageNoExist($applicationid, $request->input('startPage'), $request->input('endPage'),$request->input('part'),$docid);
				if(count($appcount)>0)
			   {
				return response()->json([
                'status' => "fail",
                'message' => "Document Already Exists with this Page No in Part ".$request->input('part')."."

                ]);
			   }
			else{
				
				$applnStore['updatedby']= $request->session()->get('userName');
				$applnStore['updatedon']= date('Y-m-d H:i:s') ;
			   
				$useractivitydtls['applicationid_receiptno'] ='';
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				 $useractivitydtls['activity'] ='Update Record Recquest' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		         DB::beginTransaction();
				 $this->recordsrequest->updateRecordApplication($applnStore,$docid);
				 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
				DB::commit();
			    return response()->json([
							'status' => "sucess",
							'message' => " Record Request Updated Successfully."
				]);		
			}
			}
		}
		
		catch(\Exception $e){
			
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
					]);
            } 
        
    }

public function saveReceivedRecords(Request $request) 
    {
		$request->validate([
                'recordreceiveddate' => 'required|date',
				'requestid' => 'required|numeric',
				'remarks' => 'required',
				]); 
		try{
				$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				$useractivitydtls['activity'] ='Update  Record Document Application' ;
				$useractivitydtls['userid'] = $request->session()->get('username');
				$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				 DB::beginTransaction();
					$requestid = $request->input('requestid');
					$applnStore['recordreceiveddate'] = date('Y-m-d',strtotime($request->input('recordreceiveddate')));
		            $applnStore['remarks']=$request->input('remarks');
					$useractivitydtls['applicationid_receiptno'] ='';
					$this->recordsrequest->updateRecordRequestApplication($applnStore,$requestid);
					
					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
					
				   DB::commit();
				   return response()->json([
							'status' => "sucess",
							'message' => " Record Request saved Successfully" 
							]);			  
			}
		
		catch(\Exception $e){			
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
					]);
            } 
        
    }
	
}