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
   <form name="form1" id="form1" action="{{ route('dataquerybox') }}" method="post">
    {{ csrf_field() }}
<div class="col-md-10">
<table class="table no-margin table-bordered">

    <tr>
    <td  class="bg-primary text-center" colspan="4"> <h4>  QueryBox </h4> </td>
    </tr>


    <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle"> Query </label> </td>
            <td>
              <textarea class="form-control" name='query' id="query" placeholder="Type your Query Here" rows="10" cols="50" data-parsley-required  data-parsley-required-message="Please Type Your Query" >
              </textarea>
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


      </script>


  @endsection
