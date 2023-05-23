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

class Causelistverify extends Controller
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
        //$this->case = new CaseManagementModel();
    }

    public function index(Request $request)
    {
         $user = $request->session()->get('userName');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['benchjudge'] = $this->IANature->getbenchjudge();

//         $data['purpose'] =  $this->IANature->getListPurpose();
//          $data['CourtHalls'] = $this->IANature->getCourthalls();
//        $data['causelisttype']=Causelisttype::all();
//         $data['list']=$this->IANature->getlist();

//          $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));

        $data['title'] = 'Verification of Causelist';
        return view('Causelist.Causelistverify',$data);
        
    }

public function getcauselistappl(Request $request){
   $benchjudge = $_POST['benchjudge']; 
    $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
   
    $data['causelist'] = DB::select("select distinct causelistcode, courthalldesc||' : '||causelistdesc||' : '|| listno as causelistdesc from causelistview where causelistdate = '" .date('Y-m-d',strtotime($hearingdate)) . "' and benchcode = " . $benchjudge . "" );
               
               
    echo json_encode($data['causelist']);

    }

public function getcauselistapplconnect(Request $request){
    $causelistcode = $_POST['causelistcode'];

       $data['causelist'] = DB::select("select causelistcode, clheader,clfooter, clnote, causelisttime, causelistsrno, listpurpose,applicationid,enteredfrom,connected,iaflag,coalesce(conapplid,'') as conapplid from causelistview where causelistcode = " . $causelistcode . " and istransferred is null order by causelistsrno " );
     echo json_encode($data['causelist']);

    }

  public function getcauselistconnectedappl(Request $request){
       $causelistcode = $_POST['causelistcode'];
       $applicationid = $_POST['applicationid'];
       $data['connectedappl'] = DB::select("select applicationid,connected,iaflag,coalesce(conapplid,'') as conapplid from causelistview where causelistcode = " . $causelistcode . " and applicationid = '".$applicationid."'  " );
    echo json_encode($data['connectedappl']);

    }


public function getcauselistremarks(Request $request){
      
  $applicationid =$request->applicationid;
  $causelistcode = $request->causelistcode;


 if ($request->choice == '1')
 { 
     $data['applicationid'] = DB::select("select coalesce(appautoremarks,'') as appautoremarks,coalesce(resautoremarks,'') as resautoremarks,coalesce(appuserremarks,'') as appuserremarks,coalesce(resuserremarks,'') as resuserremarks  from causelistview where causelistcode = " . $causelistcode . " and applicationid = '" . $applicationid . "' " );
 }
 
 if ($request->choice == '2')
  {   
    $data['applicationid'] = DB::select("select coalesce(conappautoremarks,'') as appautoremarks,coalesce(conresautoremarks,'') as resautoremarks,coalesce(conappuserremarks,'') as appuserremarks,coalesce(conresuserremarks,'') as resuserremarks  from causelistview where causelistcode = " . $causelistcode . " and conapplid = '" . $applicationid . "' " );
    }    

  echo json_encode($data['applicationid']);

    }


