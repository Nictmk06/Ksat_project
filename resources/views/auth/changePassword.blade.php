@extends('layout.mainlayout')
@section('content')

<div class="content-wrapper">

  <style type="text/css">
  .pager{
  background-color: #337ab7;
  color: #fff;
  }
  .do-scroll{
  width: 100%;
  height: 100px;
  overflow-y: scroll;
  }
  .btnSearch,
  .btnClear{
  display: inline-block;
  vertical-align: top;
  }
  </style>

  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>
@include('flash-message')

<div class="container">
<!--<form action="{{'/changePasswordSave' }}" method="POST" data-parsley-validate>-->
<form role="form" id="changePasswordForm" action="changePasswordSave" method="POST" data-parsley-validate>

@csrf

      <h2>Update Password</h2>

       <div class="row">
    <div class="col-md-6">
       <table class="table no-margin table-bordered">




      <tr>
     <td> <span class="mandatory">*</span> <label for="applTitle"> Username</label> </td>

    <td><input type="password"   class="form-control" name="username"  placeholder='<?php echo session()->get('userName') ?>' disabled></td>
    </tr>



     <tr>
     <td> <span class="mandatory">*</span> <label for="applTitle"> Current Password </label> </td>

    <td><input type="password" class="form-control" name="oldPassword" id="oldPassword" required data-parsley-required data-parsley-required-message="Enter Password" data-parsley-pattern='/[a-zA-Z0-9\.@ #]/' data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="300" placeholder="Current password"></td>
    </tr>

     <tr>
     <td> <span class="mandatory">*</span> <label for="applTitle"> New Password </label> </td>

     <td><input type="password" class="form-control" name="newPassword1" id="newPassword1" required data-parsley-required data-parsley-required-message="Enter Password" data-parsley-pattern='/[a-zA-Z0-9\.@# ]/'data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="300"  placeholder="New password"></td>
     </tr>


     <tr>
     <td> <span class="mandatory">*</span> <label for="applTitle"> Confirm Password </label> </td>

     <td><input type="password" class="form-control"  name="newPassword2" id="newPassword2" required data-parsley-required data-parsley-required-message="Enter Password" data-parsley-pattern='/[a-zA-Z0-9\. @#]/'data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="300" placeholder="Confirm password"></td>
     </tr>
      <td colspan="4">
      <div class="text-center">

    <button type="submit" name="saveButton"class="btn btn-primary" > Save </button>

    <input type="button"  button onclick="goBack()"class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel"></button>
    <script>
    function goBack() {
      window.history.back();
    }
    </script>

      </div>
    </td>
    </div>

 </div>
  </table>

</form>
</div>

<script src="js/jquery-3.4.1.js"></script>
<script src="js/user/sha512.js"></script>
<script>
$('#changePasswordForm').submit(function(e) 
   {
   var oldPassword = $('#oldPassword').val();
	   var passwordhash = CryptoJS.SHA512(oldPassword);
       $('#oldPassword').val(passwordhash);
	  
	   var newPassword1 = $('#newPassword1').val();
	   var newPassword1hash = CryptoJS.SHA512(newPassword1);
       $('#newPassword1').val(newPassword1hash);
	   
	  var newPassword2 = $('#newPassword2').val();
	   var newPassword2hash = CryptoJS.SHA512(newPassword2);
       $('#newPassword2').val(newPassword2hash);
	   
  
	 e.preventDefault();
       swal({
        title: "Are you sure to Update password ?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#changePasswordForm').submit();
                } 
        });
	})
</script>

@endsection
