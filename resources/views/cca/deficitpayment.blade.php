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
		
	
  <form role="form" id="deficitPaymentForm" method="POST" action="saveCCADeficitPayment" data-parsley-validate>
			  @csrf
			<div class="panel  panel-primary">
			  <div class="panel panel-heading">
				<h7 >Deficit Payment</h7>
			  </div>
			
			<div class="panel panel-body divstyle" >
				<div class="row">
					<div class="col-md-4">
					
					
					
					
						<div class="form-group">
						    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
							<label>Copy Application No<span class="mandatory">*</span></label>
							  <div class="input-group date">							
								   <input type="text" name="ccapplno" class="form-control pull-right number zero" id="ccapplno" value="" data-parsley-errors-container="#modlerror" data-parsley-required data-parsley-required-message='Enter CC Application No' maxlength='20'>
								
								<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="ccapplSearch">						 						 
								 <i class="fa fa-search"></i>
								</div>
								<span id="modlerror"></span>
							</div>	
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>CCA Application Date<span class="mandatory">*</span></label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="dateOfCA" class="form-control pull-right datepicker" id="dateOfCA"  value="" readonly>
							</div>
							
						</div>
					</div>
					<div class="col-md-4">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class=" ">
                     <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" readonly>
					
                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
				</div>
							
				<div class="row">
				<div class="col-md-4">
						<div class="form-group">
							<label>Deficit Amount</label>
							<input type="text" name="defi_amt" class="form-control pull-right" id="defi_amt" readonly value="" data-parsley-errors-container="#modlerror">
						</div>	
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Receipt No<span class="mandatory">*</span></label>
							<div class="input-group">
							 <input class="form-control number zero" name="receiptNo"  id="receiptNo"
							 data-parsley-pattern="/^[0-9\/]+$/" 
							 data-parsley-required data-parsley-pattern-message="Invalid Receipt No" value=""  data-parsley-required-message="Enter Receipt No"
							data-parsley-minlength='1'  data-parsley-maxlength='15' data-parsley-maxlength-message="Receipt No. Should have Maximum 15 digit" data-parsley-trigger='keypress'>
							<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="SearchReceipt">
								<i class="fa fa-search"></i>
							</div>                    
							</div>
							<span id="modlerror"></span>
						</div>
					</div>
					<div class="col-md-4">
					  <div class="form-group">
						<label>Receipt Date<span class="mandatory">*</span></label>
						<div class="input-group date">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
						  <input type="text" name="receiptDate"  readonly class="form-control pull-right datepicker" id="receiptDate"  data-parsley-required  data-parsley-required-message="Enter Receipt Date"
						 data-parsley-errors-container="#error1" data-parsley-trigger='keypress'>
						</div>
						<span id="error1"></span>
					</div>
					</div>
					
				
			  </div>
			  <div class="row">
			  	<div class="col-md-4">
					 <div class="form-group">
					<label>Applicant Name in Receipt<span class="mandatory">*</span></label>
					<div class="">					 
					 <input type="text" class="form-control" disabled readonly id="applName"  name="applName" data-parsley-required-message="Enter Applicant Name."
                  data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error" data-parsley-trigger='keypress'>
                </div>
                <span id="error"></span>
				</div>
			  </div>
			  <div class="col-md-4">
               <div class="form-group">
                <label>Amount<span class="mandatory">*</span></label>
                <input class="form-control number zero"   readonly name="recpAmount"  type="number" id="recpAmount"	min="1" max="999999"
 data-parsley-minlength="1"  data-parsley-minlength-message="Receipt Amount Should have Minimum 1 Digits" data-parsley-maxlength='6' data-parsley-maxlength-message="Receipt Amount Accepts only 6 digits" data-parsley-trigger='keypress'>
              </div>
            </div>
			</div>
			 <div class="row">
			<div class="col-md-4">
						<div class="form-group">
							<label>Certified copies to be delivered on<span class="mandatory">*</span></label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="ccdeliverydate" class="form-control pull-right datepicker" id="ccdeliverydate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Delivery Allows only digits" value=""  data-parsley-required-message="CCA Delivery Date"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
							</div>
							<span id="error3"></span>
						</div>
					</div>
				</div>
				<div class="row"  style="float: center;" id="add_apl_div">
				<div class="col-md-12 text-center">
                <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
                  <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
				</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>	
	</section>	
	 <script src="js/jquery.min.js"></script>
	 <script src="js/cca/deficitpayment.js"></script>
	
@endsection
