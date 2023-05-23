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


   <div align="center">
 <section class="content" style="width: 75%">
  <div class="panel  panel-primary">
   <form name="form1" id="form1" action="datadisposedapplication" method="post">
    {{ csrf_field() }}
<div class="col-md-10">
<table class="table no-margin table-bordered">

    <tr>
    <td  class="bg-primary text-center" colspan="4"> <h4>  List of Disposed Applcation </h4> </td>
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
       <input type="text" name="todate" class="form-control pull-right datepicker "   id="todate" value=""   data-parsley-errors-container="#error6" >
     </div>
     <span id="error6"></span>
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
<form role="form" id="disposed" name="disposed" class="" action="" method="GET" data-parsley-validate>
  <div class="panel panel-primary">
    <table class="table table-bordered table2 display" data-page-length="25" id="myTable" width="100%">
      <thead>
        <tr>
          <td class="bg-primary text-center" colspan="15" id="subtitle"><h4>List of Disposed Applcation</h4></td>
        </tr>
        <tr>
          <td><input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">Sr.No</td>
          <td>Application Description</td>
          <td>Application count</td>
          <td>Applicant count</td>
          <td>Group Application count</td>

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
   $("#form1").on('change',function(){
     var _token=$("#token").val();
     var fromdate= $("#fromdate").val();
     var todate=$("#todate").val();


      $.ajax({
        type:"POST",
        url:"disposedapplication1",
        cache:false,
        data:{"_token":_token,fromdate:fromdate,todate:todate},
        success:function(result){
          $("#myTable tbody").html(result);
          $('#subtitle').html('<h4>List of Disposed Applcation from '+fromdate+' to '+todate+'</h4>');
        }
      })

   })
});


      </script>


  @endsection
