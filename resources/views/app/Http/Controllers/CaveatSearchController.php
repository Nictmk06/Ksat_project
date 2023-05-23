<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IANature;
use App\Caveatsearch;
use Illuminate\Support\Facades\DB;
use App\ModuleAndOptions;
use Carbon\Carbon;


class CaveatSearchController extends Controller
{
    //
	public function __construct()
    {
	
		$this->caveatsearch = new caveatsearch();
		$this->IANature = new IANature();
	}
	public function index(Request $request)
    {
		$data['applType'] =  $this->caveatsearch->getApplicationType();
		$data['applCategory'] = $this->caveatsearch->getApplicationCategory();
		$data['searchon'] = $this->caveatsearch->getSearchOn();
		$data['department'] = $this->caveatsearch->getDepartment();
		$data['estcode'] = $request->session()->get('EstablishCode');
		
		return view('caveat.search',$data)->with('user',$request->session()->get('userName'));
	}
	public function SearchDetails(Request $request){
		//$tosearch = $request->get('value');
		//$type = $request->get('type');
		$select = $request->get('searchon');
		$value = $request->get('search_value');
		$flag_caveat = $request->get('checkvalue');
		$department = $request->get('deprtment_value');
		$searchin = $request->get('searchin');
		$estcode = $request->get('estcode');
		
		if ($select == 'caveatid')
		{
			$select='caveat.caveatid';
		}
		
		if ($select == 'goorderno')
		{
			$select='goorderno';
		}
		if ($select == 'goorderdate')
		{
			$select='goorderno';
			$value = $request->get('goorderdate');
		}
	
		$searchdata = $this->caveatsearch->getSearchResults($select,$value,$flag_caveat,$searchin,$department,$estcode);
		//dd($searchdata);
		//return view('caveat.search',$data)->with('user',$request->session()->get('userName'));
		return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Application Added Successfully",
			'data'=>$searchdata

           ]);
		//echo json_encode($searchdata);
		
	}
	public function SearchDetailsNull(Request $request){
		//$tosearch = $request->get('value');
		//$type = $request->get('type');
		//$select = $request->get('searchon');
		//$value = $request->get('search_value');
		$flag_caveat = $request->get('checkvalue');
		$department = $request->get('deprtment_value');
		$searchin = $request->get('searchin');
		$estcode = $request->get('estcode');
		
		$searchdata = $this->caveatsearch->getSearchResultsNull($flag_caveat,$searchin,$department,$estcode);
		//dd($searchdata);
		//return view('caveat.search',$data)->with('user',$request->session()->get('userName'));
		return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Application Added Successfully",
			'data'=>$searchdata

           ]);
		//echo json_encode($searchdata);
		
	}
	
	public function SearchBasedOnID(Request $request)
	{
		$caveatid = $request->get('caveatid');
		//$search_relsults = $this->caveatsearch->getSearchOnId($caveatid);
		$data['caveatDtls'] = $this->caveatsearch->getCaveatOnId($caveatid);
		$data['caveatorDtls'] = $this->caveatsearch->getCaveatorDtlsOnId($caveatid);
		$data['CaveateeDtls'] = $this->caveatsearch->getCaveateeDtlsOnId($caveatid);
		echo json_encode(array($data['caveatDtls'], $data['caveatorDtls'], $data['CaveateeDtls'])); 
		//return response()->json([
          //  'success'=>'Got Simple Ajax Request.',
          //  'value' => "Application Added Successfully",
		//	'data'=>$search_relsults
         //  ]);
	}

	public function LinkBasedOnID(Request $request){
		
		$applicantId = $request->get('applicantId');
		$caveatid = $request->get('caveatid');
		$current = Carbon::now('Asia/Kolkata');
		DB::table('application')->where('applicationid', $applicantId)->update([ 'caveatid'=>$caveatid,'caveatmatchdate'=> $current]);
		DB::table('caveat')->where('caveatid', $caveatid)->update([ 'matchdate'=> $current,'applicationid'=>$applicantId]);
		 $useractivitydtls['applicationid_receiptno'] = $caveatid;
		 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		 $useractivitydtls['activity'] ='Link Caveat' ;
		 $useractivitydtls['userid'] = $request->session()->get('username');
		 $useractivitydtls['establishcode'] = $establishcode ;
	     $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
		return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Application Added Successfully"
			
           ]);
	}
	
	public function validateApplication(Request $request)
	{
		$applicantId = $request->get('applicantId');
		$result = DB::table('application')->select('applicationid','nextlistdate','lastlistdate','caveatmatchdate','caveatid')->where('applicationid',$applicantId)->get();
		return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Application Added Successfully",
			'data' =>$result
           ]);
	}
	
}