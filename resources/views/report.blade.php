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
            { extend: 'copy', footer: true,header:true},
            { extend: 'excel', footer: true,header:true },
            { extend: 'csv', footer: true,header:true },
            { extend: 'pdf', footer: true,header:true},
            { extend: 'print', footer: true,header:true}

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

            <th align="center">Sr.No</th>
            @foreach ($c_name as $c_name1)

                <th align="center">{{ $c_name1}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        @foreach ($result as $result1)
                <tr>
                    <td align="center">{{$i++}}</td>
                    @foreach ($c_name as $c_name1)
                        <td align="center">{{ $result1->$c_name1}}</td>
                    @endforeach
                </tr>
        @endforeach
    </tbody>
    </table>
</div>
</section>

</div>


  @endsection
