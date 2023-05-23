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

    <form role="form" id="previouscauselist"  method="POST" action="printpreviouscauselist" target="_blank" >


    <div class="panel panel-primary">

      <div class="panel panel-heading origndiv">
        <h7 >Previous Causelist</h7>
      </div>
      <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>

      <div class="panel panel-body divstyle" >

        <input name='causelistcode' id='causelistcode' value='' type='hidden'>
       <input name='choice' id='choice' value='' type='hidden'>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Hearing Date<span class="mandatory">*</span></label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="hearingDate" class="form-control pull-right datepicker" id="hearingDate"  data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="Registered On Allows only digits"  data-parsley-required-message="Enter Hearing Date"  data-parsley-required data-parsley-errors-container="#error5">
              </div>
              <span id="error5"></span>
            </div>
          </div>

          <div class="col-md-8">
            <div class="form-group">
              <label>Prepared Causelist<span class="mandatory">*</span></label>
              <select name="causelist" id="causelist" class="form-control" data-parsley-required data-parsley-required-message="Select Causelist"><option value="" >Select Causelist</option>

            </select>
          </div>
        </div>

      </div>


<div class="row divstyle"  style="float: middle;" id="add_apl_div">
<div class="col-sm-12 text-center">

<!--  <input type="button" id="view" class="btn btn-primary btnSearch btn-md center-block" Style="width: 150px;" value="View CauseList">
-->

  <button type="submit" class="btn btn-primary" Style="width: 150px;" >View Causelist </button>

<input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
</div>
</div>

</div>
</div>

 <div class="panel  panel-primary">

      <div class="panel panel-heading origndiv">
        <h7 >List of Previous Causelist</h7>
      </div>

  <div class="panel panel-body divstyle">
<div class="row">
<table id="myTable1" class="table table-bordered table-striped  table order-list table-responsive">
  <thead >
    <tr style="background-color: #3c8dbc;color:#fff;" >

    <td class="col-md-6">Causelist</td >
   <td class="col-md-2">Finalized (Y/N) </td>


  </tr>
</thead>
<tbody id="results3" >

</tbody>
</table>
</div>
</div>


</div>

</form>
<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>


<script>
$(document).ready(function()
{
  $("#hearingDate").datepicker({dateFormat:"dd/mm/yy"})
      .datepicker("setDate",new Date()).datepicker('setEndDate', new Date());

     getcauselistfrompreviouscl();





  $("#hearingDate").change(function(){
    getcauselistfrompreviouscl();

      });
 //----------------------------------------------





$("#view1").click(function(e){

    var causelistcode = $("#causelist").val();


     // $("#"+form).parsley().validate();
      //  if ($("#"+form).parsley().isValid())
      //    {
            console.log(causelistcode);
            var form = $(this).closest("form").attr('id');
            var formaction = $(this).closest("form").attr('action');
                 $.ajax({
                    type: 'post',
                    url: formaction,
                    data: $('#'+form).serialize(),
                    success: function (data) {
                          console.log(data);

//                   swal({
//                  title: data.message,
//                  icon: "success"
//                  });

                   }

                   })

    //  }
   })


   })

    function getcauselistfrompreviouscl(){
     var hearingdate = $("#hearingDate").val();
   // alert(hearingdate);
    $.ajax({
        type: 'post',
        url: "getcauselistfrompreviouscl",
        dataType:"JSON",
        data: {"_token": $('#token').val(),hearingdate:hearingdate},
        success: function (json) {

            $('#myTable1').css('display','block');
            $('#myTable1').find('tbody tr').remove();
            $('#causelist').find('option:not(:first)').remove();
           for(var i=0;i<json.length;i++){
             var option = '<option value="'+json[i].causelistcode+'">'+json[i].causelistdesc+'</option>';
                $('#causelist').append(option);
                   // var count = 1;
                   // for(var i=0;i<json.length;i++){
                  var row = $('<tr>');
                    // console.log(obj.causelistsrno);
                    row.append('<td class="col-md-8">' + json[i].causelistdesc + '</td>');
                    row.append('<td class="col-md-2">' +json[i].finalize + '</td> </tr>' );
                    row.appendTo('#myTable1');

                    //count++;
                   // }

}
}

    });
  }
  
  $('.btnClear').click(function(){
	$("#previouscauselist").trigger('reset');
	$('#causelist').find('option:not(:first)').remove();
	$('#myTable1').find('tbody tr').remove();
})


</script>

</section>
@endsection
</div>
