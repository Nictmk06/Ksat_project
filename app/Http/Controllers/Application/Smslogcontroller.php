<?php
namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\SMSModel;
use App\DisposedApplicationModel;
use App\JudgementModel;
use App\UserActivityModel;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Smslogcontroller extends Controller
{
    private $path;

    public function __construct()
    {

        //  $this->path ="C:/Judgements";
        $this->case = new SMSModel();

    }

    public function SmsSendlog(Request $request)
    {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');

        $scrutiny=DB::SELECT("SELECT a.applicationid,TO_char(a.createdon, 'DD/MM/YYYY') createdon,TO_char(a.scrutinydate, 'DD/MM/YYYY') scrutinydate,a.acceptreject,ap.applicantname,ap.applicantmobileno,
        adv.advocatename,adv.advocatemobile,adv.advocateregno,CASE WHEN (a.acceptreject='N') THEN 'Rejected' ELSE 'Accepted' END as status from scrutiny a
        inner join applicant ap on ap.applicationid = a.applicationid and ap.applicantsrno = 1
        inner join advocate adv on ap.advocateregno = adv.advocateregno
        where a.scrutinydate= now()::date  and a.applicationid
        not in (select applicationid from smslog where modulecode='8'
        and  sentondate=now()::date  )");

      foreach($scrutiny as $s)
      {

        $content_application = DB::SELECT("SELECT smsformat from smstemplate where templateid='1107161226721758806'");
        if ($s->acceptreject == 'Y')
        {
            if ($s->applicantmobileno != null and $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
            {
                $valuestobereplaced = array(
                    '{#var1}',
                    '{#var2}',
                    '{#var3}',
                    '{#var4}'
                );
                $valuestobeassigned = array(
                    $s->applicantname,
                    $s->applicationid,
                    $s->scrutinydate,
                    $s->status
                );
                $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                $mobileno = $s->applicantmobileno;
                $appplicationid = $s->applicationid;
                $name = $s->applicantname;
                date_default_timezone_set('Asia/Kolkata');
                $today = date("Y-m-d");


                    $insrt1 = DB::table('smslog')->insert(array(
                        'applicationid' => $appplicationid,
                        'mobileno' => $mobileno,
                        'smscontent' => $smscontent,
                        'sentondate' => $today,
                        'smspreparedate' => $today,
                        'modulecode' => 8,
                        'name' => $name,
                        'deliverystatus' => 'Y',
                        'advocateflag' => 'N',
                      'establishcode' =>$establishcode,
                    ));
       }

       if ($s->advocatemobile != null and $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
       {
           $valuestobereplaced = array(
               '{#var1}',
               '{#var2}',
               '{#var3}',
               '{#var4}'
           );
           $valuestobeassigned = array(
               $s->advocatename,
               $s->applicationid,
               $s->scrutinydate,
               $s->status
           );
           $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
           $advocatecontent = "Dear " . $s->advocatename . ", (" . $s->advocateregno . ") your application : " . $s->applicationid . " is scruitinized on " . $s->createdon . ".";
           $mobileno = $s->advocatemobile;
           $appplicationid = $s->applicationid;
           $name = $name = $s->advocatename;
           date_default_timezone_set('Asia/Kolkata');
           $today = date("Y-m-d");
           $insrt = DB::table('smslog')->insert(array(
                   'applicationid' => $appplicationid,
                   'mobileno' => $mobileno,
                   'smscontent' => $smscontent,
                   'sentondate' => $today,
                   'smspreparedate' => $today,
                   'modulecode' => 8,
                   'name' => $name,
                   'deliverystatus' => 'Y',
                   'advocateflag' => 'Y',
                   'establishcode' =>$establishcode,
               ));


       }
   }

     else
        {

            //dd($smscontent);
       if ($s->applicantmobileno != null and $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
            {
                $valuestobereplaced = array(
                    '{#var1}',
                    '{#var2}',
                    '{#var3}',
                    '{#var4}'
                );
                $valuestobeassigned = array(
                    $s->applicantname,
                    $s->applicationid,
                    $s->scrutinydate,
                    $s->status
                );
                $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);

                $mobileno = $s->applicantmobileno;
                $appplicationid = $s->applicationid;
                $name = $s->applicantname;
                $today = date("Y-m-d");
                date_default_timezone_set('Asia/Kolkata');
                $insrt = DB::table('smslog')->insert(array(
                        'applicationid' => $appplicationid,
                        'mobileno' => $mobileno,
                        'smscontent' => $smscontent,
                        'sentondate' => $today,
                        'smspreparedate' => $today,
                        'modulecode' => 8,
                        'name' => $name,
                        'deliverystatus' => 'Y',
                        'advocateflag' => 'N',
                        'establishcode' =>$establishcode,
                    ));

   if ($s->advocatemobile != null and $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
                {
                    $valuestobereplaced = array(
                        '{#var1}',
                        '{#var2}',
                        '{#var3}',
                        '{#var4}'
                    );
                    $valuestobeassigned = array(
                        $s->advocatename,
                        $s->applicationid,
                        $s->scrutinydate,
                        $s->status
                    );
                    $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                    $mobileno = $s->advocatemobile;
                    $appplicationid = $s->applicationid;
                    $name = $name = $s->advocatename;
                    date_default_timezone_set('Asia/Kolkata');
                    $today = date("Y-m-d");
                    $insrt = DB::table('smslog')->insert(array(
                            'applicationid' => $appplicationid,
                            'mobileno' => $mobileno,
                            'smscontent' => $smscontent,
                            'sentondate' => $today,
                            'smspreparedate' => $today,
                            'modulecode' => 8,
                            'name' => $name,
                            'deliverystatus' => 'Y',
                            'advocateflag' => 'Y',
                            'establishcode' =>$establishcode,
                        ));


                }
            }

        }
     }

       $application=DB::SELECT("SELECT a.applicationid,TO_char(a.createdon, 'DD/MM/YYYY') createdon,To_char(a.applicationdate,'DD/MM/YYYY') applicationdate,a.registerdate,ap.applicantname,ap.applicantmobileno,
                           adv.advocatename,adv.advocatemobile,adv.advocateregno from application a
          inner join applicant ap on ap.applicationid = a.applicationid and ap.applicantsrno = 1
          inner join advocate adv on ap.advocateregno = adv.advocateregno
          where a.registerdate= now()::date and
          a.establishcode='$establishcode' and a.applicationid not in (select applicationid from smslog where modulecode='2'
         and  sentondate=now()::date) ");

          foreach ($application as $s)
          {
            $content_application = DB::SELECT("SELECT smsformat from smstemplate where templateid='1107161226735636225'");

              if ($s->applicantmobileno != null or $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
              {
                  $valuestobereplaced = array(
                      '{#var1}',
                      '{#var2}',
                      '{#var3}',
                      '{#var4}'
                  );
                  $valuestobeassigned = array(
                      $s->applicantname,
                      $s->applicationid,
                      $s->applicationdate,
                      $s->createdon
                  );
                  $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                  $mobileno = $s->applicantmobileno;
                  $appplicationid = $s->applicationid;
                  $name = $s->applicantname;
                  date_default_timezone_set('Asia/Kolkata');
                  $today = date("Y-m-d");
                  $insrt = DB::table('smslog')->insert(array(
                          'applicationid' => $appplicationid,
                          'mobileno' => $mobileno,
                          'smscontent' => $smscontent,
                          'sentondate' => $today,
                          'smspreparedate' => $today,
                          'modulecode' => 2,
                          'name' => $name,
                          'deliverystatus' => 'Y',
                          'advocateflag' => 'N',
                          'establishcode' =>$establishcode,
                      ));

              }

              if ($s->advocatemobile != null or $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
                      {
                          $valuestobereplaced = array(
                              '{#var1}',
                              '{#var2}',
                              '{#var3}',
                              '{#var4}'
                          );
                          $valuestobeassigned = array(
                              $s->advocatename,
                              $s->applicationid,
                              $s->applicationdate,
                              $s->createdon
                          );
                          $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                          $mobileno = $s->advocatemobile;
                          $appplicationid = $s->applicationid;
                          $name = $name = $s->advocatename;
                          date_default_timezone_set('Asia/Kolkata');
                          $today = date("Y-m-d");
                          $insrt = DB::table('smslog')->insert(array(
                                  'applicationid' => $appplicationid,
                                  'mobileno' => $mobileno,
                                  'smscontent' => $smscontent,
                                  'sentondate' => $today,
                                  'smspreparedate' => $today,
                                  'modulecode' => 2,
                                  'name' => $name,
                                  'deliverystatus' => 'Y',
                                  'advocateflag' => 'Y',
                                  'establishcode' =>$establishcode,
                              ));
                      }
          }


        $causelist=DB::SELECT("SELECT a.causelistcode,trim(a.applicationid) applicationid,c.finalizeflag,c.courthallno,c.listno
          ,ap.applicantname,ap.applicantmobileno,adv.advocatename,adv.advocatemobile,To_char(c.causelistdate,'DD/MM/YYYY') causelistdate,
          adv.advocateregno ,b.judgeshortname  from causelistappltemp  a
          inner join causelisttemp c  on a.causelistcode=c.causelistcode
          inner join applicant ap on ap.applicationid = a.applicationid and ap.applicantsrno = 1
          inner join advocate adv on ap.advocateregno = adv.advocateregno
          inner join bench b on c.benchcode=b.benchcode
          where c.establishcode='$establishcode' and c.causelistdate = (select distinct min(causelistdate) from causelisttemp
          where causelistdate>now()::date) and a.applicationid not in (select applicationid from smslog where modulecode='3'
             and  sentondate=now()::date  )");

        foreach($causelist as $s)
        {
          $content_application = DB::SELECT("SELECT smsformat from smstemplate where templateid='1107161226739489906'");

          if ($s->finalizeflag == 'Y')
          {
              if ($s->applicantmobileno != null or $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
              {
                  $valuestobereplaced = array(
                      '{#var1}',
                      '{#var2}',
                      '{#var3}',
                      '{#var4}'
                  );
                  $valuestobeassigned = array(
                      $s->applicantname,
                      $s->applicationid,
                      $s->causelistdate,
                      $s->judgeshortname
                  );
                  $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                  $mobileno = $s->applicantmobileno;
                  $appplicationid = $s->applicationid;
                  $name = $s->applicantname;
                  date_default_timezone_set('Asia/Kolkata');
                  $today = date("Y-m-d");
                  $insrt = DB::table('smslog')->insert(array(
                          'applicationid' => $appplicationid,
                          'mobileno' => $mobileno,
                          'smscontent' => $smscontent,
                          'sentondate' => $today,
                          'smspreparedate' => $today,
                          'modulecode' => 3,
                          'name' => $name,
                          'deliverystatus' => 'Y',
                          'advocateflag' => 'N',
                          'establishcode' =>$establishcode,
                      ));

              }
              if ($s->advocatemobile != null or $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
              {
               $valuestobereplaced = array(
                      '{#var1}',
                      '{#var2}',
                      '{#var3}',
                      '{#var4}'
                  );
                  $valuestobeassigned = array(
                      $s->advocatename,
                      $s->applicationid,
                      $s->causelistdate,
                      $s->judgeshortname
                  );
                  $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                  $mobileno = $s->advocatemobile;
                  $appplicationid = $s->applicationid;
                  $name = $name = $s->advocatename;
                  date_default_timezone_set('Asia/Kolkata');
                  $today = date("Y-m-d");

                  $insrt = DB::table('smslog')->insert(array(
                          'applicationid' => $appplicationid,
                          'mobileno' => $mobileno,
                          'smscontent' => $smscontent,
                          'sentondate' => $today,
                          'smspreparedate' => $today,
                          'modulecode' => 3,
                          'name' => $name,
                          'deliverystatus' => 'Y',
                          'advocateflag' => 'Y',
                          'establishcode' =>$establishcode,
                      ));
              }

          }

       }
       $data1['smslog']=DB::SELECT("SELECT s.*,m.modulename from smslog  s inner join module as m
           on s.modulecode=m.modulecode where sentondate= now()::date and establishcode='$establishcode'");


        return view('Judgement.Smslog', $data1)->with('user', $request->session()->get('userName'));

    }

    //function calling for scuitny / applcation /causelist modules for sms sms insert/prepare sms
    public function smsInsert(Request $request)
    {


       $establishcode = $request->session()->get('EstablishCode');
        $json = array();
        $module = $request->get('module');

        $module = SMSModel::getSmsSend();

        foreach ($module as $i)
        {
            if ($i->modulecode == 8)
            {
                $content_application = DB::SELECT("SELECT smsformat from smstemplate where templateid='1107161226721758806'");

                // for scruitny module sms details preparation
                $datas = SMSModel::getsmsDetailsformodulescrutiny($i->modulecode);

                /*  if($datas==null)
                {
                echo '<script language="javascript">';
                echo 'alert("message already sent or there is no data today")';
                echo '</script>';

                } */
                //the application id is scruitinized sucessfully on created on date
                foreach ($datas as $s)
                {
                    if ($s->acceptreject == 'Y')
                    {
                        if ($s->applicantmobileno != null and $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
                        {
                            $valuestobereplaced = array(
                                '{#var1}',
                                '{#var2}',
                                '{#var3}',
                                '{#var4}'
                            );
                            $valuestobeassigned = array(
                                $s->applicantname,
                                $s->applicationid,
                                $s->scrutinydate,
                                $s->status
                            );
                            $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                            //dd($smscontent);
                            //$smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." is scruitinized  on ".$s->createdon.".";
                            $mobileno = $s->applicantmobileno;
                            $appplicationid = $s->applicationid;
                            $name = $s->applicantname;
                            //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                            $templateid = '1107161226721758806';
                            $username = "Mobile_1-KSATRG";
                            $password = "ksatrg@1234"; //password of the department
                            $senderid = "KSATRG"; //senderid of the deparment
                            $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date("Y-m-d");
                            $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                            $data = array(
                                "username" => trim($username) ,
                                "password" => trim($password) ,
                                //2/11/2021
                                //2/3
                                "senderid" => trim($senderid) ,
                                "content" => trim($smscontent) ,
                                "smsservicetype" => "singlemsg",
                                "mobileno" => trim($mobileno) ,
                                "key" => trim($key) ,
                                "templateid" => trim($templateid)

                            );
                            DB::beginTransaction();
                            try
                            {

                                $insrt = DB::table('sms')->insert(array(
                                    'applicationid' => $appplicationid,
                                    'mobileno' => $mobileno,
                                    'smscontent' => $smscontent,
                                    'sentondate' => $today,
                                    'smspreparedate' => $today,
                                    'modulecode' => 8,
                                    'name' => $name,
                                    'deliverystatus' => 'Y',
                                    'advocateflag' => 'N',
                                ));

                                $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url to send sms
                                //$insert= $this->case->insertSmsDetails($smscontent,$i->modulecode,$s->applicantmobileno,$s->applicantname,$s->applicationid,"N");
                                $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                                $status=strpos($i[0],'200'); 
                                 if ($status)
                                {
                                    DB::commit();
                                }
                                else
                                {
                                    DB::rollback();
                                }
                            }
                            catch(\Exception $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting went wrong, Message not sent !!');
                            }
                            catch(\Throwable $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting wrong, Message not sent !!');
                            }


                        }

                        if ($s->advocatemobile != null and $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
                        {
                            $valuestobereplaced = array(
                                '{#var1}',
                                '{#var2}',
                                '{#var3}',
                                '{#var4}'
                            );
                            $valuestobeassigned = array(
                                $s->advocatename,
                                $s->applicationid,
                                $s->scrutinydate,
                                $s->status
                            );
                            $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                            $advocatecontent = "Dear " . $s->advocatename . ", (" . $s->advocateregno . ") your application : " . $s->applicationid . " is scruitinized on " . $s->createdon . ".";
                            //$insert= $this->case->insertSmsDetails($advocatecontent,$i->modulecode,$s->advocatemobile,$s->advocatename,$s->applicationid,"Y");
                            $mobileno = $s->advocatemobile;
                            $appplicationid = $s->applicationid;
                            $name = $name = $s->advocatename;
                            //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                            $templateid = '1107161226721758806';
                            $username = "Mobile_1-KSATRG";
                            $password = "ksatrg@1234"; //password of the department
                            $senderid = "KSATRG"; //senderid of the deparment
                            $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date("Y-m-d");
                            $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                            $data = array(
                                "username" => trim($username) ,
                                "password" => trim($password) ,
                                //2/11/2021
                                //2/3
                                "senderid" => trim($senderid) ,
                                "content" => trim($smscontent) ,
                                "smsservicetype" => "singlemsg",
                                "mobileno" => trim($mobileno) ,
                                "key" => trim($key) ,
                                "templateid" => trim($templateid)

                            );
                            DB::beginTransaction();
                            try
                            {

                                $insrt = DB::table('sms')->insert(array(
                                    'applicationid' => $appplicationid,
                                    'mobileno' => $mobileno,
                                    'smscontent' => $smscontent,
                                    'sentondate' => $today,
                                    'smspreparedate' => $today,
                                    'modulecode' => 8,
                                    'name' => $name,
                                    'deliverystatus' => 'Y',
                                    'advocateflag' => 'Y',
                                ));
                                $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);

                                $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                                $status=strpos($i[0],'200'); 
                                if ($status)
                                {
                                    DB::commit();
                                }
                                else
                                {
                                    DB::rollback();
                                }
                            }
                            catch(\Exception $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting went wrong, Message not sent !!');
                            }
                            catch(\Throwable $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting wrong, Message not sent !!');
                            }

                        }

                    }

                    else
                    {

                        //dd($smscontent);
                        if ($s->applicantmobileno != null and $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
                        {
                            $valuestobereplaced = array(
                                '{#var1}',
                                '{#var2}',
                                '{#var3}',
                                '{#var4}'
                            );
                            $valuestobeassigned = array(
                                $s->applicantname,
                                $s->applicationid,
                                $s->scrutinydate,
                                $s->status
                            );
                            $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                            //dd($smscontent);
                            // $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." is scruitinized  on ".$s->createdon ." and objection is raised. Contact KSAT. ";
                            //  $insert=  $this->case->insertSmsDetails($smscontent,$i->modulecode,$s->applicantmobileno,$s->applicantname,$s->applicationid,"N");
                            //$data=$s->advocatemobile;
                            //  dd($data);
                            $mobileno = $s->applicantmobileno;
                            $appplicationid = $s->applicationid;
                            $name = $s->applicantname;
                            //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                            $templateid = '1107161226721758806';
                            $username = "Mobile_1-KSATRG";
                            $password = "ksatrg@1234"; //password of the department
                            $senderid = "KSATRG"; //senderid of the deparment
                            $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date("Y-m-d");
                            $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                            $data = array(
                                "username" => trim($username) ,
                                "password" => trim($password) ,
                                //2/11/2021
                                //2/3
                                "senderid" => trim($senderid) ,
                                "content" => trim($smscontent) ,
                                "smsservicetype" => "singlemsg",
                                "mobileno" => trim($mobileno) ,
                                "key" => trim($key) ,
                                "templateid" => trim($templateid)

                            );
                            DB::beginTransaction();
                            try
                            {

                                $insrt = DB::table('sms')->insert(array(
                                    'applicationid' => $appplicationid,
                                    'mobileno' => $mobileno,
                                    'smscontent' => $smscontent,
                                    'sentondate' => $today,
                                    'smspreparedate' => $today,
                                    'modulecode' => 8,
                                    'name' => $name,
                                    'deliverystatus' => 'Y',
                                    'advocateflag' => 'N',

                                ));
                                $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url to send sms
                                $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                                $status=strpos($i[0],'200'); 
                                if ($status){
                                    DB::commit();
                                }
                                else
                                {
                                    DB::rollback();
                                }

                            }
                            catch(\Exception $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting went wrong, Message not sent !!');
                            }
                            catch(\Throwable $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting wrong, Message not sent !!');
                            }


                        }

                        if ($s->advocatemobile != null and $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
                        {
                            $valuestobereplaced = array(
                                '{#var1}',
                                '{#var2}',
                                '{#var3}',
                                '{#var4}'
                            );
                            $valuestobeassigned = array(
                                $s->advocatename,
                                $s->applicationid,
                                $s->scrutinydate,
                                $s->status
                            );
                            $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                            //  dd($smscontent);
                            //           $advocatecontent="Dear ".$s->advocatename .", (".$s->advocateregno.") your application :".$s->applicationid." is scruitinized  on ".$s->createdon ." and objection is raised. Contact KSAT.";
                            //  $insert= $this->case->insertSmsDetails($advocatecontent,$i->modulecode,$s->advocatemobile,$s->advocatename,$s->applicationid,"Y");
                            $mobileno = $s->advocatemobile;
                            $appplicationid = $s->applicationid;
                            $name = $name = $s->advocatename;
                            //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                            $templateid = '1107161226721758806';
                            $username = "Mobile_1-KSATRG";
                            $password = "ksatrg@1234"; //password of the department
                            $senderid = "KSATRG"; //senderid of the deparment
                            $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date("Y-m-d");
                            $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                            $data = array(
                                "username" => trim($username) ,
                                "password" => trim($password) ,
                                //2/11/2021
                                //2/3
                                "senderid" => trim($senderid) ,
                                "content" => trim($smscontent) ,
                                "smsservicetype" => "singlemsg",
                                "mobileno" => trim($mobileno) ,
                                "key" => trim($key) ,
                                "templateid" => trim($templateid)

                            );
                            DB::beginTransaction();
                            try
                            {

                                $insrt = DB::table('sms')->insert(array(
                                    'applicationid' => $appplicationid,
                                    'mobileno' => $mobileno,
                                    'smscontent' => $smscontent,
                                    'sentondate' => $today,
                                    'smspreparedate' => $today,
                                    'modulecode' => 8,
                                    'name' => $name,
                                    'deliverystatus' => 'Y',
                                    'advocateflag' => 'Y',
                                ));
                                $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);

                                $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                                $status=strpos($i[0],'200'); 
                                 if ($status)
                                {
                                    DB::commit();
                                }
                                else
                                {
                                    DB::rollback();
                                }
                            }
                            catch(\Exception $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting went wrong, Message not sent !!');
                            }
                            catch(\Throwable $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting wrong, Message not sent !!');
                            }
                        }

                    }
                }
            }
            /// for applcation module
            else if ($i->modulecode == 2)
            {
                $datas = SMSModel::getsmsDetailsformoduleApplication($i->modulecode,$establishcode);
                // Applicated registered sucessfully on the createdon date
                //  dd($datas);
                /*  if($datas==null)
                {
                echo '<script language="javascript">';
                echo 'alert("message already sent or there is no data today")';
                echo '</script>';

                }*/
                $content_application = DB::SELECT("SELECT smsformat from smstemplate where templateid='1107161226735636225'");
                //  dd($content_application);
                foreach ($datas as $s)
                {
                    if ($s->applicantmobileno != null or $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
                    {
                        $valuestobereplaced = array(
                            '{#var1}',
                            '{#var2}',
                            '{#var3}',
                            '{#var4}'
                        );
                        $valuestobeassigned = array(
                            $s->applicantname,
                            $s->applicationid,
                            $s->applicationdate,
                            $s->createdon
                        );
                        //dd($valuestobeassigned);
                        $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                        $mobileno = $s->applicantmobileno;
                        $appplicationid = $s->applicationid;
                        $name = $s->applicantname;
                        //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                        $templateid = '1107161226735636225';
                        $username = "Mobile_1-KSATRG";
                        $password = "ksatrg@1234"; //password of the department
                        $senderid = "KSATRG"; //senderid of the deparment
                        $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                        date_default_timezone_set('Asia/Kolkata');
                        $today = date("Y-m-d");
                        $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                        $data = array(
                            "username" => trim($username) ,
                            "password" => trim($password) ,
                            //2/11/2021
                            //2/3
                            "senderid" => trim($senderid) ,
                            "content" => trim($smscontent) ,
                            "smsservicetype" => "singlemsg",
                            "mobileno" => trim($mobileno) ,
                            "key" => trim($key) ,
                            "templateid" => trim($templateid)

                        );
                        DB::beginTransaction();
                        try
                        {

                            $insrt = DB::table('sms')->insert(array(
                                'applicationid' => $appplicationid,
                                'mobileno' => $mobileno,
                                'smscontent' => $smscontent,
                                'sentondate' => $today,
                                'smspreparedate' => $today,
                                'modulecode' => 2,
                                'name' => $name,
                                'deliverystatus' => 'Y',
                                'advocateflag' => 'N',

                            ));
                            $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data); //calling post_to_url to send sms
                            $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                            $status=strpos($i[0],'200'); 
                            if ($status)
                            {
                                DB::commit();
                            }
                            else
                            {
                                DB::rollback();
                            }
                        }
                        catch(\Exception $e)
                        {
                            DB::rollback();
                            return redirect()->route('SmsSendlog')
                                ->with('error', 'Someting went wrong, Message not sent !!');
                        }
                        catch(\Throwable $e)
                        {
                            DB::rollback();
                            return redirect()->route('SmsSendlog')
                                ->with('error', 'Someting wrong, Message not sent !!');
                        }
                        //   $insert= SMSModel::insertSmsDetails($smscontent,$i->modulecode,$s->applicantmobileno,$s->applicantname,$s->applicationid,"N");

                    }
                    if ($s->advocatemobile != null or $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
                    {
                        $valuestobereplaced = array(
                            '{#var1}',
                            '{#var2}',
                            '{#var3}',
                            '{#var4}'
                        );
                        $valuestobeassigned = array(
                            $s->advocatename,
                            $s->applicationid,
                            $s->applicationdate,
                            $s->createdon
                        );
                        $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                        //   $advocatecontent="Dear ".$s->advocatename .", (".$s->advocateregno.") your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                        //$smscontent=str_replace($valuestobereplaced,$valuestobeassigned,$content_application[0]->smsformat);
                        $mobileno = $s->advocatemobile;
                        $appplicationid = $s->applicationid;
                        $name = $name = $s->advocatename;
                        //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                        $templateid = '1107161226735636225';
                        $username = "Mobile_1-KSATRG";
                        $password = "ksatrg@1234"; //password of the department
                        $senderid = "KSATRG"; //senderid of the deparment
                        $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                        date_default_timezone_set('Asia/Kolkata');
                        $today = date("Y-m-d");
                        $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                        $data = array(
                            "username" => trim($username) ,
                            "password" => trim($password) ,
                            //2/11/2021
                            //2/3
                            "senderid" => trim($senderid) ,
                            "content" => trim($smscontent) ,
                            "smsservicetype" => "singlemsg",
                            "mobileno" => trim($mobileno) ,
                            "key" => trim($key) ,
                            "templateid" => trim($templateid)

                        );
                        DB::beginTransaction();
                        try
                        {

                            $insrt = DB::table('sms')->insert(array(
                                'applicationid' => $appplicationid,
                                'mobileno' => $mobileno,
                                'smscontent' => $smscontent,
                                'sentondate' => $today,
                                'smspreparedate' => $today,
                                'modulecode' => 2,
                                'name' => $name,
                                'deliverystatus' => 'Y',
                                'advocateflag' => 'Y',
                            ));
                            $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);

                            $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                            $status=strpos($i[0],'200'); 
                            if ($status)
                            {
                                DB::commit();
                            }
                            else
                            {
                                DB::rollback();
                            }
                        }
                        catch(\Exception $e)
                        {
                            DB::rollback();
                            return redirect()->route('SmsSendlog')
                                ->with('error', 'Someting went wrong, Message not sent !!');
                        }
                        catch(\Throwable $e)
                        {
                            DB::rollback();
                            return redirect()->route('SmsSendlog')
                                ->with('error', 'Someting wrong, Message not sent !!');
                        }

                    }
                }

            }

            else if ($i->modulecode == 3)
            {

                $datas = SMSModel::getsmsDetailsformoduleCauselist($i->modulecode,$establishcode);
                // Applicated registered sucessfully on the createdon date
                /*  if($datas==null)
                {
                echo '<script language="javascript">';
                echo 'alert("message already sent or there is no data today")';
                echo '</script>';

                }*/
                $content_application = DB::SELECT("SELECT smsformat from smstemplate where templateid='1107161226739489906'");

                foreach ($datas as $s)
                {
                    if ($s->finalizeflag == 'Y')
                    {
                        if ($s->applicantmobileno != null or $s->applicantmobileno != '' and strlen($s->applicantmobileno) == 10)
                        {
                            //  $smscontent="Dear ".$s->applicantname .", your application: ".$s->applicationid." is posted for hearing on ".$s->causelistdate ." ( Court Hall No.".$s->courthallno.", List No.".$s->listno.", Bench ".$s->judgeshortname." )" ;
                            $valuestobereplaced = array(
                                '{#var1}',
                                '{#var2}',
                                '{#var3}',
                                '{#var4}'
                            );
                            $valuestobeassigned = array(
                                $s->applicantname,
                                $s->applicationid,
                                $s->causelistdate,
                                $s->judgeshortname
                            );
                            $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);
                            // $insert= $this->case->insertSmsDetails($smscontent,$i->modulecode,$s->applicantmobileno,$s->applicantname,$s->applicationid,"N");
                            $mobileno = $s->applicantmobileno;
                            $appplicationid = $s->applicationid;
                            $name = $s->applicantname;
                            //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                            $templateid = '1107161226739489906';
                            $username = "Mobile_1-KSATRG";
                            $password = "ksatrg@1234"; //password of the department
                            $senderid = "KSATRG"; //senderid of the deparment
                            $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date("Y-m-d");
                            $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                            $data = array(
                                "username" => trim($username) ,
                                "password" => trim($password) ,
                                //2/11/2021
                                //2/3
                                "senderid" => trim($senderid) ,
                                "content" => trim($smscontent) ,
                                "smsservicetype" => "singlemsg",
                                "mobileno" => trim($mobileno) ,
                                "key" => trim($key) ,
                                "templateid" => trim($templateid)

                            );
                           // DB::beginTransaction();
                            try
                            {

                                $insrt = DB::table('sms')->insert(array(
                                    'applicationid' => $appplicationid,
                                    'mobileno' => $mobileno,
                                    'smscontent' => $smscontent,
                                    'sentondate' => $today,
                                    'smspreparedate' => $today,
                                    'modulecode' => 3,
                                    'name' => $name,
                                    'deliverystatus' => 'Y',
                                    'advocateflag' => 'N',

                                ));
                                $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);

                                $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                                $status=strpos($i[0],'200'); 
                                if ($status)
                                {
                                   DB::commit();
                                }
                                else
                                {
                                    DB::rollback();
                                }
                            }
                            catch(\Exception $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting went wrong, Message not sent !!');
                            }
                            catch(\Throwable $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting wrong, Message not sent !!');
                            }


                        }

                        if ($s->advocatemobile != null or $s->advocatemobile != '' and strlen($s->advocatemobile) == 10)
                        {
                            //$advocatecontent="Dear ".$s->advocatename .", your application: ".$s->applicationid." is posted for hearing on ".$s->causelistdate ." ( Court Hall No.".$s->courthallno.", List No.".$s->listno.", Bench ".$s->judgeshortname." )" ;
                            //$insert= $this->case->insertSmsDetails($advocatecontent,$i->modulecode,$s->advocatemobile,$s->advocatename,$s->applicationid,"Y");
                            $valuestobereplaced = array(
                                '{#var1}',
                                '{#var2}',
                                '{#var3}',
                                '{#var4}'
                            );
                            $valuestobeassigned = array(
                                $s->advocatename,
                                $s->applicationid,
                                $s->causelistdate,
                                $s->judgeshortname
                            );
                            $smscontent = str_replace($valuestobereplaced, $valuestobeassigned, $content_application[0]->smsformat);

                            $mobileno = $s->advocatemobile;
                            $appplicationid = $s->applicationid;
                            $name = $name = $s->advocatename;
                            //  $smscontent="Dear ".$s->applicantname .", your application : ".$s->applicationid." dated ".$s->applicationdate." is registered on ".$s->createdon.".";
                            $templateid = '1107161226739489906';
                            $username = "Mobile_1-KSATRG";
                            $password = "ksatrg@1234"; //password of the department
                            $senderid = "KSATRG"; //senderid of the deparment
                            $deptSecureKey = "ad7e3442-9aca-410a-a4ac-124e48cada9b";
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date("Y-m-d");
                            $key = hash('sha512', trim($username) . trim($senderid) . trim($smscontent) . trim($deptSecureKey));
                            $data = array(
                                "username" => trim($username) ,
                                "password" => trim($password) ,
                                //2/11/2021
                                //2/3
                                "senderid" => trim($senderid) ,
                                "content" => trim($smscontent) ,
                                "smsservicetype" => "singlemsg",
                                "mobileno" => trim($mobileno) ,
                                "key" => trim($key) ,
                                "templateid" => trim($templateid)

                            );
                            DB::beginTransaction();
                            try
                            {

                                $insrt = DB::table('sms')->insert(array(
                                    'applicationid' => $appplicationid,
                                    'mobileno' => $mobileno,
                                    'smscontent' => $smscontent,
                                    'sentondate' => $today,
                                    'smspreparedate' => $today,
                                    'modulecode' => 3,
                                    'name' => $name,
                                    'deliverystatus' => 'Y',
                                    'advocateflag' => 'Y',
                                ));

                                $this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", $data);

                                $i = get_headers("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT", 1);
                                
                                $status=strpos($i[0],'200');
                               
                                if ($status)
                                {
                                   DB::commit();
                                }
                                else
                                {
                                    DB::rollback();
                                }
                                DB::commit();

                            }
                            catch(\Exception $e)
                            {
                                DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting went wrong, Message not sent !!');
                            }
                            catch(\Throwable $e)
                            {
                               DB::rollback();
                                return redirect()->route('SmsSendlog')
                                    ->with('error', 'Someting wrong, Message not sent !!');
                            }
                        }

                    }
                }
            }

            // return response()->json_encode($data['smsdata']);
            //   echo json_encode($json);
            //}

        }
        /*  echo '<script language="javascript">';
        echo 'alert("message successfully sent")';
        echo '</script>'; */
        return redirect()
            ->route('SmsSendlog')
            ->with('success', 'Message successfully Sent for today !!');

    }

    //sendign sms through API for scruitny /Application/causelist
    public function post_to_url($url, $data)
    {
        $fields = '';
        foreach ($data as $key => $value)
        {
            $fields .= $key . '=' . urlencode($value) . '&';
        }
        rtrim($fields, '&');
        $post = curl_init();
        //  dd($post);
        //curl_setopt($post, CURLOPT_SSLVERSION, 5); // uncomment for systems supporting TLSv1.1 only
        curl_setopt($post, CURLOPT_SSLVERSION, 6); // use for systems supporting TLSv1.2 or comment the line
        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post); //result from mobile seva server
        //dd($result);
        //echo $result; //output from server displayed
        curl_close($post);

    }

}
