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
		
	<div class="modal fade" id="editmodal">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='edit_appl-title'></h4>
          </div>
          <div class="modal-body">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row">
            
              <div class="col-md-6">
                <div class="form-group">
                  <label>Copy Application No<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" value="" data-parsley-errors-container="#modlerror12"  
					data-parsley-required-message='Enter Application No' data-parsley-pattern='^[\/\w]*$' data-parsley-pattern-message='Invalid Application No' maxlength='15' data-parsley-trigger="keypress" >
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="editSearch">
                      <i class="fa fa-search"></i>
                    </div>

                  </div>
                  <span id="modlerror12"></span>
                </div>
              </div>
            </div>

          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

		
		
		<ul class="nav nav-tabs" id="myTab">
			
				<li style="float:right;" id="editApplication"> <input type="button"  id="" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Edit"></li>
				</ul>
				
	
    <form role="form" id="ccApplicationForm" method="POST" action="saveCCApplication" data-parsley-validate>
			  @csrf
			<div class="panel  panel-primary">
			  <div class="panel panel-heading">
				<h7 >Copy Application</h7>
			  </div>
			
			<div class="panel panel-body divstyle" >
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="form-group">
								<label>Copy Application No<span class="mandatory">*</span></label>
								<input type="text" name="ccapplno" class="form-control pull-right" id="ccapplno" name="ccapplno" readonly value="" >
						</div>	
						</div>
					</div>
				</div>
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
                     <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" data-parsley-errors-container="#modlerror1" data-parsley-required data-parsley-required-message='Enter Application No' data-parsley-pattern='^[0-9\/]+$' data-parsley-pattern-message='Invalid Application No' maxlength='12' data-parsley-trigger="keypress" >
					<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <input name="estcode" type="hidden" id="estcode" value="{{ $estcode }}"/>
					  <input name="ccacharge" type="hidden" id="ccacharge" value="{{ $ccacharge }}"/>
					  <input name="ccaapplicationno" type="hidden" id="ccaapplicationno" value=""/>						 
					 <i class="fa fa-search"></i>
                    </div>
                    
                  </div>
                  <span id="modlerror1"></span>
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
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>CCA Application Date<span class="mandatory">*</span></label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="dateOfCA" class="form-control pull-right datepicker" id="dateOfCA"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="CCA Application Date"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
							</div>
							<span id="error3"></span>
						</div>
					</div>	
					<div class="col-md-4">
						<div class="form-group">
						 <label>Requested Document Type<span class="mandatory">*</span></label>
						 <select class="form-control dynamic" name="ccadoc_type" type="text" id="ccadoc_type" >
							<option value="" class="form-control">Select Document Type</option>
							@foreach($docType as $document)
								<option value="{{$document->ccadocumentcode}}">{{$document->ccadocumentname}}</option>
							@endforeach
						 </select>	
						</div>
					</div>
					
				</div>
				
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

            <div class="row" id="myOrderdiv" style="display: none">
				  <table id="myTableOrder" class="table table-bordered table-striped  table order-list" style="width:80%;" >
					<thead >
					  <tr style="background-color: #3c8dbc;color:#fff" >
						<td style="width:20%;" align="center">Order Date</td>
						<td style="width:20%;" align="center">Page count</td>
						<td style="width:20%;" align="center">View</td>
					  </tr>
					</thead>
					<tbody id="results8" >
					</tbody>
				  </table>
            </div>

            <div class="row" id="myorderdate" style="display: none">
					<div class="col-md-4">
						<div class="form-group">
							<label>Select Order Date</label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="dateOfOrdSel" class="form-control pull-right datepicker" id="dateOfOrdSel"  value="" data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Order Date"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
							</div>
							<span id="error4"></span>
						</div>
					</div>
				</div>
				
			<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Order Date</label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="dateOfOrd" class="form-control pull-right datepicker" id="dateOfOrd"  value="" data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Order Date"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
							</div>
							<span id="error4"></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Name of the Document</label>
							<input type="text" class="form-control" id="documentname" name="documentname"  data-parsley-required-message="Enter Document Name."
							data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Document Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Document Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Document Name Accepts only 100 characters" data-parsley-errors-container="#error9" data-parsley-trigger='keypress'>	
													
						</div>
					</div>
				</div>
				<div class="row">
					 
					<div class="col-md-4" id="advocate_div">
						<div class="form-group">
							<label>Is Advocate?<span class="mandatory">*</span></label><br>
							<label class="radio-inline">
							  <input type="radio" name="isAdvocate" id="isAdvocate" value="Y" checked>Yes
							</label>
							<label class="radio-inline">
							  <input type="radio" name="isAdvocate" id="isAdvocate" value="N" >No
							</label>
						</div>
						  <span id="error12"></span>
					</div>
				
					<div class="col-md-4 advDetails">
						<div class="form-group">
							<label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
								<div class="">
								<input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
								data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20"  data-parsley-errors-container='#errorAdv1'>
								<datalist id="browsers">
									<?php foreach($advocatedetails as $advocate){?>
									<option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
									<?php }?>
								</datalist>
						<!--	<div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="advocateAdd">
							<i class="fa fa-plus"></i>
							</div>-->
                  
							</div>
							<span id='errorAdv1'></span>
						</div>
              		</div>
					<div class="col-md-4 advDetails">
						<div class="form-group">
							<label>Advocate Name</label>
							<div class="">
							
							<input type="hidden" class="form-control" id="advTitle" name="advTitle" readonly="">
							<input type="text" class="form-control" id="advName" name="advName" readonly="">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 caapplDetails">
						<div class="form-group">
							<div class="form-group">
							<label>Name of the Applicant</label>
							 <input type="text" class="form-control" id="caapplicantname" name="caapplicantname" data-parsley-required data-parsley-required-message="Enter Applicant Name."
							data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Applicant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error9" data-parsley-trigger='keypress'>			  
							
							</div>
						</div>
					</div>
					<div class="col-md-4 caapplDetails">
						<label>Address</label>
						 <textarea class="form-control"  name="caaddress" type="text" id="caaddress" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/"  data-parsley-pattern-message="Invalid Address" data-parsley-minlength="3"  data-parsley-minlength-message="Address  Should have Minimum 3 Characters" data-parsley-maxlength='300' data-parsley-maxlength-message="Address Accepts only 300 characters" data-parsley-required data-parsley-required-message="Enter Address" data-parsley-trigger='keypress'></textarea>
													
					</div>
				</div>
				
				<div class="row">
				 <div class="col-md-4 caapplDetails">
                          <div class="form-group">
                            <label>Pincode</label>
                            <input class="form-control zero number" name="capincode" type="number" id="capincode" data-parsley-pattern="/^[1-9][0-9]{5}$/" data-parsley-pattern-message="Invalid Pincode" data-parsley-trigger='keypress'>
                          </div>
                        </div>
					<div class="col-md-4 caapplDetails">
						<div class="form-group">
							<label>District</label>
							<select name="distcode" id="distcode" data-dependent="CATaluk" class="form-control districtCA">
								<option value="">Select District</option>
									@foreach($dist_list as $dist)
									<option value="{{$dist->distcode}}">{{$dist->distname}}</option>
									@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4 caapplDetails">
						<div class="form-group">
							<label>Taluk</label>
							<select name="CATaluk" id="CATaluk" class="form-control">
								<option value="">Select Taluk</option>
							</select>
						</div>
					</div>
				
				</div>
				<div class="row">
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
						  <input type="text" name="receiptDate"  readonly class="form-control pull-right datepicker" id="receiptDate" data-parsley-required  data-parsley-required-message="Enter Receipt Date"
						 data-parsley-errors-container="#error1" data-parsley-trigger='keypress'>
						</div>
						<span id="error1"></span>
					</div>
					</div>
					
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
			  </div>
			  <div class="row">
			 <div class="col-md-4">
              <div class="form-group">
                <label>Amount<span class="mandatory">*</span></label>
                <input class="form-control number zero"   readonly name="recpAmount"  type="number" id="recpAmount"	data-parsley-minlength="2"  data-parsley-minlength-message="Receipt Amount Should have Minimum 2 Digits" data-parsley-maxlength='6' data-parsley-maxlength-message="Receipt Amount Accepts only 6 digits" data-parsley-trigger='keypress'>
              </div>
            </div>
				
				<!--<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Receipt Details<span class="mandatory">*</span></label>
							<textarea class="form-control" name='recp_details' id="recp_details"  readonly data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject'></textarea>
									
						</div>
					</div>
				</div>-->
				
					<div class="col-md-4">
						<div class="form-group">
							
							<label>Number of pages</label>							
							 <input class="form-control zero number" name="noOfPages" type="number" id="noOfPages" 
							 data-parsley-required data-parsley-required-message="Enter No Of Pages."
							 maxlength="5" data-parsley-trigger='keypress'>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Number of Copies</label>
							<input class="form-control zero number" name="noOfCopies" type="number" id="noOfCopies" data-parsley-required data-parsley-required-message="Enter No Of Copies."
							maxlength="3" data-parsley-trigger='keypress'>
							
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							
							<label>Amount to be Collected</label>
							<input type="text" name="amount_coll" class="form-control pull-right" id="amount_coll" readonly value="" data-parsley-errors-container="#modlerror">
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Deficit Amount</label>
							<input type="text" name="defi_amt" class="form-control pull-right" id="defi_amt" readonly value="" data-parsley-errors-container="#modlerror">
						</div>	
					</div>
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
				<br><br>
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
	 <script src="js/cca/cca.js"></script>
	
@endsection
