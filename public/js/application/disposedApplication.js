
$(document).ready(function() {
    //$("#actSectionName").css('pointer-events', 'none');
    //$("#applYear").css('pointer-events', 'none');

	$("#editApplication").click(function(){
		$('#editmodal').modal('show');
			$('#edit_appl-title').text('Edit Application');
	})


	$("#editSearch").click(function(){
		var type = $("#edit_modal_type").val();
		var newtype = type.split('-');
		var applId = newtype[1]+'/'+$("#edit_applno").val();
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getDisposedApplicationDetails',
				data: {"_token": $('#token').val(),applicationid:applId},
				success: function (json) {
          console.log(json);
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
						$("#applnDisposeDate").val('');
					}
					else
					{
						var doa = json[i].disposeddate;
						var doa_split = doa.split('-');
						var dateOfDisposed = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#applnDisposeDate").val(dateOfDisposed);
					}

					$("#applStartNo").attr('readonly',true);
					$("#applStartNo").css('pointer-events', 'none');
					$("#dateOfAppl").attr('readonly',true);
					$("#dateOfAppl").css('pointer-events', 'none');
					$("#applTypeName").attr('readonly',true);
					$("#applTypeName").css('pointer-events', 'none');
					$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					$("#actSectionName").val(json[i].actsectioncode);
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
					$("#applYear").val(json[i].applicationyear);
					$("#applStartNo").val(json[i].applicationsrno);
					$("#applEndNo").val(json[i].applicationtosrno);
					$("#noOfAppl").val(json[i].applicantcount);
					$("#noOfRes").val(json[i].respondentcount);
					$("#connectedappl").val(json[i].connectedcase);
					$("#benchtypename").val(json[i].benchtypename);
					$("#lastpostedfor").val(json[i].purposecode);
					$("#lastorderPassed").val(json[i].ordertypecode);

                    $("#authorby").val(json[i].authorby);
                    $("#advBarRegNo").val(json[i].advocateregno);
                    $("#resadvBarRegNo").val(json[i].resadvocateregno);

					$("#reviewAppl").val(json[i].referapplid);
					$("#benchcode").val(json[i].benchcode);
					$(".title_sel2").css('display', 'none');
					$(".advancedSearch2 .selection2").text(json[i].applicantnametitle);
					$("#applicantName").val(json[i].applicantname);
					$("#gender").val(json[i].gender);
					$("#applDeptType").val(json[i].depttype);
					$("#applnameOfDept").val(json[i].departcode);
					$(".title_sel5").css('display', 'none');
					$(".advancedSearch5 .selection5").text(json[i].resnametitle);
					$("#respondantName").val(json[i].resnametitle);
					$("#respondantName").val(json[i].respondname);
					$("#resGender").val(json[i].respondgender);
					$("#resDeptType").val(json[i].responddepttype);
					$("#resnameofDept").val(json[i].responddeptcode);
					}
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
					$("#editmodal").modal('hide');
				}
			else
			{
				swal({
				title: "Application Does Not Exist",
				icon: "error"
				})
				$("#disposedApplicationForm").trigger('reset');
			}
		 }
 	 });
	})

$("#actName").change(function(){
		var typename = $(this).val();
		//var type = typename.split('-');
		$.ajax({
        type: 'post',
        url: 'getSections',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typename},
        success: function (data) {
        	
				$('#actSectionName').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].actsectioncode+'">'+data[i].actsectionname+'</option>';

  						$('#actSectionName').append(option);
				 }
        	}
        });

	})

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

$("#applDeptType").change(function(){
        var typeval = $(this).val();
                $.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
            //var option = "<option value=''>Select Option</option>";
        //$('#nameOfDept').empty();
        $('#applnameOfDept').empty();
                 for(var i=0;i<data.length;i++){
                     var option = '<option value="'+data[i].departmentcode+'">'+data[i].departmentname+'</option>';

                        //$('#nameOfDept').append(option);
                        $('#applnameOfDept').append(option);
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

 $("#applTypeName").change(function(){

        var type = $(this).val();
        var typ2 = $("#applTypeName option:selected").html();
        var newtype = type.split('-');
        var applnewtype = newtype[1];
        var applref = newtype[2];
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
        }else{

        $("#reviewAppl").text('');
        $("#reviewApplId1").val('');
        }

    })

$("#benchtypename").change(function() {
         var text = $(this).val();

        $.ajax({
                type: 'post',
                url: "getBenchJudges",
                dataType:"JSON",
                data: {"_token": $('#token').val(),benchtype:text,display:''},
                success: function (json) {
                    $('#benchcode').find('option:not(:first)').remove();
                     for(var i=0;i<json.length;i++){
                         var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
                            $('#benchcode').append(option);
                     }
                }
            });

    });

$("#applYear").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });

$("#dateOfAppl").change(function() {
        var doa = $("#dateOfAppl").val();
        var split = doa.split("-");
        $("#applYear").val(split[2]);
    })

$("#dateOfAppl").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#applnRegDate').datepicker('setStartDate', startDate);
        $('#applnDisposeDate').datepicker('setStartDate', startDate);
    }).on('clearDate', function(selected) {
        $('#applnRegDate').datepicker('setStartDate', null);
        $('#applnDisposeDate').datepicker('setStartDate', null);
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


$("#applnDisposeDate").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#dateOfAppl').datepicker('setEndDate', endDate);
	    $('#applnRegDate').datepicker('setEndDate', endDate);
    }).on('clearDate', function(selected) {
        $('#dateOfAppl').datepicker('setEndDate', null);
		$('#applnRegDate').datepicker('setEndDate', null);
    });



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
            url: 'getApplication',
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

        $("#reviewAppl").text(applId);
        $("#reviewApplId1").val(applId);
        $('#modal-default').modal('hide');
    })


$('.btnClear').click(function(){
	$("#disposedApplicationForm").trigger('reset');
    $("#sbmt_adv").val('A');
	$("#saveADV").val('Save');
	$("#applStartNo,#dateOfAppl,#applTypeName").attr('readonly',false);
    $("#applStartNo,#dateOfAppl,#applTypeName").css('pointer-events', 'visible');

})



$('#disposedApplicationForm').submit(function(e)
   {
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
			   var appl = parseInt($("#noOfAppl").val());
			   var endno = parseInt($("#applEndNo").val());
			   var startno = parseInt($("#applStartNo").val());
			   var groupsn = $('#groupsn').val();
			   $("#"+form).parsley().validate();
		     if ($("#"+form).parsley().isValid())
    		{
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
    		       $.ajax({
										type: 'post',
										url: formaction,
										data: $('#'+form).serialize(),
										success: function (data) {
										if(data.status=="sucess")
										{
									     swal({
										title: data.message,
										icon: "success"
										})

										$("#disposedApplicationForm").trigger('reset');
										$("#sbmt_adv").val('A');
										$("#saveADV").val('Save');
										$("#applStartNo,#dateOfAppl,#applTypeName").attr('readonly',false);
										$("#applStartNo,#dateOfAppl,#applTypeName").css('pointer-events', 'visible');

										}
										else if(data.status=="fail")
										{
										swal({
										title: data.message,
										icon: "error"
										   })
										}
										else if(data.status=="exists")
										{
										swal({
										title: data.message,
										icon: "error"
										   })
										}}
										});
		}else
			{
				return false;
			}
		}
      });
	})




});
