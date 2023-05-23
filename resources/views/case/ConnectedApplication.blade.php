
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
  <section class="content">
    <form role="form" id="caseForm" action="" data-parsley-validate>
      <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Details of Connected Case</h7>
        </div>
        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
        <div class="panel panel-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Type of Application<span class="mandatory">*</span></label>
                <select class="form-control" name="applTypeName" id="applTypeName" data-parsley-required data-parsley-required-message="Select Application Type">
                  <option value="">Select Applcation Type</option>
                  @foreach($applicationType as $applType)
                  <option value="{{$applType->applTypeCode.'-'.$applType->applTypeShort}}">{{$applType->applTypeDesc.'-'.$applType->applTypeShort}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Application Id<span class="mandatory">*</span></label></br>
                <input type="text" class="form-control pull-right" id="applicationId" name="applicationId"    data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-pattern-message="Applcation Id allows only Alphanumeric" data-parsley-pattern-message="Applcation Id allows only Alphanumeric" value=""
                data-parsley-required  data-parsley-required-message="Enter Applcation Id">
              </div>
            </div>
            <div class="col-md-4">
              <label>Date Of Application<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="dateOfAppl" class="form-control pull-right datepicker" id="dateOfAppl"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Date Of Application" data-parsley-lt="#applnRegDate" data-parsley-lt-message="Date Of Application  Should be less than Date Of Registration" value="" data-parsley-errors-container="#error3">
              </div>
              <span id="error3"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label>Registration Date</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="applnRegDate" class="form-control pull-right datepicker" id="applnRegDate" value=""  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits" value="" data-parsley-required  data-parsley-required-message="Enter Registration Date" data-parsley-gt-message="Registration Date. Should be greater than Date Of Applciation" data-parsley-gt="#dateOfAppl" data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Application Category<span class="mandatory">*</span></label>
                <select class="form-control" name="applCatName" id="applCatName" data-parsley-required  data-parsley-required-message="Select Application Category" >
                  <option value="">Select Applcation Category</option>
                  @foreach($applCategory as $applCat)
                  <option value="{{$applCat->applCatCode}}">{{$applCat->applCatName}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Subject<span class="mandatory">*</span></label>
                <textarea class="form-control" name='applnSubject' id="applnSubject" data-parsley-required  data-parsley-required-message="Enter Subject" >
                </textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Type</label>
                <select name="type" id="type" class="form-control" data-parsley-required data-parsley-required-message="Select Type"><option value="" >Select  Type</option>
                <option>Type one</option>
                <option>Type Two</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label>Hearing Date<span class="mandatory">*</span></label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="hearingDate" class="form-control pull-right datepicker" id="hearingDate" value=""   data-parsley-errors-container="#error6">
            </div>
            <span id="error6"></span>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Bench<span class="mandatory">*</span></label>
              <select name="bench" id="bench" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Bench</option>
              <option>Bench 1</option>
              <option>Bench 2</option>
            </select>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12" >
          <div class="form-group">
            <label>Court order/Direction</label>
            <textarea class="form-control" name="direction" id="direction"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" >
          <div class="form-group">
            <label>Reason For Connection<span class="mandatory">*</span></label>
            <textarea class="form-control" name="direction" id="direction"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label>Order Date<span class="mandatory">*</span></label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="orderDate" class="form-control pull-right datepicker" id="orderDate" value=""  data-parsley-lt="#dateOfAppl" data-parsley-lt-message="Order Date  Should be less than Date Of Application" data-parsley-errors-container="#error7">
            </div>
            <span id="error7"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Order No</label>
            <input class="form-control" name="orderNo" id="orderNo" type="text" value="" daa>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>*Connected Application ID<span class="mandatory">*</span></label>
            <input class="form-control" name="conApplId" id="conApplId" type="text" value="" >
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label>Registration Date</label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" name="applnRegDate" class="form-control pull-right datepicker" id="applnRegDate" value=""  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits" value="" data-parsley-required  data-parsley-required-message="Enter Registration Date" data-parsley-gt-message="Registration Date. Should be greater than Date Of Applciation" data-parsley-gt="#dateOfAppl" data-parsley-errors-container="#error5">
          </div>
          <span id="error5"></span>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Connected Application Start No<span class="mandatory">*</span></label></br>
            <input type="number" class="form-control pull-right" id="conApplStartNo" name="conApplStartNo" data-parsley-lt="#conApplEndNo" data-parsley-lt-message="Start No.  Should be less than End No."  data-parsley-maxlength="5" data-parsley-maxlength-message="Application Start No Allows only 5 digits"   data-parsley-pattern="/^[0-9]+$/" data-parsley-pattern-message="Application Start No allows only numbers" data-parsley-pattern-message="Application End No allows only numbers" value=""
            data-parsley-required  data-parsley-required-message="Enter Applcation Start No">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Connected Application End No</label>
            <input type="number" name="conApplEndNo" id="conApplEndNo" class="form-control" data-parsley-gt="#conApplStartNo" data-parsley-gt-message="End No. Should be greater than Start No."   data-parsley-pattern="/^[0-9]+$/" data-parsley-maxlength="5" data-parsley-maxlength-message="Application End No Allows only 5 digits" data-parsley-pattern-message="Application End No allows only numbers" data-parsley-pattern-message="Application End No allows only numbers"  value=""
            data-parsley-required  data-parsley-required-message="Enter Applcation End No">
          </div>
        </div>
      </div>
      <div class="row"  style="float: right;" id="add_apl_div">
        <div class="col-sm-12 text-center">
          <input type="hidden" name="sbmt_connected" id="sbmt_connected" value="A">
          <input type="button" id="saveConnectedCase" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Add List">
          <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
        </div>
      </div>
      <br><br><br>
      <div class="row">
        <table id="myTable" class="table order-list" style="width:100%;" >
          <thead >
            <tr style="background-color: #3c8dbc;color:#fff" >
              <td>SrNo</td>
            <td>Order Date</td >
            <td>Applcation Start No</td>
            <td>Application Last No</td>

          </tr>
        </thead>
        <tbody id="results2">
        </tbody>
      </table>
    </div>
  </div>
</div>
</form>
<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>
</section>
@endsection
