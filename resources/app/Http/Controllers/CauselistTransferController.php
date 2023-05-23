<?php

namespace App\Http\Controllers;

use App\Causelist;
use App\Causelist1;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\Causelisttype;
use Illuminate\Support\Facades\DB;
use App\IADocument as IADocumentModel;
use App\causelistconnecttemp;
use App\ModuleAndOptions;
  use PDF;



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
        $this->module= new ModuleAndOptions();
        //$this->case = new CaseManagementModel();
    }


  public function printcauselistformat()
    {

    }

    public function index(Request $request)
    {
        $user = $request->session()->get('userName');     
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['benchjudge'] = $this->IANature->getbenchjudge();  
        $data['purpose'] =  $this->IANature->getListPurpose();
        $data['CourtHalls'] = $this->IANature->getCourthalls();
        $data['causelisttype']=Causelisttype::orderBy('causelisttypecode')->get();
        $data['list']=$this->IANature->getlist();

        $data['title'] = 'Transfer Causelist';
        return view('Causelist.CauselistTransfer',$data);
        
    }

public function getcauselistapplforTransfer(Request $request){
       $causelistcode = $_POST['causelistcode'];

       $data['causelist'] = DB::select("select  distinct applicationid, causelistcode,  causelistsrno, listpurpose from causelistview where causelistcode = " . $causelistcode . " and istransferred is null order by causelistsrno " );
     echo json_encode($data['causelist']);

    }

  public function getFinalizedcauselist(Request $request){
   
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
			   if($tocauselistcode==0)
               {
				   $value = DB::select("select max(causelistcode) as last from causelisttemp");
                        $tocauselistcode = $value[0]->last + 1; 
                        $causelisttemp = new Causelist1([
                        'causelistcode' => $tocauselistcode,
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
			  
			 if($savevalue) {
			   for($i = 0; $i < $arrlen; $i++)
				{
					list($applicationid, $fromcauselistcode) = explode('::', $caseArr[$i]);              
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
						DB::insert($query2);
						
						$k++;
					}
                }
		      }
		}else{
			  for($i = 0; $i < $arrlen; $i++)
				{
					list($applicationid, $fromcauselistcode) = explode('::', $caseArr[$i]);              
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
						DB::insert($query2);
						$k++;
                }
		      }
		   }
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



    
}