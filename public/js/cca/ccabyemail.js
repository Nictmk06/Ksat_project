$(document).ready(function() {


$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
     //================================dsc code=========================
function myFunction() {
			var x = document.getElementById("tsaurls").value;
			if (x != 0) {
				document.getElementById("tsaURL").value = x;
			} else {
				document.getElementById("tsaURL").value = "";
			}
		}
		$(document)
				.ready(
						function() {

							$('#verifyPdfBtn').hide();

							var initConfig = {
								"preSignCallback" : function() {
									// do something 
									// based on the return sign will be invoked
									return true;
								},
								"postSignCallback" : function(alias, sign, key) {
									$('#signedPdfData').val(sign);
									$('#lblEncryptedKey').val(key);
									// Implement signed pdf upload and pdf Download here
									var requestData = {
										action : "DECRYPT",
										en_sig : sign,
										ek : key
									};
									$
											.ajax(
													{
														url : dscapibaseurl
																+ "/pdfsignature",
														type : "post",
														dataType : "json",
														contentType : 'application/json',
														data : JSON
																.stringify(requestData),
														async : false
													})
											.done(
													function(data) {
														if (data.status_cd == 1) {
															//get data.data -> decode base64 -> get json->check status == SUCCESS
															//get data.data.sig -> add pdf header and append to link
															var jsonData = JSON
																	.parse(atob(data.data));
															if (jsonData.status === "SUCCESS") {
																$(
																		'#verifyPdfBtn')
																		.show();
																//Set Class to download link
																$(
																		'#downloadDiv')
																		.addClass(
																				'btn btn-info');
																//get pdf data
																var pdfData = jsonData.sig;
																var dlnk = document
																		.getElementById('downloadDiv');
																dlnk.href = 'data:application/pdf;base64,'
																		+ pdfData;
																	var base64='data:application/pdf;base64,' + pdfData;
																	                   //       var bin = base64_decode(b64);
																						//===========show the dsc facesheet in the browser window========
																						let pdfWindow = window.open("")
																							pdfWindow.document.write(
																							    "<iframe width='100%' height='100%' src='data:application/pdf;base64, " +
																							    encodeURI(pdfData) + "'></iframe>"
																							);
																	//alert("after showig pdf in window");
																	var id=$('#selectedid').val();
																	
																	alert(id);
																	
																	//alert(btid);
																	var selbtnid=$('#selectedbtn').val();
																	alert(selbtnid);
																	facesheetsave(pdfData,id,selbtnid);	
                                                                   $('#'+selbtnid).hide();
                                                                   $('#mail'+selbtnid).removeAttr('disabled'); 
                                                                   
                                                             // $('#dscelement').empty();
 														//$('#dwnsheet'+selbtnid).append('hi');

																$(
																		"#downloadDiv")
																		.text(
																			

	"Download Signed PDF File1");

															}

														} else {
															if (data.error.error_cd == 1002) {
																alert(data.error.message);
																return false;
															} else {
																alert("Decryption Failed for Signed PDF File");
																return false;
															}

														}
													}).fail(
													function(jqXHR, textStatus,
															errorThrown) {
														alert(textStatus);
													});
								},
								signType : 'pdf',
								mode : 'stamping'
							//"certificateSno" : 13705892,
							};
							dscSigner.configure(initConfig);

							$('#signPdf').click(function() {
								var data = $("#pdfData").val();
								var reason = $("#signingReason").val();
								var location = $("#signingLocation").val();
								if (data != null || data != '') {
									dscSigner.sign(data);
								}
							});

							$('#verifyPdfBtn')
									.click(
											function() {
												var signedPdfData = $(
														'#signedPdfData').val();
												var key = $('#lblEncryptedKey')
														.val();

												// Implement Verify here
												var requestData = {
													action : "VERIFY",
													en_sig : signedPdfData,
													ek : key
												};
												$
														.ajax(
																{
																	url : dscapibaseurl
																			+ "/pdfsignature",
																	type : "post",
																	dataType : "json",
																	contentType : 'application/json',
																	data : JSON
																			.stringify(requestData),
																	async : false
																})
														.done(
																function(data) {
																	if (data.status_cd == 1) {
																		//get pdfSignatureVerificationResponse
																		$(
																				'#verificationResponse')
																				.val(
																						atob(data.data));
																	} else {
																		alert("Verification Failed");
																	}

																})
														.fail(
																function(
																		jqXHR,
																		textStatus,
																		errorThrown) {
																	alert(textStatus);
																});
											});

							function readPdfURL(input) {
								if (input.files && input.files[0]) {
									var reader = new FileReader();

									reader.onload = function(e) {
										var data = e.target.result;
										var base64 = data
												.replace(/^[^,]*,/, '');
										$("#pdfData").val(base64);
									}

									reader.readAsDataURL(input.files[0]);
								}
							}

							function readImageURL(input) {
								if (input.files && input.files[0]) {
									var reader = new FileReader();

									reader.onload = function(e) {
										var data = e.target.result;
										var base64 = data
												.replace(/^[^,]*,/, '');
										$("#imageData").val(base64);
									}

									reader.readAsDataURL(input.files[0]);
								}
							}

							$("#pdfFile").change(function() {
								readPdfURL(this);
							});

							$("#stampingFile").change(function() {
								readImageURL(this);
							});

						});
     //===================================dsccode end====================


function facesheetsave(b64,id,selbtnid){
var formData = new FormData();
alert(b64);

formData.append('base64', b64);
formData.append('id', id);
//var hide="'#facesheetid"+id+"'";

 
$.ajax({

type:'POST',
url: "facesheetsave",
data: formData,
dataType: "JSON",
cache:false,
contentType: false,
processData: false,
success: function (response) {
alert(response[0].msg);
alert(response[0].fac_file);

	var file = 'file:///'+response[0].fac_file;
$('#dwnsheet'+selbtnid).append('<button  class="btn btn-primary"><a href="' + file + '" target="_blank" style="color:white;">facesheet</a></button><br>'); 
   $("#downloadDiv").hide();								 
$('#verifyPdfBtn').hide();

},

error: function(response){
console.log(response);
}

});

}
//=============================== send email=============================================


//------------- send mail ===================================
});