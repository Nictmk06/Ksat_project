<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
use App\CaseManagementModel;


Route::get('/', function () {
	$data['establishments'] = DB::table('establishment')->select('establishcode','establishname','defaultdisplay')->get();
	$data['title'] = 'login';
    return view('Authentication/login',$data);
});


Route::post('/getApplicationbyuser', function (Request $Request ) {
			{
			$user = $_POST['$user'];

			if($user!='')
			{
				
				$value = DB::select("select applicationid, createdon, applicantcount, registerdate from application where createdby='".$user."'  order by createdon desc");
			
				return $value;
		  }
			
		}
	});


//Route::post('/getApplicationbyuser','CaseManagementModel@getApplicationbyuser');

// Hegade -------------------------------------------------------------------
// Receipt generation (Add/Edit/receipt generation)
Route::get('/receiptEntryIndex', [ 'as' => 'receiptCrudIndex', 'uses' => 'ReceiptController@index']);
Route::get('/receiptEntryCreate', [ 'as' => 'receiptCrudCreate', 'uses' => 'ReceiptController@create']);
Route::post('/receiptEntryStore', [ 'as' => 'receiptCrudStore', 'uses' => 'ReceiptController@store']);
Route::get('/receiptEntryEdit', [ 'as' => 'receiptCrudEdit', 'uses' => 'ReceiptController@edit']);
Route::PUT('/receiptEntryUpdate', [ 'as' => 'receiptCrudUpdate', 'uses' =>  'ReceiptController@update']);
Route::get('/receiptEntryEditlist', [ 'as' => 'receiptCrudEditlist', 'uses' => 'ReceiptController@editlist']);
Route::get('/receiptEntryShow', [ 'as' => 'receiptCrudShow', 'uses' => 'ReceiptController@show']);
Route::get('/receiptEntryDtShow', [ 'as' => 'receiptCrudDtShow', 'uses' => 'ReceiptController@showdtrange']);
Route::get('/receiptGetFeeAmount', [ 'as' => 'receiptCrudFeeAmount', 'uses' => 'ReceiptController@getfeeamount']);
Route::get('/receiptEntryGen', [ 'as' => 'receiptCrudGen', 'uses' => 'ReceiptController@receiptgen']);
// Receipt day account closing
Route::get('/receiptCloseDtGet', [ 'as' => 'receiptCrudCloseDtget', 'uses' => 'ReceiptController@receiptclosedtget']);
Route::post('/receiptCloseStore', [ 'as' => 'receiptCrudCloseStore', 'uses' => 'ReceiptController@receiptclosestore']);
// Receipt reports
Route::get('/receiptReport', [ 'as' => 'receiptCrudReport', 'uses' => 'ReceiptController@receiptreport']);
Route::get('/receiptRepDailyGetDt', [ 'as' => 'receiptCrudRepDailyGetDt', 'uses' => 'ReceiptController@receiptrepdailygetdt']);
Route::get('/receiptRepSummGetDt', [ 'as' => 'receiptCrudRepSummGetDt', 'uses' => 'ReceiptController@receiptrepsummgetdt']);
Route::get('/receiptRepDaily', [ 'as' => 'receiptCrudRepDaily', 'uses' => 'ReceiptController@receiptrepdaily']);
Route::get('/receiptRepSummary', [ 'as' => 'receiptCrudRepSummary', 'uses' => 'ReceiptController@receiptrepsummary']);
Route::get('/receiptGetBank', [ 'as' => 'receiptCrudGetBank', 'uses' => 'ReceiptController@receiptgetbank']);

//---------------------------------------------------------



Route::get('/ConnectedApplication', 'ConnectedApplicationController@index');
Route::get('/dashboard', function(Request $request)
{
	
//	$establishcode = $request->session()->get('EstablishCode');
//	$this->case = new CaseManagementModel();
//     $data['establishname'] = $this->case->getEstablishName($establishcode);
     $data['userAppDet'] = DB::select('select * from usercount1');
//     $this->module = new ModuleAndOptions();
//      $data['options'] = $this->module->getOptions();
//      $data['Modules'] = $this->module->getModules();
	$data['title']='Dashboard';
	return view('dashboard',$data);
   // return view('dashboard',$data);
});
//Route::get('/ARStatus','ExtraAdvocateController@show')->name('ARStatus');

