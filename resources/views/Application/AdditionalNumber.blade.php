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


    <form role="form" id="additionalnumberForm" action="addAdditionalNumber" data-parsley-validate>
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
            <h7 >Details of Additional no</h7>
          </div>

          <div class="panel panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Additional No<span class="mandatory">*</span></label>
                   <input class="form-control number zero" name="additionalno" id="additionalno" type="number" onkeyup="this.value = minmax(this.value, 1, 100)" value="" data-parsley-required data-parsley-required-message="Enter Additional No" data-parsley-minlength="1" data-parsley-minlength-message="Additional No Should have minimum 1 digit" data-parsley-maxlength="3" data-parsley-maxlength-message="Additional No Should have maximum 3 digit" data-parsley-trigger='keypress' >
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Remarks</label>
                  <textarea  class="form-control" id="additionalremark"name="additionalremark" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remark" data-parsley-required-message="Enter Remark"></textarea>
                </div>

              </div>
            </div>

            <div class="row"  style="float: right;" id="add_apl_div">
              <div class="col-sm-12 text-center">
                <input name='sbmt_additional' id="sbmt_additional" value="A" type='hidden'>
                <input type="button" id="saveAdditional" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                <input type="button" class="btn btn-danger btn-md center-block btnClear" id="clearAdditional" Style="width: 100px;" value="Cancel">
              </div>
            </div><br><br>
            <div class="row">
              <table id="myTable5" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                <thead >
                  <tr style="background-color: #3c8dbc;color:#fff" >
                    <td>Additional no</td>
                    <td>Date</td>
                    <td>Remarks</td>
                  </tr>
                </thead>
                <tbody id="results8" >
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </form>
    <!-- /.tab-pane -->
    <script src="js/jquery.min.js"></script>
    <script src="js/application/additionalnumber.js"></script>
  </section>
  @endsection
