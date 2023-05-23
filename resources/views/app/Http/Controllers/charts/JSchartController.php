<?php

namespace App\Http\Controllers\charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JSchartController extends Controller
{
    function index()
    {
	//	$data = DB::select("select type_name as casetype,count(*) as number from civil_t ct left join case_type_t ctt on ctt.case_type=ct.regcase_type where regcase_type is not null group by ct.regcase_type,type_name order by ct.regcase_type");

		 $data  = DB::select('select appltypedesc, sum(applicationcnt) as applicationcnt, appltypecode from applicationcount
group by appltypedesc,appltypecode order by appltypecode ');
   
     $array[] = ['Case Type', 'Number'];

     foreach($data as $key => $value)
     {
      $array[++$key] = [$value->appltypedesc, (int) $value->applicationcnt];
     }
     return view('dashboard.jschart')->with('casetype', json_encode($array));
    }
}
