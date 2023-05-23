$(document).ready(function(){
	$("#multiorder").css('pointer-events', 'none');
	$('.nav-tabs li').not('.active').addClass('disabled');
	$('.nav-tabs li').not('.active').find('a').removeAttr("data-toggle");
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
		$('#edit_applno').val('');
			$('#edit_appl-title').text('Edit Application');




	})


$('.btnClear').click(function(){
	$("#caveat").trigger('reset');
    $("#sbmt_case").val('A');
})


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

	$("#editSearch").click(function(){

		var user = $("#username").text();
		var estcode = $('#estcode').val();
		var type = $("#edit_modal_type").val();
		var srno = $("#edit_applno").val();
		//var newtype = type.split('-');
		//var applId = newtype[1]+'/'+$("#edit_applno").val();
	//	var est = estcode+'/C'+srno;
		var est = srno;
		//alert(est);
		var _token = $('input[name="_token"]').val();
		//alert(_token);
		///console.log(applId);
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getCaveatDetails',
				data: {_token:_token,applicationid:est,user:user},
				success: function(data) {
					//alert('workign');
					if(data.status=='success')
					{
						//alert('workign');
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

$("#distcode").change(function(){
	var distCode = $(this).val();
 var _token = $('input[name="_token"]').val();
	$.ajax({
        type: 'post',
        url: 'getTaluk',
        dataType:'JSON',
        data:  { "_token": _token,distCode:distCode},
        success: function (data) {
        $('#talukname').empty();
		//alert(data.length);
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].talukcode+'">'+data[i].talukname+'</option>';
  						$('#talukname').append(option);
				 }
        	}
        });
})



$("#adistcode").change(function(){
	var distCode = $(this).val();
 var _token = $('input[name="_token"]').val();
	$.ajax({
        type: 'post',
        url: 'getTaluk',
        dataType:'JSON',
        data:  { "_token": _token,distCode:distCode},
        success: function (data) {
        $('#taluknameApp').empty();
		//alert(data.length);
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].talukcode+'">'+data[i].talukname+'</option>';
  						$('#taluknameApp').append(option);
				 }
        	}
        });
})

$("#rdistcode").change(function(){
	var distCode = $(this).val();
 var _token = $('input[name="_token"]').val();
	$.ajax({
        type: 'post',
        url: 'getTaluk',
        dataType:'JSON',
        data:  { "_token": _token,distCode:distCode},
        success: function (data) {
        $('#taluknameRes').empty();
		//alert(data.length);
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].talukcode+'">'+data[i].talukname+'</option>';
  						$('#taluknameRes').append(option);
				 }
        	}
        });
})


	$("#resDeptType").change(function(){
		var typeval = $(this).val();
		var _token = $('input[name="_token"]').val();
		$.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token": _token,typeval:typeval},
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

$("#resnameofDept").change(function(){
		var typeval = $(this).val();
		var _token = $('input[name="_token"]').val();
		$.ajax({
        type: 'post',
        url: 'getDesignationByDepartment',
        dataType:'JSON',
        data:  { "_token": _token,typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
         $('#desigRes').find('option:not(:first)').remove();
                	 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
  						$('#desigRes').append(option);
				 }
        	}
        });
	})


$("#depttypecode").change(function(){
		var typeval = $(this).val();
		var _token = $('input[name="_token"]').val();
    	$.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token":_token,typeval:typeval},
        success: function (data) {
        	//var option = "<option value=''>Select Option</option>";
        $('#departmentcode').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].departmentcode+'">'+data[i].departmentname+'</option>';
  						$('#departmentcode').append(option);
				 }
        	}
        });
	})

