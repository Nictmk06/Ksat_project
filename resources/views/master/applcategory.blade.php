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

<form action="applcategorySave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Application Subject Cateogry </h4> </td>
        </tr>




<tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> Application Cateogry code</label> </td>
<td>
<input type="numeric" class="form-control pull-right" id="applcatcode" name="applcatcode"
value="<?php $applcatcode=DB::select("SELECT max(CAST(applcatcode as INT))as applcatcode from applcategory")[0];
          $prposecode=$applcatcode->applcatcode+1;
          $userSave['applcatcode']= $prposecode;
          echo   $userSave['applcatcode'] ?>"  readonly='readonly' >
</td>
</tr>
<tr>
    <td> <span class="mandatory">*</span> <label for="applTitle">Application Subject Name</label> </td>
        <td>
            <input type="text" class="form-control pull-right" id="applcatname" name="applcatname"    data-parsley-pattern="/[A-Za-z!@#$%^&,'*()]/" data-parsley-pattern-message=" Application Subject can only Alphanumeric" data-parsley-pattern-message="Application Subject can  allows only Alphanumeric" value=""
             data-parsley-required  data-parsley-required-message="Enter Application Subject Name">
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
                      <td>Application Cateogry Code</td>
                      <td>Application Subject Name</td>


                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>

                      @foreach($applcategory as $applcategory)


                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$applcategory->applcatcode}}" class="extraClick"> {{$applcategory->applcatcode}}   </a></td>



                  <td>{{$applcategory->applcatname}}</td>




                  </tr>
                  @endforeach

                </tbody>
              </table>
            </div>
<script src="js/jquery-3.4.1.js"></script>
<script>


 //$("#todate").datepicker( { minDate: -0,dateFormat: 'dd-mm-yyyy', maxDate: new Date(2050, 1,18) });
 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick").click(function(){
			//console.log('hiii ');
			var applcatcode   = $(this).attr('data-value');
			//console.log('hiii '+userid);

			$.ajax({
				type: 'get',
				url: "getapplcategory",
				dataType:"JSON",
				data: {"_token": $('#token').val(),applcatcode:applcatcode},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
          $("#applcatcode").val(json[0].applcatcode);
          $("#applcatname").val(json[0].applcatname);







					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');






						}
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
