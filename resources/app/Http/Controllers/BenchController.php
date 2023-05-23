<?php

namespace App\Http\Controllers;

use App\Causelist;
use App\Causelist1;
use App\Bench;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\Causelisttype;
use Illuminate\Support\Facades\DB;
use App\IADocument as IADocumentModel;
use App\causelistconnecttemp;
use App\ModuleAndOptions;

class BenchController extends Controller{
	
    public function __construct()
    {
	
    $this->bench = new bench();    
	}
	public function index(Request $request)
    {
		$bench = Bench::all();
		
		$data['BenchType'] =  $this->bench->getBenchType();
		$data['bench_list'] = $this->bench->getBenchList();
        $data['jdesig'] = $this->bench->getJudgeDesignation();
		$data['jname'] = $this->bench->getJudge();
	
        return view('bench.create',$data)->with('user',$request->session()->get('userName'));
    
		//index
	}
	
	function create(Request $request)
	{
		//create form;
		$bench = Bench::all();
		$data['bench_list'] = $this->bench->getBenchList();
        $data['BenchType'] =  $this->bench->getBenchType();
        $data['jdesig'] = $this->bench->getJudgeDesignation();
		$data['jname'] = $this->bench->getJudge();
		
	
		return view('bench.create',$data)->with('user',$request->session()->get('userName'));
	}
	function fetch(Request $request)
    {
     $select = $request->get('select');
     $value = $request->get('value');
     $dependent = $request->get('dependent');
     $data = DB::table('benchtype')
       ->where($select, $value)
       ->get();
     $output = '<option value="">Select '.ucfirst($dependent).'</option>';
     foreach($data as $row)
     {
      $output .= '<option value="'.$row->$dependent.'">'.$row->$dependent.'</option>';
     }
     echo $output;
    }
	public function store(Request $request)
	{
		
        
		 $request->validate([
            'benchtypename' =>'required',
            		
        ]);
		
			$jsort=DB::table('judge')
			 ->select('judge.judgeshortname')
             ->where('judgecode','=', $request->benchJudge)->first();
			
			$jdesig=DB::table('judgedesignation')->select('judgedesigshort')->where('judgedesigcode','=', $request->judgeDesig)->first();
			
			$jsort_one=DB::table('judge')
			 ->select('judge.judgeshortname')
             ->where('judgecode','=', $request->benchJudge1)->first();
			
			$jdesig_one=DB::table('judgedesignation')->select('judgedesigshort')->where('judgedesigcode','=', $request->judgeDesig1)->first();
			
			$jsort_two=DB::table('judge')
			 ->select('judge.judgeshortname')
             ->where('judgecode','=', $request->benchJudge2)->first();
			 
			$jdesig_two=DB::table('judgedesignation')->select('judgedesigshort')->where('judgedesigcode','=', $request->judgeDesig2)->first();
			 
			 
			$jsort_three=DB::table('judge')
			 ->select('judge.judgeshortname')
             ->where('judgecode','=', $request->benchJudge3)->first();
			
			$jdesig_three=DB::table('judgedesignation')->select('judgedesigshort')->where('judgedesigcode','=', $request->judgeDesig3)->first();
			
			
			$jsort_four=DB::table('judge')
			 ->select('judge.judgeshortname')
             ->where('judgecode','=', $request->benchJudge4)->first();
			
			$jdesig_four=DB::table('judgedesignation')->select('judgedesigshort')->where('judgedesigcode','=', $request->judgeDesig4)->first();
			
			$jsname1=$jsort->judgeshortname.''."({$jdesig->judgedesigshort})";
				
			 $bcode=DB::table('bench')->max('benchcode');
			 $bcode++;
			
			if ($request->benchtypename=='1')
				{
					$count = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge)->count();
					$count_1 = DB::table('bench')->where('judgeshortname','=',$jsname1)->where('display','=','Y')->count();
					
					if($count_1) {
						$request->session()->flash('alert-warning', 'Bench Already with this Judge Created');
						return redirect()->back()->withInput($request->all());
						
					}
					else{
					$task = bench::create(['benchcode' => $bcode,
					'benchtypename' => 'Single Bench',
					'judgescount'=>'1',
					'fromdate'=>$request->FromDate,
					'todate'=>$request->todate,
					'display'=>'Y',
					'judgecode'=>$request->benchJudge,
					'judgeshortname'=>$jsort->judgeshortname.''."({$jdesig->judgedesigshort})"]);
					//'createdby'=>$jsort->judgeshortname.''."({$jsort->judgedesigshort})",
					//'updatedby'=>$request->judgeDesig]);
					$request->session()->flash("Data Saved Succussfully");
					
					DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig]);
					$request->session()->flash('alert-success', 'Bench was successful created!');
					return redirect('create'.$task->id);
					}
					
				}
			elseif($request->benchtypename=='2'){
					
						$count2 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge)->count();
						$count1 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge1)->count();
						$judgecode = $request->benchJudge;
						$judgecode1 = $request->benchJudge1;
						$jsname2=$jsort_one->judgeshortname.''."({$jdesig_one->judgedesigshort})";
						
						$judgeshortname= $jsname1.''."+{$jsname2}";
						$judgeshortname1= $jsname2.''."+{$jsname1}";
						
						$count_1 = DB::table('bench')->where('judgeshortname','=',$judgeshortname)->where('display','=','Y')->count();
						$count_2 = DB::table('bench')->where('judgeshortname','=',$judgeshortname1)->where('display','=','Y')->count();
						$check_1 = DB::table('benchjudges')->join('bench','bench.benchcode','=','benchjudges.benchcode')
						->where('display','=','Y')
						->where('bench.benchtypename','=','Division Bench')
						->where('judgecode','=',$judgecode)->get();
						$check_2 = DB::table('benchjudges')->join('bench','bench.benchcode','=','benchjudges.benchcode')
						->where('display','=','Y')
						->where('bench.benchtypename','=','Division Bench')
						->where('judgecode','=',$judgecode1)->get();
					if($count_1>0||$count_2>0)	
					{
						$request->session()->flash('alert-warning', 'Bench Already  Created with this Judge1 or Judge2 or Duplicate ');
							return redirect()->back()->withInput($request->all());
						
							
					}
					else{
								
						$task = bench::create(['benchcode' => $bcode,
						'benchtypename' => 'Division Bench',
						'judgescount'=>'2',
						'fromdate'=>$request->FromDate,
						'todate'=>$request->todate,
						'display'=>'Y',
						'judgecode'=>$request->benchJudge,
						'judgecode1'=> $request->benchJudge1,
						'judgeshortname'=>$jsname1.''."+{$jsname2}"]);
						//'createdby'=>$jsort_one->judgeshortname.''."({$jsort_one->judgedesigshort})",
						//'updatedby'=>$request->judgeDesig]);
						
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge1,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig1]);
						$request->session()->flash('alert-success', 'Bench was successful created!');
						return redirect('/bench/create'.$task->id);
					}
				
				
				
		
				
			}  
			elseif($request->benchtypename=='3'){
				
						$judgecode = $request->benchJudge;
						$judgecode1 = $request->benchJudge1;
						$judgecode2 = $request->benchJudge2;
						
						$count_f = DB::select(DB::raw("select count(*) from benchjudges  c where benchcode in
											(select b.benchcode from benchjudges b where benchcode in (
												select bench.benchcode from benchjudges 
												inner join bench on bench.benchcode=benchjudges.benchcode
												where bench.benchtypename='Full Bench' and judgecode='$judgecode' and bench.display='Y')
												and (b.judgecode='$judgecode1')) and (c.judgecode='$judgecode2')"));
						
						if($count_f[0]->count>0)  {
									$request->session()->flash('alert-warning', 'Bench Already Created with this Judge1 or Judge2 or Duplicate ');
									
									return redirect()->back()->withInput($request->all());
						
									
							}
							
					
						else{
						$jsname2=$jsort_one->judgeshortname.''."({$jdesig_one->judgedesigshort})";
						$jsname3=$jsort_two->judgeshortname.''."({$jdesig_two->judgedesigshort})";
						$task = bench::create(['benchcode' => $bcode,
							'benchtypename' => 'Full Bench',
							'judgescount'=>'5',
							'fromdate'=>$request->FromDate,
							'todate'=>$request->todate,
							'display'=>'Y',
							'judgecode'=>$request->benchJudge,
							'judgecode1'=> $request->benchJudge1,
							'judgeshortname'=>$jsname1.''."+{$jsname2}".''."+{$jsname3}"]);
							//'createdby'=>$jsort_one->judgeshortname.''."({$jsort_one->judgedesigshort})",
							//'updatedby'=>$request->judgeDesig]);
							$count2 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge)->count();
							$count1 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge1)->count();
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge1,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig1]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge2,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig2]);
						
							$request->session()->flash('alert-success', 'Bench was successful created!');
							return redirect('/bench/create'.$task->id);
						}
				
			}
			
			elseif($request->benchtypename=='4'){
				
						$count1 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge1)->count();
						$count2 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge)->count();
						$count3 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge2)->count();
						$count4 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge3)->count();
						
						
						$judgecode = $request->benchJudge;
						$judgecode1 = $request->benchJudge1;
						$judgecode2 = $request->benchJudge2;
						$judgecode3 = $request->benchJudge3;
						
						$count_f = DB::select(DB::raw("select count(*) from benchjudges  d where benchcode in
									(select c.benchcode from benchjudges c where benchcode in
											(select b.benchcode from benchjudges b where benchcode in (
												select bench.benchcode from benchjudges 
												inner join bench on bench.benchcode=benchjudges.benchcode
												where bench.benchtypename='Full Bench' and judgecode='$judgecode' and bench.display='Y')
												and (b.judgecode='$judgecode1')) and (c.judgecode='$judgecode2'))and (d.judgecode='$judgecode3')"));
						
						if($count_f[0]->count>0)  {
									$request->session()->flash('alert-warning', 'Bench Already Created with this Judge1 or Judge2 or Duplicate ');
									
									return redirect()->back()->withInput($request->all());
						
									
							}
							
					
						else{
						$jsname2=$jsort_one->judgeshortname.''."({$jdesig_one->judgedesigshort})";
						$jsname3=$jsort_two->judgeshortname.''."({$jdesig_two->judgedesigshort})";
						$jsname4=$jsort_three->judgeshortname.''."({$jdesig_three->judgedesigshort})";
						$task = bench::create(['benchcode' => $bcode,
							'benchtypename' => 'Full Bench',
							'judgescount'=>'5',
							'fromdate'=>$request->FromDate,
							'todate'=>$request->todate,
							'display'=>'Y',
							'judgecode'=>$request->benchJudge,
							'judgecode1'=> $request->benchJudge1,
							'judgeshortname'=>$jsname1.''."+{$jsname2}".''."+{$jsname3}".''."+{$jsname4}"]);
							//'createdby'=>$jsort_one->judgeshortname.''."({$jsort_one->judgedesigshort})",
							//'updatedby'=>$request->judgeDesig]);
							$count2 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge)->count();
							$count1 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge1)->count();
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge1,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig1]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge2,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig2]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge3,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig3]);
						
							$request->session()->flash('alert-success', 'Bench was successful created!');
							return redirect('/bench/create'.$task->id);
						}
				
			}
			
			//Full Bench
			elseif($request->benchtypename=='5'){
						$count1 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge1)->count();
						$count2 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge)->count();
						$count3 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge2)->count();
						$count4 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge3)->count();
						$count5 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge4)->count();
						
						
						$judgecode = $request->benchJudge;
						$judgecode1 = $request->benchJudge1;
						$judgecode2 = $request->benchJudge2;
						$judgecode3 = $request->benchJudge3;
						$judgecode4 = $request->benchJudge4;
						
						$count_f = DB::select(DB::raw("select count(*) from benchjudges  e where benchcode in
									(select d.benchcode from benchjudges d where benchcode in
										(select c.benchcode from benchjudges c where benchcode in
											(select b.benchcode from benchjudges b where benchcode in (
												select bench.benchcode from benchjudges 
												inner join bench on bench.benchcode=benchjudges.benchcode
												where bench.benchtypename='Full Bench' and judgecode='$judgecode' and bench.display='Y')
												and (b.judgecode='$judgecode1')) and (c.judgecode='$judgecode2'))and (d.judgecode='$judgecode3')) and (e.judgecode='$judgecode4')"));
						
						if($count_f[0]->count>0)  {
									$request->session()->flash('alert-warning', 'Bench Already Created with this Judge1 or Judge2 or Duplicate ');
									
									return redirect()->back()->withInput($request->all());
						
									
							}
							
					
						else{
						$jsname2=$jsort_one->judgeshortname.''."({$jdesig_one->judgedesigshort})";
						$jsname3=$jsort_two->judgeshortname.''."({$jdesig_two->judgedesigshort})";
						$jsname4=$jsort_three->judgeshortname.''."({$jdesig_three->judgedesigshort})";
						$jsname5=$jsort_four->judgeshortname.''."({$jdesig_four->judgedesigshort})";
						$task = bench::create(['benchcode' => $bcode,
							'benchtypename' => 'Full Bench',
							'judgescount'=>'5',
							'fromdate'=>$request->FromDate,
							'todate'=>$request->todate,
							'display'=>'Y',
							'judgecode'=>$request->benchJudge,
							'judgecode1'=> $request->benchJudge1,
							'judgeshortname'=>$jsname1.''."+{$jsname2}".''."+{$jsname3}".''."+{$jsname4}".''."+{$jsname5}"]);
							//'createdby'=>$jsort_one->judgeshortname.''."({$jsort_one->judgedesigshort})",
							//'updatedby'=>$request->judgeDesig]);
							$count2 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge)->count();
							$count1 = DB::table('benchjudges')->where('judgecode', '=' ,$request->benchJudge1)->count();
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge1,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig1]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge2,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig2]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge3,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig3]);
						DB::table('benchjudges')->insert(['judgecode' => $request->benchJudge4,'benchcode'=>$bcode,'judgedesigcode'=>$request->judgeDesig4]);
						
							$request->session()->flash('alert-success', 'Bench was successful created!');
							return redirect('/bench/create'.$task->id);
						}	
			}
		
	
		
		
	}
	function show(Request $request)
	{   
		$data['BenchType'] =  $this->bench->getBenchType();
        $data['jdesig'] = $this->bench->getJudgeDesignation();
		$data['jname'] = $this->bench->getJudge();
		$data['jdesig2'] = $this->bench->getJudgeDesig();
		$data['benchjudge'] = $this->bench->getBenchJudge();
		
        return view('bench.create',$data)->with('user',$request->session()->get('userName'));

    
	}

	public function edit($benchcode)
    {
		
		$where = array('benchcode' => $benchcode);
        $data['bench_info'] = DB::table('bench')->select('bench.benchtypename','bench.judgeshortname','bench.benchcode','bench.fromdate','bench.display','bench.todate','bench.judgeshortname','judge.judgename')->join('benchjudges','bench.benchcode','=','benchjudges.benchcode')->Join('judge','benchjudges.judgecode','=','judge.judgecode')->where('benchjudges.benchcode','=',$where)->first();
		$data['bench_judge'] = DB::table('bench')->join('benchjudges','bench.benchcode','=','benchjudges.benchcode')
							->join('judge','benchjudges.judgecode','=','judge.judgecode')
							->join('judgedesignation','benchjudges.judgedesigcode','=','judgedesignation.judgedesigcode')
							->select('bench.benchcode','benchjudges.judgecode','judge.judgename','judgedesignation.judgedesigname')
							->where('bench.benchcode','=',$benchcode)->orderby('judgedesigorder')->get();
        return view('bench.edit', $data);
		
		
		
	}
	public function update(Request $request,$benchcode)
	{
		
		
		$update = ['fromdate' => $request->fromdate, 'todate' => $request->todate,'display'=> $request->display];
        bench::where('benchcode',$benchcode)->update($update);
   
       $request->session()->flash('alert-success', 'Bench Updated successfully!');
		return redirect('create');
				
		
	}
	public function getBenches(){
		
		 $benches = DB::table('benchtype')->pluck("benchtypename","judgecount");
        return view('bench/dropdown',compact('benches'));
	}
	public function getjcount($judgecount) 
	{        
        $states = DB::table("benchtype")->where("judgecount",$judgecount)->pluck("benchtypename","judgecount");
        return json_encode($states);
	}
	public function view(benchjudges $benchjudges)
	{
		$benchejudges->load('judge');
		return view('bench.edit', compact('benchjudges'));
	}
}