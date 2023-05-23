$(document).ready(function(){

//$("#applStatus option[value='2']").remove();

/*$("#benchJudge").change(function(){
  		
  	})*/

  	//$("#applStatus")
$('input[name="isnexthearing"]').click(function() {

		if ($('input[name="isnexthearing"]').is(':checked')) {
			$(".nexthrdiv").show();
			$(".hearingdiv").show();
			$("#nextHrDate").attr('data-parsley-required',true);
			$("#nextBench").attr('data-parsley-required',true);
			$("#nextbenchJudge").attr('data-parsley-required',true);
			$("#nextPostfor").attr('data-parsley-required',true);
		} else {
			$(".nexthrdiv").hide();
			$(".hearingdiv").hide();
			$("#nextHrDate").attr('data-parsley-required',false);
			$("#nextBench").attr('data-parsley-required',false);
			$("#nextbenchJudge").attr('data-parsley-required',false);
			$("#nextPostfor").attr('data-parsley-required',false);

		}
	})
  	$("#endpage").focusout(function() {
		var startno = $("#startpage").val();
		var endNo = $('#endpage').val();

		if (parseInt($('#endpage').val()) < parseInt($('#startpage').val())) {
			//console.log("yes");
			$('#endpage').parsley().removeError('customValidationId');
			$('#endpage').parsley().addError('customValidationId', {
				message: "Application End No Should be greater than or equal to start no."
			});
			return false;
		} else { //console.log("no");
			$('#endpage').parsley().removeError('customValidationId');
		}
	})



  		$("#IAFillingDate").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var startDate = new Date(selected.date.valueOf());
		$('#IARegistrationDate').datepicker('setStartDate', startDate);
	}).on('clearDate', function(selected) {
		$('#IARegistrationDate').datepicker('setStartDate', null);
	});
		
	$("#benchJudge").change(function(){
		var judge = $(this).val();
  		$('#nextbenchJudge').val(judge).attr('selected',true);
		var hearingdate  = $("#hearingDate").val();
		var benchcode = $("#benchCode").val();
		var benchjudge = $(this).val();
		var docType = $("#applTypeName").val();
		var newdocType = docType.split('-');
		var applID = $("#applicationId").val();
		var newappl_id = newdocType[1]+'/'+applID;

		//console.log(newappl_id);
		if(hearingdate!='')
		{
			
			$("#hearingDate").parsley().removeError('hearingDate');
			$.ajax({
				type: 'post',
				url: "getHearingDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),hearingdate:hearingdate,benchjudge:benchjudge,applicationid:newappl_id},
				success: function (json) {
					if(json.length>0)
					{
						swal("Already Data Exists");

						$("#sbmt_da").val('U');
						var DHearingdate =  json[0].hearingdate;
						var arr =DHearingdate.split('-');
						if(json[0].casestatus=='4'|| json[0].casestatus=='2')
						{
							var Ddispose =  json[0].disposeddate;
							var arr2 =Ddispose.split('-');
							var disposeddate =arr2[2]+'-'+arr2[1]+'-'+ arr2[0]; 
							$("#disposeddatediv").show();
							$("#disposedDate").val(disposeddate);
						}
						else
						{
							$("#disposedDate").val('');
							$("#disposeddatediv").hide();
						}
						
						if(json[0].nextdate!=null)
						{
							$('#isnexthearing').prop('checked',true);
							$('.nexthrdiv').show();
							$(".hearingdiv").show();
							
							var NDate =  json[0].nextdate;
							var arr1 =NDate.split('-');
							var nexthrdate =arr1[2]+'-'+arr1[1]+'-'+ arr1[0]; 
							$("#nextBench").val(json[0].nextbenchtypename);
							$("#nextHrDate").val(nexthrdate);
							$("#nextbenchJudge").val(json[0].nextbenchcode);
							$("#nextPostfor").val(json[0].nextpurposecode);
						}
						else
						{
							//var nexthrdate='';
							$('#isnexthearing').prop('checked',false);
							$('.nexthrdiv').hide();
							$(".hearingdiv").hide();
							$("#nextBench").val('');
							$("#nextHrDate").val('');
							$("#nextbenchJudge").val('');
							$("#nextPostfor").val('');
						}

						$("#sbmt_da").val('U');
						$("#saveDailyHearing").val('Update');
						$("#postedfor").val(json[0].purposecode);
						$("#courthall").val(json[0].courthallno);
						$("#courtDirection").val(json[0].courtdirection);
						$("#officenote").val(json[0].courtdirection);
						$("#orderPassed").val(json[0].orderpassed);
						$("#applStatus").val(json[0].casestatus);
						$("#caseRemarks").val(json[0].caseremarks);
						
						$("#benchjudge").val(json[0].benchcode);
						
						$("#orderPassed").val(json[0].ordertypecode);
						$("#hearingCode").val(json[0].hearingcode);
						var applicationid = newappl_id;
						getHearing(hearingdate,applicationid,benchjudge);
			

					}
					else
					{
						$("#sbmt_da").val('A');
						$("#saveDailyHearing").val('Save');
		   				$("#postedfor").val('');
						$("#courthall").val('');
						$("#courtDirection").val('');
						$("#officenote").val('');
						
						$("#applStatus").val('');
						$("#caseRemarks").val('');
						$("#nextHrDate").val('');
						
						
						$("#nextPostfor").val('');
						$("#orderPassed").val('');
						
						$("#myTable2").show();
						var flag='dailyhearing';
						//console.log("ia document");
							$.ajax({
							type: 'post',
							url: "getIADocAppl",
							dataType:"JSON",
							data: {"_token": $('#token').val(),application_id:newappl_id,flag:flag},
							success: function (json) {

							$('#myTable2').find('tbody tr').remove();
							//$("#myTable2").find('tbody tr').remove();
							var count = 1;
							$.each(json, function(index, obj) {
							var row = $('<tr>');
							var IADocdate =  obj.iafillingdate;
							var IADocReg=  obj.iaregistrationdate;
							var arr =IADocdate.split('-');
							var arr2 =IADocReg.split('-');


							row.append('<td>' +obj.documentname + '</td>');
							row.append('<td><a href="#" data-value="'+obj.iasrno+'-'+obj.applicationid+'-'+obj.documenttypecode+'" class="iaClick" >' + obj.iano + '</a></td>');
							row.append('<td>'+obj.ianaturedesc+'</td>');
							row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
							row.append('<td>' + arr2[2]+'-'+arr2[1]+'-'+ arr2[0] + '</td>');

							row.append('<td ><a data-value="'+obj.iano+'-'+obj.applicationid+'-'+obj.statuscode+'" class="statusClick">' + obj.statusname + '</a></td>');




							row.appendTo('#myTable2');
							//row.clone().appendTo('#myTable2');

							count++;
							})

							$(".statusClick").click(function(){
							
		  
		    
		  var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		  $("#"+form).parsley().validate();
		    if ($("#"+form).parsley().isValid())
	
    		{
	var value   = $(this).attr('data-value');
	var valuesplit = value.split('-');
	
	
	$("#modal-status").modal('show');
	$("#modal_appl_id").val(valuesplit[1]);
	$("#iastatus").val(valuesplit[2]);
	$("#modal_srno").val(valuesplit[0]);
}

						    
							})
							}
});
					}




						
				}
			});


		}
		else
		{
			$("#hearingDate").parsley().removeError('hearingDate');
			 $('#hearingDate').parsley().addError('hearingDate', {
								message: "Enter  hearingDate"
								});
		}
	})
