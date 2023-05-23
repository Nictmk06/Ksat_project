<?php

namespace App\Http\Controllers;
use Auth;
use App\ConnectedApplication as ConnectedModel;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\IADocument as IADocumentModel;
use App\Dailyhearing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\ConnectedApplication1 as ConnectedModel1;
use App\ModuleAndOptions;

class applicationtransfercontroller extends Controller
{
    /**
     * Display a listing of the res
     ource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
    {
        $this->IANature = new IANature();
        $this->case = new CaseManagementModel();
         $this->module= new ModuleAndOptions();
    }
    public function applicationtransfer(Request $request)
    {
        $user = $request->session()->get('userName');
      //  print_r($user);
       $data['establishment'] = DB::select("SELECT establishcode,establishname FROM establishment order by establishcode");
       //$data['establishment1'] = DB::select("SELECT establishcode,establishname FROM establishment order by establishcode");
        $data['applicationType'] = $this->case->getApplType();

        $data['applicationtransfer']=DB::select("SELECT at.applicationid,at.establishcode,e.establishname,at.transferredon FROM applicationtransfer
as at INNER JOIN establishment as e on at.establishcode=e.establishcode
order by at.applicationid");
        //$data['title'] = 'Connected Appl';
//         $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
        //return view('ConnectedApplication.create',$data)->with('user',$request->session()->get('userName'));
        return view('case.applicationtransfer',$data)->with('user',$request->session()->get('userName'));

    }

    /**
     * Show the form for creating a new resource.
     *-
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
   public function applicationtransferSave(Request $request)
   {

        $request->validate([
        'establishment' => 'required',
        'toestablishment' => 'required',
        'applTypeName' => 'required',
        'ApplId' => 'required',
        'registerdate' => 'required',
        'applicationdate' => 'required',
        'subject' => 'required',
        'applcategory'=> 'required',


       ]);

     $applicationid=Input::post('ApplId');
     $type1 = explode('-',$request->get('applTypeName'));
     $data['applicationid']       = $type1[1].'/'.$request->input('ApplId');
     $applicationid = $type1[1].'/'.$request->input('ApplId');
     $data['establishfrom']  = Input::post('establishment');
     $fromestablishment1=$request->input('establishment');
     $data['establishcode']  = Input::post('toestablishment');
     $toestablishment=$request->input('toestablishment');
     $data['transferredon'] = Carbon::now();
     $data['createdon'] =Carbon::now();
     $data['createdby'] =$request->session()->get('userName');

DB::beginTransaction();


                try {
                  DB::table('applicationtransfer')->insert($data);


    $fromestablishname = DB::table('establishment')->select('establishname')->where('establishcode',$fromestablishment1)->get();

    $toestablishname = DB::table('establishment')->select('establishname')->where('establishcode',$toestablishment)->get();

          $message='Application ID '.$applicationid.' Transferd from '.$fromestablishname[0]->establishname.' to '.$toestablishname[0]->establishname;
               DB::update("UPDATE application set establishcode='$toestablishment' where applicationid ='".$applicationid."' ");

                    DB::commit();
                       return back()->with('success', $message);
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                      return back()->with('error', 'Someting wrong,Application not Transferd');
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw $e;
                  return back()->with('error', 'Someting wrong, Application  not Transferd !!');
                }


  }





   public function getAppDetails(Request $request)
   {

      $applicationid = $_GET['applicationid'];

      $data['Appdetails'] = DB::select("SELECT a.applicationdate,a.registerdate,a.applcategory,ap.applcatname,a.subject
from application as a Inner join applcategory as ap on a.applcategory=ap.applcatcode where applicationid='$applicationid'" );

      echo json_encode($data['Appdetails']);

   }
 public function findtoEstablishment()
       {
         $fromestablishment = request()->get('fromestablishment');
   $to_establishment = DB::select("SELECT establishcode,establishname FROM establishment where establishcode !='$fromestablishment' order by establishcode");
        return response()->json($to_establishment);
       }
}
?>
