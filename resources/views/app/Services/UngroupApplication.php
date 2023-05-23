<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UngroupApplication 
{

	public function __construct()
	{
    
    }

	public static function addApplicationDetails($newapplicationid,$newapplicantcount,$newapplicationsrno,$newapplicationtosrno,$applicationyear,$applicationdate,$applicationid)
	{
		$value= DB::insert("insert into application SELECT '$applicationdate', '$newapplicationid', 
		        $applicationyear, appltypecode, $newapplicationsrno, $newapplicationtosrno, serviceaddress, servicepincode, servicetaluk, servicedistrict, advocateregnno, actcode, actsectioncode, totalamount, applcategory, subject, interimprayer, isinterimrelief, advocatesingle,
            $newapplicantcount, respondentcount, resadvsingle, rserviceaddress, rservicepincode,
			rservicetaluk, rservicedistrict, firstlistdate, nextlistdate, lastlistdate, decisiondate,
			registerdate, ianextdate, statusid, purposelast, purposenext, beforeme, objectionflag, 
			urgent, groupapplication, caveatmatchdate, connectedcase, remarks, againstorderno, 
			againstorderdate, againstorderissuedby, createdby, relationtitle, createdon, updatedby, updatedon, againstorders, benchcodelast, benchcodenext, scrutinyflag, establishcode
            FROM application where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}

	public static function updateApplicationDetails($oldapplicationtosrno,$oldapplicantcount,$applicationid)
	{
		$value=   DB::update("update application set applicationtosrno = $oldapplicationtosrno, 
            applicantcount = $oldapplicantcount  where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}

	public static function updategroupApplicationDetails($newapplicationid,$applicationid)
	{
		$value=   DB::update("update groupno set applicationid = '$newapplicationid'
            where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}
		
	public static function updateUngroupFlag($applicationid)
	{
		$value=   DB::update("update application set ungroupflag = 'Y'
            where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}
	
public static function addapplagainstDetails($newapplicationid,$applicationid)
	{
		$value=  DB::insert("insert into applagainst SELECT '$newapplicationid', referapplid, createdby, reviewgovtapplicant, suomotoapplication FROM applagainst where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}


	public static function addapplreliefDetails($newapplicationid,$applicationid)
	{
		$value=  DB::insert("insert into applrelief SELECT '$newapplicationid', relief, reliefsrno, createdby, createdon, updatedby, updatedon FROM applrelief  where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}

public static function addrespondantDetails($newapplicationid,$applicationid)
	{
		$value=  DB::insert("insert into respondant SELECT '$newapplicationid', respondsrno, respontdepttype, respontdeptcode, respontgovtdept, ismainrespond, respondtitle, respondname, respondaddress, respondpincode, respondtaluk, responddistrict, respondmobileno, respondemail, respondingperson, advocateregno, isgovtadvocate, respondstatus, createdon, createdby, gender, respondantage, relationname, relationtitle, issingleadvocate, relation, desigcode, updatedby, updatedon, partystatus, remarks, statuschangedate, advocatecode
       FROM respondant where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}

	public static function rearrangeApplicantSrNo($arrlen ,$newapplicationid)
	{
		$value=   DB::update("update applicant set applicantsrno=applicantsrno-$arrlen
							  where applicationid = :applicationid ",
							  ['applicationid'=>$newapplicationid]);
		return $value;
	}
	
	public static function rearrangeApplicantSrNoFromBetween($arrlen,$ungroupapplicantsrno,$applicationid)
	{
		$value=   DB::update("update applicant set applicantsrno=applicantsrno-$arrlen
							  where applicantsrno > $ungroupapplicantsrno and applicationid = :applicationid ",
							  ['applicationid'=>$applicationid]);
		return $value;
	}

	public static function updateapplicantDetails($newapplicationid,$newapplicantsrno,$applicationid,$applicantsrno)
	{
		$value=   DB::update("update applicant set applicationid ='$newapplicationid', applicantsrno=$newapplicantsrno
							  where applicationid = :applicationid and applicantsrno=:applicantsrno",
							  ['applicationid'=>$applicationid,'applicantsrno'=>$applicantsrno]);
		return $value;
	}
	
	public static function updateapplicantDetailsID($newapplicationid,$applicationid)
	{
		$value=   DB::update("update applicant set applicationid ='$newapplicationid'
		where applicationid = :applicationid" ,
							  ['applicationid'=>$applicationid]);
		return $value;
	}
	public static function addapplicantDetails($newapplicationid,$newapplicantcount,$applicationid)
	{
		$value=  DB::insert("insert into applicant SELECT '$newapplicationid', depttype, departcode, isgovtdept, applicantsrno, ismainparty, nametitle, applicantname, gender, relationname, applicantaddress, applicantpincode, talukcode, districtcode, applicantmobileno, applicantemail, partyinperson, advocateregno, applicantstatus, createdon, createdby, applicantage, relation, relationtitle, issingleadv, desigcode, updatedon, updatedby, partystatus, remarks, statuschangedate, advocatecode FROM applicant where applicationid = :applicationid and applicantsrno  not in ($applicantsrno)",['applicationid'=>$applicationid]);
		return $value;
	}

	public static function deleteapplicantDetails($newapplicationid,$newapplicantcount,$applicationid)
	{
		$value= DB::delete("delete from applicant where applicationid = :applicationid and applicantsrno  not in ($applicantsrno)",['applicationid'=>$applicationid]);

		return $value;
	}
 
	public static function addapplicationindexDetails($newapplicationid,$applicationid)
	{
		$value=  DB::insert("insert into applicationindex SELECT '$newapplicationid', documentname, documentno, startpage, endpage, documentdate, createdby, createdon, updatedby, updatedon
       FROM applicationindex where applicationid = :applicationid",['applicationid'=>$applicationid]);
		return $value;
	}

    public static function adddailyhearingDetails($newapplicationid,$applicationid)
	{
		$value=  DB::insert("insert into dailyhearing (applicationid, benchcode, courthallno, hearingdate, listno,
							purposecode, causelistsrno, business, starttime, endtime, courtdirection, caseremarks, orderyn, casestatus,
							postafter, postaftercategory, nextdate, nextbenchcode, nextcausetypecode, takenonboard, officenote, 
							nextpurposecode, orderdate, disposeddate, benchtypename, nextbenchtypename, ordertypecode, nextcauselistcode, 
							causelistcode,connectedcase, createdon, mainapplicationid, establishcode, enteredfrom
							) SELECT  '$newapplicationid', benchcode, courthallno, hearingdate, listno, purposecode, 
							causelistsrno, business, starttime, endtime, courtdirection, caseremarks, orderyn, 
							casestatus, postafter, postaftercategory, nextdate, nextbenchcode, nextcausetypecode,
							takenonboard, officenote, nextpurposecode, orderdate, disposeddate, benchtypename, 
							nextbenchtypename, ordertypecode, nextcauselistcode, causelistcode ,connectedcase,
							createdon, mainapplicationid, establishcode, enteredfrom FROM dailyhearing
							where applicationid = :applicationid ",['applicationid'=>$applicationid]);
		return $value;
	}
	

	 

}