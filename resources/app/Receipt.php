<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    protected $fillable = [ 'receiptdate','receiptno','modeofpayment','ddchqno','ddchqdate',
    'bankcode','amount','receiptsrno','applicantadvocate','feepurposecode','name','otherdetails','applicationid',
    'receiptuseddate','createdon','createdby','titlename','updatedby','updatedon'
];
    protected $table      = 'receipt';
    public $timestamps    = false;
    protected $primaryKey = 'receiptno';
    public $incrementing  = false;
    
}