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


    <form role="form" id="officenoteform"  method="POST" action="saveofficenote" data-parsley-validate>
      @csrf

     <div class="panel  panel-primary">
      <div class="panel panel-heading origndiv">
        <h7 >Office Note</h7>
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
              <label>Office Note Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="officenoteDate" class="form-control pull-right datepicker" id="officenoteDate"    data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter officenote Date"  data-parsley-required data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
          </div>
		<div class="col-md-8">
							<div class="form-group">
							  <label>Office Note<span class="mandatory">*</span></label>
							  <textarea class="form-control" name='officenote' id="officenote" style="height: 100px; "   data-parsley-pattern="/^[-@.\/,'&()#+\w\s]*$/"
							  data-parsley-pattern-message="Subject Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter office note'
							data-parsley-maxlength='10000' data-parsley-maxlength-message="order template accepts upto 10000 characters" ></textarea>
							</div>
						  </div>
		</div>
    <input type="hidden" name="officenotecode" id="officenotecode" value="">


	 <div class="row"  style="float: right;" id="add_apl_div">
                <div class="col-sm-12 text-center">
		<!--  <button type="submit" class="btn btn-primary" Style="width: 150px;" >View Order </button>    -->
    <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
    <input type="button" id="saveofficenote" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px; " value="Save">
		<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
		</div>
		</div>
	  <br><br>
	   </div>
	 </div>
	</form>

	 <form role="form" id="ordergenerationform"  method="POST" action="generateordersheet" data-parsley-validate>
      @csrf

     <div class="panel  panel-primary">
      <div class="panel panel-heading origndiv">
        <h7 >Generate Order Sheet</h7>
      </div>

     <div class="panel panel-body">



       <div class="row">
         <div class="col-md-3">
                   <div class="form-group">

               <div class="input-group date">
                 <label>Order sheet Note for First Hearing</label>
                 <label class="radio-inline">


                   <input type="checkbox" name="firstpage" id="firstpage" value="Y" data-parsley-trigger='keypress'>Yes

                 </label>
               </div>
               <span id="error7"></span>
             </div>
          </div>
  </div>

  <div class="row">
    <div class="col-md-3">
        <div class="form-group">
          <label>Select Order Type</label>
          <select  class="form-control" id="ordertype"name="ordertype" type="text" data-parsley-trigger='keypress' >
            <option value="" disabled>Select Order type</option>

            <option value="O">Office Note</option>
            <option value="C">Court Direction</option>
            <option value="B">Both</option>
         </select>
        </div>
      </div>
  </div>

		<div class="row">
      <div class="col-md-4">
            <div class="form-group">
			 <input type="hidden" name="applId" id='applId' value=''>
              <label>From Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="fromDate" class="form-control pull-right datepicker"
				id="fromDate"  >
              </div>
              <span id="error6"></span>
            </div>
          </div>
		<div class="col-md-4">
            <div class="form-group">
              <label>To Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="toDate" class="form-control pull-right datepicker" id="toDate"  >
              </div>
              <span id="error7"></span>
            </div>

	      </div>




		   </div>

		 <div class="row"  style="float: right;" id="">
		<div class="col-sm-12 text-center">
				  <div  class="form-group;align-bottom">
		  <button type="submit" class="btn btn-primary" Style="width: 170px;" >Generate Order Sheet </button>
		 </div>
		 </div>
	  <br><br>
	   </div>
	 </div>
	</form>
	<div class="panel panel-body">
      <div class="row">
                <table id="myTable" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
					<!--<td style:width="10%">Select  <br> <input type="checkbox" id="toggleCheckbox"> <label > Toggle All </label></td >
             -->
                    <td style:width="20%">Date</td>
                    <td style:width="35%">officenote </td >
                    <td style:width="35%">court direction</td>



                  </tr>
                </thead>
                <tbody id="results2" style="">

                </tbody>
              </table>
            </div>
			</div>


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
<script src="js/casefollowup/officenote.js"></script>

</section>
@endsection
