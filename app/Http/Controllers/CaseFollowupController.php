<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\CaseFollowUpModel as CaseFollowUpModel;
use App\IADocument as IADocumentModel;
use App\IANature;
use App\CaseManagementModel;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\UserActivityModel;

class CaseFollowupController extends Controller
{

  public function __construct()
	{
     $this->case = new CaseManagementModel();
     $this->caseFollowUpModel = new CaseFollowUpModel();
     $this->IANature = new IANature();
	 $this->UserActivityModel = new UserActivityModel();
    }



  public function officenote(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
         return view('casefollowup.officenote',$data)->with('user',$request->session()->get('userName'));

     }

  public function getofficenoteDetails(request $request)
    {
	    $request->validate([
		'application_id' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),

              ]);

       $applicationId = $_POST['application_id'];
       $data['officenoteDetails'] = CaseFollowUpModel::getofficenoteDetails($applicationId,'','');
       echo json_encode($data['officenoteDetails']);
    }


    public function getofficenoteDetailsforEdit(request $request)
      {

        $applicationid = request()->get('applicationid');
        $officenotecode = request()->get('officenotecode');
        $officenotedate = request()->get('officenotedate');

      //  $valuesdeleted = DB::select("DELETE from officenote where  applicationid='$applicationid' and  officenotecode='$officenotecode' and officenotedate='$officenotedate'  ");
$officenoteDetailsedit =DB::SELECT("SELECT * from officenote where officenotecode='$officenotecode' and applicationid='$applicationid' and officenotedate='$officenotedate'");

          return response()->json($officenoteDetailsedit);
      }

