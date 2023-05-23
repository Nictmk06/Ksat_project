<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\IANature;

class AcknowledgequeryController extends Controller
{
  public function __construct(){
       $this->IANature = new IANature();
     }

     public function Acknowledgequery(Request $request)
     {

       $forwardedon=date('Y-m-d');
       $forwardedon=date("d-m-Y",strtotime($forwardedon));
       $forwardedto=$this->IANature->getsection();
       $userid =$this->IANature->getUserDetails();
       $querytypecode= $this->IANature->getquerytypecode();
       $acknowledgeon=date('Y-m-d');
       $acknowledgeon=date("d-m-Y",strtotime($acknowledgeon));
       $repliedon=date('Y-m-d');
       $repliedon=date("d-m-Y",strtotime($repliedon));


       $username = $request->session()->get('userName');

       $result=DB::select("SELECT queryno,querytype.querytypedescription as querytypedescription,querycontent,mobileno,enteron,repliedon,replycontent,
                                  statuscode,userid,forwardedon,enteron,repliedby,sectioncode,
                                   forwardedto,acknowledgeon from webquery
                                   join querytype on webquery.querytypecode = querytype.querytypecode
                                   where statuscode::integer =1
                                   order by queryno desc");


     return view('Admin.Acknowledgequery',['result'=>$result,'querytypecode'=>$querytypecode,'username'=>$username,'forwardedto'=>$forwardedto,
            'forwardedon'=>$forwardedon,'userid'=>$userid]);

   }


     public function ackdataupdate(Request $request) {
       $userid=$request->userid;
       $acknowledgeon=date('Y-m-d');
       $forwardedon=date('Y-m-d');
       $forwardedto=$request->userid;
       $repliedby=$request->repliedby;
       $queryno=$request->queryno;
       $sectioncode=$request->forwardedto;

       for($i=0;$i<count($queryno);$i++) {
       $insert=DB::table('webquery')
                         ->where('queryno',$queryno[$i])
                          ->update([
                         'acknowledgeon'=>$acknowledgeon,
                         'forwardedon'=>$forwardedon,
                         'forwardedto'=>$forwardedto,
                         'repliedby'=>$repliedby,
                         'userid'=>$userid,
                         'sectioncode'=>$sectioncode
       ]);
       }
        return redirect()->back();



     }



     public function ackdata(Request $request)
     {
       $id=$request->forwardedto;
      $establishcode=Session::get('EstablishCode');


        $result=DB::select("select  distinct userid,username,establishcode from userdetails join usersection on
                           userdetails.sectioncode=usersection.userseccode
                          where userseccode = $id and establishcode=$establishcode order by userid asc");


      return $result;
     }











}
