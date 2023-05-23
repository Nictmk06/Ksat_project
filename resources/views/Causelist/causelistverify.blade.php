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
  .divstyle
  {
  padding-top: 0px;
  padding-bottom: 0px;
  }
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

  <section class="content">
    <form role="form" id="cuaselistForm" action="{{ route('Causelistverify.store') }}" data-parsley-validate>
    <div class="panel  panel-primary">
      <div class="panel panel-heading origndiv">
        <h7 >Verify Causelist</h7>
      </div>
      <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>

      <div class="panel panel-body divstyle" >
        <input name='causelistcode' id='causelistcode' value='' type='hidden'>
        <input name='choice' id='choice' value='' type='hidden'>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Hearing Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="hearingDate" class="form-control pull-right datepicker" id="hearingDate"  data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter Hearing Date"  data-parsley-required data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Bench Type<span class="mandatory">*</span></label>
              <select name="benchCode" id="benchCode" class="form-control" data-parsley-required data-parsley-required-message="Select Bench Type"><option value="" >Select Bench Type</option>
              @foreach($Benches as $bench)
              <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Bench <span class="mandatory">*</span></label>
            <select name="benchJudge" id="benchJudge" class="form-control" data-parsley-required data-parsley-required-message="Select Bench">
              <option value=''>Select Bench</option>

              @foreach($benchjudge as $bench)
              <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

 <!--     <div class="row">

        <div class="col-md-4">
          <div class="form-group">
            <label>CauseList Type<span class="mandatory">*</span></label>
            <select name="causetypecode" id="causetypecode" class="form-control" data-parsley-required data-parsley-required-message="Select Causelist type"><option value="" >Select CauseList Type</option>
            @foreach($causelisttype as $cltype)
            <option value="{{$cltype->causelisttypecode}}">{{$cltype->causelistdesc}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>List No<span class="mandatory">*</span></label>
          <select class="form-control" name="listno" id="listno" data-parsley-required data-parsley-required-message="Select Court Hall">><option value="0" >Select List No</option>
          @foreach($list as $lists)
          <option value="{{$lists->listcode}}">{{$lists->listcode}}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label>Court Hall<span class="mandatory">*</span></label>
        <select class="form-control" name="courthall" id="courthall" data-parsley-required data-parsley-required-message="Select Court Hall">><option value="" >Select Court Hall</option>
        @foreach($CourtHalls as $courthall)
        <option value="{{$courthall->courthallno}}">{{$courthall->courthalldesc}}</option>
        @endforeach
      </select>
    </div>
  </div>   -->
 <div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Select Causelist <span class="mandatory">*</span></label>
      <select class="form-control" name="causelistfrm" id="causelistfrm" data-parsley-required data-parsley-required-message="Cause List from">><option value="" >Select Cause List</option>

    </select>
  </div>
</div>

 <div class="col-md-4">
      <div class="form-group">
      <label>C.H Time <span class="mandatory">*</span></label>
       <input name="causelisttime" id="causelisttime" class="form-control"
              data-parsley-required  data-parsley-required-message="Enter Court Hall Time" >
          </div>
     </div>
     </div>
<!--  style="display: none; -->
<div class="row">
 <div class="col-md-4 headersection">
    <div class="form-group">
      <label>Causelist Header   <span class="mandatory">*</span></label>
       <textarea type="text" name="clheader" class="form-control pull-right" id="clheader"  value="" data-parsley-pattern="/^[-@.\/,'()#+\w\s]*$/"  ></textarea>
    </div>
  </div>

   <div class="col-md-4 headersection">
    <div class="form-group">
      <label>Causelist Footer  <span class="mandatory">*</span></label>
       <textarea type="text" name="clfooter" class="form-control pull-right" id="clfooter"  value="" data-parsley-pattern="/^[-@.\/,'()#+\w\s]*$/"  ></textarea>
    </div>
  </div>

 <div class="col-md-3 headersection">
     <div class="form-group">
      <label>Causelist Note  <span class="mandatory">*</span></label>
       <textarea type="text" name="clnote" class="form-control pull-right" id="clnote"  value=""  data-parsley-pattern="/^[-@.\/',()#+\w\s]*$/" ></textarea>

    </div>
  </div>

 <div class="col-md-1 headersection">
     <div class="form-group">
        <br>  <br>
  <input type="button" id="save1" class="btn  btn-md btn-success" Style="width: 70px; " value="Save">
</div>
</div>
  </div>

 <div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Select Application <span class="mandatory">*</span></label>
      <select class="form-control" name="applselect" id="applselect" ><option value="0" >Select Application </option>

    </select>
  </div>
</div>
<div class="col-md-6">
    <div class="form-group">
      <label>Select Connected Application <span class="mandatory">*</span></label>
      <select class="form-control" name="conapplselect" id="conapplselect" ><option value="0" >Select Connected Appl</option>

    </select>
  </div>
</div>
</div>
<div class="row divstyle"  style="float: middle;" id="add_apl_div1">

 <div class="col-md-6 headersection">
    <div class="form-group">
      <label>Applicant Remarks  <span class="mandatory">*</span></label>
      <a href="#" id="getclremarks" > Get Remarks from previous Cause List</a>
 <!-- <input type="button" id="getclremarkss"  class="btn   btn-md btn-success" Style="width: 300px; " value="Get Remarks from previous Cause List ">  -->

       <textarea type="text"  name="appautoremarks" class="form-control pull-right" id="appautoremarks"  value="" data-parsley-pattern="/^[-@.\/,'&()#+\w\s]*$/" ></textarea>
    </div>
  </div>

   <div class="col-md-6 headersection">
    <div class="form-group">
      <label>Respondent Remarks  <span class="mandatory">*</span></label>
       <textarea type="text" name="resautoremarks" class="form-control pull-right" id="resautoremarks"  value="" data-parsley-pattern="/^[-@.\/,'&()#+\w\s]*$/"  ></textarea>
    </div>
  </div>
</div>

<div class="row divstyle"  style="float: middle;" id="add_apl_div2">
 <div class="col-md-6 headersection">
    <div class="form-group">
      <label>Applicant Additional Remarks  <span class="mandatory">*</span></label>
       <textarea type="text" name="appuserremarks" class="form-control pull-right" id="appuserremarks"  value="" data-parsley-pattern="/^[-@.\/,'&()#+\w\s]*$/" ></textarea>
    </div>
  </div>

   <div class="col-md-6 headersection">
    <div class="form-group">
      <label>Respondent Additional Remarks  <span class="mandatory">*</span></label>
       <textarea type="text" name="resuserremarks" class="form-control pull-right" id="resuserremarks"  value="" data-parsley-pattern="/^[-@.\/,'&()#+\w\s]*$/"  ></textarea>
    </div>
  </div>


</div>
<br>
<div class="row divstyle"  style="float: middle;" id="add_apl_div">
<div class="col-sm-12 text-center">

<input type="button" id="save"  class="btn   btn-md btn-success" Style="width: 100px; " value="Save ">

<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">



</div>
</div>





<!--<div class="panel  panel-primary">
 <div class="panel panel-heading origndiv">
//<h7 >Causelist Applications </h7>
</div>
<div class='panel panel-body divstyle'  overflow: auto;>
 -->



<!-- <div class='col-md-2' id="reorderbtn" style="display: none;padding-left: 0px;">
  <input type='button' name='rearange' id='rearange' class="form-control btn btn-warning" value='Rearrange SRNo'>
</div>
-->
<div class="row">
<table id="myTable1" class="table table-bordered table-striped  table order-list table-responsive"  >
  <thead >
    <tr style="background-color: #3c8dbc;color:#fff;" >
      <!--  <td>Sr.no</td> -->
      <td class="col-md-2">Serial No.</td>
    <td class="col-md-4">Posted for</td >
    <td class="col-md-2">Application </td>
    <td class="col-md-2">Connected Appl </td>
    <td class="col-md-2">Posted from</td>
    <td class="col-md-2">IA - Connected</td>
    <td id='delete' style="display: none;"></td>
  </tr>
</thead>
<tbody id="results3" >

</tbody>
</table>
</div>
</div>

</div>

</form>
<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>
<script src="js/casemanagement/causelistverify.js"></script>
</section>
</div>
@endsection
