<?php
use App\Http\Controllers\Auth\URL;

use Spatie\Analytics\Analytics;

//use URL;
use App\users;
use App\userdetails as userdetails;
use Illuminate\Support\Facades\Auth;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\AddNewDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\District;
use App\Taluk;
class DepartmentController extends Controller
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

    public function AddNewDepartment(Request $request){
         $data['departmenttype']=DB::select("SELECT * FROM departmenttype order by depttypecode ASC");
         $data['district']=DB::select("SELECT * FROM district order by distcode ASC");
         $data['taluk']=DB::select("SELECT  * FROM taluk ORDER BY distcode ASC");
         $data['department']=DB::select("SELECT * FROM department order by departmentcode");
        // $data['$department1']=DB::select("SELECT departmentcode from department oder ");
        // $data['department']=DB::select("SELECT departmentcode from department ");

          return view('AddNewDepartment',$data);
    }

    public function AddNewDepartmentSave(Request $request){
       if($request->input('sbmt_adv') == "A")
        {
             $request->validate([
                'departmenttype' => 'required',
             //   'userid' => 'required|unique:posts',
                'department' => 'required',
                'departmentaddress' => 'required',
                'district' => 'required',
                'taluk' => 'required',
               //  'password' => 'min:6|required_with:password_confirmation',
               //password_confirmation'=>'sometimes|required_with:password',
                'phonenumber' => 'required|numeric|max:9999999999',
               'mobilenumber' => 'required|numeric|max:9999999999',

            ]);

             //  print_r($userrole);

               // Construct input data array

              // Match::all()->where('departmentcode', $request->all()['department']);

        //     $userSave['departmentcode']=DB::select(INSERT INTO department (departmentcode) values (102) );
             $userSave['depttypecode']       = $request->input('departmenttype');
             $userSave['departmentname']     = $request->input('department');
             $userSave['deptaddress']  = $request->input('departmentaddress');
             $userSave['deptdistrict']  = $request->input('district');
             $userSave['depttaluk']  = $request->input('taluk');
             $userSave['deptphoneno']  = $request->input('phonenumber');
             $userSave['deptmobile']     = $request->input('mobilenumber');


            DB::beginTransaction();
           $deptcode=DB::select("select max(departmentcode) as deptcode from department")[0];
//print_r($deptcode);
           $departmentcode=$deptcode->deptcode+1;

      //     print_r($departmentcode);
            $userSave['departmentcode']= $departmentcode;

            try {
              DB::table('department')->insert($userSave);
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
                   return back()->with('success', 'Department Added Into Database ');
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
           'departmenttype' => 'required',
           'department' => 'required',
           'departmentaddress' => 'required',
            'district' => 'required',
           'taluk' => 'required',
           'phonenumber' => 'required|numeric|max:9999999999',
           'mobilenumber' => 'required|numeric|max:9999999999',

       ]);



       $departmentcode = Input::post('departmentcode');
       $departmenttype = Input::post('departmenttype');
       $department = Input::post('department');
       $departmentaddress = Input::post('departmentaddress');
       $district = Input::post('district');
       $taluk = Input::post('taluk');
       $phonenumber = Input::post('phonenumber');
       $mobilenumber = Input::post('mobilenumber');

       DB::beginTransaction();

       try {
          // $userdetails->save();
           //DB::delete("delete from userrole where userid='".$userid."'");
          // DB::select('UPDATE department SET $departmenttype='depttypecode',$departmenttype='depttypecode',$department='departmentname',$departmentaddress='deptaddress',$district='deptdistrict',$taluk='depttaluk',
        //    DB::delete('delete from department where departmentid= :userid',['userid' => $userid]);

            // $phonenumber='deptphoneno',$mobilenumber='deptmobile' WHERE  $departmentcode='deptcode');

             //['roleid']  =  $row;
            // DB::table('department')->insert($userSave);
            DB::UPDATE("UPDATE department SET depttypecode = '$departmenttype',departmentname ='$department',deptaddress = '$departmentaddress',deptdistrict = '$district',depttaluk ='$taluk',deptphoneno = '$phonenumber',deptmobile = '$mobilenumber' WHERE departmentcode = '$departmentcode' ");
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
  public function getDepartmentDetails(Request $request)
  {
    // $deptcode=DB::select("select max(departmentcode) as deptcode from department")[0];
     $departmentcode = $_POST['departmentcode'];
    //$departmentcode=$deptcode->deptcode+1;


      //print_r($departmentcode);
     //$userSave['departmentcode']= $departmentcode;
     // $data['userDetails'] = DB::select("select * from userdetails where userid ='".$userid."'");

     $data['userdetails'] = DB::select("SELECT * from department where departmentcode =$departmentcode" );
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

  public function index()
     {
         $district = District::all();
         return view('AddNewDepartment',compact('district'));
     }
     public function destroy($id)
     {
         //
     }


  public function findDistrictWithTaluk($districtID )
      {   //$districtID=$_POST['district'];
          $taluk = DB::select("SELECT talukcode,talukname FROM taluk WHERE distcode='$districtID ' order by talukname");
          return response()->json($taluk);
      }



 }



?>
