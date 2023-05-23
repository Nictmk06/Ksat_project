<?php

namespace App\Http\Controllers;

use App\ConnectedApplication as ConnectedModel;
use Illuminate\Http\Request;
use App\IANature;
use App\CaseManagementModel;
use App\IADocument as IADocumentModel;
use App\Dailyhearing;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\ConnectedApplication1 as ConnectedModel1;
use App\ModuleAndOptions;

class ConnectedApplicationController extends Controller
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
    }
    public function index(Request $request)
    {
      $user = $request->session()->get('userName');
        $data['applicationType'] = $this->case->getApplType();
        $data['applCategory'] = $this->case->getApplCategory();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['benchcode'] = $this->IANature->getBenchcodes();
        $data['title'] = 'Connected Appl';
//         $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
        return view('ConnectedApplication.create',$data)->with('user',$request->session()->get('userName'));
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
    public function store(Request $request)
    {
        /* $validator = \Validator::make($request->all(), [
            'applicationId' => 'required|max:10',
               
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {*/

            
            
             $type1 = explode('-',$request->get('applTypeName'));
            
             $applYear = explode('-', $request->get('dateOfAppl'));
           
            $Connected = new ConnectedModel([
            'applicationid' =>$request->get('orignapplid'),
            'reason'=> $request->get('reasonforconn'),
            'hearingdate'=>date('Y-m-d',strtotime($request->get('hearingDate'))),
            'benchcode'=>$request->get('benchJudge'),
            'benchtypename'=> $request->get('benchCode'),
            'createdby'=>$request->session()->get('userName'),
            'createdon'=>date('Y-m-d H:i:s'),
            'orderdate'=>date('Y-m-d',strtotime($request->get('orderDate'))),
            'type'=>$request->get('connectedtype'),
            'orderno'=>$request->get('orderNo'),
            'appltypecode'=> $type1[0],
            'applicationyear'=>$applYear[2]
            ]);
            $Connected1 = new ConnectedModel1([
            'applicationid' =>$request->get('orignapplid'),
            'conapplid'=> $request->get('conaplid'),
            'conapplfrsrno'=>$request->get('conApplStartNo'),
            'conappltosrno'=>$request->get('conApplEndNo'),
            'registerdate'=> date('Y-m-d')
            ]);
          
           




            $sbmt_val = $request->get('sbmt_connected');
           $chkval = $request->get('chkval');
           if($sbmt_val=='A' && $chkval=='A')
            {
                $count = DB::table('connected1')->where('conapplid',$request->get('conaplid'))->count();
                if($count>0)
                {
                    return response()->json([
                            'status' => "exists",
                            'message' => "Connected Application Already Exists"

                            ]);
                }
                else
                {
                     $Connected = ConnectedModel::find($request->get('orignapplid'));
                 // $Connected->applicationid =$request->get('conaplid');
                    $Connected->reason= $request->get('reasonforconn');
                    $Connected->hearingdate=date('Y-m-d',strtotime($request->get('hearingDate')));
                    $Connected->benchcode=$request->get('benchJudge');
                    $Connected->benchtypename= $request->get('benchCode');

                    $Connected->orderdate=date('Y-m-d',strtotime($request->get('orderDate')));
                    $Connected->type=$request->get('connectedtype');
                    $Connected->orderno=$request->get('orderNo');
                        if($Connected1->save() && $Connected->save())
                    {
                        return response()->json([
                            'status' => "sucess",
                            'message' => "Connected Application Added Successfully"

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
            if($sbmt_val=='A')
            {
                if($Connected->save() && $Connected1->save())
                {
                    return response()->json([
                        'status' => "sucess",
                        'message' => "Connected Application Added Successfully"

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
            else if($sbmt_val=='U')
            {   //update iA Document
                
                 $Connected = ConnectedModel::find($request->get('orignapplid'));
                 // $Connected->applicationid =$request->get('conaplid');
             $Connected->reason= $request->get('reasonforconn');
             $Connected->hearingdate=date('Y-m-d',strtotime($request->get('hearingDate')));
             $Connected->benchcode=$request->get('benchJudge');
             $Connected->benchtypename= $request->get('benchCode');
            
             $Connected->orderdate=date('Y-m-d',strtotime($request->get('orderDate')));
             $Connected->type=$request->get('connectedtype');
             $Connected->orderno=$request->get('orderNo');

            
                   
                    

                   if($Connected->save())
                    {
                         return response()->json([
                        'status' => "sucess",
                        'message' => "Connected Application Updated Successfully"

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
            /**/
       /* }*/
    }
    public function getConnected(Request $request)
    {
        $applicationid = $_POST['applicationid'];
        $data['connappl'] = ConnectedModel1::getConnecteddata($applicationid);
        echo json_encode($data['connappl']);
    }
    public function getConApplDet(Request $request)
    {
         $applicationid = $_POST['applicationid'];
        $data['connappl'] = ConnectedModel::getConnectDetails($applicationid);
        echo json_encode($data['connappl']);
    }
    public function getConStatusdetails()
    {
      $applicationid = $_POST['applicationid'];
      $newflag = $_POST['newflag'];
     
        $data['connappl1'] = ConnectedModel::getConExits($applicationid,$newflag);
        //print_r($data['connappl1']);
       echo json_encode($data['connappl1']);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\ConnectedApplication  $connectedApplication
     * @return \Illuminate\Http\Response
     */
    public function show(ConnectedApplication $connectedApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ConnectedApplication  $connectedApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(ConnectedApplication $connectedApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ConnectedApplication  $connectedApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConnectedApplication $connectedApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ConnectedApplication  $connectedApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConnectedApplication $connectedApplication)
    {
        //
    }
}
