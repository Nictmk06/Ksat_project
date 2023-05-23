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
                { extend: 'copy',messageTop: 'CCA delivery report  from '+fromdate+' to '+  todate,title: establishfullname,footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                { extend: 'excel', messageTop: 'CCA delivery report  from '+fromdate+' to '+  todate,title:establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape' },
                { extend: 'csv', messageTop: 'CCA delivery report  from'+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape' },
                { extend: 'pdf', messageTop: 'CCA delivery report  from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                { extend: 'print', messageTop: 'CCA delivery report  from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'}

            ],

                });
            });

        </script>






<div class="content-wrapper">

 <section class="content" >

  <div class="panel  panel-primary">

    <table class="table table-bordered table2 display"  data-page-length='25' id="myTable" width="100%">
        <thead>
          <tr>
          <td  class="bg-primary text-center" colspan="9"> <h3> <?php echo $establishmentname; ?></h3> </td>
          </tr>
          <tr>
          <td  class="bg-primary text-center" colspan="9"> <h4>  CCA delivery report from  <?php echo   date('d/m/Y', strtotime($fromdate)) ?> to <?php echo   date('d/m/Y', strtotime($todate)) ?> </h4> </td>
          </tr>

<tr>
<td>Sr.No</td>
<td>Application ID</td>
<td>CCA Application No</td>
<td>CCA Application Date</td>
<td>Document Type</td>
<td>Document Name</td>
<td>Case Number</td>
<td>Mode of delivery</td>
<td>Document delivered on</td>
<td>Delivered to</td>
</tr>

    </thead>
    <tbody>

<?php $sum=0;?>
                <?php $i = 1; ?>

              @foreach ($result as $result1)
           <tr>
            <?php
            $applicationid=$result1->applicationid;
            $ccaapplicationno=$result1->ccaapplicationno;
            $caapplicationdate=$result1->caapplicationdate;
            $documenttype=$result1->documenttype;
            $documentname=$result1->documentname;
            $cano=$result1->cano;
            $deliverydesc=$result1->deliverydesc;
            $deliveredon=$result1->deliveredon;
            $deliveredto=$result1->deliveredto;



             ?>
             <td align="center">{{$i++}}</td>
               <td><?php echo $applicationid; ?></td>
               <td><?php echo $ccaapplicationno; ?> </td>
               <td><?php echo date('d/m/Y', strtotime($caapplicationdate)); ?></td>

               <td><?php echo $documenttype; ?></td>
               <td><?php echo $documentname; ?></td>
               <td><?php echo $cano; ?></td>
               <td><?php echo $deliverydesc; ?></td>
               <td><?php echo $deliveredon; ?></td>
               <td><?php echo $deliveredto; ?></td>





               @endforeach
            </tr>






    </tbody>
    </table>
</div>
</section>

</div>


  @endsection
