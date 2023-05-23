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
         <form  name="form1" id="form1" action="ackdataupdate" method="post">
           <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
           {{ csrf_field() }}
           <div class="col-md-10">
             <table class="table no-margin table-bordered">
               <tr>
                  <td  class="bg-primary text-center" colspan="4"> <h4>Acknowledge Query Changes </h4> </td>
                </tr>

                <tr>
                  <td width="20%"> <label>Forwarded To[Section] <span class="mandatory">*</span> </label></td>
                  <td width="35%">
                        <select class="form-control" name="forwardedto" id="forwardedto">
                          <option value="">Select </option>
                          @foreach($forwardedto as $forward)
                          <option value="{{$forward->userseccode}}">{{$forward->usersecname}}</option>
                          @endforeach
                        </select>

                    </td>
                  </tr>
                  <tr>
                    <td width="20%"> <label>Forwarded To[UserName]: <span class="mandatory">*</span> </label></td>
                    <td width="35%">
                      <div class="form-group">
                          <select class="form-control" name="userid" id="userid"  data-parsley-required data-parsley-required-message="Select Sectioncode" >
                            <option value="">Select User </option>

                          </select>
                        </div>
                      </td>
                    </tr>
                 

                  <tr>
                    <td colspan="4">
                      <div class="text-center">
                        <input type="submit" accesskey="s" class="btn btn-primary" value="Submit" id="submit" width="100px" >
                        <a class="btn btn-warning" href=""> Cancel </a>
                      </div>
                    </td>
                  </tr>
                  </table>
                </div>



<div class="panel  panel-primary">
  <table  style="margin-right:-28px; border 1px solid black;" class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
    <thead>
        <tr>
          <td  class="bg-primary text-center" colspan="15"><h4>  Acknowledge Query</h4> </td>
        </tr>
        <tr>
            <td style="background-color: #ec971f;" ><input type="checkbox"  id="chkCheckAll" /></td>
            <td style="background-color: #ec971f;">ID</td>
            <td style="background-color: #ec971f;">Subject</td>
            <td style="background-color: #ec971f;">Description</td>
            <!-- <td style="background-color: #ec971f;">Status</td> -->
            <td style="background-color: #ec971f;">Recived From</td>
            <td style="background-color: #ec971f;">Sent On</td>
            <td style="background-color: #ec971f;">Forwarded On</td>
            <td style="background-color: #ec971f;">Forwarded To</td>
            <td style="background-color: #ec971f;">Acknowledgeon</td>


        </tr>
        </thead>
        <tbody>
          <?php $i=1; ?>
          @foreach($result as $res)
          <tr>

            <td><input type = "checkbox"  class="checkBoxClass" name = "queryno[]" value = "{{$res->queryno}}"></td>
            <td>{{$i++}}</td>
            <td>{{$res->querytypedescription}}</td>
            <td>{{$res->querycontent}}</td>
            <!-- <td>
              @if($res->statuscode == 1)
                   Pending
              @else
                  Closed
              @endif

            </td> -->
            <td>{{$res->mobileno}}</td>
            <td width="8%">{{date("d-m-Y", strtotime($res->enteron))}}</td>
            <td>@if($res->forwardedon)
                      {{date("d-m-Y", strtotime($res->forwardedon))}}
                  @endif

            </td>
            <td>{{$res->forwardedto}}</td>
            <td>@if($res->acknowledgeon)
                      {{date("d-m-Y", strtotime($res->acknowledgeon))}}
                  @endif

            </td>

          </tr>
          @endforeach
        </tbody>
      </table>
</div>
</form>

</div>
</div>
</section>
</div>
</div>


<script type="text/javascript">

$(document).ready(function(){

  $(function(e){
    $("#chkCheckAll").click(function(){
      $(".checkBoxClass").prop('checked',$(this).prop('checked') )
    })
  });

 });



$(document).ready(function(){

  $("#forwardedto").on('change',function(){

    var forwardedto=$("#forwardedto").val();
    var _token=$('input[name="_token"]').val();

    $.ajax({
      type:"POST",
      url:"ackdata",
      data:{
        _token:_token,forwardedto:forwardedto
      },
      cache:false,
      success:function(result){
        console.log(result);
        $("#userid").empty();
        $("#userid").append('<option value="">Select User</option>');
         $.each(result,function(key,value){
 $("#userid").append('<option value="'+value.userid+'">'+value.userid+'</option>');
         });
      }
    })

  });
});

// validation
$(document).ready(function(){
    $("#submit").click(function() {

                   if($("#forwardedto").val()==""){
                     alert("Please Select Section ");
                     return false;
                   }

                   if($("#userid").val()==""){
                     alert(" Please Select Login UserName");
                     return false;
                   }
                   if($('input[name^=queryno]:checked').length<=0){
                     alert('please tick the checkbox.');
                     return false;
                   }


});
});

</script>


  @endsection
