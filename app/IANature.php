<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class IANature extends Model
{

	public static function getIANature()
	{
		$value=DB::Table('ianature')->select('*')->get();
		return $value;
	}
	public static function getDocumentType()
	{
		$value=DB::Table('documenttype')->select('*')->orderby('documentname','asc')->get();
		return $value;
	}
	public function getBenches()
	{
		$value=DB::Table('benchtype')->select('*')->get();
		return $value;
	}
	public function getBenchcodes()
	{
		$value = DB::Table('bench')->select('benchcode','judgeshortname')->get();
		return $value;
	}
	public function getStatus()
	{
		$value=DB::Table('status')->select('*')->orderby('statusname','asc')->get();
		return $value;
	}
       	public function getsec($userid)
{
	return DB::select("select userseccode, usersecname from usersection where usersection.userid='$userid'");
}
public function getsection()
{
	return DB::select("select userseccode, usersecname from usersection");
}

public function getUserDetails()
{
	return DB::select("SELECT distinct userid,sectioncode from userdetails ");
}
public function getquerytypecode()
{
	return DB::select("select querytypecode,querytypedescription from querytype");
 }

	public function getListPurpose()
	{
		$value=DB::Table('listpurpose')->select('*')->orderby('listpurpose','asc')->get();
		return $value;
	}
	
       public function getNoticeType($ordertypecode,$appltypecode)
	{
	  if($ordertypecode==''){
		$value=DB::Table('noticetype')->select('*')->orderby('noticetypecode','asc')->get();
		return $value;
	   }
	  else{

                if($ordertypecode!='-1')
	  	  {
	  	  	$ordertypecode=1;
	  	  }
		$value=DB::Table('noticetype')->select('*')->where([
    ['ordertypecode', '=', $ordertypecode],
    ['appltypecode', '=', $appltypecode]
    ])->orderby('noticetypecode','asc')->get();
		return $value;
     }
	}

	public function getNoticeTypeByID($noticetypecode)
		{
		  if($noticetypecode==''){
			$value=DB::Table('noticetype')->select('*')->orderby('noticetypecode','asc')->get();
			return $value;
		   }
		  else{
			$value=DB::Table('noticetype')->select('*')->where('noticetypecode', '=', $noticetypecode)->get();
			return $value;
	     }
	}


	public function getOrderType()
	{
		$value=DB::Table('ordertype')->select('*')->orderby('ordertypedesc','asc')->get();
		return $value;
	}

	public function getCourthalls($establishcode)
	{
        if($establishcode == 1)
		{
			$value=DB::Table('courthall')->select('*')->orderby('courthalldesc','asc')->get();
			return $value;
		}else{
			$value=DB::Table('courthall')->select('*')->where('courthallno', '=', 1)->orderby('courthalldesc','asc')->get();
			return $value;
		}
	}
	public function getbenchjudge($establishcode)
	{
		if($establishcode==''){
		$value=DB::Table('bench')->select('*')->get();
		return $value;
		 }
		  else{
			$value=DB::Table('bench')->select('*')->where('establishcode', '=', $establishcode)->get();
			return $value;
	     }
	}
	public function getlist()
	{
		$value=DB::Table('list')->select('*')->get();
		return $value;
	}
		
}
?>