public  function saveofficenote(Request $request)
{
  if($request->input('sbmt_adv') == "A")
  {
       $validator = \Validator::make($request->all(), [
          'officenote' => 'required|max:10000',
          'officenoteDate' => 'required|date',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {

         $applType =  explode('-',$request->get('applTypeName'));

         $establishcode = $request->session()->get('EstablishCode');
         $officenoteStore['applicationid']=$applType[1].'/'.$request->get('applicationId');
         $applicationid = $officenoteStore['applicationid'];
         $officenoteStore['officenote']=$request->input('officenote');

         $officenoteStore['officenotedate']= date('Y-m-d',strtotime($request->input('officenoteDate')));
         $officenotedate= date('Y-m-d',strtotime($request->input('officenoteDate')));

		     $useractivitydtls['applicationid_receiptno'] =$applicationid;
         $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
         $useractivitydtls['activity'] ='Add Office Note' ;
         $useractivitydtls['userid'] = $request->session()->get('username');
         $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
         $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');

         $validation4duplicate=DB::SELECT("SELECT * from officenote
           where applicationid= '$applicationid'
          and officenotedate='$officenotedate'");
          //print_r($validation4duplicate);
        if(count($validation4duplicate)>0)
         {
           $officenotedate_DDMMYYY=date('d-m-Y',strtotime($officenotedate));
           return response()->json([
               'status' => "fail",
               'message' => "Office Note already exist for applicationid ".$applicationid." on ".$officenotedate_DDMMYYY

               ]);

         }
            if($this->caseFollowUpModel->addOfficeNote($officenoteStore))
                {
					        $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                    return response()->json([
                        'status' => "sucess",
                        'message' => "Office Note Added Successfully."

                        ]);
                }
                else
                {
                    return response()->json([
                    'status' => "fail",
                    'message' => "Something Went Wrong!!"

                    ]);
                }
          }
    }

  else if($request->input('sbmt_adv') == "U")
        {
          $validator = \Validator::make($request->all(), [
             'officenote' => 'required|max:10000',
             'officenoteDate' => 'required|date',
           ]);

           if ($validator->fails())
           {
               return response()->json(['errors'=>$validator->errors()->all()]);
           }
         else
           {

            $applType =  explode('-',$request->get('applTypeName'));
            $applicationid = $applType[1].'/'.$request->get('applicationId');
            $establishcode = $request->session()->get('EstablishCode');
            $officenote=$request->input('officenote');
            $officenotedate= date('Y-m-d',strtotime($request->input('officenoteDate')));
            $officenotecode=$request->input('officenotecode');
           /* $useractivitydtls['applicationid_receiptno'] =$applicationid;
            $useractivitydtls['updatedby'] = date('Y-m-d H:i:s') ;
            $useractivitydtls['activity'] ='UPDATE Office Note' ;
            $useractivitydtls['userid'] = $request->session()->get('username');
            $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');
            $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');  */
            $officenoteupdate=DB::SELECT("UPDATE officenote set officenote='$officenote' where officenotecode='$officenotecode' and applicationid='$applicationid' and officenotedate='$officenotedate'");
                 if($officenoteupdate)
                   {
// $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                       return response()->json([
                           'status' => "sucess",
                           'message' => "Office Note Updated Successfully."

                           ]);
                   }
                   else
                   {
                       return response()->json([
                       'status' => "fail",
                       'message' => "Something Went Wrong!!"

                       ]);
                   }


            }
        }
  }


public  function generateordersheet(Request $request)
{ if($request->input('firstpage')=="Y")
  {
      /*   $request->validate([
          'applId' => array(
						'required',
						'regex:/^[0-9a-zA-Z_\/]+$/',
						'max:20'
					),
		  'fromdate' => 'date',
	      'todate' => 'date|after_or_equal:fromdate'
      ]);  */


        $applicationId=$request->input('applId');
       // $fromDate='';
       // $toDate='';
        //$var=explode("/",$applicationid)
              /*  $appltypeshort=$var[0];
                DB::SELECT("SELECT appltypecode from applicationtype")
                $number     =$var[1];
                $year        =$var[2];
       $applicationId=DB::SELECT()*/
        $user = $request->session()->get('userName');
       $fromDate='';
       $toDate='';
     // print_r($fromDate);
    //  print_r($toDate);
       $officenoteDetails = CaseFollowUpModel::getofficenoteDetails($applicationId,$fromDate,$toDate);
    //   print_r("mini");

    //dd($officenoteDetails);
		$count_a = count($officenoteDetails);
	   $officenotes = "";
         $courtdirection = "";
	   if($officenoteDetails){
           for($i=0; $i<$count_a;  $i++){
			   $officenoteDate = explode("-",$officenoteDetails[$i]->date);
				$yy = $officenoteDate[0];
				$mm = $officenoteDate[1];
				$dd = $officenoteDate[2];
				$officenoteDate = $dd.'-'.$mm.'-'.$yy;
//dd($officenoteDate);
      if($officenoteDetails[$i]->officenote != '')
			{
 			  $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->officenote,1));
              $officenotes.=  '<w:br /><w:br />'.$officenoteDate.' :'. '<w:br /><w:br />'.$dd.'<w:br />';
//dd($officenotes);
       }
		   else if ($officenoteDetails[$i]->courtdirection != ''){
			   $cd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->courtdirection,1));
               $courtdirection.=  '<w:br /><w:br />'.$officenoteDate.' :'. '<w:br /><w:br />'.$cd.'<w:br />';
//dd($courtdirection);
		   }
		   }
                      }
		    $data['applicantDetails'] = $this->case->getTopApplicantDetails($applicationId)[0];

        $data['respondantDetails'] = $this->case->getTopRespondantDetails($applicationId)[0];
        //dd($data['applicantDetails']);
       if($data['applicantDetails']->advocateregno == "")
       {
        $data['applicantadvocateDetails']="";
        $applicantadvocate=$data['applicantadvocateDetails'];
       }
       else{
        $data['applicantadvocateDetails'] = $this->case->getAdvDetails($data['applicantDetails']->advocateregno)[0];
 $applicantadvocate=$data['applicantadvocateDetails']->advocatename;
        }// dd($data['applicantadvocateDetails']);
      //dd( $data['applicantDetails'] );
      if($data['respondantDetails']->advocateregno =="")
       {
        $data['respondantadvocateDetails']="";
        $respondantadvocate  =$data['respondantadvocateDetails'];
       }
        else
        {
       $data['respondantadvocateDetails'] = $this->case->getAdvDetails($data['respondantDetails']->advocateregno)[0];
       $respondantadvocate= $data['respondantadvocateDetails']->advocatename;
        }
     $establishcode=$request->session()->get('EstablishCode');

       $data['causelisttitle']=DB::table('establishment')->select('causelist_title')->where('establishcode',$establishcode)->get()[0];
       $data['subheading1']=DB::SELECT("SELECT 'Filed Under '||ats.actsectionname||' of '||at.actname||', on '|| to_char(ap.registerdate, 'DD-MM-YYYY') as subheading1 from application
       as ap inner join act at on ap.actcode=at.actcode
       inner join actsection ats on ap.actsectioncode=ats.actsectioncode
       where ap.applicationid='$applicationId'");
       $data['subheading2']=DB::SELECT("SELECT relief from applrelief where applicationid='$applicationId'");
       $data['subheading3']=DB::SELECT("SELECT interimprayer   from application where applicationid='$applicationId'
");


       // $data['subheading2']=$this->case->getRelief($applicationId='',$newSrno='',$user);
       //dd($data['subheading3']);
       $query_hearings= "to_date(hearingdate, 'dd/mm/yyyy')";

       $hearings = DB::table('hearingsummary1')->select('hearingdate','courthallno')->where('applicationid',$applicationId)->orderByRaw($query_hearings)->take(1)->get();
        if(count($hearings)>0)
        {
        $subheading4='Posted for Admission before  Court Hall -   ';
        }
       else
        {
        $subheading4='Posted for Admission Before  Court Hall -   ';
        }
        $data['subheading5']=DB::SELECT("SELECT ' Rs.'||amount||'/- is paid by' ||
       CASE
          WHEN modeofpayment ='C' THEN ' Cash '
          WHEN modeofpayment ='D'  THEN ' DD, DD number is ' ||
      CASE
        WHEN ddchqno is null THEN ' '
    else
    ddchqno
       END
        END  ||' and receipt No. '||receiptno||' dated:'||to_char(receiptdate, 'DD-MM-YYYY')||' and same is appended herein.'
    as courtfees from receipt where applicationid='$applicationId'
");

       $applicantname= $data['applicantDetails']->applicantname;
       $respondantname=$data['respondantDetails']->respondname;
       $causelisttitle=  $data['causelisttitle']->causelist_title;
       $subheading1=$data['subheading1'][0]->subheading1;
       //$subheading2=$data['subheading2']->relief;
       if($data['subheading2'] == null)
       {
          $subheading2='';
       }
       else
       {
         $subheading2=$data['subheading2'][0]->relief;
       }
       //dd($data['subheading3']);
       if($data['subheading3'][0]->interimprayer =="")
       {
         $subheading3='-NIL-';
       }
       else
       {
       $subheading3=$data['subheading3'][0]->interimprayer;

       }
       if($data['subheading5']== null )
       {
         $subheading5='-NIL-';
       }
       else
       {
       $subheading5=$data['subheading5'][0]->courtfees;
       }
      $remarks=DB::SELECT("SELECT remarks from application where applicationid='$applicationId'");
     //dd($remarks);
       if($remarks[0]->remarks==null)
       {
         $remarks1='-NIL-';
       }
       else{
         $remarks1=$remarks[0]->remarks;
       }
$registerdate=DB::SELECT("SELECT registerdate from application where applicationid='$applicationId'");
$registerdate1=$registerdate[0]->registerdate;
       $ianature=DB::SELECT("SELECT it.ianaturedesc ,ap.applicationid from iadocument
       as ia inner join ianature it on ia.ianaturecode=it.ianaturecode
       inner join application ap on ap.applicationid=ia.applicationid
       where ia.applicationid='$applicationId'  and ap.registerdate='$registerdate1' order by ia.iafillingdate ASC  LIMIT 1");

//dd($ianature);
  //dd($ianature);
     if($ianature == null)
     {
       $ianature1='-NIL-';
     }
     else
     {
      $ianature1=$ianature[0]->ianaturedesc;
     }
       //dd($causelisttitle);
      // $causelist_title=$data['causelist_title']->causelist_title;
       $path= "ordersheet";
       $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
       $my_template->setValue("applicationId",  $applicationId);
       $my_template->setValue("officenotes",$officenotes);
       $my_template->setValue("courtdirection",$courtdirection);
       $my_template->setValue("applicantname",$applicantname);
       $my_template->setValue("applicantadvocate",  $applicantadvocate);
       $my_template->setValue("respondantname",$respondantname);
       $my_template->setValue("respondantadvocate",$respondantadvocate);
       $my_template->setValue("causelisttitle",$causelisttitle);
       $my_template->setValue("subheading1",$subheading1);
       $my_template->setValue("subheading2",$subheading2);
       $my_template->setValue("subheading3",$subheading3);
       $my_template->setValue("subheading4",$subheading4);
       $my_template->setValue("subheading5",$subheading5);
       $my_template->setValue("remarks",$remarks1);
       $my_template->setValue("ianature",$ianature1);


      $my_template->saveAs(storage_path("order.docx"));
        $file= storage_path("order.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);
  }


 else
    {

      if($request->input('ordertype')=='O')
      {
        $request->validate([
            'applId' => array(
              'required',
              'regex:/^[0-9a-zA-Z_.\/]+$/',
              'max:20'
            ),
        'fromDate' => 'date|required',
          'toDate' => 'date|after_or_equal:fromdate|required'
        ]);

        $applicationId=$request->input('applId');
       // $fromDate='';
       // $toDate='';
        $user = $request->session()->get('userName');
       $fromDate=date('Y-m-d',strtotime($request->input('fromDate')));
       $toDate=date('Y-m-d',strtotime($request->input('toDate')));
     // print_r($fromDate);
    //  print_r($toDate);
       $officenoteDetails = CaseFollowUpModel::getofficenoteDetails($applicationId,$fromDate,$toDate);
    //   print_r("mini");
    $count_a = count($officenoteDetails);
     $officenotes = "";
         $courtdirection = "";
     if($officenoteDetails){
           for($i=0; $i<$count_a;  $i++){
         $officenoteDate = explode("-",$officenoteDetails[$i]->date);
        $yy = $officenoteDate[0];
        $mm = $officenoteDate[1];
        $dd = $officenoteDate[2];
        $officenoteDate = $dd.'-'.$mm.'-'.$yy;
      if($officenoteDetails[$i]->officenote != '')
      {
        $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->officenote,1));
              $officenotes.=  '<w:br /><w:br /><w:b/>'.$officenoteDate.':'. '<w:br /><w:br />'.$dd.'<w:br />';
       }
      }
                      }
      /*  $data['applicantDetails'] = $this->case->getTopApplicantDetails($applicationId)[0];
        $data['respondantDetails'] = $this->case->getTopRespondantDetails($applicationId)[0];
        $data['applicantadvocateDetails'] = $this->case->getAdvDetails($data['applicantDetails']->advocateregno)[0];

      $data['respondantadvocateDetails'] = $this->case->getAdvDetails($data['respondantDetails']->advocateregno)[0];*/
      $establishcode=$request->session()->get('EstablishCode');

       $data['causelisttitle']=DB::table('establishment')->select('causelist_title')->where('establishcode',$establishcode)->get()[0];


       $causelisttitle=  $data['causelisttitle']->causelist_title;
      //dd($causelisttitle);
      // $causelist_title=$data['causelist_title']->causelist_title;
       $path= "ordersheet1";
       $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
       $my_template->setValue("applicationId",  $applicationId);
       $my_template->setValue("courtdirection",$courtdirection);
       $my_template->setValue("officenotes",$officenotes);
       $my_template->setValue("causelisttitle",$causelisttitle);

      $my_template->saveAs(storage_path("order.docx"));
        $file= storage_path("order.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);


      }

      else if($request->input('ordertype')=='C')
      {
        $request->validate([
            'applId' => array(
              'required',
              'regex:/^[0-9a-zA-Z_.\/]+$/',
              'max:20'
            ),
        'fromDate' => 'date|required',
          'toDate' => 'date|after_or_equal:fromdate|required'
        ]);

        $applicationId=$request->input('applId');
       // $fromDate='';
       // $toDate='';
        $user = $request->session()->get('userName');
       $fromDate=date('Y-m-d',strtotime($request->input('fromDate')));
       $toDate=date('Y-m-d',strtotime($request->input('toDate')));
     // print_r($fromDate);
    //  print_r($toDate);
       $officenoteDetails = CaseFollowUpModel::getofficenoteDetails($applicationId,$fromDate,$toDate);
    //   print_r("mini");
    $count_a = count($officenoteDetails);
     $officenotes = "";
         $courtdirection = "";
     if($officenoteDetails){
           for($i=0; $i<$count_a;  $i++){
         $officenoteDate = explode("-",$officenoteDetails[$i]->date);
        $yy = $officenoteDate[0];
        $mm = $officenoteDate[1];
        $dd = $officenoteDate[2];
        $officenoteDate = $dd.'-'.$mm.'-'.$yy;
      if ($officenoteDetails[$i]->courtdirection != ''){
          $cd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->courtdirection,1));
                $courtdirection.=  '<w:br /><w:br />'.$officenoteDate.' :'. '<w:br /><w:br />'.$cd.'<w:br />';

        }
      }
                      }
      /*  $data['applicantDetails'] = $this->case->getTopApplicantDetails($applicationId)[0];
        $data['respondantDetails'] = $this->case->getTopRespondantDetails($applicationId)[0];
        $data['applicantadvocateDetails'] = $this->case->getAdvDetails($data['applicantDetails']->advocateregno)[0];

      $data['respondantadvocateDetails'] = $this->case->getAdvDetails($data['respondantDetails']->advocateregno)[0];*/
      $establishcode=$request->session()->get('EstablishCode');

       $data['causelisttitle']=DB::table('establishment')->select('causelist_title')->where('establishcode',$establishcode)->get()[0];


       $causelisttitle=  $data['causelisttitle']->causelist_title;
      //dd($causelisttitle);
      // $causelist_title=$data['causelist_title']->causelist_title;
       $path= "ordersheet1";
       $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
       $my_template->setValue("applicationId",  $applicationId);
       $my_template->setValue("officenotes",$officenotes);
       $my_template->setValue("courtdirection",$courtdirection);
       $my_template->setValue("causelisttitle",$causelisttitle);

      $my_template->saveAs(storage_path("order.docx"));
        $file= storage_path("order.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);
      }


      else if($request->input('ordertype')=='B')
      {
        $request->validate([
            'applId' => array(
              'required',
              'regex:/^[0-9a-zA-Z_.\/]+$/',
              'max:20'
            ),
        'fromDate' => 'date|required',
          'toDate' => 'date|after_or_equal:fromdate|required'
        ]);

        $applicationId=$request->input('applId');
       // $fromDate='';
       // $toDate='';
        $user = $request->session()->get('userName');
       $fromDate=date('Y-m-d',strtotime($request->input('fromDate')));
       $toDate=date('Y-m-d',strtotime($request->input('toDate')));
     // print_r($fromDate);
    //  print_r($toDate);
       $officenoteDetails = CaseFollowUpModel::getofficenoteDetails($applicationId,$fromDate,$toDate);
    //   print_r("mini");
    $count_a = count($officenoteDetails);
     $officenotes = "";
         $courtdirection = "";
     if($officenoteDetails){
           for($i=0; $i<$count_a;  $i++){
         $officenoteDate = explode("-",$officenoteDetails[$i]->date);
        $yy = $officenoteDate[0];
        $mm = $officenoteDate[1];
        $dd = $officenoteDate[2];
        $officenoteDate = $dd.'-'.$mm.'-'.$yy;

        if($officenoteDetails[$i]->officenote != '')
        {
          $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->officenote,1));
          $officenotes.=  '<w:br /><w:br />'.$officenoteDate.' :'. '<w:br /><w:br />'.$dd.'<w:br />';
         }
      else if ($officenoteDetails[$i]->courtdirection != '')
        {
          $cd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($officenoteDetails[$i]->courtdirection,1));
          $courtdirection.=  '<w:br /><w:br />'.$officenoteDate.' :'. '<w:br /><w:br />'.$cd.'<w:br />';
        }
      }
  }
      /*  $data['applicantDetails'] = $this->case->getTopApplicantDetails($applicationId)[0];
        $data['respondantDetails'] = $this->case->getTopRespondantDetails($applicationId)[0];
        $data['applicantadvocateDetails'] = $this->case->getAdvDetails($data['applicantDetails']->advocateregno)[0];

      $data['respondantadvocateDetails'] = $this->case->getAdvDetails($data['respondantDetails']->advocateregno)[0];*/
      $establishcode=$request->session()->get('EstablishCode');

       $data['causelisttitle']=DB::table('establishment')->select('causelist_title')->where('establishcode',$establishcode)->get()[0];


       $causelisttitle=  $data['causelisttitle']->causelist_title;
      //dd($causelisttitle);
      // $causelist_title=$data['causelist_title']->causelist_title;
       $path= "ordersheet1";
       $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
       $my_template->setValue("applicationId",  $applicationId);
       $my_template->setValue("officenotes",$officenotes);
       $my_template->setValue("courtdirection",$courtdirection);
       $my_template->setValue("causelisttitle",$causelisttitle);

      $my_template->saveAs(storage_path("order.docx"));
        $file= storage_path("order.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);
      }

  }
}
public function viewordergenerationform(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
         return view('casefollowup.ordergeneration',$data)->with('user',$request->session()->get('userName'));

     }

