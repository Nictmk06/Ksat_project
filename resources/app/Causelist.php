<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//cuaselist appl temp tabl 
class Causelist extends Model
{
     protected $fillable = [ 'applicationid','purposecode','causelistsrno','iaflag','createdon','createdby','causelistcode','enteredfrom','appltypecode','connected','updatedby','updatedon'
    ];
    protected $table = 'causelistappltemp';
    public $timestamps = false;
    protected $primaryKey = 'causelistcode';
	public static function getCauselistdate($causelistcode)
	{
		/*$value = DB::select("select c.causelistcode,a.applicationid,l.listpurpose,c.enteredfrom,a.connectedcase,c.causelistsrno from causelistappltemp as c left join application as a on a.applicationid=c.applicationid  order by c.causelistcode desc" );
		return $value;*/
		$value = DB::select("select c.causelistcode,a.applicationid,l.listpurpose,c.enteredfrom,a.connectedcase,c.iaflag,c.causelistsrno,ct.courthallno from causelistappltemp as c left join application as a on 
		a.applicationid=c.applicationid left join listpurpose as l
		on l.purposecode=c.purposecode 
		left join causelisttemp as ct on ct.causelistcode=c.causelistcode where c.causelistcode='". $causelistcode."'order by  c.causelistsrno asc");
		return $value;
	}
	//get the causelist existance based on hearing date,applicationid if its fresh

	public static function getCauselistExistances($hearingdate,$applicationid)
	{
		$value = DB::table('causelisttemp as ct')->select('ct.causelistcode','applicationid','benchcode','causelisttypecode','listno','courthallno')->leftJoin('causelistappltemp as cat','ct.causelistcode','=','cat.causelistcode')->where('causelistdate',$hearingdate)->where('applicationid',$applicationid)->first();
		return $value;
	}
	public static function getCuaseData($benchJudge,$hearingdate,$causetypecode,$listno)
	{
		$value = DB::table('causelisttemp')->select('*')->leftJoin('causelistappltemp','causelisttemp.causelistcode','=','causelistappltemp.causelistcode')->leftJoin('applicationtype','applicationtype.appltypecode','=','causelistappltemp.appltypecode')->where('causelistdate',$hearingdate)->where('benchcode',$benchJudge)->where('causelisttypecode',$causetypecode)->where('listno',$listno)->get();
		return $value;
	}
	public static function reordercause($causelistcode)
	{
		$value = DB::table('causelisttemp')->select('*')->leftJoin('causelistappltemp','causelisttemp.causelistcode','=','causelistappltemp.causelistcode')->leftJoin('listpurpose','listpurpose.purposecode','=','causelistappltemp.purposecode')->leftJoin('application','application.applicationid','=','causelistappltemp.applicationid')->leftJoin('applicationtype','applicationtype.appltypecode','=','causelistappltemp.appltypecode')->where('causelisttemp.causelistcode',$causelistcode)->orderby('listorder','asc')->orderby('applicationyear','asc')->orderby('applicationsrno','asc')->get();
		return $value;
	}
	public static function getConnectedCase($applicationid)
	{
		//$value = DB::table('application')->select('connectedcase')->where('applicationid',$applicationid)->get();
		$value = DB::select("select a.appltypecode,a.connectedcase from application a where a.applicationid='".$applicationid."' ");
		//echo dd($value);
		return $value;
	}
    
}
