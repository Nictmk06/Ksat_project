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

  body {
margin-bottom: 200%;
}

/* Box styles */
.myBox {
border: none;
padding: 5px;
font: 24px/36px sans-serif;
width: 1000px;
height: 700px;
overflow: scroll;
}

/* Scrollbar styles */
::-webkit-scrollbar {
width: 12px;
height: 12px;
}

::-webkit-scrollbar-track {
border: 1px solid orange;
border-radius: 10px;
}

::-webkit-scrollbar-thumb {
background: maroon;
border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
background: green;
}

  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

<section class="content">

<div class="row">
        <div class="col-lg-10 margin-tb">
            <div class="bg-primary text-center">
                <h2> Receipt Reports </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{route('receiptCrudRepDailyGetDt')}}"> Daily Receipts Detail </a>
                <a class="btn btn-info" href="{{route('receiptCrudRepSummGetDt')}}"> Closed Receipt Summary </a>
            </div>
        </div>
</div>

</section>
@endsection
