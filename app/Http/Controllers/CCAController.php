<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IANature;
use App\CopyApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\ModuleAndOptions;
use Carbon\Carbon;
use App\CaseManagementModel;
use App\UserActivityModel;
class CCAController extends Controller
{
    //
	public function __construct()
    {
		$this->case = new CaseManagementModel();
		$this->copyapplication = new CopyApplication();
		$this->IANature = new IANature();
		$this->UserActivityModel = new UserActivityModel();
	}

	public function index(Request $request)
    {
		$data['docType'] = $this->copyapplication->getDocumentType();
		$data['advocatedetails'] = $this->copyapplication->getAdvocate();
		$data['dist_list'] = $this->copyapplication->getDistList();
		$data['estcode'] = $request->session()->get('EstablishCode');
	    $data['applType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
	 	$establishDtls = $this->copyapplication->getEstablishDtls($request->session()->get('EstablishCode'));
		$data['ccacharge'] =$establishDtls[0]->ccacharge;
		return view('cca.ccapplication',$data)->with('user',$request->session()->get('userName'));
	}

	public function deficitEntry(Request $request)
    {
	   $data['estcode'] = $request->session()->get('EstablishCode');
	   return view('cca.deficitpayment',$data)->with('user',$request->session()->get('userName'));
	  }

	public function markccaready(Request $request)
    {
	   $data['applType'] = $this->case->getApplType();
       $data['applCategory'] = $this->case->getApplCategory();
	   $data['estcode'] = $request->session()->get('EstablishCode');
	   $data['castatus'] =  $this->copyapplication->getCAStatus();
	   return view('cca.markccaready',$data)->with('user',$request->session()->get('userName'));
	}


	public function ccaDelivery(Request $request)
    {
	   $data['applType'] = $this->case->getApplType();
       $data['applCategory'] = $this->case->getApplCategory();
	   $data['estcode'] = $request->session()->get('EstablishCode');
	   $data['deliverymode'] =  $this->copyapplication->getCCADeliveryMode();
	   return view('cca.ccadelivery',$data)->with('user',$request->session()->get('userName'));
	}

	public function getCCAApplicationsByApplId(Request $request)
    {
	  $request->validate([
		'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),
		'ccastatuscode' => 'required|numeric',
              ]);
        $establishcode = $request->session()->get('EstablishCode');
		$applicationId = $request->input('applicationId');
		$ccastatuscode = $request->input('ccastatuscode');
                            
  $data['ccapplicationDetails'] = $this->copyapplication->getCCAApplicationsByApplId($applicationId,$ccastatuscode,$establishcode);

	  echo json_encode($data['ccapplicationDetails']);
    }

  public function getCCApplicationDetails(Request $request)
    {
	  $request->validate([
		'ccaapplicationno' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),
              ]);
      $establishcode = $request->session()->get('EstablishCode');
      $ccaapplicationno = $_POST['ccaapplicationno'];
      $data['ccapplicationDetails'] = $this->copyapplication->getCCApplicationDetails($ccaapplicationno,$establishcode);

	  echo json_encode($data['ccapplicationDetails']);
    }


