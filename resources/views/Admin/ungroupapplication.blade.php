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
  div.ext1 {
  height: 150px;
  width: 90%;
  overflow-y: scroll;
}


  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

  <section class="content">

      <form role="form" id="ungroupapplicationForm" method="POST" action="saveungroupapplications" data-parsley-validate>
      @csrf
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

                <input type="hidden" name="applicationtosrno" id='applicationtosrno' value=''>
				 <input type="hidden" name="applicationsrno" id='applicationsrno' value=''>
				  <input type="hidden" name="applicantcount" id='applicantcount' value=''>
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
                    <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" data-parsley-errors-container="#modlerror" data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='15'>
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div>

                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>


        </div>

		<div class="row appldiv " id="appldiv"  style="display: none">
               <div class="col-md-4">
                <div class="form-group">
                  <label for=""> Application No</label>
				  <div name="applicationNo" id="applicationNo"></div>
				    <div name="additionalNo" id="additionalNo"></div>
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
                            <label>Order No<span class="mandatory">*</span></label>
                            <input class="form-control  " name="orderno" id="orderno" type='' required data-parsley-pattern="/^[a-zA-Z0-9.,-/ ()\n\r]+$/"
							data-parsley-pattern-message="Invalid Order No." data-parsley-trigger='keypress' data-parsley-required-message="Enter Order No" maxlength="20">
                          </div>
                        </div>
                <div class="col-md-4">
                 <div class="form-group">
                            <label>Order Date<span class="mandatory">*</span></label>
                           <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" name="orderdate" id="orderdate" value="" class="form-control pull-right datepicker" required   data-parsley-trigger='keypress' data-parsley-errors-container="#error20" data-parsley-required-message="Enter Order Date">
                            </div>
							<span id="error20"></span>
                          </div>
                </div>
				</div>
			 <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Ungroup Start No<span class="mandatory">*</span></label>

                  <input type="text" name="ungroupstno" class="form-control pull-right number zero" id="ungroupstno" value=""  data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='5'>
                </div>
              </div>
                <div class="col-md-4">
                <div class="form-group">
                  <label>Ungroup End No<span class="mandatory">*</span></label>

                  <input type="text" name="ungroupendno" class="form-control pull-right number zero" id="ungroupendno" value=""  data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='5'>
                </div>
              </div>
			  </div>
			 <br><br>

		<div class="row">
		 <div class="col-md-8" style="height:40px;width:90%" >
          <table id="my1"   class=" table table-striped  table order-list table-responsive"  border="1"  >
            <thead >
              <tr style="color:orange" >
                <!--  <td>Sr.no</td> -->
           <!--    <td class="grid-item" width="15%">Sr.No.</td> -->
              <td class="grid-item" width="15%">Select </td >
              <td class="grid-item" width="35%">Sr No.</td>
			 <td class="grid-item"  width="45%">Name </td>

            </tr>
          </thead>
        </table>
      </div>
	   <div class="ext1 col-md-8"">
         <table id="myTable1" class=" table table-striped  table order-list table-responsive"  border="1" >
          <tbody id="results3" >
          </tbody>
          </table>
         </div>
	 </div>
   <br><br>
       <div class="row"  style="float: right;" id="add_apl_div">
              <div class="col-sm-12 text-center">
                <input type="submit" id="ungroupappl" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="ungroup">
                <input type="button" class="btn btn-danger btn-md center-block btnClear" id="clear" Style="width: 100px;" value="Cancel">
              </div>
        </div><br><br>
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
    <script src="js/admin/ungroupapplication.js"></script>
  </section>
  @endsection
