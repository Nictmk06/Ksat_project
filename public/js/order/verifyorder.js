
$(document).ready(function() {

$("#downloadjudgementdiv").hide();

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
    $("#downloadjudgementdiv").hide();
    var flag='application';
    $.ajax({
    type: 'POST',
    url: 'getOrderDetails',
    data: {"_token": $('#token').val(),applicationid:applicationId},
    dataType: "JSON",
    success: function (json) {
      if (json.status == "fail") {
          swal({
          title: json.message,
            icon: "error",
            });
        $("#applnRegDate").val('');
        $("#dateOfAppl").val('');
        $("#applTypeName").val('');
        $("#applCatName").val('');
        $("#applnSubject").val('');
        $("#applicationId").val('');
         } 
    else{
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
          if(json[i].orderdate==null)
          {
          
            $("#orderdate").val('');
          }
          else
          { 
            var doa = json[i].orderdate;
            var doa_split = doa.split('-');
            var dateOfOrd = doa_split[2]+'-'+doa_split[1]+'-'+doa_split[0];
            $("#orderdate").val(dateOfOrd);
          }
          //$("#applTypeName").val(json[i].appltypecode+'-'+json[i].appltypeshort);
          $("#applCatName").val(json[i].applcategory);
          $("#applnSubject").val(json[i].subject);
          $("#ordertype").val(json[i].ordertypecode);
          $("#downloadjudgementdiv").show();
          
              }
        
      }
      else
      {
        swal({
        title: "Application is  already disposed or Order is not uploaded",
        icon: "error"
        })
        $("#applnRegDate").val('');
        $("#dateOfAppl").val('');
        $("#applTypeName").val('');
        $("#applCatName").val('');
        $("#applnSubject").val('');
        $("#applicationId").val('');
    }}
    }
  });
});

 $('#viewjudgement').click(function() {
        $('#verifyJudgementsForm').submit();
    });

$('#VerifyJudgement').click(function(e){
  if (!$('input[name="declaration"]').is(':checked')) {
      alert("Please select declaration !");
      return false;
    } else{     
           var type = $("#applTypeName").val();
      var newtype = type.split('-');
      var applicationId = newtype[1]+'/'+$("#applicationId").val();
      e.preventDefault();
        swal({
      title: "Are you sure ?",
      icon: "warning",
      showCancelButton: true,
      buttons: true,
      dangerMode: true,
      })
    .then((willDelete) => {
        if (willDelete) {   
    $.ajax({
      type: 'post',
      url: "verifyOrder",
      dataType:"JSON",
      data: {"_token": $('#token').val(),applicationId:applicationId},
      success: function (data) {
                if (data.status == "sucess") {
          swal({
          title: data.message,
          icon: "success",
            });
          $("#verifyJudgementsForm").trigger('reset');
          $("#downloadjudgementdiv").hide();
          } else if (data.status == "fail") {
            swal({
              title: data.message,
            icon: "error",
              });
          }   }
       })
    }
  });
  }  
  } )



$('.btnClear').click(function(){
  $("#verifyJudgementsForm").trigger('reset');
  $("#downloadjudgementdiv").hide();
    
})

});
