<?php

namespace App\Http\Controllers;

use App\ConnectedApplication as ConnectedModel;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\IADocument as IADocumentModel;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\ConnectedApplication1 as ConnectedModel1;
use App\ModuleAndOptions;

class userroleController extends Controller
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

    }
    public function userrole(Request $request){
             $data['module']=DB::select("SELECT modulecode,modulename from module EXCEPT
			SELECT modulecode,modulename from module
			where modulecode='0' order by modulename");
         $data['users']=DB::SELECT("SELECT userid from userpassword order by userid");
        $data['table']=DB::SELECT("SELECT ur.userid,ur.roleid,r.rolename,ur.modulecode,m.modulename,ur.optioncode,op.optionname from userrole as ur
INNER JOIN role as r on ur.roleid=r.roleid
LEFT JOIN module as m on m.modulecode=ur.modulecode
LEFT JOIN options as op on op.optioncode=ur.optioncode
 order by ur.userid,r.rolename,m.modulename,op.optionname");
          return view('master.userrole',$data);
    }
    public function userroleSave(Request $request){

             $request->validate([
               'userid' => 'required',
               'roleid' => 'required',


            ]);



             if($request->input('modulename')==null)
            {
               $userSave['modulecode'] =0;
            }
            else
            {
              $userSave['modulecode']  = $request->input('modulename');

            }

            if($request->input('optionname')==null)
            {
               $userSave['optioncode']  = 0;
            }

            else
            {
              $userSave['optioncode']  = $request->input('optionname');
            }

             $userSave['userid']       = $request->input('userid');
             $userSave['roleid']     = $request->input('roleid');
             $userSave['createdby']=$request->session()->get('username');
             $userSave['createdon']=date('Y-m-d H:i:s');



            DB::beginTransaction();

            try {
              DB::table('userrole')->insert($userSave);
                        DB::commit();
                   return back()->with('success', 'Option alloted to user ');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                  return back()->with('error', 'Someting wrong, Option not not alloted to user !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
              return back()->with('error', 'Someting wrong,Option not not alloted to user !!');
            }








  }


  public function getrole(Request $request)
  {

    $userid = $_GET['userid'];
    $data['userdetails'] = DB::select("SELECT 0 as roleid,'----' as rolename
UNION ALL
SELECT roleid ,rolename from role where roleid  not in (select roleid  from userrole where userid='$userid')
order by rolename" );
      echo json_encode($data['userdetails']);

  }
  public function getuserrole(Request $request)
  {
    $userid = $_GET['userid'];
    $roleid = $_GET['roleid'];
    $data['userdetails'] = DB::select("SELECT * from userrole where userid ='$userid' and roleid='$roleid' " );
    echo json_encode($data['userdetails']);

  }

  public function findOptionsWithModule()
      {
        $moduleid = request()->get('moduleid');
        $userid = request()->get('userid');
        $roleid = request()->get('roleid');

      $optionname = DB::select("SELECT optioncode,optionname from options where modulecode='$moduleid' and optioncode  not in
         (SELECT optioncode  from userrole where
                         userid='$userid' and roleid='$roleid' and modulecode='$moduleid')  order by optionname");

          return response()->json($optionname);
      }

      public function destroy(Request $request)
          {   //$districtID=$_POST['district'];


            $modulecode = request()->get('modulecode');
          //  dd($modulecode);
            $optioncode = request()->get('optioncode');
            $userid = request()->get('userid');
            $roleid = request()->get('roleid');
            if($modulecode== "null" && $optioncode== "null"){
              $cond1 ="modulecode is null and optioncode  is null ";

            }
            else{
             $cond1 = "modulecode='$modulecode' and optioncode='$optioncode'";
          }

            $valuesdeleted = DB::select("DELETE from userrole where userid='$userid' and roleid='$roleid'
              and ".$cond1." ");


              return response()->json($valuesdeleted);
          }

          public function findtablevaluesaccordingtouserid()
              {   //$districtID=$_POST['district'];

                $userid = request()->get('userid');

                $table = DB::select("SELECT ur.userid,ur.roleid,r.rolename,ur.modulecode,m.modulename,ur.optioncode,op.optionname from userrole as ur
        LEFT JOIN role as r on ur.roleid=r.roleid
        LEFT JOIN module as m on m.modulecode=ur.modulecode
        LEFT JOIN options as op on op.optioncode=ur.optioncode
        where ur.userid='$userid'
         order by ur.userid");


                  return response()->json($table);
              }



  }
