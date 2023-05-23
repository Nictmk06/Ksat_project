@extends('layout.mainlayout')
@section('content')
 
<style type="text/css">
  .tableBodyScroll tbody {
  display: block;
  max-height: 300px;
  overflow-y: scroll;
}

.tableBodyScroll thead,
tbody tr {
  display: table;
  width: 100%;
  table-layout: fixed;
}

.tableBodyScroll1 thead,
tbody tr {
  display: table;
  width: 100%;
}

  .tableBodyScroll1  tbody {
     display: block;
    max-height: 500px;
     width: 100%;
  overflow-y: scroll;
}
</style>


<!-- <div class="panel panel-heading origndiv">
//<h7 >Causelist Applications </h7>
</div>-->

 <div  class="content-wrapper"  > 
<section class="content"  > 
<div class="panel  panel-primary">
  
<div class='panel panel-body divstyle'  overflow: auto;>
  
<?php

      echo ChartManager::setChartType($charttype)
                        ->setOptions($options)
                        ->setCols($cols)
                        ->setRows($rows)
                        ->render();
if ($chartcount == 2) {
   echo ChartManager::setChartType($charttype)
                        ->setOptions($options1)
                        ->setCols($cols1)
                        ->setRows($rows1)
                        ->render();
}
?>

</div>
</div>

</section>
</div> 






@endsection