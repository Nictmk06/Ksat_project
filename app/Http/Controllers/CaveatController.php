<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\IANature;
use App\Caveatapplicant;
use App\UserActivityModel;
use Illuminate\Support\Facades\DB;
use App\ModuleAndOptions;
use Carbon\Carbon;


class CaveatController extends Controller
{
    //
	public function __construct()
    {
		$this->UserActivityModel = new UserActivityModel();
		$this->caveatapplicant = new caveatapplicant();
		$this->IANature = new IANature();
	}
	public function index(Request $request)
    {
		$caveatapplicant = caveatapplicant::all();

		$data['DeptType'] =  $this->caveatapplicant->getDeptType();
		$data['dist_list'] = $this->caveatapplicant->getDistrict();
    $data['advocatedetails'] = $this->caveatapplicant->getAdvocate();
		$data['nameTitle'] = $this->caveatapplicant->getNameTitle();
		$data['relationTitle'] = $this->caveatapplicant->getRelationTitle();
		$data['design'] = $this->caveatapplicant->getDesignation();
		//$data['applicationType'] = $this->caveatapplicant->getApplType();
		$data['applCategory'] = $this->caveatapplicant->getApplCategory();
		$data['actDetails'] = $this->caveatapplicant->getActDetails();
		$data['sectionDetails'] = $this->caveatapplicant->getSectionDetails();
		$data['establishment'] = $this->caveatapplicant->getEstablishment();
		$data['csrlno'] = $this->caveatapplicant->getSerialNo();
	//	$data['serialno'] = $this->caveatapplicant->getCaveatSerial();
		$data['estcode'] = $request->session()->get('EstablishCode');
		$user = $request->session()->get('username');



		//$data['jname'] = $this->bench->getJudge();
		//editin--sectionDetails
		$data['Temp'] = $this->caveatapplicant->getApplicationId($applicationid='',$user);
		$data['TempApplicant'] = $this->caveatapplicant->getApplicantDetails($applicationid='',$user);
		$data['TempRespondant'] = $this->caveatapplicant->getRespondantDetails($applicationid='',$user);
        //$data['TempReceipt'] = $this->caveatapplicant->getReceiptDetails($applicationid='',$user);

		$data['title']='caveat';

        return view('caveat.caveat',$data)->with('user',$request->session()->get('userName'));

		//index
	}

