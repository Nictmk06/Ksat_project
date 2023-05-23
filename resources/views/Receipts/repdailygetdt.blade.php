
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

<form action="receiptCrudRepDaily" name="showreceipts">
<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Receipt Daily Report </h4> </td>
        </tr>

        <tr>
        <td> <label for="fromdate"> Date From </label><span class="mandatory">*</span></td>
        <td> <input type="text" name="fromdate" class="form-control pull-right datepicker" id="fromdate" autocomplete="off" placeholder="dd-mm-yyyy" required> </td>
        <td> <label for="todate"> Date To </label><span class="mandatory">*</span></td>
        <td> <input type="text" name="todate" class="form-control pull-right datepicker" id="todate" autocomplete="off"  placeholder="dd-mm-yyyy" required> </td>
        </td>
        </tr>

        <tr>
        <td colspan="2">  <label for="HeadofAccounts"> Head of Accounts </label> </td>
        <td colspan="2">
           <select class="form-control" name="paymentheads" id="paymentheads" style="height:34px">
           <option value="" class="style1" > Select Head of Accounts </option>
            @foreach($paymentheads as $paymentheads)
            <option value="{{$paymentheads->paymentcode}}">{{$paymentheads->headofaccount}}</option>
            @endforeach
           </select>
        </td>
        </tr>

    <!--     <tr>
        <td colspan="2">  <label for="feepurpose"> Fee purpose  </label> </td>
        <td colspan="2">
           <select class="form-control" name="feepurpose" id="feepurpose" style="height:34px">
           <option value="-1" class="style1" > Select All Purposes </option>
            @foreach($feepurposes as $feepurpose)
            <option value="{{$feepurpose->purposecode}}">{{$feepurpose->purposename}}</option>
            @endforeach
           </select>
        </td>
        </tr> -->

        <tr>
        <td colspan="2"> <label for="zeroamount"> Display </label></td>
        <td colspan="2"> <input type="radio" name="zeroamount" value="all" checked> All receipts
                         <input type="radio" name="zeroamount" value="zero"> Zero fee receipts only
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


</section>

 @endsection