function getHearing(hearingdate,applicationid,benchjudge)
{
	$("#myTable2").show();
					$.ajax({
						type: 'post',
						url: "getIABasedOnHearing",
						dataType:"JSON",
						data: {"_token": $('#token').val(),hearingdate:hearingdate,applicationid:applicationid,benchjudge:benchjudge},
						success: function (json) {
					
						 
						    	$("#myTable2").find('tbody tr').remove();
								

						    	var count = 1;
	$.each(json, function(index, obj) {
	//$("#hiddenstatus").val(obj.statuscode);
	var row = $('<tr>');
	var IADocdate =  obj.iafillingdate;
	var IADocReg=  obj.iaregistrationdate;
	var arr =IADocdate.split('-');
	var arr2 =IADocReg.split('-');

	console.log(obj.statuscode);
	row.append('<td>' +obj.documentname + '</td>');
	/*row.append('<td><a href="#" data-value="'+obj.iasrno+'-'+obj.applicationid+'-'+obj.documenttypecode+'" class="iaClick" >' + obj.iano + '</a></td>');*/
	row.append('<td>'+ obj.iano + '</td>');
	row.append('<td>'+ obj.ianaturedesc + '</td>');
	row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
	row.append('<td>' + arr2[2]+'-'+arr2[1]+'-'+ arr2[0] + '</td>');

	row.append('<td ><a data-value="'+obj.iano+'-'+obj.applicationid+'-'+obj.statuscode+'" class="statusClick">' + obj.statusname + '</a></td>');


	
		
row.appendTo('#myTable2');
//row.clone().appendTo('#myTable2');
	
	count++;
	})
	$(".statusClick").click(function(){
		
		  var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		  
		    
		  // console.log(form+formaction);
		  $("#"+form).parsley().validate();
		    if ($("#"+form).parsley().isValid())
	
    		{
	var value   = $(this).attr('data-value');
	var valuesplit = value.split('-');
	
	
	$("#modal-status").modal('show');
	$("#modal_appl_id").val(valuesplit[1]);
	$("#iastatus").val(valuesplit[2]);
	$("#modal_srno").val(valuesplit[0]);
}
})
						    }

						});
}
$("#saveIAStatus").click(function(){
	var applicationid = $("#modal_appl_id").val();
	var iasrno = $("#modal_srno").val();
	var status = $("#iastatus").val();
	var hearingdate  = $("#hearingDate").val();
	var benchjudge = $("#benchJudge").val();
	var courthallno = $("#courthall").val();
	var caseremarks = $("#caseRemarks").val();
	$.ajax({
			type: 'post',
			url: 'updateIAStatus',
			data: 
			{"_token": $('#token').val()
			,applicationid:applicationid
			,iasrno:iasrno,
			status:status,
			hearingdate:hearingdate,
			benchjudge:benchjudge,courthallno:courthallno,caseremarks:caseremarks},
			success: function (data) {
				if(data.status=='success')
				{
					$("#modal-status").modal('hide');
					 getHearing(hearingdate,applicationid,benchjudge);

				}
				else
				{
					$("#modal-status").modal('show');
				}
			}
		});
})



		$("#documentTypeCode").change(function(){
			var value = $(this).val();
			var typesplit = value.split('-');
			if(typesplit[0]!=1)
			{
				$("#naturediv").hide();
				$("#IANatureCode").attr('data-parsley-required',false);
			}
			else 
			{
				$("#naturediv").show();
				$("#IANatureCode").attr('data-parsley-required',true);
				
			}
			$("#IASrNo").val('0');

})
			/*
			//var value = $(this).val();
			var docType = $("#applTypeName").val();
		var newdocType = docType.split('-');
		var applID = $("#applicationId").val();
		var newappl_id = newdocType[1]+'/'+applID
		//alert(newappl_id);
		var Type = value.split('-');
		var docType = Type[0];
		$.ajax({
				type: 'post',
				url: "getIANo",
				dataType:"JSON",
				data: {"_token": $('#token').val(),docType:docType,applicationid:newappl_id},
				success: function (json) {
					if (json == null) {
					$("#IASrNo").val('1');
				} else {
					$("#IASrNo").val(parseInt(json.iasrno)+1);
				}
					
					
				}
			});
			
		})									
*/

	$("#hearingDate").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var startDate = new Date(selected.date.valueOf());
		//to add one day to selected datepicker
		  startDate.setDate(startDate.getDate() + 1);
    $('#nextHrDate').datepicker('setDate', startDate);
	//$('#disposedDate').datepicker('setStartDate', startDate);
	$('#nextHrDate').datepicker('setStartDate', startDate);
	}).on('clearDate', function(selected) {
	//	$('#disposedDate').datepicker('setStartDate', null);
		$('#nextHrDate').datepicker('setStartDate', null);
	});




	$('.nav-tabs li').not('.active').addClass('disabled');
    $('.nav-tabs li').not('.active').find('a').removeAttr("data-toggle");
    //to fetch details of application

      $("#dateOfAppl").css('pointer-events', 'block');
   //    $("#dateOfAppl").css('pointer-events', 'block');



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
					{ var doa = json[i].applicationdate;
						
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#dateOfAppl").val(dateOfApp);
					}
					/*if(json[i].iasrno==null)
					{
						$("#IASrNo").val('1');
						$('#IASrNo').attr('readonly',true);
					}
					else
					{
						var newsrno = parseInt(json[i].iasrno)+1;
						$('#IASrNo').val(newsrno);
						$('#IASrNo').attr('readonly',true);
					}*/
					
					
					$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
				}
					var regdate_1  = $("#applnRegDate").val();
	  			$('#IAFillingDate').datepicker('setStartDate', regdate_1);
	  			$('#disposedDate').datepicker('setStartDate', regdate_1);
				
			//	getApplicationIndex(applicationId);
				getApplDetails(applicationId);
			//	getregDate(applicationId);
				getHearingDetails(applicationId);
				getStartPage(applicationId);
				
				
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

