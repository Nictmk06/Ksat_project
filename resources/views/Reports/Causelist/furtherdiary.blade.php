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
   <form name="form1" id="form1" action="datafurtherdiary" method="post">
    {{ csrf_field() }}
<div class="col-md-10">
<table class="table no-margin table-bordered">

    <tr>
    <td  class="bg-primary text-center" colspan="4"> <h4>Further Diary </h4> </td>
    </tr>




<tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> From Date</label> </td>
    <td>
      <div class="input-group date">
      <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
      </div>
           <input type="text"  name="fromdate"  class="form-control pull-right datepicker1" id="fromdate"  value=""  >
          </div>


       </td>
</tr>

<tr>
<td> <span class="mandatory">*</span> <label for="applTitle">To Date </label> </td>
<td>
  <div class="input-group date">
       <div class="input-group-addon">
         <i class="fa fa-calendar"></i>
       </div>
       <input type="text" name="todate" class="form-control pull-right datepicker1"   id="todate" value=""    >
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



</table>
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
@endsection
<script src="js/jquery-3.4.1.js"></script>



<script>
$(document).ready(function(){
  /*$("#fromdate").datepicker({
              format: 'dd-mm-yyyy',
              autoclose: true,
            }).on('changeDate', function(selected) {
              var endDate = new Date(selected.date.valueOf());
             $('#todate').datepicker('setStartDate', endDate);
            }).on('clearDate', function(selected) {
              $('#todate').datepicker('setEndDate', null);
            });*/


  $("#fromdate").datepicker({
      format: "dd-mm-yyyy"
  }).datepicker("setDate", "0");

  $("#todate").datepicker({
          format: "dd-mm-yyyy",

      });

});
</script>
