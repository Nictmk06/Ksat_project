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
	<form role="form" id="requestrecordApplicationForm" method="POST" action="saveRecordRequest"  data-parsley-validate>
			  @csrf
			<div class="panel  panel-primary">
			  <div class="panel panel-heading">
				<h4>Records request entry</h4>
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
			
			 <div class="row">
             
                <div class="col-md-4">
				<div class="form-group">
				  <label>Request received from (section)<span class="mandatory">*</span></label>
				  <select class="form-control" name="section"  id="section" data-parsley-required data-parsley-required-message="Select Section">
					<option value=''>Select Section</option>
					@foreach($userSection as $userSection)
					<option value="{{$userSection->userseccode}}">{{$userSection->usersecname}}</option>				
					@endforeach
				  </select>
				</div>
			  </div>
			  <div class="col-md-4 caapplDetails">
						<div class="form-group">
						  <label>Requested by<span class="mandatory">*</span></label>
							<select name="requestedBy" id="requestedBy" class="form-control">
								<option value="">Select Requested By</option>
							</select>
						</div>
					</div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Requested on<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="requestedDate" class="form-control pull-right datepicker" id="requestedDate" value=""  data-parsley-trigger='keypress' 
					   data-parsley-errors-container="#error20" data-parsley-required-message="Enter Date" data-parsley-required>
                  </div>
                  <span id="error20"></span>
                </div>
              </div>
			  </div>
			
			 <div class="row">
            
              
              <div class="col-md-4">
                <div class="form-group">
                  <label>Record Sent on<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="recordsentdate" class="form-control pull-right datepicker" id="recordsentdate" value=""  data-parsley-trigger='keypress' 
					   data-parsley-errors-container="#error21" data-parsley-required-message="Enter Date" data-parsley-required>
                  </div>
                  <span id="error21"></span>
                </div>
              </div>
			  </div>
			  
			  <div class="row"  style="float: right;" id="add_apl_div">
				<div class="col-md-12 text-center">
                <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
				<input type="hidden" name="userid" id="userid" value="">
				<input type="hidden" name="username1" id="username1" value="">
                  <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
				</div>
				</div>
		 </div>	
	</div>
		</div>
	</form>	
	</section>	
	 <script src="js/jquery.min.js"></script>
	 <script src="js/records/requestrecords.js"></script>
	
@endsection