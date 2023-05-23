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
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  margin: 0;
  }
  <style>
  .text{
  white-space: pre-wrap;
  }
  </style>
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  <div id="appends" style="display: none">@extends('Application/caseData')</div>
  <div id="newcontent"></div>
  @include('flash-message')
  <!-- modal to add advocae -->
  <section class="content">
    <input type='hidden' name='flageFrAddr' id="flageFrAddr" value='E'>
    <div class="modal fade" id="modal-add-advocate">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <form role="form" id="advocateForm" data-parsley-validate>
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id='appl-title'>Add Advocate</h4>
            </div>
            <div class="modal-body">
              <div class='row'>
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <input type='hidden' name='typeOfadv' id='typeOfadv'>
                <div class='col-md-6'>
                  <label>Advocate name<span class="mandatory">*</span></label>
                  <div class='form-group'>
                    <input type="text" name="modal_adv_name" class="form-control" id="modal_adv_name" data-parsley-required data-parsley-required-message="Enter Advocate Name."
                    data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Advocate Name Accepts only 100 characters"  data-parsley-trigger='keypress' >
                  </div>

                </div>
                <div class='col-md-6'>
                  <label>Advocate Bar Reg No<span class="mandatory">*</span></label>
                  <div class='form-group'>
                    <input type="text" name="modal_adv_bar_reg_no" class="form-control" id="modal_adv_bar_reg_no" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No." data-parsley-pattern="^([a-zA-Z]{3})\/([0-9]{1,6})\/([0-9]{4})$" data-parsley-pattern-message="Please Enter Advocate Reg No in Correct Format" data-parsley-trigger='keypress'>
                  </div>

                </div>
              </div>
              <div class='row'>
                <div class='col-md-6'>
                  <label>Mobile No</label>
                  <div class='form-group'>
                    <input  name="modal_mobile_no" class="form-control" id="modal_mobile_no" type="number" maxlength="10" data-parsley-pattern="\d*" data-parsley-pattern-message="Invalid Mobile No."  data-parsley-required-message="Enter Mobile No." data-parsley-trigger='keypress' type="text" >
                  </div>

                </div>
                <div class='col-md-6'>
                  <label>Email Id</label>
                  <div class='form-group'>
                    <input type="email" name="modal_email_no" class="form-control" id="modal_email_no" value="" >
                  </div>

                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-md btn-primary" id='saveAdvocate' >SAVE</button>
            </div>
          </div>
        </form>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- modal to add designation to master table -->
    <div class="modal fade" id="modal-add-designation">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'>Add Designation</h4>
          </div>
          <div class="modal-body">
            <div class='row'>
              <input type='hidden' name='typeOfappl' id='typeOfappl'>
              <div class='col-md-12'>
                <label>Designation name<span class="mandatory">*</span></label>
                <div class='form-group'>
                  <input type="text" name="designame" class="form-control number zero" id="designame" value=""  data-parsley-required-message="Enter Designation Name." data-parsley-pattern="/^[a-zA-Z.-/ ]*$/" maxlength='100'>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-md btn-primary" id='saveDesignation' >SAVE</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-default">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'></h4>
          </div>
          <div class="modal-body">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Type of Application<span class="mandatory">*</span></label>
                  <select class="form-control" name="modl_appltype_name" id="modl_appltype_name"  >
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
                    <input type="text" name="modl_applno" class="form-control pull-right" id="modl_applno" value="" data-parsley-errors-container="#modlerror">
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div>

                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
            </div>
            <div class="row" id="displAppl1" style="display: none">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Application Date<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="modl_appldate" class="form-control pull-right" id="modl_appldate"  value="" readonly=""
                    >
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Registration Date<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="modl_regdate" class="form-control pull-right" id="modl_regdate"  value="" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="displAppl2" style="display: none">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Subject<span class="mandatory">*</span></label>

                  <textarea type="text" name="modl_subject" class="form-control pull-right" id="modl_subject"  value="" readonly></textarea>

                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Disposed Date<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="modl_disposedate" class="form-control pull-right" id="modl_disposedate"  value="" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveOtherAppl" disabled="true">Save</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#tab_1" data-toggle="tab">Application</a></li>
        <li><a href="#tab_2" data-toggle="tab">Receipt </a></li>
        <li><a href="#tab_3" data-toggle="tab">Applicant </a></li>
        <li><a href="#tab_4" data-toggle="tab">Respondant </a></li>
        <li><a href="#tab_5" data-toggle="tab">Application Index</a></li>
        <input type='hidden' id="canelid" value=''>
        <li style="float:right;" id="editApplication"> <input type="button"  id="" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Edit"></li>



      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <form role="form" id="caseForm" action="caseDataStore" data-parsley-validate>
            <div class="panel  panel-primary">
              <div class="panel panel-heading">
                <h7 >Details of Application</h7>
              </div>
              <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
              <input class="form-control" name="reviewApplId1" type="hidden" id="reviewApplId1">
              <input type='hidden' name='Originalapplid' class="Originalapplid" value=''>
              <div class="panel panel-body">

                <div class="row">
                  <div class="col-md-4">

                    <div class="form-group">

                      <label>Type of Application<span class="mandatory">*</span><span id="reviewAppl" style="color:red;"></span></label>
                      <select class="form-control" name="applTypeName" id="applTypeName" readonly data-parsley-required data-parsley-required-message="Select Application Type" data-parsley-trigger='change'>
                        <option value="">Select Application Type</option>
                        @foreach($applicationType as $applType)

                        <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort.'-'.$applType->feerequired}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>KSAT Act<span class="mandatory">*</span></label>
                      <select class="form-control" name="actName" disabled>
                        <option value="">Select Act</option>
                        @foreach($actDetails as $act)
                        <option value="{{$act->actcode}}" selected>{{$act->actname}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Section Name<span class="mandatory">*</span></label>
                      <select class="form-control" name="actSectionName" id='actSectionName'  readonly>
                        <option value="">Select Section Name</option>
                        @foreach($sectionDetails as $actsection)
                        <option value="{{$actsection->actsectioncode}}">{{$actsection->actsectionname}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Date Of Application<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="dateOfAppl" class="form-control pull-right datepicker" id="dateOfAppl" readonly value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Date Of Application"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
                      </div>
                      <span id="error3"></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Application Year:<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="applYear"  class="form-control pull-right datepicker1"  id="datepicker1" data-parsley-date-format="YYYY" readonly="" data-parsley-trigger='keypress' >
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Application Start No<span class="mandatory">*</span></label></br>
                      <input type="number" class="form-control pull-right zero number" id="applStartNo" name="applStartNo" readonly
                      data-parsley-required  data-parsley-required-message="Enter Applcation Start No" data-parsley-trigger='keypress'>
                    </div>
                  </div>

                </div><br>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Application End No<span class="mandatory">*</span></label>
                      <input type="number" name="applEndNo" id="applEndNo" class="form-control zero number" readonly
                      data-parsley-pattern="/^[0-9]+$/"
                      data-parsley-required  data-parsley-required-message="Enter Applcation End No" data-parsley-trigger='keypress'>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Registration Date<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="applnRegDate" class="form-control pull-right datepicker" id="applnRegDate" value=""  readonly data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits" value="" data-parsley-required  data-parsley-required-message="Enter Registration Date"  data-parsley-errors-container="#error5" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
                      </div>
                      <span id="error5"></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject Category<span class="mandatory">*</span></label>
                      <select class="form-control" name="applCatName" readonly id="applCatName" data-parsley-required  data-parsley-required-message="Select Application Category" data-parsley-trigger='keypress'>
                        <option value="" class="form-control">Select Applcation Category</option>
                        @foreach($applCategory as $applCat)

                        <option value="{{$applCat->applcatcode}}" class="form-control">{{$applCat->applcatname}}</option>

                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject<span class="mandatory">*</span></label>
                      <textarea class="form-control" name='applnSubject' id="applnSubject" readonly data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject'
                      ></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>No of Applicants.<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfAppl" id="noOfAppl" readonly type="number" id="noOfAppl" value="" data-parsley-required data-parsley-required-message="Enter No fo Applicants" data-parsley-minlength="1" data-parsley-minlength-message="No of Applicants Should have minimum 1 digit" data-parsley-maxlength="6" data-parsley-maxlength-message="No of Applicants Should have maximum 6 digit" data-parsley-trigger='keypress' >
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>No of Respondants<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfRes" type="number" id="noOfRes" readonly  value="" data-parsley-required data-parsley-required-message="Enter No of Respondants" data-parsley-minlength="1" data-parsley-minlength-message="No of Respondants Should have minimum 1 digit" data-parsley-maxlength="6" data-parsley-maxlength-message="No of Respondants Should have maximum 6 digit" data-parsley-trigger='keypress'>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading" >Order Against Which Application Is Made</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Order No</label>
                            <input class="form-control orderNo " name="orderNo" id="orderNo" type='' data-parsley-pattern="/^[a-zA-Z0-9.,-/ ()\n\r]+$/" data-parsley-pattern-message="Invalid Order No." data-parsley-trigger='keypress' data-parsley-required-message="Enter Order No" maxlength="60">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Order Date<span class="mandatory">*</span></label>
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" name="orderDate" class="form-control pull-right datepicker orderDate" id="orderDate" value=""  data-parsley-trigger='keypress' data-parsley-errors-container="#error20" data-parsley-required-message="Enter Order Date">
                            </div>
                            <span id="error20"></span>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Issued By</label>
                            <!-- -->
                            <div class="input-group date">

                              <input type="text" class="form-control pull-right applnIssuedBy" id="applnIssuedBy" name="applnIssuedBy" data-parsley-pattern="/^[a-zA-Z ]+$/" data-parsley-pattern-message="Issued By Accepts Only Characters."maxlength="100"  data-parsley-trigger='keypress' data-parsley-required-message="Enter Issued By" data-parsley-errors-container='#errorOrder'>
                              <div class="input-group-addon"style="color:#fff;background-color: #337ab7" id='addorder'>
                                <i  class="fa fa-plus"></i>

                              </div>
                              <div class="input-group-addon" style="color:#fff;background-color: #d9534f" id='resetorder'> <i  class="fa fa fa-eraser"></i>
                              </div>

                            </div>
                            <span id="errorOrder"></span>
                          </div>
                        </div>
                        <!-- <div class='col-md-2'>
                          <label></label>
                          <div class='form-group' style="padding-top: 20px;padding-right: 0px;margin-left: -25px;">
                            <button type='button' class='btn btn-primary' id='addorder'><i class="fa fa-plus"></i></button>
                            <button type='button' class='btn btn-danger' id='resetorder'><i class="fa fa-eraser"></i></button>
                          </div>
                        </div> -->
                        <!-- /.col -->
                      </div>
                      <div class='row'>
                        <div class='col-md-4'>
                          <div class='form-group'>
                            <label>Other orders</label>
                            <textarea  class="form-control" id="multiorder"name="multiorder" type="text" data-parsley-trigger='keypress'  data-parsley-required-message="Enter Other Order"></textarea>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading">Interim order if any</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Interim Prayer</label>
                            <label class="radio-inline">


                              <input type="checkbox" name="interimPrayer" id="interimPrayer" value="Y" data-parsley-trigger='keypress'>Yes

                            </label>
                          </div>
                        </div>
                        <div class="col-md-4" id="interimOrderDiv1" style="display: none;">
                          <div class="form-group">
                            <label>Interim Order Prayed for:</label>
                            <textarea  class="form-control" id="interimOrder"name="interimOrder" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Interim Order" ></textarea>
                          </div>
                        </div>
                      </div>
                      <div class='row'>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>IA Nature</label>
                            <select  class="form-control" id="IANature"name="IANature" type="text" data-parsley-trigger='keypress' >
                              <option value="">Select</option>
                              @foreach($IANature as $nature)
                              <option value="{{$nature->ianaturecode}}">{{$nature->ianaturedesc}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>IA for:</label>
                            <textarea  class="form-control" id="IAprayer"name="IAprayer" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Interim Application"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading">Address For Service</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Address For Service<span class="mandatory">*</span></label>
                            <textarea class="form-control" name="addrForService" type="text" id="addrForService" data-parsley-required data-parsley-required-message="Enter Service Address" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Service Address Accepts Only Characters" data-parsley-trigger='keypress' maxlength='300'></textarea>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Pincode</label>
                            <input class="form-control zero number" name="rPincode" type="number" id="rPincode" data-parsley-pattern="/^[1-9][0-9]{5}$/" data-parsley-pattern-message="Invalid Pincode" data-parsley-trigger='keypress'>
                          </div>
                        </div>

                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>District</label>
                            <select name="rDistrict" id="rDistrict" class="form-control" data-parsley-required data-parsley-required-message="Select District" data-parsley-trigger='keypress'>
                              <option value="">Select District</option>
                              @foreach($district as $dist)

                              <option value="{{$dist->distcode}}">{{$dist->distname}}</option>

                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Taluk</label>
                            <select name="rTaluk" id="rTaluk" class="form-control" data-parsley-required data-parsley-required-message="Select Taluk" data-parsley-trigger='keypress'>
                              <option value="">Select Taluk</option>
                              <!--  @foreach($taluka as $taluk)
                              <option value="{{$taluk->talukcode}}">{{$taluk->talukname}}</option>
                              @endforeach -->
                            </select>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading">Any Other Details</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="form-group">
                          <label>Remarks</label>
                          <textarea  class="form-control" id="caseremarks"name="caseremarks" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remark" data-parsley-required-message="Enter Remark"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading"></div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2" style="float: right;">
                    <input type="hidden" name="sbmt_case" id="sbmt_case" value="A">
                    <input type="hidden" id="relief_value2" name="relief_value2"   runat="server"value=''>
                    <a href="#" class="btn btn-md btn-primary btnNext">Save & Next</a>

                  </div>
                </div>
              </div>

            </div>
          </form>
        </div>
        <div class="tab-pane" id="tab_2">

          <div class='panel panel-primary'>
            <div class="panel panel-heading">Details Of Relief </div>
            <div class="panel panel-body">

              <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
              <input type='hidden' name='Originalapplid' class="Originalapplid" value=''>
              <div class="row">
                <div class="col-md-10" class="relief_valid">
                  <div class="form-group">
                    <input type="hidden" value='1' id="reliefcount">
                    <textarea type="text" name="reliefsought" id="reliefsought" class="reliefsought form-control" data-parsley-required-message="Enter Relief Sought" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Relief Sought"  data-parsley-required /></textarea>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">

                    <input type="hidden" id="relief_value" name="relief_value"   runat="server"value='A'>
                    <input type="hidden" id="refapplId" name="applicationId"   value=''>
                    <button class="btn btn-md btn-primary" type="button" id="sbmt_relief">Add</button>
                  </div>
                </div>




              </div>
              <div class="row">
                <table id="myTable" class="table table-bordered table-striped order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td class="col-sm-1">Sr.No</td>
                    <td class="col-sm-10">Relief Sought</td >
                  <td class="col-sm-1"></td >
                </tr>
              </thead>
              <tbody id="results2" style="overflow-y:auto;">


              </tbody>
            </table>
          </div>
        </div>
      </div>  <form  id="receiptForm" action="receiptDataStore" data-parsley-validate>
      <div class="panel panel-primary">
        <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
        <div class="panel panel-heading">Details Of Receipt</div>
        <div class="panel panel-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">

                <label>Receipt No.<span class="mandatory">*</span></label>
                <input class="form-control number zero" name="receiptNo"  id="receiptNo"  data-parsley-required data-parsley-pattern="/^[0-9\/]+$/"  data-parsley-required-message="Enter Receipt No." data-parsley-minlength='1' data-parsley-minlength-message="Receipt No. Should have Minimum 1 digit" data-parsley-maxlength='15' data-parsley-maxlength-message="Receipt No. Should have Maximum 15 digit" data-parsley-trigger='keypress'>
                <input type="hidden" id="recpApplYear" name="recpApplYear" value="" placeholder="applY">
                <input type="hidden" id="addreceipt" name="addreceipt" value="A">
                <input type="hidden" id="recAppId" name="recAppId"  value="" placeholder="apptype">
                <input type="hidden" name="receiptSrno" id="receiptSrno">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Receipt Date<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="receiptDate"  disabled readonly class="form-control pull-right datepicker" id="receiptDate" data-parsley-required data-parsley-required-message="Enter Receipt Date." data-parsley-errors-container="#error1" data-parsley-trigger='keypress'>
                </div>
                <span id="error1"></span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Applicant Name in Receipt<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch1" data-toggle="dropdown"><span class="title_sel1">Mr</span> <span class="selection1"></span>
                    <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu dropdown_all1" >
                      @foreach($nameTitle as $title)
                      <?php if($title->titleName=='Mr'){?>
                      <li class="active"><a  class="active" value="{{$title->titlename}}">{{$title->titlename}}</a></li>
                      <?php }else
                      {?>  <li><a   value="{{$title->titlename}}">{{$title->titlename}}</a></li>
                      <?php } ?>
                      @endforeach
                      {{--  <li><a href="#">Mr.</a></li>
                      <li><a href="#">Miss.</a></li> --}}
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control" id="applTitle" name="applTitle" value="Mr">
                  <input type="text" class="form-control" disabled readonly id="applName" name="applName" data-parsley-required data-parsley-required-message="Enter Applicant Name."
                   data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error" data-parsley-trigger='keypress'>
                </div>
                <span id="error"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Amount<span class="mandatory">*</span></label>
                <input class="form-control number zero" name="recpAmount" type="number" disabled readonly id="recpAmount" data-parsley-required data-parsley-required-message="Enter Receipt Amount"  data-parsley-minlength="2"  data-parsley-minlength-message="Receipt Amount Should have Minimum 2 Digits" data-parsley-maxlength='6' data-parsley-maxlength-message="Receipt Amount Accepts only 6 digits" data-parsley-trigger='keypress'>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group" id="submit_div">
                <br>
                <input value="A" name="sbmt_value" id="sbmt_value" type="hidden">
                <input type="button" name="recpSubmit" id="recpSubmit" class="btn btn-md btn-primary" value="Add List">
                <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
              </div>
              {{-- <div class="form-group" id="update_div" style="display: none;">
                <br>
                <input type="button" name="updateReceipt" id="updateReceipt" class="btn btn-md btn-primary" value="Update List">
              </div> --}}
            </div>
          </div>
          <div class="row">
            <table id="example1" class="table table-bordered table-striped receipt-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr>

                  <td class="col-md-2">Receipt No.</td>
                  <td class="col-md-2">Receipt Date</td>
                  <td class="col-md-2">Name of Applicant</td>

                  <td class="col-md-2">Amount</td>
                 <!-- <td class="col-md-2"></td>-->
                </tr>
              </thead>
              <tbody>



              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-md-1" style="float:left;">
              <a href="#" class="btn btn-md btn-primary btnPrevious">Previous</a>
            </div>
            <div class="col-md-9"></div>
            <div class=" col-md-2" style="float: right;">
              <a href="#" class="btn btn-md btn-primary " id="recpNext">Save & Next</a>
            </div>
          </div>
        </div>

      </div>
    </form>
  </div>
  <div class="tab-pane" id="tab_3">
    <div class="panel panel-primary">
      <div class="panel panel-heading">Details Of Applicant</div>
      <div class="panel panel-body">
        <form role="form" id="applicantForm" action="applicantDataStore" data-parsley-validate>
          <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
          <input type="hidden" id="applApplId" name="applApplId" value="">
          <input type="hidden" id="noOfAppCount" id="noOfAppCount" value="">
          <input type="hidden" id="applicantApplYear" name="applicantApplYear" value="">
          <input type="hidden" id="applicantStartSrNo" name="applicantStartSrNo"  value="0">
          <input type="hidden" id="serialcount" name="serialcount" value="">
          <input type="hidden" id="appadvcode" name="appadvcode" value="">

          <!--   {{-- <div class="col-md-4">
              <div class="form-group">
                <label>Is Main Party?<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="isMainParty" value="Y"   checked>Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="isMainParty" value="N" >No
                </label>
              </div>

            </div> --}} -->
            <div class="row" id="divApplicant" >
            <div class="col-md-3">
              <div class="form-group">
              <!--   <label>Select Applicant</label> -->
                 <select class="form-control" name="applicantDetails1" type="text" id="applicantDetails1" data-parsley-trigger='keypress'>
                  <option value="">Select Applicants</option>
                 </select>
                 </div>
            </div>
            <!--  <div class="col-md-3">
               <div>
                    <a class="btn btn-md btn-primary getApplicants" href="#" id="" > get Applicants </a>
               </div>
              </div> -->
               <div class="col-md-3">
              <div class="form-group">
                  <select class="form-control" name="respondantDetails1" type="text" id="respondantDetails1" data-parsley-trigger='keypress'>
                  <option value="">Select Respondants</option>
                 </select>
                 </div>
            </div>
             <div class="col-md-3">
               <div>
                 <a  class="btn btn-md btn-primary getApplicants" href="#" id="" > Get Data</a>
              </div>
            </div>
           </div>
            <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Applicant Name<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch2" data-toggle="dropdown"><span class="title_sel2" >Select Title</span> <span class="selection2"></span>
                    <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu dropdown_all2"  >
                      @foreach($nameTitle as $title)
                      <li class='relation2'><a id="link1" value="{{$title->titlename.'-'.$title->gender}}">{{$title->titlename}}</a></li>
                      @endforeach
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control" id="applicantTitle" name="applicantTitle" >
                  <input type="text" class="form-control" id="applicantName" name="applicantName" data-parsley-required data-parsley-required-message="Enter Applicant Name."
                  data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Applicant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error9" data-parsley-trigger='keypress'>
                </div>
              </div>
              <span id="error9"></span>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Relation</label>
                <select class="form-control" name="relationType" type="text" id="relationType" data-parsley-required data-parsley-required-message="Select Relation" data-parsley-trigger='keypress'>
                  <option value="">Select Relation</option>
                  @foreach($relationTitle as $relation)
                  <option value="{{$relation->relationtitle}}">{{$relation->relationname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Name of Relation<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch3" data-toggle="dropdown"><span class="title_sel3">Select Title</span> <span class="selection3"></span>
                    <span class="fa fa-caret-down"></span></button>
                    {{--     <ul class="dropdown-menu dropdown_all3" >
                      @foreach($nameTitle as $title)
                      <li ><a value="{{$title->titlename}}">{{$title->titlename}}</a></li>
                      @endforeach
                    </ul> --}}
                    <ul class="dropdown-menu dropdown_all3" id="rel7" >
                      @foreach($nameTitle as $title)

                      <li class="relation6"><a value="{{$title->gender}}">{{$title->titlename}}</a></li>


                      @endforeach
                    </ul>
                    <ul class="dropdown-menu dropdown_all3" id="rel6" style="display: none">
                      @foreach($nameTitle as $title)
                      <?php if($title->gender=='F'){?>
                      <li class="relation6"><a value="{{$title->gender}}">{{$title->titlename}}</a></li>
                      <?php } ?>

                      @endforeach
                    </ul>
                    <ul class="dropdown-menu dropdown_all3" id="rel5" style="display: none">
                      @foreach($nameTitle as $title)
                      <?php if($title->gender=='M'){?>
                      <li class="relation6"><a value="{{$title->gender}}">{{$title->titlename}}</a></li>
                      <?php } ?>

                      @endforeach
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control" id="relationTitle" name="relationTitle">
                  <input type="text" class="form-control" id="relationName" name="relationName"  data-parsley-required data-parsley-required-message="Enter Relation Name."
                  data-parsley-pattern="/^[a-zA-Z- ]*$/" data-parsley-pattern-message="Relation Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Relation Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Relation Name Accepts only 100 characters" data-parsley-errors-container="#error15" data-parsley-trigger='keypress'>
                </div>
              </div>
              <span id="error15"></span>
            </div>
          </div>
          <div class="row">


            <div class="col-md-4">
              <div class="form-group">
                <label>Gender<span class="mandatory">*</span></label>
                <select class="form-control gender" name="gender" type="text" id="gender" data-parsley-required data-parsley-required-message="Select Gender" data-parsley-trigger='keypress'>
                  <option value="">Select Gender</option>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                  <option value="T">Transgender</option>
                  <option value="NA">Not Applicable</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Age<span class="mandatory">*</span></label>
                <input class="form-control number" name="applAge" type="number" id="applAge" onkeyup="this.value = minmax(this.value, 0, 100)"
                data-parsley-required  data-parsley-required-message="Enter Age"
                data-parsley-minlength="1" data-parsley-minlength-message="Age Should have minimum 1 year" data-parsley-maxlength="3" data-parsley-maxlength-message="Age Should have maximum 3 digit" data-parsley-trigger='keypress'>
              </div>
            </div>
             <div class="col-md-4">
              <div class="form-group">
                <label>Department Type<span class="mandatory">*</span></label>
                <select class="form-control" name="applDeptType" type="text" id="applDeptType" data-parsley-required data-parsley-required-message="Select Department Type" data-parsley-trigger='keypress'>
                  <option value="">Select Department</option>
                  @foreach($deptType as $dept)
                  <option value="{{$dept->depttypecode}}">{{$dept->depttype}}</option>
                  @endforeach
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

            <div class="col-md-4">
              <div class="form-group">
                <label>Name Of Department<span class="mandatory">*</span></label>
                <select class="form-control" name="nameOfDept" type="text" id="nameOfDept" data-parsley-required data-parsley-required-message="Select Department Name" data-parsley-trigger='keypress'>
                  <option value="">Select Department Name</option>
                  @foreach($deptName as $deptname)
                  <option value="{{$deptname->departmentcode}}">{{$deptname->departmentname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">

              <div class="form-group">
                <label>Designation Of Applicant<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <select class="form-control" name="desigAppl"  id="desigAppl" data-parsley-required data-parsley-required-message="Select Designation" >
                    <option value="">Select Designation</option>
                    @foreach($appldesig as $desig)
                    <option value="{{$desig->desigcode}}">{{$desig->designame}}</option>
                    @endforeach
                  </select>
                 <!-- <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="desigadd">
                    <i class="fa fa-plus"></i>
                  </div>-->

                </div>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Address<span class="mandatory">*</span></label>
                <textarea class="form-control"  name="addressAppl" type="text" id="addressAppl" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/"  data-parsley-pattern-message="Invalid Address" data-parsley-minlength="3"  data-parsley-minlength-message="Address  Should have Minimum 3 Characters" data-parsley-maxlength='300' data-parsley-maxlength-message="Address Accepts only 300 characters" data-parsley-required data-parsley-required-message="Enter Address" data-parsley-trigger='keypress'></textarea>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Pincode</label>
                <input class="form-control number zero" name="pincodeAppl" type="number" id="pincodeAppl"  data-parsley-pattern="/^[1-9][0-9]{5}$/" data-parsley-pattern-message="Invalid Pincode" data-parsley-trigger='keypress'>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>District<span class="mandatory">*</span></label>
                <select class="form-control" name="districtAppl" type="text" id="districtAppl" data-parsley-required data-parsley-required-message="Select District" data-parsley-trigger='keypress'>
                  <option value="">Select District</option>
                  @foreach($district as $dist)
                  <option value="{{$dist->distcode}}">{{$dist->distname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
             <div class="col-md-4">
              <div class="form-group">
                <label>Taluk<span class="mandatory">*</span></label>
                <select class="form-control" name="talukAppl" type="text" id="talukAppl" data-parsley-required data-parsley-required-message="Select taluk" data-parsley-trigger='keypress'>
                  <option value="">Select Taluk</option>
                  @foreach($taluka as $taluk)
                  <option value="{{$taluk->talukcode}}">{{$taluk->talukname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Mobile No.</label>
                <input class="form-control number zero" name="applMobileNo"  id="applMobileNo" type="number" maxlength="10" data-parsley-pattern="\d*" data-parsley-pattern-message="Invalid Mobile No."  data-parsley-trigger='keypress'type="text"  >
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email Id</label>
                <input class="form-control" name="applEmailId" type="email" id="applEmailId" >
              </div>
            </div>
             <div class="col-md-4"  id="party_div">
              <div class="form-group">
                <label>Party in Person<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="partyInPerson" id="partyInPerson" value="Y" >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="partyInPerson" id="partyInPerson" value="N"checked>No
                </label>
              </div>
              <span id="error12"></span>
            </div>
          </div>
          <div class="row">


            {{-- <div class="col-md-4 applsingleadvocate">
              <div class="form-group">
                <label>Is Single Advocate<span class="mandatory">*</span></label>
                <br>
                <label class="radio-inline">
                  <input type="radio" name="isAdvocate" id="isAdvocate" value="Y" >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="isAdvocate" id="isAdvocate" value="N" checked>No
                </label>

                <input type="hidden" name="" id="">
              </div>
            </div> --}}
            <div class="col-md-4 advDetails">
              <!-- <div class="form-group">
                <label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
                <input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20">
                <datalist id="browsers">
                <?php foreach($adv as $advocate){?>
                <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                <?php }?>
                </datalist>
              </div> -->
              <div class="form-group">
                <label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
                <div class="input-group date">
                 <!-- <input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."  data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20"  data-parsley-errors-container='#errorAdv1'>
                -->
 <input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo"  data-parsley-errors-container='#errorAdv1'>


  <datalist id="browsers">
                  <?php foreach($adv as $advocate){?>
                  <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                  <?php }?>
                  </datalist>
                  <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="advocateAdd">
                    <i class="fa fa-plus"></i>
                  </div>

                </div>
                <span id='errorAdv1'></span>
              </div>

            </div>
            <div class="col-md-4 advDetails">
              <div class="form-group">
                <label>Advocate Name</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch4" data-toggle="dropdown"><span class="title_sel4">Select Title</span> <span class="selection4"></span>
                    <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu dropdown_all4" >
                      @foreach($nameTitle as $title)
                      <li ><a value="{{$title->titlename}}">{{$title->titlename}}</a></li>
                      @endforeach
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control" id="advTitle" name="advTitle" readonly="">
                  <input type="text" class="form-control" id="advName" name="advName" readonly="">
                </div>
              </div>
            </div>
            <div class="col-md-4 advDetails">
              <div class="form-group">
                <label>Address</label><br>
                <textarea class="form-control" name="advRegAdrr" type="text" id="advRegAdrr" readonly></textarea>
              </div>
            </div>
          </div>
          <div class="row">


            <div class="col-md-4 advDetails">
              <div class="form-group">
                <label>Pincode</label><br>
                <input class="form-control" name="advRegPin" type="text" id="advRegPin" readonly>
              </div>
            </div>
             <div class="col-md-4 advDetails">
              <div class="form-group">
                <label>District</label>
                <select name="advRegDistrict" id="advRegDistrict" class="form-control" readonly>

                </select>
              </div>
            </div>
            <div class="col-md-4 advDetails">
              <div class="form-group">
                <label>Taluk</label>
                <select name="advRegTaluk" id="advRegTaluk" class="form-control" readonly>

                </select>
              </div>
            </div>

          <div class="col-md-4">
              <div class="form-group">
                <input class="searchType" type="checkbox" name="populate" id="populate" value="checked"><label class="searchtype1label">Populate Cause Title</label></input>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Text for CauseTitle</label><br>
                <textarea class="form-control" name="textcausetitle" rows='9' cols='40' type="text" id="textcausetitle"  style="white-space: pre-line;"></textarea>
              </div>
            </div>
          </div>
          <div class="row">


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
                  <td  class="col-sm-1">Sr.No</td>
                  <td class="col-md-2">Applicants</td>
                  <td class="col-md-2">Advocates</td>
                </tr>
              </thead>
              <tbody id="results">



              </tbody>
            </table>
          </div><br>
          <div class="row">
            <div class=" col-md-1" style="float:left;">
              <a  class="btn btn-md btn-primary btnPrevious">Previous</a>
            </div>
            <div class="col-md-10"></div>
            <div class=" col-md-2" style="float: right;">
              <a  class="btn btn-md btn-primary" id="applNext">Save & Next</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="tab-pane" id="tab_4">
    <div class="panel panel-primary">
      <div class="panel panel-heading">Details Of Respondants</div>
      <div class="panel panel-body">
        <form role="form" id="respondantForm" action="respondantDataStore" data-parsley-validate>
          <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
          <input class="form-control" name="resApplId" type="hidden" id="resApplId" >
          <input class="form-control" name="resStartNo" type="hidden" id="resStartNo" value='0' >
          <input class="form-control" name="resSerilNo" type="hidden" id="resSerilNo" >
          <input class="form-control" name="resCount" type="hidden" id="resCount" >
          {{-- <input class="form-control" name="resApplYear" type="hidden" id="resApplYear"> --}}
          <input class="form-control" name="noOfResCount" type="hidden" id="noOfResCount" >
          <input class="form-control" name="resadvocde" type="hidden" id="resadvcode" >

            <!--  <div class="col-md-4">
              <div class="form-group">
                <label>Is Main Repondant<span class="mandatory">*</span></label>
                <label class="radio-inline">
                  <input type="radio" name="isMainRes" value="Y" >YES
                </label>
                <label class="radio-inline">
                  <input type="radio" name="isMainRes" value="N" checked>NO
                </label>
              </div>&nbsp;
            </div> -->

        <div class="row" id="divRespondant" >
           <div class="col-md-3">
              <div class="form-group">
              <select class="form-control" name="applicantDetails2" type="text" id="applicantDetails2" data-parsley-trigger='keypress'>
                  <option value="">Select Applicants</option>
                 </select>
                 </div>
            </div>
           <!--   <div class="col-md-3">
               <div>
                    <a class="btn btn-md btn-primary getApplicants" href="#" id="" > get Applicants </a>
               </div>
              </div> -->
          <div class="col-md-3">
              <div class="form-group">
                  <select class="form-control" name="respondantDetails2" type="text" id="respondantDetails2" data-parsley-trigger='keypress'>
                  <option value="">Select Respondants</option>
                 </select>
                 </div>
            </div>
             <div class="col-md-3">
               <div>
                 <a  class="btn btn-md btn-primary getApplicants" href="#" id="" > Get Data</a>
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Name Of Respondant<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch5" data-toggle="dropdown"><span class="title_sel5">Select Title</span> <span class="selection5"></span>
                    <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu dropdown_all5" >
                      @foreach($nameTitle as $title)
                      <li ><a value="{{$title->titlename.'-'.$title->gender}}">{{$title->titlename}}</a></li>
                      @endforeach
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control" name="respondantTitle" id="respondantTitle">
                  <input type="text" class="form-control number zero" name="respondantName" id="respondantName" data-parsley-required data-parsley-required-message="Enter Respondant Name."
                  data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Respondant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Respondant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Respondant Name Accepts only 100 characters" data-parsley-errors-container="#error17" data-parsley-trigger='keypress'>
                </div>
              </div>
              <span id="error17"></span>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Relation</label>
                <select class="form-control" name="resReltaion" type="text" id="resReltaion" data-parsley-required data-parsley-required-message="Select Relation" data-parsley-trigger='keypress'>
                  <option value="">Select Relation</option>
                  @foreach($relationTitle as $relation)
                  <option value="{{$relation->relationtitle}}">{{$relation->relationname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Name of Relation<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch6" data-toggle="dropdown"><span class="title_sel6">Select Title</span> <span class="selection6"></span>
                    <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu dropdown_all6" id="rel8" >
                      @foreach($nameTitle as $title)

                      <li class="relation6"><a value="{{$title->gender}}">{{$title->titlename}}</a></li>


                      @endforeach
                    </ul>
                    <ul class="dropdown-menu dropdown_all6" id="rel9" style="display: none">
                      @foreach($nameTitle as $title)
                      <?php if($title->gender=='F'){?>
                      <li class="relation6"><a value="{{$title->gender}}">{{$title->titlename}}</a></li>
                      <?php } ?>

                      @endforeach
                    </ul>
                    <ul class="dropdown-menu dropdown_all6" id="rel10" style="display: none">
                      @foreach($nameTitle as $title)
                      <?php if($title->gender=='M'){?>
                      <li class="relation6"><a value="{{$title->gender}}">{{$title->titlename}}</a></li>
                      <?php } ?>

                      @endforeach
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control number zero" name="resRelTitle" id="resRelTitle">
                  <input type="text" class="form-control" name="resRelName" id="resRelName" data-parsley-required data-parsley-required-message="Enter Relation Name."
                  data-parsley-pattern="/^[a-zA-Z- ]*$/" data-parsley-pattern-message="Relation Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Relation Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Relation Name Accepts only 100 characters" data-parsley-errors-container="#error18" data-parsley-trigger='keypress'>
                </div>
              </div>
              <span id="error18"></span>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Gender<span class="mandatory">*</span></label>
                <select class="form-control resGender" name="resGender" type="text" id="resGender" data-parsley-required data-parsley-required-message="Select Gender" data-parsley-trigger='keypress'>
                  <option value="">Select Gender</option>
                  <option value="M">Male</option>
                  <option value="F">Female</option>
                  <option value="T">Transgender</option>
                  <option value="NA">Not Applicable</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Age<span class="mandatory">*</span></label>
                <input type="number" name="resAge" id="resAge" class="form-control number" onkeyup="this.value = minmax(this.value, 0, 100)"
                data-parsley-required  data-parsley-required-message="Enter Age"
                data-parsley-minlength="1" data-parsley-minlength-message="Age Should have minimum 1 digit" data-parsley-maxlength="3" data-parsley-maxlength-message="Age Should have maximum 3 digit" data-parsley-trigger='keypress'>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Department Type<span class="mandatory">*</span></label>
                <select class="form-control" name="resDeptType" type="text" id="resDeptType"  data-parsley-required  data-parsley-required-message="Select Department Type" data-parsley-trigger='keypress'>
                  <option value="">Select Department Type</option>
                  @foreach($deptType as $dept)
                  <option value="{{$dept->depttypecode}}">{{$dept->depttype}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Name of Department<span class="mandatory">*</span></label>
                <select name="resnameofDept" id="resnameofDept" class="form-control" data-parsley-required  data-parsley-required-message="Select Department" data-parsley-trigger='keypress'>
                  <option value="">Select Department Name</option>
                  @foreach($deptName as $deptname)
                  <option value="{{$deptname->departmentcode}}">{{$deptname->departmentname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <!--  <div class="form-group">
                <label>Designation of Respondant</label>

                <select class="form-control" name="resDesig"  id="resDesig" data-parsley-required data-parsley-required-message="Select Designation" >
                  <option value="">Select Designation</option>
                  @foreach($appldesig as $desig)
                  <option value="{{$desig->desigcode}}">{{$desig->designame}}</option>
                  @endforeach
                </select>
              </div> -->
              <div class="form-group">
                <label>Designation of Respondant<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <select class="form-control" name="resDesig"  id="resDesig" data-parsley-required data-parsley-required-message="Select Designation" >
                    <option value="">Select Designation</option>
                    @foreach($appldesig as $desig)
                    <option value="{{$desig->desigcode}}">{{$desig->designame}}</option>
                    @endforeach
                  </select>
                <!--  <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="resdesigadd">
                    <i class="fa fa-plus"></i>
                  </div>-->

                </div>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Address<span class="mandatory">*</span></label>
                <textarea type="text" name="resAddress2" id="resAddress2" class="form-control zero" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Address" data-parsley-minlength="3"  data-parsley-minlength-message="Address  Should have Minimum 3 Characters" data-parsley-maxlength='300' data-parsley-maxlength-message="Address Accepts only 300 characters" data-parsley-required data-parsley-required-message="Enter Address" data-parsley-trigger='keypress'></textarea>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Pincode</label>
                <input type="number" name="respincode2" id="respincode2" class="form-control number zero" data-parsley-pattern="/^[1-9][0-9]{5}$/" data-parsley-pattern-message="Invalid Pincode" data-parsley-trigger='keypress'>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>District<span class="mandatory">*</span></label>
                <select name="resDistrict" id="resDistrict" class="form-control" data-parsley-required data-parsley-required-message="Select District" data-parsley-trigger='keypress'>
                  <option value="">Select District</option>
                  @foreach($district as $dist)
                  <option value="{{$dist->distcode}}">{{$dist->distname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Taluk<span class="mandatory">*</span></label>
                <select name="resTaluk" id="resTaluk" class="form-control" data-parsley-required data-parsley-required-message="Select Taluk" data-parsley-trigger='keypress'>
                  <option value="">Select Taluk</option>
                  @foreach($taluka as $taluk)
                  <option value="{{$taluk->talukcode}}">{{$taluk->talukname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Mobile No.</label>
                <input class="form-control" name="resMobileNo" type="number" id="resMobileNo" data-parsley-pattern="\d*"  data-parsley-pattern-message="Invalid Mobile No."  data-parsley-trigger='keypress' maxlength="10">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email Id</label>
                <input class="form-control" name="resEmailId" type="email" id="resEmailId" >
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Is Govt Advocate</label>
                <label class="radio-inline">
                  <input type="checkbox" name="isGovtAdv" value="Y" >
                </label>
              </div>&nbsp;
            </div>
          </div>
          <div class="row">


            <div class="col-md-4 resadvDatails">
              <div class="form-group">
                <label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
                <div class="input-group date">
                 <!-- <input list="browsers1"  class="form-control number zero" name="resadvBarRegNo" type="text" id="resadvBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No." data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="AdvocateBar Reg No.  Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters"maxlength="20" data-parsley-trigger='keypress' data-parsley-errors-container='#errorAdv' >
          --> 
 <input list="browsers1"  class="form-control number zero" name="resadvBarRegNo" type="text" id="resadvBarRegNo"  data-parsley-trigger='keypress' data-parsley-errors-container='#errorAdv' >


           <datalist id="browsers1">
                  <?php foreach($adv as $advocate){?>
                  <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                  <?php }?>
                  </datalist>
                 <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="resadvocateAdd">
                    <i class="fa fa-plus"></i>
                  </div>

                </div>
                <span id="errorAdv"></span>
              </div>

            </div>
            <div class="col-md-4 resadvDatails">
              <div class="form-group">
                <label>Advocate Name</label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch7" data-toggle="dropdown"><span class="title_sel7">Select Title</span> <span class="selection7"></span>
                    <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu dropdown_all7" disabled>
                      @foreach($nameTitle as $title)
                      <li ><a value="{{$title->titlename}}">{{$title->titlename}}</a></li>
                      @endforeach
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control" name="respAdvTitle" id="respAdvTitle" readonly>
                  <input type="text" class="form-control" name="respAdvName" id="respAdvName" readonly>
                </div>
              </div>
            </div>
            <div class="col-md-4 resadvDatails">
              <div class="form-group">
                <label>Address</label><br>
                <textarea class="form-control"  name="resadvaddr" type="text" id="resadvaddr" readonly></textarea>
              </div>
            </div>

          </div>
          <div id="resAdvocateAddr">
            <div class="row">

              <div class="col-md-4 resadvDatails">
                <div class="form-group">
                  <label>Pincode</label><br>
                  <input class="form-control" name="resadvpincode" type="text" id="resadvpincode" readonly>
                </div>
              </div>

              <div class="col-md-4 resadvDatails">
                <div class="form-group">
                  <label>District</label>
                  <select name="resadvdistrict" id="resadvdistrict" class="form-control" readonly>
                    {{--  <option value="">Select District</option>
                    @foreach($district as $dist)
                    <option value="{{$dist->distCode}}">{{$dist->distName}}</option>
                    @endforeach --}}
                  </select>
                </div>
              </div>
               <div class="col-md-4 resadvDatails">
                <div class="form-group">
                  <label>Taluk</label>
                  <select name="resadvtaluk" id="resadvtaluk" class="form-control" readonly>


                  </select>
                </div>
              </div>

             <div class="col-md-4">
                <div class="form-group">
                  <input class="searchType1" type="checkbox" name="populate1" id="populate1"  value="checked1"><label class="searchtype1label">Populate Cause Title</label></input>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Text for CauseTitle</label><br>
                  <textarea class="form-control" name="textcausetitle1" rows='9' cols='17' type="text" id="textcausetitle1"  style="white-space: pre-line;"></textarea>
                </div>
              </div>
            </div>
            <div class='row'>

            </div>


          </div>
          <div class="row"  style="float: right;" id="res_sbmt_div">
            <div class="col-sm-12 text-center">
              <input type="hidden" name="sbmt_respondant" id="sbmt_respondant" value="A">
              <input type="button" id="saveRespondant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;"  value="Add List">
              <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
            </div>
          </div>

          <br><br>
          <div class="row" id="respondant_div">
            <table id="example3" class="table table-bordered table-striped respondant-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr>
                  <td class="col-sm-1">Sr.No</td>
                  <td class="col-md-2">Respondants</td>
                  <td class="col-md-2">Advocates</td>
                </tr>
              </thead>
              <tbody id="results4">



              </tbody>
            </table>
          </div><br>
          <div class="row">
            <div class=" col-md-1" style="float:left;">
              <a  class="btn btn-md btn-primary btnPrevious">Previous</a>
            </div>
            <div class="col-md-9"></div>
            <div class=" col-md-2" style="float: right;">
              <a  class="btn btn-md btn-primary" id="respNext">Save & Next</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="tab-pane" id="tab_5">
    <div class="panel panel-primary">
      <div class="panel panel-heading">Details Of Application Index</div>
      <div class="panel panel-body">
        <form role="form" id="ApplIndexForm" action="applicationIndexDataStore" data-parsley-validate>
          <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
          <input class="form-control" name="applIndexId" type="hidden" id="applIndexId">
          <input class="form-control" name="applIndexStartNo" type="hidden" id="applIndexStartNo">
          <input class="form-control" name="reviewApplId" type="hidden" id="reviewApplId">
          <div class="row">
            <table id="applIndexTbl" class="table application-list">
              <thead  style="background-color: #3c8dbc;color:#fff;">
                <tr>
                  {{-- <td class="col-sm-1">Sr.No</td> --}}
                  <td  class="col-xs-4">Particulars of documents</td>
                  <td class="col-xs-1">Start Page</td>
                  <td class="col-xs-1">End Page</td>
                <td class="col-xs-1"></a>
              </tr>
            </thead>
            <tbody>
              <tr>
                {{-- <td class="col-sm-1">
                  <input type="hidden" name="count[]" class="counter" id="count" value="1">1
                </td> --}}
                <td class="col-xs-4">
                  <textarea type="mail" name="partOfDoc[]"  id="partOfDoc" class="form-control" data-parsley-required data-parsley-required-message="Enter Part Of Document" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Part Of Document Accepts Only Alpanumeric" data-parsley-trigger='keypress'></textarea>
                </td>
                <td class="col-xs-1">
                  <input type="number" name="start[]" id="start"  class="form-control number start" data-parsley-required data-parsley-required-message="Enter Start No." data-parsley-trigger='keypress'  />
                </td>
                <td class="col-xs-1">
                  <input type="number" id='endPage' name="endPage[]" id="endPage"  class="form-control number endPage" data-parsley-required data-parsley-required-message="Enter End No." data-parsley-trigger='keypress'  />
                </td>
                <td class="col-xs-1"><a class="deleteRow2"></a>
              </td>
            </tr>
          </tbody>
          <tfoot>
          <tr>
            <td colspan="5" style="text-align: right;">
              <input type="button" class="btn btn-md btn-primary " id="addrow2" value="Add New" />
            </td>
          </tr>
          <tr>
          </tr>
          </tfoot>
        </table>
        <div >
          <span id="applErrormsg" style="color:red"></span>
        </div>
      </div>
      <div class="row">
        <div class="panel panel-primary">
          <div class="panel-heading"></div>
        </div>

      </div>
      <div class="row">
        <div class=" col-md-1" style="float:left;">
          <a  class="btn btn-md btn-primary btnPrevious">Previous</a>
        </div>
        <div class="col-sm-10 text-center" style="float: right;">
          <input type="hidden" name="applIndex_up_value" id="applIndex_up_value" value='U'>
          <input  id="saveAplicantionIndex" type="button" class="btn btn-primary btn-lg center-block btnSearch" Style="width: 100px;" value="SAVE" >
          <input type="button" class="btn btn-danger btn-lg center-block btnClear" Style="width: 100px;" value="Cancel" >
        </div>
      </div>
    </form>
  </div>
</div>
<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>
<script src="js/application/detailEntryApplication.js"></script>
<script>
$('.searchType').click(function() {
  //var details = $('input[name="box"]:checked').closest("tr").find("input[name="pincodeAppl"]").serialize();

var applicantTitle=$("#applicantTitle").val();

  var applicantname=$("#applicantName").val();
  var relationtype=$("#relationType").val();
  var releationshipname=$("#relationName").val();
  var departmentaddress=$("#addressAppl").val();
  var pincodeAppl = $("#pincodeAppl").val();
  var age=$("#applAge").val();
  var deptcode=$("#nameOfDept").val();
  var designation=$("#desigAppl").val();
  var distcode=$("#districtAppl").val();
  var talukcode=$("#talukAppl").val();
  //var endNo = $('#applEndNo').val();
       if(this.checked){
            $.ajax({
                type: "POST",
                url: "causetitletext",
                data: {
                  "_token": $('#token').val(),
                  applicantname: applicantname,departmentaddress:departmentaddress,pincodeAppl:pincodeAppl,
                  age:age,deptcode:deptcode,
                  designation:designation,distcode:distcode,talukcode:talukcode,relationtype:relationtype,releationshipname:releationshipname,applicantTitle:applicantTitle
                  },
              dataType: "JSON",
                success: function(json) {
                  $("#textcausetitle").val(json);
                },
                /*s error: function() {
                    alert('it broke');
                },
                complete: function() {
                    alert('it completed');
                }  */
            });

            }
            else{
              document.getElementById('textcausetitle').value = '';
            }
      });
</script>
<script>
$('.searchType1').click(function() {
  //var details = $('input[name="box"]:checked').closest("tr").find("input[name="pincodeAppl"]").serialize();
  var respondantName=$("#respondantName").val();
  var resReltaion=$("#resReltaion").val();
  var resRelName=$("#resRelName").val();
  var resAddress2=$("#resAddress2").val();
  var respincode2 = $("#respincode2").val();
  var resAge=$("#resAge").val();
  var deptcode=$("#resnameofDept").val();
  var designation=$("#resDesig").val();
  var distcode=$("#resDistrict").val();
  var talukcode=$("#resTaluk").val();
  //var endNo = $('#applEndNo').val();

       if(this.checked){
            $.ajax({
                type: "POST",
                url: "causetitletext1",
                data: {
                  "_token": $('#token').val(),
                  respondantName: respondantName,resAddress2:resAddress2,respincode2:respincode2,
                  resAge:resAge,deptcode:deptcode,
                  designation:designation,distcode:distcode,talukcode:talukcode,resReltaion:resReltaion,resRelName:resRelName
                  },
              dataType: "JSON",
                success: function(json) {
                  $("#textcausetitle1").val(json);
                },
                /*s error: function() {
                    alert('it broke');
                },
                complete: function() {
                    alert('it completed');
                }  */
            });

            }
            else{
              document.getElementById('textcausetitle1').value = '';
            }
      });
</script>



</section>



@endsection
