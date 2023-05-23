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

<form action="AddNewDepartmentSave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4>  Department </h4> </td>
        </tr>


    <td> <span class="mandatory">*</span> <label for="applTitle">Department Type </label> </td>
       <td>
         <select class="form-control" name="departmenttype" id="departmenttype" data-parsley-required  data-parsley-required-message="Select Department Type" >
           <option value="">Select Department Type</option>
           @foreach($departmenttype as $departmenttype)
           <option value="{{$departmenttype->depttypecode}}">{{$departmenttype->depttype}}</option>
           @endforeach

         </select>
          </td>

 		<td> <span class="mandatory">*</span> <label for="applTitle"> Name of the Department </label> </td>


          <td>
            <input type="hidden" id="departmentcode" name="departmentcode" value="{{$department->departmentcode}}">
            <input type="text" class="form-control pull-right" id="department" name="department"    data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-pattern-message="Department addresss allows only Alphanumeric" data-parsley-pattern-message="Name of Department allows only Alphanumeric" value=""
            data-parsley-required  data-parsley-required-message="Enter Name of the Department">
          </td>



        </tr>

        <tr>
          <td> <span class="mandatory">*</span> <label for="applTitle"> Department Address </label> </td>
          <td>
            <textarea class="form-control" name='departmentaddress' id="departmentaddress" data-parsley-required  data-parsley-required-message="Department Address" >
            </textarea>
        </td>




        </tr>

        <tr>
          <td> <span class="mandatory">*</span> <label for="applTitle">  District </label> </td>
           <td>
             <select class="form-control" name="district" id="district" data-parsley-required  data-parsley-required-message="Select District" >
               <option value="">Select District</option>
               @foreach($district as $district)
               <option value="{{$district->distcode}}">{{$district->distname}}</option>
               @endforeach

             </select>
           </td>





          <td> <span class="mandatory">*</span> <label for="applTitle">Taluk </label> </td>
          <td>

            <select class="form-control" name="taluk" id="taluk" data-parsley-required  data-parsley-required-message="Select Taluk" >
              <option value="">Select Taluk</option>
              @foreach($taluk as $taluk)
              <option value="{{$taluk->talukcode}}">{{$taluk->talukname}}</option>
              @endforeach

            </select>




        </tr>




        <tr>

        </td>

        <td> <span class="mandatory">*</span> <label for="applTitle">Phone Number </label> </td>
         <td>
           <input type="text" class="form-control pull-right" id="phonenumber" name="phonenumber"    data-parsley-pattern="/^[0-9 ]/" data-parsley-pattern-message="Phone Number allows only numeric" data-parsley-pattern-message="Phone Number allows only numeric" value=""
           data-parsley-required  data-parsley-required-message="Enter Phone Number">
        </td>

          <td> <span class="mandatory">*</span> <label for="applTitle">Mobile Number </label> </td>
           <td>
             <input type="text" class="form-control pull-right" id="mobilenumber" name="mobilenumber"    data-parsley-pattern="/^[0-9 ]/" data-parsley-pattern-message="Phone Number allows only numeric" data-parsley-pattern-message="Phone Number allows only numeric" value=""
             data-parsley-required  data-parsley-required-message="Enter Mobile Number">
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
                      <td>Department Code</td>
                      <td>Department Name</td>
                      <td>Department Address</td>
                      <td>Phone number</td>
                      <td>Mobile Number</td>

                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                  	@foreach($department as $department)

                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$department->departmentcode}}" class="extraClick"> {{$department->departmentcode}}   </a></td>


                  <td>{{$department->departmentname}}</td>
                  <td>{{$department->deptaddress}}</td>

                   <td>{{$department->deptphoneno}}</td>
                  <td>{{$department->deptmobile}}</td>


                  </tr>
                 @endforeach

                </tbody>
              </table>
            </div>
            <script src="js/jquery-3.4.1.js"></script>

<script>
$(document).ready(function() {


 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick").click(function(){
			//console.log('hiii ');
			var departmentcode   = $(this).attr('data-value');
			//console.log('hiii '+userid);
			$.ajax({
				type: 'post',
				url: "getDepartmentDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),departmentcode:departmentcode},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
			    console.log('inside success '+json[0].departmentcode);
          $("#departmentcode").val(json[0].departmentcode);
					$("#departmentcode").attr('readonly', true);
					$("#department").val(json[0].departmentname);
					//$("#userid").attr('readonly', true);
					$("#departmenttype").val(json[0].depttypecode);
					$("#departmentaddress").val(json[0].deptaddress);
					$("#taluk").val(json[0].depttaluk);
					$("#district").val(json[0].deptdistrict);
					$("#phonenumber").val(json[0].deptphoneno);
					$("#mobilenumber").val(json[0].deptmobile);

					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');

					console.log('inside success '+json[1]);
					 $("#department option:selected").removeAttr("selected");




						}
					});
		});
});
</script>
<script>
         $(document).ready(function() {
        $('#district').on('change', function() {
            var districtID = $(this).val();
            if(districtID) {
                $.ajax({
                    url: '/findDistrictWithTaluk/'+districtID,
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: "json",
                    success:function(data) {
                        console.log(data);
                      if(data){
                        $('#taluk').empty();
                        $('#taluk').focus;
                        $('#taluk').append('<option value="">-- Select Taluk --</option>');
                        $.each(data, function(key, value){
                          console.log(key);
                          console.log(value);
                        $('select[name="taluk"]').append('<option value="'+ value.talukcode +'">' + value.talukname+ '</option>');

                    });
                  }

                  else{
                    $('taluk').empty();
                  }
                  }
                });
            }else{
              $('taluk').empty();
            }
        });
    });
    </script>

@endsection
