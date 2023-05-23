<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Chp extends Model
{
    protected $fillable = [ 'hearingcode', 'applicationid', 'benchcode', 'courthallno', 'hearingdate', 'listno', 'purposecode', 'causelistsrno', 'business', 'starttime', 'endtime', 
    'courtdirection', 'caseremarks', 'orderyn', 'casestatus', 'postafter', 'postaftercategory', 'nextdate', 'nextbenchcode', 'nextcausetypecode', 'takenonboard', 'officenote', 
    'nextpurposecode', 'orderdate', 'disposeddate', 'benchtypename', 'nextbenchtypename', 'ordertypecode', 'nextcauselistcode', 'causelistcode', 'connectedcase', 'createdon'
    ];
    
    protected $table      = 'dailyhearing';
    public $timestamps    = false;
    protected $primaryKey = 'hearingcode';
    public $incrementing  = false;


    public function getUserChno($user,$estcode)
    {
        
              $chno = DB::table('userdetails')->select('courthallno')->where('userid', '=', $user)->where('establishcode', '=', $estcode)->get();
              return $chno;
         
    }
    
}