public function getpreviouscauselistremarks(Request $request){
      
  $applicationid =$request->applicationid;
 // $causelistcode = $request->causelistcode;

 if ($request->choice == '1')
 { 
    $data['applicationid'] = DB::select("select coalesce(appautoremarks,'') as appautoremarks,coalesce(resautoremarks,'') as resautoremarks,coalesce(appuserremarks,'') as appuserremarks,coalesce(resuserremarks,'') as resuserremarks  from causelistappl ca inner join causelist c on ca.causelistcode = c.causelistcode where applicationid = '" . $applicationid . "' order by causelistdate desc LIMIT 1 " );
 }
 
 if ($request->choice == '2')
  {   

   $data['applicationid'] = DB::select("select coalesce(appautoremarks,'') as appautoremarks,coalesce(resautoremarks,'') as resautoremarks,coalesce(appuserremarks,'') as appuserremarks,coalesce(resuserremarks,'') as resuserremarks  from  causelistconnect ca inner join causelist c on ca.causelistcode = c.causelistcode where conapplid = '" . $applicationid . "' order by causelistdate desc LIMIT 1" );
    }    
//print_r($data['applicationid']);
  echo json_encode($data['applicationid']);

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

  

    public function updatecauselistheader(Request $request){
      
   $causelistcode = $request->causelistcode;
   $header = $_POST['clheader'];
   $footer = $_POST['clfooter'];
   $note = $_POST['clnote'];
   $time = $_POST['causelisttime'];

  $query1 = "update causelisttemp set clheader = '" . $header . "', clfooter = '" . $footer . "', clnote = '" . $note . "', causelisttime ='" . $time  . "' where causelistcode = " . $causelistcode. "";

  $value= DB::update($query1);

if ($value ==1)
       {
                        return response()->json([
                        'status' => "success",
                        'message' => "Header details updated Successfully",
                      
                        ]);
          
                    }
                   else
                    {  return response()->json([
                        'status' => "fail",
                        'message' => "Error in updation",
                 
                        ]); 
                         }

}

    public function store(Request $request)
    {


       $validator = \Validator::make($request->all(), [
            'hearingDate' => 'required|max:10',
            'benchCode'=>'required',
            'benchJudge'=>'required'
    
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }


if ($request->choice == 1)
    { 
  $query1 = "update causelistappltemp set appautoremarks = '" . $request->appautoremarks . "', resautoremarks = '" . $request->resautoremarks . "', appuserremarks = '" . $request->appuserremarks  . "', resuserremarks ='" . $request->resuserremarks  . "' where causelistcode = " . $request->causelistfrm . " and applicationid = '" . $request->applselect . "'";
  $value= DB::update($query1);
 
}

if ($request->choice == 2)
 $value= DB::update("update causelistconnecttemp set appautoremarks = '" . $request->appautoremarks . "', resautoremarks = '" . $request->resautoremarks . "', appuserremarks = '" . $request->appuserremarks  . "', resuserremarks ='" . $request->resuserremarks  . "' where causelistcode = " . $request->causelistfrm . " and conapplid = '" . $request->conapplselect . "'" );

    
if ($value ==1)
       {
                        return response()->json([
                        'status' => "success",
                        'message' => "Remarks updated Successfully",
                      
                        ]);
          
                    }
                   else
                    {  return response()->json([
                        'status' => "fail",
                        'message' => "Error in updateion",
                 
                        ]); 
                         }

  
            
 }  // for function


 


    /**
     * Display the specified resource.
     *
     * @param  \App\Causelist  $causelist
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->session()->get('userName');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['benchjudge'] = $this->IANature->getbenchjudge();
         $data['purpose'] =  $this->IANature->getListPurpose();
          $data['CourtHalls'] = $this->IANature->getCourthalls();
        $data['causelisttype']=Causelisttype::all();
         $data['list']=$this->IANature->getlist();
        $data['title'] = 'Publish Causelist';
        return view('Causelist.causelistdated',$data)->with('user',$request->session()->get('userName'));
    }

    public function getCauselist(Request $request)
    {

     //$hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));

    //  $causelistcode =$request->causelistcode;
        
     $causelistcode = $_POST['causelistcode'];
     if ($causelistcode != '') 
     { 
        $data['causelistappldata'] = Causelist::getCauselistdate($causelistcode);
     echo json_encode($data['causelistappldata']); }
   
        
  
    }

    public function getCauseBasedOnlistno(Request $request)
    {
          $benchJudge = $_POST['benchJudge']; 
          $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
          $causetypecode= $_POST['causetypecode'];
          $listno = $_POST['listno'];
          //get the already existing records
          $data['causelistdata'] = Causelist::getCuaseData($benchJudge,$hearingdate,$causetypecode,$listno);
         echo json_encode($data['causelistdata']);
    }

      
    
}