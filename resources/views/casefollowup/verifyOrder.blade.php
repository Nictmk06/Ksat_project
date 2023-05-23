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
   input[type=checkbox]
 {
  /* Double-sized Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px;
  }
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

  <section class="content">


   <form role="form" id="verifyJudgementsForm" action="DownloadOrder"  method="POST"
  data-parsley-validate>
  @csrf
      <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Verify Order</h7>
        </div>

        <div class="panel panel-body">
            <div class="row">
            <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="col-md-4">
              <div class="form-group">
                <label>Type of Application<span class="mandatory">*</span></label>

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
      
      <div class="row">
           <div class="col-md-4">
              <div class="form-group">
                <label>Order Date</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="orderdate" class="form-control pull-right " id="orderdate"   readonly>
                </div>

              </div>
            </div>

             <div class="col-md-4">
              <div class="form-group">
                 <label>Order Type<span class="mandatory">*</span></label>
               <select class="form-control" name="ordertype" id="ordertype"  data-parsley-trigger='change'  data-parsley-required data-parsley-required-message="Select Order Type" disabled>
                  <option value="">Select Order Type</option>
                  @foreach($ordertype as $ordertype)
                  <option value="{{$ordertype->ordertypecode}}">{{$ordertype->ordertypedesc}}</option>
                  @endforeach
                  </select>

              </div>
            </div>
      </div>

      
      <div id="downloadjudgementdiv">
      <div class="row"  style="display: inline" >

      <!-- <div class="col-md-2" >
              <div class="form-group">
                <label>View Judgement</label>
                  </div>
           </div>-->
           <div class="col-md-4" >
               <div id="element">
         <a href="#" id="viewjudgement" class="icon-plus" ><h4>View Order</h4></a>
        <!--<a href="{{ URL::route('DownloadJudgements', ['applicationId' => '1559/2005']) }}"
                      id="viewjudgement" class="icon-plus" ><i>View Judgement</i></a>-->

      </div>
            </div>
      </div>
        </br>
      </br>
      <div class="row">


      <div class="col-md-12">
               <input type="checkbox"  name="declaration" id="declaration" value="Y" data-parsley-trigger='keypress'>
              <label class="radio-inline" style="display: inline-block"  >
              <h4>I hereby declare that Order uploaded is correct.</h4>
                              </label></div>

      </div>

 </div>
          </div>


       </div>

        <div class="row"  style="float: right;" id="add_apl_div">

       <div class="col-sm-12 text-center">
                <button type="button" id="VerifyJudgement" class="btn btn-primary">Verify Order</button>
                <input type="button" class="btn btn-danger btn-md center-block btnClear" id="clear" Style="width: 100px;" value="Cancel">
              </div>
        </div>
    </div<br><br>





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
    <script src="js/jquery.min.js"></script>
    <script src="js/order/verifyorder.js"></script>

  </section>

  @endsection
