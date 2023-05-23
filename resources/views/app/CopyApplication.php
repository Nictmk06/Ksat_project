<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CopyApplication extends Model
{
    //
	public function getApplicationType()
	{
		$value = DB::table('applicationtype')->select('*')->orderBy('appltypecode', 'asc')->get();
		return $value;
	}
	public function getSearchResults($applicationid)
		{
			//$value = DB::Table('applicationview')->select('*')->where('applicationid',$applicationid)->distinct()->get();
			$value = DB::Table('applicationsummary1')->select('*')->where('applicationid',$applicationid)->distinct()->get();
			return $value;
		}
	public function getJudgementResults($applicationid)
		{
			$value = DB::Table('judgement')->select('*')->where('applicationid',$applicationid)->get();
			return $value;
		}
	public function getDocumentType()
	{
		$value = DB::Table('ccadocument')->select('*')->orderBy('ccadocumentcode','asc')->get();
		return $value;
		
	}
	public function getApplicationCAStatus($applicationid)
	{
		$value = DB::Table('applicationdisposed')->select('*')->where('applicationid',$applicationid)->get();
		return $value;
	}
	public function getAdvocate()
		{
			$value = DB::table('advocate')->select('*')->get();
			return $value;
		}
	/*public function getReceiptCAStatus($search_recpno)
	{
		$value = DB::table('receipt')->select('*')->where('receiptno',$search_recpno)->distinct()->get();
		return $value;
	}*/
	public function getDistList()
	{
		$value = DB::Table('district')->select('*')->get();
		return $value;
	}
}
