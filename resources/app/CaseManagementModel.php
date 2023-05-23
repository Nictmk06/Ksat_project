<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CaseManagementModel extends Model
{

		public static function getApplicationId($applicationid,$user)
		{
			if($applicationid!='')
			{
				
				$value = DB::select("select application.*,iadocument.ianaturecode,iaprayer,applicationtype.* from application left join iadocument on iadocument.applicationid=application.applicationid and  iadocument.iasrno='1' left join 
						applicationtype on applicationtype.appltypecode=application.appltypecode
						where application.applicationid='".$applicationid."'  order by application.applicationid");
				
				return $value;
			}
			else
			{
				
				
					$value = DB::select("select application.*,iadocument.ianaturecode,iaprayer,applicationtype.* from application left join iadocument on iadocument.applicationid=application.applicationid  and iadocument.iasrno='1' left join 
						applicationtype on applicationtype.appltypecode=application.appltypecode
						where application.applicationid like 'T%' and application.createdby ='".$user."'   order by application.applicationid  desc limit 1");
					
		
					return $value;
			}
			
		}



		public static function getRelief($applicationId='',$newSrno='',$user)
		{

			if($applicationId=='')
			{
				//echo "in 1";
				$value=db::table('applrelief')->select('*')->where('applicationid', 'like', 'T%')->where('createdby', '=', $user)->orderby('reliefsrno', 'asc')->get();
			return $value;
			}
			else if($applicationId!='' && $newSrno!='')
			{
				//echo "in 2";
				$value=db::table('applrelief')->select('*')->where('applicationid',$applicationId)->where('reliefsrno',$newSrno)->orderby('reliefsrno', 'asc')->get();
				return $value;
			}
			else if($applicationId!='')
			{
				//echo "in 3";
				$value=db::table('applrelief')->select('*')->where('applicationid',$applicationId)->orderby('reliefsrno', 'asc')->get();
				return $value;
				
			}
			else
			{
				//echo "in 4";
				$value=db::table('applrelief')->select('*')->where('applicationid',$applicationId)->where('reliefsrno',$newSrno)->orderby('reliefsrno', 'asc')->get();
				return $value;
			}
			
		}
		public static function getApplicantDetails($applicationid='',$user)
		{ 
			if($applicationid!='')
			{
				$value=db::table('applicant')->select('*')->leftjoin('advocate', 'advocate.advocateregno', '=', 'applicant.advocateregno')->where('applicationid', '=', $applicationid)->orderby('applicantsrno', 'asc')->get();
				return $value;
			}
			else
			{
				$value=db::table('applicant')->select('*')->leftjoin('advocate', 'advocate.advocateregno', '=', 'applicant.advocateregno')->where('applicationid', 'like', 'T%')->where('applicant.createdby', '=', $user)->orderby('applicantsrno', 'asc')->get();
			return $value;
			}
			
		}



		public static function getRespondantDetails($applicationid='',$user)
		{
			if($applicationid!='')
			{
				$value=db::table('respondant')->select('*')->leftjoin('advocate', 'advocate.advocateregno', '=', 'respondant.advocateregno')->where('applicationid', '=', $applicationid)->orderby('respondsrno', 'asc')->get();
				return $value;
			}
			else
			{
				$value=db::table('respondant')->select('*')->leftjoin('advocate', 'advocate.advocateregno', '=', 'respondant.advocateregno')->where('applicationid', 'like', 'T%')->where('respondant.createdby', '=', $user)->orderby('respondsrno', 'asc')->get();
			return $value;
			}
			
		}

public static function getTopApplicantDetails($applicationid)
		{ 
			if($applicationid!='')
			{
				$value=db::table('applicant')->select('*')->where('applicationid', '=', $applicationid)->where('applicantsrno','1')->get();
				return $value;
			}	
		}



		public static function getTopRespondantDetails($applicationid)
		{
			if($applicationid!='')
			{
				$value=db::table('respondant')->select('*')->where('applicationid', '=', $applicationid)->where('respondsrno','1')->get();
				return $value;
			}
		}


		
		public static function getApplTypeRefer($applicationid,$user)
		{
			if($applicationid!='')
			{
				$value=db::table('applagainst')->select('*')->where('applagainst.applicationid', '=', $applicationid)->leftjoin('application','application.applicationid','=','applagainst.applicationid')->leftjoin('applicationtype','applicationtype.appltypecode','=','application.appltypecode')->orderby('applagainst.applicationid', 'desc')->get();
				return $value;
			}
			else
			{
				$value=db::table('applagainst')->select('*')->where('applagainst.applicationid', 'like', 'T%')->leftjoin('application','application.applicationid','=','applagainst.applicationid')->leftjoin('applicationtype','applicationtype.appltypecode','=','application.appltypecode')->where('applagainst.createdby', '=', $user)->orderby('applagainst.applicationid', 'desc')->get();
				return $value;
			}
			
		}
		public static function getApplicationIndex($applicationid,$user)
		{
			if($applicationid!='')
			{
				$value=db::table('applicationindex')->select('*')->where('applicationid', '=', $applicationid)->orderby('documentno', 'asc')->get();
				return $value;
			}
			else
			{
				$value=db::table('applicationindex')->select('*')->where('applicationid', 'like', 'T%')->where('createdby', '=', $user)->orderby('documentno', 'asc')->get();
				return $value;
			}
		}
		public static function getAdvDetails($advbarregNo)
		{
			$value = db::table('advocate')->select('advocatename','advocateaddress','advocate.distcode','advocate.talukcode','pincode','distname','talukname','nametitle','advocatecode')->leftjoin('taluk', 'taluk.talukcode', '=', 'advocate.talukcode')->leftjoin('district','district.distcode','=','advocate.distcode')->where('advocate.advocateregno',$advbarregNo)->get();
            return $value;
		}
		public static function getActDetails(){
			$value=db::table('act')->select('actcode','actname')->get(); 
			return $value;
		}
		public static function getAdv()
		{
			$value=db::table('advocate')->select('advocatename','advocateregno')->get();
			return $value;
		}
		public static function getResAdv($application_id)
		{
			//subquery with where not in
		
			$data = DB::table("advocate")->select('advocatename','advocateregno')
            ->whereNOTIn('advocateregno',function($query) use($application_id){
               $query->select('advocateregno')->whereNotNull('advocateregno')->where('applicationid',$application_id)->where('advocateregno','KAR/GOV/001')->from('applicant');
            })
            ->get();
          //  echo dd($data);
			return $data;
		}
		public static function getSectionDetails()
		{
			$value=db::table('actsection')->select('actsectioncode','actsectionname')->where('actcode',1)->orderBy('actsectioncode', 'asc')->get(); 
			return $value;
		}
		public static function getApplType()
		{
			$value=db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedefault','applfees','feerequired')->orderBy('appltypecode', 'asc')->get(); 
			return $value;
		}
		public static function getApplCategory()
		{
			$value=db::table('applcategory')->orderBy('applcatname', 'asc')->get(); 
			return $value;
		}

		public static function getApplCategoryName($applcatcode)
		{
			$value=db::table('applcategory')->where('applcatcode',$applcatcode)->orderBy('applcatname', 'asc')->get(); 
			return $value;
		}

		public static function getDistrict()
		{
			$value=DB::Table('district')->orderBy('distname', 'asc')->get(); 
			return $value;
		}
		public static function getTaluka($distCode)
		{
			if($distCode!='')
			{

				$value=DB::Table('taluk')->select('*')->where('distcode',$distCode)->orderBy('talukname', 'asc')->get(); 
				return $value;
			}
			else
			{
				$value=DB::Table('taluk')->orderBy('talukname', 'asc')->get(); 
				return $value;
			}
			
		}

		public static function getDeptNames($typeval)
		{
			if($typeval!='')
			{

				$value=DB::Table('department')->select('*')->where('depttypecode',$typeval)->orderBy('departmentname', 'asc')->get(); 
				return $value;
			}
			else
			{
				$value=DB::Table('department')->orderBy('departmentname', 'asc')->get(); 
				return $value;
			}
		}
		public static function getNameTitle()
		{
			$value=DB::Table('nametitle')->get(); 
			return $value;
		}
		public static function getRelationTitle()
		{
			$value=DB::Table('relationtitle')->orderby('relationtitle','asc')->get(); 
			return $value;
		}
		public static function getDeptType()
		{
			$value=DB::Table('departmenttype')->orderby('depttype','asc')->get(); 
			return $value;
		}
		public static function getDeptName()
		{
			$value=DB::Table('department')->orderby('departmentname','asc')->get(); 
			return $value;
		}
		public static function addApplDetails($applnStore,$IAStore)
		{

			if($IAStore!='')
			{
				//echo "in not null";
				$value = DB::table('application')->insert($applnStore);
				if($value)
				{
					$value = DB::table('iadocument')->insert($IAStore);
				}
				return $value;
			
			}
			else
			{
				//echo "in null";
				$value=DB::table('application')->insert($applnStore,$IAStore=''); 

				return $value;
			}
			
			
		}
		public static function addReliefDetails($reliefStore)
		{
				 
				$value=DB::table('applrelief')->insert($reliefStore); 
				/*$value = DB::table('applrelief')->select('reliefsrno')->where('applicationid',$reliefStore['applicationid'])->get()->last();*/
				return $value;
		}
		public static function addReceiptDetails($receiptStore)
		{
			try {
			$value=DB::table('receipt')->insert($receiptStore); 
			return $value;
			}catch(\Exception $e){
			return false;
			}
			
		}

		public static function getReceiptDetails($applicationid='',$user)
		{ 	
			if($applicationid!='')
			{
				$value=DB::Table('receipt')->select('*')->where('applicationid', '=', $applicationid)->orderBy('receiptno', 'asc')->get();
				return $value;
			}
			else
			{
			$application=DB::Table('application')->select('applicationid')->where('applicationid', 'LIKE', 'T%')->where('createdby', '=', $user)->get();
			 if (count($application)==0)
			 {
			 	$applicationno ="";
			 }
			 else{
			    $applicationno=$application[0]->applicationid;
			 }
			//$value=DB::select("select * from receipt where applicationid =(select applicationid from application where applicationid like 'T%' and createdby='".$user."')");
			$value=DB::Table('receipt')->select('*')->where('applicationid', '=', $applicationno)->orderBy('receiptno', 'asc')->get();
				//$value=DB::Table('receipt')->select('*')->where('applicationid', 'LIKE', 'T%')->where('createdby', '=', $user)->orderBy('receiptno', 'asc')->get();
			return $value;
			}
			
		}
		public static function getReceipts($receiptno)
		{
			$value=DB::Table('receipt')->select('*')->where('receiptsrno',$receiptno)->orderBy('receiptsrno','asc')->get();
			return $value;
		}
		public static function getReceiptsApplication($application_id)
		{

			$value=DB::Table('receipt')->select('*')->where('applicationid',$application_id)->orderBy('receiptsrno','asc')->get();
			return $value;
		}
		public static function addApplicantDetails($applicantStore)
		{
			/*try {*/
			$value=DB::table('applicant')->insert($applicantStore); 
			return $value;
			/*}catch(\Exception $e){
			return false;
			}*/
			
			/*DB::transaction(function () use($applicantStore,$noofappl,$application_id) {
			DB::table('applicant')->insert($applicantStore);
			DB::table('application')->where('applicationId', $application_id)->update(array('applicantCount'=>$noofappl));
			});*/
		}
		public function addRespondantDetails($repondantStore)
		{
			try {
			$value=DB::table('respondant')->insert($repondantStore); 
			return $value;
			}catch(\Exception $e){
			return false;
			}
		}
		public function getAllApplicationDetails($application_id,$user,$flag)
		{

			if($flag=='application')
			{
				$value = DB::select("select distinct a.applicationid,a.applicationdate,a.registerdate,a.subject,
				a.applcategory,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode,a.applicationyear,a.appltypecode,a.applicationsrno,a.applicationtosrno,a.serviceaddress,a.servicepincode,a.servicetaluk,a.servicedistrict,a.advocateregnno,a.actcode,a.actsectioncode,a.totalamount,a.applcategory,a.subject,a.interimprayer,a.isinterimrelief,a.advocatesingle,a.applicantcount,a.respondentcount,a.resadvsingle,a.rserviceaddress,a.rservicetaluk,a.rservicedistrict,a.rservicedistrict,a.againstorders,a.remarks,a.createdon
				from application as a  
				left join iadocument as b on b.applicationid=a.applicationid 
				left join applicationtype as c on c.appltypecode=a.appltypecode
				where a.applicationid='".$application_id."' 
				group by a.applicationid,b.iasrno,c.appltypecode,c.appltypeshort,b.documenttypecode
				order by b.iasrno desc limit 1");
				return $value;
			}
			else if($flag=='iadocument' || $flag=='connected')
			{
				
				$value = DB::select("select a.appltypecode, a.applicationid,a.applicationdate,
a.registerdate,a.applcategory,a.subject,b.appltypeshort,a.applicationsrno,a.applicationtosrno from application as a
left join applicationtype as b on
b.appltypecode=a.appltypecode where applicationid='".$application_id."'
                ");
			return $value;
			}
				
		}


//Mini 
		
	public function getApplicationDetailsCauseList($application_id,$user,$flag)
		{

			if($flag=='Fresh')
			{
				$value = DB::select("select a.appltypecode, a.applicationid,a.applicationdate,
				a.registerdate,a.applcategory,a.subject,c.appltypeshort,a.applicationsrno,a.applicationtosrno from application as a
				inner join scrutiny as b on b.applicationid=a.applicationid 
				left join applicationtype as c on
				c.appltypecode=a.appltypecode where a.applicationid='".$application_id."' and a.lastlistdate is null
				                ");
				return $value;
			}
			else if($flag=='Other')
			{
				$value = DB::select("select a.appltypecode, a.applicationid,a.applicationdate,
				a.registerdate,a.applcategory,a.subject,b.appltypeshort,a.applicationsrno,a.applicationtosrno from application as a
				left join applicationtype as b on
				b.appltypecode=a.appltypecode where applicationid='".$application_id."'
				                ");
				return $value;
			}
				
		}

		public function getSerialCount($application_id)
		{
			$value = DB::table('applicant')->where('applicationid',$application_id)->count();
			return $value;
		}
		public function getLastSrno($application_id)
		{
			//$value = DB::table('applicant')->select('applicantSrNo')->where('applicationId',$application_id)->latest()->first();
			$value=DB::table('applicant')->select('applicantsrno')->where('applicationid',$application_id)->orderBy('applicantsrno', 'desc')->first();
			return $value;
		}
		public function getRespSerialCount($application_id)
		{
			$value = DB::table('respondant')->where('applicationid',$application_id)->count();
			return $value;
		}
		public function getRespLastSrno($application_id)
		{
			//$value = DB::table('applicant')->select('applicantSrNo')->where('applicationId',$application_id)->latest()->first();
			$value=DB::table('respondant')->select('respondsrno')->where('applicationid',$application_id)->orderBy('respondsrno', 'desc')->first();
			return $value;
		}
		public function getApplExist($application_id,$startno,$applType,$applYear,$endno)
		{
			$value1 = DB::select("select count(*) as applcount from application where ((applicationsrno between '".$startno."' and '".$endno."') or  (applicationtosrno between '".$startno."' and '".$endno."')) and applicationyear='".$applYear."' and appltypecode='".$applType."'");
			 
			
			if($value1[0]->applcount==0)
			{

				$value = DB::select("select count(*) as applcount from groupno where (applicationsrno='".$startno."' or applicationsrno='".$endno."') and applicationyear='".$applYear."' and appltypecode='".$applType."'" );
				
				return $value;
			}
			//$value1 = $value1[0]->applcount;
			return $value1;
			
		}
		public function getApplicationExistanceCount($application_id,$startno,$applType,$applYear,$endno)
		{
		
			$value1 = DB::select("select count(*) as applcount from application where ((applicationsrno between '".$startno."' and '".$endno."') or  (applicationtosrno between '".$startno."' and '".$endno."')) and applicationyear='".$applYear."' and appltypecode='".$applType."'");
			 
			
			if($value1[0]->applcount==0)
			{

				$value = DB::select("select count(*) as applcount from groupno where ((applicationsrno between '".$startno."' and '".$endno."') or  (applicationtosrno between '".$startno."' and '".$endno."')) and applicationyear='".$applYear."' and appltypecode='".$applType."'");
				
				return $value;
			}
			//$value1 = $value1[0]->applcount;
			return $value1;

		}
		public function getReceiptExistanceCount($recpNewNo)
		{
			$value=DB::table('receipt')->where('receiptno',$recpNewNo)->exists();
			return $value;
		}
		public function addApplIndexStore($applDet,$application_id,$new_appl_id,$reviewApplId)
		{
			try {
			$value = DB::transaction(function () use($applDet,$application_id,$new_appl_id,$reviewApplId) {
			DB::table('application')->insert($applDet);
			DB::table('applicant')->where('applicationid', $application_id)->update(array('applicationid'=>$new_appl_id));
			DB::table('applrelief')->where('applicationid',$application_id)->update(array('applicationid'=>$new_appl_id));
			DB::table('respondant')->where('applicationid',$application_id)->update(array('applicationid'=>$new_appl_id));
			DB::table('receipt')->where('applicationid', $application_id)->update(array('applicationid'=>$new_appl_id));
			DB::table('iadocument')->where('applicationid', $application_id)->update(array('applicationid'=>$new_appl_id));
			
			if(!empty($reviewApplId))
			{
				DB::table('applagainst')->where('applicationid',$application_id)->delete();DB::table('applagainst')->insert(array('referapplid'=>$reviewApplId,'applicationid'=>$new_appl_id));
			}
			DB::table('application')->where('applicationid',$application_id)->delete();
			return true;
			});
			return $value;
			}catch(\Exception $e){
			return false;
			}
		}
		public static function addApplicationIndex($apllIndexStore)
		{
			$value=DB::table('applicationindex')->insert($apllIndexStore); 
			return $value;
		}
		public static function updateReceiptDetails($receiptUpStore, $receiptSrNo,$receiptNo)
		{

			try {
			$value=DB::table('receipt')->where('receiptsrno', $receiptSrNo)->where('receiptno',$receiptNo)->update($receiptUpStore);
			return $value;
			}catch(\Exception $e){
			return false;
			}
			
		}
		public static function getApplicantSerialNoDet($application_id)
		{
			$value=DB::Table('applicant')->select('*')->leftJoin('advocate', 'advocate.advocateregno', '=', 'applicant.advocateregno')->where('applicationid',$application_id)->orderBy('applicantsrno','asc')->get();
			return $value;
		}
		public function getApplicantSerialDetails($newSrno,$applicationid)
		{
			$advregno=null;
			if($advregno!=null)
			{
				
				$value=DB::Table('applicant')->select('*')->where('applicationid',$applicationid)->where('applicantsrno',$newSrno)->orderBy('applicantsrno','asc')->get();
				return $value;
				
			}
			else
			{
				//echo "in not null";
				
				$value = DB::Table('applicant')->select('*')->where('applicantsrno',$newSrno)->where('applicationid',$applicationid)->orderBy('applicantsrno','asc')->get();
				/*$value=DB::Table('applicant')->select('applicant.*','advocate.nametitle as advtitle','advocate.*')->leftJoin('advocate', 'advocate.advocateregno', '=', 'applicant.advocateregnno')->leftJoin('district','district.distcode','=','advocate.advresdistcode')->leftJoin('taluk','taluk.talukcode','=','advocate.advrestalukcode')->where('applicantsrno',$newSrno)->where('applicationid',$applicationid)->get();*/
				return $value;
			}
			
			
		}

		
		public static function getRespondantSerialNoDet($application_id)
		{

			$value=DB::Table('respondant')->select('*')->leftJoin('advocate', 'advocate.advocateregno', '=', 'respondant.advocateregno')->where('applicationid',$application_id)->orderBy('respondsrno','asc')->get();
			return $value;
		}
		public static function getRespondantSerialNoDetails($newSrno,$applicationid)
		{
			$value=DB::Table('respondant')->select('*')->where('respondsrno',$newSrno)->where('applicationid',$applicationid)->get();
			return $value;
		}
		public static function updateApplicantDetials($applicantStore,$applicantSrNo){
			/*try {*/
			$value=DB::table('applicant')->where('applicantsrno', $applicantSrNo)->where('applicationid',$applicantStore['applicationid'])->update($applicantStore);
			return $value;/*}catch(\Exception $e){
			return false;
			}*/
		}
		public static function UpdateRespondantDetails($repondantStore,$respondSrNo){
			
			try {
			$value=DB::table('respondant')->where('respondsrno', $respondSrNo)->where('applicationid',$repondantStore['applicationid'])->update($repondantStore);
			return $value;
			}catch(\Exception $e){
			return false;
			}
		}

		public static function updateApplDet($applnStore,$applId,$IAStore,$sbmt_case)
		{
			// changed by raj to delink intrim order and IA - if statements were deleted
		
					
			$value = DB::table('application')->where('applicationid', $applId)->update($applnStore);
		
	
			if ($value) {
			if(!empty($IAStore) )
			{ 

		
			  	$value1 = DB::table('iadocument')->where('applicationid',$applId)->where('iasrno',1)->first();
		   	
				if(!empty($value1))
					{
						$value1 = DB::table('iadocument')->where('applicationid', $applId)->where('iasrno',1)->update($IAStore);
						
					}
				else
					$value1 = DB::table('iadocument')->where('applicationid', $applId)->insert($IAStore);
			}
			else
			{	// ia is removed, to delete old IA if entered
	
			$value1 = DB::table('iadocument')->where('applicationid',$applId)->where('iasrno',1)->first();
				
				if(!empty($value))
					$value1 = DB::table('iadocument')->where('applicationid', $applId)->where('iasrno',1)->delete();

			}
		}
			return $value;

		}

/*			else if($applnStore['isinterimrelief']=='Y' && $IAStore=='')
			{
				//echo "interim yes";
				$value = DB::table('application')->where('applicationid', $applId)->update($applnStore);
				
				return $value;
			}
			else
			{
						$value = DB::table('iadocument')->where('applicationid',$applId)->where('iasrno',1)->first();
					if(count($value)>0)
					{
						//echo "exits";
						$value = DB::table('application')->where('applicationid', $applId)->update($applnStore);
						$value = DB::table('iadocument')->where('applicationid', $applId)->where('iasrno',1)->update($IAStore);
						return $value;
					}
					else 
			{
				//echo "out of box";
				$value = DB::table('iadocument')->where('applicationid', $applId)->insert($IAStore);
				return $value;
			}
		}
			
		} */

		public static function updateReliefDetails($reliefStore,$reliefSrNo,$appId)
		{
			//print_r($reliefupStore);
			
			$value=DB::table('applrelief')->where('reliefsrno', $reliefSrNo)->where('applicationid', $appId)->update($reliefStore);
			return $value;
		}
		
		public static function updateapplagainst($applicationid,$referapplid)
		{
		   try {
			$value=DB::Table('applagainst')->where(['applicationid'=>$applicationid])->update(['referapplid' =>$referapplid]); 
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}


		public static function getDesignation()
		{
			$value=DB::Table('designation')->orderby('designame','asc')->get(); 
			return $value;
		}

		public function addApplreferType($applagain)
		{
			try {
			$value=DB::Table('applagainst')->insert($applagain); 
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}
		public function updateAppreferType($applId,$revappl)
		{
			try {
				
			$value=DB::Table('applagainst')->where(['referapplid'=>$revappl])->update(['applicationid' =>$applId]); 
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}
		public static function getSingleAdvCount($applicationid)
		{
			$value = DB::table('applicant')->where('applicationid',$applicationid)->where('issingleadv','Y')->count();
			return $value;
		}
		public static function getResSingleAdvCount($applicationid)
		{
			$value = DB::table('respondant')->where('applicationid',$applicationid)->where('issingleadvocate','Y')->count();
			return $value;
		}
		public static function getSingleAdvData($applicationid)
		{
			$value = DB::table('applicant')->where('applicationid',$applicationid)->leftJoin('advocate','advocate.advocateregno','=','applicant.advocateregno')->leftJoin('taluk','taluk.talukcode','=','advocate.advrestalukcode')->leftJoin('district','district.distcode','=','advocate.advrestalukcode')->where('issingleadv','Y')->get();
			return $value;
		}
		public static function getResSingleAdvData($applicationid)
		{
			$value = DB::table('respondant')->where('applicationid',$applicationid)->leftJoin('advocate','advocate.advocateregno','=','respondant.advocateregno')->leftJoin('taluk','taluk.talukcode','=','advocate.advrestalukcode')->leftJoin('district','district.distcode','=','advocate.advrestalukcode')->where('issingleadvocate','Y')->get();
			return $value;
		}
		public static function updateApplIndex($applicationid,$apllIndexStore)
		{

			try{
				
				$value = DB::transaction(function () use($applicationid,$apllIndexStore) {
					
						
					DB::table('applicationindex')->insert($apllIndexStore);
				return true;
					
				});
				return $value;
			}catch(\Exception $e){
				return false;
				}
			
		}

		public function deleteApplicationId($applicationid,$user)
		{
			try{
				
				$value = DB::transaction(function () use($applicationid,$user) {
					
					
					DB::table('applicationindex')->where('applicationid',$applicationid)->where('createdby',$user)->delete();

				  $receipt=DB::Table('receipt')->select('receiptdate')->where('applicationid',$applicationid)->get();
			     if($receipt[0]->receiptdate < '2020-02-03')
				  {
				    DB::table('receipt')->where('applicationid',$applicationid)->delete();
				  }else{
				    DB::Table('receipt')->where('applicationid',$applicationid)->update(['applicationid' =>null]);
			    	}

					//DB::table('receipt')->where('applicationid',$applicationid)->where('createdby',$user)->delete();	
					
					DB::table('iadocument')->where('applicationid',$applicationid)->where('createdby',$user)->delete();
					DB::table('applrelief')->where('applicationid',$applicationid)->where('createdby',$user)->delete();
					DB::table('applagainst')->where('applicationid',$applicationid)->where('createdby',$user)->delete();
					DB::table('applicant')->where('applicationid',$applicationid)->where('createdby',$user)->delete();
					DB::table('respondant')->where('applicationid',$applicationid)->where('createdby',$user)->delete();
					DB::table('application')->where('applicationid',$applicationid)->where('createdby',$user)->delete();
				return true;
					
				});
				return $value;
			}catch(\Exception $e){
				return false;
				}
		}
		public function getSections($typeid)
		{
			$value = DB::table('actsection')->where('actsectioncode',$typeid)->get();
			return $value;
		}
		public function getEstablishName($establishcode)
		{
			$value = DB::table('establishment')->select('*')->where('establishcode',$establishcode)->get();
			return $value;
		}
		public function getApplRespDetails($applicationid,$flag)
		{
			if($flag=='A')
			{
				
				$value = DB::table('applicant')->select('*')->where('applicationid',$applicationid)->orderby('applicantsrno','asc')->get();
				return $value;
			}
			else if($flag='R')
			{
				
				$value = DB::table('respondant')->select('*')->where('applicationid',$applicationid)->orderby('respondsrno','asc')->get();
				return $value;
			}
		}
		public function getStartPageofApll($applicationid)
		{
			$value = DB::table('applicationindex')->select('endpage')->where('applicationid',$applicationid)->orderby('endpage','desc')->first();
			return $value;
		}
		public function updateApplicationIndex($applIndex,$applicationid,$documentno)
		{

			try{
			$value = DB::table('applicationindex')->where('applicationid',$applicationid)->where('documentno',$documentno)->update($applIndex);
			//echo dd($value);
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}
		public function getPartyStatus()
		{
			$value = DB::table('partystatus')->orderby('partystatus','asc')->get();
			//echo dd($value);
			return $value;
		}
		public function updateApplicant($applUp,$applicationid,$applsrno)
		{
			try{
			$value = DB::table('applicant')->where('applicationid',$applicationid)->where('applicantsrno',$applsrno)->update($applUp);
			//echo dd($value);
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}
		public function updateRespondant($resUp,$applicationid,$applsrno)
		{
			try{
			$value = DB::table('respondant')->where('applicationid',$applicationid)->where('respondsrno',$applsrno)->update($resUp);
			//echo dd($value);
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}

		public function addGroupApplication($groupStore)
		{
			try{
			$value = DB::table('groupno')->insert($groupStore);
			//echo dd($value);
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}
		public function updateGroupApplication($groupStore,$applicationid ,$applicationsrno,$applendsrno)
		{
			try{
			$value = DB::table('groupno')->where('applicationid',$applicationid)->where('applicationsrno',$applicationsrno)->where('applicationtosrno',$applendsrno)->update($groupStore);
			//echo dd($value);
			return $value;
			}catch(\Exception $e){
				return false;
				}
		}
		public function getGroupApp($applicationid)
		{
			$value = DB::table('groupno')->where('applicationid',$applicationid)->get();
			//echo dd($value);
			return $value;
		}
		public function getUserAppcount($userid)
		{
			$value = DB::select("select  application.createdby AS username,application.createdon::date AS entered_on,count(*) AS application_entered
from application where  application.createdby='".$userid."' group by application.createdby, (application.createdon::date) order by application.createdby, (application.createdon::date) DESC");
			return $value;

		}
}
?>