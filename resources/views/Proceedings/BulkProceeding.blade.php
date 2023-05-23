
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

  div.ext1 {
  height: 200px;
  width: 100%;
  overflow-y: scroll;
}

div.ext2 {
  color: black;
  font-size:14px;
  background-color: white;
  height: 30px;
  width: 1000px;
  overflow-y: scroll;
}

.highlight {
   font-size:14px;
   color:darkred;
  	background:yellow;
  }

  button.btn1 {
  background-color: green; /* Green */
  border: 2px;
  border-radius: 8px;
  color: white;
  padding: 6px 14px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 20px;
  font-weight: bold;
}
button.btn2 {
  background-color:orange; /* Green */
  border: 2px;
  border-radius: 8px;
  color: white;
  padding: 6px 14px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 20px;
  font-weight: bold;
}
.grid-container {
  display: grid;
  grid-gap: 1px;
  grid-template-columns: 100px 150px 250px auto;
  background-color: white /*#2196F3*/;
  padding: 3px;
}

.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 3px;
  font-size: 16px;
  text-align: center;
}
  </style>



  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>

@include('flash-message')
<br> <br>
<?php
 if (count($dailyhearing) == 0)
 { ?>
    <div class="alert alert-danger">
	 <button type="button" class="close" data-dismiss="alert">×</button>
            <strong> No applications found/left for today, in this court hall </strong>
   </div>

<?php
 } ?>

<div class="container">


