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

class QueryBoxController extends Controller
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
    public function querybox(Request $request){
      // $data['purposecode']=DB::select("SELECT purposecode FROM listpurpose order by purposecode ASC");
         $establishcode=Session::get('EstablishCode');


        // $data['$department1']=DB::select("SELECT departmentcode from department oder ");
        // $data['department']=DB::select("SELECT departmentcode from department ");

          return view('master.querybox');
    }
    public function queryboxfunction(Request $request)
    {
      $establishcode=Session::get('EstablishCode');
      $establishmentname=Session::get('establishfullname');

      $query=$request->get('query');

      $statement=(explode(" ",$query));
      $statement=strtoupper($statement[0]);



      switch($statement){
        case "SELECT":

             $temp=DB::select("CREATE TEMP TABLE  mytable AS "." $query");

             $resultheading=DB::select("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'mytable'");
             $resultdata=DB::select("$query");

            //$result= json_encode($resultdata);
            $temp=DB::select("DROP TABLE  mytable");

          // print_r($result);


             //$data=json_encode($result);
             //i = pg_num_fields($data);
          //   $columns=pg_field_name($data,$i);
             return view('master.queryboxReport', ['resultheading'=>$resultheading],['resultdata'=>$resultdata,'establishmentname'=>$establishmentname,'query'=>$query]);
             break;

        case "DELETE":
              if($result=DB::select("$query")==TRUE)
            {

              echo "<script>alert('Record Successfully Deleted');</script>";
            }
            else{
             echo "<script>alert('No Record Deleted');</script>";

            }
            break;

        case "UPDATE":
            if($result=DB::select("$query")==TRUE)
              {

                echo "<script>alert('Record Successfully UPDATED');</script>";

              }
              else{
               echo "<script>alert('No Record UPDATED');</script>";

              }
              break;

        case "INSERT":
                  if($result=DB::select("$query")==TRUE)
                  {

                  echo "<script>alert('Record Successfully Inserted');</script>";
                  }
              else
              {
               echo "<script>alert('No Record INSERTED');</script>";

              }
                break;


        case "CREATE":
            if($result=DB::select("$query")==FALSE){


               echo "<script>alert('Table Successfully Created');</script>";
           }
           else
           {
            echo "<script>alert('Error in Table Creation');</script>";

           }
             break;

        case "ALTER":
              if($result=DB::select("$query")==FALSE){


                    echo "<script>alert('Table Successfully Altered ');</script>";
                }
                else
                {
                 echo "<script>alert('Error in Table Alteration');</script>";

                }
                  break;
           case "DROP":
                        if($result=DB::select("$query")==FALSE){


                              echo "<script>alert('Table or View Successfully Dropped ');</script>";
                          }
                          else
                          {
                           echo "<script>alert('Error in Table or view Drop');</script>";

                          }
                            break;

        

      default:
              echo "Invalid Query";
               break;

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
