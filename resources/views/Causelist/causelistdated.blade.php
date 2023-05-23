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
  @include('flash-message')
  <section class="content">

    <form role="form" id="cuaselistForm" action="" data-parsley-validate>
      <div class="panel  panel-primary">
        <div class="panel panel-heading origndiv">
          <h7 >Causelist(dated)</h7>
        </div>
        <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
        <div class="panel panel-body">


            <input name="applicationId" id="application_id" type="hidden">

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Hearing Date<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="hearingDate" class="form-control pull-right datepicker" id="hearingDate"  data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter Hearing Date"  data-parsley-required data-parsley-errors-container="#error5">
                  </div>
                  <span id="error5"></span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>CauseList Type<span class="mandatory">*</span></label>
                  <select name="causetypecode" id="causetypecode" class="form-control" data-parsley-required data-parsley-required-message="Select Causelist type"><option value="" >Select CauseList Type</option>
                   @foreach($causelisttype as $cltype)
                  <option value="{{$cltype->causelisttypecode}}">{{$cltype->causelistdesc}}</option>
                  @endforeach
                </select>
              </div>
            </div>
             <div class="col-md-4">
                <div class="form-group">
                  <label>Bench Type<span class="mandatory">*</span></label>
                  <select name="benchCode" id="benchCode" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Bench Type</option>
                  @foreach($Benches as $bench)
                  <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
                  @endforeach
                </select>
              </div>
            </div>

        </div>
        <div class="row">
           <div class="col-md-4">
              <div class="form-group">
                <label>Bench <span class="mandatory">*</span></label>
                <select name="benchJudge" id="benchJudge" class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA">
                  <option value=''>Select Bench</option>

                   @foreach($benchjudge as $bench)
                  <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
                  @endforeach

              </select>
            </div>
          </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>List No<span class="mandatory">*</span></label>
            <select class="form-control" name="listno1" id="listno1" data-parsley-required data-parsley-required-message="Select Court Hall">><option value="" >Select List No</option>
            @foreach($list as $lists)
            <option value="{{$lists->listcode}}">{{$lists->listcode}}</option>
            @endforeach
          </select>
        </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Court Hall<span class="mandatory">*</span></label>
            <select class="form-control" name="courthall1" id="courthall1" data-parsley-required data-parsley-required-message="Select Court Hall">><option value="" >Select Court Hall</option>
            @foreach($CourtHalls as $courthall)
            <option value="{{$courthall->courthallno}}">{{$courthall->courthalldesc}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-4">
          <div class="form-group">
            <label>Cause List from <span class="mandatory">*</span></label>
            <select class="form-control" name="causelistfrm1" id="causelistfrm1" data-parsley-required data-parsley-required-message="Cause List from">><option value="" >Select Cause List</option>
            <option value='Fresh'>Fresh</option>
            <option value='Dated'>Dated</option>
            <option value='Other'>Other</option>
          </select>
        </div>
      </div>
       <div class="col-md-4">
            <div class="form-group">
              <label>Posted For<span class="mandatory">*</span></label>
              <select class="form-control" name="postedfor1" type="text" id="postedfor1" data-parsley-required data-parsley-required-message="Select Posted For">><option value="" >Select Posted For</option>
              @foreach($purpose as $postedfor)
              <option value="{{$postedfor->purposecode}}">{{$postedfor->listpurpose}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <table id="myTable2" class="table table-bordered table-striped  table order-list" style="width:100%;" >
        <thead >
          <tr style="background-color: #3c8dbc;color:#fff;" >
           <!--  <td>Sr.no</td> -->
          <td>CL Serial No</td>
          <td>Posted for</td >
          <td>Application id</td>
          <td>Posted from</td>

        </tr>
      </thead>
      <tbody id="results3" >

      </tbody>
    </table>
    </div>
     <div class="row">
      <div class='col-md-6'>
        <div class='form-group'>
           <label>Applicant Remarks</label>
                          <textarea  class="form-control" id="applicantremark"name="applicantremark" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remark" data-parsley-required-message="Enter Remark"></textarea>
        </div>
      </div>
      <div class='col-md-6'>
        <div class='form-group'>
           <label>respondant Remarks</label>
                          <textarea  class="form-control" id="respondantremark" name="respondantremark" type="text" data-parsley-trigger='keypress' data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Invalid Remark" data-parsley-required-message="Enter Remark"></textarea>
        </div>
      </div>
     </div>
     <div class="row">
        <div class='col-md-1'></div>
        <div class="col-md-10 text-center" style="float: right;">
          <input type="hidden" name="causedate_val" id="applIndex_up_value" value='A'>
          <input  id="saveCausedated" type="button" class="btn btn-primary btn-md center-block btnSearch"  value="Prepare Cause List" >
          <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Finalize" >
        </div>
         <div class='col-md-1'></div>
      </div>
      </div>

      </form>
      <!-- /.tab-pane -->
      <script src="js/jquery.min.js"></script>
      <script src="js/casemanagement/causelist.js"></script>
    </section>
    @endsection