Route::get('/ExtraAdvocate', 'ExtraAdvocateController@index');

Route::resource('/ExtraAdvocateController', 'ExtraAdvocateController');
//Route::get('{slug}',[ 'as' => 'pages.show','uses'=>'Auth\LoginController@show']);
Route::post('login', [ 'as' => 'userLogin','uses'=>'Auth\LoginController@verifyLogin']);
Route::post('casestore', [ 'as' => 'caseDataStore','uses'=>'CaseManagementController@store']);
Route::post('receiptstore', [ 'as' => 'receiptDataStore','uses'=>'CaseManagementController@receiptStore']);
Route::post('applicantstore', [ 'as' => 'applicantDataStore','uses'=>'CaseManagementController@applicantStore']);
Route::post('appresstore', [ 'as' => 'AppDataStore','uses'=>'CaseManagementController@applicantrespondentstore']);

Route::post('respondantstore', [ 'as' => 'respondantDataStore','uses'=>'CaseManagementController@respondantStore']);
Route::post('applIndexstore', [ 'as' => 'applicationIndexDataStore','uses'=>'CaseManagementController@applIndexStore']);

Route::post('extraapplicantStore', [ 'as' => 'extraapplicantStore','uses'=>'CaseManagementController@extraapplicantStore']);
Route::post('extrarespondantStore', [ 'as' => 'extrarespondantStore','uses'=>'CaseManagementController@extrarespondantStore']);


Route::post('addRelief',[ 'as' => 'releifDataStore','uses'=>'CaseManagementController@AddreliefDetails']);
Route::post('/getApplicationDetails','CaseManagementController@getApplBasedOnID');
//Route::post('/applicationStatus', 'CaseManagementController@ApplicationStatusCreate');
Route::get('/case', 'CaseManagementController@index');

Route::get('/modules', 'ModuleController@index');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/caseEntry', 'CaseManagementController@create');
Route::get('/receiptEntry', 'CaseManagementController@receiptCreate');
Route::post('/receipts', 'CaseManagementController@receiptDet');
Route::post('/advRegNo', 'CaseManagementController@getAdvRegNo');
Route::post('/getResAdv','CaseManagementController@getResAdvReg');

Route::post('/getTempDate','CaseManagementController@getResAdvReg');
Route::post('/getReceipt', 'CaseManagementController@receiptDetails');
Route::post('/updateReceipt', 'CaseManagementController@updateReceiptData');
Route::post('/getApplicant', 'CaseManagementController@ApplicantDetails');
Route::post('/updateApplicant', 'CaseManagementController@UpdateApplicantDetails');
Route::post('/getRespondant', 'CaseManagementController@RespondantDetails');
Route::post('/updateRespondant', 'CaseManagementController@UpdatRespondantDetails');
Route::post('/updateCaseDetails', 'CaseManagementController@UpdatCaseDetails');
Route::post('/getApplicantData', 'CaseManagementController@ApplicantSerialDetails');
Route::post('/getRespondantData', 'CaseManagementController@RespondantSerialDetails');
Route::post('/getsubmenu', 'Auth\LoginController@submenu');
Route::post('/getTaluk', 'Auth\LoginController@getTaluk');
Route::post('/getApplication', 'CaseManagementController@ApplicationDetails');
Route::post('/getApplicationDetailsCauseList', 'CaseManagementController@ApplicationDetailsCauseList');
Route::resource('/IADocument', 'IADocument');


Route::post('/getIADocAppl', 'IADocument@show');
Route::post('/IAExistance', 'IADocument@checkIAExistance');


