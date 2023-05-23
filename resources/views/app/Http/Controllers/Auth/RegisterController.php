<?php

namespace App\Http\Controllers\Auth;
use App\UserActivityModel;
use App\User;
use App\userdetails as userdetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }




    public function createUser(Request $request)
    { 
        $establishcode = $request->session()->get('EstablishCode');  
        $data['userDetails'] = DB::select("select * from userdetails where enableuser ='Yes' or enableuser is null and establishcode = $establishcode");
        $data['designation']  = DB::table('userdesignation')->select('*')->orderby('userdesigcode')->get();
        $data['userSection'] = DB::table('usersection')->select('*')->orderby('userseccode')->get();
        $data['courthall'] = DB::table('courthall')->select('*')->orderby('courthallno')->get();
        $data['establishment'] = DB::table('establishment')->select('*')->orderby('establishcode')->get();
        $data['role'] = DB::table('role')->select('*')->orderby('roleid')->get();
        return view('auth.createUser', $data);
          
    }

    public function saveNewUser(Request $request){
       if($request->input('sbmt_adv') == "A")
        {  
             $request->validate([
                //'userid' => 'required',
               'userid' => 'required|unique:userdetails,userid',
                'userName' => 'required',
                'designation' => 'required',
                'userSection' => 'required',
                'establishment' => 'required', 
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
               //  'password' => 'min:6|required_with:password_confirmation',
               //password_confirmation'=>'sometimes|required_with:password',
                'mobileno' => 'required|numeric|max:9999999999',
                'email' => 'required',
                'enableuser' => 'required'          
            ]);  
          
             //  print_r($userrole);
             $establishcode = $request->session()->get('EstablishCode');
               // Construct input data array
             $userSave['userid']       = $request->input('userid');
             $userSave['username']     = $request->input('userName');
             $userSave['userdesigcode']  = $request->input('designation');
             $userSave['sectioncode']  = $request->input('userSection');
             $userSave['courthallno']  = $request->input('courtHallNo');
             $userSave['userlevel']  = $request->input('userLevel');
             $userSave['mobileno']     = $request->input('mobileno');
             $userSave['enableuser'] = $request->input('enableuser');
             $userSave['useremail'] = $request->input('email');
             $userSave['establishcode'] =  $request->input('establishment');
             $userSave['createdon']    = date('Y-m-d H:i:s') ;
             $userSave['createdby']    = $request->session()->get('username');

             $userPassword['userid']   = $request->input('userid');
            // $userPassword['password'] = md5($request->input('password'));
             $userPassword['password']  =  Hash::make($_POST['password']);
             //print_r(Hash::make($_POST['password']));
            
			$useractivitydtls['applicationid_receiptno'] = $request->input('userid');
		    $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			$useractivitydtls['activity'] ='Create New User' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			
            try {
                DB::beginTransaction();
                DB::table('userdetails')->insert($userSave);    
                DB::table('userpassword')->insert($userPassword); 
              // foreach($_POST["userrole"] as $row)
                foreach($request->input('userrole') as $row)
                {
                  $userrole['userid']  =  $request->input('userid');
                  $userrole['roleid']  =  $row;
                  DB::table('userrole')->insert($userrole);
                 }

				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		        DB::commit();
                 return redirect()->route('createUser')->with('success', 'User Created successfully');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('createUser')->with('error', 'Someting wrong, User  not saved !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('createUser')->with('error', 'Someting wrong, User  not saved !!');
            }
        }
        else if($request->input('sbmt_adv') == "U"){
         $request->validate([
                'userid' => 'required',
                'userName' => 'required',
                'designation' => 'required',
                'userSection' => 'required',
                'mobileno' => 'required|numeric|max:9999999999',
                'email' => 'required',
                'enableuser' => 'required'          
            ]);  
            $userDetails = userdetails::find($request->get('userid'));
            $userDetails->username =$request->input('userName');
            $userDetails->userdesigcode=$request->input('designation');
            $userDetails->sectioncode=$request->input('userSection');
            $userDetails->courthallno=$request->input('courtHallNo');
            $userDetails->userlevel=$request->input('userLevel');
            $userDetails->mobileno=$request->input('mobileno');
            $userDetails->useremail=$request->input('email');
            $userDetails->establishcode=$request->input('establishment');
            $userDetails->enableuser=$request->input('enableuser');
        
            $userDetails->updatedon=date('Y-m-d H:i:s') ;
            $userDetails->updatedby=$request->session()->get('username');
           
            $userid = $request->get('userid');
          
		    $useractivitydtls['applicationid_receiptno'] = $request->input('userid');
		    $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			$useractivitydtls['activity'] ='Update User Details' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
            DB::beginTransaction();

            try {
                $userDetails->save();
                //DB::delete("delete from userrole where userid='".$userid."'");
 
                DB::delete('delete from userrole where userid= :userid',['userid' => $userid]);

                foreach($request->input('userrole') as $row)
                {
                  $userrole['userid']  =  $request->input('userid');
                  $userrole['roleid']  =  $row;
                  DB::table('userrole')->insert($userrole);
                 }
				$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                DB::commit();
                return  redirect()->route('createUser')->with('success', 'User Updated successfully');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
               return redirect()->route('createUser')->with('error', 'Someting wrong, User not Updated !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return redirect()->route('createUser')->with('error', 'Someting wrong, User not Updated !!');
            }
        }     
    }

    public function getUserDetails(Request $request)
    {
	   $request->validate([
		'userid' => array(
						'required',
						'regex:/^[0-9a-zA-Z_]+$/',
						'max:20'
					),                 
              ]);
       $userid = $_POST['userid'];
       $data['userDetails'] = DB::select("select * from userdetails where userid = :userid", ['userid' =>$userid]);$records = DB::select("select * from userrole where userid =:userid", ['userid' =>$userid]);
       $role1="";
        foreach($records as $role)
            {
                 $role1  .= $role->roleid.',';
            }
        $role1 = substr_replace($role1 ,"",-1);
        $data['userRole']=$role1;
       echo json_encode(array($data['userDetails'], $role1)); 
      }
}
