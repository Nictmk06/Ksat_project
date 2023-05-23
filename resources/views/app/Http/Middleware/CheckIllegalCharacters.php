<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Session;
class CheckIllegalCharacters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
      $uri = $request->path();
      if($uri=='applIndexstore'){
         return $next($request);
      }else{
      $array = array("' OR 1=1", 'script', 'select', 'insert', 'XP_', 'delete');
      foreach($_REQUEST as $key => $value){
		  //	echo $key . " : " . $value . "<br />\r\n";
        if($key !='_token'){
			//echo $key . " ghgh: " . $value . "<br />\r\n";
            if (is_array($value)) {
            foreach($value as $key1 => $value1){
                //echo $key1 . " : " . $value1 . "<br />\r\n";
               foreach ($array as $array) {
                if (stripos($value1, $array) !== FALSE ) {
            //    echo 'ERROR: ILLEGAL characters found.';
                 return response('ERROR: ILLEGAL characters found.', 403);
                exit();
              }
              }  }
          }
             else{
                foreach($array as $arr) {
                  if (stripos($value, $arr) !== FALSE ) {
                //  echo 'ERROR: ILLEGAL characters found.';
                  return response('ERROR: ILLEGAL characters found.', 403);
                exit();
            }
        }
           }
        }
    }



    // foreach ($array as $array) {
       
    //      //echo $key . " : " . $value . "<br />\r\n";
    //        if (is_array($value)) {
    //         foreach($value as $key1 => $value1){
    //             //echo $key1 . " : " . $value1 . "<br />\r\n";
    //             if (strpos($value1, $array) !== FALSE ) {
    //         //    echo 'ERROR: ILLEGAL characters found.';
    //              return response('ERROR: ILLEGAL characters found.', 403);
    //             exit();
    //           }
    //             }
    //       }
    //          else{
    //           if (strpos($value, $array) !== FALSE ) {
    //         //    echo 'ERROR: ILLEGAL characters found.';
    //              return response('ERROR: ILLEGAL characters found.', 403);
    //             exit();
    //         }
    //        }
    //    }
    //  }

 	return $next($request);
    }
	}
}
