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
    
public function getDDExist($ddchqno,$bankcode,$receiptno)
    {
        if($receiptno=='')
     { $value = DB::select("select count(*) as count from receipt where ddchqno='".$ddchqno."' and bankcode=$bankcode");
        return $value;
    }
    else{
        $value = DB::select("select count(*) as count from receipt where ddchqno='".$ddchqno."' and bankcode=$bankcode and receiptno !='".$receiptno."'");
        return $value;
      
    }
    }


}