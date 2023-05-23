
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
<section class="content">

<form action="receiptCrudCloseStore" method="POST">
@csrf
 <div class="panel panel-body">
<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h5> Receipts - Day account closing </h5> </td>
        </tr>

        <tr>
        <td> <label for="fromdate"> Previous account closed date </label></td>
        <td> <input type="text" name="pdate" class="form-control bg-success" id="pdate"  value="{{ date('d-m-Y', strtotime($pclosedt)) }}" disabled>
             <input type="hidden" name="prev_close_date" id="prev_close_date"  value="{{ date('d-m-Y', strtotime($pclosedt)) }}" >
             <input type="hidden" name="prev_close_amt"  id="prev_close_amt"  value="{{ $pcloseamt }}" >
        </td>
        <td> <label for="todate"> Account closing date (Probably today's date) </label><span class="mandatory">*</span></td>
             <input type="hidden" name="today_close_date" id="today_close_date"  value="{{ date('d-m-Y', strtotime($accClosingDt)) }}" >

	   <td> <input type="text" name="today_close_date1" class="form-control pull-right " id="today_close_date1" autocomplete="off"  value="{{ date('d-m-Y' , strtotime($accClosingDt)) }}" disabled> </td>
        </td>
        </tr>

        <tr>
        <td> <label for="daycollection"> Day total collection (Rs.) </label></td>
        <td> <input type="number" name="daycollection" class="form-control" id="daycollection" value="0" size="12" maxlength="10"> </td>
        <td> <label for="feeadjust"> Any adjustment in fee amount (Rs.) </label></td>
        <td> <input type="number" name="feeadjust" class="form-control" id="feeadjust" value="0" size="12" maxlength="10"> </td>
        </tr>

        <tr>
        <td colspan="2"> <label for="closeremarks"> Closing remarks if any </label></td>
        <td colspan="2"> <input type="text" name="closeremarks" class="form-control" id="closeremarks" size="40" maxlength="150" placeholder="Remarks"> </td>
        </td>
        </tr>

        <tr>
        <td colspan="4">
        <div class="text-center">

                <button type="submit" class="btn btn-primary btnSearch  btn-md">Submit</button>
               <a class="btn btn-warning" href="{{route('receiptCrudIndex')}}"> Cancel </a>
        </div>

        </td>
        </tr>
    </table>

    </div>
    </div>
	</div>
    </form>
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
