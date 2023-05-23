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
		
	
    <form role="form" id="markCCApplicationForm" method="POST" action="updateCCAStatus" data-parsley-validate>
			  @csrf
			<div class="panel  panel-primary">
			  <div class="panel panel-heading">
				<h7 >Mark Ready / Rejected CCA Application</h7>
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
                     <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" data-parsley-errors-container="#modlerror" data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/]+$' data-parsley-pattern-message='Invalid Application No' maxlength='20'>
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
				
			
				
				
				
				
				
				
				
				
				</div>
			
			
		 <div class="panel  panel-primary">
          <div class="panel panel-heading">
            <h7 >CCA Applications</h7>
          </div>

          <div class="panel panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>CCA Application No<span class="mandatory">*</span></label>

                  <input type="text" name="ccaapplicationno" class="form-control pull-right number zero" id="ccaapplicationno" readonly   data-parsley-required data-parsley-error-message='Enter Application No' maxlength='20'>
                </div>
              </div>
                <div class="col-md-4">
				<div class="form-group">
				  <label>CA Application Status<span class="mandatory">*</span></label>
				  <select class="form-control" name="caapplStatus"  id="caapplStatus" data-parsley-required data-parsley-required-message="Select CA ApplicationStatus">
					<option value=''>Select Application Status</option>
					@foreach($castatus as $castatus)
					 <?php if($castatus->ccastatuscode=='3' || $castatus->ccastatuscode=='4')
					{?>
					<option value="{{$castatus->ccastatuscode}}">{{$castatus->ccastatusdesc}}</option>				
					<?php }?>
					@endforeach
				  </select>
				</div>
			  </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Date<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="readyrejectedDate" class="form-control pull-right datepicker" id="readyrejectedDate" value=""  data-parsley-trigger='keypress' data-parsley-errors-container="#error20" data-parsley-required-message="Enter Date" data-parsley-required>
                  </div>
                  <span id="error20"></span>
                </div>
              </div>
			  </div>
			   <div class="row">
              <div class="col-md-4 divrejectedreason">
                <div class="form-group">
                  <label>Rejected Reason</label>
                  <textarea  class="form-control" id="rejectedreason"name="rejectedreason" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remark" data-parsley-required-message="Enter Reason For Rejection"></textarea>
                </div>

              </div>
            </div>



            <div class="row"  style="float: right;" id="add_apl_div">
              <div class="col-sm-12 text-center">
                <input type="button" id="saveCCAStatus" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                <input type="button" class="btn btn-danger btn-md center-block btnClear" id="clearAdditional" Style="width: 100px;" value="Cancel">
              </div>
            </div><br><br>
            <div class="row">
              <table id="myTableCCA" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                <thead >
                  <tr style="background-color: #3c8dbc;color:#fff" >
                    <td>CCA Application No</td>
                    <td>CCA Application Date</td>
					<td>Requested Document Type</td>
                    <td>Total Amount</td>
				    <td>Receipt Amount</td>
					<td>Deficit Receipt Amount</td>
                  </tr>
                </thead>
                <tbody id="results8" >

                </tbody>
              </table>
            </div>



          </div>
        </div>
		</div>
	</form>	
	</section>	
	 <script src="js/jquery.min.js"></script>
	 <script src="js/cca/markccaready.js"></script>
	
@endsection