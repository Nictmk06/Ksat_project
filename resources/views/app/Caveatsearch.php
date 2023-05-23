<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class caveatsearch extends Model
{
    //
	protected $table = 'caveatsearchon';
	public $timestamps = false;
	protected $primaryKey = '';
	
	public function getSearchResults($select,$value,$flag_caveat,$searchin,$department,$estcode)
		{
			//DB::enableQueryLog();
			if($flag_caveat!='Y')
			{
				 // Enable query log

				$value = DB::Table('caveat')->leftjoin('caveatorview', 'caveatorview.caveatid', '=', 'caveat.caveatid')->leftjoin('caveateeview', 'caveateeview.caveatid', '=', 'caveat.caveatid')->
				select($select,'caveatfiledate','caveat.caveatid')->WHERE([
				[$searchin,'LIKE','%'.$department.'%'],
				[$select,'ilike','%'.$value.'%'],
				['establishcode','=',"{$estcode}"],])->distinct()->get();
				//dd(DB::getQueryLog()); 
					// Your Eloquent query executed by using get()

					// Show results of log
				return $value;
			}
			else{
				$date=Carbon::today()->subDays(90);
				$value = DB::Table('caveat')->leftjoin('caveatorview', 'caveatorview.caveatid', '=', 'caveat.caveatid')->leftjoin('caveateeview', 'caveateeview.caveatid', '=', 'caveat.caveatid')->select($select,'caveatfiledate','caveat.caveatid')->WHERE([
				[$searchin,'LIKE','%'.$department.'%'],
				[$select,'ilike','%'.$value.'%'],
				['establishcode','=',"{$estcode}"],
				['caveatfiledate','>',$date],])->distinct()->get();
				//dd(DB::getQueryLog()); 
				return $value;
			}
		}
	public function getSearchResultsNull($flag_caveat,$searchin,$department,$estcode)
		{
			if($flag_caveat!='Y')
			{
				$value = DB::Table('caveat')->leftjoin('caveatorview', 'caveatorview.caveatid', '=', 'caveat.caveatid')->leftjoin('caveateeview', 'caveateeview.caveatid', '=', 'caveat.caveatid')->select($searchin,'caveatfiledate','caveat.caveatid')->WHERE([
				[$searchin,'LIKE','%'.$department.'%'],
				['establishcode','=',"{$estcode}"],])->distinct()->get();
				return $value;
			}
			else{
				$date=Carbon::today()->subDays(90);
				$value = DB::Table('caveat')->leftjoin('caveatorview', 'caveatorview.caveatid', '=', 'caveat.caveatid')->leftjoin('caveateeview', 'caveateeview.caveatid', '=', 'caveat.caveatid')->select($searchin,'caveatfiledate','caveat.caveatid')->WHERE([
				[$searchin,'LIKE','%'.$department.'%'],
				['establishcode','=',"{$estcode}"],
				['caveatfiledate','>',$date],])->distinct()->get();
				//$value = "AJAX";
				return $value;
			}
		}
	public function getSearchOn()
		{
			$value = DB::Table('caveatsearchon')->select('*')->get();
			return $value;
		}
	public function getApplicationType()
	{
		$value = DB::table('applicationtype')->select('*')->orderBy('appltypecode', 'asc')->get();
		return $value;
	}
	public function getApplicationCategory()
	{
		$value = DB::table('applcategory')->select('*')->orderBy('applcatcode', 'asc')->get();
		return $value;
	}
	public function getSearchOnId($caveatid)
		{
			$value = DB::Table('caveatview')->select('*')->where('caveatid',$caveatid)->distinct()->get();
			return $value;
		}
	
	public function getDepartment()
	{
		$value = DB::Table('department')->select('*')->orderBy('departmentname', 'asc')->get(); 
			return $value;
	}

	public static function getCaveatOnId($caveatid)
		{ 
				$value=db::table('caveat')->select('*')->where('caveatid', '=', $caveatid)->get();
				return $value;
						
		}
	public static function getCaveatorDtlsOnId($caveatid)
		{ 
				$value=db::table('caveatapplicant')->select('*')->leftjoin('advocate', 'advocate.advocateregno', '=', 'caveatapplicant.advocateregnno')->leftjoin('department', 'department.departmentcode', '=', 'caveatapplicant.departcode')->where('caveatid', '=', $caveatid)->orderby('applicantsrno', 'asc')->get();
				return $value;
						
		}



public static function getCaveateeDtlsOnId($caveatid)
		{ 
				$value=db::table('caveatee')->select('*')->leftjoin('department', 'department.departmentcode', '=', 'caveatee.caveateedepartcode')->where('caveatid', '=', $caveatid)->orderby('caveateesrno', 'asc')->get();
				return $value;
			
			
		}
}

