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
class AddModuleController extends Controller
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
    public function AddModule(Request $request){
      // $establishcode=Session::get('EstablishCode');
      //  $data['judgedesignation']=DB::select("SELECT * FROM judgedesignation order by judgedesigcode");
       $data['module']=DB::select("SELECT * FROM module order by modulecode");

         return view('master.AddModule',$data);
    }
    public function AddModuleSave(Request $request)
    {
      if($request->input('sbmt_adv') == "A")
       {
            $request->validate([
              'modulecode' => 'required',
               'modulename' => 'required',
               'moduleorder' => 'required',

			   ]);


          $userSave['modulecode']       = $request->input('modulecode');
            $userSave['modulename']       = $request->input('modulename');
              $userSave['moduleorder']       = $request->input('moduleorder');

            DB::beginTransaction();
            try {
             DB::table('module')->insert($userSave);

               DB::commit();
                  return back()->with('success', 'Module Added Successfully');
           } catch (\Exception $e) {
               DB::rollback();
               throw $e;
                 return back()->with('error', 'Someting wrong, Module  not added !!');
           } catch (\Throwable $e) {
               DB::rollback();
               throw $e;
             return back()->with('error', 'Someting wrong, Module  not added !!');
           }
       }

       else if($request->input('sbmt_adv') == "U"){
    $request->validate([
      'modulecode' => 'required',
       'modulename' => 'required',
       'moduleorder' => 'required',


     ]);
    $modulecode=Input::post('modulecode');
     $modulename = Input::post('modulename');
     $moduleorder = Input::post('moduleorder');


     DB::beginTransaction();

     try {

          DB::UPDATE("UPDATE module SET modulename = '$modulename',moduleorder='$moduleorder' WHERE modulecode = '$modulecode' ");

	   DB::commit();
         return back()->with('success', 'Updated Successfully !!');
     } catch (\Exception $e) {
         DB::rollback();
         throw $e;
         return back()->with('error', 'Someting wrong, Module  not Updated !!');
     } catch (\Throwable $e) {
         DB::rollback();
         throw $e;
           return back()->with('error', 'Someting wrong, Module  not Updated !!');
       }
    }
}





    public function getModuleDetails(Request $request)
   {
	   $request->validate([
   	    'modulecode' => 'required|numeric',
             ]);
     $modulecode = $_GET['modulecode'];
    $data['userdetails'] = DB::select("SELECT * from module where modulecode ='$modulecode'" );
   echo json_encode($data['userdetails']);

   }
  }
