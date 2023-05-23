@extends('layout.mainlayout')
@section('content')




<div class="content-wrapper">
  <div align="center">
  <section class="content" style="width: 75%">
      <div class="panel  panel-primary">
      <br>
 <br>
          <form name="form1" id="form1" action="{{ route('datapendingapplication') }}"  method="post">
            <!-- action="{{ route('datapendingapplication') }}" -->
          {{ csrf_field() }}
          <div class="col-md-10">
                <table class="table no-margin table-bordered">
                  <tr>
                    <td  class="bg-primary text-center" colspan="4"> <h4>  List of  Pending Application </h4> </td>
                  </tr>
                  <tr>
                    <td><label>Application Type<span class="mandatory">*</span></label></td>
                    <td>
                      <div class="form-group">
                        <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-required data-parsley-required-message="Select Connected Application Type" >
                          <option value="">Select Application Type</option>
                          <option value="1" >All</option>
                        @foreach($applicationType as $applType)
                          <option value="{{ $applType->appltypeshort}}" >{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                          @endforeach
                        </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td><label>Application Year<span class="mandatory">*</span></label></td>
                    <td>
                      <div class="form-group">
                        <select class="form-control" name="applicationYear" id="applicationYear"  data-parsley-required data-parsley-required-message="Select Connected Application Type" >
                          <option value="">Select Application Type</option>
                          <option value="1" >All</option>
                        @foreach($applicationYear as $apply)
                          <option value="{{ $apply->applicationyear}}" >{{$apply->applicationyear}}</option>
                          @endforeach
                        </select>
                      </div>
                    </td>
                  </tr>

                <tr>
                <td>  <label for="applTitle" > Date<span class="mandatory">*</span></label> </td>
                    <td>
                      <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input style="background-color: white;" type="text" name="registerdate"  class="form-control pull-right datepicker " id="registerdate" value=""   data-parsley-errors-container="#error6" >
                          <!--  class="form-control pull-left datepicker " -->
                        </div>
                        <span id="error6"></span>
                      </td>
                </tr>
                <tr>
             <td colspan="4">
             <div class="text-center">
                 <input type="button" accesskey="s" class="btn btn-primary" value="Submit" id="submit" width="100px" >
                    <a class="btn btn-warning" href=""> Cancel </a>
             </div>
             </td>
             </tr>


                </table>
</div>
</form>
<form role="form" id="pendingform" name="pendingform" method="GET" action="pendingapplication1" data-parsley-validate>
  <div class="panel  panel-primary">
    <table class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
      <thead>
          <tr>
            <td  class="bg-primary text-center" colspan="15"><h4> Pending Application</h4> </td>
          </tr>
          <tr>

              <td class="hiding"><input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">Sr.No</td>
              <td class="hiding">Application Year</td>
              <td class="hiding">Appltype Desc </td>
              <td class="hiding">Application Count</td>
              <td class="hiding">Applicant Count</td>
              <td class="hiding">Group Application Count</td>

          </tr>
          </thead>
          <tbody>
          </tbody>
    </table>
    <!-- </table> -->

  </div>
</form>
<!-- </div>
</form> -->


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


</section>
</div>
</div>
<script type="text/javascript" src="js/jquery.min.js"></script>

<script>
  $(document).ready(function(){
   $("#submit").on('click',function(){


      var _token = $('#token').val();
      var applTypeName=$("#applTypeName").val();
      var applicationYear=$("#applicationYear").val();
      var registerdate=$("#registerdate").val();
      //  alert(applTypeName);
      //  alert(applicationYear);
      //  alert(registerdate);
      // alert( this.value );

      $.ajax({
        type:"POST",
        url:"pendingapplication1",
        cache:false,
        data:{"_token":_token,applicationYear:applicationYear,applTypeName:applTypeName,registerdate:registerdate},
        success:function(res){
         $("#myTable tbody").html(res);
        }
      })



    })
  });

</script>


  @endsection
