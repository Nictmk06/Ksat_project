$(document).ready(function(){  

	$( "#hearingDate").datepicker({dateFormat:"yy/mm/dd"})
		 .datepicker('setStartDate', new Date());

$( "#replyDate").datepicker({dateFormat:"yy/mm/dd"})
     .datepicker('setStartDate', new Date());


$( "#replyDate" ).datepicker({dateFormat:"yy/mm/dd"})
     .datepicker('setEndDate', null);

	$("#hearingDate").change(function(){
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

$("#causelist").change(function(){
	    var causelistcode  = $(this).val();
        $.ajax({
				type: 'post',
				url: "getcauselistapplconnect",
				dataType:"JSON",
				data: {"_token": $('#token').val(),causelistcode:causelistcode},
				success: function (json) {
				 	$('#applselect').find('option:not(:first)').remove();
				   $('#conapplselect').find('option:not(:first)').remove();
				   for(var i=0;i<json.length;i++){
					 var option = '<option value="'+json[i].applicationid+'">'+json[i].causelistsrno+'-->'+json[i].applicationid+'</option>';
						$('#applselect').append(option);				
					}
				  //for unique values in select
				  $(function() {
					  var select = $('#applselect');
					  var objOpt = {};
					  select.find('option').each(function() {
						  objOpt[this.outerHTML] = this;
					  });
					  select.empty();
					  for(key in objOpt) {
						  select.append( objOpt[key] );
						};
            $("#applselect").val("0").attr('selected','selected');
				});  
				}
      });
  	})




 $("#applselect").change(function(){
    var applid = $("#applselect").val().trim();
    var causelistcode = $("#causelist").val();
    $.ajax({
				type: 'post',
				url: "getcauselistconnectedappl",
				dataType:"JSON",
				data: {"_token": $('#token').val(),causelistcode:causelistcode,applicationid:applid},
				success: function (json) {
				  //console.log(json);
				  $('#conapplselect').find('option:not(:first)').remove();
				   for(var i=0;i<json.length;i++){
					 if (json[i].conapplid != '') {
					 var option = '<option value="'+json[i].conapplid+'">'+json[i].applicationid+'-->'+json[i].conapplid+'</option>';
					 $('#conapplselect').append(option); 
                     }
					}
				}
      });	

})

    $("#conapplselect").change(function(){
    var applid = $("#conapplselect").val();
  
    if (applid != 0) {
    $("#choice").val(2); 
     $("#applselect").val(0);
      }
      else
         $("#choice").val(0); 
      getremarks(applid,2);

})


  
   


$('.btnClear').click(function(){
$("#ordergenerationform").trigger('reset');

$("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',false);
$("#courthall,#listno,#causetypecode,#benchJudge,#benchCode").css('pointer-events', 'visible');

})







// end of document ready function
        
    })





  
