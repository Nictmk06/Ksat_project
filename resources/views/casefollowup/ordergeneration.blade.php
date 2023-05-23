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

  div.ext1 {
  height: 200px;
  width: 100%;
  overflow-y: scroll;
}

div.ext2 {
  color: black;
  font-size:14px;
  background-color: white;
  height: 30px;
  width: 1000px;
  overflow-y: scroll;
}

.highlight {
   font-size:14px;
   color:darkred;
  	background:yellow;
  }

  button.btn1 {
  background-color: green; /* Green */
  border: 2px;
  border-radius: 8px;
  color: white;
  padding: 6px 14px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 20px;
  font-weight: bold;
}
button.btn2 {
  background-color:orange; /* Green */
  border: 2px;
  border-radius: 8px;
  color: white;
  padding: 6px 14px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 20px;
  font-weight: bold;
}
.grid-container {
  display: grid;
  grid-gap: 1px;
  grid-template-columns: 100px 150px 250px auto;
  background-color: white /*#2196F3*/;
  padding: 3px;
}

.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 3px;
  font-size: 16px;
  text-align: center;
}

  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

  <section class="content">


    <form role="form" id="ordergenerationform"  method="POST" action="ordergenerate" data-parsley-validate>
      @csrf

     <div class="panel  panel-primary">
      <div class="panel panel-heading origndiv">
        <h7 >Order Generation</h7>
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
		<div class="col-md-10">
							<div class="form-group">
							  <label>Order Template<span class="mandatory">*</span></label>
							  <textarea class="form-control" name='ordertemplate' id="ordertemplate" style="height: 200px; "   data-parsley-pattern="/^[-@.\/,()#+\w\s]*$/"
							  data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter order template'
							data-parsley-maxlength='5000' data-parsley-maxlength-message="order template accepts upto 5000 characters" ></textarea>
							</div>
						  </div>
		</div>

	    </div>
	 <div class="row divstyle"  style="float: middle;" id="add_apl_div">
		<div  class="col-sm-10 text-center">
		  <button type="submit" class="btn btn-primary" Style="width: 150px;" >View Order </button>
		  <input type="button" id="save" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px; " value="Save ">
		<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
		</div>
		</div>
	  </div>



    </div>












	</form>
<!-- /.tab-pane -->

<script src="js/jquery.min.js"></script>
<script src="js/casefollowup/ordergeneration.js"></script>

</section>
@endsection
</div>