Route::post('/getSingleAdv','CaseManagementController@getSinglApplAdv');
Route::post('/getResSingleAdv','CaseManagementController@getResSinglApplAdv');
Route::post('/storeDailyHearing', [ 'as' => 'storeDailyHearing','uses'=>'IADocument@dailyHearingstore']);
Route::post('/getIADocApplSerial', 'IADocument@getIADocumentSerialWise');
Route::post('/getPendingIADoc','IADocument@getPendingIADocuments');
Route::post('/getHearingDet', 'IADocument@getHearingDocument');
Route::post('/getHearingCodeWise', 'IADocument@getHearingCodeDet');
Route::post('Connectedstore', [ 'as' => 'ConnectedstoreData','uses'=>'ConnectedApplicationController@store']);
Route::post('/getAppSrCount','CaseManagementController@ApplicationSrCount');
Route::post('/getLastSerialNo','CaseManagementController@LastApplicantSrNo');
Route::post('/getResSrCount','CaseManagementController@RespondantSrCount');
Route::post('/getRsLastSerialNo','CaseManagementController@LastRespondantSrNo');
Route::post('/getApplicationExistance','CaseManagementController@getAppIDExistance');
Route::post('/getReceiptExistance','CaseManagementController@getReceiptExistance');
Route::post('/getRelief','CaseManagementController@getReliefSought');
Route::post('/getreliefData','CaseManagementController@getReliefSrSought');
Route::post('/deleteApplication','CaseManagementController@deleteApplication');
Route::post('/getSingleAdvDet','CaseManagementController@getSingleAdvDetails');
Route::post('/getResSingleAdvDet','CaseManagementController@getResSingleAdvDetails');
Route::post('/getDepatmentName','CaseManagementController@getDepartmentNames');

Route::post('/storeDesignation','DesignationController@store');



Route::post('/storeAdvocate','AdvocateController@store');

Route::post('/getDesignation','CaseManagementController@getDesignation');


Route::post('/getAdvocate','CaseManagementController@getAdvocate');


Route::post('/getSections','CaseManagementController@getSections');


Route::post('/deleterecp', function (Request $request) {
	$receipt=DB::Table('receipt')->select('receiptdate')->where('receiptsrno', '=',$_POST['receiptsrno'])->where('applicationid', '=', $_POST['applicationid'])->get();
   if($receipt[0]->receiptdate < '2020-02-03')
	{
	    $value = DB::table('receipt')->where('receiptsrno',$_POST['receiptsrno'])->where('applicationid',$_POST['applicationid'])->delete();
	}else{
	    $value=DB::Table('receipt')->where(['applicationid'=>$_POST['applicationid']])->where(['receiptsrno'=>$_POST['receiptsrno']])->update(['applicationid' =>null]);
	}
	if($value==true)
	{
		return response()->json([
                        'status' => "sucess",
                        'message' => "Receipt Deleted Successfully",
                        ]);
	}
	else
	{
		return response()->json([
                        'status' => "fail",
                        'message' => "Something Went Wrong",
                        ]);
	}
});

Route::post('/deletecause', function (Request $request) {
	$value = DB::table('causelistappltemp')->select('causelistcode')->where('causelistcode',$_POST['causelistcode'])->count();
	
	if($value>=1)
	{

        $value1 = false;
		$applicationid = $_POST['applicationid'];
		$causelistcode = $_POST['causelistcode'];

		$value1 = DB::transaction(function () use($applicationid,$causelistcode,$value) {
			
			 DB::table('causelistappltemp')->where('applicationid',$_POST['applicationid'])->where('causelistcode',$_POST['causelistcode'])->delete();

			 DB::table('causelistconnecttemp')->where('applicationid',$_POST['applicationid'])->where('causelistcode',$_POST['causelistcode'])->delete();
			
			
			if ($value == 1) { 
					DB::table('causelisttemp')->where('causelistcode',$_POST['causelistcode'])->delete();
				// safe to delete all records in both table
					DB::table('causelistappltemp')->where('causelistcode',$_POST['causelistcode'])->delete();

			}

			return true;
			});

		if($value1==true)
		{ $_POST['causelistcode'] = 0;
						return response()->json([
					    'value' => $value,	
                        'status' => "sucess",
                        'message' => "Causelist Deleted Successfully",
                       
                        ]);
		}
		else
		{
						return response()->json([
                        'status' => "fail",
                        'message' => "Something Went Wrong",

                        ]);
	}
	}


});

