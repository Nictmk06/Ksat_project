<!DOCTYPE html>
<html>
 <head>
  <title></title>


   <script type="text/javascript" src="js/chartloader.js"></script>

  <style type="text/css">
   .box{
    width:800px;
    margin:0 auto;
   }
  </style>
  <script type="text/javascript">
   var analytics = <?php echo $casetype; ?>
   
   google.charts.load('current', {'packages':['corechart']});

   google.charts.setOnLoadCallback(drawChart);

   function drawChart()
   {
    var data = google.visualization.arrayToDataTable(analytics);
    var options = {
      title: 'Total Registered Applications ',
      pieHole: 0.4,
    };
  //  var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
    
    chart.draw(data, options);
   }
  </script>
 </head>
 <body>
  <br />
  <div class="container">
   <h3 align="center"> </h3><br />
   
   <div class="panel panel-default">
    <div class="panel-heading">
     
    </div>
    <div class="panel-body" align="center">
     <div id="donutchart" style="width:750px; height:450px;">

     </div>
    </div>
   </div>
   
  </div>
 </body>
</html>