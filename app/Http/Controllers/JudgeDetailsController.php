<?php

namespace App\Http\Controllers;
use Session;
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
class JudgeDetailsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

  // use AddNewDepartment;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
   protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
		$this->UserActivityModel = new UserActivityModel();
    }
    public function JudgeDetails(Request $request){
       $establishcode=Session::get('EstablishCode');
        $data['judgedesignation']=DB::select("SELECT * FROM judgedesignation order by judgedesigcode");
         $data['judge']=DB::select("SELECT * FROM judge INNER JOIN judgedesignation ON judge.judgedesigcode = judgedesignation.judgedesigcode AND  establishcode ='$establishcode' order by CAST(judgecode as INT)");

         return view('case.JudgeDetails',$data);
    }
    public function JudgeDetailsSave(Request $request)
    {
      if($request->input('sbmt_adv') == "A")
       {
            $request->validate([
              'judgecode' => 'required',
               'name' => 'required',
              'judgeshortname' => 'required',
               'designation' => 'required',
               'active' => 'required',
			   ]);

          $userSave['establishcode']=Session::get('EstablishCode');
          $userSave['judgecode']       = $request->input('judgecode');
            $userSave['judgename']       = $request->input('name');
            $userSave['judgedesigcode']     = $request->input('designation');
            $userSave['judgeshortname']  = $request->input('judgeshortname');
			$userSave['fromdate']= $request->fromdate !== null ? date('Y-m-d', strtotime($request->fromdate)) : null;
			$userSave['todate']=$request->todate !== null ? date('Y-m-d', strtotime($request->todate)) : null;
			$userSave['active']     = $request->input('active');
            DB::beginTransaction();
            try {
             DB::table('judge')->insert($userSave);
				$useractivitydtls['applicationid_receiptno'] = $request->input('judgecode');
		         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						 $useractivitydtls['activity'] ='Add Judge Detail' ;
						 $useractivitydtls['userid'] = $request->session()->get('username');
						 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
						 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
						 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
               DB::commit();
                  return back()->with('success', 'Judge Added Successfully');
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

       else if($request->input('sbmt_adv') == "U"){
    $request->validate([
      'judgecode' => 'required',
       'name' => 'required',
    'judgeshortname' => 'required',
       'designation' => 'required',
     'fromdate' => 'required',
       'todate' => 'required',
       'active' => 'required',

     ]);


     $judgecode=Input::post('judgecode');
     $name = Input::post('name');
     $name= str_replace("'","''",$name);
     $designation = Input::post('designation');
     $judgeshortname = Input::post('judgeshortname');
     $userSave['fromdate']= date('Y-m-d',strtotime($request->get('fromdate')));
     $userSave['todate'] = date('Y-m-d',strtotime($request->get('todate')));
     $fromdate=$userSave['fromdate'];
     $todate=$userSave['todate'];
     $active = Input::post('active');

     DB::beginTransaction();

     try {
       
          DB::UPDATE("UPDATE judge SET judgename = '$name',judgeshortname = '$judgeshortname',fromdate = '$fromdate',todate ='$todate',active = '$active',judgedesigcode='$designation' WHERE judgecode = '$judgecode' ");
		  $useractivitydtls['applicationid_receiptno'] = $judgecode;
		         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						 $useractivitydtls['activity'] ='Update Judge Detail' ;
						 $useractivitydtls['userid'] = $request->session()->get('username');
						 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
						 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
						 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);       
	   DB::commit();
         return back()->with('success', 'Updated Successfully !!');
     } catch (\Exception $e) {
         DB::rollback();
         throw $e;
         return back()->with('error', 'Someting wrong, Department  not Updated !!');
     } catch (\Throwable $e) {
         DB::rollback();
         throw $e;
           return back()->with('error', 'Someting wrong, Department  not Updated !!');
       }
    }
}





    public function getJudgeDetails(Request $request)
   {
	   $request->validate([
   	    'judgecode' => 'required|numeric',
             ]);
     $judgecode = $_GET['judgecode'];
    $data['userdetails'] = DB::select("SELECT * from judge where judgecode ='$judgecode'" );
   echo json_encode($data['userdetails']);

   }
  }
