$(document).ready(function(){

  $('#toggleCheckbox').change(function(e){
    $('#CaseCheckUncheck input[type="checkbox"]').prop('checked', this.checked);
  });


 $('#tohearingDate').datepicker('setStartDate',  new Date());
	  $('#tohearingDate').datepicker('setEndDate', null);
 
	$( "#hearingDate" ).datepicker('setStartDate', new Date());
     $('#hearingDate').datepicker('setEndDate', null);
 
	//$( "#tohearingDate" ).datepicker({dateFormat:"yy/mm/dd"})
	//	 .datepicker('setStartDate', new Date());

  //$( "#tohearingDate" ).datepicker({dateFormat:"yy/mm/dd"})
	//	 .datepicker('setEndDate', null);
		 
	$("#hearingDate").change(function(){
		var hearingDate = $("#hearingDate").val();
		$('#tohearingDate').datepicker('setStartDate', hearingDate);
		$('#tohearingDate').datepicker('setEndDate', null);
 
		getcauselist();
	   });

	function getcauselist(){
		 var hearingdate = $("#hearingDate").val();
        $.ajax({
        type: 'post',
        url: "getFinalizedcauselist",
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

	$('#causelist').change(function() {
       var causelistcode  = $(this).val();
       $.ajax({
        type: 'post',
        url: "getcauselistapplforTransfer",
        dataType:"JSON",
        data: {"_token": $('#token').val(),causelistcode:causelistcode},
       success: function (json) 
        {
		  $('#myTable1').find('tbody tr').remove();
         var counter=0;
           for(var i=0;i<json.length;i++){
        var row = $('<tr>');
         row.append('<td class="grid-item" width="15%" id="CaseCheckUncheck"> <input type="checkbox" name="caseSelect[]" id="caseSelect[]" value="'+json[i].applicationid+"::"+json[i].causelistcode+'" ></td>');
         if(json[i].causelistsrno == null || json[i].causelistsrno =='')
        {
          row.append('<td class="grid-item" width="35%">' +json[i].applicationid + '</td>');
		 // row.append('<td class="grid-item" width="20%">' +json[i].conapplid + '</td>');
        
        }
        else
        {
          row.append('<td class="grid-item" width="35%">' +json[i].causelistsrno +" --> "+json[i].applicationid + '</td>');
		  //  row.append('<td class="grid-item" width="20%">' +json[i].conapplid + '</td>');
		  
        } 

        row.append('<td class="grid-item" width="45%">' +json[i].listpurpose + '</td>');
        row.append('</tr>');
        row.appendTo('#myTable1');
        counter++;
          }
    
        }  // End of : success: function (data) for $('#postedFor').change(function() 
      });  // End of : $.ajax({ for  $('#postedFor').change(function()    
    }); 



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

/*
$("#transferapplication").click(function(e){
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
                    $('#causelisttransferform').submit();
                } 
        });
})
*/
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
	$("#transferapplication").attr('disabled',false);
	$("#benchJudge,#benchCode,#courthall").css('pointer-events', 'visible');
    $.ajax({
	  type: 'post',
	  url: "getCauselistDataNew",
	  dataType:"JSON",
	  data: {"_token": $('#token').val(),benchJudge:benchJudge,hearingdate:tohearingDate,causetypecode:causetypecode,listno:listno},
	  success: function (json) {
	   //console.log(json);
		if(json.length>0)
		{
			 if(json[0].finalizeflag=='Y'){
			 alert("Cause list is finalised for this list no");
			 $('#cuaselistForm').find('input, select, textarea').not("#hearingDate,#benchCode,#benchJudge,#causetypecode,#listno,#token,#savecauseundate,.btnClear").val('');
			 $("#causelistcode").val(0);		
			 $("#courthall").attr('disabled',true);
		     $("#transferapplication").attr('disabled',true);
		 }else{
			 $("#courthall").val(json[0].courthallno);
			$("#causelistcode").val(json[0].causelistcode);
			var causelistcode = json[0].causelistcode;
			var applicationid = '';
			$("#benchJudge,#benchCode,#courthall").css('pointer-events', 'none');
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


  	$("#causelistfrm").change(function(){
  	    var causelistcode = $("#causelistfrm").val();
  
       $("#causelistfrm").val(causelistcode);

       $("#benchJudge,#hearingDate,#benchCode").attr('readonly',true);
        $("#benchJudge,#benchCode").css('pointer-events', 'none');
          
  $.ajax({
        type: 'post',
        url: "getcauselistapplconnect",
        dataType:"JSON",
        data: {"_token": $('#token').val(),causelistcode:causelistcode},
        success: function (json) {
          //console.log(json);

  $("#clheader").val(json[0].clheader);
   $("#clfooter").val(json[0].clfooter);
   $("#clnote").val(json[0].clnote);
   $("#causelisttime").val(json[0].causelisttime);

             $('#applselect').find('option:not(:first)').remove();
           for(var i=0;i<json.length;i++){
             var option = '<option value="'+json[i].applicationid+'">'+json[i].causelistsrno+'-->'+json[i].applicationid+'</option>';
                $('#applselect').append(option);
            if (json[i].conapplid != '') {
             var option = '<option value="'+json[i].conapplid+'">'+json[i].applicationid+'-->'+json[i].conapplid+'</option>';
                $('#conapplselect').append(option); }
            }
          

           
             

// To display on the grid

 $("#rearange").show();
 $("#reorderbtn").show();
  $('#myTable1').css('display','block');
  $('#myTable1').find('tbody tr').remove();
  var count = 1;
  for(var i=0;i<json.length;i++){
var row = $('<tr>');
  // console.log(obj.causelistsrno);

if(json[i].causelistsrno!=null)
{

  row.append('<td class="col-md-2">' + json[i].causelistsrno + '</td>');
}
else
{
  row.append('<td class="col-md-2">' +'---' + '</td>');
}

  row.append('<td class="col-md-2">' +json[i].listpurpose + '</td>');
  row.append('<td class="col-md-4">' + json[i].applicationid+ '</td>');
 row.append('<td class="col-md-4"> '+json[i].conapplid+'</td>');
row.append('<td class="col-md-2">' +json[i].enteredfrom+ '</td>');
if(json[i].connected=='Y')
{ if (json[i].iaflag=='Y')
  row.append('<td class="col-md-2">Y - Y</td>');
  else
  row.append('<td class="col-md-2">N - Y</td>');
}
else
{
  if (json[i].iaflag=='Y')
  row.append('<td class="col-md-2">Y - N</td>');
else
  row.append('<td class="col-md-2">N - N </td>');
}
  
row.appendTo('#myTable1');
  
  count++;
  }

}

       //    getCauselist(causelistcode);
  		
  	});
  	})


  
   


$('.btnClear').click(function(){
$("#causelisttransferform").trigger('reset');
 $('#myTable1').find('tbody tr').remove();
//$("#mytablediv").hide();
$("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',false);
$("#courthall,#listno,#causetypecode,#benchJudge,#benchCode").css('pointer-events', 'visible');

})







// end of document ready function
        
    })





  