public  function ordergenerate(Request $request)
{
      $arr=explode('-',$request->input('applTypeName'));
        $applicationId=$arr[1].'/'.$request->applicationId;
        $ordertemplate=$request->ordertemplate;
        $data['applicantDetails'] = $this->case->getTopApplicantDetails($applicationId)[0];
        $data['respondantDetails'] = $this->case->getTopRespondantDetails($applicationId)[0];
        $data['applicantadvocateDetails'] = $this->case->getAdvDetails($data['applicantDetails']->advocateregno)[0];

        $data['respondantadvocateDetails'] = $this->case->getAdvDetails($data['respondantDetails']->advocateregno)[0];

        $applicantname= $data['applicantDetails']->applicantname;
        $applicantadvocate=$data['applicantadvocateDetails'] ->advocatename;
        $respondantname=$data['respondantDetails']->respondname;
        $respondantadvocate= $data['respondantadvocateDetails'] ->advocatename;

       $path= "ordersheet";
       $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/'.$path.'.docx'));
        $my_template->setValue("applicationId",  $request->applicationId);
        $my_template->setValue("ordertemplate",$ordertemplate);
        $my_template->setValue("applicantname",$applicantname);
        $my_template->setValue("applicantadvocate",  $applicantadvocate);
        $my_template->setValue("respondantname",$respondantname);
        $my_template->setValue("respondantadvocate",$respondantadvocate);
        $my_template->saveAs(storage_path("order.docx"));
        $file= storage_path("order.docx");
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,"order.docx", $headers)->deleteFileAfterSend(true);
}

