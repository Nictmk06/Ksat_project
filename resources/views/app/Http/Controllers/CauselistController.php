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
class CauselistController extends Controller
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

    public function index(Request $request)
    {
         $establishcode = $request->session()->get('EstablishCode');
         $user = $request->session()->get('userName');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);
         $data['purpose'] =  $this->IANature->getListPurpose();
          $data['CourtHalls'] = $this->IANature->getCourthalls($establishcode);
       // $data['causelisttype']=Causelisttype::all();
          
       $data['causelisttype']=Causelisttype::orderBy('causelisttypecode')->get();
         $data['list']=$this->IANature->getlist();

//          $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));

        $data['title'] = 'Prepare Causelist';
        return view('Causelist.causelist',$data);
        
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
       $validator = \Validator::make($request->all(), [
            'hearingDate' => 'required|max:10',
            'benchCode'=>'required',
            'benchJudge'=>'required'
            ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
            $causedate_val = $_POST['causedate_val'];
            $establishcode = $request->session()->get('EstablishCode');

//     if ($causedate_val=='A')        
  //      {
            //if it is add
            if($_POST['causelistfrm']=='Fresh' || $_POST['causelistfrm']=='Other')
            {
                //if it is fresh
               
                $type1 = explode('-',$request->get('applTypeName'));
                $applicationid = $type1[1].'/'.$request->get('applicationId');
                $appltype =  $type1[0];
                $purposecode =$_POST['postedfor'];
                $causeexists = Causelist::getCauselistExistances(date('Y-m-d',strtotime($_POST['hearingDate'])),$applicationid);
//                 echo $applicationid;
//                echo ($causeexists);
                   if  (  empty ( $causeexists )) 
                       {
                       $value = DB::select("select causelistcode as last from causelisttemp where causelistdate = '" .date('Y-m-d',strtotime($_POST['hearingDate'])) . "' and benchcode = " . $_POST['benchJudge'] . " and listno = " .  $_POST['listno'] . "" );
                       if (empty($value)) 
                           $_POST['causelistcode'] = 0; 
                        else
                           $_POST['causelistcode'] = $value[0]->last; }
// not empty
                    else

                        {
                        if (($causeexists->benchcode == $_POST['benchJudge']) && ($causeexists->courthallno == $_POST['courthall']))
                            $_POST['causelistcode']= $causeexists->causelistcode;
                        else
                        {
                        if (($causeexists->benchcode == $_POST['benchJudge']) && ($causeexists->listno <> $_POST['listno']))
                              {
                        return response()->json([
                        'status' => "exists",
                        'message' => "Application is linked with list number ". $causeexists->listno
                        ]);
                        }
                        else    
                        return response()->json([
                        'status' => "exists",
                        'message' => "Application is already added in courthall-". $causeexists->courthallno
                        ]);
                        }
                               }

                if  ( ! empty ( $causeexists )) 
                     {
                    return response()->json([
                        'status' => "exists",
                        'message' => $applicationid . " Application is already added on " . date('d-m-Y',strtotime($_POST['hearingDate'])) . " Bench code " . $causeexists->benchcode . " List no " . $causeexists->listno . " Causelist Type " . $causeexists->causelisttypecode ,
                        'causecode'=>$_POST['causelistcode']
                        ]); 
                    }
 
              // repeat of code (above code of single case )
                $causeconnected = Causelist::getConnectedCase($applicationid);
                if (empty($causeconnected)) 
                 {
                     return response()->json([
                        'status' => "exists",
                        'message' => "Record not available "
                        ]); 
                 }
                  if ($causeconnected[0]->connectedcase == '1')
                            $connected='Y';
                        else
                            $connected='N';
         //       if ($causeconnected[0]->iapending == 'Y')
         //                   $iaflag='Y';
         //               else
         //                   $iaflag='N';
               
                if($causeconnected[0]->connectedcase==2)
                  {
                    return response()->json([
                        'status' => "connect",
                        'message' => "Application is Connected with other application"
                        ]); 
                  }
                             
                 if($causeconnected[0]->connectedcase==1 || $causeconnected[0]->connectedcase=='' || $causeconnected[0]->connectedcase==0)
                {                        
                 //  echo $_POST['causelistcode'];
                    if ($_POST['causelistcode'] > 0)
                    {
                    $lastcauselistcode  = $_POST['causelistcode'];
                    $savevalue = 1;
                    }
                    else
                    {   
                       // $value = DB::select("select max(causelistcode) as last from causelisttemp");
                       // $lastcauselistcode = $value[0]->last + 1; 
                        $causelisttemp = new Causelist1([
                      //  'causelistcode' => $lastcauselistcode,
                        'causelisttypecode'=> $_POST['causetypecode'],
                        'benchcode'=> $_POST['benchJudge'],
                        'appltypecode'=>$appltype,
                        'courthallno'=>$_POST['courthall'],
                        'causelistdate'=>date('Y-m-d',strtotime($_POST['hearingDate'])),
                        'listno'=>$_POST['listno'],
                        'benchtypename'=>$_POST['benchCode'],
                        'createdon' => date('Y-m-d H:i:s'),
                        'createdby' => session()->get('username'),
                        'establishcode' => $establishcode
                        ]);
                        $savevalue = $causelisttemp->save();
						//print_r($savevalue);
						$lastcauselistcode = $causelisttemp->causelistcode;
						//print_r('lastcauselistcode'.$lastcauselistcode);
						 }

             if($savevalue) {
                 $causelistappltemp = new Causelist([
                   'causelistcode'=>$lastcauselistcode,
                 //      'causelistcode'=>1,
                    'connected'=>$connected,
                    // iaflag is not used from application table
                    'iaflag'=> $_POST['iaflag'],
                    'applicationid' =>$applicationid,
                    'appltypecode'=>$appltype,
                    'purposecode'=>$_POST['postedfor'],
                    'enteredfrom'=>$_POST['causelistfrm'],
                    'createdon' => date('Y-m-d H:i:s'),
                    'createdby' => session()->get('username')
                    ]);
                    }
                  $causesave = $causelistappltemp->save();
                if ($causeconnected[0]->connectedcase==1)
                {// echo $causesave;
                        if($causesave)
                        {
                            // if it is connected
                            $value = DB::select("select connected.applicationid,connected.type,conapplid from connected1 left join connected on connected.applicationid=connected1.applicationid where connected.applicationid='".$applicationid."'");
                           foreach ($value as $key) {
                            
                               $causelistconnecttemp = new causelistconnecttemp([
                               
                                'applicationid' =>$key->applicationid,
                                'purposecode'=>$_POST['postedfor'],
                                //'enteredfrom'=>$_POST['causelistfrm'],
                                'iaflag' =>  ' ',
                                'causelistcode'=>$lastcauselistcode,
                                'createdon' => date('Y-m-d H:i:s'),
                                'createdby' => session()->get('username'),
                                'conapplid'=>$key->conapplid ,
                                'type' =>$key->type
                                ]); 
                              // print_r($causelistconnecttemp);
                                $causelistconnecttemp->save();
                           }
                        }
               }
              
              //                  echo $causesave;

                     if($causesave || $causelistconnecttemp)
                     {
						 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						 $useractivitydtls['activity'] ='Prepare Cause List' ;
						 $useractivitydtls['userid'] = $request->session()->get('username');
						 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
						 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
						 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                        return response()->json([
                        'status' => "sucess",
                        'message' => "Application Added Successfully",
                        'causecode'=> $lastcauselistcode
                        ]);
              }
                   else
                    {  return response()->json([
                        'status' => "fail",
                        'message' => "Application not saved",
                        'causecode'=>$lastcauselistcode
                        ]); 
                         }
             }         
            }
// dated applications
            else
            {
                 $applicationid=null;
                 $appltype = 0;
                 $purposecode =0;
                 if (! empty( $_POST['CLchkbox']))
                 $val = $_POST['CLchkbox'];
               else
                return;
                foreach ($val as $key) 
            {
                 $arr = explode('|', $key);
                 $applicationid = $arr[1];
                 $postedfor = $arr[2];                 //hardcoded for testing
                 $causeexists = Causelist::getCauselistExistances(date('Y-m-d',strtotime($_POST['hearingDate'])),$applicationid);
//                 echo $applicationid;
//                echo ($causeexists);
                 if  (  empty ( $causeexists )) 
                       {
                       $value = DB::select("select causelistcode as last from causelisttemp where causelistdate = '" . date('Y-m-d',strtotime($_POST['hearingDate'])) . "' and benchcode = " . $_POST['benchJudge'] . " and listno = " .  $_POST['listno'] . "" );
                      if (empty($value)) 
                           $_POST['causelistcode'] = 0; 
                        else
                           $_POST['causelistcode'] = $value[0]->last; }
                       else
                        {
                        if (($causeexists->benchcode == $_POST['benchJudge']) && ($causeexists->courthallno == $_POST['courthall']))
                            $_POST['causelistcode']= $causeexists->causelistcode;
                        else
                        {
                        return response()->json([
                        'status' => "exists",
                        'message' => "Bench is linked with courthall-". $causeexists->courthallno
                        ]);
                        }
                        if (($causeexists->benchcode == $_POST['benchJudge']) && ($causeexists->listno <> $_POST['listno']))
                       {
                        return response()->json([
                        'status' => "exists",
                        'message' => "Application is linked with list number ". $causeexists->listno
                        ]);
                        }
                               }

                if  ( ! empty ( $causeexists )) 
                     {
                    return response()->json([
                        'status' => "exists",
                        'message' => $applicationid . " Application is already added on " . date('d-m-Y',strtotime($_POST['hearingDate'])) . " Bench code " . $causeexists->benchcode . " List no " . $causeexists->listno . " Causelist Type " . $causeexists->causelisttypecode ,
                        'causecode'=>$_POST['causelistcode']

                        ]); 
                    }
 

              // repeat of code (above code of single case )
  
                $causeconnected = Causelist::getConnectedCase($applicationid);
                 if (empty($causeconnected)) 
                 {
                     return response()->json([
                        'status' => "exists",
                        'message' => "Record not available "
                        ]); 
                 }

         //        if ($causeconnected[0]->iapending == 'Y')
        //                    $iaflag='Y';
        //                else
        //                    $iaflag='N';

              if($causeconnected[0]->connectedcase==2)
                  {
                    return response()->json([
                        'status' => "connect",
                        'message' => "Application is Connected with other application"
                        ]); 
                  }
                             
                 if($causeconnected[0]->connectedcase==1 || $causeconnected[0]->connectedcase=='')
                {                        
                 //  echo $_POST['causelistcode'];
                    if ($_POST['causelistcode'] > 0)
                    {
                    $lastcauselistcode  = $_POST['causelistcode'];
                    $savevalue = 1;
                    }
                    else
                    {   
                       // $value = DB::select("select max(causelistcode) as last from causelisttemp");
                        //$lastcauselistcode = $value[0]->last + 1; 
                        $causelisttemp = new Causelist1([
                       // 'causelistcode' => $lastcauselistcode,
                        'causelisttypecode'=> $_POST['causetypecode'],
                        'benchcode'=> $_POST['benchJudge'],
                        'appltypecode'=>$appltype,
                        'courthallno'=>$_POST['courthall'],
                        'causelistdate'=>date('Y-m-d',strtotime($_POST['hearingDate'])),
                        'listno'=>$_POST['listno'],
                        'benchtypename'=>$_POST['benchCode'],
                        'createdon' => date('Y-m-d H:i:s'),
                        'createdby' => session()->get('username'),
                        'establishcode' => $establishcode
                        ]);
                        $savevalue = $causelisttemp->save();
						$lastcauselistcode = $causelisttemp->causelistcode;
                    }

             if($savevalue) {
                 $causelistappltemp = new Causelist([
                   'causelistcode'=>$lastcauselistcode,
                 //      'causelistcode'=>1,
                    'connected'=>'Y',
                    'iaflag'=> ' ',
                    'applicationid' =>$applicationid,
                    'appltypecode'=>$appltype,
                    'purposecode'=>$postedfor,
                    'enteredfrom'=>$_POST['causelistfrm'],
                    'createdon' => date('Y-m-d H:i:s'),
                    'createdby' => session()->get('username')
                    ]);
                    }
                  $causesave = $causelistappltemp->save();
                if ($causeconnected[0]->connectedcase==1)
                {// echo $causesave;
                        if($causesave)
                        {
                            // if it is connected
                            $value = DB::select("select connected.applicationid,connected.type,conapplid from connected1 left join connected on connected.applicationid=connected1.applicationid where connected.applicationid='".$applicationid."'");
                           foreach ($value as $key) {
                            
                               $causelistconnecttemp = new causelistconnecttemp([
                               
                                'applicationid' =>$key->applicationid,
                                'purposecode'=>$postedfor,
                                //'enteredfrom'=>$_POST['causelistfrm'],
                                'iaflag' =>   ' ',
                                'causelistcode'=>$lastcauselistcode,
                                'createdon' => date('Y-m-d H:i:s'),
                                'createdby' => session()->get('username'),
                                'conapplid'=>$key->conapplid ,
                                'type' =>$key->type
                                ]); 
                              // print_r($causelistconnecttemp);
                                $causelistconnecttemp->save();
                           }
                   }
               }
			}                   
        }
               if($causesave)
                     {
						 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						 $useractivitydtls['activity'] ='Prepare Cause List' ;
						 $useractivitydtls['userid'] = $request->session()->get('username');
						 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
						 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
						 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                        return response()->json([
                        'status' => "sucess",
                        'message' => "Selected Application added successfully",
                        'causecode'=> $lastcauselistcode
                        ]);
               }
                   else
                    {  return response()->json([
                        'status' => "fail",
                        'message' => "Application not saved",
                        'causecode'=>$lastcauselistcode
                        ]); 
                         }
        } // for dated            
 }  // for function


 


    /**
     * Display the specified resource.
     *
     * @param  \App\Causelist  $causelist
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
         $establishcode = $request->session()->get('EstablishCode');
        $user = $request->session()->get('userName');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);
         $data['purpose'] =  $this->IANature->getListPurpose();
          $data['CourtHalls'] = $this->IANature->getCourthalls($establishcode);
        $data['causelisttype']=Causelisttype::all();
         $data['list']=$this->IANature->getlist();
        $data['title'] = 'Publish Causelist';
        return view('Causelist.causelistdated',$data)->with('user',$request->session()->get('userName'));
    }

    public function getCauselist(Request $request)
    {

     //$hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));

    //  $causelistcode =$request->causelistcode;
         $request->validate([
             'causelistcode' => 'required|numeric',              
         ]); 
		 $causelistcode = $_POST['causelistcode'];
		 if ($causelistcode != '') 
		 { 
			$data['causelistappldata'] = Causelist::getCauselistdate($causelistcode);
		 echo json_encode($data['causelistappldata']); }  
        
    }

    public function getCauseBasedOnlistno(Request $request)
    {
		$request->validate([
             'benchJudge' => 'required|numeric',  
			 'hearingdate' => 'required|date',
             'listno' => 'required|numeric', 
			 'causetypecode' => 'required|numeric', 			 
         ]);
          $benchJudge = $_POST['benchJudge']; 
          $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
          $causetypecode= $_POST['causetypecode'];
          $listno = $_POST['listno'];
          //get the already existing records
          $data['causelistdata'] = Causelist::getCuaseData($benchJudge,$hearingdate,$causetypecode,$listno);
         echo json_encode($data['causelistdata']);
    }

    public function getCausereorderData(Request $request)
    {
		$request->validate([
             'causelistcode' => 'required|numeric',  
					 
         ]);
        $causelistcode = $_POST['causelistcode']; 
//        echo $causelistcode;
     //   $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
        $data['reorder'] = Causelist::reordercause($causelistcode);
        $i=1;
        foreach ($data['reorder'] as $key) {
            $causelistorder =  $key->causelistsrno;
            $applicationid =  $key->applicationid;
            $purposecode = $key->purposecode;
           
            $value=DB::table('causelistappltemp')->where('causelistcode',$causelistcode)->where('applicationid',$applicationid)->
            update(array('causelistsrno'=>$i)); 
           
            $i++;
        }

       $data['reorder'] = Causelist::getCauselistdate($causelistcode);

        if($value==true)
        {
            // echo json_encode($data['reorder']);
             return response()->json([
                        'status' => "sucess",
                        'message' => "Serial numbers are rearranged. ",
                        'causecode'=> $causelistcode
                        ]);
        }
        
       

    }

 public function initializeCauselist(Request $request)
   {
    $causelistdate = DB::select("SELECT causelistdate FROM public.causelisttemp where postedtocourt is null ORDER BY causelistdate LIMIT 1")[0];
        $data['causelistdate']= $causelistdate->causelistdate;
    
 //   $causelistdate =date('Y-m-d');
  /*  $causelistday = DB::select("SELECT * FROM causelistday where causelistdate=:causelistdate",['causelistdate' => $causelistdate]);
    if (count($causelistday)==0)
        $data['initialize']='Y';
        else
       $data['initialize']='N';*/
        return view('Causelist.initializecauselist',$data);
  //   return view('Causelist.initializecauselist');
   }

