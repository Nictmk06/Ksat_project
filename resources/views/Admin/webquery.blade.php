@extends('layout.mainlayout')
@section('content')

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
<script type="text/javascript" src="js/jquery-3.4.1.slim.min.js"></script>
<script type="text/javascript" src="js/popper.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="assets/js/search_filter/datatables.min.js"></script>
<script src="assets/js/search_filter/jquery-1.11.1.min.js"></script> <!-- JQuery Reference -->
<script src="assets/js/search_filter/jquery.dataTables.min.js"></script>
<script src="assets/js/search_filter/dataTables.buttons.min.js"></script>
<script src="assets/js/search_filter/buttons.colVis.min.js"></script>
<script src="assets/js/search_filter/buttons.flash.min.js"></script>
<script src="assets/js/search_filter/buttons.html5.min.js"></script>
<script src="assets/js/search_filter/buttons.print.min.js"></script>
<script src="assets/js/search_filter/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="assets/js/search_filter/datatables.min.js"></script>

    <script src="assets/js/search_filter/jquery-1.11.1.min.js"></script>
    <script src="assets/js/search_filter/jquery.dataTables.min.js"></script>
    <script src="assets/js/search_filter/dataTables.buttons.min.js"></script>
    <script src="assets/js/search_filter/buttons.colVis.min.js"></script>
    <script src="assets/js/search_filter/buttons.flash.min.js"></script>
    <script src="assets/js/search_filter/buttons.html5.min.js"></script>
    <script src="assets/js/search_filter/buttons.print.min.js"></script>
    <script src="assets/js/search_filter/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="assets/css/search_filter/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/search_filter/dataTables.jqueryui.css">
    <link rel="stylesheet" href="assets/css/search_filter/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/search_filter/buttons.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/search_filter/responsive.dataTables.min.css">

    <script type="text/javascript">
       var tb = "";
        var jq = $.noConflict();

        var subtitle = "<?php echo  $subtitle;  ?>"
        var establishfullname="<?php echo $establishmentname; ?> "
             jq(document).ready(function () {


            tb = jq('#myTable').dataTable({
                  "pagingType": "full_numbers",
                  "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                  "searching": true,
                  "ordering": true,
                  "info": true,
                  "paging": true,
                  "responsive": true,
                  "rowReorder": true,

                  "lengthChange": true,
                    dom: 'Bfrtip',
                    buttons: [
                      { extend: 'copy',messageTop: subtitle,title: establishfullname,footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                      { extend: 'excel',messageTop: subtitle,title:establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape' },
                      { extend: 'csv',messageTop: subtitle,title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                      { extend: 'pdf', messageTop: subtitle,title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                      { extend: 'print', messageTop: subtitle,title: establishfullname, footer: true,header:true, pageSize : 'LEGAL',orientation : 'landscape'}

          ],



                });
            });

        </script>
<div class="content-wrapper">
<script type="text/javascript" src="js/jquery.min.js"></script>
   <div align="center">
     <section class="content" style="width: 100%">
       <div class="panel  panel-primary">
         <form name="webquerydata" id="webquerydata" action="webquerydata" method="post">
           <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
           {{ csrf_field() }}
             <div class="col-md-10" style="width:70%;margin-left:-13px;">
             <table class="table no-margin table-bordered" >
               <tr>
                  <td  class="bg-primary text-center" colspan="4"> <h4>Web Query </h4> </td>
                </tr>
                <tr>
                    <td width="25%">  <label for=""> From Date<span class="mandatory">*</span></label> </td>
                        <td>
                          <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" name="fromdate" class="form-control pull-right datepicker" id="fromdate" value="" data-parsley-errors-container="#error6" autocomplete="off">
                            </div>
                            <span id="error6"></span>

                          </td>
                    </tr>

                    <tr>
                    <td>  <label for="">To Date <span class="mandatory">*</span></label> </td>
                    <td>
                      <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" name="todate" class="form-control pull-right datepicker "   id="todate" value=""   data-parsley-errors-container="#error6" autocomplete="off">
                        </div>
                        <span id="error6"></span>
                      </td>
                    </tr>
                    <tr>
                  <td> <label>Forwarded To[Section] <span class="mandatory">*</span> </label></td>
                  <td>
                    <div class="form-group">
                        <select class="form-control" name="forwardedto" id="forwardedto">
                          <option value="All">All</option>
                          @foreach($forwardedto as $forward)
                          <option value="{{$forward->userseccode}}">{{$forward->usersecname}}</option>
                          @endforeach
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td> <label>Forwarded To[UserName]: <span class="mandatory">*</span> </label></td>
                    <td>
                      <div class="form-group">
                          <select class="form-control" name="userid" id="userid"  data-parsley-required data-parsley-required-message="Select Sectioncode" >
                            <!-- <option value="">Select User </option> -->
                        <option value="All">All</option>

                          </select>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td> <label>Status:</label>
                       </td>
                      <td>
                        <div class="form-group">
                          <select class="form-control" name="statuscode"  id="statuscode">
                            <option value="">select</option>
                            <option value="1">Pending</option>
                            <option value="2">Closed</option>

                          </select>
                        </div>
                      </td>
                    </tr>

                  <tr>
                    <td colspan="4">
                      <div class="text-center">
                        <input type="button" accesskey="s" class="btn btn-warning" value="Submit" id="submit">
                        <a class="btn btn-warning" href=""> Cancel </a>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>

  <table class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
    <thead>
        <tr>
          <td id="webqueryreport"  class="bg-primary text-center" colspan="15"><h4> WebQuery Report
          </h4></td>
        </tr>
        <tr>
            <td style="background-color: #ec971f;">ID</td>
            <td style="background-color: #ec971f;">Subject</td>
            <td style="background-color: #ec971f;">Description</td>
            <td style="background-color: #ec971f;">Status</td>
            <td style="background-color: #ec971f;">Recived From</td>
            <td style="background-color: #ec971f;">Sent On</td>
            <td style="background-color: #ec971f;">Replied ON</td>
            <td style="background-color: #ec971f;">Reply Content</td>
            <td style="background-color: #ec971f;">Section</td>
            <td style="background-color: #ec971f;">Forwarded On</td>
            <td style="background-color: #ec971f;">Forwarded To</td>
            <td style="background-color: #ec971f;">Acknowledgeon</td>
            <td style="background-color: #ec971f;">UserName</td>

            </tr>
        </thead>
        <tbody>
        <?php $i=1; ?>
          @foreach($result as $res)
          <tr>
            <td>{{$i++}}</td>
            <td>{{$res->querytypedescription}}</td>
            <td>{{$res->querycontent}}</td>
            <td> @if($res->statuscode == 1)
                   Pending
              @else
                  Closed
              @endif
            </td>
            <td>{{$res->mobileno}}</td>
            <td>{{date("d-m-Y", strtotime($res->enteron))}}</td>
            <td>@if($res->repliedon)
                {{date("d-m-Y", strtotime($res->repliedon))}}
             @endif</td>
            <td>{{$res->replycontent}}</td>
            <td>{{$res->usersecname}}
               </td>
            <td>@if($res->forwardedon)
                {{date("d-m-Y", strtotime($res->forwardedon))}}
            @endif</td>
            <td>{{$res->forwardedto}}</td>
            <td>@if($res->acknowledgeon)
                      {{date("d-m-Y", strtotime($res->acknowledgeon))}}
                  @endif</td>
            <td>{{$res->userid}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

</form>
 </div>
</section>
</div>
</div>

<script>
$(document).ready(function(){


  $("#fromdate").datepicker({
    fromdate:"dd-mm-yyyy",
    autoclose:true,
  }).on('changedate',function(selected){
  var enddate=new Date(selected.date.valueof());
  $('#todate').datepicker('setStartDate',endDate);
}).on('clearDate',function(selected){
  $('#todate').datepicker('setEndDate',null);
});


$("#submit").on('click',function(){
  var _token=$('input[name="_token"]').val();
  var fromdate=$("#fromdate").val();
  var todate=$("#todate").val();
  var forwardedto=$("#forwardedto").val();
  var userid=$("#userid").val();
  var statuscode=$("#statuscode").val()

  var forwardedtotext = $("#forwardedto option:selected").text();
  var useridtext = $("#userid option:selected").text();


  $.ajax({
    type:"POST",
    url:"webquerydata",
    data:{
      '_token':_token,fromdate:fromdate,todate:todate,forwardedto:forwardedto,userid:userid,statuscode:statuscode
    },
    cache:false,
    success:function(result){

    $("#myTable tbody").empty();
    $("#myTable tbody").append(result);
    // alert('vasanta');
    $('#webqueryreport').html('<h4>Query Recieved Between '+fromdate+' & '+todate+' Section: '+forwardedtotext+' User : '+useridtext+'</h4>');


  }
});

});

$("#forwardedto").on('change',function(){

var forwardedto=$("#forwardedto").val();
var _token=$('input[name="_token"]').val();

$.ajax({
  type:"POST",
  url:"sectiondata",
  data:{
    _token:_token,forwardedto:forwardedto
  },
  cache:false,
  success:function(result){
    // console.log(result);
    $("#userid").empty();
    // $("#userid").append('<option value="">Select User</option>');
    $("#userid").append('<option value="All">All</option>');
     $.each(result,function(key,value){
       $("#userid").append('<option value="'+value.userid+'">'+value.username+'-'+value.userid+'</option>');
     });
  }
})

});


});

//validateApplication
$(document).ready(function(){
    $("#submit").click(function() {

                   if($("#fromdate").val()==""){
                     alert("Please Select fromdate ");
                     return false;
                   }

                   if($("#todate").val()==""){
                     alert(" Please Select todate");
                     return false;
                   }

                   // if($("#forwardedto").val()==""){
                   //   alert(" Please Select forwardedto");
                   //   return false;
                   // }



});
});
</script>

  @endsection
