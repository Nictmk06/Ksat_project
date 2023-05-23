<?php

namespace App\Http\Controllers;

use App\Causelist;
use App\Causelist1;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\Causelisttype;
use Illuminate\Support\Facades\DB;
use App\IADocument as IADocumentModel;
use App\causelistconnecttemp;
use App\ModuleAndOptions;
use App\UserActivityModel;
use PDF;



class causelistfinalize extends Controller
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
        $this->UserActivityModel = new UserActivityModel();
    }


public function printcauselistformat()
{


}

    public function index(Request $request)
    {
         $user = $request->session()->get('userName');
       
      $establishcode = Session()->get('EstablishCode');
      $causelistdate = DB::select("SELECT causelistdate FROM public.causelisttemp where establishcode=$establishcode and  finalizeflag is null ORDER BY causelistdate LIMIT 1");
       if(count($causelistdate)>0)
        $data['causelistdate']= $causelistdate[0]->causelistdate;
      else
        $data['causelistdate']=date('Y-m-d');
        $data['title'] = 'Finalize Causelist';
        return view('Causelist.causelistfinalize',$data);
        
    }

public function getcauselist(Request $request){
    $request->validate([
   	 	 'hearingdate' => 'required | date', 
               ]);
    $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
    $establishcode = Session()->get('EstablishCode');
   $query1 = "select distinct causelistcode, judgeshortname||' : '||courthalldesc||' : '||causelistdesc||' : List-'|| listno as causelistdesc,coalesce(finalizeflag,'N') as finalize , coalesce(postedtocourt,'N') as postedtocourt,causelistdate from causelistview where establishcode=$establishcode and causelistdate = '" .date('Y-m-d',strtotime($hearingdate)) . "'";
 
    $data['causelist'] = DB::select($query1);
               
    echo json_encode($data['causelist']);

    }
	



