<?php

namespace App\Http\Controllers;
use App\UserActivityModel;
use App\IANature;
use App\CaseManagementModel;
use App\IADocument as IADocumentModel;
use App\Dailyhearing;
use App\Chp;
use App\DisposedApplicationModel;
use App\ModuleAndOptions;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use session;
use DateTime;
use Carbon\Carbon;





class ChpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function __construct()
    {
        $this->IANature = new IANature();
        $this->case = new CaseManagementModel();
        $this->module= new ModuleAndOptions();
        $this->disposedApplication =  new DisposedApplicationModel();
		$this->UserActivityModel = new UserActivityModel();
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * Regular Court hall proceedings form show
     */
    public function ChpShow(Request $request, Chp $chp)
    {
        $user     = $request->session()->get('username');
        $estcode  = $request->session()->get('EstablishCode');

        $chnum   = $chp->getUserChno($user,$estcode);
        if (count($chnum) > 0 )
        {
            $courthallno = $chnum[0]->courthallno;
            if ( $courthallno == null )
              $courthallno = 0;
        }
        else
        {
            $courthallno = 0;
        }
        $establishcode = Session()->get('EstablishCode');
        //$today_dt = date('Y-m-d', strtotime('2018-07-23'));
        $today_dt = date('Y-m-d');
        $listno = DB::select("SELECT distinct listno, b.judgeshortname,d.benchcode from dailyhearing d, bench b  where d.benchcode = b.benchcode and d.establishcode = '$establishcode' and d.courthallno='$courthallno' and d.hearingdate='$today_dt'  order by d.listno");

        $dailyhearing = DB::select("select applicationid, benchcode, courthallno, hearingdate, listno,causelistsrno from dailyhearing where establishcode = $establishcode and (courthallno='$courthallno' and hearingdate='$today_dt' and business='N' and (mainapplicationid is null or trim(mainapplicationid)='')) order by listno, causelistsrno, applicationid");

        $benchdetail  = DB::select("select distinct h.hearingdate, h.courthallno, h.listno, h.benchcode,b.benchtypename, b.judgeshortname  from dailyhearing as h, bench as b where (h.establishcode = $establishcode and h.courthallno='$courthallno' and h.hearingdate='$today_dt' and h.benchcode=b.benchcode) order by h.listno ");

            $data = array();
            $data['listno'] = $listno;
            $data['dailyhearing'] = $dailyhearing;
            $data['benchdetail']  = $benchdetail;
        //    print_r($data);
         
            return view('Proceedings.proceeding', $data, ['courthall' => $courthallno]);
       
        
    }

public function getCHPApplication(Request $request,  Chp $chp){
   
    $user     = $request->session()->get('username');
    $estcode  = $request->session()->get('EstablishCode');
    $establishcode = Session()->get('EstablishCode');
    $bulkproceedings = $_POST['bulkproceedings'];
    $chnum   = $chp->getUserChno($user,$estcode);
        if (count($chnum) > 0 )
        {
            $courthallno = $chnum[0]->courthallno;
            if ( $courthallno == null )
              $courthallno = 0;
        }
        else
        {
            $courthallno = 0;
        }
       // $listno = $_POST['listno'];
        $listno1=$request->get('listno');
        $arr=explode('-',$listno1);
        $listno=$arr[0];
        $benchcode=$arr[1];
       // $benchcode=$_POST['benchcode'];
        //dd($benchcode);

        $today_dt = date('Y-m-d');

        if($bulkproceedings == 'N'){
        $dailyhearing = DB::select("select applicationid,causelistsrno, benchcode, courthallno, hearingdate, listno,benchcode from dailyhearing where (establishcode = $establishcode and benchcode='$benchcode' and  courthallno='$courthallno' and hearingdate='$today_dt' and business='N' and listno = $listno and (mainapplicationid is null or trim(mainapplicationid)='')) order by causelistsrno, applicationid");
        }elseif($bulkproceedings == 'Y'){
         $dailyhearing    = DB::select("select hearingcode, causelistsrno, applicationid, benchcode, courthallno, hearingdate, listno, casestatus, purposecode ,statusname from dailyhearing left join status on dailyhearing.casestatus =status.statuscode where (establishcode = $establishcode and benchcode='$benchcode' and courthallno='$courthallno' and hearingdate='$today_dt' and business='N' and listno = $listno) order by listno, causelistsrno, applicationid");
        }
        $data['dailyhearing'] = $dailyhearing;
        echo json_encode($data['dailyhearing']);

    }


    /**
     * Display the specified resource.
     *
     * Bulk Court hall proceedings form show
     */

    public function ChpBulkShow(Request $request, Chp $chp)
    {
        $user     = $request->session()->get('username');
        $estcode  = $request->session()->get('EstablishCode');
        $establishcode = Session()->get('EstablishCode');
        $chnum   = $chp->getUserChno($user,$estcode);
        if (count($chnum) > 0 )
        {
            $courthallno = $chnum[0]->courthallno;
            if ( $courthallno == null )
              $courthallno = 0;
        }
        else
        {
            $courthallno = 0;
        }
        //$today_dt = date('Y-m-d', strtotime('2018-07-23'));
        $today_dt = date('Y-m-d');
        $listno = DB::select("select distinct listno from dailyhearing where courthallno='$courthallno' and hearingdate='$today_dt'  order by listno");
        $dailyhearing    = DB::select("select hearingcode, applicationid, benchcode, courthallno, hearingdate, listno, casestatus, purposecode from dailyhearing where (establishcode = $establishcode and courthallno='$courthallno' and hearingdate='$today_dt' and business='N') order by listno, causelistsrno, applicationid");
        $benchdetail     = DB::select("select h.hearingdate, h.courthallno, h.listno, b.benchtypename, b.judgeshortname from dailyhearing as h, bench as b where (h.establishcode = $establishcode and h.courthallno='$courthallno' and h.hearingdate='$today_dt' and h.benchcode=b.benchcode) order by h.causelistsrno limit 1");
        $m_status        = DB::select("select * from status");
        $m_purpose       = DB::select("select * from listpurpose");
        $m_bench         = DB::select("select * from bench where display='Y'");
        $m_causelisttype = DB::select("select * from causelisttype");
         
        $data = array();
           $data['listno'] = $listno;
            $data['dailyhearing']     = $dailyhearing;
            $data['benchdetail']      = $benchdetail;
            $data['m_status']         = $m_status;
            $data['m_purpose']        = $m_purpose;
            $data['m_bench']          = $m_bench;
            $data['m_causelisttype']  = $m_causelisttype;
       
            return view('Proceedings.BulkProceeding', $data, ['courthall' => $courthallno]);

    }

    /**
     * Update the specified resource in storage.
     *
     * Regular Court hall proceedings update
     */
    public function ChpUpdate(Request $request, Chp $chp)
    {
        
        // Construct input data array
        $hearingcode                      = $request->input('hearingCode');
        list($applicationid, $hrdate)     = explode(':', $request->input('hiddenApplicationId'));
        $hearingdate                      = date('Y-m-d',strtotime($hrdate));
        // Daily hearing update array
        $chpup['business']                = $request->input('business');
        $chpup['courtdirection']          = $request->input('courtDirection');
        $chpup['caseremarks']             = $request->input('remarksIfAny');
        $chpup['ordertypecode']           = $request->input('orderPassed');
        if ($request->input('orderDate') != '')
        {
        $chpup['orderdate']               = date('Y-m-d', strtotime($request->input('orderDate')));
        $chpup['orderyn']                 = 'Y';
        }
        $chpup['casestatus']              = $request->input('applicationFinalStatus');
        if ($request->input('disposedDate') != '')
        {
        $chpup['disposeddate']            = date('Y-m-d', strtotime($request->input('disposedDate')));
        }
        $chpup['postafter']               = $request->input('postAfterPeriod');
        $chpup['postaftercategory']       = $request->input('dwm');
        if ($request->input('nextHearingDate') != '')
        {
        $chpup['nextdate']                = date('Y-m-d', strtotime($request->input('nextHearingDate')));
        }
        else
        {
        $chpup['nextdate']                = null;  
        }
        $chpup['nextbenchcode']           = intval($request->input('bench'));
        $chpup['nextbenchtypename']       = $request->input('benchType');
        $chpup['nextpurposecode']         = intval($request->input('nextListingPurpose'));

        try{
        DB::beginTransaction();
        //IA update array (If any IA attached to application)
        $iaselected = $request->input('pendingIa');
        if ( $iaselected != "0:0")
        {
           list($applicationid, $iano)   = explode(':', $iaselected);
            $chpupia['benchcode']         = $request->input('benchcode');
            $chpupia['courthallno']       = $request->input('courthallno');
            $chpupia['iaprayer']          = $request->input('iaPrayer');
            $chpupia['remark']            = $request->input('iaRemarks');
            $chpupia['ordertypecode']     = $request->input('iaOrderPassed');
            $chpupia['hearingdate']     =  date('Y-m-d',strtotime($hrdate));
            if($request->input('iaStatus')== '2' ){
            $chpupia['disposeddate']     =  date('Y-m-d',strtotime($hrdate));
            }
            $chpupia['iastatus']          = $request->input('iaStatus');
            DB::table('iadocument')->where('applicationid', $applicationid)->where('iano', $iano)->update($chpupia);
        }
        
        if(DB::table('dailyhearing')->where('applicationid', $applicationid)->where('hearingdate', $hearingdate)->update($chpup))
        {
			 $value = true;
			//update Applicationdisposed table if status ='Disposed'
			if($request->input('applicationFinalStatus')==2)
			{ 		
			 $appldetail   = DB::select("select d.*, p.listpurpose as postedfor from dailyhearing as d left join 
				  listpurpose as p on (d.purposecode=p.purposecode) where (applicationid='$applicationid' and hearingdate='$hearingdate') limit 1");
			$applTypeRefer = $this->case->getApplTypeRefer($applicationid,'');
			if( count($applTypeRefer)>0)
             {
                        $applnStore['referapplid'] = $applTypeRefer[0]->referapplid;
                         
               }
			$establishcode = $request->session()->get('EstablishCode');

			//get application dtls from application table
			$ApplicationDet = $this->case->getAllApplicationDetails($applicationid,'','application',$establishcode);
									
				$applnStore['applicationdate']=$ApplicationDet[0]->applicationdate;
				$applnStore['actsectioncode']=$ApplicationDet[0]->actsectioncode;
				$applnStore['applicationyear']=$ApplicationDet[0]->applicationyear;
				$applnStore['appltypecode']=$ApplicationDet[0]->appltypecode;
				$applnStore['applicationsrno']=$ApplicationDet[0]->applicationsrno;
				$applnStore['applicationtosrno']=$ApplicationDet[0]->applicationtosrno;
				$applnStore['registerdate']=$ApplicationDet[0]->registerdate;
				$applnStore['applcategory']=$ApplicationDet[0]->applcategory;
				$applnStore['subject']=$ApplicationDet[0]->subject;
	    		$applnStore['applicantcount']=$ApplicationDet[0]->applicantcount;
				$applnStore['respondentcount']=$ApplicationDet[0]->respondentcount;		   
				$applnStore['disposeddate'] = date('Y-m-d', strtotime($request->input('disposedDate')));
				$applnStore['applicationid']= $applicationid;
				$applnStore['statusid']=$request->input('applicationFinalStatus');
				$applnStore['benchcode']=$request->input('benchcode');
				$applnStore['purposecode']=$appldetail[0]->purposecode;
				$applnStore['benchtypename']=$appldetail[0]->benchtypename;
				$applnStore['ordertypecode']=$request->input('orderPassed');
			
				$applnStore['applcategory'] = $request->input('applCatName');
				$applnStore['subject'] = $request->input('applnSubject');
				$applnStore['createdby']= $request->session()->get('userName');
				$applnStore['createdon']= date('Y-m-d H:i:s') ;
				$applnStore['establishcode']= $establishcode ;
				$value = $this->disposedApplication->addDisposedApplDetails($applnStore);
		 }
		 
		     $useractivitydtls['applicationid_receiptno'] =$applicationid;
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Save Court Hall proceedings.' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
		}
		
		 DB::commit();
         if( $value == true)
           {
           return redirect()->route('ChProceedingShow')->with('success', 'Application Updated successfully : '.$applicationid);
          }
        else
        {
		   DB::rollback();
           return redirect()->route('ChProceedingShow')->with('error', 'Some thing went wrong, Case not updated !!');
        }
         }catch (\Exception $e) {
			      DB::rollback();
               return redirect()->route('ChProceedingShow')->with('error', 'Someting went wrong, Case not updated !!');
            } catch (\Throwable $e) {
                DB::rollback();
                return redirect()->route('ChProceedingShow')->with('error', 'Someting wrong, Case not updated !!');
            }   

    }


    /**
     * Bulk Court hall proceedings update
     */
    public function ChpBulkUpdate(Request $request, Chp $chp)
    {

        function validateDate($date, $format = 'd-m-Y')
       {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
       }

        if (isset($_POST['caseSelect']))
        {
            $arrlen             = count($request->input('caseSelect'));
            $caseArr            = $request->input('caseSelect');

            $business           = $request->input('businessText');
            $casestatus         = $request->input('caseStatus');
            $natureofdisposal   = $request->input('natureOfDisposal');
            $postafter          = $request->input('postAfterPeriod');
            $postaftercategory  = $request->input('dwm');
            if ( validateDate($request->input('nextHearingDate'), 'd-m-Y' ))
            {
            $nexthearingdate    = date('Y-m-d', strtotime($request->input('nextHearingDate')));
            }
            else
            {
            $nexthearingdate    = null;
            }
            $nextbenchcode      = $request->input('nextBenchCode');
            $nextcausetypecode  = $request->input('nextCauseListType');
            $postedfor          = $request->input('postedFor');
            
            $k = 0;
            for($i = 0; $i < $arrlen; $i++)
            {
                list($hearingcode, $arrIndex) = explode('::', $caseArr[$i]);
                
                
                $rec_arr = array();

                $rec_arr['courtdirection']    = $business;
                // $rec_arr['casestatus']     = $casestatus[$arrIndex];
                $rec_arr['casestatus']        = $natureofdisposal;
                $rec_arr['postafter']         = $postafter;
                $rec_arr['postaftercategory'] = $postaftercategory;
                $rec_arr['nextdate']          = $nexthearingdate;
                $rec_arr['nextbenchcode']     = $nextbenchcode;
                $rec_arr['nextcausetypecode'] = $nextcausetypecode;
                $rec_arr['nextpurposecode']   = $postedfor;
                $rec_arr['business']          = 'Y';

                if ( DB::table('dailyhearing')->where('hearingcode', $hearingcode)->update($rec_arr) );
                {
                    $k++;
                }
		     $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Save Bulk Court Hall proceedings.' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	

            }
             return redirect()->route('ChBulkProceedingShow')->with('success', $k . ' Case(s) updated.');
        }
        else
        {
            return redirect()->route('ChBulkProceedingShow')->with('success', ' No Case(s) selected.');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chp  $chp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chp $chp)
    {
        //
    }
    
    // AJAX data access in JSON format get data to refresh form for changing events
    public function ChpAjax(Request $request)
    {
	  $request->validate([
		'optag' => 'numeric',
		'applicationid' => array(
						'regex:/^[0-9a-zA-Z_.\/]+$/',
						'max:20'
					),
		'hearingdate' => 'date',
		 
        ]);
          $optag = intval($_GET['optag']); 

          if ($optag == 1)   // Population of the form after changing application id 
          {
          $appid = $_GET['applicationid'];
          $hrdt  = date('Y-m-d', strtotime($_GET['hearingdate']));

          $benchdetail  = DB::select("select h.listno, b.benchtypename, b.judgeshortname from dailyhearing as h, bench as b where (h.applicationid='$appid' and h.hearingdate='$hrdt' and h.benchcode=b.benchcode) limit 1");
          $appldetail   = DB::select("select d.*, p.listpurpose as postedfor from dailyhearing as d left join listpurpose as p on (d.purposecode=p.purposecode) where (applicationid='$appid' and hearingdate='$hrdt') limit 1");
          $conappls     = DB::select("select applicationid, hearingdate from dailyhearing where (mainapplicationid='$appid' and business='N')");  // Connected applications
          $applicants   = DB::select("select applicantsrno, applicantname, relationname, applicantaddress from applicant where (applicationid='$appid') order by applicantsrno");
          $respondents  = DB::select("select respondsrno, respondname, respondaddress from respondant where (applicationid='$appid') order by respondsrno");
          $iapending    = DB::select("select * from iadocument where (applicationid='$appid') order by iasrno");
          $m_status     = DB::select("select * from status");
          $m_ordertype  = DB::select("select * from ordertype order by ordertypedesc");
          $m_purpose    = DB::select("select * from listpurpose");
          $m_bench      = DB::select("select * from bench where display='Y'");
          $m_benchtype  = DB::select("select * from benchtype");

          $user     = $request->session()->get('username');

          $data = array();

          $data['benchdetail']  = $benchdetail;
          $data['appldetail']   = $appldetail;
          $data['conappls']     = $conappls;
          $data['applicants']   = $applicants;
          $data['respondents']  = $respondents;
          $data['iapending']    = $iapending;
          $data['m_status']     = $m_status;
          $data['m_ordertype']  = $m_ordertype;
          $data['m_purpose']    = $m_purpose;
          $data['m_bench']      = $m_bench;
          $data['m_benchtype']  = $m_benchtype;
          
          echo json_encode($data);  
          }
          else if ($optag == 2)  // Population of IA data after changing IA selection event
          {
            $appid = $_GET['applicationid'];
            $iano  = $_GET['iano'];

            $iapending    = DB::select("select * from iadocument where (applicationid='$appid' and iano='$iano') order by iasrno limit 1");
            $m_status     = DB::select("select * from status");

            $data = array();

            $data['iapending']    = $iapending;
            $data['m_status']     = $m_status;

            echo json_encode($data); 
          }
          else if ($optag == 99)  // // To calculate next haering date depends upon the user Days/Weeks/Months input 
          {
            $dwmno = intval($_GET['dwmno']);
            $dwm  = $_GET['dwm'];
            $today_date = date_create(date("Y-m-d"));

            if ( $dwm == "d")
            {
                date_modify($today_date, '+'.$dwmno.' day');
                $date = date_format($today_date, 'd-m-Y');
            }
            else if ( $dwm == "w")
            {
                date_modify($today_date, '+'.$dwmno.' week');
                $date = date_format($today_date, 'd-m-Y');
            }
            else
            {
                date_modify($today_date, '+'.$dwmno.' month');
                $date = date_format($today_date, 'd-m-Y');
            }
            
            $data  = array(array('postdt' => $date));
             echo json_encode($data); 
          }
          else
          {
            $data = array();
            echo json_encode($data);
          }
          
    }

 public function courthallendsession(Request $request)
    {
        $request->validate([
        'courthallno' => 'numeric|required',       
         
        ]);
        
        $courthallno = $_POST["courthallno"];
         $displayboard['stage']  = "";
         DB::table('displayboard')->where('courthallno', $courthallno )->update($displayboard);
              $response = array('msg' => ' (End session)');
         echo json_encode($response);
       }
    

    // Update DB and IA table
    public function ChpAjaxPost(Request $request)
    {
        $optag = intval($_POST["optag"]);
        if ( $optag == 2) //IA update array (Update previously selected IA application when change event occur)
        {
            $appid                  = $_POST["applicationid"];
            $iano                   = $_POST["iano"];
            $chpupia['iaprayer']    = $_POST["iaprayer"];
            $chpupia['remark']      = $_POST["iaremarks"];
            $chpupia['iastatus']    = $_POST["iastatus"];
            
            if (DB::table('iadocument')->where('applicationid', $appid)->where('iano', $iano)->update($chpupia))
            {
                echo 1;
            } 
            else 
            {
                echo 0;
            }
        }
        else if ( $optag == 3) // Update displayBoard table
        {
            list($benchcode, $listno, $courthallno, $hearingdate, $causelistsrno, $applicationid, $stage) = explode('^^', $_POST["stage"]);
            $courthallno            = intval($courthallno);
           // $dbu['benchcode']       = intval($benchcode);
           // $dbu['listno']          = intval($listno);
           // $dbu['hearingdate']     = date('Y-m-d', strtotime($hearingdate));
           // $dbu['causelistsrno']   = intval($causelistsrno);
            //$dbu['applicantionid']  = $applicationid;
          
			$causelistsrno=intval($causelistsrno)+1;
		//	print_r($causelistsrno);
		    $dbu['stage']           = $stage;
            $dbu['lastupdated']     = date('h:m:s');
            $dbu['userid']          = $request->session()->get('username');
            $displayboard['stage']  = "";
			$displayboardnext['stage']  = 'N';          
			$displayboardnext['lastupdated']     = date('h:m:s');
            $displayboardnext['userid']          = $request->session()->get('username');
		  
		 
		$appldtls=DB::Table('displayboard')->select('*')->where('applicationid', '=', $applicationid)->get();
		$existingstage =  $appldtls[0]->stage;
		print_r($existingstage.' Stage '.$stage);
		$establishcode = Session()->get('EstablishCode');
       
		if ( $stage == 'A')
            {
			if($existingstage == 'P'){
			 DB::table('displayboard')->where('stage', 'A')->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($displayboard);
             DB::table('displayboard')->where('applicationid', $applicationid)->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($dbu);
			 DB::table('displayboard')->where('applicationid', $applicationid)->where('stage', 'P')->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($displayboard);
            }
				else{
            DB::table('displayboard')->where('stage', 'A')->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($displayboard);
            DB::table('displayboard')->where('applicationid', $applicationid)->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($dbu);
			DB::table('displayboard')->where('stage', 'N')->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($displayboard);           
		    DB::table('displayboard')->where('causelistsrno', $causelistsrno)->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($displayboardnext);
				}
		   $response = array('msg' => ' (Admitted)');
               } 
          else if ( $stage == 'P')
           {
              DB::table('displayboard')->where('applicationid', $applicationid)->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($dbu);
              $response = array('msg' => ' (Paused)');
                echo json_encode($response);
            } 
			else if ( $stage == 'E')
           {
              DB::table('displayboard')->where('courthallno', $courthallno )->where('establishcode', $establishcode )->update($displayboard);
              $response = array('msg' => ' (Paused)');
                echo json_encode($response);
            }
            else 
            {
                $response = array('msg' => 'DB not updated');
                echo json_encode($response);
            }
        }
        else
        {
           //
        }
        
    }

   
 public function gethearingdtls(Request $request)
 {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['CourtHalls'] = $this->IANature->getCourthalls($establishcode);
        $data['Status'] =  $this->IANature->getStatus();
        $data['purpose'] =  $this->IANature->getListPurpose();
        $data['ordertype'] =  $this->IANature->getOrderType();
        $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);
        $data['title'] = 'IADocument';
        return view('Proceedings.hearingdetails',$data)->with('user',$request->session()->get('userName'));
    }


public function updateDailyHearing(Request $request)
 {
       $validator = \Validator::make($request->all(), [
           'hearingDate'=>'required|date',
            'benchCode'=>'required',
            'postedfor'=>'required',
            'courtDirection'=>'required',
          'officenote'=>'required',  
          'applStatus'=>'required',   
          'disposedDate' => 'required_if:applStatus,2',           
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {
            //add daily hearing
           if($request->get('isnexthearing')=='Y')
            {
              $nexthrdate = date('Y-m-d',strtotime($request->get('nextHrDate')));
              $nextbench = $request->get('nextbenchJudge');
              $nextpurpose = $request->get('nextPostfor');
              $nextbenchtype = $request->get('nextBench');
            }
            else
            {
               $nexthrdate = null;
              $nextbench = null;
              $nextpurpose =null;
              $nextbenchtype = null;
            }
            
            if($request->get('applStatus')==2||$request->get('applStatus')==4)
            {
                $disposeddate = date('Y-m-d',strtotime($request->get('disposedDate')));                 
            }
            else 
            {
                   $disposeddate = null;           
            }            
           
		try{
       $establishcode = $request->session()->get('EstablishCode');        
       
			$hearingCode = $request->get('hearingCode');
             DB::beginTransaction();
             //updating ia array
              $HearingDoc = Dailyhearing::find($request->get('hearingCode'));
             //       dd($HearingDoc);    
                    $HearingDoc->applicationid = $request->get('applicationId');
                     $HearingDoc->hearingdate=date('Y-m-d',strtotime($request->get('hearingDate')));
                     $HearingDoc->benchcode=$request->get('benchJudge');
                     $HearingDoc->purposecode=$request->get('postedfor');
                     $HearingDoc->courtdirection=$request->get('courtDirection');
                     $HearingDoc->caseremarks=$request->get('caseRemarks');
                     $HearingDoc->business='Y';
                     $HearingDoc->casestatus= $request->get('applStatus');
                     $HearingDoc->disposeddate=$disposeddate;
                     $HearingDoc->officenote=$request->get('officenote');
                     $HearingDoc->nextdate=$nexthrdate;
                     $HearingDoc->nextbenchcode=$nextbench;
                     $HearingDoc->nextpurposecode=$nextpurpose;
                     $HearingDoc->ordertypecode=$request->get('ordertypecode');
                     $HearingDoc->benchtypename=$request->get('benchCode');
                     $HearingDoc->nextbenchtypename=$nextbenchtype;
					 $applicationid= $request->get('applicationId');                    
					if($HearingDoc->save())
                     {
						   $value = true;
								//update Applicationdisposed table if status ='Disposed'
								if($request->get('applStatus')==2)
								{ 		
               					  $appldetail  = DB::select("select d.*, p.listpurpose as postedfor from dailyhearing as d left join listpurpose as p on (d.purposecode=p.purposecode) where (hearingcode='$hearingCode') limit 1");
								  $applTypeRefer = $this->case->getApplTypeRefer($applicationid,'');
								  if( count($applTypeRefer)>0)
									{
										$applnStore['referapplid'] = $applTypeRefer[0]->referapplid;										 
									}
									
									//get application dtls from application table
									$ApplicationDet = $this->case->getAllApplicationDetails($applicationid,'','application',$establishcode);
									
									$applnStore['applicationdate']=$ApplicationDet[0]->applicationdate;
									$applnStore['actsectioncode']=$ApplicationDet[0]->actsectioncode;
									$applnStore['applicationyear']=$ApplicationDet[0]->applicationyear;
									$applnStore['appltypecode']=$ApplicationDet[0]->appltypecode;
									$applnStore['applicationsrno']=$ApplicationDet[0]->applicationsrno;
									$applnStore['applicationtosrno']=$ApplicationDet[0]->applicationtosrno;
									$applnStore['registerdate']=$ApplicationDet[0]->registerdate;
									$applnStore['applcategory']=$ApplicationDet[0]->applcategory;
									$applnStore['subject']=$ApplicationDet[0]->subject;
									$applnStore['applicantcount']=$ApplicationDet[0]->applicantcount;
									$applnStore['respondentcount']=$ApplicationDet[0]->respondentcount;
									
								    $applnStore['disposeddate'] = $disposeddate;
									$applnStore['applicationid']= $applicationid;
									$applnStore['statusid']=$request->input('applStatus');
									$applnStore['benchcode']=$request->input('benchJudge');
									$applnStore['purposecode']=$appldetail[0]->purposecode;
									$applnStore['benchtypename']=$appldetail[0]->benchtypename;
									$applnStore['ordertypecode']=$request->input('ordertypecode');				
									$applnStore['createdby']= $request->session()->get('userName');
									$applnStore['createdon']= date('Y-m-d H:i:s') ;
							        $applnStore['establishcode']= $establishcode ;
									$value = $this->disposedApplication->addDisposedApplDetails($applnStore);									
							
 }
						     $useractivitydtls['applicationid_receiptno'] =$applicationid; 
							 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
							 $useractivitydtls['activity'] ='Update Daily Hearing' ;
							 $useractivitydtls['userid'] = $request->session()->get('username');
							 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
							 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
							 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);	
						}
					DB::commit();
				    if( $value == true)
						{ 					  				  
						 return response()->json([
                        'status' => "sucess",
                        'message' => "Daily Hearing Updated Successfully"
                        ]);
				   	}                   
            else
              {  
				      DB::rollback();
                      return response()->json([
                     'status' => "fail",
                     'message' => "Something Went Wrongaa!!"
                    ]);
                    }  
			}catch (\Exception $e) {
			   DB::rollback();
         return response()->json([
                  'status' => "fail",
                  'message' => "Something Went Wrongss !!"
                   ]);
			}
		    	catch (\Throwable $e) {
                  DB::rollback();
                  return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong gg!!"
                    ]);
			} 					
        }
    }




public function showdisplayboard(Request $request){
  return view('Proceedings.DisplayBoard');
}

public function showdisplayboardbelgavi(Request $request){
  return view('Proceedings.DisplayBoardBelgavi');
}

public function showdisplayboardkalburgi(Request $request){
  return view('Proceedings.DisplayBoardKalburgi');
}


public function getdisplayboard(Request $request){ 
   $establishcode = $request->get('establishcode');
   $hearingdate = date('Y-m-d');
   $query1 = "select a.applicationid, applicationo, stage, causelistsrno,courthallno from displayboard as a,applicant_cause1 as b where  stage in ('A','P','N') and  a.applicationid=b.applicationid and establishcode=$establishcode and hearingdate = '" .date('Y-m-d',strtotime($hearingdate)) . "'";
   $data['displayboard'] = DB::select($query1);
   echo json_encode($data['displayboard']);
}


}
