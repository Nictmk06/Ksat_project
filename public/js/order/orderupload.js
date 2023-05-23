$(document).ready(function() {
  
$("#applnJudgementDate").css('pointer-events', 'none');

$('#uploadorder').change(function (event) {
  $('#element').empty();
    var file = URL.createObjectURL(event.target.files[0]);
    $('#element').append('<a href="' + file + '" target="_blank">' + event.target.files[0].name + '</a><br>');
});

  $("#applSearch").click(function(){
     if($("#applTypeName").val()=='')
     {
    $('#applTypeName').parsley().removeError('applTypeName');
    $('#applTypeName').parsley().addError('applTypeName', {message: "Select Application Type"});
    return false;
     }
    else
    {
      $('#applTypeName').parsley().removeError('applTypeName');
    }
    if($("#applicationId").val()=='')
    {
      $('#applicationId').parsley().removeError('applicationId');
      $('#applicationId').parsley().addError('applicationId', {message: "Enter Application No"});
      return false;
    }
      else
    {
      $('#applicationId').parsley().removeError('applicationId');
    }
    var modl_appltype_name = $("#applTypeName").val();
    var newtype = modl_appltype_name.split('-');
    var applnewtype = newtype[1];
    var modl_modl_applno = $("#applicationId").val();
    var applicationId =applnewtype+'/'+modl_modl_applno;
    
    var flag='application';
    $.ajax({
    type: 'POST',
    url: 'getApplicationDetails1',
    data: {"_token": $('#token').val(),applicationid:applicationId},
    dataType: "JSON",
    success: function (json) {
      if(json.length>0)
      {
        for (var i = 0; i < json.length; i++) {
          if(json[i].registerdate===null){
                    $("#applnRegDate").val('');
          }
          else
          {
            var dor = json[i].registerdate;
            var dor_split = dor.split('-');
            var dateOfReg = dor_split[2]+'-'+dor_split[1]+'-'+dor_split[0];
            $("#applnRegDate").val(dateOfReg);  
          }
          if(json[i].applicationdate==null)
          {
          
            $("#dateOfAppl").val('');
          }
          else
          { 
            var doa = json[i].applicationdate;
            var doa_split = doa.split('-');
            var dateOfApp = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
            $("#dateOfAppl").val(dateOfApp);
          }
          if(json[i].disposeddate==null)
          {
          
            $("#applnJudgementDate").val('');
          }
          else
          { 
            var doa = json[i].disposeddate;
            var doa_split = doa.split('-');
            var dodisposed = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
            $("#applnJudgementDate").val(dodisposed);
          }
          
          
          $("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
          $("#applCatName").val(json[i].applcategory);
          $("#applnSubject").val(json[i].subject);
         
                 

          
        }
        
      }
      else
      {
        swal({
        title: "Application not found",

        icon: "error"
        })
        $("#applnRegDate").val('');
        $("#dateOfAppl").val('');
        $("#applTypeName").val('');
        $("#applCatName").val('');
        $("#applnSubject").val('');
        $("#applicationId").val('');
      }
    }
  });


});



$('.btnClear').click(function(){
  $("#uploadjudgements").trigger('reset');
  $('#element').empty();
})

});


  $('#uploadOrderForm').submit(function(e) 
   {
     e.preventDefault();
     
        swal({
        title: "Are you sure to save?",
        icon: "warning",
        buttons:[
            'Cancel',
            'OK'
            ],
            }).then(function(isConfirm){        
                if(isConfirm){ 
                    $('#uploadOrderForm').submit();
                } 
        });
        
       
    });


$(document).ready(function() {
              $('#orderdate').on('change', function() {
                  var hearingdate = $(this).val();
                  var applicationtype=$('#applTypeName').val();
                  var result= applicationtype.split('-');
                  var appltypeshort=result[1];
                  var appnum=$('#applicationId').val();
                  var applicationid=appltypeshort+'/'+appnum;
                 console.log(applicationid);
                  if(hearingdate) {
                      $.ajax({
                          url: "/findJudgeWithBenchCode2",
                          type: "get",
                         data: {
          "_token": $('#token').val(),
          applicationid: applicationid,hearingdate:hearingdate
          },
                         
                          success:function(data) {
                              console.log(data);
                            if(data){
                              $('#benchname').empty();
                              $('#benchname').focus;
                              $('#benchname').append('<option value="">Select Bench Name</option>');

                              $.each(data, function(key, value){
                                console.log(key);
                                console.log(value);
                             $('select[name="benchname"]').append('<option value="'+ value.benchcode  +'">' + value.judgeshortname+ ' : '+ value.courthalldesc +  ' : '+ 'List No '+ value.listno + '</option>');

                          });
                        }

                        else{
                          $('benchname').empty();
                        }
                        }
                      });
                  }else{
                    $('benchname').empty();
                  }
              });
          });
