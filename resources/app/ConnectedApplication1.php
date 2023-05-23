<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ConnectedApplication1 extends Model
{
       protected $fillable = [ 'applicationid','conapplid','conapplfrsrno','conappltosrno','registerdate'
    ];
    protected $table = 'connected1';
    public $timestamps = false;
   // protected $primaryKey = '';
     public $incrementing = false;
    public static function getConnecteddata($applicationid)
    {
    	$value = DB::table('connected1')->select('*')->where('applicationid',$applicationid)->get();
    	return $value;
    }
}
