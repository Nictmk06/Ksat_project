$(document).ready(function() {
	$("#hearingDate").css('pointer-events', 'none');
    $("#benchCode").css('pointer-events', 'none');
	$("#benchJudge").css('pointer-events', 'none');
    $("#postedfor").css('pointer-events', 'none');
	$("#courthall").css('pointer-events', 'none');
	
	
	$("#applSearch").click(function(){
    	if($("#applTypeName").val()=='')
		{
		  $('#applTypeName').parsley().removeError('applTypeName');
		  $('#applTypeName').parsley().addError('applTypeName', {message: "Select Application Type"});
		  return false;
		}
		else
		{
			$('#applTypeName').parsley().removeError('applTypeName');
		}
		if($("#applicationId1").val()=='')
		{
			$('#applicationId1').parsley().removeError('applicationId1');
			$('#applicationId1').parsley().addError('applicationId1', {message: "Enter Application No"});
			return false;
		}
		else
		{
			$('#applicationId1').parsley().removeError('applicationId1');
		}
		var modl_appltype_name = $("#applTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#applicationId1").val();
		var applicationId =applnewtype+'/'+modl_modl_applno;
		var flag='iadocument';
			$.ajax({
			type: 'POST',
			url: 'getApplication',
			data:  { "_token": $('#token').val(),application_id:applicationId,flag:flag},
			dataType: "JSON",
			success: function (json) {
				//to get last row of  json array
				/*var lastIndex =  json[json.length-1];
				$("#IASrNo").val((lastIndex.iasrno)+1);*/
					$('#example2').find('tbody tr').remove();
				    $(".nexthrdiv").hide();
			        $(".hearingdiv").hide();
				    $("#hearingDate").val('');
						$("#nextBench").val('');
							$("#nextHrDate").val('');
							$("#nextbenchJudge").val('');
							$("#nextPostfor").val('');
										$("#benchCode").val('');
										$("#benchJudge").val('');
										$("#postedfor").val('');
										$("#courthall").val('');
										$("#courtDirection").val('');
										$("#caseRemarks").val('');
									    $("#officenote").val('');
										$("#ordertypecode").val('');
										$("#applStatus").val('');
									//	$("#isnexthearing").val('');
	       if(json.length>0)
				{
					for (var i = 0; i < json.length; i++) {
						if(json[i].registerdate===null){
                        $("#applnRegDate").val('');
						}
						else
						{
						var dor = json[i].registerdate;
						var dor_split = dor.split('-');
						var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#applnRegDate").val(dateOfReg);	
					}
					if(json[i].applicationdate==null)
					{
						$("#dateOfAppl").val('');
					}
					else
					{ 
						var doa = json[i].applicationdate;
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#dateOfAppl").val(dateOfApp);
					}
					$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
				}
					var regdate_1  = $("#applnRegDate").val();
	  			$('#disposedDate').datepicker('setStartDate', regdate_1);
			//	getApplDetails(applicationId);
				getHearingDetails(applicationId);
				
			}
			else
			{
				swal({
				title: "Application Does Not Exist",
				icon: "error"
				})
				$("#applnRegDate").val('');
				$("#dateOfAppl").val('');
				$("#applTypeName").val('');
				$("#applCatName").val('');
				$("#applnSubject").val('');
				$("#applicationId1").val('');
			}
		}
	});

});

	$('.btnClear').click(function(){
	//var form = $(this).closest("form").attr('id');
	//$("#"+form).trigger('reset');

	$("#saveDailyHearing").val('Update');

	
	
	$("#updatedailyHearingForm").trigger('reset');

})
	
	
	$('input[name="isnexthearing"]').click(function() {
        if ($('input[name="isnexthearing"]').is(':checked')) {
			$(".nexthrdiv").show();
			$(".hearingdiv").show();
			$("#nextHrDate").attr('data-parsley-required',true);
			$("#nextBench").attr('data-parsley-required',true);
			$("#nextbenchJudge").attr('data-parsley-required',true);
			$("#nextPostfor").attr('data-parsley-required',true);
	 	} else {
			$(".nexthrdiv").hide();
			$(".hearingdiv").hide();
			$("#nextHrDate").attr('data-parsley-required',false);
			$("#nextBench").attr('data-parsley-required',false);
			$("#nextbenchJudge").attr('data-parsley-required',false);
			$("#nextPostfor").attr('data-parsley-required',false);
		}
	})

$("#applStatus").change(function(){
  		var status = $(this).val();
  		if(status==2 || status==4)
  		{
  			$("#disposeddatediv").show();
  			$("#nexthearingdiv").hide();
  		}
  		else
  		{
  			$("#nexthearingdiv").show();
  			$("#disposeddatediv").hide();
  		}
  		
  	})


$("#benchCode").change(function() {
   	    var text = $(this).val();
   	    $.ajax({
				type: 'post',
				url: "getBenchJudges",
				dataType:"JSON",
				data: {"_token": $('#token').val(),benchtype:text,display:''},
				success: function (json) {
					$('#benchJudge').find('option:not(:first)').remove();
					 for(var i=0;i<json.length;i++){
					 	 var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
	  						$('#benchJudge').append(option);
					 }
				}
			});
   		$("#nextBench").val(text).attr('selected');   
   		$('#nextBench').val(text).change();
  	});

$("#nextBench").change(function() {
   		 var text = $(this).val();
   		$.ajax({
				type: 'post',
				url: "getBenchJudges",
				dataType:"JSON",
				data: {"_token": $('#token').val(),benchtype:text,display:'Y'},
				success: function (json) {
					$('#nextbenchJudge').find('option:not(:first)').remove();
					 for(var i=0;i<json.length;i++){
					 	 var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
	  						$('#nextbenchJudge').append(option);
					 }
				}
			});
   		});

$("#benchJudge").change(function(){
		var judge = $(this).val();
  		$('#nextbenchJudge').val(judge).attr('selected',true);
		var hearingdate  = $("#hearingDate").val();
		var benchcode = $("#benchCode").val();
		var benchjudge = $(this).val();
		var docType = $("#applTypeName").val();
		var newdocType = docType.split('-');
		var applID = $("#applicationId").val();
		var newappl_id = newdocType[1]+'/'+applID;
       if(hearingdate!='')
		{			
			$("#hearingDate").parsley().removeError('hearingDate');
			$.ajax({
				type: 'post',
				url: "getHearingDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),hearingdate:hearingdate,benchjudge:benchjudge,applicationid:newappl_id},
				success: function (json) {
					if(json.length>0)
					{
						swal("Already Data Exists");
					
						var DHearingdate =  json[0].hearingdate;
						var arr =DHearingdate.split('-');
						if(json[0].casestatus=='4'|| json[0].casestatus=='2')
						{
							var Ddispose =  json[0].disposeddate;
							var arr2 =Ddispose.split('-');
							var disposeddate =arr2[2]+'-'+arr2[1]+'-'+ arr2[0]; 
							$("#disposeddatediv").show();
							$("#disposedDate").val(disposeddate);
						}
						else
						{
							$("#disposedDate").val('');
							$("#disposeddatediv").hide();
						}
						if(json[0].nextdate!=null)
						{
							$('#isnexthearing').prop('checked',true);
							$('.nexthrdiv').show();
							$(".hearingdiv").show();
						    var NDate =  json[0].nextdate;
							var arr1 =NDate.split('-');
							var nexthrdate =arr1[2]+'-'+arr1[1]+'-'+ arr1[0]; 
							$("#nextBench").val(json[0].nextbenchtypename);
							$("#nextHrDate").val(nexthrdate);
							$("#nextbenchJudge").val(json[0].nextbenchcode);
							$("#nextPostfor").val(json[0].nextpurposecode);
						}
						else
						{
							$('#isnexthearing').prop('checked',false);
							$('.nexthrdiv').hide();
							$(".hearingdiv").hide();
							$("#nextBench").val('');
							$("#nextHrDate").val('');
							$("#nextbenchJudge").val('');
							$("#nextPostfor").val('');
						}
					
						$("#saveDailyHearing").val('Update');
						$("#postedfor").val(json[0].purposecode);
						$("#courthall").val(json[0].courthallno);
						$("#courtDirection").val(json[0].courtdirection);
						$("#officenote").val(json[0].courtdirection);
						$("#ordertypecode").val(json[0].orderpassed);
						$("#applStatus").val(json[0].casestatus);
						$("#caseRemarks").val(json[0].caseremarks);
						$("#benchjudge").val(json[0].benchcode);
						$("#ordertypecode").val(json[0].ordertypecode);
						$("#hearingCode").val(json[0].hearingcode);
						var applicationid = newappl_id;
						getHearing(hearingdate,applicationid,benchjudge);
			}
			}
	});
   }
	else
		{
			$("#hearingDate").parsley().removeError('hearingDate');
			 $('#hearingDate').parsley().addError('hearingDate', {
								message: "Enter  hearingDate"
								});
		}
	})

$("#hearingDate").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var startDate = new Date(selected.date.valueOf());
		 startDate.setDate(startDate.getDate() + 1);
    $('#nextHrDate').datepicker('setDate', startDate);
	$('#nextHrDate').datepicker('setStartDate', startDate);
	}).on('clearDate', function(selected) {
		$('#nextHrDate').datepicker('setStartDate', null);
	});

