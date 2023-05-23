<?php if($flag=='E'){?> <?php }else{?>
  <script src="js/jquery.min.js"></script>
  <?php }?>

  <?php if(count($Temp)){
    $address = str_replace("\r\n", '\n', $Temp[0]->serviceaddress);
          if(!empty($Temp[0]->goorderno))
     {

      $againstorder = str_replace("\r\n",'\n',$Temp[0]->goorderno);
     }
     else
     {
      $againstorder ="";
     }
    $subject = str_replace("\r\n", '\n', $Temp[0]->subject);
	//$againstorder = $Temp[0]->goorderno;
	$caveatid = $Temp[0]->caveatid;
    ?>

{{--
  <script src="js/jquery.min.js"></script> --}}

  <script type="text/javascript">

$(document).ready(function(){
  <?php if($flag=='E'){?> <?php }else{?>
 swal("Previous Entered Application was not saved,Click Ok to Recover Details");
   <?php }?>
//alert("EDITITING");
$("#applTypeName").css('pointer-events', 'none');
$("#multiorder").val("<?php echo $againstorder;?>");
$("#applStartNo").val("<?php echo $Temp[0]->caveatsrno; ?>");
$("#dateOfAppl").val("<?php echo date('d-m-Y',strtotime($Temp[0]->caveatfiledate)); ?>");
$("#datepicker1").val("<?php echo $Temp[0]->caveatyear; ?>");
$("#noOfAppl").val("<?php echo $Temp[0]->caveatorcount; ?>");
document.getElementById('noOfAppl').readOnly = true;
$("#noOfRes").val("<?php echo $Temp[0]->caveateecount; ?>");
document.getElementById('noOfRes').readOnly = true;
$("#distcode").val("<?php echo $Temp[0]->servicedistrict; ?>");
$("#rPincode").val("<?php echo $Temp[0]->servicepincode; ?>");
$("#addrForService").val("<?php echo $address;?>");
$("#applCatName").val("<?php echo $Temp[0]->applcatcode; ?>");
$("#caseremarks").val("<?php echo $Temp[0]->remarks; ?>");
  //alert("Previous Entered Application was not saved,Click ok to Recover Details");

$("#applnSubject").val("<?php echo $Temp[0]->subject; ?>");
var distCode = <?php echo $Temp[0]->servicedistrict; ?>;

var talukarr = <?php echo $taluka3; ?>;
for(i=0;i<talukarr.length;i++)
{

      if(distCode==talukarr[i].distcode)
      {
          var option = '<option value="'+talukarr[i].talukcode+'" selected>'+talukarr[i].talukname+'</option>';
          $('#talukname').append(option);
      }
      else
      {
          var option = '<option value="'+talukarr[i].talukcode+'">'+talukarr[i].talukname+'</option>';
          $('#talukname').append(option);
      }

}
$("#talukname").val("<?php echo $Temp[0]->servicetaluk;?>")


})
/*end of change */
$("#sbmt_case").val('U');



  </script>

  <?php }?>
