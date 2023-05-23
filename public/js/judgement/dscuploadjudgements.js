$(document).ready(function() {
	

//=========================================digital sign by dsc toke n=======================================

function myFunction() {
				var x = document.getElementById("tsaurls").value;
				if (x != 0) {
					document.getElementById("tsaURL").value = x;
				} else {
					document.getElementById("tsaURL").value = "";
				}
			}
			

			$('#btnDecryptVerify').hide();
			$('#btnDecryptVerifyWithCrt').hide();

			var initConfig = {
				"preSignCallback" : function() {
					// do something 
					// based on the return sign will be invoked
					return true;
				},
				"postSignCallback" : function(alias, sign) {
					$('#signedPdfData').val(sign);
					
					//Set Class to download link
					$('#downloadDiv').addClass('btn btn-info');
					//get pdf data
					var pdfData = sign;
					var dlnk = document.getElementById('downloadDiv');
					dlnk.href = 'data:application/pdf;base64,' + pdfData;
					$("#downloadDiv").text("Download Signed PDF File");

				},
				signType : 'pdf',
				mode : 'nostampingnoencryptionv2',
				certificateData : $('#cert').val()
			//"certificateSno" : 13705892,
			};
			dscSigner.configure(initConfig);

			$('#cert').bind('input propertychange', function() {
				var initConfig = {
					"preSignCallback" : function() {
						// do something before signing
						alert("Pre-sign event fired");
						return true;
					},
					"postSignCallback" : function(alias, sign) {
						//do something after signing
						$('#signedPdfData').val(sign);
						
						//Set Class to download link
						$('#downloadDiv').addClass('btn btn-info');
						//get pdf data
						var pdfData = sign;
						var dlnk = document.getElementById('downloadDiv');
						dlnk.href = 'data:application/pdf;base64,' + pdfData;
						$("#downloadDiv").text("Download Signed PDF File");

					},
					signType : 'pdf',
					mode : 'nostampingnoencryptionv2',
					certificateData : $('#cert').val()
				//Set the cerificate serial number to skip certificate selection
				//"certificateSno" : 13705892,
				};
				dscSigner.configure(initConfig);
			});

			$('#signPdf').click(function() {
				
				var data = $("#pdfData").val();

				if (data != null || data != '') {
					dscSigner.sign(data);
				}
			});

			function readURL(input) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();

					reader.onload = function(e) {
						var data = e.target.result;
						var base64 = data.replace(/^[^,]*,/, '');
						$("#pdfData").val(base64);
					}

					reader.readAsDataURL(input.files[0]);
				}
			}

			$("#pdfFile").change(function() {
				readURL(this);
			});

//=========================================digital sign by dsc toke n=======================================

$("#applnJudgementDate").css('pointer-events', 'none');

$('#pdfFile').change(function (event) {
	
    event.preventDefault();
//$('#dscelement').empty();
 var formData = new FormData();
formData.append('file', $('#pdfFile')[0].files[0]);


/*//water mark code
*/
//=============== water mark code ==================================

/*$.ajax({
type:'POST',
url: "getdscvalidate",
data: formData,
dataType: "JSON",
cache:false,
contentType: false,
processData: false,
success: function (response) {
	console.log(response);
	console.log(response[0].msg);
	console.log(response.msg);
 if(response[0].msg !='0'){
 	//alert('File is dsc validated');
 	 $('#element').empty();
 	$('#judgement').val('');
 	     swal({
                  title: "File is Digitally signed, Please upload normal pdf file",
                  icon: "warning",
                  buttons:[
                      'Cancel',
                      'OK'
                      ],
                      });
 }else{
 	$('#element').empty();
    var file = URL.createObjectURL(event.target.files[0]);
    $('#element').append('<a href="' + file + '" target="_blank">' + event.target.files[0].name + '</a><br>');
 }
},

error: function(response){
console.log(response);
}
});
*/
});
//=============== water mark code ==================================


//===================== uploading dsc judgment ==============================
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$('#dscjudgement').change(function (event) { //==============dsc judgment button ====================
event.preventDefault();
$('#dscelement').empty();
 var formData = new FormData();
formData.append('file', $('#dscjudgement')[0].files[0]);
//formData.append('judgementfile', '2');
formData.append('applicationid', $('#applicationId').val());
formData.append('applnJudgementDate', $('#applnJudgementDate').val());
$.ajax({
type:'POST',
url: "getdscvalidate",
data: formData,
dataType: "JSON",
cache:false,
contentType: false,
processData: false,
success: function (response) {
	console.log(response);
	console.log(response[0].msg);
	console.log(response.msg);
 if(response[0].msg !='0'){
 	//alert('File is dsc validated');
 	 $('#dscelement').empty();
 	var file = URL.createObjectURL(event.target.files[0]);
    $('#dscelement').append('<a href="' + file + '" target="_blank">' + event.target.files[0].name + '</a><br>');

 	     swal({
                  title: "File is  digitally signed",
                  icon: "success",
                  buttons:[
                      'Cancel',
                      'OK'
                      ],
                      });
 }
 else{
 //alert('File is not dsc validated');}
$('#dscjudgement').val('');

            swal({
                  title: "File is not digitally signed",
                  icon: "warning",
                  buttons:[
                      'Cancel',
                      'OK'
                      ],
                      });
}},

error: function(response){
console.log(response);
}
});
});//========================dsc judgment upload button===============================


