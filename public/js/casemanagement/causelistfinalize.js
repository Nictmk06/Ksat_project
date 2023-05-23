$(document).ready(function(){
  
$( "#hearingDate" ).datepicker({dateFormat:"yy/mm/dd"})
      .datepicker("setDate",new Date()).datepicker('setStartDate', new Date());



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
$("#cuaselistForm").trigger('reset');
$("#myTable1").hide();
//$("#reorderbtn").hide();
//$("#myTable2").hide();
$(".applDiv").hide();
//$("#mytablediv").hide();
$("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',false);
$("#courthall,#listno,#causetypecode,#benchJudge,#benchCode").css('pointer-events', 'visible');
 $("#myTable").hide();  //ia table
 document.getElementById("hearingDate").focus();
})



  $("#applselect").change(function(){
    var applid = $("#applselect").val();

  if (applid != 0)
    { $("#choice").val(1); 
       $("#conapplselect").val(0);
     }
     else
        $("#choice").val(0); 

  getremarks(applid,1);

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


   function  getremarks(applid,choice)
    {   
       
        var applicationid = applid;
        var causelistcode = $("#causelistfrm").val();
        console.log(applid);

        $.ajax({
        type: 'post',
        url: "getcauselistremarks",
        dataType:"JSON",
        data: {"_token": $('#token').val(),applicationid:applicationid,causelistcode:causelistcode,choice:choice},
        success: function (json) {
          console.log(json);
           $('#appautoremarks').val(json[0].appautoremarks);
          $('#resautoremarks').val(json[0].resautoremarks);
          $('#appuserremarks').val(json[0].appuserremarks);
          $('#resuserremarks').val(json[0].resuserremarks);
        }
     })
      }

$("#save").click(function(e){
      e.preventDefault();
    swal({
        title: "Want to update the remarks?",
        icon: "warning",
        showCancelButton: true,
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
           var form = $(this).closest("form").attr('id');
             var formaction = $(this).closest("form").attr('action');
            
      
      $("#"+form).parsley().validate();
        if ($("#"+form).parsley().isValid())
  
        {
                 $.ajax({
                    type: 'post',
                    url: formaction,
                    data: $('#'+form).serialize(),
                    success: function (data) {
                          
                   swal({
                  title: data.message,
                  icon: "success"
                  });

          $('#appautoremarks').val(" ");
          $('#resautoremarks').val(" ");
          $('#appuserremarks').val(" ");
          $('#resuserremarks').val(" ");

                     }

                   })
              
      }
   }
 })
})


$('#save1').click(function(){
  
 
  var causelistcode = $("#causelistfrm").val();
  var clheader = $("#clheader").val();
  var clfooter = $("#clfooter").val();
  var clnote = $("#clnote").val();
  var causelisttime = $("#causelisttime").val();

    swal({
        title: "Want to update the Header,Footer,Note?",
        icon: "warning",
        showCancelButton: true,
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
      
//        $("#"+form).parsley().validate();
//        if ($("#"+form).parsley().isValid())
  
//        {

   $.ajax({
        type: 'post',
        url: "updatecauselistheader",
        dataType:"JSON",
        data: {"_token": $('#token').val(),causelistcode:causelistcode,clheader:clheader,clfooter:clfooter,clnote:clnote,causelisttime:causelisttime},
        success: function (data) {
              swal({
                  title: data.message,
                  icon: "success"
                  });
              console.log('success');
        $("#clheader,#clfooter,#clnote,#causelisttime").attr('readonly',true);
      
                                        
        }
      })
// }

}

});
} )

// end of document ready function
        
    })





  