	function getcaveatstartno(Request $request)
		{
		 $year = $request->get('year');

		 $ccode=DB::SELECT("SELECT max(caveatsrno) from caveat where caveatyear='$year'");
     if($ccode[0]->max == null)
 		{
 			$ccode=0;
			$ccode++;
		return json_encode($ccode);
		}

		$ccode=$ccode[0]->max+1;
    echo json_encode($ccode);
		}
	function fetch(Request $request)
    {
     $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data = DB::table('department')
       ->where($select, $value)
       ->get();
     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
     foreach($data as $row)
     {
      $output .= '<option value="'.$row->departmentcode.'">'.$row->departmentname.'</option>';
     }
     echo $output;
    }
	function district(Request $request)
    {
     $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data = DB::table('taluk')
       ->where($select, $value)
       ->get();
     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
     foreach($data as $row)
     {
      $output .= '<option value="'.$row->talukcode.'">'.$row->talukname.'</option>';
     }
     echo $output;
    }
	function districtApplicant(Request $request)
    {
     $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data_app = DB::table('taluk')
       ->where($select, $value)
       ->get();
     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
     foreach($data_app as $row)
     {
      $output .= '<option value="'.$row->talukcode.'">'.$row->talukname.'</option>';
     }
     echo $output;
    }
	function getDesignationlist(Request $request)
	{
		$select = $request->get('select');
		$value = $request->get('value');
		$dependent = 'desigcode';
		$data_app = DB::table('designationview')
		->where($select, $value)
		->get();
		$output = '<option value="">Select '.ucfirst($dependent).'</option>';
			foreach($data_app as $row)
			{
				$output .= '<option value="'.$row->desigcode.'">'.$row->designame.'</option>';
			}
		echo $output;

	}
	function getDesignationlistRes(Request $request)
	{
		$select = $request->get('select');
		$value = $request->get('value');
		$dependent = 'desigcode';
		$data_app = DB::table('designationview')
		->where('departmentcode', $value)
		->get();
		$output = '<option value="">Select '.ucfirst($dependent).'</option>';
			foreach($data_app as $row)
			{
				$output .= '<option value="'.$row->desigcode.'">'.$row->designame.'</option>';
			}
		echo $output;

	}
	function advsearch(Request $request){
		$select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data = DB::table('advocate')
       ->where($select, $value)
       ->get();
     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
     foreach($data as $row)
     {
      $output .= '<option value="'.$row->advocateregno.'">'.$row->advresaddress.'</option>';
     }
     echo $output;

	}
	public function search(Request $request){

		$application_id = $request->get('application_id');

	    $appdata=DB::SELECT("SELECT applicantsrno,caveatid,applicantname,advocateregnno from caveatapplicant where caveatid='$application_id'");
       
       	return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Application Added Successfully",
			'data'=>$appdata,
			'appid'=>$application_id

           ]);
	}
	public function ressearch(Request $request){
		$application_id = $request->get('application_id');
        $resdata=DB::SELECT("SELECT caveateesrno,caveatid,caveateename,advocateregnno from caveatee where caveatid='$application_id'");
    	return response()->json([
            'success'=>'Got Simple Ajax Request.',
            'value' => "Respondant Added Successfully",
			'data'=>$resdata,
			'appid'=>$application_id
           ]);
	}
	public function store(Request $request)
	{
		$sbmt_case =  $request->input('sbmt_case');
		$establishcode = $request->session()->get('EstablishCode');
        $request->validate([
            'talukname' =>'required',

        ]);
		if($sbmt_case=='A'){

		$estname = $request->input('estname');
		$applYear = $request->input('applYear');
		$ccode=DB::SELECT("SELECT max(caveatsrno) from caveat where caveatyear='$applYear'");
    if($ccode[0]->max == null)
		{
			$ccode=0;
			$ccode++;
		}
		else
		{
		$ccode=$ccode[0]->max+1;
  	}
		$dateOfAppl =date('Y-m-d',strtotime($request->input('dateOfAppl')));
		$applCatName = $request->input('applCatName');
		$orderNo = $request->input('multiorder');
		$orderDate = date('Y-m-d',strtotime($request->input('orderDate')));
		$applnSubject = $request->input('applnSubject');
		$noOfAppl = $request->input('noOfAppl');
		$noOfRes = $request->input('noOfRes');
		$addrForService = $request->input('addrForService');
		$SerPincode = $request->input('rPincode');
		$SerDistrict = $request->input('distcode');
		$SerTaluk = $request->input('talukname');
		$remarks = $request->input('caseremarks');
		$current = Carbon::now('Asia/Kolkata');
		$caveat_id= $establishcode.'/C'.$ccode.'/'.$applYear;
		DB::table('caveat')->insert(['caveatid' => $caveat_id,
		'caveatsrno'=>$ccode,
		'caveatyear'=>$applYear,
		'caveatfiledate'=>$dateOfAppl,
		'applcatcode'=>$applCatName,
		'goorderno'=>$orderNo,
		'goorderdate'=>$orderDate,
		'subject'=>$applnSubject,
		'serviceaddress'=>$addrForService,
		'servicepincode'=>$SerPincode,
		'servicetaluk'=>$SerTaluk,
		'servicedistrict'=>$SerDistrict,
		'caveatorcount'=>$noOfAppl,
		'remarks'=>$remarks,
		'createdby'=>$request->session()->get('userName'),
		'createdon'=>$current,
		'caveateecount'=>$noOfRes,
		'establishcode'=>$establishcode]);
		 $useractivitydtls['applicationid_receiptno'] = $caveat_id;
		 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		 $useractivitydtls['activity'] ='Add Caveat' ;
		 $useractivitydtls['userid'] = $request->session()->get('username');
		 $useractivitydtls['establishcode'] = $establishcode ;
	     $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		$request->session()->flash('alert-success', 'Bench was successful created!');
		}
		else{
		$applStartNo = $request->input('applStartNo');
		$applYear = $request->input('applYear');
		$dateOfAppl = date('Y-m-d',strtotime($request->input('dateOfAppl')));
		$applCatName = $request->input('applCatName');
		$orderNo = $request->input('multiorder');
		$orderDate =date('Y-m-d',strtotime($request->input('orderDate')));
		$applnSubject = $request->input('applnSubject');
		$noOfAppl = $request->input('noOfAppl');
		$noOfRes = $request->input('noOfRes');
		$addrForService = $request->input('addrForService');
		$SerPincode = $request->input('rPincode');
		$SerDistrict = $request->input('distcode');
		$SerTaluk = $request->input('talukname');
		$remarks = $request->input('caseremarks');
		$current = Carbon::now('Asia/Kolkata');
		$caveat_id= $establishcode.'/C'.$applStartNo.'/'.$applYear;
		DB::table('caveat')->where('caveatid',$caveat_id)
		->update(['caveatfiledate'=>$dateOfAppl,
		'applcatcode'=>$applCatName,
		'goorderno'=>$orderNo,
		'goorderdate'=>$orderDate,
		'subject'=>$applnSubject,
		'serviceaddress'=>$addrForService,
		'servicepincode'=>$SerPincode,
		'servicetaluk'=>$SerTaluk,
		'servicedistrict'=>$SerDistrict,
		'caveatorcount'=>$noOfAppl,
		'remarks'=>$remarks,
		'updatedby'=>$request->session()->get('userName'),
		'updatedon'=>$current,
		'caveateecount'=>$noOfRes]);
		 $useractivitydtls['applicationid_receiptno'] = $caveat_id;
		 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		 $useractivitydtls['activity'] ='Update Caveat' ;
		 $useractivitydtls['userid'] = $request->session()->get('username');
		 $useractivitydtls['establishcode'] = $establishcode ;
	     $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		$request->session()->flash('alert-success', 'Updated was successful!');
		}


		return view('caveat.caveat');
	}
	public function Applicantstore(Request $request){

		//$estname = $request->input('estname');
		$sbmt_applicant =  $request->input('sbmt_applicant');
		$establishcode = $request->session()->get('EstablishCode');

		if($sbmt_applicant=='A'){
		$caveat_id = $request->input('recAppId');
		$Atitle = $request->input('applicantTitle');
		$applicantName = $request->input('applicantName');
		$relType = $request->input('relType');
		$relationTitle = $request->input('relationTitle');
		$relationName = $request->input('relationName');
		$gender = $request->input('gender');
		$applAge = $request->input('applAge');
		$depttypecode = $request->input('depttypecode');
		$departmentname= $request->input('departmentcode');
		$desigAppl = $request->input('desigAppl');
		$addressAppl = $request->input('addressAppl');
		$pincodeAppl = $request->input('pincodeAppl');
		$distcode = $request->input('adistcode');
		$taluknameApp = $request->input('taluknameApp');
		$applMobileNo = $request->input('applMobileNo');
		$applEmailId = $request->input('applEmailId');
		$partyInPerson = $request->input('partyInPerson');
		$advBarRegNo = $request->input('advBarRegNo');
		$current = Carbon::now('Asia/Kolkata');
		$srcode=DB::table('caveatapplicant')->where('caveatid',$caveat_id)->max('applicantsrno');
		$srcode++;
		//$Rtitle = $request->input('Rtitle');
		//$rel7 = $request->input('rel7');
		/* $ = $request->input('');
		$ = $request->input(''); */
		DB::table('caveatapplicant')->insert(['depttype'=>$depttypecode,
		'departcode'=>$departmentname,
		'applicanttitle'=>$Atitle,
		'applicantname'=>$applicantName,
		'reltype'=>$relType,
		'relationtitle'=>$relationTitle,
		'relationname'=>$relationName,
		'desigcode'=>$desigAppl,
		'gender'=>$gender,
		'age'=>$applAge,
		'caveataddress'=>$addressAppl,
		'caveatpincode'=>$pincodeAppl,
		'talukcode'=>$taluknameApp,
		'districtcode'=>$distcode,
		'caveatmobileno'=>$applMobileNo,
		'caveatemail'=>$applEmailId,
		'partyinperson'=>$partyInPerson,
		'advocateregnno'=>$advBarRegNo,
		'applicantsrno'=>$srcode,
		'createdby'=>$request->session()->get('userName'),
		'createdon'=>$current,
		'caveatid'=>$caveat_id]);
		 $useractivitydtls['applicationid_receiptno'] = $caveat_id;
		 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		 $useractivitydtls['activity'] ='Add Caveator ' ;
		 $useractivitydtls['userid'] = $request->session()->get('username');
		 $useractivitydtls['establishcode'] = $establishcode ;
	     $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		}else{
			$srno_appl = $request->input('srno_applicant');
			$current = Carbon::now('Asia/Kolkata');
			$caveat_id = $request->input('recAppId');
			$desigAppl = $request->input('desigAppl');
			$Atitle = $request->input('applicantTitle');
			$applicantName = $request->input('applicantName');
			$relType = $request->input('relType');
			$relationTitle = $request->input('relationTitle');
			$relationName = $request->input('relationName');
			$gender = $request->input('gender');
			$applAge = $request->input('applAge');
			$depttypecode = $request->input('depttypecode');
			$departmentname= $request->input('departmentcode');
			$desigAppl = $request->input('desigAppl');
			$addressAppl = $request->input('addressAppl');
			$pincodeAppl = $request->input('pincodeAppl');
			$distcode = $request->input('adistcode');
			$taluknameApp = $request->input('taluknameApp');
			$applMobileNo = $request->input('applMobileNo');
			$applEmailId = $request->input('applEmailId');
			$partyInPerson = $request->input('partyInPerson');
			$advBarRegNo = $request->input('advBarRegNo');
			$current = Carbon::now('Asia/Kolkata');
			DB::table('caveatapplicant')->where('caveatid',$caveat_id)->where('applicantsrno',$srno_appl)
			->update(['depttype'=>$depttypecode,
			'departcode'=>$departmentname,
			'applicanttitle'=>$Atitle,
			'applicantname'=>$applicantName,
			'reltype'=>$relType,
			'relationtitle'=>$relationTitle,
			'relationname'=>$relationName,
			'desigcode'=>$desigAppl,
			'gender'=>$gender,
			'age'=>$applAge,
			'caveataddress'=>$addressAppl,
			'caveatpincode'=>$pincodeAppl,
			'talukcode'=>$taluknameApp,
			'districtcode'=>$distcode,
			'caveatmobileno'=>$applMobileNo,
			'caveatemail'=>$applEmailId,
			'partyinperson'=>$partyInPerson,
			'advocateregnno'=>$advBarRegNo,
			'updatedby'=>$request->session()->get('userName'),
			'updatedon'=>$current]);
			}
//		$request->input('applicantName')='';
		return view('caveat.caveat');
	}
	function fetchdeptres(Request $request)
    {
     $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data = DB::table('department')
       ->where('depttypecode', $value)
       ->get();
     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
     foreach($data as $row)
     {
      $output .= '<option value="'.$row->departmentcode.'">'.$row->departmentname.'</option>';
     }
     echo $output;
    }
	function districtRespondant(Request $request)
    {
     $select = $request->get('select');
	 $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data_app = DB::table('taluk')
       ->where($select, $value)
       ->get();
     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
     foreach($data_app as $row)
     {
      $output .= '<option value="'.$row->talukcode.'">'.$row->talukname.'</option>';
     }
     echo $output;
    }

	public function Respondantstore(Request $request){

		$sbmt_respondant =  $request->input('sbmt_respondant');
		$establishcode = $request->session()->get('EstablishCode');

		if($sbmt_respondant == 'A'){
		$caveatee_id = $request->input('resApplId');
		$respondantTitle = $request->input('respondantTitle');
		$respondantName = $request->input('respondantName');

		$resType = $request->input('resRelation');
		$resTitle = $request->input('resRelTitle');
		$resName = $request->input('resRelName');
		$resGen = $request->input('resGender');
		$resAge = $request->input('resAge');
		$resAddress2 = $request->input('resAddress2');
		$resDeptType = $request->input('resDeptType');
		$resnameofDept = $request->input('resnameofDept');
		$resdesig = $request->input('desigRes');
		$respin = $request->input('respincode2');
		$restaluk = $request->input('taluknameRes');
		$resdistcode = $request->input('rdistcode');
		$resmobile = $request->input('resMobileNo');
		$resemail = $request->input('resEmailId');
		$isgovtadv = $request->input('isgovtadv');
		$current = Carbon::now('Asia/Kolkata');
		$resadvoc = $request->input('resadvBarRegNo');
		$rsrcode=DB::table('caveatee')->where('caveatid',$caveatee_id)->max('caveateesrno');
		$rsrcode++;
		DB::table('caveatee')->insert(['caveatid'=>$caveatee_id,
		'caveateetitle'=>$respondantTitle,
		'caveateereltype'=>$resType,
		'relationtitle'=>$resTitle,
		'relationname'=>$resName,
		'caveateegender'=>$resGen,
		'caveateeage'=>$resAge,
		'caveateedesigcode'=>$resdesig,
		'caveateeaddress'=>$resAddress2,
		'caveateesrno'=>$rsrcode,
		'caveateedepttype'=>$resDeptType,
		'caveateedepartcode'=>$resnameofDept,
		'caveateepincode'=>$respin,
		'caveateetaluk'=>$restaluk,
		'caveateedistrict'=>$resdistcode,
		'caveateemobileno'=>$resmobile,
		'caveateeemail'=>$resemail,
		'isgovtadvocate'=>$isgovtadv,
		'advocateregnno'=>$resadvoc,
		'createdby'=>$request->session()->get('userName'),
		'createdon'=>$current,
		'caveateename'=>$respondantName]);

		 $useractivitydtls['applicationid_receiptno'] = $caveatee_id;
		 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		 $useractivitydtls['activity'] ='Add Caveatee ' ;
		 $useractivitydtls['userid'] = $request->session()->get('username');
		 $useractivitydtls['establishcode'] = $establishcode ;
	     $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		}else{
			$request->validate([
				'resAge' => 'required|integer|between:0,100',
			]);
			$srno_resp = $request->input('srno_respondant');
			$resdesig = $request->input('desigRes');
			$caveatee_id = $request->input('resApplId');
			$respondantTitle = $request->input('respondantTitle');
			$respondantName = $request->input('respondantName');
			$resType = $request->input('resRelation');
			$resTitle = $request->input('resRelTitle');
			$resName = $request->input('resRelName');
			$resGen = $request->input('resGender');
			$resAge = $request->input('resAge');
			$resAddress2 = $request->input('resAddress2');
			$resDeptType = $request->input('resDeptType');
			$resnameofDept = $request->input('resnameofDept');
			$respin = $request->input('respincode2');
			$restaluk = $request->input('taluknameRes');
			$resdistcode = $request->input('rdistcode');
			$resmobile = $request->input('resMobileNo');
			$resemail = $request->input('resEmailId');
			$isgovtadv = $request->input('isgovtadv');
			$current = Carbon::now('Asia/Kolkata');
			$resadvoc = $request->input('resadvBarRegNo');
			DB::table('caveatee')->where('caveatid',$caveatee_id)->where('caveateesrno',$srno_resp)
			->update(['caveateetitle'=>$respondantTitle,
		'caveateereltype'=>$resType,
		'relationtitle'=>$resTitle,
		'relationname'=>$resName,
		'caveateeaddress'=>$resAddress2,
		'caveateedepttype'=>$resDeptType,
		'caveateedepartcode'=>$resnameofDept,
		'caveateepincode'=>$respin,
		'caveateetaluk'=>$restaluk,
		'caveateedistrict'=>$resdistcode,
		'caveateemobileno'=>$resmobile,
		'caveateeemail'=>$resemail,
		'isgovtadvocate'=>$isgovtadv,
		'advocateregnno'=>$resadvoc,
		'caveateename'=>$respondantName,
		'caveateeage'=>$resAge,
		'caveateegender'=>$resGen,
		'caveateedesigcode'=>$resdesig,
		'updatedby'=>$request->session()->get('userName'),
		'updatedon'=>$current]);
		}
		/* 'depttype'=>$depttypecode,
		'departcode'=>$departmentname,
		'applicanttitle'=>$Atitle,
		'applicantname'=>$applicantName,
		'relationtitle'=>$relationTitle,
		'relationname'=>$relationName,
		'gender'=>$gender,
		'caveataddress'=>$addressAppl,
		'caveatpincode'=>$pincodeAppl,
		'talukcode'=>$taluknameApp,
		'districtcode'=>$distcode,
		'caveatmobileno'=>$applMobileNo,
		'caveatemail'=>$applEmailId,
		'advocateregnno'=>$advBarRegNo, */
		//$request->input('respondantName')='';

		return view('caveat.caveat');

	}
	public function getCaveatBasedOnID(Request $request)
    {
        $applicationid = $request->get('applicationid');
        //$user = $_POST['user'];
		$user = $request->get('userName');
        $data['flag']='E';
        $data['Temp'] = $this->caveatapplicant->getCApplicationId($applicationid);
       //print_r($data['Temp']);
             if(count($data['Temp'])==0)
            {
               return response()->json([
            'status' => 'success',
            'message'=>'Application Does Not Exist']);
            }

        if( count($data['Temp'])>0)
            {
                 $data['taluka3'] = $this->caveatapplicant->getTaluka($data['Temp'][0]->servicedistrict);

            }
            else
            {
                    $data['taluka3'] = $this->caveatapplicant->getTaluka($distCode='');
               //  return response()->json([
            //'status' => 'success',
            //'message'=>'Application ELSE Exists']);
            }
		$data['TempApplicant'] = $this->caveatapplicant->getApplicantDetails($applicationid,$user);
        $data['TempRespondant'] = $this->caveatapplicant->getRespondantDetails($applicationid,$user);
        $options = view('caveat.caseData',$data)->render();
        echo json_encode($options);
       /* $data['Temprelief'] = $this->caveatapplicant->getRelief($applicationid,$newsrno='',$user);
        $data['TempReceipt'] = $this->caveatapplicant->getReceiptDetails($applicationid,$user);
        $data['TempApplicant'] = $this->caveatapplicant->getApplicantDetails($applicationid,$user);
        $data['TempRespondant'] = $this->caveatapplicant->getRespondantDetails($applicationid,$user);
        $data['TempApplTypeRefer'] = $this->caveatapplicant->getApplTypeRefer($applicationid,$user);
        $data['ApplicationIdex'] = $this->->getApplicationIndex($applicationid,$user);
		*/
		//return view('caveat.caseData',$data);
        //echo json_encode($options);
		 //return response()->json([
           // 'status' => 'success',
           // 'message'=>'Application Does Not Exist']);

    }
	public function ApplicantSerialDetails(Request $request)
    {
        $newSrno = $request->get('newSrno');
		$applicationid = $request->get('applicationid');
        $applsrdata = $this->caveatapplicant->getApplicantSerialDetails($newSrno,$applicationid);
		echo json_encode($applsrdata);
    }
	public function RespondantSerialDetails(Request $request)
    {
        $newSrno = $request->get('newSrno');
		$applicationid = $request->get('applicationid');
        $respsrdata = $this->caveatapplicant->getRespondantSerialNoDetails($newSrno,$applicationid);
        echo json_encode($respsrdata);
    }
		public function getAdvRegNoApp(Request $request)
    {
        $advbarregNo = $_POST['value'];
        $data['advocateDetails'] = $this->caveatapplicant->getAdvDetails($advbarregNo);
        echo json_encode($data['advocateDetails']);
    }
}
