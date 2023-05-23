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
     <div class="modal fade" id="editmodal">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='edit_appl-title'></h4>
          </div>
          <div class="modal-body">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Type of Application<span class="mandatory">*</span></label>
                  <select class="form-control" name="edit_modal_type" id="edit_modal_type" >
                    @foreach($applicationType as $applType)

                    <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>

                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" value="" data-parsley-errors-container="#modlerror">
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="editSearch">
                      <i class="fa fa-search"></i>
                    </div>

                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
            </div>

          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <form role="form" id="connectedForm" action="ConnectedstoreData" data-parsley-validate>
      <div class="panel  panel-primary">
        <div class="panel panel-heading origndiv">
          <h7 >Original Application</h7>
        </div>
        <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
        <div class="panel panel-body">
          <div class="row origndiv">
            <div class="col-md-4">
              <div class="form-group">
                <input type='hidden' name='orignapplid' id='orignapplid' value='' >
                 <input type='hidden' name='conaplid' id='conaplid' value='' >
                   <input type='hidden' name='chkval' id='chkval' value='' >
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
                    <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='20'>
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Date Of Application<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="dateOfAppl" class="form-control pull-right datepicker" id="dateOfAppl"  value=""  readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="row origndiv">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Registration Date</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="applnRegDate" class="form-control pull-right datepicker" id="applnRegDate" value=""  readonly>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Application Category<span class="mandatory">*</span></label>
                  <select class="form-control" name="applCatName" id="applCatName" disabled>
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
                  <textarea class="form-control" name='applnSubject' id="applnSubject" readonly >
                  </textarea>
                </div>
              </div>

          </div>
          <div class="row">
                <div class="col-md-4">
                      <div class="form-group">
                        <label>Type</label>
                        <select name="connectedtype" id="connectedtype" class="form-control" data-parsley-required data-parsley-required-message="Select Type"><option value="" >Select  Type</option>
                        <option value='C/W'>Connected With</option>
                        <option value='A/W'>Along With</option>
                      </select>
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
                <select name="benchJudge" id="benchJudge" class="form-control" data-parsley-required data-parsley-required-message="Select Bench Code">
                  <option value=''>Select Bench</option>

                   @foreach($benchcode as $bench)
                  <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
                  @endforeach

              </select>
            </div>
          </div>

          </div>
          <div class='row'>
             <div class="col-md-4">
                    <div class="form-group">
                      <label>Hearing Date<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="hearingDate" id="hearingDate" class="form-control pull-right datepicker"  value=""  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Hearing Date Allows only digits" value="" data-parsley-required  data-parsley-required-message="Enter Hearing Date"  data-parsley-errors-container="#error6" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY" >
                      </div>
                      <span id="error6"></span>
                    </div>

                  </div>
                  <div class="col-md-4">
                  <div class="form-group">
                    <label>Order No<span class="mandatory">*</span></label>
                    <input class="form-control" name="orderNo" id="orderNo" type="text" value="" data-parsley-pattern="/^[a-zA-Z0-9.,-/ ()\n\r]+$/" data-parsley-pattern-message="Invalid Order No." data-parsley-trigger='keypress' data-parsley-required-message="Enter Order No" maxlength="60" data-parsley-required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Order Date<span class="mandatory">*</span></label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" name="orderDate" class="form-control pull-right datepicker" id="orderDate"  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Order Date Allows only digits" value="" data-parsley-required  data-parsley-required-message="Enter Order Date"  data-parsley-errors-container="#error7" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY"  >
                    </div>
                    <span id="error7"></span>
                  </div>
                </div>
                <div class="col-md-4" >
                  <div class="form-group">
                    <label>Reason For Connection<span class="mandatory">*</span></label>
                    <textarea class="form-control" name="reasonforconn" id="reasonforconn" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Reason Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Reason for Connection'></textarea>
                  </div>
                </div>
          </div>

        </div>
        <div class="panel  panel-primary origndiv1">
            <div class="panel panel-heading ">
              <h7 >Connected Application</h7>
            </div>
            <div class="panel panel-body">
              <div class="row origndiv">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Type of Application<span class="mandatory">*</span></label>
                    <select class="form-control" name="conapplTypeName" id="conapplTypeName"  data-parsley-trigger='change'>
                      <option value="">Select Application Type</option>
                      @foreach($applicationType as $applType)
                      <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                      @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Application No<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <input type="text" name="conapplicationId" class="form-control pull-right" id="conapplicationId" data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='20' data-parsley-errors-container='#applerror' >
                        <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="conapplSearch">
                          <i class="fa fa-search"></i>
                        </div>

                      </div>
                     <span id="applerror"></span>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Registration Date</label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="conapplnRegDate" class="form-control pull-right datepicker" id="conapplnRegDate"   data-parsley-required-message='Enter registration date'  readonly>
                      </div>

                    </div>

                  </div>
                </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Connected Application Start No<span class="mandatory">*</span></label></br>
                        <input type="number" class="form-control pull-right" id="conApplStartNo" name="conApplStartNo"  readonly>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Connected Application End No</label>
                        <input type="number" name="conApplEndNo" id="conApplEndNo" class="form-control"   readonly>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Connected Application Year</label>
                       </div>
                          <input type="text" name="conapplyear" id="conapplyear" class="form-control pull-right datepicker" readonly style="pointer-events: none;">
                    </div>


                </div>
              </div>
            </div>
              <div class="panel  panel-primary origndiv2">

                <div class="panel panel-body">




              <div class="row"  style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
                  <input type="hidden" name="sbmt_connected" id="sbmt_connected" value="A">
                  <input type="button" id="saveConnectedCase" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div>
              <br><br><br>
              <div class="row origndiv">
                <table id="myTable" class="table table-bordered table-striped order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>SrNo</td>
                    <td>Application</td>
                    <td>Connected Application</td>
                    <td></td>
                  </tr>
                </thead>
                <tbody id="results2">
                </tbody>
              </table>
            </div>
          </div>
      </form>
      <!-- /.tab-pane -->
      <script src="js/jquery.min.js"></script>
      <script src="js/casemanagement/connectedapplication.js"></script>
    </section>
    @endsection
