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


    <form role="form" id="causelisttransferform"  method="POST" action="transferCauseList" data-parsley-validate>
      @csrf

     <div class="panel  panel-primary">
      <div class="panel panel-heading origndiv">
        <h7 >Transfer Causelist</h7>
      </div>
      <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
      <div class="panel panel-body divstyle" >
        <?php
         $date_sys = date("d-m-Y");
        ?>
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
            <div class="col-md-8">
            <div class="form-group">
              <label>Finalized Causelist<span class="mandatory">*</span></label>
              <select name="causelist" id="causelist" class="form-control" data-parsley-required data-parsley-required-message="Select Causelist"><option value="" >Select Causelist</option>

            </select>
          </div>



        </div>

      </div>
	  </div>
	    <div class="" style="height:70px;" >
          <table id="my1"   class=" table table-striped  table order-list table-responsive"  border="1"  >
            <thead >
              <tr style="color:orange" >
                <!--  <td>Sr.no</td> -->
           <!--    <td class="grid-item" width="15%">Sr.No.</td> -->
              <td class="grid-item" width="15%">Select  <br> <input type="checkbox" id="toggleCheckbox"> <label style="color:blue;"> Toggle All </label></td >
              <td class="grid-item" width="35%">Application No.</td>
			  <!--<td class="grid-item" width="25%">Connected Appl</td>-->
              <td class="grid-item"  width="45%">Posted for </td>

            </tr>
          </thead>
        </table>
      </div>
      <div class="ext1">
         <table id="myTable1" class=" table table-striped  table order-list table-responsive"  border="1" >
          <tbody id="results3" >
          </tbody>
          </table>
         </div>

    </div>
   <div class="panel  panel-primary">
	  <div class="panel panel-heading origndiv">
        <h7 >Transfer To</h7>
      </div>
	  <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>To Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="tohearingDate" class="form-control pull-right datepicker" id="tohearingDate"
					data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"
					data-parsley-required-message="Enter Hearing Date"  data-parsley-required data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Bench Type<span class="mandatory">*</span></label>
              <select name="benchCode" id="benchCode" class="form-control" data-parsley-required data-parsley-required-message="Select Bench Type"><option value="" >Select Bench Type</option>
              @foreach($Benches as $bench)
              <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Bench <span class="mandatory">*</span></label>
            <select name="benchJudge" id="benchJudge" class="form-control" data-parsley-required data-parsley-required-message="Select Bench">
              <option value=''>Select Bench</option>

              @foreach($benchjudge as $bench)
              <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
	   <div class="row">

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
          <label>List No<span class="mandatory">*</span></label>
          <select class="form-control" name="listno" id="listno" data-parsley-required data-parsley-required-message="Select Court Hall">><option value="0" >Select List No</option>
          @foreach($list as $lists)
          <option value="{{$lists->listcode}}">{{$lists->listcode}}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label>Court Hall<span class="mandatory">*</span></label>
        <select class="form-control" name="courthall" id="courthall" data-parsley-required data-parsley-required-message="Select Court Hall">><option value="" >Select Court Hall</option>
        @foreach($CourtHalls as $courthall)
        <option value="{{$courthall->courthallno}}">{{$courthall->courthalldesc}}</option>
        @endforeach
      </select>
    </div>
  </div>
  </div>
 </br>
	  <div class="row divstyle"  style="float: center;" id="add_apl_div">
		<div class="col-sm-12 text-center">
		 <input name='causelistcode' id='causelistcode' value='' type='hidden'>
		 <input type="submit" id="transferapplication" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Transfer">
		 <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
	</div>
</div>
</br>

</div>











	</form>
<!-- /.tab-pane -->

<script src="js/jquery.min.js"></script>
<script src="js/casemanagement/causelisttransfer.js"></script>

</section>
@endsection
</div>
