@extends('layout.mainlayout')
@section('content')

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
<div class="content-wrapper">
<!--<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-3.4.1.slim.min.js"></script>
<script type="text/javascript" src="js/popper.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/jquery-3.4.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/buttons.flash.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>-->

   <div align="center">
 <section class="content" style="width: 75%">
  <div class="panel  panel-primary">
   <form name="form1" id="form1" action="{{ route('data') }}" method="post">
    {{ csrf_field() }}
<div class="col-md-10">
<table class="table no-margin table-bordered">

    <tr>
    <td  class="bg-primary text-center" colspan="4"> <h4>  List of Application Received </h4> </td>
    </tr>
<tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> From Date</label> </td>
    <td>
      <div class="input-group date">
           <div class="input-group-addon">
             <i class="fa fa-calendar"></i>
           </div>
           <input type="text" name="fromdate" class="form-control pull-right datepicker "   id="fromdate" value=""   data-parsley-errors-container="#error6" >
         </div>
         <span id="error6"></span>

       </td>
</tr>

<tr>
<td> <span class="mandatory">*</span> <label for="applTitle">To Date </label> </td>
<td>
  <div class="input-group date">
       <div class="input-group-addon">
         <i class="fa fa-calendar"></i>
       </div>
       <input type="text" name="todate" class="form-control pull-right datepicker"   id="todate" value=""   data-parsley-errors-container="#error6" >
     </div>
     <span id="error6"></span>
  </td>
</tr>
<tr>
  <td><label>Application Type<span class="mandatory">*</span></label></td>
  <td>
    <div class="form-group">

      <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-required data-parsley-required-message="Select Connected Application Type" >
        <option value="">Select Application Type</option>
         <option value="-1" >All</option>
       @foreach($applicationType as $applType)
        <option value="{{ $applType->appltypeshort}}" >{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
        @endforeach

      </select>
    </div>
  </td>
</tr>
     <tr>
    <td colspan="4">
    <div class="text-center">
        <input type="submit" accesskey="s" class="btn btn-primary" value="Submit" id="submit" width="100px" >
           <a class="btn btn-warning" href=""> Cancel </a>
    </div>

    </td>
    </tr>
</form>
<form role="form" id="appliactionreceived" name="applicationreceived" action="" method="GET"  data-parsely-validate>
  <div class="panel panel-primary">
   <table class="table table-border table2 display" data-page-length="25" id="myTable" width="100%">
    <thead>
      <tr>
        <td class="bg-primary text-center" colspan="15" id="subtitle">
        <h4>List of Applcation Received from </h4>
      </td>
      </tr>
      <tr>
        <td><input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">Sr.No</td>
        <td>Application Type</td>
        <td>Application Count</td>
        <td>Applicant Count</td>
       <td>Group Application Count</td>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
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
 </div>
</div>
</section>
</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
 <script>

 $(document).ready(function() {
   $("#fromdate").datepicker({
               format: 'dd-mm-yyyy',
               autoclose: true,
             }).on('changeDate', function(selected) {
               var endDate = new Date(selected.date.valueOf());
               $('#todate').datepicker('setStartDate', endDate);
             }).on('clearDate', function(selected) {
               $('#todate').datepicker('setEndDate', null);
             });
 });


$(document).ready(function(){
  $("select").on('change',function(){
    var _token=$("#token").val();
    var applTypeName=$("#applTypeName").val();
    var fromdate=$("#fromdate").val();
    var todate=$("#todate").val();


    $.ajax({
      method:"POST",
      url:"applicationreceived1",
      cache:false,
      data:{"_token":_token,applTypeName:applTypeName,fromdate:fromdate,todate:todate},
      success:function(result){
          $("#myTable tbody").html(result);
          $("#subtitle").html('<h4>List of Applcation Received from '+fromdate+' to '+todate+'</h4>');
      }
    })
  })
})


      </script>


  @endsection
