<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Designation extends Model
{
	 protected $fillable = [ 'desigcode','designame'];
    
    protected $table = 'designation';
    public $timestamps = false;
    protected $primaryKey = 'desigcode';

   
}
