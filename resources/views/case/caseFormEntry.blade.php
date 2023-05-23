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
  #btnSearch,
#btnClear{
    display: inline-block;
    vertical-align: top;
}
  </style>
  <section class="content">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Case Details</a></li>
        <li><a href="#tab_2" data-toggle="tab">Receipt Details</a></li>
        <li><a href="#tab_3" data-toggle="tab">Applicant Details</a></li>
        <li><a href="#tab_4" data-toggle="tab">Respondant Details</a></li>
        <li><a href="#tab_5" data-toggle="tab">Application Index</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          {{--  <div class="box box-default"> --}}
            <!-- /.box-header -->
            <div class="panel  panel-primary">
              <div class="panel panel-heading">
                <h7 >Details of Application</h7>
              </div>
              <form role="form">
                <div class="panel panel-body">
                 <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                         <label>KSAT Act<span class="mandatory">*</span></label>
                        <select class="form-control" name="actName" >
                          <option value="">Select Act</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                       <label>Section Name<span class="mandatory">*</span></label>
                        <select class="form-control" name="actSectionName" >
                          <option value="">Select Section Name</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label>Type of Application<span class="mandatory">*</span></label>
                        <select class="form-control" name="applTypeName" >
                          <option value="">Select Applcation Type</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                       <label>Application Year:<span class="mandatory">*</span></label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" name="applYear" class="form-control pull-right" id="datepicker1">
                        </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                       <label>Application Start No<span class="mandatory">*</span></label></br>
                        <input type="text" class="form-control pull-right" id="applStartNo" name="applStartNo">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Application End No<span class="mandatory">*</span></label>
                        <input type="text" name="applEndNo" id="applEndNo" class="form-control">
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
                          <input type="text" name="dateOfAppl" class="form-control pull-right datepicker" id="dateOfAppl">
                        </div>
                    </div>
                    <div class="col-md-4">
                      <label>Registration Date<span class="mandatory">*</span></label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" name="dateOfAppl" class="form-control pull-right datepicker" id="dateOfAppl">
                        </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                         <label>Application Catergory<span class="mandatory">*</span></label>
                        <select class="form-control" name="applCatName" >
                          <option value="">Select Applcation Category</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Subject</label>
                        <textarea class="form-control"></textarea>
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
                              <label>Order No<span class="mandatory">*</span></label>
                              <input class="form-control" name="orderNo" type="text">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Order Date<span class="mandatory">*</span></label>
                              <div class="input-group date">
                                <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="orderDate" class="form-control pull-right datepicker" id="orderDate">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label>Issued By</label>
                              <input type="text" class="form-control pull-right" id="applcationNo" name="applcationNo">
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
                                <input class="form-control" name="interimPrayer" type="text">
                              </div>
                            </div>
                             <div class="col-md-4">
                              <div class="form-group">
                                 <label>Interim Order Prayed for:</label>
                                <textarea  class="form-control" name="interimOrder" type="text"></textarea>
                              </div>
                            </div>
                           </div>
                           <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Address for Service:</label>
                                <textarea  class="form-control" name="interimService" type="text"></textarea>
                              </div>
                            </div>

                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Pincode</label>
                                <input class="form-control" name="pincode" type="text">
                              </div>
                            </div>
                             <div class="col-md-4">
                              <div class="form-group">
                                   <label>Taluk</label>
                        <select name="resTaluk" id="resTaluk" class="form-control">
                          <option value="1">Taluk1</option>
                          <option value="1">Taluk2</option>
                        </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                 <label>District</label>
                        <select name="resDistrict" id="resDistrict" class="form-control">
                          <option value="1">District1</option>
                          <option value="1">District2</option>
                        </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <table id="myTable" class="table order-list" >
                        <thead>
                          <tr>
                            <td>Sr.No</td>
                            <td>Relief Sought</td>
                          </tr>
                        </thead>
                        <tbody id="results2">
                          <tr>
                            <td class="col-sm-1">
                              1
                            </td>
                            <td class="col-sm-10">
                              <input type="text" name="name" class="form-control" />
                            </td>
                            <td  class="col-sm-1"><input type="button" class="btn btn-sm btn-primary " id="addrow" value="+" /></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
              <div class="panel panel-primary">
                <div class="panel panel-heading">Details Of Receipt</div>
                <div class="panel panel-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Receipt No.<span class="mandatory">*</span></label>
                        <input class="form-control" name="receiptNo" type="text" id="receiptNo">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Receipt Date</label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" name="receiptDate" class="form-control pull-right datepicker" id="receiptDate">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Applicant Name in Receipt</label>
                        <input class="form-control" name="applRecpName" type="text" id="applRecpName">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Amount</label>
                        <input class="form-control" name="recpAmount" type="text" id="recpAmount">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <br>
                        <input type="button" name="recpSubmit" id="recpSubmit" class="btn btn-md btn-primary" value="Add List">
                      </div>

                  </div>
                  </div>
                  <div class="row">
                    <table id="example1" class="table table-bordered table-striped receipt-list" >
                      <thead>
                        <tr>
                          <td>Sr.No</td>
                          <td>Receipt No.</td>
                          <td>Name of Applicant</td>
                          <td>Amount</td>
                        </tr>
                      </thead>
                      <tbody id="results1">
                        <tr>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="row">
                    <div class=" col-md-1" style="float:left;">
                      <a href="#" class="btn btn-md btn-primary btnPrevious">Previous</a>
                    </div>
                    <div class="col-md-9"></div>
                    <div class=" col-md-2" style="float: right;">
                      <a href="#" class="btn btn-md btn-primary btnNext">Next</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
              <div class="panel panel-primary">
                <div class="panel panel-heading">Details Of Applicant</div>
                <div class="panel panel-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>No of Applicants.<span class="mandatory">*</span></label>
                        <input class="form-control" name="receiptNo" type="text" id="noOfAppl">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Advocate<span class="mandatory">*</span></label>
                        <input class="form-control" name="advocateName" type="text" id="advocateName">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Applicant Name<span class="mandatory">*</span> </label>
                        <input class="form-control" name="applName" type="text" id="applName">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Gender<span class="mandatory">*</span></label>
                        <select class="form-control" name="gender" type="text" id="gender">
                          <option value="">Select Gender</option>
                          <option value="M">Male</option>
                          <option value="F">Female</option>
                          <option value="O">Others</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Age<span class="mandatory">*</span></label>
                        <input class="form-control" name="applAge" type="text" id="applAge">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Relation</label>
                        <select class="form-control" name="relation" type="text" id="relation">
                          <option value="">Select Relation</option>
                          <option value="M">S/O</option>
                          <option value="F">W/O</option>
                          <option value="O">D/O</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name Of Relation</label>
                        <input class="form-control" name="relationName" type="text" id="relationName">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Department Type</label>
                        <select class="form-control" name="relation" type="text" id="relation">
                          <option value="">Select Department</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name Of Department</label>
                        <input class="form-control" name="nameOfDept" type="text" id="nameOfDept">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Designation Of Applicant</label>
                        <input class="form-control" name="nameOfDept" type="text" id="desigAppl">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control"  name="addressAppl" type="text" id="addressAppl"></textarea>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Pincode</label>
                        <input class="form-control" name="pincodeAppl" type="text" id="pincodeAppl">
                      </div>
                    </div>
                  </div>
                    <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Taluk</label>
                        <select class="form-control" name="talukAppl" type="text" id="talukAppl">
                          <option value="">Select Taluk</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>District</label>
                        <select class="form-control" name="districtAppl" type="text" id="districtAppl">
                          <option value="">Select Taluk</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Mobile No.</label>
                        <input class="form-control" name="mobileNo" type="text" id="mobileNo">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Email Id</label>
                        <input class="form-control" name="emailId" type="text" id="emailId">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Party in Person<span class="mandatory">*</span></label><br>
                        <label class="radio-inline">
                          <input type="radio" name="partyInPerson" value="Y" checked>Yes
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="partyInPerson" value="N">No
                        </label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Is Main Party?<span class="mandatory">*</span></label><br>
                        <label class="radio-inline">
                          <input type="radio" name="isMainParty" value="Y" checked>Yes
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="isMainParty" value="N">No
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row">

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
                        <input class="form-control" name="advBarRegNo" type="text" id="advBarRegNo">
                      </div>
                    </div>
                  </div>
                   <div id="advocateAddr" style="display: none;">
                  <div class="row">


                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Address<span class="mandatory">*</span></label><br>
                          <textarea class="form-control" name="advBarRegNo" type="text" id="advBarRegNo"></textarea>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Pincode<span class="mandatory">*</span></label><br>
                          <input class="form-control" name="advBarRegNo" type="text" id="advBarRegNo">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Taluk</label>
                        <select name="applTaluk" id="applTaluk" class="form-control">
                          <option value="1">Taluk1</option>
                          <option value="1">Taluk2</option>
                        </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>District</label>
                        <select name="applDistrict" id="applDistrict" class="form-control">
                          <option value="1">District1</option>
                          <option value="1">District2</option>
                        </select>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-1"  style="float: right;">
                      <a href="#" class="btn btn-md btn-primary" id="saveApplicant">Save</a>
                    </div>
                  </div><br>
                  <div class="row" id="applcant_div">
                    <table id="example2" class="table table-bordered table-striped applicant-list" >
                      <thead>
                        <tr>
                          <td>Sr.No</td>
                          <td>Applicants</td>
                          <td>Advocates</td>
                        </tr>
                      </thead>
                      <tbody id="results">
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
                      <a  class="btn btn-md btn-primary btnNext">Next</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab_4">
              <div class="panel panel-primary">
                <div class="panel panel-heading">Details Of Respondants</div>
                <div class="panel panel-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Address For Service<span class="mandatory">*</span></label>
                        <textarea class="form-control" name="addrForService" type="text" id="addrForService"></textarea>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Pincode</label>
                        <input class="form-control" name="resPincode" type="text" id="resPincode">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Taluk</label>
                        <select name="resTaluk" id="resTaluk" class="form-control">
                          <option value="1">Taluk1</option>
                          <option value="1">Taluk2</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>District</label>
                        <select name="resDistrict" id="resDistrict" class="form-control">
                          <option value="1">District1</option>
                          <option value="1">District2</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>No of Respondants<span class="mandatory">*</span></label>
                        <input class="form-control" name="noOfRes" type="text" id="noOfRes">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Advocate<span class="mandatory">*</span></label>
                        <input type="text" name="resAdvocate" id="resAdvocate" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name Of Respondant<span class="mandatory">*</span></label><br>
                        <div class="form-group">
                          <input class="form-control" name="resName" type="text" id="resName">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Gender<span class="mandatory">*</span></label>
                        <select class="form-control" name="resGender" type="text" id="resGender">
                          <option value="">Select Gender</option>
                          <option value="M">Male</option>
                          <option value="F">Female</option>
                          <option value="O">Others</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Age<span class="mandatory">*</span></label>
                        <input type="text" name="resAge" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Relation</label>
                        <select class="form-control" name="resReltaion" type="text" id="resReltaion">
                          <option value="">Select Gender</option>
                          <option value="M">Father</option>
                          <option value="F">Mother</option>
                          <option value="O">Sister</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name of Relation</label>
                        <input type="text" name="resrelName" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Department Type</label>
                        <select class="form-control" name="resDeptType" type="text" id="resDeptType">
                          <option value="">Select Department Type</option>
                          <option value="M">Dept1</option>
                          <option value="F">Dept2</option>
                          <option value="O">Dept3</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Name of Department</label>
                        <input type="text" name="resDeptName" class="form-control" >
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Designation of Respondant</label>
                        <input type="text" name="resDesig" class="form-control" >
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Address</label>
                        <textarea type="text" name="resAddress2" class="form-control" ></textarea>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" name="respincode2" class="form-control" >
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Taluk</label>
                        <select name="resTaluk" id="resTaluk" class="form-control">
                          <option value="1">Taluk1</option>
                          <option value="1">Taluk2</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>District</label>
                        <select name="resDistrict" id="resDistrict" class="form-control">
                          <option value="1">District1</option>
                          <option value="1">District2</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Is Govt Advocate<span class="mandatory">*</span></label>
                        <label class="radio-inline">
                          <input type="checkbox" name="isGovtAdv" value="Y" checked>
                        </label>
                      </div>&nbsp;
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-1"  style="float: right;">
                      <a  class="btn btn-md btn-primary" id="saveRespondant">Save</a>
                    </div>
                  </div><br>
                  <div class="row" id="respondant_div">
                    <table id="example3" class="table table-bordered table-striped respondant-list" >
                      <thead>
                        <tr>
                          <td>Sr.No</td>
                          <td>Respondants</td>
                          <td>Advocates</td>
                        </tr>
                      </thead>
                      <tbody id="results4">
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
                    <div class=" col-lg-2" style="padding-left: 172px;float: right;">
                      <a  class="btn btn-md btn-primary btnNext">Next</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab_5">
              <div class="panel panel-primary">
                <div class="panel panel-heading">Details Of Application Index</div>
                <div class="panel panel-body">
                  <div class="row">
                    <table id="applIndexTbl" class=" table application-list">
                      <thead>
                        <tr>
                          <td>Sr.No</td>
                          <td>Particulars of documents</td>
                          <td>start</td>
                          <td>End Page</td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="col-sm-1">
                            1
                          </td>
                          <td class="col-sm-4">
                            <input type="mail" name="partOfDoc"  id="partOfDoc" class="form-control"/>
                          </td>
                          <td class="col-sm-4">
                            <input type="text" name="start" id="start"  class="form-control"/>
                          </td>
                          <td class="col-sm-3">
                            <input type="text" name="endPage" id="endPage"  class="form-control"/>
                          </td>
                          <td class="col-sm-2"><a class="deleteRow2"></a>
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
        <button id="btnSearch" class="btn btn-primary btn-md center-block" Style="width: 100px;" OnClick="btnSearch_Click" >Save</button>
         <button id="btnClear" class="btn btn-danger btn-md center-block" Style="width: 100px;" OnClick="btnClear_Click" >Cancel</button>
     </div>

                </div>

            </div>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- nav-tabs-custom -->
    </div>
    <script src="js/jquery.min.js}"></script>
    <script src="js/jquery.slimscroll.min.js"></script>
    <script src="js/casemanagement/casemanagement.js"></script>
  </section>
  @endsection