$("#postedfor").change(function() {
   		 var id = $(this).val();
  		$("#nextPostfor").val(id).attr('selected');
  	});

function getHearing(hearingdate,applicationid,benchjudge)
{
	$("#myTable2").show();
					$.ajax({
						type: 'post',
						url: "getIABasedOnHearing",
						dataType:"JSON",
						data: {"_token": $('#token').val(),hearingdate:hearingdate,applicationid:applicationid,benchjudge:benchjudge},
						success: function (json) {				
						 $("#myTable2").find('tbody tr').remove();
							var count = 1;
							$.each(json, function(index, obj) {
							//$("#hiddenstatus").val(obj.statuscode);
							var row = $('<tr>');
							var IADocdate =  obj.iafillingdate;
							var IADocReg=  obj.iaregistrationdate;
							var arr =IADocdate.split('-');
							var arr2 =IADocReg.split('-');
						//	console.log(obj.statuscode);
							row.append('<td>' +obj.documentname + '</td>');
							row.append('<td>'+ obj.iano + '</td>');
							//row.append('<td>'+ obj.ianaturedesc + '</td>');
							row.append('<td>'+ obj.iaprayer + '</td>');
							row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
							row.append('<td>' + arr2[2]+'-'+arr2[1]+'-'+ arr2[0] + '</td>');
							row.append('<td ><a data-value="'+obj.iano+'-'+obj.applicationid+'-'+obj.statuscode+'" class="statusClick">' + obj.statusname + '</a></td>');
							row.appendTo('#myTable2');
							count++;
								})
							$(".statusClick").click(function(){
								    var form = $(this).closest("form").attr('id');
								    var formaction = $(this).closest("form").attr('action');
								    $("#"+form).parsley().validate();
									if ($("#"+form).parsley().isValid())
								 {
								var value   = $(this).attr('data-value');
								var valuesplit = value.split('-');
								$("#modal-status").modal('show');
								$("#modal_appl_id").val(valuesplit[1]);
								$("#iastatus").val(valuesplit[2]);
								$("#modal_srno").val(valuesplit[0]);
							}
							})
								}
						});
				}
				
				
