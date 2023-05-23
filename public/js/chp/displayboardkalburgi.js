var passedCasesCH1 = [];
var lengthPassedCasesCH1=0;
var count1=1;


$(document).ready(function(){
  executeQuery();
  updateCall1();
});


function executeQuery() {
	$.ajax({
        type: 'post',
        url: "getdisplayboard",
        dataType:"JSON",
        data: {"_token": $('#token').val(),establishcode:2},
		dataType: "JSON",
        success: function (json) {
        var ch1=0;
        var ch2=0;
        var ch3=0;
           for(var i=0;i<json.length;i++){
              if(json[i].courthallno ==1){

           		 		if(json[i].stage =='A'){
           		 			$('#ch1a').html(json[i].causelistsrno + ' - '+json[i].applicationo);
						}
						/*if(json[i].stage =='N'){
           		 			$('#ch1next').html(json[i].causelistsrno + ' - '+json[i].applicationo);
						}*/
						
						if(json[i].stage =='P'){
              passedCasesCH1[lengthPassedCasesCH1]=json[i].causelistsrno + ' - '+json[i].applicationo;
              lengthPassedCasesCH1=lengthPassedCasesCH1+1;
           		$('#ch1p').html(json[i].causelistsrno + ' - '+json[i].applicationo);
						}
						ch1++;
					}
					  
			               
          }
          if(ch1==0){
            $('#ch1table').find('tbody tr').remove();
             var row = $('<tr class="display_row" style="font-size:140%;">');
              row.append('<td width="35%"> Court Hall - I </td>  <td>No Session</td> </tr>' ); 
              row.appendTo('#ch1table');
			/*var row = $('<tr class="display_row1"  style="font-size:140%;">');
              row.append('<td width="35%">&nbsp;</td>  <td></td> </tr>' ); 
              row.appendTo('#ch1table');*/
             var row = $('<tr class="display_row2"  style="font-size:140%;">');
             row.append('<td width="35%">&nbsp;</td>  <td></td> </tr>' ); 
             row.appendTo('#ch1table');
          }
        
     }
      
    });
 
     updateCall();
}


function updateCall(){
	//alert("timer");
 //   refreshPassedCases();
    setTimeout(function(){executeQuery()}, 30000); 
}


function refreshPassedCases(){
  // alert("lengthPassedCasesCH1--"+lengthPassedCasesCH1);
  //  alert('count1'+count1);
  //  alert('cdvfgkjfn'+  passedCasesCH1[1]);
  
  if(lengthPassedCasesCH1>1)
   {
    $('#ch1p').html(passedCasesCH1[count1 % lengthPassedCasesCH1]);
    count1=(count1+1)%lengthPassedCasesCH1;
   }
   updateCall1();
}

 function updateCall1(){
  //alert("timer");
    setTimeout(function(){refreshPassedCases()}, 5000);
}
