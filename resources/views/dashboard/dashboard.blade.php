@extends('layout.mainlayout')



<link href="css/dash_material-design-iconic-font.min.css" rel="stylesheet" media="all">

<link href="css/dash_theme.css" rel="stylesheet" media="all">

@section('content')

<style type="text/css">

.table-responsive {
    max-width: 40%;
    min-height: 0.01%;
    max-height: 50%;
    overflow-x: auto;
}
</style>

<div class="content-wrapper">
<div class="page-wrapper">
<div class="main-content">
<div class="section__content section__content--p30">
<div class="container-fluid">


<div class="row">
<div class="col-md-12">
<div class="overview-wrap">

<button class="au-btn au-btn-icon au-btn--blue">
<i class="zmdi zmdi-plus"></i>KSAT</button>
</div>
</div>
</div>

<div class="row m-t-25">
<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c1">
<div class="overview__inner">
<div class="overview-box clearfix">
<div class="icon">
<i class="zmdi zmdi-account-o"></i>
</div>
<div class="text">
<h2>10368</h2>
<span>members online</span>
</div>
</div>

</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c2">
<div class="overview__inner">
<div class="overview-box clearfix">
<div class="icon">
<i class="zmdi zmdi-account-o"></i>
</div>

<div class="text">
<h2>388,688</h2>
<span>items solid</span>
</div>
</div>

</div>
</div>
</div>

<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c3">
<div class="overview__inner">
<div class="overview-box clearfix">
<div class="icon">
<i class="zmdi zmdi-calendar-note"></i>
</div>
<div class="text">
<h2>1,086</h2>
<span>this week</span>
</div>
</div>

</div>
</div>
</div>


<div class="col-sm-6 col-lg-3">
<div class="overview-item overview-item--c4">
<div class="overview__inner">
<div class="overview-box clearfix">
 <div class="icon">
<i class="zmdi zmdi-money"></i>
</div>
<div class="text">
<h2>$1,060,386</h2>
<span>total earnings</span>
</div>
</div>

</div>
</div>
</div>

</div>


<div class="row">

<h2 >Applications entered status</h2>
<div class="table-responsive m-b-40 ">
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

    row.append('<td style="font-weight:bold">' + 'Applications entered by the user : '+ $user + '</td>');
    row.appendTo('#appltab');

row = $('<tr>');

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
