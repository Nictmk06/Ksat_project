<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\IANature;
class Webquerycontroller extends Controller
{
  public function __construct(){
     $this->IANature = new IANature();
   }

 public function webquery(Request $request)
   {
    $forwardedon=date('Y-m-d');
   // $forwardedon=date("d-m-Y",strtotime($forwardedon));
    $forwardedto=$this->IANature->getsection();
   $userid =$this->IANature->getUserDetails();
    $querytypecode= $this->IANature->getquerytypecode();
    $acknowledgeon=date('Y-m-d');
   // $acknowledgeon=date("d-m-Y",strtotime($acknowledgeon));
   $repliedon=date('Y-m-d');
   // $repliedon=date("d-m-Y",strtotime($repliedon));
   $subtitle="";
   //
   //
    $establishmentname=Session::get('establishfullname');
   //
    $username = $request->session()->get('userName');

   $result=DB::select("SELECT queryno,querytype.querytypedescription as querytypedescription,querycontent,mobileno,enteron,repliedon,replycontent,
                              statuscode,userid,forwardedon,repliedby,sectioncode,usersecname,
                               forwardedto,acknowledgeon from webquery
                               join querytype on webquery.querytypecode = querytype.querytypecode
                               join usersection on webquery.sectioncode = usersection.userseccode
                               order by queryno desc");


 return view('Admin.webquery',['result'=>$result,'querytypecode'=>$querytypecode,'username'=>$username,
 'forwardedto'=>$forwardedto,'forwardedon'=>$forwardedon,'userid'=>$userid,'subtitle'=>$subtitle,'establishmentname'=>$establishmentname]);

   }


   public function Webquerydata(Request $request) {

      $fromdate=date("Y-m-d",strtotime($request->fromdate));
      $todate=date("Y-m-d",strtotime($request->todate));
      $forwardedto  = $request->forwardedto;
      $statuscode=$request->statuscode;
      $userid  = $request->userid;


      $str1="SELECT queryno,querytype.querytypedescription as querytypedescription,querycontent,
                                    mobileno,enteron,repliedon,replycontent,usersecname,
                                   statuscode,userid,forwardedon,enteron,repliedby,
                                     forwardedto,acknowledgeon from webquery
                      join querytype on webquery.querytypecode = querytype.querytypecode
                       join usersection on webquery.sectioncode = usersection.userseccode";



      if($fromdate and $todate) {
        $str2=" where enteron >='$fromdate' and enteron<='$todate'" ;
      }

     if($forwardedto!="All"){
       $str2 = $str2." and sectioncode='$forwardedto'";

     }

     if($userid!="All")
     {
      $str2 = $str2." and forwardedto='$userid'";
     }

     if($statuscode!="")
     {
        $str2=$str2." and  statuscode='$statuscode'";
     }



         $resultquery=$str1." ".$str2;





        $result=DB::select($resultquery);



      return view('admin.Webquerydata', ['result'=>$result]);

    }
   public function sectiondata(Request $request) {
     $id=$request->forwardedto;
     $establishcode=Session::get('EstablishCode');
     if($id == "All") {
       $result=DB::select("select distinct userid,username from userdetails join usersection on
       userdetails.sectioncode=usersection.userseccode order by userid asc");
     } else {
      $result=DB::select("select  distinct userid,username from userdetails join usersection on
                         userdetails.sectioncode=usersection.userseccode
                        where userseccode = $id and establishcode=$establishcode order by userid asc");
     }
     return $result;
   }
}
