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

  <form name="form1" id="form1" action="datascrutinyhistory" method="post">
   {{ csrf_field() }}
<div class="col-md-10">
<table class="table no-margin table-bordered">
 <tr>
   <td  class="bg-primary text-center" colspan="4"> <h4>Scrutiny Objections History </h4> </td>
 </tr>
 <tr>
    <td><span class="mandatory">*</span> <label for="applTitle">Type of Application</label> </td>
       <td>
             <select class="form-control" name="applicationType" id="applicationType"  >
              <option value="">Select Application Type</option>
                    @foreach($applicationType as $applType)
                    <option value="{{$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                    @endforeach
                  </select>
          </td>
      </tr>
<tr>
<td><span class="mandatory">*</span> <label for="applTitle"> Application ID</label> </td>
   <td>
    <input type="text" name="applicationNo" id="applicationNo" class="form-control"  data-parsley-required data-parsley-required-message="Enter Application Number" data-parsley-pattern="/^[0-9\/]+$/"  data-parsley-trigger='keypress' maxlength="100" placeholder=" Application Number" >
  </td>
</tr>

<tr>
 <td><span class="mandatory">*</span>  <label>Scrutiny Date</label></td>
 <td>
   <div class="form-group">
     <select class="form-control" name="scrutinydate" id="scrutinydate"  data-parsley-required data-parsley-required-message="Select Bench Name" >
       <option value="">Select Scrutiny Date</option>
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

 
            <script src="js/jquery-3.4.1.js"></script>

            <script>
                     $(document).ready(function() {
                    $('#applicationNo').on('change', function() {
                        var applicationno = $(this).val();
                        console.log(applicationno);
                        var applicationtype=$("#applicationType").val();
                        var result=applicationtype+'/'+applicationno;
                        var applicationid=result.replaceAll("/", "-");
                        console.log(applicationid);
                        if(applicationid) {
                            $.ajax({
                                url: 'findScrutinydate/'+applicationid,
                                type: "get",
                                cache: false,
                                data : {"_token":"{{ csrf_token() }}"},
                                dataType: "json",
                                success:function(data) {
                                    console.log(data);
                                  if(data!=null){
                                    $('#scrutinydate').empty();
                                    $('#scrutinydate').focus;
                                    $('#scrutinydate').append('<option value="">Select Scrutiny Date</option>');

                                    $.each(data, function(key, value){
                                      console.log(key);
                                      console.log(value);
                                   $('select[name="scrutinydate"]').append('<option value="'+ value.scrutinydate +'">' + value.scrutinydate+ '</option>');

                                });
                              }

                              else{

                                $('scrutinydate').empty();


                              }
                              }
                            });
                        }else{
                          $('scrutinydate').empty();

                        }
                    });
                });
                </script>


</script>

@endsection
