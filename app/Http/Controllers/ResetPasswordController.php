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
          //  print_r(1);
          $username=$request->session()->get('username');

          //$re = $request->input('userName');
          $oldPassword = md5($request->input('oldPassword'));
          $newPassword1 =md5($request->input('newPassword1'));
          $newPassword2 = md5($request->input('newPassword2'));
          if($request->input('sbmt_adv') == "A")
          {
               $request->validate([

                  'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',

              ]);
           }

               //  'password' => 'min:6|required_with:password_confirmation',
               //password_confirmation'=>'sometimes|required_with:password',


          $data['userAppDet'] = DB::select("SELECT password FROM userpassword where password='$oldPassword' AND userid='$username'");

          //print_r($data['userAppDet']);


          if (empty($data['userAppDet'])) {
          //  $message = "Incorrect Password!!!";
        //    echo "<script type='text/javascript'>alert('$message');</script>";
          //  header("Location : URL::route('changePassword')");
          //  return redirect()->back()->withErrors('incorrect password');
           return back()->with('error', 'Incorrect Password');
          }
          $num=$data['userAppDet'];
          //print_r($num);
        //  print_r($num[0]->password);
       if($newPassword1!= $newPassword2){
        // $message = "New Password and Confirm password does not match!!!";
        //   echo "<script type='text/javascript'>alert('$message');</script>";
        //    return view('auth.changePassword');
        return back()->with('error', 'New Password and Confirm Password does not match');

       }

        if($oldPassword == $num[0]->password)
         {
           $data['userAppDet'] = DB::select("UPDATE userpassword set password='$newPassword1' where userid='$username'");
        //   $message = "Password Updated !!!";
        //   echo "<script type='text/javascript'>alert('$message');</script>";
        //    return view('auth.changePassword');
        return back()->with('error', 'Password Updated Successfully');

        }




        }

    public function changePassword(){

          return view('auth.changePassword');
    }
}
