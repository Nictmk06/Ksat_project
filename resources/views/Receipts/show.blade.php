
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

<script>
$(document).ready(function () {
$('#dtVerticalScrollExample').DataTable({
"scrollY": "200px",
"scrollCollapse": true,
});
$('.dataTables_length').addClass('bs-select');
});
</script>

  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

<div class="container">

<div class="row">
        <div class="col-lg-11 margin-tb">
            <div class="bg-primary text-center">
                <h2> Receipt View </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="receiptCrudDtShow"> Back </a>
            </div>

        </div>
</div>
  <br>
  <div class="row">
  <div class="col-lg-11 text-center myBox">

  <p class="text-info text-left"> <small> Type any text in the below table to filter the list (Ex. Applicant name): </small> </p>
  <input class="form-control" id="searchInput" type="text" placeholder="Search..">
  <br>
  <table class="table table-bordered table-striped">
        <thead>
            <tr>
            <th >Sr. No.</th>
            <th>Receipt Date</th>
            <th>Receipt No.</th>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Applicant Type </th>
            <th>Applicant Name</th>
            <th>Attached to Application.?</th>
            </tr>
        </thead>
        <?php
          $i = 0;
        ?>
        @foreach ($receiptshow as $receipt)
        <?php
        if ($receipt->applicationid == null or strlen(trim($receipt->applicationid)) == 0)
        {
            $attach_yn = "No";
        }
        else
        {
            $attach_yn = "Yes";
        }

        if ($receipt->applicantadvocate == 'A')
        {
            $appliedby = "Advocate";
        }
        else
        {
            $appliedby = "others";
        }

        if ($receipt->modeofpayment == 'D')
        {
            $paymode = "DD";
        }
        else
        {
            $paymode = "Cash";
        }

        ?>
        <tbody id="receiptTable">
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ date('d-m-Y', strtotime($receipt->receiptdate)) }}</td>
            <td>{{ $receipt->receiptno }}</td>
            <td>{{ $receipt->amount }}</td>
            <td>{{ $paymode }}</td>
            <td>{{ $appliedby }}</td>
            <td>{{ $receipt->name }}</td>
            <td>{{ $attach_yn }}</td>
         </tr>
         @endforeach
         </tbody>
        </table>

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
{{--
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif  --}}

</div>  {{-- class contaner --}}

@endsection
