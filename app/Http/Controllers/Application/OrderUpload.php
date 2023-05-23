<?php
namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\CaseManagementModel;
use App\DisposedApplicationModel;
use App\JudgementModel;
use App\UserActivityModel;
use App\OrderModel;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use File;
class OrderUpload  extends Controller
{
  private $path ;

  public function __construct()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		//	echo 'This is a server using Windows!';
			  $this->path ="C:/Orders";
		} else {
		//	echo 'This is a server not using Windows!';
			 $this->path ="/var/www/data/ksat/orders";
		}
  //  $this->path ="C:/Judgements";
    $this->case = new CaseManagementModel();
    $this->Order = new OrderModel();
    $this->disposedApplication =  new DisposedApplicationModel();
    $this->Judgement =  new JudgementModel();
    $this->UserActivityModel = new UserActivityModel();
	//when accessing by httpserver
    //$this->pathdownload ="http://10.10.28.84:9000/judgements";
  }

 public function findJudgeWithBenchCode(Request $request)
    {
        $establishcode = Session::get('EstablishCode');
        $applicationid=$request->get('applicationid');
        $hearingdate=$request->get('hearingdate');
        
        $fromdate = date('Y-m-d', strtotime($hearingdate));
        $applTypeName = DB::select("SELECT DISTINCT dh.benchcode,b.judgeshortname,dh.listno,dh.courthallno,ch.courthalldesc
from  dailyhearing as dh INNER JOIN bench as b ON dh.benchcode=b.benchcode
INNER JOIN courthall ch on dh.courthallno=ch.courthallno
where dh.hearingdate='$fromdate' and dh.establishcode='$establishcode' and dh.applicationid='$applicationid' order by benchcode");
        return response()->json($applTypeName);
    }
  public function uploadorder(Request $request)
     {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['ordertype']=DB::SELECT("SELECT * from ordertype where casestatus='1'  or (casestatus='2' and ordertypecode='35')
except (SELECT * from ordertype where ordertypecode='4' or ordertypecode='6' or ordertypecode='13'
 or ordertypecode='30' or ordertypecode='31' or ordertypecode='14' or
ordertypecode='20' or  ordertypecode='27' or ordertypecode='29' or ordertypecode='7' or ordertypecode='5' )    order by ordertypecode");

        return view('casefollowup.uploadorder',$data)->with('user',$request->session()->get('userName'));

     }

     public function showVerifyOrder(Request $request)
     {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['ordertype']=DB::SELECT("SELECT * from ordertype where casestatus='1'  or (casestatus='2' and ordertypecode='35')
except (SELECT * from ordertype where ordertypecode='4' or ordertypecode='6' or ordertypecode='13'
 or ordertypecode='30' or ordertypecode='31' or ordertypecode='14' or
ordertypecode='20' or  ordertypecode='27' or ordertypecode='29' or ordertypecode='7' or ordertypecode='5' )    order by ordertypecode");

        return view('casefollowup.verifyOrder',$data)->with('user',$request->session()->get('userName'));
     }

     public function applicationDetails(Request $request){
       $request->validate([
    'applicationid' => array(
            'required',
            'regex:/^[0-9a-zA-Z_\/]+$/',
            'max:20'
          ),
              ]);
      $establishcode = $request->session()->get('EstablishCode');
      $applicationid = $_POST['applicationid'];
      $data['applicationDetails']=DB::SELECT("SELECT application.*,applicationtype.*
from application
left join applicationtype on applicationtype.appltypecode=application.appltypecode
where application.applicationid='$applicationid'  and
application.establishcode='$establishcode'
order by application.applicationid");
      echo json_encode($data['applicationDetails']);

     }


         public function getOrderDetails(Request $request){
       $request->validate([
    'applicationid' => array(
            'required',
            'regex:/^[0-9a-zA-Z_\/]+$/',
            'max:20'
          ),
              ]);
      $establishcode = $request->session()->get('EstablishCode');
      $applicationid = $_POST['applicationid'];
      $data['applicationDetails']=DB::SELECT("SELECT application.*,orderupload.*
from orderupload
left join application on orderupload.applicationid=application.applicationid
where orderupload.applicationid='$applicationid' 
 and
application.establishcode='$establishcode' and verified_by is null
order by orderupload.orderdate DESC");

     
      echo json_encode($data['applicationDetails']);

     }
public function getAllOrderByApplNoCCA(Request $request)
    {
     $request->validate([
                'applicationId' => array(
            'required',
            'regex:/^[0-9a-zA-Z\/]+$/'
          ),
          ]);
        $applicationid = $request->get('applicationId');
        $establishcode = $request->session()->get('EstablishCode'); 
        $orderdate=$request->get('dateOfOrdSel');
        $orderdate = date('Y-m-d', strtotime($orderdate));
         
        $ccadocumentcode=  $request->get('ccadocumentcode');

       $ordertypedesc=DB::SELECT("SELECT ccadocumentname from ccadocument where ccadocumentcode='$ccadocumentcode'")[0]->ccadocumentname;
      
        $ordertypecode_order=DB::SELECT("SELECT ordertypecode from ordertype where ordertypedesc='$ordertypedesc'")[0]->ordertypecode;

       //  print_r($applicationid);
        $ordeDetails = DB::SELECT("SELECT application.*,orderupload.*
from orderupload
left join application on orderupload.applicationid=application.applicationid
where orderupload.applicationid='$applicationid' and
application.establishcode='$establishcode' and orderupload.ordertypecode='$ordertypecode_order' 
and orderdate='$orderdate' and verified_by is not null
order by orderupload.orderdate DESC");

    if (count($ordeDetails)==0)
        {  
          return response()->json([
                'status' => "fail",
                  'message' => "Order doesnot exists "
                ]);
        }else{ 
         
           echo json_encode($ordeDetails);
       
       }

    }


public function DownloadOrder_bydate(Request $request)
    {
     $establishcode = $request->session()->get('EstablishCode');
     $temp =  explode('_', trim($_GET['applicationid']));
     $applicationid = $temp[0];
     $orderdate = $temp[1];
      
     
       $appcount = $this->Order->getOrderexist($applicationid,$establishcode,$orderdate);
      
       $appcount= $appcount[0]->ordercount;
           //print_r($appcount);
       if($appcount==0)
       {       
          return back()->with('error','Order doesnot exists');
         }else{         
           $orderDetails =DB::SELECT("SELECT application.*,applicationtype.*,orderupload.*
from application
left join applicationtype on applicationtype.appltypecode=application.appltypecode
left join orderupload on orderupload.applicationid=application.applicationid
where application.applicationid='$applicationid'  and application.establishcode='$establishcode' and orderdate='$orderdate'
order by application.applicationid");
          
            $order_path=$orderDetails[0]->order_path;
            $path=$this->path.'/'.$order_path;
            // $path=$this->pathdownload.'/'.$judgement_path;
           //  $path= "http://ksat.kar.nic.in:8080/kat_act.pdf";
     $fileName=$applicationid.'.pdf';
          $main_url =$path;
          header("Content-disposition:attachment; filename=$fileName");
          readfile($main_url);
       }     
 } 

 public function verifyOrder(Request $request)
    {
    $request->validate([
    'applicationId' => array(
            'required',
            'regex:/^[0-9a-zA-Z_\/]+$/',
            'max:20'
          ),                 
              ]);
         $establishcode = $request->session()->get('EstablishCode');
         $applicationid = $request->get('applicationId');
        // $orderDetails = $this->Order->orderDetails($applicationid,$establishcode);

        $orderDetails = DB::SELECT("SELECT application.*,orderupload.*
from orderupload
left join application on orderupload.applicationid=application.applicationid
where orderupload.applicationid='$applicationid'  and
application.establishcode='$establishcode' and verified_by is null
order by orderupload.orderdate");
      
         $orderdate=$orderDetails[0]->orderdate;
         
         $orderStore['verified_by'] = $request->session()->get('userName');;
         $orderStore['verifieddate'] = date('Y-m-d');
        
     $useractivitydtls['applicationid_receiptno'] = $applicationid;
     $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
     $useractivitydtls['activity'] ='Verify Order' ;
     $useractivitydtls['userid'] = $request->session()->get('username');
     $useractivitydtls['establishcode'] = $establishcode ;
       $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
         if($this->Order->updateOrderDetails($orderStore,$orderdate,$applicationid))
          {     
         $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
            return response()->json([
                'status' => "sucess",
                  'message' => "Order Verified Successfully"
                ]);
          }
          else
          {
            return response()->json([
                'status' => "fail",
                 'message' => "Something went wrong, Failed to verify Judgement"
                ]);
          }  
      }


public function DownloadOrder(Request $request)
    {
       
       $request->validate([
                'applTypeName' => array(
            'required',
            'regex:/^[0-9a-zA-Z-\/]+$/',
            'max:20'
          ),  
                'applicationId' => array(
            'required',
            'regex:/^[0-9a-zA-Z\/]+$/',
            'max:20'
          ),    
             ]);  
       $establishcode = $request->session()->get('EstablishCode');
       $applicationNo = $request->input('applicationId');
       $applicationType = explode("-",$request->input('applTypeName'));
       $applicationid = $applicationType[1].'/'.$applicationNo;   
       $orderdate= date('Y-m-d',strtotime($request->input('orderdate')));
       $appcount = $this->Order->getOrderexist($applicationid,$establishcode,$orderdate);
      
       $appcount= $appcount[0]->ordercount;
           //print_r($appcount);
       if($appcount==0)
       {       
          return back()->with('error','Order doesnot exists');
         }else{         
         
           //$orderDetails = $this->Order->orderDetails($applicationid,$establishcode);
                   
            $orderDetails=DB::SELECT("SELECT application.*,applicationtype.*,orderupload.*
from application
left join applicationtype on applicationtype.appltypecode=application.appltypecode
left join orderupload on orderupload.applicationid=application.applicationid
where application.applicationid='$applicationid' and orderdate='$orderdate' and
application.establishcode='$establishcode'
order by application.applicationid");
            $order_path=$orderDetails[0]->order_path;
            $path=$this->path.'/'.$order_path;
            // $path=$this->pathdownload.'/'.$judgement_path;
           //  $path= "http://ksat.kar.nic.in:8080/kat_act.pdf";
     $fileName=$applicationid.'.pdf';
          $main_url =$path;
          header("Content-disposition:attachment; filename=$fileName");
          readfile($main_url);
       }     
 } 

 public function saveorder(Request $request)
    {

		 $request->validate([
                'applTypeName' => array(
						'required',
						'regex:/^[0-9a-zA-Z-\/]+$/',
						'max:20'
					),
                'applicationId' => array(
						'required',
						'regex:/^[0-9a-zA-Z\/]+$/',
						'max:20'
					),
           'benchname'=>'required',
	     'uploadorder' => 'required|mimes:pdf|max:100000',
         'orderdate' => 'required|date',
         'ordertype'=>'required',

             ]);

         $orderdate= date('Y-m-d',strtotime($request->input('orderdate')));
         $establishcode = $request->session()->get('EstablishCode');
         $applType =  explode('-',$request->get('applTypeName'));

         $applicationid = $applType[1].'/'.$request->get('applicationId');
         $appcount = $this->Order->getOrderExist($applicationid,$establishcode,$orderdate);
         $appcount= $appcount[0]->ordercount;
        if($appcount>0)
           {
            return back() ->with('error','Order already uploaded .');
           }
         else{

           $applicationDetails = $this->Order->applicationDetails($applicationid,$establishcode);

           $fileName = str_replace("/","_",$applicationid);
           $orderdate= $request->input('orderdate');
           $fileName = $fileName.'_'.$orderdate.'.'.request()->uploadorder->getClientOriginalExtension();
            //to upload in upload folder in storage\app\upload
           //  $file = $request->imgUpload1->storeAs('upload',$fileName);
           $orderyear =date('Y',strtotime($request->input('orderdate')));

		       // $path=$this->pathdownload.'/'.$judgementyear;
           $path=$this->path.'/'.$orderyear;

           if (!file_exists($path)) {
                mkdir($path);
            }

            $file = $request->uploadorder->move($path, $fileName);
            $mpdf = new \Mpdf\Mpdf();
            $pagecount = $mpdf->SetSourceFile($path.'/'.$fileName);
            $arr=explode('-',$request->input('applTypeName'));
            $appltypeshort = $arr[1];
            $ordertypecode =  $request->get('ordertype');
            if($ordertypecode!=null)
            {
           $ordertypedesc=DB::SELECT("SELECT ordertypedesc from ordertype where ordertypecode='$ordertypecode'")[0]->ordertypedesc;
            }
            $orderStore['applicationid']=$applicationid;
            $orderStore['benchcode']=$request->get('benchname');
            $orderStore['order_path']=$orderyear.'/'.$fileName;
            $orderStore['orderdate']= date('Y-m-d',strtotime($request->input('orderdate')));
            $orderStore['pagecount']=$pagecount;
            $orderStore['establishcode']=$establishcode;
            $orderStore['createdby']= $request->session()->get('userName');
            $orderStore['createdon']= date('Y-m-d H:i:s') ;
            $orderStore['ordertypecode']=$ordertypecode ;


            $this->Order->addOrder($orderStore);

			$useractivitydtls['applicationid_receiptno'] = $applicationid;
			 $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Upload Order' ;
			 $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $establishcode ;
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			  $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
        return back() ->with('success',$ordertypedesc.' uploaded Successfully for '.$applicationid);
        }
       }




}
