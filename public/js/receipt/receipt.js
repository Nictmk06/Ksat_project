$(document).ready(function() {
	$('.nav-tabs li').not('.active').addClass('disabled');
	$('.nav-tabs li').not('.active').find('a').removeAttr("data-toggle");
	$("#addorder").click(function(){
		var sbmt_case  = $("#sbmt_case").val();
		var orderNo = $(".orderNo").val();
			if(orderNo=='')
			{
				 $('.orderNo').parsley().removeError('orderNo');
				 $('.orderNo').parsley().addError('orderNo', {
				message: "Enter Order No"
				});
				return false;	
			}
			 else
	 		 {
	 		 	 $('.orderNo').parsley().removeError('orderNo');
	 		 }

  		var orderDate = $(".orderDate").val();
  		if(orderDate=='')
			{
				 $('.orderDate').parsley().removeError('orderDate');
				 $('.orderDate').parsley().addError('orderDate', {
				message: "Enter Order Date"
				});
				return false;	
			}
			 else
	 		 {
	 		 	  $('.orderDate').parsley().removeError('orderDate');
	 		 }
 		var applIssuedby = $(".applnIssuedBy").val();
 		if(applIssuedby=='')
			{
				 $('.applnIssuedBy').parsley().removeError('applnIssuedBy');
				 $('.applnIssuedBy').parsley().addError('applnIssuedBy', {
				message: "Enter Issued By"
				});
				return false;	
			}
			 else
	 		 {
	 		 	  $('.applnIssuedBy').parsley().removeError('applnIssuedBy');
	 		 }
 		 				
		if(sbmt_case=='A')
		{
			//console.log(orderNo+'|'+orderDate+"fghf");
			$("#multiorder").prepend(orderNo+'|'+orderDate+'|'+applIssuedby+'\n');
			$(".orderNo").val('');
			$(".orderDate").val('');
			$(".applnIssuedBy").val('');
		}
		else
		{
			//console.log(orderNo+'|'+orderDate);
			var  ordear= $("#multiorder").val();
			$("#multiorder").val(orderNo+'|'+orderDate+'|'+applIssuedby+'\n'+ordear);
			$(".orderNo").val('');
			$(".orderDate").val('');
			$(".applnIssuedBy").val('');
		}
	})
	
	
	$("#receiptNo").blur(function(){
		var receiptNo   = $('#receiptNo').val();
	    $.ajax({
			type: 'POST',
			url: 'getReceiptDtlsForFreshAppl',
			data: {
				"_token": $('#token').val(),
				receiptNo: receiptNo
			},
			dataType: "JSON",
			success: function(json) {
				console.log(json);
			  if(json.length > 0){
			    if(json[0].applicationid=='' || json[0].applicationid == null)
					{
					    $('#applName').val(json[0].name);
						$('#recpAmount').val(json[0].amount);
						$(".advancedSearch1 .selection1").text(json[0].titlename);
						$(".title_sel1").css('display', 'none');
						var dor = json[0].receiptdate;
						var dor_split = dor.split('-');
						var receiptDate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#receiptDate").val(receiptDate);	
						$("#addreceipt").val('U');
						$("#recpSubmit").prop("disabled", false);
					}else{
						alert('Receipt No:'+receiptNo+'  is already used in the application '+json[0].applicationid);
						$('#applName').val('');
						$('#recpAmount').val('');
						$(".advancedSearch1 .selection1").text('');
						$(".title_sel1").css('display', 'none');
						$("#receiptDate").val('');	
						$("#recpSubmit").prop("disabled", true);
					}
			}else{
				   $('#applName').val('');
					$('#recpAmount').val('');
					$(".advancedSearch1 .selection1").text('');
			    	$(".title_sel1").css('display', 'none');
			    	$("#receiptDate").val('');	
					$("#addreceipt").val('A');
			}
		}
})
});

//mini
$(".getApplicants").click(function(){
	         var applId   = $('#reviewApplId1').val();
			 $('#applicantDetails1').empty().append('<option value="">Select Applicants</option>');
			 $('#applicantDetails2').empty().append('<option value="">Select Applicants</option>');
			 $('#respondantDetails1').empty().append('<option value="">Select Respondants</option>');
			 $('#respondantDetails2').empty().append('<option value="">Select Respondants</option>');
			$.ajax({
			type: 'POST',
			url: 'getApplicantRespondantDetails',
			data: {
				"_token": $('#token').val(),
				application_id: applId
			},
			dataType: "JSON",
			success: function(json) {
				for(var i=0;i<json[0].length;i++){
					var option = '<option value="'+json[0][i].applicantsrno+'-'+applId+'">'+json[0][i].applicantname+'</option>';
	  				$('#applicantDetails1').append(option);
					 }
				for(var i=0;i<json[1].length;i++){
					var option = '<option value="'+json[1][i].respondsrno+'-'+applId+'">'+json[1][i].respondname+'</option>';
	  				$('#respondantDetails1').append(option);
					 }
				for(var i=0;i<json[0].length;i++){
					var option = '<option value="'+json[0][i].applicantsrno+'-'+applId+'">'+json[0][i].applicantname+'</option>';
	  				$('#applicantDetails2').append(option);
					 }
				for(var i=0;i<json[1].length;i++){
					var option = '<option value="'+json[1][i].respondsrno+'-'+applId+'">'+json[1][i].respondname+'</option>';
	  				$('#respondantDetails2').append(option);
					 }
			}
		});
		});

/*$(".getRespondants").click(function(){
			var applId   = $('#reviewApplId1').val();
			//alert("applId"+applId);
			$('#respondantDetails').empty().append('<option value="">Select Respondants</option>');
			$.ajax({
			type: 'POST',
			url: 'getApplicantRespondantDetails',
			data: {
				"_token": $('#token').val(),
				application_id: applId
			},
			dataType: "JSON",
			success: function(json) {
				
				for(var i=0;i<json[1].length;i++){
					var option = '<option value="'+json[1][i].respondsrno+'-'+applId+'">'+json[1][i].respondname+'</option>';
	  				$('#respondantDetails').append(option);
					 }


			}
		});
		});*/

	$("#resetorder").click(function(){
		$("#multiorder").val('');
		/*$(".orderNo").val('');
			$(".orderDate").val('');
			$(".applnIssuedBy").val('');*/
	})

	//$("#actSectionName").css('pointer-events', 'none');
	
	$("#desigadd").click(function(){
		$('#modal-add-designation').modal('show');
		$("#typeOfappl").val('A');
	})
	$("#resdesigadd").click(function(){
		$("#designame").val('');
		$('#modal-add-designation').modal('show');
		$("#typeOfappl").val('R');
	})
	$("#advocateAdd").click(function(){
		$('#modal-add-advocate').modal('show');
		$("#typeOfadv").val('A');
	})
	$("#resadvocateAdd").click(function(){
	
		$('#modal-add-advocate').modal('show');
		$("#typeOfadv").val('R');
	})
	$("#saveDesignation").click(function(){

		if($("#designame").val()=='')
			{
				$('#designame').parsley().removeError('designame');

								$('#designame').parsley().addError('designame', {
								message: "Enter Designation Name"
								});
								return false;
			}
			else
			{
				$('#designame').parsley().removeError('designame');
			}

			if($("#designame").val()!='')
			{
					var textfieldmask = /^[a-zA-Z.-/ ]*$/;
				  var testname = textfieldmask.test($("#designame").val());
				  if (testname != true) {
            					 $('#designame').parsley().removeError('designame');
								 $('#designame').parsley().addError('designame', {
								message: "Invalid Designation"
								});
								return false;
       		 		 }
       		 		 else
       		 		 {
       		 		 	 $('#designame').parsley().removeError('designame');
       		 		 }
			}
		var designame = $("#designame").val();

		$.ajax({
        type: 'post',
        url: 'storeDesignation',
       // dataType:'JSON',
        data:  { "_token": $('#token').val(),designame:designame},
        success: function (data) {
        	
        		$("#modal-add-designation").modal('hide');
        		$("#designame").val('');
        		var flag=$("#typeOfappl").val();
        		getDesignations(flag);
        		 //$("#desigAppl").simulate('mousedown');
        		//getDesignations();

        	}
        });
	})
	function getDesignations(flag)
	{
		$.ajax({
        type: 'post',
        url: 'getDesignation',
        dataType:'JSON',
        data:  { "_token": $('#token').val()},
        success: function (data) {
        	if(flag=='A')
        	{
        		//console.log("yes");
					$('#desigAppl').empty();
					for(var i=0;i<data.length;i++){
					    var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
						$('#desigAppl').append(option);
					}				
        	}
        	else
        	{
        		$('#resDesig').empty();
					for(var i=0;i<data.length;i++){
						 var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
					     $('#resDesig').append(option);
					}

        	}
        }

        	
        });
	}
	$("#saveAdvocate").click(function(){
		var form = $(this).closest("form").attr('id');
	    $("#" + form).parsley().validate();
		if ($("#" + form).parsley().isValid()) {
			$.ajax({
			type: 'post',
			url: 'storeAdvocate',
			data: $('#' + form).serialize(),
			success: function (data) {
			if(data.status=='sucess')
			{
			$("#modal-add-advocate").modal('hide');
			$("#advocateForm").trigger('reset');
			var flag = $("#typeOfadv").val();
			getAdvocateList(flag);
			}
			}
			});
		}
		else
		{
			return false;
		}
	})

	function getAdvocateList(flag)
	{
		$.ajax({
        type: 'post',
        url: 'getAdvocate',
        dataType:'JSON',
        data:  { "_token": $('#token').val()},
        success: function (data) {
        	if(flag=='A')
        	{
        		    $('#browsers').empty();
					for(var i=0;i<data.length;i++){
						var option = '<option value="'+data[i].advocateregno+'">'+data[i].advocateregno+"-"+data[i].advocatename+'</option>';
						$('#browsers').append(option);
					}				
        	}
        	else
        	{
        		$('#browsers1').empty();
					for(var i=0;i<data.length;i++){
						var option = '<option value="'+data[i].advocateregno+'">'+data[i].advocateregno+"-"+data[i].advocatename+'</option>';
						$('#browsers1').append(option);
					}
        	}
        }  	
        });
	}
	
	$("#applDeptType").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
        $('#nameOfDept').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].departmentcode+'">'+data[i].departmentname+'</option>';
  						$('#nameOfDept').append(option);
				 }
        	}
        });
	})



