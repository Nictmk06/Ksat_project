<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModuleAndOptions extends Model
{
    public function getModulesAndOtions()
    {
    	/*$modules_options = DB::table('module')
                ->rightJoin('options', 'options.moduleCode', '=', 'module.moduleCode')
               	->select('module.moduleCode','options.optionCode','module.moduleName','options.optionName','options.linkName')
                ->get();
*/
        $modules_options = DB::table('options')->select('optioncode','optionname','linkname')->orderBy('optioncode', 'asc')->get();
      	return $modules_options;
    }
      public function usermenu($username)
    {
    	/*$modules_options = DB::table('module')
                ->rightJoin('options', 'options.moduleCode', '=', 'module.moduleCode')
               	->select('module.moduleCode','options.optionCode','module.moduleName','options.optionName','options.linkName')
                ->get();

        $usermenu = DB::table('usermenu')->select('*')->get(); */

        $usermenu = DB::select ("select * from usermenu1('".$username."')  as f(module character varying,option character varying,link character varying,subtitle character varying,moduleorder int,optionorder int,helptext character varying)");
      	return $usermenu;
    }
      public function usermenumodule($username)
    {
      /*$modules_options = DB::table('module')
                ->rightJoin('options', 'options.moduleCode', '=', 'module.moduleCode')
                ->select('module.moduleCode','options.optionCode','module.moduleName','options.optionName','options.linkName')
                ->get();

        $usermenu = DB::select("select distinct modulename,modulecode from usermenu order by modulecode"); */

       $usermenumodule = DB::select ("select distinct module,moduleorder from usermenu1('".$username."') as f(module character varying,option character varying,link character varying,subtitle character varying,moduleorder int,optionorder int,helptext character varying) order by moduleorder" );
 


        return $usermenumodule;
    }
    
}
