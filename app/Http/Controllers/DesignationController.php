<?php

namespace App\Http\Controllers;




use App\Designation as DesignationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\UserActivityModel;
class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
	public function __construct()
    {
        $this->UserActivityModel = new UserActivityModel();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

 public function adddesignation(Request $request){
    $data['departmenttype']=DB::select("SELECT * FROM departmenttype order by depttypecode ASC");
        $data['department']=DB::select("SELECT * FROM department order by departmentcode");
        $data['designation']=DB::select("SELECT * from designation order by desigcode");
        // $data['department']=DB::select("SELECT departmentcode from department ");

          return view('master.AddDesignation',$data);
    }



public function savedeptdesigmapping(Request $request){
         
             $request->validate([
                'department' => 'required',
                'desigAppl' => 'required'
                         
            ]);  
          
             $department_designation['departmentcode']       = $request->input('department');
             $department_designation['desigcode']     = $request->input('desigAppl');
        $value =  DB::table('department_designation')->insert($department_designation);
         if($value)
         {
			$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		    $useractivitydtls['activity'] ='Add Dept Designation Mapping' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);    
            return back() ->with('success','Mapping added Successfully.');
      
         }else{
           return back() ->with('error','Something went wrong.');
      
         }  
     }



    public function store(Request $request)
    {
        $Designation = new DesignationModel([
           'designame' =>$request->input('designame')
            ]);
         if($Designation->save())
         {
			$useractivitydtls['applicationid_receiptno'] = $Designation->desigcode;
		    $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		    $useractivitydtls['activity'] ='Add Designation' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
			$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);           
		   $data['designation'] = array('desigcode'=>$Designation->desigcode,'designame'=>$request->input('designame'));
           
            echo json_encode($data['designation']);

                    
         }
         
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit(Designation $designation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $designation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designation $designation)
    {
        //
    }
}