//=======================single button normal pdf file upload ====================//

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$('#pdfFile').change(function (event) { //==============dsc judgment button ====================
event.preventDefault();
$('#dscelement').empty();
 var formData = new FormData();
formData.append('file', $('#pdfFile')[0].files[0]);
//formData.append('judgementfile', '2');
formData.append('applicationid', $('#applicationId').val());
formData.append('applnJudgementDate', $('#applnJudgementDate').val());
$.ajax({

type:'POST',
url: "getWatermark",
data: formData,
dataType: "JSON",
cache:false,
contentType: false,
processData: false,
success: function (response) {
	
	
	console.log(response);
	console.log(response[0].msg);
	console.log(response.msg);
//	alert("watermark");

$('#dscelement').empty();
 	var file2 ='pdf/'+response[0].msg; 
 	//var jud_dscsplit = response[0].dscjudgement.split('/');

 	//if(response[0].dscjudgement !=null && response[0].dscjudgement !=''){
 		//$('#dscjudgement').parsley().removeError('dscjudgement');
    	$('#dscelement').append('<a href="'+file2+'" target="_blank">' +'watermark File'+response[0].msg + '</a><br>');
	//}

},
error: function(response){
console.log(response);
}
});


$.ajax({
type:'POST',
url: "getdscvalidate",
data: formData,
dataType: "JSON",
cache:false,
contentType: false,
processData: false,
success: function (response) {
	console.log(response);
	console.log(response[0].msg);
	console.log(response.msg);
 if(response[0].msg !='0'){
 	//alert('File is dsc validated');
 	 //$('#dscelement').empty();
 	//var file = URL.createObjectURL(event.target.files[0]);
   // $('#dscelement').append('<a href="' + file + '" target="_blank">' + event.target.files[0].name + '</a><br>');
        $('#pdfFile').val('');// to remove file link for if dsc signed ====//
 	     swal({
                  title: "File is  digitally signed",
                  icon: "success",
                  buttons:[
                      'Cancel',
                      'OK'
                      ],
                      });
 }
 else{
//$('#pdfFile').val('');

 //$('#dscelement').empty();
 	//var file = URL.createObjectURL(event.target.files[0]);
   // $('#dscelement').append('<a href="' + file + '" target="_blank">' + event.target.files[0].name + '</a><br>');

            swal({
                  title: "File is not digitally signed",
                  icon: "warning",
                  buttons:[
                      'Cancel',
                      'OK'
                      ],
                      });
}},

error: function(response){
console.log(response);
}
});
});

//===================== single button normal pdf file upload ========================//

// ==============================to get the response date picker from disposed date to current date==================
$('#applicationId').change(function (e) {
	e.preventDefault();
	//alert($('#applTypeName').val());
	var formData = new FormData();
    formData.append('applTypeName', $('#applTypeName').val());
	formData.append('applicationid', $('#applicationId').val());
   $.ajax({
	            type:'POST',
                url: "getdisposedate",
                data: formData,
				dataType: "JSON",
				cache:false,
				contentType: false,
				processData: false,
				success: function (response) {
					console.log(response[0].msg);
					$('.date').datepicker({
						//useCurrent :false,
						format: "dd-mm-yyyy",
					       startDate:response[0].msg,
					       autoclose: true,
					       // orientation: "bottom",
					        endDate: "today"

					});

				}	,
error: function(response){
console.log(response);
}
});
});

