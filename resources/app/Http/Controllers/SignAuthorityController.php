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

    }
    public function SignAuthority(Request $request){
      // $data['purposecode']=DB::select("SELECT purposecode FROM listpurpose order by purposecode ASC");
         $establishcode=Session::get('EstablishCode');
         $data['signauthority']=DB::select("SELECT * FROM signauthority where establishcode ='$establishcode' order by idno");

        // $data['$department1']=DB::select("SELECT departmentcode from department oder ");
        // $data['department']=DB::select("SELECT departmentcode from department ");

          return view('Case.SignAuthority',$data);
    }
    public function SignAuthoritySave(Request $request)
    {
      if($request->input('sbmt_adv') == "A")
       {
            $request->validate([
               'name' => 'required',
            //   'userid' => 'required|unique:posts',
               'designation' => 'required',
               'departmentname' => 'required',
               'city' => 'required',
               'fromdate' => 'required',
               'todate' => 'required',
               'signdocument' => 'required',

              //  'password' => 'min:6|required_with:password_confirmation',
              //password_confirmation'=>'sometimes|required_with:password',

           ]);

            //  print_r($userrole);

              // Construct input data array

             // Match::all()->where('departmentcode', $request->all()['department']);

       //     $userSave['departmentcode']=DB::select(INSERT INTO department (departmentcode) values (102) );
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
      'name' => 'required',
   //   'userid' => 'required|unique:posts',
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
        // $userdetails->save();
         //DB::delete("delete from userrole where userid='".$userid."'");
        // DB::select('UPDATE department SET $departmenttype='depttypecode',$departmenttype='depttypecode',$department='departmentname',$departmentaddress='deptaddress',$district='deptdistrict',$taluk='depttaluk',
      //    DB::delete('delete from department where departmentid= :userid',['userid' => $userid]);

          // $phonenumber='deptphoneno',$mobilenumber='deptmobile' WHERE  $departmentcode='deptcode');

           //['roleid']  =  $row;
          // DB::table('department')->insert($userSave);
          DB::UPDATE("UPDATE signauthority SET name = '$name',deptname1 ='$departmentname',deptname2 = '$city',fromdate = '$fromdate',todate ='$todate',documenttype = '$signdocument',designation='$designation' WHERE idno = '$idno' ");
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


    public function getSignAuthorityDetails(Request $request)
   {
     $idno = $_POST['idno'];

    //$departmentcode=$deptcode->deptcode+1;
  //  echo $id;

      //print_r($departmentcode);
     //$userSave['departmentcode']= $departmentcode;
     // $data['userDetails'] = DB::select("select * from userdetails where userid ='".$userid."'");

     $data['userdetails'] = DB::select("SELECT * from signauthority where idno =$idno " );
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
