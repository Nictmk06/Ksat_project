$(document).ready(function(){

   $('#saveButton').attr('disabled','disabled');

   $("#applicationId, #conApplicationId").change(function(){
   var applicationid = $(this).val();
   
   $('#hiddenApplicationId').val(applicationid);  // Used for updating dailyhearing table
   $('#saveButton').attr('disabled','disabled');
   $('#orderDateDiv').hide();
   $('#disposedDiv').hide();
   $('#admitPause').hide();
        
   id                = applicationid.split(':',2);
   var appid         = id[0];
   var hrdt          = id[1];

   var optag = 1;
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {_token: CSRF_TOKEN,optag:optag,applicationid:appid,hearingdate:hrdt},
      success: function (data) 
      {
        var benchdetail    = data['benchdetail'];
        var appldetail     = data['appldetail'];
        var conappls       = data['conappls'];   // Connected applications
        var applicants     = data['applicants'];
        var respondents    = data['respondents'];
        var iapending      = data['iapending'];
        var m_status       = data['m_status'];
        var m_ordertype    = data['m_ordertype'];
        var m_purpose      = data['m_purpose'];
        var m_bench        = data['m_bench'];
        var m_benchtype    = data['m_benchtype'];

        if ( iapending.length > 0 )
        {
         $('#iaTableBlock').show(); 
        }
        else
        {
         $('#iaTableBlock').hide(); 
        }

        if (appldetail.length > 0)
        {
          $('#saveButton').removeAttr('disabled');
        }

        // Highlight selected application ID, main application or connected application
        if (appldetail[0].mainapplicationid == null)
        {
         $('#connectIdLabel').removeClass('highlight');
         //$('#mainIdLabel').addClass('highlight');
        }
        else
        {
       //  $('#connectIdLabel').addClass('highlight');
      //   $('#mainIdLabel').removeClass('highlight');
        }

        // Set button value for Admin and Pause buttons
        var admitPause = appldetail[0].benchcode + '^^' + appldetail[0].listno + '^^' + appldetail[0].courthallno + '^^' + appldetail[0].hearingdate + '^^' + appldetail[0].causelistsrno + '^^' + appldetail[0].applicationid;
        var admitVal   = admitPause + '^^' + 'A';
        var pauseVal   = admitPause + '^^' + 'P';
        $('#admitButton').val(admitVal);
        $('#pauseButton').val(pauseVal);

        //Set hearingcode(PK) to hidden input value. This may be used while updaing current application
        if (appldetail.length > 0)
        {
           $('#hearingCode').val(appldetail[0].hearingcode);
        }
        else
        {
         $('#hearingCode').val('');
        }

        //Display bench details like bench type, court hall no, hearing date at top of form
        if (benchdetail.length > 0)
        {
          $('#hd_benchdetail').text(benchdetail[0].benchtypename + ' [ ' + benchdetail[0].judgeshortname + ' ]');
          $('#hd_listno').text(benchdetail[0].listno);
        }
        else
        {
         $('#hd_benchdetail').text('Not found');
         $('#hd_listno').text('Not found');
        }

        // Populate Connected applications drop-down
        if (conappls.length > 0 || appldetail[0].mainapplicationid == null)
        {
           $('#conApplicationId').find('option').not(':first').remove();
           for (i = 0; i < conappls.length; i++)
           {
             $('#conApplicationId').append('<option value="' + conappls[i].applicationid + ':'  + conappls[i].hearingdate + '">' + conappls[i].applicationid + '</option>');
           }
        }

        //Display Applicants(petioners) and Respondets
        var applicantText = "<b> <u> Applicant : </u> </b>";
        if (applicants.length > 0)
        {
          for (i = 0; i < 1; i++)
          {
             applicantText +=  applicants[i].applicantsrno + ' ' + applicants[i].applicantname; // + ' ' + applicants[i].relationname + ' ' + applicants[i].applicantaddress + ', ';
          }
        }
        else
        {
             applicantText +=  "No applicants found";
        }
        
        applicantText +=  "<b> <u> Respondent : </u> </b>";
        if (respondents.length > 0)
        {
          for (i = 0; i < 1; i++)
          {
             applicantText +=  respondents[i].respondsrno + ' ' + respondents[i].respondname; // + ' ' + respondents[i].respondaddress + ' ';
          }
        }
        else
        {
             applicantText +=  "No respondents found";
        }
           
           $('#applicantRespondent').html(applicantText);

        //Display Posted for(purpose), Court directions and Remarks(officenote)
        if (appldetail.length > 0)
        {
          $('#postedFor').html(appldetail[0].postedfor);
          $('#courtDirection').val(appldetail[0].courtdirection);
          $('#remarksIfAny').val(appldetail[0].caseremarks);
        }
        else
        {
         $('#postedFor').html('');
         $('#courtDirection').val('');
         $('#remarksIfAny').val('');
        }

        //Set Pop-down list for Status of application, Application final status, Next listing purpose, Bench type and Bench name
        if (appldetail.length > 0)   // Set pop-ups and IA details
        {
          var applid             = appldetail[0].applicationid;
          var applstatus         = appldetail[0].casestatus;
          var ordertypecode      = appldetail[0].ordertypecode;
          var purposecode        = appldetail[0].purposecode;
          var benchcode          = appldetail[0].benchcode;
          var benchtypename      = appldetail[0].benchtypename;
          var nextpurposecode    = appldetail[0].nextpurposecode;
          var nextbenchcode      = appldetail[0].nextbenchcode;
          var nextbenchtypename  = appldetail[0].nextbenchtypename;
         
          // Set Order Passed 
          $('#orderPassed').find('option').not(':first').remove();
          for (i = 0; i < m_ordertype.length; i++)
          {
              if ( m_ordertype[i].ordertypecode == ordertypecode )
              {
                 $('#orderPassed').append('<option selected="true" value="' + m_ordertype[i].ordertypecode + '">' + m_ordertype[i].ordertypedesc + '</option>');
              }
              else
              {
                 $('#orderPassed').append('<option value="' + m_ordertype[i].ordertypecode + '">' + m_ordertype[i].ordertypedesc + '</option>');
              }
          }
          // Set Application Final status
          $('#applicationFinalStatus').find('option').not(':first').remove();
          for (i = 0; i < m_status.length; i++)
          {
              if ( m_status[i].statuscode == applstatus )
              {
                 $('#applicationFinalStatus').append('<option selected="true" value="' + m_status[i].statuscode + '">' + m_status[i].statusname + '</option>');
              }
              else
              {
                 $('#applicationFinalStatus').append('<option value="' + m_status[i].statuscode + '">' + m_status[i].statusname + '</option>');
              }
          }
          
          // Set Next listing purpose
          $('#nextListingPurpose').find('option').not(':first').remove();
          if (nextpurposecode != null && nextpurposecode != 0)
          {
               var purposesel = nextpurposecode;
          }
          else
          {
               var purposesel = purposecode;
          }
          for (i = 0; i < m_purpose.length; i++)
          {
              // if ( m_purpose[i].purposecode == purposesel )
              // {
              //    $('#nextListingPurpose').append('<option selected="true" value="' + m_purpose[i].purposecode + '">' + m_purpose[i].listpurpose + '</option>');
              // }
              // else 
           //   {
                 $('#nextListingPurpose').append('<option value="' + m_purpose[i].purposecode + '">' + m_purpose[i].listpurpose + '</option>');
            //  }
          }
          // Set Next bench type name
         //('#benchType').find('option').not(':first').remove();
          if (nextbenchtypename != null && nextbenchtypename != '')
          {
               var benchtypenamesel = nextbenchtypename;
          }
          else
          {
               var benchtypenamesel = benchtypename;
          }
         /* for (i = 0; i < m_benchtype.length; i++)
          {
              if ( m_benchtype[i].benchtypename == benchtypenamesel )
              {
                 $('#benchType').append('<option selected="true" value="' + m_benchtype[i].benchtypename + '">' + m_benchtype[i].benchtypename + '</option>');
              }
              else
              {
                 $('#benchType').append('<option value="' + m_benchtype[i].benchtypename + '">' + m_benchtype[i].benchtypename + '</option>');
              }
          }*/
          // Set Next bench (sitting bench)
          $('#bench').find('option').not(':first').remove();
          if (nextbenchcode != null && nextbenchcode != 0)
          {
               var benchcodesel = nextbenchcode;
          }
          else
          {
               var benchcodesel = benchcode;
          }
          for (i = 0; i < m_bench.length; i++)
          {
              // if ( m_bench[i].benchcode == benchcodesel )
              // {
              //    $('#bench').append('<option selected="true" value="' + m_bench[i].benchcode + '">' + m_bench[i].judgeshortname + '</option>');
              // }
              // else
              // {
                 $('#bench').append('<option value="' + m_bench[i].benchcode + '">' + m_bench[i].judgeshortname + '</option>');
             // }
          }  
           
          // Set Pending IA applications(Documents)
          $('#pendingIa').find('option').not(':first').remove();
          $('#iaPrayer').val('');
          $('#iaRemarks').val('');
          $('#iaStatus').find('option').not(':first').remove();
          $('#iaOrderPassed').find('option').not(':first').remove();

          for (i = 0; i < iapending.length; i++)
          {
              if ( i == 0 )  // If IA applications exists, then select 1st IA application and display its iaprayer(remarks) and IA status
              {
                 $('#iaPrayer').val(iapending[i].iaprayer);
                 $('#iaRemarks').val(iapending[i].remark);
                 $('#hiddenPendingIa').val(iapending[i].applicationid + ':' + iapending[i].iano);
                 $('#pendingIa').append('<option selected="true" value="' + iapending[i].applicationid + ':' + iapending[i].iano + '">' + iapending[i].iano + '</option>');
                 for (j = 0; j < m_status.length; j++)  // Set IA status drop-down list
                 {
                     if ( m_status[j].statuscode == iapending[i].iastatus )
                     {
                        $('#iaStatus').append('<option selected="true" value="' + m_status[j].statuscode + '">' + m_status[j].statusname + '</option>');
                     }
                     else
                     {
                        $('#iaStatus').append('<option value="' + m_status[j].statuscode + '">' + m_status[j].statusname + '</option>');
                     }
                 }

               $('#iaOrderPassed').find('option').not(':first').remove();
                for (j = 0; j < m_ordertype.length; j++)
                {
                 // alert(iapending[i].ordertypecode);
                    if ( m_ordertype[j].ordertypecode == iapending[i].ordertypecode )
                    {
                       $('#iaOrderPassed').append('<option selected="true" value="' + m_ordertype[j].ordertypecode + '">' + m_ordertype[j].ordertypedesc + '</option>');
                    }
                    else
                    {
                       $('#iaOrderPassed').append('<option value="' + m_ordertype[j].ordertypecode + '">' + m_ordertype[j].ordertypedesc + '</option>');
                    }
                }
                  
              }
              else
              {
                 $('#pendingIa').append('<option value="' + iapending[i].applicationid + ':' + iapending[i].iano + '">' + iapending[i].iano + '</option>');
              }
   
          }

          // Set post after count, radio button of day/week/month and nextdate
         
          var postafter = appldetail[0].postafter;
          if ( postafter != null )
          {
             $('#postAfterPeriod').val(postafter);
          }
          else
          {
            $('#postAfterPeriod').val('');
          }

          var postaftercat = appldetail[0].postaftercategory;
          if ( postaftercat == null || postaftercat == '')
          {
              postaftercat = 'd';
          }
          $('[name="dwm"]').removeAttr('checked');
          $("input[name=dwm][value='" + postaftercat + "']").prop('checked', true);

          var nextdate = appldetail[0].nextdate;
          if ( nextdate != null && nextdate != '')
          {
             $('#nextHearingDate').val(nextdate);
          }
          else
          {
             $('#nextHearingDate').val('');
          }

          
          // Set radio button of Business complete (Y/N)
          $('[name="business"]').removeAttr('checked');
          var business = appldetail[0].business;
          
             business = "Y";
         
          $("input[name=business][value='" + business + "']").prop('checked', true);


        } // End of : if (appldetail.length > 0)   // Set pop-ups and IA details
 
      }    // End of : success: function (data) for $("#applicationId").change(function()
     });  // End of : $.ajax({ for $("#applicationId, #conApplicationId").change(function()
   });   //  End of : $("#applicationId, #conApplicationId").change(function()

   // Update previous and populate selected IA details when IA drop-down changed (If more than one IA exists for single application)
   $("#pendingIa").change(function(){
      var previousId = $('#hiddenPendingIa').val();
      previousIdSplit     = previousId.split(':',2);
      var previousAppid   = previousIdSplit[0];
      var previousIano    = previousIdSplit[1];

      var changedId      = $(this).val();
      changedIdSplit     = changedId.split(':',2);
      var changedAppid   = changedIdSplit[0];
      var changedIano    = changedIdSplit[1];

      $('#hiddenPendingIa').val(changedId);

      var optag = 2;

      // Update previously selected IA 
      if ( previousId != "0:0" )
      {
         var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
         $.ajax({
         type : 'POST',
         url : "ChpAjaxPost",
         dataType : "JSON",
         data : {_token: CSRF_TOKEN, optag: optag, applicationid: previousAppid, iano: previousIano, iaprayer: $('#iaPrayer').val(), iaremarks: $('#iaRemarks').val(), iastatus: $('#iaStatus').val()},
            success: function(response)
            {
              //
				}
         });
      }  // End of : if ( previousId != "0:0" )
      
      // Now display changed IA data
      var optag = 2;
      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {optag:optag,applicationid:changedAppid, iano:changedIano},
        success: function (iadata) 
        {
          var iapending2      = iadata['iapending'];
          var m_status2       = iadata['m_status'];
          
          if ( iapending2.length > 0)
          {
            var iastatus = iapending2[0].iastatus;

            $('#iaPrayer').val(iapending2[0].iaprayer);
            $('#iaRemarks').val(iapending2[0].remark);
            for (j = 0; j < m_status2.length; j++) 
            {
              if ( m_status2[j].statuscode == iastatus )
              {
                $('#iaStatus').append('<option selected="true" value="' + m_status2[j].statuscode + '">' + m_status2[j].statusname + '</option>');
              }
              else
              {
                $('#iaStatus').append('<option value="' + m_status2[j].statuscode + '">' + m_status2[j].statusname + '</option>');
              }
            }
          }
          else
          {
            $('#iaPrayer').val('');
            $('#iaRemarks').val('');
            $('#iaStatus').find('option').not(':first').remove();
          }
    
        }  // End of : success: function (data) for $("#pendingIa").change(function()
      }); // End of : $.ajax({ for $("#pendingIa").change(function()
   });  // End of : $("#pendingIa").change(function()

   // Set orderDate once orderPassed drop-down is changed
   $('#orderPassed').change(function() {
      var selectedText = $(this).find('option:selected').text().trim().toUpperCase();
      if ( selectedText != "NO ORDER" && selectedText != "ORDER PASSED")
      {
         var d = new Date();
         var month = d.getMonth()+1;
         var day = d.getDate();
         var ordDt = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
         $('#orderDate').val(ordDt);
         $('#orderDateDiv').show();
      }
      else
      {
         $('#orderDate').val('');
         $('#orderDateDiv').hide();
      }
   });  // End of : $('#orderPassed').change(function()

   // Set disposedDate once applicationFinalStatus drop-down is changed
   $('#applicationFinalStatus').change(function() {
      var selectedText = $(this).find('option:selected').text().trim().toUpperCase();
      if ( selectedText == "DISPOSED")
      {
         var d = new Date();
         var month = d.getMonth()+1;
         var day = d.getDate();
         var ordDt = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
         $('#disposedDate').val(ordDt);
         $('#disposedDiv').show();
         $('#postAfterPeriod').val('');
         $('#nextHearingDate').val('');
        // $('#postAfterPeriod').prop('required',false);
         $('#disposedYN').hide();
      }
      else
      {
         $('#disposedDate').val('');
         $('#disposedDiv').hide();
        // $('#postAfterPeriod').prop('required',true);
         $('#disposedYN').show();
      }
   });  // End of : $('#orderPassed').change(function()
   
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


