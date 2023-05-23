<?php
use App\Http\Controllers\Auth\URL;

use Spatie\Analytics\Analytics;

//use URL;
use Illuminate\Support\Facades\Auth;
namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ResetPasswordController extends Controller
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

    use ResetsPasswords;

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

    public function changePasswordSave(Request $request){
		
	 $request->validate([
    	'oldPassword' => 'required',
		'newPassword1' => 'required',
		'newPassword2' => 'required',
        ]);
           $username=$request->session()->get('username');
          //$oldPassword = md5($request->input('oldPassword'));
         // $newPassword1 =md5($request->input('newPassword1'));
          //$newPassword2 = md5($request->input('newPassword2'));
        if($_POST['newPassword1']!= $_POST['newPassword2']){
              return back()->with('error', 'New Password and Confirm Password does not match');
          }
           $oldPassword  =  Hash::make($_POST['oldPassword']);
           $newPassword1  =  Hash::make($_POST['newPassword1']);
          $data['userAppDet'] = DB::select("SELECT * FROM userpassword where  userid='$username'");
           //  $data['userAppDet'] = DB::select("SELECT * FROM userpassword where  userid='miniaa'");

         // if (empty($data['userAppDet'])) {
         //     return back()->with('error', 'Incorrect current Password');
         //  }
         // $flag = Hash::check($data['userAppDet'][0]->password, $oldPassword);
        //if($oldPassword == $num[0]->password)
        // print_r($data['userAppDet'][0]->password);
          if (!Hash::check($_POST['oldPassword'], $data['userAppDet'][0]->password)) 
         {
           return back()->with('error', 'Incorrect current Password');
         }else{
           $data['userAppDet'] = DB::select("UPDATE userpassword set password='$newPassword1' where userid='$username'");
            return back()->with('success', 'Password Updated Successfully');

        }

        }

    public function changePassword(){

          return view('auth.changePassword');
    }
}
