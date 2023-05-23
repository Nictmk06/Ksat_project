 <?php if (Session::has('username')) { ?> 
<!DOCTYPE html>
<html lang="en">
  <head>
    @include('layout.partials.head')
   
  <script type="text/javascript" src="js/chartloader.js"></script>
<script>
   
  google.charts.load('current', {packages: ['corechart']});
  google.charts.setOnLoadCallback(drawChart);
 
</script>
 
  
</script>

  </head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    	@include('layout.partials.nav')

      @include('layout.partials.sidebar')
     
         @yield('content') 
     
      {{--  @include('layout.partials.footer')  --}}
      
      @include('layout.partials.footer-scripts')
  </div>
  </body>
</html>
 <?php } else { ?> 
  <script>
// your "Imaginary javascript"
 window.location.href = '/login';

</script>
 <?php } ?> 