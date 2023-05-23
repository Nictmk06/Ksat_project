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
		
		var flag='application';
	 	$.ajax({
		type: 'POST',
		url: 'getDisposedApplicationDetails',
		data: {"_token": $('#token').val(),applicationid:applicationId},
		dataType: "JSON",
		success: function (json) {
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
			}
			else
			{
				swal({
				title: "Application is not disposed",
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
			url: 'getFreeCopyApplRespondantStatus',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId,flag:flag},
			success: function (data) {
               if(flag=='A')
				{
					$("#myTable4").find('tbody tr').remove();
						var count = 1;
						$.each(data, function(index, obj) {
						var row = $('<tr>');
						if(obj.remarks==null)
						{							
							var remarks = '';
						}
						else
						{
							var remarks = obj.remarks;							
						}	

						if(obj.deliveryon==null)
						{
							var deliveryon="";
						}
						else{
							var dor = obj.deliveryon;
							var dor_split = dor.split('-');
							var deliveryon = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
							$("#deliverydate").val(deliveryon);	
					    }
						row.append('<td><a href="#" data-value="'+obj.applicantsrno+'|'+obj.applicationid+'|'+obj.applicantname+'|'+obj.applicantstatus+'|'+deliveryon+'|'+obj.deliverycode+'|'+remarks+'" class="applicantClick" >'+obj.applicantsrno+ '</a></td>');
						row.append('<td>' +obj.applicantname + '</td>');
						row.append('<td>'+obj.applicantstatus+'</td>');
						row.append('<td>'+obj.ismainparty+'</td>');
						row.append('<td>'+deliveryon + '</td>');
						
						
					    row.appendTo('#myTable4');
						count++;
						})
						$(".applicantClick").click(function(){
							
							
							var value = $(this).attr('data-value');
							var split = value.split('|');
							$("#modal_appl_id").val(split[1]);
							$("#modal_srno").val(split[0]);
							$("#modal_flag").val(flag);
							$("#partyname").val(split[2]);
							$("#deliverydate").val(split[4]);
							$("#deliverymode").val(split[5]);
							$("#remarks").val(split[6]);
							$("#modal-status").modal('show');
							if($("#deliverydate").val()=='')
							{
								$("#saveApllStatus").removeAttr('disabled');
							}
							else{					
							$("#saveApllStatus").attr('disabled','disabled');
							}						
					})			
				}
				else if(flag=='R'){					
					$("#myTable4").find('tbody tr').remove();
						var count = 1;
						$.each(data, function(index, obj) {
						var row = $('<tr>');
						
						if(obj.remarks==null)
						{							
							var remarks = '';
						}
						else
						{
							var remarks = obj.remarks;							
						}	

						if(obj.deliveryon==null)
						{
							var deliveryon="";
						}
						else{
							var dor = obj.deliveryon;
							var dor_split = dor.split('-');
							var deliveryon = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
							$("#deliverydate").val(deliveryon);	
					    }
						
						row.append('<td><a href="#" data-value="'+obj.respondsrno+'|'+obj.applicationid+'|'+obj.respondname+'|'+obj.respondstatus+'|'+deliveryon+'|'+obj.deliverycode+'|'+remarks+'" class="respondantClick" >'+obj.respondsrno+ '</a></td>');
						row.append('<td>' +obj.respondname + '</td>');
						row.append('<td>'+obj.respondstatus+'</td>');
						row.append('<td>'+obj.ismainrespond+'</td>');
						row.append('<td>'+deliveryon + '</td>');
						row.appendTo('#myTable4');
						count++;
						})
						$(".respondantClick").click(function(){
						var value = $(this).attr('data-value');
							var split = value.split('|');
							$("#modal_appl_id").val(split[1]);
							$("#modal_srno").val(split[0]);
							$("#modal_flag").val(flag);
							$("#partyname").val(split[2]);
							$("#deliverydate").val(split[4]);
							$("#deliverymode").val(split[5]);
							$("#remarks").val(split[6]);
							$("#modal-status").modal('show');
							if($("#deliverydate").val()=='')
							{
								$("#saveApllStatus").removeAttr('disabled');
							}
							else{					
							   $("#saveApllStatus").attr('disabled','disabled');
							}
						
		
				}	)		 
			}
		}
})
});
		$("#saveApllStatus").click(function(){
				alert("jfdrs");
					   var form = $(this).closest("form").attr('id');
					   var formaction = $(this).closest("form").attr('action');   
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
												$('#statusfor').change();
												//$('#statusfor').change();
											}
										}
									});
				}
		})
		
		
$('.btnClear').click(function(){
	$("#issuefreecopyform").trigger('reset');
	})


});


 
