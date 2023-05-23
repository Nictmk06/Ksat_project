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
<script type="text/javascript" src="js/jquery.min.js"></script>


   <div align="center">
 <section class="content" style="width: 75%">
  <div class="panel  panel-primary">
   <form name="form1" id="form1" action="datacourtproceedingsentered2" method="post">
    {{ csrf_field() }}
<div class="col-md-6">
<table class="table no-margin table-bordered">

    <tr>
    <td  class="bg-primary text-center" colspan="4"> <h4>Rollback Courtdirection  </h4> </td>
    </tr>







<tr>
  <td>   <span class="mandatory">*</span>  <label>Application ID</label></td>
  <td>
    <div>

      <input type="text" name="applicationid" class="form-control pull-right number zero" id="applicationid" value="" data-parsley-errors-container="#modlerror" data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='20'>
    </div>


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








  @endsection
