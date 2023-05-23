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
        //$this->case = new CaseManagementModel();
    }


public function printcauselistformat()
{

 




}


    public function index(Request $request)
    {
         $user = $request->session()->get('userName');
       

        $data['title'] = 'Finalize Causelist';
        return view('Causelist.Causelistfinalize',$data);
        
    }

public function getcauselist(Request $request){
   
    $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
    
   $query1 = "select distinct causelistcode, judgeshortname||' : '||courthalldesc||' : '||causelistdesc||' : List-'|| listno as causelistdesc,coalesce(finalizeflag,'N') as finalize , coalesce(postedtocourt,'N') as postedtocourt from causelistview where causelistdate = '" .date('Y-m-d',strtotime($hearingdate)) . "'";
 
    $data['causelist'] = DB::select($query1);
               
    echo json_encode($data['causelist']);

    }


public function store(Request $request)
    {
//print_r($_POST['causelist']);
$data['title'] = DB::select("select * from establishment where establishcode = ". Session()->get('EstablishCode'));
$data['causelist'] = DB::select("select * from causelistview where causelistcode = ". $_POST['causelist'] . " order by causelistsrno ");



//print_r($data);
//print_r($data['causelist'][0]->causelistdate);

$data['causelistdate'] = date('l', strtotime($data['causelist'][0]->causelistdate)) . " the " . date("jS", strtotime($data['causelist'][0]->causelistdate)) . " Day Of" . date(" F Y ", strtotime($data['causelist'][0]->causelistdate)) . ' at ' . $data['causelist'][0]->causelisttime  ;

$data['benchjudge'] = DB::select("select * from benchjudgeview where benchcode = ". $data['causelist'][0]->benchcode );

$data['signauthority'] = DB::select("select * from signauthority where '" . $data['causelist'][0]->causelistdate . "' between fromdate and todate ");

// print_r($data['benchjudge']);

// return view('CauseListPDF',$data);

 $pdf = PDF::LoadView('CauseListPDF',$data); 

 //return $pdf->download('CauseList.pdf');

 return $pdf->stream('samplepdf.pdf');



  
            
 }  // for function



public function finalizecauselist(Request $request){
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


    
}