$("#saveIAStatus").click(function(){
	var applicationid = $("#modal_appl_id").val();
	var iasrno = $("#modal_srno").val();
	var status = $("#iastatus").val();
	var hearingdate  = $("#hearingDate").val();
	var benchjudge = $("#benchJudge").val();
	var courthallno = $("#courthall").val();
	var caseremarks = $("#caseRemarks").val();
	$.ajax({
			type: 'post',
			url: 'updateIAStatus',
			data: 
			{"_token": $('#token').val()
			,applicationid:applicationid
			,iasrno:iasrno,
			status:status,
			hearingdate:hearingdate,
			benchjudge:benchjudge,courthallno:courthallno,caseremarks:caseremarks},
			success: function (data) {
				if(data.status=='success')
				{
					$("#modal-status").modal('hide');
					 getHearing(hearingdate,applicationid,benchjudge);

				}
				else
				{
					$("#modal-status").modal('show');
				}
			}
		});
})


$("#saveDailyHearing").click(function(e){
	var applStatus = $("#applStatus").val();
	if(applStatus == 2){
		var disposeddate = $("#disposedDate").val();
		if(disposeddate == ''){
			alert('select Disposed Date ');
			return false;
		}
		
	}
		e.preventDefault();
		swal({
				title: "Are you sure to Update?",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
		 if (willDelete) {
		   var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		   var application_id = $("#applicationId").val();
	    $("#"+form).parsley().validate();
	    if ($("#"+form).parsley().isValid())
	    {
			$.ajax({
						type: 'post',
						url: formaction,
						data: $('#'+form).serialize(),
						success: function (data) {
						if(data.errors)
								{
									var errorlist = data.errors;
										for (var i = 0; i < errorlist.length; i++) {
										$("#errorlist").empty();
										$("#errorlist").append("<li >"+errorlist[i]+"</li>");
										$("#modal-default").modal('show');
										}
								}
								if(data.status=="sucess")
								{
									
									var applID = $("#applicationId").val();
									
									$("#saveDailyHearing").val('Update');
										$("#hearingDate").val('');
										$("#benchCode").val('');
										$("#benchJudge").val('');
										$("#postedfor").val('');
										$("#courthall").val('');
										$("#courtDirection").val('');
										$("#caseRemarks").val('');
									    $("#officenote").val('');
										$("#ordertypecode").val('');
										$("#applStatus").val('');
										$("#isnexthearing").val('');
									
									swal({
										title: data.message,
										icon: "success"
									})
									;
									$("#myTable2").hide();
									getHearingDetails(applID);
								}
								else if(data.status=="fail")
								{
									swal({
									title: data.message,
									icon: "error"
									})
								}
						}
					});
		}
		}
		else
		{
			return false;
		}

	});
	})

