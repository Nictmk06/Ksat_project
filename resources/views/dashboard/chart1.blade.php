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
  
  Testing of chart through blade

  <?php 
  $options = [
                    'title' => 'Population of Largest U.S. Cities',
                    'chartArea' => ['width' => '50%'],
                    'hAxis' => [
                        'title' => 'Total Population',
                        'minValue' => 0
                    ],
                    'vAxis' => [
                        'title' => 'City'
                    ],
                    'bars' => 'horizontal', //required if using material chart
                    'axes' => [
                        'y' => [0 => ['side' => 'right']]
                    ]
                ];

                $cols = ['City', '2010 Population', '2000 PopulaÎtions'];
                $rows = [
                    ['New York City, NY', 8175000, 8008000],
                    ['Los Angeles, CA', 3792000, 3694000],
                    ['Chicago, IL', 2695000, 2896000],
                    ['Houston, TX', 2099000, 1953000],
                    ['Philadelphia, PA', 1526000, 1517000]
                ];


                echo ChartManager::setChartType('bar-chart')
                        ->setOptions($options)
                        ->setCols($cols)
                        ->setRows($rows)
                        ->render();
   ?>

</div>
</div>
</section>
</div> 




 
@endsection