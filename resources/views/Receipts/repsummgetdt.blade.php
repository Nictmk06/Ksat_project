
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


<form action="receiptCrudRepSummary">
<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Closed receipt summary report </h4> </td>
        </tr>

        <tr>
        <td> <label for="fromdate"> Date From </label><span class="mandatory">*</span></td>
        <td> <input type="text" name="fromdate" class="form-control pull-right datepicker" id="fromdate" autocomplete="off" placeholder="dd-mm-yyyy" required> </td>
        <td> <label for="todate"> Date To </label><span class="mandatory">*</span></td>
        <td> <input type="text" name="todate" class="form-control pull-right datepicker" id="todate" autocomplete="off"  placeholder="dd-mm-yyyy" required> </td>
        </td>
        </tr>

        <tr>
        <td colspan="4">
        <div class="text-center">

                <button type="submit" class="btn btn-primary">Submit</button>
               <a class="btn btn-warning" href="{{route('receiptCrudReport')}}"> Cancel </a>
        </div>

        </td>
        </tr>
    </table>

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

</div>  {{-- class contaner --}}

</section>
 @endsection
