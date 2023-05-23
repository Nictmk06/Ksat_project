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
  @if ($message = Session::get('success'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
          <h1>{{ $message }}</h1>
  </div>
  @endif
  <section class="content">
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
                    @foreach($applicationType1 as $applType)

                    <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>

                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <input type="text" name="modl_applno" class="form-control pull-right" id="modl_applno" value="" data-parsley-maxlength="15" data-parsley-maxlength-message="Application No Should have maximum 15 digit"  data-parsley-trigger='keypress'data-parsley-errors-container="#modlerror">



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
    <div>
     <form role="form" id="freshApplicationForm" method="POST" action="saveFreshApplication" data-parsley-validate>
      @csrf
    <div class="panel  panel-primary">
      <div class="panel panel-heading">
        <h7 >Details of Application</h7>
      </div>


              <input class="form-control" name="reviewApplId1" type="hidden" id="reviewApplId1">
              <div class="panel panel-body">

                <div class="row">
                  <div class="col-md-4">
                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                    <div class="form-group">
                      <label>Type of Application<span class="mandatory">*</span><span id="reviewAppl" style="color:red;"></span></label>
                      <select class="form-control" name="applTypeName" id="applTypeName" >
                        <!-- data-parsley-required data-parsley-required-message="Select Application Type" data-parsley-trigger='change'> -->
                        <option value="">Select Application Type</option>
                        @foreach($applicationType as $applType)

                        <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort.'-'.$applType->applfees}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                        @endforeach
                      </select>
					    <label class="radio-inline title_sel4"  >
						    <input type="checkbox" name="applicantgovt" class="chkbox" id="applicantgovt" value="" data-parsley-trigger='keypress'>Is Applicant Government Department ?
                        </label>
                       <label class="radio-inline suomoto"  >
                    <input type="checkbox" name="suomotoappl" id="suomotoappl" class="chkbox" value="" data-parsley-trigger='keypress'>Is Suo- moto Application ?
                              </label>

                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>KSAT Act<span class="mandatory">*</span></label>
                      <select class="form-control" name="actName"  id="actName" disabled>
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
                        <input type="text" name="applYear"  class="form-control pull-right datepicker1"  id="datepicker1" data-parsley-date-format="YYYY" readonly="" data-parsley-trigger='keypress' >
                      </div>
                    </div>
                  </div>



                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Registration Date<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" readonly disabled name="applnRegDate" class="form-control pull-right datepicker" id="applnRegDate" value=""  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits" value="" data-parsley-required  data-parsley-required-message="Enter Registration Date"  data-parsley-errors-container="#error5" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
                      </div>
                      <span id="error5"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
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
               <div class="col-md-4">
                    <div class="form-group">
                      <label>No of Applicants.<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfAppl" id="noOfAppl" type="number" value="" data-parsley-required data-parsley-required-message="Enter No fo Applicants" data-parsley-minlength="1" data-parsley-minlength-message="No of Applicants Should have minimum 1 digit" data-parsley-maxlength="4" data-parsley-maxlength-message="No of Applicants Should have maximum 4 digit" data-parsley-trigger='keypress' >
                    </div>
                  </div>

                 <div class="col-md-4">
                    <div class="form-group">
                      <label>No of Respondants<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfRes" type="number" id="noOfRes"  value="" data-parsley-required data-parsley-required-message="Enter No of Respondants" data-parsley-minlength="1" data-parsley-minlength-message="No of Respondants Should have minimum 1 digit" data-parsley-maxlength="4" data-parsley-maxlength-message="No of Respondants Should have maximum 4 digit" data-parsley-trigger='keypress'>
                    </div>
                  </div>
                </div>

               <div class="row">
                 {{--  <div class="col-md-4">
                    <div class="form-group">
                      <label>Total Receipt Amount<span class="mandatory">*</span></label>
                      <input class="form-control number" name="totReceiptAmt" type="tel" id="totReceiptAmt" data-parsley-pattern="/^[0-9]+$/" value="" data-parsley-required data-parsley-required-message="Enter Total Receipt Amount" data-parsley-minlength="1"  data-parsley-minlength-message="Receipt Amount Should have Minimum 1 Digits" data-parsley-maxlength='6' data-parsley-maxlength-message="Receipt Amount Accepts only 6 digits" data-parsley-trigger='keypress'>
                    </div>
                  </div>
				    <div class="col-md-4">
                    <div class="form-group">
                      <label>Additional No (Multiple Remedies)</label>
                      <input class="form-control number zero" name="additionalNo" type="tel" id="additionalNo" data-parsley-pattern="/^[0-9]+$/" value="" data-parsley-maxlength='6' data-parsley-maxlength-message="Additional No Accepts only 6 digits" data-parsley-trigger='keypress'>
                    </div>
                  </div> --}}
                 <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject<span class="mandatory">*</span></label>
                      <textarea class="form-control" name='applnSubject' id="applnSubject"   data-parsley-pattern="/^[-@.\/,()#+\w\s]*$/" data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Subject'
                    data-parsley-maxlength='100' data-parsley-maxlength-message="Subject accepts upto 100 characters" ></textarea>
                    </div>
                  </div>
               </div>


          </div>
        </div>

         <div class="panel panel-primary receiptdiv" id="receiptdiv"  style="display: inline" >
         <div class="panel panel-heading">Details Of Receipt</div>
        <div class="panel panel-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">

                <label>Receipt No.<span class="mandatory">*</span></label>
                <input class="form-control number zero" name="receiptNo"  id="receiptNo"
				 data-parsley-pattern="/^[0-9\/]+$/"
				data-parsley-minlength='1'  data-parsley-maxlength='15' data-parsley-maxlength-message="Receipt No. Should have Maximum 15 digit" data-parsley-trigger='keypress'>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Receipt Date<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="receiptDate" disabled readonly class="form-control pull-right datepicker" id="receiptDate"
				 data-parsley-errors-container="#error1" data-parsley-trigger='keypress'>
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
                  <input type="text" class="form-control" disabled readonly id="applName"  name="applName" data-parsley-required-message="Enter Applicant Name."
                  data-parsley-pattern="/^[a-zA-Z ]*$/" data-parsley-pattern-message="Applicant Name Accepts Only Characters" data-parsley-minlength="3"  data-parsley-minlength-message="Applicant Name Should have Minimum 3 Characters" data-parsley-maxlength='100' data-parsley-maxlength-message="Applicant Name Accepts only 100 characters" data-parsley-errors-container="#error" data-parsley-trigger='keypress'>
                </div>
                <span id="error"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Amount<span class="mandatory">*</span></label>
                <input class="form-control number zero"  disabled readonly name="recpAmount"  type="number" id="recpAmount"
          data-parsley-minlength="2"  data-parsley-minlength-message="Receipt Amount Should have Minimum 2 Digits" data-parsley-maxlength='6' data-parsley-maxlength-message="Receipt Amount Accepts only 6 digits" data-parsley-trigger='keypress'>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group" id="submit_div">
                <br>
                <input type="button" name="recpSubmit" id="recpSubmit" disabled class="btn btn-md btn-primary" value="Add List">
              <!--  <input type="button" class="btn btn-danger btn-md center-block " Style="width: 100px;" value="Cancel">
             --> </div>

            </div>
          </div>
          <div class="row">
            <table id="example1" class="table myTable table-bordered table-striped receipt-list" >
              <thead style="background-color: #3c8dbc;color:#fff">
                <tr>

                  <td class="col-md-2">Receipt No.</td>
                  <td class="col-md-2">Receipt Date</td>
                  <td class="col-md-2">Name of Applicant</td>

                  <td class="col-md-2">Amount</td>
                  <td class="col-md-2"></td>
                </tr>
              </thead>
              <tbody>



              </tbody>

            </table>
          </div>

        </div>

      </div>
         <div class="row" style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
                  <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div><br><br>

      </div>
       </form>
      
     </div>
      <!-- /.tab-pane -->
      <script src="js/jquery-3.4.1.js"></script>
        <script src="js/jquery.min.js"></script>
      <script src="js/application/application.js"></script>
    </section>
    @endsection
