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

  <script type="text/javascript" src="js/jquery-3.4.1.slim.min.js"></script>
  <script type="text/javascript" src="js/popper.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/jquery-3.3.1.js"></script>
  <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="assets/js/search_filter/datatables.min.js"></script>
  <script src="assets/js/search_filter/jquery-1.11.1.min.js"></script> <!-- JQuery Reference -->
  <script src="assets/js/search_filter/jquery.dataTables.min.js"></script>
  <script src="assets/js/search_filter/dataTables.buttons.min.js"></script>
  <script src="assets/js/search_filter/buttons.colVis.min.js"></script>
  <script src="assets/js/search_filter/buttons.flash.min.js"></script>
  <script src="assets/js/search_filter/buttons.html5.min.js"></script>
  <script src="assets/js/search_filter/buttons.print.min.js"></script>
  <script src="assets/js/search_filter/dataTables.responsive.min.js"></script>
      <script type="text/javascript" src="assets/js/search_filter/datatables.min.js"></script>

      <script src="assets/js/search_filter/jquery-1.11.1.min.js"></script>
      <script src="assets/js/search_filter/jquery.dataTables.min.js"></script>
      <script src="assets/js/search_filter/dataTables.buttons.min.js"></script>
      <script src="assets/js/search_filter/buttons.colVis.min.js"></script>
      <script src="assets/js/search_filter/buttons.flash.min.js"></script>
      <script src="assets/js/search_filter/buttons.html5.min.js"></script>
      <script src="assets/js/search_filter/buttons.print.min.js"></script>
      <script src="assets/js/search_filter/dataTables.responsive.min.js"></script>
      <link rel="stylesheet" href="assets/css/search_filter/jquery-ui.css">
      <link rel="stylesheet" href="assets/css/search_filter/dataTables.jqueryui.css">
      <link rel="stylesheet" href="assets/css/search_filter/jquery.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/search_filter/buttons.dataTables.min.css">
      <link rel="stylesheet" href="assets/css/search_filter/responsive.dataTables.min.css">



<script type="text/javascript">
    var jq = $.noConflict();
    var fromdate="<?php echo   date('d/m/Y', strtotime($fromdate)) ?>";

    var todate="<?php echo   date('d/m/Y', strtotime($todate))   ?>";


    var establishfullname="<?php echo $establishmentname; ?> "
      jq(document).ready(function () {

               jq('#myTable').dataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "searching": true,
                "ordering": true,
                "info": true,
                "paging": true,
                "responsive": true,
                "rowReorder": true,

                "lengthChange": true,
                dom: 'Bfrtip',
                  buttons: [
            { extend: 'copy',messageTop: 'List of Caveat filed(period) from '+fromdate+' to '+  todate,title:establishfullname,footer: true,header:true,orientation : 'landscape'},
            { extend: 'excel', messageTop: 'List of Caveat filed(period) from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,orientation : 'landscape' },
            { extend: 'csv', messageTop: 'List of Caveat filed(period) from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,orientation : 'landscape'},
            { extend: 'pdf', messageTop: 'List of Caveat filed(period) from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,orientation : 'landscape'},
            { extend: 'print', messageTop: 'List of Caveat filed(period) from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,orientation : 'landscape'}

        ],

            });
        });

    </script>




<div class="content-wrapper">

 <section class="content" >

  <div class="panel  panel-primary">

    <table class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
        <thead>
          <tr>
          <td  class="bg-primary text-center" colspan="10"> <h3>  <?php echo $establishmentname; ?></h3> </td>
          </tr>

            <tr>
          <td  class="bg-primary text-center" colspan="10"> <h4> List of Caveat filed(period) from <?php echo   date('d/m/Y', strtotime($fromdate)) ?> to <?php echo   date('d/m/Y', strtotime($todate)) ?>  </h4> </td>
          </tr>


<tr>
<td>Sr.No</td>
<td>Caveat ID</td>
<td>Caveat File Date</td>
<td>Subject Category</td>
<td>Subject</td>
<td>Caveator Count</td>
<td>Caveatee Count</td>
<td>GoOrders</td>


</tr>

    </thead>

    <tbody>



                <?php $i = 1; ?>

              @foreach ($result as $result1)
           <tr>
            <?php
            $caveatid=$result1->caveatid;
            $caveatfiledate=$result1->caveatfiledate;
            $subject=$result1->subject;
            $applcatname=$result1->applcatname;
            $caveatorcount=$result1->caveatorcount;
            $caveateecount=$result1->caveateecount;
            $goorders=$result1->goorders;








             ?>
             <td align="center">{{$i++}}</td>
              <td><?php echo $caveatid; ?></td>
              <td><?php echo date('d/m/Y', strtotime($caveatfiledate)); ?></td>
              <td><?php echo $applcatname; ?></td>
              <td><?php echo $subject; ?></td>
              <td><?php echo $caveatorcount; ?></td>
              <td><?php echo $caveateecount; ?></td>
              <td><?php echo $goorders; ?></td>



               @endforeach

            </tr>




    </tbody>
    </table>
</div>
</section>

</div>


  @endsection
