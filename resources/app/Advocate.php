<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Advocate extends Model
{
   protected $fillable = [ 'advocatename','advocatetypecode','advocateregno','advestablishment','advocatemobile','advocateemail'];
    
    protected $table = 'advocate';
    public $timestamps = false;
    protected $primaryKey = 'advocateregno';
}


