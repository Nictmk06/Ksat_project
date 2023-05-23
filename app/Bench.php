<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class bench extends Model
{
	protected $fillable = ['benchcode','benchtypename','fromdate','todate','judgeshortname','createdon','createdby','display','judgescount','establishcode'];
	protected $table = 'bench';
	public $timestamps = false;
	protected $primaryKey = 'benchcode';
    //
	public function getBenchType()
	{
		$value_b=DB::Table('benchtype')->select('*')->orderBy('judgecount', 'asc')->get();
		return $value_b;
	}
	
	public function getJudgeDesignation()
	{
		$value_d=DB::Table('judgedesignation')->select('*')->orderBy('judgedesigorder', 'asc')->get();
		return $value_d;
	}
	public function getJudgeDesig()
	{
		$value=DB::Table('judgedesignation')->select('*')->orderBy('judgedesigorder', 'asc')->get();
		return $value;
	}
	
	public function getJudge()
	{
		$value=DB::Table('judge')->select('*')->get();
		return $value;
	}
	public function getBench()
	{
		$value=DB::Table('bench')->select('*')->get();
		return $value;
	}
	public function getBenchForView()
	{
		$value = DB::table('bench')->select('*')->Join('judge','bench.judgeshortname','=','judge.judgeshortname')->Join('judgedesignation','judge.judgedesigcode','=','judgedesignation.judgedesigcode')->get();
		return $value;
	}
	public function getJudgeDesigShortCode($benchJudge)
	{
		$value=DB::Table('judge')->select('judgeshortname')->Join('judgedesignation','judge.judgedesigcode','=','judgedesignation.judgedesigcode')->get();
		return $value;
	}
	public function getBenchJudge()
	{
		$value = DB::Table('benchjudges')->select('bench.benchcode','bench.fromdate','bench.display','bench.todate','bench.judgeshortname','bench.benchtypename','judgedesignation.judgedesigshort')->Join('bench','benchjudges.benchcode','=','bench.benchcode')->Join('judge','benchjudges.judgecode','=','judge.judgecode')->Join('judgedesignation','benchjudges.judgedesigcode','=','judgedesignation.judgedesigcode')->where('bench.display','=','Y')->groupBy('benchjudges.benchcode','bench.benchcode','bench.benchtypename','judge.judgeshortname','judgedesignation.judgedesigshort','bench.fromdate','bench.display','bench.todate','judgedesigorder')->orderby('bench.benchcode','asc')->orderBy('judgedesigorder','asc')->get();

	//	$value = DB::Table('bench')->select('bench.benchcode','bench.fromdate','bench.display','bench.todate','bench.judgeshortname','bench.benchtypename')->where('bench.display','=','Y')->orderby('bench.judgeshortname','asc')->get();
		
		return $value;
	}
	public function getBenchList(){
		$value = DB::table('benchtype')->groupBy('benchtypename','judgecount')->get();
		return $value;
	}
	
}