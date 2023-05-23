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

     <form role="form" id="documentfilingForm" action="savedocumentfiling" data-parsley-validate>
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
                <input type='hidden' name='groupNo' value='groupNo' id='flag'>
                <input type='hidden' name='applEndno' value='' id='applEndno'>
                {{-- <input type="text" name="applStartno" id='applStartno' value=''> --}}
                <input type="hidden" name="applYear" id='applYear' value=''>
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
					  <option value="O">Others</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4 appresdiv">
                  <div class="form-group">
                    <label>Name<span class="mandatory">*</span></label>
                    <select class="form-control" name='filledbyname' id='filledbyname'  data-parsley-required-message='Select Name'>
                      <option value=''>Select  Name</option>

                    </select>
                  </div>
                </div>
				 <div class="col-md-4 otherdiv">
                          <div class="form-group">
                            <label>Name<span class="mandatory">*</span></label>
                            <input class="form-control  " name="filledbynameother" id="filledbynameother"  type='' data-parsley-pattern="/^[a-zA-Z0-9.,-/ ()\n\r]+$/" data-parsley-pattern-message="Invalid filled by name." data-parsley-trigger='keypress' data-parsley-required-message="Enter filled by name" maxlength="30">
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


          </div>
        </div>
      </div>
    </form>
    <!-- /.tab-pane -->
    <script src="js/jquery.min.js"></script>
    <script src="js/application/documentfiling.js"></script>
  </section>
  @endsection
