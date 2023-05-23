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
 <!-- modal to add designation to master table -->
    <div class="modal fade" id="modal-add-designation">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id='appl-title'>Add Designation</h4>
          </div>
          <div class="modal-body">
            <div class='row'>
              <input type='hidden' name='typeOfappl' id='typeOfappl'>
              <div class='col-md-12'>
                <label>Designation name<span class="mandatory">*</span></label>
                <div class='form-group'>
                  <input type="text" name="designame" class="form-control number zero"
				  id="designame" value=""  data-parsley-required-message="Enter Designation Name."
				  data-parsley-pattern="/^[a-zA-Z.-/ ]*$/" maxlength='100'>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-md btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-md btn-primary" id='saveDesignation' >SAVE</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

<form  id ="applDeptDesigForm"  action="savedeptdesigmapping" method="POST" data-parsley-validate>
@csrf
  <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Add Department - Designation Mapping</h7>
        </div>
		 <div class="panel panel-body">
          <div class="row">
		   <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
		   <div class="col-md-3">
               <div class="form-group">
                <label>Department Type <span class="mandatory">*</span></label>
                <div class="input-group date">
                 <select class="form-control" name="applDeptType" id="applDeptType" data-parsley-required  data-parsley-required-message="Select Department " >
			   <option value="">Select Department Type </option>
			   @foreach($departmenttype as $departmenttype)
				<option value="{{$departmenttype->depttypecode}}">{{$departmenttype->depttype}}</option>
				@endforeach
			  </select>
             </div>
             </div>
            </div>

		    <div class="col-md-4">
               <div class="form-group">
                <label>Department<span class="mandatory">*</span></label>
                <div class="input-group date">
                 <select class="form-control" name="department" id="department" data-parsley-required  data-parsley-required-message="Select Department " >
			   <option value="">Select Department </option>
			   @foreach($department as $department)
			   <option value="{{$department->departmentcode}}">{{$department->departmentname}}</option>
			   @endforeach
			  </select>
             </div>
             </div>
            </div>


          <div class="col-md-4">
              <div class="form-group">
                <label>Designation Of Applicant<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <select class="form-control" name="desigAppl"  id="desigAppl" data-parsley-required data-parsley-required-message="Select Designation" >
                    <option value="">Select Designation</option>
                    @foreach($designation as $designation)
                     <option value="{{$designation->desigcode}}">{{$designation->designame}}</option>
                     @endforeach
                  </select>
                 <div class="input-group-addon" class="form-control"style="color:#fff;background-color: #337ab7" id="desigadd">
                    <i class="fa fa-plus"></i>
                  </div>
                </div>
              </div>
            </div>
			</div>


              <br>

              <br>
          <div class="row"  style="float: center;" id="add_apl_div">
                <div class="col-sm-12 text-center">
                  <input type="submit" id="save" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div>

          </div>
        </div>
      </div>
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

 <!--<div class="row">
                <table id="myTable4" class="table table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Department Code</td>
                      <td>Department Name</td>
                      <td>Department Address</td>
                      <td>Phone number</td>
                      <td>Mobile Number</td>

                    </tr>
                </thead>

                <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                  	@foreach($department as $department)

                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$department->departmentcode}}" class="extraClick"> {{$department->departmentcode}}   </a></td>


                  <td>{{$department->departmentname}}</td>
                  <td>{{$department->deptaddress}}</td>

                   <td>{{$department->deptphoneno}}</td>
                  <td>{{$department->deptmobile}}</td>


                  </tr>
                 @endforeach

                </tbody>
              </table>
            </div>-->
            <script src="js/jquery-3.4.1.js"></script>

<script>
$(document).ready(function() {

	$("#applDeptType").change(function(){
		var typeval = $(this).val();
				$.ajax({
        type: 'post',
        url: 'getDepatmentName',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:typeval},
        success: function (data) {
        $('#department').find('option:not(:first)').remove();
		 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].departmentcode+'">'+data[i].departmentname+'</option>';
  						$('#department').append(option);
				 }
        	}
        });
	})


	$("#desigadd").click(function(){
		$('#modal-add-designation').modal('show');
		$("#typeOfappl").val('A');
	})


	$("#saveDesignation").click(function(){

		if($("#designame").val()=='')
			{
				$('#designame').parsley().removeError('designame');

								$('#designame').parsley().addError('designame', {
								message: "Enter Designation Name"
								});
								return false;
			}
			else
			{
				$('#designame').parsley().removeError('designame');
			}

			if($("#designame").val()!='')
			{
					var textfieldmask = /^[a-zA-Z.-/ ]*$/;
				  var testname = textfieldmask.test($("#designame").val());
				  if (testname != true) {
            					 $('#designame').parsley().removeError('designame');
								 $('#designame').parsley().addError('designame', {
								message: "Invalid Designation"
								});
								return false;
       		 		 }
       		 		 else
       		 		 {
       		 		 	 $('#designame').parsley().removeError('designame');
       		 		 }
			}
		var designame = $("#designame").val();

		$.ajax({
        type: 'post',
        url: 'storeDesignation',
       // dataType:'JSON',
        data:  { "_token": $('#token').val(),designame:designame},
        success: function (data) {

        		$("#modal-add-designation").modal('hide');
        		$("#designame").val('');
        		getDesignations();
        		alert("Designation added successfully");

        	}
        });
	})

	function getDesignations()
	{
		$.ajax({
        type: 'post',
        url: 'getDesignation',
        dataType:'JSON',
        data:  { "_token": $('#token').val()},
        success: function (data) {

        		$('#desigAppl').empty();
					for(var i=0;i<data.length;i++){
						 var option = '<option value="'+data[i].desigcode+'">'+data[i].designame+'</option>';
					     $('#desigAppl').append(option);
					}

        	   }


        });
	}

	$('.btnClear').click(function(){
	$("#applDeptDesigForm").trigger('reset');
})
});
</script>

@endsection
