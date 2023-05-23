<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\IANature;
use App\Advocate;
use DB;
use App\Http\Requests;

class AdvocateapprovalController extends Controller
{

  public function __construct()
  {
    $this->IANature = new IANature();
    $this->Advocate = new Advocate();
  }

  public  function advocateapproval() {
     return view('Admin.advocateapproval');
 }

 public  function cancelcourtdirection(){
     return view('casefollowup.cancelcourtdirection');
 }
 public function approvallist(Request $request){
 

  $statusofadvocatechange = $request->statusofadvocatechange;



   
 if($statusofadvocatechange == 1) {

  
      $usersonline=DB::SELECT("Select u1.id as id, u1.mobile,u1.name,
 u1.advocatename,u1.advocateaddress,
 u1.advocateregno,u1.pincode, D.distname, t.talukname from users_online u1
 Join district D on D.distcode=u1.distcode
 Join taluk T on T.talukcode=u1.talukcode
 where (isverified ='N' or isverified IS NULL) and newadvocate='Y'");
 } 
elseif($statusofadvocatechange == 2) {
  $usersonline =DB::SELECT("SELECT u1.id as id,u1.name, u1.mobile,
                   u1.advocateregno, u1.advocatename,u1.advocateaddress,u1.pincode,d1.distname,t1.talukname,
                   a1.advocatename as aadvocatename,a1.advocateaddress as aadvocateaddress,
                   a1.pincode as apincode,
                   D.distname as adistname, t.talukname as atalukname
                   FROM users_online u1
                   INNER JOIN advocate a1   ON u1.advocateregno = a1.advocateregno
                   left Join district D on D.distcode=a1.distcode
                   left join taluk t on t.talukcode = a1.talukcode
                   left Join district D1 on D1.distcode=u1.distcode
                   left join taluk t1 on t1.talukcode = u1.talukcode
                   where newadvocate = 'N' and isverified = 'N'");
   
   } else{


         $usersonline=DB::SELECT("Select u1.id as id, u1.mobile,u1.name,
                                u1.advocatename,u1.advocateaddress,
                                u1.advocateregno,u1.pincode,
                                D.distname, t.talukname from users_online u1
                                Join district D on D.distcode=u1.distcode
                                 Join taluk T on T.talukcode=u1.talukcode where isverified ='Y'");
 
  }
  
 //print_r('$usersonline');
 
return view('Admin.approvallist', ['usersonline' => $usersonline],['statusofadvocatechange' =>$statusofadvocatechange] );
  
}

public function updateprofile(Request $request) {
$statusofadvocatechange = $request->statusofadvocatechange;
  
 $ids = $request->ids;

 if($statusofadvocatechange == 1) {
 
    if($ids==null) {
 
      return response()->json([
  
         'status' => "error",
   
        'message' => "Please Select to Approve the Details Of Advocate",
 
        ]);
     
  }

 try{
    
   DB::begintransaction();
   
      for($i=0;$i<count($ids);$i++) {

     
      DB::SELECT("UPDATE users_online set isverified='Y',newadvocate='N' where id='$ids[$i]'");
      
     DB::INSERT("INSERT INTO advocate(advocateregno,advocatename, advocateaddress,distcode,talukcode, pincode)

                              SELECT advocateregno,advocatename, advocateaddress,distcode,talukcode,pincode FROM users_online
  where id='$ids[$i]'");

    
 
      DB::commit();
   
     }

  return response()->json([
 
         'status' => "sucess",
     
     'message' => "Saved Successfully"
     
   ]);


 } catch (\Throwable $e) {
  
      DB::rollback();
  
     //  throw $e;
  
      return response()->json([
      
      'status' => "error",
     
       'message' => "Error in Insertion"
       
  ]);
 
   }
  
 }

if($statusofadvocatechange == 2) {
 
    if($ids==null) {
 
      return response()->json([
   
        'status' => "error",
     
      'message' => "Please Select to Approve the Details Of Advocates"
         
]);
    
 }

   try{
  
     DB::begintransaction();
     
    for($i=0;$i<count($ids);$i++) {
        
   DB::SELECT("UPDATE users_online set isverified='Y' where id='$ids[$i]'");
     
   DB::INSERT("UPDATE advocate as s SET advocatename=t.advocatename,advocateaddress =t.advocateaddress,
 
                      distcode=t.distcode,talukcode=t.talukcode,pincode=t.pincode  FROM (SELECT advocateregno, advocatename, advocateaddress , distcode,talukcode,pincode

                         from users_online where id='$ids[$i]') t  where s.advocateregno=t.advocateregno");
   

        DB::commit();

  }

  } catch (\Throwable $e) {
 
       DB::rollback();
    
   //  throw $e;
    
 
   return response()->json([
   
         'status' => "error",
     
       'message' => "Error in updation "
  
       ]);
 
   }

 return response()->json([
 
            'status' => "sucess",
       
      'message' => "Updated Successfully"
 
          ]);
 
    }
  }
 }
 
