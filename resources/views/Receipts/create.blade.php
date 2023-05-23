
@extends('layout.mainlayout')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
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


<form role="form" id="receiptForm" action="receiptCrudStore" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">

    <table class="table no-margin table-bordered">
      <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Fee Receipt </h4> </td>
        </tr>

        <?php
         $receiptdate_sys = date("d-m-Y");
        ?>

        <tr>
        <td class="bg-success"> <label for="receiptNo"> Receipt No </label> </td>
        <td class="bg-success"> <input type="text" name="receiptNo" class="form-control" placeholder="Receiptno" disabled> </td>
        <td> <span class="mandatory">*</span> <label for="receiptDateDisp"> Date of Receipt </label> </td>
        <td> <input type="text" class="form-control pull-right datepicker" name="receiptDate" id="receiptDate" value="{{ $receiptdate_sys }}">
             <input type="hidden"  name="receipt_Last_Closed_Date" id="receipt_Last_Closed_Date" value="{{ date('d-m-Y', strtotime($rlcd[0]->receiptdate)) }}">
        </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="advocateComplainant"> Applicant Type </label> </td>
        <td colspan="3">
        <div class="form-check">
                         <input type="radio" class="form_check_input" name="advocateComplainant" value="A" checked >  Advocate
                         <input type="radio" class="form_check_input" name="advocateComplainant" value="C" > Others
        </div>
        </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="feePurpose"> Purpose </label> </td>
        <td colspan="3">
           <select class="form-control" name="feePurpose" id="feePurpose" required data-parsley-required data-parsley-required-message="Select Purpose" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus'>
           <option value="" >Select Fee Purpose </option>
            @foreach($feepurposes as $feepurpose)
            <option value="{{$feepurpose->purposecode . ':' . $feepurpose->paymentcode}}">{{$feepurpose->purposename}}</option>
            @endforeach
           </select>
        </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="applTitle"> Name </label> </td>
        <td colspan="3">
        <div class="row">
            <div class="col-md-2">
            <select name="applTitle" id="applTitle" class="form-control" data-parsley-required data-parsley-required-message="Select title" required  style="height:34px" data-parsley-trigger='focus'>
            <option value="" >Select Title </option>
            <option value="Shri." > Shri. </option>
            <option value="Mr." > Mr. </option>
            <option value="Smt." > Smt. </option>
            <option value="Miss." > Miss. </option>
            <option value="M/s"> M/s </option>
            <option value="Dr." > Dr. </option>
            </select>
            </div>
            <div class="col-md-10">
            <input type="text" name="applicantName" class="form-control" required data-parsley-required data-parsley-required-message="Enter Name"  data-parsley-pattern="/[a-zA-Z\. ]+$/" data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="100" placeholder="Name" >
            </div>
        </div>
        </td>
        </tr>

        <tr>
        <td> <label for="otherDetails"> Other Details </label></td>
        <td> <input type="text" name="otherDetails" class="form-control" data-parsley-required data-parsley-required-message="Enter Other Details"  data-parsley-pattern="/[a-zA-Z\. ]+$/" data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' placeholder="Other Details" maxlength="100"> </td>
        <td> <span class="mandatory">*</span> <label for="feeAmount"> Fee Amount </label> </td>
        <td> <input type="text" name="feeAmount" id="feeAmount" class="form-control number zero" required data-parsley-required data-parsley-required-message="Enter amount"  data-parsley-pattern="/[0-9]+$/" data-parsley-pattern-message="Invalid Amount" data-parsley-trigger='keypress' maxlength="7" placeholder="Fee amount in numeric"> </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="paymentMode"> Payment Mode </label> </td>
        <td>
        <div class="form-check">
             <input type="radio" class="form_check_input" name="paymentMode" id="paymentModeCash" value="C" checked > Cash
             <input type="radio"  class="form_check_input" name="paymentMode" id="paymentModeDD" value="D" > DD
        </div>
        <td> <label for="ddDate"> DD Date </label></td>
        <td> <input type="text" name="ddDate" class="form-control pull-right datepicker" id="ddDate" placeholder="DD Date" disabled=true> </td>
        </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="ddNumber"> DD Number </label></td>
        <td> <input type="text" name="ddNumber" class="form-control" id="ddNumber" data-parsley-required data-parsley-required-message="Enter DD Number"  data-parsley-pattern="/[a-zA-Z0-9\/- ]+$/" data-parsley-pattern-message="Invalid DD Number" data-parsley-trigger='keypress' maxlength="15" placeholder="DD Number" disabled=true> </td>
        <td> <span class="mandatory">*</span> <label for="drawnBank"> Drawn Bank </label></td>
        <td>
           <select class="form-control" name="drawnBank" id="drawnBank" data-parsley-required data-parsley-required-message="Select Bank" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus' disabled=true>
           <option value="" >Select Bank Branch </option>
            @foreach($bankbranches as $bankbranch)
            <option value="{{$bankbranch->bankcode}}">{{$bankbranch->bankdesc}}</option>
            @endforeach
            </select>
        </td>
        </tr>
        <tr>
        <td colspan="4">
        <div class="text-center">

                <button type="submit" name="saveButton" class="btn btn-primary"> Save </button>
               <a class="btn btn-warning" href="{{route('receiptCrudIndex')}}"> Cancel </a>
        </div>

        </td>
        </tr>
    </table>

    </div>
    </div>
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