$("#nameOfDept").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDesignationByDepartment',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
         $('#desigAppl').find('option:not(:first)').remove();
                	 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
  						$('#desigAppl').append(option);
				 }
        	}
        });
	})


$("#resnameofDept").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDesignationByDepartment',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
         $('#resDesig').find('option:not(:first)').remove();		
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
  						$('#resDesig').append(option);
				 }
        	}
        });
	})

	$("#resDeptType").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
        $('#resnameofDept').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].departmentcode+'">'+data[i].departmentname+'</option>';
  					 $('#resnameofDept').append(option);
				 }
        	}
        });
	})
	$("#actName").change(function(){
		var actcode = $(this).val();	
		$.ajax({
        type: 'post',
        url: 'getSections',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:actcode},
        success: function (data) {
        console.log(data);
        $('#actSectionName').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].actsectioncode+'">'+data[i].actsectionname+'</option>';
  					 $('#actSectionName').append(option);
				 }
        	}
        });
		
	})
	var data = $("#myTab li.active a").prop("href");
	var url = data.substring(data.indexOf('#'));
	if(url=='#tab_1')
	{
		$("#ediformbuttons").show();
	}
	else
	{
		$("#ediformbuttons").hide();
	}


	$("#editApplication").click(function(){
		$('#editmodal').modal('show');
			$('#edit_appl-title').text('Edit Application');					 		
	})
	
	$("#editSearch").click(function(){
		var user = $("#username").text();
		var type = $("#edit_modal_type").val();
		var newtype = type.split('-');
		var applId = newtype[1]+'/'+$("#edit_applno").val();
		if(newtype[1] == 'OA'){
					$("#divApplicant").hide();
					$("#divRespondant").hide();
				}
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getApplicationDetails',
				data: {"_token": $('#token').val(),user:user,applicationid:applId},
				success: function(data) {
					if(data.status=='success')
					{
						swal({
								title: data.message,
								icon: "error",
							});
					}
					else
					{
							$("#editmodal").modal('hide');
							$("#appends").hide();
							$("#newcontent").show();
							$("#newcontent").html(data);
					}
				}
			});
		
	})
	$("#cancelApplication").click(function(){
		swal({
  title: "Are you sure to delete application?",
  
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    var applicationid = $("#canelid").val();
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'deleteApplication',
				data: {"_token": $('#token').val(),applicationid:applicationid},
				success: function(data) {
					if(data.status=='success')
					{
						swal({
								title: data.message,
								icon: "success",
							});
						window.location.reload();
					}
					else
					{
						swal({
								title: data.message,
								icon: "error",
						});
					}
					
				}
			});
  } else {
   return false;
  }
});
		
	})
	$('input[type=radio][name=partyInPerson]').on('change', function() {
		/*var issingl = $("input[type=radio][name='isAdvocate']:checked").val();*/
		var partyinperson = $("input[type=radio][name='partyInPerson']:checked").val();
		if (partyinperson == 'Y') {
			$(".advDetails").hide();
			$("#advBarRegNo").attr('data-parsley-required', false);
		} else {
			$(".advDetails").show();
			$("#advBarRegNo").attr('data-parsley-required', true);
		}
	})

	$(':input[type=number]').on('mousewheel', function(e) {
		$(this).blur();
	});
	$("#start,#endPage,#applStartNo,#applEndNo,#noOfRes,#noOfAppl,#rPincode,#receiptNo,#recpAmount,#pincodeAppl,#respincode2").keypress(function(e) {
		if (this.value.length > 10) {
			return false;
		}
	});
	if ($("#sbmt_case").val() == 'A') {
		$("#applYear").css('pointer-events', 'none');
	} else {
		$("#applYear").css('pointer-events', 'none');
	}
	$("#receiptDate").datepicker({
		autoclose: true,
		startDate: "01-01-1900",
		//endDate: '+0d',
		format: 'dd-mm-yyyy'
	})
	
	//mini
	var establishcode=$('#establishcode').val();
        //console.log(establishcode);	
	//alert(establishcode);
	if(establishcode==1)
	{
	//$('#receiptDate').datepicker('setEndDate', "02-0-2023");
	}
	if(establishcode==2)
	{
	//$('#receiptDate').datepicker('setEndDate', "01-01-2022");
	}
	if(establishcode==3)
	{
	//$('#receiptDate').datepicker('setEndDate', "01-01-2022");
	}

	/*$("#applAge").keypress(function(e) {
		if (this.value.length > 2) {
			return false;
		}

	})

	$("#applAge").focusout(function(e) {
		// console.log($(this).val());
		if (parseInt($(this).val()) < 20) {

			$('#applAge').parsley().removeError('ageValid2');
			$('#applAge').parsley().removeError('ageValid');
			$('#applAge').parsley().addError('ageValid', {
				message: "Age Should be Greater than or equal to 20"
			});
			return false;
		} else if (this.value >= 110) {
			$('#applAge').parsley().removeError('ageValid');
			$('#applAge').parsley().removeError('ageValid2');
			$('#applAge').parsley().addError('ageValid2', {
				message: "Age Should be Less than or equal to 110"
			});
			return false;
		} else {
			$('#applAge').parsley().removeError('ageValid');
			$('#applAge').parsley().removeError('ageValid2');
		}
	});
	$("#resAge").keypress(function(e) {
		if (this.value.length > 2) {
			return false;
		}

	})
	$("#resAge").focusout(function(e) {
		// console.log($(this).val());
		if (parseInt($(this).val()) < 20) {
			$('#resAge').parsley().removeError('ageValid2');
			$('#resAge').parsley().removeError('ageValid');
			$('#resAge').parsley().addError('ageValid', {
				message: "Age Should be Greater than or equal to 20"
			});
			return false;
		} else if (this.value >= 110) {
			$('#resAge').parsley().removeError('ageValid');
			$('#resAge').parsley().removeError('ageValid2');
			$('#resAge').parsley().addError('ageValid2', {
				message: "Age Should be Less than or equal to 110"
			});
			return false;
		} else {
			$('#resAge').parsley().removeError('ageValid');
			$('#resAge').parsley().removeError('ageValid2');
		}
	});*/

	//$("#applYear").css('pointer-events', 'block');

	$("#applEndNo").focusout(function() {
		var startno = $("#applStartNo").val();
		var endNo = $('#applEndNo').val();
		if (parseInt($('#applEndNo').val()) < parseInt($('#applStartNo').val())) {
			//console.log("yes");
			$('#applEndNo').parsley().removeError('customValidationId');
			$('#applEndNo').parsley().addError('customValidationId', {
				message: "Application End No Should be greater than or equal to start no."
			});
			return false;
		} else { //console.log("no");
			$('#applEndNo').parsley().removeError('customValidationId');
		}
	})

	
	$("#dateOfAppl").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var startDate = new Date(selected.date.valueOf());
		$('#applnRegDate').datepicker('setStartDate', startDate);
	}).on('clearDate', function(selected) {
		$('#applnRegDate').datepicker('setStartDate', null);
	});

	$("#applnRegDate").datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var endDate = new Date(selected.date.valueOf());
		$('#dateOfAppl').datepicker('setEndDate', endDate);
	}).on('clearDate', function(selected) {
		$('#dateOfAppl').datepicker('setEndDate', null);
	});
	$("#dateOfAppl").datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var startDate = new Date(selected.date.valueOf());
		$('#orderDate').datepicker('setEndDate', startDate);
	}).on('clearDate', function(selected) {
		$('#orderDate').datepicker('setEndDate', null);
	});

	$("#orderDate").datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
	}).on('changeDate', function(selected) {
		var endDate = new Date(selected.date.valueOf());
		$('#dateOfAppl').datepicker('setStartDate', endDate);
	}).on('clearDate', function(selected) {
		$('#dateOfAppl').datepicker('setStartDate', null);
	});
