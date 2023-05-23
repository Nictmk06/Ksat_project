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
			url: 'getDisposedApplicationDetails',
			data: {"_token": $('#token').val(),applicationid:applicationId},
		dataType: "JSON",
		success: function (json) {
			if(json.length>0)
			{
				
				
				for (var i = 0; i < json.length; i++) {
					
					if(json[i].enteredfrom =='Legacy'){
                    	swal({
						title: "Application cannot be restored",

						icon: "error"
						})
						$("#applnRegDate").val('');
						$("#dateOfAppl").val('');
						$("#applTypeName").val('');
						$("#applCatName").val('');
						$("#applnSubject").val('');
						$("#applicationId").val('');
						$("#applnDisposedDate").val('');
					}
					else
					{
					
					if(json[i].registerdate==null){
                    	$("#applnRegDate").val('');
					}
					else
					{
						var dor = json[i].registerdate;
						var dor_split = dor.split('-');
						var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#applnRegDate").val(dateOfReg);	
					}
					if(json[i].disposeddate==null){
                      $("#applnDisposedDate").val('');
					}
					else
					{
						var dor = json[i].disposeddate;
						var dor_split = dor.split('-');
						var applnDisposedDate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#applnDisposedDate").val(applnDisposedDate);	
						$('#orderdate').datepicker('setStartDate', applnDisposedDate);
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
				$("#applnDisposedDate").val('');
					
			}
		}
	});


});

$("#restorefrom").change(function()
{
    var restorefrom = $(this).val();
    if (restorefrom == 'MA') {
    	$(".restorefromdiv").css('display', 'inline');
        }
        else {
        $(".restorefromdiv").css('display', 'none');
        }        

    })


});


$('#applicationrestoreForm').submit(function(e) 
   {
	   
	var restorefrom = $('#restorefrom').val();
    if (restorefrom == 'MA') {
		var restoreapplicationId = $('#restoreapplicationId').val();
		if(restoreapplicationId == ''){
		 alert("Enter Miscellaneous application ");
		 return false;
	  }
	}
	
		e.preventDefault();     
        swal({
        title: "Are you sure to restore the application ?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#applicationrestoreForm').submit();
                } 
        });
	   
	})

 $('.btnClear').click(function(){
 	var form = $(this).closest("form").attr('id');
 	$("#"+form).trigger('reset');
	
 })


