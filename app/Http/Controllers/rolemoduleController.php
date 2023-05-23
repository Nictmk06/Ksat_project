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

class rolemoduleController extends Controller
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
    
public function rolemodule(Request $request){

      $data['module']=DB::select("SELECT modulecode,modulename from module where modulecode!=0 order by modulename ");
      $data['role']=DB::SELECT("SELECT roleid,rolename from role order by rolename");
      $data['table']=DB::SELECT("SELECT rm.roleid,r.rolename,rm.modulecode,m.modulename,rm.optioncode,o.optionname from rolemodule as rm
INNER JOIN role as r on rm.roleid=r.roleid
LEFT JOIN module as m on rm.modulecode=m.modulecode
LEFT JOIN options as o on rm.optioncode=o.optioncode
order by r.rolename,m.modulename,rm.optioncode");
      return view('master.rolemodule',$data);
}


    public function rolomoduleSave(Request $request){

             $request->validate([

               'roleid' => 'required',
               'modulename' => 'required',
               'optionname' => 'required'

            ]);


             $userSave['roleid']     = $request->input('roleid');
             $userSave['modulecode']  = $request->input('modulename');
             $userSave['optioncode']  = $request->input('optionname');



            DB::beginTransaction();

            try {
              DB::table('rolemodule')->insert($userSave);
                DB::commit();
                   return back()->with('success', 'New Option  alloted  ');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                  return back()->with('error', 'Someting wrong,  New Option not alloted !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
              return back()->with('error', 'Someting wrong,New  Option  not alloted !!');
            }
  }

  public function getrolemodule(Request $request)
  {
    $data['userdetails'] = DB::select("SELECT * from rolemodule " );
    echo json_encode($data['userdetails']);

  }

  public function findOptionsWithModule()
      {
          $moduleid = request()->get('moduleid');
          $roleid = request()->get('roleid');
    //     $optionname=DB::select("SELECT optioncode,optionname from options where (modulecode='$moduleid' or optioncode=0) and optioncode not in
    // (select optioncode from rolemodule where roleid='$roleid'and modulecode='$moduleid')  order by optionname	");
  $optionname=DB::SELECT("With Test
			as
			(
			  Select roleid, modulecode, optioncode from rolemodule where (roleid='$roleid' and modulecode='$moduleid')
			  union all
			  Select roleid, RM.modulecode, 0 from rolemodule RM
			  Where RM.optioncode!=0 and  roleid='$roleid' and modulecode='$moduleid'
			  union all
			  Select RM.roleid,RM.modulecode,O.optioncode
			  from options O
			  inner join
			  rolemodule RM on 1=(case when RM.optioncode=0 then 1 else 0 end)
			  and   O.modulecode=RM.modulecode and  roleid='$roleid' and rm.modulecode='$moduleid'


			)
      Select optioncode,OptionName from options O
			where optioncode not in (Select optioncode from test t where (t.modulecode=o.modulecode
			or (t.optioncode='0' and o.modulecode=0 ))) and
			(o.modulecode='$moduleid' or (o.optioncode='0' and o.modulecode =0)) order by optionname");
return response()->json($optionname);
}

      public function destroy()
          {   //$districtID=$_POST['district'];
            $roleid = request()->get('roleid');
            $modulecode = request()->get('modulecode');
            $optioncode = request()->get('optioncode');

            $valuesdeleted = DB::select("DELETE from rolemodule where  roleid='$roleid' and  modulecode='$modulecode' and optioncode='$optioncode'  ");


              return response()->json($valuesdeleted);
          }

          public function findtablevaluesaccordingtoroleid()
              {
                $roleid = request()->get('roleid');
                $table = DB::select("SELECT rm.roleid,r.rolename,rm.modulecode,m.modulename,rm.optioncode,o.optionname from rolemodule as rm
INNER JOIN role as r on rm.roleid=r.roleid
LEFT JOIN module as m on rm.modulecode=m.modulecode
LEFT JOIN options as o on rm.optioncode=o.optioncode
where rm.roleid='$roleid' order by r.rolename,m.modulename,rm.optioncode");
return response()->json($table);
              }

      public function  AddRoleSave(Request $request)
      {
         $data['roleid'] = $request->get('rolecode');

         $data['rolename'] = $request->get('rolename');

        $roleadded =DB::table('role')->insert( $data);

       return redirect('rolemodule');


      }
  }