<form action="ChBulkProceedingUpdate" method="POST" data-parsley-validate>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@csrf
<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered" style="font-size:14px;">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Court Hall - Bulk Proceedings </h4> </td>
        </tr>

        <?php
         $today_sys_dt = date("d-m-Y");
        ?>

        <tr>
        <td colspan="4">

        <table width="99%">
        <tr>
		<td style:width="40%"> <label> Hearing Date : </label> <span name="hd_hearingdate" id="hd_hearingdate"> {{ date('d-m-Y') }} </span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		<td style:width="40%">    <label> Bench : </label>
             <span name="hd_benchdetail" id="hd_benchdetail">  {{$benchdetail[0]->benchtypename}} [ {{$benchdetail[0]->judgeshortname}} ] </span>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
       <td style:width="40%">   <label> Court Hall : </label>  <span name="hd_courthallno" id="hd_courthallno"> {{$courthall}} </span>
	    </td>

		<td align="left"> <label for="causelistno"> <span id="mainIdLabel">  Cause List No. : </span><span class="mandatory">*</span> </label>  </td>
        <td> <select class="form-control" style="width:200px" name="listno" id="listno" required data-parsley-required-message="Select  Cause List No" style="height:34px" data-parsley-trigger='focus'>
           <option value="0" >Select Cause List No </option>

            @foreach($listno as $listno)

            <option value="{{$listno->listno}}">{{$listno->listno}}</option>
            @endforeach
            </select>
		</td>



       <!--  <td colspan="2"><label> - : </label>
           <select class="form-control" style="width:200px" name="applicationId" id="applicationId" required data-parsley-required-message="Select Application" style="height:34px" data-parsley-trigger='focus'>
           <option value="0:0" >Select Application </option>

            @foreach($dailyhearing as $daily)
            <option value="{{$daily->applicationid . ':' . $daily->hearingdate}}">{{$daily->applicationid}}</option>
            @endforeach
            </select>
            </td> -->
        </tr>
        </table>

        </td>
        </tr>

        <tr>
        <td> <label for="businessText" > Business <span class="mandatory">*</span> </label>  </td>
        <td colspan="2"> <textarea class="form-control" name="businessText" id="businessText" rows="3" cols="50" required> </textarea> </td>

        <td>  <input type="checkbox" name="businessYN" id="businessYN" >  (No Business) </td>
        </tr>

        <tr>
        <td colspan="4">

        <!--  <div class="grid-container" style="color:orange">
            <div class="grid-item">Sr.No.</div>
            <div class="grid-item">Select Case(s) <br> <input type="checkbox" id="toggleCheckbox1"> <label style="color:blue;"> Toggle All </label> </div>
            <div class="grid-item">Case Number</div>
            <div class="grid-item">Present Status</div>
        </div>
       <div class="ext1">
        <div class="grid-container">
        <?php
        $i         = 1;
        $arr_index = 0;
        ?>
          @foreach($dailyhearing as $daily)

            <div class="grid-item">{{ $i }}</div>
            <div class="grid-item" id="CaseCheckUncheck"> <input type="checkbox" name="caseSelect[]" id="caseSelect[]" value="{{ $daily->hearingcode . '::' . $arr_index }}"> </div>
            <div class="grid-item">{{ $daily->applicationid }}</div>
            <div class="grid-item">
              <select class="form-control" name="caseStatus[]" id="caseStatus[]" required style="height:34px" data-parsley-trigger='focus'  disabled="true">
              @foreach($m_status as $cStatus)
                 @if ( $daily->casestatus == $cStatus->statuscode)
                   <option value="{{ $cStatus->statuscode }}" selected="true">{{ $cStatus->statusname }}</option>
                 @else
                   <option value="{{ $cStatus->statuscode }}">{{ $cStatus->statusname }}</option>
                @endif
              @endforeach
              </select>
            </div>

            <?php
             $i++;
             $arr_index++;
            ?>
          @endforeach

        </div>
        </div>  -->


        <div class="" style="height:70px;" >
          <table id="my1"   class=" table table-striped  table order-list table-responsive"  border="1"  >
            <thead >
              <tr style="color:orange" >
                <!--  <td>Sr.no</td> -->
           <!--    <td class="grid-item" width="15%">Sr.No.</td> -->
              <td class="grid-item" width="25%">Select Case(s) <br> <input type="checkbox" id="toggleCheckbox"> <label style="color:blue;"> Toggle All </label></td >
              <td class="grid-item" width="35%">Case Number </td>
              <td class="grid-item"  width="25%">Present Status </td>

            </tr>
          </thead>
        </table>
      </div>
      <div class="ext1">
         <table id="myTable1" class=" table table-striped  table order-list table-responsive"  border="1" >
          <tbody id="results3" >

          </tbody>
          </table>
         </div>


        </td>
        </tr>

        <tr style="color:green;">
        <td colspan="2"> <label for="natureOfDisposal" > Nature of Disposal (For selected cases) <span class="mandatory">*</span>  </label> </td>
        <td colspan="2">
              <select class="form-control" name="natureOfDisposal" id="natureOfDisposal" required style="height:34px" data-parsley-trigger='focus'>
              <option style="color:red;" value=""> Select Nature of Disposal </option>
              @foreach($m_status as $cStatus)
                <option style="color:green;" value="{{ $cStatus->statuscode }}">{{ $cStatus->statusname }}</option>
              @endforeach
              </select>
        </td>
        </tr>

        <tr>
        <td> <label for="postAfterPeriod" > Post after period  </label> </td>
        <td colspan="3"> <input  Type="number" name="postAfterPeriod" id="postAfterPeriod" size="5" maxlength="3" value="">
                         <input  type="radio" name="dwm" id="dwm" value="d" checked > <label > Days </label>
                         <input  type="radio" name="dwm" id="dwm" value="w"> <label > Weeks </label>
                         <input  type="radio" name="dwm" id="dwm" value="m"> <label style=";"> Months </label>
        </td>
        </tr>

        <tr>
        <td> <label for="nextHearingDate" > Next Hearing Date </label> </td>
        <td> <input class="form-control" type="text" name="nextHearingDate" id="nextHearingDate" size="10" maxlength="10"> </td>
        <td> <label for="bench" > Next Hearing Bench </label> </td>
        <td>
           <select class="form-control" name="nextBenchCode" id="nextBenchCode">
           <option value="" selected="true">Select Bench </option>
           @foreach($m_bench as $bench)
              <option value="{{ $bench->benchcode }}">{{ $bench->judgeshortname }} ({{ $bench->benchtypename }} )</option>
           @endforeach
           </select>
        </td>
        </tr>

        <tr>
        <!-- <td> <label for="causeListType" > Cause List Type </label>  </td>
        <td>
           <select class="form-control" name="nextCauseListType" id="nextCauseListType" style="height:34px">
           <option value=""> Cause List Type </option>
           @foreach($m_causelisttype as $causelisttype)
              <option value="{{ $causelisttype->causelisttypecode }}">{{ $causelisttype->causelistdesc }} </option>
           @endforeach
           </select>
        </td> -->
        <td> <label for="postedFor" > Posted For </label>  </td>
        <td>
           <select class="form-control" name="postedFor" id="postedFor"  style="height:34px">
           <option value="">Select Posted For </option>
           @foreach($m_purpose as $purpose)
              <option value="{{ $purpose->purposecode }}">{{ $purpose->listpurpose }} </option>
           @endforeach
           </select>
        </td>

        </tr>

        <tr>
        <td colspan="4">
        <div class="text-center">

                <button type="submit" name="saveButton" id="saveButton" class="btn btn-primary"> Save </button>
               <a class="btn btn-warning" href="dashboard"> Close </a>
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

<script src="js/chp/bulkproceedings.js"></script>


@endsection
