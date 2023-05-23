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

    }
    public function listpurpose(Request $request){
      // $data['purposecode']=DB::select("SELECT purposecode FROM listpurpose order by purposecode ASC");

         $data['listorder']=DB::select("SELECT * FROM listpurpose order by purposecode");
        // $data['$department1']=DB::select("SELECT departmentcode from department oder ");
        // $data['department']=DB::select("SELECT departmentcode from department ");

          return view('case.listpurpose',$data);
    }
    public function ListPurposeSave(Request $request){
       if($request->input('sbmt_adv') == "A")
        {
             $request->validate([
               'purposecode' => 'required',
               'listpurpose' => 'required',
               'listorder' => 'required',

            ]);

             //  print_r($userrole);

               // Construct input data array

              // Match::all()->where('departmentcode', $request->all()['department']);

        //     $userSave['departmentcode']=DB::select(INSERT INTO department (departmentcode) values (102) );
             $userSave['purposecode']       = $request->input('purposecode');
             $userSave['listpurpose']     = $request->input('listpurpose');
             $userSave['listorder']  = $request->input('listorder');



            DB::beginTransaction();
           $purposecode=DB::select("select max(purposecode) as purposecode from listpurpose")[0];
//print_r($deptcode);
           $prposecode=$purposecode->purposecode+1;

      //     print_r($departmentcode);
            $userSave['purposecode']= $prposecode;

            try {
              DB::table('listpurpose')->insert($userSave);
            //    DB::table('department')->insert($userPassword);
          //    foreach($_POST["userrole"] as $row)
              /* foreach($request->input('userrole') as $row)
              {
                $userrole['userid']  =  $request->input('userid');
                $userrole['roleid']  =  $row;
                DB::table('userrole')->insert($userrole);
               }
*/
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
           'purposecode' => 'required',
           'listpurpose' => 'required',
           'listorder' => 'required',

       ]);



       $purposecode = Input::post('purposecode');
       $listpurpose = Input::post('listpurpose');
       $listorder = Input::post('listorder');


       DB::beginTransaction();

       try {
          // $userdetails->save();
           //DB::delete("delete from userrole where userid='".$userid."'");
          // DB::select('UPDATE department SET $departmenttype='depttypecode',$departmenttype='depttypecode',$department='departmentname',$departmentaddress='deptaddress',$district='deptdistrict',$taluk='depttaluk',
        //    DB::delete('delete from department where departmentid= :userid',['userid' => $userid]);

            // $phonenumber='deptphoneno',$mobilenumber='deptmobile' WHERE  $departmentcode='deptcode');

             //['roleid']  =  $row;
            // DB::table('department')->insert($userSave);
            DB::UPDATE("UPDATE listpurpose SET listpurpose ='$listpurpose',listorder = '$listorder' WHERE purposecode = '$purposecode' ");
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
    // $deptcode=DB::select("select max(departmentcode) as deptcode from department")[0];
    $listpurposecode = $_GET['purposecode'];
    //$departmentcode=$deptcode->deptcode+1;


      //print_r($departmentcode);
     //$userSave['departmentcode']= $departmentcode;
     // $data['userDetails'] = DB::select("select * from userdetails where userid ='".$userid."'");

    $data['userdetails'] = DB::select("SELECT * from listpurpose where purposecode =$listpurposecode" );
    //  $records = DB::select("select * from department where departmentcode =:departmentcode", ['departmentcode' =>$departmentcode]);
         // print_r($records);
    //  $role1="";
    //  foreach($records as $role)
      //    {
        //       $role1  .= $role->roleid.',';
        //  }
      //$role1 = substr_replace($role1 ,"",-1);
       // print_r($role1);
      //  $data['department']=$role1;
    //echo json_encode($data['userdetails']);
   //echo json_encode(array($data['getDepartmentDetails'], $role1));

    echo json_encode($data['userdetails']);

  }

  }
