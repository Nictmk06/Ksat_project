<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dailyhearing extends Model
{
   protected $fillable = [ 'hearingcode','applicationid','benchcode','courthallno','hearingdate','purposecode','business','courtdirection','caseremarks','orderyn','nextdate','nextbenchcode','nextcausetypecode','officenote','nextpurposecode','disposeddate','ordertypecode','casestatus','benchtypename','nextbenchtypename','establishcode','enteredfrom'
    ];
    protected $table = 'dailyhearing';
    public $timestamps = false;
    protected $primaryKey = 'hearingcode';
}
