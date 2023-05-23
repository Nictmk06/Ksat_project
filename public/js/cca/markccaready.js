$(document).ready(function() {

    $("#ccaapplicationno").css('pointer-events', 'visible');

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
		
		$("#dateOfOrd,#noOfPages").val('');
	    $("#dateOfOrd,#noOfPages").css('pointer-events', 'visible');
		$("#ccadoc_type").val('');
		$.ajax({
		type: 'POST',
		url: 'getApplicationCA',
		data: {
				_token: $('#token').val(),applicationId: applicationId
			},
			dataType: "JSON",
			success: function (json) {
			if(json.length>0)
			{
			
				for (var i = 0; i < json.length; i++) {
					if(json[i].registerdate1==null){
                    $("#applnRegDate").val('');
					}
					else
					{
						var dor = json[i].registerdate1;
						var dor_split = dor.split('-');
						var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#applnRegDate").val(dateOfReg);	
					}
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
					$("#app_name").val(json[i].applicantname1);
					$("#res_name").val(json[i].respondentname);
					var startDate = $("#applnRegDate").val();
					$('#dateOfCA').datepicker('setStartDate', startDate);
					$('#dateOfOrd').datepicker('setStartDate', startDate);
					
				}
			  getCCADetails(applicationId);
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




function getCCADetails(applicationId)
{
	$.ajax({
						type: 'post',
						url: "getCCAApplicationsByApplId",
						dataType: "JSON",
						data: {
						"_token": $('#token').val(),
						applicationId: applicationId,
						ccastatuscode:1},
						success: function(json1) {
						
						$("#myTableCCA").find('tbody tr').remove();
						var count = 1;
						$.each(json1, function(index, obj) {
						var row = $('<tr>');
						
						
						//row.append('<td><a href="#" data-value="'+obj.applicationid+'|'+obj.applicationsrno+'|'+obj.applicationtosrno+'|'+obj.applicationdate+'|'+obj.remarks+'" class="groupClick" >' +obj.applicationsrno+'</a></td>');
						
						if(obj.caapplicationdate==null){
							var caapplicationdate =''
						}
						else
						{
							var dor = obj.caapplicationdate;
							var dor_split = dor.split('-');
							var caapplicationdate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
							
						}
						row.append('<td><a href="#" data-value="'+obj.ccaapplicationno+'|'+caapplicationdate+'" class="groupClick" >' +obj.ccaapplicationno+'</a></td>');
						row.append('<td>'+caapplicationdate+'</td>');
						
						row.append('<td>'+obj.ccadocumentname+'</td>');
						row.append('<td >' + obj.totamount + '</a></td>');
						row.append('<td >' + obj.receiptamount + '</a></td>');
						
						if(obj.deficitreceiptamount==null){
							var deficitreceiptamount = ''
						}
						else
						{
							var deficitreceiptamount=obj.deficitreceiptamount;
						}
						row.append('<td >' +deficitreceiptamount + '</a></td>');
						row.appendTo('#myTableCCA');
						count++;
						})
						$(".groupClick").click(function()
						{
						  var value = $(this).attr('data-value');
						  var split = value.split('|');
						  $("#ccaapplicationno").val(split[0]);
						  var startDate =split[1];
						  
						  $('#readyrejectedDate').datepicker('setStartDate', startDate);
						})

						} 
						});
}


$("#caapplStatus").change(function(){
  		var status = $(this).val();
  		if(status==3)
  		{
  			$(".divrejectedreason").show();
  			$("#rejectedreason").attr('data-parsley-required', true);	
			}
  		else
  		{
  			$(".divrejectedreason").hide();
			$("#rejectedreason").attr('data-parsley-required', false);	
			
  		}
  		
  	})




$("#saveCCAStatus").click(function(e) {
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
											$("#ccaapplicationno").val();
											$("#readyrejectedDate").val('');
											$("#caapplStatus").val('');
											$("#rejectedreason").val('');
											$("#saveCCAStatus").val('Save');
											getCCADetails(applicationId);
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

	
	
	
	
$('.btnClear').click(function(){
	$("#markCCApplicationForm").trigger('reset');
	 $("#sbmt_adv").val('A');
	$("#saveADV").val('Save');
	
})



});
