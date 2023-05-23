<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IANature;
use App\CopyApplication;
use Illuminate\Support\Facades\DB;
use App\ModuleAndOptions;
use Carbon\Carbon;
use App\CaseManagementModel;

class CCAController extends Controller
{
    //
	public function __construct()
    {
		$this->case = new CaseManagementModel();  
		$this->copyapplication = new copyapplication();
		$this->IANature = new IANature();
	}
	
	public function index(Request $request)
    {
		$data['docType'] = $this->copyapplication->getDocumentType();
		$data['advocatedetails'] = $this->copyapplication->getAdvocate();
		$data['dist_list'] = $this->copyapplication->getDistList();
		$data['estcode'] = $request->session()->get('EstablishCode');
	    $data['applType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
		
		return view('cca.ccapplication',$data)->with('user',$request->session()->get('userName'));
	}
	public function getApplicationCA(Request $request)
	{
		
		$applicationId = $request->get('applicationId');
		$data['ApplicationDet']  = $this->copyapplication->getSearchResults($applicationId);
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
       ->get();
     $output = '<option value="">Select Taluk</option>';
     foreach($data_app as $row)
     {
      $output .= '<option value="'.$row->talukcode.'">'.$row->talukname.'</option>';
     }
     echo $output;
    }
}
