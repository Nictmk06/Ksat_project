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
<br> <br>
<div class="container">

<form role="form" id="newuserForm" action="saveNewUser" method="POST" data-parsley-validate>
@csrf  
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">
    
        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Create User </h4> </td>
        </tr>

 		<tr>
 		<td> <span class="mandatory">*</span> <label for="applTitle"> User Id </label> </td>
       <td>
          	 <input type="text" name="userid" id="userid" class="form-control" required data-parsley-required data-parsley-required-message="User Id"  data-parsley-pattern="/[a-zA-Z0-9\. ]+$/" data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="100" placeholder="userid" >
          </td>

 		<td> <span class="mandatory">*</span> <label for="applTitle"> Name of the User </label> </td>
          <td>
          	 <input type="text" name="userName" id="userName" class="form-control" required data-parsley-required data-parsley-required-message="Enter Name"  data-parsley-pattern="/[a-zA-Z\. ]+$/" data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="100" placeholder="Name" >
          </td>


        </tr>

        <tr>
         <td> <span class="mandatory">*</span> <label for="applTitle"> Password </label> </td>
          <td>
          	 <input type="password" name="password" id="password" class="form-control" required data-parsley-required data-parsley-required-message="Enter Password"  data-parsley-trigger='keypress' maxlength="300" placeholder="Password" >
          </td>
           <td> <span class="mandatory">*</span> <label for="applTitle">Confirm Password </label> </td>
          <td>
          	 <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required data-parsley-required data-parsley-required-message="Enter Confirm Password"  data-parsley-trigger='keypress' maxlength="300" placeholder="Confirm Password" >
          </td>
        </tr>

        <tr>
         <td> <span class="mandatory">*</span> <label for="applTitle">Designation </label> </td>
         <td >
           <select class="form-control" name="designation" id="designation"  data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus'>
           <option value="" >Select Designation </option>
            @foreach($designation as $designation)
            <option value="{{$designation->userdesigcode}}">{{$designation->userdesignation}}</option>
            @endforeach
           </select>
        </td>
          <td> <span class="mandatory"></span> <label for="applTitle">Section </label> </td>
          <td>

          	 <select class="form-control" name="userSection" id="userSection" required data-parsley-required data-parsley-required-message="Select userSection" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus'>
           <option value="" >Select Section </option>
            @foreach($userSection as $userSection)
            <option value="{{$userSection->userseccode}}">{{$userSection->usersecname}}</option>
            @endforeach
           </select>
          </td>
        </tr>

        <tr>
         <td> <span class="mandatory"></span> <label for="applTitle">Court Hall No </label> </td>
          <td>
          <select name="courtHallNo" id="courtHallNo" class="form-control" style="height:34px" data-parsley-trigger='focus'>
            <option value="" >Select </option>
             @foreach($courthall as $courthall)
            <option value="{{$courthall->courthallno}}">{{$courthall->courthalldesc}}</option>
            @endforeach

            </select>
		</td>
          <td> <span class="mandatory"></span> <label for="applTitle">User Level </label> </td>
           <td>
          <select name="userLevel" id="userLevel" class="form-control" data-parsley-required data-parsley-required-message="Select User Level " required  style="height:34px" data-parsley-trigger='focus'>
            <option value="" >Select </option>
            <option value="1" > 1 </option>
            <option value="2" > 2 </option>
            <option value="3"> 3 </option>
            <option value="4" > 4 </option>
            <option value="5"> 5 </option>
            </select>
		</td>
        </tr>

         <tr>
         <td> <span class="mandatory"></span> <label for="applTitle">Mobile Number</label> </td>
          <td>
		      <input class="form-control" name="mobileno" type="number" id="mobileno" required onblur="this.value = checkMobNo(this.value)" data-parsley-pattern="\d*"  data-parsley-pattern-message="Invalid Mobile No."  data-parsley-trigger='keypress' maxlength="10"  placeholder="Mobile Number">
          	 <!--<input type="text" name="mobileno" id="mobileno" class="form-control" required data-parsley-required data-parsley-required-message="Enter Mobile No" data-parsley-pattern="/^[0-9]+$/"  data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="100" placeholder="Mobile Number" >-->
          </td>
 		 <td> <span class="mandatory"></span> <label for="applTitle">Email</label> </td>
          <td>
          	 <input type="text" name="email" id="email" class="form-control"  required data-parsley-required data-parsley-required-message="Enter Email"  data-parsley-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" data-parsley-pattern-message="Invalid email" data-parsley-trigger='keypress' maxlength="100" placeholder="Email" >
          </td>
        </tr>

   		<tr>
         <td> <span class="mandatory"></span> <label for="applTitle"> Enable User</label> </td>
         <td>
          <select name="enableuser" id="enableuser" class="form-control" data-parsley-required data-parsley-required-message="Select Enable User" required  style="height:34px" data-parsley-trigger='focus'>
            <option value="" >Select </option>
            <option value="Yes" > Yes </option>
            <option value="No" > No </option>

            </select>
		</td>
			<td> <span class="mandatory"></span> <label for="applTitle">Establishment </label> </td>
          <td>

          	 <select class="form-control" name="establishment" id="establishment" required data-parsley-required data-parsley-required-message="Select Establishment" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus'>
           <option value="Establishment" >Select Establishment </option>
            @foreach($establishment as $establishment)
            <option value="{{$establishment->establishcode}}">{{$establishment->establishname}}</option>
            @endforeach
           </select>
          </td>
		  </tr>
         <tr>
		<td> <span class="mandatory">*</span> <label for="applTitle">Select role</label> </td>
 		<td>
          <select Style="width: 250px;height:100px;" name="userrole[]" id="userrole" multiple>
          {{--   <option value="" >Select </option> --}}
             <option value="0" > - </option> 
             @foreach($role as $userrole)
            <option value="{{$userrole->roleid}}">{{$userrole->rolename}}</option>
            @endforeach

            </select>
		</td>


        </tr>
         <tr>
        <td colspan="4">
        <div class="text-center">
          <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
             <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">


               <a class="btn btn-warning" href=""> Cancel </a>
        </div>

        </td>
        </tr>




  </form>
 @if ($errors->any())
    <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

 <div class="row">
                <table id="myTable4" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>User Id</td>
                      <td>Name</td>
                      <td>Mobile No</td>
                      <td>Court Hall Assigned</td>
					  <td>Establishment</td>
                  </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                  	@foreach($userDetails as $userDetails)

                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$userDetails->userid}}" class="extraClick"> {{$userDetails->userid}}   </a>
                     </td>

				    <td>{{$userDetails->username}}</td>

                    <td>{{$userDetails->mobileno}}</td>

                    <td>{{$userDetails->courthallno}}</td>
					<td>{{$userDetails->establishname}}</td>
                  </tr>
                 @endforeach

                </tbody>
              </table>
            </div>
<script src="js/jquery-3.4.1.js"></script>
<script src="js/user/user.js"></script>
<script src="js/user/sha512.js"></script>
  



</script>

@endsection
