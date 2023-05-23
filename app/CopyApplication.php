<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Session;
class CopyApplication extends Model
{
    protected $fillable = [];
    protected $table = 'copyapplication';
    public $timestamps = false;
	protected $primaryKey = 'ccaapplicationno';

	public static function addCCApplication($ccApplStore)
        {
                $value=DB::table('copyapplication')->insert($ccApplStore);
                return $value;
        }

    public static function updateCCApplication($ccApplStore,$ccaapplicationno)
       {
      try {
          $value= DB::table('copyapplication')->where('ccaapplicationno', $ccaapplicationno)->update($ccApplStore);
      return $value;
      }catch(\Exception $e){
      return false;
      }
    }

	public function getApplicationType()
	{
		$value = DB::table('applicationtype')->select('*')->orderBy('appltypecode', 'asc')->get();
		return $value;
	}

	 public static function getCCApplicationDetails($ccaapplicationno,$establishcode)
     {
        $value= DB::table('ccapplicationsummary')->where('ccaapplicationno', $ccaapplicationno)->where('establishcode', $establishcode)->get();
		  return $value;

         }

	 public static function getCCAApplicationsByApplId($applicationId,$ccastatuscode,$establishcode)
     {
      // dd($ccastatuscode);
      $value= DB::table('ccapplicationsummary')->where('applicationid', $applicationId)->where('ccastatuscode', $ccastatuscode)->where('establishcode', $establishcode)->get();
//        $value=DB::SELECT("SELECT ca.*,ccadocument.ccadocumentname from copyapplication ca  LEFT JOIN ccadocument ON ccadocument.ccadocumentcode = ca.documenttype
//        where applicationid='$applicationId' and ccastatuscode='$ccastatuscode' and establishcode='$establishcode'");	
  return $value;

         }


	public function getSearchResults($applicationid,$establishcode)
{       
        
      
          $var=explode('/',$applicationid);
        $appltypeshort=$var[0];
        $appltypecode=DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$appltypeshort'")[0]->appltypecode;
        $appnum1=$var[1];
        $applyear=$var[2];
        $mainapplId= DB::select("select applicationid from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."' and establishcode='$establishcode'");

      if(count($mainapplId)==0)
        {
          $applId = DB::select("select applicationid from groupno where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");
          if(count($applId) > 0)
          {
          $find_appnum = $applId[0]->applicationid;
          }
          else{
            $find_appnum ="";
            }
        }
        else{
          if($mainapplId[0]->applicationid != $applicationid)
        {
        $find_appnum="";
        }
       else
        {
        $find_appnum = $mainapplId[0]->applicationid;
        }
      }
     // dd($find_appnum);
      if($find_appnum=="")
      {
        $value = DB::Table('applicationdisposed')->select('*')->where('applicationid',$applicationid)->distinct()->get();
        return $value;
      }
      else
        {
        $value = DB::Table('applicationsummary1')->select('*')->where('applicationid',$applicationid)->distinct()->get();
        return $value;
        }
	}
	public function getJudgementResults($applicationid)
		{
			$value = DB::Table('judgement')->select('*')->where('applicationid',$applicationid)->get();
			return $value;
		}
	public function getDocumentType()
	{
		$value = DB::Table('ccadocument')->select('*')->orderBy('ccadocumentname','asc')->get();
		return $value;

	}

	public function getCAStatus()
	{
		$value=DB::Table('ccastatus')->select('*')->orderby('ccastatusdesc','asc')->get();
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
		$value = DB::Table('district')->select('*')->orderby('distname','asc')->get();
		return $value;
	}

	public function getEstablishDtls($establishcode)
	{
		$value = DB::table('establishment')->select('*')->where('establishcode',$establishcode)->get();
			return $value;
	}

	public function getCCADeliveryMode()
      {
        $value = DB::table('deliverymode')->select('*')->get();
        return $value;
      }

}
