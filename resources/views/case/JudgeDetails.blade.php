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

<form action="JudgeDetailsSave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4>  Details of Judge </h4> </td>
        </tr>




<tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> Judge code</label> </td>
<td>
<input type="numeric" class="form-control pull-right" id="judgecode" name="judgecode"
value="<?php $judgecode=DB::select("SELECT max(CAST(judgecode as INT))as judgecode from judge")[0];
          $prposecode=$judgecode->judgecode+1;
          $userSave['judgecode']= $prposecode;
          echo   $userSave['judgecode'] ?>"  readonly='readonly' >
</td>
</tr>
<tr>
    <td> <span class="mandatory">*</span> <label for="applTitle"> Name  of the Judge</label> </td>
        <td>
            <input type="text" class="form-control pull-right" id="name" name="name"    data-parsley-pattern="/[A-Za-z!@#$%^&,'*()]/" data-parsley-pattern-message="Name of Judge allows only Alphanumeric" data-parsley-pattern-message="Name of Judge allows only Alphanumeric" value=""
             data-parsley-required  data-parsley-required-message="Enter Name of the Judge">
           </td>
</tr>

  <tr>
   <td> <span class="mandatory">*</span> <label for="applTitle">Designation </label> </td>
   <td>
     <select class="form-control" name="designation" id="designation" data-parsley-required  data-parsley-required-message="Select Designation " >
       <option value="">Select Designation</option>
       @foreach($judgedesignation as $judgedesignation)
       <option value="{{$judgedesignation->judgedesigcode}}">{{$judgedesignation->judgedesigname}}</option>
       @endforeach

     </select>


  </td>

 </tr>

     <tr>
      <td> <span class="mandatory">*</span> <label for="applTitle">Judge Short Name </label> </td>
      <td>
          <input type="text" class="form-control pull-right" id="judgeshortname" name="judgeshortname"    data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-pattern-message="Judge Short Name allows only Alphanumeric" data-parsley-pattern-message="Judge Short Name allows only Alphanumeric" value=""
           data-parsley-required  data-parsley-required-message="Enter Judge Short Name">
         </td>
       </tr>


        <tr>
          <td>  <label for="applTitle">  From Date </label> </td>
           <td>
             <input type="text" name="fromdate" class="form-control pull-right datepicker "   id="fromdate" value=""   data-parsley-errors-container="#error6" >
            <span id="error6"></span>
           </td>

        </tr>


      <tr>
        <td>  <label for="applTitle">  To Date </label> </td>
         <td>

        <input type="text" name="todate" class="form-control pull-right datepicker" id="hearingDate"  >
         </td>
       </tr>



        <tr>



          <td> <span class="mandatory">*</span> <label for="applTitle">Active</label> </td>
              <td>
                <select class="form-control" name="active" id="active" data-parsley-required  data-parsley-required-message="Select Active Type" >
                  <option value="">Select Active Type</option>
                <option value="Y">Yes</option>
                <option value="N">No</option>


                </select>
                 </td>

       <input type="hidden" class="form-control pull-right" id="id" name="id"  >

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
                      <td>Judgecode</td>
                      <td>Judge Name</td>
                      <td>Designation</td>
                      <td>From Date</td>
                      <td>To Date</td>
                      <td>Active</td>
                      <td>Judge Short Name</td>

                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>

                      @foreach($judge as $judge)


                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$judge->judgecode}}" class="extraClick"> {{$judge->judgecode}}   </a></td>



                  <td>{{$judge->judgename}}</td>
                  <td>{{$judge->judgedesigname}}</td>

                  <?php
                  echo "<td align=center>".($judge->fromdate ? date('d-m-Y', strtotime($judge->fromdate)) : '')."</td>";

                  ?>

                  <?php
                  echo "<td align=center>".($judge->todate ? date('d-m-Y', strtotime($judge->todate)) : '')."</td>";

                  ?>
                  <td>{{$judge->active}}</td>
                  <td>{{$judge->judgeshortname}}</td>

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

 //$("#todate").datepicker( { minDate: -0,dateFormat: 'dd-mm-yyyy', maxDate: new Date(2050, 1,18) });
 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick").click(function(){
			//console.log('hiii ');
			var judgecode   = $(this).attr('data-value');
			//console.log('hiii '+userid);
     console.log(judgecode);
			$.ajax({
				type: 'get',
				url: "getJudgeDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),judgecode:judgecode},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
          $("#judgecode").val(json[0].judgecode);
          $("#name").val(json[0].judgename);
					$("#designation").val(json[0].judgedesigcode);
					$("#judgeshortname").val(json[0].judgeshortname);
					//$("#userid").attr('readonly', true);
          $("#active").val(json[0].active);

         if( (json[0].fromdate)  == null && (json[0].todate) == null )
          {
          $("#fromdate").val(json[0].fromdate);
          $("#hearingDate").val(json[0].todate);
        }



       else
       {

          var hrdate1 = json[0].fromdate;
          var split4 = hrdate1.split('-');
          var orderdate1 = json[0].todate;
          var split5 = orderdate1.split('-');

      $("#fromdate").val(split4[2]+'-'+split4[1]+'-'+split4[0]);
      $("#hearingDate").val(split5[2]+'-'+split5[1]+'-'+split5[0]);

           }







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
