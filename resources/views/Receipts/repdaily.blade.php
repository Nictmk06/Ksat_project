
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


function printDiv(divName)
{
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
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
                <h2> Receipt Detail Report </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{route('receiptCrudReport')}}"> Back </a>
            </div>

        </div>
</div>
  <br>

  <div class="row">
  <div class="col-lg-12 text-center myBox">

  <p class="text-info text-left"> <small> Type any text in the below table to filter the list (Ex. Applicant name): </small> </p>
  <input class="form-control" id="searchInput" type="text" placeholder="Search..">
  <br>

    <div align="center">
        <button class="btn btn-success text-center" onclick="printDiv('printableArea')"> Print Receipt </button>
        </div>
 <div id="printableArea">
  <table class="table table-sm table-bordered table-striped">
        <thead>
          <tr>
             <br>
            {{ session()->get('establishfullname') }}

           <br>

          </tr>
            <tr>
            <th >Sr. No.</th>
            <th nowrap>Receipt Date</th>
            <th>Receipt No.</th>
            <th>Purpose</th>
            <th>Amount</th>
            <th>Payment Mode</th>
            <th>Type </th>
            <th>Name</th>
            <th>Other Details</th>
            </tr>

    <!--        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7 </th>
            <th>8</th>
            <th>9</th>
            </tr>
          -->
        </thead>
        <?php
          $i          = 0;
          $phcode     = -99;
          $amt_tot    = 0;
          $amt_subtot = 0;
          $head_count = 0;
          $amt_cash = 0;
          $amt_dd = 0;
         
 function amount_to_words($amt)
  {
    $number = $amt;
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'ಒಂದು', '2' => 'ಎರಡು',
     '3' => 'ಮೂರು', '4' => 'ನಾಲ್ಕು', '5' => 'ಐದು', '6' => 'ಆರು',
     '7' => 'ಎಳು', '8' => 'ಎಂಟು', '9' => 'ಒಂಭತ್ತು',
     '10' => 'ಹತ್ತು', '11' => 'ಹನ್ನೊಂದು', '12' => 'ಹನ್ನೆರಡು',
     '13' => 'ಹದಿಮೂರು', '14' => 'ಹದಿನಾಲ್ಕು',
     '15' => 'ಹದಿನೈದು', '16' => 'ಹದಿನಾರು', '17' => 'ಹದಿನೇಳು',
     '18' => 'ಹದಿನೆಂಟು', '19' =>'ಹತ್ತೊಂಭತ್ತು', '20' => 'ಇಪ್ಪತ್ತು',
     '30' => 'ಮುವತ್ತು', '40' => 'ನಲವತ್ತು', '50' => 'ಐವತ್ತು',
     '60' => 'ಅರವತ್ತು', '70' => 'ಎಪ್ಪತ್ತು',
     '80' => 'ಎಂಭತ್ತು', '90' => 'ತೊಂಭತ್ತು');
    $digits = array('', 'ನೂರು', 'ಸಾವಿರದ', 'ಲಕ್ಷ', 'ಕೋಟಿ');
    while ($i < $digits_1) {
      $divider = ($i == 2) ? 10 : 100;
      $number = floor($no % $divider);
      $no = floor($no / $divider);
      $i += ($divider == 10) ? 1 : 2;
      if ($number) {
         $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
         $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
         $str [] = ($number < 21) ? $words[$number] .
             " " . $digits[$counter] . $plural . " " . $hundred
             :
             $words[floor($number / 10) * 10]
             . " " . $words[$number % 10] . " "
             . $digits[$counter] . $plural . " " . $hundred;
      } else $str[] = null;
   }
   $str = array_reverse($str);
   $result = implode('', $str);
   $points = ($point) ?
     "." . $words[$point / 10] . " " .
           $words[$point = $point % 10] : 'ಶೂನ್ಯ';
   if ($result == '')
      $result = 'ಶೂನ್ಯ';
   return "ರೂಪಾಯಿ  " . $result . ' ಮತ್ತು '. $points . " ಪೈಸೆ";
  }

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
            $appliedby = "Others";
        }

        if ($receipt->modeofpayment == 'D')
        {
            $paymode = "DD ";
         //   <br> (".$receipt->ddchqno.", ".date('d-m-Y', strtotime($receipt->ddchqdate)).")";
            $amt_dd = $amt_dd + $receipt->amount;
        }
        else 
        {
            $paymode = "Cash";
            $amt_cash = $amt_cash + $receipt->amount;
        }
       
        
          
        
        ?>
        <tbody id="receiptTable">
        <?php
        if ($receipt->paymentcode != $phcode )
        {
          if ( $phcode != -99)
          {?>
           <tr> <td align="center" colspan="9" class="text-bold text-dark"> Above head total amount: {{ $amt_subtot }}  </td> </tr>
          <?php
          $amt_subtot = 0;
          }
          ?>
        <tr> <td align="center" colspan="9" class="text-bold text-light-blue bg-teal-gradient"> {{ $receipt->headofaccount }}  </td> </tr>
        <?php
        $phcode = $receipt->paymentcode;
        $head_count++;
        }
        ?>
         <tr>
            <td>{{ ++$i }}</td>
            <td>{{ date('d-m-Y', strtotime($receipt->receiptdate)) }}</td>
            <td>{{ $receipt->receiptno }}</td>
            <td>{{ $receipt->purposename }}
          <?php   if ($receipt->modeofpayment == 'D')
                      { $paymode1 = " (".$receipt->ddchqno.", ".date('d-m-Y', strtotime($receipt->ddchqdate)). ",". $receipt->bankname . ")"; echo('<br>');
                echo( $paymode1 ); } ?>
                </td>

            <td>{{ $receipt->amount }}</td>
            <td>{!! nl2br($paymode) !!}</td>
            <td>{{ $appliedby }}</td>
            <td>{{ $receipt->name }}</td>
        <!--    <td>{{ $attach_yn }}</td> -->
            <td>{{ $receipt->otherdetails }}</td>
         </tr>

         <?php

         $amt_subtot = $amt_subtot + $receipt->amount;
         $amt_tot    = $amt_tot + $receipt->amount;
         ?>
         @endforeach

         @if ($head_count > 1)
         <tr> <td align="center" colspan="9" class="text-bold text-dark"> Above head total amount: {{ $amt_subtot }}  </td> </tr>
         @endif
         <tr> <td align="center" colspan="9" class="text-bold text-maroon">  Total amount (Cash) : {{ $amt_cash }}  </td> </tr>
         <tr> <td align="center" colspan="9" class="text-bold text-maroon">  Total amount (DD) : {{ $amt_dd }}  </td> </tr>
   <!--         <tr> <td align="center" colspan="9" class="text-bold text-maroon">  Total amount (Online Payment) : {{ $amt_online }}  </td> </tr>
-->
         <tr> <td align="center" colspan="9" class="text-bold text-maroon">  Total amount : {{ $amt_tot }} ({{ amount_to_words($amt_tot) }}) </td> </tr>
         </tbody>
        </table>
             </div>   {{-- Printable area --}}
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
