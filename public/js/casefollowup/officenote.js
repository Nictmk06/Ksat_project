$(document).ready(function(){
	$('#firstpage').on('click', function(){
      $('#ordertype').attr('disabled', $(this).is(':checked'));
			$('#fromDate').attr('disabled', $(this).is(':checked'));
			$('#toDate').attr('disabled', $(this).is(':checked'));

   });


	var applicationId='';
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
		applicationId =applnewtype+'/'+modl_modl_applno;
		$('#applId').val(applicationId);
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
				 getofficenoteDetails(applicationId);

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



$("#saveofficenote").click(function(e){
		e.preventDefault();
		swal({
				title: "Are you sure to Save?",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
		 if (willDelete) {
		   var form = $(this).closest("form").attr('id');
		   var formaction = $(this).closest("form").attr('action');
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

								    $("#officenote").val('');
									$("#officenoteDate").val('');
									swal({
										title: data.message,
										icon: "success"
									})
									;
									getofficenoteDetails(applicationId);
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


function getofficenoteDetails(applicationId)
{
	$.ajax({
	type: 'post',
	url: "getofficenoteDetails",
	dataType:"JSON",
	data: {"_token": $('#token').val(),application_id:applicationId},
     success: function (json) {
		$('#myTable').find('tbody tr').remove();
		var count = 1;
		$.each(json, function(index, obj) {
		var row = $('<tr>');
		var date =  obj.date;
		var arr =date.split('-');
//row.append('<td style="width:14%;" id="CaseCheckUncheck"> <input type="checkbox" name="caseSelect[]" id="caseSelect[]" value="'+obj.officenote+"::"+obj.officenote+'" ></td>');
 	row.append('<td style="width:14%;"><a href="#" id ="linkedit" class="editclick"   data-value="' + obj.officenotecode+'|'+obj.date +'|'+obj.applicationid+  '">' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</a></td>');
		//row.append('<td class="col-md-2"><a href="#" id ="linkedit" class="editclick"  data-value="' + obj.officenotecode+'|'+obj.date +'|'+obj.applicationid+ '">' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</a></td>');

//		row.append('<td style="width:14%;">' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
		row.append('<td style="width:40%;">' + obj.officenote + '</td>');
		row.append('<td style="width:45%;">' + obj.courtdirection + '</td>');
		row.appendTo('#myTable');
		count++;
	});
	$('.editclick').click(function() {
		 //var applcatcode   = $(this).attr('data-value');
	 console.log('hiii ');
		 //console.log('hiii '+userid);
		 var values = $(this).attr('data-value');
		 var split = values.split('|');
		 console.log(split);
		 var officenotecode = split[0];
		 var officenotedate = split[1];
		 var splitofficenotedate = officenotedate.split('-');

		 var officnotedate_newformat=splitofficenotedate[2]+'-'+splitofficenotedate[1]+'-'+ splitofficenotedate[0];
		 var  applicationid = split[2];

		 if(officenotecode=='0')
			{
				$("#sbmt_adv").val('A');
				$("#saveofficenote").val('Save');
				alert('Office note is not entered for '+officnotedate_newformat);
				 return 0;
			}
		 $.ajax({
			 type: 'get',
			 url: "getofficenoteDetailsforEdit",
			 dataType:"json",
			 //data: {"_token":"{{ csrf_token() }}",roleid:roleid,modulecode:modulecode,optioncode:optioncode},
			 data: {"_token":"{{ csrf_token() }}",officenotecode:officenotecode,officenotedate:officenotedate,applicationid:applicationid},
			 success: function (json) {

				 console.log('inside success '+json);
				 $("#sbmt_adv").val('U');
				 $("#saveofficenote").val('Update');
				 $("#officenote").val(json[0].officenote);
			//	 $("#officenoteDate").val(json[0].officenotedate);
				 var officenoteDate = json[0].officenotedate;
         var split2 = officenoteDate.split('-');
         $("#officenoteDate").val(split2[2]+'-'+split2[1]+'-'+split2[0]);
		   $('#officenoteDate').attr('readonly', true);
			 $('#officenoteDate').css('pointer-events','none');

				// $( "#officenoteDate" ).datepicker( "option", "disabled", true );
				//$( "#officenoteDate" ).datepicker( "option", "disabled", true );
			//	$("officenoteDate").prop('disabled', true);

				// $("#officenoteDate").datepicker().attr('readonly','readonly');

  			 $("#officenotecode").val(json[0].officenotecode);


					 }
				 });
				 });

     }
	});
}


$('.btnClear').click(function(){
    $("#ordergenerationform").trigger('reset');
	$("#officenoteform").trigger('reset');
	$('#myTable').find('tbody tr').remove();
	$('#applId').val('');
})




/*$(".edit").click(function() {
			var obj = $(this); // first store $(this) in obj
			var id = $(this).data('id'); // get id of data using this
			var values = $(this).attr('data-value');
			var split = values.split('|');
			console.log(split);
			var officenotecode = split[0];
			var officenotedate = split[1];
		 // var date1=date[2]+'-'+date[1]+'-'+ date[0]
			var  applicationid = split[2];

			$.ajax({
				type: 'GET',
				url: '/edit_officenote',
				dataType: 'JSON',
				data: {
					"_token": $('#token').val(),
					officenotecode: officenotecode,
					officenotedate:officenotedate,
					applicationid:applicationid
				},

				success: function(response) {

					if (response.status == "sucess") {
						swal({
							title: 'error',
							icon: "error",
						});

				} else {
					 $(obj).closest("tr").remove();
					 $("#officenoteform").trigger('reset');

		 //$('#myTable4').html(response);
						 swal({
							title: 'success',
							icon: "success",
						});


					}

				}

			});
		});

*/
 $('#ordergenerationform').submit(function(e)
   {
	  var applId = $('#applId').val();
     if(applId == '' )
	   {
		   alert("Select Application No");
		   return false;
	   }
    });
// end of document ready function

 })
