$(document).ready(function(){

$("#natureOfDisposal option[value='2']").remove();

// $('#saveButton').attr('disabled','disabled');

// Toggle case select checkboxes
  $('#toggleCheckbox').change(function(e){
    $('#CaseCheckUncheck input[type="checkbox"]').prop('checked', this.checked);

  });

 // Set business or No business
  $('input[name="businessYN"]').click( function(){
    if ( $(this).prop('checked') )
    {
      $('#businessText').val('No Business');
    }
    else
    {
      $('#businessText').val('');
    }
   });   

  // Get next hearing date calculate after changing post after period
   $('#postAfterPeriod').change(function() {
      var dwmno  = $(this).val();
      var dwm    = $('input[name=dwm]:checked').val();

      var optag = 99;
                 
      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {optag:optag,dwmno:dwmno,dwm:dwm},
        success: function (data) 
        {
          $('#nextHearingDate').val('');
          $('#nextHearingDate').val(data[0].postdt);
    
        }  // End of : success: function (data) for $('#postedFor').change(function() 
      });  // End of : $.ajax({ for  $('#postedFor').change(function()    
    });  // End of : $('#postedFor').change(function()

    // Get next hearing date calculate after changing Days/Weeks/Months Radio button
    $('input[name="dwm"]').change(function() {
      var dwm    = $('input[name=dwm]:checked').val();
      var dwmno  = $('#postAfterPeriod').val();

      var optag = 99;
                 
      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {optag:optag,dwmno:dwmno,dwm:dwm},
        success: function (data)
        {
          $('#nextHearingDate').val('');
          $('#nextHearingDate').val(data[0].postdt);
         }  // End of : success: function (data) for $('#postedFor').change(function() 
      });  // End of : $.ajax({ for  $('#postedFor').change(function()    
    });  // End of : $('#postedFor').change(function()  

$('#listno').change(function() {
      var listno  = $(this).val();
      $.ajax({
      type : 'post',
      url : "getCHPApplication",
      dataType : "JSON",
      data: {"_token": $('#token').val(),listno:listno,bulkproceedings:'Y'},
      success: function (json) 
        {
			  $('#myTable1').find('tbody tr').remove();
         var counter=0;
           for(var i=0;i<json.length;i++){
				var row = $('<tr>');
				//row.append('<td class="grid-item" width="15%">' + json[i].causelistsrno + '</td>');
         row.append('<td class="grid-item" width="25%" id="CaseCheckUncheck"> <input type="checkbox" name="caseSelect[]" id="caseSelect[]" value="'+json[i].hearingcode+"::"+counter+'" ></td>');
				 if(json[i].causelistsrno == null || json[i].causelistsrno =='')
        {
          row.append('<td class="grid-item" width="35%">' +json[i].applicationid + '</td>');
        
        }
        else
        {
          row.append('<td class="grid-item" width="35%">' +json[i].causelistsrno +" --> "+json[i].applicationid + '</td>');
        } 

				if(json[i].statusname == null || json[i].statusname.trim() =='')
				{
					//alert('dsfjh');
					row.append('<td class="grid-item" width="25%">Pending</td>');
				}
				else
				{
					row.append('<td class="grid-item" width="25%">' +json[i].statusname + '</td>');
					}
				row.append('</tr>');
				row.appendTo('#myTable1');
				counter++;
          }
    
        }  // End of : success: function (data) for $('#postedFor').change(function() 
      });  // End of : $.ajax({ for  $('#postedFor').change(function()    
    }); 
});  // End of : $(document).ready(function()

    
