<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IADocument extends Model
{


    
     protected $fillable = [ 'applicationid','iafillingdate','ianaturecode','iaregistrationdate','hearingdate','orderdate','disposeddate','remark','iastatus','benchcode', 'purposecode', 'courthallno','createdon', 'createdby','iasrno','documenttypecode','iano','iaprayer','partysrno','filledby','appindexref','submitby'
    ];
    protected $table = 'iadocument';
    public $timestamps = false;
    protected $primaryKey = 'iano';
    public static function getIADocAppl($applicationId,$flag)
    {
        if($flag=='iadocument')
        {
            $value=DB::Table('iadocument')->select('*')->where('applicationid', $applicationId)->leftJoin('documenttype', 'documenttype.documenttypecode', '=', 'iadocument.documenttypecode')->leftJoin('ianature', 'ianature.ianaturecode', '=', 'iadocument.ianaturecode')->leftJoin('status','status.statuscode','=','iadocument.iastatus')->orderby('iano','asc')->get();
            return $value;
        }
        else
        {
             $value=DB::Table('iadocument')->select('*')->where('applicationid', $applicationId)->leftJoin('documenttype', 'documenttype.documenttypecode', '=', 'iadocument.documenttypecode')->leftJoin('ianature', 'ianature.ianaturecode', '=', 'iadocument.ianaturecode')->leftJoin('status','status.statuscode','=','iadocument.iastatus')->where('iastatus',1)->orderby('iaregistrationdate','asc')->get();
            return $value;
        }
    	
    }
    public static function getIADocSerial($IASrrno,$applicationid,$doctype)
    {
   // dd($IASrrno);	
/*       $value = DB::Table('iadocument')->select('iadocument.applicationid','iadocument.documenttypecode','lsla','iafillingdate','ianaturecode','iadocument.iaprayer','filledby','partysrno','iaregistrationdate','startpage','endpage','iasrno','iano','appindexref')->leftJoin('documenttype','documenttype.documenttypecode','=','iadocument.documenttypecode')->leftJoin('applicationindex','applicationindex.documentno','=','iadocument.appindexref')->where('iadocument.documenttypecode',$doctype)->where('iadocument.iasrno',$IASrrno)->where('applicationindex.applicationid',$applicationid)->get();
        $value = DB::select("select i.applicationid,i.documenttypecode,d.lsla, i.iafillingdate,i.ianaturecode,i.iaprayer, i.filledby, i.partysrno,i.iaregistrationdate,a.startpage,a.endpage,i.iasrno,i.iano,i.appindexref,i.submitby from iadocument i left join documenttype d on d.documenttypecode = i.documenttypecode left join applicationindex a on a.applicationid=i.applicationid and a.documentno = i.appindexref where i.applicationid = '". $applicationid . "' and i.iano = '$IASrrno' and i.documenttypecode = " . $doctype . "");
  */ 
$value = DB::select("SELECT i.applicationid,i.documenttypecode,d.lsla, i.iafillingdate,i.ianaturecode,i.iaprayer, i.filledby,
         i.partysrno,i.iaregistrationdate,a.startpage,a.endpage,i.iasrno,i.iano,i.appindexref,i.submitby from iadocument i 
         left join documenttype d on d.documenttypecode = i.documenttypecode left join applicationindex a on a.applicationid=
         i.applicationid and a.documentno = i.appindexref where i.applicationid = '$applicationid' and i.iano = '$IASrrno' and 
         i.documenttypecode = '$doctype'");
     
        return $value;
    }


 public static function getDtlsByIANo($IAno,$applicationid,$doctype)
    {
         $value = DB::select("select i.applicationid,i.documenttypecode,d.lsla, i.iafillingdate,i.ianaturecode,i.iaprayer, i.filledby, i.partysrno,i.iaregistrationdate,a.startpage,a.endpage,i.iasrno,i.iano,i.appindexref,iascrutinyflag,objectionflag from iadocument i left join documenttype d on d.documenttypecode = i.documenttypecode left join applicationindex a on a.applicationid=i.applicationid and a.documentno = i.appindexref where i.applicationid = '". $applicationid . "' and i.iano = '". $IAno . "' and i.documenttypecode = " . $doctype . "");
        
        return $value;
    }

    public static function getPendingIA($docType,$application_id)
    {
    	$value=DB::Table('iadocument')->select('iano','iasrno')->where('iadocument.documenttypecode', $docType)->where('iadocument.applicationid', $application_id)->where('iadocument.iastatus', 1)->leftJoin('documenttype', 'documenttype.documenttypecode', '=', 'iadocument.documenttypecode')->leftJoin('ianature', 'ianature.ianaturecode', '=', 'iadocument.ianaturecode')->orderby('iasrno','asc')->get();
			return $value;
    }
    public static function getHearingDoc($application_id)
    {
       $value=DB::Table('dailyhearing')->select('*')->where('applicationid', $application_id)->leftJoin('listpurpose', 'listpurpose.purposecode', '=', 'dailyhearing.purposecode')->leftJoin('status', 'status.statuscode', '=', 'dailyhearing.casestatus')->orderby('hearingdate','asc')->get();
            return $value;
    }

  public static function getHearingDetailsByApplication($applicationid)
    {
        $value = DB::select( "select  * FROM public.dailyhearing where applicationid= '". $applicationid . "' and ordertypecode is not null order by hearingdate desc limit 1 ");
        return $value;
    }


    public static function getHearingCodeDetails($HearingCodeno)
    {
       $value=DB::Table('dailyhearing')->select('*')->where('hearingcode', $HearingCodeno)->leftJoin('documenttype', 'documenttype.documenttypecode', '=', 'dailyhearing.documenttypecode')->leftJoin('listpurpose', 'listpurpose.purposecode', '=', 'dailyhearing.purposecode')->leftJoin('bench','bench.benchcode','=','dailyhearing.benchcode')->get();
            return $value;
    }
    public static function getIAExistance($doctypecode,$iano,$applicationId)
    {
        //echo $doctype.'-'.$iano.'-'.$applicationId;
         $value=DB::table('iadocument')->where('iano',$iano)->where('documenttypecode',$doctypecode)->where('applicationid',$applicationId)->exists();
            return $value;
    }
    public static function updateApplication($applicationId,$nextHrDate,$hearingStatus,$IAStore)
    {
    
            try{
            
            $value = DB::transaction(function () use($applicationId,$nextHrDate,$hearingStatus,$IAStore) {
                
                   DB::table('application')->where('applicationid', $applicationId)->update(['ianextdate'=>date('Y-m-d',strtotime($nextHrDate)),'iapending'=>'Y','statusid'=>$hearingStatus]);  
                   
               
            return true;
                
            });
            return $value;
        }catch(\Exception $e){
            return false;
            }
    }
    public static function getRegOfPrevIA($applicationId)
    {
         $value=DB::table('iadocument')->select('iaregistrationdate')->where('applicationid',$applicationId)->orderBy('iasrno', 'DESC')->first();
            return $value;
    }
    public static function getBenchJudges($benchtype,$display,$establishcode)
    {
        if($display!='')
        {
           $value=DB::table('bench')->select('benchcode','benchtypename','judgeshortname')->where('benchtypename',$benchtype)->where('display','Y')->where('establishcode',$establishcode)->orderBy('judgeshortname', 'asc')->get();
            return $value;
        }
        else
        {
            $value=DB::table('bench')->select('benchcode','benchtypename','judgeshortname')->where('benchtypename',$benchtype)->where('establishcode',$establishcode)->orderBy('judgeshortname', 'asc')->get();
            return $value;
        }
    }
    public static function updateIA($applicationid,$iano,$IAUp)
    {
          $value=DB::table('iadocument')->where('applicationid',$applicationid)->where('iano',$iano)->update($IAUp);
            return $value;
    }
    public static function getIANO($applicationid, $docType)
    {
        $value=DB::table('iadocument')->select('iasrno')->where('applicationid',$applicationid)->where('documenttypecode',$docType)->orderBy('iasrno', 'DESC')->first();
            return $value;
    }

    public static  function getHearingDetails($hearingdate,$benchcode,$applicationid)
    {
        if($applicationid!='')
        {
            $value=DB::table('dailyhearing')->select('*')->where('applicationid',$applicationid)->where('benchcode',$benchcode)->where('hearingdate',$hearingdate)->get();
            return $value; 
        }
        else
        {

           // echo $hearingdate.'..'.$benchcode;
            $value = DB::select("select dailyhearing.applicationid, nextdate, hearingdate, dailyhearing.purposecode,
            listpurpose from dailyhearing left join listpurpose on 
            listpurpose.purposecode = dailyhearing.purposecode where 
            nextdate='".$hearingdate."' and nextbenchcode='".$benchcode."' and
            applicationid 
            not in (select applicationid from causelistappltemp  left join causelisttemp
            on causelisttemp.causelistcode=causelistappltemp.causelistcode where 
            benchcode='".$benchcode."' and causelistdate='".$hearingdate."')");
           
            return $value;
           
        }
       
    }
    public static function getIABasedHearing($hearingdate,$applicationid,$benchcode)
    {
       /* $value=DB::table('iadocument')->select('*')->leftJoin('documenttype','documenttype.documenttypecode','=','iadocument.documenttypecode')->leftJoin('status','status.statuscode','=','iadocument.iastatus')->where('hearingdate',$hearingdate)->where('applicationid',$applicationid)->where('benchcode',$benchcode)->get();
            return $value;

           $value=DB::table('iadocument')->select('*')->leftJoin('documenttype','documenttype.documenttypecode','=','iadocument.documenttypecode')->leftJoin('status','status.statuscode','=','iadocument.iastatus')->where('applicationid',$applicationid)->where('')->where(function($q){
                 $q->where('hearingdate',$hearingdate)->orWhere('status', 1);
            })*/
          /*  $value = DB::select("select * from iadocument left join documenttype on 
documenttype.documenttypecode=iadocument.documenttypecode 
left join status on status.statuscode=iadocument.iastatus 
where applicationid='".$applicationid."'  
and ((iadocument.hearingdate='".$hearingdate."'
and benchcode=1) or (iadocument.iastatus=1))");
           */   $value = DB::select("select * from iadocument left join documenttype on 
documenttype.documenttypecode=iadocument.documenttypecode 
left join status on status.statuscode=iadocument.iastatus 
where applicationid='".$applicationid."'  
and ((iadocument.hearingdate='".$hearingdate."'
and benchcode=$benchcode) )");
            return $value;
    }
}
