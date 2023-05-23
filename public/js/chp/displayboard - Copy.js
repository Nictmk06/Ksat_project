var passedCasesCH1 = [];
var passedCasesCH2 = [];
var passedCasesCH3 = [];
var lengthPassedCasesCH1=0;
var lengthPassedCasesCH2=0;
var lengthPassedCasesCH3=0;
var count1=1;
var count2=1;
var count3=1;

$(document).ready(function(){
	executeQuery();
  updateCall1();
});


function executeQuery() {
	$.ajax({
        type: 'post',
        url: "getdisplayboard",
        dataType:"JSON",
        data: {"_token": $('#token').val()},
        success: function (json) {
           // $('#myTable1').css('display','block');
         //   $('#myTable1').find('tbody tr').remove();
        var ch1=0;
        var ch2=0;
        var ch3=0;
           for(var i=0;i<json.length;i++){
              if(json[i].courthallno ==1){

           		 		if(json[i].stage =='A'){
           		 			$('#ch1a').html(json[i].applicationid);
						}
						if(json[i].stage =='P'){
              passedCasesCH1[lengthPassedCasesCH1]=json[i].applicationid;
              lengthPassedCasesCH1=lengthPassedCasesCH1+1;
           		$('#ch1p').html(json[i].applicationid);
						}
						ch1++;
					}
					  if(json[i].courthallno ==2){
           		 		if(json[i].stage =='A'){
           		 			$('#ch2a').html(json[i].applicationid);
						}
						if(json[i].stage =='P'){
               passedCasesCH2[lengthPassedCasesCH2]=json[i].applicationid;
               lengthPassedCasesCH2=lengthPassedCasesCH2+1;
           		 $('#ch2p').html(json[i].applicationid);
						}
						ch2++;
					}
					  if(json[i].courthallno ==3){
           		 		if(json[i].stage =='A'){
           		 			$('#ch3a').html(json[i].applicationid);
						}
						if(json[i].stage =='P'){
               passedCasesCH3[lengthPassedCasesCH3]=json[i].applicationid;
               lengthPassedCasesCH3=lengthPassedCasesCH3+1;
           		 $('#ch3p').html(json[i].applicationid);

						}
						ch3++;
					}                
          }
          if(ch1==0){
            $('#ch1table').find('tbody tr').remove();
             var row = $('<tr class="display_row" style="font-size:120%;background-color:#28a745">');
              row.append('<td width="40%"> Court Hall - I </td>  <td>No Session</td> </tr>' ); 
             row.appendTo('#ch1table');
          }
          if(ch2==0){
   			 $('#ch2table').find('tbody tr').remove();
   			   var row = $('<tr class="display_row" style="font-size:120%;background-color:#28a745">');
           row.append('<td width="40%"> Court Hall - II </td>  <td>No Session</td> </tr>' ); 
           row.appendTo('#ch2table');
          }
          if(ch3==0){
          	$('#ch3table').find('tbody tr').remove();
          	  var row = $('<tr class="display_row"  style="font-size:120%;background-color:#28a745">');
              row.append('<td width="40%"> Court Hall - III </td>  <td>No Session</td> </tr>' ); 
                row.appendTo('#ch3table');
          }
          
    }
      
    });
 
     updateCall();
}


function updateCall(){
	//alert("timer");
 //   refreshPassedCases();
    setTimeout(function(){executeQuery()}, 60000);
    
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
    if(lengthPassedCasesCH2>1)
   {
    $('#ch2p').html(passedCasesCH2[count2 % lengthPassedCasesCH2]);
   count2=(count2+1)%lengthPassedCasesCH2;
   }
    if(lengthPassedCasesCH3>1)
   {
    $('#ch3p').html(passedCasesCH3[count3 % lengthPassedCasesCH3]);
   count3=(count3+1)%lengthPassedCasesCH3;
   }
    updateCall1();
}

 function updateCall1(){
  //alert("timer");
    setTimeout(function(){refreshPassedCases()}, 10000);
}