public function store(Request $request)
    {
	$establishcode = Session()->get('EstablishCode');

	$data['title'] = DB::select("select * from establishment where establishcode = ". Session()->get('EstablishCode'));
	

/* $data['causelist'] = DB::select("SELECT split_part(conapplid, '/', 1) AS concat
     ,split_part(conapplid, '/', 2) AS capplid
   ,split_part(conapplid, '/', 3) AS conappyear,causelistcode,causelisttypecode,benchcode,courthallno,causelistdate,causelistfromdate,
  causelisttodate,listno,totalapplication,benchtypename,finalizeflag,clheader,clfooter,
  clnote,createdon,createdby,causelisttime,establishcode,causelistdesc,courthalldesc,judgeshortname,
  causelistsrno,purposecode,listpurpose,iaflag,connected,conapplid,enteredfrom,appautoremarks,appuserremarks
  ,resautoremarks,resuserremarks,conappautoremarks,conappuserremarks,conresuserremarks,conresautoremarks,type,postedtocourt,
  istransferred,applicationid
    FROM  causelistview where causelistcode=". $_POST['causelist'] . " order by causelistsrno,conappyear,capplid "); */
       
/*$data['causelist']=DB::SELECT("SELECT split_part(conapplid, '/', 1) AS concat, 
split_part(conapplid, '/', 2) AS capplid, split_part(conapplid, '/', 3) AS conappyear,
 MAX(a.applicantname) AS applicantname, MAX(r.respondname) AS respondname, MAX(a.advocateregno) AS appladvocateregno, 
MAX(r.advocateregno) AS resadvocateregno, causelistcode, causelisttypecode, benchcode, courthallno, 
causelistdate, causelistfromdate, causelisttodate, listno, totalapplication, benchtypename, finalizeflag, 
clheader, clfooter, clnote, cv.createdon, cv.createdby, causelisttime, establishcode, causelistdesc, 
courthalldesc, judgeshortname, causelistsrno, purposecode, listpurpose, iaflag, connected, conapplid, 
enteredfrom, appautoremarks, appuserremarks, resautoremarks, resuserremarks, conappautoremarks, conappuserremarks, 
conresuserremarks, conresautoremarks, type, postedtocourt, istransferred,cv.applicationid
 FROM causelistview AS cv 
LEFT JOIN ( SELECT applicationid, applicantname,advocateregno FROM applicant
 GROUP BY applicationid, applicantname,advocateregno ) AS
 a ON cv.applicationid = a.applicationid LEFT JOIN
 ( SELECT applicationid, respondname,advocateregno FROM respondant GROUP BY applicationid, respondname,advocateregno) 
AS r ON cv.applicationid = r.applicationid 
WHERE causelistcode = ". $_POST['causelist'] ." GROUP BY concat,capplid, conappyear, causelistcode, causelisttypecode, benchcode, courthallno, causelistdate,
 causelistfromdate, causelisttodate, listno, totalapplication, benchtypename, finalizeflag, clheader,
 clfooter, clnote, cv.createdon, cv.createdby, causelisttime, establishcode, causelistdesc, courthalldesc,
 judgeshortname, causelistsrno, purposecode, listpurpose, iaflag, connected, conapplid, enteredfrom, appautoremarks,
 appuserremarks, resautoremarks, resuserremarks, conappautoremarks, conappuserremarks, conresuserremarks, 
conresautoremarks, type, postedtocourt, istransferred, cv.applicationid ORDER BY causelistsrno, conappyear, capplid");*/

$data['causelist']=DB::SELECT("SELECT split_part(conapplid, '/', 1) AS concat,
split_part(conapplid, '/', 2) AS capplid, split_part(conapplid, '/', 3) AS conappyear,
 MAX(a.applicantname) AS applicantname, MAX(r.respondname) AS respondname, MAX(a.advocateregno) AS appladvocateregno,
MAX(r.advocateregno) AS resadvocateregno,MAX(a.againstorders) as againstorders, MAX(a.petitioneradv) as petitioneradv,MAX(r.respondadv) as respondadv, MAX(a.actname) as actname, 
MAX(a.distname) as petdistname, MAX(r.respdistname) as respdistname,dis.distname as servicedistname, causelistcode, causelisttypecode, benchcode, courthallno,
causelistdate, causelistfromdate, causelisttodate, listno, totalapplication, benchtypename, finalizeflag,
clheader, clfooter, clnote, cv.createdon, cv.createdby, causelisttime, cv.establishcode, causelistdesc,
courthalldesc, judgeshortname, causelistsrno, purposecode, listpurpose, iaflag, connected, conapplid,
enteredfrom, appautoremarks, appuserremarks, resautoremarks, resuserremarks, conappautoremarks, conappuserremarks,
conresuserremarks, conresautoremarks, type, postedtocourt, istransferred, cv.applicationid
FROM causelistview AS cv
LEFT JOIN (SELECT app.applicationid, app.applicantname,app.advocateregno,al.againstorders,adv.advocatename as petitioneradv,ac.actname,
d.distname
FROM applicant
as app left join application al on app.applicationid=al.applicationid
left join advocate adv on app.advocateregno=adv.advocateregno
left join district d on app.districtcode=d.distcode
left join act ac on al.actcode=ac.actcode
GROUP BY app.applicationid, app.applicantname,app.advocateregno,al.againstorders,petitioneradv,ac.actname,d.distname ) AS
 a ON cv.applicationid = a.applicationid LEFT JOIN
 (SELECT rpp.applicationid, rpp.respondname,rpp.advocateregno,al.againstorders,adv.advocatename as respondadv,ac.actname,
d.distname as respdistname FROM respondant as rpp
left join application al on rpp.applicationid=al.applicationid
left join advocate adv on rpp.advocateregno=adv.advocateregno
left join district d on rpp.responddistrict=d.distcode
left join act ac on al.actcode=ac.actcode
GROUP BY rpp.applicationid, rpp.respondname,rpp.advocateregno,al.againstorders,respondadv,ac.actname,respdistname)
AS r ON cv.applicationid = r.applicationid
left join application appli on cv.applicationid=appli.applicationid
left join district dis on appli.servicedistrict=dis.distcode
WHERE causelistcode = ". $_POST['causelist'] ."  GROUP BY concat,capplid, conappyear,servicedistname, causelistcode, causelisttypecode, benchcode, courthallno, causelistdate,
 causelistfromdate, causelisttodate, listno, totalapplication, benchtypename, finalizeflag, clheader,
 clfooter, clnote, cv.createdon, cv.createdby, causelisttime,cv.establishcode, causelistdesc,
courthalldesc, judgeshortname, causelistsrno, purposecode, listpurpose, iaflag, connected, conapplid,
enteredfrom, appautoremarks, appuserremarks, resautoremarks, resuserremarks, conappautoremarks, conappuserremarks,
conresuserremarks, conresautoremarks, type, postedtocourt, istransferred, cv.applicationid");

 $data['date']=strtotime($data['causelist'][0]->causelistdate);
	
	$data['causelistdate'] = date('l', strtotime($data['causelist'][0]->causelistdate)) . " the " . date("jS", strtotime($data['causelist'][0]->causelistdate)) . " Day Of" . date(" F Y ", strtotime($data['causelist'][0]->causelistdate)) . ' at ' . $data['causelist'][0]->causelisttime  ;

	$data['benchjudge'] = DB::select("select * from benchjudgeview where benchcode = ". $data['causelist'][0]->benchcode );

	$data['signauthority'] = DB::select("select * from signauthority where '" . $data['causelist'][0]->causelistdate . "' between fromdate and todate  and establishcode=$establishcode ");
	if(count($data['signauthority'])==0)
	{
		$hearingdate  = date('d-m-y',strtotime($data['causelist'][0]->causelistdate));
		return back() ->with('error','Signing Authority is not available for date '. $hearingdate);
	}
	 //return $data;
	 $pdf = PDF::LoadView('CauseListPDF',$data);
	 
	 return $pdf->stream('samplepdf.pdf');            
 }  // for function



public function finalizecauselist(Request $request){
	  $request->validate([
   	  'causelistcode' => 'required|numeric',
        ]);
  // print_r($request->causelistcode);
    $serialno = DB::select("select count(*) as count from causelistappltemp where causelistsrno is null and causelistcode =". $request->causelistcode . "");
    if($serialno[0]->count>0)
         { return response()->json([
                         'status' => "error",
                          'message' => "Causelist not finalized.Please generate serial no.",
                         ]);
        }
    else
    {
      $appautoremarks = DB::select("select count(*) as count from causelistappltemp where coalesce(TRIM(appautoremarks), '') = '' and causelistcode =". $request->causelistcode . "");
    if($appautoremarks[0]->count>0)
      {
        return response()->json([
                         'status' => "error",
                          'message' => "Causelist not finalized.Applicant remarks cannot be empty",
                         ]);
      }
    else{
     $query1 = "update causelisttemp set finalizeflag = 'Y' where causelistcode = " . $request->causelistcode . "";
     $value = DB::update($query1);

     if ($value ==1)
         {
			             $useractivitydtls['applicationid_receiptno'] = $request->causelistcode;
		            	 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						 $useractivitydtls['activity'] ='Finalize Cause List' ;
						 $useractivitydtls['userid'] = $request->session()->get('username');
						 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
						 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
						 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                        return response()->json([
                       'status' => "success",
                        'message' => "Causelist finalized.",
                      
                        ]);
          
                    }
                   else
                    {  return response()->json([
                        'status' => "error",
                        'message' => "Error in updateion",
                 
                        ]); 
                         }

  }
}
             
//   return view('samplepdf',$data);

}

public function previousCauseList(Request $request)
    {
         $user = $request->session()->get('userName');     

        $data['title'] = 'Previous Causelist';
        return view('Causelist.previouscauselist',$data);
        
    }



public function getcauselistfrompreviouscl(Request $request){
    $request->validate([
   	 	 'hearingdate' => 'required | date', 
               ]);
    $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
      $establishcode = Session()->get('EstablishCode');
   
   $query1 = "select distinct causelistcode, judgeshortname||' : '||courthalldesc||' : '||causelistdesc||' : List-'|| listno as causelistdesc,coalesce(finalizeflag,'N') as finalize , coalesce(postedtocourt,'N') as postedtocourt,causelistdate from causelistviewfrompreviouscl where establishcode=$establishcode and causelistdate = '" .date('Y-m-d',strtotime($hearingdate)) . "'";
 
    $data['causelist'] = DB::select($query1);
               
    echo json_encode($data['causelist']);

    }
	
	

 public function printpreviouscauselist(Request $request)
    {
	//print_r($_POST['causelist']);
	$data['title'] = DB::select("select * from establishment where establishcode = ". Session()->get('EstablishCode'));
	$data['causelist'] = DB::select("select * from causelistviewfrompreviouscl where causelistcode = ". $_POST['causelist'] . " order by causelistsrno ");
  $data['date']=strtotime($data['causelist'][0]->causelistdate);

	$data['causelistdate'] = date('l', strtotime($data['causelist'][0]->causelistdate)) . " the " . date("jS", strtotime($data['causelist'][0]->causelistdate)) . " Day Of" . date(" F Y ", strtotime($data['causelist'][0]->causelistdate)) . ' at ' . $data['causelist'][0]->causelisttime  ;
	$data['benchjudge'] = DB::select("select * from benchjudgeview where benchcode = ". $data['causelist'][0]->benchcode );
	$data['signauthority'] = DB::select("select * from signauthority where '" . $data['causelist'][0]->causelistdate . "' between fromdate and todate ");

	$pdf = PDF::LoadView('CauseListPDF',$data); 

	return $pdf->stream('CauseList.pdf');

  }  
    
}
