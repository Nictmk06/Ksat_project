<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Causelisttype extends Model
{
   protected $fillable = [ 'causelisttypecode','causelistdesc'
    ];
    protected $table = 'causelisttype';
    public $timestamps = false;
    protected $primaryKey = 'causelisttypecode';
}
