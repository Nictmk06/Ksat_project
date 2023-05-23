<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Session;
class CheckAuthorization
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
    	print_r($_GET);
    $request->input('applTypeName'));    		 
	 $actionName = $request->path();
	//print_r($actionName);
		
	  foreach($request->session()->get('option') as $e) 
	  {    
		 if ($actionName == $e->link)
		 {
		   // authorized request
		   return $next($request);
		 }    
	}
	   return response('Unauthorized Action', 403);
	}
}
