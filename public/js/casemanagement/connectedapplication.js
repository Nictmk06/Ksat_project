$(document).ready(function(){
	//to disable other tabs when first tab is active
	$('.nav-tabs li').not('.active').addClass('disabled');
    $('.nav-tabs li').not('.active').find('a').removeAttr("data-toggle");
    //to fetch details of application
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
	var flag='connected';
	var newflag='O';

	 	$.ajax({
		type: 'POST',
		url: 'getApplication',
		data:  { "_token": $('#token').val(),application_id:applicationId,flag:flag},
		dataType: "JSON",
		success: function (json) {

			$.ajax({
		type: 'POST',
		url: 'getConStatus',
		data:  { "_token": $('#token').val(),applicationid:applicationId,newflag:newflag},
		dataType: "JSON",
		success: function (json1) {
			//console.log(json);
			if(json1==true)
			{
				swal({
				title: "Application Already Connected",

				icon: "error"
				})
				$("#connectedForm").trigger('reset');
				$("#results2").empty();

			}
			else
			{
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
					if(json[i].iasrno==null)
					{
						$("#IASrNo").val('1');
						$('#IASrNo').attr('readonly',true);
					}
					else
					{
						var newsrno = parseInt(json[i].iasrno)+1;
						$('#IASrNo').val(newsrno);
						$('#IASrNo').attr('readonly',true);
					}


					$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);

					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
					$("#orignapplid").val(json[i].applicationid);

				// commented on 03092020 to allow other casetype can be selected

				//	$("#conapplTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort).attr('readonly',true);
				//	$("#conapplTypeName").css('pointer-events', 'none');

					$("#dateOfAppl").css('pointer-events', 'none');
					$("#applnRegDate").css('pointer-events', 'none');
					$("#conapplnRegDate").css('pointer-events', 'none');

					var regdate_1  = $("#applnRegDate").val();
	  				$('#hearingDate').datepicker('setStartDate', regdate_1);
	  				$('#orderDate').datepicker('setStartDate', regdate_1);
				}
				getConnectedApp(applicationId);

				getConnectDetails(applicationId);
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
		}
		});


		}
	});
});





	$("#conapplSearch").click(function(){

		var applId = $("#applicationId").val();
		var conApplId = $("#conapplicationId").val();
		if($("#conapplTypeName").val()=='')
	{

		$('#conapplTypeName').parsley().removeError('conapplTypeName');
		$('#conapplTypeName').parsley().addError('conapplTypeName', {message: "Select Application Type"});
		return false;
	}
	else
	{
		$('#conapplTypeName').parsley().removeError('conapplTypeName');
	}
	if($("#conapplicationId").val()=='')
	{
		$('#conapplicationId').parsley().removeError('conapplicationId');
		$('#conapplicationId').parsley().addError('conapplicationId', {message: "Enter Application No"});
		return false;
	}
	else
	{
		$('#conapplicationId').parsley().removeError('conapplicationId');
	}


		/*if(conApplId==applId || $("#conapplTypeName").val()==$("#applTypeName").val())
		{

			$('#conapplicationId').parsley().removeError('conapplicationId');
			$('#conapplicationId').parsley().addError('conapplicationId', {message: "Connect Application Should be Different!!"});
		}*/
		if(conApplId!=applId)
		{

			var type = $("#conapplTypeName").val();
			var newtype1 = type.split('-');
			var applnewtype1 = newtype1[1];
			var newconapplid = newtype1[1]+'/'+conApplId;

		//console.log(newconapplid);
			var newflag='C';
			$.ajax({
		type: 'POST',
		url: 'getConStatus',
		data:  { "_token": $('#token').val(),applicationid:newconapplid,newflag:newflag},
		dataType: "JSON",
		success: function (json) {
			//console.log(json);
			if(json==true)
			{
				swal({
				title: "Application Already Connected",

				icon: "error"
				})

							$("#conapplnRegDate").val('');
							$("#conApplEndNo").val('');
							$("#conApplStartNo").val('');
							//$("#conapplTypeName").val('');
							$("#results2").empty();
			}
			else
			{
				var flag='connected';
							$.ajax({
					type: 'POST',
					url: 'getApplication',
					data:  { "_token": $('#token').val(),application_id:newconapplid,flag:flag},
					dataType: "JSON",
					success: function (json) {

   console.log(json)

						if(json.length>0)
						{

							for (var i = 0; i < json.length; i++) {

								if(json[i].registerdate===null){

									$("#conapplnRegDate").val('');
								}
								else
								{
									var dor = json[i].registerdate;
									var dor_split = dor.split('-');
									var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
									$("#conapplnRegDate").val(dateOfReg);
								}




								$("#conapplTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);

								$("#conApplStartNo").val(json[i].applicationsrno);
								$("#conApplEndNo").val(json[i].applicationtosrno);
								$("#conaplid").val(json[i].applicationid);
                $("#conapplyear").val(json[i].applicationyear);

								var regdate_1  = $("#applnRegDate").val();
				  			$('#hearingDate').datepicker('setStartDate', regdate_1);
				  			$('#orderDate').datepicker('setStartDate', regdate_1);
							}



						}
						else
						{
							swal({
							title: "Application Does Not Exist",

							icon: "error"
							})
							$("#conapplnRegDate").val('');
							$("#conApplEndNo").val('');
							$("#conApplStartNo").val('');
							$("#conapplyear").val('');
							//$("#conapplyear").val('');
							//$("#conapplTypeName").val('');

						}
					}
				});
			}
		}
	});

					/**/
		}
		else
		{
			swal({
				title: "Application cann't be same",

				icon: "error"
				})
		}
	})
	$("#benchCode").change(function() {
   		 var text = $(this).val();

   		$.ajax({
				type: 'post',
				url: "getBenchJudges",
				dataType:"JSON",
				data: {"_token": $('#token').val(),benchtype:text,display:'Y'},
				success: function (json) {
					$('#benchJudge').find('option:not(:first)').remove();
					 for(var i=0;i<json.length;i++){
					 	 var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
	  						$('#benchJudge').append(option);
					 }
				}
			});

  	});

		$("#saveConnectedCase").click(function(e) {
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

					var applicationid = $("#orignapplid").val();
					var form = $(this).closest("form").attr('id');
					var formaction = $(this).closest("form").attr('action');
					var form = $(this).closest("form").attr('id');
		  			var formaction = $(this).closest("form").attr('action');
		  			if($("#conapplnRegDate").val()=='')
		  			{
		  				$('#conapplicationId').val('');
						$('#conapplicationId').parsley().removeError('conapplicationId');
						$('#conapplicationId').parsley().addError('conapplicationId', {message: "Enter Connected Application id"});
						return false;
		  			}
		  			else
		  			{
		  				$('#conapplicationId').parsley().removeError('conapplicationId');

		  			}


					$("#" + form).parsley().validate();
					if ($("#" + form).parsley().isValid()) {



							$.ajax({
									type: 'post',
									url: formaction,
									data: $('#' + form).serialize(),
									success: function(data) {
										if(data.status=='exists')
										{
											swal({
												title: data.message,

												icon: "error",

											});
										}
										if (data.status == "sucess") {
											swal({
												title: data.message,

												icon: "success",

											});
											getConnectedApp(applicationid);
											if(applicationid==$("#orignapplid").val())
											{
												$("#chkval").val('A');
											}
											else
											{
												$("#chkval").val('');
											}
											$('#connectedForm').find('input, select, textarea').not("#applTypeName,#applicationId,#dateOfAppl,#applnRegDate,#applCatName,#applnSubject,.btnClear,#orignapplid,#chkval").trigger('reset');
											$("#conapplicationId").val('');
											$("#conApplEndNo").val('');
											$("#conApplStartNo").val('');
											$("#conapplnRegDate").val('');
											$("#sbmt_connected").val('A');
											$("#saveConnectedCase").val('Save');
											//$("#benchJudge").val('');
											$(".origndiv1").show();
											$(".origndiv2").show();

										} else if (data.status == "fail") {
											swal({
												title: data.message,

												icon: "error",
											});

										}
									}
								});





					}
				} else {
					return false;
				}
			});

	})

});
function getConnectedApp(applicationid)
{

	$.ajax({
						type: 'post',
						url: "getConnectApplications",
						dataType: "JSON",
						data: {
						"_token": $('#token').val(),
						applicationid: applicationid
						},
						success: function(json1) {

							$("#myTable").find('tbody tr').remove();
						var count = 1;
						$.each(json1, function(index, obj) {
						var row = $('<tr>');

						row.append('<td>'+count+'</td>')
						row.append('<td>'+obj.applicationid+'</td>');

						row.append('<td >' + obj.conapplid + '</a></td>');
						row.append('<td class="col-md-2"><a href="#" class="deleteRow btn btn-sm btn-danger"  type="button" data-value="' + obj.conapplid+'|'+obj.applicationid + '">X</a></td>');
						row.appendTo('#myTable');
						count++;
						})
						$(".deleteRow").click(function() {
									var appl = $(this).attr('data-value');
									var split = appl.split('|');
									var applid = split[1];
									var connapplid = split[0];
									$.ajax({
										type: 'POST',
										url: 'deleteConnected',
										dataType: 'JSON',
										data: {
											"_token": $('#token').val(),
											conapplid: connapplid,
											applicationid:applid
										},
										cache: false,
										success: function(response) {
											if (response.data1 == "Y") {
												$("#benchJudge").val('');
												$("#connectedForm").trigger('reset');

											}
											if (response.status == "sucess") {
												swal({
													title: response.message,
													icon: "error",
												});

												getConnectedApp(applicationid);

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
}


	/*$("#editSearch").click(function(){
		var user = $("#username").text();
		var type = $("#edit_modal_type").val();

		var newtype = type.split('-');
		var applId = newtype[1]+'/'+$("#edit_applno").val();
		//console.log(applId);

		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getConAppl',
				data: {"_token": $('#token').val(),applicationid:applId},
				success: function(data) {
					$("#editmodal").modal('hide');
					$(".origndiv").hide();
					$(".origndiv").attr('data-parsley-required',false);
					for(var i=0;i<data.length;i++){
						$("#orignapplid").val(data[i].applicationid);
						$("#connectedtype").val(data[i].type);
						$('#benchCode').val(data[i].benchtypename);
						$("#benchJudge option[value="+data[i].benchcode+"]").attr('selected', 'selected');
						var hrdate = data[i].hearingdate;
						var split = hrdate.split('-');
						var orderdate = data[i].orderdate;
						var split1 = orderdate.split('-');
						$("#hearingDate").val(split[2]+'-'+split[1]+'-'+split[0]);
						$("#orderDate").val(split1[2]+'-'+split1[1]+'-'+split1[0]);
						$("#orderNo").val(data[i].orderno);
						$("#reasonforconn").val(data[i].reason);
						$("#sbmt_connected").val('U');
						$("#saveConnectedCase").val('Update');
						}

					/**/

			/*	}
			});
	})*/
	function getConnectDetails(applicationId)
	{
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getConAppl',
				data: {"_token": $('#token').val(),applicationid:applicationId},
				success: function(data) {

					//$(".origndiv1").hide();
					//$(".origndiv2").show();
					if(data.length>0)
					{
						for(var i=0;i<data.length;i++){
						$("#orignapplid").val(data[i].applicationid);
						$("#connectedtype").val(data[i].type);
						$('#benchCode').val(data[i].benchtypename);
						$("#benchJudge option[value="+data[i].benchcode+"]").attr('selected', 'selected');
						var hrdate = data[i].hearingdate;
						var split = hrdate.split('-');
						var orderdate = data[i].orderdate;
						var split1 = orderdate.split('-');
						$("#hearingDate").val(split[2]+'-'+split[1]+'-'+split[0]);
						$("#orderDate").val(split1[2]+'-'+split1[1]+'-'+split1[0]);
						$("#orderNo").val(data[i].orderno);
						$("#reasonforconn").val(data[i].reason);
						//$("#sbmt_connected").val('U');
						$("#chkval").val('A');

						//$("#saveConnectedCase").val('Update');
						}
					}
					else
					{
						//$(".origndiv1").show();
						//$(".origndiv2").show();

						$("#connectedtype").val('');
						$('#benchCode').val('');
						$("#benchJudge").val('');

						$("#hearingDate").val('');
						$("#orderDate").val('');
						$("#orderNo").val('');
						$("#reasonforconn").val('');
						$("#sbmt_connected").val('A');
						$("#saveConnectedCase").val('Save');
					}


					/**/

			}
			});
	}
	$('.btnClear').click(function(){


	$("#connectedForm").trigger('reset');

	$("#results2").empty();

})
