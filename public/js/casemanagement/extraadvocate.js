$(document).ready(function() {

$("#additionalendno").focusout(function() {
		var startno = $("#additionalstno").val();
		var endNo = $('#additionalendno').val();

		if (parseInt($('#additionalendno').val()) < parseInt($('#additionalstno').val())) {
			//console.log("yes");
			$('#additionalendno').parsley().removeError('customValidationId');
			$('#additionalendno').parsley().addError('customValidationId', {
				message: "Application End No Should be greater than or equal to start no."
			});
			return false;
		} else { //console.log("no");
			$('#additionalendno').parsley().removeError('customValidationId');
		}
	})

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
	var flag2 = $("#flag").val();

	var flag='application';
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
					{ var doa = json[i].applicationdate;
						
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#dateOfAppl").val(dateOfApp);
					}
	
					$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
					
					if(flag2=='extraadv')
					{

						getExtraadvocate(applicationId);
					}
					else if(flag2=='ARstatus')
					{
						$.ajax({
						type: 'post',
						url: "getLastSerialNo",
						dataType: "JSON",
						data: {
						"_token": $('#token').val(),
						application_id: applicationId
						},
						success: function(json1) {
						//console.log(json1);
						if (json1 == null) {
						$("#applicantStartSrNo").val('0');
						} else {
						$("#applicantStartSrNo").val(parseInt(json1.applicantsrno)+1);
						}



						}
						});
						$.ajax({
						type: 'post',
						url: "getRsLastSerialNo",
						dataType: "JSON",
						data: {
						"_token": $('#token').val(),
						application_id: applicationId
						},
						success: function(json1) {
						//console.log(json1.applicantSrNo);
						//$("#applicantStartSrNo").val('');
						if (json1 == null) {
						$("#resStartNo").val('0');
						} else {
						$("#resStartNo").val(parseInt(json1.respondsrno)+1);
						}


						}
						});
					}
					else if(flag2=='groupNo')
					{
						$("#applEndno").val(json[i].applicationtosrno);
						$("#applStartno").val(json[i].applicationsrno);
						$("#applYear").val(json[i].applicationyear);
						getGroupDetails(applicationId);
					}

					
				}
				
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

function getExtraadvocate(applicationId)
{
	var regdate_1  = $("#applnRegDate").val();
	$('#enrolleddate').datepicker('setStartDate', regdate_1);
$.ajax({
type: 'post',
url: "getExtraAdvocate",
dataType:"JSON",
data: {"_token": $('#token').val(),applicationid:applicationId},
success: function (json) {
			$("#myTable").find('tbody tr').remove();
			var count = 1;
			$.each(json, function(index, obj) {
			var row = $('<tr>');
			var EnrollDate =  obj.enrolleddate;
			var arr =EnrollDate.split('-');
			row.append('<td>' +obj.advocatetype +'-'+obj.name+ '</td>');
			row.append('<td><a href="#" data-value="'+obj.extraadvcode+'-'+obj.applicationid+'-'+obj.enrollmentno+'" class="extraClick" >' +obj.enrollmentno + '</a></td>');
			row.append('<td>' + obj.advocatename + '</td>');
			row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
			row.append('<td>'+ obj.active + '</td>');
			row.append('<td>'+ obj.display + '</td>');
			row.append('<td >' + obj.remarks + '</a></td>');
			row.appendTo('#myTable');
			count++;
			})
			$(".extraClick").click(function(){
			var value   = $(this).attr('data-value');
		var valuesplit = value.split('-');
		var applicationid = valuesplit[1];
		var id = valuesplit[0];
		var enrollno = valuesplit[2];
		$.ajax({
				type: 'post',
				url: "getExtraAdvDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),applicationid:applicationid,id:id,enrollno:enrollno},
				success: function (json) {
					
						$("#sbmt_adv").val('U');
						$("#saveADV").val('Update');
						var enrolldate =  json[0].enrolleddate;
						var arr =enrolldate.split('-');
						
						$("#filledby").val(json[0].advocatetype);
						$("#advBarRegno").val(json[0].enrollmentno);
						$("#remarks").val(json[0].remarks);
						 $('input[name="advocateStatus"][value="' + json[0].active + '"]').prop('checked', true);
						 $('input[name="isCauseList"][value="' + json[0].display + '"]').prop('checked', true);
						$("#enrolleddate").val(arr[2]+'-'+arr[1]+'-'+ arr[0]);
						$("#extraadvcode").val(json[0].extraadvcode);
						$("#applid").val(json[0].applicationid);
						var filledby = json[0].partysrno;
						$("#filledby").val(json[0].advocatetype);
						var flag=$("#filledby").val();
						getApplRes(applicationid,flag,filledby);
						

						}
					});
		})
}

});

}
function getApplRes(applicationid,flag,filledby)
{
	//console.log(applicationid+'----'+flag);
	$.ajax({
			type: 'post',
			url: 'getApplRespondant',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationid,flag:flag},
			success: function (data) {

				
				
				if(flag=='A')
				{
					
					for(var i=0;i<data.length;i++){
						//$("#filledbyname").find('option').remove();
						if(data[i].applicantsrno==filledby)
						{
							var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'-'+data[i].applicantname+'" selected>'+data[i].applicantname+'</option>';
  							$('#filledbyname').append(option);
						}
						else
						{
							var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'-'+data[i].applicantname+'">'+data[i].applicantname+'</option>';
  							$('#filledbyname').append(option);
						}

				 	 
				 }
				}
				else if(flag=='R'){
					
					for(var i=0;i<data.length;i++){
					//$("#filledbyname").find('option').remove();
					if(data[i].respondsrno==filledby)
					{
				 	 var option = '<option value="'+'R'+'-'+data[i].respondsrno+'-'+data[i].respondname+'" selected>'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
  					}
  					else
  					{
  						var option = '<option value="'+'R'+'-'+data[i].respondsrno+'-'+data[i].respondname+'">'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
  					}
				 }
				}

				 
			}
		});
	

}
	
	$("#filledby").change(function(){
	var modl_appltype_name = $("#applTypeName").val();
	var newtype = modl_appltype_name.split('-');
	var applnewtype = newtype[1];
	var modl_modl_applno = $("#applicationId").val();
	var applicationId =applnewtype+'/'+modl_modl_applno;
	var flag = $("#filledby").val();
	var value = $("#advBarRegno").val();
	if(flag=='R')
	{
		$.ajax({
			type: 'post',
			url: "getResAdv",
			dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				application_id: applicationId
			},
			success: function(json1) {
				$("#browsers1").empty();
				for (var i = 0; i < json1.length; i++) {
					$("#browsers1").append('<option value=' + json1[i].advocateregno + '>' + json1[i].advocatename + '</option>');
				}



			}
		});
	}
	else
	{
		$.ajax({
			type: 'POST',
			url: 'advRegNo',
			data: {
				"_token": $('#token').val(),
				value: value
			},
			dataType: "JSON",
			success: function(json) {

				for (var i = 0; i < json.length; i++) {
					$(".advancedSearch7 .selection7").text(json[i].nametitle);
					$(".title_sel7").css('display', 'none');
					$("#respAdvTitle").val(json[i].nametitle);
					$("#respAdvName").val(json[i].advocatename);
					$("#resadvaddr").val(json[i].advocateaddress);
					$("#resadvtaluk").attr('disabled', false);
					$("#resadvdistrict").attr('disabled', false);
					$("#resadvtaluk").empty();
					$("#resadvdistrict").empty();
					$("#resadvtaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
					$("#resadvdistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '</option>');
					$("#resadvpincode").val(json[i].pincode);
				}
			}
		});
	}
	/*$.ajax({
			type: 'post',
			url: 'getAdvocateUnique',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId},
			success: function (data) {

				$('#browsers1').empty();
					for(var i=0;i<data.length;i++){
						 var option = '<option value="'+data[i].advocateregno+'">'+data[i].advocateregno+"-"+data[i].advocatename+'</option>';

							$('#browsers1').append(option);
					}
			}
		});*/
	$.ajax({
			type: 'post',
			url: 'getApplRespondant',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId,flag:flag},
			success: function (data) {

				
				//$('#filledbyname').find('option:not(:first)').remove();
				$("#filledbyname").find('option').remove();
				if(flag=='A')
				{
					for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'-'+data[i].applicantname+'">'+data[i].applicantname+'</option>';
  						$('#filledbyname').append(option);
				 }
				}
				else if(flag=='R'){
					for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+'R'+'-'+data[i].respondsrno+'-'+data[i].respondname+'">'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
				 }
				}

				 
			}
		});
	
})
//add advocate 
$("#saveADV").click(function(e){
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
		  
		   var applType1 = appltype.split('-');
		   var applicationId = applType1[1]+'/'+$("#applicationId").val();
		 
		   	    
		  // console.log(form+formaction);
		  $("#"+form).parsley().validate();
		    if ($("#"+form).parsley().isValid())
	
    		{
    			
		    	
		  	

										$.ajax({
										type: 'post',
										url: formaction,
										data: $('#'+form).serialize(),
										success: function (data) {
										//console.log(data);
										/*if(data.errors)
										{
											
										var errorlist = data.errors;
										for (var i = 0; i < errorlist.length; i++) {
										$("#errorlist").empty();
										$("#errorlist").append("<li >"+errorlist[i]+"</li>");
										$("#modal-default").modal('show');
										}

										}*/
										if(data.status=="exists")
										{
											swal({
										title: data.message,

										icon: "error"

										})
										}
										if(data.status=="sucess")
										{
										getExtraadvocate(applicationId);
											swal({
										title: data.message,

										icon: "success"

										})
										
										$("#filledbyname").val('');
										//$("#IANo").val('');
										$("#enrolleddate").val('');
										$("#sbmt_adv").val('A');
										$("#saveADV").val('Save');
										$("#remarks").val('');
										$("#isCauseList").val('');
										$("#filledby").val('');
										$("#filledbyname").val('');
										$("#advBarRegno").val('');
										$("#advocateStatus").val('');
										$("#enrolleddate").val('');
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
							
		   		
		  
		   
		}else
	{
		return false;
	}
	}
	
});
	})

$('.btnClear').click(function(){
	//var form = $(this).closest("form").attr('id');
	//$("#"+form).trigger('reset');
	
	$("#advBarRegno").val('');
	$(".isCauseList").val('');
	$(".advocateStatus").val('');
	$("#remarks").val('');
	$("#enrolleddate").val('');
	$("#filledby").val('');
	$("#filledbyname").val('');
	
	

})
$("#clearApplicant").click(function(){
	$("#addAppResDet").trigger('reset');
})
$("#clearRespondant").click(function(){
	$("#addResDet").trigger('reset');
})
$("#statusfor").change(function(){
		var modl_appltype_name = $("#applTypeName").val();
	var newtype = modl_appltype_name.split('-');
	var applnewtype = newtype[1];
	var modl_modl_applno = $("#applicationId").val();
	var applicationId =applnewtype+'/'+modl_modl_applno;
	$(".applApplId").val(applicationId);
	$(".resApplId").val(applicationId);
	var flag = $("#statusfor").val();
	var regdate_1  = $("#applnRegDate").val();
	$('#statusdate').datepicker('setStartDate', regdate_1);
	$.ajax({
			type: 'post',
			url: 'getApplRespondant',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId,flag:flag},
			success: function (data) {

				
				//$('#filledbyname').find('option:not(:first)').remove();
				
				if(flag=='A')
				{
					//console.log("sdfsdf");
						$("#myTable4").find('tbody tr').remove();
						var count = 1;
						$.each(data, function(index, obj) {
						var row = $('<tr>');
						if(obj.statuschangedate==null)
						{
							var statuschangedate = '0';
						}
						else
						{
							var statusdate =  obj.statuschangedate;
							var arr =statusdate.split('-');
							var statuschangedate = arr[2]+'-'+arr[1]+'-'+ arr[0];
							
						}
						if(obj.remarks=='null')
						{
							
							var remarks = '0';
						}
						else
						{
							var remarks = obj.remarks;
							
						}
						row.append('<td><a href="#" data-value="'+obj.applicantsrno+'|'+obj.applicationid+'|'+obj.ismainparty+'|'+statuschangedate+'|'+obj.partystatus+'|'+obj.applicantstatus+'|'+obj.remarks+'" class="applicantClick" >'+obj.applicantsrno+ '</a></td>');
						row.append('<td>' +obj.applicantname + '</td>');
						row.append('<td>'+obj.applicantstatus+'</td>');
						row.append('<td>'+obj.ismainparty+'</td>');
						row.append('<td>'+obj.partystatus + '</td>');
						row.append('<td>'+ statuschangedate+ '</td>');
						row.append('<td >' + remarks + '</a></td>');
						row.appendTo('#myTable4');
						count++;
						})
						$(".applicantClick").click(function(){
							var value = $(this).attr('data-value');
							var split = value.split('|');
							//console.log(split[2]);
							$("#modal_appl_id").val(split[1]);

							$("#modal_srno").val(split[0]);
							$("#modal_flag").val(flag);
							$("input[name=applStatus][value=" + split[5] + "]").prop('checked', true);
							$("input[name=isMainParty][value=" + split[2] + "]").prop('checked', true);
							//$(".applStatus").val(split[5]).attr('checked',true);
							///$(".isMainParty").val(split[2]).attr('checked',true);
							$("#partystatus").val(split[4]);
							$("#statusdate").val(split[3]);
							$("#remarks").val(split[6]);
							$("#modal-status").modal('show');
						})
						$("#saveApllStatus").click(function(){
	 var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		   
		   
		 
		   	    
		  // console.log(form+formaction);
		  $("#"+form).parsley().validate();
		    if ($("#"+form).parsley().isValid())
	
    		{
    			
		    	
		  	

										$.ajax({
										type: 'post',
										url: formaction,
										data: $('#'+form).serialize(),
										success: function (data) {
											if(data.status=='sucess')
											{
												swal({
												title: "Status Change Successfull",

												icon: "success"
												})
												$("#modal-status").modal('hide');
												$('#statusfor').val('A').change();

											}
										}
									});
				}
})
				}
				else if(flag=='R'){
					
					$("#myTable4").find('tbody tr').remove();
						var count = 1;
						$.each(data, function(index, obj) {
						var row = $('<tr>');

						if(obj.statuschangedate==null)
						{
							var statuschangedate = '0';
						}
						else
						{
							var statusdate =  obj.statuschangedate;
							var arr =statusdate.split('-');
							var statuschangedate = arr[2]+'-'+arr[1]+'-'+ arr[0];
							
						}
						if(obj.remarks=='null')
						{
							
							var remarks = '0';
						}
						else
						{
							var remarks = obj.remarks;
							
						}
					
						row.append('<td><a href="#" data-value="'+obj.respondsrno+'|'+obj.applicationid+'|'+obj.ismainrespond+'|'+statuschangedate+'|'+obj.partystatus+'|'+obj.respondstatus+'|'+obj.remarks+'" class="respondantClick" >'+obj.respondsrno+ '</a></td>');
						row.append('<td>' +obj.respondname + '</td>');
						row.append('<td>'+obj.respondstatus+'</td>');
						row.append('<td>'+obj.ismainrespond+'</td>');
						row.append('<td>'+obj.partystatus + '</td>');
						row.append('<td>'+ statuschangedate+ '</td>');
						row.append('<td >' + remarks + '</a></td>');
						row.appendTo('#myTable4');
						count++;
						})
						$(".respondantClick").click(function(){
						var value = $(this).attr('data-value');
							var split = value.split('|');
							//console.log(split[2]);
							$("#modal_appl_id").val(split[1]);

							$("#modal_srno").val(split[0]);
							$("#modal_flag").val(flag);
							$("input[name=applStatus][value=" + split[5] + "]").prop('checked', true);
							$("input[name=isMainParty][value=" + split[2] + "]").prop('checked', true);
							//$(".applStatus").val(split[5]).attr('checked',true);
							///$(".isMainParty").val(split[2]).attr('checked',true);
							$("#partystatus").val(split[4]);
							$("#statusdate").val(split[3]);
							$("#remarks").val(split[6]);
							$("#modal-status").modal('show');
						})
						$("#saveApllStatus").click(function(){
	 var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		   
		   
		 
		   	    
		  // console.log(form+formaction);
		  $("#"+form).parsley().validate();
		    if ($("#"+form).parsley().isValid())
	
    		{
    			
		    	
		  	

										$.ajax({
										type: 'post',
										url: formaction,
										data: $('#'+form).serialize(),
										success: function (data) {
											if(data.status=='sucess')
											{
												swal({
												title: "Status Change Successfull",

												icon: "success"
												})
												$("#modal-status").modal('hide');
												$('#statusfor').val('R').change();

											}
										}
									});
				}
})

				}

				 
			}
		});
})