public function saveInitializeCauselist(Request $request){
	$request->validate([
             'causelistcode' => 'required|numeric',  
			 'causelistdate' => 'required|date',
            		 
         ]);
	
    $causelistcode =$request->input('causelistcode'); 
	 $hearingdate = date('Y-m-d',strtotime($_POST['causelistdate']));
  // print_r($hearingdate);
    //$hearingdate  = date('Y-m-d');
    $createdon    = date('Y-m-d H:i:s') ;
	
	 $useractivitydtls['applicationid_receiptno'] = $causelistcode;
	 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
	 $useractivitydtls['activity'] ='Initialize Cause List' ;
	 $useractivitydtls['userid'] = $request->session()->get('username');
	 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
	 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
	 				 
    $value = DB::transaction(function () use($causelistcode,$createdon,$hearingdate,$useractivitydtls) {
            
    DB::update("update causelisttemp set postedtocourt='Y' where causelistcode= :causelistcode",['causelistcode' => $causelistcode]); 
    $query1 = "insert into dailyhearing(applicationid,benchcode,courthallno,hearingdate,listno,purposecode,
            causelistsrno,business,benchtypename,createdon,courtdirection,causelistcode,establishcode) 
                select applicationid,benchcode,courthallno,causelistdate,listno,purposecode,causelistsrno,'N',benchtypename,'$createdon','',$causelistcode ,establishcode FROM causelisttemp,
                 causelistappltemp where causelisttemp.causelistcode=causelistappltemp.causelistcode and causelisttemp.causelistcode= '".$causelistcode."' and causelistappltemp.istransferred is null or istransferred=''"; 
            
    DB::insert($query1);
    DB::insert("insert into dailyhearing(applicationid,benchcode,courthallno,hearingdate,listno,purposecode,
            causelistsrno,business,benchtypename,createdon,courtdirection,causelistcode,mainapplicationid,establishcode)  
                select conapplid,benchcode,courthallno,causelistdate,listno,purposecode,causelistsrno,'N',benchtypename,'$createdon','',$causelistcode,applicationid,establishcode  FROM causelisttemp,
                 causelistconnecttemp where causelisttemp.causelistcode=causelistconnecttemp.causelistcode and causelisttemp.causelistcode= '".$causelistcode."' and causelistconnecttemp.istransferred is null or istransferred=''");
    
	DB::delete("delete from displayboard where hearingdate <'".$hearingdate."'");
    DB::insert('insert into displayboard(benchcode,listno,courthallno,hearingdate,causelistsrno,applicationid,establishcode)
                SELECT benchcode,listno,courthallno,causelistdate,causelistsrno,applicationid,establishcode
                 FROM causelisttemp,
                 causelistappltemp where causelisttemp.causelistcode=causelistappltemp.causelistcode and causelisttemp.causelistcode= :causelistcode',['causelistcode' => $causelistcode]); 
    
    DB::delete("delete from causelistappltemp where causelistcode in (select causelistcode from causelisttemp where causelistdate < '".$hearingdate."')");
    DB::delete("delete from causelistconnecttemp where causelistcode in (select causelistcode from causelisttemp where causelistdate < '".$hearingdate."')");
    DB::delete("delete from causelisttemp where causelistcode in (select causelistcode from causelisttemp where causelistdate < '".$hearingdate."')");
    DB::insert("insert into causelist select * from causelisttemp where causelistcode=:causelistcode",['causelistcode' => $causelistcode]); 
    DB::insert("insert into causelistappl select * from causelistappltemp where causelistcode=:causelistcode",['causelistcode' => $causelistcode]); 
    DB::insert("insert into causelistconnect select * from causelistconnecttemp where causelistcode=:causelistcode",['causelistcode' => $causelistcode]); 
    $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
	
	return true;
            });

  
   if($value==true)
       {
                        return response()->json([
                       'status' => "success",
                        'message' => "Causelist initialized.",
                      
                        ]);
          
                    }
                   else
                    {  return response()->json([
                        'status' => "fail",
                        'message' => "Error",
                 
                        ]); 
                         }

}


    public function addcauselist(Request $request)
    {
       
       
        //print_r($val);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Causelist  $causelist
     * @return \Illuminate\Http\Response
     */
    public function edit(Causelist $causelist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Causelist  $causelist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Causelist $causelist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Causelist  $causelist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Causelist $causelist)
    {
        //
    }

    public function causelistsrreorder(Request $request)
    {
		$request->validate([
             'causelistcode' => 'required|numeric',  
			 'cursrno' => 'required|numeric', 
             'moveto' => 'required|numeric',  
         ]);
       // causelistcode:causelistcode,cursrnocursrno,moveto:moveto
		$causelistcode = $_POST['causelistcode'];
        $cursrno = $_POST['cursrno'];
        $moveto = $_POST['moveto'];

		$maxsrno = DB::select("Select max(causelistsrno) as maxcauselistsrno from causelistappltemp where causelistcode = '". $causelistcode ."'")[0];;
		$maxcauselistsrno= $maxsrno->maxcauselistsrno;
		
	
		if($moveto > $maxcauselistsrno){
		 return response()->json([
                        'status' => "fail",
                        'message' => "Move to cannot be greater than max serial no",
                        'causecode'=>$causelistcode
                        ]);
		}
		else{
		   if($moveto>$cursrno)
          { 
          $value= DB::update("update causelistappltemp set causelistsrno = 0 where causelistsrno=" . $cursrno . " and causelistcode = '". $causelistcode ."'");
            if($value)
                {  
                    $value2 = DB::update("update causelistappltemp set causelistsrno = causelistsrno - 1 where causelistsrno>".$cursrno." and causelistsrno<=".$moveto." and causelistcode = '".$causelistcode."'");
                  if($value2)
                    {
                         $value3= DB::update("update causelistappltemp set causelistsrno = ".$moveto." where causelistsrno=0 and causelistcode = '". $causelistcode ."'");
                         if($value3)
                        {
                     //        echo  "success";
                        }
                        else
                        {
                     //       echo "fail";
                        }   }
                }  }
        else
            { 
          $value= DB::update("update causelistappltemp set causelistsrno = 0 where causelistsrno=" . $cursrno . " and causelistcode = '". $causelistcode ."'");
              if($value)
                { 
                   $value2 = DB::update("update causelistappltemp set causelistsrno = causelistsrno + 1 where causelistsrno>=".$moveto." and causelistsrno<".$cursrno." and causelistcode = '".$causelistcode."'");
                    if($value2)
                    { 
                       $value3= DB::update("update causelistappltemp set causelistsrno = ".$moveto." where causelistsrno=0 and causelistcode = '". $causelistcode ."'");
                        if($value3)
                        {
                          //    echo  "true";
                        }
                        else
                        {
                    //        echo "false";
                        } }                  
                }
          //  echo "greater(++)";
        }
      if ($value3==true)
        {
        return response()->json([
                        'status' => "sucess",
                        'message' => "Serial numbers are rearranged. ",
                        'causecode'=>$causelistcode
                        ]);
        }  }
	  }
}