function getHearingDetails(applicationId)
{
	$.ajax({
	type: 'post',
	url: "getHearingDet",
	dataType:"JSON",
	data: {"_token": $('#token').val(),application_id:applicationId},
	success: function (json) {
		var regdate_1  = $("#applnRegDate").val();
		$('#hearingDate').datepicker('setStartDate', regdate_1);
		$('#example2').find('tbody tr').remove();
		var count = 1;
		$.each(json, function(index, obj) {
		var row = $('<tr>');
		var DHearingdate =  obj.hearingdate;
		var arr =DHearingdate.split('-');
		if(obj.nextdate!=null)
		{
			var nextDate =  obj.nextdate;
		var arr2 =nextDate.split('-');
		var nexthrdate =arr2[2]+'-'+arr2[1]+'-'+ arr2[0]; 
	}
	else
	{
		var nexthrdate='--';
	}

	row.append('<td><a href="#" data-value="'+obj.hearingdate+'|'+obj.benchcode+'|'+obj.applicationid+'" class="dhclick" >' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</a></td>');
	row.append('<td>' +obj.listpurpose+ '</td>');
	row.append('<td>' + obj.courtdirection + '</td>');
	row.append('<td>' + obj.officenote + '</td>');
	row.append('<td>' +nexthrdate+ '</td>');
	$('#example2').append(row)
	count++;
	})
	//to get each record data 
	$(".dhclick").click(function(){
		var value = $(this).attr('data-value');
		var  newvalue  =  value.split('|');
		var hearingDate =  newvalue[0];
		var arr =hearingDate.split('-');
		var hearing = arr[2]+'-'+arr[1]+'-'+ arr[0];
		var applicationid = newvalue[2];
		var benchjudge = newvalue[1];
		
		$.ajax({
				type: 'post',
				url: "getHearingDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),hearingdate:hearing,benchjudge:benchjudge,applicationid:applicationid},
				success: function (json) {
					if(json.length>0)
					{
					    $('#saveDailyHearing').removeAttr("disabled");		
						$("#disposeddatediv").hide();
	  					$("#nexthearingdiv").show();
						var DHearingdate =  json[0].hearingdate;
						var arr = DHearingdate.split('-');					
						if(json[0].nextdate!=null)
						{
							$('#isnexthearing').prop('checked',true);
							$('.nexthrdiv').show();
							$(".hearingdiv").show();
							var NDate =  json[0].nextdate;
							var arr1 =NDate.split('-');
							var nexthrdate =arr1[2]+'-'+arr1[1]+'-'+ arr1[0]; 
							$("#nextBench").val(json[0].nextbenchtypename);
							$("#nextHrDate").val(nexthrdate);
							$("#nextbenchJudge").val(json[0].nextbenchcode);
							$("#nextPostfor").val(json[0].nextpurposecode);
						}
						else
						{
							$('#isnexthearing').prop('checked',false);
							$('.nexthrdiv').hide();
							$(".hearingdiv").hide();
							$("#nextBench").val('');
							$("#nextHrDate").val('');
							$("#nextbenchJudge").val('');
							$("#nextPostfor").val('');
						}
						$("#saveDailyHearing").val('Update');
						$("#postedfor").val(json[0].purposecode);
						$("#courthall").val(json[0].courthallno);
						$("#courtDirection").val(json[0].courtdirection);
						$("#officenote").val(json[0].officenote);
						$("#ordertypecode").val(json[0].orderpassed);
						$("#applStatus").val(json[0].casestatus);
						$("#caseRemarks").val(json[0].caseremarks);
						$("#benchCode").val(json[0].benchtypename);
						$("#benchJudge").val(json[0].benchcode);
						$("#ordertypecode").val(json[0].ordertypecode);
						$("#hearingCode").val(json[0].hearingcode);
						if(json[0].casestatus == '2'){
							$("#disposeddatediv").show();
	  						$("#nexthearingdiv").hide();
							$("#disposedDate").val(json[0].disposeddate);
							$('#saveDailyHearing').attr('disabled','disabled');							
						}
						$("#disposedDate").val(json[0].disposeddate);
						$("#applicationId").val(json[0].applicationid);
						$("#hearingDate").val(arr[2]+'-'+arr[1]+'-'+ arr[0]);
						var hearingdate = arr[2]+'-'+arr[1]+'-'+ arr[0];
						//var applicationid = newappl_id;
						getHearing(hearingdate,applicationid,benchjudge);
					}
				}
				});
	})
	}
	});
}

});