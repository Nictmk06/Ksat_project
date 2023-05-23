$(document).ready(function(){  
$("#hearingDate").css('pointer-events', 'none');
    $("#benchCode").css('pointer-events', 'none');
	$("#benchJudge").css('pointer-events', 'none');
	$("#OrderPassed").css('pointer-events', 'none');
    $("#courtDirection").css('pointer-events', 'none');
  
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
		$.ajax({
			type: 'POST',
			url: 'getHearingDetailsByApplication',
			data:  { "_token": $('#token').val(),applicationId:applicationId,applnewtype:applnewtype},
			dataType: "JSON",
			success: function (json) {
				 if(json[0].length>0)
				{
					for(var i=0;i<json[0].length;i++){
					  if(json[0][i].hearingdate==null){
                        $("#hearingDate").val('');
						}
						else
						{
						var dor = json[0][i].hearingdate;
						var dor_split = dor.split('-');
						var hearingdate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#hearingDate").val(hearingdate);	
					}
					
					$("#benchCode").val(json[0][i].benchtypename);
					$("#benchJudge").val(json[0][i].benchcode);
				    $("#courtDirection").val(json[0][i].courtdirection);
					$("#OrderPassed").val(json[0][i].ordertypecode);
				}
				$('#noticetype').empty().append('<option value="">Select notice type</option>');
			        for(var i=0;i<json[1].length;i++){
					var option = '<option value="'+json[1][i].noticetypecode+'">'+json[1][i].noticetypedesc+'</option>';
	  				$('#noticetype').append(option);
					 }
				
				//getRespondentDtls(applicationId);
			}
			else
			{
				swal({
				title: "Hearing Does Not Exist for this application",
				icon: "error"
				})
				$("#hearingDate").val('');
				$("#OrderPassed").val('');
				$("#courtDirection").val('');
				$("#bench").val('');
			}
		}
	});
});
  
function getRespondentDtls(applicationId)
  {
	       $('#respondantDetails').empty().append('<option value="">Select Respondants</option>');
			 $.ajax({
			type: 'POST',
			url: 'getApplicantRespondantDetails',
			data: {
				"_token": $('#token').val(),
				application_id: applicationId
			},
			dataType: "JSON",
			success: function(json) {
				for(var i=0;i<json[1].length;i++){
					var option = '<option value="'+json[1][i].respondsrno+'">'+json[1][i].respondname+'</option>';
	  				$('#respondantDetails').append(option);
					 }
				
			}
		});
		}


$('.btnClear').click(function(){
$("#generatenoticeform").trigger('reset');

})







// end of document ready function
        
    })





  
