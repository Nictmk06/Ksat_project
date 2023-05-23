<html>
<head>


  <script src="js/jquery-3.4.1.js"></script>


{{-- @extends('layout.mainlayout')
@section('content')  --}}
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
<script>
function printDiv(divName)
{
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

  </head>

  <body>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  $receiptno           = $receipts[0]->receiptno;
  $receiptdate         = date('d-m-Y', strtotime($receipts[0]->receiptdate));
  $modeofpayment       = trim($receipts[0]->modeofpayment);
  $ddchqno             = $receipts[0]->ddchqno;
  $ddchqdate           = date('d-m-Y', strtotime($receipts[0]->ddchqdate));
  $bankcode            = $receipts[0]->bankcode;
  $bankname            = $receipts[0]->bankname;
  $amount              = $receipts[0]->amount;
  $receiptsrno         = $receipts[0]->receiptsrno;
  $applicantadvocate   = $receipts[0]->applicantadvocate;
  $feepurposecode      = $receipts[0]->feepurposecode;
  $purposename         = $receipts[0]->purposename;
  $titlename           = $receipts[0]->titlename;
  $name                = $receipts[0]->name;
  $otherdetails        = $receipts[0]->otherdetails;
  $applicationid       = $receipts[0]->applicationid;
  $receiptuseddate     = $receipts[0]->receiptuseddate;
  $titlename           = $receipts[0]->titlename;
  $createdon           = date('d-m-Y h:i:s A', strtotime($receipts[0]->createdon));
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
   return " " . $result; //. ' ಮತ್ತು '. $points . " ಪೈಸೆ";
  }

  ?>

<br> <br>
<div class="container">

<div class="row">
<div class="col-md-11 text-center">

    <div id="printableArea">
    <div class="border border-primary text-center">
    <table align="center" class="no-border" width="100%" >
        <tr>
        <td  class="bg-info text-center" style="text-align: center;" colspan="4">  <strong> ಕ.ಆ.ಸ. ಪ್ರಪತ್ರ ೧  (ಅನುಚ್ಛೇದ  ೬)  </strong>   </td>
        </tr>

        <tr>
        <td  class="bg-primary text-center"  style="text-align: center;" colspan="4"> <strong> ಹಣ ಸ್ವೀಕರಿಸಿದ ಬಗ್ಗೆ ಸ್ವೀಕೃತಿ ಪತ್ರ </strong>  </td>
        </tr>
        <br><br>
        <tr>
        <td align="left" colspan="2">  ಪಾವತಿ ಕ್ರ.ಸಂ. : {{ $receiptno }} </td>
        <td align="right" colspan="2">  <u> {{ session()->get('establishment')->report_title}}  </u> </td>
        </tr>
        <tr>
        <td align="right" colspan="4"> <u> {{ session()->get('establishment')->report_title1 }} ಇವರ ಕಛೇರಿ</u> </td>
        </tr>
        <tr>
        <td align="right" colspan="4"> ಸ್ಥಳ : <u> {{ session()->get('establishment')->establishaddress }} </u> </td>
        </tr>
        <tr>
        <td align="right" colspan="4"> ದಿನಾಂಕ : <u> {{ $createdon }} </u> <br><br> </td>
        </tr>

        <tr>
        <td align="left" colspan="4"> <u>  {{ $purposename  }} -  {{ $otherdetails}} ({{$applicationid}}) </u> ಇದಕ್ಕಾಗಿ <u> <b> Rs. {{ $amount }} </b> ({{ amount_to_words($amount) }}) </u> ರೂಪಾಯಿಗಳನ್ನು
                                         <u> <b> {{ $titlename .' '. $name }} </b> </u>
                                      ಇವರಿಂದ ಸ್ವೀಕರಿಸಲಾಗಿದೆ ಮತ್ತು ನಗದು ಪುಸ್ತಕದ ............... ಸಂಖ್ಯೆ ಪುಟದಲ್ಲಿ ......................ನೇ ಬಾಬಿನಲ್ಲಿ <u> {{ $receiptdate }} </u>
                                      ರಂದು ಜಮೆ ಮಾಡಲಾಗಿದೆ.
                                      <br><br>

        </td>
        </tr>

        <tr>
        <td align="center" colspan="4"> <?php
                                      if ( $modeofpayment === 'D')
                                      { ?>
                                         ಡಿಡಿ ಸಂ : <b> <?=$ddchqno;?> </b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                         ದಿನಾಂಕ : <b> <?=$ddchqdate;?> </b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                         ಬ್ಯಾಂಕ್  : <b> <?=$bankname;?>  </b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                      <?php
                                      }
                                      else
                                      {?>
                                         ಡಿಡಿ ಸಂ:____________________________
                                         ದಿನಾಂಕ:_________________________
                                         ಬ್ಯಾಂಕ್:_______________________________________
                                      <?php
                                      } ?>
                                      <br><br><br><br>

        </td>
        </tr>

        <tr>
        <td align="left" colspan="2"> ನಗದು ಗುಮಾಸ್ತ </td>
        <td align="right" colspan="2"> ಕಛೇರಿಯ ಮುಖ್ಯಾಧಿಕಾರಿ </td>
        </tr>
        </table>

        </div>
        </div>  {{-- Printable area end --}}

        <br><br>
        <div align="center">
        <button class="btn btn-success text-center" onclick="printDiv('printableArea')"> Print Receipt </button>
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

</div>  {{-- class contaner --}}

</body>
</html>
