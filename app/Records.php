<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Records extends Model
{
    protected $fillable = [];
    protected $table = 'recordapplication';
    public $timestamps = false;
	protected $primaryKey = 'id';	
	
	
	public static function getUsersDtlsBySection($userseccode,$establishcode)
		{
			$value=db::table('userdetails')->select('*')->Join('usersection', 'userdetails.sectioncode', '=', 'usersection.userseccode')
			->where('sectioncode', $userseccode)->where('establishcode', $establishcode)->get();
		  return $value;
		}
		
	
	
	public static function getRackNo()
		{
			$value=db::table('recordroomrackno')->orderBy('rackno', 'asc')->get(); 
			return $value;
		}
		
	public static function getRoomNo()
		{
			$value=db::table('recordroomno')->orderBy('roomno', 'asc')->get(); 
			return $value;
		}
		
	public static function addRecordApplication($recordApplStore)
        {
                $value=DB::table('recordapplication')->insert($recordApplStore); 
                return $value;           
        }

    public static function updateRecordApplication($recordApplStore,$id)
       {
      try {
          $value= DB::table('recordapplication')->where('id', $id)->update($recordApplStore);
      return $value;
      }catch(\Exception $e){
      return false;
      }      
    }
		
	
	public static function getApplicationSummaryDtls($applicationid,$establishcode)
     {              
		$value = DB::Table('applicationsummary1')->select('*')->where('applicationid',$applicationid)->where('establishcode',$establishcode)->distinct()->get();
		return $value;
	  }
	
	 public static function getRecordApplicationDetails($applicationId,$establishcode)
     {              
        $value= DB::table('recordapplication')->select('*','recordroomno.roomname','recordroomrackno.rackname')->Join('recordroomno', 'recordroomno.roomno', '=', 'recordapplication.roomno')->Join('recordroomrackno', 'recordroomrackno.rackno', '=', 'recordapplication.rackno')->where('applicationid', $applicationId)->where('establishcode', $establishcode)->get();
		  return $value;
       }
	   
    public static function getRecordDocumentDetails($id,$establishcode)
    {              
        $value= DB::table('recordapplication')->select('*','recordroomno.roomname','recordroomrackno.rackname')->Join('recordroomno', 'recordroomno.roomno', '=', 'recordapplication.roomno')->Join('recordroomrackno', 'recordroomrackno.rackno', '=', 'recordapplication.rackno')->where('id', $id)->where('establishcode', $establishcode)->get();
		  return $value;
       }
	   
	public function getRecordApplSamePageNoExist($applicationid,$startno,$endno,$part,$docid)
    {
     if($docid == '')
	 {
		 $value1 = DB::select("select * from recordapplication where ((startpage between '".$startno."' and '".$endno."') or  (endpage between '".$startno."' and '".$endno."')) and applicationid='".$applicationid."' and part='".$part."'");
        return $value1;
	 }else{
		  $value1 = DB::select("select * from recordapplication where ((startpage between '".$startno."' and '".$endno."') or  
							(endpage between '".$startno."' and '".$endno."')) and applicationid='".$applicationid."' and part='".$part."' and id != $docid");
        return $value1;
	 }
    }


}
