<?php if($flag=='E'){?> <?php


 }else{?>
  <script src="js/jquery.min.js"></script>
  <?php }?>

  <?php if(count($Temp)){
    $address = str_replace("\r\n", '\n', $Temp[0]->serviceaddress);
     if(!empty($Temp[0]->againstorders))
     {

      $againstorder = str_replace("\r\n",'\n',$Temp[0]->againstorders);
     }
     else
     {
      $againstorder ="";
     }
    $subject = str_replace("\r\n", '\n', $Temp[0]->subject);
    if(!empty($Temp[0]->interimprayer))
    {
        $interimprayer = str_replace("\r\n", '\n',$Temp[0]->interimprayer);
    }
    else
    {
      $interimprayer='';
    }
    if(!empty($Temp[0]->remarks))
    {
        $remarks = str_replace("\r\n", '\n',$Temp[0]->remarks);
    }
    else
    {
      $remarks='';
    }
    //add code for parayer cnhanges 15-07-2019
    if(!empty($Temp[0]->iaprayer))
    {
        $iaprayer = str_replace("\r\n", '\n',$Temp[0]->iaprayer);
    }
    else
    {
      $iaprayer='';
    }
    ?>
{{--
  <script src="js/jquery.min.js"></script> --}}

  <script type="text/javascript">

$(document).ready(function(){



  <?php if($flag=='E'){?>
//change by raj to indicate edit even if no
$("#relief_value2").val('Edit');
$(".Originalapplid").val("<?php echo $Temp[0]->applicationid; ?>");
 $("#recAppId").val("<?php echo $Temp[0]->applicationid; ?>" );

   <?php }else{?>
	swal("Previous Entered Application was not saved,Click Ok to Recover Details");
   <?php }?>
//$("#applTypeName").css('pointer-events', 'none');
$("#multiorder").val("<?php echo $againstorder;?>");
$("#caseremarks").val("<?php echo $remarks;?>");

  //alert("Previous Entered Application was not saved,Click ok to Recover Details");
  $("#canelid").val("<?php echo $Temp[0]->applicationid ?>");
  $(".Originalapplid").val("<?php echo $Temp[0]->applicationid; ?>");
$("#addrForService").val("<?php echo $address;?>");
$("#applTypeName").val("<?php echo $Temp[0]->appltypecode.'-'.$Temp[0]->appltypeshort.'-'.$Temp[0]->feerequired.'-'.$Temp[0]->referflag; ?>");
//$("#applTypeName").attr('readonly',true);
//$("#applTypeName").trigger('change');
$("#dateOfAppl").val("<?php echo date('d-m-Y',strtotime($Temp[0]->applicationdate)); ?>");
$("#datepicker1").val("<?php echo $Temp[0]->applicationyear; ?>");
$("#applStartNo").val("<?php echo $Temp[0]->applicationsrno; ?>");
$("#applEndNo").val("<?php echo $Temp[0]->applicationtosrno; ?>");
$('#applnRegDate').datepicker('setStartDate', "<?php echo date('d-m-Y',strtotime($Temp[0]->applicationdate)); ?>");

$('#orderDate').datepicker('setEndDate', "<?php echo date('d-m-Y',strtotime($Temp[0]->applicationdate)); ?>");
$("#applnRegDate").val("<?php echo date('d-m-Y',strtotime($Temp[0]->registerdate)); ?>");
$("#applCatName").val("<?php echo $Temp[0]->applcategory; ?>");
$("#applnSubject").val("<?php echo $subject; ?>");
 $("#actName").val("<?php echo $Temp[0]->actcode; ?>");
var actcode = <?php echo $Temp[0]->actcode; ?>;
//console.log(actcode);
$.ajax({
        type: 'post',
        url: 'getSections',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),typeval:actcode},
        success: function (data) {
        console.log(data);
        $('#actSectionName').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].actsectioncode+'">'+data[i].actsectionname+'</option>';
  					 $('#actSectionName').append(option);
				 }
        	}
        });

//$("#actSectionName").val("<?php echo $Temp[0]->actsectioncode; ?>");



var relief = "<?php echo $Temp[0]->isinterimrelief; ?>";
if(relief=='Y')
{


  $("#interimPrayer").prop("checked", true);
  $("#interimOrder").val("<?php echo $interimprayer; ?>");

  $('#interimOrderDiv1').show();

}
else
{


   $("#interimPrayer").prop("checked", false);
  $("#interimOrder").val("");
  $('#interimOrderDiv1').hide();

  //  $('#interimOrder').attr('data-parsley-required', 'false');

}

  $("#IAprayer").val("<?php echo $iaprayer; ?>");
  $("#IANature").val("<?php  echo $Temp[0]->ianaturecode; ?>");


