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
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">IA/ Document Prayer</a></li>
        <li><a href="#tab_2" data-toggle="tab">Case Hearing History </a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <form role="form" id="caseForm" action="" data-parsley-validate>
            <div class="panel  panel-primary">
              <div class="panel panel-heading">
                <h7 >Details of Document</h7>
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
                    <label>Filling Date<span class="mandatory">*</span></label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="dateOfFilling" class="form-control pull-right datepicker" id="dateOfFilling"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Filling Date Allows only digits" value=""  data-parsley-required-message="Enter Filling Date" value="" data-parsley-errors-container="#error3">
                    </div>
                    <span id="error3"></span>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Document Type<span class="mandatory">*</span></label>
                      <select name="documntType" id="documntType" class="form-control" data-parsley-required data-parsley-required-message="Select Document Type"><option value="" >Select Document Type</option>
                      <option>Document one</option>
                      <option>Document Two</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IA Serial No.</label>
                    <input type="text" class="form-control pull-right" id="IASrNo" name="IASrNo" value="">
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <div class="row">
                <div class="col-md-4" >
                  <div class="form-group">
                    <label>IA Nature<span class="mandatory">*</span></label>
                    <select  class="form-control" id="IANature"name="IANature" >
                      <option>Select IA Nature</option>
                      <option>IA1</option>
                      <option>IA2</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <label>Registered On</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="registeredOn" class="form-control pull-right datepicker" id="registeredOn"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Registered On Allows only digits" value=""  data-parsley-required-message="Enter Registered On" value="" data-parsley-errors-container="#error4">
                  </div>
                  <span id="error4"></span>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IA Number</label>
                    <input class="form-control" id="IAno" name="IAno" type="text" value="">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Prayer<span class="mandatory">*</span></label>
                    <textarea class="form-control" id="prayer" name="prayer" type="text" value=""></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <table id="myTable" class="table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Document Type</td>
                    <td>IA Document No.</td >
                    <td>Filling Date</td>
                    <td>Registered On</td>
                    <td>Status</td>
                  </tr>
                </thead>
                <tbody id="results2">
                </tbody>
              </table>
            </div>
            <div class="row">
              <div class="col-md-10"></div>
              <div class="col-md-2" style="float: right;">
                <input type="hidden" name="sbmt_IADoc" id="sbmt_IADoc" value="A">
                <a href="#" class="btn btn-md btn-primary btnNext">Next</a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="tab-pane" id="tab_2">
      <div class="panel panel-primary">
        <div class="panel panel-heading">Details Of Case</div>
        <div class="panel panel-body">
          <form role="form" id="applicantForm" action="" data-parsley-validate>
            <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Document Type</label>
                  <select name="documntType" id="documntType" class="form-control" data-parsley-required data-parsley-required-message="Select Document Type"><option value="" >Select Document Type</option>
                  <option>Document one</option>
                  <option>Document Two</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Pending IA Document</label>
                <select name="pendingIA" id="pendingIA" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Pending IA</option>
                <option>Pending IA1</option>
                <option>Pending IA2</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label>Hearing Date<span class="mandatory">*</span></label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="registeredOn" class="form-control pull-right datepicker" id="registeredOn"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Registered On Allows only digits" value=""  data-parsley-required-message="EnterHearing Date" value="" data-parsley-errors-container="#error5">
            </div>
            <span id="error5"></span>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Bench<span class="mandatory">*</span></label>
              <select name="bench" id="bench" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Bench</option>
              <option>Bench 1</option>
              <option>Bench 2</option>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Posted For<span class="mandatory">*</span></label>
            <select class="form-control" name="postedfor" type="text" id="postedfor" data-parsley-required data-parsley-required-message="Select Gender">
              <option value="">Select Posted For</option>
              <option value="M">one</option>
              <option value="F">tow</option>
              <option value="T">three</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        {{--   <div class="col-md-4">
          <div class="form-group">
            <label>Name Of Relation</label>
            <input class="form-control" name="relationName" type="text" id="relationName">
          </div>
        </div> --}}
        <div class="col-md-12">
          <div class="form-group">
            <label>Court order/Direction<span class="mandatory">*</span></label>
            <textarea class="form-control" name="direction" id="direction"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Remarks</label>
            <textarea class="form-control" name="remark" id="remark"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label>Order Passed Date<span class="mandatory">*</span></label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" name="orderDate" class="form-control pull-right datepicker" id="orderDate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Registered On Allows only digits" value=""  data-parsley-required-message="Enter Order Passed Date" value="" data-parsley-errors-container="#error6">
          </div>
          <span id="error6"></span>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Application Status<span class="mandatory">*</span></label>
            <select class="form-control" name="postedfor" type="text" id="postedfor" data-parsley-required data-parsley-required-message="Select Gender">
              <option value="">Select Posted For</option>
              <option value="M">Disposed</option>
              <option value="F">Transfer</option>
              <option value="T">Dismissed</option>
              <option value="T">Dormant</option>
              <option value="T">Sine Die</option>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <label>Disposed Date<span class="mandatory">*</span></label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" name="orderDate" class="form-control pull-right datepicker" id="orderDate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Disposed Date Allows only digits" value=""  data-parsley-required-message="Enter Disposed Date" value="" data-parsley-errors-container="#error7">
          </div>
          <span id="error7"></span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Office Note<span class="mandatory">*</span></label><br>
            <textarea class="form-control" name="officenote" id="officenote"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label>Next Hearing Date<span class="mandatory">*</span></label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" name="nextHrDate" class="form-control pull-right datepicker" id="nextHrDate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Hearing Date Allows only digits" value=""  data-parsley-required-message="Enter Hearing Date" value="" data-parsley-errors-container="#error8">
          </div>
          <span id="error8"></span>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Next Bench<span class="mandatory">*</span></label>
            <select name="bench" id="bench" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Next Bench</option>
            <option>Next Bench 1</option>
            <option>Next Bench 2</option>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Next Listing Posted For<span class="mandatory">*</span></label>
          <select name="bench" id="bench" class="form-control" data-parsley-required data-parsley-required-message="Select  Posted For"><option value="" >Select  Posted For</option>
          <option> Posted For 1</option>
          <option> Posted For 2</option>
        </select>
      </div>
    </div>
  </div>
  <div class="row"  style="float: right;" id="add_apl_div">
    <div class="col-sm-12 text-center">
      <input type="hidden" name="sbmt_applicant" id="sbmt_applicant" value="A">
      <input type="button" id="saveApplicant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Add List">
      <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
    </div>
  </div>
  <br><br>
  <br>
  <div class="row" id="applcant_div">
    <table id="example2" class="table table-bordered table-striped applicant-list" >
      <thead style="background-color: #3c8dbc;color:#fff">
        <tr>
          <td  class="col-sm-1">Hearing Date</td>
          <td class="col-md-2">Direction</td>
          <td class="col-md-2">Application Status</td>
          <td class="col-md-2">Next Hearing</td>
        </tr>
      </thead>
      <tbody id="results">
        <tr>
        </tr>
        <tr>
        </tr>
      </tbody>
    </table>
  </div><br>
  <div class="row">
    <div class=" col-md-1" style="float:left;">
      <a  class="btn btn-md btn-primary btnPrevious">Previous</a>
    </div>
    <div class="col-md-10"></div>
  </div>
</form>
</div>
</div>
</div>
</div>
<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>

</section>
@endsection
