<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use carbon\carbon;
use Illuminate\Support\Facades\DB;









class OrderController extends Controller
{
  
public static function ordersheet()
{
        return view('ordersheet.ordform');
}
public static function ordergenerate(Request $request)
{
    	$request->validate([
   	     'optname' => 'required',
         'optcode' => 'required|numeric',
        ]);
        $option_name=$request->optname;
        $option_code=$request->optcode;
        $link_name=$request->linkname;
        $result=DB::select("select * from options  where optionname='$option_name' and optioncode='$option_code'");
        $optname=$result[0]->optionname;
        $optcode=$result[0]->optioncode;
        $linkcode=$result[0]->linkname;
        $path= "ordersheet";
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
        $my_template->setValue("OPTNAME",  $optname);
             $my_template->setValue("OPTCODE",$optcode); 
             $my_template->setValue("LINKCODE",$linkcode);
        $my_template->saveAs(storage_path($optname."_".$optcode."_".$linkcode.".docx"));
        $file= storage_path($optname."_".$optcode."_".$linkcode.".docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,$optname."_".$optcode."_".$linkcode.".docx", $headers)->deleteFileAfterSend(true);;
}

    
}
