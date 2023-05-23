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
use App\UserActivityModel;
use App\Services\PayUService\Exception;
class AddAdvocateDetailsController extends Controller
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
		$this->UserActivityModel = new UserActivityModel();
    }
    public function AddAdvocateDetails(Request $request){
       $establishcode=Session::get('EstablishCode');
        $data['advocatedesignation']=DB::select("SELECT * from advocatedesignation order by advdesigcode");
         $data['advocatetype']=DB::select("SELECT * from advocatetype order by advocatetypecode");
        $data['district']=DB::SELECT("SELECT * from district order by distname");
        $data['district1']=DB::SELECT("SELECT * from district order by distname");

        $data['state']=DB::SELECT("SELECT * from state");
        $data['state1']=DB::SELECT("SELECT * from state ");
        //$data['taluk2']=DB::SELECT("SELECT * from taluk order by talukname");
        $data['taluk1']=DB::SELECT("SELECT * from taluk order by talukname");
        $data['t']=DB::SELECT("SELECT * from taluk");



         return view('master.AddAdvocateDetails',$data);
    }
    public function AddAdvocateSave(Request $request)
    {
      if($request->input('sbmt_adv') == "A")
       {

            $request->validate([
              'advocatecode' => 'required',
              'advocateregno' => 'required|regex:/([a-zA-Z]{3})\/([0-9]{1,6})\/([0-9]{4})/',
              'name' => 'required',
              'advocatetype' => 'required',
              ]);
          $userSave['createdby'] = $request->session()->get('username');
          $userSave['createdon'] = date('Y-m-d H:i:s');
          $userSave['advocatecode']       = $request->input('advocatecode');
          $userSave['advocateregno']       = $request->input('advocateregno');
         $advocateregno   =  $request->input('advocateregno');
          $status= DB::SELECT("SELECT * from advocate where advocateregno='$advocateregno'");
          if($status){
             $msg="Advocate Registration Number: ". $advocateregno ." already entered.";
            return back()->with('error',$msg);

          }
          $userSave['advocatename']       = $request->input('name');
          $userSave['advocatetypecode']     = $request->input('advocatetype');
          $userSave['advocatedob']= $request->advocatedob !== null ? date('Y-m-d', strtotime($request->advocatedob)) : null;
          $userSave['advocateemail']     = $request->input('advocateemail');
          $userSave['advdesigcode']     = $request->input('designation');
          $userSave['advocatemobile']     = $request->input('advocatemobileno');
          $userSave['advocatephone']     = $request->input('advoatephone');
          $userSave['advocatephone1']     = $request->input('advoatephone1');
          $userSave['advocateaddress']     = $request->input('officeaddress');
          $userSave['distcode']     = $request->input('officedistrict');
          $userSave['talukcode']     = $request->input('officetaluk');
          $userSave['statecode']     = $request->input('officestate');
          $userSave['pincode']     = $request->input('officepincode');
          $userSave['advresaddress']     = $request->input('residentaddress');
          $userSave['advresdistcode']     = $request->input('residentdistrict');
          $userSave['advrestalukcode']     = $request->input('residenttaluk');
          $userSave['advstatecode']     = $request->input('residentstate');
          $userSave['advrespincode']     = $request->input('residentpincode');
       DB::beginTransaction();
            try
            {
            DB::table('advocate')->insert($userSave);

				     /*$useractivitydtls['applicationid_receiptno'] = $request->input('judgecode');
		         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						 $useractivitydtls['activity'] ='Add Judge Detail' ;
						 $useractivitydtls['createdby'] = $request->session()->get('username');
						 $useractivitydtls['advestablishment'] = $request->session()->get('EstablishCode');
						// $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid'); */

              DB::commit();
                  return back()->with('success', 'Advocate Information Added Successfully');
           } catch (\Exception $e) {
               DB::rollback();
               throw $e;
                 return back()->with('error', 'Someting wrong,  Advocate Information Not Added  !!');
           } catch (\Throwable $e) {
               DB::rollback();
               throw $e;
             return back()->with('error', 'Someting wrong,Advocate Information Not Added !!');
           }
       }
       else
       {

         $request->validate([
           'advocatecode' => 'required',
           'advocateregno' => 'required|regex:/([a-zA-Z]{3})\/([0-9]{1,6})\/([0-9]{4})/',
         //   'advocateregno' => 'required|regex:/([a-zA-Z]{3})\/([0-9]{4,6})\/([0-9]{4})/',

           'name' => 'required',
           'advocatetype' => 'required',
           ]);

       $advocatecode     = $request->input('advocatecode');
       $advocateregno      = $request->input('advocateregno');

       $advocatename      = $request->input('name');
       $advocatetypecode   = $request->input('advocatetype');
       $advocatedob= $request->advocatedob !== null ? date('Y-m-d', strtotime($request->advocatedob)) : null;
       $advocateemail    = $request->input('advocateemail');
       $advdesigcode    = $request->input('designation');
       $advocatemobile  = $request->input('advocatemobileno');
       $advocatephone    = $request->input('advoatephone');
       $advocatephone1    = $request->input('advoatephone1');
       $advocateaddress  = $request->input('officeaddress');
       $distcode   = $request->input('officedistrict');
       $talukcode    = $request->input('officetaluk');
       $statecode    = $request->input('officestate');
       $pincode    = $request->input('officepincode');
       $advresaddress   = $request->input('residentaddress');
       $advresdistcode  = $request->input('residentdistrict');
       $advrestalukcode   = $request->input('residenttaluk');
       $advstatecode    = $request->input('residentstate');
       $advrespincode  = $request->input('residentpincode');
       DB::beginTransaction();
         try
         {

         DB::SELECT("UPDATE advocate set advocatename='$advocatename',advocateregno='$advocateregno',advocatetypecode='$advocatetypecode',advocatedob=NULLIF('$advocatedob', '')::date,advocateemail='$advocateemail',advdesigcode=NULLIF('$advdesigcode', '')::int,
         advocatemobile='$advocatemobile',advocatephone='$advocatephone',advocatephone1='$advocatephone1',advocateaddress='$advocateaddress',distcode=NULLIF('$distcode', '')::smallint,talukcode=NULLIF('$talukcode', '')::smallint,statecode=NULLIF('$statecode', '')::smallint,pincode='$pincode',advresaddress='$advresaddress',
         advresdistcode=NULLIF('$advresdistcode', '')::smallint,advrestalukcode=NULLIF('$advrestalukcode', '')::smallint,advstatecode=NULLIF('$advstatecode', '')::int,advrespincode=NULLIF('$advrespincode', '')::smallint where advocatecode='$advocatecode'");

          /*$useractivitydtls['applicationid_receiptno'] = $request->input('judgecode');
          $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
          $useractivitydtls['activity'] ='Add Judge Detail' ;
          $useractivitydtls['createdby'] = $request->session()->get('username');
          $useractivitydtls['advestablishment'] = $request->session()->get('EstablishCode');
         // $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid'); */

           DB::commit();
               return back()->with('success', 'Advocate Information Updated Successfully');
        }catch (\Throwable $e){
         dd($e->getMessage());
        $error_code = $e->getCode();
        if($error_code == 23505){
          $msg="Advocate Registration Number: ". $advocateregno ." already exist. Please enter advocate Registration Number correctly";
          return back()->with('error',$msg);
        } }catch (\Throwable $e) {
            DB::rollback();
            throw $e;
          return back()->with('error', 'Someting wrong,Advocate Information Not Added!!');
        }

       }
}



public function findDistrict_Taluk($districtcode)
  {
        $taluk = DB::select("SELECT talukcode,talukname FROM taluk WHERE distcode='$districtcode' order by talukname");


        return response()->json($taluk);
    }


public function findDistrict_Taluk_resident($districtcode)
  {
        $taluk = DB::select("SELECT talukcode,talukname FROM taluk WHERE distcode='$districtcode' order by talukname");


        return response()->json($taluk);
    }

    public function getApplBasedOnID(Request $request)
    {
        $advocateregno = $_GET['advocateregno'];
      $advocatedetails =DB::SELECT("SELECT * from advocate where advocateregno='$advocateregno'");


            if(count($advocatedetails)==0)
            {
               return response()->json([
            'status' => 'success',
            'message'=>'Advocate Details Does Not Exist']);
            }

         else{
           echo json_encode($advocatedetails);

            }

    }



  }
