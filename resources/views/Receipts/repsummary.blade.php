
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
font: 18px/24px sans-serif;
width: 1200px;
height: 800px;
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
function getBankName(bn)
{
    $.ajax({
   type : 'get',
   url : "receiptGetBank",
   dataType : "JSON",
   data : {bankcd:bn},
   success: function (json)
   {
     if(json.length > 0)
     {
       return json[0].bankdesc;
     }
     else
     {
       return "Bank not found..";
     }

    }
    });
}
</script>

  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

<div class="container">

<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="bg-primary text-center">
                <h2> Closed receipt summary </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{route('receiptCrudReport')}}"> Back </a>
            </div>

        </div>
</div>
  <br>
  <div class="row">
  <div class="col-lg-12 text-center myBox">

  <p class="text-info text-left"> <small> Type any text in the below table to filter the list </small> </p>
  <input class="form-control" id="searchInput" type="text" placeholder="Search..">
  <br>
  <table class="table table-sm table-bordered table-striped">
        <thead>
            <tr>
            <th >Sr. No.</th>
            <th nowrap>Receipt closed date</th>
            <th>Opening Balance</th>
            <th>Days total</th>
            <th>Closing Balance</th>
            </tr>

            <tr>
            <th class="text-center">1</th>
            <th class="text-center">2</th>
            <th class="text-center">3</th>
            <th class="text-center">4</th>
            <th class="text-center">5</th>
            </tr>
        </thead>
        <?php
          $i          = 0;
        ?>

        @foreach ($receiptshow as $receipt)

        <tbody id="receiptTable">
         <tr>
            <td>{{ ++$i }}</td>
            <td>{{ date('d-m-Y', strtotime($receipt->receiptdate)) }}</td>
            <td>{{ $receipt->openingbalcounter }}</td>
            <td>{{ $receipt->daytotalcounter}}</td>
            <td>{{ $receipt->closingbalcounter }}</td>
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
