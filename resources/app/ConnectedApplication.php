<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ConnectedApplication extends Model
{
       protected $fillable = [ 'applicationid','reason','hearingdate','benchcode','orderdate','type','orderno','benchtypename','appltypecode','applicationyear'
    ];
    protected $table = 'connected';
    public $timestamps = false;
    protected $primaryKey = 'applicationid';
    public static function getConnectDetails($applicationid)
    {
    	$value = DB::table('connected')->where('applicationid',$applicationid)->get();
    	return $value;
    }
    public static function getConExits($applicationid,$newflag)
    {
        if($newflag=='C')
        {
            $value = DB::table('connected1')->where('conapplid',$applicationid)->orWhere('applicationid',$applicationid)->exists();
        
             return $value;
        }
    	else if($newflag=='O')
        {
            $value = DB::table('connected1')->where('conapplid',$applicationid)->exists();
        
             return $value;
        }
    	
    }
}
