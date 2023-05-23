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
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')
  <section class="content">
	<div class="panel  panel-primary">

		<div class="panel panel-heading origndiv" align="center">
			<h7 >Caveat Search</h7>
		</div>	<form action="" id="caveatsearch" method="post">
			{{ csrf_field() }}
		<div class="panel panel-body divstyle" >
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
				<div class="col-md-4">
					<div class="form-group">
					  <label>Date Of Application<span class="mandatory" >*</span></label>
					  <div class="input-group date">
				    	<div class="input-group-addon">
					  <i class="fa fa-calendar"></i>
						</div>
					<input type="text" name="dateOfAppl" class="form-control pull-right" id="dateOfAppl"  readonly="readonly" value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Date Of Application"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
						</div>
					<span id="error3"></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label>Subject Category<span class="mandatory">*</span></label>
						<select class="form-control" name="applCatName" id="applCatName" readonly="readonly" data-parsley-required  data-parsley-required-message="Select Application Category" data-parsley-trigger='keypress'>
						<option value="" class="form-control">Select Applcation Category</option>
						@foreach($applCategory as $applCat)
						<option value="{{$applCat->applcatcode}}">{{$applCat->applcatname}}</option>
						@endforeach
						</select>
						</div>
					</div>

			</div>
			<div class="panel  panel-primary">
				<div class="panel panel-heading origndiv">
					<h7>Search Results</h7>
				</div><!--judges div-->
				<div class='panel panel-body divstyle'  overflow: auto;>
		<div class="row">
				<div class="col-md-6">
					<div class="form-group">
					  <label>Department(Optional)<span class="mandatory">*</span></label>
					  <select class="form-control" name="searcch_type" id="search_type" >
							<option value="">Select the Parameter to Search</option>
							@foreach($department as $dept)
						<option value="{{$dept->departmentname}}">{{$dept->departmentname}}</option>
						@endforeach
					  </select>
					</div>
              </div>
			 <div class="col-md-4"  id="department_div">
              <div class="form-group">
                <label>Select the Search In Department<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="SearchInDept" id="SearchInDept" value="caveatordeptname" >Caveator
                </label>
                <label class="radio-inline">
                  <input type="radio" name="SearchInDept" id="SearchInDept" value="caveateedeptname" checked>Caveatee
                </label>
              </div>
              <span id="error12"></span>
            </div>


		</div>
		<div class="row">
		<div class="col-md-4">
                <div class="form-group">
                  <label>Search In<span class="mandatory">*</span></label>
                  <select class="form-control" name="search_on" id="search_on" >
                    	<option value="">Select the Parameter to Search</option>
						@foreach($searchon as $search)
						<option value="{{$search->searchcolumn}}">{{$search->searchon}}</option>
						@endforeach

                  </select>
                </div>
              </div>
		<div class="col-md-4 goorderdate">
						<div class="form-group">
							<label> GO Order Date<span class="mandatory">*</span></label>
							<div class="input-group date">
							<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
							</div>
							<input type="text" name="goorderdate" class="form-control pull-right datepicker" id="goorderdate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Date Of Application"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
							</div>
							<span id="error3"></span>
						</div>
		</div>	
		<div class="col-md-4 searchvalue">
                <div class="form-group">
                  <label>Search Value<span class="mandatory">*</span></label>
                    <input type="text" name="searchvalue" class="form-control" id="searchvalue" value="" data-parsley-errors-container="#modlerror">


                  <span id="modlerror"></span>
                </div>
            </div>
			  <div class="col-md-4"  id="caveat90_div">
              <div class="form-group">
                <br><br>				
                <label class="radio-inline">
                  <input type="checkbox" name="caveat90" id="caveat90">  Show Caveat of 90 days
                </label>

              </div>
              <span id="error12"></span>
            </div>
			  <!--end table row-->
		</div>
		<div class="row">
			


		</div>
		<div class="row"  style="float: center;" id="search_div">
            <div class="col-sm-12 text-center">
              <input type="button" id="SearchCa" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Search">
			  <button type="reset" class="btn btn-danger btn-md center-block btnClear" id="res_clear" Style="width: 100px;">Clear</button>
              <!--<input type="button reset" id="ClearSearch" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Clear">-->
            </div>
          </div>
		  </br>
		 <div class="row" id="search_div">
            <table id="search_tab" class="table table-bordered table-striped applicant-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr><td  class="col-sm-1" align='center'>Sr.No</td>
				<td  class="col-sm-2" align='center'>NameMatch</td>
                <td  class="col-sm-2" align='center'>Caveatid</td>
                <td  class="col-sm-2" align='center'>DateOfFiling</td></tr>
              </thead>
              <tbody id="results">

              </tbody>
            </table>
          </div><!--end of table-->

			<div class="panel  panel-primary">
				<div class="panel panel-heading origndiv" align="center" id="caveat">
					<h7>Matched Caveat Details</h7>
				</div>
				<div class='panel panel-body divstyle'  overflow: auto;>
			<div class="row" id="details_div">
            <table id="details_tab" class="table table-bordered table-striped applicant-list" >
              <thead style="background-color: #3c8dbc;color:#fff">

              </thead>
              <tbody id="results">
                <tr><td  class="col-sm-1" align='center'>Caveat No:</td></tr>
				<tr><td  class="col-sm-2" align='center'>File Date:</td></tr>
                <tr><td  class="col-sm-2" align='center'>Subject:</td></tr>
                <tr><td  class="col-sm-2" align='center'>GO Order No:</td></tr>
				<tr><td  class="col-sm-2" align='center'>GO Date:</td></tr>

              </tbody>
            </table>
          </div>
		  </div><!--AUto div end-->
		  <div class="panel panel-heading origndiv" align="center" id="caveator">
					<h7>Caveator Details</h7>
				</div>
		  <div class='panel panel-body divstyle'  overflow: auto;>
		  <div class="row" id="caveat_div">
            <table id="caveat_tab" class="table table-bordered table-striped applicant-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr><td  class="col-sm-1" align='center'>Sr.No</td>
				<td  class="col-sm-2" align='center'>Name</td>
                <td  class="col-sm-2" align='center'>Advocate</td>
                <td  class="col-sm-2" align='center'>Department</td></tr>
              </thead>
              <tbody id="results">

              </tbody>
            </table>
          </div><!--end of table1-->
		  </div><!--AUto div end-->
		  <div class="panel panel-heading origndiv" align="center" id="caveatee">
					<h7>Caveatee Details</h7>
				</div>
		  <div class='panel panel-body divstyle'  overflow: auto;>
		  <div class="row" id="caveatee_div">
            <table id="caveatee_tab" class="table table-bordered table-striped applicant-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr><td  class="col-sm-1" align='center'>Sr.No</td>
				<td  class="col-sm-2" align='center'>Name</td>
                <td  class="col-sm-2" align='center'>Department</td></tr>
              </thead>
              <tbody id="results">

              </tbody>
            </table>
          </div><!--end of table1-->
		  </div><!--AUto div end-->
			</div>
			<div class="row"  style="float: center;" id="link_div">
            <div class="col-sm-12 text-center">
              <input type="button" id="LinkCaveat" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Link Caveat">
              <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Close">
            </div>
          </div>
			<div class="row">

			</div>
		</div></div>

		</div>

	</div>