$("#addAppRes").click(function(){
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
	if($("#statusfor").val()=='')
	{
		$('#statusfor').parsley().removeError('statusfor');
		$('#statusfor').parsley().addError('statusfor', {message: "Select Status"});
		return false;
	}
	else
	{
		var status = $('#statusfor').val();
		$('#statusfor').parsley().removeError('statusfor');
		
		
		//console.log($('#statusfor').val());
		if($('#statusfor').val()=='A')
		{

			$("#modal-add").modal("show");
		}
		else
		{
			$("#modal-res").modal("show");
		}
	}
})
$("#advBarRegNo").on('change', function() {

		var value = $("#advBarRegNo").val();
		getBarregDetails(value);
		//var text = $("#browsers").find('option[value=' + value + ']').text();


	});
$("#resadvBarRegNo").on('change', function() {

		var value = $("#resadvBarRegNo").val();
		getResBarRegDetails(value)

	});
function getResBarRegDetails(value) {
		$.ajax({
			type: 'POST',
			url: 'advRegNo',
			data: {
				"_token": $('#token').val(),
				value: value
			},
			dataType: "JSON",
			success: function(json) {

				for (var i = 0; i < json.length; i++) {
					$(".advancedSearch7 .selection7").text(json[i].nametitle);
					$(".title_sel7").css('display', 'none');
					$("#respAdvTitle").val(json[i].nametitle);
					$("#respAdvName").val(json[i].advocatename);
					$("#resadvaddr").val(json[i].advocateaddress);
					$("#resadvtaluk").attr('disabled', false);
					$("#resadvdistrict").attr('disabled', false);
					$("#resadvtaluk").empty();
					$("#resadvdistrict").empty();
					$("#resadvtaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
					$("#resadvdistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '</option>');
					$("#resadvpincode").val(json[i].pincode);
				}
			}
		});
	}

	function getBarregDetails(value) {
		$.ajax({
			type: 'POST',
			url: 'advRegNo',
			data: {
				"_token": $('#token').val(),
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
					$("#advRegTaluk").attr('disabled', false);
					$("#advRegDistrict").attr('disabled', false);
					$("#advRegTaluk").empty();
					$("#advRegDistrict").empty();
					$("#advRegTaluk").append('<option value="'+ json[i].talukcode +'"selected>' + json[i].talukname + '</option>');
					$("#advRegDistrict").append('<option value="' + json[i].distcode + '"selected>' + json[i].distname + '<option>');
					$("#advRegPin").val(json[i].pincode);
				}
			}
		});
	}
$(".dropdown_all3 a").click(function() {
		$(".advancedSearch3 .selection3").text($(this).text());
		$(".title_sel3").css('display', 'none');
		$("#relationTitle").val($(this).text());
	});

	$(".dropdown_all2 a").click(function() {
		$(".advancedSearch2 .selection2").text($(this).text());
		$(".title_sel2").css('display', 'none');
		$("#applicantTitle").val($(this).text());
	});
	$(".dropdown_all5 a").click(function() {
		$(".advancedSearch5 .selection5").text($(this).text());
		$(".title_sel5").css('display', 'none');
		$("#respondantTitle").val($(this).text());
	});
	$(".dropdown_all6 a").click(function() {
		$(".advancedSearch6 .selection6").text($(this).text());
		$(".title_sel6").css('display', 'none');
		$("#resRelTitle").val($(this).text());
	});
	$(".dropdown_all7 a").click(function() {
		$(".advancedSearch7 .selection7").text($(this).text());
		$(".title_sel7").css('display', 'none');
		$("#respAdvTitle").val($(this).text());
	});

$("#saveApplicant").click(function(e) {
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

					var applsrno = $("#applicantStartSrNo").val();
					var form = $(this).closest("form").attr('id');
					var formaction = $(this).closest("form").attr('action');
					

					$("#" + form).parsley().validate();
					if ($("#" + form).parsley().isValid()) {
						

								//console.log("yes");


								$.ajax({
									type: 'post',
									url: formaction,
									data: $('#' + form).serialize(),
									success: function(data) {

										if (data.status == "sucess") {
											swal({
												title: data.message,

												icon: "success",

											});
											$("#addAppResDet").trigger('reset');
											$("#modal-add").modal('hide');
											$('#statusfor').val('A').change();
											$("#applicantStartSrNo").val(parseInt(applsrno)+1);
										} else if (data.status == "fail") {
											swal({
												title: data.message,

												icon: "error",
											});

										}
									}
								});
							
					
					}
				} else {
					return false;
				}
			});

	})
