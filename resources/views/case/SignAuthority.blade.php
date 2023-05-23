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

<form action="SignAuthoritySave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4>  Details of Signing Authority </h4> </td>
        </tr>




<tr>
    <td> <span class="mandatory">*</span> <label for="applTitle"> Name </label> </td>
        <td>
            <input type="text" class="form-control pull-right" id="name" name="name"    data-parsley-pattern="/[A-Za-z!@#$%^&,'*()]/" data-parsley-pattern-message="Namw allows only Alphanumeric" data-parsley-pattern-message="Name  allows only Alphanumeric" value=""
             data-parsley-required  data-parsley-required-message="Enter Name ">
           </td>
</tr>

  <tr>
   <td> <span class="mandatory">*</span> <label for="applTitle">Designation </label> </td>
   <td>
       <input type="text" class="form-control pull-right" id="designation" name="designation"    data-parsley-pattern="/[A-Za-z!@#$%^&,'*()]/" data-parsley-pattern-message="Designation allows only Alphanumeric" data-parsley-pattern-message="Designation allows only Alphanumeric" value=""
        data-parsley-required  data-parsley-required-message="Enter Designation">
      </td>

 </tr>




        <tr>
          <td> <span class="mandatory">*</span> <label for="applTitle">Department name </label> </td>
          <td>
              <input type="text" class="form-control pull-right" id="departmentname" name="departmentname"    data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-pattern-message="Department addresss allows only Alphanumeric" data-parsley-pattern-message="Name of Department allows only Alphanumeric" value=""
               data-parsley-required  data-parsley-required-message="Enter Name of the Department">
             </td>

      </tr>


      <tr>
      <td> <span class="mandatory">*</span> <label for="applTitle">Place/City </label> </td>
      <td>
          <input type="text" class="form-control pull-right" id="city" name="city"    data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-pattern-message="City allows only Alphanumeric" data-parsley-pattern-message="City allows only Alphanumeric" value=""
           data-parsley-required  data-parsley-required-message="Enter Place/City">
         </td>
       </tr>


        <tr>
          <td> <span class="mandatory">*</span> <label for="applTitle">  From Date </label> </td>
           <td>
             <input type="text" name="fromdate" class="form-control pull-right datepicker "   id="fromdate" value=""   data-parsley-errors-container="#error6" >
            <span id="error6"></span>
           </td>

        </tr>


      <tr>
        <td> <span class="mandatory">*</span> <label for="applTitle">  To Date </label> </td>
         <td>
           <input type="text" name="todate" class="form-control pull-right datepicker "  id="hearingDate" value=""   data-parsley-errors-container="#error6" >
          <span id="error6"></span>
         </td>
       </tr>


        <tr>



          <td> <span class="mandatory">*</span> <label for="applTitle">Signing Document </label> </td>
              <td>
                <select class="form-control" name="signdocument" id="signdocument" data-parsley-required  data-parsley-required-message="Select Sign Document Type" >
                  <option value="">Select Signing Document Type</option>
                <option value="C">Cause List</option>
                <option value="J">Judgement</option>


                </select>
                 </td>

       <input type="hidden" class="form-control pull-right" id="idno" name="idno"  >

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
                      <td>Id</td>
                      <td>Name</td>
                      <td>Designation</td>
                      <td>Department Name</td>
                      <td>Place/city</td>
                      <td>From Date</td>
                      <td>To Date</td>
                      <td>Signing  Document Type</td>

                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                      @foreach($signauthority as $signauthority)

                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$signauthority->idno}}" class="extraClick"> {{$signauthority->idno}}   </a></td>


                  <td>{{$signauthority->name}}</td>
                  <td>{{$signauthority->designation}}</td>
                  <td>{{$signauthority->deptname1}}</td>
                  <td>{{$signauthority->deptname2}}</td>
                  <td>{{date('d/m/Y', strtotime($signauthority->fromdate))}}</td>


                  <td>{{date('d/m/Y', strtotime($signauthority->todate))}}</td>
                  <td>{{$signauthority->documenttype}}</td>


                  </tr>
                  @endforeach

                </tbody>
              </table>
            </div>
            <script src="js/jquery-3.4.1.js"></script>

<script>
$(document).ready(function() {
  $("#fromdate").datepicker({
              format: 'dd-mm-yyyy',
              autoclose: true,
            }).on('changeDate', function(selected) {
              var endDate = new Date(selected.date.valueOf());
              $('#hearingDate').datepicker('setStartDate', endDate);
            }).on('clearDate', function(selected) {
              $('#hearingDate').datepicker('setEndDate', null);
            });

 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick").click(function(){
			//console.log('hiii ');
			var idno   = $(this).attr('data-value');
			//console.log('hiii '+userid);
			$.ajax({
				type: 'post',
				url: "getSignAuthorityDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),idno:idno},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
          $("#idno").val(json[0].idno);
          $("#name").val(json[0].name);
					$("#designation").val(json[0].designation);
					$("#departmentname").val(json[0].deptname1);
					//$("#userid").attr('readonly', true);
          var hrdate = json[0].fromdate;
          var split2 = hrdate.split('-');

          var orderdate = json[0].todate;

         var split3 = orderdate.split('-');


         $("#fromdate").val(split2[2]+'-'+split2[1]+'-'+split2[0]);


         $("#hearingDate").val(split3[2]+'-'+split3[1]+'-'+split3[0]);


        	$("#city").val(json[0].deptname2);

					$("#signdocument").val(json[0].documenttype);


					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');






						}
					});
		});
});
</script>

{{-- <script src="http://bladephp.co/download/multiselect/jquery.min.js"></script>
<link href="http://bladephp.co/download/multiselect/jquery.multiselect.css" rel="stylesheet" />
<script src="http://bladephp.co/download/multiselect/jquery.multiselect.js"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> --}}


</script>

@endsection
