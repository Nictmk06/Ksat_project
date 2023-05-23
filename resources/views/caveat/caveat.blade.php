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
  .divstyle
  {
  padding-top: 0px;
  padding-bottom: 0px;
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
  <div id="appends" style="display: none">@extends( 'caveat/caseData')</div>
  <div id="newcontent"></div>
  @include('flash-message')
	<section class="content">
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
              <!--<div class="col-md-6">
                <div class="form-group">
                  <label>Type of Application<span class="mandatory">*</span></label>
                  <select class="form-control" name="edit_modal_type" id="edit_modal_type" >
                    @foreach($establishment as $establish)

						<option value="{{$establish->establishcode}}">{{$establish->establishname}}-{{$establish->establishcode}}</option>
					@endforeach
                  </select>
                </div>
              </div>-->
              <div class="col-md-8">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <input type="text" name="edit_applno" class="form-control pull-right" id="edit_applno" value="" data-parsley-errors-container="#modlerror">
                    <input name="estcode" type="hidden" id="estcode" value="{{ $estcode }}"/>
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
				<li class="active"><a href="#tab_1" data-toggle="tab">CaveatApplication</a></li>
				<li><a href="#tab_2" data-toggle="tab">Caveat</a></li>
				<li><a href="#tab_3" data-toggle="tab">Cavetee</a></li>
				
				<li style="float:right;" id="editApplication"> <input type="button"  id="" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Edit"></li>
			  </ul>
			<div class="tab-content">

				<div class="tab-pane active" id="tab_1">
					<div class="panel  panel-primary">
					<div class="panel-heading origndiv" >
						<h7>Caveat Application Details</h7>
							</div>
							<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
						<div class="panel panel-body divstyle" >
			<form role="form" id="caveat" method=""  data-parsley-validate>
					@csrf
							<!--<div class="row">
								 <div class="col-md-4">

									<div class="form-group">
									  <label>Establishment Code<span class="mandatory">*</span><span id="reviewAppl" style="color:red;"></span></label>
									  <select class="form-control dynamic2" name="estname" id="estname" data-dependent="actsectionname" data-parsley-required data-parsley-required-message="Select Application Type" data-parsley-trigger='change'>
										<option value="">Select Application Type</option>
										@foreach($establishment as $establish)

										<option value="{{$establish->establishcode}}">{{$establish->establishname}}-{{$establish->establishcode}}</option>
										@endforeach
									  </select>
									</div>
								  </div>


							</div>-->
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
									  <label>Date Of Caveat<span class="mandatory">*</span></label>
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
									  <label>Caveat Year:<span class="mandatory">*</span></label>
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
									  <label>Caveat Start No<span class="mandatory">*</span></label></br>
									  <input type="text" class="form-control pull-right zero number" id="applStartNo" name="applStartNo" value="{{ $serialno }}" readonly>
									  <span class="text-danger">{{ $errors->first('applStartNo') }}</span>
									</div>
								</div>
							</div>
								<br>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
									  <label>No of Caveator<span class="mandatory">*</span></label>
									  <input class="form-control number" name="noOfAppl" id="noOfAppl" type="number"  min="1" id="noOfAppl" value="" data-parsley-required data-parsley-required-message="Enter Min No fo Caveators" data-parsley-minlength="1" data-parsley-minlength-message="No of Caveators Should have minimum 1 digit" data-parsley-maxlength="3" data-parsley-maxlength-message="No of Caveators Should have maximum 3 digit" data-parsley-trigger='keypress' onkeyup="this.value = minmax(this.value, 0, 150)">
									</div>
								  </div>
								<div class="col-md-4">
									<div class="form-group">
									  <label>No of Caveatee<span class="mandatory">*</span></label>
									  <input class="form-control number" name="noOfRes" type="number"  min="1" id="noOfRes"  value="" data-parsley-required data-parsley-required-message="Enter No of Caveatees" data-parsley-minlength="1" data-parsley-minlength-message="No of Caveatees Should have minimum 1 digit" data-parsley-maxlength="3" data-parsley-maxlength-message="No of Caveatees Should have maximum 3 digit" data-parsley-trigger='keypress'  onkeyup="this.value = minmax(this.value, 0, 150)">
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
									  <label>Subject<span class="mandatory">*</span></label>
									  <textarea class="form-control" name='applnSubject' id="applnSubject"  data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject' maxLength = '300'></textarea>
									  <span id="sub_rchars">300</span> Character(s) Remaining
									</div>
								</div>

							</div>
						</div>
						<div class="panel-heading origndiv">Order Against Which Caveat Application Is Made</div>
						<div class="panel panel-body divstyle" >
									<div class="row">
										<div class="col-md-4">
										  <div class="form-group">
											<label>GO Order No</label>
											<input class="form-control orderNo " name="orderNo" id="orderNo" type='' data-parsley-pattern="/^[a-zA-Z0-9.,-/ ()\n\r]+$/" data-parsley-pattern-message="Invalid Order No." data-parsley-trigger='keypress' data-parsley-required-message="Enter Order No" maxlength="60">
										  </div>
										</div>
										<div class="col-md-4">
										  <div class="form-group">
											<label>GO Order Date<span class="mandatory">*</span></label>
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
									</div>
									<div class='row'>
										<div class='col-md-4'>
										  <div class='form-group'>
											<label>GO Orders Challenged</label>
											<textarea  class="form-control" id="multiorder"name="multiorder" type="text" data-parsley-trigger='keypress'  data-parsley-required-message="Enter Other Order"></textarea>
										  </div>
										</div>

									  </div>
						</div>
						<!--<div class="panel-heading origndiv">
						<h7>Interim order if any</h7>
						</div>
						<div class="panel panel-body divstyle" >
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
						</div>-->
						<div class="panel-heading origndiv">
						<h7>Address For Service</h7>
						</div>
						<div class="panel panel-body divstyle">
								<div class="row">
								    <div class="col-md-4">
										  <div class="form-group">
											<label>Address For Service<span class="mandatory">*</span></label>
											<textarea class="form-control" name="addrForService" type="text" id="addrForService" data-parsley-required data-parsley-required-message="Enter Service Address" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Service Address Accepts Only Characters" data-parsley-trigger='keypress' maxlength='150'></textarea>
											<span id="rchars">150</span> Character(s) Remaining
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
											<select name="distcode" id="distcode" class="form-control dynamic1" data-dependent="talukname" data-parsley-required data-parsley-required-message="Select District" data-parsley-trigger='keypress'>
											  <option value="">Select District</option>
											  @foreach($dist_list as $dist)

											  <option value="{{$dist->distcode}}">{{$dist->distname}}</option>

											  @endforeach
											</select>
										  </div>
										</div>
										<div class="col-md-4">
										  <div class="form-group">
											<label>Taluk<span class="mandatory" >*</span></label>
											<select name="talukname" id="talukname" class="form-control"  data-parsley-required-message="Select Taluk" data-parsley-trigger='change'>
											  <option value="">Select Taluk</option>

											</select>

										  </div>
										</div>

								</div>
						</div>
						<div class="panel-heading origndiv"><h7>Any Other Details</h7></div>
							<div class="panel panel-body divstyle">
								<div class="row">
								 	<div class="form-group">
										  <label>Remarks</label>
										  <textarea  class="form-control" id="caseremarks"name="caseremarks" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remark" data-parsley-required-message="Enter Remark"></textarea>
										</div>
								</div>
					<div class="row"  style="float: right;">
						
							   <div class="col-sm-12 text-center">
								<input type="hidden" name="sbmt_case" id="sbmt_case" value="A">
								<a type="button" id="btnNext" class="btn btn-md btn-primary">Save & Next</a>
							<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
							</div>
					</div>
					@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
				</ul>
			</div><!--END IF DIV-->
			@endif

				</div><!--body-->
				</form>
			</div><!--panel  panel-primary-->
			</div><!--end of tab_1-->
				<div class="tab-pane" id="tab_2">
					<div class="panel  panel-primary">
						<div class="panel panel-heading origndiv">
							<h7 >Details of Caveator</h7>
						</div>
						<form role="form" id="caveatApplicant" method=""  data-parsley-validate>

							{{ csrf_field() }}
						<div class="panel panel-body divstyle" >

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
                <label>Caveator Name<span class="mandatory">*</span></label>
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
				  <input name="estcode" type="hidden" id="estcode" value="{{ $estcode }}"/>
				  <input type="hidden" id="recAppId" name="recAppId"  value="" placeholder="apptype">
                  <input type="text" class="form-control" id="applicantName" name="applicantName" data-parsley-required data-parsley-required-message="Enter Applicant Name."
                  data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Applicant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error9" data-parsley-trigger='keypress'>
                </div>
              </div>
              <span id="error9"></span>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Relation</label>
                <select class="form-control" name="relType" type="text" id="relType" data-parsley-required data-parsley-required-message="Select Relation" data-parsley-trigger='keypress'>
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
                    <button type="button" id="Rtitle" class="btn btn-default dropdown-toggle form-control advancedSearch3" data-toggle="dropdown"><span class="title_sel3">Select Title</span> <span class="selection3"></span>
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
                <select class="form-control  dynamic" name="depttypecode" data-dependent="departmentcode" type="text" id="depttypecode" data-parsley-required data-parsley-required-message="Select Department Type" data-parsley-trigger='keypress'>
                  <option value="">Select Department</option>
                  @foreach($DeptType as $dept)
                  <option value="{{$dept->depttypecode}}">{{$dept->depttype}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Name Of Department<span class="mandatory">*</span></label>
                <select class="form-control" name="departmentcode" type="text" id="departmentcode" data-dependent="desigAppl" data-parsley-required data-parsley-required-message="Select Department Name" data-parsley-trigger='keypress'>
                  <option value="">Select Department</option>

                </select>
              </div>
            </div>
            <div class="col-md-4">

              <div class="form-group">
                <label>Designation Of Caveator<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <select class="form-control" name="desigAppl"  id="desigAppl" data-parsley-required data-parsley-required-message="Select Designation" >
                    <option value="">Select Designation</option>
                    @foreach($design as $desig)
                    <option value="{{$desig->desigcode}}">{{$desig->designame}}</option>
                    @endforeach
                  </select>
                  <!--<div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="desigadd">
                    <i class="fa fa-plus"></i>
                  </div>-->

                </div>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Address<span class="mandatory">*</span></label>
                <textarea class="form-control"  name="addressAppl" type="text" id="addressAppl" data-parsley-required data-parsley-required-message="Enter Service Address" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Service Address Accepts Only Characters" data-parsley-trigger='keypress' maxlength='150'></textarea>
				<span id="Appchars">150</span> Character(s) Remaining
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
                <select class="form-control dynamic2" name="adistcode" type="text" id="adistcode" data-dependent="taluknameApp" data-parsley-required data-parsley-required-message="Select District" data-parsley-trigger='keypress'>
                  <option value="">Select District</option>
                  @foreach($dist_list as $dist)
                  <option value="{{$dist->distcode}}">{{$dist->distname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
             <div class="col-md-4">
              <div class="form-group">
                <label>Taluk<span class="mandatory">*</span></label>
				<!--<input class="form-control" name="taluknameApp" type="text" id="taluknameApp"  data-parsley-trigger='keypress'>-->
                <select class="form-control" name="taluknameApp" type="text" id="taluknameApp" data-parsley-required data-parsley-required-message="Select taluk" data-parsley-trigger='keypress'>
                <option value="">Select Taluk</option>

                </select>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Mobile No.</label>
                <input class="form-control number zero" name="applMobileNo"  id="applMobileNo" type="number" onblur="this.value = checkMobNo(this.value)" maxlength="10" data-parsley-pattern="/^[1-9][0-9]{9}$/" data-parsley-pattern-message="Invalid Mobile No."  data-parsley-trigger='keypress' placeholder="Mobile No." type="text"  >
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email Id</label>
				 <input type="text" name="applEmailId" id="applEmailId" class="form-control"    data-parsley-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" data-parsley-pattern-message="Invalid Email" data-parsley-trigger='keypress' maxlength="100" placeholder="Email" >
        
              
              </div>
            </div>
             <div class="col-md-4"  id="party_div">
              <div class="form-group">
                <label>Party in Person<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="partyInPerson" id="partyInPersonY" value="Y" >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="partyInPerson" id="partyInPerson" value="N" checked>No
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
                <div class="date">
                  <input list="browsers"  class="form-control number zero" name="advBarRegNo" type="text" id="advBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                  data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="Advocate Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters" maxlength="20"  data-parsley-errors-container='#errorAdv1'>
                  <datalist id="browsers">
                  <?php foreach($advocatedetails as $advocate){?>
                  <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                  <?php }?>
                  </datalist>
                 

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
          </div>
          <div class="row"  style="float: right;" id="add_apl_div">
            <div class="col-sm-12 text-center">
              <input type="hidden" name="sbmt_applicant" id="sbmt_applicant" value="A">
			  <input type="hidden" name="srno_applicant" id="srno_applicant" value="">
              <input type="button" id="saveApplicant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Add List">
              <input type="button" id="clearApp" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
            </div>
          </div>

          <br><br>
          <br>

          <div class="row" id="applcant_div">
            <table id="applicant_tab" class="table table-bordered table-striped applicant-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr>
                  <td  class="col-sm-1" align='center'>Sr.No</td>
				  <td  class="col-sm-2" align='center'>Caveat Id</td>
                  <td  class="col-sm-2" align='center'>Caveator</td>
                  <td  class="col-sm-2" align='center'>Advocates</td>
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






						</div><!--panel  body divstyle-->

						</form>


					</div><!--panel  panel-primary-->
				</div><!--end of tab_2-->
				<div class="tab-pane" id="tab_3">
					<div class="panel  panel-primary">
						<div class="panel panel-heading origndiv">
							<h7 >Details of Caveatee</h7>
						</div>
						<form role="form" id="caveatRespondant" method=""  data-parsley-validate>
							{{ csrf_field() }}
						<div class="panel panel-body divstyle" >
							<div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Name Of Caveatee<span class="mandatory">*</span></label>
                <div class="input-group input-group-sm">
                  <div class="input-group-btn">
                    <button type="button" id="restitle"  name="restitle" class="btn btn-default dropdown-toggle form-control advancedSearch5" data-toggle="dropdown"><span class="title_sel5">Select Title</span> <span class="selection5"></span>
                    <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu dropdown_all5" >
                      @foreach($nameTitle as $title)
                      <li ><a value="{{$title->titlename.'-'.$title->gender}}">{{$title->titlename}}</a></li>
                      @endforeach
                    </ul>
                  </div>
                  <!-- /btn-group -->
                  <input type="hidden" class="form-control" name="respondantTitle" id="respondantTitle">
				  <input type="hidden" id="resApplId" name="resApplId"  value="">
                  <input type="text" class="form-control number zero" name="respondantName" id="respondantName" data-parsley-required data-parsley-required-message="Enter Respondant Name."
                  data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Respondant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Respondant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Respondant Name Accepts only 100 characters" data-parsley-errors-container="#error17" data-parsley-trigger='keypress'>
                </div>
              </div>
              <span id="error17"></span>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Relation</label>
                <select class="form-control" name="resRelation" type="text" id="resRelation" data-parsley-required data-parsley-required-message="Select Relation" data-parsley-trigger='keypress'>
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
                    <button type="button" id="" class="btn btn-default dropdown-toggle form-control advancedSearch6" data-toggle="dropdown">
					<span class="title_sel6">Select Title</span> <span class="selection6"></span>
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
                <input type="number" name="resAge" id="resAge" class="form-control number"  onkeyup="this.value = minmax(this.value, 0, 100)"
                data-parsley-required  data-parsley-required-message="Enter Age"  data-parsley-minlength="1" data-parsley-minlength-message="Age Should have minimum 1 digit" data-parsley-maxlength="3" data-parsley-maxlength-message="Age Should have maximum 3 digit" data-parsley-trigger='keypress'>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Department Type<span class="mandatory">*</span></label>
                <select class="form-control dynamic4" name="resDeptType" type="text" id="resDeptType"  data-dependent="resnameofDept" data-parsley-required  data-parsley-required-message="Select Department Type" data-parsley-trigger='keypress'>
                  <option value="">Select Department Type</option>
                  @foreach($DeptType as $dept)
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
                <select name="resnameofDept" id="resnameofDept" class="form-control" data-dependent="desigRes" data-parsley-required  data-parsley-required-message="Select Department" data-parsley-trigger='keypress'>
                  <option value="">Select Department Name</option>

                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Designation of Caveatee<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <select class="form-control" name="desigRes"  id="desigRes" data-parsley-required data-parsley-required-message="Select Designation" >
                    <option value="">Select Designation</option>
                    @foreach($design as $desig)
                    <option value="{{$desig->desigcode}}">{{$desig->designame}}</option>
                    @endforeach
                  </select>


                </div>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Address<span class="mandatory">*</span></label>
                <textarea class="form-control"  name="resAddress2" type="text" id="resAddress2" data-parsley-required data-parsley-required-message="Enter Service Address" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Service Address Accepts Only Characters" data-parsley-trigger='keypress' maxlength='150'></textarea>
				<span id="Reschars">150</span> Character(s) Remaining
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
                <select name="rdistcode" id="rdistcode" class="form-control dynamic3"  data-parsley-required data-parsley-required-message="Select District" data-parsley-trigger='keypress'>
                  <option value="">Select District</option>
                  @foreach($dist_list as $dist)
                  <option value="{{$dist->distcode}}">{{$dist->distname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Taluk<span class="mandatory">*</span></label>
                <select name="taluknameRes" id="taluknameRes" class="form-control" data-parsley-required data-parsley-required-message="Select Taluk" data-parsley-trigger='keypress'>
                  <option value="">Select Taluk</option>

                </select>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Mobile No.</label>
                <input class="form-control" name="resMobileNo" type="number" id="resMobileNo" onblur="this.value = checkMobNo(this.value)" data-parsley-pattern="/^[1-9][0-9]{9}$/"  data-parsley-pattern-message="Invalid Mobile No."  data-parsley-trigger='keypress' placeholder="Mobile No." maxlength="10">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Email Id</label>
					 <input type="text" name="resEmailId" id="resEmailId" class="form-control"   data-parsley-pattern="/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/" data-parsley-pattern-message="Invalid email" data-parsley-trigger='keypress' maxlength="100" placeholder="Email" >
        
               
              </div>
            </div>
            <!--<div class="col-md-4">
              <div class="form-group">
                <label>Is Govt Advocate</label></br>
                <label class="radio-inline">
                  <input type="radio" name="isGovtAdv" id="isGovtAdv" value="Y">
                </label>
              </div>
            </div>-->
			 <div class="col-md-4"  id="govt_div">
              <div class="form-group">
                <label>Is Govt Advocate<span class="mandatory">*</span></label><br>
                <label class="radio-inline">
                  <input type="radio" name="isgovtadv" id="isgovtadvY" value="Y" >Yes
                </label>
                <label class="radio-inline">
                  <input type="radio" name="isgovtadv" id="isgovtadv" value="N" checked>No
                </label>
              </div>
              <span id="error12"></span>
            </div>
          </div>
          <div class="row">
           <div class="col-md-4 resadvDatails">
              <div class="form-group">
                <label>Advocate Bar Reg No.<span class="mandatory">*</span></label><br>
                <div class="date">
                  <input list="browsers1"  class="form-control number zero" name="resadvBarRegNo" type="text" id="resadvBarRegNo" data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No."
                  data-parsley-pattern="/^[a-zA-Z0-9/ ]*$/" data-parsley-pattern-message="AdvocateBar Reg No.  Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Advocate Bar Reg No. Should have Minimum 3 Characters"maxlength="20" data-parsley-trigger='keypress' data-parsley-errors-container='#errorAdv' >
                  <datalist id="browsers1">
                  <?php foreach($advocatedetails as $advocate){?>
                  <option value="<?php echo $advocate->advocateregno;?>"><?php echo $advocate->advocateregno.'-'.$advocate->advocatename;?></option>
                  <?php }?>
                  </datalist>
                <!-- <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="resadvocateAdd">
                   </div>-->

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
            </div>
            <div class='row'>

            </div>


          </div>

          <div class="row"  style="float: right;" id="res_sbmt_div">
            <div class="col-sm-12 text-center">
              <input type="hidden" name="sbmt_respondant" id="sbmt_respondant" value="A">
			  <input type="hidden" name="srno_respondant" id="srno_respondant" value="">
              <input type="button" id="saveRespondant" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;"  value="Add List">
              <input type="button" id="clearRes" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
            </div>
          </div>

          <br><br>
          <div class="row" id="respondant_div">
           <table id="respondant_tab" class="table table-bordered table-striped respondant-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr>
                  <td class="col-sm-1" align='center'>Sr.No</td>
				  <td  class="col-sm-2" align='center'>Caveat ID</td>
                  <td class="col-md-2" align='center'>Caveatee</td>
                  <td class="col-md-2" align='center'>Advocates</td>
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
              <a  class="btn btn-md btn-primary ResNext" id="ResNext">Close</a>
            </div>
          </div>
        </form>



						</div><!--panel  body divstyle-->

					</div><!--panel  panel-primary-->
				</div><!--end of tab_3-->

			</div>
	  </div>

	<script src="js/jquery.min.js"></script>
	<script src="js/caveat/caveat.js"></script>

	</section>

@endsection
