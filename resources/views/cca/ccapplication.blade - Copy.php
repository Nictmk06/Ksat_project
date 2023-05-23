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
  <div id="appends" style="display: none">@extends( 'cca/CopyAData')</div>
  <div id="newcontent"></div>
  @include('flash-message')
	<section class="content">
		<div class="modal fade" id="editmodal">
			  <div class="modal-dialog modal-md">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id='edit_cappl-title'></h4>
				  </div>
				  <div class="modal-body">
					<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
					<div class="row">
					  <!--<div class="col-md-6">
						<div class="form-group">
						  <label>Type of Application<span class="mandatory">*</span></label>
						  <select class="form-control" name="edit_modal_type" id="edit_modal_type" >
							@foreach($establishment as $establish)
												
								<option value="{{$establish->establishcode}}">{{$establish->establishname}}-{{$establish->establishcode}}</option>
							@endforeach
						  </select>
						</div>
					  </div>-->
					  <div class="col-md-4">
						<div class="form-group">
						  <label>Application No<span class="mandatory">*</span></label>
						  <div class="input-group date">
							<input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" value="" data-parsley-errors-container="#modlerror">
							<input name="estcode" type="hidden" id="estcode" value="{{ $estcode }}"/>
							<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="editCASearch">
							  <i class="fa fa-search"></i>
							</div>
							
						  </div>
						  <span id="modlerror"></span>
						</div>
					  </div>
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
					</div>
					
				  </div>
				  
				</div>
				<!-- /.modal-content -->
			  </div>
			  <!-- /.modal-dialog -->
		</div>
		<ul class="nav nav-tabs" id="myTab">
				<li style="float:right;" id="cancelApplication"><input type="button"  id="" class="btn btn-danger   btn-md center-block" Style="width: 100px;" value="Cancel"></li>
				<li style="float:right;" id="editApplication"> <input type="button"  id="" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Edit"></li>
				</ul>
		<div class="panel  panel-primary">
			
			<div class="panel panel-heading origndiv">
				<h7 >Copy Application</h7>
			</div>	
			<form id="ccapplication" action="/" method="post">
			{{ csrf_field() }}
			<div class="panel panel-body divstyle" >
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="form-group">
								<label>Copy Application No<span class="mandatory">*</span></label>
								<input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" readonly value="" data-parsley-errors-container="#modlerror">
								
								
								<span id="modlerror"></span>
							</div>	
						</div>
					</div>
				</div>
				<div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Type of Application<span class="mandatory">*</span></label>
                  <select class="form-control" name="search_modal_type" id="search_modal_type" >
				  <option value="" class="form-control">Select Applcation Type</option>
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
                    <input type="text" name="search_applno" class="form-control pull-right" id="search_applno" value="" data-parsley-errors-container="#modlerror">
                    <!--<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="SearchDetails">
                      <i class="fa fa-search"></i>
                    </div>-->
					<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="Searchappl">
                      <input name="estcode" type="hidden" id="estcode" value="{{ $estcode }}"/>
					  <i class="fa fa-search"></i>
                    </div>
                    
                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
            </div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
						<label>Name of the Applicant</label>
						<input type="textarea" name="app_name" class="form-control pull-right" id="app_name" readonly data-parsley-errors-container="#modlerror">
						</div>
						<div class="form-group">
						<label>Name of the Responddent</label>
						<input type="text" name="res_name" class="form-control pull-right" id="res_name" readonly data-parsley-errors-container="#modlerror">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Subject<span class="mandatory">*</span></label>
							<textarea class="form-control" name='applnSubject' id="applnSubject"  readonly data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject'></textarea>
									
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>CCA Application Date<span class="mandatory">*</span></label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="dateOfCA" class="form-control pull-right datepicker" id="dateOfCA"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Date Of Application"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
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
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Order Date<span class="mandatory">*</span></label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="dateOfOrd" class="form-control pull-right datepicker" id="dateOfOrd"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Date Of Application"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
							</div>
							<span id="error3"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Name of the Document<span class="mandatory">*</span></label>
							<input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" value="" data-parsley-errors-container="#modlerror">
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Is Advocate?<span class="mandatory">*</span></label><br>
							<label class="radio-inline">
							  <input type="radio" name="isMainParty" value="Y"   checked>Yes
							</label>
							<label class="radio-inline">
							  <input type="radio" name="isMainParty" value="N" >No
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 advDetails">
						<div class="form-group">
							<label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
								<div class="input-group date">
								<input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
								data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20"  data-parsley-errors-container='#errorAdv1'>
								<datalist id="browsers">
									<?php foreach($advocatedetails as $advocate){?>
									<option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
									<?php }?>
								</datalist>
							<div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="advocateAdd">
							<i class="fa fa-plus"></i>
							</div>
                  
							</div>
							<span id='errorAdv1'></span>
						</div>
              		</div>
					<div class="col-md-4 advDetails">
						<div class="form-group">
							<label>Advocate Name</label>
							<div class="input-group input-group-sm">
							<div class="input-group-btn">
								<button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch4" data-toggle="dropdown"><span class="title_sel4">Select Title</span> <span class="selection4"></span>
								<span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu dropdown_all4" >
								@foreach($nameTitle as $title)
									<li ><a value="{{$title->titlename}}">{{$title->titlename}}</a></li>
								@endforeach
								</ul>
							</div> <!-- /btn-group -->
							<input type="hidden" class="form-control" id="advTitle" name="advTitle" readonly="">
							<input type="text" class="form-control" id="advName" name="advName" readonly="">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<div class="form-group">
							<label>Name of the Applicant</label>
							<input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" value="" data-parsley-errors-container="#modlerror">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<label>Address</label>
						<input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" value="" data-parsley-errors-container="#modlerror">
							
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
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
					<div class="col-md-4">
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
								<input type="text" name="search_recpno" class="form-control pull-right" id="search_recpno" value="" data-parsley-errors-container="#modlerror">
							<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="SearchResp">
								<i class="fa fa-search"></i>
							</div>
                    
							</div>
							<span id="modlerror"></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Receipt Amount</label>
							<input type="text" name="resp_amt" class="form-control pull-right" id="resp_amt" readonly value="" data-parsley-errors-container="#modlerror">
						</div>	
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Receipt Details<span class="mandatory">*</span></label>
							<textarea class="form-control" name='recp_details' id="recp_details"  readonly data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject'></textarea>
									
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							
							<label>Number of pages</label>
							<input type="text" name="noOfPages" class="form-control pull-right" id="noOfPages" readonly value="" data-parsley-errors-container="#modlerror">
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>Number of Copies</label>
							<input type="text" name="noOfCopies" class="form-control pull-right" id="noOfCopies" value="" data-parsley-errors-container="#modlerror">
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
				</div>
				<br><br>
				<div class="row"  style="float: center;" id="add_apl_div">
				<div class="col-md-12 text-center">
              <input type="hidden" name="sbmt_applicant" id="sbmt_applicant" value="A">
			  <input type="hidden" name="srno_applicant" id="srno_applicant" value="">
              <input type="button" id="saveApplicant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
              <input type="button" id="clearApp" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
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
	<script src="{{asset('js/jquery.min.js')}}"></script>
	<script>
	$(document).ready(function(){
		$("#editApplication").click(function(){
			$('#editmodal').modal('show');
			$('#edit_appl-title').text('Edit Application');
			
		})
	
	$("#editCASearch").click(function(){
		
		var user = $("#username").text();
		var estcode = $('#estcode').val();
		var type = $("#edit_modal_type").val();
		var srno = $("#edit_applno").val();
		//var newtype = type.split('-');
		//var applId = newtype[1]+'/'+$("#edit_applno").val();
		var est = estcode+'/C'+srno;
		////alert(est);
		var _token = $('input[name="_token"]').val();
		//alert(_token);
		///console.log(applId);
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: '/getCaveatDetails',
				data: {_token:_token,applicationid:est,user:user},
				success: function(data) {
					////alert('workign');
					if(data.status=='success')
					{
						////alert('workign');
						swal({
													title: data.message,
													icon: "error",
												});
					}
					else
					{
							$("#editmodal").modal('hide');
							$("#appends").hide();
							$("#newcontent").show();
							$("#newcontent").html(data);
							
					}
					
				
			
					
				}
			});
		
	})
		$("#Searchappl").click(function() {
		////alert('OK');
		if ($("#search_applno").val() == '') {
			$('#search_applno').parsley().removeError('search_applno');
			$('#search_applno').parsley().addError('search_applno', {
				message: "Enter Application No"
			});
			return false;
		} else {
			$('#search_applno').parsley().removeError('search_applno');
		}
		var modl_appltype_name = $("#search_modal_type").val();
		//alert(modl_appltype_name);
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		//alert(applnewtype);
		var modl_search_applno = $("#search_applno").val();
		var applId = applnewtype+'/'+ modl_search_applno;
		//alert(applId);
		var flag='application';
		var _token = $('input[name="_token"]').val();
		$.ajax({
			type: 'POST',
			url: 'getApplicationCA',
			data: {
				_token: _token,
				application_id: applId,flag:flag
			},
			dataType: "JSON",
			success: function(data) {
				//alert('AJAY');
				var len = data['data'].length;
				
				for (var i = 0; i < data['data'].length; i++) {
					 var applnSubject1 = data.data[i].subject;
					 //alert(applnSubject1);
					// var res_name1 = data.data[i].respondname;
					var res_name1 = data.data[i].respondentname;
					 var app_name1 = data.data[i].applicantname1;
					 
					 //alert(res_name1);
					 //alert(app_name1);
					 if(i==0){
					 $("#app_name").val(app_name1);
					 $("#res_name").val(res_name1);
					 $("#applnSubject").val(applnSubject1);
					 }
					 else{
						$("#app_name").val($("#app_name").val()+' '+ (i+1)+' . '+app_name1);
						$("#res_name").val($("#res_name").val()+' '+ (i+1)+' . '+res_name1);
						$("#applnSubject").append(' '+(i+1)+' . '+applnSubject1);
					 
					 }
				}
			}
				
		});
		$.ajax({
			type: 'POST',
			url: 'getApplicationJudgement',
			data: {
				_token: _token,
				application_id: applId
			},
			dataType: "JSON",
			success: function(data) {
				//alert('AJAY');
				var len = data['data'].length;
				
				for (var i = 0; i < data['data'].length; i++) {
					var pagecount = data.data[i].pagecount;
					//alert(pagecount);
					$("#noOfPages").val(pagecount);
				}
			}
        });
	})//search appl
		$("#ccadoc_type").on('change', function() {
		//alert( "AJAY" );
		var text = $("#ccadoc_type").val();
		//alert(text);
		var modl_appltype_name = $("#search_modal_type").val();
		//alert(modl_appltype_name);
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		//alert(applnewtype);
		var modl_search_applno = $("#search_applno").val();
		//alert(modl_search_applno);
		var applId = applnewtype+'/'+ modl_search_applno;
		//alert(applId);
		var _token = $('input[name="_token"]').val();
		if(text == 1){
			//alert('swathi');
				$.ajax({
				type: 'POST',
				url: 'getApplicationStatus',
				data: {
					_token: _token,
					application_id: applId
				},
				dataType: "JSON",
				success: function(data) {
				var len = data['data'].length;
				
				if(len>0)
					{
					var disposedstatus = data.data[0].disposeddate;
					var applId = data.data[0].applicationid;
					var dspdate = data.data[0].disposeddate;
					if (applId = null){
						swal({
									title: " Application is Not Disposed ",

									icon: "success",
								});
					}else{
						swal({
									title: " Judgement Date is: "+dspdate,

									icon: "success",
								});
					}
				}	
				 else
				{
					swal({
									title: " Application is Not Disposed ",

									icon: "success",
								});
								
				$("#noOfPages").val('');
				}}
			});
		}
		else if(text == 2){
			//alert('swathi is cooling');
		}
		else{
			//alert('Swahti is on fire');
		}
	});
		$("#advBarRegNo").bind('input', function() {

		var value = $("#advBarRegNo").val();
		////alert(value);
		getBarregDetails(value);
		//var text = $("#browsers").find('option[value=' + value + ']').text();


	});
	
		function getBarregDetails(value) {
		var _token = $('input[name="_token"]').val();
		$.ajax({
			type: 'POST',
			url: 'advRegNoApp',
			data: {
				"_token":_token,
				value: value
			},
			
			dataType: "JSON",
			success: function(json) {
				//console.log(json);
				for (var i = 0; i < json.length; i++) {
					//console.log(json[i].nameTitle);
					$(".advancedSearch4 .selection4").text(json[i].nametitle);
					$(".title_sel4").css('display', 'none');
					$("#advTitle").val(json[i].nametitle);
					$("#advName").val(json[i].advocatename);
					$("#advRegAdrr").val(json[i].advocateaddress);
					/* $("#advRegTaluk").attr('disabled', false);
					$("#advRegDistrict").attr('disabled', false);
					$("#advRegTaluk").empty();
					$("#advRegDistrict").empty();
					$("#advRegTaluk").append('<option value="'+ json[i].talukcode +'"selected>' + json[i].talukname + '</option>');
					$("#advRegDistrict").append('<option value="' + json[i].distcode + '"selected>' + json[i].distname + '<option>');
					$("#advRegPin").val(json[i].pincode); */
				}
			}
		});
	}//adv search
		$('.districtCA').change(function(){
		if($(this).val() != '')
		{
			var select = $(this).attr("id");
			var value = $(this).val();
			var dependent = $(this).data('dependent');
			var _token = $('input[name="_token"]').val();
			
			$.ajax({
				url:"getCADistrict",
				method:"POST",
				data:{select:select, value:value, _token:_token, dependent:dependent},
				success:function(result)
				{
					$('#'+dependent).html(result);
				}

			})
		}
	});

		$("#SearchResp").click(function() {
		//alert('TEST Ok');
		var _token = $('input[name="_token"]').val();
		var search_recpno = $("#search_recpno").val();
		//alert(search_recpno);
		$.ajax({
				type: 'POST',
				url: 'getReceiptStatus',
				data: {
					_token: _token,
					search_recpno: search_recpno
				},
				dataType: "JSON",
				success: function(data) {
					//alert('OK');
					var len = data['data'].length;
					var recp_use_date = data.data[0].receiptuseddate;
					var applicationid = data.data[0].applicationid;
					
					alert(len);
					//alert(recp_use_date);
					if(len == 0){
						swal({
									title: " Receipt is Not Available ",

									icon: "success",
								});
					}
					//else (recp_use_date != null ){
						//var applid = data.data[i].applicationid;
					if(applicationid != null){	
						//var applid = data.data[0].applicationid;
						//alert(applid);
						swal({
									title: " The Receipt is used against the Application No: "+applicationid,

									icon: "success",
								});
					}			
					//}/*else{
					 	
					 for (var i = 0; i < data['data'].length; i++) {
							var recp_amt = data.data[i].amount;
							$("#resp_amt").val(recp_amt);//= data.data[i].amount
							var recp_date = data.data[i].receiptdate;
							var recp_name = data.data[i].name
							$("#recp_details").val('Receipt Date: '+recp_date+'\n');
							$("#recp_details").append('Name: '+recp_name);
						}
					//}
				}
		})
	})
	$("#noOfCopies").bind('input', function() {
		////alert('noOfCopies');
		var value = $("#noOfCopies").val();
		var nopages = $("#noOfPages").val();
		var reciptamt = $("#resp_amt").val();
		amount = value*nopages*15;
		$("#amount_coll").val(amount);
		$("#defi_amt").val(amount-reciptamt);
		
		//alert(value);
		//alert(nopages);
		
		//getBarregDetails(value);
		//var text = $("#browsers").find('option[value=' + value + ']').text();


	});
	$("#saveApplicant").click(function() {
		//alert('TEST Ok');
		var ccapp = $('#ccapplication').serialize();
		//alert(ccapp);
		
		});
	});
	
	</script>
	
	
@endsection