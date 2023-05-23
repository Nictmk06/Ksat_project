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


    <form role="form" id="generatenoticeform"  method="POST" action="generatenoticedocument" data-parsley-validate>
      @csrf

     <div class="panel  panel-primary">
      <div class="panel panel-heading origndiv">
        <h7 >Generate Notice</h7>
      </div>
      <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
      <div class="panel panel-body divstyle" >
        <?php
         $date_sys = date("d-m-Y");
        ?>
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

          </div>
        <div class="row">
            <div class="col-md-4">
            <div class="form-group">
              <label>Hearing Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="hearingDate" readonly class="form-control pull-right datepicker" id="hearingDate"  data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter Hearing Date"  data-parsley-required data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
          </div>
		    <div class="col-md-4">
                <div class="form-group">
                  <label>Bench Type<span class="mandatory">*</span></label>
                  <select name="benchCode" id="benchCode" readonly class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA"><option value="" >Select Bench Type</option>
                  @foreach($Benches as $bench)
                  <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Bench <span class="mandatory">*</span></label>
                <select name="benchJudge" id="benchJudge" readonly class="form-control" data-parsley-required data-parsley-required-message="Select Pending IA">
                  <option value=''>Select Bench</option>

                   @foreach($benchjudge as $bench)
                  <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
                  @endforeach

              </select>
            </div>
          </div>
</div>
		  <div class="row">
		    <div class="col-md-12">
            <div class="form-group">
              <label>Court Direction<span class="mandatory">*</span></label>
             <textarea class="form-control" name="courtDirection" id="courtDirection" readonly rows="3" cols="70" required> </textarea> </td>
        </div>
		  </div>
	 </div>

	 <div class="row">
	  <div class="col-md-4">
		<div class="form-group">
		  <label>Order Passed <span class="mandatory">*</span></label>
			<select class="form-control" name="OrderPassed" id="OrderPassed" readonly data-parsley-required data-parsley-required-message="Select Order Type" data-parsley-trigger='change'>
				  <option value="">Select Order Passed</option>
				   @foreach($OrderType as $OrderType)
				   <option value="{{$OrderType->ordertypecode}}">{{$OrderType->ordertypedesc}}</option>
				  @endforeach
		   </select>
	  </div>
</div>
<div class="col-md-4">
    <div class="form-group">
      <label>Select Notice type <span class="mandatory">*</span></label>
      <select class="form-control" name="noticetype" id="noticetype" data-parsley-required data-parsley-required-message="Select notice type">
	  <option value="0" >Select notice type</option>
	  <option value="Y" >Yes</option>
	  <option value="N" >No</option>
    </select>
  </div>
</div>

</div>
   <!--<div class="col-md-4">
              <div class="form-group">
			    <label>Select Respondents <span class="mandatory">*</span></label>
                  <select class="form-control" name="respondantDetails" id="respondantDetails" data-parsley-trigger='keypress'
				   data-parsley-required data-parsley-required-message="Select Respondents">
                  <option value="">Select Respondents</option>
                 </select>
                 </div>
            </div>



 <div class="row">
		 <div class="col-md-4">
			<div class="form-group">
			  <label>Reply Required <span class="mandatory">*</span></label>
			  <select class="form-control" name="replyrequired" id="replyrequired" ><option value="0" >Select Reply Required</option>
			  <option value="Y" >Yes</option>
			  <option value="N" >No</option>
			</select>
		  </div>
		</div>
            <div class="col-md-4">
            <div class="form-group">
              <label>Last date of reply submission<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="replyDate" class="form-control pull-right datepicker" id="replyDate"  data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter Hearing Date"  data-parsley-required data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
          </div>
            <div class="col-md-6">
            <div class="form-group">
              <label>Remarks<span class="mandatory">*</span></label>
                <textarea class="form-control" name='remarks' id="remarks"  data-parsley-pattern="/^[-@.\/,()#+\w\s]*$/"
					  data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter order template'
                    data-parsley-maxlength='100' data-parsley-maxlength-message="order template accepts upto 5000 characters" ></textarea>

          </div>
		  </div>
	 </div>-->
	 </br>
	 <div class="row divstyle"  style="float: middle;" id="add_apl_div">
		<div  class="col-sm-10 text-center">
		  <button type="submit" class="btn btn-primary" Style="width: 150px;" >Generate Notice </button>
		  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
		</div>
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
	</div>
<!-- /.tab-pane -->

<script src="js/jquery.min.js"></script>
<script src="js/casefollowup/noticegeneration.js"></script>

</section>
@endsection
