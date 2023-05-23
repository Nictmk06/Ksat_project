
$(document).ready(function() {
$("#searchJudgementByApplNo").click(function(e){
	if($("#bench").val()=='')
	{
		alert("Select Bench");
		return false;
	}
	if($("#apptype").val()=='')
	{
		alert("Select Appication Type");
		return false;
	}
	if($("#appnum").val()=='')
	{
		alert("Select Application Number");
		return false;
	}

	var apptype = $("#apptype").val();
    var newtype = apptype.split('-');
	var apptype = newtype[1];
	var appnum = $("#appnum").val().trim();
	var applyear = $("#applyear").val().trim();

	var applicationId =apptype+'/'+appnum+'/'+applyear;
	$.ajax({
	type: 'post',
	url: "getJudgementByApplNo",
	dataType:"JSON",
	data: {"_token":$('#token').val(),applicationId:applicationId},
     success: function (json) {
		$("#myTablediv").css('display', 'block');
		$('#myTable').find('tbody tr').remove();
		if(json.length > 0){
		var count = 1;

		$.each(json, function(index, obj) {
		if(obj.enteredfrom=="Legacy"){
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
			row.append('<td style="width:20%;">' + obj.applicationo+ '</td>');
			row.append('<td style="width:20%;">' + obj.applcatname + '</td>');
			row.append('<td style="width:15%;">' + obj.applicantname + '</td>');
			row.append('<td style="width:15%;">' + obj.respondname + '</td>');
			row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');


			row.append('<td style="width:30%;"><a href="DownloadJudgement_delete?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
			row.append('<td class="col-md-2"><a href="#" class="deletejudgement btn btn-sm btn-danger"  type="button" data-value="' + obj.judgementdate+'|'+obj.applicationid + '">X</a></td>');

			row.appendTo('#myTable');
			count++;
		}else{
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
			row.append('<td style="width:20%;">' + obj.applicationo+ '</td>');
			row.append('<td style="width:20%;">' + obj.applcatname +'</td>');
			row.append('<td style="width:15%;">' + obj.applicantname1 + '</td>');
			row.append('<td style="width:15%;">' + obj.respondentname + '</td>');
			row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');

			row.append('<td style="width:30%;"><a href="DownloadJudgement_delete?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
			row.append('<td class="col-md-2"><a href="#" class="deletejudgement btn btn-sm btn-danger"  type="button" data-value="' + obj.judgementdate+'|'+obj.applicationid + '">X</a></td>');

		      row.appendTo('#myTable');
		   count++;
		}
	 })
	 $(".deletejudgement").click(function(){
			var reliefsrno = $(this).attr('data-value');
					 console.log(reliefsrno);
						 var split = reliefsrno.split('|');

						 var judgementdate = split[0];
						 var applicationid =split[1];
						 console.log(judgementdate);
			 $.ajax({
		 type: 'POST',
		 url: 'deletejudgements',
		 dataType: 'JSON',
		 data:{"_token": $('#token').val(),judgementdate:judgementdate,applicationid:applicationid},
		 cache: false,
		 success: function(response) {
			 if(response.status=="success")
			 {
				 swal({
					title:response.message,
					icon: "success",
					});
				 getJudgementData(applicationid);


				 }
			 else
			 {
				swal({
				 title:response.message,
				 icon: "error",
				 });
			 }
		 }
		 });
		 });
    }else{
	  alert(json.message);
	  $('#myTable').find('tbody tr').remove();
	  $("#myTablediv").css('display', 'none');
			} }
	});
  });

	function getJudgementData(applicationid)
     {
			 console.log('argument ' +applicationid);
       $.ajax({
         type: 'post',
         url: "getJudgementByApplNo",
         dataType:"JSON",
         data: {"_token": $('#token').val(),applicationId:applicationid},
         success: function (json) {
					 if(json.length > 0){
			 		var count = 1;
					$('#myTable tbody').empty();
			 		$.each(json, function(index, obj) {
			 		if(obj.enteredfrom=="Legacy"){
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
			 			row.append('<td style="width:20%;">' + obj.applicationo+ '</td>');
			 			row.append('<td style="width:20%;">' + obj.applcatname + '</td>');
			 			row.append('<td style="width:15%;">' + obj.applicantname + '</td>');
			 			row.append('<td style="width:15%;">' + obj.respondname + '</td>');
			 			row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');


			 			row.append('<td style="width:30%;"><a href="downloadJudgement?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
			 			row.append('<td class="col-md-2"><a href="#" class="deletejudgement btn btn-sm btn-danger"  type="button" data-value="' + obj.judgementdate+'|'+obj.applicationid + '">X</a></td>');

			 			row.appendTo('#myTable');
			 			count++;
			 		}else{
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
			 			row.append('<td style="width:20%;">' + obj.applicationo+ '</td>');
			 			row.append('<td style="width:20%;">' + obj.applcatname +'</td>');
			 			row.append('<td style="width:15%;">' + obj.applicantname1 + '</td>');
			 			row.append('<td style="width:15%;">' + obj.respondentname + '</td>');
			 			row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');

			 			row.append('<td style="width:30%;"><a href="downloadJudgement?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
			 			row.append('<td class="col-md-2"><a href="#" class="deletejudgement btn btn-sm btn-danger"  type="button" data-value="' + obj.judgementdate+'|'+obj.applicationid + '">X</a></td>');

			 		      row.appendTo('#myTable');
			 		   count++;
			 		}
				})
				$(".deletejudgement").click(function(){
					 var reliefsrno = $(this).attr('data-value');
								console.log(reliefsrno);
									var split = reliefsrno.split('|');

									var judgementdate = split[0];
									var applicationid =split[1];
									console.log(judgementdate);
						$.ajax({
					type: 'POST',
					url: 'deletejudgements',
					dataType: 'JSON',
					data:{"_token": $('#token').val(),judgementdate:judgementdate,applicationid:applicationid},
					cache: false,
					success: function(response) {
						if(response.status=="success")
						{
							swal({
							 title:response.message,
							 icon: "success",
							 });
							getJudgementData(applicationid);


							}
						else
						{
						 swal({
							title:response.message,
							icon: "error",
							});
						}
					}
					});
					});

			     }
           else
           {
              $('#myTable').show();
              $('#myTable tbody tr').remove();
              $("#myTable tbody").append('<tr><td colspan="4" style=" text-align: center;">No records to display</td></tr>');
            }

         }
       });
     }
  $("#searchJudgementByParameters").click(function(e){
      var partytype = $('#partytype').val();
      var partyname = $('#partyname').val();
	  var fromdate =$("#fromdate").val();
	  var todate = $("#todate").val();
	  var judge = $("#judge").val();
	  var Subject = $("#Subject").val();
	   if(partytype == '' && partyname!="")
	    {
		   alert("Select partytype");
		   return false;
	   }
	 //  alert(partytype);
	   if(partytype != '' && partyname=="")
	   {
		   alert("Select partyname");
		   return false;
	   }
	   if(partyname!="")
	   {
		var regex = /^[A-Za-z]+$/;
		var res = regex.test(partyname);
		//alert(res);
		if(!res)
		{
			alert("partyname can contains only characters");
			return false;
		}
		var Max_Length = 20;
		var length = partyname.length;
		if (length > Max_Length) {
	    alert("partyname can have maximum 20 characters");
		return false;
		}
      }

	   if(fromdate != '' && todate=="")
	   {
		   alert("Select todate");
		   return false;
	   }

		if(fromdate == '' && todate != "")
	   {
		   alert("Select fromdate");
		   return false;
	   }

		var arrStartDate = $("#fromdate").val().split("-");
		var date1 = new Date(arrStartDate[2], arrStartDate[1], arrStartDate[0]);
		var arrEndDate = $("#todate").val().split("-");
		var date2 = new Date(arrEndDate[2], arrEndDate[1], arrEndDate[0]);

		//alert(date1);
		if(date1 > date2)
		  {
			alert("To Date must be greater than From Date");
			return false;
		  }

		if(partytype != '' || partyname!="" || fromdate != '' || todate != "" || Subject != '' || judge != "")
		{
		   // return true;
	   }else{
		   alert("Select some criteria to search");
		   return false;
	   }



	$.ajax({
	type: 'post',
	url: "searchJudgementByParameters",
	dataType:"JSON",
	data: {"_token":$('#token').val(),partytype:partytype,partyname:partyname,fromdate:fromdate,todate:todate,judge:judge,Subject:Subject},
     success: function (json) {
		$("#myTablediv1").css('display', 'block');
		$('#myTable1').find('tbody tr').remove();
		if(json.length > 0){
		var count = 1;
		$.each(json, function(index, obj) {
	/*	if(obj.enteredfrom=="Legacy"){
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
			row.append('<td style="width:20%;">' + obj.applicationid+ '</td>');
			row.append('<td style="width:20%;">' + obj.applcatname + '-'+ obj.subject + '</td>');
			row.append('<td style="width:15%;">' + obj.applicantname + '</td>');
			row.append('<td style="width:15%;">' + obj.respondname + '</td>');
			row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');
			row.append('<td style="width:30%;"><a href="downloadJudgement?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
	        row.appendTo('#myTable');
			count++;
		}else{*/
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
			row.append('<td style="width:20%;">' + obj.applicationid+ '</td>');
			row.append('<td style="width:20%;">' + obj.applcatname +  '</td>');
			row.append('<td style="width:15%;">' + obj.applicantname1 + '</td>');
			row.append('<td style="width:15%;">' + obj.respondentname + '</td>');
			row.append('<td style="width:20%;">' + dateOfJudgement + '</td>');

			row.append('<td style="width:30%;"><a href="downloadJudgement?applicationid='+obj.applicationid+'_'+obj.judgementdate+'" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"></i></a></td>');
	   row.appendTo('#myTable1');
		   count++;
		//}
	 })
    }else{
	  alert(json.message);
	  $('#myTable1').find('tbody tr').remove();
	  $("#myTablediv1").css('display', 'none');
			} }
	});
  });


  $('.btnClear').click(function(){
 	var form = $(this).closest("form").attr('id');
 	$("#"+form).trigger('reset');
	$('#myTable').find('tbody tr').remove();
	$("#myTablediv").css('display', 'none');
	$('#myTable1').find('tbody tr').remove();
	$("#myTablediv1").css('display', 'none');


 })

  $(".causeclick").click(function(){
    var causelistcode = $(this).attr('data-value');

	alert(causelistcode);//console.log(cause);
      $.post("downloadFile1", { causelist: causelistcode } );
})
})


 $('#JudgementByParametersForm').submit(function(e)
   {
	  var partytype = $('#partytype').val();
      var partyname = $('#partyname').val();
	  var fromdate =$("#fromdate").val();
	  var todate = $("#todate").val();
	  var judge = $("#judge").val();
	  var Subject = $("#Subject").val();
	   if(partytype == '' && partyname!="")
	   {
		   alert("Select partytype");
		   return false;
	   }

	   if(partytype != '' && partyname=="")
	   {
		   alert("Select partyname");
		   return false;
	   }

	   if(fromdate != '' && todate=="")
	   {
		   alert("Select todate");
		   return false;
	   }

		if(fromdate == '' && todate != "")
	   {
		   alert("Select fromdate");
		   return false;
	   }

		var arrStartDate = $("#fromdate").val().split("-");
		var date1 = new Date(arrStartDate[2], arrStartDate[1], arrStartDate[0]);
		var arrEndDate = $("#todate").val().split("-");
		var date2 = new Date(arrEndDate[2], arrEndDate[1], arrEndDate[0]);

		//alert(date1);
		if(date1 > date2)
		  {
			alert("To Date must be greater than From Date");
			return false;
		  }

		if(partytype != '' || partyname!="" || fromdate != '' || todate != "" || Subject != '' || judge != "")
		{
		    return true;
	   }else{
		   alert("Select some criteria to search");
		   return false;
	   }

    });



function minmax1(value, min, max)
	{

	//if(parseInt(value) < min || isNaN(parseInt(value)))
	//		return min;
	//	else
			if(parseInt(value) > max)
			alert("please type valid application number");
		else return value;
	}

function minmax(value, min, max)
	{
		 var d = new Date();
         var n = d.getFullYear();
		// alert(n);

	//if(parseInt(value) < min || isNaN(parseInt(value)))
		//	return min;
	//	else
			if(parseInt(value) > n)
			return n;
		else return value;
	}
