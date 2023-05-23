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
     <form role="form" id="applicationrestoreForm" action="saveapplicationrestore"  method="POST" data-parsley-validate>
		@csrf
	   <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Restore Application Details</h7>
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
                <label>Disposed Date</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="applnDisposedDate" class="form-control pull-right " id="applnDisposedDate"   readonly>
                </div>
               </div>
            </div>
          </div>
        </div>


        <div class="panel  panel-primary">
          <div class="panel panel-heading">
            <h7 >Restoration Details</h7>
          </div>

              <div class="panel panel-body">
                <div class="row">
                  <div class="col-md-4">
                          <div class="form-group">
                            <label>Order No / Reason<span class="mandatory">*</span></label>
                            <input class="form-control  " name="orderno" id="orderno" required type='' data-parsley-pattern="/^[a-zA-Z0-9.,-/ ()\n\r]+$/" data-parsley-pattern-message="Invalid Order No." data-parsley-trigger='keypress' data-parsley-required-message="Enter Order No" maxlength="30">
                          </div>
                  </div>
                <div class="col-md-4">
                 <div class="form-group">
                            <label>Order Date<span class="mandatory">*</span></label>
                           <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" name="orderdate"  id="orderdate" value="" class="form-control pull-right datepicker " required data-parsley-trigger='keypress' data-parsley-errors-container="#error20" data-parsley-required-message="Enter Order Date">
                            </div>


                            <span id="error20"></span>
                          </div>
                </div>
                <div class="col-md-4" >
                  <div class="form-group">
                    <label>Restoration through<span class="mandatory">*</span></label>
                    <select  class="form-control" id="restorefrom" name="restorefrom" required data-parsley-required-message='Select Nature'>
                      <option value="">Select</option>
                      <option value="MA"> Miscellaneous Application </option>
                      <option value="Highcourt"> High court</option>
					  <option value="Others"> Others</option>
                    </select>
                  </div>
                </div>
				</div>

		    <div class="row">
              <div class="col-md-4 restorefromdiv" id="restorefromdiv2" style="display: none" >

                 <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class="input-group ">
                    <input type="text" name="restoreapplicationId" class="form-control pull-right number zero"
					id="restoreapplicationId" value="" data-parsley-errors-container="#modlerror"
					data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No'
					maxlength='20'>
                   <!--  <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div> -->

                  </div>
                  <span id="modlerror"></span>
                </div>
                </div>
               </div>

              <br>
              <div class="row"  style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
                  <input type="submit" id="save" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div>
              <br><br>


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
    <!-- /.tab-pane -->
    <script src="js/jquery.min.js"></script>
    <script src="js/application/restoreapplication.js"></script>
  </section>
  @endsection
