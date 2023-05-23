
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

  <script src="js/jquery-3.4.1.js"></script>


<script>
$(document).ready(function()
{

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


  $("#feePurpose").change(function()
  {
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

    $("input[type='radio'][name='paymentMode']").click(function(){
      var paymode = $("input[name='paymentMode']:checked").val();

     if ( paymode == 'D')
     {
      $("#ddDate").prop('disabled',false);
      $("#ddNumber").prop('disabled',false);
      $("#drawnBank").prop('disabled',false);
     }
     else
     {
      $("#ddDate").prop('disabled',true);
      $("#ddNumber").prop('disabled',true);
      $("#drawnBank").prop('disabled',true);
     }
    });


/* $("#ddNumber").blur(function(){
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
	  alert(drawnBank);
	   alert(ddNumber);
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
 });*/

});

</script>

  <?php error_reporting(0);
  ini_set('display_errors', 0);
  $receiptno           = $receipts[0]->receiptno;
  $receiptdate         = date('d-m-Y', strtotime($receipts[0]->receiptdate));
  $modeofpayment       = $receipts[0]->modeofpayment;
  $ddchqno             = $receipts[0]->ddchqno;
  $ddchqdate           = date('d-m-Y', strtotime($receipts[0]->ddchqdate));
  $bankcode            = $receipts[0]->bankcode;
  $amount              = $receipts[0]->amount;
  $receiptsrno         = $receipts[0]->receiptsrno;
  $applicantadvocate   = $receipts[0]->applicantadvocate;
  $feepurposecode      = $receipts[0]->feepurposecode;
  $name                = $receipts[0]->name;
  $otherdetails        = $receipts[0]->otherdetails;
  $applicationid       = $receipts[0]->applicationid;
  $receiptuseddate     = $receipts[0]->receiptuseddate;
  $titlename           = $receipts[0]->titlename;

  if ($ddchqdate == date('d-m-Y', strtotime('01-01-1970')) or $modeofpayment == 'C')
     $ddchqdate = "";

  ?>
  @include('flash-message')
<br> <br>
<div class="container">

<form action="receiptCrudUpdate" method="POST" data-parsley-validate>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
@csrf
<div class="row">
<div class="col-md-10">

    <table class="table no-margin table-bordered">
    <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Receipt Edit </h4> </td>
        </tr>

        <tr>
        <td class="bg-success"> <label for="receiptNo"> Receipt No </label> </td>
        <td class="bg-success"> <input type="text" name="receiptNoDisp" id="receiptNoDisp"class="form-control" value="{{ $receiptno }}" disabled>
                                <input type="hidden" name="receiptNo" id="receiptNo" class="form-control" value="{{ $receiptno }}" >
        </td>
        <td> <span class="mandatory">*</span> <label for="receiptDateDisp"> Date of Receipt </label> </td>
        <td> <input type="text" name="receiptDateDisp" class="form-control" id="receiptDateDisp" value="{{ $receiptdate }}" disabled >
             <input type="hidden" name="receiptDate" id="receiptDate" value="{{ $receiptdate }}">
        </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="advocateComplainant"> Applicant Type </label> </td>
        <td colspan="3">
        <div class="form-check">
                         <input type="radio" class="form_check_input" name="advocateComplainant" value="A" <?php if($applicantadvocate == 'A') {?>  checked <?php } ?> >  Advocate
                         <input type="radio" class="form_check_input" name="advocateComplainant" value="C" <?php if($applicantadvocate == 'C') {?>  checked <?php } ?> > Others
        </div>
        </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="feePurpose"> Purpose </label> </td>
        <td colspan="3">
           <select class="form-control" name="feePurpose" id="feePurpose" required data-parsley-required data-parsley-required-message="Select Purpose" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus'>
           <option value="" >Select Fee Purpose </option>
            @foreach($feepurposes as $feepurpose)

            @if ($feepurpose->purposecode == $feepurposecode)
                <option value="{{$feepurpose->purposecode . ':' . $feepurpose->paymentcode}}" selected>{{$feepurpose->purposename}}</option>
            @else
             <option value="{{$feepurpose->purposecode . ':' . $feepurpose->paymentcode}}"> {{$feepurpose->purposename}}</option>
            @endif

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
            <option value="Shri." <?php if($titlename == 'Shri.') {?>  selected <?php } ?> > Shri. </option>
            <option value="Mr." <?php if($titlename == 'Mr.') {?>  selected <?php } ?> > Mr. </option>
            <option value="Smt." <?php if($titlename == 'Smt.') {?>  selected <?php } ?> > Smt. </option>
            <option value="Miss." <?php if($titlename == 'Miss.') {?>  selected <?php } ?> > Miss. </option>
            <option value="Dr." <?php if($titlename == 'Dr.') {?>  selected <?php } ?> > Dr. </option>
            </select>
            </div>
            <div class="col-md-10">
            <input type="text" name="applicantName" class="form-control" required data-parsley-required data-parsley-required-message="Enter Name"  data-parsley-pattern="/[a-zA-Z\. ]+$/" data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' maxlength="100" value="{{ $name}}" >
            </div>
        </div>
        </td>
        </tr>

        <tr>
        <td> <label for="otherDetails"> Other Details </label></td>
        <td> <input type="text" name="otherDetails" class="form-control" data-parsley-required data-parsley-required-message="Enter Other Details"  data-parsley-pattern="/[a-zA-Z\. ]+$/" data-parsley-pattern-message="Invalid input" data-parsley-trigger='keypress' value="{{ $otherdetails }}" maxlength="100"> </td>
        <td> <span class="mandatory">*</span> <label for="feeAmount"> Fee Amount </label> </td>
        <td> <input type="text" name="feeAmount" id="feeAmount" class="form-control number " required data-parsley-required data-parsley-required-message="Enter amount"  data-parsley-pattern="/[0-9]+$/" data-parsley-pattern-message="Invalid Amount" data-parsley-trigger='keypress' maxlength="7" value="{{ $amount }}" </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="paymentMode"> Payment Mode </label> </td>
        <td>
        <div class="form-check">
             <input type="radio" class="form_check_input" name="paymentMode" id="paymentModeCash"  value="C" <?php if($modeofpayment == 'C') {?>  checked <?php } ?> > Cash
             <input type="radio" class="form_check_input" name="paymentMode" id="paymentModeDD" value="D" <?php if($modeofpayment == 'D') {?>  checked <?php } ?> > DD
        </div>
        <td> <label for="ddDate"> DD Date </label></td>
        <td> <input type="text" name="ddDate" class="form-control pull-right datepicker" id="ddDate" value="{{ $ddchqdate }}" disabled=false> </td>
        </td>
        </tr>

        <tr>
        <td> <span class="mandatory">*</span> <label for="ddNumber"> DD Number </label></td>
        <td> <input type="text" name="ddNumber" class="form-control" id="ddNumber" data-parsley-required data-parsley-required-message="Enter DD Number"  data-parsley-pattern="/[a-zA-Z0-9\/- ]+$/" data-parsley-pattern-message="Invalid DD Number" data-parsley-trigger='keypress' maxlength="15" value="{{ $ddchqno }}" disabled=false> </td>
        <td> <span class="mandatory">*</span> <label for="drawnBank"> Drawn Bank </label></td>
        <td>
           <select class="form-control" name="drawnBank" id="drawnBank" data-parsley-required data-parsley-required-message="Select Bank" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus' disabled=false>
           <option value="" >Select Bank Branch </option>
            @foreach($bankbranches as $bankbranch)

            @if ($bankbranch->bankcode == $bankcode)
            <option value="{{$bankbranch->bankcode}}" selected >{{$bankbranch->bankdesc}}</option>
            @else
            <option value="{{$bankbranch->bankcode}}" >{{$bankbranch->bankdesc}}</option>
            @endif

            @endforeach
            </select>
        </td>
        </tr>
        <tr>
        <td colspan="4">
        <div class="text-center">

                <button type="submit" class="btn btn-primary">Update</button>
               <a class="btn btn-warning" href="receiptCrudEditlist"> Cancel </a>
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

@endsection
