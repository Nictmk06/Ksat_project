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

        var departmentname="<?php
                       foreach ($departmentname as $departmentname){
                            echo $departmentname->departmentname;
                          }  ?>"  ;
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
                        { extend: 'copy',messageTop: ' Application received against ' +departmentname+ ' from '+fromdate+' to '+  todate,title: establishfullname,footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                        { extend: 'excel', messageTop: ' Application received against ' + departmentname+' from '+fromdate+' to '+  todate,title:establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape' },
                        { extend: 'csv', messageTop: 'Application received against '+ departmentname +' from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                        { extend: 'pdf', messageTop: ' Application received against '+departmentname +' from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true,pageSize : 'LEGAL',orientation : 'landscape'},
                        { extend: 'print', messageTop: 'Application received against '+ departmentname +' from '+fromdate+' to '+  todate,title: establishfullname, footer: true,header:true, pageSize : 'LEGAL',orientation : 'landscape'}
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
          <td  class="bg-primary text-center" colspan="11"> <h3> <?php echo $establishmentname; ?></h3> </td>
          </tr>
          <tr>
          <td  class="bg-primary text-center" colspan="11"> <h4>  Application received against <?php  foreach ($departmentname as $departmentname){

                              echo $departmentname;
                            }?>  from  <?php echo   date('d/m/Y', strtotime($fromdate)) ?> to <?php echo   date('d/m/Y', strtotime($todate)) ?> </h4> </td>
          </tr>

          <tr>
            <td>Sr.No</td>
            <td>Application ID</td>
            <td>Register Date</td>

            <?php  if($depcode==-1){
            echo "<td> Department Name</td>";
            }?>
            <td>Application Category</td>
            <td>Subject</td>


          </tr>

    </thead>
    <tbody>

<?php $sum=0;?>
                <?php $i = 1; ?>

              @foreach ($result as $result1)
              <tr>
               <?php
               $applicationid=$result1->applicationid;
               $registerdate=$result1->registerdate;
               if($depcode==-1)
               {
               $departmentname=$result1->departmentname;
               }
               $applcatname=$result1->applcatname;
               $subject=$result1->subject;







                ?>
                <td align="center">{{$i++}}</td>
                  <td><?php echo $applicationid; ?> </td>
                  <td><?php echo date('d/m/Y', strtotime($registerdate)); ?></td>
                  <?php if($depcode==-1)
                  {

                  echo "<td> $departmentname </td>";
                  } ?>
                 <td><?php echo $applcatname; ?></td>
                 <td><?php echo $subject; ?></td>
                @endforeach
               </tr>





    </tbody>
    </table>
</div>
</section>

</div>


  @endsection
