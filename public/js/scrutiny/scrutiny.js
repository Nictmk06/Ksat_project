
$(document).ready(function() {
    var startDate = $("#dateOfAppl").val();
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
		//cols += '<td ><select class="form-control chkList" name="extraCompliance[]" id="extraCompliance" data-parsley-required data-parsley-required-message="Select Compliance"  > <option value="" >Select Compliance </option><option value="Y">Yes</option> <option value="N">No</option> </select></td>';


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
console.log(applicationComplied);
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
     }
    else if(applicationComplied =='NA')
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
      }
     else if(remarksFlag=='N')
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

   $('#myForm').submit(function(e)
   {e.stopImmediatePropagation();
     e.preventDefault();
   	var flag = checkConditions();
   	//	alert(flag);
    if (flag == true)

    {

        swal({
        title: "Are you sure to save?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){
                if(isConfirm){
                	$('#myForm').submit();
                }
		});
        }
        else{
        	return false;
        }
    });
