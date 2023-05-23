<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class userdetails extends Model
{
   protected $fillable = [ 'userid','username','userdesigcode','sectioncode','courthallno','userlevel','mobileno','enableuser','useremail','establishcode','createdon','createdby','updatedby','updatedon'
    ];
    protected $table = 'userdetails';
    public $timestamps = false;
    protected $primaryKey = 'userid';
}
