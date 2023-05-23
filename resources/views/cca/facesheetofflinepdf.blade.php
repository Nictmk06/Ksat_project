<!DOCTYPE html>
<html>
<head >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <style>

    table,tr,th,td{
    border-color: black;
     border:1px solid black;

        line-height: 35px;
    font-weight: bold;

    }
    p{

    }
  </style>

</head>

<body>
              <caption  ><font style="font-size: 25px;font-weight: bold;" > {{$ename}}</font>
             @if($ccadetails[0]->tran_time !=null)
              <font style="font-size: 20px; line-height: normal;">Online Certified Copy - Facing Sheet</font>
             @else
              <font style="font-size: 20px; line-height: normal;">Certified Copy - Facing Sheet</font>
             @endif
             </caption>
             <br/>
        @if($ccadetails[0]->documenttype=='1')
       <table id="myTable4" class="table table-bordered table-condensed" >
       @else
       <table id="myTable4" class="table table-bordered table-condensed" style="width:100%;">
       @endif

 <?php
//dd($ccadetails);
$count = count($ccadetails);
//dd($count);
for ($i = 0;$i < $count;$i++)
{
    $ccaapplicationno = $ccadetails[0]->ccaapplicationno;
    $caapplicationdate1 = $ccadetails[0]->caapplicationdate1;
    $caapplicantname = $ccadetails[0]->caapplicantname;
    $applicationo = $ccadetails[0]->applicationo;
    $documentname = $ccadetails[0]->documentname;
    $orderdate1 = $ccadetails[0]->orderdate1;
    $tran_time = $ccadetails[0]->tran_time;
    $receiptdate=$ccadetails[0]->receiptdate;
    $receiptno = $ccadetails[0]->receiptno;
    $receiptamount = $ccadetails[0]->receiptamount;
    $currentdate = $ccadetails[0]->currentdate;

    if ($ccadetails[0]->connectedapplication == 'No Connected or main applicationid')
    {
        $connectedapplication = 'No Connected Applications';
    }
    elseif (strpos($ccadetails[0]->connectedapplication, 'Connected Application ID') !== false)
    {
        $connectedapplication[$i] = substr($ccadetails[$i]->connectedapplication, 24);
    }
    elseif (strpos($ccadetails[0]->connectedapplication, 'Main Application ID') !== false)
    {
        $connectedapplication = $mainapplicationid;
    }
}
?>


              <tr>
               <td >Certified Copy Application No.</td><td ><?php echo $ccaapplicationno; ?></td></tr>

              <tr><td   >Date of  Application </td> <td><?php echo $caapplicationdate1; ?>  </td></tr>
               <tr><td   >Name of the Applicant </td> <td><?php echo $caapplicantname; ?> </td></tr>
                <tr><td   >Application Number  </td> <td><?php echo $applicationo; ?>  </td></tr>
              <?php
if ($ccadetails[0]->connectedapplication == 'No Connected or main applicationid')
{

}
elseif (strpos($ccadetails[0]->connectedapplication, 'Connected Application ID') !== false)
{
    echo "<tr><td> Connected Applications </td><td>";
}
elseif (strpos($ccadetails[0]->connectedapplication, 'Main Application ID') !== false)
{

    echo "<tr><td>Main Application number<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↑<br>
                         Connected applications</td><td>";
    /*if($other_connectedid !=''){
                   echo  "<tr><td> Other Connected Applications,Connected to $connectedapplication   </td><td>";
                    }
                    else{
                     echo  "<tr><td> Other Connected Applications,Connected to $connectedapplication   </td><td>"; }*/
}

?>

                <?php
//dd($connectedapplication);
//$count=count($connectedapplication);
if (!is_countable($connectedapplication))
{
    $count = strlen($connectedapplication);
    for ($j = 0;$j < $count;$j++)
    {
        echo $connectedapplication[$j];

    }
    if (strpos($ccadetails[0]->connectedapplication, 'Main Application ID') !== false)
    {
        if ($other_connectedid != '')
        {
            echo "\r\n<br>".PHP_EOL;
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↑\r\n<br>".PHP_EOL;
            echo "Connected applications :\r\n<br>".PHP_EOL;
            $count1 = count($other_connectedid);

            for ($k = 0;$k < $count1;$k++)
            {
                echo $other_connectedid[$k] ."\r\n<br>".PHP_EOL;

            }
        }
        else
        {
            echo "\r\n</br>".PHP_EOL;
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↑\r\n<br>".PHP_EOL;
            echo "Connected applications : Nil".PHP_EOL;
        }
    }
} // dd($count);
else
{
    //dd($connectedapplication);
    $count = count($connectedapplication);
    for ($j = 0;$j < $count;$j++)
    {
        echo $connectedapplication[$j]."<br>".PHP_EOL;

    }
}

?> </td></tr>
                <tr><td> Document  Requested</td> <td><?php echo $documentname; ?> </td></tr>
                 <tr><td>Date of Order  </td> <td><?php echo $orderdate1; ?> </td></tr>
                  @if($tran_time==null)
                   <tr><td>Date of  Payment</td> <td><?php echo date('d-m-Y',strtotime($receiptdate)); ?>  </td></tr>
                  @else
                    <tr><td>Date of  Payment</td> <td><?php echo $tran_time; ?>  </td></tr>
                  @endif

                  <tr><td> Receipt No </td> <td > <?php echo $receiptno; ?>  </td></tr>
                  <tr><td> Receipt Amount </td> <td><?php echo $receiptamount; ?>  </td></tr>


<br/>
@if($ccadetails[0]->documenttype=='1')
<tr>
   <td colspan="2" style ="text-align:center;"><p class="text-center" >
    <img  style="text-align: center" src="data:image/svg+xml;base64,{{$qrcode}}"><div>[scan to view the document]</div>
</p></td></tr>
<tr style="line-height: 0px"><td colspan="2" style ="text-align:left; font-weight: normal; padding:0 ;line-height: normal;" >
  The content of the judgment can be verified from the website of Karnataka State Administrative Tribunal(<a href="https://ksat.karnataka.gov.in/judgmentsearch" target="_blank">https://ksat.karnataka.gov.in/judgmentsearch)</a></td></tr>
@elseif($ccadetails[0]->documenttype=='4')
<tr>
  <tr style="line-height: 0px"><td colspan="2" style ="text-align:left; font-weight: normal; padding:0 ;line-height: normal;" >
  <center><b>Judgment is not uploaded yet</b></center> </td></tr>
</tr>
@endif



          </table>

          <font> Facing Sheet generated on : <?php  echo $currentdate; ?> </font><br/><br/>





</body>

</html>
