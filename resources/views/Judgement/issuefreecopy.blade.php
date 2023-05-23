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
      <form role="form" id="saveFreeCopyStatus" action="saveFreeCopyStatus" data-parsley-validate>
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'>Update Free Copy Status</h4>
          </div>

          <div class="modal-body">
            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>

            <input id="modal_appl_id" type="hidden" name="modal_appl_id" value="">
            <input id="modal_srno" type="hidden" name="modal_srno" value="">
            <input id="modal_flag" type="hidden" name="modal_flag" value="">
			<input id="partyname" type="hidden" name="partyname" value="">

           <div class="row">


           </div>
           <div class='row'>
              <div class="col-md-6">
              <div class="form-group">
                  <label>Delivery Date<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="deliverydate" class="form-control  number pull-right datepicker" id="deliverydate"   data-parsley-required data-parsley-required-message='Enter  Date' data-parsley-errors-container='#statuserror'>
                  </div>
                  <span id="statuserror"></span>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Delivery mode<span class="mandatory">*</span></label>
                 <select class="form-control" name="deliverymode" id="deliverymode"  data-parsley-trigger='change' data-parsley-errors-container="#modlerror1" data-parsley-required data-parsley-required-message="Select delivery mode">
                  <option value="">Select Delivery Mode </option>
                  @foreach($deliverymode as $deliverymode)
                  <option value="{{$deliverymode->deliverycode}}">{{$deliverymode->deliverydesc}}</option>
                  @endforeach
                  </select>
              </div>
            </div>
			   </div>
            <div class='row'>
             <div class="col-md-12">
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

    <form role="form" id="issuefreecopyform" data-parsley-validate>
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
            <h7 >Issue Free Copy</h7>
          </div>
          <div class="panel panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Issue Free Copy For<span class="mandatory">*</span></label>
                   <div class="input-group date">
                   <select class="form-control" name='' id='statusfor' data-parsley-trigger='change' data-parsley-required data-parsley-required-message='Select Status For' data-parsley-errors-container='#modlerror2'>
                    <option value=''>Select Free Copy For</option>
                    <option value='A'>Applicant</option>
                    <option value='R'>Respondant</option>
                  </select>
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="addAppRes">
                      <i class="fa fa-plus"></i>
                    </div>
                  </div>
                  <span id="modlerror2"></span>
                  </div>
              </div>
            </div>

            <div class="row">
                <table id="myTable4" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Sr.no</td>
                      <td>Name</td>
                      <td>Status</td>
                      <td>IsMainParty</td>
                      <td>Free Copy delivery Date</td>
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
      <script src="js/judgement/issuefreecopy.js"></script>
    </section>
    @endsection
