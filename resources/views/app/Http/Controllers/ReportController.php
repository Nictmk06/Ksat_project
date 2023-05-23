<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

use Input;


use carbon\carbon;




class ReportController extends Controller
{
  
public static function table_names()
{
       
        
        //$t_name = DB::select("select t_name from table_names");
  $t_name = DB::select("SELECT table_name as t_name  FROM information_schema.tables WHERE table_type='BASE TABLE' AND table_schema='public'");
 
        return view('form', compact('t_name'));



    }


    public function column_names(Request $request) {
        //$t_name=$request->t_name;
    
    $t_name = $request->t_name;
        //return $t_name;


$column_name =DB::select("select column_name from information_schema.columns where table_name='$t_name' order by column_name");
      //return $column_name;
     
return response()->json(['column_name' => $column_name]);
    
}


public function details(Request $request) {

   $c_name=$request->c_name;
   $c=count($c_name);
   $c_names = implode(',', $c_name);
   $t_name=$request->t_name;
   $condition=$request->contents;
   //return $condition;
 
   if($condition!='')
   {
   $result = DB::select("select $c_names from $t_name where $condition");
  }
  else
  {
    $result = DB::select("select $c_names from $t_name");
  }
   $c2=count($result);
    return view('report', ['c_name' => $c_name,'result'=>$result,'c'=>$c]); 
}

}
