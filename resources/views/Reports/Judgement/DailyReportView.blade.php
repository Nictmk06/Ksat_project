@extends('layout.mainlayout')
@section('content')

<head >
  <style>
table.table-bordered > tbody > tr > td{
    border:1px solid black;
    background-color: #ccd9ff;
    font-size: 16px;
    font-family:Times new roman;

}


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
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.min.js"></script>

</head>

<body>

  <div class="content-wrapper" style="height:auto; height:5400px;">
  <script type="text/javascript" src="js/jquery.min.js"></script>


     <div align="center">
   <section class="content" style="width: 75%">
    <div class="panel  panel-primary">
     <form name="form1" id="form1" action="" method="post">
      {{ csrf_field() }}
  <div class="col-md-10">
  <table class="table no-margin table-bordered">

      <tr>
      <td  class="bg-primary text-center" colspan="4"> <h4>Court Directions  </h4> </td>
      </tr>


  <tr>
  <td> <span class="mandatory">*</span> <label for="applTitle"> Hearing Date</label> </td>
      <td>
        <div class="input-group date">
             <div class="input-group-addon">
               <i class="fa fa-calendar"></i>
             </div>
             <input type="text" name="hearingdate" class="form-control pull-right datepicker " id="hearingdate" value=""  data-parsley-errors-container="#error6" >
           </div>
           <span id="error6"></span>

         </td>
  </tr>


  <tr>
    <td>   <span class="mandatory">*</span>  <label>Bench Name</label></td>
    <td>
      <div class="form-group">

        <select class="form-control" name="benchname" id="benchname"  data-parsley-required data-parsley-required-message="Select Bench Name" >
          <option value="">Select Bench Name</option>
        </select>
      </div>
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

    <div id="reportviewdata">

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
        </div>
        </div>
        </section>
        </div>

</body>

<script>

$(document).ready(function() {
  $("#fromdate").datepicker({
              format: 'dd-mm-yyyy',
              autoclose: true,
            }).on('changeDate', function(selected) {
              var endDate = new Date(selected.date.valueOf());
              $('#todate').datepicker('setStartDate', endDate);
            }).on('clearDate', function(selected) {
              $('#todate').datepicker('setEndDate', null);
            });
});



$(document).ready(function() {
    $('#hearingdate').on('change', function() {
      var hearingdate = $(this).val();
       var benchcode=$("#benchcode").val();

        console.log(hearingdate);
         if(hearingdate) {
             $.ajax({
                 url: '/findJudgeWithBenchCode1/'+hearingdate,
                 type: "get",
                 data : {"_token":"{{ csrf_token() }}"},
                 dataType: "json",
                 success:function(data) {
                     console.log(data);
                   if(data){
                     $('#benchname').empty();
                     $('#benchname').focus;
                     $('#benchname').append('<option value="">Select Bench Name</option>');

                     $.each(data, function(key, value){
                       console.log(key);
                       console.log(value);
                    $('select[name="benchname"]').append('<option value="'+ value.benchcode + ':' + value.listno +'">' + value.judgeshortname+ ' : '+ value.courthalldesc +  ' : '+ value.listno + '</option>');

                 });
               }

               else{
                 $('benchname').empty();
               }
               }
             });
         }else{
           $('benchname').empty();
         }
      });
});


$("#submit").on('click', function(){
  var _token=$('input[name="_token"]').val();
  var hearingdate=$("#hearingdate").val();
  var benchname=$("#benchname").val();
  var benchcode=$("#benchname").val();
  // alert(benchcode);


$.ajax({
  type:"post",
  url:"buttonsubmit",
  data:{_token:_token,hearingdate:hearingdate,benchname:benchname},
  cache:false,
  datatype:"html",
  success:function(data){
  $('#reportviewdata').html(data);


  }
});
});


</script>
 @endsection