// ==============================to get the response date picker from disposed date to current date==================


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
if($("#applnJudgementDate").val()!=''){
$('#applnJudgementDate').parsley().removeError('applnJudgementDate');
}

		var modl_appltype_name = $("#applTypeName").val();
		var newtype = modl_appltype_name.split('-');
		var applnewtype = newtype[1];
		var modl_modl_applno = $("#applicationId").val();
		var applicationId =applnewtype+'/'+modl_modl_applno;
		
		var flag='application';
         /// ======================= judgement release date /file /dsc file ================================// 
       var formData = new FormData();
    formData.append('applTypeName', $('#applTypeName').val());
	formData.append('applicationid',applicationId);

         $.ajax({  
	            type:'POST',
                url: "getJudgementfiledetails",
                data: formData,
				dataType: "JSON",
				cache:false,
				contentType: false,
				processData: false,
				success: function (response) {

					if(response[0].acceptreject=='A'){
                            swal({
				title: "Application is already verified",

				icon: "error"
				})
                 $('#submitjudgement').attr('disabled','disabled');
					}else{
						 $('#submitjudgement').removeAttr('disabled');
					}

					console.log(response);
					var dor =response[0].releasedate;
						var dor_split = dor.split('-');
						var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
						$("#releaseDate").val(dateOfReg);	

						$('#element').empty();

						var jud_split = response[0].judgement_path.split('/');


 	var file1 =response[0].path+'/'+jud_split[0]+'/W_'+jud_split[1]; 


if(response[0].judgement_path!=null && response[0].judgement_path!=''){
	$('#judgement').parsley().removeError('judgement');
   $('#element').append('<a href="'+file1+'" target="_blank">' +jud_split[1] + '</a><br>');
}
   $('#dscelement').empty();
 	var file2 =response[0].path1+'/' +response[0].dscjudgement; 
 	var jud_dscsplit = response[0].dscjudgement.split('/');

 	if(response[0].dscjudgement !=null && response[0].dscjudgement !=''){
 		$('#dscjudgement').parsley().removeError('dscjudgement');
    	$('#dscelement').append('<a href="'+file2+'" target="_blank">' +jud_dscsplit[1] + '</a><br>');
	}



  //$('#element').append(window.open(file,null) +response[0].judgement_path );
				//$("#releaseDate").val(response[0].releasedate);	

				}	,
error: function(response){
console.log(response);
}
});

       
// ======================= judgement release date /file /dsc file ================================//


	 	$.ajax({
		type: 'POST',
		url: 'getDisposedApplicationDetails',
		data: {"_token": $('#token').val(),applicationid:applicationId},
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
					{ 
						var doa = json[i].applicationdate;
						var doa_split = doa.split('-');
						var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#dateOfAppl").val(dateOfApp);
					}
					if(json[i].disposeddate==null)
					{
					
						$("#applnJudgementDate").val('');
					}
					else
					{ 
						var doa = json[i].disposeddate;
						var doa_split = doa.split('-');
						var dodisposed = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
						$("#applnJudgementDate").val(dodisposed);
					}
					
					
					
					$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
					$("#applCatName").val(json[i].applcategory);
					$("#applnSubject").val(json[i].subject);
							

					
				}
				
			}
			else
			{
				swal({
				title: "Application is not disposed",

				icon: "error"
				})
				$("#applnRegDate").val('');
				$("#dateOfAppl").val('');
				$("#applTypeName").val('');
				$("#applCatName").val('');
				$("#applnSubject").val('');
				$("#applicationId").val('');
			}
		}
	});


});



$('.btnClear').click(function(){
	$("#uploadjudgements").trigger('reset');
	$('#element').empty();
	$('#dscelement').empty();
})

});


 $('#uploadJudgementsForm').submit(function(e) 
   {
     e.preventDefault();
     
     if($('#applTypeName').val()==''){

		return false;

     }else if($('#applicationId').val()==''){

			return false;
     }else if($('#judgement').val()==''){

			return false;
     }else if($('#dscjudgement').val()==''){
			return false;

     }


     else{


        swal({
        title: "Are you sure to save?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#uploadJudgementsForm').submit();
                } 
        });
        }
       
    });

// $("#savejudgements").click(function(e) {
// 		e.preventDefault();
// 		swal({
// 				title: "Are you sure to upload judgement copy ?",
// 				icon: "warning",
// 				showCancelButton: true,
// 				buttons: true,
// 				dangerMode: true,
// 			})
// 			.then((willDelete) => {
// 				if (willDelete) {
// 				//	var modl_appltype_name = $("#applTypeName").val();
// 				//	var newtype = modl_appltype_name.split('-');
// 				//	var applnewtype = newtype[1];
// 				//	var modl_modl_applno = $("#applicationId").val();
// 				//	var applicationId =applnewtype+'/'+modl_modl_applno;
// 					var form = $(this).closest("form").attr('id');
// 					var formaction = $(this).closest("form").attr('action');
// 		       	$("#" + form).parsley().validate();
// 					if ($("#" + form).parsley().isValid()) {
// 						$.ajax({ 
// 					    	url:"{{ route('savejudgements') }}",
// 							   method:"POST",
// 							   data:new FormData(this),
// 							   dataType:'JSON',
// 							   contentType: false,
// 							   cache: false,
// 							   processData: false,
// 						       success: function(data) {
// 										if (data.status == "sucess") {
// 											swal({
// 												title: data.message,
// 												icon: "success",
// 											});
// 										  //  $("#sbmt_additional").val('A');
// 									//		$("#saveAdditional").val('Save');
// 											} else if (data.status == "fail") {
// 											swal({
// 												title: data.message,

// 												icon: "error",
// 											});
// 										}
// 									}
// 								});
// 					}
// 				} else {
// 					return false;
// 				}
// 			});
// 	})


