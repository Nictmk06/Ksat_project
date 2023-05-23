<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class IAScrutiny extends Model
{
	public static function getIAScrutiny($applicationid,$iano)
		{
			if($applicationid!='' && $iano !='')
			{
				
				$value = DB::select("select * from iascrutiny
						where applicationid=:applicationid and iano=:iano",
						['applicationid' => $applicationid,'iano'=>$iano]);
				
				return $value;
			}
			
		}


		public static function getIAScrutinyDetails($applicationid,$iano)
		{
			if($applicationid!='' && $iano !='')
			{
				
				$value = DB::select("select iascrutinydetails.*,iascrutinychklist.chklistdesc from iascrutinydetails left join iascrutinychklist on iascrutinydetails.chklistsrno = iascrutinychklist.chklistsrno
						where  applicationid=:applicationid and iano=:iano 
						and observation = ''",['applicationid' => $applicationid,'iano'=>$iano]);
			    return $value;
			}
			
		}


		public static function getIAScrutinyDetailsObjection($applicationid,$iano)
		{
			if($applicationid!='' && $iano !='')
			{
				
				$value = DB::select("select iascrutinydetails.* from iascrutinydetails
						where  applicationid=:applicationid and iano=:iano 
						and observation !='' and observation is not null",['applicationid' => $applicationid,'iano'=>$iano]);
				
				return $value;
			}
			
		}
}