Route::post('/deleteConnected', function (Request $request) {
	$applicationid = $_POST['applicationid'];
	$connapplid= $_POST['conapplid'];
	$count = DB::table('connected1')->where('applicationid',$applicationid)->count();
	//echo $count;
	if($count>1)
	{
		$value = DB::transaction(function () use($connapplid) {
		 DB::table('connected1')->where('conapplid',$connapplid)->delete();
		 DB::update("update application set connectedcase='' where applicationid ='".$connapplid."' ");
		 return true;
		    });
		if($value==true)
	{
		return response()->json([
                        'status' => "sucess",
                        'message' => "Connected Deleted Successfully",
                        ]);
	}
	else
	{
		return response()->json([
                        'status' => "fail",
                        'message' => "Something Went Wrong",
                        ]);
	}
	}
	else
	{
		$value = DB::transaction(function () use($applicationid,$connapplid) {
			
			DB::table('connected1')->where('conapplid',$connapplid)->delete();
			DB::table('connected')->where('applicationid',$applicationid)->delete();
			DB::update("update application set connectedcase='' where applicationid ='".$connapplid."' ");
		    DB::update("update application set connectedcase='' where applicationid ='".$applicationid."' ");
			return true;
			});
		if($value==true)
	{
		return response()->json([
                        'status' => "sucess",
                        'message' => "Connected Deleted Successfully",
                        'data1'=>'Y'
                        ]);
	}
	else
	{
		return response()->json([
                        'status' => "fail",
                        'message' => "Something Went Wrong",

                        ]);
	}
			
			
	}
	
});




Route::post('/deleterelief', function (Request $request) {
	$value = DB::table('applrelief')->where('reliefsrno',$_POST['reliefsrno'])->where('applicationid',$_POST['applicationid'])->delete();
	if($value==true)
	{
		return response()->json([
                        'status' => "sucess",
                        'message' => "Relief Deleted Successfully",
                        ]);
	}
	else
	{
		return response()->json([
                        'status' => "fail",
                        'message' => "Something Went Wrong",
                        ]);
	}
});



/////////////////////Route to iadocument//////////////////////////////////////

Route::post('/getRegDate','IADocument@getPrevIARegDate');
Route::post('/getBenchJudges','IADocument@getBenchjudges');
Route::post('/getApplicationIndex','IADocument@getApplicationIndexDetails');
Route::post('/getApplRespondant','IADocument@getApplRespondants');
Route::post('/getStartPageOfApplIndex','IADocument@getStartPage');
Route::post('/updateIAStatus','IADocument@updateIAStatusDetails');
Route::post('/getIANo','IADocument@getIANoDet');
Route::post('/getHearingDetails','IADocument@getHearingDet');
Route::post('/getIABasedOnHearing','IADocument@getIAOnHearing');
Route::post('/getExtraAdvocate','ExtraAdvocateController@getExtraAdvocateDet');
Route::post('/getExtraAdvDetails','ExtraAdvocateController@getEachExtraAdvDet');
Route::post('/getAdvocateUnique','ExtraAdvocateController@getUniqueAdvDet');
Route::get('/ARStatus','ExtraAdvocateController@show')->name('ARStatus');
Route::get('/Causelist','CauselistController@index')->name('Causelist');
Route::get('/CauselistDated','CauselistController@show')->name('CauselistDated');
Route::get('/Groupno','ExtraAdvocateController@showgroup')->name('Groupno');
Route::post('/statusupdate','ExtraAdvocateController@statusupdatestatusupdate');
Route::post('statusupdate', [ 'as' => 'statusDataStore','uses'=>'ExtraAdvocateController@statusupdate']);
Route::post('storegroupno', [ 'as' => 'groupDataStore','uses'=>'ExtraAdvocateController@storegroupno']);
Route::post('/getGroupApplications','ExtraAdvocateController@getGroupApplications');

Route::post('/getConnectApplications','ConnectedApplicationController@getConnected');
Route::post('/getConAppl','ConnectedApplicationController@getConApplDet');
Route::post('/getConStatus','ConnectedApplicationController@getConStatusdetails');
Route::post('/addCauselisttempappl','CauselistController@addcauselist');
Route::post('/addCauselisttempappl','CauselistController@addcauselist');
Route::post('/getUserApplicationCount','CaseManagementController@getUserApplCount');
Route::get('/searchapplication','ApplicationDetailsController@index');
Route::post('/appstatusresult','ApplicationDetailsController@appstatus'); 
Route::post('/getCauselistdetails','CauselistController@getCauselist');
Route::post('/getCauselistDataNew','CauselistController@getCauseBasedOnlistno');
Route::post('/getRearrangeCauselistData','CauselistController@getCausereorderData');
Route::post('/rearangeCauseOrder','CauselistController@causelistsrreorder');



