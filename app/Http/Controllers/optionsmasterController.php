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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class optionsmasterController extends Controller
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

    public function optionsmaster(Request $request){
         $data['modulename']=DB::select("SELECT modulecode,modulename from module order by modulecode");
         $data['options']=DB::select("SELECT o.optioncode,o.optionname,o.modulecode,m.modulename,o.linkname,o.helptext,o.optionorder
,o.subtitle from options as o
INNER JOIN module as m on o.modulecode=m.modulecode
order by o.optioncode,o.modulecode");
          return view('master.optionsmaster',$data);
    }

    public function optionsmasterSave(Request $request){
       if($request->input('sbmt_adv') == "A")
        {
             $request->validate([
                'optioncode' => 'required',
                'modulename' => 'required',
                'optionname' => 'required',
                'linkname' => 'required',
            ]);


             $userSave['optioncode']       = $request->input('optioncode');
             $userSave['modulecode']     = $request->input('modulename');
             $userSave['optionname']  = $request->input('optionname');
             $userSave['linkname']  = $request->input('linkname');
             $userSave['optionorder']  = $request->input('optionorder');
             $userSave['helptext']  = $request->input('helptext');
             $userSave['subtitle']     = $request->input('subtitle');


            DB::beginTransaction();

            try {
              DB::table('options')->insert($userSave);

                DB::commit();
                   return back()->with('success', 'Option values Added Into Database ');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
                  return back()->with('error', 'Someting wrong, Option values  not added !!');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
              return back()->with('error', 'Someting wrong, Option values  not added !!');
            }
        }





         else if($request->input('sbmt_adv') == "U"){
      $request->validate([
        'optioncode' => 'required',
        'modulename' => 'required',
        'optionname' => 'required',
        'linkname' => 'required',

       ]);



       $optioncode = Input::post('optioncode');
       $modulename = Input::post('modulename');
       $optionname = Input::post('optionname');
       $linkname = Input::post('linkname');
       $optionorder = Input::post('optionorder');


       $helptext = Input::post('helptext');
       $subtitle = Input::post('subtitle');


       DB::beginTransaction();

       try {
            DB::UPDATE("UPDATE options SET modulecode ='$modulename',optionname = '$optionname',linkname = '$linkname',optionorder =NULLIF('$optionorder', '')::int,helptext = '$helptext',subtitle = '$subtitle' WHERE optioncode = '$optioncode' ");
           DB::commit();
           return back()->with('success', 'Updated Successfully !!');
       } catch (\Exception $e) {
           DB::rollback();
           throw $e;
           return back()->with('error', 'Someting wrong, Option values not Updated !!');
       } catch (\Throwable $e) {
           DB::rollback();
           throw $e;
           return back()->with('error', 'Someting wrong, Option values not Updated !!');
       }
   }

  }
  public function getoptionsmasterdetails(Request $request)
  {
     $optioncode = $_GET['optioncode'];
     $data['userdetails'] = DB::select("SELECT o.optioncode,o.optionname,o.modulecode,m.modulename,o.linkname,o.helptext,o.optionorder
,o.subtitle from options as o
INNER JOIN module as m on o.modulecode=m.modulecode
where o.optioncode='$optioncode'" );


     echo json_encode($data['userdetails']);

  }





 }



?>
