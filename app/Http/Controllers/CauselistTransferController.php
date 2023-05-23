<?php

namespace App\Http\Controllers;

use App\Causelist;
use App\Causelist1;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\Causelisttype;
use Illuminate\Support\Facades\DB;
use App\causelistconnecttemp;
use App\UserActivityModel;
class CauselistTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->IANature = new IANature();
        $this->case = new CaseManagementModel();
		$this->UserActivityModel = new UserActivityModel();
    }

   public function printcauselistformat()
    {

    }

    public function index(Request $request)
    {
		  $establishcode = $request->session()->get('EstablishCode');
        $user = $request->session()->get('userName');     
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);
        $data['purpose'] =  $this->IANature->getListPurpose();
        $data['CourtHalls'] = $this->IANature->getCourthalls($establishcode);
        $data['causelisttype']=Causelisttype::orderBy('causelisttypecode')->get();
        $data['list']=$this->IANature->getlist();

        $data['title'] = 'Transfer Causelist';
        return view('Causelist.causelisttransfer',$data);
        
    }

public function getcauselistapplforTransfer(Request $request){
	  $request->validate([
   	  'causelistcode' => 'required|numeric',
        ]);
       $causelistcode = $_POST['causelistcode'];

       $data['causelist'] = DB::select("select  distinct applicationid, causelistcode,  causelistsrno, listpurpose from causelistview where causelistcode = " . $causelistcode . " and istransferred is null order by causelistsrno " );
     echo json_encode($data['causelist']);

    }

  public function getFinalizedcauselist(Request $request){
      $request->validate([
   	 	 'hearingdate' => 'required | date', 
               ]);
  $hearingdate = date('Y-m-d',strtotime($_POST['hearingdate']));
    
  $query1 = "select distinct causelistcode, judgeshortname||' : '||courthalldesc||' : '||causelistdesc||' : List-'|| listno as causelistdesc,coalesce(finalizeflag,'N') as finalize , coalesce(postedtocourt,'N') as postedtocourt from causelistview where finalizeflag = 'Y' and causelistdate = '" .date('Y-m-d',strtotime($hearingdate)) . "'";
    $data['causelist'] = DB::select($query1);               
    echo json_encode($data['causelist']);
    }


  public function transferCauseList(Request $request)
    {
      if (isset($_POST['caseSelect']))
        {
            $arrlen             = count($request->input('caseSelect'));
            $caseArr            = $request->input('caseSelect');
            $tocauselistcode  = $request->input('causelistcode');
            $k = 0;
			$establishcode = $request->session()->get('EstablishCode');
			try{
			   DB::beginTransaction();
			   if($tocauselistcode==0){
				 //  $value = DB::select("select max(causelistcode) as last from causelisttemp");
                      //  $tocauselistcode = $value[0]->last + 1; 
                        $causelisttemp = new Causelist1([
                       // 'causelistcode' => $tocauselistcode,
                        'causelisttypecode'=> $_POST['causetypecode'],
                        'benchcode'=> $_POST['benchJudge'],
                        'courthallno'=>$_POST['courthall'],
                        'causelistdate'=>date('Y-m-d',strtotime($_POST['tohearingDate'])),
                        'listno'=>$_POST['listno'],
                        'benchtypename'=>$_POST['benchCode'],
                        'createdon' => date('Y-m-d H:i:s'),
                        'createdby' => session()->get('username'),
                        'establishcode' => $establishcode
                        ]);
                        $savevalue = $causelisttemp->save();
						$tocauselistcode = $causelisttemp->causelistcode;
			 if($savevalue) {
			   for($i = 0; $i < $arrlen; $i++)
				{
					list($applicationid, $fromcauselistcode) = explode('::', $caseArr[$i]);              
					
					$applicationid=trim($applicationid);
					$rec_arr = array();
					$rec_arr['istransferred']    = 'Y';
					if ( DB::table('causelistappltemp')->where('applicationid', $applicationid)->where('causelistcode', $fromcauselistcode)->update($rec_arr) );
					{
						DB::table('causelistconnecttemp')->where('applicationid', $applicationid)->where('causelistcode', $fromcauselistcode)->update($rec_arr);
						
						$query1 = "insert into causelistappltemp select $tocauselistcode,applicationid, purposecode, $i+1,
							iaflag, createdby, connected, updatedby, appltypecode, enteredfrom, createdon, updatedon,
							appautoremarks, resautoremarks, appuserremarks, resuserremarks from causelistappltemp
							where causelistcode=$fromcauselistcode and applicationid='".$applicationid."'";             
						DB::insert($query1);
						
						$query2 = "insert into causelistconnecttemp select $tocauselistcode,applicationid, purposecode, 
						causelistsrno, iaflag, createdon, createdby, connected, type, conapplid, updatedby, 
						updatedon, appautoremarks, resautoremarks, appuserremarks, resuserremarks
						FROM public.causelistconnecttemp
							where causelistcode=$fromcauselistcode and applicationid='".$applicationid."'";             
						//print_r($query2);
						DB::insert($query2);
						
						$query3 ="delete from dailyhearing where causelistcode='$fromcauselistcode' and applicationid='".$applicationid."'";
						//print_r($query3);
						DB::delete($query3);
						DB::delete("delete from dailyhearing where causelistcode=$fromcauselistcode and mainapplicationid='".$applicationid."'");
						
						$k++;
					}
                }
		      }
		}else{
			  for($i = 0; $i < $arrlen; $i++)
				{
					list($applicationid, $fromcauselistcode) = explode('::', $caseArr[$i]);              
					$applicationid=trim($applicationid);
					$rec_arr = array();
					$rec_arr['istransferred']    = 'Y';
					if ( DB::table('causelistappltemp')->where('applicationid', $applicationid)->where('causelistcode', $fromcauselistcode)->update($rec_arr) );
					{
						DB::table('causelistconnecttemp')->where('applicationid', $applicationid)->where('causelistcode', $fromcauselistcode)->update($rec_arr);
						$query1 = "insert into causelistappltemp select $tocauselistcode,applicationid, purposecode,null,
							iaflag, createdby, connected, updatedby, appltypecode, enteredfrom, createdon, updatedon,
							appautoremarks, resautoremarks, appuserremarks, resuserremarks from causelistappltemp
							where causelistcode=$fromcauselistcode and applicationid='".$applicationid."'";             
						DB::insert($query1);
						$query2 = "insert into causelistconnecttemp select $tocauselistcode,applicationid, purposecode, 
						causelistsrno, iaflag, createdon, createdby, connected, type, conapplid, updatedby, 
						updatedon, appautoremarks, resautoremarks, appuserremarks, resuserremarks
						FROM public.causelistconnecttemp
							where causelistcode=$fromcauselistcode and applicationid='".$applicationid."'";             
					//	print_r($query2);
						DB::insert($query2);
						//print_r($fromcauselistcode);
					//	print_r($applicationid);
					$query3 ="delete from dailyhearing where causelistcode='$fromcauselistcode' and applicationid='".$applicationid."'";
						//print_r($query3);
				    	DB::delete($query3);
						DB::delete("delete from dailyhearing where causelistcode=$fromcauselistcode and mainapplicationid='".$applicationid."'");
						$k++;
                }
		      }
		   }
		   
		     $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Transfer Cause List' ;
		     $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		   DB::commit();
			return back()->with('success', $k . ' Case(s) Transferrred.');
            
        }catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return back()->with('error', 'Someting wrong, No Case(s) Transferrred.');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return back()->with('error', 'Someting wrong, No Case(s) Transferrred.');
            }
        }else
        {
            return back()->with('success', ' No Case(s) Transferrred.');
        }		
			
    }


