$(document).ready(function() {
	
	$("#requestedDate").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#recordsentdate').datepicker('setStartDate', startDate);
        
    }).on('clearDate', function(selected) {
        $('#recordsentdate').datepicker('setStartDate', null);
    });
	
	$("#recordsentdate").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#requestedDate').datepicker('setEndDate', startDate);
        
    }).on('clearDate', function(selected) {
        $('#requestedDate').datepicker('setEndDate', null);
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
		$('#myTableJudgements').find('tbody tr').remove();
		$("#myTableJudgementsdiv").css('display', 'none');
	
		$.ajax({
				type: 'POST',
				url: 'getRecordApplicationDetails',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId},
				 success: function (json) {
					if(json.length > 0){	
					var receiveddate = json[0].receiveddate;
					var dor_split = receiveddate.split('-');
					var receiveddate1 = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
					  $('#recordsentdate').datepicker('setStartDate', receiveddate1);
					  $('#requestedDate').datepicker('setStartDate', receiveddate1);
					 getAllJudgementByApplNo(applicationId);
					 getApplicationSummaryDtls(applicationId);
					}else{
						swal({
							title: "Records Does Not Exist for this Application.",
							icon: "error"
							})
						$('#myTableJudgements').find('tbody tr').remove();
						$("#myTableJudgementsdiv").css('display', 'none');
						$("#applnRegDate").val('');
						$("#dateOfAppl").val('');
						//$("#applTypeName").val('');
						$("#applCatName").val('');
						$("#applnSubject").val('');
						$("#app_name").val('');
						$("#res_name").val('');
						//$("#app_name").val('');
						} }			
			});
});



function getApplicationSummaryDtls(applicationId)
{	$.ajax({
		type: 'POST',
		url: 'getApplicationSummaryDtls',
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
					//$('#dateOfCA').datepicker('setStartDate', startDate);
				//	$('#dateOfOrd').datepicker('setStartDate', startDate);
					
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


	
$('#section').change(function(){
		if($(this).val() != '')
		{
			var sectioncode = $(this).val();
			var _token = $('input[name="_token"]').val();
			
			$.ajax({
				url:"getUsersDtlsBySection",
				method:"POST",
				 data: {"_token":$('#token').val(),sectioncode:sectioncode},
				dataType: "JSON",
				success:function(data)
				{
				$('#requestedBy').find('option:not(:first)').remove();	
							for(var i=0;i<data.length;i++){			
							var option = '<option value="'+data[i].userid+'">'+data[i].username+'</option>';
									 $('#requestedBy').append(option);
								 }			
				}
			})
		}
	});

function getAllJudgementByApplNo(applicationId)
{	
		$.ajax({
				type: 'POST',
				url: 'getAllJudgementByApplNo',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId},
				 success: function (json) {
					if(json.length > 0){
					var count = 1;
					$.each(json, function(index, obj) {		
						var row = $('<tr>');
						if(obj.judgementdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.judgementdate;
							var dor_split = dor.split('-');
							var dateOfJudgement = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center">' + dateOfJudgement + '</td>');
						row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="downloadJudgementByDate?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableJudgements');
						count++;				
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfJudgement);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');		
						$("#myTableJudgementsdiv").css('display', 'inline');
					    $('#receiveddate').datepicker('setStartDate', dateOfJudgement);
					
					 })
					}else{
						swal({
							title: "Judgement Does Not Exist",
							icon: "error"
							})
					  $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');
						$("#applnRegDate").val('');
						$("#dateOfAppl").val('');
						//$("#applTypeName").val('');
						$("#applCatName").val('');
						$("#applnSubject").val('');
						$("#app_name").val('');
						$("#res_name").val('');
						//$("#app_name").val('');
						} }
			
			});
}


$("#saveADV").click(function(e){
	  var userid= $('#requestedBy').val();
	  	$("#userid").val(userid); 
		
	   var username = $("#requestedBy option:selected").text();
	
		$("#username1").val(username);
		//alert($("#username").val());
	 var sbmt_adv =$("#sbmt_adv").val();
	   if(sbmt_adv=='A'){
		   title = "Are you sure to save?";		   
	   }else if(sbmt_adv =='U'){
		   title = "Are you sure to Update?";		
	   }
	 
	e.preventDefault();
		swal({
				title: title,
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
		 if (willDelete) {
		   var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		var modl_appltype_name = $("#applTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#applicationId").val();
		var applicationId =applnewtype+'/'+modl_modl_applno;
		//var application_id = $("#applicationId").val();
	    $("#"+form).parsley().validate();
	    if ($("#"+form).parsley().isValid())
	    {
			$.ajax({
						type: 'post',
						url: formaction,
						data: $('#'+form).serialize(),
						success: function (data) {
						if(data.errors)
								{
									var errorlist = data.errors;
										for (var i = 0; i < errorlist.length; i++) {
										$("#errorlist").empty();
										$("#errorlist").append("<li >"+errorlist[i]+"</li>");
										$("#modal-default").modal('show');
										}
								}
								if(data.status=="sucess")
								{									
									swal({
										title: data.message,
										icon: "success"
									})
									;
									$(".entrydiv").val('');
									$("#docid").val('');
									$("#sbmt_adv").val('A');
									$("#saveRecordApplDocuments").val('Save');
									getRecordApplicationDetails(applicationId);
								}
								else if(data.status=="fail")
								{
									swal({
									title: data.message,
									icon: "error"
									})
								}
						}
					});
		}
		}
		else
		{
			return false;
		}

	});
	})

	
	
$('.btnClear').click(function(){
	$("#requestrecordApplicationForm").trigger('reset');
	 $("#sbmt_adv").val('A');
	 $("#saveADV").val('Save');
	 $('#myTableJudgements').find('tbody tr').remove();
	 $("#myTableJudgementsdiv").css('display', 'none');
	})



});
