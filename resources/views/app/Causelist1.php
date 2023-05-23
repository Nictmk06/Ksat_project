<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Causelist1 extends Model
{
    protected $fillable = [ 'causelistcode','causelisttypecode','benchcode','courthallno','causelistdate','causelistfromdate','causelisttodate','listno','totalapplication','benchtypename','createdon','createdby','updatedby','updatedon','establishcode'
    ];
    protected $table = 'causelisttemp';
    public $timestamps = false;
    protected $primaryKey = 'causelistcode';
}
