<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class causelistconnecttemp extends Model
{
    protected $fillable = [ 'applicationid','purposecode','causelistsrno','iaflag','createdon','createdby','causelistcode','enteredfrom','appltypecode','conapplid','type'
    ];
    protected $table = 'causelistconnecttemp';
    public $timestamps = false;
    protected $primaryKey = 'causelistcode';
}
