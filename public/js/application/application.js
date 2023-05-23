
$(document).ready(function() {
    $("#actSectionName").css('pointer-events', 'none');
    $("#datepicker1").css('pointer-events', 'none');
	$(".title_sel4").css('display', 'none');
    $(".suomoto").css('display', 'none');
    
$("#applTypeName").change(function(){
		var typename = $(this).val();
		var type = typename.split('-');
        console.log(type[0]);
		$.ajax({
        type: 'post',
        url: 'getSections',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:type[0]},
        success: function (data) {
            
        	//var option = "<option value=''>Select Option</option>";
        $('#actSectionName').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].actsectioncode+'" selected>'+data[i].actsectionname+'</option>';

  						$('#actSectionName').append(option);
				 }
        	}
        });
		
	})

// $("#applTypeName").on('change', function() {}   
 $("#applTypeName").change(function(){
	    $(".title_sel4").css('display', 'none');
        $(".suomoto").css('display', 'none');
        $(".receiptdiv").css('display', 'inline');
        $('#applicantgovt').val('');
	    $('#suomotoappl').val('');
        var type = $(this).val();
        var typ2 = $("#applTypeName option:selected").html(); 
        var newtype = type.split('-');
        var applnewtype = newtype[1];
        console.log(applnewtype);        
        
		if ((applnewtype == 'RA' || applnewtype == 'UR') && type !='') {
         $(".title_sel4").css('display', 'inline');
        }
        if ((applnewtype == 'CA'  || applnewtype == 'UC') && type !='') {
         $(".suomoto").css('display', 'inline');
        }
		if ((applnewtype == 'MA' || applnewtype =='UM') && type !='') {
         $(".receiptdiv").css('display', 'none');   
        }
        if ((applnewtype == 'OA' || applnewtype !='UA') && type !='') {
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
        
        if((applnewtype == 'RP') && type != '') {
            $(".title_sel4").css('display', 'inline');
        }

        if((applnewtype == 'CO') && type != '') {
            $(".suomoto").css('display', 'inline');
        }

        if((applnewtype == 'MP') && type != '') {
            $(".receiptdiv").css('display', 'none');   
        }

        if((applnewtype != 'OP') && type != '') {
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
        } else {
            $("#reviewAppl").text('');
            $("#reviewApplId1").val(''); 
        }
        

    })


$("#datepicker1").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });

$("#dateOfAppl").change(function() {
        var doa = $("#dateOfAppl").val();
        var split = doa.split("-");
        $("#datepicker1").val(split[2]);
    })

$("#dateOfAppl").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var startDate = new Date(selected.date.valueOf());
        $('#applnRegDate').datepicker('setDate', startDate);
        
    }).on('clearDate', function(selected) {
        $('#applnRegDate').datepicker('setDate', null);
    });
	

/*$("#applnRegDate").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selected) {
        var endDate = new Date(selected.date.valueOf());
        $('#dateOfAppl').datepicker('setEndDate', endDate);
    }).on('clearDate', function(selected) {
        $('#dateOfAppl').datepicker('setEndDate', null);
    });
*/

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
       var applTypeName = $("#modl_appltype_name").val().split('-')[1]; 
     
       if(applTypeName == 'RA' || applTypeName == 'CA'){
         $.ajax({
            type: 'POST',
            url: 'getDisposedApplicationDetails',
            data: {
                "_token": $('#token').val(),
                applicationid: applId
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
                    if(json[i].disposeddate==null){
                       $("#modl_disposedate").val('');
                    }
                    else
                    {
                        var dor = json[i].disposeddate;
                        var dor_split = dor.split('-');
                        var applnDisposedDate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
                        $("#modl_disposedate").val(applnDisposedDate);  
                    }
                      
                        $("#modl_subject").val(json[i].subject);
                    }
                } else {
                    $("#modl_applno").val('');
                    $("#displAppl1").hide();
                    $("#displAppl2").hide();
                    swal({
                        title: "Application  Does Not Exist/ not disposed",

                        icon: "error"
                    })
                }
            }
        });
        }
        else if(applTypeName == 'OA' || applTypeName == 'MA')
        {
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
        }
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

 $("#receiptNo").blur(function(){
		var receiptNo   = $('#receiptNo').val();
		 $("#recpSubmit").prop("disabled", true);
		if(receiptNo == '')			
		{
			//alert("Select Receipt No.");
		}else{
			$.ajax({
			type: 'POST',
			url: 'getReceiptDtlsForFreshAppl',
			data: {
				"_token": $('#token').val(),
				receiptNo: receiptNo
			},
			dataType: "JSON",
			success: function(json) {
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
				
				    alert('Receipt No: '+receiptNo+' not generated');
					 $('#applName').val('');
					$('#recpAmount').val('');
					$(".advancedSearch1 .selection1").text('');
			    	$(".title_sel1").css('display', 'none');
			    	$("#receiptDate").val('');	
					 $("#recpSubmit").prop("disabled", true);
					
					
			}
		}
		}) }
});

