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
			<h7 >Edit Bench</h7>
		</div>

    </br>
     <form action='benchupdate' method="post" name="update_product">

	{{ csrf_field() }}
	@method('PATCH')
	<div class="panel panel-body divstyle" >
		<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<strong>Benchtypename</strong>
							<input type="text" name="benchtypename" disabled class="form-control" placeholder="Enter BenchType" value="{{ $bench_info->benchtypename }}">
							<span class="text-danger">{{ $errors->first('becnhtypename') }}</span>
       				</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<strong>Benchcode</strong>
							<input type="text" name="benchcode" disabled class="form-control" placeholder="Enter benchcode" value="{{ $bench_info->benchcode }}">
							<span class="text-danger">{{ $errors->first('benchcode') }}</span>
       				</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<strong>Bench Short Name</strong>
							<input type="text" name="judgeshortnamename" disabled class="form-control" placeholder="Enter benchcode" value="{{ $bench_info->judgeshortname }}">
							<span class="text-danger">{{ $errors->first('judgeshortname') }}</span>
       				</div>
				</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="form-group">
				<strong>Bench Members</strong>
					@foreach($bench_judge as $bench)
						<input type="text" name="display"  readonly class="display form-control" placeholder="Enter display" value="{{$bench->judgecode}}:{{$bench->judgename}}-----{{$bench->judgedesigname}}">
					@endforeach
				</div>
			</div>
		</div>
				<!--end row DIV-->
		<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<strong>Effective Date From</strong>
							<input type="text"  name="fromdate" class="start form-control" data-date-format="dd/mm/yyyy" placeholder="Enter fromdate" value="{{ $bench_info->fromdate }}">
							<span class="text-danger">{{ $errors->first('fromdate') }}</span>
       				</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<strong>Effective Date To</strong>

							<input type="text" name="todate" class="end form-control" data-date-format="dd/mm/yyyy" placeholder="Enter todate" value="{{ $bench_info->todate }}">
							<span class="text-danger">{{ $errors->first('todate') }}</span>
       				</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<strong>Active</strong>
							<input type="text" name="display" readOnly id="display" class="display form-control" placeholder="Enter display" value="{{ $bench_info->display }}" pattern="[Y,N]{1}" title="Y or N">
							<span class="text-danger">{{ $errors->first('display') }}</span>
       				</div>
				</div>
			</div><!--end row DIV-->
			<!--<button type="submit" class="btn btn-primary">Update</button>-->


            {{csrf_field()}}
	<div class="form-group">
	 </form>
	 <label>
      @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif
	<div class="row">
		<div class="col-md-4">
		 <td>
                        {{--Need the button to open up my edit form--}}
                        <!--<button form action="bench/{benchcode}/update">{{ trans('Bench Edit') }}</button>
                        {{--<input type="submit" name="update" id="update" value="Update" class="btn btn-primary">--}}
                        -->
                    </td>
			<button type="submit" class="btn btn-primary">Update</button>
		</div>
	 </div>
	 <!--Display-->

</form>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bench/bootstrap-datepicker.js"></script>
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
<script>
$(document).ready(function()
                  {

					var value = $("#display").val();
					//alert(value);
                 		if(value == "Y")
					{
						document.getElementById("display").readOnly = false;
					}

					});

</script>

</section>
@endsection
