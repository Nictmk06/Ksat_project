<?php

namespace App\Http\Controllers;
use App\UserActivityModel;
//use App\IANature;
//use App\CaseManagementModel;
//use App\IADocument as IADocumentModel;
//use App\Dailyhearing;
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





class ChpProcUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function __construct()
    {
      //  $this->IANature = new IANature();
      //  $this->case = new CaseManagementModel();
      //  $this->module= new ModuleAndOptions();
       // $this->disposedApplication =  new DisposedApplicationModel();
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
    public function ChProceedingupdateShow(Request $request, Chp $chp)
    {
        $user     = $request->session()->get('username');
        $estcode  = $request->session()->get('EstablishCode');

     $data['getminheardt']=DB::select("select to_char(min(hearingdate),'dd-mm-yyyy') as dt from dailyhearing");
        
      
     //dd( $getminheardt);
        //$hearing_details=
        
        
        //    print_r($data);
         
            return view('Proceedings.proceedingUpdate' ,$data);
       
        
    }
    public function getHearingDetails(Request $request)
    {
        $estcode  = $request->session()->get('EstablishCode');
      
        $hdate=$request->input('hearingDate1');

       $json=array();
       $json= DB::select("select distinct (a.benchcode) as benchcode,a.courthallno,a.listno ,
       b.judgeshortname,concat(b.judgeshortname,' - list no - ',a.listno) as judgelist
         from  dailyhearing  as a
          inner join benchjudgeview as b
        on a.benchcode=b.benchcode
         where to_char(hearingdate,'dd-mm-yyyy')='".$hdate."'
          and establishcode='".$estcode."'
        order  by a.benchcode, a.courthallno,a.listno ");

       
           echo json_encode($json); 
    }

    public function getcourtHall(Request $request){
      $estcode  = $request->session()->get('EstablishCode');  
       $hdate=$request->input('hearingDate1');
       $sel_bno=$request->get('benchno');
       $sel_lno=$request->get('listno');
      // dd($sel_chall);
       $json=array();
       $json= DB::select("select applicationid,a.benchcode, a.courthallno,a.listno,a.establishcode,b.ordertypedesc,* 
        ,a.casestatus , 
       CASE when a.casestatus =1 then 'Pending' else 'Disposed'
       END as status
        from dailyhearing  as a
       inner join ordertype as b
        on a.ordertypecode=b.ordertypecode
          where to_char(hearingdate,'dd-mm-yyyy')='".$hdate."'
          and benchcode='".$sel_bno."' and listno ='".$sel_lno."'
           order  by applicationid, a.benchcode, a.courthallno,a.listno
           limit 3");

       
           echo json_encode($json); 

    }
    
  /*  public function getBench(Request $request){
      $estcode  = $request->session()->get('EstablishCode');  
       $hdate=$request->input('hearingDate1');
       $sel_chall=$request->get('courthallno');
       $sel_bench=$request->get('benchno');
      // dd($sel_chall);
       $json=array();
       $json= DB::select("select distinct(listno)as list from dailyhearing 
          where to_char(hearingdate,'dd-mm-yyyy')='".$hdate."' and establishcode='".$estcode."' and courthallno='". $sel_chall."' and benchcode='".$sel_bench."'");

       
           echo json_encode($json); 

    }*/

    /*public function getList(Request $request){
      $estcode  = $request->session()->get('EstablishCode');  
       $hdate=$request->input('hearingDate1');
       $sel_chall=$request->get('courthallno');
       $sel_bench=$request->get('benchno');
        $sel_list=$request->get('list_no');
      // dd($sel_chall);
      $json=array();
       //$data['app_details']= DB::select(" select 
       $json= DB::select(" select distinct applicationid ,trim(courtdirection) as c_direction,caseremarks,orderyn,officenote,casestatus from dailyhearing
          where to_char(hearingdate,'dd-mm-yyyy')='".$hdate."' and establishcode='".$estcode."' and courthallno='". $sel_chall."' and benchcode='".$sel_bench."' and listno='".$sel_list."'
          order by applicationid limit 3");

       
           echo json_encode($json); 
 // return view('Proceedings.proceedingUpdate' ,$data);
    }*/

    public function getapphearing(Request $request){
     
      $estcode  = $request->session()->get('EstablishCode');  
       $hdate=$request->get('hearingDate1');
      // $sel_chall=$request->get('courthallno');
      // $sel_bench=$request->get('benchno');
       // $sel_list=$request->get('list_no');
        $appId=$request->get('applicationID');
       
        $json=array();
      $json=DB::select("select applicationid,a.benchcode, a.courthallno,a.listno,a.establishcode,b.ordertypedesc,* 
        ,a.casestatus , 
       CASE when a.casestatus =1 then 'Pending' else 'Disposed'
       END as status
        from dailyhearing  as a
       inner join ordertype as b
        on a.ordertypecode=b.ordertypecode
          where applicationid='".$appId."' and to_char(hearingdate,'dd-mm-yyyy')='".$hdate."' 
          order by applicationid ");
      echo json_encode($json); 
    }

    public function getordertype()
    {

      $json=array();  
      $json=DB::select("select * from ordertype order by ordertypedesc ");
      echo json_encode($json);
    }

    public function saveProceeding(Request $request){
       $estcode  = $request->session()->get('EstablishCode');  
      $appid = $request->input('appId');
       $hdate = $request->input('hdate');
       $cno = $request->input('cno');
       $bno = $request->input('bno');
       $lno = $request->input('lno');
       $cdir = $request->get('courtDirection');
       $cremarks = $request->get('remarksIfAny');
       $offnote = $request->get('officeNote');
       $orderpass = $request->get('orderPassed');
       $cstatus = $request->get('caseStatus');
           //dd( $appid )  ; 
   
    // $json=array();  
      $json=DB::update("update dailyhearing 
            set courtdirection='".$cdir."',
              caseremarks='".$cremarks."',
              officenote='".$offnote."',
              ordertypecode='".$orderpass."',
            
              business='Y'
        where applicationid='".$appid."' and to_char(hearingdate,'dd-mm-yyyy')='".$hdate."' 
         ");

      //echo json_encode($json);
   //     return back() ->with('success','Application Proceeding uploaded Successfully.');

     $data['getminheardt']=DB::select("select to_char(min(hearingdate),'dd-mm-yyyy') as dt from dailyhearing");
        $data['benchvalue']= $bno.','.$lno;
         $data['benchname']= $cno;
         $data['herdate']=$hdate;
       return view('Proceedings.proceedingUpdate' ,$data);

    }

    
   

}