$("#saveRespondant").click(function(e) {
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

					var ressrno = $("#resStartNo").val();
					var form = $(this).closest("form").attr('id');
					var formaction = $(this).closest("form").attr('action');
					

					$("#" + form).parsley().validate();
					if ($("#" + form).parsley().isValid()) {
						

								//console.log("yes");


								$.ajax({
									type: 'post',
									url: formaction,
									data: $('#' + form).serialize(),
									success: function(data) {

										if (data.status == "sucess") {
											swal({
												title: data.message,

												icon: "success",

											});
											$("#addResDet").trigger('reset');
											$("#modal-res").modal('hide');
											$('#statusfor').val('R').change();
											$("#resStartNo").val(parseInt(ressrno)+1);
										} else if (data.status == "fail") {
											swal({
												title: data.message,

												icon: "error",
											});

										}
									}
								});
							
					
					}
				} else {
					return false;
				}
			});

	})
$("#applDeptType").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
        //$('#nameOfDept').empty();
		 $('#nameOfDept').find('option:not(:first)').remove();  
         $('#desigAppl').find('option:not(:first)').remove();		 
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].departmentcode+'">'+data[i].departmentname+'</option>';

  						$('#nameOfDept').append(option);
				 }
        	}
        });
	})



$("#nameOfDept").change(function(){
	var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDesignationByDepartment',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
                $('#desigAppl').find('option:not(:first)').remove();
                for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
  						$('#desigAppl').append(option);
				 }
        	}
        });
	})