	public function getApplicationCA(Request $request)
	{

		$applicationId = $request->get('applicationId');
                $establishcode= $request->session()->get('EstablishCode');
		$data['ApplicationDet']  = $this->copyapplication->getSearchResults($applicationId,$establishcode);
	//	print_r($applicationId);
		echo json_encode($data['ApplicationDet']);
		//$searchdata = $this->copyapplication->getSearchResults($applicationid);
		//dd($searchdata);
		//return view('caveat.search',$data)->with('user',$request->session()->get('userName'));

    }
	public function getApplicationJudgement(Request $request)
	{
		$applicationid = $request->get('application_id');
		$judgement = $this->copyapplication->getJudgementResults($applicationid);
		//dd($searchdata);
		//return view('caveat.search',$data)->with('user',$request->session()->get('userName'));
		return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Application Added Successfully",
			'data'=>$judgement
           ]);
    }

	public function getApplicationStatus(Request $request)
	{
		$applicationid = $request->get('application_id');
		$data['applstatus'] = $this->copyapplication->getApplicationCAStatus($applicationid);
		echo json_encode($data['applstatus']);

	}
	public function getReceiptStatus(Request $request)
	{
		$search_recpno = $request->get('search_recpno');
		$recpno_status = $this->copyapplication->getReceiptCAStatus($search_recpno);

		return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Application Added Successfully",
			'data'=>$recpno_status

           ]);
	}
	function getCADistrict(Request $request)
    {
     $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data_app = DB::table('taluk')
       ->where($select, $value)
       ->orderBy('talukname', 'asc')->get();
     $output = '<option value="">Select Taluk</option>';
     foreach($data_app as $row)
     {
      $output .= '<option value="'.$row->talukcode.'">'.$row->talukname.'</option>';
     }
     echo $output;
    }

		public function rules() {
    $rules = [];

    switch($this->method()) {
        case 'POST':
        {
            $rules = [
                'contr_nom' => 'required|max:255',
                'contr_cog' => 'required',
                'polizza' => 'required',
                'email' => 'required',
                'targa' => 'required',
                'iban' => 'required|iban',
                'int_iban' => 'required',
            ];
        }

        default:
            break;
    }

    return $rules;
}


 public function saveCCApplication(Request $request)
    {
			$request->validate([
									'applicationId' => 'required',
									'applTypeName' => 'required',
									'dateOfOrd' => 'nullable|required_if:ccadoc_type,1,2|date',
									'dateOfCA' => 'required|date',
									'ccadoc_type' => 'required|numeric',
									'documentname' => 'required_if:ccadoc_type,3',
									'caapplicantname' => 'required_if:isAdvocate,N',
									'caaddress' => 'required_if:isAdvocate,N',
									'capincode' => 'required_if:isAdvocate,N',
									'distcode' => 'required_if:isAdvocate,N',
									'CATaluk' => 'required_if:isAdvocate,N',
									'isAdvocate' => 'required|in:Y,N',
									'advBarRegNo' => 'required_if:isAdvocate,Y',
									'advName' => 'required_if:isAdvocate,Y',
									'receiptNo' => 'required',
									'receiptDate' => 'required|date',

				]);
		try{
			$applType = explode('-',$request->get('applTypeName'));
			$applicationid = $applType[1].'/'.$request->get('applicationId');
			$establishcode = $request->session()->get('EstablishCode');

			$applnStore['applicationid'] = $applicationid;
			$applnStore['appltypecode'] = $applType[0];
			if($request->input('dateOfOrd') != null){
				$applnStore['orderdate'] = date('Y-m-d',strtotime($request->input('dateOfOrd')));

			}
			$applnStore['caapplicationdate'] = date('Y-m-d',strtotime($request->input('dateOfCA')));

			$applnStore['documenttype'] = $request->input('ccadoc_type');
			$applnStore['documentname'] = $request->input('documentname');

			$applnStore['caapplicantname']=$request->get('app_name');
                     
			$applnStore['caaddress']=$request->input('caaddress');
			$applnStore['capincode']=$request->input('capincode');
			$applnStore['cadistrict']=$request->input('distcode');
			$applnStore['cataluk']=$request->input('CATaluk');

			$applnStore['isadvocate']=$request->input('isAdvocate');
			$applnStore['advocateregno']=$request->input('advBarRegNo');
			$applnStore['advocatename']=$request->input('advName');
			$applnStore['receiptno']=$request->input('receiptNo');
			$applnStore['receiptdate'] = date('Y-m-d',strtotime($request->input('receiptDate')));

			$applnStore['receiptamount']=$request->input('recpAmount');

			$applnStore['totamount']=$request->input('amount_coll');
			$applnStore['deficitamount']=$request->input('defi_amt');

			$applnStore['copycount']=$request->input('noOfCopies');
			$applnStore['pagecount']=$request->input('noOfPages');
			$applnStore['copyreadyon']=date('Y-m-d',strtotime($request->input('ccdeliverydate')));

			$applnStore['establishcode'] = $establishcode;
			if($request->input('defi_amt')==0 or $request->input('defi_amt')==null )
			{
				$applnStore['ccastatuscode'] = 1;
			}else{
				$applnStore['ccastatuscode'] = 2;
			}
			$ccaapplicationno='';
      	    if($request->input('sbmt_adv') == "A")
			{
				$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				$useractivitydtls['activity'] ='Add Certified Copy Application' ;
				$useractivitydtls['userid'] = $request->session()->get('username');
				$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
				$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				$cur_year = date("Y");

				 DB::beginTransaction();

					$nextno =  DB::select("SELECT f_caapplicationsrno('$establishcode','$cur_year','CAA') as nin");
					$nextno = $nextno[0]->nin;
					list($cano, $yr) = explode('/', $nextno);
					$ccaapplicationno = $establishcode . '/CA' . $cano . '/' . $yr;
					$applnStore['ccaapplicationno']  = $ccaapplicationno;
					$applnStore['cano'] = $cano;
					$applnStore['createdby']= $request->session()->get('userName');
					$applnStore['createdon']= date('Y-m-d H:i:s') ;
					$useractivitydtls['applicationid_receiptno'] =$ccaapplicationno;
					$this->copyapplication->addCCApplication($applnStore);

					$receiptStore['applicationid']= $ccaapplicationno;
					$receiptStore['receiptuseddate']= date('Y-m-d H:i:s') ;
					$receiptStore['receiptno'] = $request->input('receiptNo');
					$receiptSrNo = explode('/',$request->input('receiptNo'));
					$receiptStore['receiptsrno'] = $receiptSrNo[2];
					$this->case->updateReceiptDetails($receiptStore, $receiptStore['receiptsrno'],$receiptStore['receiptno']);

					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);

				   DB::commit();
				   return response()->json([
							'status' => "sucess",
							'message' => " Certified Copy Application saved Successfully, CA No - ". $ccaapplicationno." "
							]);

			 }

			else if($request->input('sbmt_adv') == "U"){
				$ccaapplicationno=$request->input('ccaapplicationno');
				$applnStore['updatedby']= $request->session()->get('userName');
				$applnStore['updatedon']= date('Y-m-d H:i:s') ;

				$useractivitydtls['applicationid_receiptno'] =$ccaapplicationno;
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				 $useractivitydtls['activity'] ='Update CCA Application' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		         DB::beginTransaction();
				  $this->copyapplication->updateCCApplication($applnStore,$ccaapplicationno);
				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
				DB::commit();
			    return response()->json([
							'status' => "sucess",
							'message' => " Certified Copy Updated Successfully, CA No - ". $ccaapplicationno." "
				]);
	    }
		}
		catch(\Exception $e){

                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
					]);
            }
    }


 public function saveCCADeficitPayment(Request $request)
    {
		$request->validate([
             'ccapplno' => 'required',
			 'applicationId' => 'required',
             'ccdeliverydate' => 'required|date',
			 'defi_amt' => 'required|numeric',
			 'receiptNo' => 'required',
			 'receiptDate' => 'required|date',
			 'recpAmount'  => 'required|numeric',
			]);
		try{
			$applnStore['deficitreceiptno'] = $request->input('receiptNo');
			$applnStore['deficitreceiptdate'] =date('Y-m-d',strtotime($request->input('receiptDate')));
			$applnStore['deficitreceiptamount'] = $request->input('recpAmount');
			$applnStore['copyreadyon'] =date('Y-m-d',strtotime($request->input('ccdeliverydate')));

			$applnStore['ccastatuscode'] = 1;
			$ccaapplicationno=$request->input('ccapplno');
      	    if($request->input('sbmt_adv') == "A")
			{
				$applnStore['deficitenteredby']= $request->session()->get('userName');
				$applnStore['deficitenteredon']= date('Y-m-d H:i:s') ;

				$useractivitydtls['applicationid_receiptno'] =$ccaapplicationno;
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				 $useractivitydtls['activity'] ='Add CCA Deficit Entry' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		         DB::beginTransaction();
				 $this->copyapplication->updateCCApplication($applnStore,$ccaapplicationno);
					$receiptStore['applicationid']= $ccaapplicationno;
					$receiptStore['receiptuseddate']= date('Y-m-d H:i:s') ;
					$receiptStore['receiptno'] = $request->input('receiptNo');
					$receiptSrNo = explode('/',$request->input('receiptNo'));
					$receiptStore['receiptsrno'] = $receiptSrNo[2];
					$this->case->updateReceiptDetails($receiptStore, $receiptStore['receiptsrno'],$receiptStore['receiptno']);
				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
				 DB::commit();
				 return response()->json([
							'status' => "sucess",
							'message' => " Deficit Entry saved Successfully "
							]);

			 }

			else if($request->input('sbmt_adv') == "U"){

				$useractivitydtls['applicationid_receiptno'] =$ccaapplicationno;
				 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				 $useractivitydtls['activity'] ='Update CCA Deficit Entry' ;
				 $useractivitydtls['userid'] = $request->session()->get('username');
				 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
				 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		         DB::beginTransaction();
				  $this->copyapplication->updateCCApplication($applnStore,$ccaapplicationno);
				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
				DB::commit();
			    return response()->json([
							'status' => "sucess",
							'message' => " Deficit Entry Updated Successfully of CA No - ". $ccaapplicationno." "
				]);
	    }
		}
		catch(\Exception $e){

                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
					]);
            }

    }

