$(document).ready(function() {

$("#applTypeName option[value='1-OA']").remove();

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
		url: 'getApplication',
		data:  { "_token": $('#token').val(),application_id:applicationId,flag:flag},
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
				getApplTypeReferDetails(applicationId);
				
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




function getApplTypeReferDetails(applicationId)
{
	var flag='application';
	$.ajax({
		type: 'POST',
		url: 'getApplTypeReferDetails',
		data:  { "_token": $('#token').val(),application_id:applicationId,flag:flag},
		dataType: "JSON",
		success: function (json) {
			if(json.length>0)
			{
				for (var i = 0; i < json.length; i++) {
					if(json[i].registerdate===null){
                    $("#referapplnRegDate").val('');
					}
					else
					{
						var dor = json[i].registerdate;
						var dor_split = dor.split('-');
						var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#referapplnRegDate").val(dateOfReg);	
					}
					if(json[i].applicationdate==null)
					{
					
						$("#referdateOfAppl").val('');
					}
					else
					{ 
						var doa = json[i].applicationdate;
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#referdateOfAppl").val(dateOfApp);
					}
					var applicationid = json[i].applicationid;
					var applicationid_split = applicationid.split('/');
					var applicationid = applicationid_split[1]+'/'+applicationid_split[2];
					$("#referapplicationId").val(applicationid);	
					$("#referapplTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					$("#referapplCatName").val(json[i].applcategory);
					$("#referapplnSubject").val(json[i].subject);
					
					
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

}

$("#referapplSearch").click(function(){
	   if($("#referapplTypeName").val()=='')
	   {
		$('#referapplTypeName').parsley().removeError('referapplTypeName');
		$('#referapplTypeName').parsley().addError('referapplTypeName', {message: "Select Application Type"});
		return false;
	   }
		else
		{
			$('#referapplTypeName').parsley().removeError('referapplTypeName');
		}
		if($("#referapplicationId").val()=='')
		{
			$('#referapplicationId').parsley().removeError('referapplicationId');
			$('#referapplicationId').parsley().addError('referapplicationId', {message: "Enter Application No"});
			return false;
		}
    	else
	 	{
			$('#referapplicationId').parsley().removeError('referapplicationId');
		}
		var modl_appltype_name = $("#referapplTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#referapplicationId").val();
		var applicationId =applnewtype+'/'+modl_modl_applno;
		var flag='application';
	 	$.ajax({
		type: 'POST',
		url: 'getApplicationDetailsDisposed',
		data:  { "_token": $('#token').val(),application_id:applicationId,flag:flag},
		dataType: "JSON",
		success: function (json) {
			if(json.length>0)
			{
				for (var i = 0; i < json.length; i++) {
					if(json[i].registerdate===null){
                    $("#referapplnRegDate").val('');
					}
					else
					{
						var dor = json[i].registerdate;
						var dor_split = dor.split('-');
						var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#referapplnRegDate").val(dateOfReg);	
					}
					if(json[i].applicationdate==null)
					{
					
						$("#referdateOfAppl").val('');
					}
					else
					{ 
						var doa = json[i].applicationdate;
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#referdateOfAppl").val(dateOfApp);
					}
					$("#referapplTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					$("#referapplCatName").val(json[i].applcategory);
					$("#referapplnSubject").val(json[i].subject);
				}
			}
			else
			{
				swal({
				title: "Application Does Not Exist",
				icon: "error"
				})
				$("#referapplnRegDate").val('');
				$("#referdateOfAppl").val('');
				$("#referapplTypeName").val('');
				$("#referapplCatName").val('');
				$("#referapplnSubject").val('');
				$("#referapplicationId").val('');
			}
		}
	});
});


$('.btnClear').click(function(){
	$("#applagainstForm").trigger('reset');
})


$("#updateapplagainst").click(function(e) {
		e.preventDefault();
		swal({
				title: "Are you sure to update refer application ?",
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
										    $("#sbmt_additional").val('A');
											$("#saveAdditional").val('Save');
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


});
