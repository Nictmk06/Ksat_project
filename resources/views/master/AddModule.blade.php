@extends('layout.mainlayout')
@section('content')


<div class="content-wrapper">

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

  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>

@include('flash-message')
<br> <br>
<div class="container">

<form action="AddModuleSave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4>  Add Module  </h4> </td>
        </tr>




<tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> Module code</label> </td>
<td>
<input type="numeric" class="form-control pull-right" id="modulecode" name="modulecode"
value="<?php $modulecode=DB::select("SELECT max(CAST(modulecode as INT))as modulecode from module")[0];
          $modulecode=$modulecode->modulecode+1;
          $userSave['modulecode']= $modulecode;
          echo   $userSave['modulecode'] ?>"  readonly='readonly' >
</td>
</tr>
<tr>
    <td> <span class="mandatory">*</span> <label for="applTitle"> Module Name</label> </td>
        <td>
            <input type="text" class="form-control pull-right" id="modulename" name="modulename"    value="" data-parsley-required  data-parsley-required-message="Enter Name of the Module">
           </td>
</tr>

<tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> Module order</label> </td>
<td>
<input type="text" class="form-control pull-right" id="moduleorder" name="moduleorder"
value=""   data-parsley-trigger="keyup"
data-parsley-type="digits" data-parsley-required  data-parsley-required-message="Enter Module Order" >
</td>
</tr>


         <tr>
        <td colspan="4">
        <div class="text-center">
          <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
             <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">


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

 <div class="row">
                <table id="myTable4" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Module Code</td>
                      <td>Module Name</td>
                      <td>Module order</td>



                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>

                      @foreach($module as $module)


                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$module->modulecode}}" class="extraClick"> {{$module->modulecode}}   </a></td>



                  <td>{{$module->modulename}}</td>
                  <td>{{$module->moduleorder}}</td>




                  </tr>
                  @endforeach

                </tbody>
              </table>
            </div>
  <script src="js/jquery-3.4.1.js"></script>
<script>
$(document).ready(function() {
  	$(".extraClick").click(function(){
			//console.log('hiii ');
			var modulecode   = $(this).attr('data-value');
			//console.log('hiii '+userid);
     console.log(modulecode);
			$.ajax({
				type: 'get',
				url: "getModuleDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),modulecode:modulecode},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
          $("#modulecode").val(json[0].modulecode);
          $("#modulename").val(json[0].modulename);
          $("#moduleorder").val(json[0].moduleorder);


      }
					});
		});
});
</script>

{{-- <script src="http://bladephp.co/download/multiselect/jquery.min.js"></script>
<link href="http://bladephp.co/download/multiselect/jquery.multiselect.css" rel="stylesheet" />
<script src="http://bladephp.co/download/multiselect/jquery.multiselect.js"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> --}}


</script>

@endsection
