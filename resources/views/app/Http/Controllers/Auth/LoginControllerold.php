<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\CaseManagementModel;
use Illuminate\Http\Request;
use Session;
use App\User;//use model User
use App\IANature;
use App\ModuleAndOptions;//use model Module & Options
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Support\Facades\DB;
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
       $this->module= new ModuleAndOptions();
        $this->IANature = new IANature();
    }
    public function create()
    {
        return view('logins.dashboard');
    }
    public function verifyLogin(Request $request)
    {
        $password = md5($request->input('userPassword'));
        $username = $request->input('userName');

        $establishCode =  $request->input('establishment');


        $users = new User();
        $verify_result = $users->verifyLoginDetails($password,$username,$establishCode);

        if($verify_result)
        {
            $modules = new ModuleAndOptions();
            $data['userName'] = $users->getUserName($password,$username,$establishCode);

           // $data['modules_options'] =  $modules->getModulesAndOtions();
           //return 
            $data['userAppDet'] = DB::select('select * from usercount1');
             $request->session()->put('userName',$username);
             $request->session()->put('EstablishCode',$establishCode);
             $establishcode = $request->session()->get('EstablishCode');
            $data['establishname'] = $this->case->getEstablishName($establishcode);
            $establishname = $data['establishname'][0]->establishfullname;
             $request->session()->put('establishfullname',$establishname);
              $data['options'] = $this->module->getOptions();
            $data['Modules'] = $this->module->getModules();
              //$request->session()->put('EstablishCode',$establishCode);
             $data['title'] ='Dashboard';

            return view('dashboard',$data)->with('user',$request->session()->get('userName'));
        }
        else
        {
            //Redirect::back()->withErrors(['msg', 'Invalid Username or Password']);
            return back()->withErrors(['failure' => ['Invalid Username or Password']]);
        }

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
        $distCode = $request->input('distCode');
       $data['getTaluk'] = $this->case->getTaluka($distCode);
       echo json_encode($data['getTaluk']);
    }

}
