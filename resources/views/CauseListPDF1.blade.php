
<html>
<head>
<style>
body {font-family: arial;
    font-size: 11pt;
}

table thead td { background-color: #FFFFFF;
    text-align: center; vertical-align:text-top;
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
    margin-top: 2.54cm;
    margin-bottom: 2.54cm;
    margin-left: 2.54cm;
    margin-right: 2.54cm;
	footer: myfooter;
}
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

<table  width='100%'><thead><tr><td align='left'><font size='1'><b>Website:https://ksat.karnataka.gov.in</b></font></td><td align='center'><font size='1'><b></b></font></td><td align='right'><font size='1'><b>Page {PAGENO} of {nb} </b></font></td></tr></thead></table>

</div>
</htmlpagefooter>



<!-- <sethtmlpageheader name='firstpage' value='on' show-this-page='1' /> 
-->
<sethtmlpageheader name='otherpages' value='on' />
<sethtmlpagefooter name='myfooter' value='on' />

<table class='items' border='0' width='100%' style='font-size: 12.5pt;'>
  <tr> 
		<th  colspan='3'  style='font-size: 14pt;' align='center'> {{ $title[0]->causelist_title }} <br> 
		 {{  $title[0]->causelist_title1 }}  <br> 
		<br>
				{{  $causelistdate }}
		<br>
		 <br>BEFORE    <br>  <br> 
		</th> 
	</tr>
	<tr>
		<td colspan='3'  style='font-size: 14pt;' align='center'>
		  <b> 
		  <?php if (!empty($benchjudge))
		  { 
		  if ($benchjudge[0]->judgescount == 1)
		  {   echo($benchjudge[0]->judgename);echo('.....');echo($benchjudge[0]->judgedesigname); }
			  else {
		  for ($i=0;$i<$benchjudge[0]->judgescount;$i++)
			 { echo($benchjudge[$i]->judgename);echo('.....');echo($benchjudge[$i]->judgedesigname);
        echo '<br>';
	
		if($i < $benchjudge[0]->judgescount -1){
		echo 'AND';
		}
		
		 echo '<br>';
//       if (($benchjudge[0]->judgescount = 2) && ($i = 0)) echo(' AND ');
//       if (($benchjudge[0]->judgescount = 3) && ($i = 1)) echo(' AND ');
        }
		   }  }
		?>
          </b>
		</td>
	</tr>
   <tr>
            <td colspan='3' style='font-size: 14pt;' align='center'> <b> {{  $causelist[0]->courthalldesc  }}  </b>
            </td>
    </tr>


             <!--    <tr><td colspan='3' width='100%' style='font-size: 14pt;' align='center'><b>
                 <br>CAUSELIST TYPE:  {{ $causelist[0]->causelistdesc }}</b> </td>
                </tr> -->


 <!-- <tr style='height:1px;'><td colspan=3><hr></td></tr>
                 <tr>
                <td  style='width:10%;'align='center' ><b>Sl.No.</b></td>
                <td  style='width:45%;' align='left'><b>Adv for Applicant</b></td>
                <td  style='width:45%;' align='left'><b>Adv for Respondent</b></td>
                </tr>
 <tr style='height:1px;'><td colspan=3><hr></td></tr> 

  <tr style='height:1px; text-align:center;'>
	    <td colspan='3' align=center style='font-size: 14pt;'><b> {{ $causelist[0]->clheader }}  </b> 
		</td>
	</tr>

-->
           
	 <tr style='height:1px; text-align:center;'>
	    <td colspan='3' align=center style='font-size: 14pt;'><b> <?php  echo nl2br($causelist[0]->clheader); echo '<br>';
             ?> </b> 
		</td>
	</tr>
    

  
    
    
  <?php 
    $listpurpose = $causelist[0]->listpurpose;
    $applicationid = ' ';
    $i = 0;

   for($i=0;$i<count($causelist);$i++){
   if (($i==0) || ($listpurpose != $causelist[$i]->listpurpose)) { 
  ?>
  <tr>
    <td colspan="3"></td>
    </tr>
    <tr>
     <td colspan='3' align=center style='font-size: 14pt;'><b> {{ $causelist[$i]->listpurpose }} </b>
     </td>  
     </tr>
     <tr>
      <td colspan='3'>
      </td>
     </tr>
     </br>
   <?php } ?>
     
 <!--  <tr>
          <td style="vertical-align:text-top;"> SR NO </td>
       <td colspan="2" id="casetd" style="vertical-align:text-top;"> CASE NO <br>
       REMARKS </td>
        <td style="vertical-align:text-top;"> CLASSIFICATION </td>
      <td colspan=3 style="vertical-align:text-top;">PETITIONER ADVOCATE </td>
   <td colspan=3 style="vertical-align:text-top;">RESPONDENT ADVOCATE </td>
       </tr>
             <tr>
            <td colspan=10></td>
            </tr>'     -->
         
  <?php if (($applicationid != $causelist[$i]->applicationid) ) { ?>
  <tr><td  style="padding: 10px">
   </td>
   </tr>
    <tr>
        <td style='width:10%;' valign='top' > {{ $causelist[$i]->causelistsrno }}.</td>
        <td style='width:45%;' valign='top'  align='left'> 
                <?php  echo nl2br($causelist[$i]->appautoremarks); echo '<br>';
             ?>
                <?php echo nl2br($causelist[$i]->appuserremarks) ; echo '<br>';  ?>
        </td>
        <td style='width:45%;padding:right 10px;' valign='top' align='justify'>
                <?php echo nl2br($causelist[$i]->resautoremarks);  echo '<br>';   ?> 
               
                <?php echo nl2br($causelist[$i]->resuserremarks); echo '<br>';  ?>
        </td>
     </tr>
      <?php   } ?>
  

 <?php if (($applicationid == $causelist[$i]->applicationid) ||  ($causelist[$i]->conapplid != null)) { ?>
     <tr><td colspan="3" align=left><u>Connected With </u></td></tr>
     <tr>
            <td style='width:10%;' align='center'>  </td>
            <td style='width:45%;'  align='left' valign='top'> 
            <?php echo nl2br($causelist[$i]->conappautoremarks) ;   echo '<br>';
           ?>
            <?php echo nl2br($causelist[$i]->conappuserremarks); echo '<br>'; ?>
                  </td>
                <td style='width:45%;padding:right 10px;' valign='top' align='justify'> 
            <?php echo nl2br($causelist[$i]->conresautoremarks) ;  echo '<br>';
            ?>
            <?php echo nl2br($causelist[$i]->conresuserremarks); echo '<br>'; ?> 
                   </td>
     </tr>           
 <?php   } ?>
 
 
    <?php  $listpurpose = $causelist[$i]->listpurpose;
  $applicationid =  $causelist[$i]->applicationid ;
    } 


   
    ?>


               
 </table>

 <table border='0' width='100%' style='font-size: 12.5pt;'>
  
    <tr> <td style='width:30%;' align='center'> 
</td>
      <td style='width:70%;' align='center'> 

      <?php   echo '<br>'; echo '<br>';echo '<br>';
        echo nl2br($signauthority[0]->name); echo '<br>'; ?>
        <?php    echo nl2br($signauthority[0]->designation); echo '<br>'; ?>
            <?php    echo nl2br($signauthority[0]->deptname1); echo '<br>'; ?>
        <?php    echo nl2br($signauthority[0]->deptname2); echo '<br>'; ?>
   
   </td> </tr> </table>
 </body>
  </html> 
 
