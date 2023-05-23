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

<?php
 $purposecode=DB::select("select max(purposecode) as purposecode from listpurpose")[0];
$prposecode=$purposecode->purposecode+1;
$userSave['purposecode']= $prposecode;
?>
@include('flash-message')
<br> <br>
<div class="container">

<form action="ListPurposeSave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4>  LIST PURPOSE </h4> </td>
        </tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> Purpose Code </label> </td>


          <td>
            <input type="numeric" class="form-control pull-right"   data-parsley-pattern="/[0-9 ]/" data-parsley-pattern-message="Purpose code allows only numeric" data-parsley-pattern-message="Purpose code allows only numeric" data-parsley-required-message="Purpose code" id="purposecode" name="purposecode" value="<?php $purposecode=DB::select("select max(purposecode) as purposecode from listpurpose")[0];
           $prposecode=$purposecode->purposecode+1;
           $userSave['purposecode']= $prposecode;
           echo   $userSave['purposecode'] ?>"readonly="readonly">

          </td>



        </tr>

        <tr>
          <td> <span class="mandatory">*</span> <label for="applTitle">List Purpose </label> </td>
          <td>
            <textarea class="form-control" name='listpurpose' id="listpurpose" value="" data-parsley-required  data-parsley-required-message="List purpose" >
            </textarea>
        </td>
</tr>





        <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle">List Order </label> </td>
            <td>
              <input type="numeric" class="form-control pull-right"   data-parsley-pattern="/[0-9 ]/" data-parsley-pattern-message="List order  allows only numeric" id="listorder" name="listorder" data-parsley-required-message="list order" value="">
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
  </table>
</div>
</div>
 <div class="row">
                <table id="myTable4" class="table table-bordered table-striped  table order-list" style="width:90%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Purpose Code</td>
                      <td>List Purpose</td>
                      <td>List Order</td>


                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                  	@foreach($listorder as $listpurpose)

                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$listpurpose->purposecode}}" class="extraClick"> {{$listpurpose->purposecode}}   </a></td>


                  <td>{{$listpurpose->listpurpose}}</td>
                  <td>{{$listpurpose->listorder}}</td>




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
			var purposecode   = $(this).attr('data-value');
			console.log('hiii '+purposecode);
			$.ajax({
				type: 'get',
				url: "getListPurposeDetails",
				dataType:"JSON",
				data: {"_token": $('#token').val(),purposecode:purposecode},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
			    console.log('inside success '+json[0].purposecode);
          $("#purposecode").val(json[0].purposecode);
					$("#purposecode").attr('readonly', true);
					$("#listpurpose").val(json[0].listpurpose);
					//$("#userid").attr('readonly', true);
					$("#listorder").val(json[0].listorder);


					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');

					console.log('inside success '+json[1]);
				//	 $("#department option:selected").removeAttr("selected");




						}
					});
		});
});
</script>
<script>
         $(document).ready(function() {
        $('#district').on('change', function() {
            var districtID = $(this).val();
            if(districtID) {
                $.ajax({
                    url: 'findDistrictWithTaluk/'+districtID,
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: "json",
                    success:function(data) {
                        console.log(data);
                      if(data){
                        $('#taluk').empty();
                        $('#taluk').focus;
                        $('#taluk').append('<option value="">-- Select Taluk --</option>');
                        $.each(data, function(key, value){
                          console.log(key);
                          console.log(value);
                        $('select[name="taluk"]').append('<option value="'+ value.talukcode +'">' + value.talukname+ '</option>');

                    });
                  }

                  else{
                    $('taluk').empty();
                  }
                  }
                });
            }else{
              $('taluk').empty();
            }
        });
    });
    </script>

@endsection
