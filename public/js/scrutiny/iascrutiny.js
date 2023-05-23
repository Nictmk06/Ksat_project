
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
		
		var flag='application';
	 	$.ajax({
		type: 'POST',
		url: 'getPendingIADoc',
		data:  { "_token": $('#token').val(),application_id:applicationId,docType:1},
		dataType: "JSON",
		success: function (json) {
			if(json.length>0)
			{
				for (var i = 0; i < json.length; i++) {
					var option = '<option value="'+json[i].iano+'">'+json[i].iano+'</option>';
	  				$('#iano').append(option);
					
				
				}
				
				
			}
			else
			{
				swal({
				title: "No I.A. for this Application",

				icon: "error"
				})
				
				//$("#applTypeName").val('');				
				//$("#applicationId").val('');
			}
		}
	});


});

	
		
    var startDate = $("#iaFilingDate").val();
    $('#accrejdate').datepicker('setStartDate', startDate);
    $('#tobecomplieddate').datepicker('setStartDate', startDate);
    if ($('#sbmt_adv').val()=='A')
    {
      $('#remarks1').val(startDate);
    }

    $("#accrejdate").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
        }).on('changeDate', function(selected) {
            var startDate = new Date(selected.date.valueOf());
            var oldDate = new Date(selected.date);
     	    	var endDate = new Date();
            endDate.setDate(oldDate.getDate() +60);
       
            $('#tobecomplieddate').datepicker('setStartDate', startDate);
            $('#tobecomplieddate').datepicker('setEndDate', endDate);
        }).on('clearDate', function(selected) {
            $('#tobecomplieddate').datepicker('setStartDate', null);
            $('#tobecomplieddate').datepicker('setEndDate', null);
        });
});

//Scrutiny Compliance
$(document).ready(function() {
 $("table.complaince tr.item").each(function() {
				var selected=$(this).find("select.chkList").val();
		        var remarks=$(this).find("textarea.remarks").val();
 				if(selected =='Y'){
 					$(this).find("select.chkList").attr('readonly','readonly');
	                $(this).find("textarea.remarks").attr('readonly','readonly');
	               
	                }
					 $('.extraObjection').each(function(){
					 		$(this).attr('readonly','readonly');
					  });
		  });
});

var counter = 1;
$("#addrow2").on("click", function() {
		var newRow = $("<tr class='item'>");
		var cols = "";

		//cols += '<td class="col-sm-1"><input type="hidden" class="counter" name="count[]" value="' + counter + '">' + counter + '</td>;'
		cols += '<td ><textarea type="text" name="extraObjection[]"  id="extraObjection" data-parsley-required data-parsley-required-message="Enter Objection" data-parsley-trigger="focusout"  class="form-control number" /></textarea></td>';
		cols += '<td ><select class="form-control chkList" name="extraCompliance[]" id="extraCompliance" data-parsley-required data-parsley-required-message="Select Compliance"  > <option value="" >Select Compliance </option><option value="Y">Yes</option> <option value="N">No</option> </select></td>';
		

		cols += '<td ><textarea type="text" name=extraRemarks[] id="extraRemarks" class="form-control remarks" /></textarea></td>';
		cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>;';
		newRow.append(cols);
		$("table.application-list").append(newRow);
		counter++;
	});

$("table.application-list").on("click", ".ibtnDel", function(event) {
		$(this).closest("tr").remove();
		});

$('.btnClear').click(function(){
   //alert('dkjs');
   location.reload();
   //window.history.back();
 })

function checkConditions(){
		 var flag= "Y";
		 var remarksFlag="Y";
		 $("table.myTable tr.item").each(function() {
				var selected=$(this).find("select.chkList").val();
		        var remarks=$(this).find("textarea.remarks").val();
 				if(selected =='N'){
	                	flag = "N";
	                	if (remarks.trim() =='')
	                	{
	                		remarksFlag ='N';
	                	}
	                }
		  });

     var applicationComplied = $('#applicationComplied').val();
    if(applicationComplied =='N')
     {
       var complieddate = $('#tobecomplieddate').val();
       if (complieddate =='')
 	    {
 	   	alert("Enter Date to be complied");
 	    return false;
 	    }else if($('#rejectReason').val().trim()=='')
 	    {
 	   	 	alert("Enter Reason for objection");
	 	    return false;
 	    }else{
 	     return true;	
 	    }
     }else if(remarksFlag=='N')
     {
       alert("Enter reason for No Compliance.");
       return false;
      }else if(flag==applicationComplied)
      {
 	   return true;
 	  }
 	  else{
	 	alert("Application Complied is not as per checklist");
		return false;
	 }
}

   $('#iaApplScrutinyForm').submit(function(e) 
   {
   	var flag = checkConditions();
   	//	alert(flag);
    if (flag == true)
    
    { e.preventDefault();
     
        swal({
        title: "Are you sure to save?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                	$('#iaApplScrutinyForm').submit();
                } 
		});
        }
        else{
        	return false;
        }
    });
        
	
