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
		$('#myTableJudgements').find('tbody tr').remove();
		$("#myTableJudgementsdiv").css('display', 'none');
	
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
					 getApplicationSummaryDtls(applicationId);
					 getRecordApplicationDetails(applicationId);
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

$("#saveRecordApplDocuments").click(function(e){
	 var sbmt_adv =$("#sbmt_adv").val();
	   if(sbmt_adv=='A'){
		   title = "Are you sure to save?";		   
	   }else if(sbmt_adv =='U'){
		   title = "Are you sure to Update?";		
	   }
	   
	   var startpage=$("#startPage").val();
	   var endpage=$("#endPage").val();
	   
	   if(startpage>endpage){
		   alert("Start page must be <= end page");
		   return false;
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



function getRecordApplicationDetails(applicationId)
{
	$.ajax({
	type: 'post',
	url: "getRecordApplicationDetails",
	dataType:"JSON",
	data: {"_token": $('#token').val(),applicationId:applicationId},
	success: function (json) {
		
		$('#parta').find('tbody tr').remove();
		$('#partb').find('tbody tr').remove();
		var count = 1;
		$.each(json, function(index, obj) {
		var row = $('<tr>');
		var receiveddate =  obj.receiveddate;
		var arr =receiveddate.split('-');
		
		
		var receivedon =arr[2]+'-'+arr[1]+'-'+ arr[0]; 
	

		row.append('<td><a href="#" data-value="'+obj.id+'" class="docclick" >' +obj.documentname+ '</a></td>');
		row.append('<td>' +obj.startpage+ '</td>');
		row.append('<td>' + obj.endpage + '</td>');
		row.append('<td>' +receivedon+ '</td>');
		row.append('<td>' + obj.roomname + '</td>');
		row.append('<td>' + obj.rackname + '</td>');
		row.append('<td>' + obj.bundleno + '</td>');
		if(obj.part == 'A' )
		$('#parta').append(row)
		if(obj.part =='B')
		$('#partb').append(row)
		count++;
	})
	//to get each record data 
	$(".docclick").click(function(){
		var value = $(this).attr('data-value');
		
		var id =value;
		
		$.ajax({
				type: 'post',
				url: "getRecordDocumentDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),id:id},
				success: function (json) {
					if(json.length>0)
					{
					 for (var i = 0; i < json.length; i++) {
						if(json[i].receiveddate==null){
						$("#receiveddate").val('');
						}
						else
						{
							var dor = json[i].receiveddate;
							var dor_split = dor.split('-');
							var receiveddate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
							$("#receiveddate").val(receiveddate);	
						}
						$("#docid").val(json[i].id);
						$("#part").val(json[i].part);
						$("#documentname").val(json[i].documentname);
						$("#startPage").val(json[i].startpage);
						$("#endPage").val(json[i].endpage);
						$("#storedat").val(json[i].roomno);
						$("#rackno").val(json[i].rackno);
						$("#bundleno").val(json[i].bundleno);
				        $("#sbmt_adv").val('U');
						$("#saveRecordApplDocuments").val('Update');
						}				
					}
				}
				});
	})
	}
	});
}
	
	
$('.btnClear').click(function(){
	$("#recordApplicationForm").trigger('reset');
	 $("#sbmt_adv").val('A');
	 $("#docid").val(''); 
	 $("#saveRecordApplDocuments").val('Save');
	 $('#parta').find('tbody tr').remove();
	 $('#partb').find('tbody tr').remove();
	 $('#myTableJudgements').find('tbody tr').remove();
	 $("#myTableJudgementsdiv").css('display', 'none');
	})



});
