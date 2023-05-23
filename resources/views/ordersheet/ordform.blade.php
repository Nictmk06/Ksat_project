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
   <form name="form1" id="form1" action="ordergenerate" method="post">
    {{ csrf_field() }}

    <div class="panel panel-body divstyle" >

           <br><br>

      <div class="row">
           <div class="col-md-4">
          <div class="form-group">
          <label>Enter Option Name </label>
          <input type="text" name="optname" id="optname" class="form-control" >
              </div>
            </div>

      <div class="col-md-4">
        <div class="form-group">
          <label>Enter Option Code</label>
            <input type="text" name="optcode" id="optcode" class="form-control" >
                </div>
              </div>

              <div class="col-md-4">
        <div class="form-group">
          <label>Enter Link Name </label>
          <input type="text" name="linkname" id="linkname" class="form-control" >
        </div>
      </div>
    </div>
    <br><br>

    <div class="row">

      <div class="col-md-4">
        <div class="form-group">
        </div>
      </div>

        <div class="col-md-4">
        <div class="form-group">
        <br><br> <br>

          <input type="submit" accesskey="s" class="btn btn-primary" value="Submit" id="submit" width="48px" >
  </div>
</div>
</div>



         </div>


    </form>

</div>
</section>
</div>







  @endsection
