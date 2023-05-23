<?php

namespace App\Http\Controllers;
use App\CaseManagementModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Support\Facades\DB;
use App\ModuleAndOptions;

class ApplicationDetailsController extends Controller
{
    
    public function __construct()
{
     $this->module= new ModuleAndOptions();
}
    public function index(Request $request)
    {
     $title = 'Searchapplication';
     $applications = db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedefault')->orderBy('appltypecode', 'asc')->get(); 
//      $data['usermenu'] =$this->module->usermenu($request->Session()->get('userName'));
//      $data['usermenumodule'] = $this->module->usermenumodule($request->Session()->get('userName'));

     return view('searchapplication')->with(['applications'=>$applications,'title'=>$title]);

    }
    public function appstatus(Request $request)
    {
    	$title = 'Searchapplication';
    	$applications = db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedefault')->orderBy('appltypecode', 'asc')->get(); // result from table applicationtype

        $appnum= $request->input('appnum');

    	$apptype= $request->input('apptype');

        $apptype_explode = explode('-', $apptype);

         $apptype_code= $apptype_explode[1];

         $find_appnum = $apptype_code."/".$appnum;
        

    $results = DB::table('applicationsummary1')->where('applicationid', $find_appnum)->get();
   

    if ($results->isEmpty())
    {       $error=" Case Details Not Found";
// $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
  //      $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
           return redirect('searchapplication')->with(compact('error','appnum','apptype'));

    }
    else{
         if($apptype != 'OA')
         {
            $applagainst = DB::table('applagainst')->where('applicationid', $find_appnum)->get()[0];
         }else
            $applagainst = "";
         $isconnected = $results[0]->connectedcase;
        if($isconnected == 1){
            $connectedappl = DB::table('connecetdappldtls')->where('applicationid', $find_appnum)->get();
        }else{
            $connectedappl="";
        }
          $appindexes = DB::table('applicationindex')->where('applicationid', $find_appnum)->orderBy('documentno', 'ASC')->get();   
    $applicants = DB::table('applicantsummary1')->where('applicationid', $find_appnum)->get();
    $respondents = DB::table('respondentsummary1')->where('applicationid', $find_appnum)->get();
    $applreliefs = DB::table('applrelief')->where('applicationid',$find_appnum)->orderBy('reliefsrno', 'ASC')->get();
    $receipts = DB::table('receipt')->where('applicationid',$find_appnum)->get();
    $hearings = DB::table('hearingsummary1')->where('applicationid',$find_appnum)->get();
    $iaotherdocuments = DB::table('iaotherdocumentview')->where('applicationid',$find_appnum)->get();
 //   $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
    return view('searchapplication',compact('applications', 'results', 'appindexes' ,'applicants','respondents','applreliefs','receipts','hearings','appnum','apptype','applagainst','connectedappl','iaotherdocuments'));  
        }              
    }

}

    