$("#resnameofDept").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDesignationByDepartment',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
        $('#resDesig').find('option:not(:first)').remove();			
				for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
  						$('#resDesig').append(option);
				 }
        	}
        });
	})


	$("#resDeptType").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
      //  $('#resnameofDept').empty();
	   $('#resnameofDept').find('option:not(:first)').remove();  
	     $('#resDesig').find('option:not(:first)').remove();	
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].departmentcode+'">'+data[i].departmentname+'</option>';

  						$('#resnameofDept').append(option);
				 }
        	}
        });
	})

	
	$("#saveAdditional").click(function(e) {
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

					var modl_appltype_name = $("#applTypeName").val();
					var newtype = modl_appltype_name.split('-');
					var applnewtype = newtype[1];
					var modl_modl_applno = $("#applicationId").val();
					var applicationId =applnewtype+'/'+modl_modl_applno;
					var form = $(this).closest("form").attr('id');
					var formaction = $(this).closest("form").attr('action');
					
					var additionalno = $("#additionalno").val();
					$("#" + form).parsley().validate();
					if ($("#" + form).parsley().isValid()) {
						

								//console.log("yes");


								$.ajax({
									type: 'post',
									url: formaction,
									data: $('#' + form).serialize(),
									success: function(data) {
										if (data.status == "exists") {

											swal({
												title: data.message,

												icon: "error",

											});
										}
										if (data.status == "sucess") {
											swal({
												title: data.message,

												icon: "success",

											});
											$("#additionalDate").val('');
											$("#additionalno").val('');
											$("#additionalremark").val('');
											$("#sbmt_additional").val('A');
											$("#saveAdditional").val('Save');
											getGroupDetails(applicationId);
										} else if (data.status == "fail") {
											swal({
												title: data.message,

												icon: "error",
											});

										}
									}
								});
							
					
					}
				} else {
					return false;
				}
			});

	})

