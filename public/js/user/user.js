$(document).ready(function() {

      
 //	$('#userrole').multiselect({		
	//	nonSelectedText: 'Select Role'				
//	}); 


	$(".extraClick").click(function(){
			//console.log('hiii ');
			var userid   = $(this).attr('data-value');
			//console.log('hiii '+userid);
			$.ajax({
				type: 'post',
				url: "getUserDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),userid:userid},
				success: function (json) {
				 
					//console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
			    	console.log('inside success '+json[0][0].username);
					$("#userid").val(json[0][0].userid);
					$("#userid").attr('readonly', true); 
					$("#userName").val(json[0][0].username);
					$("#designation").val(json[0][0].userdesigcode);
					$("#userSection").val(json[0][0].sectioncode);
					$("#courtHallNo").val(json[0][0].courthallno);
					$("#userLevel").val(json[0][0].userlevel);
                    $("#mobileno").val(json[0][0].mobileno);
					$("#email").val(json[0][0].useremail);
					$("#establishment").val(json[0][0].establishcode);
					$("#enableuser").val(json[0][0].enableuser);
					
					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');
					
					console.log('inside success '+json[1]);				
					 $("#userrole option:selected").removeAttr("selected");

					var role = json[1].split(",");
					console.log('role= '+role[0]);
					var i;
					for (i = 0; i < role.length; i++) {
					  $('#userrole').find('option').each( function() {
					      var $this = $(this);
					      if ($this.val() == role[i]) {
					         $this.attr('selected','selected');
					         return false;
					      }
					 });

					}

					
					 $("#password").prop('disabled', true);
				     $("#password_confirmation").prop('disabled', true);
				  
				    
				     $('#password').removeAttr('required');
				     $('#password_confirmation').removeAttr('required');
				     $('#password').attr('data-parsley-required', 'false');
					 $('#password_confirmation').attr('data-parsley-required', 'false');
						}
					});
		});
});

 $('#newuserForm').submit(function(e) 
   {
	   //alert('dsj');
	   var password = $('#password').val();
	   var passwordhash = CryptoJS.SHA512(password);
       $('#password').val(passwordhash);
	  
	  var confirmpassword = $('#password_confirmation').val();
	   var confirmpasswordhash = CryptoJS.SHA512(confirmpassword);
       $('#password_confirmation').val(confirmpasswordhash);
	   
   
    //alert($('#password_confirmation').val()); 
	//alert($('#password').val());
	  
	 e.preventDefault();
       swal({
        title: "Are you sure ?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#newuserForm').submit();
                } 
        });
	})


function validateEmail(inputText)
{
var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
if(inputText.match(mailformat))
{
$('#email').focus();
return true;
}
else
{
alert("You have entered an invalid email address!");
$('#email').focus();
return false;
}
}

function checkMobNo(value) 
	{
	//	alert("checkMobNo");
	  if(value.length==10 || value.length==0 ){
                   var validate = true;
				   return value;
              } else {
                  alert('Please put 10  digit mobile number');
                  var validate = false;
				 //  $('#mobileno').focus();
				  return value;
              } 
			
		
	}