///connected application search


function getStartPage(applicationId)
{
	$.ajax({
		type: 'post',
		url: 'getStartPageOfApplIndex',
		dataType:"JSON",
		data: {"_token": $('#token').val(),applicationId:applicationId},
		success: function (data) {
			var no = data.endpage;
			var newstartpg = no+1;
			$("#startpage").val(newstartpg);
		}
	});
}






/*-----------------------------------------------------------*/

	$("#saveIA").click(function(e){
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
           var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		   
		   var appltype =$("#applTypeName").val();
		   var iasrno = $("#IASrNo").val();
		   var applType1 = appltype.split('-');
		   var applicationId = applType1[1]+'/'+$("#applicationId").val();
		 
		   	    
		   //console.log(form+formaction);
		  $("#"+form).parsley().validate();
		    if ($("#"+form).parsley().isValid())
	
    		{
    			/*var date = new Date($("#IAFillingDate").val());

    var startdate = new Date($("#IARegistrationDate").val());



   if(Date.parse(startdate) >= Date.parse(date))
    {
    $("#IARegistrationDate").parsley().removeError('IARegistrationDate');
    }
    else
    {
    	 $("#IARegistrationDate").parsley().removeError('IARegistrationDate');
			 $('#IARegistrationDate').parsley().addError('IARegistrationDate', {
								message: "Register Date Should be Greater than Filling Date"
								});
			 return false;
    	
    }*/
		    	var iano = $("#IANo").val();
		  		var docType = $("#documentTypeCode").val();
		   		var newdocType = docType.split('-');

										$.ajax({
										type: 'post',
										url: formaction,
										data: $('#'+form).serialize(),
										success: function (data) {
										//console.log(data);
										/*if(data.errors)
										{
											
										var errorlist = data.errors;
										for (var i = 0; i < errorlist.length; i++) {
										$("#errorlist").empty();
										$("#errorlist").append("<li >"+errorlist[i]+"</li>");
										$("#modal-default").modal('show');
										}

										}*/
										if(data.status=="sucess")
										{
										//console.log(applicationId);

										///getregDate(applicationId);
										getApplDetails(applicationId);
										swal({
										title: data.message,

										icon: "success"

										})
										//getApplicationIndex(applicationId);
										$("#documentTypeCode").val('');
										$("#IAFillingDate").val('');
										$("#IANatureCode").val('');
										$("#IARegistrationDate").val('');
										//$("#IANo").val('');
										$("#IAPrayer").val('');
										$("#sbmt_ia").val('A');
										$("#saveIA").val('Save');
										$("#startpage").val('');
										$("#endpage").val('');
										$("#filledby").val('');
										$("#filledbyname").val('');
										

								//		if($("#sbmt_ia").val()=='A')
							//			{
							//			$("#IASrNo").val(parseInt(iasrno)+1);
							//			}


										//to get each record data 


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
							
		   		
		  
		   
		}else
	{
		return false;
	}
	}
	
});
	})


	function getApplDetails(applicationId)
{
	
	var flag='iadocument';
	$.ajax({
	type: 'post',
	url: "getIADocAppl",
	dataType:"JSON",
	data: {"_token": $('#token').val(),application_id:applicationId,flag:flag},
	success: function (json) {
	
	$('#myTable').find('tbody tr').remove();
	//$("#myTable2").find('tbody tr').remove();
	var count = 1;
	$.each(json, function(index, obj) {
	var row = $('<tr>');
	var IADocdate =  obj.iafillingdate;
	var IADocReg=  obj.iaregistrationdate;
	var arr =IADocdate.split('-');
	var arr2 =IADocReg.split('-');

	
	row.append('<td>' +obj.documentname + '</td>');
	//if(obj.iastatus!=1 || obj.iano=='IA/1')
	//{
		row.append('<td>' + obj.iano + '</td>');
	//} 
	//else
	//{
		row.append('<td><a href="#" data-value="'+obj.iasrno+'-'+obj.applicationid+'-'+obj.documenttypecode+'" class="iaClick" >' + obj.iano + '</a></td>');
	//}
	row.append('<td>'+obj.ianaturedesc+'</td>');
	row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
	row.append('<td>' + arr2[2]+'-'+arr2[1]+'-'+ arr2[0] + '</td>');

	row.append('<td >' + obj.statusname + '</td>');


	
	
row.appendTo('#myTable');
//row.clone().appendTo('#myTable2');
	
	count++;
	})
		





	 /*$('#myTable').on('click','td:(:last-child)',function(){
 alert('hi')
 })*/
$(".iaClick").click(function(){
										$("#sbmt_ia").val('U');

										$("#saveIA").val('Update List');
										var application_id  = $(this).attr('data-value');
										//console.log(application_id);
										var applsplit = application_id.split('-');
										var IASrrno = applsplit[0];
										var doctype = applsplit[2];
										//$("#doctype").val(applsplit[2]);
										//console.log(doctype+'---'+IASrrno);
										$("#IASrNo").val(applsplit[0]);
										$.ajax({
										type: 'post',
										url: "getIADocApplSerial",
										dataType:"JSON",
										data: {"_token": $('#token').val(),IASrrno:IASrrno,applicationid:applsplit[1],doctype:doctype},
										success: function (json) {
										//console.log(json);
										for(var i=0;i<json.length;i++){
											var IADocdate = json[i].iafillingdate;
											var IADocReg=  json[i].iaregistrationdate;
											var arr =IADocdate.split('-');
											var arr2 =IADocReg.split('-');
											var newapplid = json[i].applicationid.substring(3);
											
											$("#documentno").val(json[i].appindexref);
											$("#applicationId").val(newapplid);
											
											$("#documentTypeCode").val(json[i].documenttypecode+'-'+json[i].lsla);
											
											
											$("#IANo").val(json[i].iano);
										
											if(json[i].documenttypecode==1)
											{
												$("#naturediv").show();
												$("#IANatureCode").val(json[i].ianaturecode);
											}
											else
											{
												$("#naturediv").hide();
												$("#IANatureCode").val("");
											}
											$("#IAFillingDate").val( arr[2]+'-'+arr[1]+'-'+ arr[0]);
											$("#IARegistrationDate").val( arr2[2]+'-'+arr2[1]+'-'+ arr2[0]);
											
											
											
											//var sbmt_val  ='U';
											
											var filledby = json[i].partysrno;
											$("#filledby").val(json[i].filledby);
											var flag=$("#filledby").val();
											getApplRes(applsplit[1],flag,filledby);
											$("#IANo").attr('readonly',true);
											$("#IASrNo").val(json[i].iasrno);
											$("#IAPrayer").val(json[i].iaprayer);
											$("#startpage").val(json[i].startpage);
											$("#endpage").val(json[i].endpage);
										}
										}});

									})
}

	});
}

function getApplRes(applicationid,flag,filledby)
{
	//console.log(applicationid+'----'+flag);
	$.ajax({
			type: 'post',
			url: 'getApplRespondant',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationid,flag:flag},
			success: function (data) {

				
				
				if(flag=='A')
				{
					
					for(var i=0;i<data.length;i++){
						//$("#filledbyname").find('option').remove();
						if(data[i].applicantsrno==filledby)
						{
							var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'" selected>'+data[i].applicantname+'</option>';
  							$('#filledbyname').append(option);
						}
						else
						{
							var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'">'+data[i].applicantname+'</option>';
  							$('#filledbyname').append(option);
						}

				 	 
				 }
				}
				else if(flag=='R'){
					
					for(var i=0;i<data.length;i++){
					//$("#filledbyname").find('option').remove();
					if(data[i].respondsrno==filledby)
					{
				 	 var option = '<option value="'+'R'+'-'+data[i].respondsrno+'" selected>'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
  					}
  					else
  					{
  						var option = '<option value="'+'R'+'-'+data[i].respondsrno+'">'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
  					}
				 }
				}

				 
			}
		});
	

}
/*function getregDate(applicationId)
{
	$.ajax({
			type: 'post',
			url: 'getRegDate',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId},
			success: function (data) {

				var regdate = data.iaregistrationdate;
				var date = regdate.split('-');
				

				 $('#IAFillingDate').datepicker('setStartDate', date[2]+'-'+date[1]+'-'+date[0]);
			}
		});
}
*/
//to load Hearing table


