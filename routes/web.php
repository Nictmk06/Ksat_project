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
// 	var_dump($data);
// exit;
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

Route::get('/Circular', 'Application\ApplicationController@Circular');

//Route::get('{slug}',[ 'as' => 'pages.show','uses'=>'Auth\LoginController@show']);
Route::post('login', [ 'as' => 'userLogin','uses'=>'Auth\LoginController@verifyLogin']);
// Hegade -------------------------------------------------------------------
// Receipt generation (Add/Edit/receipt generation)

Route::post('/getDDExist', 'ReceiptController@getDDExist')->middleware('CheckAuthorization:Receipts');

Route::get('/receiptEntryIndex', [ 'as' => 'receiptCrudIndex', 'uses' => 'ReceiptController@index'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptCrudCreate', [ 'as' => 'receiptCrudCreate', 'uses' => 'ReceiptController@create'])->middleware('CheckAuthorization:Receipts');
Route::post('/receiptCrudStore', [ 'as' => 'receiptCrudStore', 'uses' => 'ReceiptController@store'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptCrudEdit', [ 'as' => 'receiptCrudEdit', 'uses' => 'ReceiptController@edit'])->middleware('CheckAuthorization:Receipts');
Route::PUT('/receiptCrudUpdate', [ 'as' => 'receiptCrudUpdate', 'uses' =>  'ReceiptController@update'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptCrudEditlist', [ 'as' => 'receiptCrudEditlist', 'uses' => 'ReceiptController@editlist'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptCrudShow', [ 'as' => 'receiptCrudShow', 'uses' => 'ReceiptController@show'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptCrudDtShow', [ 'as' => 'receiptCrudDtShow', 'uses' => 'ReceiptController@showdtrange'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptGetFeeAmount', [ 'as' => 'receiptCrudFeeAmount', 'uses' => 'ReceiptController@getfeeamount'])->middleware('CheckAuthorization:Receipts');
Route::get('receiptCrudGen', [ 'as' => 'receiptCrudGen', 'uses' => 'ReceiptController@receiptgen'])->middleware('CheckAuthorization:Receipts');
// Receipt day account closing
Route::get('/receiptCloseDtGet', [ 'as' => 'receiptCrudCloseDtget', 'uses' => 'ReceiptController@receiptclosedtget'])->middleware('CheckAuthorization:Receipts');
Route::post('/receiptCrudCloseStore', [ 'as' => 'receiptCrudCloseStore', 'uses' => 'ReceiptController@receiptclosestore'])->middleware('CheckAuthorization:Receipts');
// Receipt reports
Route::get('/receiptReport', [ 'as' => 'receiptCrudReport', 'uses' => 'ReceiptController@receiptreport'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptRepDailyGetDt', [ 'as' => 'receiptCrudRepDailyGetDt', 'uses' => 'ReceiptController@receiptrepdailygetdt'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptRepSummGetDt', [ 'as' => 'receiptCrudRepSummGetDt', 'uses' => 'ReceiptController@receiptrepsummgetdt'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptCrudRepDaily', [ 'as' => 'receiptCrudRepDaily', 'uses' => 'ReceiptController@receiptrepdaily'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptCrudRepSummary', [ 'as' => 'receiptCrudRepSummary', 'uses' => 'ReceiptController@receiptrepsummary'])->middleware('CheckAuthorization:Receipts');
Route::get('/receiptGetBank', [ 'as' => 'receiptCrudGetBank', 'uses' => 'ReceiptController@receiptgetbank'])->middleware('CheckAuthorization:Receipts');

//Receipt with application number format
Route::get('/receiptCrudCreateWtApp', [ 'as' => 'receiptCrudCreateWtApp', 'uses' => 'ReceiptController@receiptCrudCreateWtApp'])->middleware('CheckAuthorization:Receipts');
Route::post('/getApplicationDetailsFreshApplicationReceipt','ReceiptController@getApplicationDetailsFreshApplicationReceipt')->middleware('CheckAuthorization:Receipts');
Route::post('/receiptCrudStoreWtApp', [ 'as' => 'receiptCrudStoreWtApp', 'uses' => 'ReceiptController@receiptCrudStoreWtApp'])->middleware('CheckAuthorization:Receipts');

//---------------------------------------------------------


Route::get('/dashboardmain', 'charts\dashboardcontroller@index')->middleware('CheckAuthorization:Legacy');

Route::get('/dashboardmain1', function(Request $request)
{
 $data['userAppDet'] = DB::select('select * from usercount1');
     $data['applicationcnt']  = DB::select('select appltypedesc,appltypecode,sum(applicationcnt) as applicationcnt,sum(applicantcnt) as applicantcnt from applicationcount
group by appltypedesc,appltypecode order by appltypecode');

$data['pendingapplcnt']  = DB::select('select appltypedesc,appltypecode,sum(applicationcnt) as applicationcnt,sum(applicantcnt) as applicantcnt from applicationcount where statuscode = 1 or statuscode is null
group by appltypedesc,appltypecode order by appltypecode');

$data['disposedapplcnt'] = DB::select("SELECT apt.appltypedesc, count(applicationid) as applicationcnt,
sum(applicantcount) as applicantcnt
from public.applicationdisposed as ad
left join applicationtype apt on apt.appltypecode = ad.appltypecode
where ad.establishcode='$establishcode' group by apt.appltypedesc,apt.appltypecode order by apt.appltypecode");

	$data['title']='Dashboard';

   return view('dashboard.dashboardmain',$data);

   // return view('dashboard',$datmaina);
})->middleware('CheckAuthorization:Legacy');

Route::get('/dashboardchart', function(Request $request)
{

	$data['title']='Dashboard Charts';

   return view('dashboard.dashboardchart',$data);

   // return view('dashboard',$datmaina);
})->middleware('CheckAuthorization:Legacy');



Route::get('/piechart', 'charts\piechartcontroller@index')->middleware('CheckAuthorization:Legacy');
Route::get('/barchart', 'charts\barchartcontroller@index')->middleware('CheckAuthorization:Legacy');
Route::get('/stackedbarchart', 'charts\stackedbarchartcontroller@index')->middleware('CheckAuthorization:Legacy');
Route::get('/candlestickchart', 'charts\candlestickchartcontroller@index')->middleware('CheckAuthorization:Legacy');
Route::get('/donutchart', 'charts\donutchartcontroller@index')->middleware('CheckAuthorization:Legacy');
Route::get('/donutchart1', 'charts\JSchartController@index')->middleware('CheckAuthorization:Legacy');
//Route::get('/ARStatus','ExtraAdvocateController@show')->name('ARStatus');

Route::get('/ExtraAdvocate', 'ExtraAdvocateController@index')->middleware('CheckAuthorization:Application');

Route::resource('/ExtraAdvocateController', 'ExtraAdvocateController')->middleware('CheckAuthorization:Application');

Route::post('caseDataStore', [ 'as' => 'caseDataStore','uses'=>'CaseManagementController@store'])->middleware('CheckAuthorization:Legacy,Application');
Route::post('/receiptDataStore', [ 'as' => 'receiptDataStore','uses'=>'CaseManagementController@receiptStore']);
Route::post('applicantDataStore', [ 'as' => 'applicantDataStore','uses'=>'CaseManagementController@applicantStore'])->middleware('CheckAuthorization:Legacy,Application');
Route::post('appresstore', [ 'as' => 'AppDataStore','uses'=>'CaseManagementController@applicantrespondentstore'])->middleware('CheckAuthorization:Legacy,Application');

Route::post('respondantDataStore', [ 'as' => 'respondantDataStore','uses'=>'CaseManagementController@respondantStore'])->middleware('CheckAuthorization:Legacy,Application');
Route::post('applicationIndexDataStore', [ 'as' => 'applicationIndexDataStore','uses'=>'CaseManagementController@applIndexStore'])->middleware('CheckAuthorization:Legacy,Application');

Route::post('extraapplicantStore', [ 'as' => 'extraapplicantStore','uses'=>'CaseManagementController@extraapplicantStore'])->middleware('CheckAuthorization:Legacy,Application');
Route::post('extrarespondantStore', [ 'as' => 'extrarespondantStore','uses'=>'CaseManagementController@extrarespondantStore'])->middleware('CheckAuthorization:Legacy,Application');


Route::post('addRelief',[ 'as' => 'releifDataStore','uses'=>'CaseManagementController@AddreliefDetails'])->middleware('CheckAuthorization:Application,Legacy');
Route::post('/getApplicationDetails','CaseManagementController@getApplBasedOnID')->middleware('CheckAuthorization:Application,Legacy');

Route::post('/getApplicationDetailsFreshApplication','CaseManagementController@getApplBasedOnIDFreshApplication')->middleware('CheckAuthorization:Application');

//Route::post('/applicationStatus', 'CaseManagementController@ApplicationStatusCreate');
Route::get('/case', 'CaseManagementController@index')->middleware('CheckAuthorization:Legacy');

Route::get('/modules', 'ModuleController@index');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/caseEntry', 'CaseManagementController@create');
Route::get('/receiptEntry', 'CaseManagementController@receiptCreate');
Route::post('/receipts', 'CaseManagementController@receiptDet');
Route::post('/advRegNo', 'CaseManagementController@getAdvRegNo')->middleware('CheckAuthorization:Application,Legacy');
Route::post('/getResAdv','CaseManagementController@getResAdvReg')->middleware('CheckAuthorization:Application,Legacy');

Route::post('/getTempDate','CaseManagementController@getResAdvReg');
Route::post('/getReceipt', 'CaseManagementController@receiptDetails');
Route::post('/updateReceipt', 'CaseManagementController@updateReceiptData');
Route::post('/getApplicant', 'CaseManagementController@ApplicantDetails');
Route::post('/updateApplicant', 'CaseManagementController@UpdateApplicantDetails');
Route::post('/getRespondant', 'CaseManagementController@RespondantDetails');
Route::post('/updateRespondant', 'CaseManagementController@UpdatRespondantDetails');
Route::post('/updateCaseDetails', 'CaseManagementController@UpdatCaseDetails');
Route::post('/getApplicantData', 'CaseManagementController@ApplicantSerialDetails')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getRespondantData', 'CaseManagementController@RespondantSerialDetails')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getsubmenu', 'Auth\LoginController@submenu');
Route::post('/getTaluk', 'Auth\LoginController@getTaluk');
Route::post('/getApplication', 'CaseManagementController@ApplicationDetails');

Route::post('/getApplicationDetailsCauseList', 'CaseManagementController@ApplicationDetailsCauseList');
Route::resource('/IADocument', 'IADocument')->middleware('CheckAuthorization:Legacy,Application');


Route::post('/getIADocAppl', 'IADocument@show')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/IAExistance', 'IADocument@checkIAExistance');


Route::post('/getSingleAdv','CaseManagementController@getSinglApplAdv')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getResSingleAdv','CaseManagementController@getResSinglApplAdv')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/storeDailyHearing', [ 'as' => 'storeDailyHearing','uses'=>'IADocument@dailyHearingstore'])->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getIADocApplSerial', 'IADocument@getIADocumentSerialWise')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getPendingIADoc','IADocument@getPendingIADocuments')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getHearingDet', 'IADocument@getHearingDocument')->middleware('CheckAuthorization:Legacy,Application,Court Hall');
Route::post('/getHearingCodeWise', 'IADocument@getHearingCodeDet');
Route::post('Connectedstore', [ 'as' => 'ConnectedstoreData','uses'=>'ConnectedApplicationController@store']);
Route::post('/getAppSrCount','CaseManagementController@ApplicationSrCount')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getLastSerialNo','CaseManagementController@LastApplicantSrNo')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getResSrCount','CaseManagementController@RespondantSrCount')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getRsLastSerialNo','CaseManagementController@LastRespondantSrNo')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getApplicationExistance','CaseManagementController@getAppIDExistance');
Route::post('/getReceiptExistance','CaseManagementController@getReceiptExistance');
Route::post('/getRelief','CaseManagementController@getReliefSought')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getreliefData','CaseManagementController@getReliefSrSought')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/deleteApplication','CaseManagementController@deleteApplication')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getSingleAdvDet','CaseManagementController@getSingleAdvDetails');
Route::post('/getResSingleAdvDet','CaseManagementController@getResSingleAdvDetails');
Route::post('/getDepatmentName','CaseManagementController@getDepartmentNames');

Route::post('/storeDesignation','DesignationController@store');



Route::post('/storeAdvocate','AdvocateController@store');

Route::post('/getDesignation','CaseManagementController@getDesignation')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getDesignationByDepartment','CaseManagementController@getDesignationByDepartment')->middleware('CheckAuthorization:Legacy,Application');


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
})->middleware('CheckAuthorization:Legacy,Application');



/////////////////////Route to iadocument//////////////////////////////////////

Route::post('/getRegDate','IADocument@getPrevIARegDate');
Route::post('/getBenchJudges','IADocument@getBenchjudges');
Route::post('/getApplicationIndex','IADocument@getApplicationIndexDetails');
Route::post('/getApplRespondant','IADocument@getApplRespondants')->middleware('CheckAuthorization:Legacy,Application,Judgements');
Route::post('/getStartPageOfApplIndex','IADocument@getStartPage')->middleware('CheckAuthorization:Legacy');
Route::post('/updateIAStatus','IADocument@updateIAStatusDetails');
Route::post('/getIANo','IADocument@getIANoDet');
Route::post('/getHearingDetails','IADocument@getHearingDet')->middleware('CheckAuthorization:Legacy,Application,Court Hall');
Route::post('/getIABasedOnHearing','IADocument@getIAOnHearing')->middleware('CheckAuthorization:Legacy,Court Hall');
Route::post('/getExtraAdvocate','ExtraAdvocateController@getExtraAdvocateDet')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getExtraAdvDetails','ExtraAdvocateController@getEachExtraAdvDet')->middleware('CheckAuthorization:Legacy,Application');
Route::post('/getAdvocateUnique','ExtraAdvocateController@getUniqueAdvDet')->middleware('CheckAuthorization:Legacy,Application');
Route::get('/ARStatus','ExtraAdvocateController@show')->name('ARStatus')->middleware('CheckAuthorization:Application');


Route::get('/Groupno','ExtraAdvocateController@showgroup')->name('Groupno')->middleware('CheckAuthorization:Legacy,Filing Counter');
Route::post('/getGroupApplications','ExtraAdvocateController@getGroupApplications')->middleware('CheckAuthorization:Legacy,Filing Counter');
Route::post('storegroupno', [ 'as' => 'groupDataStore','uses'=>'ExtraAdvocateController@storegroupno'])->middleware('CheckAuthorization:Legacy,Filing Counter');
//Route::post('/statusupdate','ExtraAdvocateController@statusupdatestatusupdate')->middleware('CheckAuthorization:Application');
Route::post('statusupdate', [ 'as' => 'statusDataStore','uses'=>'ExtraAdvocateController@statusupdate'])->middleware('CheckAuthorization:Application');




//Connect Application
Route::get('/ConnectedApplication', 'ConnectedApplicationController@index')->middleware('CheckAuthorization:Application');
Route::post('/getConnectApplications','ConnectedApplicationController@getConnected')->middleware('CheckAuthorization:Application');
Route::post('/getConAppl','ConnectedApplicationController@getConApplDet')->middleware('CheckAuthorization:Application');
Route::post('/getConStatus','ConnectedApplicationController@getConStatusdetails')->middleware('CheckAuthorization:Application');

//Connect Application

Route::post('/addCauselisttempappl','CauselistController@addcauselist');
Route::post('/addCauselisttempappl','CauselistController@addcauselist');
Route::post('/getUserApplicationCount','CaseManagementController@getUserApplCount');
Route::get('/searchapplication','ApplicationDetailsController@index')->middleware('CheckAuthorization:Application');
Route::post('appstatusresult','ApplicationDetailsController@appstatus')->middleware('CheckAuthorization:Application');
Route::post('/getCauselistdetails','CauselistController@getCauselist')->middleware('CheckAuthorization:Cause List');
Route::post('/getCauselistDataNew','CauselistController@getCauseBasedOnlistno')->middleware('CheckAuthorization:Cause List');
Route::post('/getRearrangeCauselistData','CauselistController@getCausereorderData')->middleware('CheckAuthorization:Cause List');
Route::post('/rearangeCauseOrder','CauselistController@causelistsrreorder')->middleware('CheckAuthorization:Cause List');



Route::resource('/CauselistController', 'CauselistController')->middleware('CheckAuthorization:Cause List');
Route::resource('/causelistfinalize', 'Causelistfinalize')->middleware('CheckAuthorization:Cause List');
Route::resource('/Causelistverify', 'Causelistverify')->middleware('CheckAuthorization:Cause List');
Route::resource('/AdminController', 'AdminController');

//causelist
Route::get('/Causelist','CauselistController@index')->name('Causelist')->middleware('CheckAuthorization:Cause List');
Route::get('/CauselistDated','CauselistController@show')->name('CauselistDated')->middleware('CheckAuthorization:Cause List');

//causelist

// causelist verify
Route::post('/getcauselistappl','Causelistverify@getcauselistappl')->middleware('CheckAuthorization:Cause List');
Route::post('/getcauselistapplconnect','Causelistverify@getcauselistapplconnect')->middleware('CheckAuthorization:Cause List');
Route::post('/getcauselistconnectedappl','Causelistverify@getcauselistconnectedappl')->middleware('CheckAuthorization:Cause List');
Route::post('/getpreviouscauselistremarks','Causelistverify@getpreviouscauselistremarks')->middleware('CheckAuthorization:Cause List');
Route::post('/getcauselistremarks','Causelistverify@getcauselistremarks')->middleware('CheckAuthorization:Cause List');
Route::post('/updatecauselistheader','Causelistverify@updatecauselistheader')->middleware('CheckAuthorization:Cause List');

//causelist finalize
Route::post('/getcauselist','Causelistfinalize@getcauselist')->middleware('CheckAuthorization:Cause List,Court Hall');
//Route::post('/printcauselist','Causelistfinalize@store');
Route::post('finalizecauselist','Causelistfinalize@finalizecauselist')->middleware('CheckAuthorization:Cause List');
Route::post('printcauselist', [ 'as' => 'printcauselist','uses'=>'Causelistfinalize@store'])->middleware('CheckAuthorization:Cause List');

//Previous causelist
Route::get('/previousCauseList', 'Causelistfinalize@previousCauseList')->name('previousCauseList')->middleware('CheckAuthorization:Cause List');
Route::post('/getcauselistfrompreviouscl','Causelistfinalize@getcauselistfrompreviouscl')->middleware('CheckAuthorization:Cause List');
Route::post('/printpreviouscauselist','Causelistfinalize@printpreviouscauselist')->name('printpreviouscauselist')->middleware('CheckAuthorization:Cause List');
//Previous causelist

//Move cause list
Route::get('/movecauselist', 'CauselistTransferController@movecauselist')->name('movecauselist')->middleware('CheckAuthorization:Cause List');
Route::post('/moveCauseListSave','CauselistTransferController@moveCauseListSave')->name('moveCauseListSave')->middleware('CheckAuthorization:Cause List');


//transfer Cause List
Route::get('/causelisttransfer', 'CauselistTransferController@index')->name('causelisttransfer')->middleware('CheckAuthorization:Cause List');
Route::post('/getFinalizedcauselist','CauselistTransferController@getFinalizedcauselist')->middleware('CheckAuthorization:Cause List');
Route::post('/getcauselistapplforTransfer','CauselistTransferController@getcauselistapplforTransfer')->middleware('CheckAuthorization:Cause List');
Route::post('/transferCauseList','CauselistTransferController@transferCauseList')->name('transferCauseList')->middleware('CheckAuthorization:Cause List');



//---------------------------------------
// Change Password - prashant
Route::get('/changePassword', 'Auth\ResetPasswordController@changePassword');
Route::post('/changePasswordSave', [ 'as' => 'changePasswordSave', 'uses' => 'Auth\ResetPasswordController@changePasswordSave']);


//-------------------
//Master
Route::get('applcategory', 'applcategoryController@applcategory');
Route::post('/applcategorySave', [ 'as' => 'applcategorySave', 'uses' => 'applcategoryController@applcategorySave']);
Route::get('/getapplcategory','applcategoryController@getapplcategory');
Route::get('Department', 'DepartmentController@AddNewDepartment')->middleware('CheckAuthorization:Master');
Route::post('/AddNewDepartmentSave', [ 'as' => 'AddNewDepartmentSave', 'uses' => 'DepartmentController@AddNewDepartmentSave'])->middleware('CheckAuthorization:Master');
Route::post('/getDepartmentDetails','DepartmentController@getDepartmentDetails')->middleware('CheckAuthorization:Master');
Route::get('/findDistrictWithTaluk/{districtID}','DepartmentController@findDistrictWithTaluk')->middleware('CheckAuthorization:Master');
Route::get('listpurpose', 'ListPurposeController@listpurpose')->middleware('CheckAuthorization:Master');
Route::post('ListPurposeSave', [ 'as' => 'ListPurposeSave', 'uses' => 'ListPurposeController@ListPurposeSave'])->middleware('CheckAuthorization:Master');
Route::get('/getListPurposeDetails', 'ListPurposeController@getListPurposeDetails')->middleware('CheckAuthorization:Master');

// bench Ajay --------------------------------------------------------
Route::get('bench','BenchController@index')->middleware('CheckAuthorization:Master');
Route::get('bench','BenchController@show')->middleware('CheckAuthorization:Master');
Route::post('save','BenchController@store')->middleware('CheckAuthorization:Master');
Route::post('/benchsave','BenchController@store')->middleware('CheckAuthorization:Master');
Route::post('/Bench/fetch','BenchController@fetch')->name('/Bench/fetch')->middleware('CheckAuthorization:Master');
Route::get('/Bench/fetch','BenchController@fetch')->name('/Bench/fetch')->middleware('CheckAuthorization:Master');

//Route::get('/benchedit','BenchController@edit')->middleware('CheckAuthorization:BenchController');
Route::get('/benchedit', [ 'as' => 'benchedit', 'uses' => 'BenchController@edit']);

Route::post('benchcontroller/fetch', 'BenchController@fetch')->name('benchcontroller.fetch')->middleware('CheckAuthorization:Master');
Route::get('dropdownlist','BenchController@getBenches')->middleware('CheckAuthorization:Master');
Route::get('dropdownlist/getjcount/{judgecount}','BenchController@getjcount')->middleware('CheckAuthorization:Master');
Route::get('getjcount','BenchController@getjcount')->middleware('CheckAuthorization:Master');

Route::get('/json-judgenumber','CountryController@judgenumber');
Route::resource('bench', 'BenchController')->middleware('CheckAuthorization:Master');
Route::get('edit','BenchController@edit')->middleware('CheckAuthorization:Master');
Route::post('/update','BenchController@update')->middleware('CheckAuthorization:Master');


Route::get('create','BenchController@show')->middleware('CheckAuthorization:Master');
Route::get('bench/create','BenchController@show')->middleware('CheckAuthorization:Master');
Route::post('fetch','BenchController@fetch')->name('benchcontroller.fetch')->middleware('CheckAuthorization:Master');
Route::get('/bench/edit','BenchController@edit')->middleware('CheckAuthorization:Master');
Route::get('bench/update','BenchController@update')->middleware('CheckAuthorization:Master');
Route::get('/SignAuthority', 'SignAuthorityController@SignAuthority')->middleware('CheckAuthorization:Master');
Route::post('/SignAuthoritySave', [ 'as' => 'SignAuthoritySave', 'uses' => 'SignAuthorityController@SignAuthoritySave'])->middleware('CheckAuthorization:Master');
Route::post('/getSignAuthorityDetails','SignAuthorityController@getSignAuthorityDetails')->middleware('CheckAuthorization:Master');
Route::get('/JudgeDetails', 'JudgeDetailsController@JudgeDetails')->middleware('CheckAuthorization:Master');
Route::post('/JudgeDetailsSave', [ 'as' => 'JudgeDetailsSave', 'uses' => 'JudgeDetailsController@JudgeDetailsSave'])->middleware('CheckAuthorization:Master');
Route::get('/getJudgeDetails','JudgeDetailsController@getJudgeDetails')->middleware('CheckAuthorization:Master');
Route::get('/ianature', 'ianaturecontroller@JudgeDetails')->middleware('CheckAuthorization:Master');
Route::post('/ianatureSave', [ 'as' => 'ianatureSave', 'uses' => 'ianaturecontroller@ianatureSave'])->middleware('CheckAuthorization:Master');
Route::get('/getianature','ianaturecontroller@getianature')->middleware('CheckAuthorization:Master');
Route::get('/adddesignation', 'DesignationController@adddesignation')->middleware('CheckAuthorization:Master');
Route::post('/savedeptdesigmapping', 'DesignationController@savedeptdesigmapping')->name('savedeptdesigmapping')->middleware('CheckAuthorization:Master');


Route::get('/AddAdvocateDetails', 'AddAdvocateDetailsController@AddAdvocateDetails')->middleware('CheckAuthorization:Master');
Route::post('/AddAdvocateSave', [ 'as' => 'AddAdvocateSave', 'uses' => 'AddAdvocateDetailsController@AddAdvocateSave'])->middleware('CheckAuthorization:Master');
//Route::get('/getAdvocateDetails','AddAdvocateDetailsController@getAdvocateDetails')->middleware('CheckAuthorization:Master');
Route::get('/findDistrict_Taluk/{districtcode}','AddAdvocateDetailsController@findDistrict_Taluk')->middleware('CheckAuthorization:Master');
Route::get('/findDistrict_Taluk_resident/{districtcode}','AddAdvocateDetailsController@findDistrict_Taluk_resident')->middleware('CheckAuthorization:Master');
Route::get('/getdetailsofadvocate','AddAdvocateDetailsController@getApplBasedOnID')->middleware('CheckAuthorization:Master');
Route::get('/deleteAdvocatedetails','AddAdvocateDetailsController@deleteAdvocatedetails')->middleware('CheckAuthorization:Master');




//Master

//User
Route::get('optionsmaster', 'optionsmasterController@optionsmaster')->middleware('CheckAuthorization:User');
Route::post('/optionsmasterSave', [ 'as' => 'optionsmasterSave', 'uses' => 'optionsmasterController@optionsmasterSave'])->middleware('CheckAuthorization:User');
Route::get('/getoptionsmasterdetails','optionsmasterController@getoptionsmasterdetails')->middleware('CheckAuthorization:User');


Route::get('/AddModule', 'AddModuleController@AddModule')->middleware('CheckAuthorization:User');
Route::post('/AddModuleSave', [ 'as' => 'AddModuleSave', 'uses' => 'AddModuleController@AddModuleSave'])->middleware('CheckAuthorization:User');
Route::get('/getModuleDetails','AddModuleController@getModuleDetails')->middleware('CheckAuthorization:User');



Route::get('userrole', 'userroleController@userrole')->middleware('CheckAuthorization:User');
Route::post('/userroleSave', [ 'as' => 'userroleSave', 'uses' => 'userroleController@userroleSave'])->middleware('CheckAuthorization:User');
Route::get('/getuserrole','userroleController@getuserrole')->middleware('CheckAuthorization:User');
Route::get('/getrole','userroleController@getrole')->middleware('CheckAuthorization:User');
Route::get('/findOptionsWithModule_userrole','userroleController@findOptionsWithModule')->middleware('CheckAuthorization:User');
Route::get('/findtablevaluesaccordingtouserid','userroleController@findtablevaluesaccordingtouserid')->middleware('CheckAuthorization:User');
Route::get('/destroy_userrole', 'userroleController@destroy')->middleware('CheckAuthorization:User');
//Route::get('/findUserWithRole/{userid}','userroleController@findUserWithRole');
//Route::get('/findModuleWithRole','userroleController@findModuleWithRole');


Route::get('rolemodule', 'rolemoduleController@rolemodule')->middleware('CheckAuthorization:User');
Route::post('/rolomoduleSave', [ 'as' => 'rolomoduleSave', 'uses' => 'rolemoduleController@rolomoduleSave'])->middleware('CheckAuthorization:User');
Route::get('/getrolemodule','rolemoduleController@getuserrole')->middleware('CheckAuthorization:User');
Route::get('/findOptionsWithModule_rolemodule','rolemoduleController@findOptionsWithModule')->middleware('CheckAuthorization:User');
Route::get('/destroy_rolemodule', 'rolemoduleController@destroy')->middleware('CheckAuthorization:User');
Route::get('/findtablevaluesaccordingtoroleid','rolemoduleController@findtablevaluesaccordingtoroleid')->middleware('CheckAuthorization:User');
Route::post('/AddRoleSave', [ 'as' => 'AddRoleSave', 'uses' => 'rolemoduleController@AddRoleSave'])->middleware('CheckAuthorization:User'); //for new role






//------------------
//scrutiny
Route::get('scrutiny','ScrutinyController@index')->name('scrutiny')->middleware('CheckAuthorization:Scrutiny');
Route::post('/showApplicationScrutiny','ScrutinyController@showApplicationScrutiny')->name('showApplicationScrutiny')->middleware('CheckAuthorization:Scrutiny');
Route::get('/getScrutinyDetailsForExtraQuestions', 'ScrutinyController@getScrutinyDetailsForExtraQuestions')->middleware('CheckAuthorization:Scrutiny');

Route::post('/saveApplicationScrutiny','ScrutinyController@saveApplicationScrutiny')->name('saveApplicationScrutiny')->middleware('CheckAuthorization:Scrutiny');
Route::post('/AddExtraQuestionaries', [ 'as' => 'AddExtraQuestionaries', 'uses' => 'ScrutinyController@AddExtraQuestionaries'])->middleware('CheckAuthorization:Scrutiny'); //for new role

Route::get('scrutinyCompliance','ScrutinyController@scrutinyCompliance')->name('scrutinyCompliance')->middleware('CheckAuthorization:Scrutiny');
Route::post('/showScrutinyCompliance','ScrutinyController@showScrutinyCompliance')->name('showScrutinyCompliance')->middleware('CheckAuthorization:Scrutiny');
Route::post('/saveScrutinyCompliance','ScrutinyController@saveScrutinyCompliance')->name('saveScrutinyCompliance')->middleware('CheckAuthorization:Scrutiny');
Route::get('scrutinyCheckSlip','ScrutinyController@scrutinyCheckSlip')->name('scrutinyCheckSlip')->middleware('CheckAuthorization:Scrutiny');
Route::post('printScrutinyCheckSlip', [ 'as' => 'printScrutinyCheckSlip','uses'=>'ScrutinyController@printScrutinyCheckSlip'])->middleware('CheckAuthorization:Scrutiny');

Route::get('scrutinyhistory','ScrutinyController@scrutinyhistory')->name('scrutinyhistory')->middleware('CheckAuthorization:Scrutiny');
Route::post('datascrutinyhistory', ['uses' => 'ScrutinyController@scrutinyhistoysearch',  'as' => 'datascrutinyhistory'  ]);
Route::get('/findScrutinydate/{applicationid}','ScrutinyController@findscrutinydate');

//scrutiny

//iascrutiny
Route::get('iascrutiny','IAScrutinyController@index')->name('iascrutiny')->middleware('CheckAuthorization:Scrutiny');
Route::post('/showiascrutiny','IAScrutinyController@showiascrutiny')->name('showiascrutiny')->middleware('CheckAuthorization:Scrutiny');
Route::post('/saveiascrutiny','IAScrutinyController@saveiascrutiny')->name('saveiascrutiny')->middleware('CheckAuthorization:Scrutiny');
Route::get('iascrutinycompliance','IAScrutinyController@iascrutinycompliance')->name('iascrutinycompliance')->middleware('CheckAuthorization:Scrutiny');
Route::post('/showIAScrutinyCompliance','IAScrutinyController@showIAScrutinyCompliance')->name('showIAScrutinyCompliance')->middleware('CheckAuthorization:Scrutiny');
Route::post('/saveIAScrutinyCompliance','IAScrutinyController@saveIAScrutinyCompliance')->name('saveIAScrutinyCompliance')->middleware('CheckAuthorization:Scrutiny');



Route::get('iascrutinycheckslip','Scrutiny\IAScrutinyController@iascrutinycheckslip')->name('iascrutinycheckslip')->middleware('CheckAuthorization:Scrutiny');
Route::post('printIAScrutinyCheckSlip', [ 'as' => 'printIAScrutinyCheckSlip','uses'=>'Scrutiny\IAScrutinyController@printIAScrutinyCheckSlip'])->middleware('CheckAuthorization:Scrutiny');



Route::post('/getApplicantRespondantDetails', 'CaseManagementController@getApplicantRespondantDetails');
Route::post('/getReceiptDetails', 'ReceiptController@getReceiptDetails')->middleware('CheckAuthorization:Legacy,Application');

//Filing Counter
//freshApplication

Route::get('/registerUnnumber', 'Application\ApplicationController@registerUnnumber')->name('registerUnnumber')->middleware('CheckAuthorization:Application,FilingCounter');
Route::post('/conversionUnnumber', 'Application\ApplicationController@conversionUnnumber')->name('conversionUnnumber')->middleware('CheckAuthorization:Application,FilingCounter');
Route::post('/getApplDetails', 'Application\ApplicationController@getApplDetails');


Route::get('FreshApplication1', 'Application\ApplicationController1@index')->name('FreshApplication1')->middleware('CheckAuthorization:Filing Counter');
Route::post('/getReceiptDtlsForFreshAppl', 'ReceiptController@getReceiptDtlsForFreshAppl')->middleware('CheckAuthorization:Filing Counter,Legacy,Application,CCA');
Route::post('saveFreshApplication1','Application\ApplicationController1@saveFreshApplication1')->name('saveFreshApplication1')->middleware('CheckAuthorization:Filing Counter');


Route::get('/freshApplication', 'Application\ApplicationController@index')->name('freshApplication')->middleware('CheckAuthorization:Filing Counter');
Route::post('/getReceiptDtlsForFreshAppl', 'ReceiptController@getReceiptDtlsForFreshAppl')->middleware('CheckAuthorization:Filing Counter,Legacy,Application,CCA');
Route::post('/saveFreshApplication','Application\ApplicationController@saveFreshApplication')->name('saveFreshApplication')->middleware('CheckAuthorization:Filing Counter');

Route::get('/FilingCounter',  [ 'as' => 'FilingCounter', 'uses' =>'FilingCounterController@FilingCounter'])->middleware('CheckAuthorization:Filing Counter');
Route::post('/FilingCounterSave', [ 'as' => 'FilingCounterSave', 'uses' => 'FilingCounterController@FilingCounterSave'])->middleware('CheckAuthorization:Filing Counter');
Route::post('/getFilingCounterDetails', 'FilingCounterController@getFilingCounterDetails')->middleware('CheckAuthorization:Filing Counter');
Route::get('/additionalnumbers', 'Application\ApplicationController@additionalnumbers')->name('additionalnumbers')->middleware('CheckAuthorization:Filing Counter');
Route::post('/addAdditionalNumber','Application\ApplicationController@addAdditionalNumber')->name('addAdditionalNumber')->middleware('CheckAuthorization:Filing Counter');

//Filing Counter


//Legacy
Route::get('/applicationDisposed', 'Application\ApplicationController@applicationDisposed')->name('applicationDisposed')->middleware('CheckAuthorization:Legacy');
Route::post('/saveDisposedApplication','Application\ApplicationController@saveDisposedApplication')->name('saveDisposedApplication')->middleware('CheckAuthorization:Legacy');
Route::post('/getDisposedApplicationDetails', 'Application\ApplicationController@getDisposedApplicationDetails');
//Legacy


//Application
Route::get('/detailEntryApplication', 'Application\ApplicationController@detailEntryApplication')->name('detailEntryApplication')->middleware('CheckAuthorization:Application');
Route::get('/applagainst', 'Application\ApplicationController@applagainst')->name('applagainst')->middleware('CheckAuthorization:Application');
Route::post('/getApplicationDetailsDisposed', 'CaseManagementController@getApplicationDetailsDisposed');
Route::post('/getApplTypeReferDetails', 'CaseManagementController@getApplTypeReferDetails');
Route::post('/updateapplagainst','Application\ApplicationController@updateapplagainst')->name('updateapplagainst')->middleware('CheckAuthorization:Application');

Route::get('/documentfiling', 'Application\DocumentFilingController@index')->name('documentfiling')->middleware('CheckAuthorization:Application');
Route::post('/savedocumentfiling','Application\DocumentFilingController@savedocumentfiling')->name('savedocumentfiling')->middleware('CheckAuthorization:Application');

Route::get('DisconnectApplication', 'DisconnectConnectedApplicationController@DisconnectConnectedApplication')->middleware('CheckAuthorization:Application');
Route::GET('/getConnectedAppDetails/{connectionid}', 'DisconnectConnectedApplicationController@getConnectedAppDetails')->middleware('CheckAuthorization:Application');
Route::GET('/getDisConnectedAppDetails/{connectionid}', 'DisconnectConnectedApplicationController@getDisConnectedAppDetails')->middleware('CheckAuthorization:Application');
Route::post('/DisconnectConnectedSave', [ 'as' => 'DisconnectConnectedSave', 'uses' => 'DisconnectConnectedApplicationController@DisconnectConnectedSave'])->middleware('CheckAuthorization:Application');

Route::post('/causetitletext', 'CaseManagementController@causetitletext');
Route::post('/causetitletext1', 'CaseManagementController@causetitletext1');
//Application

//Judgements


Route::get('/deletejudgement', 'Application\JudgementController@deletejudgement')->name('deletejudgement')->middleware('CheckAuthorization:Admin');
Route::post('/deletejudgements','Application\JudgementController@deletejudgementfunction' )->middleware('CheckAuthorization:Admin');
Route::get('/DownloadJudgement_delete','Application\JudgementController@DownloadJudgement_delete')->name('DownloadJudgement_delete');
Route::post('/getJudgementByApplNo','Application\JudgementController@getJudgementByApplNo')->name('getJudgementByApplNo');

Route::get('/uploadjudgements', 'Application\JudgementController@uploadjudgements')->name('uploadjudgements')->middleware('CheckAuthorization:Judgments');
Route::post('/savejudgements','Application\JudgementController@savejudgements')->name('savejudgements')->middleware('CheckAuthorization:Judgments');

Route::get('/showVerifyJudgements', 'Application\JudgementController@showVerifyJudgements')->name('showVerifyJudgements')->middleware('CheckAuthorization:Judgments');
Route::post('/getJudgementDetails', 'Application\JudgementController@getJudgementDetails')->middleware('CheckAuthorization:Judgments');
Route::post('/verifyJudgements','Application\JudgementController@verifyJudgements')->name('verifyJudgements')->middleware('CheckAuthorization:Judgments');
Route::get('/Causetitlegeneration','Application\JudgementController@Causetitlegeneration')->middleware('CheckAuthorization:Judgments');
Route::post('Causetitlegenerate', ['uses' => 'Application\JudgementController@Causetitlegenerate',
            'as' => 'Causetitlegenerate'
        ])->middleware('CheckAuthorization:Judgments');
Route::get('/searchjudgements', 'Application\JudgementController@SearchJudgements')->name('SearchJudgements')->middleware('CheckAuthorization:Judgments');

Route::post('/DownloadJudgements','Application\JudgementController@DownloadJudgements')->name('DownloadJudgements')->middleware('CheckAuthorization:Judgments');
Route::post('/DownloadJudgements1','Application\DscJudgementController@DownloadJudgements')->name('DownloadJudgements1')->middleware('CheckAuthorization:Judgments');
Route::post('/getJudgementApplicantDetails', 'Application\JudgementController@getJudgementApplicantDetails')->middleware('CheckAuthorization:Judgments');
Route::get('/issuefreecopy', 'Application\JudgementController@issuefreecopy')->name('issuefreecopy')->middleware('CheckAuthorization:Judgments');
Route::post('/getFreeCopyApplRespondantStatus', 'Application\JudgementController@getFreeCopyApplRespondantStatus')->middleware('CheckAuthorization:Judgments');
Route::post('/saveFreeCopyStatus','Application\JudgementController@saveFreeCopyStatus')->name('saveFreeCopyStatus')->middleware('CheckAuthorization:Judgments');
Route::post('/getAllJudgementByApplNo','Application\JudgementController@getAllJudgementByApplNo')->name('getAllJudgementByApplNo')->middleware('CheckAuthorization:Judgments,CCA');

Route::post('/getAllJudgementByApplNoCCA','Application\JudgementController@getAllJudgementByApplNoCCA')->name('getAllJudgementByApplNo')->middleware('CheckAuthorization:Judgments,CCA');

Route::get('/downloadJudgementByDate','Application\JudgementController@downloadJudgementByDate')->name('DownloadJudgement');

Route::get('/showVerifyJudgements1', 'Application\DscJudgementController@showVerifyJudgements')->name('showVerifyJudgements1')->middleware('CheckAuthorization:Judgments');
Route::post('/getJudgementDetails1', 'Application\DscJudgementController@getJudgementDetails')->middleware('CheckAuthorization:Judgments');
//Route::post('/verifyjudgements','Application\DscJudgementController@verifyjudgements')->name('verifyjudgements')->middleware('CheckAuthorization:Judgments');
//Judgements



// Court Hall
Route::get('initialize', 'CauselistController@initializeCauselist')->middleware('CheckAuthorization:Court Hall');
Route::post('saveInitializeCauselist','CauselistController@saveInitializeCauselist')->middleware('CheckAuthorization:Court Hall');
Route::get('/gethearingdtls', 'ChpController@gethearingdtls')->name('gethearingdtls')->middleware('CheckAuthorization:Court Hall');
Route::post('/updateDailyHearing','ChpController@updateDailyHearing')->name('updateDailyHearing')->middleware('CheckAuthorization:Court Hall');
Route::get('/displayboard', 'ChpController@showdisplayboard')->name('showdisplayboard');
Route::post('/getdisplayboard','ChpController@getdisplayboard')->name('getdisplayboard');

Route::get('/displayboardbelgavi', 'ChpController@showdisplayboardbelgavi')->name('showdisplayboardbelgavi');
Route::get('/displayboardkalburgi', 'ChpController@showdisplayboardkalburgi')->name('showdisplayboardkalburgi');

Route::get('/Proceeding', [ 'as' => 'ChProceedingShow', 'uses' => 'ChpController@ChpShow'])->middleware('CheckAuthorization:Court Hall');
Route::post('/getCHPApplication','ChpController@getCHPApplication')->middleware('CheckAuthorization:Court Hall');
Route::put('/ChProceedingUpdate', [ 'as' => 'ChProceedingUpdate', 'uses' => 'ChpController@ChpUpdate'])->middleware('CheckAuthorization:Court Hall');
Route::get('/BulkProceeding', [ 'as' => 'ChBulkProceedingShow', 'uses' => 'ChpController@ChpBulkShow'])->middleware('CheckAuthorization:Court Hall');
Route::put('/BulkProceedingUpdate', [ 'as' => 'ChBulkProceedingUpdate', 'uses' => 'ChpController@ChpBulkUpdate'])->middleware('CheckAuthorization:Court Hall');
Route::get('/ChpAjax', [ 'as' => 'ChpAjax', 'uses' => 'ChpController@ChpAjax'])->middleware('CheckAuthorization:Court Hall');
Route::post('/ChpAjaxPost', [ 'as' => 'ChpAjaxPost', 'uses' => 'ChpController@ChpAjaxPost'])->middleware('CheckAuthorization:Court Hall');
Route::post('/courthallendsession', [ 'as' => 'courthallendsession', 'uses' => 'ChpController@courthallendsession'])->middleware('CheckAuthorization:Court Hall');


Route::get('/ProceedingAppUpdate', 'ChpProcUpdateController@ChProceedingupdateShow')->name('ChProceedingupdateShow');

Route::post('/getHearingDetails1', 'ChpProcUpdateController@getHearingDetails')->name('getHearingDetails1');
Route::post('/getcourtHall', 'ChpProcUpdateController@getcourtHall')->name('getcourtHall');

Route::post('/getapphearing', 'ChpProcUpdateController@getapphearing')->name('getapphearing');

Route::post('/getordertype', 'ChpProcUpdateController@getordertype')->name('getordertype');

Route::post('/saveProceedingCP', 'ChpProcUpdateController@saveProceeding')->name('saveProceedingCP');
//Court Hall Proceedings
// Court Hall




//-----------------------------------
//Admin
Route::get('cancelcauselist', 'CauselistController@cancelcauselist')->middleware('CheckAuthorization:Admin');
Route::get('/getcauselistforrollback','CauselistController@getcauselistforrollback')->middleware('CheckAuthorization:Admin');
Route::get('RevertCauselistFlag','CauselistController@RevertCauselistFlag')->middleware('CheckAuthorization:Admin');


Route::get('/createUser', 'Auth\RegisterController@createUser')->name('createUser')->middleware('CheckAuthorization:User');
Route::post('/saveNewUser', 'Auth\RegisterController@saveNewUser')->name('saveNewUser')->middleware('CheckAuthorization:User');
Route::post('/getUserDetails','Auth\RegisterController@getUserDetails')->middleware('CheckAuthorization:User');
Route::get('/preparecl','AdminController@preparecl')->name('preparecl')->middleware('CheckAuthorization:User');
//ungroup applications
Route::get('/ungroupapplications', 'AdminController@ungroupapplications')->name('ungroupapplications')->middleware('CheckAuthorization:Admin');
Route::post('/getApplDtls','AdminController@getApplDtls')->middleware('CheckAuthorization:Admin');
Route::post('/saveungroupapplications','AdminController@saveungroupapplications')->name('saveungroupapplications')->middleware('CheckAuthorization:Admin');
Route::get('/restoreapplication', 'Application\ApplicationController@restoreapplication')->name('restoreapplication')->middleware('CheckAuthorization:Admin');
Route::post('/saveapplicationrestore','Application\ApplicationController@saveapplicationrestore')->name('saveapplicationrestore')->middleware('CheckAuthorization:Admin');


//Route::get('/dbquery', 'AdminController@dbquery')->name('dbquery')->middleware('CheckAuthorization:Admin');

Route::get('querybox','QueryBoxController@querybox');
Route::post('dataquerybox', ['uses' => 'QueryBoxController@queryboxfunction',  'as' => 'dataquerybox'  ]);

Route::get('viewdata', [
            'uses' => 'ReportController@table_names',
            'as' => 'table_names'
        ])->middleware('CheckAuthorization:Admin');

Route::get('column_names', [
            'uses' => 'ReportController@column_names',
            'as' => 'column_names'
        ])->middleware('CheckAuthorization:Admin');

Route::post('details', [
            'uses' => 'ReportController@details',
            'as' => 'details'
        ])->middleware('CheckAuthorization:Admin');

       //Laxmi
 Route::get('/advocateapproval', 'AdvocateapprovalController@advocateapproval')->name('advocateapproval');
 Route::post('/approvallist', 'AdvocateapprovalController@approvallist')->name('approvallist');
 Route::post('/saveProceeding', 'AdvocateapprovalController@saveProceeding')->name('saveProceeding');
 Route::post('/updateprofile', 'AdvocateapprovalController@updateprofile')->name('updateprofile');
 Route::get('/cancelapproval','AdvocateapprovalController@cancelapproval');



Route::get('replyquery','ReplyqueryController@replyquery');
Route::post('updatereply','ReplyqueryController@updatereply');

Route::get('acknowledgequery','AcknowledgequeryController@acknowledgequery');
Route::post('acknowledgequerydata','AcknowledgequeryController@acknowledgequerydata');
Route::post('ackdata','AcknowledgequeryController@ackdata');
Route::post('ackdataupdate','AcknowledgequeryController@ackdataupdate');

Route::get('webquery','WebqueryController@webquery');
Route::post('webquerydata','WebqueryController@webquerydata');
Route::post('sectiondata','WebqueryController@sectiondata');
//Admin




//caveat
Route::get('/caveat', 'CaveatController@index')->middleware('CheckAuthorization:Caveat');
//Route::post('caveatcontroller/fetch','CaveatController@fetch')->name('caveatcontroller.fetch')->middleware('CheckAuthorization:Caveat');
//Route::post('caveatcontroller/district','CaveatController@district')->name('caveatcontroller.district')->middleware('CheckAuthorization:Caveat');
//Route::post('caveatcontroller/districtApplicant','CaveatController@districtApplicant')->name('caveatcontroller.districtApplicant')->middleware('CheckAuthorization:Caveat');
//Route::post('caveatcontroller/section','CaveatController@section')->name('caveatcontroller.section')->middleware('CheckAuthorization:Caveat');
//Route::post('caveatcontroller/advsearch','CaveatController@advsearch')->name('caveatcontroller.advsearch')->middleware('CheckAuthorization:Caveat');
//Route::post('caveatcontroller/districtRespondant','CaveatController@districtRespondant')->name('caveatcontroller.districtRespondant')->middleware('CheckAuthorization:Caveat');
//Route::post('caveatcontroller/fetchdeptres','CaveatController@fetchdeptres')->name('caveatcontroller.fetchdeptres')->middleware('CheckAuthorization:Caveat');

Route::post('caveatsearch','CaveatController@search')->middleware('CheckAuthorization:Caveat');
Route::post('searchcaveat','CaveatSearchController@SearchBasedOnID')->middleware('CheckAuthorization:Caveat');
Route::post('linkcaveat','CaveatSearchController@LinkBasedOnID')->middleware('CheckAuthorization:Caveat');
Route::post('getcaveatstartno','CaveatController@getcaveatstartno')->middleware('CheckAuthorization:Caveat');
Route::post('caveatstore', 'CaveatController@store')->middleware('CheckAuthorization:Caveat');
Route::post('caveatapplicant','CaveatController@Applicantstore')->middleware('CheckAuthorization:Caveat');
Route::post('caveatrespondant','CaveatController@Respondantstore')->middleware('CheckAuthorization:Caveat');
Route::post('caveateesearch','CaveatController@ressearch')->middleware('CheckAuthorization:Caveat');
Route::post('getCaveatDetails','CaveatController@getCaveatBasedOnID')->middleware('CheckAuthorization:Caveat');
Route::post('getCaveatApplicantData', 'CaveatController@ApplicantSerialDetails')->middleware('CheckAuthorization:Caveat');
Route::post('getCaveatRespodantData', 'CaveatController@RespondantSerialDetails')->middleware('CheckAuthorization:Caveat');
Route::post('/advRegNoApp', 'CaveatController@getAdvRegNoApp')->middleware('CheckAuthorization:Caveat');

Route::get('/caveatsearch','CaveatSearchController@index')->middleware('CheckAuthorization:Caveat');
Route::post('/validateApplication','CaveatSearchController@validateApplication')->middleware('CheckAuthorization:Caveat');
Route::post('getSearchDetails','CaveatSearchController@SearchDetails')->middleware('CheckAuthorization:Caveat');
Route::post('getSearchDetailsNull','CaveatSearchController@SearchDetailsNull')->middleware('CheckAuthorization:Caveat');

//caveat

//Case Follow Up
Route::get('/uploadorder', 'Application\OrderUpload@uploadorder')->name('uploadorder')->middleware('CheckAuthorization:Case Follow Up');
Route::post('/saveorder','Application\OrderUpload@saveorder')->name('saveorder')->middleware('CheckAuthorization:Case Follow Up');
Route::post('/getApplicationDetails1', 'Application\OrderUpload@applicationDetails');

Route::get('/showVerifyOrder', 'Application\OrderUpload@showVerifyOrder')->name('showVerifyOrder')->middleware('CheckAuthorization:Case Follow Up');
Route::post('/getOrderDetails', 'Application\OrderUpload@getOrderDetails')->middleware('CheckAuthorization:Case Follow Up');
Route::post('/verifyOrder','Application\OrderUpload@verifyOrder')->name('verifyOrder')->middleware('CheckAuthorization:Case Follow Up');

Route::post('/DownloadOrder','Application\OrderUpload@DownloadOrder')->name('DownloadOrder')->middleware('CheckAuthorization:Case Follow Up');


Route::get('/officenote', 'CaseFollowupController@officenote')->name('officenote')->middleware('CheckAuthorization:Case Follow Up');
Route::post('getofficenoteDetails','CaseFollowupController@getofficenoteDetails')->middleware('CheckAuthorization:Case Follow Up');
Route::get('getofficenoteDetailsforEdit','CaseFollowupController@getofficenoteDetailsforEdit')->middleware('CheckAuthorization:Case Follow Up');
Route::post('/saveofficenote','CaseFollowupController@saveofficenote')->name('saveofficenote')->middleware('CheckAuthorization:Case Follow Up');
Route::post('generateordersheet', ['uses' => 'CaseFollowupController@generateordersheet',
            'as' => 'generateordersheet'
        ])->middleware('CheckAuthorization:Case Follow Up');


Route::get('/ordergeneration', 'CaseFollowupController@viewordergenerationform')->name('ordergeneration')->middleware('CheckAuthorization:Case Follow Up');
Route::post('ordergenerate', ['uses' => 'CaseFollowupController@ordergenerate',
            'as' => 'ordergenerate'
        ])->middleware('CheckAuthorization:Case Follow Up');
Route::get('/generatenotice', 'CaseFollowupController@viewgeneratenotice')->name('generatenotice')->middleware('CheckAuthorization:Case Follow Up');
Route::post('/generatenoticedocument', ['uses' => 'CaseFollowupController@generatenoticedocument',
            'as' => 'generatenoticedocument'
        ])->middleware('CheckAuthorization:Case Follow Up');
Route::post('/getHearingDetailsByApplication','CaseFollowupController@getHearingDetailsByApplication')->middleware('CheckAuthorization:Case Follow Up');

Route::get('/bulkdailyorder','bulkordercontroller@courtproceedingsentered');
Route::post('datacourtproceedingsentered1', ['uses' => 'bulkordercontroller@courtproceedingsenteredfunction',  'as' => 'datacourtproceedingsentered1'  ]);
Route::get('/findJudgeWithBenchCode1/{hearingdate}','bulkordercontroller@findJudgeWithBenchCode');

Route::get('/findJudgeWithBenchCode2','Application\OrderUpload@findJudgeWithBenchCode');

Route::get('/getHearingDetailsSelectedCheckbox','bulkordercontroller@getHearingDetailsSelectedCheckbox');

Route::get('generateordersheet1', ['uses' => 'bulkordercontroller@generateordersheet1',
            'as' => 'generateordersheet1']);
Route::get('assignuser', ['uses' => 'bulkordercontroller@assignuser',
										            'as' => 'assignuser'
										        ]);

Route::post('/getapphearing_bulkdailyorder', 'bulkordercontroller@getapphearing')->name('getapphearing');
Route::post('/saveProceeding', 'bulkordercontroller@saveProceeding')->name('saveProceeding');

Route::get('/cancelcourtdirection','bulkordercontroller@cancelcourtdirection');

Route::post('datacourtproceedingsentered2', ['uses' => 'bulkordercontroller@courtproceedingsenteredrollbackfunction',  'as' => 'datacourtproceedingsentered2'  ]);

Route::get('cancelcourdirectionrollback', ['uses' => 'bulkordercontroller@cancelcourdirectionrollback',
            'as' => 'cancelcourdirectionrollback']);

//Case Follow Up

//Certified Application


Route::get('previousfacesheetoffline','CCAControllerbyemail@previousfacesheetoffline')->name('previousfacesheetoffline')->middleware('CheckAuthorization:CCA');
Route::post('/previousfacesheetdownload','CCAControllerbyemail@previousfacesheetdownload')->name('previousfacesheetdownload')->middleware('CheckAuthorization:CCA');


Route::get('/facesheetoffline','CCAControllerbyemail@facesheetlist')->name('facesheetlist')->middleware('CheckAuthorization:CCA');
Route::post('/facesheetdownloadoffline','CCAControllerbyemail@facesheetdownload_1')->name('facesheetdownloadoffline')->middleware('CheckAuthorization:CCA');
  
Route::get('/ccaApplication','CCAController@index')->middleware('CheckAuthorization:CCA');
Route::post('/getApplicationCA','CCAController@getApplicationCA')->middleware('CheckAuthorization:CCA');

Route::post('/getAllOrderByApplNoCCA','Application\OrderUpload@getAllOrderByApplNoCCA')->name('getAllOrderByApplNoCCA');
Route::get('/DownloadOrder_bydate','Application\OrderUpload@DownloadOrder_bydate')->name('DownloadOrder_bydate');



Route::post('/getCCApplicationDetails','CCAController@getCCApplicationDetails');
Route::post('/getCADistrict', 'CCAController@getCADistrict')->middleware('CheckAuthorization:CCA');
Route::post('/saveCCApplication','CCAController@saveCCApplication')->name('saveCCApplication')->middleware('CheckAuthorization:CCA');

Route::get('/ccabyemail','CCAControllerbyemail@index')->name('ccabyemail')->middleware('CheckAuthorization:CCA');

//==================QR code webphp route ==========================//
Route::get('qrcode', function () {
     return QrCode::size(300)->generate('A basic example of QR code!');
 });
//============email send after dsc facesheet sign and judgment============/
Route::get('/sendattachmentemail_dscsigner','MailController@sendattachmentemail_dscsigner')->name('ccabyemail')->middleware('CheckAuthorization:CCA');

Route::post('/ccemail','CCAControllerbyemail@attachment_email')->name('ccemail')->middleware('CheckAuthorization:CCA');
//============email send after dsc facesheet sign and judgment============/


/*Route::post('sendattachmentemail_dscsigner', ['uses' => 'MailController@sendattachmentemail_dscsigner','as' => 'sendattachmentemail_dscsigner'])->middleware('role:copying_four');
*/
//===========================QR code webphp route ==========================//

Route::get('/facesheet','CCAControllerbyemail@facesheet')->name('facesheet')->middleware('CheckAuthorization:CCA');
Route::post('/facesheetsave','CCAControllerbyemail@facesheetsave')->name('facesheetsave')->middleware('CheckAuthorization:CCA');
Route::post('/facesheetdownload','CCAControllerbyemail@facesheetdownload')->name('facesheetdownload')->middleware('CheckAuthorization:CCA');


//============cca application code=========================================
Route::post('/getCCApplicationDetails','CCAController@getCCApplicationDetails');
// ========== code cca application details ==========================

Route::get('/deficitEntry','CCAController@deficitEntry')->middleware('CheckAuthorization:CCA');
Route::post('/saveCCADeficitPayment','CCAController@saveCCADeficitPayment')->name('saveCCADeficitPayment')->middleware('CheckAuthorization:CCA');

Route::get('/markccaready','CCAController@markccaready')->middleware('CheckAuthorization:CCA');
Route::post('/getCCAApplicationsByApplId','CCAController@getCCAApplicationsByApplId')->middleware('CheckAuthorization:CCA');
Route::post('/updateCCAStatus','CCAController@updateCCAStatus')->name('updateCCAStatus')->middleware('CheckAuthorization:CCA');

Route::get('/ccaDelivery','CCAController@ccaDelivery')->middleware('CheckAuthorization:CCA');
Route::post('/updateCCADeliveryStatus','CCAController@updateCCADeliveryStatus')->name('updateCCADeliveryStatus')->middleware('CheckAuthorization:CCA');
//Certified Application


//Records
Route::get('/applicationreceived','RecordsController@index')->middleware('CheckAuthorization:Records');
Route::post('/getApplicationSummaryDtls','RecordsController@getApplicationSummaryDtls')->middleware('CheckAuthorization:Records');

Route::post('/applicationreceived1','Reports\Application\ApplicationReportController@applicationreceived1')->name('applicationreceived1');

Route::post('/saveRecordApplication','RecordsController@saveRecordApplication')->name('saveRecordApplication')->middleware('CheckAuthorization:Records');
Route::post('/getRecordApplicationDetails','RecordsController@getRecordApplicationDetails')->middleware('CheckAuthorization:Records');
Route::post('/getRecordDocumentDetails','RecordsController@getRecordDocumentDetails')->middleware('CheckAuthorization:Records');

Route::get('/requestrecords','RecordsController@requestrecords')->middleware('CheckAuthorization:Records');
Route::post('/saveRecordRequest','RecordsController@saveRecordRequest')->name('saveRecordRequest')->middleware('CheckAuthorization:Records');

Route::post('/getUsersDtlsBySection', 'RecordsController@getUsersDtlsBySection')->middleware('CheckAuthorization:Records');

Route::get('/receiverecords','RecordsController@receiverecords')->middleware('CheckAuthorization:Records');
Route::post('/getRecordsPendingForReceiving','RecordsController@getRecordsPendingForReceiving')->middleware('CheckAuthorization:Records');
Route::post('/saveReceivedRecords','RecordsController@saveReceivedRecords')->name('saveReceivedRecords')->middleware('CheckAuthorization:Records');

//Records

Route::get('/furtherdiary','Reports\Causelist\CauselistReportController@furtherdiary');
Route::post('datafurtherdiary', ['uses' => 'Reports\Causelist\CauselistReportController@furtherdiaryfunction',  'as' => 'datafurtherdiary'  ]);

//Transfer Application
Route::get('applicationtransfer', 'applicationtransfercontroller@applicationtransfer');
Route::GET('/getAppDetails/{applicationid}', 'applicationtransfercontroller@getAppDetails');
Route::post('/applicationtransferSave', [ 'as' => 'applicationtransferSave', 'uses' => 'applicationtransfercontroller@applicationtransferSave']);
Route::get('/findtoEstablishment','applicationtransfercontroller@findtoEstablishment');
//Transfer Application

//Reports
//Application
Route::get('/applicationreceivedreport','Reports\Application\ApplicationReportController@ApplicationReceived');
Route::post('data', ['uses' => 'Reports\Application\ApplicationReportController@ApplicationReceivedfunction',  'as' => 'data'  ]);

Route::post('/applicationreceived1','Reports\Application\ApplicationReportController@applicationreceived1')->name('applicationreceived1');


Route::get('/applicationwithfeesreport','Reports\Application\ApplicationReportController@Applicationwithfees');
Route::post('datafees', ['uses' => 'Reports\Application\ApplicationReportController@Applicationwithfeesfunction',  'as' => 'datafees'  ]);

Route::get('/receiptissuedandnotrealized','Reports\Application\ApplicationReportController@ReceiptIssuedandnotRealized');
Route::post('receiptissuednotrealized', ['uses' => 'Reports\Application\ApplicationReportController@ReceiptIssuedandnotRealizedfunction',  'as' => 'receiptissuednotrealized'  ]);

Route::get('/detailedentryreport','Reports\Application\ApplicationReportController@detailedentryreport');

Route::get('/listofdocumentsreceived','Reports\Application\ApplicationReportController@listofdocumentsreceived');
Route::post('datadocumentsreceived', ['uses' => 'Reports\Application\ApplicationReportController@listofdocumentsreceivedfunction',  'as' => 'datadocumentsreceived'  ]);



Route::get('/departmentapplicant','Reports\Application\ApplicationReportController@departmentapplicant');

Route::post('datadepartmentapplicant', ['uses' => 'Reports\Application\ApplicationReportController@departmentapplicantfunction',  'as' => 'datadepartmentapplicant'  ]);

Route::get('/departmentrespondent','Reports\Application\ApplicationReportController@departmentrespondent');

Route::post('datadepartmentrespondent', ['uses' => 'Reports\Application\ApplicationReportController@departmentrespondentfunction',  'as' => 'datadepartmentrespondent'  ]);

Route::get('/findDepWithDeptype/{deptypecode}','Reports\Application\ApplicationReportController@findDepWithDeptype');

Route::get('/pendingapplication','Reports\Application\ApplicationReportController@pendingapplication');
Route::post('datapendingapplication', ['uses' => 'Reports\Application\ApplicationReportController@pendingapplicationfunction',  'as' => 'datapendingapplication'  ]);
Route::post('/pendingapplication1', 'Reports\Application\ApplicationReportController@pendingapplication1')->name('pendingapplication1');
Route::get('/pendingapplicationReport2', 'Reports\Application\ApplicationReportController@pendingapplicationReport2')->name('pendingapplicationReport2');



//Scrutiny Reports
Route::get('/scrutinizedapplication','Reports\Scrutiny\ScrutinyReportController@scrutinizedapplication');
Route::post('datascrutinized', ['uses' => 'Reports\Scrutiny\ScrutinyReportController@scrutinizedapplicationfunction',  'as' => 'datascrutinized'  ]);

Route::get('/objectedapplication','Reports\Scrutiny\ScrutinyReportController@objectedapplication');
Route::post('dataobjected', ['uses' => 'Reports\Scrutiny\ScrutinyReportController@objectedapplicationfunction',  'as' => 'dataobjected'  ]);

Route::get('/applicationnotcomplied','Reports\Scrutiny\ScrutinyReportController@applicationnotcomplied');
Route::post('datacomplied', ['uses' => 'Reports\Scrutiny\ScrutinyReportController@applicationnotcompliedfunction',  'as' => 'datacomplied'  ]);

Route::get('/objectionraised','Reports\Scrutiny\ScrutinyReportController@objectionraised');
Route::post('dataobjectionraised', ['uses' => 'Reports\Scrutiny\ScrutinyReportController@objectionraisedfunction',  'as' => 'dataobjectionraised'  ]);

Route::get('/scrutinypendingapplication','Reports\Scrutiny\ScrutinyReportController@scrutinypendingapplication');
Route::post('datascrutinypendingapplication', ['uses' => 'Reports\Scrutiny\ScrutinyReportController@scrutinypendingapplicationfunction',  'as' => 'datascrutinypendingapplication'  ]);


Route::get('/scrutinizedandnotpostedtoch','Reports\Scrutiny\ScrutinyReportController@scrutinizedandnotpostedtoch');




//Judgement Module
Route::get('/judgmentnotuploaded','Reports\Judgement\JudgementReportController@judgmentnotuploaded');

Route::get('/judgmentuploaded','Reports\Judgement\JudgementReportController@judgmentuploaded');
Route::post('datajudgmentuploaded', ['uses' => 'Reports\Judgement\JudgementReportController@judgmentuploadedfunction',  'as' => 'datajudgmentuploaded'  ]);

Route::get('/disposedapplication','Reports\Judgement\JudgementReportController@disposedapplication');
Route::post('datadisposedapplication', ['uses' => 'Reports\Judgement\JudgementReportController@disposedapplicationfunction',  'as' => 'datadisposedapplication'  ]);
Route::post('/disposedapplication1','Reports\Judgement\JudgementReportController@disposedapplication1');
Route::get('/legacydisposal','Reports\Judgement\JudgementReportController@legacydisposal');
Route::post('datalegacydisposal', ['uses' => 'Reports\Judgement\JudgementReportController@legacydisposalfunction',  'as' => 'datalegacydisposal'  ]);


Route::get('/reservedfororder','Reports\Judgement\JudgementReportController@reservedfororder');
Route::post('datareservedfororder', ['uses' => 'Reports\Judgement\JudgementReportController@reservedfororderfunction',  'as' => 'datareservedfororder'  ]);


//Caveat Reports
Route::get('/caveatfiled','Reports\Caveat\CaveatReportController@caveatfiled');
Route::post('datacaveatfiled', ['uses' => 'Reports\Caveat\CaveatReportController@caveatfiledfunction',  'as' => 'datacaveatfiled'  ]);

Route::get('/caveatmatched','Reports\Caveat\CaveatReportController@caveatmatched');
Route::post('datacaveatmatched', ['uses' => 'Reports\Caveat\CaveatReportController@caveatmatchedfunction',  'as' => 'datacaveatmatched'  ]);

Route::get('/caveatfiledagainstdepartment','Reports\Caveat\CaveatReportController@caveatfiledagainstdepartment');
Route::post('datacaveatfiledagainstdepartment', ['uses' => 'Reports\Caveat\CaveatReportController@caveatfiledagainstdepartmentfunction',  'as' => 'datacaveatfiledagainstdepartment'  ]);

Route::get('/dscjudgements', 'Application\DscJudgementController@uploadjudgements')->name('uploadjudgements')->middleware('CheckAuthorization:Judgments');
Route::post('/dscsavejudgements','Application\DscJudgementController@savejudgements')->name('dscsavejudgements')->middleware('CheckAuthorization:Judgments');
Route::post('/getdscvalidate','Application\DscJudgementController@getdscvalidate')->name('getdscvalidate');
Route::post('/getWatermark','Application\DscJudgementController@getWatermark')->name('getWatermark');
Route::post('/getdisposedate','Application\DscJudgementController@getdisposedate')->name('getdisposedate');
Route::post('/getJudgementfiledetails','Application\DscJudgementController@getJudgementfiledetails')->name('getJudgementfiledetails');

Route::get('/watermark', [
        'uses' => 'waterController@watermark',
        'as' => 'watermark'
    ]);

//Route::post('printW','waterController@PlaceWatermark');

Route::post('/printW', [
        'uses' => 'waterController@PlaceWatermark',
        'as' => 'printW'
    ]);

//CCA Reports

Route::get('/listofccareceived','Reports\CCA\CCAReportController@listofccareceived');
Route::post('datalistofccareceived', ['uses' => 'Reports\CCA\CCAReportController@listofccareceivedfunction',  'as' => 'datalistofccareceived'  ]);

Route::get('/applicationwihfee','Reports\CCA\CCAReportController@applicationwihfee');
Route::post('dataapplicationwihfee', ['uses' => 'Reports\CCA\CCAReportController@applicationwihfeefunction',  'as' => 'dataapplicationwihfee'  ]);

Route::get('/listofccastatus','Reports\CCA\CCAReportController@listofccastatus');
Route::post('datalistofccastatus', ['uses' => 'Reports\CCA\CCAReportController@listofccastatusfunction',  'as' => 'datalistofccastatus'  ]);

Route::get('/ccadelivery','Reports\CCA\CCAReportController@ccadelivery');
Route::post('dataccadelivery', ['uses' => 'Reports\CCA\CCAReportController@ccadeliveryfunction',  'as' => 'dataccadelivery'  ]);

Route::get('/ccadeficit','Reports\CCA\CCAReportController@ccadeficit');
Route::post('dataccadeficit', ['uses' => 'Reports\CCA\CCAReportController@ccadeficitfunction',  'as' => 'dataccadeficit'  ]);

Route::get('/ccapaid','Reports\CCA\CCAReportController@ccapaid');
Route::post('dataccapaid', ['uses' => 'Reports\CCA\CCAReportController@ccapaidfunction',  'as' => 'dataccapaid'  ]);

Route::get('/ccaonlinepaid','Reports\CCA\CCAReportonlineController@ccaonlinepaid');
Route::post('dataonlineccapaid', ['uses' => 'Reports\CCA\CCAReportonlineController@onlineccapaidfunction',  'as' => 'dataonlineccapaid'  ]);
//CCA Reports


//Court Hall Reports
Route::get('/courtproceedingsnotentered','Reports\Courthall\courthallReportcontroller@courtproceedingsnotentered');
Route::post('datacourtproceedingsnotentered', ['uses' => 'Reports\Courthall\courthallReportcontroller@courtproceedingsnotenteredfunction',  'as' => 'datacourtproceedingsnotentered'  ]);

Route::get('/courtproceedingsentered','Reports\Courthall\courthallReportcontroller@courtproceedingsentered');
Route::post('datacourtproceedingsentered', ['uses' => 'Reports\Courthall\courthallReportcontroller@courtproceedingsenteredfunction',  'as' => 'datacourtproceedingsentered'  ]);
Route::get('/findJudgeWithBenchCode/{hearingdate}','Reports\Courthall\courthallReportcontroller@findJudgeWithBenchCode');


//Reports

Route::get('ordersheet', [
            'uses' => 'OrderController@ordersheet',
            'as' => 'ordersheet'
        ]);

//SMS REPORTS
Route::get('/smssent','Reports\SMS\smsReportController@smssent')->middleware('CheckAuthorization:SMS');
Route::post('datasmssent', ['uses' => 'Reports\SMS\smsReportController@smssentfunction',  'as' => 'datasmssent'  ])->middleware('CheckAuthorization:SMS');



Route::get('/SmsSendlog', 'Application\Smslogcontroller@SmsSendlog')->name('SmsSendlog')->middleware('CheckAuthorization:SMS');
Route::get('/smsInsert', 'Application\Smslogcontroller@smsInsert')->name('smsInsert')->middleware('CheckAuthorization:SMS');




 Route::get('/dailystatusreport','Reports\Judgement\dailystatusReportController@dailystatusreport');


/*Route::post('ordergenerate1', [
            'uses' => 'OrderController@ordergenerate',
            'as' => 'ordergenerate'
        ]);*/

//Daily Status Report 

Route::get('/dailyreportview','DailyReportViewController@dailystatusreport');
Route::get('/findJudgeWithBenchCode1/{hearingdate}','DailyReportViewController@findJudgeWithBenchCode');
Route::post('/buttonsubmit','DailyReportViewController@buttonsubmit');




