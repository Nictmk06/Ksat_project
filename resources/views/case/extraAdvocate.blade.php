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
     <form role="form" id="extraAdvocateForm" action="{{ route('ExtraAdvocateController.store') }}" data-parsley-validate>
    <div class="panel  panel-primary">
      <div class="panel panel-heading">
        <h7 >Details of Application</h7>
      </div>

      <div class="panel panel-body">

          <div class="row">
            <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="col-md-4">
              <div class="form-group">
                <label>Type of Application<span class="mandatory">*</span></label>
                <input type='hidden' name='extra' value='extraadv' id='flag'>
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



        <div class="panel  panel-primary">
          <div class="panel panel-heading">
            <h7 >Details of Advocate</h7>
          </div>

          <div class="panel panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Extra Advocate For<span class="mandatory">*</span></label>
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
              <div class="col-md-4">
                <div class="form-group">
                  <label>Advocate Bar Reg No.<span class="mandatory">*</span></label>

                  <input list="browsers1"  class="form-control number zero" name="advBarRegno" type="text" id="advBarRegno" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                  data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="AdvocateBar Reg No.  Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters"maxlength="20" data-parsley-trigger='keypress' data-parsley-errors-container='#errorAdv' >
                  <datalist id="browsers1">
                  <?php foreach($adv as $advocate){?>
                  <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                  <?php }?>
                  </datalist>

                  <span id="errorAdv"></span>
                </div>
              </div>

            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Enrolled On<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="enrolleddate" class="form-control pull-right datepicker" id="enrolleddate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Invalid Enrolled On" value=""  data-parsley-required-message="Enter Enrolled On"  data-parsley-errors-container='#enrollerror'>
                  </div>
                  <span id="enrollerror"></span>
                </div>
              <input type='hidden' name="extraadvcode" id='extraadvcode'>
              <input type='hidden' name='appid' id='applid'>

            </div>
              <div class="col-md-4">
              <div class="form-group">
                <label>Display Adocate in causelist<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="isCauseList" class='isCauseList' value="Y"   >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="isCauseList" class='isCauseList' value="N" checked>No
                </label>
              </div>
               </div>
               <div class="col-md-4">
              <div class="form-group">
                <label>Advocate Active Status<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="advocateStatus" class='advocateStatus'   value="Y"   >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="advocateStatus"  class='advocateStatus' value="N" checked>No
                </label>
              </div>
            </div>
           </div>
           <div class='row'>
            <div class="col-md-4">
              <div class="form-group">
                <label>Remarks</label>
                 <textarea class="form-control" name="remarks" id="remarks"  data-parsley-required-message="Enter Remarks" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remarks" data-parsley-trigger='keypress'></textarea>
              </div>
            </div>
           </div>
              <div class="row"  style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
                  <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
                  <input type="button" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div><br><br>
              <div class="row">
                <table id="myTable" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                    <!--   <td>Sr.no</td> -->
                     <td>Applicant/Respondant</td>
                      <td>Enrollment No</td>
                    <td>Name of Advocate</td >
                    <td>Enrolled On</td>

                    <td>Active</td>
                    <td>Display</td>
                    <td>Remarks</td>
                  </tr>
                </thead>
                <tbody id="results2" style="">

                </tbody>
              </table>
            </div>



          </div>
        </div>
      </div>
       </form>
      <!-- /.tab-pane -->
      <script src="js/jquery.min.js"></script>
      <script src="js/casemanagement/extraadvocate.js"></script>
    </section>
    @endsection
