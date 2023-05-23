@extends('layout.mainlayout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <style type="text/css">
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
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'>Error List</h4>
          </div>
          <div class="modal-body">

            <div class = "alert alert-danger">
              <ol  id="errorlist">

              </ol>
            </div>

          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-status">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'>Update Status</h4>
          </div>
          <div class="modal-body">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <input id="modal_appl_id" type="hidden" name="modal_appl_id" value="">
            <input id="modal_srno" type="hidden" name="modal_srno" value="">
           <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                   <label>Status</label>
                    <select class="form-control number zero" id="iastatus" name="iastatus"  data-parsley-required
                    >
                      @foreach($Status as $statuses)
                      <option value="{{$statuses->statuscode}}">{{$statuses->statusname}}</option>
                      @endforeach
                    </select>
                </div>
              </div>
           </div>

          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-primary" id="saveIAStatus">SAVE</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">IA/ Document Prayer</a></li>
        <li><a href="#tab_2" data-toggle="tab">Application Hearing History </a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <form role="form" id="applicationStatusForm" action="IADocument.store" data-parsley-validate>
            <div class="panel  panel-primary">
              <div class="panel panel-heading">
                <h7 >Details of Application</h7>
              </div>
              <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
              <div class="panel panel-body">
                <div class="row">
                  <div class="col-md-4">

                    <div class="form-group">
                      <label>Type of Application<span class="mandatory">*</span></label>
                      <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-trigger='change' data-parsley-errors-container="#modlerror1" data-parsley-required data-parsley-required-message="Select Application Type">
                        <option value="">Select Application Type</option>
                        @foreach($applicationType as $applType)
                        <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                        @endforeach
                        </select> <span id="modlerror1"></span>
                      </div>

                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Application No<span class="mandatory">*</span></label>
                        <div class="input-group date">
                          <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" data-parsley-errors-container="#modlerror" data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='20'>
                          <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                            <i class="fa fa-search"></i>
                          </div>

                        </div>
                        <span id="modlerror"></span>
                      </div>
                    </div>




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
                </div>
                <div class="row">
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
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject<span class="mandatory">*</span></label>
                      <textarea class="form-control" name='applnSubject' id="applnSubject"  readonly>
                      </textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel  panel-primary">
              <div class="panel panel-heading">
                <h7 >Details of Document</h7>
              </div>

              <div class="panel panel-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Document Type<span class="mandatory">*</span></label>
                      <select name="documentTypeCode" id="documentTypeCode" class="form-control" data-parsley-required data-parsley-required-message="Select Document Type"><option value="" >Select Document Type</option>
                      @foreach($documenttype as $doctype)
                      <option value="{{$doctype->documenttypecode.'-'.$doctype->lsla}}">{{$doctype->documentname}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Filling Date<span class="mandatory">*</span></label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="IAFillingDate" class="form-control pull-right datepicker" id="IAFillingDate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Filling Date Allows only digits" value=""  data-parsley-required-message="Enter Filling Date" value="" data-parsley-errors-container="#error3">
                    </div>
                    <span id="error3"></span>
                  </div>
                </div>
                <div class="col-md-4" id="naturediv" style="display: none" >
                  <div class="form-group">
                    <label>IA Nature<span class="mandatory">*</span></label>
                    <select  class="form-control" id="IANatureCode"name="IANatureCode" data-parsley-required-message='Select Nature'>
                      <option value="">Select</option>
                      @foreach($IANature as $nature)
                      <option value="{{$nature->ianaturecode}}">{{$nature->ianaturedesc}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>



                <input type="hidden" class="form-control pull-right" id="IASrNo" name="IASrNo" >
                <input type="hidden" class="form-control pull-right" id="documentno" name="documentno" >

                <!-- /.col -->
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Prayer/Content<span class="mandatory">*</span></label>
                    <textarea class="form-control  zero" id="IAPrayer" name="IAPrayer" type="text" data-parsley-required  data-parsley-required-message="Enter IAPrayer" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid IAPrayer" data-parsley-trigger='keypress'></textarea>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Filled By<span class="mandatory">*</span></label>
                    <select class="form-control" name='filledby' id='filledby' data-parsley-trigger='change' data-parsley-required data-parsley-required-message='Select Filled By'>
                      <option value=''>Select Filled By</option>
                      <option value='A'>Applicant</option>
                      <option value='R'>Respondant</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Name<span class="mandatory">*</span></label>
                    <select class="form-control" name='filledbyname' id='filledbyname' data-parsley-required data-parsley-required-message='Select Name'>
                      <option value=''>Select  Name</option>

                    </select>
                  </div>
                </div>
                <input class="form-control number zero" id="IANo" name="IANo" type="hidden">

              </div>
              <div class='row'>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Registered On</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="IARegistrationDate" class="form-control pull-right datepicker" id="IARegistrationDate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Registered On Allows only digits"   data-parsley-required-message="Enter Registered On"  data-parsley-errors-container="#error4">
                    </div>
                    <span id="error4"></span>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Start Page<span class="mandatory">*</span></label>
                    <input class="form-control number zero" id="startpage" name="startpage" type="text" data-parsley-required data-parsley-required-message="Enter Start No." data-parsley-trigger='keypress' >
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>End Page<span class="mandatory">*</span></label>
                    <input class="form-control number zero" id="endpage" name="endpage" type="text" data-parsley-required data-parsley-required-message="Enter End No." data-parsley-trigger='keypress' >
                  </div>
                </div>
              </div>
              <br>
              <div class="row"  style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
                  <input type="hidden" name="sbmt_ia" id="sbmt_ia" value="A">
                  <input type="button" id="saveIA" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div>
              <br><br>
              <div class="row">
                <table id="myTable" class="table table-bordered table-striped  table order-list" style="width:100%;" >
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
            <!-- <div class="row">
              <div class="panel  panel-primary">
                <div class="panel panel-heading">
                  <h7 >Details of Index</h7>
                </div>

                <div class="panel panel-body">
                  <div class="row">
                    <table id="example1" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                      <thead >
                        <tr style="background-color: #3c8dbc;color:#fff" >
                          <td >Sr.no</td>
                          <td>Document Name</td>
                          <td>Start Page</td>
                          <td>End Page</td>
                        </tr>
                      </thead>
                      <tbody id="results2">

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> -->

            <div class="row">
              <div class="col-md-10"></div>
              <div class="col-md-2" style="float: right;">

                <a href="#" class="btn btn-md btn-primary btnNext">Next</a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="tab-pane" id="tab_2">
      <div class="panel panel-primary">
        <div class="panel panel-heading">Details Of Case (<span id="new_appl_id"></span>)</div>
        <div class="panel panel-body">
          <form role="form" id="dailyHearingForm" action="storeDailyHearing" data-parsley-validate>
            <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
            <input name="applicationId" id="application_id" type="hidden">
            <input name="hearingCode" id="hearingCode" type="hidden">
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
                  <select name="benchCode" id="benchCode" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Bench Type</option>
                  @foreach($Benches as $bench)
                  <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Bench <span class="mandatory">*</span></label>
                <select name="benchJudge" id="benchJudge" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA">
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
              <label>Posted For<span class="mandatory">*</span></label>
              <select class="form-control" name="postedfor" type="text" id="postedfor" data-parsley-required data-parsley-required-message="Select Posted For">><option value="" >Select Posted For</option>
              @foreach($purpose as $postedfor)
              <option value="{{$postedfor->purposecode}}">{{$postedfor->listpurpose}}</option>
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
          <label>Court order/Direction<span class="mandatory">*</span></label>
          <textarea class="form-control" name="courtDirection" id="courtDirection" data-parsley-required  data-parsley-required-message="Enter Court Direction" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Court Direction" data-parsley-trigger='keypress'></textarea>
        </div>
      </div>
    </div>

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Remarks</label>
      <textarea class="form-control" name="caseRemarks" id="caseRemarks"  data-parsley-required-message="Enter Court Direction" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Court Direction" data-parsley-trigger='keypress'></textarea>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Office Note/Action Taken<span class="mandatory">*</span></label><br>
      <textarea class="form-control" name="officenote" id="officenote" data-parsley-required  data-parsley-required-message="Enter Office Note" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Office Note" data-parsley-trigger='keypress'></textarea>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Order Passed <span class="mandatory">*</span></label>
      <select class="form-control" name="ordertypecode"  id="ordertypecode" data-parsley-required data-parsley-required-message="Select Order Passed">
        <option value=''>Select Order Passed</option>
        @foreach($ordertype as $order)
        <option value="{{$order->ordertypecode}}">{{$order->ordertypedesc}}</option>
        @endforeach
      </select>

    </div>
  </div>
</div>
<div class="row">
<div class="col-md-4">
    <div class="form-group">
      <label>Application Status<span class="mandatory">*</span></label>
      <select class="form-control" name="applStatus"  id="applStatus" data-parsley-required data-parsley-required-message="Select ApplicationStatus">
        <option value=''>Select Application Status</option>
        @foreach($Status as $statuses)

        <?php if($statuses->display=='Y')
        {?>
        <option value="{{$statuses->statuscode}}" selected>{{$statuses->statusname}}</option>
        <?php }else{?>
        <option value="{{$statuses->statuscode}}">{{$statuses->statusname}}</option>
        <?php }?>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-4" style="display: none" id="disposeddatediv">
    <div class="form-group">
      <label>Disposed Date<span class="mandatory">*</span></label>
      <div class="input-group date">
        <div class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </div>
        <input type="text" name="disposedDate" class="form-control pull-right datepicker" id="disposedDate"  value="" >
      </div>
      <!-- <span id="error7"></span> -->
    </div>

  </div>
  <div class="col-md-4" id="nexthearingdiv">
        <div class="form-group">
          <label>Next Hearing is?</label><br>
          <label class="radio-inline">


            <input type="checkbox" name="isnexthearing" id="isnexthearing" value="Y" data-parsley-trigger='keypress'>Yes

          </label>
        </div>
      </div>
</div>
<div class='row'>
<table id="myTable2" class="table table-bordered table-striped  table order-list" style="width:100%;display: none" >
        <thead >
          <tr style="background-color: #3c8dbc;color:#fff;" >
           <!--  <td>Sr.no</td> -->
            <td>Document Type</td>
          <td>IA Document No.</td >
          <td>IA reason for</td>
          <td>Filling Date</td>
          <td>Registered On</td>
          <td>Status</td>
        </tr>
      </thead>
      <tbody id="results3" >

      </tbody>
    </table>
</div>
<br>
<div class="row">
  <div class="panel panel-primary">
    <div class="panel panel-heading hearingdiv" style="display: none;">Hearing Details</div>
    <div class="panel panel-body">
      <div class="row nexthrdiv" style="display:none">
        <div class="col-md-4">
          <div class="form-group">
            <label>Next Hearing Date<span class="mandatory">*</span></label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="nextHrDate" class="form-control pull-right" id="nextHrDate"   data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-pattern-message="Hearing Date Allows only digits"  data-parsley-required-message="Enter Hearing Date" value="" data-parsley-errors-container="#error8">
            </div>
            <span id="error8"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Next Bench Type<span class="mandatory">*</span></label>
            <select name="nextBench" id="nextBench" class="form-control"  data-parsley-required-message="Select Bench"><option value="" >Select Bench Type</option>
            @foreach($Benches as $bench)
            <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Next Bench <span class="mandatory">*</span></label>
          <select name="nextbenchJudge" id="nextbenchJudge" class="form-control" data-parsley-required-message="Select Pending IA"><option value="" >Select Bench</option>
           @foreach($benchjudge as $bench)
                  <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
                  @endforeach
        </select>
      </div>
    </div>
  </div>
    <div class="row nexthrdiv"  style="display:none">
    <div class="col-md-4">
      <div class="form-group">
        <label>Next Listing Posted For<span class="mandatory">*</span></label>
        <select name="nextPostfor" id="nextPostfor" class="form-control"  data-parsley-required-message="Select  Posted For"><option value="" >Select  Posted For</option>
        @foreach($purpose as $postedfor)
        <option value="{{$postedfor->purposecode}}">{{$postedfor->listpurpose}}</option>
        @endforeach
      </select>
    </div>

  </div>
</div>
<br>

<div class="row"  style="float: right;" id="add_apl_div">
<div class="col-sm-12 text-center">
<input type="hidden" name="sbmt_da" id="sbmt_da" value="A">
<input type="button" id="saveDailyHearing" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
</div>
</div>
<br><br>
<div class="row" id="applcant_div">
<table id="example2" class="table table-bordered table-striped applicant-list" >
<thead style="background-color: #3c8dbc;color:#fff">
  <tr>
    <td  class="col-sm-1">Hearing Date</td>
    <td  class="col-sm-1">Posted for</td>
    <td  class="col-sm-1">Court Direction</td>
    <td class="col-md-2">Action Taken</td>
    <td class="col-md-2">NextHearing date</td>

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
<script src="js/casemanagement/applicationstatus.js"></script>
</section>
@endsection