function getGroupDetails(applicationId)
{
	var regdate_1  = $("#applnRegDate").val();
	$('#additionalDate').datepicker('setStartDate', regdate_1);
	$.ajax({
						type: 'post',
						url: "getGroupApplications",
						dataType: "JSON",
						data: {
						"_token": $('#token').val(),
						application_id: applicationId
						},
						success: function(json1) {
						
							$("#myTable5").find('tbody tr').remove();
						var count = 1;
						$.each(json1, function(index, obj) {
						var row = $('<tr>');
						
						if(obj.applicationsrno==obj.applicationtosrno || obj.applicationtosrno==null )
						{
							row.append('<td><a href="#" data-value="'+obj.applicationid+'|'+obj.applicationsrno+'|'+obj.applicationtosrno+'|'+obj.applicationdate+'|'+obj.remarks+'" class="groupClick" >' +obj.applicationsrno+'</a></td>');
						}
						else
						{
							row.append('<td><a href="#" data-value="'+obj.applicationid+'|'+obj.applicationsrno+'|'+obj.applicationtosrno+'|'+obj.applicationdate+'|'+obj.remarks+'" class="groupClick" >' +obj.applicationsrno+'-'+obj.applicationtosrno+ '</a></td>');
						}
						
						row.append('<td>'+obj.applicationyear+'</td>');
						
						row.append('<td >' + obj.remarks + '</a></td>');
						row.appendTo('#myTable5');
						count++;
						})
						$(".groupClick").click(function()
						{
							var value = $(this).attr('data-value');
							var split = value.split('|');
							$("#additionalstno").val(split[1]);
							var newdate = split[3];
							var date = newdate.split('-')
							$("#additionalDate").val(date[2]+'-'+date[1]+'-'+date[0]);
							$("#additionalremark").val(split[4]);
							$("#additionalendno").val(split[2]);
							$("#sbmt_additional").val('U');
							$("#saveAdditional").val('Update');
						})

						}
						});
}

$("#clearAdditional").click(function(){
	$("#additionalno").val('');
	$("#additionalDate").val('');
	$("#additionalremark").val('');
})
});


function minmax(value, min, max) 
	{
	//	if(parseInt(value) < min || isNaN(parseInt(value))) 
			//return min; 
		//else 
			if(parseInt(value) > max) 
			return max; 
		else return value;
	}
