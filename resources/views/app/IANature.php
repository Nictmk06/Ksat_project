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
	public function getListPurpose()
	{
		$value=DB::Table('listpurpose')->select('*')->orderby('listpurpose','asc')->get();
		return $value;
	}
	public function getNoticeType($ordertypecode)
	{
	  if($ordertypecode==''){
		$value=DB::Table('noticetype')->select('*')->orderby('noticetypecode','asc')->get();
		return $value;
	   }
	  else{
		$value=DB::Table('noticetype')->select('*')->where('ordertypecode', '=', $ordertypecode)->orderby('noticetypecode','asc')->get();
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

	public function getCourthalls()
	{
		$value=DB::Table('courthall')->select('*')->orderby('courthalldesc','asc')->get();
		return $value;
	}
	public function getbenchjudge()
	{
		$value=DB::Table('bench')->select('*')->get();
		return $value;
	}
	public function getlist()
	{
		$value=DB::Table('list')->select('*')->get();
		return $value;
	}
		
}
?>