<script src="js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".goorderdate").hide();
	$("#details_tab").hide();
	$("#caveat_tab").hide();
	$("#caveatee_div").hide();
	$("#caveat").hide();
    $("#caveator").hide();
	$("#caveatee").hide();
	var link_caveatid;
	var link_appId;
	
	$("#search_on").on('change', function() {
		var search_on = $("#search_on").val();
		if(search_on=="goorderdate"){
			$(".goorderdate").show();
			$(".searchvalue").hide();
		}else{
				
			$(".goorderdate").hide();
			$(".searchvalue").show();
			}
		
		
	});
	
	$("#SearchDetails").click(function(){
		//alert('search function');
		var user = $("#username").text();
		var type = $("#search_type").val();
		var value = $("#basedonsearch").val();
		//alert(type);
		//alert(value);
		//var newtype = type.split('-');
		//var applId = newtype[1]+'/'+$("#edit_applno").val();
		//var est = type+'/C'+srno;
		//alert(est);
		var _token = $('input[name="_token"]').val();
		//alert(_token);
		///console.log(applId);
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getSearchDetails',
				data: {_token:_token},
				success: function(data) {
					//alert('workign');
					/* if(data.status=='success')
					{
						//alert('workign');
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

					} */




				}
			});

	})
	$("#Searchappl").click(function() {
		//alert('OK');
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
		link_appId = applId;
		//alert(applId);
		var flag='application';
		var _token = $('input[name="_token"]').val();
		$.ajax({
			type: 'POST',
			url: 'getApplication',
			data: {
				_token: _token,
				application_id: applId,flag:flag
			},
			dataType: "JSON",
			success: function(json) {
				if (json.length > 0) {
					//console.log(json.length);
					$("#displAppl1").show();
					$("#displAppl2").show();


					for (var i = 0; i < json.length; i++) {
						//console.log(json[i].registerdate);
						//if (json[i].registerdate === null) {

							//$("#modl_regdate").val('');
						//} else {
							//var dor = json[i].registerdate;
							//var dor_split = dor.split('-');
							//var dateOfReg = dor_split[2] + '-' + dor_split[1] + '-' + dor_split[0];
							//$("#modl_regdate").val(dateOfReg);
						//}
						if (json[i].applicationdate == null) {

							$("#dateOfAppl").val('');
						} else {
							var doa = json[i].applicationdate;

							var doa_split = doa.split('-');
							//var dateOfApp = doa_split[2] + '-' + doa_split[1] + '-' + doa_split[0];
							var dateOfApp = json[i].applicationdate;
							$("#dateOfAppl").val(dateOfApp);

						}



						//$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);

						//$("#applCatName").val(json[i].applcategory);
						$("#applCatName").val(json[i].applcategory);
						$("#applCatName").prop("disabled", true);
					}
				} else {
					$("#search_applno").val('');
					$("#displAppl1").hide();
					$("#displAppl2").hide();
					swal({
						title: "Application Does Not Exist",

						icon: "error"
					})
				}
			}
		});

	})
	$("#SearchCa").click(function(){
	 //alert('search started');
	 var search_value = $("#searchvalue").val();
	  var goorderdate = $("#goorderdate").val();
	 //alert(search_value);
	 var searchon = $("#search_on").val();
	 var deprtment_value = $("#search_type").val();
	 if(deprtment_value == '' && searchon == '' && search_value=="")
	   {
		   alert("Select some criteria to search ");
		   return false;
	   }
	  
	if(searchon == '' && search_value!="")
	   {
		   alert("Select search in");
		   return false;
	   }
	  // alert(search_value);
	if(searchon == 'goorderdate' && goorderdate=="")
	  {
		   alert("Select GO Order Date");
		   return false;
	  }	  
	  if(searchon != '' && searchon != 'goorderdate' && search_value=="")
	   {
		   alert("Select search value");
		   return false;
	   }

	//alert(searchon);
	 //var caveat90 = $("#caveat90").checked();
	 var checkRadio = document.querySelector(
                'input[name="caveat90"]:checked');
	 if(checkRadio){
		 //alert('checked');
		 var checkvalue = 'Y'

	 }else{
		 //alert('Not checked');
		 var checkvalue = 'N'
	 }
	 //alert(checkvalue);
	 var deprtment_value = $("#search_type").val();
	 //alert(deprtment_value);
	 //alert(caveat90);
	 	/*var issingl = $("input[type=radio][name='isAdvocate']:checked").val();*/
		var searchin = $("input[type=radio][name='SearchInDept']:checked").val();
		//alert(searchin);

	var estcode = $('#estcode').val();
	//alert(estcode);
	 var _token = $('input[name="_token"]').val();
	 link_caveatid = "";
	 //alert(link_caveatid );
	 if(searchon == ""){
		$('#search_tab').find('tbody td').remove();
					$('#details_tab').find('tbody td').remove();
					$('#details_tab').find('tbody th').remove();
				    $('#caveat_tab').find('tbody tr').remove();
					$('#caveatee_tab').find('tbody tr').remove();
		 $.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getSearchDetailsNull',
				data: {_token:_token,checkvalue:checkvalue,deprtment_value:deprtment_value,searchin:searchin,estcode:estcode},
				success: function(data) {
					//alert('workign');
					$('#search_tab').find('tbody td').remove();
					$('#details_tab').find('tbody td').remove();
					$('#details_tab').find('tbody th').remove();
					$('#caveat_tab').find('tbody tr').remove();
					$('#caveatee_tab').find('tbody tr').remove();
					$("#caveat").hide();
					$("#caveator").hide();
					$("#caveatee").hide();
					var len = data['data'].length;
					//alert(len);
			        var id = data.data[0].caveatid;
					//alert(id);

					$("#search_tab tbody").empty(); // Empty <tbody>
				if(len > 0){
					for(var i=0; i<len; i++){
						//alert('for');
						var SRNO = i+1;
						var NAMEMATCH = data.data[i][searchin];
						var APPLICATIONID = data.data[i].caveatid;
						var dor = data.data[i].caveatfiledate;
						var dor_split = dor.split('-');
						var FILEDATE = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						alert(FILEDATE);


					   var tr_str = "<tr>" +
						   "<td align='center'>" + SRNO + "</td>" +
						   "<td align='center'>" + NAMEMATCH + "</td>" +
						   "<td align='center'><a href='#' data-value='"+APPLICATIONID+"' class='searchClick'>" + APPLICATIONID + "</td>" +
						   "<td align='center'>" + FILEDATE + "</td>" +
					   "</tr>";

						$("#search_tab tbody").append(tr_str);
						//$("#caveatApplicant").trigger("reset");

					}
					$(".searchClick").click(function(){
					$("#details_tab").show();
					$("#caveat_tab").show();
					$("#caveatee_div").show();
					$("#caveat").show();
					$("#caveator").show();
					$("#caveatee").show();
					var caveatno  = $(this).attr('data-value');

					//alert(caveatno);
					//alert('on ok');
					$.ajax({
						type: 'post',
						dataType:'JSON',
						url: 'searchcaveat',
						data: {_token:_token,caveatid:caveatno},
						success: function(data) {
								alert('22');
							//var len = data['data'].length;
							 console.log(data);
							//$("#details_tab tbody tr").each(function(){
							//$('#details_tab').find('tbody tr').remove();
							$('#caveat_tab').find('tbody tr').remove();
							$('#caveatee_tab').find('tbody tr').remove();
							$('#details_tab').find('tbody td').remove();
							$('#details_tab').find('tbody th').remove();
						
							var dtable = $("#details_tab tbody");
							var count = $("#details_tab > tbody > tr").length;
							link_caveatid = data[0][0].caveatid;
							var dor = data[0][0].caveatfiledate;
							var dor_split = dor.split('-');
							var FILEDATE = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
							//alert(count);
								$("#details_tab tbody tr:first").append("<th align='center' class='col-sm-3' style='text-align:center'>" + 'Caveat Id' + "</th>");
								$("#details_tab tbody tr:first").append("<td align='left'>" + data[0][0].caveatid + "</td>");
								$("#details_tab tbody tr:first").append("<th align='center' class='col-sm-3' style='text-align:center'>" + 'Caveat file Date' + "</th>");
								$("#details_tab tbody tr:first").append("<td align='left'>" +  FILEDATE + "</td>");
								$("#details_tab tbody tr").eq(2).append("<th align='center' class='col-sm-1' style='text-align:center'>" + 'Government Order challenged '+ "</th>");
						    	$("#details_tab tbody tr").eq(2).append("<td align='left' colspan='3'>" + data[0][0].goorderno + "</td>");
							//	$("#details_tab tbody tr").eq(2).append("<th align='center' class='col-sm-1' style='text-align:center'>" + 'Government Order Date' + "</th>");
								//$("#details_tab tbody tr").eq(2).append("<td align='left'>" + data[0][0].goorderdate + "</td>");
									$("#details_tab tbody tr").eq(3).append("<th align='center' class='col-sm-1'  style='text-align:center'>" + 'Subject' + "</th>");
								$("#details_tab tbody tr").eq(3).append("<td align='left' colspan='3'>" + data[0][0].subject + "</td>");
								
							for(var i=0; i<data[1].length; i++){		
							  var caveator_tr_str = "<tr>" +
						   "<td align='center'>" + (i+1) + "</td>" +
						   "<td align='center'>" + data[1][i].applicantname + "</td>" +
						   "<td align='center'><a href='#' data-value='"+APPLICATIONID+"' class='searchClick'>" + data[1][i].advocatename + "</td>" +
						   "<td align='center'>" + data[1][i].departmentname + "</td>" +
						   "</tr>";

						$("#caveat_tab tbody").append(caveator_tr_str);
							}					
												
						for(var i=0; i < data[2].length; i++){	
							var caveatee_tr_str = "<tr>" +
						   "<td align='center'>" + (i+1) + "</td>" +
						   "<td align='center'>" + data[2][i].caveateename + "</td>" +
						   "<td align='center'>" + data[2][i].departmentname + "</td>" +
						   "</tr>";
						$("#caveatee_tab tbody").append(caveatee_tr_str);
							//for(j=0; j<count; j++){

							}
						}
							});
					});
				  }
				}
			})//AJAX end
	 }
	 else{
		 $.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getSearchDetails',
				data: {_token:_token,search_value:search_value,goorderdate:goorderdate,searchon:searchon,checkvalue:checkvalue,deprtment_value:deprtment_value,searchin:searchin,estcode:estcode},
				success: function(data) {
					//alert('workign');
					$('#search_tab').find('tbody td').remove();
					$('#caveat_tab').find('tbody tr').remove();
					$('#caveatee_tab').find('tbody tr').remove();
					$('#details_tab').find('tbody td').remove();
					$('#details_tab').find('tbody th').remove();						
					$("#caveat").hide();
					$("#caveator").hide();
					$("#caveatee").hide();
					var len = data['data'].length;
					//var id = data.data[0].caveatid;
					$("#search_tab tbody").empty(); // Empty <tbody>
					if(len > 0){
					for(var i=0; i<len; i++){
						//alert('for');
						var SRNO = i+1;
						if(searchon=='goorderdate')
						{
							searchon="goorderno";
							var NAMEMATCH = data.data[i][searchon];
						}else{
							var NAMEMATCH = data.data[i][searchon];
						}
						
						var APPLICATIONID = data.data[i].caveatid;
						 var dor = data.data[i].caveatfiledate;
						var dor_split = dor.split('-');
						var FILEDATE = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						 var tr_str = "<tr>" +
						   "<td align='center'>" + SRNO + "</td>" +
						   "<td align='center'>" + NAMEMATCH + "</td>" +
						   "<td align='center'><a href='#' data-value='"+APPLICATIONID+"' class='searchClick'>" + APPLICATIONID + "</td>" +
						   "<td align='center'>" + FILEDATE + "</td>" +
					   "</tr>";

						$("#search_tab tbody").append(tr_str);
						//$("#caveatApplicant").trigger("reset");

					}
					$(".searchClick").click(function(){
					$("#details_tab").show();
					$("#caveat_tab").show();
					$("#caveatee_div").show();
					$("#caveat").show();
					$("#caveator").show();
					$("#caveatee").show();
					var caveatno  = $(this).attr('data-value');
					link_caveatid = caveatno;
					$.ajax({
						type: 'post',
						dataType:'JSON',
						url: 'searchcaveat',
						data: {_token:_token,caveatid:caveatno},
						success: function(data) {
						//	alert('1111');
							//var len = data['data'].length;
							 console.log(data);
							//$("#details_tab tbody tr").each(function(){
							//$('#details_tab').find('tbody tr').remove();
							$('#caveat_tab').find('tbody tr').remove();
							$('#caveatee_tab').find('tbody tr').remove();
							$('#details_tab').find('tbody td').remove();
							$('#details_tab').find('tbody th').remove();
							var dtable = $("#details_tab tbody");
							var count = $("#details_tab > tbody > tr").length;
							link_caveatid = data[0][0].caveatid;
							
							var dor = data[0][0].caveatfiledate;
							var dor_split = dor.split('-');
							var FILEDATE = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
							//alert(count);
								$("#details_tab tbody tr:first").append("<th align='center' class='col-sm-3' style='text-align:center'>" + 'Caveat Id' + "</th>");
								$("#details_tab tbody tr:first").append("<td align='left'>" + data[0][0].caveatid + "</td>");
								$("#details_tab tbody tr:first").append("<th align='center' class='col-sm-3' style='text-align:center'>" + 'Caveat file Date' + "</th>");
								$("#details_tab tbody tr:first").append("<td align='left'>" + FILEDATE + "</td>");
								$("#details_tab tbody tr").eq(2).append("<th align='center' class='col-sm-1' style='text-align:center'>" + 'Government Orders challenged '+ "</th>");
						    	$("#details_tab tbody tr").eq(2).append("<td align='left' colspan='3'>" + data[0][0].goorderno + "</td>");
								//$("#details_tab tbody tr").eq(2).append("<th align='center' class='col-sm-1' style='text-align:center'>" + 'Government Order Date' + "</th>");
							//	$("#details_tab tbody tr").eq(2).append("<td align='left'>" + data[0][0].goorderdate + "</td>");
									$("#details_tab tbody tr").eq(3).append("<th align='center' class='col-sm-1'  style='text-align:center'>" + 'Subject' + "</th>");
								$("#details_tab tbody tr").eq(3).append("<td align='left' colspan='3'>" + data[0][0].subject + "</td>");					

						for(var i=0; i<data[1].length; i++){							
						   var caveator_tr_str = "<tr>" +
						   "<td align='center'>" + (i+1) + "</td>" +
						   "<td align='center'>" + data[1][i].applicantname + "</td>" +
						   "<td align='center'><a href='#' data-value='"+APPLICATIONID+"' class='searchClick'>" + data[1][i].advocatename + "</td>" +
						   "<td align='center'>" + data[1][i].departmentname + "</td>" +
						   "</tr>";
						$("#caveat_tab tbody").append(caveator_tr_str);
							}

						for(var i=0; i < data[2].length; i++){	
							var caveatee_tr_str = "<tr>" +
						   "<td align='center'>" + (i+1) + "</td>" +
						   "<td align='center'>" + data[2][i].caveateename + "</td>" +
						   "<td align='center'>" + data[2][i].departmentname + "</td>" +
						   "</tr>";
						$("#caveatee_tab tbody").append(caveatee_tr_str);
							}
							}
                       });
					});
				  }else{
					 if(searchon=='goorderdate')
						{
							  alert('GO order not found with '+goorderdate);
						}else{
							  alert(search_value +' not found in '+searchon);
						}
					
				  }
				}
			})//AJAX end
	 }//end if-else


	})
