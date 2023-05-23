$(document).ready(function() {
	
$("#applnJudgementDate").css('pointer-events', 'none');

$('#judgement').change(function (event) {
	$('#element').empty();
    var file = URL.createObjectURL(event.target.files[0]);
    $('#element').append('<a href="' + file + '" target="_blank">' + event.target.files[0].name + '</a><br>');
});

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
					if(json[i].disposeddate==null)
					{
					
						$("#applnJudgementDate").val('');
					}
					else
					{ 
						var doa = json[i].disposeddate;
						var doa_split = doa.split('-');
						var dodisposed = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#applnJudgementDate").val(dodisposed);
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



$('.btnClear').click(function(){
	$("#uploadjudgements").trigger('reset');
	$('#element').empty();
})

});


 $('#uploadJudgementsForm').submit(function(e) 
   {
     e.preventDefault();
     
        swal({
        title: "Are you sure to save?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#uploadJudgementsForm').submit();
                } 
        });
        
       
    });

// $("#savejudgements").click(function(e) {
// 		e.preventDefault();
// 		swal({
// 				title: "Are you sure to upload judgement copy ?",
// 				icon: "warning",
// 				showCancelButton: true,
// 				buttons: true,
// 				dangerMode: true,
// 			})
// 			.then((willDelete) => {
// 				if (willDelete) {
// 				//	var modl_appltype_name = $("#applTypeName").val();
// 				//	var newtype = modl_appltype_name.split('-');
// 				//	var applnewtype = newtype[1];
// 				//	var modl_modl_applno = $("#applicationId").val();
// 				//	var applicationId =applnewtype+'/'+modl_modl_applno;
// 					var form = $(this).closest("form").attr('id');
// 					var formaction = $(this).closest("form").attr('action');
// 		       	$("#" + form).parsley().validate();
// 					if ($("#" + form).parsley().isValid()) {
// 						$.ajax({ 
// 					    	url:"{{ route('savejudgements') }}",
// 							   method:"POST",
// 							   data:new FormData(this),
// 							   dataType:'JSON',
// 							   contentType: false,
// 							   cache: false,
// 							   processData: false,
// 						       success: function(data) {
// 										if (data.status == "sucess") {
// 											swal({
// 												title: data.message,
// 												icon: "success",
// 											});
// 										  //  $("#sbmt_additional").val('A');
// 									//		$("#saveAdditional").val('Save');
// 											} else if (data.status == "fail") {
// 											swal({
// 												title: data.message,

// 												icon: "error",
// 											});
// 										}
// 									}
// 								});
// 					}
// 				} else {
// 					return false;
// 				}
// 			});
// 	})


