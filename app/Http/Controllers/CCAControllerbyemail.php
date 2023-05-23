<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IANature;
use App\CopyApplication;
use App\JudgementModel;
use Illuminate\Support\Facades\DB;
use App\ModuleAndOptions;
use Carbon\Carbon;
use App\CaseManagementModel;
use Mail;
use File;
//use QrCode;
use PDF;
use Illuminate\Support\Facades\Storage;
use Response;
//use Mpdf\QrCode\QrCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode; 

class CCAControllerbyemail extends Controller
{
    //
	public function __construct()
    {
    	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		//	echo 'This is a server using Windows!';
			  $this->path ="C:/Judgements";
          $this->path1 ="C:/dscjudgement";
           $this->path2 ="C:/facesheet";
		} else {
		//	echo 'This is a server not using Windows!';
			 $this->path ="/var/www/data/ksat/judgements";
                         $this->path1 ="/var/www/data/ksat/dscjudgement";
                         $this->path2 ="/var/www/data/ksat/facesheet";
		}
		$this->case = new CaseManagementModel();  
		 $this->Judgement =  new JudgementModel();
		$this->copyapplication = new CopyApplication();
		$this->IANature = new IANature();
		//$this->UserActivityModel = new UserActivityModel();
	}
 public function previousfacesheetoffline(Request $request)
    {
      $data['applicationType'] = $this->case->getApplType();
      return view('cca.previousfacesheetoffline',$data);
    }
