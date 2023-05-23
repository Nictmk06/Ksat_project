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
			row.append('<td><a href="#" data-value="'+obj.advocatecode+'-'+obj.applicationid+'-'+obj.enrollmentno+'" class="extraClick" >' +obj.enrollmentno + '</a></td>');
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
						$("#advocatecode").val(json[0].advocatecode);
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
							row.append('<td><a href="#" data-value="'+obj.applicationid+'|'+obj.applicationsrno+'|'+obj.applicationtosrno+'|'+obj.applicationdate+'|'+obj.remarks+'"  >' +obj.applicationsrno+'</a></td>');
						}
						else
						{
							row.append('<td><a href="#" data-value="'+obj.applicationid+'|'+obj.applicationsrno+'|'+obj.applicationtosrno+'|'+obj.applicationdate+'|'+obj.remarks+'">' +obj.applicationsrno+'-'+obj.applicationtosrno+ '</a></td>');
						}
						

						var doa = obj.applicationdate;
						
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						
						row.append('<td>'+dateOfApp+'</td>');
						if(obj.remarks!=null )
						row.append('<td >' + obj.remarks + '</a></td>');
						else
						row.append('<td >' + '' + '</a></td>');
						row.appendTo('#myTable5');
						count++;
						})
						

						}
						});
}

$("#clearAdditional").click(function(){
	$("#additionalno").val('');
	$("#additionalremark").val('');
})
});


function minmax(value, min, max) 
	{
		//if(parseInt(value) < min || isNaN(parseInt(value))) 
		//	return min; 
		//else 
			if(parseInt(value) > max) 
			return max; 
		else return value;
	}