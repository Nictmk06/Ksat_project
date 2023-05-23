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
  .divstyle
  {
  padding-top: 0px;
  padding-bottom: 0px;
  }
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')
	  <section class="content">
	<div class="panel  panel-primary">

	<div class="panel panel-heading origndiv">
			<h7 >Add New Bench</h7>
		</div>	<form action="bench" method="post">
			{{ csrf_field() }}
		<div class="panel panel-body divstyle" >

			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Type of Bench<span class="mandatory">*</span></label>
						<select name="benchtypename" id="benchtypename" class="form-control dynamic" data-dependent="judgecount" data-parsley-required data-parsley-required-message="Select Bench Type">
						<option value="" >Select Bench Type</option>

						@foreach($BenchType as $benchtype)
						<option value="{{$benchtype->judgecount}}" @if(old('benchtypename')==$benchtype->judgecount) selected @endif>{{$benchtype->benchtypename}}::{{$benchtype->judgecount}}</option>
						@endforeach
						</select>
						<br />


					</div>

				</div>


			<div class="col-md-4">
				<div class="form-group">
				  <label>Effective Date From</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" name="FromDate" class="start form-control pull-right" id="FromDate"  value="{{old('FromDate')}}" data-date-format="dd/mm/yyyy" data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter Date">
						<!--<input type="text" name="FromDate" class="start form-control pull-right" id="FromDate" value=""  data-parsley-trigger='keypress' data-parsley-errors-container="#error20" data-parsley-required-message="Enter Order Date"> -->
				    </div>
				  <span id="error5"></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
				  <label>Effective Date To</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" name="todate" class="end form-control pull-right" id="todate"  value="{{old('todate')}}" data-date-format="dd/mm/yyyy" data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter Date">
						<!--<input type="text" name="FromDate" class="start form-control pull-right" id="FromDate" value=""  data-parsley-trigger='keypress' data-parsley-errors-container="#error20" data-parsley-required-message="Enter Order Date"> -->
				    </div>
				  <span id="error5"></span>
				</div>
			</div>



			</div><!--end row1-->
			<div class="row">


				<div class="col-md-4">
					<div class="form-group">
						<label>Name of the Judge <span class="mandatory">*</span></label>
						<select name="benchJudge" id="benchJudge" class="form-control" data-parsley-required data-parsley-required-message="Select Judge">
							<option value=''>Select Judge</option>
								@foreach($jname as $judge)
									<option value="{{$judge->judgecode}}" @if(old('benchJudge')==$judge->judgecode) selected @endif>{{$judge->judgename}} </option>

								@endforeach
						</select>

					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Designation<span class="mandatory">*</span></label>
						<select name="judgeDesig" id="judgeDesig" class="form-control" data-parsley-required data-parsley-required-message="Select Judge Designation">
							<option value=''>Select Judge Designation</option>
							@foreach($jdesig as $jdesig)
							<option value="{{$jdesig->judgedesigcode}}" @if(old('judgeDesig')==$jdesig->judgedesigcode) selected @endif>{{$jdesig->judgedesigname}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div><!--end row2-->
			<div class="row">
				<div class="col-md-4" id="benchJudge1">
					<div class="form-group">
						<label>Name of the Judge <span class="mandatory">*</span></label>
						<select name="benchJudge1" id="benchJudge1" class="form-control" data-parsley-required data-parsley-required-message="Select Judge">
							<option value=''>Select Judge</option>
								@foreach($jname as $judge)
									<option value="{{$judge->judgecode}}" @if(old('benchJudge1')==$judge->judgecode) selected @endif>{{$judge->judgename}}</option>
								@endforeach
						</select>

					</div>
				</div>
				<div class="col-md-4" id="judgeDesig1">
					<div class="form-group">
						<label>Designation<span class="mandatory">*</span></label>
						<select name="judgeDesig1" id="judgeDesig1" class="form-control" data-parsley-required data-parsley-required-message="Select Judge Designation">
							<option value=''>Select Judge Designation</option>
							@foreach($jdesig2 as $jdesig)
							<option value="{{$jdesig->judgedesigcode}}" @if(old('judgeDesig1')==$jdesig->judgedesigcode) selected @endif>{{$jdesig->judgedesigname}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div><!--end row3-->
			<div class="row">
				<div class="col-md-4" id="benchJudge2">
					<div class="form-group">
						<label>Name of the Judge <span class="mandatory">*</span></label>
						<select name="benchJudge2" id="benchJudge2" class="form-control" data-parsley-required data-parsley-required-message="Select Judge">
							<option value=''>Select Judge</option>
								@foreach($jname as $judge)
									<option value="{{$judge->judgecode}}" @if(old('benchJudge2')==$judge->judgecode) selected @endif>{{$judge->judgename}}</option>
								@endforeach
						</select>

					</div>
				</div>
				<div class="col-md-4" id="judgeDesig2">
					<div class="form-group">
						<label>Designation<span class="mandatory">*</span></label>
						<select name="judgeDesig2" id="judgeDesig2" class="form-control" data-parsley-required data-parsley-required-message="Select Judge Designation">
							<option value=''>Select Judge Designation</option>
							@foreach($jdesig2 as $jdesig)
							<option value="{{$jdesig->judgedesigcode}}" @if(old('judgeDesig2')==$jdesig->judgedesigcode) selected @endif>{{$jdesig->judgedesigname}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div><!--end row4-->
			<div class="row">
				<div class="col-md-4" id="benchJudge3">
					<div class="form-group">
						<label>Name of the Judge <span class="mandatory">*</span></label>
						<select name="benchJudge3" id="benchJudge3" class="form-control" data-parsley-required data-parsley-required-message="Select Judge">
							<option value=''>Select Judge</option>
								@foreach($jname as $judge)
									<option value="{{$judge->judgecode}}" @if(old('benchJudge3')==$judge->judgecode) selected @endif>{{$judge->judgename}}</option>
								@endforeach
						</select>

					</div>
				</div>
				<div class="col-md-4" id="judgeDesig3">
					<div class="form-group">
						<label>Designation<span class="mandatory">*</span></label>
						<select name="judgeDesig3" id="judgeDesig3" class="form-control" data-parsley-required data-parsley-required-message="Select Judge Designation">
							<option value=''>Select Judge Designation</option>
							@foreach($jdesig2 as $jdesig)
							<option value="{{$jdesig->judgedesigcode}}" @if(old('judgeDesig3')==$jdesig->judgedesigcode) selected @endif>{{$jdesig->judgedesigname}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div><!--end row5-->
			<div class="row">
				<div class="col-md-4" id="benchJudge4">
					<div class="form-group">
						<label>Name of the Judge <span class="mandatory">*</span></label>
						<select name="benchJudge4" id="benchJudge4" class="form-control" data-parsley-required data-parsley-required-message="Select Judge">
							<option value=''>Select Judge</option>
								@foreach($jname as $judge)
									<option value="{{$judge->judgecode}}" @if(old('benchJudge4')==$judge->judgecode) selected @endif>{{$judge->judgename}}</option>
								@endforeach
						</select>

					</div>
				</div>
				<div class="col-md-4" id="judgeDesig4">
					<div class="form-group">
						<label>Designation<span class="mandatory">*</span></label>
						<select name="judgeDesig4" id="judgeDesig4" class="form-control" data-parsley-required data-parsley-required-message="Select Judge Designation">
							<option value=''>Select Judge Designation</option>
							@foreach($jdesig2 as $jdesig)
							<option value="{{$jdesig->judgedesigcode}}" @if(old('judgeDesig4')==$jdesig->judgedesigcode) selected @endif>{{$jdesig->judgedesigname}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div><!--end row6-->

			@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
				</ul>
			</div><!--END IF DIV-->
			@endif
			<div class="flash-message">
				@foreach (['danger', 'warning', 'success', 'info'] as $msg)
					@if(Session::has('alert-' . $msg))

						<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
					@endif
				@endforeach
			</div> <!-- end .flash-message -->
			<div class="row">
				<div class="col-md-4">
					<button type="submit" class="btn btn-primary" >Submit</button>
					<input type="reset" class="btn btn-danger" " value="Cancel">
				</div>
			</div><!--end row4 SUBMIT-->
			</br>
			<!--Display-->
			<div class="panel  panel-primary">
				<div class="panel panel-heading origndiv">
					<h7>List of Judges </h7>
				</div><!--judges div-->
				<div class='panel panel-body divstyle'  overflow: auto;>
					<div class="row">
						<table id="myTable1" class="table table-bordered table-striped  table order-list table-responsive"  >
							<thead >
								<tr style="background-color: #3c8dbc;color:#fff;" >
								  <!--  <td>Sr.no</td> -->
								<td class="col-md-2">Bench Code</td>
								<td class="col-md-2">Type of Bench</td>
								<td class="col-md-2">Judge</td >
								<td class="col-md-2">Designation</td>
								<td class="col-md-2">Active</td>
								<td class="col-md-2">Effective Date From</td>
								<td class="col-md-2">Effective Date To</td>
								<th width="150px">Action</th>
								<td id='delete' style="display: none;"></td>
							  </tr>
							</thead>
							<tbody id="results3" >

								@foreach($benchjudge as $show)
								<tr>
									<td>{{$show->benchcode}}</td>
									<td>{{$show->benchtypename}}</td>
									<td>{{$show->judgeshortname}}</td>
									<td>{{$show->judgedesigshort}}</td>
									<td>{{$show->display}}</td>
									<td>{{$show->fromdate}}</td>
									<td>{{$show->todate}}</td>
									<td align="center">
										
								<a class="btn btn-info" href="{{ route('benchedit', ['benchcode' => $show->benchcode]) }}">Edit</a>
								</td>

                            {{csrf_field()}}
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>


					</div><!--end of table-->
				</div><!--AUto div end-->
			</div><!--panel  panel-primary-->
		</form>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
			$(document).ready(function()
                  {
					$("#judgeDesig1").hide();
					$("#benchJudge1").hide();
					$("#judgeDesig2").hide();
					$("#benchJudge2").hide();
					$("#judgeDesig3").hide();
					$("#benchJudge3").hide();
					$("#judgeDesig4").hide();
					$("#benchJudge4").hide();

			$("#benchtypename").change(function()
			{

						if($(this).val() == "2")
					{
						$("#judgeDesig1").show();
						$("#benchJudge1").show();
						$("#judgeDesig2").hide();
						$("#benchJudge2").hide();
						$("#judgeDesig3").hide();
						$("#benchJudge3").hide();
						$("#judgeDesig4").hide();
						$("#benchJudge4").hide();


					}
					else if($(this).val() == "3")
					{
						$("#judgeDesig1").show();
						$("#benchJudge1").show();
						$("#judgeDesig2").show();
						$("#benchJudge2").show();
						$("#judgeDesig3").hide();
						$("#benchJudge3").hide();
						$("#judgeDesig4").hide();
						$("#benchJudge4").hide();
					}
					else if($(this).val() == "4")
					{
						$("#judgeDesig1").show();
						$("#benchJudge1").show();
						$("#judgeDesig2").show();
						$("#benchJudge2").show();
						$("#judgeDesig3").show();
						$("#benchJudge3").show();
						$("#judgeDesig4").hide();
						$("#benchJudge4").hide();
					}
					else if($(this).val() == "5")
					{
						$("#judgeDesig1").show();
						$("#benchJudge1").show();
						$("#judgeDesig2").show();
						$("#benchJudge2").show();
						$("#judgeDesig3").show();
						$("#benchJudge3").show();
						$("#judgeDesig4").show();
						$("#benchJudge4").show();

					}
					else {
						$("#judgeDesig1").hide();
						$("#benchJudge1").hide();
						$("#judgeDesig2").hide();
						$("#benchJudge2").hide();
						$("#judgeDesig3").hide();
						$("#benchJudge3").hide();
						$("#judgeDesig4").hide();
						$("#benchJudge4").hide();

						}

			});
					});
	</script>
	<script>
$(document).ready(function(){

 $('.dynamic').change(function(){
  if($(this).val() != '')
  {
   var select = $(this).attr("id");
   var value = $(this).val();
   var dependent = $(this).data('dependent');
   var _token = $('input[name="_token"]').val();
   	var benches = {!!json_encode($BenchType->toArray())!!};
	console.log(benches);

   $.ajax({
    url:"benchcontroller.fetch",
    method:"POST",
    data:{select:select, value:value, _token:_token, dependent:dependent},
    success:function(result)
    {
     $('#'+dependent).html(result);
    }

   })
  }
 });

 $('#benchtypename').change(function(){
  $('#judgecount').val('');

 });



});
</script>

<script>
$('.start').datepicker({
	autoclose:true
}).on('changeDate',function(e){
	$('.end').datepicker('setStartDate',e.date)

});

$('.end').datepicker({
	autoclose:true
}).on('changeDate',function(e){
	$('.start').datepicker('setEndDate',e.date)
})
</script>

  </section>
  </div>
  @endsection
