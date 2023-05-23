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

    <form role="form" id="causelistfinalize"  method="POST" action="" >
     <div class="panel  panel-primary">
      <div class="panel panel-heading origndiv">
        <h7 >Initialize Causelist</h7>
      </div>
      <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
      <div class="panel panel-body divstyle" >
        <?php
         $date_sys = date("d-m-Y");
        ?>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="hearingDate" class="form-control pull-right datepicker" id="hearingDate"   data-parsley-pattern="/^[0-9_-]+$/"   value="{{ date('d-m-Y', strtotime($causelistdate)) }}" data-parsley-required-message="Enter Hearing Date"  data-parsley-required data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
          </div>
      </div>
     </div>
    </div>

	<div class="panel  panel-primary">
       <div class="panel panel-heading origndiv">
			<h7 >List of prepared Causelist</h7>
		</div>
 	  <div class="panel panel-body divstyle">
		<div class="row">
	<!-- 	<table id="myTable1" class="table table-bordered table-striped  table order-list table-responsive application-list" >
	    <tbody id="results3" >
	    </tbody>
	   </table> -->

     <table id="myTable1" class="table table-bordered table-striped  table order-list table-responsive application-list">
  <thead >
    <tr style="background-color: #3c8dbc;color:#fff;" >

    <td class="col-md-6">Causelist</td >
   <td class="col-md-2">Finalized (Y/N) </td>
  <td class="col-md-2">Action</td>

  </tr>
</thead>
<tbody id="results3" >

</tbody>
</table>
	   </div>
	</div>
	</div>




	 <!--  <div class="row divstyle"  style="float: middle;" id="add_apl_div">
		<div class="col-sm-12 text-center">

    @if($initialize == 'Y')
    <input type="button" id="save" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px; " value="Initialize">
   @else
   <input type="button" id="save" disabled class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px; " value="Initialize">
    @endif

		</div>
	  </div> -->


	</form>
<!-- /.tab-pane -->

<script src="js/jquery.min.js"></script>
<script>
$(document).ready(function()
{
	  $("#hearingDate").css('pointer-events', 'none');

    $('#hearingDate').datepicker('setEndDate',  new Date());



$("#hearingDate").change(function(){
   getCauselistdata();
});


 getCauselistdata();

	$("table.application-list").on("click", ".extraClick", function(e) {
	  var dataValue   = $(this).attr('data-value');
	  var arr = dataValue.split("/");
    if(arr[1]=='N'){
      alert('Please Finalized Causelist before posting to court');
      return;
    }
	  e.preventDefault();
		swal({
			title: "Are you sure to Initialize the causelist?",
			icon: "warning",
			showCancelButton: true,
			buttons: true,
			dangerMode: true,
		  })
		  .then((willDelete) => {
		   if (willDelete) {
		   var causelistcode = arr[0];
		   var causelistdate = arr[2];
		   $.ajax({
			type: 'post',
			url: "saveInitializeCauselist",
			dataType:"JSON",
			data: {"_token": $('#token').val(),causelistcode:causelistcode,causelistdate:causelistdate},
			success: function (data) {
			  if(data.status=='success')
				  {
				   getCauselistdata();
                                   swal({
                                          title: data.message,
                                          icon: "success"
                                          })
                                  }
				 else{
                                     swal({
                                     title: data.message,
                                      icon: "error"
                                      })
                                   }
		}});
	}})
		})
	 //----------------------------------------------
/*
$("#save").click(function(e){
   var hearingdate = $("#hearingDate").val();

      e.preventDefault();
    swal({
        title: "Are you sure to Initialize the causelist?",
        icon: "warning",
        showCancelButton: true,
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {

       var causelistcode = $("#causelist").val();
       $.ajax({
        type: 'post',
        url: "saveInitializeCauselist",
        dataType:"JSON",
        data: {"_token": $('#token').val(),hearingdate:hearingdate},
        success: function (data) {
          if(data.status=='success')
              {
                $("#save").attr('disabled',true);

              }


              swal({
                  title: data.message,
                  icon: "success"
                  })

    }
  });

}

})
    })*/





   });

 function getCauselistdata()
  {
   var hearingdate = $("#hearingDate").val();
     $.ajax({
        type: 'post',
        url: "getcauselist",
        dataType:"JSON",
        data: {"_token": $('#token').val(),hearingdate:hearingdate},
        success: function (json) {
            $('#myTable1').css('display','block');
            $('#myTable1').find('tbody tr').remove();

           for(var i=0;i<json.length;i++){

                  var row = $('<tr>');
                     row.append('<input type="hidden" id="finalize" value='+json[i].finalize+'>');
                    row.append('<td><input type="hidden" id="causelistcode" value='+json[i].causelistcode+'>'+ json[i].causelistdesc +'</td>');
                   // row.append('<td>' + json[i].causelistdesc + '</td>');
                     row.append('<td class="col-md-2">' +json[i].finalize + '</td> </tr>' );
                    if(json[i].postedtocourt =='Y'){
                        row.append('<td><a  class="btn btn-md btn-primary extraClick " id="PushData" disabled >Push Data to CourtHall</a></td>;');
                    }
                      else{
                    row.append('<td><a data-value='+json[i].causelistcode+'/'+json[i].finalize+'/'+json[i].causelistdate+' class="btn btn-md btn-primary extraClick " id="PushData"  >Push Data to CourtHall</a></td>;');
                      }

                    row.appendTo('#myTable1');

                    //count++;
                   // }

}
}

    });
 }


</script>

</section>
@endsection
</div>