<?php if(count($TempApplicant)){?>
	<script type="text/javascript">

$(document).ready(function(){

$('#applicant_tab').find('tbody tr').remove();
var count = 1;
var ApplicantArr = <?php echo json_encode($TempApplicant) ?>;
//alert('Applicant');
//alert(ApplicantArr);
console.log(ApplicantArr);
$.each(ApplicantArr, function (i, elem) {
var row = $('<tr>');
//row.append('<td>' +elem.applicantsrno+ '</td>');
row.append('<td align="center"><a href="#" data-value="'+elem.applicantsrno+'-'+elem.caveatid+'" class="applicantClick" >' + elem.applicantsrno + '</td>');
row.append('<td align="center"><a href="#" data-value="'+elem.applicantsrno+'-'+elem.caveatid+'" class="applicantClick" >' + elem.caveatid + '</td>');
row.append('<td align="center">' +elem.applicantname + '</td>');
if(elem.advocateregnno==null)
{
row.append('<td>' + '---' + '</td>');
}
else
{
row.append('<td align="center">' + elem.advocateregnno + '</td>');
}

$('#applicant_tab').append(row)
count++;
});
$(".applicantClick").click(function(){
                $("#sbmt_applicant").val('U');
                $("#saveApplicant").val('Update List');
                //$(this).closest('form').find("input[type=text], textarea").val("");
                /**/
                var newSrno1  = $(this).attr('data-value');
                //alert(newSrno1);
                var newSrnoarr = newSrno1.split('-');
                //alert(newSrnoarr);
				var newApllSrno = newSrnoarr[0];
                //alert(newApllSrno);
                var newApplid = newSrnoarr[1];
                //alert(newApplid);
                //alert(newApplid+newApllSrno);
				var _token = $('input[name="_token"]').val();
				$("#srno_applicant").val(newApllSrno);

                $.ajax({
                type: 'post',
                url: "getCaveatApplicantData",
                dataType:"JSON",
                data: {"_token": _token,newSrno:newApllSrno,applicationid:newApplid},
                success: function (data) {
					//alert('success');
					var len = data.length;
					//alert(len);
					for(var i=0;i<=data.length;i++){
                    //alert('success');
					var applname = data[i].applicantname;
					//alert(applname);
                    //$("#addressAppl").val(data[i].applicantsrno);
					$("#applicantName").val(data[i].applicantname);
					$("#relType").val(data[i].reltype);
					$("#relationName").val(data[i].relationname);
					$("#gender").val(data[i].gender);
					$("#depttypecode").val(data[i].depttype);
					$("#addressAppl").val(data[i].caveataddress);
					$("#pincodeAppl").val(data[i].caveatpincode);
					$("#adistcode").val(data[i].districtcode);
					var option = '<option value="'+data[i].talukcode+'" selected>'+data[i].talukname+'</option>';
					$('#taluknameApp').append(option);
					//$("#taluknameApp").val(data[i].talukcode);
					$("#applMobileNo").val(data[i].caveatmobileno);
					$("#applEmailId").val(data[i].caveatemail);
					$("#partyInPerson").val(data[i].partyinperson);
					$("#advBarRegNo").val(data[i].advocateregnno);
					$("#applAge").val(data[i].age);
					$("#desigAppl").val(data[i].desigcode);
					var option1 = '<option value="'+data[i].departcode+'" selected>'+data[i].departmentname+'</option>';
					$("#departmentcode").append(option1);
					//$("#Appdepart").apend('<option value="'+data[i].departcode+'" selected>'+data[i].departmentname+'</option>');
					$(".advancedSearch2 .selection2").text(data[i].applicanttitle);
                      $(".title_sel2").css('display','none');

                      $(".advancedSearch3 .selection3").text(data[i].relationtitle);
                      $(".title_sel3").css('display','none');

					var pinp= data[i].partyinperson;
					//alert(pinp);
						if(pinp == 'N')
						{
							var value = $("#advBarRegNo").val();
							//alert(value);
							getBarregDetails(value);
							document.getElementById('partyInPerson').checked = true;
							$(".advDetails").show();
						}else{
							document.getElementById('partyInPersonY').checked = true;
							$(".advDetails").hide();
						}


                    }
                }
                });
              });

    function getBarregDetails(value)
  {
    //console.log(value);
	var _token = $('input[name="_token"]').val();
     $.ajax({
      type: 'POST',
      url: 'advRegNo',
      data:  { "_token": _token,value:value},
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
<?php if(count($TempRespondant)){?>
	<script type="text/javascript">
  $(document).ready(function(){
  $('#respondant_tab').find('tbody tr').remove();
var count = 1;
var RespondantArr = <?php echo json_encode($TempRespondant) ?>;
//alert('Respondant');
//alert(RespondantArr);
console.log(RespondantArr);
              $.each(RespondantArr, function(index, obj) {
              var row = $('<tr>');
			  //row.append('<td>' +obj.caveateesrno+ '</td>');
			  row.append('<td align="center"><a href="#" data-value="'+obj.caveateesrno+'-'+obj.caveatid+'" class="respondantClick" >' + obj.caveateesrno + '</td>');
              //  row.append('<td><a href="#" data-value="'+obj.receiptSrNo+'" class="receiptClick" >' + count + '</td>');
              row.append('<td align="center"><a href="#" data-value="'+obj.caveateesrno+'-'+obj.caveatid+'" class="respondantClick" >' + obj.caveatid + '</td>');
              row.append('<td align="center">' +obj.caveateename + '</td>');
              row.append('<td align="center">' + obj.advocateregnno + '</td>');

              $('#respondant_tab').append(row)
              count++;
              });
              $(".respondantClick").click(function(){
                $("#sbmt_respondant").val('U');
                $("#saveRespondant").val('Update List');
                //$(this).closest('form').find("input[type=text], textarea").val("");
                /**/
                var newSrno1  = $(this).attr('data-value');
				//alert(newSrno1);
                var newSrnoarr = newSrno1.split('-');
				//alert(newSrnoarr);
                var newApllSrno = newSrnoarr[0];
                //alert(newApllSrno);
				var newApplid = newSrnoarr[1];
				//alert(newApplid);
				$("#resApplId").val(newApplid);
				var _token = $('input[name="_token"]').val();
				//alert(newApplid);
				$("#srno_respondant").val(newApllSrno);
                $.ajax({
                type: 'post',
                url: "getCaveatRespodantData",
                dataType:"JSON",
                data: {"_token": _token,newSrno:newApllSrno,applicationid:newApplid},
                    success: function (json1) {
						alert('success');
						  for(var i=0;i<json1.length;i++){
                          $(".advancedSearch5 .selection5").text(json1[i].caveateetitle);
                          $(".title_sel5").css('display','none');
                          $("#resRelation").val(json1[i].caveateereltype);
                          $("#respondantName").val(json1[i].caveateename);
						  $("#resGender").val(json1[i].caveateegender);
						  $("#resAge").val(json1[i].caveateeage);
						  $("#desigRes").val(json1[i].caveateedesigcode);
						  $("#resAddress2").val(json1[i].caveateeaddress);
                          $("#respincode2").val(json1[i].caveateepincode);
						  $("#rdistcode").val(json1[i].caveateedistrict);
                          $("#taluknameRes").val(json1[i].caveateetaluk);
                          $("#resMobileNo").val(json1[i].caveateemobileno);
                          $("#resEmailId").val(json1[i].caveateeemail);
						  $("#resadvBarRegNo").val(json1[i].advocateregnno);
						  var option = '<option value="'+json1[i].caveateetaluk+'" selected>'+json1[i].talukname+'</option>';
						  $('#taluknameRes').append(option);
                           //var relvalue = json1[i].relation+'-'+json1[i].gender;
						  $("#resReltaion").val(json1[i].relation);

                         // $("#resReltaion >  [value='" + relvalue +"']").attr("selected",true);
                          $(".advancedSearch6 .selection6").text(json1[i].caveateetitle);
                          $(".title_sel6").css('display','none');
						  $("#resDeptType").val(json1[i].caveateedepttype);
						var option1 = '<option value="'+json1[i].caveateedepartcode+'" selected>'+json1[i].departmentname+'</option>';
						$("#resnameofDept").append(option1);
						//$("#resAge").val(json1[i].caveateename);
                          //$("#resnameofDept").val(json1[i].caveateedepartcode);


						  //$("#resAge").val(json1[i].caveateename);

                          $("#resRelTitle").val(json1[i].relationtitle);
                          $("#resRelName").val(json1[i].relationname);
                          //$("#resGender").val(json1[i].gender);
                          //$("#resAge").val(json1[i].respondantage);
                          $("#resDesig").val(json1[i].desigcode);
						  $("#isGovtAdv").val(json1[i].isgovtadvocate);
                          //$("#isMainRes").val(json1[i].ismainrespond);
                          //$("#resadvBarRegNo").val(json1[i].advocateregno);
                          //value = $("#resadvBarRegNo").val(json1[i].advocateregno);
                        //getResBarRegDetails(value);
						var isgoadv = json1[i].isgovtadvocate;
						if(isgoadv == 'N')
						{
							var value = $("#resadvBarRegNo").val();
							getResBarRegDetails(value);
							document.getElementById('isgovtadv').checked = true;
							$(".resadvDatails").show();
						}else{
							document.getElementById('isgovtadvY').checked = true;
							$(".resadvDatails").hide();
						}
                       }
                }
                });
              });
		 function getResBarRegDetails(value)
			{
				  //console.log("in res");
				  var _token = $('input[name="_token"]').val();
				  $.ajax({
				type: 'POST',
				url: 'advRegNo',
				data:  { "_token": _token,value:value},
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






</script>
<?php }?>