public function movecauselist(Request $request)
    {
       $establishcode = $request->session()->get('EstablishCode');
        $user = $request->session()->get('userName');     
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['benchjudge'] = $this->IANature->getbenchjudge($establishcode);  
        $data['purpose'] =  $this->IANature->getListPurpose();
        $data['CourtHalls'] = $this->IANature->getCourthalls($establishcode);
        $data['causelisttype']=Causelisttype::orderBy('causelisttypecode')->get();
        $data['list']=$this->IANature->getlist();

        $data['title'] = 'Move Causelist';
        return view('Causelist.movecauselist',$data);
        
    }
	
	
	 public function moveCauseListSave(Request $request)
    {
		$request->validate([
   	     'benchJudge' => 'required',
         'causelist' => 'required|numeric',
         'courthall'=> 'required|numeric|max:10',
	     'tohearingDate' => 'required | date', 
		  'listno' => 'required | numeric', 
		 'benchCode' => 'required',  
		 
               ]);
		$causelistcode = $request->input('causelist');
        try{
			DB::beginTransaction();
			  
			       $cl_arr['causelisttypecode']   = $_POST['causetypecode'];
					$cl_arr['benchcode']    = $_POST['benchJudge'];
					$cl_arr['courthallno']    = $_POST['courthall'];
					$cl_arr['causelistdate']    = date('Y-m-d',strtotime($_POST['tohearingDate']));
					$cl_arr['listno']    = $_POST['listno'];
					$cl_arr['benchtypename']    = $_POST['benchCode'];
					
									
			 DB::table('causelisttemp')->where('causelistcode', $causelistcode)->update($cl_arr) ;
			 $useractivitydtls['applicationid_receiptno'] =$causelistcode;
		     $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
			 $useractivitydtls['activity'] ='Move Cause List' ;
		     $useractivitydtls['userid'] = $request->session()->get('username');
			 $useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			 $useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			 $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
		   DB::commit();
			return back()->with('success', 'Cause List Moved Successfully');
        }catch (\Exception $e) {
                DB::rollback();
                throw $e;
                return back()->with('error', 'Someting went wrong');
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
                return back()->with('error', 'Someting went wrong');
            }
        }	
			
    
    
}