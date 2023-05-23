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

<form action="smsInsert" method="GET" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
<table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h7>SMS SEND</h7></td>
        </tr>
</table>

     <table class="table no-margin table-bordered">

  <!--      <tr>
     		<td> <span class="mandatory">*</span> <label for="applTitle">Type of Application</label> </td>
           <td>
              	 <select class="form-control" name="applTypeName" id="applTypeName"  >
                  <option value="">Select Application Type</option>
                        @foreach($applicationType as $applType)

                        <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>

                        @endforeach
                      </select>
              </td>

     		<td> <span class="mandatory">*</span> <label for="applTitle">Application Number
              <td>
              	 <input type="text" name="applicationId" id="applicationId" class="form-control"  data-parsley-required data-parsley-required-message="Enter Application Number" data-parsley-pattern="/^[0-9\/]+$/" data-parsley-trigger='keypress' maxlength="100" placeholder=" Application Number" >
              </td>


            </tr>

-->
<tr>
  <input type="hidden" id="module" name="module" value="<?php print_r($module[0]->modulecode);?>">

</tr>

     <tr>
      <td colspan="4">
      <div class="text-center">
        <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Send Sms">

        </div>
      </td>
   </tr>
</table>

<table class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
    <thead>

<tr>
  <td  class="bg-primary text-center" colspan="10">   <h4> Today's SMS LIST </h4> </td>
</tr>

<tr>
<td>Sr.No</td>
<td>Application ID</td>
<td>Name</td>
<td>Mobile Number</td>
<td>SMS CONTENT</td>
<td>Module Name</td>

<td>Advocate Flag</td>


</tr>
</thead>

<tbody>
  <?php $sum=0;?>
                  <?php $i = 1; ?>
      @foreach ($smslog as $smslog)
       <tr>
        <?php
        $applicationid=$smslog->applicationid;
        $name=$smslog->name;
        $mobilenumber=$smslog->mobileno;
        $smscontent=$smslog->smscontent;
        $modulename=$smslog->modulename;
        $advocateflag=$smslog->advocateflag;


         ?>


        <td align="center">{{$i++}}</td>
        <td><?php echo $applicationid; ?></td>
        <td><?php echo $name; ?></td>
        <td><?php echo $mobilenumber; ?></td>
        <td><?php echo $smscontent; ?></td>
        <td><?php echo $modulename; ?></td>
        <td><?php echo $advocateflag; ?></td>



      @endforeach

  </tr>
</tbody>
</table>
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
<script src="js/jquery-3.4.1.js"></script>
</script>
@endsection
