<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\CaseManagementModel;
use App\UserLoginHistoryModel;

use Illuminate\Http\Request;
use Session;
use App\User;//use model User
use App\IANature;
use App\ModuleAndOptions;//use model Module & Options
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
       $this->case = new CaseManagementModel();
        $this->IANature = new IANature();
         $this->UserLoginHistoryModel = new UserLoginHistoryModel();
    }
    public function create()
    {
        return view('logins.dashboard');
    }
    public function verifyLogin(Request $request)
    {
		$request->validate([
		'userName' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
	 				), 
	    'userPassword' => 'required',					
              ]);
        //$password = password_hash($_POST['userPassword'], PASSWORD_BCRYPT);
        $password =  $_POST['userPassword'];
       // $password = md5($request->input('userPassword'));
        $username = $request->input('userName');
        $establishcode =  $request->input('establishment');


        $users = new User();
        $userAppDet = DB::select("SELECT * FROM userpassword where userid='$username'");
         if($userAppDet)
        {
		if (!Hash::check($password, $userAppDet[0]->password)) 
         {
           return back()->withErrors(['failure' => ['Invalid Username or Password']]);
         }else{
			 
            $modules = new ModuleAndOptions();
           
           $data['userAppDet'] = DB::select('select * from usercount1'); 
           $data['establishname'] = $this->case->getEstablishName($establishcode);
            $data['title'] ='Dashboard';
            $establishment = $this->case->getEstablishName($establishcode)[0];
            $establishname = $data['establishname'][0]->establishfullname;
            $userdtls = $users->getUserData($username,$establishcode);
            $userlevel = $userdtls[0]->userlevel;
            //print_r($userlevel);
             session()->put('username',$username);
             session()->put('userName',$username);  // to be removed.. it is used many places in form
             session()->put('EstablishCode',$establishcode);
             session()->put('establishment',$establishment);
             session()->put('userlevel',$userlevel);
             session()->put('establishfullname',$establishname);
             session::put('option', $modules->usermenu($username));
             session::put('menu',$modules->usermenumodule($username));

			 $data['userAppDet'] = DB::select('select * from usercount1');
			 $data['applicationcnt']  = DB::select('select appltypedesc,appltypecode,sum(applicationcnt) as applicationcnt,sum(applicantcnt) as applicantcnt from applicationcount group by appltypedesc,appltypecode order by appltypecode');

			$data['pendingapplcnt']  = DB::select('select appltypedesc,appltypecode,sum(applicationcnt) as applicationcnt,sum(applicantcnt) as applicantcnt from applicationcount where statuscode = 1 or statuscode is null group by appltypedesc,appltypecode order by appltypecode');

           $data['title']='Dashboard';

        try{
          $userlogindtls['userid'] = $username;
          $userlogindtls['ipaddress'] =$_SERVER['REMOTE_ADDR'];
           //$this->get_client_ip();
          $userlogindtls['logintime'] = date('Y-m-d H:i:s') ;
          $userlogindtls['establishcode'] = $establishcode;
        // $userdtls['sessionid'] = session()->getId();
         $value = $this->UserLoginHistoryModel->insertUserLoginDtls($userlogindtls);
         session()->put('usersessionid',$value);

           //print_r($value);
            return view('dashboard.dashboardmain',$data);
            }catch(\Exception $e){
                
                }

          
		 }   }
  }

   public static function get_client_ip()
 {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
          $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';

      return $ipaddress;
 }



 public function logout(Request $request)
{
    //print_r("fgvfg");
   
    $userlogoutdtls['logouttime'] = date('Y-m-d H:i:s');
    $usersessionid=$request->session()->get('usersessionid');
    $value = $this->UserLoginHistoryModel->updateUserLoginDtls($userlogoutdtls,$usersessionid);
    $this->guard()->logout();
    $request->session()->invalidate();
   return $this->loggedOut($request) ?: redirect('/');
}
     /*public function show($slug,Request $request)
    {
     
        if($slug=="logout")
        {
            return redirect('/');
        }
          $modules = new ModuleAndOptions();
          $data['modules_options'] =  $modules->getModulesAndOtions();
          if($slug)
          {

            $page = $slug;
            $modules = new ModuleAndOptions();
            $data['modules_options'] =  $modules->getModulesAndOtions();
            $data['actDetails'] = $this->case->getActDetails();
            $data['sectionDetails'] = $this->case->getSectionDetails();
            $data['applicationType'] = $this->case->getApplType();
            $data['applCategory'] = $this->case->getApplCategory();
            $data['district'] = $this->case->getDistrict();
            $data['taluka'] = $this->case->getTaluka($distCode='');
            $data['nameTitle'] = $this->case->getNameTitle();
            $data['relationTitle'] = $this->case->getRelationTitle();
            $data['deptType'] = $this->case->getDeptType();
            $data['deptName'] = $this->case->getDeptName();
            $data['adv']=$this->case->getAdv();
            $user = $request->session()->get('userName');
            $data['Temp'] = $this->case->getApplicationId($user);
           
            $data['Temprelief'] = $this->case->getRelief();
            $data['TempReceipt'] = $this->case->getReceiptDetails();
            $data['TempApplicant'] = $this->case->getApplicantDetails();
            $data['TempRespondant'] = $this->case->getRespondantDetails();
            $data['nameTitle'] = $this->case->getNameTitle();

            $data['IANature'] =  $this->IANature->getIANature();
             return view('Case.'.$slug,$data)->with('user',$request->session()->get('userName'));
          }
          else
          {
            $data['title'] = '404';
             $user = $request->session()->get('userName');
            return view('pagenotfound',$data)->with('user',$request->session()->get('userName'));
          }
    }*/
    public function getTaluk(Request $request)
    {   
    	$request->validate([
   	     'distCode' => 'required|numeric',
             ]);		
        $distCode = $request->input('distCode');
       $data['getTaluk'] = $this->case->getTaluka($distCode);
       echo json_encode($data['getTaluk']);
    }

}
