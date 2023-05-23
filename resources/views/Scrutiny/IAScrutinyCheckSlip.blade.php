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

  <form role="form" id="iascrutinyCheckSlipForm" action="printIAScrutinyCheckSlip" method="POST" data-parsley-validate>
    @csrf
      <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >IA Check Slip</h7>
        </div>

        <div class="panel panel-body">

          <div class="row">
            <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="col-md-4">
              <div class="form-group">
                <label>Type of Application<span class="mandatory">*</span></label>

           
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


			<div class="col-md-4">
              <div class="form-group">
                <label>Select IA<span class="mandatory">*</span></label>

                <select class="form-control" name="iano" id="iano"  data-parsley-trigger='change' data-parsley-errors-container="#modlerror2" data-parsley-required data-parsley-required-message="Select IA">
                  <option value="">Select IA</option>
                 
                  </select> <span id="modlerror2"></span>
                </div>

              </div>

          </div>
        </br>
		</br>

    
        <div class="row"  style="float: center;" id="add_apl_div">
              <div class="col-sm-12 text-center">
               <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Download">
               <a class="btn btn-danger btn-md center-block btnClear" href=""> Cancel </a>
              </div>
        </div><br><br>




          </div>
       </form>
    <!-- /.tab-pane -->
    <script src="js/jquery.min.js"></script>
    <script src="js/scrutiny/iascrutiny.js"></script>
  </section>
  @endsection