public function updateCCAStatus(Request $request)
    {
		$request->validate([
             'ccaapplicationno' => 'required',
			 'readyrejectedDate' => 'required|date',
			 'caapplStatus' => 'required|in:3,4',
			 'rejectedreason' => 'required_if:caapplStatus,3',
			]);
			try{
		    $ccaapplicationno=$request->input('ccaapplicationno');
			$ccastatuscode = $request->input('caapplStatus');
			$applnStore['ccastatuscode'] = $ccastatuscode;
			if($ccastatuscode == 3){
				$applnStore['rejectionreason'] = $request->input('rejectedreason');
				$applnStore['rejectedon'] =date('Y-m-d',strtotime($request->input('readyrejectedDate')));
				$applnStore['copyreadyon']='';
			}
			if($ccastatuscode == 4){
				$applnStore['copyreadyon'] =date('Y-m-d',strtotime($request->input('readyrejectedDate')));
			}

			$this->copyapplication->updateCCApplication($applnStore,$ccaapplicationno);
			  return response()->json([
							'status' => "sucess",
							'message' => "CCA Status Updated Successfully, CA No - ". $ccaapplicationno." "
				]);
			}catch(\Exception $e){
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
					]);
            }
	}


public function updateCCADeliveryStatus(Request $request)
    {
		$request->validate([
             'ccaapplicationno' => 'required',
			 'deliveryDate' => 'required|date',
			 'deliverycode' => 'required | numeric',
			 'deliveredTo' => 'required_if:deliverycode,1',
			]);
			try{
				$ccaapplicationno=$request->input('ccaapplicationno');
				$deliverycode = $request->input('deliverycode');
				$applnStore['deliverycode'] = $deliverycode;
				$applnStore['deliveredon'] = date('Y-m-d',strtotime($request->input('deliveryDate')));
				$applnStore['ccastatuscode'] =5;
				if($deliverycode == 1){
					$applnStore['deliveredto'] = $request->input('deliveredTo');
				}
				else{
					$applnStore['deliveredto'] = '';
				}

			$this->copyapplication->updateCCApplication($applnStore,$ccaapplicationno);
			  return response()->json([
							'status' => "sucess",
							'message' => "CCA Delivery Status Updated Successfully, CA No - ". $ccaapplicationno." "
				]);
			}catch(\Exception $e){
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"
					]);
            }
	}


}
