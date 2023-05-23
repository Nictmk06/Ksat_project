<?php

namespace App\Http\Controllers;
use App\CaseManagementModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Support\Facades\DB;
use App\ModuleAndOptions;

class ApplicationDetailsController extends Controller
{

    public function __construct()
{
     $this->module= new ModuleAndOptions();
}
    public function index(Request $request)
    {
     $title = 'Searchapplication';
     $applications = db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedefault')->orderBy('appltypecode', 'asc')->get();
//      $data['usermenu'] =$this->module->usermenu($request->Session()->get('userName'));
//      $data['usermenumodule'] = $this->module->usermenumodule($request->Session()->get('userName'));

     return view('searchapplication')->with(['applications'=>$applications,'title'=>$title]);

    }
  /*  public function appstatus(Request $request)
    {
    	$title = 'Searchapplication';
    	$applications = db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedefault')->orderBy('appltypecode', 'asc')->get(); // result from table applicationtype

        $appnum= $request->input('appnum');

    	$apptype= $request->input('apptype');

        $apptype_explode = explode('-', $apptype);

         $apptype_code= $apptype_explode[1];

         $find_appnum = $apptype_code."/".$appnum;


    $results = DB::table('applicationsummary1')->where('applicationid', $find_appnum)->get();
   //print_r($results);

    if ($results->isEmpty())
    {       $error=" Case Details Not Found";
// $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
  //      $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
           return redirect('searchapplication')->with(compact('error','appnum','apptype'));

    }
    else{
         if($apptype_code != 'OA')
         {
            $applagainst = DB::table('applagainst')->where('applicationid', $find_appnum)->get()[0];
         }else
            $applagainst = "";
         $isconnected = $results[0]->connectedcase;
        if($isconnected == 1){
            $connectedappl = DB::table('connectedappldtls')->where('applicationid', $find_appnum)->get();
        }else{
            $connectedappl="";
        }
          $appindexes = DB::table('applicationindex')->where('applicationid', $find_appnum)->orderBy('documentno', 'ASC')->get();
    $applicants = DB::table('applicantsummary1')->where('applicationid', $find_appnum)->get();
    $respondents = DB::table('respondentsummary1')->where('applicationid', $find_appnum)->get();
    $applreliefs = DB::table('applrelief')->where('applicationid',$find_appnum)->orderBy('reliefsrno', 'ASC')->get();
    $receipts = DB::table('receipt')->where('applicationid',$find_appnum)->get();
    $hearings = DB::table('hearingsummary1')->where('applicationid',$find_appnum)->get();
    $iaotherdocuments = DB::table('iaotherdocumentview')->where('applicationid',$find_appnum)->get();
 //   $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
    return view('searchapplication',compact('applications', 'results', 'appindexes' ,'applicants','respondents','applreliefs','receipts','hearings','appnum','apptype','applagainst','connectedappl','iaotherdocuments'));
        }
    }*/

