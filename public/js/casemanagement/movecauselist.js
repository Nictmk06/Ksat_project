$(document).ready(function(){
 
 $( "#tohearingDate" ).datepicker({dateFormat:"yy/mm/dd"})
		 .datepicker('setStartDate', new Date());

  $( "#tohearingDate" ).datepicker({dateFormat:"yy/mm/dd"})
		 .datepicker('setEndDate', null);
		 
	$("#hearingDate").change(function(){
		getcauselist();
	   });

	function getcauselist(){
		 var hearingdate = $("#hearingDate").val();
        $.ajax({
        type: 'post',
        url: "getcauselist",
        dataType:"JSON",
        data: {"_token": $('#token').val(),hearingdate:hearingdate},
        success: function (json) {        
            $('#causelist').find('option:not(:first)').remove();
            for(var i=0;i<json.length;i++){
             var option = '<option value="'+json[i].causelistcode+'">'+json[i].causelistdesc+'</option>';
                $('#causelist').append(option);
		    	}
		    }      
		});
	  }


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
   	
  	})


$("#listno").change(function() {
  var listno = $(this).val();
  var benchJudge = $("#benchJudge").val(); 
  var tohearingDate = $("#tohearingDate").val();
  var causetypecode= $("#causetypecode").val();
  var applicationid='';
  $("#courthall").attr('disabled',false);
  $("#causelistfrm").attr('disabled',false);

	  if($("#tohearingDate").val()=='')
	  {
		 $('#tohearingDate').parsley().removeError('tohearingDate');
		 $('#tohearingDate').parsley().addError('tohearingDate', {
					message: "Enter To Hearing Date"
					});
					return false;
	  }
	  else
	  {
		 $('#hearingDate').parsley().removeError('hearingDate');   
	  } 

	   if($("#benchCode").val()=='')
	  {
		 $('#benchCode').parsley().removeError('benchCode');
		$('#benchCode').parsley().addError('benchCode', {
					message: "Select Bench"
					});
					return false;
	  }
	  else
	  {
		$('#benchCode').parsley().removeError('benchCode');   
	  }

	   if($("#benchJudge").val()=='')
	  {
		$('#benchJudge').parsley().removeError('benchJudge');
		$('#benchJudge').parsley().addError('benchJudge', {
					message: "Enter Judge"
					});
					return false;
	  }
	  else
	  {
		$('#benchJudge').parsley().removeError('benchJudge');
	  }

	  if($("#causetypecode").val()=='')
	  {
		 $('#causetypecode').parsley().removeError('causetypecode');
		$('#causetypecode').parsley().addError('causetypecode', {
					message: "Select Cause Type"
					});
					return false;
	  }
	  else
	  {    $('#causetypecode').parsley().removeError('causetypecode');  }  
	
	$("#courthall").attr('disabled',false);
	$("#movecauselist").attr('disabled',false);
	$("#benchJudge,#benchCode,#courthall").css('pointer-events', 'visible');
    var causelistcode = $("#causelist").val();
	$.ajax({
	  type: 'post',
	  url: "getCauselistDataNew",
	  dataType:"JSON",
	  data: {"_token": $('#token').val(),benchJudge:benchJudge,hearingdate:tohearingDate,causetypecode:causetypecode,listno:listno},
	  success: function (json) {
	   //console.log(json);
		if(json.length>0)
		{
			if(json[0].causelistcode!=causelistcode){
			 alert("Cause list is already exists for this list no");
			  $("#courthall").attr('disabled',true);
		     $("#movecauselist").attr('disabled',true);
			 
			
		 }else{			
		}			
      }
    else
    {
	  $('#cuaselistForm').find('input, select, textarea').not("#hearingDate,#benchCode,#benchJudge,#causetypecode,#listno,#token,.btnClear").val('');
      $("#causelistcode").val(0);
    }
  }
  });
});

  
$("#benchJudge").change(function(){
     var benchjudge = $("#benchJudge").val();  
     var hearingdate = $("#hearingDate").val();
    $.ajax({
        type: 'post',
        url: "getcauselistappl",
        dataType:"JSON",
        data: {"_token": $('#token').val(),hearingdate, benchjudge},
        success: function (json) {

            $('#causelistfrm').find('option:not(:first)').remove();
           for(var i=0;i<json.length;i++){
             var option = '<option value="'+json[i].causelistcode+'">'+json[i].causelistdesc+'</option>';
                $('#causelistfrm').append(option);
           }
        }
      });
 })


$('.btnClear').click(function(){
	$("#movecauselistform").trigger('reset');
	$("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',false);
	$("#courthall,#listno,#causetypecode,#benchJudge,#benchCode").css('pointer-events', 'visible');
})


// end of document ready function
   })





  
