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
class ianaturecontroller extends Controller
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
		$this->UserActivityModel = new UserActivityModel();
        $this->middleware('guest');

    }
    public function JudgeDetails(Request $request){
      $establishcode=Session::get('EstablishCode');
        $data['ianature']=DB::select("SELECT * FROM ianature order by ianaturecode");

       return view('Case.ianature',$data);
    }
    public function ianatureSave(Request $request)
    {
      if($request->input('sbmt_adv') == "A")
       {
            $request->validate([
              'ianaturecode' => 'required',
               'ianature' => 'required',
          ]);

           $userSave['ianaturecode']       = $request->input('ianaturecode');
           $userSave['ianaturedesc']       = $request->input('ianature');
            $useractivitydtls['applicationid_receiptno'] = $request->input('ianaturecode');  
        	$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		    $useractivitydtls['activity'] ='Add IA Nature' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
		  DB::beginTransaction();

           try {
             DB::table('ianature')->insert($userSave);
          	$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
			DB::commit();
                  return back()->with('success', 'Successful');
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
      'ianaturecode' => 'required',
       'ianature' => 'required',

     ]);


     $ianaturecode=Input::post('ianaturecode');
     $ianature = Input::post('ianature');


     DB::beginTransaction();

     try {
       DB::UPDATE("UPDATE ianature SET ianaturedesc = '$ianature' WHERE ianaturecode = '$ianaturecode' ");
			$useractivitydtls['applicationid_receiptno'] = $request->input('ianaturecode');     
			$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			$useractivitydtls['activity'] ='Update IA Nature' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
            $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);DB::commit();
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

  public function getianature(Request $request)
   {	      
    	$request->validate([
   	    'ianaturecode' => 'required|numeric',
           ]);
     $ianaturecode = $_GET['ianaturecode'];
  
     $data['userdetails'] = DB::select("SELECT * from ianature where ianaturecode ='$ianaturecode'" );
     echo json_encode($data['userdetails']);

   }
  }