	public function appstatus(Request $request)
    {
    	$title = 'Searchapplication';
    	$applications = db::table('applicationtype')->select('appltypedesc','appltypecode','appltypeshort','appltypedefault')->orderBy(			'appltypecode', 'asc')->get(); // result from table applicationtype
		$establishcode = $request->session()->get('EstablishCode');
		$appnum= $request->input('appnum');
 	    $appnumyear_explode = explode('/', $appnum);
		$appnum1= $appnumyear_explode[0];
	    $applyear= $appnumyear_explode[1];
    	$apptype= $request->input('apptype');
		$apptype_explode = explode('-', $apptype);
		$appltypeshort= $apptype_explode[1];

        $find_appnum1 = $appltypeshort."/".$appnum;
        //print_r($find_appnum1);
		$find_appnum ="";
		
		try{
			
			//Changed by NIC - start
			
			$appcount = DB::select("select count(*) as judgementcount from judgement where  applicationid='".$find_appnum1."' and establishcode=:establishcode",['establishcode' => $establishcode]);
			$appcount= $appcount[0]->judgementcount;
			if($appcount > 0){
				$judgementDetails = DB::table('judgement')->select('judgement.*')
                ->where('applicationid','=',$find_appnum1)
                 ->where('establishcode','=',$establishcode)
                 ->orderBy('judgementdate', 'desc')
                 ->take(1)
                ->get();
				$judgement_path=$judgementDetails[0]->judgement_path;
				$path='/usr/local/apache24/htdocs/Judgements/'.$judgement_path;
				if (file_exists($path)){
				$fileName=$find_appnum1.'.pdf';
				$fileData = base64_encode(file_get_contents($path));
				//header("Content-disposition:attachment; filename=$fileName");
				//readfile($main_url);
				}
				else{
					$fileName='';
	                                $fileData='';
				}
			}
			else{
				$fileName='';
				$fileData='';
			}
			//Changed by NIC - end
			
			
			$applications1 = db::table('applicationtype')->select('appltypecode')->where('appltypeshort', $appltypeshort)->get();
			$appltypecode = $applications1[0]->appltypecode;
			$mainapplId= DB::select("select applicationid from application where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."' and establishcode='$establishcode'");
			//dd($mainapplId);

			if(count($mainapplId) == 0)
			{
				$applId = DB::select("select applicationid from groupno where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."' ");
				if(count($applId) > 0)
				{
					$find_appnum = $applId[0]->applicationid;
				}
				else{
					$find_appnum ="";
				}
			}
			else{
       
				if($mainapplId[0]->applicationid != $find_appnum1)
				{
					// $find_appnum="";
					$find_appnum = $mainapplId[0]->applicationid;
				}
				else
				{
					$find_appnum = $mainapplId[0]->applicationid;
      
				}
			}
			//print_r($find_appnum);
			if ($find_appnum == "")
			{
					/*  $results = DB::table('applicationdisposed')->where([
					['applicationid', '=', $find_appnum1],    
					['establishcode', '=', $establishcode],
					['enteredfrom', '=', 'Legacy']
					])->get();*/
				  //print_r($results);
				$mainapplIddisposed= DB::select("select applicationid from applicationdisposed where applicationsrno <= '".$appnum1."' and  applicationtosrno >=  '".$appnum1."' and applicationyear='".$applyear."' and appltypecode='".$appltypecode."'");
				if($mainapplIddisposed!=null){
					if($mainapplIddisposed[0]->applicationid != $find_appnum1)
					{
						//$find_appnum="";
						$find_appnum = $mainapplIddisposed[0]->applicationid;
						$results = DB::table('applicationdisposed')->where('applicationid', $find_appnum)->where([
								['establishcode', '=', $establishcode],
								['enteredfrom', '=', 'Legacy']
							])->get();
					}
					else{
						$results = DB::table('applicationdisposed')->where('applicationid', $find_appnum1)->where([
							['establishcode', '=', $establishcode],
							['enteredfrom', '=', 'Legacy']
						])->get();
					}
				}
				else{
					$results = DB::table('applicationdisposed')->where('applicationid', $find_appnum1)->where([
						['establishcode', '=', $establishcode],
						['enteredfrom', '=', 'Legacy']
					])->get();
				} 


				if (count($results)>0)
				{
				   /*if($apptype != 'OA')
					 {
						$applagainst = DB::table('applagainst')->where('applicationid', $find_appnum1)->get();
						if ($applagainst->get('referapplid')=='')
						{
						 $applagainst ="";
						}
						else{
						  $applagainst = DB::table('applagainst')->where('applicationid', $find_appnum1)->get()[0];
						}
					 }else
					  $applagainst = "";
				  $isconnected = $results->get('connectedcase');
				  if($isconnected == 1){
					  $connectedappl = DB::table('connectedappldtls')->where('applicationid', $find_appnum1)->get();
				  }else{
					  $connectedappl="";
				  }
			  */
				//    $appindexes = DB::table('applicationindex')->where('applicationid', $find_appnum1)->orderBy('documentno', 'ASC')->get();
			   //$applicants = DB::table('applicantsummary1')->where('applicationid', $find_appnum1)->get();
			   //$respondents = DB::table('respondentsummary1')->where('applicationid', $find_appnum1)->get();
			  // $applreliefs = DB::table('applrelief')->where('applicationid',$find_appnum1)->orderBy('reliefsrno', 'ASC')->get();
			  // $receipts = DB::table('receipt')->where('applicationid',$find_appnum1)->get();
			  // $hearings = DB::table('hearingsummary1')->where('applicationid',$find_appnum1)->get();
			   //$iaotherdocuments = DB::table('iaotherdocumentview')->where('applicationid',$find_appnum1)->get();
			   //   $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
			   //        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
					return view('searchapplication_disposed', compact('applications', 'results', 'appnum','apptype','appltypeshort','fileName','fileData'));


				}
				else
				{
					$error=" Case Details Not Found";
					return redirect('searchapplication')->with(compact('error','appnum','apptype','applyear','establishcode'));
					// return redirect('casestatus')->with(compact('error','appnum','apptype'));

				}
			}
			else{
				$results = DB::table('applicationsummary1')->where('applicationid', $find_appnum)->where('establishcode', $establishcode)->get();
				//  print_r($results);
				if ($results->isEmpty())
				{       
					$error=" Case Details Not Found";
					// $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
					  //      $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
					return redirect('searchapplication')->with(compact('error','appnum','apptype','applyear','establishcode'));

				}
				else{
					/* if($appltypeshort != 'OA')
					{
						$applagainst = DB::table('applagainst')->where('applicationid', $find_appnum)->get()[0];
					} */
					$appltypeshortF=DB::table('applicationtype')->where('appltypeshort', $appltypeshort)->get()[0];
					 if($appltypeshortF->referflag != 'N')
					{
						$applagainst = DB::table('applagainst')->where('applicationid', $find_appnum)->get()[0];
						
					}
					else
						$applagainst = "";
					$isconnected = $results[0]->connectedcase;
					if($isconnected == 1){
						$connectedappl = DB::table('connectedappldtls')->where('applicationid', $find_appnum)->get();
						$other_connectedid="";
						$mainapplication_no="";
					}
					else{
						$mainapplicationid=DB::SELECT("SELECT c.applicationid from applicationsummary1 as ap
									inner join connected1  c on ap.applicationid=c.conapplid
									where ap.applicationid='$find_appnum'");

						if($mainapplicationid==null)
						{
							$connectedappl="";
							$other_connectedid="";
							$mainapplication_no="";
						}
						else
						{
							$mainid=$mainapplicationid[0]->applicationid;
							$connecetedapplications=DB::SELECT("SELECT connapplno FROM connecetdappldtls WHERE applicationid='$mainid'");

							if($connecetedapplications==null)
							{
								$other_connectedid="";
								$mainapplication_no="";
							}
							else
							{
								$mainapplication_no=DB::SELECT("SELECT applicationo
								from applicationsummary1
								where applicationid='$mainid'");
								$other_connectedid=$connecetedapplications;
								//dd($mainapplication_no);
							}

						}
						$connectedappl="";           
					}

					$appindexes = DB::table('applicationindex')->where('applicationid', $find_appnum)->orderBy('documentno', 'ASC')->get();
					$applicants = DB::table('applicantsummary1')->where('applicationid', $find_appnum)->get();
					$respondents = DB::table('respondentsummary1')->where('applicationid', $find_appnum)->get();
					$applreliefs = DB::table('applrelief')->where('applicationid',$find_appnum)->orderBy('reliefsrno', 'ASC')->get();
					$receipts = DB::table('receipt')->where('applicationid',$find_appnum)->get();
					$hearings = DB::table('hearingsummary1')->where('applicationid',$find_appnum)->get();
					$iaotherdocuments = DB::table('iaotherdocumentview')->where('applicationid',$find_appnum)->get();
					//   $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
					//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
					return view('searchapplication',compact('mainapplication_no','other_connectedid','applications', 'results', 'appindexes' ,'applicants','respondents','applreliefs','receipts','hearings','appnum','apptype','applagainst','connectedappl','iaotherdocuments','fileName','fileData'));
				}
			}
		}catch(Exception $e){
			$error=" Something went wrong";
			return redirect('casestatus')->with(compact('error','appnum','apptype','applyear','establishcode'));
		}
	}
}
