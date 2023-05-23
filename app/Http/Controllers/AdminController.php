<?php

namespace App\Http\Controllers;
use App\UserActivityModel;
use App\Causelist;
use App\Causelist1;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\Causelisttype;
use Illuminate\Support\Facades\DB;
use App\causelistconnecttemp;
use App\Services\UngroupApplication;
use App\UngroupApplicationModel;


class AdminController extends Controller
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
        $this->UngroupApplication = new UngroupApplication();
		$this->UngroupApplicationModel = new UngroupApplicationModel();
		$this->UserActivityModel = new UserActivityModel();
    }

    public function preparecl(Request $request)
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

       $data['title'] = 'Prepare Causelist';
        return view('Admin.preparecauselist',$data);
        
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
            'benchJudge'=>'required|numeric'
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
               
                       $value = DB::select("select causelistcode as last from causelisttemp where causelistdate = '" .date('Y-m-d',strtotime($_POST['hearingDate'])) . "' and benchcode = " . $_POST['benchJudge'] . " and listno = " .  $_POST['listno'] . "" );
                       if (empty($value)) 
                           $_POST['causelistcode'] = 0; 
                        else
                           $_POST['causelistcode'] = $value[0]->last; 
// not empty
                  

 
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
                     //   $lastcauselistcode = $value[0]->last + 1; 
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
						$lastcauselistcode = $causelisttemp->causelistcode;
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
                 $value = DB::select("select causelistcode as last from causelisttemp where causelistdate = '" . date('Y-m-d',strtotime($_POST['hearingDate'])) . "' and benchcode = " . $_POST['benchJudge'] . " and listno = " .  $_POST['listno'] . "" );
                      if (empty($value)) 
                           $_POST['causelistcode'] = 0; 
                        else
                           $_POST['causelistcode'] = $value[0]->last; 
                   

              
 

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
   	     'benchJudge' => 'required',
         'causetypecode' => 'required|numeric',
         'listno'=> 'required|numeric|max:10',
	     'hearingdate' => 'required | date', 
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
    {   $request->validate([

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

 


    public function causelistsrreorder(Request $request)
    {  $request->validate([
   	     'cursrno' => 'required|numeric',
         'causelistcode' => 'required|numeric',

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
	  
	  
	public function ungroupapplications(Request $request)
    {
        $title = 'ungroup applications';
        $data['applicationType'] = $this->case->getApplType();
   	  
     return view('Admin.ungroupapplication',$data);

    }

    public function getApplDtls(Request $request)
    {
      $applicationid = $request->get('applicationId');
      $data['applicationDetails'] = DB::table('applicationsummary1')->where('applicationid', $applicationid)->get();
       echo json_encode($data['applicationDetails']); 
    }

    public function saveungroupapplications(Request $request)
    {
       $request->validate([
                'applTypeName' => 'required',
                'applicationId' => 'required',
                'ungroupstno' => 'required|numeric',
                'ungroupendno' => 'required|numeric',
				'orderno' => 'required',
                'orderdate' => 'required | date',  
            ]);  
		$ungrouping='';
		$applType =  explode('-',$request->get('applTypeName'));
        $applicationid = $applType[1].'/'.$request->get('applicationId');
        $applicationdtls = $this->case->getApplicationId($applicationid,'');
        $applicationsrno=$applicationdtls[0]->applicationsrno;
        $applicationtosrno= $applicationdtls[0]->applicationtosrno;
        $applicationyear =$applicationdtls[0]->applicationyear;
		$applicantcount =$applicationdtls[0]->applicantcount;
		$applicationdate =$applicationdtls[0]->applicationdate;
        $ungroupstno = $request->get('ungroupstno');
        $ungroupendno = $request->get('ungroupendno');
		
		//find ungroup is from application table or group table
	    $applicationdtls1 = $this->case->getAppDtlsBysrno($applicationid,$ungroupstno,$ungroupendno);
        $groupapplicationdtls = $this->case->getGroupAppDtlsBysrno($applicationid,$ungroupstno,$ungroupendno);
        
		if (count($applicationdtls1)> 0)
			 {
			 	$ungrouping ="Main";
			 }
		else if (count($groupapplicationdtls) > 0)
		 {
			 $groupapplicationsrno=$groupapplicationdtls[0]->applicationsrno;
		     $groupapplicationtosrno=$groupapplicationdtls[0]->applicationtosrno;
			 $groupapplicationyear = $groupapplicationdtls[0]->applicationyear;
			 $groupapplicationdate = $groupapplicationdtls[0]->applicationdate;
		     $ungrouping ="Additional";
	     }else{
			 $ungrouping ="Invalid";
		 }
	    if($ungrouping == "Invalid")
		 {
			   return back() ->with('error','Invalid Ungroup Start or end no.');
		 }
		try{
        DB::beginTransaction();
		if($ungrouping == "Main")
		 {			
		//case -I
		//ugroup no is start side
		  if($ungroupstno == $applicationsrno)
		  {
            $newstartno=$ungroupendno+1;
            $newapplicationid = $applType[1].'/'.$newstartno.'/'.$applicationyear;
            $newapplicationsrno=$newstartno;
			$newapplicationtosrno= $applicationtosrno;
            $newapplicantcount = $applicantcount-($ungroupendno-$ungroupstno+1);
			$oldapplicantcount = $ungroupendno-$ungroupstno+1;
			$oldapplicationtosrno = $ungroupendno;
           // $createdon= date('Y-m-d H:i:s') ;
           // $createdby= 'ungroup' ;
            $this->UngroupApplication->addApplicationDetails($newapplicationid,$newapplicantcount,$newapplicationsrno,
			                                     $newapplicationtosrno,$applicationyear,$applicationdate,$applicationid); 
            $this->UngroupApplication->updateApplicationDetails($oldapplicationtosrno,$oldapplicantcount,$applicationid); 
            $this->UngroupApplication->addapplreliefDetails($newapplicationid,$applicationid); 
            $this->UngroupApplication->addrespondantDetails($newapplicationid,$applicationid);
			$this->UngroupApplication->updateapplicantDetailsID($newapplicationid,$applicationid);
			$this->UngroupApplication->updategroupApplicationDetails($newapplicationid,$applicationid);
			
			//print_r('ungroup='.$value);
			$newapplicantsrno =1;
			$arrlen ='';
			if (isset($_POST['applicantsrnoSelect']))
			{
				$arrlen  = count($request->input('applicantsrnoSelect'));
			    for($i = 0; $i < $arrlen; $i++)
				{
                $applicantsrno = $request->input('applicantsrnoSelect')[$i];
                $this->UngroupApplication->updateapplicantDetails($applicationid,$newapplicantsrno,$newapplicationid,
							$applicantsrno);
                $newapplicantsrno++;
               } 
			}
			$this->UngroupApplication->addapplicationindexDetails($newapplicationid,$applicationid);
            $this->UngroupApplication->adddailyhearingDetails($newapplicationid,$applicationid);
			//print_r($arrlen );         
			$this->UngroupApplication->rearrangeApplicantSrNo($arrlen ,$newapplicationid);
			$this->UngroupApplication->updateUngroupFlag($applicationid);
        if($applType[1] != 'OA')
        {
               $this->UngroupApplication->addapplagainstDetails($newapplicationid,$applicationid); 
		 }
		 }
		 //case -II
        //ugrouping is from end side only
        if($ungroupendno == $applicationtosrno)
        {
			$newstartno=$ungroupstno;
            $newapplicationid = $applType[1].'/'.$newstartno.'/'.$applicationyear;
            $newapplicationsrno=$newstartno;
			$newapplicationtosrno=$ungroupendno;			
            $newapplicantcount = $ungroupendno-$ungroupstno+1;	
			$oldapplicantcount = $applicantcount-($ungroupendno-$ungroupstno+1);
			$oldapplicationtosrno = $ungroupstno-1;
			$this->UngroupApplication->addApplicationDetails($newapplicationid,$newapplicantcount,$newapplicationsrno,
			                           $newapplicationtosrno,$applicationyear,$applicationdate,$applicationid); 
            $this->UngroupApplication->updateApplicationDetails($oldapplicationtosrno,$oldapplicantcount,$applicationid); 
            $this->UngroupApplication->addapplreliefDetails($newapplicationid,$applicationid); 
            $this->UngroupApplication->addrespondantDetails($newapplicationid,$applicationid); 
			$newapplicantsrno =1;
			if (isset($_POST['applicantsrnoSelect']))
			{
				$arrlen  = count($request->input('applicantsrnoSelect'));
			    for($i = 0; $i < $arrlen; $i++)
				{
                $applicantsrno = $request->input('applicantsrnoSelect')[$i];
                $this->UngroupApplication->updateapplicantDetails($newapplicationid,$newapplicantsrno,$applicationid,
							$applicantsrno);
                $newapplicantsrno++;
               } 
			}           
		    $this->UngroupApplication->addapplicationindexDetails($newapplicationid,$applicationid);
            $this->UngroupApplication->adddailyhearingDetails($newapplicationid,$applicationid);
        	$this->UngroupApplication->updateUngroupFlag($newapplicationid);
			if($applType[1] != 'OA')
			{
               $this->UngroupApplication->addapplagainstDetails($newapplicationid,$applicationid); 
			}
		}
		//case -III
        //ugrouping is from between
		if(($ungroupstno != $applicationsrno) && ($ungroupendno != $applicationtosrno))
        {
			$newstartno=$ungroupstno;
            $newapplicationid = $applType[1].'/'.$newstartno.'/'.$applicationyear;
            $newapplicationsrno=$newstartno;
			$newapplicationtosrno=$ungroupendno;			
            $newapplicantcount = $ungroupendno-$ungroupstno+1;			
			$oldapplicantcount = $applicantcount-($ungroupendno-$ungroupstno+1);
			$oldapplicationtosrno = $ungroupstno-1;
			$this->UngroupApplication->addApplicationDetails($newapplicationid,$newapplicantcount,$newapplicationsrno,
			                               $newapplicationtosrno,$applicationyear,$applicationdate,$applicationid); 
            $this->UngroupApplication->updateApplicationDetails($oldapplicationtosrno,$oldapplicantcount,$applicationid); 
            $this->UngroupApplication->addapplreliefDetails($newapplicationid,$applicationid); 
            $this->UngroupApplication->addrespondantDetails($newapplicationid,$applicationid); 
			$newapplicantsrno =1;
			$arrlen = '';
			$applicantsrno = '';
			if (isset($_POST['applicantsrnoSelect']))
			{
				$arrlen  = count($request->input('applicantsrnoSelect'));
			    for($i = 0; $i < $arrlen; $i++)
				{
                $applicantsrno = $request->input('applicantsrnoSelect')[$i];
                $this->UngroupApplication->updateapplicantDetails($newapplicationid,$newapplicantsrno,$applicationid,
							$applicantsrno);
                $newapplicantsrno++;
               } 
			}               
		    $this->UngroupApplication->addapplicationindexDetails($newapplicationid,$applicationid);
            $this->UngroupApplication->adddailyhearingDetails($newapplicationid,$applicationid);
			$this->UngroupApplication->rearrangeApplicantSrNoFromBetween($arrlen,$applicantsrno,$applicationid);
			$this->UngroupApplication->updateUngroupFlag($newapplicationid);
			if($applType[1] != 'OA')
			{
               $this->UngroupApplication->addapplagainstDetails($newapplicationid,$applicationid); 
			}
			
			//Insert in groupno table
			$groupStore['applicationid'] = $applicationid;
			$groupStore['applicationsrno'] = $ungroupendno+1;
            $groupStore['applicationtosrno'] = $applicationtosrno;
			$groupStore['applicationyear'] =$applicationyear;
	   	    $groupStore['createdon'] =date('Y-m-d') ;
            $groupStore['createdby'] = $request->session()->get('userName');
		    $groupStore['applicationdate'] =$applicationdate;
            $groupStore['appltypecode'] = $applType[0];
            $groupStore['remarks'] ='Ungrouping';
	        $this->case->addGroupApplication($groupStore);
		  }
		}
			
       if($ungrouping == "Additional")
	   {	   
		    $newstartno=$ungroupstno;
            $newapplicationid = $applType[1].'/'.$newstartno.'/'.$groupapplicationyear;
            $newapplicationsrno=$newstartno;
			$newapplicationtosrno=$ungroupendno;			
            $newapplicantcount = $ungroupendno-$ungroupstno+1;		
			
			$oldapplicantcount = $applicantcount-($ungroupendno-$ungroupstno+1);
			$oldapplicationtosrno = $applicationtosrno;
		    $this->UngroupApplication->addApplicationDetails($newapplicationid,$newapplicantcount,$newapplicationsrno,
			                          $newapplicationtosrno,$groupapplicationyear,$groupapplicationdate,$applicationid); 
            $this->UngroupApplication->updateApplicationDetails($oldapplicationtosrno,$oldapplicantcount,$applicationid); 
            $this->UngroupApplication->addapplreliefDetails($newapplicationid,$applicationid); 
            $this->UngroupApplication->addrespondantDetails($newapplicationid,$applicationid); 
			$newapplicantsrno =1;
			if (isset($_POST['applicantsrnoSelect']))
			{
				$arrlen  = count($request->input('applicantsrnoSelect'));
			    for($i = 0; $i < $arrlen; $i++)
				{
                $applicantsrno = $request->input('applicantsrnoSelect')[$i];
                $this->UngroupApplication->updateapplicantDetails($newapplicationid,$newapplicantsrno,$applicationid,
							$applicantsrno);
                $newapplicantsrno++;
               } 
			}                        
		    $this->UngroupApplication->addapplicationindexDetails($newapplicationid,$applicationid);
            $this->UngroupApplication->adddailyhearingDetails($newapplicationid,$applicationid);
			$this->UngroupApplication->updateUngroupFlag($newapplicationid);
			if($applType[1] != 'OA')
			{
               $this->UngroupApplication->addapplagainstDetails($newapplicationid,$applicationid); 
			}
		   //case -IV
        //ugrouping is from additional no  (including start and end no)
		  if(($ungroupstno == $groupapplicationsrno) && ($ungroupendno == $groupapplicationtosrno))
        {
	       DB::delete("delete from groupno where applicationid = :applicationid and
		   applicationsrno =:groupapplicationsrno"
		   ,['applicationid'=>$applicationid,'groupapplicationsrno'=>$groupapplicationsrno]);

		}	
   //case -V
        //ugrouping is from additional no from start side
		  if(($ungroupstno == $groupapplicationsrno) && ($ungroupendno != $groupapplicationtosrno))
        {
	       DB::update("update groupno set applicationsrno = $ungroupendno+1
            where applicationid = :applicationid and applicationsrno =:groupapplicationsrno"
		   ,['applicationid'=>$applicationid,'groupapplicationsrno'=>$groupapplicationsrno]);
		
		}	
 //case -VI
        //ugrouping is from additional no from end side
		  if(($ungroupstno != $groupapplicationsrno) && ($ungroupendno == $groupapplicationtosrno))
        {
	       DB::update("update groupno set applicationtosrno = $ungroupstno-1
            where applicationid = :applicationid and applicationsrno =:groupapplicationsrno"
		   ,['applicationid'=>$applicationid,'groupapplicationsrno'=>$groupapplicationsrno]);
		
		}
 //case -VII
        //ugrouping is from additional no from between
		  if(($ungroupstno != $groupapplicationsrno) && ($ungroupendno != $groupapplicationtosrno))
          {
	       DB::update("update groupno set applicationtosrno = $ungroupstno-1
            where applicationid = :applicationid and applicationsrno =:groupapplicationsrno"
		   ,['applicationid'=>$applicationid,'groupapplicationsrno'=>$groupapplicationsrno]);
		
		   DB::insert("insert into groupno SELECT applicationid, ungroupendno+1, applicationyear, createdon, 
					   createdby, isconnectedappl, applicationdate, appltypecode, remarks, 
					   $groupapplicationtosrno, appltypeshort FROM groupno where
					   applicationid = :applicationid and
						applicationsrno =:groupapplicationsrno"
						,['applicationid'=>$applicationid,'groupapplicationsrno'=>$groupapplicationsrno]);
		   }	
		}
				
        $applnStore['applicationid']=$applicationid;
        $applnStore['orderno'] = $request->input('orderno');
        $applnStore['orderdate']=date('Y-m-d',strtotime($request->input('orderdate')));
        $applnStore['ungroupfrom']=$ungrouping;
		$applnStore['ungroupstartno']=$ungroupstno;
		$applnStore['ungroupendno']=$ungroupendno;
		$applnStore['createdby']= $request->session()->get('userName');
        $applnStore['createdon']= date('Y-m-d H:i:s') ;
	    $this->UngroupApplicationModel->addUngroupApplicationDetails($applnStore);
	   
		$useractivitydtls['applicationid_receiptno'] = $applicationid;
	    $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		$useractivitydtls['activity'] ='Ungroup Application' ;
		$useractivitydtls['userid'] = $request->session()->get('username');
		$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
		$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
	   DB::commit();
         return redirect()->route('ungroupapplications')->with('success', 'Application ungrouped Successfully');
         }catch (\Exception $e) {
                DB::rollback();
                return redirect()->route('ungroupapplications')->with('error', 'Someting went wrong, Application not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                return redirect()->route('ungroupapplications')->with('error', 'Someting wrong, Application not saved !!');
            } 
 }
}
