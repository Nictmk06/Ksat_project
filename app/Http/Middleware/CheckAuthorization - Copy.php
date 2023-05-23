<?php

namespace App\Http\Middleware;
use App\User;
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
    public function handle($request, Closure $next,...$roles)
    {    
// print_r($roles);
    
   if($request->session()->get('menu'))
  {
        foreach($roles as $role) {
	 
        // Check if user has the role This check will depend on how your roles are set up
        foreach($request->session()->get('menu') as $e) 
	  {
        //print_r($e);
        if($e->module == $role)
            return $next($request);
   		 }
   	}
    	 
	   return response('Unauthorized Action', 403);
	}
    else{
         return redirect('/');
    }
}
}
