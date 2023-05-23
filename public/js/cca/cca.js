$(document).ready(function() {

$("#ccdeliverydate").datepicker({dateFormat:"dd/mm/yy"})
      .datepicker("setDate",new Date()).datepicker('setStartDate', new Date())
	  .datepicker('setEndDate', null);

  $(".caapplDetails").hide();
  $("#dateOfOrd,#noOfPages,#receiptDate,#recpAmount").css('pointer-events', 'none');
  $("#caapplicantname").attr('data-parsley-required', false);
			$("#caaddress").attr('data-parsley-required', false);
			$("#capincode").attr('data-parsley-required', false);
			$("#distcode").attr('data-parsley-required', false);
			$("#CATaluk").attr('data-parsley-required', false);


$("#editApplication").click(function(){
			$('#editmodal').modal('show');
			$('#edit_applno').val('');
			$('#edit_appl-title').text('Edit Copy Application');

		})

$("#editSearch").click(function(){
		var type = $("#edit_modal_type").val();
	    var applId = $("#edit_applno").val();
		$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getCCApplicationDetails',
				data: {"_token": $('#token').val(),ccaapplicationno:applId},
				success: function (json) {
				if(json.length>0)
		    	{
				  if(json[0].ccastatuscode == 1 || json[0].ccastatuscode == 2){
					for (var i = 0; i < json.length; i++) {
						if(json[i].registerdate1 == null){
                        $("#applnRegDate").val('');
						}
						else
						{
						var dor = json[i].registerdate1;
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
					if(json[i].caapplicationdate==null)
					{
						$("#dateOfCA").val('');
					}
					else
					{
						var doa = json[i].caapplicationdate;
						var doa_split = doa.split('-');
						var dateOfCA = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#dateOfCA").val(dateOfCA);
					}

					if(json[i].orderdate==null)
					{
						$("#dateOfOrd").val('');
					}
					else
					{
						var doa = json[i].orderdate;
						var doa_split = doa.split('-');
						var dateOfOrd = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#dateOfOrd").val(dateOfOrd);
					}

					if(json[i].receiptdate==null)
					{
						$("#receiptDate").val('');
					}
					else
					{
						var doa = json[i].receiptdate;
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#receiptDate").val(dateOfApp);
					}
          if(json[i].copyreadyon==null)
          {
            $("#ccdeliverydate").val('');
          }
          else
          {
            var doa = json[i].copyreadyon;
            var doa_split = doa.split('-');
            var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
            $("#ccdeliverydate").val(dateOfApp);
          }
          $("#noOfPages").attr('data-parsley-required', false);
          $("#noOfCopies").attr('data-parsley-required', false);
          $("#amount_coll").attr('data-parsley-required', false);
          $("#defi_amt").attr('data-parsley-required', false);
					$("#ccaapplicationno").val(json[i].ccaapplicationno);
					$("#ccapplno").val(json[i].ccaapplicationno);
					var applid= json[i].applicationid;
					var doa_split = applid.split('/');
					var applicationid = doa_split[1]+'/'+doa_split[2];
					$("#applicationId").val(applicationid);

					$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
					$("#app_name").val(json[i].applicantname1);
					$("#res_name").val(json[i].respondentname);
					$("#ccadoc_type").val(json[i].documenttype);
					$("#documentname").val(json[i].documentname);
					//isadvocate
					//isAdvocate
					$("#advBarRegNo").val(json[i].advocateregno);
					$("#advName").val(json[i].advocatename);
					$("#caapplicantname").val(json[i].caapplicantname);
					$("#caaddress").val(json[i].caaddress);
					$("#capincode").val(json[i].capincode);
					$("#distcode").val(json[i].cadistrict);
					$("#receiptNo").val(json[i].receiptno);
					$("#applName").val(json[i].name);
					$("#recpAmount").val(json[i].receiptamount);

					$("#noOfPages").val(json[i].pagecount);
					$("#noOfCopies").val(json[i].copycount);
					$("#amount_coll").val(json[i].totamount);
					$("#defi_amt").val(json[i].deficitamount);


					$("#receiptNo").attr('readonly',true);
					$("#receiptNo").css('pointer-events', 'none');
					$("#applicationId").attr('readonly',true);
					$("#applicationId").css('pointer-events', 'none');
					$("#applTypeName").attr('readonly',true);
					$("#applTypeName").css('pointer-events', 'none');
					$('input[name^="isAdvocate"][value="' + json[i].isadvocate + '"').prop('checked', true);
					var isAdvocate = $("input[type=radio][name='isAdvocate']:checked").val();
					if (isAdvocate == 'N') {
						$(".caapplDetails").show();
						$(".advDetails").hide();
						$("#advBarRegNo").val('');
						$("#advName").val('');
						$("#advBarRegNo").attr('data-parsley-required', false);
						$("#caapplicantname").attr('data-parsley-required', true);
						$("#caaddress").attr('data-parsley-required', true);
						$("#capincode").attr('data-parsley-required', true);
						$("#distcode").attr('data-parsley-required', true);
						$("#CATaluk").attr('data-parsley-required', true);
					} else {
						$(".caapplDetails").hide();
						$(".advDetails").show();
						$("#advBarRegNo").attr('data-parsley-required', true);
						$("#caapplicantname").attr('data-parsley-required', false);
						$("#caaddress").attr('data-parsley-required', false);
						$("#capincode").attr('data-parsley-required', false);
						$("#distcode").attr('data-parsley-required', false);
						$("#CATaluk").attr('data-parsley-required', false);
					}



					if(json[i].cadistrict != ""){
					 var distCode = json[i].cadistrict;
					 var talukCode = json[i].cataluk;

					 $.ajax({
							type: 'post',
							url: 'getTaluk',
							dataType:'JSON',
							data: {"_token": $('#token').val(),distCode:distCode},
							success: function (data) {
							//$('#CATaluk').empty();
							$('#CATaluk').find('option:not(:first)').remove();
							for(var i=0;i<data.length;i++){
								if(talukCode==data[i].talukcode)
								  {
									var option = '<option value="'+data[i].talukcode+'" selected>'+data[i].talukname+'</option>';
									 $('#CATaluk').append(option);
								  }
								  else
								  {
									var option = '<option value="'+data[i].talukcode+'">'+data[i].talukname+'</option>';
									$('#CATaluk').append(option);
								   }

								 }
								}
							});
					}

					}
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
					$("#editmodal").modal('hide');
				}
					else{
						swal({
					title: "CC Application Cannot be Updated",
					icon: "error"
					})
					$("#ccApplicationForm").trigger('reset');
					}

				}
			else
			{
				swal({
				title: "Application Does Not Exist",
				icon: "error"
				})
				$("#ccApplicationForm").trigger('reset');
			}
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
		$('#myTableJudgements').find('tbody tr').remove();
		$("#myTableJudgementsdiv").css('display', 'none');
		$("#dateOfOrd,#noOfPages").val('');
	    $("#dateOfOrd,#noOfPages").css('pointer-events', 'visible');
		$("#ccadoc_type").val('');
		$.ajax({
		type: 'POST',
		url: 'getApplicationCA',
		data: {
				_token: $('#token').val(),applicationId: applicationId
			},
			dataType: "JSON",
			success: function (json) {
console.log(json);
if (json[0].hasOwnProperty('disposeddate'))
  {
    console.log('1');
    if(json.length>0)
    {

      for (var i = 0; i < json.length; i++) {
        if(json[i].registerdate==null){
                  $("#applnRegDate").val('');
        }
        else
        {
          var dor = json[i].registerdate;
          var dor_split = dor.split('-');
          var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
          $("#applnRegDate").val(dateOfReg);
        }
        $("#applCatName").val(json[i].applcategory);
        $("#applnSubject").val(json[i].subject);
        $("#app_name").val(json[i].applicantname);
        $("#res_name").val(json[i].respondname);
        var startDate = $("#applnRegDate").val();
        console.log(startDate);
        $('#dateOfCA').datepicker('setStartDate', startDate);
        $('#dateOfOrd').datepicker('setStartDate', startDate);

      }

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
      $("#app_name").val('');
      $("#res_name").val('');
    }



    }
  else
      {
			if(json.length>0)
			{

				for (var i = 0; i < json.length; i++) {
					if(json[i].registerdate1==null){
                    $("#applnRegDate").val('');
					}
					else
					{
						var dor = json[i].registerdate1;
						var dor_split = dor.split('-');
						var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#applnRegDate").val(dateOfReg);
					}
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
					$("#app_name").val(json[i].applicantname1);
					$("#res_name").val(json[i].respondentname);
					var startDate = $("#applnRegDate").val();
					$('#dateOfCA').datepicker('setStartDate', startDate);
					$('#dateOfOrd').datepicker('setStartDate', startDate);

				}

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
				$("#app_name").val('');
				$("#res_name").val('');
			}
    }
		}
	});


});


$('input[type=radio][name=isAdvocate]').on('change', function() {
		var isAdvocate = $("input[type=radio][name='isAdvocate']:checked").val();
		if (isAdvocate == 'N') {
			$(".caapplDetails").show();
			$(".advDetails").hide();
			$("#advBarRegNo").val('');
			$("#advName").val('');
			$("#advBarRegNo").attr('data-parsley-required', false);
			$("#caapplicantname").attr('data-parsley-required', true);
			$("#caaddress").attr('data-parsley-required', true);
			$("#capincode").attr('data-parsley-required', true);
			$("#distcode").attr('data-parsley-required', true);
			$("#CATaluk").attr('data-parsley-required', true);
		} else {
			$(".caapplDetails").hide();
			$(".advDetails").show();
			$("#advBarRegNo").attr('data-parsley-required', true);
			$("#caapplicantname").attr('data-parsley-required', false);
			$("#caaddress").attr('data-parsley-required', false);
			$("#capincode").attr('data-parsley-required', false);
			$("#distcode").attr('data-parsley-required', false);
			$("#CATaluk").attr('data-parsley-required', false);
		}
	})
$("#ccadoc_type").on('change', function() {
	var ccadoc_type = $("#ccadoc_type").val();
	if ((ccadoc_type == 1) || (ccadoc_type == 3) || (ccadoc_type == 4) )
   {
   $('#myorderdate').hide(); 

    $('#myTableOrder').find('tbody tr').remove();
	 $("#myOrderdiv").css('display', 'none');

   $('#myTableJudgements').find('tbody tr').remove();
   $("#myTableJudgementsdiv").css('display', 'none');

   $("#dateOfOrdSel").val('');
   }
 else{
	  $('#myorderdate').show(); 

      $('#myTableOrder').find('tbody tr').remove();
	  $("#myOrderdiv").css('display', 'none');

	  $('#myTableJudgements').find('tbody tr').remove();
   $("#myTableJudgementsdiv").css('display', 'none');
	 }
})

$("#ccadoc_type").on('change', function() {
		var ccadoc_type = $("#ccadoc_type").val();
		console.log(ccadoc_type);
		var modl_appltype_name = $("#applTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#applicationId").val();
		var applicationId =applnewtype+'/'+modl_modl_applno;
		var establishcode = $('#estcode').val();
		var dateOfOrdSel=$("#dateOfOrdSel").val();
		console.log(dateOfOrdSel);
		$('#myTableJudgements').find('tbody tr').remove();
		$("#myTableJudgementsdiv").css('display', 'none');
		$("#dateOfOrd,#noOfPages").val('');
	    $("#dateOfOrd,#noOfPages").css('pointer-events', 'visible');
		if(ccadoc_type == 1){
			if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllJudgementByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId},
				 success: function (json) {
					if(json.length > 0){
					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.judgementdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.judgementdate;
							var dor_split = dor.split('-');
							var dateOfJudgement = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfJudgement+'_'+obj.pagecount+'" class="searchClick">' + dateOfJudgement + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="downloadJudgementByDate?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableJudgements');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfJudgement);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myTableJudgementsdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					  $('#myTableOrder').find('tbody tr').remove();
	                  $("#myOrderdiv").css('display', 'none');

	 
							} }

			});
		}
		
		else if(ccadoc_type == 3){
			 $('#myTableJudgements').find('tbody tr').remove();
			 $("#myTableJudgementsdiv").css('display', 'none');
			 $("#dateOfOrd").attr('data-parsley-required', false);
		     $("#dateOfOrd,#noOfPages").css('pointer-events', 'visible');
		      $('#myTableOrder').find('tbody tr').remove();
	                  $("#myOrderdiv").css('display', 'none');
		}
    else if(ccadoc_type == 4){
			 $('#myTableJudgements').find('tbody tr').remove();
			 $("#myTableJudgementsdiv").css('display', 'none');
			 $("#dateOfOrd").attr('data-parsley-required', false);
       $("#noOfPages").attr('data-parsley-required', false);
       $("#noOfCopies").attr('data-parsley-required', false);
       $("#dateOfCA").attr('data-parsley-required', false);
        $("#dateOfOrd").attr('data-parsley-required', false);
       $("#dateOfOrd,#noOfPages").css('pointer-events', 'visible');
		}
  


	});
$("#dateOfOrdSel").datepicker({});
$("#dateOfOrdSel").on('changeDate', function() {
var ccadoc_type = $("#ccadoc_type").val();
		console.log(ccadoc_type);
		var modl_appltype_name = $("#applTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#applicationId").val();
		var applicationId =applnewtype+'/'+modl_modl_applno;
		var establishcode = $('#estcode').val();
		var dateOfOrdSel=$("#dateOfOrdSel").val();
		console.log(dateOfOrdSel);
  if(ccadoc_type == 2){
			var dateOfOrdSel=$("#dateOfOrdSel").val();
       if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:2,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
					if(json.length > 0){
						  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					  $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
		}
  else if(ccadoc_type==5)
  {    
  	  var dateOfOrdSel=$("#dateOfOrdSel").val();
      if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:5,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
					if(json.length > 0){
				      $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					    $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
  }

   else if(ccadoc_type==6)
  { var dateOfOrdSel=$("#dateOfOrdSel").val();
        if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:6,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
					if(json.length > 0){
						('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					    $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
  }

else if(ccadoc_type==7)
  {  var dateOfOrdSel=$("#dateOfOrdSel").val();
       if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:7,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
					if(json.length > 0){
						$('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					    $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
  }
  else if(ccadoc_type==8)
  {  var dateOfOrdSel=$("#dateOfOrdSel").val();
       if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:8,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
				 	$('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					if(json.length > 0){
					var count = 1;
					$.each(json, function(index, obj) {

						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					
							} }

			});
  }
  else if(ccadoc_type==9)
  {  var dateOfOrdSel=$("#dateOfOrdSel").val();
       if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:9,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
				 	$('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					if(json.length > 0){
					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					    $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
  }
  else if(ccadoc_type==10)
  {     var dateOfOrdSel=$("#dateOfOrdSel").val();
       if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:10,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
				 	$('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					if(json.length > 0){
					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					    $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
  }
  else if(ccadoc_type==11)
   {  var dateOfOrdSel=$("#dateOfOrdSel").val();
       if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:11,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
				 	$('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					if(json.length > 0){

					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					    $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
  }

  else if(ccadoc_type==12)
   {  
   	  var dateOfOrdSel=$("#dateOfOrdSel").val();
       if(applicationId ==''){
				alert('Select application');
				return false;
			}
			$.ajax({
				type: 'POST',
				url: 'getAllOrderByApplNoCCA',
				dataType: "JSON",
			    data: {"_token":$('#token').val(),applicationId:applicationId,ccadocumentcode:12,dateOfOrdSel:dateOfOrdSel},
				 success: function (json) {
				 	$('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					if(json.length > 0){

					var count = 1;
					$.each(json, function(index, obj) {
						var row = $('<tr>');
						if(obj.orderdate===null){
						$("#judgementdate").val('');
						}
						else
						{
							var dor = obj.orderdate;
							var dor_split = dor.split('-');
							var dateOfOrder = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						}
						row.append('<td style="width:20%;" align="center"><a href="#" data-value="'+dateOfOrder+'_'+obj.pagecount+'" class="searchClick">' + dateOfOrder + '</td>');
					//	row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
	    				row.append('<td style="width:15%;" align="center">' + obj.pagecount + '</td>');
						row.append('<td style="width:30%;" align="center"><a href="DownloadOrder_bydate?applicationid='+obj.applicationid+'_'+obj.orderdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
						row.appendTo('#myTableOrder');
						count++;
						$("#noOfPages").val(obj.pagecount);
						$("#dateOfOrd").val(dateOfOrder);
						$("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
						$("#myOrderdiv").css('display', 'inline');
						$("#dateOfOrd").attr('data-parsley-required', true);
						$(".searchClick").click(function(){
						   var value  = $(this).attr('data-value').split("_");
						    $("#dateOfOrd").val(value[0]);
						    $("#noOfPages").val(value[1]);
						});
					 })
					}else{
					  alert(json.message);
					  $('#myTableOrder').find('tbody tr').remove();
					  $("#myOrderdiv").css('display', 'none');
					  $("#noOfPages,#dateOfOrd").css('pointer-events', 'none');
					    $('#myTableJudgements').find('tbody tr').remove();
					  $("#myTableJudgementsdiv").css('display', 'none');

							} }

			});
  }
});
 



	$("#advBarRegNo").bind('input', function() {

		var value = $("#advBarRegNo").val();
		////alert(value);
		getBarregDetails(value);
		//var text = $("#browsers").find('option[value=' + value + ']').text();


	});



	function getBarregDetails(value) {
		var _token = $('input[name="_token"]').val();
		$.ajax({
			type: 'POST',
			url: 'advRegNoApp',
			data: {
				"_token":_token,
				value: value
			},

			dataType: "JSON",
			success: function(json) {
				//console.log(json);
				for (var i = 0; i < json.length; i++) {
					//console.log(json[i].nameTitle);
					$(".advancedSearch4 .selection4").text(json[i].nametitle);
					$(".title_sel4").css('display', 'none');
					$("#advTitle").val(json[i].nametitle);
					$("#advName").val(json[i].advocatename);
					$("#advRegAdrr").val(json[i].advocateaddress);
					/* $("#advRegTaluk").attr('disabled', false);
					$("#advRegDistrict").attr('disabled', false);
					$("#advRegTaluk").empty();
					$("#advRegDistrict").empty();
					$("#advRegTaluk").append('<option value="'+ json[i].talukcode +'"selected>' + json[i].talukname + '</option>');
					$("#advRegDistrict").append('<option value="' + json[i].distcode + '"selected>' + json[i].distname + '<option>');
					$("#advRegPin").val(json[i].pincode); */
				}
			}
		});
	}//adv search


	$('.districtCA').change(function(){
		if($(this).val() != '')
		{
			var select = $(this).attr("id");
			var value = $(this).val();
			var dependent = $(this).data('dependent');
			var _token = $('input[name="_token"]').val();

			$.ajax({
				url:"getCADistrict",
				method:"POST",
				data:{select:select, value:value, _token:_token, dependent:dependent},
				success:function(result)
				{
					$('#'+dependent).html(result);
				}

			})
		}
	});




$("#SearchReceipt").click(function() {
		var receiptNo   = $('#receiptNo').val();
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
						var dor = json[0].receiptdate;
						var dor_split = dor.split('-');
						var receiptDate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#receiptDate").val(receiptDate);
					}else{
		            alert('Receipt No:'+receiptNo+'  is already used in the application '+json[0].applicationid);
				    $('#applName').val('');
					$('#recpAmount').val('');
					$(".advancedSearch1 .selection1").text('');
			    	$("#receiptDate").val('');
					}

			}else{

				    alert('Receipt No: '+receiptNo+' not generated');
					 $('#applName').val('');
					$('#recpAmount').val('');
					$(".advancedSearch1 .selection1").text('');
			    	$("#receiptDate").val('');
					}

			$("#defi_amt").val('');
			$("#noOfPages").val('');
			$("#noOfCopies").val('');
			$("#amount_coll").val('');
		}
		}) }
});


$("#noOfCopies").bind('input', function() {
		////alert('noOfCopies');
		var value = $("#noOfCopies").val();
		var nopages = $("#noOfPages").val();
		var reciptamt = $("#recpAmount").val();
		var ccacharge = $("#ccacharge").val();
		amount1 = value*nopages*ccacharge;
		amount=Math.ceil(amount1);
		$("#amount_coll").val(amount);
		var defiamt = parseInt(amount-reciptamt);
		if(defiamt < 0)
		{
		$("#defi_amt").val(0);
	   }else{
		$("#defi_amt").val(defiamt);
		}
	});






$("#noOfPages").bind('input', function() {
		////alert('noOfCopies');
		var value = $("#noOfCopies").val();
		var nopages = $("#noOfPages").val();
		var reciptamt = $("#recpAmount").val();
		var ccacharge = $("#ccacharge").val();
    amount1 = value*nopages*ccacharge;
    amount=Math.ceil(amount1);
    $("#amount_coll").val(amount);

		var defiamt = parseInt(amount-reciptamt);
		if(defiamt < 0)
		{
		$("#defi_amt").val(0);
	   }else{
		$("#defi_amt").val(defiamt);
		}
	});

$('#ccApplicationForm').submit(function(e)
   {
	   var sbmt_adv =$("#sbmt_adv").val();
	   if(sbmt_adv=='A'){
		   title = "Are you sure to save?";
	   }else if(sbmt_adv=='U'){
		   title = "Are you sure to Update?";
	   }
		e.preventDefault();
		swal({
				title: title,
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
										if(data.status=="sucess")
										{
									     swal({
										title: data.message,
										icon: "success"
										})

										$("#ccApplicationForm").trigger('reset');
										$('#myTableJudgements').find('tbody tr').remove();
										$("#myTableJudgementsdiv").css('display', 'none');
										$("#sbmt_adv").val('A');
										$("#saveADV").val('Save');
										$("#applicationId").attr('readonly',false);
										$("#applicationId").css('pointer-events', 'visible');
										$("#applTypeName").attr('readonly',false);
										$("#applTypeName").css('pointer-events', 'visible');
 										$(".advDetails").show();

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



$('.btnClear').click(function(){
	$("#ccApplicationForm").trigger('reset');
	 $("#sbmt_adv").val('A');
	$("#saveADV").val('Save');
	$("#receiptNo,#applicationId,#applTypeName").attr('readonly',false);
    $("#receiptNo,#applicationId,#applTypeName").css('pointer-events', 'visible');
	$('#CATaluk').find('option:not(:first)').remove();
})



});
