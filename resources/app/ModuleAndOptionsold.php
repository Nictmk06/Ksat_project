<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModuleAndOptions extends Model
{
    public function getOptions()
    {
    	/*$modules_options = DB::table('module')
                ->rightJoin('options', 'options.modulecode', '=', 'module.modulecode')
               	->select('module.modulecode','options.optioncode','module.modulename','options.optionname','options.linkname')
                ->get();*/

        $modules_options = DB::table('options')->select('optioncode','optionname','linkname')->orderBy('optioncode', 'asc')->get();

      	return $modules_options;
    }
    public function getModules()
    {
    	 $modules_options = DB::table('module')->orderBy('modulecode', 'asc')->get();
    	 return $modules_options;
    }
}