$('input[name="interimPrayer"]').click(function(){
if($('input[name="interimPrayer"]').is(':checked'))
{

//$('#interimOrder').attr('data-parsley-required', 'true');
$('#interimOrderDiv1').show();

}else
{

//$('#interimOrder').attr('data-parsley-required', 'false');
$('#interimOrderDiv1').hide();

}
})
/*end of change */










$("#rPincode").val("<?php echo $Temp[0]->servicepincode; ?>");
$("#noOfAppl").val("<?php echo $Temp[0]->applicantcount; ?>");
$("#rDistrict").val("<?php echo $Temp[0]->servicedistrict; ?>");

var distCode = <?php  if($Temp[0]->servicedistrict =='') echo 0; else echo ($Temp[0]->servicedistrict) ; ?>;

var talukarr = <?php echo $taluka3; ?>;
for(i=0;i<talukarr.length;i++)
{

      if(distCode==talukarr[i].distcode)
      {
          var option = '<option value="'+talukarr[i].talukcode+'" selected>'+talukarr[i].talukname+'</option>';
          $('#rTaluk').append(option);
      }
      else
      {
          var option = '<option value="'+talukarr[i].talukcode+'">'+talukarr[i].talukname+'</option>';
          $('#rTaluk').append(option);
      }

}
$("#rTaluk").val("<?php echo $Temp[0]->servicetaluk;?>")

$("#noOfRes").val("<?php echo $Temp[0]->respondentcount; ?>");