Route::resource('/CauselistController', 'CauselistController');
Route::resource('/causelistfinalize', 'Causelistfinalize');
Route::resource('/Causelistverify', 'Causelistverify');
Route::resource('/AdminController', 'AdminController');

// causelist verify
Route::post('/getcauselistappl','Causelistverify@getcauselistappl');
Route::post('/getcauselistapplconnect','Causelistverify@getcauselistapplconnect');
Route::post('/getcauselistconnectedappl','Causelistverify@getcauselistconnectedappl');
Route::post('/getpreviouscauselistremarks','Causelistverify@getpreviouscauselistremarks');
Route::post('/getcauselistremarks','Causelistverify@getcauselistremarks');
Route::post('/updatecauselistheader','Causelistverify@updatecauselistheader');

//causelist finalize
Route::post('/getcauselist','causelistfinalize@getcauselist');
//Route::post('/printcauselist','causelistfinalize@store');
Route::post('finalizecauselist','Causelistfinalize@finalizecauselist');
Route::post('printcauselist', [ 'as' => 'printcauselist','uses'=>'Causelistfinalize@store']);

//transfer Cause List
Route::get('/causelisttransfer', 'CauselistTransferController@index')->name('causelisttransfer');
Route::post('/getFinalizedcauselist','CauselistTransferController@getFinalizedcauselist');
Route::post('/getcauselistapplforTransfer','CauselistTransferController@getcauselistapplforTransfer');
Route::post('/transferCauseList','CauselistTransferController@transferCauseList')->name('transferCauseList');


// bench Ajay --------------------------------------------------------
Route::get('bench','BenchController@index');
Route::get('bench','BenchController@show');
Route::post('save','BenchController@store');
Route::post('/benchsave','BenchController@store');
Route::post('/Bench/fetch','BenchController@fetch')->name('/Bench/fetch');
Route::get('/Bench/fetch','BenchController@fetch')->name('/Bench/fetch');
Route::post('/bench.edit','BenchController@edit');


Route::post('benchcontroller/fetch', 'BenchController@fetch')->name('benchcontroller.fetch');


Route::get('dropdownlist','BenchController@getBenches');
Route::get('dropdownlist/getjcount/{judgecount}','BenchController@getjcount');
Route::get('getjcount','BenchController@getjcount');

Route::get('/json-judgenumber','CountryController@judgenumber');
Route::resource('bench', 'BenchController');
Route::get('edit','BenchController@edit');
Route::post('/update','BenchController@update');


Route::get('create','BenchController@show');
Route::get('bench/create','BenchController@show');
Route::post('fetch','BenchController@fetch')->name('benchcontroller.fetch');
Route::get('bench/edit','BenchController@edit');
Route::get('bench/update','BenchController@update');
Route::get('show','BenchController@show');
Route::get('bench','BenchController@show');
//---------------------------------------
// Change Password - prashant
Route::get('/changePassword', 'Auth\ResetPasswordController@changePassword');
Route::post('/changePasswordSave', [ 'as' => 'changePasswordSave', 'uses' => 'Auth\ResetPasswordController@changePasswordSave']);

Route::post('/getDepartmentDetails','DepartmentController@getDepartmentDetails');
Route::get('/findDistrictWithTaluk/{districtID}','DepartmentController@findDistrictWithTaluk');
Route::get('Department', 'DepartmentController@AddNewDepartment');
Route::post('/AddNewDepartmentSave', [ 'as' => 'AddNewDepartmentSave', 'uses' => 'DepartmentController@AddNewDepartmentSave']);

Route::get('listpurpose', 'ListPurposeController@listpurpose');
Route::post('ListPurposeSave', [ 'as' => 'ListPurposeSave', 'uses' => 'ListPurposeController@ListPurposeSave']);
Route::get('/getListPurposeDetails', 'ListPurposeController@getListPurposeDetails');


Route::get('DisconnectApplication', 'DisconnectConnectedApplicationController@DisconnectConnectedApplication');
Route::GET('/getConnectedAppDetails/{connectionid}', 'DisconnectConnectedApplicationController@getConnectedAppDetails');
Route::GET('/getDisConnectedAppDetails/{connectionid}', 'DisconnectConnectedApplicationController@getDisConnectedAppDetails');
Route::post('/DisconnectConnectedSave', [ 'as' => 'DisconnectConnectedSave', 'uses' => 'DisconnectConnectedApplicationController@DisconnectConnectedSave']);


