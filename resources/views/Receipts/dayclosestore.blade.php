@extends('layout.mainlayout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
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

  .style1 {color:red;}
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>
  @include('flash-message')
<br> <br>
<section class="content">

  <div class="panel panel-body">

<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-success text-center" colspan="4"> <h3>........ Day account closed successfully ......... </h3> </td>
        </tr>

        <tr>
        <td> <label> Closing date </label></td>
        <td> {{ date('d-m-Y', strtotime($receiptdate)) }} </td>
        <td> <label> Opening balance </label></td>
        <td> {{ $openingbalcounter }} </td>
        </tr>

        <tr>
        <td> <label> System calculated day total </label></td>
        <td> {{ $daytotalcounter }} </td>
        <td> <label> Closing balance </label></td>
        <td> {{ $closingbalcounter }} </td>
        </tr>

        <tr>
        <td> <label> Manually entered day total </label></td>
        <td> {{ $entereddaytotal }} </td>
        <td> <label> Adjustments if any </label></td>
        <td> {{ $adjustmentcounter }} </td>
        </tr>

        <tr>
        <td colspan="1"> <label> Remarks </label></td>
        <td colspan="3"> {{ $remarkscounter }} </td>
        </tr>

      </table>

    </div>
    </div>
   <div class="row">
        <div class="col-lg-10 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{route('receiptCrudIndex')}}"> Back </a>
            </div>
        </div>
</div>
</div>
    @if ($errors->any())
    <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

 </section>
  @endsection
