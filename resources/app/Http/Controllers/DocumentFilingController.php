<?php

namespace App\Http\Controllers\Application;
use App\IANature;
use App\CaseManagementModel;
use App\IADocument as IADocumentModel;
use App\Dailyhearing;
use Illuminate\Http\Request;
use App\ModuleAndOptions;
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
         $this->module= new ModuleAndOptions();
    }


    public function index(Request $request)
    {
        $user = $request->session()->get('userName');

         $establishcode = $request->session()->get('EstablishCode');
//        $data['establishname'] = $this->case->getEstablishName($establishcode);
        $data['documenttype'] = $this->IANature->getDocumentType();
        $data['applicationType'] = $this->case->getApplType();
        $data['Benches'] =  $this->IANature->getBenches();
        $data['CourtHalls'] = $this->IANature->getCourthalls();
        $data['Status'] =  $this->IANature->getStatus();
        $data['purpose'] =  $this->IANature->getListPurpose();
        $data['OrderType'] =  $this->IANature->getOrderType();
        $data['benchjudge'] = $this->IANature->getbenchjudge();
//        $data['usermenu'] =$this->module->usermenu($request->session()->get('userName'));
//        $data['usermenumodule'] = $this->module->usermenumodule($request->session()->get('userName'));
        $data['title'] = 'IADocument';
        return view('Application.DocumentFiling',$data)->with('user',$request->session()->get('userName'));
    }
}