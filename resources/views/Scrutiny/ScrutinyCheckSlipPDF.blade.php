
<html>
<head>
<style>
body {font-family: Times New Roman;
    font-size: 11pt;
}

table thead td { background-color: #FFFFFF;
    text-align: left; vertical-align:text-top;
 }
 tbody tr   { box-shadow: 0 1px 10px #000000; }
  tbody td   { vertical-align:text-top; }
 tbody { border-top: 30px solid transparent}
thead { display: table;
    width: 100%;
    margin-bottom: 10px; }
tfoot { display: table-row-group }
tr { page-break-inside: avoid }
td.lastrow {
    background-color: #FFFFFF;
    border: 1mm none #000000;
    border-bottom: 0.3mm solid #000000;
    border-left: 0.3mm solid #000000;
  border-right: 0.3mm solid #000000;
}
@page {
    margin-top: 2.00cm;
    margin-bottom: 2.54cm;
    margin-left: 2.175cm;
    margin-right: 1.175cm;
      footer: myfooter;
}
/*.page_break { page-break-before: always; }*/
</style>
</head>
<body>



<htmlpageheader name='otherpages' >
  <div style='text-align:center'>{PAGENO} </div>
 <table width='100%'><thead><tr><td align='center'><u><b> HEADING  </b></u></td></tr></thead></table>
 <p>&nbsp;</p>
</htmlpageheader>

<htmlpagefooter name='myfooter'>
<div style='border-top: 1px solid #000000; font-size: 1pt; text-align: center; padding-top: 0.5mm; '>

<table  width='100%'><thead>
  <tr>
  <td align='left'><font size='1'><b> </b></font></td>
  <td align='center'><font size='1'><b>Page {PAGENO} of {nb}</b></font></td>
  <td align='right'><font size='1'><b>Printed on {{ date("d-m-Y H:i:s") }} </b></font></td>


</tr></thead></table>

</div>
</htmlpagefooter>



<!-- <sethtmlpageheader name='firstpage' value='on' show-this-page='1' />
-->
<sethtmlpageheader name='otherpages' value='on' />
<sethtmlpagefooter name='myfooter' value='on' />



<table class='items'  cellspacing="5"  border='0' width='100%' style='font-size: 12.5pt;'>

  <tr>
   <th  colspan='3'  style='font-size: 14pt;' align='center'> {{ $title[0]->establishfullname }} <br>
     </th>
   </tr>
   <tr>
    <td colspan='3'  style='font-size:12pt;' align='center'>
     <b> CHECKSLIP  </b>
    </td>
  </tr>


<tr>
<td colspan='3' style='font-size: 14pt;' align='center'>
        {{$applicantDetails->nametitle}} {{$applicantDetails->applicantname}}
<br>
</td></tr>
<tr>
<td colspan='3' style='font-size: 14pt;' align='center'>
               Vs.
<br>
</td></tr>
<tr>
<td colspan='3' style='font-size: 14pt;' align='center'>
{{$respondantDetails->respondname}}
</td>
<tr>
  <td colspan='3' style='font-size: 14pt;' >
Subject matter (in brief) - {{$applSubject}}
   <br>    <br>
</td>
<tr>


</tr>
<tr>
  <td colspan="3"> Application No. <b> {{$applicantDetails->applicationid}}</b>
  </td>
</tr>
<tr>

 <td>
                   <?php $i = 1; ?>
                    @foreach($scrutinydetails as $scrutinydetails)
                    @if($scrutinydetails->observation == '')
                    <tr class="item">
                       <td style='width:5%;' valign="top">{{ $i++ }}.
                       </td>
                       <td style='width:55%;padding:right 15px;' align='justify'>{{$scrutinydetails->chklistdesc}}
                       </td>
                       <td style='width:40%;' valign="top">
                          {{$scrutinydetails->remarks}}
                         </td>

                       </tr>
                       @endif
                 @endforeach
               </td>
             </tr>

               </br>

               </tr>
             </tr>
         </table>
          <?php
          //dd($getScrutinyDetailsForExtraQuestions);

            if(sizeof($getScrutinyDetailsForExtraQuestions) > 0)
          echo "<h3>" .' Objection/Objections'. "</h3>";
          else {
            echo "";
          }
           ?>
         <table class='items'  cellspacing="5"  border='0' width='100%' style='font-size: 12.5pt;'>
     <?php $j = 1; ?>
    @foreach($getScrutinyDetailsForExtraQuestions as $getScrutinyDetailsForExtraQuestions)
         <tr class="item">
            <td style='width:5%;' valign="top">{{ $j++ }}.
          </td>
          <td style='width:55%;padding:right 15px;' align='justify'>{{$getScrutinyDetailsForExtraQuestions->remarks}}
       <!-- <td  style='width:40%;' valign="top">
             {{$getScrutinyDetailsForExtraQuestions->remarks}}
             </td>-->
          </tr>
    @endforeach
  </tr>
</tr>
  </table>
  <table class='items'  cellspacing="5"  border='0' width='100%' style='font-size: 12.5pt;'>

  @if(sizeof($scrutinydetails11) > 0)
 <tr>
  <td colspan="3"><b> Other Objections</b></td>
 </tr>
 @endif
  <?php $j = 1; ?>
 @foreach($scrutinydetails11 as $scrutinydetails11)
  @if(($scrutinydetails11->observation)!= '')
    <tr class="item">
         <td style='width:5%;' valign="top">{{ $j++ }}.
       </td>
       <td style='width:55%;padding:right 15px;' align='justify'> {{$scrutinydetails11->observation}}</td>
       <td  style='width:40%;' valign="top">
          {{$scrutinydetails11->remarks}}
          </td>

       </tr>

    @endif
 @endforeach
</table>



        <div class="">
              <table border='0' width='100%' cellspacing="10" style='font-size: 12.5pt;' >
             <tr>
                  <td colspan="3"><b> ORDER OF THE REGISTRAR</b></td>
              </tr>
              <tr>
                 @if($applicationDetails->scrutinyflag == 'Y')
                  <td colspan="3" > Register the application and place before the Bench no. ___________ for admission.
                  </td>
                  @else
                  <td colspan="3" > (i) Rectification of defects stated  to be made on or before {{ date('d-m-Y', strtotime($scrutiny->tobecomplieddate)) }}</td>

                  <tr>
                   <td colspan="3" style="text-align: center;" >OR </td></tr>

                    <tr><td colspan="3">
                   (ii) Post before the Registrar for orders on objections raised 
                   </td></tr>@endif

              </tr>
              </table>
          </div>
</br></br></br></br>
<table border='0' width='100%' cellspacing="50" style='font-size: 12.5pt;'>
  </br></br></br></br></br></br></br></br>

   <tr>
     <td style='width:70%;' align='left'>
       <br>
       <br>
     <h2>Signature of the Checking Officer<h2>
   </td>
   <td style='width:70%;' align='center'>
     <br>
     <br>
   <h2>Signature of the Section Officer</h2>
 </td>
      <td style='width:70%;' align='right'>

        <br>
       <h2>ASSISTANT/DEPUTY REGISTRAR</h2>
    </td>
  </tr>
 </table>




 </body>
  </html>
