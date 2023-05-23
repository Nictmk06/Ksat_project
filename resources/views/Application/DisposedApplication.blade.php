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

  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')
<input type="hidden" id="groupsn" name="groupsn" value="<?php echo session()->get('estgroupsn'); ?>"> 
  <section class="content">
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
                    <input type="text" name="modal_adv_bar_reg_no" class="form-control" id="modal_adv_bar_reg_no" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                    data-parsley-pattern="^([a-zA-Z]{3})\/([0-9]{1,6})\/([0-9]{4})$" data-parsley-pattern-message="Please Enter Advocate Reg No in Correct Format" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20" >
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
            <button type="button" class="btn btn-primary" disabled="true" id="saveOtherAppl">Save</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

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

    <div>
	 <ul class="nav nav-tabs" id="myTab">
     <li style="float:right;" id="editApplication"> <input type="button"  id="" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Edit"></li>
     </ul>
     <form role="form" id="disposedApplicationForm" method="POST" action="{{ route('saveDisposedApplication') }}" data-parsley-validate>
      @csrf

    <div class="panel  panel-primary">
      <div class="panel panel-heading">
        <h7>Details of Application</h7>
      </div>
        <input class="form-control" name="reviewApplId1" type="hidden" id="reviewApplId1">
              <div class="panel panel-body">
                  <div class="row">
                  <div class="col-md-4">

                    <div class="form-group">
                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <label>Type of Application<span class="mandatory">*</span><span id="reviewAppl" style="color:red;"></span></label>
                      <select class="form-control" name="applTypeName" id="applTypeName" data-parsley-required data-parsley-required-message="Select Application Type" data-parsley-trigger='change'>
                        <option value="">Select Application Type</option>
                        @foreach($applicationType as $applType)

                        <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort.'-'.$applType->referflag}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Act<span class="mandatory">*</span></label>
                      <select class="form-control" name="actName" id="actName">
                        <option value="">Select Act</option>
                        @foreach($actDetails as $act)
                        <option value="{{$act->actcode}}">{{$act->actname}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Section Name<span class="mandatory">*</span></label>
                      <select class="form-control" name="actSectionName" id='actSectionName'>
                        <option value="">Select </option>
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
                        <input type="text" name="dateOfAppl" class="form-control pull-right datepicker" id="dateOfAppl"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Date Of Application Allows only digits" value=""  data-parsley-required-message="Enter Date Of Application"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
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
                        <input type="text" name="applYear"  id="applYear" class="form-control pull-right datepicker1"  data-parsley-date-format="YYYY"  data-parsley-trigger='keypress' >
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Application Start No<span class="mandatory">*</span></label></br>

					 <input type="text" class="form-control pull-right zero number" id="applStartNo"
					 name="applStartNo" maxlength="6"
                      data-parsley-required  data-parsley-required-message="Enter Application Start No"
					  data-parsley-minlength="1" data-parsley-minlength-message="Application No Should have minimum 1 digit"
					  data-parsley-maxlength="6"  data-parsley-maxlength-message="Application No Should have maximum 6 digit"
					  data-parsley-trigger='keypress' onblur="setendapp()">
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Application End No<span class="mandatory">*</span></label>
                      <input type="text" name="applEndNo" id="applEndNo" class="form-control zero number" maxlength="6"
                      data-parsley-pattern="/^[0-9]+$/"
                      data-parsley-required  data-parsley-required-message="Enter Application End No" data-parsley-minlength="1" data-parsley-minlength-message="Application No Should have minimum 1 digit"
					  data-parsley-maxlength="6"  data-parsley-maxlength-message="Application No Should have maximum 6 digit"
					  data-parsley-trigger='keypress'>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Registration Date<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="applnRegDate" class="form-control pull-right datepicker" id="applnRegDate" value=""  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits" value="" data-parsley-required  data-parsley-required-message="Enter Registration Date"  data-parsley-errors-container="#error5" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
                      </div>
                      <span id="error5"></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject Category<span class="mandatory">*</span></label>
                      <select class="form-control" name="applCatName" id="applCatName" data-parsley-required  data-parsley-required-message="Select Application Category" data-parsley-trigger='keypress'>
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
                      <label>No of Applicants.<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfAppl" id="noOfAppl" type="text"
					  id="noOfAppl" value="" data-parsley-required data-parsley-required-message="Enter No fo Applicants"
					  data-parsley-minlength="1" data-parsley-minlength-message="No of Applicants Should have minimum 1 digit"
					  data-parsley-maxlength="3" data-parsley-maxlength-message="No of Applicants Should have maximum 3 digit" data-parsley-trigger='keypress' >
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>No of Respondants<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfRes" type="text" id="noOfRes"
					  value="" data-parsley-required data-parsley-required-message="Enter No of Respondants" data-parsley-minlength="1"
					  data-parsley-minlength-message="No of Respondants Should have minimum 1 digit" data-parsley-maxlength="3"
					  data-parsley-maxlength-message="No of Respondants Should have maximum 3 digit" data-parsley-trigger='keypress'>
                    </div>
                  </div>
				  <div class="col-md-4">
                      <label>Disposed Date<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="applnDisposeDate" class="form-control pull-right datepicker" id="applnDisposeDate"
						value=""  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits"
						data-parsley-required  data-parsley-required-message="Enter Registration Date"  data-parsley-errors-container="#error5" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
                      </div>
                      <span id="error5"></span>
                    </div>
                </div>
			<div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Connected Applications</label>
                      <textarea class="form-control" name='connectedappl' id="connectedappl" ></textarea>
                    </div>
                  </div>
				  <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject<span class="mandatory">*</span></label>
                      <textarea class="form-control" name='applnSubject' id="applnSubject"  data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject'
                      ></textarea>
                    </div>
                  </div>
				   
                    <div class="col-md-4">
                        <div class="form-group">
                        <label>Author By</label>
                        <select name="authorby" id="authorby" class="form-control">
                          <option value=''>Select Author</option>
                          @foreach($benchjudge as $bench)
                          <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
                          @endforeach

                      </select>
                          <!-- <span id="error7"></span> -->
                        </div>
                    </div>
                </div>
           <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading" >Hearing History</div>
                    <div class="panel-body">
            <div class="row">


            <div class="col-md-4">
                <div class="form-group">
                  <label>Bench Type<span class="mandatory">*</span></label>
                  <select name="benchtypename" id="benchtypename" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Bench Type</option>
                  @foreach($Benches as $bench)
                  <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
                  @endforeach
                </select>
              </div>
            </div>



         <div class="col-md-4">
              <div class="form-group">
                <label>Bench <span class="mandatory">*</span></label>
                <select name="benchcode" id="benchcode" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA">
                  <option value=''>Select Bench</option>

                   @foreach($benchjudge as $bench)
                  <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
                  @endforeach

              </select>
            </div>
          </div>
		  
            <div class="col-md-4">
                <div class="form-group">
                  <label>Last Posted For<span class="mandatory">*</span></label>
                    <select class="form-control" name="lastpostedfor" type="text" id="lastpostedfor" data-parsley-required data-parsley-required-message="Select Posted For">><option value="" >Select Posted For</option>
              @foreach($purpose as $postedfor)
              <option value="{{$postedfor->purposecode}}">{{$postedfor->listpurpose}}</option>
              @endforeach
            </select>
              </div>
            </div>
        </div>

      <div class="row">





         <div class="col-md-4">
              <div class="form-group">
                <label>Last Action Taken <span class="mandatory">*</span></label>
                 <select class="form-control" name="lastorderPassed"  id="lastorderPassed" data-parsley-required data-parsley-required-message="Select Order Passed">
               <option value=''>Select Order Passed</option>
                @foreach($ordertype as $order)
                <option value="{{$order->ordertypecode}}">{{$order->ordertypedesc}}</option>
                  @endforeach
                </select>
                </div>
          </div>
        </div>
            <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading" >Details Of Applicant</div>
                    <div class="panel-body">
                      <div class="row">
                         <div class="col-md-4">
              <div class="form-group">
                <label>Applicant Name<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch2" data-toggle="dropdown">
					<span class="title_sel2" >Select Title</span> <span class="selection2"></span>
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
                <label>Department Type<span class="mandatory">*</span></label>
                <select class="form-control" name="applDeptType" type="text" id="applDeptType" data-parsley-required data-parsley-required-message="Select Department Type" data-parsley-trigger='keypress'>
                  <option value="">Select Department</option>
                  @foreach($deptType as $dept)
                  <option value="{{$dept->depttypecode}}">{{$dept->depttype}}</option>
                  @endforeach
                </select>
              </div>
              <span id="error15"></span>
            </div>
          </div>
           <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Name Of Department<span class="mandatory">*</span></label>
                <select class="form-control" name="applnameOfDept" type="text" id="applnameOfDept" data-parsley-required data-parsley-required-message="Select Department Name" data-parsley-trigger='keypress'>
                  <option value="">Select Department Name</option>
                  @foreach($deptName as $deptname)
                  <option value="{{$deptname->departmentcode}}">{{$deptname->departmentname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
			 <div class="col-md-4">
          <div class="form-group">
            <label>Advocate Bar Reg No.</label><br>
            <div class="input-group date">
              <input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20"  data-parsley-errors-container='#errorAdv1'>
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
          </div>
       
        <div class="row">
                  <div class="panel panel-primary">
                    <div class="panel-heading" >Details Of Respondant</div>
                    <div class="panel-body">
                      <div class="row">
                         <div class="col-md-4">
              <div class="form-group">
              <label>Name Of Respondant<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch5" data-toggle="dropdown">
					<span class="title_sel5">Select Title</span> <span class="selection5"></span>
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
             <div class="form-group">
            <label>Advocate Bar Reg No.</label><br>
            <div class="input-group date">
              <input list="browsers1"  class="form-control number zero" name="resadvBarRegNo" type="text" id="resadvBarRegNo" data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="AdvocateBar Reg No.  Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters"maxlength="20" data-parsley-trigger='keypress' data-parsley-errors-container='#errorAdv' >
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
          </div>
		</div>
   
             <div class="row" style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
				  <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
                  <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div><br><br>
          </div>
        </div>




      </div>
       </form>
       @if ($errors->any())
    <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
     </div>
      <!-- /.tab-pane -->
      <script src="js/jquery-3.4.1.js"></script>
<!--       <script src="{{asset('js/jquery.min.js')}}"></script> -->
      <script src="js/application/disposedApplication.js"></script>
	  <script>
function setendapp(){
	var applStartNo = $('#applStartNo').val();
	$('#applEndNo').val(applStartNo);
}
</script>
      <script>
      $("#advocateAdd").click(function(){
       $('#modal-add-advocate').modal('show');
       $("#typeOfadv").val('A');
     })
     $("#resadvocateAdd").click(function(){

       $('#modal-add-advocate').modal('show');
       $("#typeOfadv").val('R');
     })

      $("#saveAdvocate").click(function(){
    var form = $(this).closest("form").attr('id');
      $("#" + form).parsley().validate();
    if ($("#" + form).parsley().isValid()) {
      $.ajax({
      type: 'post',
      url: 'storeAdvocate',
      data: $('#' + form).serialize(),
      success: function (data) {
      if(data.status=='sucess')
      {
      $("#modal-add-advocate").modal('hide');
      $("#advocateForm").trigger('reset');
      var flag = $("#typeOfadv").val();
      getAdvocateList(flag);
      }
      }
      });
    }
    else
    {
      return false;
    }
  })
    </script>
    </section>
    @endsection
