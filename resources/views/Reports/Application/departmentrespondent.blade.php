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
<script type="text/javascript" src="js/jquery-3.4.1.slim.min.js"></script>
<script type="text/javascript" src="js/popper.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-3.3.1.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/jquery-3.4.1.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/buttons.flash.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>

   <div align="center">
 <section class="content" style="width: 75%">
  <div class="panel  panel-primary">
   <form name="form1" id="form1" action="{{ route('datadepartmentrespondent') }}" method="post">
    {{ csrf_field() }}
<div class="col-md-10">
<table class="table no-margin table-bordered">

    <tr>
    <td  class="bg-primary text-center" colspan="4"> <h4>Application received against department  </h4> </td>
    </tr>




<tr>
<td> <span class="mandatory">*</span> <label for="applTitle"> From Date</label> </td>
    <td>
      <div class="input-group date">
           <div class="input-group-addon">
             <i class="fa fa-calendar"></i>
           </div>
           <input type="text" name="fromdate" class="form-control pull-right datepicker "   id="fromdate" value=""   data-parsley-errors-container="#error6" >
         </div>
         <span id="error6"></span>

       </td>
</tr>

<tr>
<td> <span class="mandatory">*</span> <label for="applTitle">To Date </label> </td>
<td>
  <div class="input-group date">
       <div class="input-group-addon">
         <i class="fa fa-calendar"></i>
       </div>
       <input type="text" name="todate" class="form-control pull-right datepicker "   id="todate" value=""   data-parsley-errors-container="#error6" >
     </div>
     <span id="error6"></span>
  </td>
</tr>
<tr>
  <td>   <span class="mandatory">*</span>  <label>Department Type</label></td>
  <td>
    <div class="form-group">

      <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-required data-parsley-required-message="Select Department Type" >
        <option value="">Department Type</option>

       @foreach($depttypee1 as $depttypee)
        <option value="{{ $depttypee->depttypecode}}" >{{$depttypee->depttype}}</option>
        @endforeach

      </select>
    </div>
  </td>

</tr>

<tr>
  <td>   <span class="mandatory">*</span>  <label>Department Name</label></td>
  <td>
    <div class="form-group">

      <select class="form-control" name="depname" id="depname"  data-parsley-required data-parsley-required-message="Select Department Name" >

        <option value="">Department Name</option>

       @foreach($namedept as $namedept)

        <option value="{{ $namedept->departmentcode}}" >{{$namedept->departmentname}}</option>
        @endforeach

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





         </div>




</div>
</section>
</div>






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


      </script>

      <script>
               $(document).ready(function() {
              $('#applTypeName').on('change', function() {
                  var deptypecode = $(this).val();

                  console.log(deptypecode);
                  if(deptypecode) {
                      $.ajax({
                          url: '/findDepWithDeptype/'+deptypecode,
                          type: "get",
                          data : {"_token":"{{ csrf_token() }}"},
                          dataType: "json",
                          success:function(data) {
                              console.log(data);
                            if(data){
                              $('#depname').empty();
                              $('#depname').focus;
                              $('#depname').append('<option value="-1">-- ALL --</option>');

                              $.each(data, function(key, value){
                                console.log(key);
                                console.log(value);
                              $('select[name="depname"]').append('<option value="'+ value.departmentcode +'">' + value.departmentname+ '</option>');

                          });
                        }

                        else{
                          $('depname').empty();
                        }
                        }
                      });
                  }else{
                    $('depname').empty();
                  }
              });
          });
          </script>


  @endsection
