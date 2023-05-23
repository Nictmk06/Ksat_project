$(document).ready(function(){  
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
  


$('.btnClear').click(function(){
$("#ordergenerationform").trigger('reset');

})







// end of document ready function
        
    })





  
