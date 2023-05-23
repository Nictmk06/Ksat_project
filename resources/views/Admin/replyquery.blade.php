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
         <form  name="replyquerydata" id="replyquerydata" action="updatereply" method="post">
           <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
           {{ csrf_field() }}
           <div class="col-md-10" style="width:70%;margin-right:0px; margin-left:-12px;">
             <table class="table no-margin table-bordered" >
               <tr>
                  <td  class="bg-primary text-center" colspan="4"> <h4>Reply Query </h4> </td>
                </tr>
                <tr>
                  <td> <span class="mandatory">*</span> <label for="">Reply Content</label></td>

                    <td> <textarea name="replycontent" id="replycontent" rows="4" cols="50"></textarea> </td>
                  </tr>

                  <tr>
                    <td colspan="4">
                      <div class="text-center">
                        <input type="submit" accesskey="s" class="btn btn-warning" value="Submit" id="submit"     width="100px" >
                        <a class="btn btn-warning" href=""> Cancel </a>
                      </div>
                    </td>
                  </tr>
                </table>
                </div>

            <div class="panel  panel-primary" style="width:80%;margin-right:290px;">
              <table class="table table-bordered table2 display solid black" data-page-length='25' id="myTable" width="100%">
                <thead>
                    <tr>
                      <td  class="bg-primary text-center" colspan="15"><h4> Reply Query</h4> </td>
                    </tr>
                    <tr>
                      <td style="background-color: #ec971f;"></td>
                      <td style="background-color: #ec971f;">Sr.No</td>
                      <td style="background-color: #ec971f;">Subject</td>
                      <td style="background-color: #ec971f;">Description</td>
                      <td style="background-color: #ec971f;">Replied ON</td>
                      <td style="background-color: #ec971f;">Reply Content</td>
                      <td style="background-color: #ec971f;"> Status</td>

                    </tr>
                    </thead>
                    <tbody>
                      <?php $i=1; ?>

                        @foreach($result as $res)
                        <tr>
                          <td> <input type="checkbox" class="checkBoxClass" name="queryno[]" value="{{$res->queryno}}"/> </td>
                          <td>{{$i++}}</td>
                          <td>{{$res->querytypedescription}}</td>
                          <td>{{$res->querycontent}}</td>
                          <td>@if($res->repliedon)
                                  {{date("d-m-Y", strtotime($res->repliedon))}}
                              @endif

                        </td>
                          <td>{{$res->replycontent}}</td>
                          <td>@if($res->statuscode == 1)
                                Pending
                              @else
                                Closed
                              @endif
                          </td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
          </form>
        </div>
    </section>
  </div>
</div>
<script type="text/javascript">


$(document).ready(function(){

  $('input[type="checkbox"]').on('change', function() {
   $('input[type="checkbox"]').not(this).prop('checked', false);
});


    $("#submit").click(function() {

        if($("#replycontent").val()=="")
        {
          alert("please enter Description");
          return false;
        }
           if ($('input[name^=queryno]:checked').length <= 0)
              {
                 alert('please tick the checkbox.');
                 return false;
              }

  });
});




</script>



  @endsection
