$(document).ready(function(){
  
 $( "#hearingDate" ).datepicker({dateFormat:"dd/mm/yy"})
      .datepicker("setDate",new Date()).datepicker('setStartDate', new Date());

 $("#hearingDate").change(function() {
 // alert('mdnfg');
  var hearingdate = $("#hearingDate").val();
  $("#cuaselistForm").trigger('reset');
  $("#hearingDate").val(hearingdate);
 }
);


 $("#listno").change(function() {
  $("#benchJudge,#benchCode,#courthall").css('pointer-events', 'visible');
  var listno = $(this).val();
  var benchJudge = $("#benchJudge").val(); 
  var hearingdate = $("#hearingDate").val();
  var causetypecode= $("#causetypecode").val();
  var applicationid='';
  $("#courthall").attr('disabled',false);
  $("#causelistfrm").attr('disabled',false);

  if($("#hearingDate").val()=='')
  {
     $('#hearingDate').parsley().removeError('hearingDate');

    $('#hearingDate').parsley().addError('hearingDate', {
                message: "Enter Hearing Date"
                });
                return false;
  }
  else
  {
    $('#hearingDate').parsley().removeError('hearingDate');
   
  //getCauselistdata(hearingdate,benchJudge,applicationid);
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
  {
    $('#causetypecode').parsley().removeError('causetypecode');
  }
  
  //getCauselistdata(hearingdate,benchJudge,applicationid);
$.ajax({
  type: 'post',
  url: "getCauselistDataNew",
  dataType:"JSON",
  data: {"_token": $('#token').val(),benchJudge:benchJudge,hearingdate:hearingdate,causetypecode:causetypecode,listno:listno},
  success: function (json) {
   console.log(json);
    if(json.length>0)
    {
     if(json[0].finalizeflag=='Y'){
		 alert("Cause list is finalised for this list no");
		 $('#cuaselistForm').find('input, select, textarea').not("#hearingDate,#benchCode,#benchJudge,#causetypecode,#listno,#token,#causedate_val,#savecauseundate,.btnClear").val('');
		 $("#causedate_val").val('A');
		 $("#causelistcode").val(0);
		 $("#myTable").hide();
		 $("#myTable1").hide();
		// $("#reorderbtn").hide();
     $("#reorderbtn").css('display','none');
     $("#courthall").attr('disabled',true);
     $("#causelistfrm").attr('disabled',true);

	 }else{
        $("#courthall").val(json[0].courthallno);
        $("#causelistcode").val(json[0].causelistcode);
        var causelistcode = json[0].causelistcode;
        var applicationid = '';
        $("#courthall").attr('disabled',false);
        $("#causelistfrm").attr('disabled',false);
         $("#benchJudge,#benchCode,#courthall").css('pointer-events', 'none');
//        getCauselistdata(hearingdate,benchJudge,applicationid);
        getCauselist(causelistcode);
     }
    }
    else
    {
     $('#cuaselistForm').find('input, select, textarea').not("#hearingDate,#benchCode,#benchJudge,#causetypecode,#listno,#token,#causedate_val,#savecauseundate,.btnClear").val('');
     $("#causedate_val").val('A');
     $("#causelistcode").val(0);
     $("#myTable").hide();
     $("#myTable1").hide();
    }
  }
  });
});

$("#rearange").click(function(){
  var causelistcode = $("#causelistcode").val();
//  var benchJudge = $("#benchJudge").val(); 
  $.ajax({
        type: 'post',
        url: "getRearrangeCauselistData",
        dataType:"JSON",
        data: {"_token": $('#token').val(),causelistcode:causelistcode},
        success: 
       
        function (data) {

                if(data.status=="sucess")
                {
                  var causelistcode = data.causecode;
                   swal({
                  title: data.message,

                  icon: "success"
                  })
                  // $("#cuaselistForm").trigger('reset');
                  var benchJudge = $("#benchJudge").val();  
                  var hearingdate = $("#hearingDate").val();
                  var applicationid = '';
                  var causelistcode = data.causecode;

                  $(".checkBoxClass").prop('checked',false);
                  $("#causelistcode").val(data.causecode);
       
            
                  if ($("#causelistfrm").val()=='Dated')  { console.log('raj');
                         getCauselistdata(hearingdate,benchJudge,applicationid); 
                       }
                  else
                  {    console.log('raj - no dated  - rearrange button');}
                             // getCauselist(hearingdate,benchJudge);//for particualr cuaselist display records in list

                 getCauselist(causelistcode);

                }
                else if(data.status=="fail")
                {
                  swal({
                  title: data.message,

                  icon: "error"
                  })
                }

            }
              
          });

  })

$("#saveCausesrno").click(function(){
  var causelistcode = $("#causelistcode1").val();
  var cursrno = $("#curserialno").val();
  var moveto = $("#movetosrno").val();
  if(cursrno == moveto){
	alert("Current Serial No. and Move to cannot be same");     
	 }
	 else{
     $.ajax({
        type: 'post',
        url: "rearangeCauseOrder",
        dataType:"JSON",
        data: {"_token": $('#token').val(),causelistcode:causelistcode,cursrno:cursrno,moveto:moveto},
        success: function (data) {
         $('#modal-serial').modal('hide');
       //  console.log('raj---raj');
                if(data.status=="sucess")
                {
                  var causelistcode = data.causecode;
                   swal({
                  title: data.message,

                  icon: "success"
                  })
                  // $("#cuaselistForm").trigger('reset');
                  var benchJudge = $("#benchJudge").val();  
                  var hearingdate = $("#hearingDate").val();
                  var applicationid = '';
                  var causelistcode = data.causecode;

                  $(".checkBoxClass").prop('checked',false);
                  $("#causelistcode").val(data.causecode);
       
            
                  if ($("#causelistfrm").val()=='Dated')  { console.log('raj');
                         getCauselistdata(hearingdate,benchJudge,applicationid); 
                       }
                  else
                  {   
                             // getCauselist(hearingdate,benchJudge);//for particualr cuaselist display records in list
                 getCauselist(causelistcode);
                   }
                 }
                else if(data.status=="fail")
                {
                  swal({
                  title: data.message,

                  icon: "error"
                  })
                }

            }
              
          });
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


  	$("#causelistfrm").change(function(){
  		if($(this).val()=='Dated')
  		{
        $(".applDiv").hide();
        $("#iadocumentdiv").hide();
        $("#mytablediv").show();
        $("#posteddiv").hide();
        $("#applTypeName").attr('data-parsley-required',false);
        $("#applicationId").attr('data-parsley-required',false);
        $("#postedfor").attr('data-parsley-required',false);
        $("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',true);
        //$("#hearingDate").attr('disabled',true);
        
        $("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").css('pointer-events', 'none');
        var benchJudge = $("#benchJudge").val();  
        var hearingdate = $("#hearingDate").val();
       /* var causetypecode = $("#causetypecode").val();
        var listno = $("#listno").val();*/
        var applicationid='';
        getCauselistdata(hearingdate,benchJudge,applicationid);
  		}
  		else if($(this).val()=='Fresh' || $(this).val()=='Other')
  		{
        $("#applTypeName").attr('data-parsley-required',true);
        $("#applicationId").attr('data-parsley-required',true);
        $("#postedfor").attr('data-parsley-required',true);
        $("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',true);
      // $("#hearingDate").attr('disabled',true);
        $("#courthall,#listno,#causetypecode,#benchJudge,#benchCode,#hearingDate").css('pointer-events', 'none');
        $(".applDiv").show();
        $("#mytablediv").hide();
        $("#posteddiv").show();
  		$("#applTypeName").val('');
        $("#applicationId").val('');
        $("#dateOfAppl").val('');
        $("#applnRegDate").val('');
        $("#applnSubject").val('');
        $("#applCatName").val('');
        $("#postedfor").val('');
        $("#iadocumentdiv").hide();
         
  		}
      else 
      {
         $(".applDiv").hide();
        $("#mytablediv").hide();
      }
  		
  	})

    /*$("#benchJudge").change(function(){
      var benchJudge = $(this).val();  
      var hearingdate = $("#hearingDate").val();
      //console.log(benchJudge+''+hearingdate);
    })*/
    function getCauselistdata(hearingdate,benchJudge,applicationid)
    {
      
//     console.log(hearingdate+benchJudge+applicationid);
      $.ajax({
        type: 'post',
        url: "getHearingDetails",
        dataType:"JSON",
        data: {"_token": $('#token').val(),hearingdate:hearingdate,benchjudge:benchJudge,applicationid:applicationid},
        success: function (json) {
          if(json.length>0)
          {
           $('#myTable2').find('tbody tr').remove();
            $("#myTable2").show(); 
  //$("#myTable2").find('tbody tr').remove();
  var count = 1;
  $.each(json, function(index, obj) {

  var row = $('<tr>');
  if(obj.hearingdate==null)
{
  var HRdate ='---';
}
else
{
  var HearingDate =  obj.hearingdate;

  var arr =HearingDate.split('-');
  var HRdate =arr[2]+'-'+arr[1]+'-'+ arr[0];
}
  
 //$("#applid").val(obj.applicationid);
 //$("#purposecodeold").val(obj.purposecode);

row.append('<td><input type="checkbox" class="checkBoxClass" name="CLchkbox[]" value='+HRdate+'|'+obj.applicationid+'|'+obj.purposecode+' name="chkbox[]"></td>');
  
  row.append('<td>' +obj.applicationid + '</td>');
  
   
  
  row.append('<td>' +HRdate+ '</td>');


  row.append('<td >' + obj.listpurpose + '</td>');


  
  
row.appendTo('#myTable2');
//row.clone().appendTo('#myTable2');
  
  count++;
  })
              
          }
          else
          {
             $('#myTable2').show();
             $('#myTable2 tbody tr').remove();
             $("#myTable2 tbody").append('<tr><td colspan="4" style=" text-align: center;">No records to display</td></tr>');
           }
       
        }
      });
    }
    $("#ckbCheckAll").click(function () {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});

  /*  $("#saveCauselistappl").click(function(){

      var chk_val = new Array();
      var applid = new Array();
      var purposecode = new Array();
        $( "input[name='CLchkbox[]']:checked" ).each( function() {
              var chekval = $(this).val();
              var split = chekval.split('|');
             // var 
                chk_val.push(split[0]);
                applid.push(split[1]);
                purposecode.push(split[2]);
        } );
        var valid = false;
     $.ajax({
    type: 'POST',
    url: 'addCauselisttempappl',
    data: {"_token": $('#token').val(),chk_val: chk_val,applicationid:applid,purposecode:purposecode},
    dataType: 'JSON',
    success: function(data) {
       
            }
    });
    })*/
     
     $("#savecauseundate").click(function(e)
    {
        e.preventDefault();
		swal({
        title: "Are you sure to save?",
        icon: "warning",
        showCancelButton: true,
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => 
      {
          var benchJudge = $("#benchJudge").val();  
          var hearingdate = $("#hearingDate").val();
			  if (! (willDelete) )
			  return;
           var form = $(this).closest("form").attr('id');
           var formaction = $(this).closest("form").attr('action');
      // var application_id = $("#applicationId").val();
             
        $("#"+form).parsley().validate();
        if (! ($("#"+form).parsley().isValid()))
          {  return; }
       
          $.ajax({
            type: 'post',
            url: formaction,
            data: $('#'+form).serialize(),
            success: function (data) {
              if(data.status=='exists')
                {
                  swal({
                  title: data.message,
                  icon: "error"
                  })
          //         $("#cuaselistForm").trigger('reset');
                  $("#iadocumentdiv").hide();
          //         $("#myTable1").hide();

                }
                if(data.status=="connect")
                {
                  swal({
                  title: data.message,
                  icon: "error"
                  })
                 }
                if(data.status=="sucess")
                {
                  var causelistcode = data.causecode;
                  swal({
                  title: data.message,
                  icon: "success"
                  })
                  // $("#cuaselistForm").trigger('reset');
                  var benchJudge = $("#benchJudge").val();  
                  var hearingdate = $("#hearingDate").val();
                  var applicationid = '';
                  $(".checkBoxClass").prop('checked',false);
                  $("#causelistcode").val(data.causecode);


             //     if( $("#applicationId").val()==null)
             //     {
             //       var modl_appltype_name = $("#applTypeName").val();
             //       var newtype = modl_appltype_name.split('-');
             //       var applnewtype = newtype[1];
             //       var modl_modl_applno = $("#applicationId").val();
             //        applicationid =applnewtype+'/'+modl_modl_applno;
             //     }
                  
         //      else
         //         {

         //           $("#causedate_val").val('U');
         //         }
                  
               
                //   var applicationid='';
                  //get cuaselit with checkbox to prepare cuaselist 
            
                 
                getCauselist(causelistcode);
                if ($("#causelistfrm").val()=='Dated')  {
					console.log('raj');
                    getCauselistdata(hearingdate,benchJudge,applicationid); }
                else
                {
				  console.log('raj - no dated - save button');}
                             // getCauselist(hearingdate,benchJudge);//for particualr cuaselist display records in list
                }
                else if(data.status=="fail")
                {
                  swal({
                  title: data.message,
                  icon: "error"
                  })
                }
            }
          });
        
         if($("#causelistfrm").val()=='Fresh' || $("#causelistfrm").val()=='Others')
                  {
                    $("#applTypeName").val('');
                    $("#applicationId").val('');
                    $("#dateOfAppl").val('');
                    $("#applnRegDate").val('');
                    $("#applnSubject").val('');
                    $("#applCatName").val('');
                    $("#postedfor").val('');
                    $("#iadocumentdiv").hide();
                    $("#postedfor").val();
                     $("#causedate_val").val('A');

                  }
                  if($("#causelistfrm").val()=='Dated')
                  {
                     var applicationid='';
//                    getCauselistdata(hearingdate,benchJudge,applicationid);
//                    $("#causedate_val").val('S');
                    $(".applDiv").hide();
                    $("#mytablediv").show();
                  }


  });

  })












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
      var flag=$("#causelistfrm").val();
      $.ajax({
			type: 'POST',
			url: 'getApplicationDetailsCauseList',
			data:  { "_token": $('#token').val(),application_id:applicationId,flag:flag},
			dataType: "JSON",
			success: function (json) {
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
				  /*if(json[i].iasrno==null)
				  {
					$("#IASrNo").val('1');
					$('#IASrNo').attr('readonly',true);
				  }
				  else
				  {
					var newsrno = parseInt(json[i].iasrno)+1;
					$('#IASrNo').val(newsrno);
					$('#IASrNo').attr('readonly',true);
				  }*/
				  
				  
				  $("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
				  
				  $("#applCatName").val(json[i].applcategory);
				  $("#applnSubject").val(json[i].subject);
				}
				  var regdate_1  = $("#applnRegDate").val();
				  $('#IAFillingDate').datepicker('setStartDate', regdate_1);
				  $('#disposedDate').datepicker('setStartDate', regdate_1);
				  getApplDetails(applicationId);//ia document display det
			  }
			  else
			  {
        if(flag=='Fresh')
				{
          swal({
  				title: "Application Does Not Exist / Scrutiny not performed.",
  				icon: "error"
  			})}
        if(flag=='Other')
        {
         swal({
         title: "Application Does Not Exist ",
         icon: "error"
        })}
				$("#applnRegDate").val('');
				$("#dateOfAppl").val('');
				$("#applTypeName").val('');
				$("#applCatName").val('');
				$("#applnSubject").val('');
				$("#applicationId").val('');
				$("#myTable").hide();
			   // $("#myTable1").hide();
			   // $("#rearange").hide();
				$("#postedfor").val('');
			  }
    }
  });

});
     });






















// to display iadocument detals
function getApplDetails(applicationId)
{
  $("#myTable").show();
 var flag='dailyhearing';
  $("#iaflag").val('N');
          //  console.log("ia document");
              $.ajax({
              type: 'post',
              url: "getIADocAppl",
              dataType:"JSON",
              data: {"_token": $('#token').val(),application_id:applicationId,flag:flag},
              success: function (json) {
               
                if(json.length>0)
                {
                  $("#iaflag").val('Y');
                   $('#myTable').find('tbody tr').remove();
             
              var count = 1;
              $.each(json, function(index, obj) {
              var row = $('<tr>');
              var IADocdate =  obj.iafillingdate;
              var IADocReg=  obj.iaregistrationdate;
              var arr =IADocdate.split('-');
              var arr2 =IADocReg.split('-');

              if(obj.documenttypecode==1)
              {
                row.append('<td>' +obj.documentname + '</td>');
              row.append('<td>' + obj.iano + '</td>');
              if(obj.ianaturedesc==null)
              {
                row.append('<td>---</td>');
              }
              else
              {
                 row.append('<td>'+obj.ianaturedesc+'</td>');
               
              }
              
              row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
              row.append('<td>' + arr2[2]+'-'+arr2[1]+'-'+ arr2[0] + '</td>');

              row.append('<td >' + obj.statusname + '</td>');
              }
              




              row.appendTo('#myTable');
              //row.clone().appendTo('#myTable2');

              count++;
              })
                  $("#iadocumentdiv").show();
                }
                else
                {
                   $("#iadocumentdiv").hide();
                }
             

  
  
}
});

}




//-----------------------------------


$('.btnClear').click(function(){
$("#cuaselistForm").trigger('reset');
$("#myTable1").hide();
$("#reorderbtn").css('display','none');
//$("#reorderbtn").hide();
$("#myTable2").hide();
$(".applDiv").hide();
$("#mytablediv").hide();
$("#courthall,#listno,#causetypecode,#benchJudge,#hearingDate,#benchCode").attr('readonly',false);
//$("#hearingDate").attr('disabled',false);

$("#courthall,#listno,#causetypecode,#benchJudge,#benchCode,#hearingDate").css('pointer-events', 'visible');
 $("#myTable").hide();  //ia table
 document.getElementById("hearingDate").focus();
})




//display cuaselist details
    function  getCauselist(causelistcode)
    {
      
       
        $.ajax({
        type: 'post',
        url: "getCauselistdetails",
        dataType:"JSON",
        data: {"_token": $('#token').val(),causelistcode:causelistcode},
        success: function (json) {
          //console.log(json);
          $("#rearange").val('Rearrange SRNo');
          //$("#reorderbtn").show();
          $("#reorderbtn").css('display','inline');
           $('#myTable1').css('display','block');
            $('#myTable1').find('tbody tr').remove();
  //$("#myTable2").find('tbody tr').remove();
  console.log('getCauselist - raj');
  var count = 1;
  $.each(json, function(index, obj) {
var row = $('<tr>');
  // console.log(obj.causelistsrno);
  row.append('<td class="col-md-4">' +obj.listpurpose + '</td>');
  row.append('<td class="col-md-2">' +obj.applicationid+ '</td>');

if(obj.causelistsrno!=null)
{

  row.append('<td class="col-md-2"><a href="#" class="causeclick" data-value="'+obj.causelistcode+'-'+obj.causelistsrno+'">' + obj.causelistsrno + '</a></td>');
}
else
{
  row.append('<td class="col-md-2">' +'---' + '</td>');
}
row.append('<td class="col-md-2">' +obj.enteredfrom+ '</td>');
if(obj.connectedcase==1)
{ if (obj.iaflag=='Y')
  row.append('<td class="col-md-2">Y - Y</td>');
  else
  row.append('<td class="col-md-2">N - Y</td>');
}
else
{
  if (obj.iaflag=='Y')
  row.append('<td class="col-md-2">Y - N</td>');
else
  row.append('<td class="col-md-2">N - N </td>');
}
/*if($("#causedate_val").val()=='U')
{*/
  //console.log(obj.applicationid+'|'+obj.causelistcode);
  $("#delete").show();
  row.append('<td ><a data-value='+obj.applicationid+'|'+obj.causelistcode+' class="btn btn-danger deletecause">X</a></td>');
  //  row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="deletecause" >' + count + '</td>');
/*}
else
{
  $("#delete").hide();
}*/
  
row.appendTo('#myTable1');
//row.clone().appendTo('#myTable2');
  
  count++;
  })


  $(".causeclick").click(function(){
  $('#modal-serial').modal('show');
  var causedata  = $(this).attr('data-value');
  var datasplit = causedata.split('-');
  var causelistcode = datasplit[0];
  var causesrno = datasplit[1];
  $("#curserialno").val(causesrno);
  $("#curserialno").attr('readonly',true);
  $("#causelistcode1").val(causelistcode);
})


  $(".deletecause").click(function(){
    var cause = $(this).attr('data-value');
    //console.log(cause);
    var appl = cause.split('|');
     $.ajax({
        type: 'post',
        url: "deletecause",
        dataType:"JSON",
        data: {"_token": $('#token').val(),causelistcode:appl[1],applicationid:appl[0]},
        success: function (json) {
          if(json.status=='sucess')
          {
            swal({
            title: json.message,

            icon: "success"
            })

             if (json.value == '1')
                   { console.log('raj'); $('#causelistvalue').val(0); }

            var benchJudge = $("#benchJudge").val();  
            var hearingdate = $("#hearingDate").val();
            var applicationid='';
            if ($("#causelistfrm").val()=='Dated') 
                    getCauselistdata(hearingdate,benchJudge,applicationid);

             getCauselist( $("#causelistcode").val());

             //$("#cuaselistForm").trigger('reset');
          }
          else
          {
            swal({
            title: json.message,

            icon: "error"
            })
          }
        }
      });
  })
        }
      });
        
    }





  
    
//      });
//})