// Update DisplayBoard Table after clicking A or P button
$("#admitButton, #pauseButton").button().click(function(){
      var buttVal = $(this).val();
      var applicationid= $('#hiddenApplicationId').val().split(':'); 
//      alert($('#hiddenApplicationId').val());
 //alert(applicationid);	
	var appid         = applicationid[1];
	var applsrno= applicationid[0]
   alert(appid);
    alert(applsrno);
   
      var optag = 3;
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
         $.ajax({
         type : 'POST',
         url : "ChpAjaxPost",
         dataType : "JSON",
         data : {_token: CSRF_TOKEN,optag:optag,stage:buttVal,applicationid:appid,applsrno:applsrno},
            success: function(response)
            { 
               $("#admitPause").text(response.msg);
            }  // End of : success: function (data) for  $("#admitButton").button().click(function()
      }); // End of : $.ajax({ for  $("#admitButton").button().click(function()
   });  // End of : $("#admitButton").button().click(function(){


 $('#listno').change(function() {
      var listno  = $(this).val();
                
      $.ajax({
      type : 'post',
      url : "getCHPApplication",
      dataType : "JSON",
      data: {"_token": $('#token').val(),listno:listno,bulkproceedings:'N'},
      success: function (json) 
        {
          $('#applicationId').empty();
          $('#applicationId').append('<option value=""> Select Application </option>');
           for(var i=0;i<json.length;i++){
           var option = '<option value="'+json[i].causelistsrno+':'+json[i].applicationid+':'+json[i].hearingdate+'">'+json[i].causelistsrno+'-->'+json[i].applicationid+'</option>';
             $('#applicationId').append(option);
          }
    
        }  // End of : success: function (data) for $('#postedFor').change(function() 
      });  // End of : $.ajax({ for  $('#postedFor').change(function()    
    }); 

});  // End of : $(document).ready(function()
