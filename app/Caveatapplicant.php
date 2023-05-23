<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class caveatapplicant extends Model
{
    //
	protected $table = 'caveatapplicant';
	public $timestamps = false;
	protected $primaryKey = 'caveatid';
	protected $fillable = [
      //  'caveatid','applicanttitle','applicantname'
	  'depttype','departcode','applicanttitle','applicantname',
		'relationtitle','relationname','gender','caveataddress','caveatpincode',
		'talukcode','districtcode','caveatmobileno','caveatemail','advocateregnno'];
	
	public function getDeptType()
	{
		$value=DB::Table('departmenttype')->select('*')->orderBy('depttypecode', 'asc')->get();
		return $value;
	}
	public function getDistrict()
	{
		$value = DB::Table('district')->select('*')->orderBy('distname')->get();
		return $value;
	
	}
	public function getAdvocate()
		{
			$value = DB::table('advocate')->select('*')->get();
			return $value;
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
		
	public static function getDesignation()
		{
			$value=DB::Table('designation')->orderby('designame','asc')->get(); 
			return $value;
		}
	/* public static function getApplType()
		{
			$value=db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedefault','actsectioncode')->orderBy('appltypecode', 'asc')->get(); 
			return $value;
		}	
	 */
	 public static function getApplCategory()
		{
			$value=db::table('applcategory')->orderBy('applcatname', 'asc')->get(); 
			return $value;
		}	
	public static function getActDetails(){
			$value=db::table('act')->select('actcode','actname')->get(); 
			return $value;
		}
	public static function getSectionDetails()
		{
			$value=db::table('actsection')->select('actsectioncode','actsectionname')->where('actcode',1)->orderBy('actsectioncode', 'asc')->get(); 
			return $value;
		}
	public static function getEstablishment()
	{
		$value=db::table('establishment')->select('establishcode','establishname')->orderBy('establishcode', 'asc')->get(); 
			return $value;
	}
	public static function getCaveatSerial(){
		
		$value = DB::table('caveat')->select('caveatsrno')->max('caveatsrno');
		$value++;
		//dd($value);
		
		return $value;
		
	}
	public static function getSerialNo(){
		$value = DB::table('caveat')->max('caveatsrno');
		//$value++;
		return $value;

	}
	public static function getApplicants($application_id){
		$value = DB::table('caveatapplicant')->select('*')->where('caveatid',$application_id)->orderBy('applicantsrno', 'asc')->get();
//		dd($value);
		return $value;
	}
	public static function getRespondants($application_id){
		$value = DB::table('caveatee')->select('*')->where('caveatid',$application_id)->orderBy('caveateesrno', 'asc')->get();
//		dd($value);
		return $value;
	}
	//editing section
	public function getCApplicationId($applicationid){
		$value = DB::table('caveat')->select('*')->where('caveatid',$applicationid)->get();
		//dd($value);
		return $value;
	}
	//editing---------------------------------------------------------------
	public static function getApplicationId($applicationid,$user)
		{
			if($applicationid!='')
			{
				
				$value = DB::select("select * from caveat where caveatid='".$applicationid."'");
				
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
		public static function getApplicantDetails($applicationid='',$user)
		{ 
			if($applicationid!='')
			{
				$value=db::table('caveatapplicant')->select('*')->where('caveatid', '=', $applicationid)->orderBy('applicantsrno', 'asc')->get();
				//dd($value);
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
				$value=db::table('caveatee')->select('*')->where('caveatid', '=', $applicationid)->orderBy('caveateesrno', 'asc')->get();
				return $value;
			}
			else
			{
				$value=db::table('respondant')->select('*')->leftjoin('advocate', 'advocate.advocateregno', '=', 'respondant.advocateregno')->where('applicationid', 'like', 'T%')->where('respondant.createdby', '=', $user)->orderby('respondsrno', 'asc')->get();
			return $value;
			}
			
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
		public function getApplicantSerialDetails($newSrno,$applicationid)
		{
				
				$value=DB::Table('caveatapplicant')->join('taluk','taluk.talukcode','=','caveatapplicant.talukcode')->join('department','department.departmentcode','=','caveatapplicant.departcode')->select('caveatapplicant.*','taluk.talukname','department.departmentname')->where('caveatid',$applicationid)->where('applicantsrno',$newSrno)->orderBy('applicantsrno','asc')->get();
				return $value;				
					
		}
		public static function getRespondantSerialNoDetails($newSrno,$applicationid)
		{
			$value=DB::Table('caveatee')->join('taluk','taluk.talukcode','=','caveatee.caveateetaluk')->join('department','department.departmentcode','=','caveatee.caveateedepartcode')->select('caveatee.*','taluk.talukname','department.departmentname')->where('caveateesrno',$newSrno)->where('caveatid',$applicationid)->orderBy('caveateesrno','asc')->get();
			return $value;
		}
		public static function getAdvDetails($advbarregNo)
		{
			$value = db::table('advocate')->select('advocatename','advocateaddress','advocate.distcode','advocate.talukcode','pincode','distname','talukname','nametitle')->leftjoin('taluk', 'taluk.talukcode', '=', 'advocate.talukcode')->leftjoin('district','district.distcode','=','advocate.distcode')->where('advocate.advocateregno',$advbarregNo)->get();
            return $value;
		}
		
}

