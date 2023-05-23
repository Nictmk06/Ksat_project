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
            { extend: 'copy',messageTop: 'List of Disposed Applcation from '+fromdate+' to '+  todate,title:establishfullname,footer: true,header:true,pageSize:'LEGAL',orientation : 'landscape'},
            { extend: 'excel', messageTop: 'List of Disposed Applcation from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize:'LEGAL',orientation : 'landscape' },
            { extend: 'csv', messageTop: 'List of Disposed Applcation from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize:'LEGAL',orientation : 'landscape'},
            { extend: 'pdf', messageTop: 'List of Disposed Applcation from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize:'LEGAL',orientation : 'landscape'},
            { extend: 'print', messageTop: 'List of Disposed Applcation from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize:'LEGAL',orientation : 'landscape'}

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
          <td  class="bg-primary text-center" colspan="13"> <h3>  <?php echo $establishmentname; ?></h3> </td>
          </tr>

            <tr>
          <td  class="bg-primary text-center" colspan="13"> <h4>  List of Disposed Applcation from <?php echo   date('d/m/Y', strtotime($fromdate)) ?> to <?php echo   date('d/m/Y', strtotime($todate)) ?>  </h4> </td>
          </tr>


<tr>
<td>Sr.No</td>
<td>Bench Type</td>
<td>Bench Name</td>
<td>Court HAll no </td>
<td>Application ID</td>
<td>Connected Applications</td>
<td>Plural Remedy Count</td>
<td>Author By</td>
<td>Register Date</td>
<td>Disposed Date</td>
<td>Category</td>
<td>Subject</td>
<td>Order Type</td>
<td>Applicant Count</td>
<td>Respondent Count</td>


</tr>

    </thead>

    <tbody>



                <?php $i = 1; ?>

              @foreach ($result as $result1)
           <tr>
            <?php
            $benchtypename=$result1->benchtypename;
            $benchname=$result1->judgeshortname1;
            $applicationid=$result1->applicationid;
            $conapplid=$result1->conapplid;
            $pluralremedycount=$result1->total_additionalno;
            $authorjudge=$result1->judgeby;
            $registerdate=$result1->registerdate;
            $disposeddate=$result1->disposeddate;
            $applcatname=$result1->applcatname;
            $subject=$result1->subject;
            $applicantcount=$result1->applicantcount;
            $respondentcount=$result1->respondentcount;
            $ordertype=$result1->ordertypedesc;
            $courthallno=$result1->courthallno;




             ?>
             <td align="center">{{$i++}}</td>
          <td><?php echo $benchtypename; ?> </td>
              <td><?php echo $benchname; ?> </td>
              <td><?php echo $courthallno; ?> </td>
              <td><?php echo $applicationid; ?></td>
              <td><?php echo $conapplid; ?></td>
              <td><?php echo $pluralremedycount; ?></td>
              <td><?php echo $authorjudge; ?></td>
              <?php echo "<td>".($registerdate ? date('d/m/Y', strtotime($registerdate)) : '')."</td>";?>
              <td><?php echo date('d/m/Y', strtotime($disposeddate)); ?></td>
            <td><?php echo $applcatname; ?></td>
            <td><?php echo $subject; ?></td>
            <td><?php echo $ordertype; ?></td>
            <td><?php echo $applicantcount; ?></td>
            <td><?php echo $respondentcount; ?></td>



               @endforeach

            </tr>




    </tbody>
    </table>
</div>
</section>

</div>


  @endsection
