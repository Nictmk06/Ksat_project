$(document).ready(function() {
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
		if($("#applicationId").val()=='')
		{
			$('#applicationId').parsley().removeError('applicationId');
			$('#applicationId').parsley().addError('applicationId', {message: "Enter Application No"});
			return false;
		}
		else
		{
			$('#applicationId').parsley().removeError('applicationId');
		}
		var modl_appltype_name = $("#applTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#applicationId").val();
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
	  			$('#IAFillingDate').datepicker('setStartDate', regdate_1);
	  			$('#disposedDate').datepicker('setStartDate', regdate_1);
				getApplDetails(applicationId);
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
				$("#applicationId").val('');
			}
		}
	});

});

$("#filledby").change(function(){
	var modl_appltype_name = $("#applTypeName").val();
	var newtype = modl_appltype_name.split('-');
	var applnewtype = newtype[1];
	var modl_modl_applno = $("#applicationId").val();
	var applicationId =applnewtype+'/'+modl_modl_applno;
	var flag = $("#filledby").val();
	if(flag=='O'){
		$(".otherdiv").css('display', 'inline');
         $(".appresdiv").css('display', 'none');
		 $("#filledbynameother").val('');
		 $("#filledbynameother").attr('data-parsley-required', true);
		 $("#filledbyname").attr('data-parsley-required', false);
	}
	else{
		 $(".otherdiv").css('display', 'none');
		 $(".appresdiv").css('display', 'inline');
		 $("#filledbynameother").val('');
		 $("#filledbynameother").attr('data-parsley-required', false);
		 $("#filledbyname").attr('data-parsley-required',true );
	$.ajax({
			type: 'post',
			url: 'getApplRespondant',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId,flag:flag},
			success: function (data) {
				$("#filledbyname").find('option').remove();
				if(flag=='A')
				{
					for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'">'+data[i].applicantname+'</option>';
  						$('#filledbyname').append(option);
				 }}
				else if(flag=='R'){
					for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+'R'+'-'+data[i].respondsrno+'">'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
				 }
				}}
		});
	}
	})

$("#IAFillingDate").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var startDate = new Date(selected.date.valueOf());
		$('#IARegistrationDate').datepicker('setStartDate', startDate);
	}).on('clearDate', function(selected) {
		$('#IARegistrationDate').datepicker('setStartDate', null);
	});

$("#documentTypeCode").change(function(){
			var value = $(this).val();
			var typesplit = value.split('-');
			if(typesplit[0]!=1)
			{
				$("#naturediv").hide();
				$("#IANatureCode").attr('data-parsley-required',false);
			}
			else 
			{
				$("#naturediv").show();
				$("#IANatureCode").attr('data-parsley-required',true);
				}
			$("#IASrNo").val('0');
})

function getApplDetails(applicationId)
{
	var flag='iadocument';
	$.ajax({
	type: 'post',
	url: "getIADocAppl",
	dataType:"JSON",
	data: {"_token": $('#token').val(),application_id:applicationId,flag:flag},
	success: function (json) {
		$('#myTable').find('tbody tr').remove();
		var count = 1;
		$.each(json, function(index, obj) {
		var row = $('<tr>');
		var IADocdate =  obj.iafillingdate;
		var IADocReg=  obj.iaregistrationdate;
		var arr =IADocdate.split('-');
		var arr2 =IADocReg.split('-');
		row.append('<td>' +obj.documentname + '</td>');
		// if(obj.iastatus!=1 || obj.iano=='IA/1')
		//{
		//	row.append('<td>' + obj.iano + '</td>');
		//}
		//else
		//{ 
			
			row.append('<td><a href="#" data-value="'+obj.iano+'-'+obj.applicationid+'-'+obj.documenttypecode+'" class="iaClick" >' + obj.iano + '</a></td>');
	//	}
		row.append('<td>'+obj.ianaturedesc+'</td>');
		row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
		row.append('<td>' + arr2[2]+'-'+arr2[1]+'-'+ arr2[0] + '</td>');
		row.append('<td >' + obj.statusname + '</td>');
		row.appendTo('#myTable');
		count++;
	})
		

	$(".iaClick").click(function(){
			$("#sbmt_ia").val('U');
           	$("#saveIA").val('Update List');
										var application_id  = $(this).attr('data-value');
										//console.log(application_id);
										var applsplit = application_id.split('-');
										
										var IASrrno = applsplit[0];
										var doctype = applsplit[2];
										console.log(IASrrno);
										//$("#doctype").val(applsplit[2]);
										//console.log(doctype+'---'+IASrrno);
										$("#IASrNo").val(applsplit[0]);
										$.ajax({
										type: 'post',
										url: "getIADocApplSerial",
										dataType:"JSON",
										data: {"_token": $('#token').val(),IASrrno:IASrrno,applicationid:applsplit[1],doctype:doctype},
										success: function (json) {
										console.log(json);
										for(var i=0;i<json.length;i++){
											var IADocdate = json[i].iafillingdate;
											var IADocReg=  json[i].iaregistrationdate;
											var arr =IADocdate.split('-');
											var arr2 =IADocReg.split('-');
											var newapplid = json[i].applicationid.substring(3);
									$("#documentno").val(json[i].appindexref);
											$("#applicationId").val(newapplid);
											$("#documentTypeCode").val(json[i].documenttypecode+'-'+json[i].lsla);
											$("#IANo").val(json[i].iano);
										if(json[i].documenttypecode==1)
											{
												$("#naturediv").show();
												$("#IANatureCode").val(json[i].ianaturecode);
											}
											else
											{
												$("#naturediv").hide();
												$("#IANatureCode").val("");
											}
											$("#IAFillingDate").val( arr[2]+'-'+arr[1]+'-'+ arr[0]);
											$("#IARegistrationDate").val( arr2[2]+'-'+arr2[1]+'-'+ arr2[0]);
										    var filledby = json[i].partysrno;
											$("#filledby").val(json[i].filledby);
											var flag=$("#filledby").val();
											if(flag == 'O'){
												$("#filledbynameother").val(json[i].submitby);
												$(".otherdiv").css('display', 'inline');
												$(".appresdiv").css('display', 'none');
											}
											else{
												getApplRes(applsplit[1],flag,filledby);
											    $(".otherdiv").css('display', 'none');
												$(".appresdiv").css('display', 'inline');
											}
											$("#IANo").attr('readonly',true);
											$("#IASrNo").val(json[i].iano);
											$("#IAPrayer").val(json[i].iaprayer);
											$("#startpage").val(json[i].startpage);
											$("#endpage").val(json[i].endpage);
										}
										}});
									})
		          }
	});
}