/*------------------------------------------------*/



//to navigate to next tab
	$('.btnNext').click(function(){
		var docType = $("#applTypeName").val();
		var newdocType = docType.split('-');
		var applID = $("#applicationId").val();
		var newappl_id = newdocType[1]+'/'+applID
		//console.log(newdocType[1]+'/'+applID);
		$("#application_id").val(newappl_id);
		$("#new_appl_id").text(newappl_id);
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');
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
				
				
	$('.nav-tabs li.active').next('li').removeClass('disabled');
	$('.nav-tabs li.active').next('li').find('a').attr("data-toggle","tab");
	$('.nav-tabs > .active').next('li').find('a').trigger('click');
				
	    		 

	    		 
	   
	});



	

  	$("#iaStatus").change(function(){
  		var status = $(this).val();
  		if(status==2 || status==4)
  		{
  			$("#disposeddatediv").show();
  		}
  		else
  		{
  			$("#disposeddatediv").hide();
  		}
  		
  	})
  	$("#applStatus").change(function(){
  		var status = $(this).val();
  		if(status==2 || status==4)
  		{
  			$("#disposeddatediv").show();
  			$("#nexthearingdiv").hide();
  		}
  		else
  		{
  			$("#nexthearingdiv").show();
  			$("#disposeddatediv").hide();
  		}
  		
  	})
	/*$("#benchCode").change(function(){
		alert();
	})*/
	$("#saveDailyHearing").click(function(e){
		
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
		
           var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
		   var application_id = $("#applicationId").val();

		
		 
		$("#"+form).parsley().validate();
	    if ($("#"+form).parsley().isValid())
	    {


	
		   		$.ajax({
						type: 'post',
						url: formaction,
						data: $('#'+form).serialize(),
						success: function (data) {
							//console.log(data);
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
									$("#sbmt_da").val('A');
									if($("#sbmt_da").val()=='A')
									{
									var docType = $("#applTypeName").val();
									var newdocType = docType.split('-');
									var applID = newdocType[1]+'/'+application_id;
									}
									else
									{
									var applID = $("#applicationId").val();
									}
									$("#saveDailyHearing").val('Save');
									$("#"+form).trigger('reset');
									//$("#pendingIA").val('');
									
									swal({
									title: data.message,

									icon: "success"
									})
									;
									$("#myTable2").hide();
									getHearingDetails(applID);
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
		    /*{*/
		    /*}*/
		}
		}
		else
		{
			return false;
		}

	});
	})


