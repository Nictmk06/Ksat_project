<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class UngroupApplicationModel extends Model
{
       protected $fillable = [ 'applicationid','orderno', 'orderdate','ungroupfrom','ungroupstartno','ungroupendno','createdon','createdby'
    ];
    protected $table = 'ungroupapplication';
    public $timestamps = false;
    //protected $primaryKey = 'applicationid';
   
   public static function addUngroupApplicationDetails($applnStore)
        {
                $value=DB::table('ungroupapplication')->insert($applnStore); 
                return $value;           
        }

  }
