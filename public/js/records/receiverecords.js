$(document).ready(function() {
	
// getRecordsPendingForReceiving();




	$("#myTable1").on("click", ".extraClick", function(e) {
	 
    var requestid   = $(this).attr('data-value');
	var remarks=$("#remarks"+requestid).val();
	var recordreceiveddate=$("#recordreceiveddate"+requestid).val();
	alert('remarks'+remarks);	
	  e.preventDefault();
		swal({
			title: "Are you sure to Save ?",
			icon: "warning",
			showCancelButton: true,
			buttons: true,
			dangerMode: true,
		  })
		  .then((willDelete) => {
		   if (willDelete) {
		  $.ajax({
			type: 'post',
			url: "saveReceivedRecords",
			dataType:"JSON",
			data: {"_token": $('#token').val(),recordreceiveddate:recordreceiveddate,remarks:remarks,requestid:requestid},
			success: function (data) {
			  if(data.status=='success')
				  {
				     $('#requestid').remove(); 
				  
				  swal({
					  title: data.message,
					  icon: "success"
					  })
					  }
		}});
	}})
		})



 function getRecordsPendingForReceiving()
  {
    $.ajax({
        type: 'post',
        url: "getRecordsPendingForReceiving",
        dataType:"JSON",
        data: {"_token": $('#token').val()},
        success: function (json) {
            $('#myTable1').css('display','block');
            $('#myTable1').find('tbody tr').remove();
			var j=1;
           for(var i=0;i<json.length;i++){

                  var row = $('<tr>');
                     //row.append('<input type="hidden" id="finalize" value='+json[i].finalize+'>');
                   // row.append('<td><input type="hidden" id="causelistcode" value='+json[i].causelistcode+'>'+ json[i].causelistdesc +'</td>');
                    row.append('<td>' + j + '</td>');
					row.append('<td>' + json[i].applicationid + '</td>');
                    row.append('<td>' +json[i].usersecname + '</td> </tr>' );
					row.append('<td>' +json[i].recordsentdate + '</td> </tr>' ); 
					row.append('<td><input type="text" name="recordreceiveddate" class="form-control pull-right datepicker" id="recordreceiveddate"  data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required-message="Enter Received On"  data-parsley-required data-parsley-errors-container="#error5">    </td> ' ); 
					row.append('<td><textarea class="form-control" name="" id="remarks'+json[i].requestid+'" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Remarks Accepts Only Characters" data-parsley-required data-parsley-required-message="Enter Remarks"></textarea></td> </tr>' ); 
                    row.append('<td><a data-value='+json[i].requestid+' class="btn btn-md btn-primary extraClick " id="Save"  >Save</a></td>;');
                    row.appendTo('#myTable1');

                    j++;
                   // }

}
}

    });

}
});