</div>  {{-- class contaner --}}


<script src="js/jquery-3.4.1.js"></script>



<script>

$(document).ready(function(){

  $("#feePurpose").change(function(){
   var feePurposeCode = $(this).val();
   feePurposeCode     = feePurposeCode.split(':',1);
   $.ajax({
   type : 'get',
   url : "receiptGetFeeAmount",
   dataType : "JSON",
   data : {feepurposecode:feePurposeCode},
   success: function (json)
   {
     if(json.length > 0)
     {
       $("#feeAmount").val(json[0].feeamount);
     }
     else
     {
       $("#feeAmount").val('Fee amount not found');
     }

    }
    });
   });



  $("#feeAmount").blur(function(){

     var feeAmount   = $('#feeAmount').val();
    if(feeAmount > 499)
        {
          $("#paymentModeDD").prop("checked", true);
             $("#paymentModeCash").attr('disabled', true);
              $("#ddDate").prop('disabled',false);
              $("#ddNumber").prop('disabled',false);
              $("#drawnBank").prop('disabled',false);
         }else{
           $("#paymentModeCash").prop("checked", true);
           $("#paymentModeCash").attr('disabled', false);
           $("#ddDate").prop('disabled',true);
           $("#ddNumber").prop('disabled',true);
           $("#drawnBank").prop('disabled',true);
        }

    });




   $("input[type='radio'][name='paymentMode']").click(function(){
    console.log('raj');
      var paymode = $("input[name='paymentMode']:checked").val();

     if ( paymode == 'D')
     {
      $("#ddDate").prop('disabled',false);
      $("#ddNumber").prop('disabled',false);
      $("#drawnBank").prop('disabled',false);
     }
     else
     {
		  $("#ddDate").val('');
      $("#ddNumber").val('');
      $("#drawnBank").val('');
      $("#ddDate").prop('disabled',true);
      $("#ddNumber").prop('disabled',true);
      $("#drawnBank").prop('disabled',true);
     }
    });


});



 $("#ddNumber").blur(function(){
        var drawnBank     = $('#drawnBank').val();
	  var ddNumber = $('#ddNumber').val();
	  if(ddNumber!='' ||drawnBank !=''){
		  	$.ajax({
		   type: 'POST',
            url: 'getDDExist',
			 dataType : "JSON",
			   data: {
                "_token": $('#token').val(),
               ddchqno:ddNumber,bankcode:drawnBank
            },
          success: function (data)
        {
		 if(data.status=="success")
		{
			}
		else if(data.status=="exists")
		{
		swal({
		title: data.message,
		icon: "error"
		  })
		  $("#ddNumber").val('');
      $("#drawnBank").val('');
		  return false;
			}

			}
	 });
	  }
 });

$("#drawnBank").change(function(){
	 var drawnBank     = $('#drawnBank').val();
	  var ddNumber = $('#ddNumber').val();
	  if(ddNumber!='' ||drawnBank !=''){
		  	$.ajax({
		   type: 'POST',
            url: 'getDDExist',
			 dataType : "JSON",
			   data: {
                "_token": $('#token').val(),
               ddchqno:ddNumber,bankcode:drawnBank
            },
          success: function (data)
        {
		 if(data.status=="success")
		{

			}
		else if(data.status=="exists")
		{
		swal({
		title: data.message,
		icon: "error"
		  })
		$("#ddNumber").val('');
        $("#drawnBank").val('');
		  return false;
			}

			}
	 });
	  }
 });

$('#receiptForm').submit(function(e)
   {

   var feeAmount=$('#feeAmount').val();
   if(feeAmount > 0)
       {
	var paymode = $("input[name='paymentMode']:checked").val();
      if ( paymode == 'D')
     {
	var ddDate = $('#ddDate').val();
	 var ddNumber = $('#ddNumber').val();
	 var drawnBank     = $('#drawnBank').val();
	 if(ddDate ==''|| ddNumber=='' ||drawnBank ==''){
		 alert("Please enter DD details");
		 return false;
	 }

   }
        e.preventDefault();
        swal({
        title: "Are you sure to save?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){
                if(isConfirm){
                    $('#receiptForm').submit();
                }
        });

       }
       else{
        alert(" Fee Amount cannot be zero.")
        return false;
       }
    });

</script>


@endsection
