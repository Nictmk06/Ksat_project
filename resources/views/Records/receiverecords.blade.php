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
  <style>
  .text{
  white-space: pre-wrap;
  }
  </style>
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')
	<section class="content">
		
	
    <form role="form" id="receiverecords" method="POST" action="updateCCAStatus" data-parsley-validate>
			  @csrf
			<div class="panel  panel-primary">
			  <div class="panel panel-heading">
				<h4  class="text-center">Receiving Records Entry</h4>
			  </div>
			  <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
			<div class="panel panel-body divstyle" >
			 
            <div class="row">
              <table id="myTable1" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                <thead >
                  <tr style="background-color: #3c8dbc;color:#fff" >
                    <td class="col-md-1">Sr.No</td>
                    <td class="col-md-2">Application No</td>
					<td class="col-md-2">Sent to Section</td>
                    <td class="col-md-2">Sent on</td>
				    <td class="col-md-2">Received on</td>
					<td class="col-md-2">Remarks</td>
					<td class="col-md-2"></td>
                  </tr>
                </thead>
               <tbody>
               <tr>
             <?php $i = 1; ?>
                  	@foreach($records as $records)

                  <tr  id="{{$records->requestid}}">
					<td>{{ $i++ }}
                    <td> {{$records->applicationid}}      </td>

				    <td>{{$records->usersecname}}</td>
					<input type="hidden" name="recordsentdate" id="recordsentdate{{$records->requestid}}" value="{{ date('d-m-Y', strtotime($records->recordsentdate)) }}"></td>
                    <td>{{ date('d-m-Y', strtotime($records->recordsentdate)) }}</td>

                    <td><input type="text" name="recordreceiveddate" class="form-control pull-right datepicker" id="recordreceiveddate{{$records->requestid}}" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required-message="Enter Received On"  data-parsley-required data-parsley-errors-container="#error5"></td>
					<td><textarea class="form-control" name="" id="remarks{{$records->requestid}}" data-parsley-pattern="/^[a-zA-Z0-9.;:`,-/ ()&\n\r]+$/" data-parsley-pattern-message="Remarks Accepts Only Characters" data-parsley-required data-parsley-required-message="Enter Remarks"></textarea>  </td>
					<td><a data-value='{{$records->requestid}}' class="btn btn-md btn-primary extraClick " id="Save"  >Save</a></td>                
				</tr>
                 @endforeach

                </tbody>
              </table>
            </div>



          </div>
        </div>
		</div>
	</form>	
	</section>	
	 <script src="js/jquery.min.js"></script>
	 <script src="js/records/receiverecords.js"></script>
	
@endsection