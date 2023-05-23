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

    <form role="form" id="cuaselistForm" action="{{ route('AdminController.store') }}" data-parsley-validate>

     <div class="modal fade" id="modal-serial">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'>Change Serial</h4>
          </div>
          <div class="modal-body">
            <div class='row'>
               <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
              <input type='hidden' name='causelistcode1' id='causelistcode1'>
              <div class='col-md-12'>
                <label>Current Serial No.<span class="mandatory">*</span></label>
                <div class='form-group'>
                  <input type="text" name="curserialno" class="form-control number zero" id="curserialno" value=""   data-parsley-pattern="/^[0-9]*$/" maxlength='3'>
                </div>
              </div>
              <div class='col-md-12'>
                <label>Move to <span class="mandatory">*</span></label>
                <div class='form-group'>
                  <input type="text" name="movetosrno" class="form-control number zero" id="movetosrno" value=""  data-parsley-required-message="Enter Move to." data-parsley-pattern="/^[0-9]*$/" maxlength='3'>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-md btn-primary" id='saveCausesrno' >SAVE</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>


    {{--  <div class="modal fade" id="modal-default">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'>Hearing List</h4>
          </div>
          <div class="modal-body">
            <div class='row'>
              <table id="myTable2" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                <thead >
                  <tr style="background-color: #3c8dbc;color:#fff;" >
                    <!--  <td>Sr.no</td> -->
                    <td><input type='checkbox' id="ckbCheckAll"></td>
                  <td>Application Id</td >
                  <td>Hearing Date</td>
                  <td>Posted for</td>
                </tr>
              </thead>
              <tbody id="results3" >

              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">

          <button type="button" class="btn btn-primary" id="saveCauselistappl">Save</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div> --}}



    <div class="panel  panel-primary">

      <div class="panel panel-heading origndiv">
        <h7 >Prepare Causelist</h7>
      </div>
      <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
      <div class="panel panel-body divstyle" >

        <input name='causelistcode' id='causelistcode' value='' type='hidden'>
        <input name='iaflag' id='iaflag' value='N' type='hidden'>
        <input name="applid" id="applid" type="hidden">
        <input name="purposecodeold" id="purposecodeold" type="hidden">

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
      <div class="row">

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
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Cause List from <span class="mandatory">*</span></label>
      <select class="form-control" name="causelistfrm" id="causelistfrm" data-parsley-required data-parsley-required-message="Cause List from">><option value="" >Select Cause List</option>
      <option value='Fresh'>Fresh</option>
      <option value='Dated'>Dated</option>
      <option value='Other'>Other</option>
    </select>
  </div>
</div>
<div class="col-md-4 applDiv" style="display: none;">
  <div class="form-group">
    <label>Type of Application<span class="mandatory">*</span></label>
    <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-trigger='change' data-parsley-required-message="Select Application Type" data-parsley-trigger='change' data-parsley-errors-container='#modlerror1'>
      <option value="">Select Application Type</option>
      @foreach($applicationType as $applType)
      <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
      @endforeach
      </select> <span id="modlerror1"></span>
    </div>

  </div>
  <div class="col-md-4 applDiv" style="display: none;">
    <div class="form-group">
      <label>Application No<span class="mandatory">*</span></label>
      <div class="input-group date">
        <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value=""  data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='20' data-parsley-errors-container="#modlerror">
        <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
          <i class="fa fa-search"></i>
        </div>

      </div>
      <span id="modlerror"></span>
    </div>
  </div>



</div>
<div class="row applDiv" style="display: none;">





  <div class="col-md-4"> <div class="form-group">
    <label>Date Of Application<span class="mandatory">*</span></label>
    <div class="input-group date">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input type="text" name="dateOfAppl" class="form-control pull-right " id="dateOfAppl"  value=""  readonly>
    </div>
  </div>
</div>
<div class="col-md-4">
  <div class="form-group">
    <label>Registration Date</label>
    <div class="input-group date">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
      <input type="text" name="applnRegDate" class="form-control pull-right " id="applnRegDate"   readonly>
    </div>

  </div>
</div>
<div class="col-md-4">
  <div class="form-group">
    <label>Application Category<span class="mandatory">*</span></label>
    <select class="form-control" name="applCatName" id="applCatName" disabled >
      <option value="">Select Applcation Category</option>
      @foreach($applCategory as $applCat)
      <option value="{{$applCat->applcatcode}}">{{$applCat->applcatname}}</option>
      @endforeach
    </select>
  </div>
</div>
</div>
<div class="row applDiv" style="display: none;">

<div class="col-md-4">
  <div class="form-group">
    <label>Subject<span class="mandatory">*</span></label>
    <textarea class="form-control" name='applnSubject' id="applnSubject"  readonly>
    </textarea>
  </div>
</div>
<div class="col-md-4">
  <div class="form-group" id='posteddiv'>
    <label>Posted For<span class="mandatory">*</span></label>
    <select class="form-control" name="postedfor" type="text" id="postedfor" data-parsley-required-message='Select Posted For'>><option value="" >Select Posted For</option>
    @foreach($purpose as $postedfor)
    <option value="{{$postedfor->purposecode}}">{{$postedfor->listpurpose}}</option>
    @endforeach
  </select>
</div>
</div>
</div>

<div class="panel  panel-primary">

<div class='panel panel-body divstyle'>
<div class="row" id="iadocumentdiv" style="display: none">
  <table id="myTable" class="table table-bordered table-striped  table order-list table-responsive" style="width:100%;" >
    <thead >
      <tr style="background-color: #3c8dbc;color:#fff" >
        <!--   <td>Sr.no</td> -->
        <td>Document Type</td>
      <td>IA Document No.</td >
      <td>IA reason for</td>
      <td>Filling Date</td>
      <td>Registered On</td>
      <td>Status</td>
    </tr>
  </thead>
  <tbody id="results2" style="">

  </tbody>
</table>
</div>
<div class='row' id="mytablediv" style="display: none;">
<table id="myTable2" class="table table-bordered table-striped  table order-list table-responsive" style="width:100%;" >
  <thead >
    <tr style="background-color: #3c8dbc;color:#fff;" >
      <!--  <td>Sr.no</td> -->
      <td><input type='checkbox' id="ckbCheckAll"></td>
    <td>Application Id</td >
    <td>Hearing Date</td>
    <td>Posted for</td>
  </tr>
</thead>
<tbody id="results5" >

</tbody>
</table>
</div>
</div>
<br><br>
<div class="row divstyle"  style="float: right;" id="add_apl_div">
<div class="col-sm-12 text-center">
<input type="hidden" name="causedate_val" id="causedate_val" value="A">
<input type="button" id="savecauseundate" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
</div>
</div><br><br>
<div class="panel  panel-primary">
<div class="panel panel-heading origndiv">
<h7 >Causelist Applications </h7>
</div>
<div class='panel panel-body divstyle'  overflow: auto;>
<div class="row">
<!--<div class='col-md-2' id="reorderbtn" style="display:none; padding-left: 0px;">
  <input type='button' name='rearange' id='rearange' class="form-control btn btn-warning" value="Rearrange SRNo">
</div>-->
<table id="myTable1" class="table table-bordered table-striped  table order-list table-responsive"  >
  <thead >
    <tr style="background-color: #3c8dbc;color:#fff;" >
      <!--  <td>Sr.no</td> -->
      <td class="col-md-4">Posted For</td>
    <td class="col-md-2">Application Id</td >
    <td class="col-md-2">CL Serial Number</td>
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
</div>
</form>
<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>
<script src="js/admin/preparecauselist.js"></script>
</section>
@endsection