Route::get('/SignAuthority', 'SignAuthorityController@SignAuthority');
Route::post('/SignAuthoritySave', [ 'as' => 'SignAuthoritySave', 'uses' => 'SignAuthorityController@SignAuthoritySave']);
Route::post('/getSignAuthorityDetails','SignAuthorityController@getSignAuthorityDetails');

Route::get('/JudgeDetails', 'JudgeDetailsController@JudgeDetails');
Route::post('/JudgeDetailsSave', [ 'as' => 'JudgeDetailsSave', 'uses' => 'JudgeDetailsController@JudgeDetailsSave']);
Route::get('/getJudgeDetails','JudgeDetailsController@getJudgeDetails');


Route::get('/ianature', 'ianaturecontroller@JudgeDetails');
Route::post('/ianatureSave', [ 'as' => 'ianatureSave', 'uses' => 'ianaturecontroller@ianatureSave']);
Route::get('/getianature','ianaturecontroller@getianature');


Route::get('/FilingCounter',  [ 'as' => 'FillingCounter', 'uses' =>'FillingCounterController@FillingCounter']);
Route::post('/FillingCounterSave', [ 'as' => 'FillingCounterSave', 'uses' => 'FillingCounterController@FillingCounterSave']);
Route::post('/getFillingCounterDetails', 'FillingCounterController@getFillingCounterDetails');




//-----------------------------------
//Create User - mini
Route::get('/createUser', 'Auth\RegisterController@createUser')->name('createUser');
Route::post('/saveNewUser', 'Auth\RegisterController@saveNewUser')->name('saveNewUser');
Route::post('/getUserDetails','Auth\RegisterController@getUserDetails');

Route::get('/preparecl','AdminController@preparecl')->name('preparecl');

//------------------ 
//scrutiny
Route::get('scrutiny','Scrutiny\ScrutinyController@index')->name('scrutiny');
Route::post('/showApplicationScrutiny','Scrutiny\ScrutinyController@showApplicationScrutiny')->name('showApplicationScrutiny');
Route::post('/saveApplicationScrutiny','Scrutiny\ScrutinyController@saveApplicationScrutiny')->name('saveApplicationScrutiny');

Route::get('scrutinyCompliance','Scrutiny\ScrutinyController@scrutinyCompliance')->name('scrutinyCompliance');
Route::post('/showScrutinyCompliance','Scrutiny\ScrutinyController@showScrutinyCompliance')->name('showScrutinyCompliance');
Route::post('/saveScrutinyCompliance','Scrutiny\ScrutinyController@saveScrutinyCompliance')->name('saveScrutinyCompliance');
Route::get('scrutinyCheckSlip','Scrutiny\ScrutinyController@scrutinyCheckSlip')->name('scrutinyCheckSlip');
Route::post('printScrutinyCheckSlip', [ 'as' => 'printScrutinyCheckSlip','uses'=>'Scrutiny\ScrutinyController@printScrutinyCheckSlip']);


Route::post('/getApplicantRespondantDetails', 'CaseManagementController@getApplicantRespondantDetails');
Route::post('/getReceiptDetails', 'ReceiptController@getReceiptDetails');


//freshApplication
Route::get('/freshApplication', 'Application\ApplicationController@index')->name('freshApplication');
Route::post('/saveFreshApplication','Application\ApplicationController@saveFreshApplication')->name('saveFreshApplication');
Route::get('/applicationDisposed', 'Application\ApplicationController@applicationDisposed')->name('applicationDisposed');
Route::post('/saveDisposedApplication','Application\ApplicationController@saveDisposedApplication')->name('saveDisposedApplication');
Route::post('/getDisposedApplicationDetails', 'Application\ApplicationController@getDisposedApplicationDetails');