function getHearingDetails(applicationId)
{
	
	$.ajax({
	type: 'post',
	url: "getHearingDet",
	dataType:"JSON",
	data: {"_token": $('#token').val(),application_id:applicationId},
	success: function (json) {
//console.log(json);
	/*	if(json.length>0)
	  {
	  		var lastIndex =  json[json.length-1];
	  		var heardate = lastIndex.hearingdate;

	  		var datesplit = heardate.split('-');
	  		var startDate = (Number(datesplit[2])+1)+'-'+datesplit[1]+'-'+datesplit[0];
    		$("#hearingDate").datepicker('setStartDate',startDate);
	  }
	  else
	  {*/
	  	var regdate_1  = $("#applnRegDate").val();
	  	$('#hearingDate').datepicker('setStartDate', regdate_1);
/*	  }*/


	$('#example2').find('tbody tr').remove();
	var count = 1;
	$.each(json, function(index, obj) {
	var row = $('<tr>');
	var DHearingdate =  obj.hearingdate;
	
	var arr =DHearingdate.split('-');
	
	
	if(obj.nextdate!=null)
	{
		var nextDate =  obj.nextdate;
		var arr2 =nextDate.split('-');
		var nexthrdate =arr2[2]+'-'+arr2[1]+'-'+ arr2[0]; 
	}
	else
	{
		var nexthrdate='--';
	}

	//	row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
	
	row.append('<td><a href="#" data-value="'+obj.hearingdate+'|'+obj.benchcode+'|'+obj.applicationid+'" class="dhclick" >' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</a></td>');
	
	//row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
	row.append('<td>' +obj.listpurpose+ '</td>');
	row.append('<td>' + obj.courtdirection + '</td>');
	row.append('<td>' + obj.officenote + '</td>');
	
	
	row.append('<td>' +nexthrdate+ '</td>');

	$('#example2').append(row)
	count++;
	})
	//to get each record data 
	$(".dhclick").click(function(){
		var value = $(this).attr('data-value');
		var  newvalue  =  value.split('|');
		var hearingDate =  newvalue[0];
		var arr =hearingDate.split('-');
		var hearing = arr[2]+'-'+arr[1]+'-'+ arr[0];
		var applicationid = newvalue[2];
		var benchjudge = newvalue[1];
		
		$.ajax({
				type: 'post',
				url: "getHearingDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),hearingdate:hearing,benchjudge:benchjudge,applicationid:applicationid},
				success: function (json) {
					if(json.length>0)
					{
					
						$("#sbmt_da").val('U');
						var DHearingdate =  json[0].hearingdate;
						var arr =DHearingdate.split('-');

						
						if(json[0].nextdate!=null)
						{
							$('#isnexthearing').prop('checked',true);
							$('.nexthrdiv').show();
							$(".hearingdiv").show();
							
							var NDate =  json[0].nextdate;
							var arr1 =NDate.split('-');
							var nexthrdate =arr1[2]+'-'+arr1[1]+'-'+ arr1[0]; 
							$("#nextBench").val(json[0].nextbenchtypename);
							$("#nextHrDate").val(nexthrdate);
							$("#nextbenchJudge").val(json[0].nextbenchcode);
							$("#nextPostfor").val(json[0].nextpurposecode);
						}
						else
						{
							//var nexthrdate='';
							$('#isnexthearing').prop('checked',false);
							$('.nexthrdiv').hide();
							$(".hearingdiv").hide();
							$("#nextBench").val('');
							$("#nextHrDate").val('');
							$("#nextbenchJudge").val('');
							$("#nextPostfor").val('');
						}

						$("#saveDailyHearing").val('Update');
						$("#postedfor").val(json[0].purposecode);
						$("#courthall").val(json[0].courthallno);
						$("#courtDirection").val(json[0].courtdirection);
						$("#officenote").val(json[0].officenote);
						$("#orderPassed").val(json[0].orderpassed);
						$("#applStatus").val(json[0].casestatus);
						$("#caseRemarks").val(json[0].caseremarks);
						$("#benchCode").val(json[0].benchtypename);
						
						$("#benchJudge").val(json[0].benchcode);
						
						$("#orderPassed").val(json[0].ordertypecode);
						$("#hearingCode").val(json[0].hearingcode);
						$("#hearingDate").val(arr[2]+'-'+arr[1]+'-'+ arr[0]);
						var hearingdate = arr[2]+'-'+arr[1]+'-'+ arr[0];
						//var applicationid = newappl_id;
						getHearing(hearingdate,applicationid,benchjudge);
						

					}
				}
				});
	})
	}
	});
}
function getApplicationIndex(applicationId)
{
	$.ajax({
			type: 'post',
			url: 'getApplicationIndex',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId},
			success: function (data) {
				 $('#example1').find('tbody tr').remove();
    var count = 1;
    
     $.each(data, function(index, obj) {
                  var row = $('<tr>');
                  //  row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
                 /* row.append('<td class="col-sm-1"><a href="#"  data-value="'+obj.documentno+'-'+obj.applicationid+'">' + obj.documentno + '</td>');*/
                 row.append('<td class="col-sm-2">' +obj.documentno + '</td>');
                   row.append('<td class="col-sm-4">' +obj.documentname + '</td>');
                  row.append('<td class="col-sm-2">' +obj.startpage + '</td>');
                 row.append('<td class="col-sm-2">' +obj.endpage + '</td>');
                    
               
                 

                  $('#example1').append(row)
                  count++;
                    });
			}	
		});
}
$("#filledby").change(function(){
	var modl_appltype_name = $("#applTypeName").val();
	var newtype = modl_appltype_name.split('-');
	var applnewtype = newtype[1];
	var modl_modl_applno = $("#applicationId").val();
	var applicationId =applnewtype+'/'+modl_modl_applno;
	var flag = $("#filledby").val();
	$.ajax({
			type: 'post',
			url: 'getApplRespondant',
			dataType:"JSON",
			data: {"_token": $('#token').val(),applicationId:applicationId,flag:flag},
			success: function (data) {

				
				//$('#filledbyname').find('option:not(:first)').remove();
				$("#filledbyname").find('option').remove();
				if(flag=='A')
				{
					for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+'A'+'-'+data[i].applicantsrno+'">'+data[i].applicantname+'</option>';
  						$('#filledbyname').append(option);
				 }
				}
				else if(flag=='R'){
					for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+'R'+'-'+data[i].respondsrno+'">'+data[i].respondname+'</option>';
  						$('#filledbyname').append(option);
				 }
				}

				 
			}
		});
	
})


	

