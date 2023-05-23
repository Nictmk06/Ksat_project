@extends('layout.mainlayout')
@section('content')

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
    var query="<?php print_r($query);?>"  ;
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
                "pageLength": 50,
                "lengthChange": true,
                dom: 'Bfrtip',
                  buttons: [
            { extend: 'copy',messageTop: 'QUERY : '+query,footer: true,header:true,pageSize : 'LEGAL',orientation:'landscape'},
            { extend: 'excel',messageTop: 'QUERY : '+query,footer: true,header:true,pageSize : 'LEGAL',orientation:'landscape' },
            { extend: 'csv', messageTop: 'QUERY : '+query,footer: true,header:true,pageSize : 'LEGAL',orientation:'landscape' },
            { extend: 'pdf', messageTop: 'QUERY : '+query,footer: true,header:true,pageSize : 'LEGAL',orientation:'landscape'},
            { extend: 'print',messageTop: 'QUERY : '+query,footer: true,header:true,pageSize : 'LEGAL',orientation:'landscape'}

        ],

            });
        });

    </script>




<div class="content-wrapper">

 <section class="content" >

  <div class="panel  panel-primary">
    <table class="table table-bordered table2 display" id="myTable" width="100%">
        <thead>
          <tr>
          <td  class="bg-primary text-center" colspan="21"> <h3>  <?php echo $establishmentname; ?></h3> </td>
          </tr>

            <tr>
          <td  class="bg-primary text-center" colspan="21"> <h4> QUERY:<?php echo $query; ?> </h4> </td>
          </tr>
        <tr>


          <th align="center">Sr.No</th>

            @foreach ($resultheading as $heading)

                <th align="center">{{ $heading->column_name}}</th>

            @endforeach
        </tr>
    </thead>
    <tbody>

<?php
//echo '<pre>';print_r($resultheading);echo "</pre>";

  //$getcolname = $reshead->column_name;
$i=1;
foreach($resultdata as $resdata){
      echo '<tr>';
      echo '<td>'. $i++ .'</td>';
      foreach($resultheading as $reshead){
           $getcolname = $reshead->column_name;

	    	?>
    <td><?php echo $resdata->$getcolname;?></td>
<?php	}
  echo '</tr>';
}
?>


    </tbody>

    </table>
</div>
</section>

</div>


  @endsection
