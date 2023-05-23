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
    <div class="modal fade" id="modal-status">
      <form role="form" id="extraAdvocateForm" action="statusDataStore" data-parsley-validate>
      <div class="modal-dialog modal-md">
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
            <input id="modal_flag" type="hidden" name="modal_flag" value="">

           <div class="row">
              <div class="col-md-6">
              <div class="form-group">
                <label> Status<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="applStatus" class='applStatus'   value="Y"   >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="applStatus"  class='applStatus' value="N" checked>No
                </label>
              </div>
            </div>
             <div class="col-md-6">
              <div class="form-group">
                <label> Is Main Party<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="isMainParty" class='isMainParty'   value="Y"   >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="isMainParty"  class='isMainParty' value="N" checked>No
                </label>
              </div>
            </div>
           </div>
           <div class='row'>
            <div class="col-md-6">
              <div class="form-group">
                <label>Party Status<span class="mandatory">*</span></label>
                <select class="form-control" name="partystatus" id="partystatus"  >
                  <option value="">Select Party Status</option>
                  @foreach($partystatus as $status)
                  <option value="{{$status->partystatus}}">{{$status->partystatus}}</option>
                  @endforeach
                </select>
              </div>
            </div>
             <div class="col-md-6">
              <div class="form-group">
                  <label>Date</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="statusdate" class="form-control  number pull-right datepicker" id="statusdate"   data-parsley-required data-parsley-required-message='Enter  Date' data-parsley-errors-container='#statuserror'>
                  </div>
                  <span id="statuserror"></span>
                </div>
            </div>
           </div>
          <div class='row'>
             <div class="col-md-6">
              <div class="form-group">
                <label>Remarks<span class="mandatory">*</span></label>
                 <textarea class="form-control" name="remarks" id="remarks" data-parsley-required  data-parsley-required-message="Enter Remarks" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remarks" data-parsley-trigger='keypress'></textarea>
              </div>
            </div>
          </div>
          </div>
          <div class="modal-footer">

            <button type="button" class="btn btn-primary" id="saveApllStatus">SAVE</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
    </form>
      <!-- /.modal-dialog -->
    </div>

     <div class="modal fade" id="modal-add">
      <form role="form" id="addAppResDet" action="extraapplicantStore" data-parsley-validate>
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='res-title'>Add Applicant</h4>
          </div>

          <div class="modal-body">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>

            <input class="applApplId" type="hidden" name="applApplId" value="">
              <input id="applicantStartSrNo" type="hidden" name="applicantStartSrNo" value="">
          {{--   <input id="modal_srno" type="hidden" name="modal_srno" value="">
            <input id="modal_flag" type="hidden" name="modal_flag" value=""> --}}
            <div class="row">
            {{-- <div class="col-md-4">
              <div class="form-group">
                <label>Is Main Party?<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="isMainParty" value="Y"   checked>Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="isMainParty" value="N" >No
                </label>
              </div>

            </div> --}}
            <div class="col-md-4">
              <div class="form-group">
                <label>Name<span class="mandatory">*</span></label>
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
                <input class="form-control number" name="applAge" type="number" id="applAge"
				onkeyup="this.value = minmax(this.value, 0, 100)"
                data-parsley-required  data-parsley-required-message="Enter Age" data-parsley-trigger='keypress'>
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
                <label>Designation<span class="mandatory">*</span></label>
               {{--  <div class="input-group date"> --}}
                  <select class="form-control" name="desigAppl"  id="desigAppl" data-parsley-required data-parsley-required-message="Select Designation" >
                    <option value="">Select Designation</option>
                    @foreach($appldesig as $desig)
                    <option value="{{$desig->desigcode}}">{{$desig->designame}}</option>
                    @endforeach
                  </select>
                  {{-- <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="desigadd">
                    <i class="fa fa-plus"></i>
                  </div> --}}

               {{--  </div> --}}

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
               {{--  <div class="input-group date"> --}}
                  <input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                  data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20"  data-parsley-errors-container='#errorAdv1'>
                  <datalist id="browsers">
                  <?php foreach($adv as $advocate){?>
                  <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                  <?php }?>
                  </datalist>
                  {{-- <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="advocateAdd">
                    <i class="fa fa-plus"></i>
                  </div>

                </div> --}}

              </div>
              <span id='errorAdv1'></span>
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
          </div>


          </div>
          <div class="modal-footer">

             <div class="col-sm-12">
              <input type="hidden" name="sbmt_applicant" id="sbmt_applicant" value="A">
              <input type="button" id="saveApplicant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
              <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" id="clearApplicant" value="Cancel">
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
    </form>
      <!-- /.modal-dialog -->
    </div>
     <div class="modal fade" id="modal-res">
      <form role="form" id="addResDet" action="extrarespondantStore" data-parsley-validate>
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='res-title'>Add Respondant</h4>
          </div>

          <div class="modal-body">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>

            <input class="resApplId" type="hidden" name="resApplId" value="">
              <input id="resStartNo" type="hidden" name="resStartNo" value="">
          {{--   <input id="modal_srno" type="hidden" name="modal_srno" value="">
            <input id="modal_flag" type="hidden" name="modal_flag" value=""> --}}
          <div class="row">
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
                <input type="number" name="resAge" id="resAge" onkeyup="this.value = minmax(this.value, 0, 100)"
				class="form-control number"
                data-parsley-required  data-parsley-required-message="Enter Age" data-parsley-trigger='keypress'>
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
               {{--  <div class="input-group date"> --}}
                  <select class="form-control" name="resDesig"  id="resDesig" data-parsley-required data-parsley-required-message="Select Designation" >
                    <option value="">Select Designation</option>
                    @foreach($appldesig as $desig)
                    <option value="{{$desig->desigcode}}">{{$desig->designame}}</option>
                    @endforeach
                  </select>
                {{--   <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="resdesigadd">
                    <i class="fa fa-plus"></i>
                  </div>

                </div> --}}

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
              {{--   <div class="input-group date"> --}}
                  <input list="browsers1"  class="form-control number zero" name="resadvBarRegNo" type="text" id="resadvBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                  data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="AdvocateBar Reg No.  Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters"maxlength="20" data-parsley-trigger='keypress' data-parsley-errors-container='#errorAdv' >
                  <datalist id="browsers1">
                  <?php foreach($adv as $advocate){?>
                  <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                  <?php }?>
                  </datalist>
                  {{-- <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="resadvocateAdd">
                    <i class="fa fa-plus"></i>
                  </div> --}}

               {{--  </div> --}}

              </div>
               <span id="errorAdv"></span>
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
            </div>
          </div>


          </div>
          <div class="modal-footer">

             <div class="col-sm-12">
               <input type="hidden" name="sbmt_respondant" id="sbmt_respondant" value="A">
              <input type="button" id="saveRespondant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;"  value="Save">
              <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" id="clearRespondant" value="Cancel">
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
    </form>
      <!-- /.modal-dialog -->
    </div>


     <form role="form" id="extraAdvocateForm" action="ExtraAdvocateController.store" data-parsley-validate>
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
                <input type='hidden' name='ARstatus' value='ARstatus' id='flag'>
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
            <h7 >Details of Status Change</h7>
          </div>

          <div class="panel panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Staus For<span class="mandatory">*</span></label>
                   <div class="input-group date">
                   <select class="form-control" name='' id='statusfor' data-parsley-trigger='change' data-parsley-required data-parsley-required-message='Select Status For' data-parsley-errors-container='#modlerror2'>
                    <option value=''>Select Status For</option>
                    <option value='A'>Applicant</option>
                    <option value='R'>Respondant</option>
                  </select>
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="addAppRes">
                      <i class="fa fa-plus"></i>
                    </div>

                  </div>
                  <span id="modlerror2"></span>
                  {{--  --}}
                </div>
              </div>

            </div>



              {{-- <div class="row"  style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
                  <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
                  <input type="button" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div><br><br> --}}
              <div class="row">
                <table id="myTable4" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Sr.no</td>
                      <td>Name</td>
                      <td>Status</td>
                      <td>IsMainParty</td>
                      <td>PartyStatus</td>
                      <td>Date</td>
                      <td>Remarks</td>
                  </tr>
                </thead>
                <tbody id="results7" >

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