$('#sbmt_case').attr('value','U');
if($("#sbmt_case").val()=='U')
{
  $("#dateOfAppl").attr('readonly','readonly');
  //$("#dateOfAppl").datepicker("destroy");
  $("#dateOfAppl").css('pointer-events', 'none');
  $("#datepicker1").css('pointer-events', 'none');
  $("#applStartNo").attr('readonly','readonly');
  //$("#applEndNo").attr('readonly','readonly');
}
else
{
  $("#dateOfAppl").attr('readonly','readonly');
  //$("#dateOfAppl").datepicker("destroy");
  $("#dateOfAppl").css('pointer-events', 'block');
  $("#datepicker1").css('pointer-events', 'block');
  $("#applStartNo").removeAttr("readonly");
  //$("#applEndNo").removeAttr("readonly");
}
//$("#sbmt_case").val('U');
})


  </script>
   <?php
  }else
  {
    //echo "temp appl empty";?>
    <script type="text/javascript">
$("#applTypeName").val("");
$("#dateOfAppl").val("");
$("#datepicker1").val("");
$("#applStartNo").val("");
$("#applEndNo").val("");
$("#applnRegDate").val("");
$("#applCatName").val("");
$("#applnSubject").val("");

  $("#IAprayer").val("");
  $("#IANature").val("");
$("#interimPrayer").prop("checked", true);
$("#interimOrderDiv").show();
$("#interimOrder").val("");

$("#addrForService").val("");
$("#rPincode").val("");
$("#noOfAppl").val("");
$("#rDistrict").val("");
$("#rTaluk").val("");
$("#noOfRes").val("");


</script>
<?php }?>
<?php if(count($Temprelief))
{ //echo " Temp relief not empty";
  foreach($Temprelief as $relief) {?>

   <script type="text/javascript">
   $(document).ready(function(){
/*$('#example1').find('tbody tr').remove();
var count = 1;*/
 // $("#relief_value").val("U");
var response = <?php echo json_encode($Temprelief) ?>;

 getReliefTable(response);

        $(".reliefClick").click(function()
        {

          var srno  = $(this).attr('data-value');
                var newarr = srno.split('-');
                var newReliefSrNo = newarr[0];
                var applId = newarr[1];
                    $.ajax({
                    type: 'post',
                    url: "getreliefData",
                    dataType:"JSON",
                    data: {"_token": $('#token').val(),newSrno:newReliefSrNo,applId:applId},
                    success: function (json1) {
                    for(var i=0;i<json1.length;i++){
                    $("#reliefcount").val(json1[i].reliefsrno);
                    $("#reliefsought").val(json1[i].relief);
                    $("#relief_value").val('UP');
                    $("#sbmt_relief").text('Update');
                    $("#refapplId").val(json1[i].applicationid);
                    }
                    }
                    });
        });

function getReliefTable(response)
    {
      $('#myTable').find('tbody tr').remove();

    var count = 1;
     var rowcount = response.length;
   // console.log(rowcount);
     $.each(response, function(index, obj) {
                  var row = $('<tr>');
                  //  row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
                  row.append('<td class="col-sm-1"><a href="#" class="reliefClick" data-value="'+obj.reliefsrno+'-'+obj.applicationid+'">' + obj.reliefsrno + '</td>');
                  row.append('<td class="col-sm-10">' +obj.relief + '</td>');
                  if(obj.reliefsrno!=1)
                  {

                     row.append('<td class="col-md-2"><a href="#" class="deleterelief btn btn-sm btn-danger"  type="button" data-value="' + obj.reliefsrno+'-'+obj.applicationid + '">X</a></td>');
                  }
                  else
                  {
                     row.append('<td class="col-md-2"></td>');
                  }


                  $('#myTable').append(row)
                  count++;
                    });
      $(".deleterelief").click(function(){
         var reliefsrno = $(this).attr('data-value');
                  var split = reliefsrno.split('-');
                var reliefsrno1 = split[0];
                 var applId =split[1];
          $.ajax({
        type: 'POST',
        url: 'deleterelief',
        dataType: 'JSON',
        data:{"_token": $('#token').val(),reliefsrno:reliefsrno1,applicationid:applId},
        cache: false,
        success: function(response) {

          if(response.status=="sucess")
          {

           var relief_value='';
            $.ajax({
            type: 'POST',
            url: 'getRelief',
            dataType: 'JSON',
            data:{"_token": $('#token').val(),relief_value:relief_value,applId:applId},
            cache: false,
            success: function(response) {
            getReliefTable(response);
            }});
            }
          else
          {
           swal({
            title:response.message,

            icon: "error",
            });
          }
        }
        });
        });
    }
});
  </script>

 <?php } }else
{
 // echo " Temp relief  empty";
 ?>
<?php }?>
<?php if(!$TempApplicant->isEmpty()){ ?>
<script type="text/javascript">
$(document).ready(function(){

$('#example2').find('tbody tr').remove();
var count = 1;
var ApplicantArr = <?php echo json_encode($TempApplicant) ?>;
$.each(ApplicantArr, function (i, elem) {
var row = $('<tr>');
row.append('<td><a href="#" data-value="'+elem.applicantsrno+'-'+elem.applicationid+'" class="applicantClick" >' + elem.applicantsrno + '</td>');
row.append('<td>' +elem.applicantname + '</td>');
if(elem.advocatename==null)
{
row.append('<td>' + '---' + '</td>');
}
else
{
row.append('<td>' + elem.advocatename + '</td>');
}

$('#example2').append(row)
count++;
});
$(".applicantClick").click(function(){
                $("#sbmt_applicant").val('U');
                $("#saveApplicant").val('Update List');
                //$(this).closest('form').find("input[type=text], textarea").val("");
                /**/
                var newSrno1  = $(this).attr('data-value');
                var newSrnoarr = newSrno1.split('-');
                var newApllSrno = newSrnoarr[0];
                var newApplid = newSrnoarr[1];
                //alert(newApplid+newApllSrno);


                $.ajax({
                type: 'post',
                url: "getApplicantData",
                dataType:"JSON",
                data: {"_token": $('#token').val(),newSrno:newApllSrno,applicationid:newApplid},
                success: function (json1) {

                  for(var i=0;i<=json1.length;i++){

                    $(".advancedSearch2 .selection2").text(json1[i].nametitle);
                      $(".title_sel2").css('display','none');

                      $(".advancedSearch3 .selection3").text(json1[i].relationtitle);
                      $(".title_sel3").css('display','none');


                   // console.log(json1[i].nametitle);
                       $('input[name^="isAdvocate"][value="'+json1[i].issingleadv+'"').prop('checked',true);

                    $("#applApplId").val(json1[i].applicationid);
                    $("#applicantStartSrNo").val(json1[i].applicantsrno);
                    $("#applicantTitle").val(json1[i].nametitle);
                    $("#applicantName").val(json1[i].applicantname);

                    $("#relationTitle").val(json1[i].relationtitle);
                    $("#relationName").val(json1[i].relationname);

                    //console.log(json1[i].relation+'-'+json1[i].gender);
                     $("#relationType").val(json1[i].relation);
                    $("#gender").val(json1[i].gender);
                    $("#applAge").val(json1[i].applicantage);
                    $("#applDeptType").val(json1[i].depttype);
                    $("#nameOfDept").val(json1[i].departcode);
                    $("#desigAppl").val(json1[i].desigcode);
                    $("#addressAppl").val(json1[i].applicantaddress);
                    $("#pincodeAppl").val(json1[i].applicantpincode);
                    $("#talukAppl").val(json1[i].talukcode);
                    $("#districtAppl").val(json1[i].districtcode);
                    $("#applMobileNo").val(json1[i].applicantmobileno);
                    $("#applEmailId").val(json1[i].applicantemail);
                   $('input[name^="partyInPerson"][value="'+json1[i].partyinperson+'"').prop('checked',true);
                   $('input[name^="isMainParty"][value="'+json1[i].ismainparty+'"').prop('checked',true);





                     var partyinperson = $("input[type=radio][name='partyInPerson']:checked").val();
                    // console.log(partyinperson);
                            if (partyinperson == 'Y') {

                              $(".advDetails").hide();
                              $("#advBarRegNo").attr('data-parsley-required', false);
                            } else {

                              $(".advDetails").show();
                              $("#advBarRegNo").attr('data-parsley-required', true);
                              value = $("#advBarRegNo").val(json1[i].advocateregno);

                              getBarregDetails(value);
                            }



                    }
                }
                });
              });

    function getBarregDetails(value)
  {
    //console.log(value);
     $.ajax({
      type: 'POST',
      url: 'advRegNo',
      data:  { "_token": $('#token').val(),value:value},
      dataType: "JSON",
      success: function (json) {
        //console.log(json);
          for(var i=0;i<json.length;i++){
            //console.log(json[i].nameTitle);
            $(".advancedSearch4 .selection4").text(json[i].nametitle);
          $(".title_sel4").css('display','none');
          $("#advTitle").val(json[i].nametitle);
          $("#advName").val(json[i].advocatename);
          $("#advRegAdrr").val(json[i].advocateaddress);
          $("#advRegTaluk").attr('disabled', false);
          $("#advRegDistrict").attr('disabled', false);
          $("#advRegTaluk").empty();
          $("#advRegDistrict").empty();
          $("#advRegTaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
          $("#advRegDistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '<option>');
          $("#advRegPin").val(json[i].pincode);
        }
      }
  });
  }

})

</script>
<?php }?>

<?php if(!$TempReceipt->isEmpty()){  ?>
<script type="text/javascript">
$(document).ready(function(){
/*$('#example1').find('tbody tr').remove();
var count = 1;*/
var response = <?php echo json_encode($TempReceipt) ?>;
  getRecpTable(response);

        $(".receiptClick").click(function()
        {$('#receiptNo').prop('readonly', true);

          $("#sbmt_value").val('U');
          $("#recpSubmit").val('Update List');
          var receiptno  = $(this).attr('data-value');

          $.ajax({
          type: 'POST',
          url: 'getReceipt',
          data:  { "_token": $('#token').val(),receiptno:receiptno},
          dataType: "JSON",
          success: function (json) {

          for(var i=0;i<json.length;i++){
            var recNo = json[i].receiptno;
            var arr = recNo.split('/');
            var recpdate = json[i].receiptdate;
            var arr1 = recpdate.split('-');
           // console.log(recNo);
          $("#receiptNo").val(arr[2]);
          $("#recAppId").val(json[i].applicationid);
          $("#receiptDate").val(arr1[2]+'-'+arr1[1]+'-'+arr1[0]);
          $(".advancedSearch1 .selection1").text(json[i].titlename);
          $(".title_sel1").css('display','none');
          $("#applTitle").val(json[i].titlename);
          $("#receiptSrno").val(json[i].receiptsrno);
          $("#applName").val(json[i].name);
          $("#recpAmount").val(json[i].amount);
          }

          }
          });
        });
        $(".deleteRow").click(function(){
          var receiptsrno  = $(this).attr('data-value');
          $.ajax({
        type: 'POST',
        url: 'deleterecp',
        dataType: 'JSON',
        data:{"_token": $('#token').val(),receiptsrno:receiptsrno,applicationid:$("#recAppId").val()},
        cache: false,
        success: function(response) {

          if(response.status=="sucess")
          {


            $.ajax({
            type: 'POST',
            url: 'receipts',
            dataType: 'JSON',
            data:{"_token": $('#token').val(),application_id:$("#recAppId").val()},
            cache: false,
            success: function(response) {
            getRecpTable(response);
            }});
            }
          else
          {
           swal({
            title:response.message,

            icon: "error",
            });
          }
        }
        });
        });
function getRecpTable(response)
    {
      $('#example1').find('tbody tr').remove();
    var count = 1;
    $.each(response, function(index, obj) {
    var row = $('<tr>');


var recpdate = obj.receiptdate;
          var arr1 = recpdate.split('-');


    //  row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
    row.append('<td class="col-md-2"><a href="#" data-value="'+obj.receiptsrno+'" >' + obj.receiptno + '</td>');
    row.append('<td class="col-md-2">'+arr1[2]+'-'+arr1[1]+'-'+arr1[0]+'</td>');
    row.append('<td class="col-md-2">' +obj.name + '</td>');

    row.append('<td class="col-md-2">' + obj.amount + '</td>');

    row.append('<td class="col-md-2"><a href="#" class="deleteRow btn btn-sm btn-danger"  type="button" data-value="'+obj.receiptsrno+'">X</a></td>');

    $('#example1').append(row)
    count++;
    })
    }
});
</script>
<?php }?>
<?php if(!$TempRespondant->isEmpty()){ ?>
<script type="text/javascript">
  $(document).ready(function(){
  $('#example3').find('tbody tr').remove();
var count = 1;
var RespondantArr = <?php echo json_encode($TempRespondant) ?>;
              $.each(RespondantArr, function(index, obj) {
              var row = $('<tr>');
              //  row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
              row.append('<td><a href="#" data-value="'+obj.respondsrno+'-'+obj.applicationid+'" class="respondantClick" >' + count + '</td>');
              row.append('<td>' +obj.respondname + '</td>');
              row.append('<td>' + obj.advocatename + '</td>');

              $('#example3').append(row)
              count++;
              });
              $(".respondantClick").click(function(){
                $("#sbmt_respondant").val('U');
                $("#saveRespondant").val('Update List');
                //$(this).closest('form').find("input[type=text], textarea").val("");
                /**/
                var newSrno1  = $(this).attr('data-value');
                var newSrnoarr = newSrno1.split('-');
                var newApllSrno = newSrnoarr[0];
                var newApplid = newSrnoarr[1];
                //alert(newApplid+newApllSrno);
                $.ajax({
                type: 'post',
                url: "getRespondantData",
                dataType:"JSON",
                data: {"_token": $('#token').val(),newSrno:newApllSrno,applicationid:newApplid},
                    success: function (json1) {
                      for(var i=0;i<json1.length;i++){
                        $('#isAdvocate').find(':radio[name=isAdvocate][value="'+json1[i].issingleadv+'"]').attr('checked', true);
                          $(".advancedSearch5 .selection5").text(json1[i].respondtitle);
                          $(".title_sel5").css('display','none');
                          $("#respondantTitle").val(json1[i].respondtitle);
                          $("#respondantName").val(json1[i].respondname);
                           //var relvalue = json1[i].relation+'-'+json1[i].gender;

                          $("#resReltaion").val(json1[i].relation);

                         // $("#resReltaion >  [value='" + relvalue +"']").attr("selected",true);
                          $(".advancedSearch6 .selection6").text(json1[i].relationtitle);
                          $(".title_sel6").css('display','none');
                          $("#resApplId").val(json1[i].applicationid);

                          $("#resStartNo").val(json1[i].respondsrno);


                          $("#resRelTitle").val(json1[i].relationtitle);
                          $("#resRelName").val(json1[i].relationname);
                          $("#resGender").val(json1[i].gender);
                          $("#resAge").val(json1[i].respondantage);
                          $("#resDeptType").val(json1[i].respontdepttype);
                          $("#resnameofDept").val(json1[i].respontdeptcode);
                          $("#resDesig").val(json1[i].desigcode);
                          $("#resAddress2").val(json1[i].respondaddress);
                          $("#respincode2").val(json1[i].respondpincode);
                          $("#resTaluk").val(json1[i].respondtaluk);
                          $("#resDistrict").val(json1[i].responddistrict);
                          $("#resMobileNo").val(json1[i].respondmobileno);
                          $("#resEmailId").val(json1[i].respondemail);
                          $("#isGovtAdv").val(json1[i].isgovtadvocate);
                          $("#isMainRes").val(json1[i].ismainrespond);
                          $("#resadvBarRegNo").val(json1[i].advocateregno);
                          value = $("#resadvBarRegNo").val(json1[i].advocateregno);
                        getResBarRegDetails(value);
                       }
                }
                });
              });
 function getResBarRegDetails(value)
{
  //console.log("in res");
  $.ajax({
type: 'POST',
url: 'advRegNo',
data:  { "_token": $('#token').val(),value:value},
dataType: "JSON",
success: function (json) {

    for(var i=0;i<json.length;i++){
    $(".advancedSearch7 .selection7").text(json[i].nametitle);
    $(".title_sel7").css('display','none');
    $("#respAdvTitle").val(json[i].nametitle);
    $("#respAdvName").val(json[i].advocatename);
    $("#resadvaddr").val(json[i].advocateaddress);
    $("#resadvtaluk").attr('disabled', false);
    $("#resadvdistrict").attr('disabled', false);
    $("#resadvtaluk").empty();
    $("#resadvdistrict").empty();
    $("#resadvtaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
    $("#resadvdistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '</option>');
    $("#resadvpincode").val(json[i].pincode);
    }
    }});
}
})
  $("#resadvBarRegNo").on('change',function(){
    var value = $("#resadvBarRegNo").val();

//var text = $("#browsers1").find('option[value=' + value + ']').text();
$.ajax({
type: 'POST',
url: 'advRegNo',
data:  { "_token": $('#token').val(),value:value},
dataType: "JSON",
success: function (json) {

    for(var i=0;i<json.length;i++){
    $(".advancedSearch7 .selection7").text(json[i].nametitle);
    $(".title_sel7").css('display','none');
    $("#respAdvTitle").val(json[i].nametitle);
    $("#respAdvName").val(json[i].advocatename);
    $("#resadvaddr").val(json[i].advocateaddress);
    $("#resadvtaluk").attr('disabled', false);
    $("#resadvdistrict").attr('disabled', false);
    $("#resadvtaluk").empty();
    $("#resadvdistrict").empty();
    $("#resadvtaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukname + '</option>');
    $("#resadvdistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distname + '</option>');
    $("#resadvpincode").val(json[i].pincode);
    }
    }});
});


  $("#advBarRegNo").on('change',function(){
  var value = $("#advBarRegNo").val();
  getBarregDetails(value)
  //var text = $("#browsers").find('option[value=' + value + ']').text();

});



</script>
<?php } ?>
<?php if(!$TempApplTypeRefer->isEmpty()){ ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#modal-default').on('hidden.bs.modal','.modal',function () {
  $(this).removeData('bs.modal');
});
  var applType = $("#applTypeName").val();
  var appltype1 = applType.split('-');
  if(appltype1[1]!='OA')
  {
      var refer = <?php echo json_encode($TempApplTypeRefer) ?>;
             for (var i = 0; i < refer.length; i++) {
             if(appltype1[1] == refer[i].appltypeshort)
             {

                $("#reviewAppl").text(refer[i].referapplid);
               $("#reviewApplId1").val(refer[i].referapplid);
               $("#reviewApplId").val(refer[i].referapplid);
            
             }

          }
  }
  else
  {
    $("#reviewAppl").text('');
    $("#reviewApplId1").val('');
    $("#reviewApplId").val('');
  }

  })
</script>
<?php }?>
<?php if(!$ApplicationIdex->isEmpty()){?>
<script type="text/javascript">
  $(document).ready(function(){
 $("#applIndexTbl tbody tr").remove();
var applIndex = <?php echo json_encode($ApplicationIdex) ?>;
counter=1;
             for (var i = 0; i < applIndex.length; i++) {
//console.log(applIndex[i].startpage);
            var newRow = $("<tr>");
            var cols = "";

          /*  cols += '<td class="col-sm-1"><input type="hidden" name="count" class="counter" value="' + counter + '">' + counter + '</td>;';*/
            cols += '<td class=""col-xs-4""><textarea type="text" class="form-control number" data-parsley-required data-parsley-required-message="Enter Service Address" name="partOfDoc[]">'+applIndex[i].documentname+'</textarea></td>';
            cols += '<td class="col-xs-1"><input type="number"  data-parsley-required data-parsley-required-message="Enter Start No." class="form-control number" name="start[]"  value="'+applIndex[i].startpage+'"></td>';
            cols += '<td class="col-xs-1"><input type="number"  data-parsley-required data-parsley-required-message="Enter End No."  class="form-control  number" name="endPage[]" value="'+applIndex[i].endpage+'"></td>';
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td></tr>';
            newRow.append(cols);

            $("table.application-list").append(newRow);
             counter++;

             }
             $("#applIndex_up_value").val('U');
              $("#relief_value2").val('Edit');



  })
</script>
<?php } ?>