$("#recpSubmit").click(function() {
	var flag;
	if($('#receiptNo').val()==''){
		alert("Select Receipt No. ");
		return false;
	}
       $("table.myTable tr.item").each(function() {
				var receiptno=$(this).find("input.receiptno").val();
 				
				if(receiptno ==  $('#receiptNo').val())
				{
					flag=true;
				}
		  });	
		  if(flag == true){
	    	alert("Receipt No. already added");
		  }
		else{		  
		 var row = $("<tr class='item'>");
			row.append('<td class="col-md-2"><input type="hidden" name="receiptno[]" class="receiptno" id ="receiptno" value="' + $('#receiptNo').val() + '">' +$('#receiptNo').val()+ '</td>');
			row.append('<td class="col-md-2">' + $("#receiptDate").val() + '</td>');
			row.append('<td class="col-md-2">' +$('#applName').val() + '</td>');
			row.append('<td class="col-md-2 "><input type="hidden" class="reciptAmount" id ="recpAmount" value="' + $('#recpAmount').val() + '">' + $('#recpAmount').val()+ '</td>');
			row.append('<td class="col-md-2"><a href="#" class="deleteRow btn btn-sm btn-danger"  type="button" data-value="' + $('#receiptNo').val() + '">X</a></td>');
			$('#example1').append(row)
			$('#applName').val('');
			$('#receiptNo').val('');
			$('#recpAmount').val('');
			$(".advancedSearch1 .selection1").text('');
			$(".title_sel1").css('display', 'none');
			$("#receiptDate").val('');	
			$("#recpSubmit").prop("disabled", true);
					
		}					

	});
	
 $("table.receipt-list").on("click", ".deleteRow", function(event) {
		$(this).closest("tr").remove();
		});

$('.chkbox').click(function(){
	//alert('df');
	if ($('input[name="applicantgovt"]').is(':checked') || $('input[name="suomotoappl"]').is(':checked')) {
		$(".receiptdiv").css('display', 'none');       
	}
	else{
	$(".receiptdiv").css('display', 'inline');
    }
});


$('.btnClear').click(function(){
$("#freshApplicationForm").trigger('reset');
$('#example1').find('tbody tr').remove();
		
// $("#myTable1").hide();
// $("#reorderbtn").hide();
// $("#myTable2").hide();
// $(".applDiv").hide();
// $("#mytablediv").hide();
// $("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',false);
// $("#courthall,#listno,#causetypecode,#benchJudge,#benchCode").css('pointer-events', 'visible');
//  $("#myTable").hide();  //ia table
//  document.getElementById("hearingDate").focus();
})

});

// $('#freshApplicationForm').submit(function(e) 
$('#freshApplicationForm').on('submit', function(e)
   {
	  var newtype = $('#applTypeName').val().split('-');
      var applnewtype = newtype[1]; 
	  reviewApplId = $('#reviewApplId1').val(); 
	//   if((reviewApplId == '') && ( applnewtype != 'UA') )  //commented for taking KERC application Type      
     if(reviewApplId == '' && ( applnewtype != 'OP') )   //Raju Changed applnewtype= from 'OP' to 'UA'
	   {           
		   alert("Select Referring application");    
		   return false;
	   }
	   
	$('#applicantgovt').val('');
	$('#suomotoappl').val('');
    var noOfApplicants = $('#noOfAppl').val();
	var totReceiptAmt =0  ;
	var additionalNo = parseInt($('#additionalNo').val()) || 0;
    var receiptamt = $('#applTypeName').val().split('-')[2];
    var totReceiptAmtCalc = (parseInt(noOfApplicants) + parseInt(additionalNo))*(receiptamt);
    $("table.myTable tr.item").each(function() {
				var amount=$(this).find("input.reciptAmount").val();
 				totReceiptAmt=parseInt(totReceiptAmt)+parseInt(amount);
		  });	

    if (applnewtype == 'RA' || applnewtype=='UR' ) {
		if ($('input[name="applicantgovt"]').is(':checked')) {
			$('#applicantgovt').val('Y');			
				totReceiptAmt=0;
				totReceiptAmtCalc=0;
		} else{
			$('#applicantgovt').val('');
        }
		}
	 if (applnewtype == 'CA' || applnewtype=='UC') {
        if ($('input[name="suomotoappl"]').is(':checked')) {
            	$('#suomotoappl').val('Y');
				totReceiptAmt=0;
                totReceiptAmtCalc=0;
        } 
		else{
			$('#suomotoappl').val('');
	 }}
	//if (totReceiptAmt == totReceiptAmtCalc)
    // {
     e.preventDefault();
       swal({
        title: "Are you sure to save?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#freshApplicationForm').submit();
                } 
        });
       // }
     //   else{
      //      alert('No of applicants and Receipt Amount is not matching !');
    //        return false;
      //  }
    });

