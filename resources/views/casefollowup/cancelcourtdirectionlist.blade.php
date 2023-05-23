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

 <section class="content" >

  <div class="panel  panel-primary">
    <form role="form" id="ordergenerationform" name="ordergenerationform" method="GET" action="cancelcourdirectionrollback" data-parsley-validate>
      <div class="text-center">
        <button class="tablink" name="FinalizeCourtDirection" type="submit" class="btn btn-primary" id="FinalizeCourtDirection"  value="FinalizeCourtDirection"  Style="width: 200px;" >RollBack Court Direction</button>
        <a  href="cancelcourtdirection">
            <i class="fa fa-arrow-circle-o-left"></i>
            <span>Back</span>
        </a>
      </div>
    <table class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
        <thead>
          <tr>
          <td  class="bg-primary text-center" colspan="10"> <h3>  <?php echo $establishmentname; ?></h3> </td>
          </tr>

            <tr>
          <td  class="bg-primary text-center" colspan="10">   <h4> Courtdirection for application id
            <?php echo $applicationid; ?>   </h4> </td>


          </tr>


<tr>
<td><input type="checkbox" id="chkCheckAll" /></td>
<td>Hearing Date</td>
<td>Courthallno </td>
<td>ApplicationID</td>
<td>Publish</td>
<td>Court Direction</td>






</tr>

    </thead>

    <tbody>





              @foreach ($result as $result1)
           <tr>
            <?php
            $hearingdate=$result1->hearingdate;
            $courthallno=$result1->courthallno;
            $applicationid=$result1->applicationid;
            $publish=$result1->publish;
            $courtdirection=$result1->courtdirection;



             ?>

             <td><input type="checkbox" name="ids[]" id="ids" class="checkBoxClass" value="<?php echo $result1->hearingcode; ?>"/>  </td>
             <td><?php echo date('d/m/Y', strtotime($hearingdate)); ?></td>
             <td><?php echo $courthallno; ?></td>
             <td><?php echo $applicationid; ?></td>
             <td><?php echo $publish; ?></td>
             <td><?php echo $courtdirection; ?></td>





               @endforeach

            </tr>




    </tbody>
    </table>
  </form>
</div>
</section>

</div>

<script src="js/jquery-3.4.1.js"></script>
<script>
    $(function(e) {
        $("#chkCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'))
        });
      });
</script>

<script>
$('.tablink').click(function(e) {
    console.log('i m here');
//    $("#applicationId").val(data[0].applicationid);
//var ids = $("input:checked");

var ids = new Array();
$.each($("input[name='ids[]']:checked"), function() {
ids.push($(this).val());
  // or you can do something to the actual checked checkboxes by working directly with  'this'
  // something like $(this).hide() (only something useful, probably) :P
});

//var ids =$('input[name="ids"]:checked');
console.log(ids);

e.preventDefault();
 $.ajax({
        type     : "GET",
        cache    : false,
        url      : "cancelcourdirectionrollback",
        data: {"_token": $('#token').val(),ids : ids },
        success  : function(data) {
          console.log(data);
          if(data.status=="sucess")
          {
            //  gethearing();
              location.reload();
              swal({
              title: data.message,
              icon: "success"
            })
            ;

          }
          if(data.status=="error")

          {
            swal({
              title: data.message,
              icon: "error"
            })
            ;
          }
          if(data.status=="update")
          {
        //  $("#remarksQuestionaries").val('');
        //  $("#ids").val('');
            swal({
              title: data.message,
              icon: "success"
            })
            ;
          }
        }
   });

});



</script>

<script>

</script>


  @endsection
