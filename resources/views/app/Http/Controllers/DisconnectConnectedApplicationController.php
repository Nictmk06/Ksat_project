<?php

namespace App\Http\Controllers;

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
use App\UserActivityModel;
class DisconnectConnectedApplicationController extends Controller
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
		$this->UserActivityModel = new UserActivityModel();
    }
    public function DisconnectConnectedApplication(Request $request)
    {
        $user = $request->session()->get('userName');
      //  print_r($user);
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['disconnected']=DB::select("SELECT * FROM disconnected order by dhearingdate");
        return view('Case.DisconnectConnectedApplication',$data)->with('user',$request->session()->get('userName'));

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
   public function DisconnectConnectedSave(Request $request)
   {
 if($request->input('sbmt_disconnected') == "A")
    {
        $request->validate([
        'conApplId' => 'required',
        'applicationId' => 'required',
        'applTypeName' => 'required',
        'orderNo' => 'required',
        'type' => 'required',
        'bench' => 'required|numeric',
        'benchtype' => 'required',
        'hearingDate' => 'required|date',
        'orderDate' => 'required|date',
        'direction' => 'required',
        'applicationyear' => 'required|numeric',
        'dbenchtype' => 'required',
        'dbench' => 'required',
        'dhearingDate' => 'required|date',
        'dorderNo' => 'required',
        'dorderDate' => 'required|date',
        'ddirection' => 'required',

       ]);

     $applicationid=Input::post('applicationId');
     $data['applicationid']     = Input::post('applicationId');
     $type1 = explode('-',$request->get('applTypeName'));
     $data['appltypecode']     = $type1[0];
     $data['conapplid']       = $type1[1].'/'.$request->input('conApplId');
     $conapplicationid = $type1[1].'/'.$request->input('conApplId');
     $data['orderno']     = Input::post('orderNo');
     $data['type']  = Input::post('type');
     $data['benchcode']  = Input::post('bench');
     $data['benchtypename']  = Input::post('benchtype');
     $data['hearingdate']  = date('Y-m-d',strtotime($request->get('hearingDate')));
     $data['orderdate']     = date('Y-m-d',strtotime($request->get('orderDate')));
     $data['reason']     = Input::post('direction');
     $data['applicationyear']     = Input::post('applicationyear');


     $data['dbenchtypename']     = $request->input('dbenchtype');
     $data['dbenchcode']     = $request->input('dbench');
     $data['dhearingdate']     = date('Y-m-d',strtotime($request->get('dhearingDate')));
     $data['dorderno']     = $request->input('dorderNo');
     $data['dorderdate']     = date('Y-m-d',strtotime($request->get('dorderDate')));
     $data['dreason']     = $request->input('ddirection');

	$useractivitydtls['applicationid_receiptno'] = Input::post('applicationId');
	$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
	$useractivitydtls['activity'] ='Disconnect Application' ;
	$useractivitydtls['userid'] = $request->session()->get('username');
	$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
	$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid'); 

    DB::beginTransaction();
		try {
            DB::table('disconnected')->insert($data);
            DB::table('connected1')->where('conapplid', '=', $data['conapplid'])->delete();
            DB::update("update application set connectedcase='' where applicationid ='".$conapplicationid."' ");
            $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
 		    DB::commit();
                return back()->with('success', 'connected Application got disconnected ');
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                      return back()->with('error', 'Someting wrong, Department  not added !!');
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw $e;
                  return back()->with('error', 'Someting wrong, Department  not added !!');
                }
   }
   else if($request->input('sbmt_disconnected') == "E"){

     $request->validate([
       'conApplId' => 'required',
       'applicationId' => 'required',
       'applTypeName' => 'required',
       'orderNo' => 'required',
       'type' => 'required',
       'bench' => 'required',
       'benchtype' => 'required',
       'hearingDate' => 'required',
       'orderDate' => 'required',
       'direction' => 'required',
       'applicationyear' => 'required',
       'dbenchtype' => 'required',
       'dbench' => 'required',
       'dhearingDate' => 'required',
       'dorderNo' => 'required',
       'dorderDate' => 'required',
       'ddirection' => 'required',

    ]);

      $conapplid  = Input::post('conApplId');
      $applicationid     = Input::post('applicationId');
      $type1 = explode('-',$request->get('applTypeName'));
      $data['appltypecode']  = $type1[0];
      $appltypecode=$data['appltypecode'];
      $orderno  = Input::post('orderNo');
      $type  = Input::post('type');
      $benchcode = Input::post('bench');
      $benchtypename = Input::post('benchtype');
    //  $hearingdate = Input::post('hearingDate');
      $hearingdate= date('Y-m-d',strtotime($request->get('hearingDate')));
      //$hearingdate->format('d-m-Y');
    //  $orderdate = Input::post('orderDate');
      $orderdate=date('Y-m-d',strtotime($request->get('orderDate')));
      $reason = Input::post('direction');
      $applicationyear = Input::post('applicationyear');


      $dbenchtypename = Input::post('dbenchtype');
      $dbenchcode  = Input::post('dbench');
      $dhearingdate  =date('Y-m-d',strtotime($request->get('dhearingDate')));

      $dorderno = Input::post('dorderNo');
    //  $dorderdate  = Input::post('dorderDate');
      $dorderdate=date('Y-m-d',strtotime($request->get('dorderDate')));
      $dreason  = Input::post('ddirection');

      DB::beginTransaction();

      try {
         DB::UPDATE("UPDATE disconnected SET applicationid = '$applicationid',dreason ='$dreason',dhearingdate = '$dhearingdate',dbenchcode = '$dbenchcode',dorderdate ='$dorderdate',dorderno = '$dorderno',dbenchtypename = '$dbenchtypename' WHERE conapplid = '$conapplid' ");

          DB::commit();
          return back()->with('success', 'Updated Successfully !!');
      } catch (\Exception $e)
      {
          DB::rollback();
          throw $e;
          return back()->with('error', 'Someting wrong, Disconnect Table  not Updated !!');
      }
      catch (\Throwable $e) {
          DB::rollback();
          throw $e;
          return back()->with('error', 'Someting wrong, Disconnect Table  not Updated !!');
      }
    }
  }

 public function getConnectedAppDetails(Request $request)
   {

      $connectedApplicationId = $_GET['connectionid'];
    //  print_r($connectedApplicationId);
      $data['connAppdetails'] = DB::select("SELECT connected.applicationid,connected.applicationyear,connected.reason,connected.appltypecode,connected.hearingdate,connected.benchcode,connected.orderdate,connected.type,connected.benchtypename,connected.orderno,
connected1.conapplid
FROM connected ,connected1
WHERE  connected.applicationid =connected1.applicationid
AND connected1.conapplid='$connectedApplicationId'" );

      echo json_encode($data['connAppdetails']);

   }

   public function getDisConnectedAppDetails(Request $request)
   {

      $disconnectedApplicationId = $_GET['connectionid'];
    //  print_r($connectedApplicationId);
      $data['disconnAppdetails'] = DB::select("SELECT disconnected.applicationid,disconnected.applicationyear,disconnected.reason,disconnected.appltypecode,disconnected.hearingdate,disconnected.benchcode,disconnected.orderdate,disconnected.type,disconnected.benchtypename,disconnected.orderno
,disconnected.conapplid,disconnected.dreason,disconnected.dhearingdate,disconnected.dbenchcode,disconnected.dorderdate,disconnected.dorderno,disconnected.dbenchtypename FROM disconnected WHERE  disconnected.conapplid='$disconnectedApplicationId'");


      echo json_encode($data['disconnAppdetails']);

   }





}
?>
