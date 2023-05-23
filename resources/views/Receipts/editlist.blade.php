  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
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
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>
  @include('flash-message')

<div class="container">

<div class="row">
        <div class="col-lg-10 margin-tb">
            <div class="bg-primary text-center">
                <h2> Edit Receipt <small style="color:white"> (Receipts after last account closed date) </small> </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{route('receiptCrudIndex')}}"> Back </a>
            </div>
        </div>
</div>

  <div class="row">
  <div class="col-md-10 text-center">

  <p class="text-info text-left"> <small> Type any text in the below table to filter the list (Ex. Applicant name): </small> </p>
  <input class="form-control" id="searchInput" type="text" placeholder="Search..">
  <br>
  <table class="table table-bordered table-striped">
        <thead>
            <tr>
            <th>Sr. No.</th>
            <th>Receipt Date</th>
            <th>Receipt No.</th>
            <th>Amount</th>
			<th>Payment Mode</th>
            <th>Applicant Name</th>
            <th>Attached to Application.?</th>
            <th colspan="2" align="center">Action</th>
            </tr>
        </thead>
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
        <tbody id="receiptTable">
          <tr>
            <td>{{ ++$i }}</td>
            <td>{{ date('d-m-Y', strtotime($receipt->receiptdate)) }}</td>
            <td>{{ $receipt->receiptno }}</td>
            <td>{{ $receipt->amount }}</td>
           @if($receipt->modeofpayment == 'D')
					 <td>DD ({{$receipt->ddchqno}}, {{date('d-m-Y', strtotime($receipt->ddchqdate)) }}, {{$receipt->bankdesc}})</td>
			 @endif
			  @if($receipt->modeofpayment == 'C')
				<td>Cash</td>

			 @endif
			 <td>{{ $receipt->name }}</td>
            <td>{{ $attach_yn }}</td>
             @if($userlevel > 4)
            <td>
           
                <a class="btn btn-info" href="{{ route('receiptCrudEdit', ['rptno' => $receipt->receiptno]) }}">Edit</a>

            </td>
             @endif
            <td>
              <a class="btn btn-warning" href="{{ route('receiptCrudGen', ['rptno' => $receipt->receiptno]) }}" target="__blank">Generate Receipt</a>
            </td>
          </tr>
        </tbody>
         @endforeach
        </table>

    {{ $receipts->links() }}

    </div>
    </div>

    <script>
      $(document).ready(function(){
        $("#searchInput").on("keyup", function() {
         var value = $(this).val().toLowerCase();
         $("#receiptTable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
    </script>

    @if ($errors->any())
    <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

</div>  {{-- class contaner --}}

@endsection
