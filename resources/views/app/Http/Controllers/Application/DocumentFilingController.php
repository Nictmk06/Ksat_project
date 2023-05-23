<?php

namespace App\Http\Controllers\Application;
use App\IANature;
use App\CaseManagementModel;
use App\UserActivityModel;
use App\IADocument as IADocumentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class DocumentFilingController extends Controller
{
	  public function __construct()
    {
        $this->IANature = new IANature();
        $this->case = new CaseManagementModel();
         $this->UserActivityModel = new UserActivityModel();
		 }


    public function index(Request $request)
    {
        $user = $request->session()->get('userName');
        $establishcode = $request->session()->get('EstablishCode');
        $data['documenttype'] = $this->IANature->getDocumentType();
        $data['applicationType'] = $this->case->getApplType();
         $data['applCategory'] = $this->case->getApplCategory();
        $data['IANature'] =  $this->IANature->getIANature();
        $data['title'] = 'IADocument';
        return view('Application.DocumentFiling',$data)->with('user',$request->session()->get('userName'));
    }


     public function savedocumentfiling(Request $request)
    {
         $validator = \Validator::make($request->all(), [
            'applicationId' => 'required|max:10',
            'IAFillingDate'=>'required|date',
            'documentTypeCode'=>'required',
            //'IASrNo'=>'numeric|between:1,10000',
           // 'IANatureCode'=>'required',
            'IARegistrationDate'=>'required|date',
            //'IANo'=>'required|numeric',
            'IAPrayer'=>'required'      
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }
        else
        {

            //Add IA Document
           $filliedbyname =  $request->get('filledbyname');
            $filledname  = explode('-',$filliedbyname);
            $type1 = explode('-',$request->get('applTypeName'));
            $applicationid = $type1[1].'/'.$request->get('applicationId');
          $type = explode('-',$request->get('documentTypeCode'));
            $sbmt_val= $request->get('sbmt_ia');

        if ($sbmt_val=='A')
            {
            
            $newiano = DB::table('iadocument')->select('iasrno')->where('applicationid',$applicationid)->where('documenttypecode',$type[0])->orderBy('iasrno', 'DESC')->first();
          if(!empty($newiano))
             $iasrno = $newiano->iasrno + 1;
           else
             $iasrno = 1;
            }
        else
          $iasrno = $request->get('IASrNo');  
  
           $iano = $type[1].'/'.$iasrno;

       if ($sbmt_val=='A')
            {
              $value = DB::table('applicationindex')->select('documentno')->where('applicationid',$applicationid)->orderby('documentno','desc')->first();
            
            if(!empty($value))
            {
              $doc = $value->documentno;
              $document = ($doc)+1;
           }
           else{
              $document =1;
           }
        }
        else
          $document = $request->get('documentno');

            $IADocument = new IADocumentModel([
            'applicationid' =>$type1[1].'/'.$request->get('applicationId'),
            'iafillingdate'=> date('Y-m-d',strtotime($request->get('IAFillingDate'))),
            'iaregistrationdate'=>date('Y-m-d',strtotime($request->get('IARegistrationDate'))),
            'iaprayer'=>$request->get('IAPrayer'),
            'ianaturecode'=> $request->get('IANatureCode'),
            'createdby'=>$request->session()->get('userName'),
            'createdon'=>date('Y-m-d H:i:s'),
            'iasrno'=>$iasrno,
            'iastatus'=>1,
            'documenttypecode'=>$type[0],
            'iano'=> $iano,
            'partysrno'=>$filledname[1],
            'appindexref'=> $document,
            'filledby'=> $filledname[0]
            ]);
           
            //application index 
            $applIndex['applicationid']=$type1[1].'/'.$request->get('applicationId');
            $applIndex['documentname']=$request->get('IAPrayer');
             $applIndex['documentno']=$document;
            

            $applIndex['documentdate']=date('Y-m-d',strtotime($request->get('IAFillingDate')));
            if($sbmt_val=='A'){
            $applIndex['createdby']=$request->session()->get('userName');
            $applIndex['createdon']=date('Y-m-d H:i:s');
            }
            else
            {
            $applIndex['updatedby']=$request->session()->get('userName');
            $applIndex['updatedon']=date('Y-m-d H:i:s');
            }
               
			$useractivitydtls['applicationid_receiptno'] =$type1[1].'/'.$request->get('applicationId');
            $useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
		    $useractivitydtls['activity'] ='Document Filing' ;
			$useractivitydtls['userid'] = $request->session()->get('username');
			$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
			$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid'); 
            if($sbmt_val=='A')
            {
               // if($IADocument->save() && $this->case->addApplicationIndex($applIndex))
                 if($IADocument->save())
                {
					$this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                    return response()->json([
                        'status' => "sucess",
                        'message' => "IA Document Added Successfully"

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
            else
            {   //update iA Document
                
                    $IADocuments = IADocumentModel::find($iano);
                    $IADocuments->iafillingdate= date('Y-m-d',strtotime($request->get('IAFillingDate')));
                    $IADocuments->iaregistrationdate=date('Y-m-d',strtotime($request->get('IARegistrationDate')));
                    $IADocuments->iaprayer=$request->get('IAPrayer');
                    $IADocuments->ianaturecode= $request->get('IANatureCode');
                    $IADocuments->createdby=$request->session()->get('userName');
                    $IADocuments->createdon=date('Y-m-d H:i:s');
                    $IADocuments->iasrno=$iasrno;
                    $IADocuments->iastatus=1;
                    $IADocuments->documenttypecode=$type[0];
                    $IADocuments->partysrno=$filledname[1];
                    $IADocuments->filledby=$filledname[0];
                    $IADocuments->appindexref = $document;
                   
                    $iano = $request->get('IANo');
                  
                    $IADocuments->iano=$iano;


                   //if($IADocuments->save() && $this->case->updateApplicationIndex($applIndex,$applIndex['applicationid'],$applIndex['documentno']))
                if($IADocuments->save())
                    {
						$useractivitydtls['applicationid_receiptno'] =$type1[1].'/'.$request->get('applicationId');
						$useractivitydtls['createdon'] = date('Y-m-d H:i:s') ;
						$useractivitydtls['activity'] ='Update Document Filing' ;
						$useractivitydtls['userid'] = $request->session()->get('username');
						$useractivitydtls['establishcode'] = $request->session()->get('EstablishCode');   
						$useractivitydtls['login_session_id'] = $request->session()->get('usersessionid');
                         $this->UserActivityModel->insertUserActivityDtls($useractivitydtls);
                         return response()->json([
                        'status' => "sucess",
                        'message' => "IA Document Updated Successfully"

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
        }
    }
}