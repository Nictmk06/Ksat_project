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

<form action="optionsmasterSave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4>  Options master Form </h4> </td>
        </tr>

 <tr>
      <td> <span class="mandatory">*</span> <label for="applTitle">Option Code </label> </td>
        <td>
          <input type="numeric" class="form-control pull-right" name="optioncode" id="optioncode"  data-parsley-pattern="/[0-9 ]/" data-parsley-pattern-message="Option code allows only numeric" data-parsley-pattern-message="Option code allows only numeric" data-parsley-required-message="Option code"  value="<?php $purposecode=DB::select("select max(optioncode) as optioncode from options")[0];
         $prposecode=$purposecode->optioncode+1;
         $userSave['purposecode']= $prposecode;
         echo   $userSave['purposecode'] ?>"readonly="readonly">

        </td>

      </tr>
   <tr>
    <td> <span class="mandatory">*</span> <label for="applTitle">Select Module </label> </td>
       <td>
         <select class="form-control" name="modulename" id="modulename" data-parsley-required  data-parsley-required-message="Select Module " >
           <option value="">Select Module Name</option>
           @foreach($modulename as $modulename)
           <option value="{{$modulename->modulecode}}">{{$modulename->modulename}}</option>
           @endforeach

         </select>
          </td>

</tr>

        <tr>
          <td> <span class="mandatory">*</span> <label for="applTitle"> Option Name</label> </td>


                <td>

                  <input type="text" class="form-control pull-right" id="optionname" name="optionname" value=""
                  data-parsley-required  data-parsley-required-message="Enter Option Name">
                </td>
              </tr>

        <tr>
          <td> <span class="mandatory">*</span> <label for="applTitle"> Link Name </label> </td>
          <td>
            <input type="text" class="form-control pull-right" id="linkname" name="linkname"    data-parsley-pattern="/^[a-zA-Z0-9_]+$/" data-parsley-required data-parsley-required-message="Enter Link name">
        </td>


          </tr>
        <tr>
          <td><label for="applTitle">Option Order </label> </td>
          <td>

          <input type="numeric" class="form-control pull-right" name="optionorder" id="optionorder"  data-parsley-pattern="/[0-9 ]/" data-parsley-pattern-message="Option order allows only numeric" data-parsley-pattern-message="Option order allows only numeric"  value=""   >
        </td>
        </tr>



        <tr>
          <td> <span class="mandatory"></span> <label for="applTitle"> Help Text </label> </td>
          <td>
            <input type="text" class="form-control pull-right" id="helptext" name="helptext"    data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  value=""
            >
        </td>







        </tr>
        <tr>
          <td> <span class="mandatory"></span> <label for="applTitle">Subtitle  </label> </td>
           <td>
             <select class="form-control" name="subtitle" id="subtitle"  >
               <option value="">Select Subtitle</option>
               <option value="Y">Y</option>
               <option value="N">N</option>


             </select>
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
                      <td>Option Code</td>
                      <td>Option Name</td>

                      <td>Module Name</td>
                      <td>Link Name</td>
                      <td>Option Order</td>
                      <td>Help Text</td>

                      <td>Subtitle</td>

                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                  	@foreach($options as $options)

                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$options->optioncode}}" class="extraClick"> {{$options->optioncode}}   </a></td>



                  <td>{{$options->optionname}}</td>


                  <td>{{$options->modulename}}</td>
                  <td>{{$options->linkname}}</td>
                  <td>{{$options->optionorder}}</td>
                  <td>{{$options->helptext}}</td>

                  <td>{{$options->subtitle}}</td>


                  </tr>
                 @endforeach

                </tbody>
              </table>
            </div>
            <script src="js/jquery-3.4.1.js"></script>

<script>
$(document).ready(function() {


 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick").click(function(){
			//console.log('hiii ');
			var optioncode   = $(this).attr('data-value');
			//console.log('hiii '+userid);
			$.ajax({
				type: 'get',
				url: "getoptionsmasterdetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),optioncode:optioncode},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
			    console.log('inside success '+json[0].optioncode);
          $("#optioncode").val(json[0].optioncode);
					$("#optioncode").attr('readonly', true);
					$("#modulename").val(json[0].modulecode);
					//$("#userid").attr('readonly', true);
					$("#optionname").val(json[0].optionname);
					$("#linkname").val(json[0].linkname);
					$("#helptext").val(json[0].helptext);
          $("#optionorder").val(json[0].optionorder);
					$("#subtitle").val(json[0].subtitle);


					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');





						}
					});
		});
});
</script>


@endsection
