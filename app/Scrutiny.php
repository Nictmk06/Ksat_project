<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Scrutiny extends Model
{
	public static function getScrutiny($applicationid)
		{
			if($applicationid!='')
			{

				$value = DB::select("select * from scrutiny
						where applicationid=:applicationid ",['applicationid' => $applicationid]);

				return $value;
			}

		}


		public static function getScrutinyDetails($applicationid)
		{
			if($applicationid!='')
			{

				//$value = DB::select("select scrutinydetails.*,scrutinychklist.chklistdesc from scrutinydetails left join scrutinychklist on scrutinydetails.chklistsrno = scrutinychklist.chklistsrno and scrutinydetails.appltypecode= scrutinychklist.appltypecode
					//	where applicationid=:applicationid and observation = '' and scrutinydetails.chklistsrno>=1 and scrutinydetails.chklistsrno<=12 order by scrutinydetails.chklistsrno",['applicationid' => $applicationid]);
				$value =	DB::SELECT("SELECT DISTINCT scrutinydetails.*,scrutinychklist.chklistdesc from scrutinydetails
					left join scrutinychklist on scrutinydetails.chklistsrno = scrutinychklist.chklistsrno
					left join  scrutinychklist as sc on scrutinydetails.appltypecode = sc.appltypecode
					where applicationid='$applicationid' and
						observation = '' and scrutinydetails.chklistsrno>=1 and
						scrutinydetails.chklistsrno<=12 order by scrutinydetails.chklistsrno");
				  return $value;
			}

		}


		public static function getScrutinyDetailsObjection($applicationid)
		{
			if($applicationid!='')
			{

				$value = DB::select("select scrutinydetails.* from scrutinydetails
						where applicationid=:applicationid  and observation !='' and observation is not null",['applicationid' => $applicationid]);

				return $value;
			}

		}
}
