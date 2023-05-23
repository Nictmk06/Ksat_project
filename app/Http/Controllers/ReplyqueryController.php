<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\IANature;

class ReplyqueryController extends Controller
{
  public function __construct()
 {
   $this->IANature = new IANature();

 }
   public function replyquery()
  {
   $userid=Session::get('username');


    $result=DB::select("SELECT queryno,repliedon,replycontent,querytypedescription as querytypedescription,querycontent,
    statuscode from webquery
     join querytype on webquery.querytypecode = querytype.querytypecode  where forwardedto='$userid' order by queryno desc");
    return view('Admin.replyquery',['result'=>$result]);
  }




   public function updatereply(Request $request) {

       $id=$request->id;
       $userid=$request->userid;
       $repliedon=$request->repliedon;
       $repliedon=date('Y-m-d');
       $replycontent=$request->replycontent;
       $statuscode=$request->statuscode;
       $statuscode=DB::select("select statuscode,statusname from status where statuscode='1'");
       $queryno=$request->queryno;

       for($i=0;$i<count($queryno);$i++)  {
        $insert=DB::table('webquery')
                     ->where('queryno',$queryno[$i])
                     ->update([
                           'repliedon'=>$repliedon,
                           'replycontent'=>$replycontent,
                           'statuscode'=>2]);

             }
        return redirect()->back();
   }




}
