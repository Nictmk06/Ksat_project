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
<div class="content-wrapper">
<script type="text/javascript" src="js/jquery.min.js"></script>
   <div align="center">
     <section class="content" style="width: 100%">
       <div class="panel  panel-primary">
         <form name="form1" id="form1" action="approvallist" method="post">
           <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
           {{ csrf_field() }}
           <div class="col-md-10">
             <table class="table no-margin table-bordered">
               <tr>
                  <td  class="bg-primary text-center" colspan="4"> <h4>Approval of Advocate Changes </h4> </td>
                </tr>
                <tr>
                  <td> <span class="mandatory">*</span>  <label>Status of Advocate Change:</label></td>
                  <td>
                    <div class="form-group">
                        <select class="form-control" name="statusofadvocatechange" id="statusofadvocatechange"  data-parsley-required data-parsley-required-message="Select Status of Advocate" >
                          <option value="">Select </option>
                          <option value="1">Pending - New Advocate</option>
                          <option value="2">Pending - Change in mobile/address</option>
                          <option value="3">Approved</option>
                        </select>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="4">
                      <div class="text-center">
                        <input type="button" accesskey="s" class="btn btn-primary" value="Approve" id="update" width="100px" >
                        <a class="btn btn-warning" href=""> Cancel </a>
                      </div>
                    </td>
                  </tr>
                </form>

<!-- advocateapproval -->
<form role="form" id="ordergenerationform" name="ordergenerationform" method="GET" action="{{ route('approvallist',[],false) }}" data-parsley-validate>
<div class="panel  panel-primary">
  <table class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
    <thead>
        <tr>
          <td  class="bg-primary text-center" colspan="15"><h4> Details Of Advocates</h4> </td>
        </tr>
        <tr>

            <td style="background-color: #ec971f;" class="checkboxhiding"><input type="checkbox" id="chkCheckAll" /></td>
            <td style="background-color: #ec971f;" class="hiding1">ID</td>
            <td style="background-color: #ec971f;">Name</td>
            <td style="background-color: #ec971f;">Mobile</td>
            <td style="background-color: #ec971f;">Advocateregno</td>
            <td style="background-color: #ec971f;" class="nonhiding">Name</td>
            <td style="background-color: #ec971f;" class="nonhiding">Address</td>
            <td style="background-color: #ec971f;" class="nonhiding">District </td>
            <td style="background-color: #ec971f;" class="nonhiding">Taluk</td>
            <td style="background-color: #ec971f;" class="nonhiding">Pincode</td>
            <td class="hiding" style="background-color: #ec971f;">Changed Name</td>
            <td class="hiding" style="background-color: #ec971f;"> Changed Address</td>
            <td class="hiding" style="background-color: #ec971f;"> Changed District </td>
            <td class="hiding" style="background-color: #ec971f;"> Changed Taluk</td>
            <td class="hiding" style="background-color: #ec971f;"> Changed Pincode</td>
            <td class="hiding" style="background-color: #ec971f;"> Advocate Name</td>
            <td class="hiding" style="background-color: #ec971f;"> Advocate Address</td>
            <td class="hiding" style="background-color: #ec971f;"> District Name</td>
            <td class="hiding" style="background-color: #ec971f;"> Taluk Name</td>
            <td class="hiding" style="background-color: #ec971f;"> Pincode</td>



        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
</div>
</form>
</div>

</div>

 </div>

</div>
</section>
</div>

<script type="text/javascript">
// $("#statusofadvocatechange").click(function(){
// ��$("#hold").show();
// });

$(document).ready(function() {
  $('.hiding').hide();
  //$('.hiding1').hide();
  //$('.nonhiding').hide();




  $("#statusofadvocatechange").change(function(){
      var statusofadvocatechange =  $("#statusofadvocatechange").val();
      if(statusofadvocatechange == "") {
          $("#myTable tbody").empty();
      } else {


      if(statusofadvocatechange == 2 ) {
          $('.hiding').show();
          $('.nonhiding').hide();
      } else {
          $('.hiding').hide();
          $('.nonhiding').show();
      }


      if(statusofadvocatechange == 3) {
          $('.checkboxhiding').hide();

      } else {
          $('.checkboxhiding').show();

      }


      $.ajax({
        type: "POST",
        url: "approvallist",
        data: {
          "_token": $('#token').val(),
          statusofadvocatechange: statusofadvocatechange
          },
       cache: false,
      success: function(data) {
        console.log(data); // I get error and success function does not execute
        $("#myTable tbody").empty();
        $("#myTable tbody").append(data);
      }
});
}
});

});
</script>
<script>
    $(function(e) {
        $("#chkCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'))
        });
      });

      $('#update').click(function(e) {
          var ids = new Array();
          var statusofadvocatechange =  $("#statusofadvocatechange").val();
          $.each($("input[name='ids[]']:checked"), function() {
            ids.push($(this).val());
          });

      // alert(ids);
      e.preventDefault();
       $.ajax({
              type     : "POST",
              cache    : false,
              url      : "updateprofile",
              data: {"_token": $('#token').val(),ids : ids,statusofadvocatechange:statusofadvocatechange },
              success  : function(data) {
                console.log(data);
                if(data.status=="sucess") {
                    location.reload();
                    swal({
                    title: data.message,
                    icon: "success"
                  });
                }

                if(data.status=="error"){
                  swal({
                    title: data.message,
                    icon: "error"
                  });
                }
                if(data.status=="update"){
                  swal({
                    title: data.message,
                    icon: "success"
                  });
                }
              }
         });

      });
</script>

  @endsection