public function viewgeneratenotice(Request $request)
     {
         $user = $request->session()->get('userName');
         $establishcode = $request->session()->get('EstablishCode');
         $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();

         $data['OrderType'] = $this->IANature->getOrderType();
         $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);
         $data['Benches'] =  $this->IANature->getBenches();
        return view('casefollowup.generatenotice',$data)->with('user',$request->session()->get('userName'));

     }




public  function generatenoticedocument(Request $request)
{

  $request->validate([
            'applTypeName' => 'required',
          'applicationId' => 'required|max:20',
        //  'respondantDetails' => 'required',
          'noticetype' => 'required'
            ]);

      $arr=explode('-',$request->input('applTypeName'));
      $applicationId=$arr[1].'/'.$request->applicationId;
      $appltypecode=DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$arr[1]'")[0]->appltypecode;
      $noticetypecode = $request->input('noticetype');
      $respondantSrNo = $request->input('respondantDetails');
      $appltypedisplay=DB::SELECT("SELECT appltypedisplay from applicationtype where appltypeshort='$arr[1]'")[0]->appltypedisplay;
      $noticetypeDetails = $this->IANature->getNoticeTypeByID($noticetypecode,$appltypecode);
      $establishmentname = Session::get('establishfullname');
      $hearingdate=DB::SELECT("SELECT hearingdate from dailyhearing  where applicationid='$applicationId' and ordertypecode is not null order by hearingdate DESC LIMIT 1")[0]->hearingdate;
      $hearingdate_new= date("d-m-Y", strtotime($hearingdate));

      $bench=DB::SELECT("SELECT dh.benchcode,b.judgeshortname from dailyhearing dh
inner join bench as b on dh.benchcode=b.benchcode where applicationid='$applicationId' and hearingdate='$hearingdate'");
      $benchcode= $bench[0]->benchcode;            
      $judgeshortname=$bench[0]->judgeshortname;
       $benchjudge = DB::select("SELECT * from benchjudgeview where benchcode ='$benchcode' " );
       

              $judgename = "";
            if ($benchjudge[0]->judgescount == 1)
            {
                $judgename = $benchjudge[0]->judgename . '  ,' . "\n" . $benchjudge[0]->judgedesigname;
            }
            else
            {
                for ($i = 0;$i < $benchjudge[0]->judgescount;$i++)
                {
                    $judgename = $judgename . "\n" . $benchjudge[$i]->judgename . '  ,' .  $benchjudge[$i]->judgedesigname."\nAND";
               
                }

            }
            $dd= preg_replace('/[\n]/', '<w:br />', htmlspecialchars($judgename, 1));
          
           if ($benchjudge[0]->judgescount != 1)
           {
            $dd= rtrim($dd,"AND");
           }
            $judgename =  $dd ;
         
         
      //dd($noticetypeDetails);
      if($noticetypeDetails[0]->template_name == ""){
            return redirect()->back()->with('response-error','No template found');
           }else{


               $path = $noticetypeDetails[0]->template_name;
               $name = $noticetypeDetails[0]->noticetypedesc;

               if($appltypecode >='1' && $appltypecode <'100')
               {
      $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/notices/Notices'.$appltypecode.'/'.$path.'.docx'));
	  
               }
               /*elseif($appltypecode=='2')
              {
      $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/notices/Notices2/'.$path.'.docx'));

              }
              elseif($appltypecode=='3')
              {
      $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/notices/Notices3/'.$path.'.docx'));
              }*/
              else{

      $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/notices/Notices100/'.$path.'.docx'));
              }
       }

         
       $application = $this->case->getApplicationId($applicationId,$user='');
  $applicant_details = DB::select("select * from causetitleapplicant where applicationid = '". $applicationId ."'");
       $respondent_details = DB::select("select * from causetitlerespondent where applicationid = '". $applicationId ."'");

 $count_a = count($applicant_details);
 $count_r = count($respondent_details);

       $applicant = "";
       $respondent = "";
   $applicantadvocate = $applicant_details[0]->advocatename;
if($applicant_details){
                           for($i=0; $i<$count_a;  $i++){
                            //  $dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars($applicant_details[$i]->applicant_address,1));
                           //   $applicant.=  '<w:br /><w:br />'.$applicant_details[$i]->applicantsrno. ' . '. htmlspecialchars($applicant_details[$i]->applicantname,1).'<w:br />'.$dd.'<w:br />';
                     
$dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars(strtoupper($applicant_details[$i]->petcausetitle),1));
$applicant.=  '<w:br /><w:br />'.strtoupper($applicant_details[$i]->applicantsrno). ' . '.$dd.'<w:br /><w:br />';

     }
                      }
 $respondantadvocate = $respondent_details[0]->advocatename;
 if($respondent_details){
                       for($j=0; $j<$count_r;  $j++){
//  $dd1 = preg_replace('/[\n]/','<w:br />',htmlspecialchars($respondent_details[$j]->respondent_address,1));
//  $respondent.=  '<w:br /><w:br />'. $respondent_details[$j]->respondsrno .' . '.htmlspecialchars($respondent_details[$j]->respondname,1).'<w:br />'.$dd1.'<w:br />';

$dd = preg_replace('/[\n]/','<w:br />',htmlspecialchars(strtoupper($respondent_details[$j]->rescausetitle),1));
$respondent.=  '<w:br /><w:br />'. strtoupper($respondent_details[$j]->respondsrno) .' . '.$dd.'<w:br /><w:br />';



 }
            }

         $establishmentname=strtoupper($establishmentname); 
         $applicantadvocate=strtoupper($applicantadvocate);      
        // $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('ordertemplates/notices/'.$path.'.docx'));
     //    $my_template->setValue("TYPENAME", $application[0]->appltypedisplay);
          $my_template->setValue("hearingdate", $hearingdate_new);
         $my_template->setValue("establishmentname", $establishmentname);

         $my_template->setValue("appltypedisplay", $appltypedisplay);
         $my_template->setValue("APPLICATIONNO", $application[0]->applicationsrno);

         $my_template->setValue("APPLICATIONYEAR", $application[0]->applicationyear);
         $my_template->setValue("applicant_details",  $applicant);
    $my_template->setValue("applicantadvocate",$applicantadvocate);
         $my_template->setValue("respondent_details",$respondent);
         $my_template->setValue("respondantadvocate",$respondantadvocate);
         $my_template->setValue("judgename", $judgename);

        //$my_template->saveAs(storage_path("notice.docx"));
$my_template->saveAs(storage_path($path));

        //$file= storage_path("notice.docx");
$file= storage_path($path);
        $headers = array(
                         'Content-Type: application/msword',
                          );
    return response()->download($file,$name.".docx", $headers)->deleteFileAfterSend(true);
  }





  public function getHearingDetailsByApplication(Request $request)
    {
$request->validate([
         'applicationId' => 'required|max:20',

            ]);

       $applicationId=$request->applicationId;
       $var=explode('/',$applicationId);
       $appltypeshort=$var[0];
  $appltypecode=DB::SELECT("SELECT appltypecode from applicationtype where appltypeshort='$appltypeshort'")[0]->appltypecode;
       $data['hearingDetails'] = IADocumentModel::getHearingDetailsByApplication($applicationId);

        $noticetype='';
       if (count($data['hearingDetails']) > 0)
      {
       $ordertypecode = $data['hearingDetails'][0] ->ordertypecode;
       
       $data['noticetype'] = $this->IANature->getNoticeType($ordertypecode,$appltypecode);
       }
       echo json_encode(array($data['hearingDetails'], $data['noticetype']));
    //   echo json_encode($data['hearingDetails']);
     }


}
