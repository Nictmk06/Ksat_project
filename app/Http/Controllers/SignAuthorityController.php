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

class SignAuthorityController extends Controller
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
    public function SignAuthority(Request $request){
       $establishcode=Session::get('EstablishCode');
       $data['signauthority']=DB::select("SELECT * FROM signauthority where establishcode ='$establishcode' order by idno");
       return view('case.SignAuthority',$data);
    }
	
    public function SignAuthoritySave(Request $request)
    {
      if($request->input('sbmt_adv') == "A")
       {
            $request->validate([
               'name' => 'required',
               'designation' => 'required',
               'departmentname' => 'required',
               'city' => 'required',
               'fromdate' => 'required',
               'todate' => 'required',
               'signdocument' => 'required',
         ]);

            $userSave['establishcode']=Session::get('EstablishCode');
            $userSave['name']       = $request->input('name');
            $userSave['designation']     = $request->input('designation');
            $userSave['deptname1']  = $request->input('departmentname');
            $userSave['deptname2']  = $request->input('city');
            $userSave['fromdate']  = date('Y-m-d',strtotime($request->get('fromdate')));
            $userSave['todate']  = date('Y-m-d',strtotime($request->get('todate')));
            $userSave['documenttype']     = $request->input('signdocument');


           DB::beginTransaction();

           try {
             DB::table('signauthority')->insert($userSave);
               $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				$useractivitydtls['activity'] ='Add Sign Authority ' ;
				$useractivitydtls['userid'] = $request->session()->get('username');
				$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
               DB::commit();
                  return back()->with('success', 'Added Successfully');
           } catch (\Exception $e) {
               DB::rollback();
               throw $e;
                 return back()->with('error', 'Someting wrong, SignAuthority  not added !!');
           } catch (\Throwable $e) {
               DB::rollback();
               throw $e;
             return back()->with('error', 'Someting wrong, SignAuthority  not added !!');
           }
       }

     else if($request->input('sbmt_adv') == "U"){
    $request->validate([
      'name' => 'required',
     'designation' => 'required',
      'departmentname' => 'required',
      'city' => 'required',
      'fromdate' => 'required',
      'todate' => 'required',
      'signdocument' => 'required',

     ]);


     $idno=Input::post('idno');

     $name = Input::post('name');
     $designation = Input::post('designation');
     $departmentname = Input::post('departmentname');
     $city = Input::post('city');
     $userSave['fromdate']= date('Y-m-d',strtotime($request->get('fromdate')));
     $userSave['todate'] = date('Y-m-d',strtotime($request->get('todate')));
     $fromdate=$userSave['fromdate'];
     $todate=$userSave['todate'];
     $signdocument = Input::post('signdocument');

     DB::beginTransaction();

     try {
       DB::UPDATE("UPDATE signauthority SET name = '$name',deptname1 ='$departmentname',deptname2 = '$city',fromdate = '$fromdate',todate ='$todate',documenttype = '$signdocument',designation='$designation' WHERE idno = '$idno' ");
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
				$useractivitydtls['activity'] ='Update Sign Authority ' ;
				$useractivitydtls['userid'] = $request->session()->get('username');
				$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
				$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		DB::commit();
         return back()->with('success', 'Updated Successfully !!');
     } catch (\Exception $e) {
         DB::rollback();
         throw $e;
         return back()->with('error', 'Someting wrong, SignAuthority  not Updated !!');
     } catch (\Throwable $e) {
         DB::rollback();
         throw $e;
           return back()->with('error', 'Someting wrong, SignAuthority  not Updated !!');
       }
     }
    }

   public function getSignAuthorityDetails(Request $request)
   {
	  $request->validate([
   	    'idno' => 'required|numeric',
            ]);
     $idno = $_POST['idno'];
     $data['userdetails'] = DB::select("SELECT * from signauthority where idno =$idno " ); 
     echo json_encode($data['userdetails']);

   }
  }