$('.btnClear').click(function(){
	//var form = $(this).closest("form").attr('id');
	//$("#"+form).trigger('reset');
	$("#documentTypeCode").val('');
	$("#IAFillingDate").val('');
	$("#IANatureCode").val('');
	$("#IARegistrationDate").val('');
	$("#IANo").val('');
	$("#IAPrayer").val('');
	$("#sbmt_ia").val('A');
	$("#saveIA").val('Save');
	$("#saveDailyHearing").val('Save');
	$("#startpage").val('');
	$("#endpage").val('');
	$("#filledby").val('');
	$("#filledbyname").val('');
	
	$("#dailyHearingForm").trigger('reset');

})

$('.btnPrevious').click(function() {
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');

	/*	$("#" + form).parsley().validate();
		if ($("#" + form).parsley().isValid()) {*/
			if(form=='applicationStatusForm')
			{
				//console.log(form);
				var count = $('#myTable > tbody > tr').length;
				if(count>0)
				{
					$('.nav-tabs > .active').prev('li').find('a').trigger('click');
				}
				else
				{
						$("#" + form).parsley().validate();
				}
				
			}
			else if(form=='applicantForm')
			{
				var count = $('#example2 > tbody > tr').length;
				if(count>0)
				{
					$('.nav-tabs > .active').prev('li').find('a').trigger('click');
				}
				else
				{
						$("#" + form).parsley().validate();
				}
			}
			else if(form=='respondantForm')
			{
				var count = $('#example3 > tbody > tr').length;
				if(count>0)
				{
					$('.nav-tabs > .active').prev('li').find('a').trigger('click');
				}
				else
				{
						$("#" + form).parsley().validate();
				}

			}
			else
			{
				$('.nav-tabs > .active').prev('li').find('a').trigger('click');
			}
			


			
		/*}*/
	});
$("#benchCode").change(function() {
   		 var text = $(this).val();
   		 
   		$.ajax({
				type: 'post',
				url: "getBenchJudges",
				dataType:"JSON",
				data: {"_token": $('#token').val(),benchtype:text,display:''},
				success: function (json) {
					$('#benchJudge').find('option:not(:first)').remove();
					 for(var i=0;i<json.length;i++){
					 	 var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
	  						$('#benchJudge').append(option);
					 }
				}
			});
   		$("#nextBench").val(text).attr('selected');   
   		$('#nextBench').val(text).change();
  	});
  	
  	$("#nextBench").change(function() {
   		 var text = $(this).val();
   		$.ajax({
				type: 'post',
				url: "getBenchJudges",
				dataType:"JSON",
				data: {"_token": $('#token').val(),benchtype:text,display:''},
				success: function (json) {
					$('#nextbenchJudge').find('option:not(:first)').remove();
					 for(var i=0;i<json.length;i++){
					 	 var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
	  						$('#nextbenchJudge').append(option);
					 }
				}
			});
   		//$("#nextBench").val(text).attr('selected');
  	});

	$("#postedfor").change(function() {
   		 var id = $(this).val();
  		$("#nextPostfor").val(id).attr('selected');
  	});
});