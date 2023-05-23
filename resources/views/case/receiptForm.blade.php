
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
@extends( 'Case/caseData')
 @include('flash-message')
  <section class="content">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Case</a></li>
        <li><a href="#tab_2" data-toggle="tab">Receipt </a></li>
        <li><a href="#tab_3" data-toggle="tab">Applicant </a></li>
         <li><a href="#tab_4" data-toggle="tab">Respondant </a></li>
        <li><a href="#tab_5" data-toggle="tab">Application Index</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <form role="form" id="caseForm" action="caseDataStore" data-parsley-validate>
              <div class="panel  panel-primary">
                <div class="panel panel-heading">
                  <h7 >Details of Application</h7>
                </div>
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="panel panel-body">

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>KSAT Act<span class="mandatory">*</span></label>
                        <select class="form-control" name="actName" disabled>
                          <option value="">Select Act</option>
                          @foreach($actDetails as $act)
                          <option value="{{$act->actCode}}" selected>{{$act->actName}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Section Name<span class="mandatory">*</span></label>
                        <select class="form-control" name="actSectionName"  disabled>
                          <option value="">Select Section Name</option>
                          @foreach($sectionDetails as $actsection)
                          <option value="{{$actsection->actSectionCode}}" selected>{{$actsection->actSectionName}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
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
                  </div>
                  <div class="row">
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
                    <div class="col-md-4">
                      <label>Application Year:<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="applYear"  class="form-control pull-right" onkeydown="return false" id="datepicker1" data-parsley-pattern="/[0-9]\d*/" data-parsley-date-format="YYYY" data-parsley-date-format-message="Enter Valid Application Year" data-parsley-pattern-message="Only numbers allowed" value="" data-parsley-required  data-parsley-required-message="Enter Application Year" data-parsley-errors-container="#error4">
                      </div>
                      <span id="error4"></span>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Application Start No<span class="mandatory">*</span></label></br>
                        <input type="number" class="form-control pull-right" id="applStartNo" name="applStartNo" data-parsley-lt="#applEndNo" data-parsley-lt-message="Start No.  Should be less than End No."  data-parsley-maxlength="5" data-parsley-maxlength-message="Application Start No Allows only 5 digits"   data-parsley-pattern="/^[0-9]+$/" data-parsley-pattern-message="Application Start No allows only numbers" data-parsley-pattern-message="Application End No allows only numbers" value=""
                        data-parsley-required  data-parsley-required-message="Enter Applcation Start No">
                      </div>
                    </div>

                  </div><br>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Application End No<span class="mandatory">*</span></label>
                        <input type="number" name="applEndNo" id="applEndNo" class="form-control" data-parsley-gt="#applStartNo" data-parsley-gt="#applStartNo" data-parsley-gt-message="End No. Should be greater than Start No."   data-parsley-pattern="/^[0-9]+$/" data-parsley-maxlength="5" data-parsley-maxlength-message="Application End No Allows only 5 digits" data-parsley-pattern-message="Application End No allows only numbers" data-parsley-pattern-message="Application End No allows only numbers"  value=""
                        data-parsley-required  data-parsley-required-message="Enter Applcation End No">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label>Registration Date<span class="mandatory">*</span></label>
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
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Subject<span class="mandatory">*</span></label>
                        <textarea class="form-control" name='applnSubject' id="applnSubject" data-parsley-required  data-parsley-required-message="Enter Subject" >
                        </textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="panel panel-primary">
                      <div class="panel-heading" >Order Against Which Applcation Is Made</div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Order No</label>
                              <input class="form-control" name="orderNo" id="orderNo" type="text" value="" daa>
                            </div>
                          </div>
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
                              <label>Issued By</label>
                              <input type="text" class="form-control pull-right" id="applnIssuedBy" name="applnIssuedBy" value="">
                            </div>
                          </div>
                          <!-- /.col -->
                        </div></div>
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


                                  <input type="checkbox" name="interimPrayer" id="interimPrayer" value="Y" >Yes

                                </label>
                              </div>
                            </div>
                            <div class="col-md-4" id="interimOrderDiv" style="display: none;">
                              <div class="form-group">
                                <label>Interim Order Prayed for:</label>
                                <textarea  class="form-control" id="interimOrder"name="interimOrder" type="text"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Address for Service:</label>
                                <textarea  class="form-control"
                                id="interimService"name="interimService" type="text" data-parsley-required data-parsley-required-message="Enter Service Address" data-parsley-pattern="/^[a-zA-Z0-9 ]*$/" data-parsley-pattern-message="Service Address Accepts Only Alpanumeric"></textarea>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Pincode</label>
                                <input class="form-control" id="interimPincode" name="interimPincode" type="text" value="" data-parsley-required data-parsley-required-message="Enter Pincode" data-parsley-pattern="/^[1-9][0-9]{5}$/" data-parsley-pattern-message="Invalid Pincode">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Taluk</label>
                                <select name="interimTaluk" id="interimTaluk" class="form-control" data-parsley-required data-parsley-required-message="Select Taluk"><option value="" >Select Taluk</option>
                                @foreach($taluka as $taluk)

                                <option value="{{$taluk->talukCode}}">{{$taluk->talukName}}</option>

                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>District</label>
                              <select name="interimDistrict" id="interimDistrict" class="form-control" data-parsley-required data-parsley-required-message="Select District">
                                <option value="">Select District</option>
                                @foreach($district as $dist)

                                <option value="{{$dist->distCode}}">{{$dist->distName}}</option>

                                @endforeach
                              </select>
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
                              <textarea class="form-control" id="addrForService" name="addrForService" type="text" id="addrForService" data-parsley-required data-parsley-required-message="Enter Service Address" data-parsley-pattern="/^[a-zA-Z0-9]*$/" data-parsley-pattern-message="Service Address Accepts Only Characters"></textarea>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Pincode</label>
                              <input class="form-control" name="rPincode" type="text" id="rPincode" value="" data-parsley-required data-parsley-required-message="Enter Pincode" data-parsley-pattern="/^[1-9][0-9]{5}$/" data-parsley-pattern-message="Invalid Pincode">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>No of Applicants.<span class="mandatory">*</span></label>
                              <input class="form-control" name="noOfAppl" id="noOfAppl" type="number" id="noOfAppl" value="" data-parsley-required data-parsley-required-message="Enter No fo Applicants" data-parsley-minlength="1" data-parsley-minlength-message="No of Applicants Should have minimum 1 digit" data-parsley-maxlength="3" data-parsley-maxlength-message="No of Applicants Should have maximum 3 digit"  >
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>District</label>
                              <select name="rDistrict" id="rDistrict" class="form-control" data-parsley-required data-parsley-required-message="Select District">
                                <option value="">Select District</option>
                                @foreach($district as $dist)

                                <option value="{{$dist->distCode}}">{{$dist->distName}}</option>

                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Taluk</label>
                              <select name="rTaluk" id="rTaluk" class="form-control" data-parsley-required data-parsley-required-message="Select Taluk">
                                <option value="">Select Taluk</option>
                                @foreach($taluka as $taluk)

                                <option value="{{$taluk->talukCode}}">{{$taluk->talukName}}</option>

                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>No of Respondants<span class="mandatory">*</span></label>
                              <input class="form-control" name="noOfRes" type="text" id="noOfRes"  value="" data-parsley-required data-parsley-required-message="Enter No of Respondants" data-parsley-minlength="1" data-parsley-minlength-message="No of Respondants Should have minimum 1 digit" data-parsley-maxlength="3" data-parsley-maxlength-message="No of Respondants Should have maximum 3 digit">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <table id="myTable" class="table order-list" style="width:100%;" >
                      <thead >
                        <tr style="background-color: #3c8dbc;color:#fff" >
                          <td class="col-sm-1">Sr.No</td>
                        <td class="col-sm-10">Relief Sought</td >
                        <td class="col-sm-1"></td>
                      </tr>
                    </thead>
                    <tbody id="results2">
                      <tr>
                        <td class="col-sm-1" name="counter[]" class="counter">
                          1
                        </td>
                        <td class="col-sm-10">
                          <input type="text" name="reliefsought[]" class="reliefsought form-control" data-parsley-required data-parsley-required-message="Enter Relief Sought" data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Relief Sought Accepts Only Characters"/>
                        </td>
                        <td  class="col-sm-1"><input type="button" class="btn btn-sm btn-primary " id="addrow" value="+" /></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2" style="float: right;">
                    <input type="hidden" name="sbmt_case" id="sbmt_case" value="A">
                    <a href="#" class="btn btn-md btn-primary btnNext">Next</a>

                  </div>
                </div>
              </div>

            </div>
          </form>
        </div>
        <div class="tab-pane" id="tab_2">
          <form  id="receiptForm" action="receiptDataStore" data-parsley-validate>
            <div class="panel panel-primary">
              <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
              <div class="panel panel-heading">Details Of Receipt</div>
              <div class="panel panel-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Receipt No.<span class="mandatory">*</span></label>
                      <input class="form-control" name="receiptNo" type="text" id="receiptNo" data-parsley-required data-parsley-required-message="Enter Receipt No." data-parsley-minlength='1' data-parsley-minlength-message="Receipt No. Should have Minimum 1 digit" data-parsley-maxlength='10' data-parsley-maxlength-message="Receipt No. Should have Maximum 5 digit">
                      <input type="hidden" id="recpApplYear" name="recpApplYear" value="" placeholder="applY">

                       <input type="hidden" id="recAppId" name="recAppId"  value="" placeholder="apptype">
                      <input type="hidden" name="receiptSrno" id="receiptSrno">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Receipt Date</label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="receiptDate" class="form-control pull-right datepicker" id="receiptDate" data-parsley-required data-parsley-required-message="Enter Receipt Date." data-parsley-errors-container="#error1">
                      </div>
                      <span id="error1"></span>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <label>Applicant Name in Receipt</label>
                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch1" data-toggle="dropdown"><span class="title_sel1">Select Title</span> <span class="selection1"></span>
                        <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu dropdown_all1" >
                          @foreach($nameTitle as $title)
                          <li ><a value="{{$title->titleName}}">{{$title->titleName}}</a></li>
                          @endforeach
                          {{--  <li><a href="#">Mr.</a></li>
                          <li><a href="#">Miss.</a></li> --}}
                        </ul>
                      </div>
                      <!-- /btn-group -->
                      <input type="hidden" class="form-control" id="applTitle" name="applTitle">
                      <input type="text" class="form-control" id="applName" name="applName" data-parsley-required data-parsley-required-message="Enter Applicant Name."
                      data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Applicant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error">
                    </div>
                    <span id="error"></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Amount</label>
                      <input class="form-control" name="recpAmount" type="number" id="recpAmount" data-parsley-required data-parsley-required-message="Enter Receipt Amount"  data-parsley-minlength="2"  data-parsley-minlength-message="Receipt Amount Should have Minimum 2 Digits" data-parsley-maxlength='5' data-parsley-maxlength-message="Receipt Amount Accepts only 5 digits">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group" id="submit_div">
                      <br>
                      <input value="A" name="sbmt_value" id="sbmt_value" type="hidden">
                      <input type="button" name="recpSubmit" id="recpSubmit" class="btn btn-md btn-primary" value="Add List">
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
                        <td class="col-md-2">Name of Applicant</td>
                        <td class="col-md-2">Amount</td>
                      </tr>
                    </thead>
                    <tbody>

                      <tr id="receiptrel">

                      </tr>

                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-md-1" style="float:left;">
                    <a href="#" class="btn btn-md btn-primary btnPrevious">Previous</a>
                  </div>
                  <div class="col-md-9"></div>
                  <div class=" col-md-2" style="float: right;">
                    <a href="#" class="btn btn-md btn-primary " id="recpNext">Next</a>
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
                <input type="hidden" id="applicantStartSrNo" name="applicantStartSrNo"  value="">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Is Single Advocate<span class="mandatory">*</span></label>
                      <br>
                      <label class="radio-inline">
                        <input type="radio" name="isAdvocate" id="isAdvocate" value="Y" >Yes
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="isAdvocate" id="isAdvocate" value="N" >No
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="isAdvocate" id="isAdvocate" value="NA" checked>N/A
                      </label>
                      <input type="hidden" name="" id="">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>Applicant Name<span class="mandatory">*</span></label>
                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch2" data-toggle="dropdown"><span class="title_sel2" >Select Title</span> <span class="selection2"></span>
                        <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu dropdown_all2"  >
                          @foreach($nameTitle as $title)
                          <li ><a value="{{$title->titleName}}">{{$title->titleName}}</a></li>
                          @endforeach
                        </ul>
                      </div>
                      <!-- /btn-group -->
                      <input type="hidden" class="form-control" id="applicantTitle" name="applicantTitle" >
                      <input type="text" class="form-control" id="applicantName" name="applicantName" data-parsley-required data-parsley-required-message="Enter Applicant Name."
                      data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Applicant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error9">
                    </div>
                    <span id="error9"></span>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Relation</label>
                      <select class="form-control" name="relationType" type="text" id="relation" data-parsley-required data-parsley-required-message="Select Relation">
                        <option value="">Select Relation</option>
                        @foreach($relationTitle as $relation)
                        <option value="{{$relation->relationTitle}}">{{$relation->relationName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <label>Name of Relation<span class="mandatory">*</span></label>
                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch3" data-toggle="dropdown"><span class="title_sel3">Select Title</span> <span class="selection3"></span>
                        <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu dropdown_all3" >
                          @foreach($nameTitle as $title)
                          <li ><a value="{{$title->titleName}}">{{$title->titleName}}</a></li>
                          @endforeach
                        </ul>
                      </div>
                      <!-- /btn-group -->
                      <input type="hidden" class="form-control" id="relationTitle" name="relationTitle">
                      <input type="text" class="form-control" id="relationName" name="relationName"  data-parsley-required data-parsley-required-message="Enter Relation Name."
                      data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Relation Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Relation Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Relation Name Accepts only 100 characters" data-parsley-errors-container="#error11">
                    </div>
                    <span id="#error11"></span>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Gender<span class="mandatory">*</span></label>
                      <select class="form-control" name="gender" type="text" id="gender" data-parsley-required data-parsley-required-message="Select Gender">
                        <option value="">Select Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="T">Transgender</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Age<span class="mandatory">*</span></label>
                      <input class="form-control" name="applAge" type="number" id="applAge" data-parsley-maxlength="3" data-parsley-maxlength-message="Age Allows only 3 digits"   data-parsley-minlength="2" data-parsley-minlength-message="Age Allows Minimum 2 digit"
                        data-parsley-required  data-parsley-required-message="Enter Age">
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
                      <label>Department Type</label>
                      <select class="form-control" name="applDeptType" type="text" id="applDeptType" data-parsley-required data-parsley-required-message="Select Department Type">
                        <option value="">Select Department</option>
                        @foreach($deptType as $dept)
                        <option value="{{$dept->DeptTypeCode}}">{{$dept->DeptType}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Name Of Department</label>
                      <select class="form-control" name="nameOfDept" type="text" id="nameOfDept" data-parsley-required data-parsley-required-message="Select Department Name">
                        <option value="">Select Department Name</option>
                        @foreach($deptName as $deptname)
                        <option value="{{$deptname->departmentCode}}">{{$deptname->departmentName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Designation Of Applicant</label>
                      <input class="form-control" name="desigAppl" type="text" id="desigAppl" data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Designation Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Designation  Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Designation Accepts only 100 characters" data-parsley-required data-parsley-required-message="Enter Designation" >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Address</label>
                      <textarea class="form-control"  name="addressAppl" type="text" id="addressAppl" data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Address Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Address  Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Address Accepts only 100 characters" data-parsley-required data-parsley-required-message="Enter Address"></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Pincode</label>
                      <input class="form-control" name="pincodeAppl" type="text" id="pincodeAppl" data-parsley-required data-parsley-required-message="Enter Pincode" data-parsley-pattern="/^[1-9][0-9]{5}$/" data-parsley-pattern-message="Invalid Pincode">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Taluk</label>
                      <select class="form-control" name="talukAppl" type="text" id="talukAppl" data-parsley-required data-parsley-required-message="Select taluk">
                        <option value="">Select Taluk</option>
                        @foreach($taluka as $taluk)
                        <option value="{{$taluk->talukCode}}">{{$taluk->talukName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>District</label>
                      <select class="form-control" name="districtAppl" type="text" id="districtAppl" data-parsley-required data-parsley-required-message="Select District">
                        <option value="">Select District</option>
                        @foreach($district as $dist)
                        <option value="{{$dist->distCode}}">{{$dist->distName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Mobile No.</label>
                      <input class="form-control" name="applMobileNo" type="number" id="applMobileNo" data-parsley-pattern="/^[6-9]\d{9}$/" data-parsley-pattern-message="Invalid Mobile No." data-parsley-required data-parsley-required-message="Enter Mobile No." >
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Email Id</label>
                      <input class="form-control" name="applEmailId" type="email" id="applEmailId" data-parsley-type="email" data-parsley-type-message="Invalid Email Id" data-parsley-required data-parsley-required-message="Enter Email Id">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4"  id="party_div">
                    <div class="form-group">
                      <label>Party in Person<span class="mandatory">*</span></label><br>
                      <label class="radio-inline">
                        <input type="radio" name="partyInPerson" value="Y" >Yes
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="partyInPerson" value="N"checked>No
                      </label>
                    </div>
                    <span id="error12"></span>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Is Main Party?<span class="mandatory">*</span></label><br>
                      <label class="radio-inline">
                        <input type="radio" name="isMainParty" value="Y"   checked>Yes
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="isMainParty" value="N" >No
                      </label>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
                      <input list="browsers"  class="form-control" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                      data-parsley-pattern="/^[a-zA-Z0-9 ]*$/" data-parsley-pattern-message="Applicant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Advocate Bar Reg No. Accepts only 100 characters">
                      <datalist id="browsers">
                      <?php foreach($adv as $advocate){?>
                      <option value="<?php echo $advocate->advocateRegNo;?>"><?php echo $advocate->advocateRegNo.'-'.$advocate->advocateName;?></option>
                      <?php }?>
                      </datalist>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <label>Advocate Name<span class="mandatory">*</span></label>
                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch4" data-toggle="dropdown"><span class="title_sel4">Select Title</span> <span class="selection4"></span>
                        <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu dropdown_all4" disabled>
                          @foreach($nameTitle as $title)
                          <li ><a value="{{$title->titleName}}">{{$title->titleName}}</a></li>
                          @endforeach
                        </ul>
                      </div>
                      <!-- /btn-group -->
                      <input type="hidden" class="form-control" id="advTitle" name="advTitle" disabled>
                      <input type="text" class="form-control" id="advName" name="advName" disabled>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Address<span class="mandatory">*</span></label><br>
                      <textarea class="form-control" name="advRegAdrr" type="text" id="advRegAdrr" disabled></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Pincode<span class="mandatory">*</span></label><br>
                      <input class="form-control" name="advRegPin" type="text" id="advRegPin" disabled>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Taluk</label>
                      <select name="advRegTaluk" id="advRegTaluk" class="form-control" disabled>
                        {{--  @foreach($taluka as $taluk)
                        <option value="{{$taluk->talukCode}}">{{$taluk->talukName}}</option>
                        @endforeach --}}
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>District</label>
                      <select name="advRegDistrict" id="advRegDistrict" class="form-control" disabled>
                        {{--   @foreach($district as $dist)
                        <option value="{{$dist->distCode}}">{{$dist->distName}}</option>
                        @endforeach --}}
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row"  style="float: right;" id="add_apl_div">
                  <div class="col-sm-12 text-center">
                    <input type="hidden" name="sbmt_applicant" id="sbmt_applicant" value="A">
                    <input type="button" id="saveApplicant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Add List">
                    <button id="btnClear" type="reset" class="btn btn-danger btnClear btn-md center-block" Style="width: 100px;" OnClick="btnClear_Click" >Cancel</button>
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
                  <div class=" col-md-2" style="float: right;">
                    <a  class="btn btn-md btn-primary" id="applNext">Next</a>
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
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <input class="form-control" name="resApplId" type="hidden" id="resApplId">
                      <input class="form-control" name="resStartNo" type="hidden" id="resStartNo">
                      <input class="form-control" name="resSerilNo" type="hidden" id="resSerilNo">
                       {{-- <input class="form-control" name="resApplYear" type="text" id="resApplYear"> --}}
                       <input class="form-control" name="noOfResCount" type="hidden" id="noOfResCount">
                      <label>Is Single Advocate<span class="mandatory">*</span></label>
                      <br>
                      <label class="radio-inline">
                        <input type="radio" name="isResAdvocate" id="isResAdvocate" value="Y" >Yes
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="isResAdvocate" id="isResAdvocate" value="N" >No
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="isResAdvocate" id="isResAdvocate" value="NA" checked>NA
                      </label>
                      <input type="hidden" name="resAdvName" id="resAdvName">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>Name Of Respondant<span class="mandatory">*</span></label>
                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch5" data-toggle="dropdown"><span class="title_sel5">Select Title</span> <span class="selection5"></span>
                        <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu dropdown_all5" >
                          @foreach($nameTitle as $title)
                          <li ><a value="{{$title->titleName}}">{{$title->titleName}}</a></li>
                          @endforeach
                        </ul>
                      </div>
                      <!-- /btn-group -->
                      <input type="hidden" class="form-control" name="respondantTitle" id="respondantTitle">
                      <input type="text" class="form-control" name="respondantName" id="respondantName">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Relation</label>
                      <select class="form-control" name="resReltaion" type="text" id="resReltaion">
                        <option value="">Select Relation</option>
                        @foreach($relationTitle as $relation)
                        <option value="{{$relation->relationTitle}}">{{$relation->relationName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <label>Name of Relation<span class="mandatory">*</span></label>
                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch6" data-toggle="dropdown"><span class="title_sel6">Select Title</span> <span class="selection6"></span>
                        <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu dropdown_all6" >
                          @foreach($nameTitle as $title)
                          <li ><a value="{{$title->titleName}}">{{$title->titleName}}</a></li>
                          @endforeach
                        </ul>
                      </div>
                      <!-- /btn-group -->
                      <input type="hidden" class="form-control" name="resRelTitle" id="resRelTitle">
                      <input type="text" class="form-control" name="resRelName" id="resRelName">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Gender<span class="mandatory">*</span></label>
                      <select class="form-control" name="resGender" type="text" id="resGender">
                        <option value="">Select Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="T">Transgender</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Age<span class="mandatory">*</span></label>
                      <input type="text" name="resAge" id="resAge" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Department Type</label>
                      <select class="form-control" name="resDeptType" type="text" id="resDeptType">
                        <option value="">Select Department Type</option>
                        @foreach($deptType as $dept)
                        <option value="{{$dept->DeptTypeCode}}">{{$dept->DeptType}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Name of Department</label>
                      <select name="resnameofDept" id="resnameofDept" class="form-control">
                        @foreach($deptName as $deptname)
                        <option value="{{$deptname->departmentCode}}">{{$deptname->departmentName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Designation of Respondant</label>
                      <input type="text" name="resDesig" class="form-control" id="resDesig">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Address</label>
                      <textarea type="text" name="resAddress2" id="resAddress2" class="form-control" ></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Pincode</label>
                      <input type="text" name="respincode2" id="respincode2" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Taluk</label>
                      <select name="resTaluk" id="resTaluk" class="form-control">
                        <option value="">Select Taluk</option>
                        @foreach($taluka as $taluk)
                        <option value="{{$taluk->talukCode}}">{{$taluk->talukName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>District</label>
                      <select name="resDistrict" id="resDistrict" class="form-control">
                        <option value="">Select District</option>
                        @foreach($district as $dist)
                        <option value="{{$dist->distCode}}">{{$dist->distName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Mobile No.</label>
                      <input class="form-control" name="resMobileNo" type="text" id="resMobileNo">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Email Id</label>
                      <input class="form-control" name="resEmailId" type="text" id="resEmailId">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
                      <input list="browsers1"  class="form-control" name="resadvBarRegNo" type="text" id="resadvBarRegNo">
                      <datalist id="browsers1">
                      <?php foreach($adv as $advocate){?>
                      <option value="<?php echo $advocate->advocateRegNo;?>"><?php echo $advocate->advocateRegNo.'-'.$advocate->advocateName;?></option>
                      <?php }?>
                      </datalist>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label>Advocate Name<span class="mandatory">*</span></label>
                    <div class="input-group input-group-sm">
                      <div class="input-group-btn">
                        <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch7" data-toggle="dropdown"><span class="title_sel7">Select Title</span> <span class="selection7"></span>
                        <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu dropdown_all7" disabled>
                          @foreach($nameTitle as $title)
                          <li ><a value="{{$title->titleName}}">{{$title->titleName}}</a></li>
                          @endforeach
                        </ul>
                      </div>
                      <!-- /btn-group -->
                      <input type="hidden" class="form-control" name="respAdvTitle" id="respAdvTitle" disabled>
                      <input type="text" class="form-control" name="respAdvName" id="respAdvName" disabled>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Address<span class="mandatory">*</span></label><br>
                      <textarea class="form-control" name="resadvaddr" type="text" id="resadvaddr" disabled></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Pincode<span class="mandatory">*</span></label><br>
                      <input class="form-control" name="resadvpincode" type="text" id="resadvpincode" disabled>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Taluk</label>
                      <select name="resadvtaluk" id="resadvtaluk" class="form-control" readonly>
                        {{--  <option value="">Select Taluk</option>
                        @foreach($taluka as $taluk)
                        <option value="{{$taluk->talukCode}}">{{$taluk->talukName}}</option>
                        @endforeach --}}
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
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
                </div>
                <div id="resAdvocateAddr">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Is Govt Advocate<span class="mandatory">*</span></label>
                        <label class="radio-inline">
                          <input type="checkbox" name="isGovtAdv" value="Y" checked>
                        </label>
                      </div>&nbsp;
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Is Main Repondant<span class="mandatory">*</span></label>
                        <label class="radio-inline">
                          <input type="radio" name="isMainRes" value="Y" >YES
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="isMainRes" value="N" >NO
                        </label>
                      </div>&nbsp;
                    </div>
                  </div>
                </div>
                <div class="row"  style="float: right;" id="res_sbmt_div">
                  <div class="col-sm-12 text-center">
                    <input type="hidden" name="sbmt_respondant" id="sbmt_respondant" value="A">
                    <input type="button" id="saveRespondant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;"  value="Add List">
                    <button id="btnClear" type="reset" class="btn btn-danger btnClear btn-md center-block" Style="width: 100px;" OnClick="btnClear_Click" >Cancel</button>
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
                  <div class="col-md-9"></div>
                  <div class=" col-md-2" style="float: right;">
                    <a  class="btn btn-md btn-primary" id="respNext">Next</a>
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
                <div class="row">
                  <table id="applIndexTbl" class="table application-list">
                    <thead  style="background-color: #3c8dbc;color:#fff;">
                      <tr>
                        <td class="col-sm-1">Sr.No</td>
                        <td  class="col-xs-4">Particulars of documents</td>
                        <td class="col-xs-1">Start</td>
                        <td class="col-xs-1">End Page</td>
                      <td class="col-xs-1"></a>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="col-sm-1">
                        <input type="hidden" name="count[]" value="1">1
                      </td>
                      <td class="col-xs-4">
                        <textarea type="mail" name="partOfDoc[]"  id="partOfDoc" class="form-control"></textarea>
                      </td>
                      <td class="col-xs-1">
                        <input type="text" name="start[]" id="start"  class="form-control"/>
                      </td>
                      <td class="col-xs-1">
                        <input type="text" name="endPage[]" id="endPage"  class="form-control"/>
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
            </div>
            <div class="row"  style="float: right;">
              <div class="col-sm-12 text-center">
                <input  id="saveAplicantionIndex" type="button" class="btn btn-primary btn-md center-block btnSearch" Style="width: 100px;" value="SAVE" >
                <button id="" type="reset" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" OnClick="btnClear_Click" >Cancel</button>
              </div>
            </div>
          </form>
        </div>
    </div>
        <!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>

<script src="js/receipt/receipt.js"></script>

</section>
@endsection
