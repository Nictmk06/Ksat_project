
@extends('layout.mainlayout')

<link href="css/dash_material-design-iconic-font.min.css" rel="stylesheet" media="all">

<link href="css/dash_theme.css" rel="stylesheet" media="all">

@section('content')

<style type="text/css">

.table-responsive {
    max-width: 75%;
    min-height: 0.01%;
    max-height: 60%;
    overflow-x: auto;
}
.table-responsive1 {
    max-width: 75%;
    max-height: 60%;
    overflow-x: auto;
    overflow-y: auto;

}
</style>

<div class="content-wrapper">
<div class="page-wrapper">

<div class="section__content section__content--p30">
<div class="container-fluid">


<div class="row">
  <div class="panel panel-primary">
  <br>
<div class="panel panel-heading">
    <h7> Dashboard Charts </h7>

</div>



<div class="table-responsive ">
<table class="table table-borderless table-striped table-earning " >
<thead>
<tr>
<th>Sr. No. </th>
<th>Description</th>
<th> </th>
</tr>
</thead>
   <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
     <tbody>
                    <tr>
                    <td>
                     1
                     </td>
                     <td>
                     <a href="piechart"> No. of Pending Applications  </a>
                   </td>
                   <td>
                   </td> </tr>

         <tr>
                    <td>
                     2
                     </td>
                     <td>
                     <a href="barchart"> Top 5 Highest Number of  registered applications (Departments) for the last 3 years </a>
                   </td>
                   <td>
                   </td> </tr>
  <tr>
                    <td>
                     3
                     </td>
                     <td>
                     <a href="stackedbarchart"> Pending applications of top 5 stages for the last 5 years   </a>
                   </td>
                   <td>
                   </td> </tr>

 <tr>
                    <td>
                     4
                     </td>
                     <td>
                     <a href="donutchart"> Applications registered month wise for a year  </a>
                   </td>
                   <td>
                   </td> </tr>    
                    <tr>
                    <td>
                     5
                     </td>
                     <td>
                     <a href="donutchart1"> Applications registered (Donut Chart)  </a>
                   </td>
                   <td>
                   </td> </tr>    
                  </tbody>

</table>
</div>




</div>
</div>

</div>
</div>
</div>
</div>

@endsection
