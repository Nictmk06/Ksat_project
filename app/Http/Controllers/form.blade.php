@extends('layout.mainlayout')
@section('content')
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
   <form name="form1" id="form1" action="{{ route('details') }}" method="post">
    {{ csrf_field() }}

    <table class="table no-margin table-bordered">
      <tr>
      <td  class="bg-primary text-center" colspan="4"> <h4>Search Data</h4> </td>
      </tr>


    <tr>





          <td>  <label>Select Table or View <span class="mandatory">*</span></label></td>

              <td> <select class="form-control dynamic"  accesskey="1" autofocus="autofocus" name="t_name" id="t_name" style="height: 48px;" required>

                  <option selected="true" >Choose Table or View</option>


                    @foreach($t_name as $t_name)
                      <option value="{{ $t_name->t_name }}"> {{ $t_name->t_name }} </option>
                    @endforeach

                </select>
                <br />
                </td>




    </tr>


        <tr>
          <td><label>Select Column </label></td>
        <td>
          <select  class="form-control dynamic" accesskey="1" autofocus="autofocus" name="c_name[]" id="c_name" multiple="multiple" required >
                  <option selected="true" disabled="disabled">Choose</option>
                </select>
                <br/>
              </td>

       </tr>







        <tr>
          <td><label>Select Column for Condition </label></td>
          <td> <select  class="form-control" accesskey="1" autofocus="autofocus" name="c_names" id="c_names">
                  <option selected="true" disabled="disabled">Choose</option>
              </select>

            </td>
          </tr>


      <tr>
        <td>  <label>Select Operator</label></td>
        <td>  <select  class="form-control" accesskey="1" autofocus="autofocus" name="cond" id="cond"required >

                  <option selected="true" disabled="disabled">Choose</option>
                    <option>=</option>
                      <option>>=</option>
                      <option><=</option>
                      <option><></option>
                      <option>ilike</option>
                </select>
       </td>
     </tr>

         <tr>
        <td>  <label>Enter Value </label></td>
      <td>    <input type="text" name="values1" id="values1" class="form-control" ></td>
        <td><button type="button" class="btn btn-primary" id="add" onclick="addc()">ADD</button>
      <button type="button" class="btn btn-danger" id="clear" onclick="clr()">CLEAR</button></td>
    </tr>





     <div class="row">




        <td><label>Conditions criteria</label></td>
      <td>  <textarea rows="3"   name="contents" id="contents" class="form-control" readonly></textarea></td>




      <tr>
         <td colspan="4">
         <div class="text-center">


            <input type="submit" accesskey="s" class="btn btn-primary" value="Submit" id="submit" width="48px" >

                <a class="btn btn-warning" href=""> Cancel </a>
         </div>

         </td>
         </tr>




         </div>


    </form>

</div>
</section>
</div>




 <script>

    $('#t_name').change(function(){
    var t_name = $(this).val();
    if(t_name){
        $.ajax({
           type:"GET",
           url:"column_names?t_name="+t_name,
           success:function(res){
           //console.log(res);
            if(res){
                $("#c_name").empty();
                $("#c_names").empty();

                $("#c_name").append('<option selected="true" disabled="disabled">Select</option>');

                $("#c_names").append('<option selected="true" disabled="disabled">Select</option>');
                for (var i = 0; i < res.column_name.length; i++)
                {

                    $("#c_name").append('<option value="'+res.column_name[i].column_name+'">'+res.column_name[i].column_name+'</option>');

                    $("#c_names").append('<option value="'+res.column_name[i].column_name+'">'+res.column_name[i].column_name+'</option>');


                 }

            }else{
               $("#c_name").empty();
               $("#c_names").empty();
            }
           }
        });
    }else{
        $("#c_name").empty();
        $("#c_names").empty();

    }
   });

        function addc()
        {
          var col = $("#c_names").val();
          var cond = $("#cond").val();
          var val = $("#values1").val();
          var slctd=$("#contents").val();

          //alert(col);
          //alert(cond);
          //alert(val);
          //alert(slctd);
          if(slctd=='' && cond=='ilike')
          {
          $("#contents").append(col+" "+cond+" '%"+val+"%'");
          }

          else if(slctd=='')
          {
          $("#contents").append(col+cond+"'"+val+"'");
          }
          else if(slctd!='' && cond=='ilike')
          {
            $("#contents").append(' and '+col+" "+cond+"'%"+val+"%'");
          }

          else
          {
            $("#contents").append(' and '+col+cond+"'"+val+"'");
          }

        }

        function clr()
        {
          $("#contents").empty();
        }

      </script>


  @endsection
