

$(document).ready(function() {

$("#downloadjudgementdiv").hide();

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

		$('#dateOfAppl').parsley().removeError('dateOfAppl');
		var modl_appltype_name = $("#applTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#applicationId").val();
		var applicationId =applnewtype+'/'+modl_modl_applno;
		$("#downloadjudgementdiv").hide();
		var flag='application';
	 	$.ajax({
		type: 'POST',
		url: 'getJudgementDetails',
		data: {"_token": $('#token').val(),applicationid:applicationId},
		dataType: "JSON",
		success: function (json) {
		  if (json.status == "fail") {
					swal({
					title: json.message,
						icon: "error",
						});
				
				 } 
		else
{
		 if (json[0].acceptreject == "A") {
					swal({
					title: 'Judgement aleardy verified',
						icon: "error",
						});
				   $("#VerifyJudgement").attr('disabled','disabled');
				   $("#accept").attr('checked','checked');
				    $("#reject").attr('disabled','disabled');
				     $("#accept").removeAttr('disabled');
				 } else {
				 	 $("#VerifyJudgement").removeAttr('disabled');
				 	  $("#reject").attr('checked','checked');
				 	  $("#reject").removeAttr('disabled');
				 	  
				 } 


		//else{
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
					if(json[i].judgementdate==null)
					{
					
						$("#applnJudgementDate").val('');
					}
					else
					{ 
						var doa = json[i].judgementdate;
						var doa_split = doa.split('-');
						var dateOfJud = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#applnJudgementDate").val(dateOfJud);
					}
					//$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
					$("#downloadjudgementdiv").show();
					
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
		}
	});
});

 $('#viewjudgement').click(function() {
        $('#verifyJudgementsForm').submit();
    });
//var jfile=0;
var wfile=0;
var dscfile=0;
//$('#jright').hide();
$('#wright').hide();
$('#dscright').hide();

 /*$("form input[name=judgement_file]").click(function() {
      	jfile++;
		//alert(jfile);
		$('#jcross').hide();
		$('#jright').show();
    });*/

 $("form input[name=water_mark_judgement_file]").click(function() {
wfile++;
$('#wcross').hide();
$('#wright').show();


});
   $("form input[name=dsc_judgement_file]").click(function() {
dscfile++;
$('#dsccross').hide();
$('#dscright').show();

});

$('#VerifyJudgement').click(function(e){




 

//if(jfile>0 && wfile>0 && dscfile>0  ){
	if( wfile>0 && dscfile>0  ){
//alert(count);

	if (!$('input[name="accept"]').is(':checked')) {
			alert("Please select accept or reject!");
			return false;
		} 

else if($("#dateOfAppl").val()=='')
		{
			$('#dateOfAppl').parsley().removeError('dateOfAppldateOfAppl');
			$('#dateOfAppl').parsley().addError('dateOfAppl', {message: "Enter Application Date"});
			return false;
		}

		else{			
           var type = $("#applTypeName").val();
           var accept=$('input[name="accept"]:checked').val();
         //  alert(accept);
			var newtype = type.split('-');
			var applicationId = newtype[1]+'/'+$("#applicationId").val();
			e.preventDefault();
        swal({
			title: "Are you sure ?",
			icon: "warning",
			showCancelButton: true,
			buttons: true,
			dangerMode: true,
		  })
		.then((willDelete) => {
  		if (willDelete) {  

		$.ajax({

			type: 'POST',
			url: "verifyJudgements",
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId,accept:accept},
			success: function (data) {
				//alert("hi");
              	if (data.status == "sucess") {
					swal({
					title: data.message,
					icon: "success",
						});
				  $("#verifyJudgementsForm").trigger('reset');
					$("#downloadjudgementdiv").hide();

                      //$('#jright').hide();
						$('#wright').hide();
						$('#dscright').hide();
						//$('#jcross').show();
						$('#wcross').show();
						$('#dsccross').show();
						//jfile=0;
						wfile=0;
						dscfile=0;

					} else if (data.status == "fail") {
						swal({
							title: data.message,
						icon: "error",
							});
					}

				},error: function(data){
console.log(data);
}
       });
		}
	});
	}  
}


//if(jfile<=0 || wfile<=0 || dscfile<=0  ){
if(wfile<=0 || dscfile<=0  ){

alert("Please review documents!");
			return false;

}

	} );





$('.btnClear').click(function(){
	$("#verifyJudgementsForm").trigger('reset');
	$("#downloadjudgementdiv").hide();
		
})

});


