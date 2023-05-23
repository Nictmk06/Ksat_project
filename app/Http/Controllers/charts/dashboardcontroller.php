<?php

namespace App\Http\Controllers\charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class  dashboardcontroller extends Controller 
{
	
    public function __construct()
    {
	
       
	}

	public function index(Request $request)
    { 

$establishcode = Session()->get('EstablishCode');

 $data['userAppDet'] = DB::select("select * from usercount1 where establishcode = '$establishcode' ");

 $data['applicationcnt']  = DB::select("select appltypedesc,appltypecode,sum(applicationcnt) as applicationcnt,sum(applicantcnt) as applicantcnt from applicationcount where establishcode = '$establishcode' group by appltypedesc,appltypecode order by appltypecode");

$data['pendingapplcnt']  = DB::select("select appltypedesc,appltypecode,sum(applicationcnt) as applicationcnt,sum(applicantcnt) as applicantcnt from applicationcount where establishcode = '$establishcode' and (statuscode = 1 or statuscode is null)    group by appltypedesc,appltypecode order by appltypecode");

$data['disposedapplcnt'] = DB::select("SELECT apt.appltypedesc, count(applicationid) as applicationcnt,
                                    sum(applicantcount) as applicantcnt
                                    from public.applicationdisposed as ad
                                    left join applicationtype apt on apt.appltypecode = ad.appltypecode
                                    where ad.establishcode='$establishcode' group by apt.appltypedesc,apt.appltypecode order by apt.appltypecode");

  $data['title']='Dashboard';


   return view('dashboard.dashboardmain',$data);

         
}

	
	
	
	
}