$("#res_clear").click(function(){
	$('#search_tab').find('tbody tr').remove();
    $('#caveat_tab').find('tbody tr').remove();
	$('#caveatee_tab').find('tbody tr').remove();
	$('#details_tab').find('tbody td').remove();
$('#details_tab').find('tbody th').remove();
})

$("#LinkCaveat").click(function(){
	var caveatid = link_caveatid;
	var applicantId = link_appId;
	var _token = $('input[name="_token"]').val();

	if(caveatid == ""){
		swal({
				title: "Select Caveat to Link",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
	}

	else{

		swal({
				title: "Are you sure to Link? Caveat ID:"+ caveatid + " with Application: " + applicantId,
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete)=> {
					if(willDelete){
						//alert('workign');
						$.ajax({
						type: 'post',
						dataType:'JSON',
						url: 'validateApplication',
						data: {_token:_token,applicantId:applicantId},
						success: function(data) {
							if (data.data[0].nextlistdate != null || data.data[0].lastlistdate != null)
							{
							swal({
								title: "Application is in hearing stage Caveat cannot be searched for this application",
								icon: "warning",
								showCancelButton: true,
								buttons: true,
								dangerMode: true,
								})
								.then(willDelete => {
									if (willDelete) {
										//swal("Deleted!", "Your imaginary file has been deleted!", "success");
										$("#caveatsearch").trigger("reset");
										link_caveatid = "";
										link_appId = "";
										$('#search_tab').find('tbody td').remove();
										$('#caveat_tab').find('tbody tr').remove();
										$('#caveatee_tab').find('tbody tr').remove();
										$('#details_tab').find('tbody td').remove();
										$("#caveat").hide();
										$("#caveator").hide();
										$("#caveatee").hide();
										}
								});

							} else if (data.data[0].caveatmatchdate != null)
						     {
								swal({
									title: "Caveat Number:"+ data.data[0].caveatid + " Already matched with this Application No" + data.data[0].applicationid,
									icon: "warning",
									showCancelButton: true,
									buttons: true,
									dangerMode: true,
								})
								.then(willDelete => {
									if (willDelete) {
									$("#caveatsearch").trigger("reset");
									link_caveatid = "";
									link_appId = "";
									$('#search_tab').find('tbody td').remove();
									$('#caveat_tab').find('tbody tr').remove();
									$('#caveatee_tab').find('tbody tr').remove();
									$('#details_tab').find('tbody td').remove();
									$("#caveat").hide();
									$("#caveator").hide();
									$("#caveatee").hide();
									}
								});
							 }else{
							$.ajax({
								type: 'post',
								dataType:'JSON',
								url: 'linkcaveat',
								data: {_token:_token,caveatid:caveatid,applicantId:applicantId},
								success: function(data) {
									//alert('Caveat successfully linked');
									swal({
                                                                        	title: "Caveat successfully linked",
	                                                                        icon: "success",
										showCancelButton: true,
	                                                                        buttons: true                                                                       
                                	                                });
									$("#caveatsearch").trigger("reset");
									link_caveatid = "";
									link_appId = "";
									$('#search_tab').find('tbody td').remove();
									$('#caveat_tab').find('tbody tr').remove();
									$('#caveatee_tab').find('tbody tr').remove();
									$('#details_tab').find('tbody td').remove();
									$("#caveat").hide();
									$("#caveator").hide();
									$("#caveatee").hide();
							//var len = data['data'].length;
							 //console.log(data);
									}
								})
						}
						}
						})

						}// end-if(will)
						//$("#searchvalue").empty();

		})//swal
	}
	//$("#search_on").reset();
	//$('input[name="caveat90"]').attr('checked',false);
})

})//doucment
</script>

</section>
</div>
@endsection