public function facesheetlist(Request $request)
{
$establishcode=$request->session()->get('EstablishCode');
$data['docType'] = $this->copyapplication->getDocumentType();
$data['advocatedetails'] = $this->copyapplication->getAdvocate();
$data['dist_list'] = $this->copyapplication->getDistList();
$data['estcode'] = $request->session()->get('EstablishCode');
$data['ename']= $request->session()->get('establishfullname');
$data['applType'] = $this->case->getApplType();
$data['applCategory'] = $this->case->getApplCategory();
$establishDtls = $this->copyapplication->getEstablishDtls($request->session()->get('EstablishCode'));
$data['ccacharge'] =$establishDtls[0]->ccacharge;

$data['ccadetails'] = $this->case->getccadetails_offline($establishcode);
return view('cca.facesheetoffline',$data)->with('user',$request->session()->get('userName'));
}


	
	public function index(Request $request)
    {           
                $establishcode=$request->session()->get('EstablishCode');
		$data['docType'] = $this->copyapplication->getDocumentType();
		$data['advocatedetails'] = $this->copyapplication->getAdvocate();
		$data['dist_list'] = $this->copyapplication->getDistList();
		$data['estcode'] = $request->session()->get('EstablishCode');
		$data['ename']= $request->session()->get('establishfullname');
	    $data['applType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
		$establishDtls = $this->copyapplication->getEstablishDtls($request->session()->get('EstablishCode'));
		$data['ccacharge'] =$establishDtls[0]->ccacharge;

		 $data['ccadetails'] = $this->case->getccadetails($establishcode);
		return view('cca.ccapplicationbyemail',$data)->with('user',$request->session()->get('userName'));
	}


    public function facesheetdownload(Request $request)
    {
                 
    	$number=$request->get('applicationId');
          $var=explode('-',$number);
           $applicationNo=$var[0];
           $ccaapplicationno=$var[1];
      
			$establishcode= $request->session()->get('EstablishCode');
			$data['ename']= $request->session()->get('establishfullname');
		
            $judgementDetails = $this->Judgement->getJudgementDetails($applicationNo,$establishcode);
                               
            
          $judgementDetails[0]->judgement_path;
  
            $data['ccadetails'] = $this->case->getfacingsheet($applicationNo,$ccaapplicationno);
          
             $datacca = $this->case->getfacingsheet($applicationNo,$ccaapplicationno);
         

           if($datacca[0]->connectedapplication=='No Connected or main applicationid')
           {
               $data['mainapplicationid']='';
               $data['other_connectedid']='';
           }
           elseif(strpos($datacca[0]->connectedapplication, 'Connected Application ID') !== false)
           {
               $data['mainapplicationid']='';
               $data['other_connectedid']='';
           }

          elseif(strpos($datacca[0]->connectedapplication, 'Main Application ID')!== false){
             $connectedapplicationid=substr($datacca[0]->connectedapplication,19);
            
             $data['mainapplicationid']=DB::SELECT("SELECT a1.applicationo from copyapplication as cp
                               inner join connected1  c on cp.applicationid=c.conapplid
                               inner join applicationsummary1 a1 on c.applicationid=a1.applicationid
                           where cp.applicationid='$connectedapplicationid'")[0]->applicationo;

            $mainapplicationid=DB::SELECT("SELECT a1.applicationid from copyapplication as cp
                               inner join connected1  c on cp.applicationid=c.conapplid
                               inner join applicationsummary1 a1 on c.applicationid=a1.applicationid
                           where cp.applicationid='$connectedapplicationid'")[0]->applicationid;
            
          /*  $other_connectedid=DB::SELECT("SELECT connapplno FROM connecetdappldtls WHERE connapplno IN (SELECT connapplno FROM connecetdappldtls WHERE conapplid!='$connectedapplicationid' and 
                      applicationid='$mainapplicationid')");  */
$other_connectedid=DB::SELECT("SELECT connapplno FROM connecetdappldtls WHERE applicationid='$mainapplicationid'");

            // count($other_connectedid);
            if(count($other_connectedid)=='1')
            {   
                $other_connectedid='';
                $data['other_connectedid']=$other_connectedid;
            }
            else
               { //dd($other_connectedid);
                $count=count($other_connectedid);
                for($j=0;$j<$count;$j++){
                     $data['other_connectedid'][]= $other_connectedid[$j]->connapplno;
                    }
                                    
               }            
           }  
           $ccano=str_replace('/', '-',$datacca[0]->ccaapplicationno);
            
             $orderdt = str_replace('/', '-', $datacca[0]->orderdate1);
             $orderdt=date('Y-m-d',strtotime($orderdt));
           // $applicationno=str_replace('/','_',$applicationNo);
            // $orderDt=date('d-m-Y',strtotime($orderdt));
             $jfile=$applicationNo.'_'.$orderdt;
             //$data['qrcode'] = base64_encode(QrCode::format('svg')->size(150)->generate('http://ksat.karnataka.gov.in/downloadJudgement?applicationid=OA/5569/2016_2016-06-24'));
        //   return view('cca.facesheet',$data);
             $data['qrcode'] = base64_encode(QrCode::format('svg')->size(150)->generate('http://ksat.karnataka.gov.in/downloadJudgement?applicationid='.$jfile));
              // dd($data['qrcode']);
		//   dd($data);
                   $pdf = PDF::loadView('cca.facesheet',$data);
                  
		   $pdf->save('/var/www/data/ksat/facesheet/'.$ccano.'.pdf');

        
    		 $pdfFilestore ='/var/www/data/ksat/facesheet/'.$ccano.'.pdf';
            $imagb64 ='images/ksat.jpg';
			$pdfFilestore = chunk_split(base64_encode(file_get_contents($pdfFilestore)));
            $imagb64 = chunk_split(base64_encode(file_get_contents($imagb64)));
             $json=array();
            $json[]=array(
                

               'msg' =>$pdfFilestore ,
                'img' => $imagb64             //b64 to ajx again

          );
          
           echo json_encode($json);  

    }

 public function facesheetdownload_1(Request $request)
{

$number=$request->get('printfacesheet');
$var=explode('-',$number);
$applicationNo=$var[0];
$ccaapplicationno=$var[1];

$establishcode= $request->session()->get('EstablishCode');
$data['ename']= $request->session()->get('establishfullname');

$judgementDetails = $this->Judgement->getJudgementDetails($applicationNo,$establishcode);

if(sizeof($judgementDetails)>0)
{
$judgementDetails[0]->judgement_path;
}
else{
return '<script type="text/javascript">alert("Judgment does not exist  in the system");</script>';


}
$data['ccadetails'] = $this->case->getfacingsheet($applicationNo,$ccaapplicationno);

$datacca = $this->case->getfacingsheet($applicationNo,$ccaapplicationno);


if($datacca[0]->connectedapplication=='No Connected or main applicationid')
{
$data['mainapplicationid']='';
$data['other_connectedid']='';
}
elseif(strpos($datacca[0]->connectedapplication, 'Connected Application ID') !== false)
{
$data['mainapplicationid']='';
$data['other_connectedid']='';
}

elseif(strpos($datacca[0]->connectedapplication, 'Main Application ID')!== false){
$connectedapplicationid=substr($datacca[0]->connectedapplication,19);

$data['mainapplicationid']=DB::SELECT("SELECT a1.applicationo from copyapplication as cp
inner join connected1  c on cp.applicationid=c.conapplid
inner join applicationsummary1 a1 on c.applicationid=a1.applicationid
where cp.applicationid='$connectedapplicationid'")[0]->applicationo;

$mainapplicationid=DB::SELECT("SELECT a1.applicationid from copyapplication as cp
inner join connected1  c on cp.applicationid=c.conapplid
inner join applicationsummary1 a1 on c.applicationid=a1.applicationid
where cp.applicationid='$connectedapplicationid'")[0]->applicationid;

/*  $other_connectedid=DB::SELECT("SELECT connapplno FROM connecetdappldtls WHERE connapplno IN (SELECT connapplno FROM connecetdappldtls WHERE conapplid!='$connectedapplicationid' and
applicationid='$mainapplicationid')");  */
$other_connectedid=DB::SELECT("SELECT connapplno FROM connecetdappldtls WHERE applicationid='$mainapplicationid'");

// count($other_connectedid);
if(count($other_connectedid)=='1')
{
$other_connectedid='';
$data['other_connectedid']=$other_connectedid;
}
else
{ //dd($other_connectedid);
$count=count($other_connectedid);
for($j=0;$j<$count;$j++){
$data['other_connectedid'][]= $other_connectedid[$j]->connapplno;
}

}
}
$ccano=str_replace('/', '-',$datacca[0]->ccaapplicationno);
$orderdt = str_replace('/', '-', $datacca[0]->orderdate1);
$orderdt=date('Y-m-d',strtotime($orderdt));
$jfile=$applicationNo.'_'.$orderdt;
$data['qrcode'] = base64_encode(QrCode::format('svg')->size(150)->generate('http://ksat.karnataka.gov.in/downloadJudgement?applicationid='.$jfile));
$pdf = PDF::loadView('cca.facesheetofflinepdf',$data);
return $pdf->download($jfile.'.pdf');
}

public function previousfacesheetdownload(Request $request)
{

$ccaapplicationno=$request->get('ccaapplicationNo');
$establishcode= $request->session()->get('EstablishCode');
$applicationID=DB::SELECT("SELECT * from copyapplication where ccaapplicationno='$ccaapplicationno'");



if($applicationID!=null){
    if($applicationID[0]->requested_mode=='O')
        {
    return back()->with('error', $ccaapplicationno.' is Online CCA Application ,Only Offline Application are allowed!!!');

        }


    $ccaestablishcode=$applicationID[0]->establishcode;
    $applicationID=$applicationID[0]->applicationid;
   
  
   if($establishcode!=$ccaestablishcode)
   { 
    $ccaestablishname=DB::SELECT("SELECT establishname from establishment where establishcode='$ccaestablishcode'");
    $ccaestablishname=$ccaestablishname[0]->establishname;
    $sessionestablishname=DB::SELECT("SELECT establishname from establishment where establishcode='$establishcode'");
    $establishname=$sessionestablishname[0]->establishname;
    return back()->with('error', $ccaapplicationno.' belongs to '.$ccaestablishname. ' and you are using session of '.$establishname);

   }
}
else{
    return back()->with('error', $ccaapplicationno.' does no attached with any Application ID present in the system');
}


$data['ename']= $request->session()->get('establishfullname');

$judgementDetails = $this->Judgement->getJudgementDetails($applicationID,$establishcode);

if(sizeof($judgementDetails)>0)
{
$judgementDetails[0]->judgement_path;
}
else{
 return back()->with('error', 'Judgment for '.$applicationID.' which is attached to Copy application no '.$ccaapplicationno.' does not exist');
  }

//$judgementDetails[0]->judgement_path;
                     
$data['ccadetails'] = DB::select("select a.*,b.*,a.ccaapplicationno,c.applicationo,to_char(caapplicationdate,'DD/MM/YYYY') as caapplicationdate1,to_char(a.orderdate,'DD/MM/YYYY') as orderdate1,to_char(trsn_timestamp, 'DD/MM/YYYY HH24:MI:SS') as tran_time,to_char(now(), 'DD/MM/YYYY HH24:MI:SS') as currentdate,
CASE 
WHEN EXISTS (select conapplid
                 from connecetdappldtls c1
                 where c1.applicationid = a.applicationid)
THEN 'Connected Application ID'||c1.connapplno
WHEN EXISTS (select c1.applicationid
                 from connecetdappldtls c1
                 where c1.conapplid = a.applicationid)
THEN 'Main Application ID'||c.applicationid 
ELSE
'No Connected or main applicationid'  
END  
as  connectedapplication from copyapplication  as a
left  join payment_details as b on (a.ccaapplicationno=b.ccaapplicationno )
left join applicationsummary1 as c on (a.applicationid=c.applicationid )
left join connecetdappldtls as c1 on a.applicationid=c1.applicationid	
where a.ccastatuscode=5   and a.payment_yn='Y' and  (b.status='PAYMENT_DONE' or b.status is null ) and (a.apply_type=1 or 
a.apply_type=2) and a.applicationid='".$applicationID."'  and a.ccaapplicationno='".$ccaapplicationno."'  ");


$datacca = DB::select("select a.*,b.*,a.ccaapplicationno,c.applicationo,to_char(caapplicationdate,'DD/MM/YYYY') as caapplicationdate1,to_char(a.orderdate,'DD/MM/YYYY') as orderdate1,to_char(trsn_timestamp, 'DD/MM/YYYY HH24:MI:SS') as tran_time,to_char(now(), 'DD/MM/YYYY HH24:MI:SS') as currentdate,
CASE 
WHEN EXISTS (select conapplid
                 from connecetdappldtls c1
                 where c1.applicationid = a.applicationid)
THEN 'Connected Application ID'||c1.connapplno
WHEN EXISTS (select c1.applicationid
                 from connecetdappldtls c1
                 where c1.conapplid = a.applicationid)
THEN 'Main Application ID'||c.applicationid 
ELSE
'No Connected or main applicationid'  
END  
as  connectedapplication from copyapplication  as a
left  join payment_details as b on (a.ccaapplicationno=b.ccaapplicationno )
left join applicationsummary1 as c on (a.applicationid=c.applicationid )
left join connecetdappldtls as c1 on a.applicationid=c1.applicationid	
where a.ccastatuscode=5   and a.payment_yn='Y' and  (b.status='PAYMENT_DONE' or b.status is null ) and (a.apply_type=1 or a.apply_type=2) and a.applicationid='".$applicationID."'  and a.ccaapplicationno='".$ccaapplicationno."'  ");

if($datacca ==null)
{
    return back()->with('error', $ccaapplicationno.' is not delivered yet !!!!');

}

if($datacca[0]->connectedapplication=='No Connected or main applicationid')
{
$data['mainapplicationid']='';
$data['other_connectedid']='';
}
elseif(strpos($datacca[0]->connectedapplication, 'Connected Application ID') !== false)
{
$data['mainapplicationid']='';
$data['other_connectedid']='';
}

elseif(strpos($datacca[0]->connectedapplication, 'Main Application ID')!== false){
$connectedapplicationid=substr($datacca[0]->connectedapplication,19);

$data['mainapplicationid']=DB::SELECT("SELECT a1.applicationo from copyapplication as cp
inner join connected1  c on cp.applicationid=c.conapplid
inner join applicationsummary1 a1 on c.applicationid=a1.applicationid
where cp.applicationid='$connectedapplicationid'")[0]->applicationo;

$mainapplicationid=DB::SELECT("SELECT a1.applicationid from copyapplication as cp
inner join connected1  c on cp.applicationid=c.conapplid
inner join applicationsummary1 a1 on c.applicationid=a1.applicationid
where cp.applicationid='$connectedapplicationid'")[0]->applicationid;

/*  $other_connectedid=DB::SELECT("SELECT connapplno FROM connecetdappldtls WHERE connapplno IN (SELECT connapplno FROM connecetdappldtls WHERE conapplid!='$connectedapplicationid' and
applicationid='$mainapplicationid')");  */
$other_connectedid=DB::SELECT("SELECT connapplno FROM connecetdappldtls WHERE applicationid='$mainapplicationid'");

// count($other_connectedid);
if(count($other_connectedid)=='1')
{
$other_connectedid='';
$data['other_connectedid']=$other_connectedid;
}
else
{ //dd($other_connectedid);
$count=count($other_connectedid);
for($j=0;$j<$count;$j++){
$data['other_connectedid'][]= $other_connectedid[$j]->connapplno;
}

}
}
$ccano=str_replace('/', '-',$datacca[0]->ccaapplicationno);
$orderdt = str_replace('/', '-', $datacca[0]->orderdate1);
$orderdt=date('Y-m-d',strtotime($orderdt));
$jfile=$applicationID.'_'.$orderdt;
$data['qrcode'] = base64_encode(QrCode::format('svg')->size(150)->generate('http://ksat.karnataka.gov.in/downloadJudgement?applicationid='.$jfile));
$pdf = PDF::loadView('cca.facesheetofflinepdf',$data);
return $pdf->download($jfile.'.pdf');
}



	public function facesheet(Request $request)
    {
    	$applicationNo =$request->get('applicationId');
    	
    	$establishcode =$request->session()->get('EstablishCode');
    		         
          $judgementDetails = $this->Judgement->getJudgementDetails($applicationNo,$establishcode);
       
	        $fileName = str_replace("/","_",$applicationNo);
	      
	        $j_date = date('Y-m-d',strtotime($judgementDetails[0]->judgementdate));
	        $name=$fileName.'_'.$judgementDetails[0]->judgementdate;
	         //dd($name);
	       $appcount = $this->Judgement->getJudgementExist($applicationNo,$establishcode,$j_date);
	       $appcount= $appcount[0]->judgementcount;
	           //dd($appcount);
	       if($appcount==0)
	       {       
	          return back() ->with('error','Judgement doesnot exists');
	         }else{

	          //if($request->get('judgement')=='view Judgement'){
	            
	            $dscjudgement_path=$judgementDetails[0]->dscjudgement_path;
            $path=$this->path1.'/'.$dscjudgement_path;

            // $path=$this->pathdownload.'/'.$judgement_path;
           //  $path= "http://ksat.kar.nic.in:8080/kat_act.pdf";
     $fileName='dsc_'.$name.'.pdf';
	          $main_url =$path;
	          header("Content-disposition:attachment; filename=$fileName");
	          readfile($main_url);
	      }

		
			
     	}
		

  public function facesheetsave(Request $request)
  {

  		$base64 =$request->get('base64');
         $establishcode = $request->session()->get('EstablishCode');
  		$applicationid =$request->get('id');
         //$applicationNo =$request->get('applicationId');
        // print_r($applicationNo);
  	   $ccadet =$this->case->getfacingsheet($applicationid);
         $ccano=$ccadet[0]->ccaapplicationno;


  		 $bin = base64_decode($base64, true);
  		 $fileName = str_replace("/","_",$ccano);
           
		$dscname='facesheeet_'.$fileName.'.pdf';
		$file_path  = $this->path2.'/'.$dscname;
        $result = file_put_contents($file_path, $bin);
         $fac_file=$file_path;
             $json=array();
            $json[]=array(
                
               'msg' =>'save sucessfully',
                'fac_file'=> $fac_file
          );
          
            echo json_encode($json); 
  }



	public function attachment_email(Request $request) {
      $ename= $request->session()->get('establishfullname');
 $number=$request->get('applicationId');
          $var=explode('-',$number);
           $applicationNo=$var[0];
           $ccaapplicationno=$var[1];
 

 //     $applicationNo =$request->get('applicationId');
      $establishcode =$request->session()->get('EstablishCode');
    		         
          $judgementDetails = $this->Judgement->getJudgementDetails($applicationNo,$establishcode);
       
	        $fileName = str_replace("/","_",$applicationNo);
	      
	        $j_date = date('Y-m-d',strtotime($judgementDetails[0]->judgementdate));
	        $name=$fileName.'_'.$judgementDetails[0]->judgementdate;
	         //dd($name);
	       $appcount = $this->Judgement->getJudgementExist($applicationNo,$establishcode,$j_date);
	       $appcount= $appcount[0]->judgementcount;
	           //dd($appcount);
	       if($appcount==0)
	       {       
	          return back() ->with('error','Judgement doesnot exists');
	         }else{

	          //if($request->get('judgement')=='view Judgement'){
	            
	         $dscjudgement_path=$judgementDetails[0]->dscjudgement_path;
            $dsc_jud_file=$this->path1.'/'.$dscjudgement_path;//============judgement dsc file =========

            

		     $ccadet =$this->case->getfacingsheet($applicationNo,$ccaapplicationno);
		      //  dd($ccadet);
                          $ccano=$ccadet[0]->ccaapplicationno;
                       
		       $appname=  $ccadet[0]->caapplicantname;
		       $ccdetails_mail=$ccadetails=$this->case-> getccadetailbyccaID($ccano);
		       $appl_email=$ccdetails_mail[0]->email;
		       $ccaappdate=$ccdetails_mail[0]->caapplicationdate1;


  		// $bin = base64_decode($base64, true);
  		 $fileName = str_replace("/","-",$ccano);
           
		$dscname=$fileName.'.pdf';
		$fac_dsc_file  = $this->path2.'/'.$dscname;//========facesheetdsc _file==========
       
      
$msg='Dear '.$appname.' ,
     With refernece to your certified copy application no - '.$ccano.' the copy of the judgement and the facing sheet is enclosed here with.';

     Mail::send([], [], function($message) use($dsc_jud_file,$fac_dsc_file,$ename,$ccano,$appname,$appl_email, $ccaappdate) {

     	 //$msg_content= $message->setBody($msg,'text/html');

         $message->to('gonekarr@gmail.com', 'KSAT Admin')->subject
            ('KSAT-digitally signed judgment and facing sheet copy ') ->setBody('Dear '.$appname.' ,<br/> 
       <p style="padding-left:20px">With reference to your certified copy application no - '.$ccano.' dated - '. $ccaappdate.'  the copy of the judgment and the facing sheet is enclosed here with.</p>
       Computer section<br/>Karnataka State Administrative Tribunal<br/><br/>
         Note: This is an auto generated email. Please do not reply or send queries to this e-mail','text/html'); 
         $message->attach($dsc_jud_file);
          $message->attach($fac_dsc_file);
    
         $message->from('gonekar.ranjana@nic.in',$ename);
      });
      $this->case->updateccastatus($ccano);
       $email_sent_by=$request->session()->get('userName');
    //  $ccadetails=$this->case-> getccadetailbyccaID($ccano);

     $this->case->Insert_cca_email_log($ccano,$email_sent_by,$appl_email);

 
      $json=array(); 
      $json[]=array(
               'msg' =>'Sent email sucessfully'
          );
           echo json_encode($json);
          
 
}
   }  
   
    
}
