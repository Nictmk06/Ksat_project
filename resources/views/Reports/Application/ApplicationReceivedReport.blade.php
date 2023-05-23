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
var subtitle = "<?php echo  $subtitle  ?>";
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
                { extend: 'copy',messageTop: subtitle, title: establishfullname,footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                { extend: 'excel', messageTop:  subtitle, title:establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape' },
                { extend: 'csv', messageTop:subtitle, title: establishfullname, footer: true,header:true ,pageSize : 'LEGAL',orientation : 'landscape'},
                { extend: 'pdf', messageTop:subtitle, title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                { extend: 'print', messageTop:subtitle, title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'}

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
          <td  class="bg-primary text-center" colspan="14"> <h3> <?php echo $establishmentname; ?></h3> </td>
          </tr>
          <tr>
          <td  class="bg-primary text-center" colspan="14"> <h4>  <?php echo  $subtitle ?> </h4> </td>
          </tr>

<tr>
<td>Sr.No</td>
<td>Application ID</td>
<td>Date of Registration</td>

<td>Applicant Count</td>
<td>Plural Remedy Count</td>
<td>Respondant Count</td>

<td>Name of Applicants</td>
<td>Name of Respondant</td>
<td>Subject</td>
<td>Connected With</td>

<td>Application category</td>
<td>Name of Advocate</td>
<td>Receipt Number</td>
<td>Application Fees</td>
</tr>

    </thead>
    <tbody>

<?php $sum=0;?>
                <?php $i = 1; ?>

              @foreach ($result as $result1)
           <tr>
            <?php
            $applicationid=$result1->applicationid;
            $registerdate1=$result1->registerdate1;
            $applicantcount=$result1->applicantcount;
            $additonalno=$result1->total_additionalno;
            $respondantcount=$result1->respondentcount;

            $applicantname1=$result1->applicantname1;
            $respondantname=$result1->respondentname;
            $subject=$result1->subject;
            $connectedwith=$result1->conapplid;
            $applcatname=$result1->applcatname;
            $advocatename=$result1->advocatename;
            $amount=$result1->amount;
            $receiptno=$result1->receiptno;
            //  $sum=$sum+(int)$amount;
             if( strpos($amount, ',') !== false ) {
                $res = explode(',',$amount);

                $amount1 = array_sum($res);
                $sum=$sum+(int)$amount1;
              }
              else{
                $sum=$sum+(int)$amount;
             }


             ?>
             <td align="center">{{$i++}}</td>
               <td><?php echo $applicationid; ?> </td>
               <td><?php echo date('d/m/Y', strtotime($registerdate1)); ?></td>
               <td><?php echo $applicantcount; ?></td>
               <td><?php echo $additonalno; ?></td>

              <td><?php echo $respondantcount; ?></td>
              <td><?php echo $applicantname1; ?></td>
               <td><?php echo $respondantname; ?></td>
               <td><?php echo $subject; ?></td>
               <td><?php echo $connectedwith; ?></td>
               <td><?php echo $applcatname; ?></td>
               <td><?php echo $advocatename; ?></td>
               <td><?php echo $receiptno; ?></td>
                <td align="right"><?php echo $amount; ?></td>




               @endforeach
            </tr>

            <tr>
              <td></td>
              <td>Total Amount:<?php echo $sum;?></td>
                <td></td>
                 <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                  <td></td>
                   <td></td>
                   <td></td>
                    <td></td>

                <td align="right"></td>
                  <td align="right"> </td>



          </tr>





    </tbody>
    </table>
</div>
</section>

</div>


  @endsection