/*	var sbmt_value = $("#sbmt_case").val();
	if (sbmt_value == 'U') {
		var doa = $("#dateOfAppl").val();
		$('#applnRegDate').datepicker('setStartDate', doa);
		$('#orderDate').datepicker('setEndDate', doa);
	}*/

	//receipt datepicker--------------------------------------------------

	$(".dropdown_all1 a").click(function() {
		$(".advancedSearch1 .selection1").text($(this).text());
		$(".title_sel1").css('display', 'none');
		$("#applTitle").val($(this).text());
	});
	
	$("#recpSubmit").click(function() {
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');
		var application_id = $("#recAppId").val();
		//console.log(application_id);
		var recpNo = $("#receiptNo").val();
		var recpY = $("#recpApplYear").val();
		var recpNewNo = recpY + '/' + recpNo;
		$("#" + form).parsley().validate();
		if ($("#" + form).parsley().isValid()) {
			$.ajax({
				type: 'post',
				url: formaction,
				data: $('#' + form).serialize(),
				success: function(data) {
					$("#addreceipt").val('A');
					if (data.status == 'exits') {
						swal({
							title: data.message,
							icon: "error",
						});
						$('#' + form).trigger("reset");
					}
					if (data.status == "sucess") {
					swal({
							title: data.message,
							icon: "success",
						});
						if ($("#sbmt_value").val() == 'U') {
							$("#receiptNo").attr('readonly', false);
							$("#sbmt_value").val('A');
							$("#recpSubmit").val('Add List');
							$('#' + form).trigger("reset");
						} else {
							$('#' + form).trigger("reset");
						}
						$.ajax({
							type: 'POST',
							url: 'receipts',
							dataType: 'JSON',
							data: {
								"_token": $('#token').val(),
								application_id: application_id
							},
							cache: false,
							success: function(response) {
								getRecpTable(response);
								$(".receiptClick").click(function() {
									$('#receiptNo').prop('readonly', true);
									$("#sbmt_value").val('U');
									$("#recpSubmit").val('Update List');
									var receiptno = $(this).attr('data-value');
								    $.ajax({
										type: 'POST',
										url: 'getReceipt',
										data: {
											"_token": $('#token').val(),
											receiptno: receiptno
										},
										dataType: "JSON",
										success: function(json) {
											for (var i = 0; i < json.length; i++) {
												var recNo = json[i].receiptno;
												var arr = recNo.split('/');
												var recpdate = json[i].receiptdate;
												var arr1 = recpdate.split('-');
												$("#receiptNo").val(arr[2]);
												$("#recAppId").val(json[i].applicationid);
												$("#receiptDate").val(arr1[2] + '-' + arr1[1] + '-' + arr1[0]);
												$(".advancedSearch1 .selection1").text(json[i].titlename);
												$(".title_sel1").css('display', 'none');
												$("#applTitle").val(json[i].titlename);
												$("#receiptSrno").val(json[i].receiptsrno);
												$("#applName").val(json[i].name);
												$("#recpAmount").val(json[i].amount);
											}
										}
									});
								});

								$(".deleteRow").click(function() {
									var receiptsrno = $(this).attr('data-value');
									$.ajax({
										type: 'POST',
										url: 'deleterecp',
										dataType: 'JSON',
										data: {
											"_token": $('#token').val(),
											receiptsrno: receiptsrno,
											applicationid:application_id
										},
										cache: false,
										success: function(response) {
											if (response.status == "sucess") {
												$.ajax({
													type: 'POST',
													url: 'receipts',
													dataType: 'JSON',
													data: {
														"_token": $('#token').val(),
														application_id: application_id
													},
													cache: false,
													success: function(response) {
														getRecpTable(response);
													}
												});
												//$("#example1").load(window.location + " #example1");
											} else {
												swal({
													title: response.message,
													icon: "error",
												});
											}
										}
									});
								});
							}
						});

					} else if (data.status == "fail") {
						swal({
							title: data.message,
						icon: "error",
						});
					}
				}
			});
		}



	});
	
	function getRecpTable(response) {
		$('#example1').find('tbody tr').remove();
		var count = 1;
		$.each(response, function(index, obj) {
			var row = $('<tr>');
			var recpdate = obj.receiptdate;
			var arr1 = recpdate.split('-');
			//	row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
			row.append('<td class="col-md-2"><a href="#" data-value="' + obj.receiptsrno + '" class="receiptClick" >' + obj.receiptno + '</td>');
			row.append('<td class="col-md-2">' + arr1[2] + '-' + arr1[1] + '-' + arr1[0] + '</td>');
			row.append('<td class="col-md-2">' + obj.name + '</td>');
			row.append('<td class="col-md-2">' + obj.amount + '</td>');
			row.append('<td class="col-md-2"><a href="#" class="deleteRow btn btn-sm btn-danger"  type="button" data-value="' + obj.receiptsrno + '">X</a></td>');
			$('#example1').append(row)
			count++;
		})
	}
	//end of receipt form---------------------------------------------------------
	//case form entry-------------------------------------------------------------
	//datepicker
	$("#applYear").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years"
	});

	//assinging year to applyear textbox
	$("#dateOfAppl").change(function() {
		var doa = $("#dateOfAppl").val();
		var split = doa.split("-");
		$("#applYear").val(split[2]);
	})

	//interim prayer checkbox
	$('input[name="interimPrayer"]').click(function() {
		if ($('input[name="interimPrayer"]').is(':checked')) {
			
			//$("#interimOrderDiv2").show();
			$("#interimOrderDiv1").show();
		} else {
			$("#interimOrderDiv1").hide();
			//$("#interimOrderDiv2").hide();
			
		}
	})

	/*$("#orderNo").keypress(function() {
		var multiorder = $("#multiorder").val();
		
		if(multiorder.length>0){
			$("#orderDate").attr('data-parsley-required', 'false');
			$("#applnIssuedBy").attr('data-parsley-required', 'false');
			$("#orderNo").attr('data-parsley-required', 'false');
		} else {
			
			$("#orderDate").attr('data-parsley-required', 'true');
			$("#applnIssuedBy").attr('data-parsley-required', 'true');
			$("#orderNo").attr('data-parsley-required', 'true');
		}

	})*/
	var counter = 2;

	$("#addrow").on("click", function() {
		var newRow = $("<tr>");
		var cols = "";

		cols += '<td class="col-xs-1">' + counter + '</td>;';
		cols += '<td class="col-sm-2"><textarea type="text" class="form-control" name="reliefsought[]' + counter + '"/></textarea></td>';

		cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="-"></td>;';
		newRow.append(cols);
		$("table.order-list").append(newRow);
		counter++;
	});
	//remove relief sought
	$("table.order-list").on("click", ".ibtnDel", function(event) {
		$(this).closest("tr").remove();
		counter -= 1
	});
	
	$('.btnNext').click(function(e) {
		var apllStart = $("#applStartNo").val();
		var applYear = $("#applYear").val();
		var appltypename = $("#applTypeName").val();
		var arr = appltypename.split('-');
		if($("#relief_value2").val()=='Edit')
		{
			var applId = arr[1] +'/'+ apllStart + '/' + applYear;
		}
		else
		{
			var applId = 'T' + arr[1] +'/'+ apllStart + '/' + applYear;
		}
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');
		var appl = parseInt($("#noOfAppl").val());
		var endno = parseInt($("#applEndNo").val());
		var startno = parseInt($("#applStartNo").val());
		var groupsn = $('#groupsn').val();
		//console.log(groupsn);
		$("#" + form).parsley().validate();
		if ($("#" + form).parsley().isValid()) {
			if(groupsn == 'Y'){
				if (((startno + appl) - 1) != endno) {
					$('#noOfAppl').parsley().removeError('noOfAppl');
					$('#noOfAppl').parsley().addError('noOfAppl', {
						message: "Application Start No,End No not matching with the applicants count"
					});
					return false;
				} else {
					$('#noOfAppl').parsley().removeError('noOfAppl');
				}
			}
			
		var newtype = $('#applTypeName').val().split('-');
        var applnewtype = newtype[1];
		var applref = newtype[3];
		var reviewApplId = $('#reviewApplId1').val();
	    if(reviewApplId == '' && ( applref != 'N' ) )
	     {
	 	   alert("Select Referring application");
		   return false;
			}
		
			var new_applID = arr[1]+'/'+ apllStart + '/' + applYear;
			$.ajax({
				type: 'post',
				url: formaction,
				data: $('#' + form).serialize(),
				success: function(data) {
					if(data.status=='exists')
					{
						swal({
							title:data.message,
							icon: "error",
						});
					}
					if (data.status == "sucess") {
						$("#sbmt_case").val('U');
						$("#recpApplYear").val(applYear);
						$("#recAppId").val(applId);
						var data = $("#myTab li.active a").prop("href");
						var url = data.substring(data.indexOf('#'));
						if(url=='#tab_1')
							{
								$("#cancelApplication").remove();
								$("#editApplication").remove();
							}
													
							$('.nav-tabs li.active').next('li').removeClass('disabled');
							$('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
							$('.nav-tabs > .active').next('li').find('a').trigger('click');
					} else if (data.status == "fail") {
						swal({
							title: "Something Went Wrong!!",
							icon: "error",
						});
					}
				}
			});
		}
	});
	$('.btnPrevious').click(function() {
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');

	/*	$("#" + form).parsley().validate();
		if ($("#" + form).parsley().isValid()) {*/
			if(form=='receiptForm')
			{
				//console.log(form);
				var count = $('#example1 > tbody > tr').length;
				if(count>0)
				{
					$('.nav-tabs > .active').prev('li').find('a').trigger('click');
				}
				else
				{
					//$("#" + form).parsley().validate();
					$('.nav-tabs > .active').prev('li').find('a').trigger('click');
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
	$("#recpNext").click(function() {
		var apllStart = $("#applStartNo").val();
		var application_id = $("#recAppId").val();
		var applTypeName = $("#applTypeName").val();
		var arr = applTypeName.split('-');
		var feerequired = arr[2];
		$("#applApplId").val(application_id);
		var noOfApp = $("#noOfAppl").val();
		$("#noOfAppCount").val(noOfApp);
		$.ajax({
			type: 'post',
			url: "getAppSrCount",
			data: {
				"_token": $('#token').val(),
				application_id: application_id
			},
			success: function(json1) {
				$("#serialcount").val(json1);
			}
		});

		$.ajax({
			type: 'post',
			url: "getLastSerialNo",
			dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				application_id: application_id
			},
			success: function(json1) {
				//console.log(json1);
				if (json1 == null) {
					$("#applicantStartSrNo").val('0');
				} else {
					$("#applicantStartSrNo").val(parseInt(json1.applicantsrno));
				}
			}
		});
		//$('.nav-tabs li.active').next('li').removeClass('disabled');
		//$('.nav-tabs > .active').next('li').find('a').trigger('click');
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');
		/*$("#"+form).parsley().validate();
		if ($("#"+form).parsley().isValid())

		{*/
			var count2 = $('#myTable > tbody > tr').length;
			if (count2 > 0) {
				
				$('#reliefsought').parsley().removeError('reliefsought');
			} else {
				//console.log("no");
				$('#reliefsought').parsley().removeError('reliefsought');
				
				$('#reliefsought').parsley().addError('reliefsought', {
				message: "Enter relief sought"
			});
			return false;
			//$("#reliefsought").attr('data-parsley-required', true);
			}
		var count = $('#example1 > tbody > tr').length;
     if(feerequired =='Y'){
		if (count > 0) {
			getSingleAdvDetails(application_id);
			$('.nav-tabs li.active').next('li').removeClass('disabled');
			$('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
			$('.nav-tabs > .active').next('li').find('a').trigger('click');
		} else {
			//$("#" + form).parsley().validate();
			getSingleAdvDetails(application_id);
                        $('.nav-tabs li.active').next('li').removeClass('disabled');
                        $('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
                        $('.nav-tabs > .active').next('li').find('a').trigger('click');
		}
	 }else if(feerequired =='N'){
		 	getSingleAdvDetails(application_id);
			$('.nav-tabs li.active').next('li').removeClass('disabled');
			$('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
			$('.nav-tabs > .active').next('li').find('a').trigger('click');
	 }
	})
	
	$("#advBarRegNo").on('change', function() {

		var value = $("#advBarRegNo").val();
		getBarregDetails(value);
		//var text = $("#browsers").find('option[value=' + value + ']').text();
	});

	function getBarregDetails(value) {
		$.ajax({
			type: 'POST',
			url: 'advRegNo',
			data: {
				"_token": $('#token').val(),
				value: value
			},
			dataType: "JSON",
			success: function(json) {
				//console.log(json);
				for (var i = 0; i < json.length; i++) {
					//console.log(json[i].nameTitle);
					$(".advancedSearch4 .selection4").text(json[i].nametitle);
					$(".title_sel4").css('display', 'none');
					$("#appadvcode").val(json[i].advocatecode);
					$("#advTitle").val(json[i].nametitle);
					$("#advName").val(json[i].advocatename);
					$("#advRegAdrr").val(json[i].advocateaddress);
					$("#advRegTaluk").attr('disabled', false);
					$("#advRegDistrict").attr('disabled', false);
					$("#advRegTaluk").empty();
					$("#advRegDistrict").empty();
					$("#advRegTaluk").append('<option value="'+ json[i].talukcode +'"selected>' + json[i].talukname + '</option>');
					$("#advRegDistrict").append('<option value="' + json[i].distcode + '"selected>' + json[i].distname + '<option>');
					$("#advRegPin").val(json[i].pincode);
				}
			}
		});
	}
	$(".dropdown_all3 a").click(function() {
		$(".advancedSearch3 .selection3").text($(this).text());
		$(".title_sel3").css('display', 'none');
		$("#relationTitle").val($(this).text());
	});

	$(".dropdown_all2 a").click(function() {
		$(".advancedSearch2 .selection2").text($(this).text());
		$(".title_sel2").css('display', 'none');
		$("#applicantTitle").val($(this).text());
	});
	var j = 0;
	var count = 0;
	
	$("#saveApplicant").click(function(e) {
		//$("#saveApplicant").attr("disabled", true);
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

					var application_id = $("#recAppId").val();
					var seialcount = $("#serialcount").val();
					var form = $(this).closest("form").attr('id');
					var formaction = $(this).closest("form").attr('action');
					var noOfApp = $("#noOfAppCount").val();
					var val = $("#applicantStartSrNo").val();
					var endno = parseInt($("#applEndNo").val());

					$("#" + form).parsley().validate();
					if ($("#" + form).parsley().isValid()) {
						if (!isNaN(val)) {
							var seialcount = $("#serialcount").val();
							var sbmt_val = $("#sbmt_applicant").val();
							/*if (sbmt_val == 'A') { //console.log("yes");
								var start_no = $("#applicantStartSrNo").val();
								if (parseInt(noOfApp) != 0) {
									$("#noOfAppCount").val(parseInt(noOfApp) - 1);
									$("#applicantStartSrNo").val(parseInt(start_no) + 1);
								}
							} else {
								$("#applicantStartSrNo").val();
							}*/
							if (sbmt_val == 'A' && parseInt(seialcount) >= parseInt(noOfApp)) {
								swal({
									title: "Total number of applicants : "+noOfApp+".\n You cannot add more applicants.",
									icon: "error",
								});
								$('#' + form).attr('onsubmit', 'return true;');
							} else {
								//console.log("yes");
								$.ajax({
									type: 'post',
									url: formaction,
									data: $('#' + form).serialize(),
									success: function(data) {
										if (data.status == "sucess") {
											$('#' + form).trigger("reset");
										    $("#sbmt_applicant").val('A');
											$("#saveApplicant").val('Add List') ;
											$("#advRegDistrict").val('');
											$("#advRegTaluk").val('');
											$("#appadvcode").val('');
											$("#nameOfDept").val('');
											$("#talukAppl").val('');
											if($("#applicantStartSrNo").val()!=1)
											{
												$('#isMainParty').find(':radio[name=isMainParty][value="N"]').prop('checked', true);
												$('input[type="radio"][name="isMainParty"]:not(:checked)').attr('disabled', true);
											}
											
												if(data.data2=='Y' && $("#sbmt_applicant").val()=='A')
											{
												$('.advancedSearch4').attr('readonly', true);
												$(".advancedSearch4 .selection4").text(data.data[3]);
												$(".title_sel4").css('display', 'none');
												$("#advTitle").val(data.data[3]);
												$("#advBarRegNo").val(data.data[1]);
												$('#advBarRegNo').attr('readonly', false);
												$("#advName").val(data.data[2]);
												$("#advRegAdrr").val(data.data[4]);
												$("#advRegPin").val(data.data[8]);
												$('#advRegPin').attr('readonly', true);
												$('#advRegAdrr').attr('readonly', true);
												$("#advRegTaluk").val(data.data[6]);
												$("#advRegTaluk").attr('readonly', true);
												$("#advRegDistrict").val(data.data[5]);
												$("#advRegDistrict").attr('readonly', true);
											}
												
											//var newSrno = $("#applicantStartSrNo").val();
											//console.log(application_id);
											$.ajax({
												type: 'post',
												url: "getApplicant",
												dataType: "JSON",
												data: {
													"_token": $('#token').val(),
													application_id: application_id
												},
												success: function(json) {
													$('#example2').find('tbody tr').remove();
													var count = 1;
													$.each(json, function(index, obj) {
														var row = $('<tr>');
														//	row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
														row.append('<td><a href="#" data-value="' + obj.applicantsrno + '-' + obj.applicationid + '" class="applicantClick" >' + obj.applicantsrno + '</td>');
														row.append('<td>' + obj.applicantname + '</td>');
														if(obj.advocatename==null)
														{
															row.append('<td>' + '---' + '</td>');
														}
														else
														{
															row.append('<td>' + obj.advocatename + '</td>');
														}
														$('#example2').append(row)
														count++;
													})
													$(".applicantClick").click(function() {
														$("#sbmt_applicant").val('U');
														$("#saveApplicant").val('Update List');

														//$(this).closest('form').find("input[type=text], textarea").val("");
														/**/
														var newSrno1 = $(this).attr('data-value');
														var newSrnoarr = newSrno1.split('-');
														var newApllSrno = newSrnoarr[0];
														var newApplid = newSrnoarr[1];
													    $.ajax({
															type: 'post',
															url: "getApplicantData",
															dataType: "JSON",
															data: {
																"_token": $('#token').val(),
																newSrno: newApllSrno,
																applicationid: newApplid
															},
															success: function(json1) {
																for (var i = 0; i <= json1.length; i++) {
																	//$('#isAdvocate').find(':radio[name=isAdvocate][value="'+json1[i].issingleadv+'"]').prop('checked', true);
																	$(".advancedSearch2 .selection2").text(json1[i].nametitle);
																	$(".title_sel2").css('display', 'none');
																	$(".advancedSearch3 .selection3").text(json1[i].relationtitle);
																	$(".title_sel3").css('display', 'none');
																	// console.log(json1[i].nametitle);
																	$('input[name^="isAdvocate"][value="' + json1[i].issingleadv + '"').prop('checked', true);
																	$("#applApplId").val(json1[i].applicationid);
																	$("#applicantStartSrNo").val(json1[i].applicantsrno);
																	$("#applicantTitle").val(json1[i].nametitle);
																	$("#applicantName").val(json1[i].applicantname);
																	$("#relationTitle").val(json1[i].relationtitle);
																	$("#relationName").val(json1[i].relationname);
																    $("#relationType").val(json1[i].relation);
																	$("#gender").val(json1[i].gender);
																	$("#applAge").val(json1[i].applicantage);
																	$("#applDeptType").val(json1[i].depttype);
																	$("#nameOfDept").val(json1[i].departcode);
																	$("#desigAppl").val(json1[i].desigcode);
																	$("#addressAppl").val(json1[i].applicantaddress);
																	$("#pincodeAppl").val(json1[i].applicantpincode);
																	$("#talukAppl").val(json1[i].talukcode);
																	$("#districtAppl").val(json1[i].districtcode);
																	$("#applMobileNo").val(json1[i].applicantmobileno);
																	$("#applEmailId").val(json1[i].applicantemail);
																	$('input[name^="partyInPerson"][value="' + json1[i].partyinperson + '"').prop('checked', true);
																	$('input[name^="isMainParty"][value="' + json1[i].ismainparty + '"').prop('checked', true);
																	
																	// $("#isMainParty").val(json1[i].ismainparty);

																	// $("#advBarRegNo").val();
																		var partyinperson = $("input[type=radio][name='partyInPerson']:checked").val();
																		if (partyinperson == 'Y') {
																			$(".advDetails").hide();
																			$("#advBarRegNo").attr('data-parsley-required', false);
																		} else {
																			$(".advDetails").show();
																			$("#advBarRegNo").attr('data-parsley-required', true);
																			value = $("#advBarRegNo").val(json1[i].advocateregno);
																			getBarregDetails(value);
																			$("#appadvcode").val(json1[i].advocatecode);
																		}
																}
															}
														});
													});
												}
											});

										} else if (data.status == "fail") {
											swal({
												title: data.message,
												icon: "error",
											});
										}
									}
								});
							}
						}
					}
				} else {
					return false;
				}
			});

	})

	function getSingleAdvDetails(application_id) {
		$.ajax({
			type: 'post',
			url: "getSingleAdv",
			dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				application_id: application_id
			},
			success: function(json1) {
				if (json1 > 0 && $("#applicantStartSrNo").val() != 1) {
					$.ajax({
						type: 'post',
						url: "getSingleAdvDet",
						dataType: "JSON",
						data: {
							"_token": $('#token').val(),
							application_id: application_id
						},
						success: function(json) {
							//console.log(json);
							for (var i = 0; i <= json.length; i++) {
								$("input[type=radio][name='isAdvocate']:checked").val(json[i].issingleadv);
								$('input[type="radio"][name="isAdvocate"]').attr('disabled', true);
								$('#advBarRegNo').attr('readonly', true);
								$('.advancedSearch4').attr('readonly', true);
								$(".advancedSearch4 .selection4").text(json[i].nametitle);
								$(".title_sel4").css('display', 'none');
								$("#advTitle").val(json[i].nametitle);
								$("#advBarRegNo").val(json[i].advocateregno);
								$("#appadvcode").val(json[i].advocatecode);
								$("#advName").val(json[i].advocatename);
								$("#advTitle").val(json[i].nametitle);
								$("#advRegAdrr").val(json[i].advresaddress);
								$("#advRegPin").val(json[i].advrespincode);

								$("#advRegTaluk").append('<option value=' + json[i].advrestalukcode + 'selected>' + json[i].talukname + '</option>');
								$("#advRegTaluk").attr('disabled', true);
								$("#advRegDistrict").append('<option value=' + json[i].advresdistcode + 'selected>' + json[i].distname + '<option>');
								$("#advRegDistrict").attr('disabled', true);
							}
						}
					});
				}
			}
		});
	}


	function getResSingleAdvDetails(application_id) {
		$.ajax({
			type: 'post',
			url: "getResSingleAdv",
			dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				application_id: application_id
			},
			success: function(json1) {
				//console.log(json1+"fgh");
				if (json1 > 0 && $("#resStartNo").val() != 1) {
					$.ajax({
						type: 'post',
						url: "getResSingleAdvDet",
						dataType: "JSON",
						data: {
							"_token": $('#token').val(),
							application_id: application_id
						},
						success: function(json) {
							for (var i = 0; i <= json.length; i++) {
								$("input[type=radio][name='isResAdvocate']:checked").val(json[i].issingleadv);
								$('input[type="radio"][name="isResAdvocate"]').attr('disabled', true);
								$('#resadvBarRegNo').attr('readonly', true);
								$(".advancedSearch7 .selection7").text(json[i].nametitle);
								$(".title_sel7").css('display', 'none');
								$("#respAdvTitle").val(json[i].nametitle);
								$("#respAdvName").val(json[i].advocatename);
								$("#resadvaddr").val(json[i].advocateaddress);
								$("#resadvtaluk").attr('disabled', false);
								$("#resadvdistrict").attr('disabled', false);
								$("#resadvtaluk").empty();
								$("#resadvdistrict").empty();
								$("#resadvtaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
								$("#resadvdistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '</option>');
								$("#resadvpincode").val(json[i].pincode);
								$("#resadvdistrict").attr('disabled',true);
								$("#resadvcode").val(json[i].advocatecode);
							}
						}
					});
				}
			}
		});
	}
	$("#applNext").click(function() {
		var apllStart = $("#applStartNo").val();
		var application_id = $("#recAppId").val();
		var applYear = $("#applYear").val();
		//$('#resStartNo').val(apllStart);
		$("#resApplId").val(application_id);
		$("#resApplYear").val(applYear);
		var noOfres = $("#noOfRes").val();
		$("#noOfResCount").val(noOfres);
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');
		$.ajax({
			type: 'post',
			url: "getResSrCount",
			data: {
				"_token": $('#token').val(),
				application_id: application_id
			},
			success: function(json1) {
				$("#resCount").val(json1);
			}
		});

		$.ajax({
			type: 'post',
			url: "getRsLastSerialNo",
			dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				application_id: application_id
			},
			success: function(json1) {
				console.log(json1);
				//$("#applicantStartSrNo").val('');
				if (json1 == null) {
					$("#resStartNo").val('0');
				} else {
					$("#resStartNo").val(parseInt(json1.respondsrno));
				}
			}
		});
		$.ajax({
			type: 'post',
			url: "getResAdv",
			dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				application_id: application_id
			},
			success: function(json1) {
				$("#browsers1").empty();
				for (var i = 0; i < json1.length; i++) {
					$("#browsers1").append('<option value=' + json1[i].advocateregno + '>' + json1[i].advocatename + '</option>');
				}
			}
		});

		/*$("#"+form).parsley().validate();
		if ($("#"+form).parsley().isValid())
		{*/
		var count = $('#example2 > tbody > tr').length;
		var appl = parseInt($("#noOfAppl").val());
		if (count == appl) {
			getResSingleAdvDetails(application_id);
			$('.nav-tabs li.active').next('li').removeClass('disabled');
			$('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
			$('.nav-tabs > .active').next('li').find('a').trigger('click');
		} else {
			swal({
				title: "No of Applicants does not match with the entered applicants!!",

				icon: "error",
			});
			return false;
		}

		/*  }*/

	})
	$(".dropdown_all5 a").click(function() {
		$(".advancedSearch5 .selection5").text($(this).text());
		$(".title_sel5").css('display', 'none');
		$("#respondantTitle").val($(this).text());
	});
	$(".dropdown_all6 a").click(function() {
		$(".advancedSearch6 .selection6").text($(this).text());
		$(".title_sel6").css('display', 'none');
		$("#resRelTitle").val($(this).text());
	});
	$(".dropdown_all7 a").click(function() {
		$(".advancedSearch7 .selection7").text($(this).text());
		$(".title_sel7").css('display', 'none');
		$("#respAdvTitle").val($(this).text());
	});
	$("#resadvBarRegNo").on('change', function() {
		var value = $("#resadvBarRegNo").val();
		getResBarRegDetails(value)

	});

	function getResBarRegDetails(value) {
		$.ajax({
			type: 'POST',
			url: 'advRegNo',
			data: {
				"_token": $('#token').val(),
				value: value
			},
			dataType: "JSON",
			success: function(json) {

				for (var i = 0; i < json.length; i++) {
					$(".advancedSearch7 .selection7").text(json[i].nametitle);
					$(".title_sel7").css('display', 'none');
					$("#respAdvTitle").val(json[i].nametitle);
					$("#respAdvName").val(json[i].advocatename);
					$("#resadvaddr").val(json[i].advocateaddress);
					$("#resadvtaluk").attr('disabled', false);
					$("#resadvdistrict").attr('disabled', false);
					$("#resadvtaluk").empty();
					$("#resadvdistrict").empty();
					$("#resadvtaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
					$("#resadvdistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '</option>');
					$("#resadvpincode").val(json[i].pincode);
					$("#resadvcode").val(json[i].advocatecode);
				}
			}
		});
	}
	var j = 0;
	var count = 0;
	$("#saveRespondant").click(function(e) {
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
					var application_id = $("#resApplId").val();
					var noOfRes = $("#noOfResCount").val();
					var val = $("#resStartNo").val();
					var endno = parseInt($("#applEndNo").val());
					$("#" + form).parsley().validate();
					if ($("#" + form).parsley().isValid()) {
						if (!isNaN(val)) {
							var resseialcount = $("#resCount").val();
							var sbmt_val = $("#sbmt_respondant").val();
							if (sbmt_val == 'A') {
								var start_no = $("#resStartNo").val();
								if (parseInt(noOfRes) != 0) {
								$("#noOfResCount").val(parseInt(noOfRes) - 1);
								$("#resStartNo").val(parseInt(start_no) + 1);
							   }
							} else {
								$("#resStartNo").val();
							}
							if (sbmt_val == 'A' && parseInt(resseialcount) >= parseInt(noOfRes)) {
								swal({
									title: "Total number of respondents : "+noOfRes+".\n You cannot add more respondents.",
								    icon: "error",
								});

								$('#' + form).attr('onsubmit', 'return true;');
							}
							else
							{
								$.ajax({
								type: 'post',
								url: formaction,
								data: $('#' + form).serialize(),
								success: function(data) {
									if (data.status == "sucess") {
										$('#' + form).trigger("reset");
											
											$("#sbmt_respondant").val('A');
											$("#saveRespondant").val('Add List');
											$("#resDistrict").val('');
											$("#resTaluk").val('');
											$("#resnameofDept").val('');
											$("#resadvtaluk").val('');
											$("#resadvcode").val('');
											$("#resadvdistrict").val('');
											if($("#resStartNo").val()!=1)
											{
												$('#isMainRes').find(':radio[name=isMainRes][value="N"]').prop('checked', true);
												$('input[type="radio"][name="isMainRes"]:not(:checked)').attr('disabled', true);
											}
										if(data.data2=='Y')
											{
												

												$(".advancedSearch7 .selection7").text(data.data[3]);
												$(".title_sel7").css('display', 'none');


												$(".title_sel4").css('display', 'none');
												$("#respAdvTitle").val(data.data[3]);
												$("#resadvBarRegNo").val(data.data[1]);
												$('#resadvBarRegNo').attr('readonly', false);
												$("#respAdvName").val(data.data[2]);
												
												$("#resadvaddr").val(data.data[4]);
												$("#resadvpincode").val(data.data[8]);
												$("#resadvcode").val(data.data[9]);
												$('#resadvpincode').attr('readonly', true);
												$('#resadvaddr').attr('readonly', true);
												$("#resadvtaluk").val(data.data[6]);
												$("#resadvtaluk").attr('readonly', true);
												$("#resadvdistrict").val(data.data[5]);
												$("#resadvdistrict").attr('readonly', true);

											}
										var newSrno = $("#resStartNo").val();
										$.ajax({
											type: 'post',
											url: "getRespondant",
											dataType: "JSON",
											data: {
												"_token": $('#token').val(),
												application_id: application_id
											},
											success: function(json) {
												$('#example3').find('tbody tr').remove();
												var count = 1;
												$.each(json, function(index, obj) {
													var row = $('<tr>');
													//	row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
													row.append('<td><a href="#" data-value="' + obj.respondsrno + '-' + obj.applicationid + '" class="respondantClick" >' + count + '</td>');
													row.append('<td>' + obj.respondname + '</td>');
													row.append('<td>' + obj.advocatename + '</td>');

													$('#example3').append(row)
													count++;
												})
												$(".respondantClick").click(function() {
													$("#sbmt_respondant").val('U');
													$("#saveRespondant").val('Update List');
													
													var newSrno1 = $(this).attr('data-value');
													var newSrnoarr = newSrno1.split('-');
													var newApllSrno = newSrnoarr[0];
													var newApplid = newSrnoarr[1];
													
													
													$.ajax({
														type: 'post',
														url: "getRespondantData",
														dataType: "JSON",
														data: {
															"_token": $('#token').val(),
															newSrno: newApllSrno,
															applicationid: newApplid
														},
														success: function(json1) {
															for (var i = 0; i < json1.length; i++) {
																$('#isResAdvocate').find(':radio[name=isResAdvocate][value="'+json1[i].issingleadvocate+'"]').attr('checked', true);
																$(".advancedSearch5 .selection5").text(json1[i].respondtitle);
																$(".title_sel5").css('display', 'none');
																$("#respondantTitle").val(json1[i].respondtitle);
																$("#resReltaion").val(json1[i].relation);
																$("#respondantName").val(json1[i].respondname);
																$(".advancedSearch6 .selection6").text(json1[i].relationtitle);
																$(".title_sel6").css('display', 'none');
																$("#resApplId").val(json1[i].applicationid);
																$("#resStartNo").val(json1[i].respondsrno);
																$("#resRelTitle").val(json1[i].relationtitle);
																$("#resRelName").val(json1[i].relationname);
																$("#resGender").val(json1[i].gender);
																$("#resAge").val(json1[i].respondantage);
																//$('select[name^="resReltaion"] option[value="'+relvalue+'"]').attr("selected","selected");
																$("#resDeptType").val(json1[i].respontdepttype);
																$("#resnameofDept").val(json1[i].respontdeptcode);
																$("#resDesig").val(json1[i].desigcode);
																$("#resAddress2").val(json1[i].respondaddress);
																$("#respincode2").val(json1[i].respondpincode);
																$("#resTaluk").val(json1[i].respondtaluk);
																$("#resDistrict").val(json1[i].responddistrict);
																$("#resMobileNo").val(json1[i].respondmobileno);
																$("#resEmailId").val(json1[i].respondemail);
																if(json1[i].isgovtadvocate=='Y')
																{
																	$('input[name^="isGovtAdv"][value="' + json1[i].isgovtadvocate + '"').prop('checked', true)
																}
																else
																{
																	$('input[name^="isGovtAdv"][value="' + json1[i].isgovtadvocate + '"').prop('checked', false)
																}
																$('input[name^="isMainRes"][value="' + json1[i].ismainrespond + '"').prop('checked', true);
																/*$("#isGovtAdv").val(json1[i].isgovtadvocate);
																$("#isMainRes").val(json1[i].ismainrespond);*/
																$("#resadvBarRegNo").val(json1[i].advocateregno);
																	$("#resadvcode").val(json1[i].advocatecode);
																  value = $("#resadvBarRegNo").val(json1[i].advocateregno);
																getResBarRegDetails(value);
															}
														}
													});
												});
											}
										});

									} else if (data.status == "fail") {
										swal({
											title: data.message,
											icon: "error",
										});
										//$(".messages").delay(5000).slideUp(300);
									}
								}
							});
							}
						}
					}
				} else {
					return false;
				}
			});
	})
	$("#respNext").click(function() {
		var apllStart = $("#applStartNo").val();
		var application_id = $("#recAppId").val();
		var applYear = $("#applYear").val();
		$('#applIndexStartNo').val(apllStart);
		$("#applIndexId").val(application_id);
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');
		/*$("#"+form).parsley().validate();
		if ($("#"+form).parsley().isValid())
		{*/
		var count = $('#example3 > tbody > tr').length;
		var res = parseInt($("#noOfRes").val());
		if (count == res) {
			$('.nav-tabs li.active').next('li').removeClass('disabled');
			$('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
			$('.nav-tabs > .active').next('li').find('a').trigger('click');
		} else {
			swal({
				title: "No of Respondants does not match with the entered Respondants!!",
				icon: "error",
			});
			return false;
		}
		/*  }*/
	})
	var counter =2;

	$("#addrow2").on("click", function() {
		var newRow = $("<tr>");
		var cols = "";
		/*cols += '<td class="col-sm-1"><input type="hidden" class="counter" name="count[]" value="' + counter + '">' + counter + '</td>;';*/
		cols += '<td class=""col-xs-4""><textarea type="text"   data-parsley-required data-parsley-required-message="Enter Part Of Document" data-parsley-trigger="focusout"  class="form-control number" name="partOfDoc[]' + counter + '"/></textarea></td>';
		cols += '<td class="col-xs-1"><input type="number" id="start" class="form-control number start" name="start[]"   data-parsley-trigger="focusout"    data-parsley-required data-parsley-required-message="Enter Start No."' + counter + '"/></td>';
		cols += '<td class="col-xs-1"><input type="number" id="endPage" class="form-control number endPage" name="endPage[]"  data-parsley-trigger="focusout"  data-parsley-gt-message="End No should be greater than start no"   data-parsley-required data-parsley-required-message="Enter End No."' + counter + '"/></td>';
		cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>;';
		newRow.append(cols);
		$("table.application-list").append(newRow);
		counter++;
	});
	//remove relief sought
	$("table.application-list").on("click", ".ibtnDel", function(event) {
		$(this).closest("tr").remove();
		counter -= 1
	});
	$("#saveAplicantionIndex").click(function(e) {
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
					var application_id = $("#resApplId").val();
					var noOfRes = $("#noOfResCount").val();
					var val = $("#resStartNo").val();
					var endno = parseInt($("#applEndNo").val());
					var startval = 0;
					$("#" + form).parsley().validate();
					if ($("#" + form).parsley().isValid()) {
						$.ajax({
							type: 'post',
							url: formaction,
							data: $('#' + form).serialize(),
							success: function(data) {
								if(data.status=='sucess')
								{
									swal({
										title: data.message,
										icon: "success",
									})
									location.reload();
								}
								if(data.status=='errormsg')
								{
									$("#applErrormsg").html(data.message);
								}
								else
								{
									$("#applErrormsg").html();
								}
									/*if(data.status='errormsg')
								{

									$("#applErrormsg").html(data.message);
								}
								else if(data.status == "sucess") {
									$("#applErrormsg").hide();
									swal({
										title: data.message,

										icon: "success",
									})

									location.reload();
								} else if(data.status='fail') {
									swal({
										title: data.message,

										icon: "error",
									})
								}*/
							}
						});

					}
				} else {
					return false;
				}
			});

	})
	$('.btnClear').click(function() {
		var form = $(this).closest("form").attr('id');
		$sbmt_case = $("#sbmt_case").val();
		$sbmt_value = $("#sbmt_value").val();
		$sbmt_applicant = $("#sbmt_applicant").val();
		$sbmt_respondant = $("#sbmt_respondant").val();
		if ($sbmt_value == 'U') {
			$('#' + form).trigger("reset");
			$("#sbmt_value").val('A');
			$("#recpSubmit").val('Add List');
			$('#receiptNo').prop('readonly', false);
		} else if ($sbmt_applicant == 'U') {
			$('#' + form).trigger("reset");
			$("#advBarRegNo").attr("readonly", false);
			$(".title_sel4").css('display', 'block');
			$(".selection4").css('display', 'none');
			$("#advRegDistrict").val('');
			$("#advRegTaluk").val('');
			$("#sbmt_applicant").val('A');
			$("#saveApplicant").val('Add List');
			$.ajax({
				type: 'post',
				url: "getAppSrCount",
				data: {
					"_token": $('#token').val(),
					application_id: application_id
				},
				success: function(json1) {
					$("#serialcount").val(json1);
				}
			});

			$.ajax({
				type: 'post',
				url: "getLastSerialNo",
				dataType: "JSON",
				data: {
					"_token": $('#token').val(),
					application_id: application_id
				},
				success: function(json1) {
					//console.log(json1.applicantSrNo);
					//$("#applicantStartSrNo").val('');
					if (json1 == null) {
						$("#applicantStartSrNo").val('0');
					} else {
						$("#applicantStartSrNo").val(parseInt(json1.applicantsrno));
					}
				}
			});
			//var apllStart = $("#applStartNo").val();
			var application_id = $("#recAppId").val();
			var applYear = $("#applYear").val();
			$('#applIndexStartNo').val(apllStart);
			$("#applIndexId").val(application_id);
			var noOfApp = $("#noOfApp").val();
			$("#noOfAppCount").val(noOfApp);
		} else if ($sbmt_respondant == 'U') {
			$('#' + form).trigger("reset");
			$("#sbmt_respondant").val('A');
			$("#saveRespondant").val('Add List');
			$.ajax({
				type: 'post',
				url: "getResSrCount",
				data: {
					"_token": $('#token').val(),
					application_id: application_id
				},
				success: function(json1) {
					$("#resCount").val(json1);
				}
			});

			$.ajax({
				type: 'post',
				url: "getRsLastSerialNo",
				dataType: "JSON",
				data: {
					"_token": $('#token').val(),
					application_id: application_id
				},
				success: function(json1) {
					//console.log(json1.applicantSrNo);
					//$("#applicantStartSrNo").val('');
					if (json1 == null) {
						$("#resStartNo").val('0');
					} else {
						$("#resStartNo").val(parseInt(json1.respondsrno));
					}
				}
			});
			//var apllStart = $("#applStartNo").val();
			var application_id = $("#recAppId").val();
			var applYear = $("#applYear").val();
			//$('#resStartNo').val(apllStart);
			$("#resApplId").val(application_id);
			$("#resApplYear").val(applYear);
			var noOfres = $("#noOfRes").val();
			$("#noOfResCount").val(noOfres);
		} else {
			$('#' + form).trigger("reset");
			$("#advBarRegNo").attr("readonly", false);
			$(".title_sel4").css('display', 'block');
			$(".selection4").css('display', 'none');
			$("#advRegAdrr").val('');
			$("#advTitle").val('');
			$("#advRegPin").val('');
			$("#advRegDistrict").val('');
			$("#advRegTaluk").val('');
		}
	})
	
	$("#sbmt_relief").click(function() {
			if($("#reliefsought").val()=='')
			{
				$('#reliefsought').parsley().removeError('reliefsought');
								$('#reliefsought').parsley().addError('reliefsought', {
								message: "Enter Relief Sought"
								});
								return false;
			}
			else
			{
				$('#reliefsought').parsley().removeError('reliefsought');
			}
			if($("#reliefsought").val()!='')
			{
				var textfieldmask = /^[a-zA-Z0-9.`,-/ ()\n\r]+$/;
				  var testname = textfieldmask.test($("#reliefsought").val());
				  if (testname != true) {
            					 $('#reliefsought').parsley().removeError('reliefsought');
								$('#reliefsought').parsley().addError('reliefsought', {
								message: "Invalid Relief Sought"
								});
								return false;
       		 		 }
       		 		 else
       		 		 {
       		 		 	 $('#reliefsought').parsley().removeError('reliefsought');
       		 		 }
			}
			var apllStart = $("#applStartNo").val();
			var applYear = $("#applYear").val();
			var appltypename = $("#applTypeName").val();
			var reliefsought = $("#reliefsought").val();
			var reliefcount = $("#reliefcount").val();
			//console.log(reliefcount);
			var endno = parseInt($("#applEndNo").val());
			var startno = parseInt($("#applStartNo").val());
			var appl = parseInt($("#noOfAppl").val());
			var arr = appltypename.split('-');
			var relief_value = $("#relief_value").val();
		    var applicationid = $("#recAppId").val();

			$.ajax({
				type: 'POST',
				url: 'addRelief',
				dataType: 'JSON',
				data: {
					"_token": $('#token').val(),
					reliefsought: reliefsought,
					applId: applicationid,
					reliefcount: reliefcount,
					relief_value: relief_value,
					apllStart: apllStart,
					applYear: applYear,
					applType: arr[0],
					endno: endno
				},
				cache: false,
				success: function(response) {
					
						$("#reliefsought").val('');
						//$("#reliefcount").val('');
						$("#relief_value").val('A');
						$("#sbmt_relief").text('Add');

					$.ajax({
						type: 'post',
						url: "getRelief",
						data: {
							"_token": $('#token').val(),
							applId: applicationid,
							relief_value:relief_value
						},
						dataType:'JSON',
						success: function(response) {
							//$('#myTable').DataTable().ajax.reload();
							getReliefTable(response);

							$(".reliefClick").click(function() {
								var srno = $(this).attr('data-value');
								var newarr = srno.split('-');
								var newReliefSrNo = newarr[0];
								var applId = newarr[1];
								//console.log(applId);
							//	console.log(newReliefSrNo);
								$.ajax({
									type: 'post',
									url: "getreliefData",
									dataType: "JSON",
									data: {
										"_token": $('#token').val(),
										newSrno: newReliefSrNo,
										applId: applId
									},
									success: function(json1) {
										for (var i = 0; i < json1.length; i++) {
											$("#reliefcount").val(json1[i].reliefsrno);
											$("#reliefsought").val(json1[i].relief);
											$("#relief_value").val('UP');
											$("#sbmt_relief").text('Update');
											$("#refapplId").val(json1[i].applicationid);
										}
									}
								});
							});
						}
					});
				}
			});
		})
       
function getReliefTable(response)
    {
      $('#myTable').find('tbody tr').remove();
      var count = 1;
      var rowcount = response.length;
      $.each(response, function(index, obj) {
                  var row = $('<tr>');
                  //  row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
                  row.append('<td class="col-sm-1"><a href="#" class="reliefClick" data-value="'+obj.reliefsrno+'-'+obj.applicationid+'">' + obj.reliefsrno + '</td>');
                  row.append('<td class="col-sm-10">' +obj.relief + '</td>');
                  if(obj.reliefsrno!=1)
                  {
                     row.append('<td class="col-md-2"><a href="#" class="deleterelief btn btn-sm btn-danger"  type="button" data-value="' + obj.reliefsrno+'-'+obj.applicationid + '">X</a></td>');
                  }
                  else
                  {
                     row.append('<td class="col-md-2"></td>');
                  }
                  $('#myTable').append(row)
                  count++;
                    });
      $(".deleterelief").click(function(){
         var reliefsrno = $(this).attr('data-value');
                var split = reliefsrno.split('-');
                var reliefsrno1 = split[0];
                var applId =split[1];
          $.ajax({
        type: 'POST',
        url: 'deleterelief',
        dataType: 'JSON',
        data:{"_token": $('#token').val(),reliefsrno:reliefsrno1,applicationid:applId},
        cache: false,
        success: function(response) {
          if(response.status=="sucess")
          {
            var relief_value='';
            $.ajax({
            type: 'POST',
            url: 'getRelief',
            dataType: 'JSON',
            data:{"_token": $('#token').val(),relief_value:relief_value,applId:applId},
            cache: false,
            success: function(response) {
            getReliefTable(response);
            }});
            }
          else
          {
           swal({
            title:response.message,
            icon: "error",
            });
          }
        }
        });
        });
    }
    $("#applTypeName").change(function() {
		var type = $(this).val();
		var typ2 = $("#applTypeName option:selected").html();
		var newtype = type.split('-');
		var applnewtype = newtype[1];
		var applref = newtype[3];
		if (applref != 'N' && type !='') {
			$('#modal-default').modal('show');
			$('#appl-title').text(typ2);
			$("#modl_appldate").val('');
			$("#saveOtherAppl").prop("disabled", true);
			$("#modl_regdate").val('');
			$("#modl_subject").val('');
			$("#modl_disposedate").val('');
			$("#modl_applno").val('');
			$("#displAppl1").hide();
			$("#displAppl2").hide();
			$("#divApplicant").show();
			$("#divRespondant").show();
		}
		else{
			$("#divApplicant").hide();
			$("#divRespondant").hide();
		}

	})
	$("#applSearch").click(function() {
		if ($("#modl_applno").val() == '') {
			$('#modl_applno').parsley().removeError('modl_applno');
			$('#modl_applno').parsley().addError('modl_applno', {
				message: "Enter Application No"
			});
			return false;
		} else {
			$('#modl_applno').parsley().removeError('modl_applno');
		}
		var modl_appltype_name = $("#modl_appltype_name").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#modl_applno").val();
		var applId = applnewtype+'/'+ modl_modl_applno;
		var flag='application';
		$.ajax({
			type: 'POST',
			url: 'getApplicationDetailsDisposed',
			data: {
				"_token": $('#token').val(),
				application_id: applId,flag:flag
			},
			dataType: "JSON",
			success: function(json) {
				if (json.length > 0) {
					//console.log(json.length);
					$("#displAppl1").show();
					$("#displAppl2").show();
					$("#saveOtherAppl").removeAttr('disabled');
					for (var i = 0; i < json.length; i++) {
						//console.log(json[i].registerdate);
						if (json[i].registerdate === null) {
							$("#modl_regdate").val('');
						} else {
							var dor = json[i].registerdate;
							var dor_split = dor.split('-');
							var dateOfReg = dor_split[2] + '-' + dor_split[1] + '-' + dor_split[0];
							$("#modl_regdate").val(dateOfReg);
						}
						if (json[i].applicationdate == null) {
							$("#modl_appldate").val('');
						} else {
							var doa = json[i].applicationdate;
							var doa_split = doa.split('-');
							var dateOfApp = doa_split[2] + '-' + doa_split[1] + '-' + doa_split[0];
							$("#modl_appldate").val(dateOfApp);
						}
						//$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
						//$("#applCatName").val(json[i].applcategory);
						$("#modl_disposedate").val('');
						$("#modl_subject").val(json[i].subject);
					}
				} else {
					$("#modl_applno").val('');
					$("#displAppl1").hide();
					$("#displAppl2").hide();
					swal({
						title: "Application Does Not Exist",
						icon: "error"
					})
				}
			}
		});

	})

$("#saveOtherAppl").click(function() {
		if ($("#modl_applno").val() == '') {
			$('#modl_applno').parsley().removeError('modl_applno');
			$('#modl_applno').parsley().addError('modl_applno', {
				message: "Enter Application No"
			});
			return false;
		} else {
			$('#modl_applno').parsley().removeError('modl_applno');
		}
		var modl_appltype_name = $("#modl_appltype_name").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#modl_applno").val();
		var applId = applnewtype+'/' + modl_modl_applno;
//mini
		/*$.ajax({
			type: 'POST',
			url: 'getApplicantRespondantDetails',
			data: {
				"_token": $('#token').val(),
				application_id: applId
			},
			dataType: "JSON",
			success: function(json) {
				for(var i=0;i<json[0].length;i++){
					var option = '<option value="'+json[0][i].applicantsrno+'-'+applId+'">'+json[0][i].applicantname+'</option>';
	  				$('#applicantDetails').append(option);
					 }
				for(var i=0;i<json[1].length;i++){
					var option = '<option value="'+json[1][i].respondsrno+'-'+applId+'">'+json[1][i].respondname+'</option>';
	  				$('#respondantDetails').append(option);
					 }


			}
		});
*/
		$("#reviewAppl").text(applId);
		$("#reviewApplId").val(applId);
		$("#reviewApplId1").val(applId);
		$('#modal-default').modal('hide');
	})

//onchangeapplicant
	$("#applicantDetails1").change(function() {
   		var newSrno1 = $(this).val();
		var newSrnoarr = newSrno1.split('-');
		var newApllSrno = newSrnoarr[0];
		var newApplid = newSrnoarr[1];
		$.ajax({
		type: 'post',
		url: "getApplicantData",
		dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				newSrno: newApllSrno,
				applicationid: newApplid
			},
	success: function(json1) {
			for (var i = 0; i <= json1.length; i++) {
				//$('#isAdvocate').find(':radio[name=isAdvocate][value="'+json1[i].issingleadv+'"]').prop('checked', true);
				$(".advancedSearch2 .selection2").text(json1[i].nametitle);
				$(".title_sel2").css('display', 'none');
				$(".advancedSearch3 .selection3").text(json1[i].relationtitle);
				$(".title_sel3").css('display', 'none');
				// console.log(json1[i].nametitle);
				$('input[name^="isAdvocate"][value="' + json1[i].issingleadv + '"').prop('checked', true);
			    $("#applicantTitle").val(json1[i].nametitle);
				$("#applicantName").val(json1[i].applicantname);
				$("#relationTitle").val(json1[i].relationtitle);
				$("#relationName").val(json1[i].relationname);
				$("#relationType").val(json1[i].relation);
				$("#gender").val(json1[i].gender);
				$("#applAge").val(json1[i].applicantage);
				$("#applDeptType").val(json1[i].depttype);
				$("#nameOfDept").val(json1[i].departcode);
				$("#desigAppl").val(json1[i].desigcode);
				$("#addressAppl").val(json1[i].applicantaddress);
				$("#pincodeAppl").val(json1[i].applicantpincode);
				$("#talukAppl").val(json1[i].talukcode);
				$("#districtAppl").val(json1[i].districtcode);
				$("#applMobileNo").val(json1[i].applicantmobileno);
				$("#applEmailId").val(json1[i].applicantemail);
				$('input[name^="partyInPerson"][value="' + json1[i].partyinperson + '"').prop('checked', true);
				$('input[name^="isMainParty"][value="' + json1[i].ismainparty + '"').prop('checked', true);
				// $("#isMainParty").val(json1[i].ismainparty);
				// $("#advBarRegNo").val();
					var partyinperson = $("input[type=radio][name='partyInPerson']:checked").val();
					if (partyinperson == 'Y') {
						$(".advDetails").hide();
						$("#advBarRegNo").attr('data-parsley-required', false);
					} else {
					$(".advDetails").show();
					$("#advBarRegNo").attr('data-parsley-required', true);
					value = $("#advBarRegNo").val(json1[i].advocateregno);
					getBarregDetails(value);
				}
				}
				}
			});
})

$("#applicantDetails2").change(function() {
   		var newSrno1 = $(this).val();
		var newSrnoarr = newSrno1.split('-');
		var newApllSrno = newSrnoarr[0];
		var newApplid = newSrnoarr[1];
		$.ajax({
		type: 'post',
		url: "getApplicantData",
		dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				newSrno: newApllSrno,
				applicationid: newApplid
			},
	success: function(json1) {
		for (var i = 0; i <= json1.length; i++) {
				$('#isResAdvocate').find(':radio[name=isResAdvocate][value="'+json1[i].issingleadvocate+'"]').attr('checked', true);
				$(".advancedSearch5 .selection5").text(json1[i].nametitle);
				$(".title_sel5").css('display', 'none');
				$("#respondantTitle").val(json1[i].nametitle);
				$("#resReltaion").val(json1[i].relation);
				$("#respondantName").val(json1[i].applicantname);
				$(".advancedSearch6 .selection6").text(json1[i].relationtitle);
				$(".title_sel6").css('display', 'none');
				$("#resRelTitle").val(json1[i].relationtitle);
				$("#resRelName").val(json1[i].relationname);
				$("#resGender").val(json1[i].gender);
				$("#resAge").val(json1[i].applicantage);
				//$('select[name^="resReltaion"] option[value="'+relvalue+'"]').attr("selected","selected");
				$("#resDeptType").val(json1[i].depttype);
				$("#resnameofDept").val(json1[i].departcode);
				$("#resDesig").val(json1[i].desigcode);
				$("#resAddress2").val(json1[i].applicantaddress);
				$("#respincode2").val(json1[i].applicantpincode);
				$("#resTaluk").val(json1[i].talukcode);
				$("#resDistrict").val(json1[i].districtcode);
				$("#resMobileNo").val(json1[i].applicantmobileno);
				$("#resEmailId").val(json1[i].applicantemail);
				if(json1[i].isgovtadvocate=='Y')
				{
					$('input[name^="isGovtAdv"][value="' + json1[i].isgovtadvocate + '"').prop('checked', true)
				}
				else
				{
					$('input[name^="isGovtAdv"][value="' + json1[i].isgovtadvocate + '"').prop('checked', false)
				}
			    $('input[name^="isMainRes"][value="' + json1[i].ismainrespond + '"').prop('checked', true);
				/*$("#isGovtAdv").val(json1[i].isgovtadvocate);
				$("#isMainRes").val(json1[i].ismainrespond);*/
				$("#resadvBarRegNo").val(json1[i].advocateregno);
				  value = $("#resadvBarRegNo").val(json1[i].advocateregno);
				getResBarRegDetails(value);
				}
				}
			});
})

$("#respondantDetails1").change(function() {
   		var newSrno1 = $(this).val();
		var newSrnoarr = newSrno1.split('-');
		var newApllSrno = newSrnoarr[0];
		var newApplid = newSrnoarr[1];
		$.ajax({
		type: 'post',
		url: "getRespondantData",
		dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				newSrno: newApllSrno,
				applicationid: newApplid
			},
	  success: function(json1) {
			for (var i = 0; i <= json1.length; i++) {
				//$('#isAdvocate').find(':radio[name=isAdvocate][value="'+json1[i].issingleadv+'"]').prop('checked', true);
				$(".advancedSearch2 .selection2").text(json1[i].respondtitle);
				$(".title_sel2").css('display', 'none');
				$(".advancedSearch3 .selection3").text(json1[i].relationtitle);
				$(".title_sel3").css('display', 'none');
			    $('input[name^="isAdvocate"][value="' + json1[i].issingleadv + '"').prop('checked', true);
 			    $("#applicantTitle").val(json1[i].respondtitle);
				$("#applicantName").val(json1[i].respondname);
				$("#relationTitle").val(json1[i].relationtitle);
				$("#relationName").val(json1[i].relationname);
				$("#relationType").val(json1[i].relation);
				$("#gender").val(json1[i].gender);
				$("#applAge").val(json1[i].respondantage);
				$("#applDeptType").val(json1[i].respontdepttype);
				$("#nameOfDept").val(json1[i].respontdeptcode);
				$("#desigAppl").val(json1[i].desigcode);
				$("#addressAppl").val(json1[i].respondaddress);
				$("#pincodeAppl").val(json1[i].respondpincode);
				$("#talukAppl").val(json1[i].respondtaluk);
				$("#districtAppl").val(json1[i].responddistrict);
				$("#applMobileNo").val(json1[i].respondmobileno);
				$("#applEmailId").val(json1[i].respondemail);
				$('input[name^="partyInPerson"][value="' + json1[i].partyinperson + '"').prop('checked', true);
				$('input[name^="isMainParty"][value="' + json1[i].ismainparty + '"').prop('checked', true);
				// $("#isMainParty").val(json1[i].ismainparty);
				// $("#advBarRegNo").val();
					var partyinperson = $("input[type=radio][name='partyInPerson']:checked").val();
                 if (partyinperson == 'Y') {
                       $(".advDetails").hide();
					   $("#advBarRegNo").attr('data-parsley-required', false);
					} else {
                    $(".advDetails").show();
					$("#advBarRegNo").attr('data-parsley-required', true);
					value = $("#advBarRegNo").val(json1[i].advocateregno);
                    getBarregDetails(value);
				}
				}
				}
			});
})

//onchangerespondant
	$("#respondantDetails2").change(function() {
   		var newSrno1 = $(this).val();
		var newSrnoarr = newSrno1.split('-');
		var newApllSrno = newSrnoarr[0];
		var newApplid = newSrnoarr[1];
		$.ajax({
		type: 'post',
		url: "getRespondantData",
		dataType: "JSON",
			data: {
				"_token": $('#token').val(),
				newSrno: newApllSrno,
				applicationid: newApplid
			},
	success: function(json1) {
			for (var i = 0; i <= json1.length; i++) {
				$('#isResAdvocate').find(':radio[name=isResAdvocate][value="'+json1[i].issingleadvocate+'"]').attr('checked', true);
				$(".advancedSearch5 .selection5").text(json1[i].respondtitle);
				$(".title_sel5").css('display', 'none');
				$("#respondantTitle").val(json1[i].respondtitle);
				$("#resReltaion").val(json1[i].relation);
				$("#respondantName").val(json1[i].respondname);
				$(".advancedSearch6 .selection6").text(json1[i].relationtitle);
				$(".title_sel6").css('display', 'none');
				$("#resRelTitle").val(json1[i].relationtitle);
				$("#resRelName").val(json1[i].relationname);
				$("#resGender").val(json1[i].gender);
				$("#resAge").val(json1[i].respondantage);
				//$('select[name^="resReltaion"] option[value="'+relvalue+'"]').attr("selected","selected");
				$("#resDeptType").val(json1[i].respontdepttype);
				$("#resnameofDept").val(json1[i].respontdeptcode);
				$("#resDesig").val(json1[i].desigcode);
				$("#resAddress2").val(json1[i].respondaddress);
				$("#respincode2").val(json1[i].respondpincode);
				$("#resTaluk").val(json1[i].respondtaluk);
				$("#resDistrict").val(json1[i].responddistrict);
				$("#resMobileNo").val(json1[i].respondmobileno);
				$("#resEmailId").val(json1[i].respondemail);
				if(json1[i].isgovtadvocate=='Y')
				{
					$('input[name^="isGovtAdv"][value="' + json1[i].isgovtadvocate + '"').prop('checked', true)
				}
				else
				{
					$('input[name^="isGovtAdv"][value="' + json1[i].isgovtadvocate + '"').prop('checked', false)
				}
				$('input[name^="isMainRes"][value="' + json1[i].ismainrespond + '"').prop('checked', true);
				/*$("#isGovtAdv").val(json1[i].isgovtadvocate);
				$("#isMainRes").val(json1[i].ismainrespond);*/
				$("#resadvBarRegNo").val(json1[i].advocateregno);
				  value = $("#resadvBarRegNo").val(json1[i].advocateregno);
				getResBarRegDetails(value);
				}
				}
			});
})

	$(".dropdown_all2 li").on('click', function() {
		var sort = $(this).find('a').attr('value');
		var gender = sort.split('-');
		var gender1 = gender[1];
		$(".gender").val(gender1).attr('selected');
	})
	$(".dropdown_all5 li").on('click', function() {
		var sort = $(this).find('a').attr('value');
		var gender = sort.split('-');
		var gender1 = gender[1];
		$(".resGender").val(gender1).attr('selected');
	})
});




function minmax(value, min, max) 
	{
	//	if(parseInt(value) < min || isNaN(parseInt(value))) 
			//return min; 
		//else 
			if(parseInt(value) > max) 
			return max; 
		else return value;
	}
