<html>
<head>
<style>
<style>
body {font-family: courier;
    font-size: 11pt;
}
td { vertical-align: top; 
    align: center;margin:0;
    padding:2;
   }
#casetd
   {	width: 200px;	}
#emptytd
   {	width: 30px;	}
table { border-collapse: collapse; }
table thead td { background-color: #FFFFFF;
    text-align: center;
 }
 tbody tr   { box-shadow: 0 1px 10px #000000; }
 tbody { border-top: 30px solid transparent}
thead { display: table;
    width: 100%;
    margin-bottom: 10px; }
tfoot { display: table-row-group }
tr { page-break-inside: avoid }
td.lastrow {
    background-color: #FFFFFF;
    border: 0mm none #000000;
    border-bottom: 0.3mm solid #000000;
    border-left: 0.3mm solid #000000;
	border-right: 0.3mm solid #000000;
}
@page *{
    margin-top: 2.54cm;
    margin-bottom: 2.54cm;
    margin-left: 3.175cm;
    margin-right: 3.175cm;
}
</style>
</style>
</head>
<body>

<htmlpageheader name='otherpages' >
    <div style='text-align:center'>{PAGENO} </div>
	<table width='100%'><thead><tr><td align='center'><u><b> HEADING  </b></u></td></tr></thead></table>
</htmlpageheader>

<htmlpagefooter name='myfooter'>
	<div style='border-top: 1px solid #000000; font-size: 1pt; text-align: center; padding-top: 0.5mm; '>
		<table  width='100%'>
			<thead>
				<tr>
				<td align='left'><font size='1'><b>Website:https://kat.karnataka.gov.in/ </b></font></td>
				<td align='center'><font size='1'><b>Generated on : {{ date('d-m-Y H:i:s') }} </b></font></td>
				<td align='right'><font size='1'><b>Page {PAGENO} of {nb} </b></font></td></tr>
			</thead>
		</table>
	</div>
</htmlpagefooter>

<!-- <sethtmlpageheader name='firstpage' value='on' show-this-page='1' /> 
-->
<sethtmlpageheader name='otherpages' value='on' />
<sethtmlpagefooter name='myfooter' value='on' />




<table class='items' border='0' width='100%' style='font-size: 14.5pt;' cellpadding='2'>
	<tr> 
		<th colspan="10" align='center'> 
			{{ $title[0]->causelist_title }} <br>
			{{ $causelistdate }} <br>
			BEFORE
		</th> 
	</tr>
	<tr>
		<td colspan="10" align='center'>
			<b> 
			<?php 
			if (!empty($benchjudge)){ 
				if ($benchjudge[0]->judgescount == 1){   
					echo($benchjudge[0]->judgename.' '); 
					echo($benchjudge[0]->judgedesigname); 
				}
				else {
					for ($i=0;$i<$benchjudge[0]->judgescount;$i++){ 
						echo($benchjudge[$i]->judgename.' ');
						echo($benchjudge[$i]->judgedesigname);
						echo '<br>';
						if($i < $benchjudge[0]->judgescount -1){
							echo 'AND';
						}
						echo '<br>';
					}
				}  
			}
			?>
			</b>
		</td>
	</tr>
	<tr>
		<td colspan="10" align='center'> <b> {{  $causelist[0]->courthalldesc  }}  </b></td>
    </tr>

	
	
	
  
	<?php 
    $listpurpose = $causelist[0]->listpurpose;
    $applicationid = ' ';
    $i = 0;

	for($i=0;$i<count($causelist);$i++){
		if (($i==0) || ($listpurpose != $causelist[$i]->listpurpose)) 
		{ 
			?>
			</table>
			<br>
			<table class='items' border='0' width='100%' style='font-size: 12.5pt;' cellpadding='2'>
				<tr>
					<td colspan=10><b><u>{{ $causelist[$i]->listpurpose }}</u></b></td>  
				</tr>
			</table>
			<table class='items' border='1' width='100%' style='font-size: 14.5pt;' cellpadding='2'>
			<tr>
				<td style='width:20px;text-align: center;'><b> Sl No. </b></td>  
				<td style='width:100px;text-align: center;'><b> Case No </b></td>  
				<td style='width:120px;text-align: center;'><b> Petitioner </b></td>  
				<td style='width:100px;text-align: center;'><b> Petitioner Advocate </b></td>  
				<td style='width:120px;text-align: center;'><b> Act </b></td>  
				<td style='width:120px;text-align: center;'><b> Impugned Order No/Date </b></td>  
				<td style='width:120px;text-align: center;'><b> Respondent </b></td>  
				<td style='width:100px;text-align: center;'><b> District </b></td>  
				<td style='width:100px;text-align: center;'><b> Respondent Advocate</b></td>  
			</tr>
			
			<?php 
		}
		?>
     
		<?php 
		if (($applicationid != $causelist[$i]->applicationid) ) { 
		?>
			
			<tr>
				<td valign='top' style="padding: 15px;"> {{ $causelist[$i]->causelistsrno }}.</td>
				<td valign='top' align='left' style="padding: 15px;"> {{$causelist[$i]->applicationid}}</td>
				<td valign='top' align='left' style="padding: 15px;">{{$causelist[$i]->applicantname}}</td>
				<td valign='top' align='left' style="padding: 15px;">{{$causelist[$i]->petitioneradv}}</td>
				<td valign='top' align='left' style="padding: 15px;">{{$causelist[$i]->actname}}</td>
				<td valign='top' align='left' style="padding: 15px;">{{$causelist[$i]->againstorders}}</td>
				<td valign='top' align='left' style="padding: 15px;">{{$causelist[$i]->respondname}}</td>
				<td valign='top' align='left' style="padding: 15px;">{{$causelist[$i]->servicedistname}}</td>
				<td valign='top' align='left' style="padding: 15px;">{{$causelist[$i]->respondadv}}</td>
			</tr>
			
		<?php   
		} 
		?>
  

	 <?php if (($applicationid == $causelist[$i]->applicationid) ||  ($causelist[$i]->conapplid != null)) { ?>

			<tr>
				<td colspan="10" align=left>
					<?php if ($causelist[$i]->type == 'A/W')  { ?>
					<u>Along with </u>
					<?php  } 
					else  { ?> 
					<u>Connected with </u>  
					<?php  }  ?>
				</td>
			</tr>
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
 
 <table border='0' width='100%' style='font-size: 10pt;font-weight:bold'>
    <tr> 
		<td style='width:60%;' align='center'> </td>
		<td style='width:40%;' align='center'> 
		<?php echo '<br>'; echo '<br>';echo '<br>';?>
        <?php echo nl2br($signauthority[0]->designation); echo '<br>'; ?>
		<?php echo nl2br($signauthority[0]->deptname1); echo '<br>'; ?>
        <?php echo nl2br($signauthority[0]->deptname2); echo '<br>'; ?>
   
		</td> 
	</tr> 
	<tr style='height:1px; '>
		<td style='font-size: 14pt;'> <?php  echo nl2br($causelist[0]->clfooter); echo '<br>';?></td>
	</tr>
</table>
    
 </body>
  </html> 
 