function getApplRes(applicationid,flag,filledby)
{
	$.ajax({
			type: 'post',
			url: 'getApplRespondant',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationid,flag:flag},
			success: function (data) {
			$("#filledbyname").find('option').remove();
				
			if(flag=='A')
				{
					for(var i=0;i<data.length;i++){
						if(data[i].applicantsrno==filledby)
						{
							var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'" selected>'+data[i].applicantname+'</option>';
  							$('#filledbyname').append(option);
						}
						else
						{
							var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'">'+data[i].applicantname+'</option>';
  							$('#filledbyname').append(option);
						}			 	 
				 }
				}
				else if(flag=='R'){					
					for(var i=0;i<data.length;i++){
					if(data[i].respondsrno==filledby)
					{
				 	 var option = '<option value="'+'R'+'-'+data[i].respondsrno+'" selected>'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
  					}
  					else
  					{
  						var option = '<option value="'+'R'+'-'+data[i].respondsrno+'">'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
  					}
				 }
				}}
		});
}


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
						$("#sbmt_da").val('U');
						var DHearingdate =  json[0].hearingdate;
						var arr =DHearingdate.split('-');
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
						$("#orderPassed").val(json[0].orderpassed);
						$("#applStatus").val(json[0].casestatus);
						$("#caseRemarks").val(json[0].caseremarks);
						$("#benchCode").val(json[0].benchtypename);
						$("#benchJudge").val(json[0].benchcode);
						$("#orderPassed").val(json[0].ordertypecode);
						$("#hearingCode").val(json[0].hearingcode);
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

$('.btnClear').click(function(){
	//var form = $(this).closest("form").attr('id');
	//$("#"+form).trigger('reset');
	$("#documentTypeCode").val('');
	$("#IAFillingDate").val('');
	$("#IANatureCode").val('');
	$("#IARegistrationDate").val('');
	$("#IANo").val('');
	$("#IAPrayer").val('');
	$("#sbmt_ia").val('A');
	$("#saveIA").val('Save');
	$("#saveDailyHearing").val('Save');
	$("#startpage").val('');
	$("#endpage").val('');
	$("#filledby").val('');
	$("#filledbyname").val('');	
})

$("#saveIA").click(function(e){
		e.preventDefault();
		swal({
				title: "Are you sure to save?",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
			   if (willDelete) {
			   var form = $(this).closest("form").attr('id');
			   var formaction = $(this).closest("form").attr('action');
			   var appltype =$("#applTypeName").val();
			   var iasrno = $("#IASrNo").val();
			   var applType1 = appltype.split('-');
			   var applicationId = applType1[1]+'/'+$("#applicationId").val();
		 
		   	 $("#"+form).parsley().validate();
		     if ($("#"+form).parsley().isValid())
    		{
    			var iano = $("#IANo").val();
		  		var docType = $("#documentTypeCode").val();
		   		var newdocType = docType.split('-');
							$.ajax({
										type: 'post',
										url: formaction,
										data: $('#'+form).serialize(),
										success: function (data) {
										if(data.status=="sucess")
										{
									    getApplDetails(applicationId);
										swal({
										title: data.message,
										icon: "success"
										})
										$("#documentTypeCode").val('');
										$("#IAFillingDate").val('');
										$("#IANatureCode").val('');
										$("#IARegistrationDate").val('');
										$("#IAPrayer").val('');
										$("#sbmt_ia").val('A');
										$("#saveIA").val('Save');
									 
										$("#filledby").val('');
										$("#filledbyname").val('');
										
										}
										else if(data.status=="fail")
										{
										swal({
										title: data.message,
										icon: "error"
										   })
										}}
										});
		}else
			{
				return false;
			}
		}
      });
	})
});
