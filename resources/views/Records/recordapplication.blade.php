@extends('layout.mainlayout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <style type="text/css">
  .pager{
  background-color: #337ab7;
  color: #fff;
  }
  .do-scroll{
  width: 100%;
  height: 100px;
  overflow-y: scroll;
  }
  .btnSearch,
  .btnClear{
  display: inline-block;
  vertical-align: top;
  }
  .divstyle
  {
  padding-top: 0px;
  padding-bottom: 0px;
  }
  <style>
  .text{
  white-space: pre-wrap;
  }
  </style>
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')
	<section class="content">
	<form role="form" id="recordApplicationForm" method="POST" action="saveRecordApplication" data-parsley-validate>
			  @csrf
			<div class="panel  panel-primary">
			  <div class="panel panel-heading">
				<h4 >Application received</h4>
			  </div>
			
			<div class="panel panel-body divstyle" >
				
			<div class="row">
              <div class="col-md-4">
                <div class="form-group">
				   <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                  <label>Type of Application<span class="mandatory">*</span></label>
                  <select class="form-control" name="applTypeName" id="applTypeName" >
				  <option value="" class="form-control">Select Application Type</option>
					@foreach($applType as $appltype)
						<option value="{{$appltype->appltypecode.'-'.$appltype->appltypeshort}}">{{$appltype->appltypedesc.'-'.$appltype->appltypeshort}}</option>
					@endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class="input-group date">
                     <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" data-parsley-errors-container="#modlerror" data-parsley-required data-parsley-required-message='Enter Application No' data-parsley-pattern="/[0-9\/]+$/" data-parsley-pattern-message='Invalid Application No' data-parsley-trigger="keypress" maxlength='12'>
					<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                     <i class="fa fa-search"></i>
                    </div>
                    
                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
			   <div class="col-md-4"> <div class="form-group">
                <label>Registration Date<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="applnRegDate" class="form-control pull-right " id="applnRegDate"  value=""  readonly>
                </div>
              </div>
            </div>
			</div>
			<div class="row">
			   <div class="col-md-4">
              <div class="form-group">
                <label>Application Category<span class="mandatory">*</span></label>
                <select class="form-control" name="applCatName" id="applCatName" disabled >
                  <option value="">Select Applcation Category</option>
                  @foreach($applCategory as $applCat)
                  <option value="{{$applCat->applcatcode}}">{{$applCat->applcatname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
			
			<div class="col-md-4">
						<div class="form-group">
							<label>Subject<span class="mandatory">*</span></label>
							<textarea class="form-control" name='applnSubject' id="applnSubject"  readonly data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject'></textarea>
									
						</div>
					</div>
                </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
						<label>Name of the Applicant</label>
						<input type="textarea" name="app_name" class="form-control pull-right" id="app_name" readonly data-parsley-errors-container="#modlerror">
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
						<label>Name of the Respondent</label>
						<input type="text" name="res_name" class="form-control pull-right" id="res_name" readonly data-parsley-errors-container="#modlerror">
						</div>
					</div>
				</div>
				</br>
			
				<div class="row" id="myTableJudgementsdiv" style="display: none">
				  <table id="myTableJudgements" class="table table-bordered table-striped  table order-list" style="width:80%;" >
					<thead >
					  <tr style="background-color: #3c8dbc;color:#fff" >
						<td style="width:20%;" align="center">Judgement Date</td>
						<td style="width:20%;" align="center">Page count</td>
						<td style="width:20%;" align="center">View</td>
					  </tr>
					</thead>
					<tbody id="results8" >
					</tbody>
				  </table>
            </div>
		 </div>	
    <div class="panel  panel-primary">
          <div class="panel panel-heading">
            <h4>Record Room Checklist</h4>
          </div>

    <div class="panel panel-body">
        <div class="row">
         
	<div class="col-md-4">
    <div class="form-group">
      <label>Part<span class="mandatory">*</span></label>
      <select class="form-control entrydiv" name="part"  id="part" data-parsley-required data-parsley-required-message="Select Part">
        <option value=''>Select Part</option>
        <option value="A">Part A</option>
		<option value="B">Part B</option>
       </select>

    </div>
  </div>
      <div class="col-md-8">
        <div class="form-group">
          <label>Particulars of documents<span class="mandatory">*</span></label>
          <textarea  name="documentname"  id="documentname" class="form-control entrydiv" data-parsley-required data-parsley-required-message="Enter Document" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Part Of Document Accepts Only Alpanumeric" data-parsley-trigger='keypress'></textarea>
        </div>
      </div>
	  </div>
	 <div class="row">
	  <div class="col-md-4">
        <div class="form-group">
          <label>Start Page<span class="mandatory">*</span></label>
          <input type="number" name="startPage" id="startPage"  class="form-control number entrydiv" data-parsley-required data-parsley-required-message="Enter Start Page No." data-parsley-trigger='keypress'  />
        </div>
      </div>
	  <div class="col-md-4">
        <div class="form-group">
          <label>End Page<span class="mandatory">*</span></label>
          <input type="number" id='endPage' name="endPage" id="endPage"  class="form-control number entrydiv" data-parsley-required data-parsley-required-message="Enter End Page No." data-parsley-trigger='keypress'  />
        </div>
      </div>
	  <div class="col-md-4">
	   <div class="form-group">
      <label>Received on<span class="mandatory">*</span></label>
      <div class="input-group date">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        <input type="text" name="receiveddate" class="form-control pull-right datepicker entrydiv" id="receiveddate"  value="" data-parsley-required data-parsley-required-message="Enter Received on" >
      </div>
      <!-- <span id="error7"></span> -->
  </div>
</div>
</div>

<div class="row">
 <div class="col-md-4">
    <div class="form-group">
      <label>Stored at<span class="mandatory">*</span></label>
      <select class="form-control entrydiv" name="storedat"  id="storedat" data-parsley-required data-parsley-required-message="Select Stored at">
        <option value=''>Select Room No</option>
		   @foreach($roomno as $roomno)
        <option value="{{$roomno->roomno}}">{{$roomno->roomname}}</option>
        @endforeach
      </select>

    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Rack Number<span class="mandatory">*</span></label>
      <select class="form-control entrydiv" name="rackno"  id="rackno" data-parsley-required data-parsley-required-message="Select Rack Number">
        <option value=''>Select Rack Number</option>
        @foreach($rackno as $rackno)
        <option value="{{$rackno->rackno}}">{{$rackno->rackname}}</option>
        @endforeach
		
      </select>

    </div>
  </div>
 <div class="col-md-4">
    <div class="form-group">
      <label>Bundle Number<span class="mandatory">*</span></label>
        <input type="text" name="bundleno" class="form-control pull-right number " id="bundleno" value=""  data-parsley-required data-parsley-error-message='Enter Bundle No' data-parsley-pattern='^[A-za-z0-9/]+$' data-parsley-pattern-message='Invalid Bundle No' maxlength='20'>

    </div>
  </div>


</div>




<div class="row"  style="float: right;" id="add_apl_div">
<div class="col-sm-12 text-center">
<input type="button" id="saveRecordApplDocuments" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
 <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
 <input type="hidden" name="docid" id="docid" value="">
<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
</div>
</div>
<br><br>
</div>
<div class="panel  panel-primary">
          <div class="panel panel-heading">
            <h4>Record Room Checklist – Part A</h4>
          </div>

   <div class="panel panel-body">
<div class="row" id="parta_div">

<table id="parta" class="table table-bordered table-striped" >
<thead style="background-color: #3c8dbc;color:#fff">
  <tr>
    <td  class="col-md-4">Particulars of Documents</td>
    <td  class="col-md-1">Start Page</td>
    <td  class="col-md-1">End Page</td>
	<td class="col-md-2">Received On </td>
    <td class="col-md-1">Stored at</td>
    <td class="col-md-1">Rack Number</td>
	<td class="col-md-2">Bundle Number</td>

  </tr>
</thead>
<tbody id="results">
  <tr>
  </tr>
  <tr>
  </tr>
</tbody>
</table>
</div>
</div>
</div>
	
<div class="panel  panel-primary">
          <div class="panel panel-heading">
            <h4 >Record Room Checklist – Part B</h4>
          </div>

   <div class="panel panel-body">
<div class="row" id="partb_div">

<table id="partb" class="table table-bordered table-striped " >
<thead style="background-color: #3c8dbc;color:#fff">
  <tr>
    <td  class="col-md-4">Particulars of Documents</td>
    <td  class="col-md-1">Start Page</td>
    <td  class="col-md-1">End Page</td>
	<td class="col-md-2">Received On </td>
    <td class="col-md-1">Stored at</td>
    <td class="col-md-1">Rack Number</td>
	<td class="col-md-2">Bundle Number</td>
  </tr>
</thead>
<tbody id="results">
  <tr>
  </tr>
  <tr>
  </tr>
</tbody>
</table>
</div>
</div>
</div>	
			
		
			</div>
		</div>
	</form>	
	</section>	
	 <script src="js/jquery.min.js"></script>
	 <script src="js/records/records.js"></script>
	
@endsection