$(document).ready(function() {
	
 $("#receiptDate,#recpAmount").css('pointer-events', 'none');
 $("#ccdeliverydate").datepicker({dateFormat:"dd/mm/yy"})
      .datepicker("setDate",new Date()).datepicker('setStartDate', new Date())
	  .datepicker('setEndDate', null);

  

$("#ccapplSearch").click(function(){
	if($("#ccapplno").val()=='')
	   {
		$('#ccapplno').parsley().removeError('ccapplno');
		$('#ccapplno').parsley().addError('ccapplno', {message: "Enter CC Application No"});
		return false;
	   }
		else
		{
			$('#ccapplno').parsley().removeError('ccapplno');
		}
	var ccapplId = $("#ccapplno").val();
	$.ajax({
				type: 'post',
				dataType:'JSON',
				url: 'getCCApplicationDetails',
				data: {"_token": $('#token').val(),ccaapplicationno:ccapplId},
				success: function (json) {
				if(json.length>0)
		    	{
					var deficitreceiptno = json[0].deficitreceiptno;
					var deficitamount = json[0].deficitamount;
					if(deficitamount == 0)
					{
						swal({
						title: "Deficit fee is not applicable for this CC Application No",
						icon: "error"
						
						})
					}
					else if(deficitreceiptno != null)
					{
						swal({
						title: "Deficit Payment already exists for this CC Application No",
						icon: "error"
						
						})
					}
					else{
					for (var i = 0; i < json.length; i++) {
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
					
					if(json[i].copyreadyon==null)
					{
						$("#ccdeliverydate").val('');
					}
					else
					{ 
						var doa = json[i].copyreadyon;
						var doa_split = doa.split('-');
						var dateOfCA = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#ccdeliverydate").val(dateOfCA);
					}
					var applicationid= json[i].applicationid;
					$("#applicationId").val(applicationid);
					$("#defi_amt").val(json[i].deficitamount);					
					}}
				}
				else
				{
					swal({
					title: "CC Application Does Not Exist",

				icon: "error"
				})
				$("#dateOfCA").val('');
				$("#defi_amt").val('');
				$("#applicationId").val('');
			}
		 }
 	 });
	})


	

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
						var dor = json[0].receiptdate;
						var dor_split = dor.split('-');
						var receiptDate = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#receiptDate").val(receiptDate);	
					}else{
		            alert('Receipt No:'+receiptNo+'  is already used in the application '+json[0].applicationid);
				    $('#applName').val('');
					$('#recpAmount').val('');
					$("#receiptDate").val('');	
					}
					
			}else{
				
				    alert('Receipt No: '+receiptNo+' not generated');
					 $('#applName').val('');
					$('#recpAmount').val('');
					$("#receiptDate").val('');	
					}
		}
		}) }
});


	


$('#deficitPaymentForm').submit(function(e) 
   {
	   
	   var deficitamount=parseInt($("#defi_amt").val());
	   var recpAmount = parseInt($('#recpAmount').val());
	   if(recpAmount < deficitamount )
	   {
		   alert('Receipt Amount should be >= Deficit Amount');
		   return false;
	   }
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
										
										$("#deficitPaymentForm").trigger('reset');
										$("#sbmt_adv").val('A');
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
	$("#deficitPaymentForm").trigger('reset');
	 $("#sbmt_adv").val('A');
	$("#saveADV").val('Save');
	$("#receiptNo").attr('readonly',false);
    $("#receiptDate").css('pointer-events', 'visible');

})



});
