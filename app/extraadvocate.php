<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class extraadvocate extends Model
{
    protected $fillable = [ 'applicationid','advocatetype','partysrno','enrollmentno','display','active','remarks','extraadvcode','enrolleddate','createdby','createdon','updatedby','updatedon','name'
    ];
    protected $table = 'extraadvocate';
    public $timestamps = false;
    protected $primaryKey = 'extraadvcode';

    public static function getExtraAdvDetails($applicationid)
    {
        $value = DB::table('extraadvocate')->select('extraadvocate.applicationid','extraadvocate.advocatetype','extraadvocate.partysrno','extraadvocate.display','advocate.advocatename','extraadvocate.active','extraadvocate.enrollmentno','extraadvocate.remarks','extraadvocate.enrolleddate','extraadvocate.extraadvcode','extraadvocate.name')->leftjoin('advocate','advocate.advocateregno','=','extraadvocate.enrollmentno')->where('applicationid',$applicationid)->get();
        return $value;
    }
    public static function getEachAdvDet($applicationid,$id)
    {
        $value = DB::table('extraadvocate')->select('extraadvocate.applicationid','extraadvocate.advocatetype','extraadvocate.partysrno','extraadvocate.display','advocate.advocatename','extraadvocate.active','extraadvocate.enrollmentno','extraadvocate.remarks','extraadvocate.enrolleddate','extraadvocate.extraadvcode')->leftjoin('advocate','advocate.advocateregno','=','extraadvocate.enrollmentno')->where('applicationid',$applicationid)->where('extraadvcode',$id)->get();
        return $value;
    }
    public static function getUniqueAdvList($applicationid)
    {
        $value = DB::select("select advocateregno,advocatename from advocate 
where advocateregno not in(select advocateregno from applicant where applicationid='".$applicationid."')
and advocateregno not in(select advocateregno from respondant where applicationid='".$applicationid."')");
        return $value;
    }
    public static function getExtAdvExist($filledname,$filledsrno,$advregno,$applicationid)
    {
        //echo $filledsrno.'-'.$filledname.'-'.$advregno.'-'.$applicationid;
        $value=DB::table('extraadvocate')->where('advocatetype',$filledname)->where('partysrno',$filledsrno)->where('enrollmentno',$advregno)->
        where('applicationid',$applicationid)->exists();
      //  echo dd($value);
            return $value;
    }
}
