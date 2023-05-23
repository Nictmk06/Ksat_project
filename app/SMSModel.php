<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
class SMSModel extends Model
{


           // sms sending module selection

      public static function insertSmsDetails($smscontent,$module,$mobile,$name,$appid,$flag)
		{
			$value= DB::select("insert into sms(smscontent, sentondate, modulecode, deliverystatus, smspreparedate, advocateflag, mobileno, applicationid, name) values('".$smscontent."',now()::date,'".$module."','N',now()::date,'".$flag."','".$mobile."','".$appid."','".$name."')");


			return $value;
		}
// little qurery change
public static function getsmsDetailsformodulescrutiny($module)
		{
		$value= DB::select("SELECT
		a.applicationid,TO_char(a.createdon, 'DD/MM/YYYY') createdon,TO_char(a.scrutinydate, 'DD/MM/YYYY') scrutinydate,a.acceptreject,ap.applicantname,ap.applicantmobileno,
		adv.advocatename,adv.advocatemobile,adv.advocateregno,CASE WHEN (a.acceptreject='N') THEN 'Rejected' ELSE 'Accepted' END as status from scrutiny a
		 inner join applicant ap on ap.applicationid = a.applicationid and ap.applicantsrno = 1
		 inner join advocate adv on ap.advocateregno = adv.advocateregno
		 where a.scrutinydate= now()::date
         and a.applicationid not in (select applicationid from sms where modulecode='".$module."'
         and  sentondate=now()::date  ) ");



			return $value;
		}


  //function getting application module data
		public static function getsmsDetailsformoduleApplication($module,$establishcode)
		{
		$value= DB::select("SELECT a.applicationid,TO_char(a.createdon, 'DD/MM/YYYY') createdon,To_char(a.applicationdate,'DD/MM/YYYY') applicationdate,a.registerdate,ap.applicantname,ap.applicantmobileno,
            adv.advocatename,adv.advocatemobile,adv.advocateregno from application a
			 inner join applicant ap on ap.applicationid = a.applicationid and ap.applicantsrno = 1
			 inner join advocate adv on ap.advocateregno = adv.advocateregno
			 where a.establishcode='$establishcode' and a.registerdate= now()::date
       and a.applicationid not in (select applicationid from sms where modulecode='".$module."'
         and  sentondate=now()::date) ");


			return $value;
		}

// function getting causelist module data
     public static function getsmsDetailsformoduleCauselist($module,$establishcode)
		{
		$value= DB::select("SELECT a.causelistcode,trim(a.applicationid) applicationid,c.finalizeflag,c.courthallno,c.listno
			 ,ap.applicantname,ap.applicantmobileno,adv.advocatename,adv.advocatemobile,To_char(c.causelistdate,'DD/MM/YYYY') causelistdate,
			 adv.advocateregno ,b.judgeshortname  from causelistappltemp  a
			 inner join causelisttemp c  on a.causelistcode=c.causelistcode
			 inner join applicant ap on ap.applicationid = a.applicationid and ap.applicantsrno = 1
			 inner join advocate adv on ap.advocateregno = adv.advocateregno
			 inner join bench b on c.benchcode=b.benchcode
			 where c.establishcode='$establishcode' and c.causelistdate = (select distinct min(causelistdate) from causelisttemp
			 where causelistdate>now()::date)
		   and a.applicationid not in (select applicationid from sms where modulecode='".$module."'
         and  sentondate=now()::date  ) ");


			return $value;
		}

	    public static function getSmsSend()
		{
			$value=db::table('module')->select('modulecode','modulename','sms')->where('sms','Y')->orderBy('modulecode', 'asc')->get();
			return $value;
		}


		 public static function getSmsData($module)
		{
			$value= DB::select("select * from sms where modulecode='".$module."' and deliverystatus='N' and  mobileno is not null and mobileno <>''");
      return $value;
    }

//function called in smslogcontroller  file and decalred in caseManagementModel file
 		public static function getSmsDetailsByModule($module){
				 // $value=db::table('sms')->select('sentondate')->where('modulecode',$module)->orderBy('sentondate', 'desc')->get();

				//$value= DB::select("select max(smspreparedate) smspreparedate from sms
				 // where modulecode='".$module."' order by smspreparedate desc");
                $value= DB::select("select max(sentondate) sentondate from sms
				  where modulecode='".$module."' order by sentondate desc");

			return $value;
		}

		//function called in smslogcontroller
	public static function getSmsDetailsByModulelog($module){
				 // $value=db::table('sms')->select('sentondate')->where('modulecode',$module)->orderBy('sentondate', 'desc')->get();

				//$value=db::table('smslog')->select('sentondate')->where('modulecode',$module)->orderBy('sentondate', 'desc')->get();
		          $value=db::table('smslog')->select('sentondate')->where('modulecode',$module)->orderBy('sentondate', 'desc')->get();
                //$value= DB::select("select max(sentondate) sentondate from sms
				//  where modulecode='".$module."' order by sentondate desc");


			return $value;


		}

		public static function insertSmsDetailsByModule($module){

			$value= DB::select("insert into sms(smscontent,sentondate,modulecode,deliverystatus)
select
concat('Dear applicant ',x.applicantname,' your ',ap.applicationid,' registered on ',ap.registerdate) messages,
now()::date date,'".$module."' module,'N' deliverystatus
from (
select a.applicantmobileno,a.applicantname,a.advocateregno,
ad.advocatemobile,ad.advocatename,a.applicationid,a.createdon from applicant a left join advocate ad
on a.advocateregno=ad.advocateregno)x left join application ap
on x.applicationid=ap.applicationid
where x.createdon::date =(select max(sentondate)from sms 	where modulecode='".$module."')
and x.applicantmobileno is not null");

			$value1=DB::select("insert into sms(smscontent,sentondate,modulecode,deliverystatus)
select
concat('Dear advocate ',x.advocatename,'your reg no -',x.advocateregno,'-', ap.applicationid,' registered on ',ap.registerdate) advmsg,
now()::date date,'".$module."'  module,'N' deliverystatus
from (
select a.applicantmobileno,a.applicantname,a.advocateregno,
ad.advocatemobile,ad.advocatename,a.applicationid,a.createdon from applicant a left join advocate ad
on a.advocateregno=ad.advocateregno)x left join application ap
on x.applicationid=ap.applicationid
where x.createdon::date =(select max(sentondate)from sms 	where modulecode='".$module."' )
and x.advocatemobile is not null and x.advocatemobile<>''");

			return $value;

		}


public static function insertSmsDetailsByModulelog($module){
$value =DB::select("insert into smslog(applicationid,applicantsrno,applicantname,applicantmobileno,advocateregno,advocatename,
advocatemobile,smsprepareddate,deliverystatus,smscontent,modulecode,modulename,appupdatedate,sentondate,advocateflag)

Select applicationid, applicantsrno,applicantname,applicantmobileno,advocateregno,advocatename,
advocatemobile,smsprepareddate ,deliverystatus,
smscontent , modulecode,modulename,createdon,sentondate,'Y' from

(Select applicationid, applicantsrno,applicantname,applicantmobileno,advocateregno,advocatename,
advocatemobile, now()::date smsprepareddate,'N' deliverystatus,
concat('Dear ',advocatename,' ','your registration no -',advocateregno, ' ','your application no - ',applicationid,'your application registered on - ',registerdate) smscontent,
".$module." modulecode,'Application' modulename,createdon,now()::date  sentondate
from
(Select
X.applicationid, X.applicantsrno, X.applicantname,X.applicantmobileno,
ad.advocateregno,ad.advocatemobile , X.createdon, X.registerdate , X.smsid,ad.advocatename,X.modulecode
from
(select app.applicationid, ap.applicantsrno,ap.applicantname,ap.applicantmobileno,ap.advocateregno,app.createdon,
 app.registerdate, sl.smsid,sl.modulecode
from application app
left join smslog sl
on sl.applicationid = app.applicationid
left join applicant ap
on app.applicationid=ap.applicationid
where ap.applicantsrno=1 ) X
left join  advocate ad
on X.advocateregno=ad.advocateregno
 where X.smsid is null or X.modulecode<>".$module.") T
where (applicantmobileno is not null
or advocatemobile is not null )
and createdon = now()::date ) K

union

Select applicationid, applicantsrno,applicantname,applicantmobileno,advocateregno,advocatename,
advocatemobile,smsprepareddate ,deliverystatus,
smscontent , modulecode,modulename,createdon,sentondate,'N' from
(Select applicationid, applicantsrno,applicantname,applicantmobileno,advocateregno,advocatename,
advocatemobile, now()::date smsprepareddate,'N' deliverystatus,
concat('Dear ',applicantname,' ','your application no -',applicationid, ' ','your application registered on - ','registerd on -',registerdate) smscontent,
".$module." modulecode,'Application' modulename,createdon,now()::date  sentondate
from
(Select
X.applicationid, X.applicantsrno, X.applicantname,X.applicantmobileno,
ad.advocateregno,ad.advocatemobile , X.createdon, X.registerdate , X.smsid,ad.advocatename,X.modulecode
from
(select app.applicationid, ap.applicantsrno,ap.applicantname,ap.applicantmobileno,ap.advocateregno,app.createdon,
 app.registerdate, sl.smsid,sl.modulecode
from application app
left join smslog sl
on sl.applicationid = app.applicationid
left join applicant ap
on app.applicationid=ap.applicationid
where ap.applicantsrno=1 ) X
left join  advocate ad
on X.advocateregno=ad.advocateregno
 where X.smsid is null or X.modulecode<>".$module.") T
where (applicantmobileno is not null
or advocatemobile is not null )
and createdon = now()::date ) K
");

return $value;
}


// sms log table content function call in smslogcontroller passing in API
 public static function getSmsDetailsToSend()
		{
			$value=DB::select("select mobileno,smsid,smscontent from sms where deliverystatus='N' and advocateflag='N' and mobileno is not null and mobileno<>''"  );
			return $value;
		}


 public static function SendingSmsfordeliveryNo()
		{
			$value=DB::select("select mobileno,smsid,smscontent from sms where deliverystatus='N' and mobileno is not null and mobileno<>''"  );
			return $value;
		}

 public static function getSmsDetailsToSendAdvocate()
		{
			$value=DB::select("select mobileno,smsid,smscontent from sms where deliverystatus='N' and advocateflag='Y' and mobileno is not null and mobileno<>'' ");
			return $value;
		}

/// sms log table module fetching in the drop down
 public static function getModuleName()
		{
			$value=db::table('module')->select('modulecode','modulename','sms')->where('sms','Y')->orderBy('modulecode', 'asc')->get();
			return $value;
		}


public static function updateSmsDetailsByModulelog($uid,$status,$date,$mobile_no,$serial_no_res)
		{
			$value=DB::select("update sms set responsid='".$uid."',deliverystatus='Y',sentondate='".$date."' where mobileno='".$mobile_no."' and smsid='".$serial_no_res."'"
			);
			return $value;
		}



public static function updateSms($uid,$status,$date,$mobile_no,$serial_no_res)
		{
			$value=DB::select("update sms set responseid='".$uid."',deliverystatus='Y',sentondate='".$date."' where mobileno='".$mobile_no."' and smsid='".$serial_no_res."'"
			);
			return $value;
		}


}
?>
