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
		
		$.ajax({
		type: 'POST',
		url: 'getApplDtls',
		data:  { "_token": $('#token').val(),applicationId:applicationId},
		dataType: "JSON",
		success: function (json) {
			if(json.length>0)
			{
				for (var i = 0; i < json.length; i++) {
					applicationNo =  json[i].applicationo;
                    additionalNo= json[i].additionalnumber;
					$("#applicationsrno").val(json[i].applicationsrno);
					$("#applicationtosrno").val(json[i].applicationtosrno);
					$("#applicantcount").val(json[i].applicantcount);
				}		
          $(".appldiv").css('display', 'inline');
		  $('#applicationNo').html(applicationNo);	
		  $('#additionalNo').html(additionalNo);			  
			  getApplicantDtls(applicationId);
			}
			else
			{
				swal({
				title: "Application Does Not Exist",
				icon: "error"
				})
				$("#applTypeName").val('');
				$("#applicationId").val('');
			}
		}
	});


});

function getApplicantDtls(applicationId)
  {
	       $('#respondantDetails').empty().append('<option value="">Select Respondants</option>');
			 $.ajax({
			type: 'POST',
			url: 'getApplRespondant',
			data: {
				"_token": $('#token').val(),
				applicationId: applicationId,
				flag :"A"
			},
			dataType: "JSON",
			success: function(json) {
				 $('#myTable1').find('tbody tr').remove();
				 var counter=0;
				   for(var i=0;i<json.length;i++){
				var row = $('<tr>');
			    row.append('<td class="grid-item" width="15%" id="CaseCheckUncheck"><input type="checkbox" name="applicantsrnoSelect[]" id="applicantsrnoSelect[]" value="'+json[i].applicantsrno+'" ></td>');
       
				row.append('<td class="grid-item" width="35%">' +json[i].applicantsrno + '</td>');
				row.append('<td class="grid-item" width="45%">' +json[i].applicantname + '</td>');
				row.append('</tr>');
				row.appendTo('#myTable1');
				counter++;
          }
				
			}
		});
		}



$('.btnClear').click(function(){
	$("#ungroupapplicationForm").trigger('reset');
})

});

 $('#ungroupapplicationForm').submit(function(e) 
   {
	  
	  
	  applicantcount =$('#applicantcount').val();
	  applicationsrno = $('#applicationsrno').val();
	  applicationtosrno = $('#applicationtosrno').val();
	  ungroupstno = $('#ungroupstno').val();
	  ungroupendno = $('#ungroupendno').val();
	  var ungroupapplcount=ungroupendno-ungroupstno+1;
	  var check = $('input[type="checkbox"]:checked').length;
      if(ungroupapplcount != check)
	   {
		   alert("Applicants selected should be equal to ungroup applications");
		   return false;		   
	   }
	   if(ungroupstno == applicationsrno && ungroupendno==applicationtosrno)
	   {
		   alert("Application cannot be ungroup as start no and end are same");
		   return false;		   
	   }
	    if(applicantcount == 1)
	   {
		   alert("Cannot ungroup as applicant count is 1");
		   return false;
	   }
	 e.preventDefault();
       swal({
        title: "Are you sure to ungroup?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#ungroupapplicationForm').submit();
                } 
        });
	})