Route::get('/additionalnumbers', 'Application\ApplicationController@additionalnumbers')->name('additionalnumbers');
Route::post('/addAdditionalNumber','Application\ApplicationController@addAdditionalNumber')->name('addAdditionalNumber');
Route::get('/detailEntryApplication', 'Application\ApplicationController@detailEntryApplication')->name('detailEntryApplication');
Route::get('/applagainst', 'Application\ApplicationController@applagainst')->name('applagainst');
Route::post('/updateapplagainst','Application\ApplicationController@updateapplagainst')->name('updateapplagainst');

Route::get('/documentfiling', 'Application\DocumentFilingController@index')->name('documentfiling');
Route::post('/savedocumentfiling','Application\DocumentFilingController@savedocumentfiling')->name('savedocumentfiling');

Route::get('/uploadjudgements', 'Application\JudgementController@uploadjudgements')->name('uploadjudgements');
Route::post('/savejudgements','Application\JudgementController@savejudgements')->name('savejudgements');

Route::get('/searchjudgements', 'Application\JudgementController@SearchJudgements')->name('SearchJudgements');
Route::post('/DownloadJudgements','Application\JudgementController@DownloadJudgements')->name('DownloadJudgements');
Route::post('/getJudgementApplicantDetails', 'Application\JudgementController@getJudgementApplicantDetails');

Route::get('/issuefreecopy', 'Application\JudgementController@issuefreecopy')->name('issuefreecopy');
Route::post('/getFreeCopyApplRespondantStatus', 'Application\JudgementController@getFreeCopyApplRespondantStatus');

Route::post('/saveFreeCopyStatus','Application\JudgementController@saveFreeCopyStatus')->name('saveFreeCopyStatus');


Route::get('/gethearingdtls', 'ChpController@gethearingdtls')->name('gethearingdtls');
Route::post('/updateDailyHearing','ChpController@updateDailyHearing')->name('updateDailyHearing');

Route::get('/displayboard', 'ChpController@showdisplayboard')->name('showdisplayboard');
Route::post('/getdisplayboard','ChpController@getdisplayboard')->name('getdisplayboard');



//initialize
Route::get('initialize', 'CauselistController@initializeCauselist');
Route::post('saveInitializeCauselist','CauselistController@saveInitializeCauselist');


 //Court Hall Proceedings 
Route::get('/Proceeding', [ 'as' => 'ChProceedingShow', 'uses' => 'ChpController@ChpShow']);
//Route::get('/getCHPApplication', [ 'as' => 'getCHPApplication', 'uses' => 'ChpController@getCHPApplication']);

Route::post('/getCHPApplication','ChpController@getCHPApplication');
Route::put('/ProceedingUpdate', [ 'as' => 'ChProceedingUpdate', 'uses' => 'ChpController@ChpUpdate']);
Route::get('/BulkProceeding', [ 'as' => 'ChBulkProceedingShow', 'uses' => 'ChpController@ChpBulkShow']);
Route::put('/BulkProceedingUpdate', [ 'as' => 'ChBulkProceedingUpdate', 'uses' => 'ChpController@ChpBulkUpdate']);
Route::get('/ChpAjax', [ 'as' => 'ChpAjax', 'uses' => 'ChpController@ChpAjax']);
Route::post('/ChpAjaxPost', [ 'as' => 'ChpAjaxPost', 'uses' => 'ChpController@ChpAjaxPost']);


//MIni
Route::get('/ordergeneration', 'CaseFollowupController@viewordergenerationform')->name('ordergeneration');
Route::post('ordergenerate', ['uses' => 'CaseFollowupController@ordergenerate',
            'as' => 'ordergenerate'
        ]);
Route::get('/generatenotice', 'CaseFollowupController@viewgeneratenotice')->name('generatenotice');

Route::post('/getHearingDetailsByApplication','CaseFollowupController@getHearingDetailsByApplication');




Route::get('table_names', [
            'uses' => 'ReportController@table_names',
            'as' => 'table_names'
        ]);

Route::get('column_names', [
            'uses' => 'ReportController@column_names',
            'as' => 'column_names'
        ]);

Route::post('details', [
            'uses' => 'ReportController@details',
            'as' => 'details'
        ]);

Route::get('ordersheet', [
            'uses' => 'OrderController@ordersheet',
            'as' => 'ordersheet'
        ]);

/*Route::post('ordergenerate1', [
            'uses' => 'OrderController@ordergenerate',
            'as' => 'ordergenerate'
        ]);*/