$("#departmentcode").change(function(){
		var typeval = $(this).val();
		var _token = $('input[name="_token"]').val();
		$.ajax({
        type: 'post',
        url: 'getDesignationByDepartment',
        dataType:'JSON',
        data:  { "_token": _token,typeval:typeval},
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


	//assinging year to applyear textbox
	$("#dateOfAppl").change(function() {
		var doa = $("#dateOfAppl").val();
		var split = doa.split("-");
		$("#datepicker1").val(split[2]);
	});

	$("#dateOfAppl").change(function() {
		var doa = $("#dateOfAppl").val();
		var split = doa.split("-");
		$("#datepicker1").val(split[2]);
	});

	$("#dateOfAppl").change(function(){
			var doa = $("#dateOfAppl").val();
			var split = doa.split("-")
			var year=split[2];
			var _token = $('input[name="_token"]').val();
			$.ajax({
	        type: 'post',
	        url: 'getcaveatstartno',
	        dataType:'JSON',
	        data:  { "_token": _token,year:year},
	        success: function (data) {
	        	//var option = "<option value=''>Select Option</option>";
						$("#applStartNo").val(data);



					 }

	        });
		})


	$("#resetorder").click(function(){
		$("#multiorder").val('');
	})

	$("#applnSubject").keyup(function() {
			var maxLength = 300;
			var textlen = maxLength - $(this).val().length;
			$('#sub_rchars').text(textlen);
		})

		$("#addrForService").keyup(function() {
			var maxLength = 150;
			var textlen = maxLength - $(this).val().length;
			$('#rchars').text(textlen);
		})
		$("#addressAppl").keyup(function() {
			var maxLength = 150;
			var textlen = maxLength - $(this).val().length;
			$('#Appchars').text(textlen);
		})
		$("#resAddress2").keyup(function() {
			var maxLength = 150;
			var textlen = maxLength - $(this).val().length;
			$('#Reschars').text(textlen);
		})

 $('#depttypecode').change(function(){
  $('#depname').val('');

 });

	$('#btnNext').click(function(e) {

		var estname = $('#caveat').serialize();
		var estcode = $('#estcode').val();
		if ($("#dateOfAppl").val() == '') {
			$('#dateOfAppl').parsley().removeError('dateOfAppl');
			$('#dateOfAppl').parsley().addError('dateOfAppl', {
				message: "Enter Date of Application"
			});
			return false;
		} else {
			$('#dateOfAppl').parsley().removeError('dateOfAppl');
		}
		if ($("#noOfAppl").val() == '' || $("#noOfAppl").val() < 1) {
			$('#noOfAppl').parsley().removeError('noOfAppl');
			$('#noOfAppl').parsley().addError('noOfAppl', {
				message: "Enter Minimum No Of Caveators"
			});
			return false;
		} else {
			$('#noOfAppl').parsley().removeError('noOfAppl');
		}
		if ($("#noOfRes").val() == '' || $("#noOfRes").val()< 1) {
			$('#noOfRes').parsley().removeError('noOfRes');
			$('#noOfRes').parsley().addError('noOfRes', {
				message: "Enter Minimum No Of Caveatees"
			});
			return false;
		} else {
			$('#noOfRes').parsley().removeError('noOfRes');
		}
		if ($("#applnSubject").val() == '') {
			$('#applnSubject').parsley().removeError('applnSubject');
			$('#applnSubject').parsley().addError('applnSubject', {
				message: "Enter Subject"
			});
			return false;
		} else {
			$('#applnSubject').parsley().removeError('applnSubject');
		}

		var pin = $('#rPincode').val();
		if(pin.length != 6){
			$('#rPincode').parsley().removeError('rPincode');
			$('#rPincode').parsley().addError('rPincode', {
				message: "Enter 6 digit Pincode"
			});
			return false;
		} else {
			$('#rPincode').parsley().removeError('rPincode');
		}

		if ($("#distcode").val() == '') {
			$('#distcode').parsley().removeError('distcode');
			$('#distcode').parsley().addError('distcode', {
				message: "Select District"
			});
			return false;
		} else {
			$('#distcode').parsley().removeError('distcode');
		}
		if ($("#talukname").val() == '') {
			$('#talukname').parsley().removeError('talukname');
			$('#talukname').parsley().addError('talukname', {
				message: "Select Taluk"
			});
			return false;
		} else {
			$('#talukname').parsley().removeError('talukname');
		}

		var appsrno = $('#applStartNo').val();
		var applYear = $("#datepicker1").val();
		var estcode = $("#estcode").val();
		var applId = estcode+'/C'+appsrno +'/'+ applYear;
		var sbmt_case = $("#sbmt_case").val();
		$.ajax({
		url:"caveatstore",
		method:"POST",
		data:estname,
		success:function(result)
		{
		 $("#recAppId").val(applId);
			var data1 = $("#myTab li.active a").prop("href");
		 var data = $("#myTab li.active a").prop("href");
		 var url = data.substring(data.indexOf('#'));
		if(sbmt_case == 'A'){
				swal({
									title: " Generated Successfully Caveat ID: "+applId,

									icon: "success",
								});
		 }else{
			    swal({
									title: " Updated Successfully Caveat ID: "+applId,

									icon: "success",
								});
		 }
		 $('.nav-tabs li.active').next('li').removeClass('disabled');
		 $('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
		 $('.nav-tabs > .active').next('li').find('a').trigger('click');
		}

		})

	});
	$('.btnPrevious').click(function() {
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');

	/*	$("#" + form).parsley().validate();
		if ($("#" + form).parsley().isValid()) {*/
			if(form=='applicantForm')
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

			var sbmt_case = $("#sbmt_case").val('U');


		/*}*/
	});
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
	$('#advsearch').click(function(){
		var search = $("#search").val();
		if(search!= ''){
		$.ajax({
		url:"/caveatsearch",
		method:"GET",
		data:search,
		success:function(data)
		{
		 $('tbody').html(data);
		}

		})
		}

	})
	$("#advBarRegNo").bind('input', function() {

		var value = $("#advBarRegNo").val();
		//alert(value);
		getBarregDetails(value);
		//var text = $("#browsers").find('option[value=' + value + ']').text();


	});

	function getBarregDetails(value) {
		var _token = $('input[name="_token"]').val();
		$.ajax({
			type: 'POST',
			url: 'advRegNoApp',
			data: {
				"_token":_token,
				value: value
			},

			dataType: "JSON",
			success: function(json) {
				//console.log(json);
				for (var i = 0; i < json.length; i++) {
					//console.log(json[i].nameTitle);
					$(".advancedSearch4 .selection4").text(json[i].nametitle);
					$(".title_sel4").css('display', 'none');
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
	$('input[type=radio][name=isgovtadv]').on('change', function() {
		/*var issingl = $("input[type=radio][name='isAdvocate']:checked").val();*/
		var isGovtAdv = $("input[type=radio][name='isgovtadv']:checked").val();

		if (isGovtAdv == 'Y') {

			$(".resadvDatails").hide();
			$("#resadvBarRegNo").attr('data-parsley-required', false);
		} else {

			$(".resadvDatails").show();
			$("#resadvBarRegNo").attr('data-parsley-required', true);
		}

	})
	$("#resadvBarRegNo").bind('input', function() {

		var value = $("#resadvBarRegNo").val();
		getBarregDetailsRes(value);
		//var text = $("#browsers").find('option[value=' + value + ']').text();


	});
	function getBarregDetailsRes(value) {
		var _token = $('input[name="_token"]').val();
		$.ajax({
			type: 'POST',
			url: 'advRegNoApp',
			data: {
				"_token":_token,
				value: value
			},

			dataType: "JSON",
			success: function(json) {
				//console.log(json);
				for (var i = 0; i < json.length; i++) {
					//console.log(json[i].nameTitle);
					$(".advancedSearch7 .selection7").text(json[i].nametitle);
					$(".title_sel7").css('display', 'none');
					$("#advTitle").val(json[i].nametitle);
					$("#respAdvName").val(json[i].advocatename);
					$("#resadvaddr").val(json[i].advocateaddress);
					$("#resadvtaluk").attr('disabled', false);
					$("#resadvdistrict").attr('disabled', false);
					$("#resadvtaluk").empty();
					$("#resadvdistrict").empty();
					$("#resadvtaluk").append('<option value="'+ json[i].talukcode +'"selected>' + json[i].talukname + '</option>');
					$("#resadvdistrict").append('<option value="' + json[i].distcode + '"selected>' + json[i].distname + '<option>');
					$("#resadvpincode").val(json[i].pincode);
				}
			}
		});
	}


	$("#saveApplicant").click(function(e) {
		//$("#saveApplicant").attr("disabled", true);
		var capplicant = $('#caveatApplicant').serialize();
		var application_id = $('#recAppId').val();

		if ($("#applicantName").val() == '') {
			$('#applicantName').parsley().removeError('applicantName');
			$('#applicantName').parsley().addError('applicantName', {
				message: "Enter Caveator Name"
			});
			return false;
		} else {
			$('#applicantName').parsley().removeError('applicantName');
		}
		if ($("#relType").val() == '') {
			$('#relType').parsley().removeError('relType');
			$('#relType').parsley().addError('relType', {
				message: "Enter Caveator Relation"
			});
			return false;
		} else {
			$('#relType').parsley().removeError('relType');
		}
		if ($("#relationName").val() == '') {
			$('#relationName').parsley().removeError('relationName');
			$('#relationName').parsley().addError('relationName', {
				message: "Enter Caveator Relation Name"
			});
			return false;
		} else {
			$('#relationName').parsley().removeError('relationName');
		}
		if ($("#gender").val() == '') {
			$('#gender').parsley().removeError('gender');
			$('#gender').parsley().addError('gender', {
				message: "Enter Caveator Gender"
			});
			return false;
		} else {
			$('#gender').parsley().removeError('gender');
		}
		if ($("#applAge").val() == '') {
			$('#applAge').parsley().removeError('applAge');
			$('#applAge').parsley().addError('applAge', {
				message: "Enter Caveator Age"
			});
			return false;
		} else {
			$('#applAge').parsley().removeError('applAge');
		}

		if ($("#depttypecode").val() == '') {
			$('#depttypecode').parsley().removeError('depttypecode');
			$('#depttypecode').parsley().addError('depttypecode', {
				message: "Enter Caveator Department Type"
			});
			return false;
		} else {
			$('#depttypecode').parsley().removeError('depttypecode');
		}
		if ($("#departmentcode").val() == '') {
			$('#departmentcode').parsley().removeError('departmentcode');
			$('#departmentcode').parsley().addError('departmentcode', {
				message: "Enter Caveator Department"
			});
			return false;
		} else {
			$('#departmentcode').parsley().removeError('departmentcode');
		}

		if ($("#desigAppl").val() == '') {
			$('#desigAppl').parsley().removeError('desigAppl');
			$('#desigAppl').parsley().addError('desigAppl', {
				message: "Enter Caveator designation"
			});
			return false;
		} else {
			$('#desigAppl').parsley().removeError('desigAppl');
		}

		if ($("#adistcode").val() == '') {
			$('#adistcode').parsley().removeError('adistcode');
			$('#adistcode').parsley().addError('adistcode', {
				message: "Select District"
			});
			return false;
		} else {
			$('#adistcode').parsley().removeError('adistcode');
		}
		if ($("#taluknameApp").val() == '') {
			$('#taluknameApp').parsley().removeError('taluknameApp');
			$('#taluknameApp').parsley().addError('taluknameApp', {
				message: "Select Taluk"
			});
			return false;
		} else {
			$('#taluknameApp').parsley().removeError('taluknameApp');
		}
		var partyinperson = $("input[type=radio][name='partyInPerson']:checked").val();
		if (partyinperson == 'N') {

			if ($("#advBarRegNo").val() == '') {
			$('#advBarRegNo').parsley().removeError('advBarRegNo');
			$('#advBarRegNo').parsley().addError('advBarRegNo', {
				message: "Select Advocate"
			});
			return false;
			} else {
				$('#advBarRegNo').parsley().removeError('advBarRegNo');
			}
		}
		var sbmt_applicant = $("#sbmt_applicant").val();
		var count = $("#applicant_tab > tbody > tr").length;
		var appl = parseInt($("#noOfAppl").val());
		if (count < appl && sbmt_applicant == 'A') {
			swal({
				title: "Are you sure to Save?",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete)=> {
					if(willDelete){
				var form = $(this).closest("form").attr('id');
				$("#" + form).parsley().validate();
				if ($("#" + form).parsley().isValid()) {
			$.ajax({
			type:"POST",
			url:"caveatapplicant",
			data:capplicant,
			success: function(result) {
				$("#resApplId").val(application_id);
				
				//by nic
				
				 $.ajax({
           type:'POST',
           url:'caveatsearch',
           data:{application_id:application_id},
		   dataType: "JSON",
           success:function(data){
             console.log(data);
              var len = data['data'].length;
			  var id = data.data[0].caveatid;
			  console.log(len);
			  console.log(id);
            $("#applicant_tab tbody").empty(); // Empty <tbody>
				if(len > 0){
					for(var i=0; i<len; i++){
						var SRNO = data.data[i].applicantsrno;
						var APPLICATIONID = data.data[i].caveatid;
						var APPLICANTNAME = data.data[i].applicantname;
						var ADVOCATE = data.data[i].advocateregnno;

					   var tr_str = "<tr>" +
						   "<td align='center'><a href='#' data-value='"+SRNO+"-"+APPLICATIONID+"' class='applicantClick'>" + SRNO + "</td>" +
						   "<td align='center'>" + APPLICATIONID + "</td>" +
						   "<td align='center'>" + APPLICANTNAME + "</td>" +
						   "<td align='center'>" + ADVOCATE + "</td>" +
					   "</tr>";

						$("#applicant_tab tbody").append(tr_str);
                                            //    $('#caveatApplicant').ajax.reload();
						$("#caveatApplicant").trigger("reset");

					}
				}

			   	$(".applicantClick").click(function(){
                $("#sbmt_applicant").val('U');
                $("#saveApplicant").val('Update List');
                var newSrno1  = $(this).attr('data-value');
                var newSrnoarr = newSrno1.split('-');
                var newApllSrno = newSrnoarr[0];
                var newApplid = newSrnoarr[1];
                var _token = $('input[name="_token"]').val();
				$("#srno_applicant").val(newApllSrno);

                $.ajax({
                type: 'post',
                url: "getCaveatApplicantData",
                dataType:"JSON",
                data: {"_token": _token,newSrno:newApllSrno,applicationid:newApplid},
                success: function (data) {
					var len = data.length;
					for(var i=0;i<=data.length;i++){
                    var applname = data[i].applicantname;
					$("#applicantName").val(data[i].applicantname);
					$("#relType").val(data[i].reltype);
					$("#relationName").val(data[i].relationname);
					$("#gender").val(data[i].gender);
					$("#depttypecode").val(data[i].depttype);
					$("#addressAppl").val(data[i].caveataddress);
					$("#pincodeAppl").val(data[i].caveatpincode);
					$("#adistcode").val(data[i].districtcode);
					var option = '<option value="'+data[i].talukcode+'" selected>'+data[i].talukname+'</option>';
					$('#taluknameApp').append(option);
					//$("#taluknameApp").val(data[i].talukcode);
					$("#applMobileNo").val(data[i].caveatmobileno);
					$("#applEmailId").val(data[i].caveatemail);
					$("#advBarRegNo").val(data[i].advocateregnno);
					$("#applAge").val(data[i].age);
					$("#desigAppl").val(data[i].desigcode);
					var option1 = '<option value="'+data[i].departcode+'" selected>'+data[i].departmentname+'</option>';
					$("#departmentcode").append(option1);
					//$("#Appdepart").apend('<option value="'+data[i].departcode+'" selected>'+data[i].departmentname+'</option>');
					$(".advancedSearch2 .selection2").text(data[i].applicanttitle);
                      $(".title_sel2").css('display','none');

                      $(".advancedSearch3 .selection3").text(data[i].relationtitle);
                      $(".title_sel3").css('display','none');
                      var pinp= data[i].partyinperson;

						if(pinp == 'N')
						{
						var value = $("#advBarRegNo").val();
						getBarregDetails(value);
						document.getElementById('partyInPerson').checked = true;
						$(".advDetails").show();
						}else{
							document.getElementById('partyInPersonY').checked = true;
							$(".advDetails").hide();
						}

                    }
                }
                });

              });

			  function getBarregDetails(value)
			  {
					//console.log(value);
					var _token = $('input[name="_token"]').val();

					 $.ajax({
					  type: 'POST',
					  url: 'advRegNo',
					  data:  { "_token": _token,value:value},
					  dataType: "JSON",
					  success: function (json) {
						console.log(json);
						  for(var i=0;i<json.length;i++){
							//console.log(json[i].nameTitle);
						  $(".advancedSearch4 .selection4").text(json[i].nametitle);
						  $(".title_sel4").css('display','none');
						  $("#advTitle").val(json[i].nametitle);
						  $("#advName").val(json[i].advocatename);
						  $("#advRegAdrr").val(json[i].advocateaddress);
						  $("#advRegTaluk").attr('disabled', false);
						  $("#advRegDistrict").attr('disabled', false);
						  $("#advRegTaluk").empty();
						  $("#advRegDistrict").empty();
						  $("#advRegTaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
						  $("#advRegDistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '<option>');
						  $("#advRegPin").val(json[i].pincode);
						}
					  }
				  });
		}

			   }
        });
				
				//by nic
				
									}
								})
				$.ajaxSetup({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

    		e.preventDefault();

			var name = $("input[name=name]").val();
			var password = $("input[name=password]").val();
			var email = $("input[name=email]").val();

       
				}	}
			})//swal

		}
		else if(sbmt_applicant == 'U'){
			var capplicant = $('#caveatApplicant').serialize();
			swal({
				title: "Are you sure to Update?",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete)=> {
				if(willDelete){
				var form = $(this).closest("form").attr('id');
				$("#" + form).parsley().validate();
				if ($("#" + form).parsley().isValid()) {

			$.ajax({
				type:"POST",
				url:"caveatapplicant",
				data:capplicant,
				success: function(result) {
					$("#resApplId").val(application_id);
					var _token = $('input[name="_token"]').val();
					$.ajax({
			   type:'POST',
			   url:'caveatsearch',
			   data:{application_id:application_id,_token:_token},
			   dataType: "JSON",
			   success:function(data){
         console.log(data);			       
	 var len = data['data'].length;
				  var id = data.data[0].caveatid;
				$("#applicant_tab tbody").empty(); // Empty <tbody>
				if(len > 0){
					for(var i=0; i<len; i++){
						var SRNO = data.data[i].applicantsrno;
						var APPLICATIONID = data.data[i].caveatid;
						var APPLICANTNAME = data.data[i].applicantname;
						var ADVOCATE = data.data[i].advocateregnno;

					   var tr_str = "<tr>" +
						   "<td align='center'><a href='#' data-value='"+SRNO+"-"+APPLICATIONID+"' class='applicantClick'>" + SRNO + "</td>" +
						   "<td align='center'>" + APPLICATIONID + "</td>" +
						   "<td align='center'>" + APPLICANTNAME + "</td>" +
						   "<td align='center'>" + ADVOCATE + "</td>" +
					   "</tr>";

						$("#applicant_tab tbody").append(tr_str);
						$("#caveatApplicant").trigger("reset");
						$("#taluknameApp").empty();
						$("#departmentcode").empty();
					}
				}


			  $(".applicantClick").click(function(){
                $("#sbmt_applicant").val('U');
                $("#saveApplicant").val('Update List');
                //$(this).closest('form').find("input[type=text], textarea").val("");
                /**/
                var newSrno1  = $(this).attr('data-value');
                var newSrnoarr = newSrno1.split('-');
				   var newApllSrno = newSrnoarr[0];
				   var newApplid = newSrnoarr[1];
				   var _token = $('input[name="_token"]').val();
				$("#srno_applicant").val(newApllSrno);

                $.ajax({
                type: 'post',
                url: "getCaveatApplicantData",
                dataType:"JSON",
                data: {"_token": _token,newSrno:newApllSrno,applicationid:newApplid},
                success: function (data) {
					var len = data.length;
					for(var i=0;i<=data.length;i++){
                    var applname = data[i].applicantname;
					$("#applicantName").val(data[i].applicantname);
					$("#relType").val(data[i].reltype);
					$("#relationName").val(data[i].relationname);
					$("#gender").val(data[i].gender);
					$("#depttypecode").val(data[i].depttype);
					$("#addressAppl").val(data[i].caveataddress);
					$("#pincodeAppl").val(data[i].caveatpincode);
					$("#adistcode").val(data[i].districtcode);
					var option = '<option value="'+data[i].talukcode+'" selected>'+data[i].talukname+'</option>';
					$('#taluknameApp').append(option);
					//$("#taluknameApp").val(data[i].talukcode);
					$("#applMobileNo").val(data[i].caveatmobileno);
					$("#applEmailId").val(data[i].caveatemail);
					//$("#partyInPerson").value = data[i].partyinperson;
					$("#advBarRegNo").val(data[i].advocateregnno);
					$("#applAge").val(data[i].age);
					$("#desigAppl").val(data[i].desigcode);
					var option1 = '<option value="'+data[i].departcode+'" selected>'+data[i].departmentname+'</option>';
					$("#departmentcode").append(option1);
					//$("#Appdepart").apend('<option value="'+data[i].departcode+'" selected>'+data[i].departmentname+'</option>');
					$(".advancedSearch2 .selection2").text(data[i].applicanttitle);
                      $(".title_sel2").css('display','none');

                      $(".advancedSearch3 .selection3").text(data[i].relationtitle);
                      $(".title_sel3").css('display','none');
                    var pinp= data[i].partyinperson;
					if(pinp == 'N')
						{
							var value = $("#advBarRegNo").val();
							getBarregDetails(value);
							document.getElementById('partyInPerson').checked = true;
							$(".advDetails").show();
						}else{
							document.getElementById('partyInPersonY').checked = true;
							$(".advDetails").hide();
						}

                    }
                }
                });
              });

    function getBarregDetails(value)
  {
    //console.log(value);
	var _token = $('input[name="_token"]').val();
     $.ajax({
      type: 'POST',
      url: 'advRegNo',
      data:  { "_token": _token,value:value},
      dataType: "JSON",
      success: function (json) {
        //console.log(json);
          for(var i=0;i<json.length;i++){
            //console.log(json[i].nameTitle);
            $(".advancedSearch4 .selection4").text(json[i].nametitle);
          $(".title_sel4").css('display','none');
          $("#advTitle").val(json[i].nametitle);
          $("#advName").val(json[i].advocatename);
          $("#advRegAdrr").val(json[i].advocateaddress);
          $("#advRegTaluk").attr('disabled', false);
          $("#advRegDistrict").attr('disabled', false);
          $("#advRegTaluk").empty();
          $("#advRegDistrict").empty();
          $("#advRegTaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
          $("#advRegDistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '<option>');
          $("#advRegPin").val(json[i].pincode);
        }
      }
  });
  }


           }//new
        });

				$("#caveatApplicant").trigger("reset");
				$("#saveApplicant").val('Add List');
				$("#sbmt_applicant").val('A');


			}
			})//end of AJAX
				}}
			});//swal

		}//end of if
		else{
			swal({
				//title: "You are already entered all the caveators?",
				title: "You have already entered all the caveators.",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
		}
	})

	$("#applNext").click(function() {
		var apllStart = $("#applStartNo").val();
		var noOfAppl = $("#noOfAppl").val();
		var application_id = $("#recAppId").val();
		var applYear = $("#datepicker1").val();
		$("#resApplId").val(application_id);
		$("#resApplYear").val(applYear);
		var noOfres = $("#noOfRes").val();
		$("#noOfResCount").val(noOfres);
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');

		var count = $("#applicant_tab > tbody > tr").length;
		var appl = parseInt($("#noOfAppl").val());
		if (count == appl) {
			var data1 = $("#myTab li.active a").prop("href");
			  var data = $("#myTab li.active a").prop("href");
			 var url = data.substring(data.indexOf('#'));
			 $('.nav-tabs li.active').next('li').removeClass('disabled');
			$('.nav-tabs li.active').next('li').find('a').attr("data-toggle", "tab");
			$('.nav-tabs > .active').next('li').find('a').trigger('click');
		} else {
			swal({
				title: "No of Caveators  does not match with the entered Caveators !!"+noOfAppl,

				icon: "error",
			});
			return false;
		}






	})
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


	$("#saveRespondant").click(function(e) {
		//$("#saveApplicant").attr("disabled", true);
		var application_id = $('#recAppId').val();
		if ($("#respondantName").val() == '') {
			$('#respondantName').parsley().removeError('respondantName');
			$('#respondantName').parsley().addError('respondantName', {
				message: "Enter Cavetee Name"
			});
			return false;
		} else {
			$('#respondantName').parsley().removeError('respondantName');
		}
		if ($("#resRelName").val() == '') {
			$('#resRelName').parsley().removeError('resRelName');
			$('#resRelName').parsley().addError('resRelName', {
				message: "Enter Cavetee Relation Name"
			});
			return false;
		} else {
			$('#resRelName').parsley().removeError('resRelName');
		}
		if ($("#resGender").val() == '') {
			$('#resGender').parsley().removeError('resGender');
			$('#resGender').parsley().addError('resGender', {
				message: "Enter Cavetee Gender"
			});
			return false;
		} else {
			$('#resGender').parsley().removeError('resGender');
		}
		if ($("#resAge").val() == '') {
			$('#resAge').parsley().removeError('resAge');
			$('#resAge').parsley().addError('resAge', {
				message: "Enter Cavetee Age"
			});
			return false;
		} else {
			$('#resAge').parsley().removeError('resAge');
		}
		if ($("#resDeptType").val() == '') {
			$('#resDeptType').parsley().removeError('resDeptType');
			$('#resDeptType').parsley().addError('resDeptType', {
				message: "Enter Cavetee Department Type"
			});
			return false;
		} else {
			$('#resDeptType').parsley().removeError('resDeptType');
		}
		if ($("#resnameofDept").val() == '') {
			$('#resnameofDept').parsley().removeError('resnameofDept');
			$('#resnameofDept').parsley().addError('resnameofDept', {
				message: "Select Name of Department"
			});
			return false;
		} else {
			$('#resnameofDept').parsley().removeError('resnameofDept');
		}

		if ($("#desigRes").val() == '') {
			$('#desigRes').parsley().removeError('desigRes');
			$('#desigRes').parsley().addError('desigRes', {
				message: "Enter Caveatee designation"
			});
			return false;
		} else {
			$('#desigRes').parsley().removeError('desigRes');
		}

		if ($("#rdistcode").val() == '') {
			$('#rdistcode').parsley().removeError('rdistcode');
			$('#rdistcode').parsley().addError('rdistcode', {
				message: "Select District"
			});
			return false;
		} else {
			$('#rdistcode').parsley().removeError('rdistcode');
		}
		if ($("#taluknameRes").val() == '') {
			$('#taluknameRes').parsley().removeError('taluknameRes');
			$('#taluknameRes').parsley().addError('taluknameRes', {
				message: "Select Taluk"
			});
			return false;
		} else {
			$('#taluknameRes').parsley().removeError('taluknameRes');
		}
		var isGovtAdv = $("input[type=radio][name='isgovtadv']:checked").val();

		if (isGovtAdv == 'N') {
			if ($("#resadvBarRegNo").val() == '') {
				//alert('OK');
				$('#resadvBarRegNo').parsley().removeError('resadvBarRegNo');
				$('#resadvBarRegNo').parsley().addError('resadvBarRegNo', {
				message: "Select Advocate"
			});
			return false;
			} else {
				$('#resadvBarRegNo').parsley().removeError('resadvBarRegNo');
			}
		}

		$("#resApplId").val(application_id);
		var sbmt_respondant = $("#sbmt_respondant").val();
		var crespondant = $('#caveatRespondant').serialize();
		 var count = $("#respondant_tab > tbody > tr").length;
		var resp = parseInt($("#noOfRes").val());
		var _token = $('input[name="_token"]').val();
		if (count < resp && sbmt_respondant == 'A'){
			swal({
				title: "Are you sure to Save?",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete)=> {
			    if(willDelete){
				var form = $(this).closest("form").attr('id');
				$("#" + form).parsley().validate();
				if ($("#" + form).parsley().isValid()) {
				$.ajax({
				type:"POST",
				url:"caveatrespondant",
				data:crespondant,
						success: function(result) {
						$("#resAppId").val(application_id);
						
						//by nic
						
						 $.ajax({
           type:'POST',
           url:'caveateesearch',
           data:{application_id:application_id},
		   dataType: "JSON",
           success:function(data){
             console.log(data);
               var len = data['data'].length;
			  var id = data.data[0].caveatid;
              $("#respondant_tab tbody").empty(); // Empty <tbody>
				if(len > 0){
					for(var i=0; i<len; i++){
						var RSRNO = data.data[i].caveateesrno;
						var APPLICATIONID = data.data[i].caveatid;
						var APPLICANTNAME = data.data[i].caveateename;
						var ADVOCATE = data.data[i].advocateregnno;

					   var tr_str = "<tr>" +
						   "<td align='center'><a href='#' data-value='"+RSRNO+"-"+APPLICATIONID+"' class='respondantClick'>" + RSRNO + "</td>" +
						   "<td align='center'>" + APPLICATIONID + "</td>" +
						   "<td align='center'>" + APPLICANTNAME + "</td>" +
						   "<td align='center'>" + ADVOCATE + "</td>" +
					   "</tr>";

						$("#respondant_tab tbody").append(tr_str);
					//	$('#caveatRespondant').ajax.reload();
                                                $("#caveatRespondant").trigger("reset");
					}
				}

				$(".respondantClick").click(function(){
                $("#sbmt_respondant").val('U');
                $("#saveRespondant").val('Update List');
                 var newSrno1  = $(this).attr('data-value');
				 var newSrnoarr = newSrno1.split('-');
				 var newApllSrno = newSrnoarr[0];
                 var newApplid = newSrnoarr[1];
				$("#resApplId").val(newApplid);
				var _token = $('input[name="_token"]').val();
				$("#srno_respondant").val(newApllSrno);
                $.ajax({
					type: 'post',
					url: "getCaveatRespodantData",
					dataType:"JSON",
					data: {"_token": _token,newSrno:newApllSrno,applicationid:newApplid},
                    success: function (json1) {
						 for(var i=0;i<json1.length;i++){
                          $(".advancedSearch5 .selection5").text(json1[i].caveateetitle);
                          $(".title_sel5").css('display','none');
                          $("#resRelation").val(json1[i].caveateereltype);
                          $("#respondantName").val(json1[i].caveateename);
						  $("#resGender").val(json1[i].caveateegender);
						  $("#resAge").val(json1[i].caveateeage);
						  $("#desigRes").val(json1[i].caveateedesigcode);
						  $("#resAddress2").val(json1[i].caveateeaddress);
                          $("#respincode2").val(json1[i].caveateepincode);
						  $("#rdistcode").val(json1[i].caveateedistrict);
                          $("#taluknameRes").val(json1[i].caveateetaluk);
                          $("#resMobileNo").val(json1[i].caveateemobileno);
                          $("#resEmailId").val(json1[i].caveateeemail);
						  $("#resadvBarRegNo").val(json1[i].advocateregnno);
						  var option = '<option value="'+json1[i].caveateetaluk+'" selected>'+json1[i].talukname+'</option>';
						  $('#taluknameRes').append(option);
                           //var relvalue = json1[i].relation+'-'+json1[i].gender;
						  $("#resReltaion").val(json1[i].relation);

                         // $("#resReltaion >  [value='" + relvalue +"']").attr("selected",true);
                          $(".advancedSearch6 .selection6").text(json1[i].caveateetitle);
                          $(".title_sel6").css('display','none');
						  $("#resDeptType").val(json1[i].caveateedepttype);
						var option1 = '<option value="'+json1[i].caveateedepartcode+'" selected>'+json1[i].departmentname+'</option>';
						$("#resnameofDept").append(option1);

						  //$("#resAge").val(json1[i].caveateename);
                          //$("#resnameofDept").val(json1[i].caveateedepartcode);


						  //$("#resAge").val(json1[i].caveateename);

                          $("#resRelTitle").val(json1[i].relationtitle);
                          $("#resRelName").val(json1[i].relationname);
                          //$("#resGender").val(json1[i].gender);
                          //$("#resAge").val(json1[i].respondantage);
                          $("#resDesig").val(json1[i].desigcode);
                          //$("#isGovtAdv").val(json1[i].isgovtadvocate);
                          //$("#isMainRes").val(json1[i].ismainrespond);
						  var isgovadv = json1[i].isgovtadvocate;
						 if(isgovadv == 'N')
						{
							var value = $("#resadvBarRegNo").val();
							document.getElementById('isgovtadv').checked = true;
							$(".resadvDatails").show();
							getBarRegDetails(value);
						}else{
							document.getElementById('isgovtadvY').checked = true;
							$(".resadvDatails").hide();
						}

                          //$("#resadvBarRegNo").val(json1[i].advocateregno);
                          //value = $("#resadvBarRegNo").val(json1[i].advocateregno);
                        //getResBarRegDetails(value);
                       }
                }
                });
              });
				 function getResBarRegDetails(value)
				{
				  var _token = $('input[name="_token"]').val();
				  $.ajax({
					type: 'POST',
					url: 'advRegNo',
					data:  { "_token":_token,value:value},
					dataType: "JSON",
					success: function (json) {

					for(var i=0;i<json.length;i++){
					$(".advancedSearch7 .selection7").text(json[i].nametitle);
					$(".title_sel7").css('display','none');
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
					}
					}
					});
				} }
        });
						
						//by nic
						
						
						
											}
										})
					$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});



        e.preventDefault();

       
					}}//swal
			})
			}else if (sbmt_respondant == 'U'){
				swal({
				title: "Are you sure to Update?",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete)=> {
				if(willDelete){
				var form = $(this).closest("form").attr('id');
				$("#" + form).parsley().validate();
				if ($("#" + form).parsley().isValid()) {

				$.ajax({
				type:"POST",
				url:"caveatrespondant",
				data:crespondant,
				success: function(result) {
					var _token = $('input[name="_token"]').val();
				$.ajax({
				   type:'POST',
				   url:'caveateesearch',
				   data:{application_id:application_id,_token:_token},
				   dataType: "JSON",
				   success:function(data){
				   var len = data['data'].length;
				   var id = data.data[0].caveatid;
				   $("#respondant_tab tbody").empty(); // Empty <tbody>
					if(len > 0){
						for(var i=0; i<len; i++){
						var RSRNO = data.data[i].caveateesrno;
						var APPLICATIONID = data.data[i].caveatid;
						var APPLICANTNAME = data.data[i].caveateename;
						var ADVOCATE = data.data[i].advocateregnno;

					   var tr_str = "<tr>" +
						   "<td align='center'><a href='#' data-value='"+RSRNO+"-"+APPLICATIONID+"' class='respondantClick'>" + RSRNO + "</td>" +
						   "<td align='center'>" + APPLICATIONID + "</td>" +
						   "<td align='center'>" + APPLICANTNAME + "</td>" +
						   "<td align='center'>" + ADVOCATE + "</td>" +
					   "</tr>";

						$("#respondant_tab tbody").append(tr_str);
						$("#caveatRespondant").trigger("reset");
						$("#taluknameRes").empty();
						$("#resnameofDept").empty();
					}
				}


			  ////alert(data.caveatid);
			  $(".respondantClick").click(function(){
                $("#sbmt_respondant").val('U');
                $("#saveRespondant").val('Update List');
                //$(this).closest('form').find("input[type=text], textarea").val("");
                /**/
                var newSrno1  = $(this).attr('data-value');
				 var newSrnoarr = newSrno1.split('-');
				 var newApllSrno = newSrnoarr[0];
               var newApplid = newSrnoarr[1];
				$("#resApplId").val(newApplid);
				var _token = $('input[name="_token"]').val();
				$("#srno_respondant").val(newApllSrno);
                $.ajax({
                type: 'post',
                url: "getCaveatRespodantData",
                dataType:"JSON",
                data: {"_token": _token,newSrno:newApllSrno,applicationid:newApplid},
                    success: function (json1) {
						  for(var i=0;i<json1.length;i++){
                          $(".advancedSearch5 .selection5").text(json1[i].caveateetitle);
                          $(".title_sel5").css('display','none');
                          $("#resRelation").val(json1[i].caveateereltype);
                          $("#respondantName").val(json1[i].caveateename);
						  $("#resGender").val(json1[i].caveateegender);
						  $("#resAge").val(json1[i].caveateeage);
						  $("#desigRes").val(json1[i].caveateedesigcode);
						  $("#resAddress2").val(json1[i].caveateeaddress);
                          $("#respincode2").val(json1[i].caveateepincode);
						  $("#rdistcode").val(json1[i].caveateedistrict);
                          $("#taluknameRes").val(json1[i].caveateetaluk);
                          $("#resMobileNo").val(json1[i].caveateemobileno);
                          $("#resEmailId").val(json1[i].caveateeemail);
						  $("#resadvBarRegNo").val(json1[i].advocateregnno);
						  var option = '<option value="'+json1[i].caveateetaluk+'" selected>'+json1[i].talukname+'</option>';
						  $('#taluknameRes').append(option);
                           //var relvalue = json1[i].relation+'-'+json1[i].gender;
						  $("#resReltaion").val(json1[i].relation);

                         // $("#resReltaion >  [value='" + relvalue +"']").attr("selected",true);
                          $(".advancedSearch6 .selection6").text(json1[i].caveateetitle);
                          $(".title_sel6").css('display','none');
						  $("#resDeptType").val(json1[i].caveateedepttype);
						var option1 = '<option value="'+json1[i].caveateedepartcode+'" selected>'+json1[i].departmentname+'</option>';
						$("#resnameofDept").append(option1);

						  //$("#resAge").val(json1[i].caveateename);
                          //$("#resnameofDept").val(json1[i].caveateedepartcode);


						  //$("#resAge").val(json1[i].caveateename);

                          $("#resRelTitle").val(json1[i].relationtitle);
                          $("#resRelName").val(json1[i].relationname);
                          //$("#resGender").val(json1[i].gender);
                          //$("#resAge").val(json1[i].respondantage);
                          $("#resDesig").val(json1[i].desigcode);
                          $("#isGovtAdv").val(json1[i].isgovtadvocate);
                          $("#isMainRes").val(json1[i].ismainrespond);
                          //$("#resadvBarRegNo").val(json1[i].advocateregno);
                          //value = $("#resadvBarRegNo").val(json1[i].advocateregno);
                        //getResBarRegDetails(value);
						 var isgovadv= json[i].isgovtadvocate;
						 if(isgovadv == 'N')
						{
							var value = $("#resadvBarRegNo").val();
							getBarRegDetails(value);
							document.getElementById('isgovtadv').checked = true;
							$(".resadvDatails").show();
						}else{
							document.getElementById('isgovtadvY').checked = true;
							$(".resadvDatails").hide();
						}
                       }
                }
                });
              });
 function getResBarRegDetails(value)
{
  var _token = $('input[name="_token"]').val();
  $.ajax({
	type: 'POST',
	url: 'advRegNo',
	data:  { "_token": _token,value:value},
	dataType: "JSON",
	success: function (json) {

    for(var i=0;i<json.length;i++){
    $(".advancedSearch7 .selection7").text(json[i].nametitle);
    $(".title_sel7").css('display','none');
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
    }
    }
	});
}

				}//new
					});
					$("#sbmt_respondant").val('A');
					$("#saveRespondant").val('Add List');
				}
									})
					}//SWAL
				}});
			}else{
				swal({
				title: "You have already entered all the caveatees.",
				icon: "warning",
				showCancelButton: true,
				buttons: true,
				dangerMode: true,
			})
     }

	})



$("#ResNext").click(function() {
		var apllStart = $("#applStartNo").val();
		var noOfAppl = $("#noOfAppl").val();
		var application_id = $("#recAppId").val();
		var applYear = $("#datepicker1").val();
		$("#resApplId").val(application_id);
		$("#resApplYear").val(applYear);
		var noOfres = $("#noOfRes").val();
		$("#noOfResCount").val(noOfres);
		var form = $(this).closest("form").attr('id');
		var formaction = $(this).closest("form").attr('action');

		var count = $("#respondant_tab > tbody > tr").length;
		var resappl = parseInt($("#noOfres").val());
		if (count == noOfres) {
			var data1 = $("#myTab li.active a").prop("href");
		var data = $("#myTab li.active a").prop("href");
		var url = data.substring(data.indexOf('#'));
		 $('.nav-tabs a[href="#tab_1"]').tab('show');
			//$("#caveat").trigger("reset");
			//$("#caveatApplicant").trigger("reset");
			window.location.reload();
			//$("#resnameofDept").empty();

		} else {
			swal({
				title: "No of Caveatee does not match with the entered Caveatee !!"+noOfres,

				icon: "error",
			});
			return false;
		}
	})

$("#cancelApplication").click(function() {
	//alert('success applicant');
	var data_tab = $("#myTab li.active a").prop("href");
	//alert(data_tab);
	var url = data_tab.substring(data_tab.indexOf('#'));
	//alert(url);
	//if(url=='#tab_1'){
		window.location.reload();

})
$("#clearApp").click(function(){
	$("#caveatApplicant").trigger("reset");
		$("#taluknameApp").empty();
		$("#departmentcode").empty();
		$("#saveApplicant").val('Add List');
		$("#sbmt_applicant").val('A');
		//alert('done');
})
$("#clearRes").click(function(){
	$("#caveatRespondant").trigger("reset");
		$("#resnameofDept").empty();
		$("#taluknameRes").empty();
		$("#sbmt_respondant").val('A');
		$("#saveRespondant").val('Add List');
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

function checkMobNo(value)
	{
	//	alert("checkMobNo");
	if(value.length==10 || value.length==0 ){
                   var validate = true;
				   return value;
              } else {
                  alert('Please put 10  digit mobile number');
                  var validate = false;
				 //  $('#mobileno').focus();
				  return ;
              }


	}
