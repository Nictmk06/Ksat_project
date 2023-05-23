
@extends('layout.mainlayout')

<link href="css/dash_material-design-iconic-font.min.css" rel="stylesheet" media="all">

<link href="css/dash_theme.css" rel="stylesheet" media="all">

@section('content')

<style type="text/css">

.table-responsive {
    max-width: 45%;
    min-height: 0.01%;
    max-height: 50%;
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
    <h7> Total Number of Applications / Applicants </h7>

</div>

<div class="row m-t-25">
<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c1">
<div class="overview-box clearfix">
<div align = "right" class="text">


 <?php if (count($applicationcnt) > 0)
      {  ?>

    <h2>
 {{ $applicationcnt[0]->applicationcnt }} -   {{ $applicationcnt[0]->applicantcnt }} </h2>

<span> <h4> {{ $applicationcnt[0]->appltypedesc }} - Applicants </h4> </span>

<?php  }
 else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>


</div>
</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c2">
<div class="overview__inner">
<div class="overview-box clearfix">

<div align = "right" class="text">


 <?php if (count($applicationcnt) > 1)
      {  ?>

    <h2>
 {{ $applicationcnt[1]->applicationcnt }} -   {{ $applicationcnt[1]->applicantcnt }} </h2>

<span> <h4> {{ $applicationcnt[1]->appltypedesc }} - Applicants </h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>

</div>
</div>

</div>
</div>
</div>

<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c3">
<div class="overview__inner">
<div class="overview-box clearfix">

<div align = "right" class="text">

 <?php if (count($applicationcnt) > 2)
      {  ?>

    <h2>
 {{ $applicationcnt[2]->applicationcnt }} -   {{ $applicationcnt[2]->applicantcnt }} </h2>

<span> <h4> {{ $applicationcnt[2]->appltypedesc }} - Applicants </h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>
</div>
</div>

</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c4">
<div class="overview__inner">
<div class="overview-box clearfix">
 <div align = "right" class="text">

 <?php if (count($applicationcnt) > 3)
      {  ?>

    <h2>
 {{ $applicationcnt[3]->applicationcnt }} -   {{ $applicationcnt[3]->applicantcnt }} </h2>

<span> <h4> {{ $applicationcnt[3]->appltypedesc }} - Applicants </h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>

</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>


<!--pending  -->

<div class="row">
  <div class="panel panel-primary">
  <br>
<div class="panel panel-heading">
    <h7> Total Number of Pending Applications / Applicants </h7>
</div>

<div class="row m-t-25">

<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c1">
<div class="overview-box clearfix">
<div align = "right" class="text">

 <?php if (count($pendingapplcnt) > 0)
      {  ?>

    <h2>
 {{ $pendingapplcnt[0]->applicationcnt }} -   {{ $pendingapplcnt[0]->applicantcnt }} </h2>

<span> <h4> {{ $pendingapplcnt[0]->appltypedesc }} - Applicants </h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>

</div>
</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c2">
<div class="overview__inner">
<div class="overview-box clearfix">

<div align = "right" class="text">
 <?php if (count($pendingapplcnt) > 1)
      {  ?>

    <h2>
 {{ $pendingapplcnt[1]->applicationcnt }} -   {{ $pendingapplcnt[1]->applicantcnt }} </h2>

<span> <h4> {{ $pendingapplcnt[1]->appltypedesc }} - Applicants </h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>
</div>
</div>

</div>
</div>
</div>

<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c3">
<div class="overview__inner">
<div class="overview-box clearfix">

<div align = "right" class="text">
 <?php if (count($pendingapplcnt) > 2)
      {  ?>

    <h2>
 {{ $pendingapplcnt[2]->applicationcnt }} -   {{ $pendingapplcnt[2]->applicantcnt }} </h2>

<span> <h4> {{ $pendingapplcnt[2]->appltypedesc }} - Applicants </h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>

</div>
</div>

</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c4">
<div class="overview__inner">
<div class="overview-box clearfix">
 <div align = "right" class="text">

 <?php if (count($pendingapplcnt) > 3)
      {  ?>

    <h2>
 {{ $pendingapplcnt[3]->applicationcnt }} -   {{ $pendingapplcnt[3]->applicantcnt }} </h2>

<span> <h4> {{ $pendingapplcnt[3]->appltypedesc }} - Applicants </h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>
</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>


<!--disposedapplication  -->
<div class="row">
  <div class="panel panel-primary">
  <br>
<div class="panel panel-heading">
    <h7> Total Number of Disposed Applications / Applicants (Including Legacy Applications) </h7>
</div>

<div class="row m-t-25">

<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c1">
<div class="overview-box clearfix">
<div align = "right" class="text">

 <?php if (count($disposedapplcnt) > 0)
      {  ?>

    <h2>{{ $disposedapplcnt[0]->applicationcnt }} -   {{ $disposedapplcnt[0]->applicantcnt }} </h2>

      <span> <h4> {{ $disposedapplcnt[0]->appltypedesc }} - Applicants</h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>

</div>
</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c2">
<div class="overview__inner">
<div class="overview-box clearfix">

<div align = "right" class="text">
 <?php if (count($disposedapplcnt) > 1)
      {  ?>

        <h2>{{ $disposedapplcnt[1]->applicationcnt }} -   {{ $disposedapplcnt[1]->applicantcnt }} </h2>

          <span> <h4> {{ $disposedapplcnt[1]->appltypedesc }} - Applicants</h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>
</div>
</div>

</div>
</div>
</div>

<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c3">
<div class="overview__inner">
<div class="overview-box clearfix">

<div align = "right" class="text">
 <?php if (count($disposedapplcnt) > 2)
      {  ?>

        <h2>{{ $disposedapplcnt[2]->applicationcnt }} -   {{ $disposedapplcnt[2]->applicantcnt }} </h2>

          <span> <h4> {{ $disposedapplcnt[2]->appltypedesc }} - Applicants</h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>

</div>
</div>

</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c4">
<div class="overview__inner">
<div class="overview-box clearfix">
 <div align = "right" class="text">

 <?php if (count($disposedapplcnt) > 3)
      {  ?>

        <h2>{{ $disposedapplcnt[3]->applicationcnt }} -   {{ $disposedapplcnt[3]->applicantcnt }} </h2>

          <span> <h4> {{ $disposedapplcnt[3]->appltypedesc }} - Applicants</h4> </span>

<?php  }  else {  ?>

  <h2> - </h2>
<span> <h4> - </h4> </span>

<?php  }   ?>
</div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>












<!--  -->

<div class="row">
  <div class="panel panel-primary">
  <br>
<div class="panel panel-heading">
    <h7> Application entered status </h7>
</div>

<div class="col-lg-50">

<div class="table-responsive ">
<table class="table table-borderless table-striped table-earning " >
<thead>
<tr>
<th>User </th>
<th>Entered On</th>
<th>No. of Applications</th>
</tr>
</thead>
     <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                    @foreach($userAppDet as $userapp)

                  <tr>

                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$userapp->username}}"
                     onclick=showapplication({{$i}})> {{$userapp->username}}   </a>
                     </td>
                    <?php $i = $i + 1; if($userapp->entered_on==null){?>

                     <td>---</td>
                    <?php }
                    else
          {
            ?>
                      <td>{{date('d-m-Y',strtotime($userapp->entered_on))}}</td>
                    <?php
                  }
                  ?>
                    <td class="text-right">{{$userapp->application_entered}}</td>

                  </tr>
                 @endforeach
                  </tbody>

</table>
</div>
</div>

 <br>

                      <div class="table-responsive1 ">
                 <table id="appltab" class="table table-borderless table-striped table-earning  ">
                  <thead>

                  </thead>

                  <tbody>

                  </tbody>
                </table>
  </div>

</div>
</div>

</div>
</div>

</div>
</div>

<script type="text/javascript">

function showapplication(i)
{

  var a = '#userclick'+i;
  var $user = $(a).attr('data-value');

      $.ajax({

        type: 'post',
        url: "getApplicationbyuser",
        dataType:"JSON",
        data: {"_token": $('#token').val(),$user},
        success: function (json) {

                   $('#appltab').find('tbody tr').remove();

    var row = $('<tr>');

    row.append('<td colspan="4" style="font-weight:bold">' + 'Applications entered by the user : '+ $user + '</td>');
    row.appendTo('#appltab');

row = $('<tr background = "au-btn--blue">');

  row.append('<td>' +' Application Number '+  '</td>');
  row.append('<td>' + 'Entered On' + '</td>');
  row.append('<td>' + 'Total Applicants ' + '</td> </tr>');
row.append('<td>' + 'Registered on ' + '</td> </tr>');


row.appendTo('#appltab');


  $.each(json, function(index, obj) {
 row = $('<tr>');


  row.append('<td>' +obj.applicationid+  '</td>');
  row.append('<td>' +obj.createdon + '</td>');
  row.append('<td>' +obj.applicantcount + '</td> </tr>');
  row.append('<td>' +obj.registerdate + '</td> </tr>');

row.appendTo('#appltab');



  });



                   }
});


  // console.log($("#userclick").value());
}
 </script>
@endsection
