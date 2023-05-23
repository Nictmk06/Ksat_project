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
font: 18px/20px sans-serif;
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
                <h2> Daily Fee-Receipt Generation </h2>
            </div>
            <div class="pull-right">
		<a class="btn btn-success" href="receiptCrudCreate"> New Receipt</a>               
		<a class="btn btn-success" href="receiptCrudCreateWtApp"> New Receipt With Case No</a>
                <a class="btn btn-info" href="receiptCrudEditlist"> Edit Receipt</a>
                <a class="btn btn-primary" href="receiptCrudDtShow"> View Receipts</a>
            </div>
        </div>
</div>

  <div class="row">
  <div class="col-lg-12 text-center myBox">

    <table  style="width:90%;" class="table table-responsive table-bordered table-striped">
        <tr>
        <td  class="bg-info text-center" colspan="7"> <h4> Today's Receipts </h4> </td>
        </tr>

        <tr>
            <th style="width:5%;">Sr. No.</th>
            <th style="width:12%;">Receipt Date</th>
            <th style="width:15%;">Receipt No.</th>
            <th style="width:12%;">Amount</th>
            <th style="width:15%;" >Payment Mode</th>
            <th style="width:15%;">Applicant Name</th>
            <th style="width:15%;">Attached to Application.?</th>
        </tr>
        <?php
          $i = 0;
        ?>
        @foreach ($receipts as $receipt)
        <?php
        if ($receipt->applicationid == null or strlen(trim($receipt->applicationid)) == 0)
        {
            $attach_yn = "No";
        }
        else
        {
            $attach_yn = "Yes";
        }
        ?>
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ date('d-m-Y', strtotime($receipt->receiptdate)) }}</td>
            <td>{{ $receipt->receiptno }}</td>
            <td>{{ $receipt->amount }}</td>
            @if($receipt->modeofpayment == 'D')
              <td>DD ({{$receipt->ddchqno}}, {{date('d-m-Y', strtotime($receipt->ddchqdate)) }},{{$receipt->bankdesc}})</td>
            @endif
              @if($receipt->modeofpayment == 'C')
              <td>Cash</td>
           @endif
            <td>{{ $receipt->name }}</td>
            <td>{{ $attach_yn }}</td>
         </tr>
         @endforeach
        </table>

    {{ $receipts->links() }}

    </div>
    </div>
    @if ($message = Session::get('success'))
       <?php
         list($mess, $rptno) = explode('-', $message);
       ?>
       <script>
         window.open("{{route('receiptCrudGen', ['rptno' => $rptno])}}", "receiptWindow", "height=550, width=1000, scrollbars=1, menubar=1, resizable=0, location=0");
       </script>
      {{--
      <div class="row">
        <div class="col-lg-10 margin-tb">
            <div class="text-center" style="font-size:18px">
               <a class="btn btn-primary" href="receiptCrudGen', ['rptno' => $rptno]"> Click here to generate receipt for {{ $rptno }}  </a>
            </div>
        </div>
      </div>  --}}
    @endif


 </section>
@endsection
