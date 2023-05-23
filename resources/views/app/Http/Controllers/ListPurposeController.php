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
class ListPurposeController extends Controller
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
    public function listpurpose(Request $request){
      $data['listorder']=DB::select("SELECT * FROM listpurpose order by purposecode");
        return view('case.listpurpose',$data);
    }
	
    public function ListPurposeSave(Request $request){
       if($request->input('sbmt_adv') == "A")
        {
             $request->validate([
               'purposecode' => 'required |numeric',
               'listpurpose' => 'required',
               'listorder' => 'required|numeric',

            ]);
         $userSave['purposecode']       = $request->input('purposecode');
             $userSave['listpurpose']     = $request->input('listpurpose');
             $userSave['listorder']  = $request->input('listorder');



            DB::beginTransaction();
           $purposecode=DB::select("select max(purposecode) as purposecode from listpurpose")[0];

           $prposecode=$purposecode->purposecode+1;
           $userSave['purposecode']= $prposecode;

			$useractivitydtls['applicationid_receiptno'] = $prposecode;
		    $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		    $useractivitydtls['activity'] ='Add List Purpose' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
            try {
              DB::table('listpurpose')->insert($userSave);
			  $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
              DB::commit();
                   return back()->with('success', 'List Purpose Added Into Database ');
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
           'purposecode' => 'required|numeric',
           'listpurpose' => 'required',
           'listorder' => 'required|numeric',

       ]);



       $purposecode = Input::post('purposecode');
       $listpurpose = Input::post('listpurpose');
       $listorder = Input::post('listorder');

		$useractivitydtls['applicationid_receiptno'] = $purposecode;
		    $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		    $useractivitydtls['activity'] ='Update List Purpose' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
       DB::beginTransaction();

       try {
          DB::UPDATE("UPDATE listpurpose SET listpurpose ='$listpurpose',listorder = '$listorder' WHERE purposecode = '$purposecode' ");
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

  public function getListPurposeDetails(Request $request)
  {   
    	$request->validate([
   	     'purposecode' => 'required|numeric',
             ]);
	  
   $listpurposecode = $_GET['purposecode'];
   
    $data['userdetails'] = DB::select("SELECT * from listpurpose where purposecode =$listpurposecode" );
   
    echo json_encode($data['userdetails']);

  }

  }
