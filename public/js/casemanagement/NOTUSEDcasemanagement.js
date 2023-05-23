

$(function () {

var counter = 2;

$("#addrow2").on("click", function () {
var newRow = $("&lt;tr&gt;");
var cols = "";
var partOfDoc = $("#partOfDoc").val();
var start = $("#start").val();
var partOfDoc = $("#endPage").val();
cols += '<td><input type="hidden" value='+ counter+' name="count[]">'+counter+'</td>;';
cols += '<td><textarea type="text" class="form-control" id="partOfDoc" name="partOfDoc[]"' + partOfDoc + '"></textarea></td>;';
cols += '<td><input type="text" class="form-control" id="start" name="start[]"' + start + '"/><td>';
cols += '<td><input type="text" class="form-control" id="endPage" name="endPage[]"' + endPage + '"/></td>;';
cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>;';
newRow.append(cols);
$("table.application-list").append(newRow);
counter++;
});

$("table.application-list").on("click", ".ibtnDel", function (event) {
$(this).closest("tr").remove();       
counter -= 1
});

function calculateRow(row) {
var price = +row.find('input[name^="price"]').val();

}

function calculateGrandTotal() {
var grandTotal = 0;
$("table.order-list").find('input[name^="price"]').each(function () {
grandTotal += +$(this).val();
});
$("#grandtotal").text(grandTotal.toFixed(2));
}


$('.datepicker').datepicker({
autoclose: true,
format: 'dd-mm-yyyy'
})
$("#datepicker1").datepicker({
format: "yyyy",
viewMode: "years", 
minViewMode: "years"

});

});
$(document).ready(function () {
var counter = 2;

$("#addrow").on("click", function () {
var newRow = $("&lt;tr&gt;");
var cols = "";

cols += '<td class="col-xs-1">'+counter+'</td>;';
cols += '<td class="col-sm-2"><input type="text" class="form-control" name="reliefsought[]' + counter + '"/></td>';

cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="-"></td>;';
newRow.append(cols);
$("table.order-list").append(newRow);
counter++;
});



$("table.order-list").on("click", ".ibtnDel", function (event) {
$(this).closest("tr").remove();       
counter -= 1
});


});
$('.btnNext').click(function(){
var apllStart = $("#applStartNo").val();
var applYear = $("#datepicker1").val();
$("#recpApplYear").val(applYear);
$("#recStartSrNo").val(apllStart);
var form = $(this).closest("form").attr('id');
var formaction = $(this).closest("form").attr('action');
 $("#"+form).parsley().validate();
    if ($("#"+form).parsley().isValid())
    {
       if($("#"+form)=="receiptForm")
        {
        //$('.nav-tabs &gt; .active').next('li').find('a').trigger('click');
        e.preventDefault();
        }
        else
        {
        $.ajax({
        type: 'post',
        url: formaction,
        data:    $('#'+form).serialize(),
        success: function (data) {
        if(data=="sucess")
        {
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
        }
        }
        });
        }
    }
    else
    {
        alert("invalid");
    }
});
$('.btnPrevious').click(function(){
$('.nav-tabs > .active').prev('li').find('a').trigger('click');
});
$(function () {
$("#dateOfAppl").change(function(){
var doa = $("#dateOfAppl").val();
var split = doa.split("-");

$("#datepicker1").val(split[2]);
})
$('#example1').DataTable()
$('#example1').DataTable({
'paging'      : true,
'lengthChange': false,
'searching'   : false,
'ordering'    : true,
'info'        : true,
'autoWidth'   : false
})
$('#example2').DataTable()
$('#example2').DataTable({
'paging'      : true,
'lengthChange': false,
'searching'   : false,
'ordering'    : true,
'info'        : true,
'autoWidth'   : false
})
$('#example3').DataTable()
$('#example3').DataTable({
'paging'      : true,
'lengthChange': false,
'searching'   : false,
'ordering'    : true,
'info'        : true,
'autoWidth'   : false
})

})

$('input[name="isAdvocate"]').click(function(){
if($('input[name="isAdvocate"]').is(':checked'))
{
if($(this).val()=="NA")
{
$("#party_div").show();
}
else
{
$("#party_div").hide();
}
}});


$(".dropdown_all1 a").click(function () {
$(".advancedSearch1 .selection1").text($(this).text());
$(".title_sel1").css('display','none');
$("#applTitle").val($(this).text());
});
$(".dropdown_all2 a").click(function () {
$(".advancedSearch2 .selection2").text($(this).text());
$(".title_sel2").css('display','none');
$("#applicantTitle").val($(this).text());
});
$(".dropdown_all3 a").click(function () {
$(".advancedSearch3 .selection3").text($(this).text());
$(".title_sel3").css('display','none');
$("#relationTitle").val($(this).text());
});
$(".dropdown_all4 a").click(function () {
$(".advancedSearch4 .selection4").text($(this).text());
$(".title_sel4").css('display','none');
$("#advTitle").val($(this).text());
});
$(".dropdown_all5 a").click(function () {
$(".advancedSearch5 .selection5").text($(this).text());
$(".title_sel5").css('display','none');
$("#respondantTitle").val($(this).text());
});
$(".dropdown_all6 a").click(function () {
$(".advancedSearch6 .selection6").text($(this).text());
$(".title_sel6").css('display','none');
$("#resRelTitle").val($(this).text());
});
$(".dropdown_all7 a").click(function () {
$(".advancedSearch7 .selection7").text($(this).text());
$(".title_sel7").css('display','none');
$("#respAdvTitle").val($(this).text());
});
$('input[name="interimPrayer"]').click(function(){
if($('input[name="interimPrayer"]').is(':checked'))
{
$("#interimOrderDiv").show();
}else
{
$("#interimOrderDiv").hide();
}
})
$("#recpSubmit").click(function(){
var form = $(this).closest("form").attr('id');
var formaction = $(this).closest("form").attr('action');
var application_id = 'T'+$("#recStartSrNo").val()+$("#recpApplYear").val();
$.ajax({
type: 'post',
url: formaction,
data:    $('#'+form).serialize(),
success: function (data) {
if(data=="sucess")
{
$.ajax({
type: 'GET',
url: 'receipts',
dataType: 'json',
data:{application_id:application_id},
cache: false,
success: function(response) {
// This will clear table of the old data other than the header row

var len = response.length;
console.log(len);
}
});
}


}
});
});
$("#saveRespondant").on("click", function () {
var form = $(this).closest("form").attr('id');
var formaction = $(this).closest("form").attr('action');
$.ajax({
type: 'post',
url: formaction,
data:    $('#'+form).serialize(),
success: function (data) {
if(data=="sucess")
{
alert("Applicant Added Successfully");
}
else
{
alert("Please Try Again, After Some Time");
}

}
});
e.preventDefault();
});
$("#respNext").click(function(){

var apllStart = $("#applStartNo").val();
var applYear = $("#datepicker1").val();
$("#applIndexYear").val(applYear);
$("#applIndexStartNo").val(apllStart);
$('.nav-tabs &gt; .active').next('li').find('a').trigger('click');
})
$("#saveAplicantionIndex").click(function(){
var form = $(this).closest("form").attr('id');
var formaction = $(this).closest("form").attr('action');
$.ajax({
type: 'post',
url: formaction,
data:    $('#'+form).serialize(),
success: function (data) {
if(data==="sucess")
{
alert("Applicant Added Successfully");
}
else
{
alert("Please Try Again, After Some Time");
}

}
});
e.preventDefault();
})
$("#advBarRegNo").on('click',function(){
    var value = $("#advBarRegNo").val();
var text = $("#browsers").find('option[value=' + value + ']').text();
    $.ajax({
type: 'POST',
url: 'advRegNo',
data:  { "_token": $('#token').val(),value:value},
dataType: "JSON",
success: function (json) {
    for(var i=0;i<json.length;i++){
$("#advName").val(json[i].advocateName);
$("#advRegAdrr").val(json[i].advocateAddress);
$("#advRegTaluk").attr('disabled', false);
$("#advRegDistrict").attr('disabled', false);
$("#advRegTaluk").empty();
$("#advRegDistrict").empty();
$("#advRegTaluk").append('<option value=' + json[i].talukcode + 'selected>;' + json[i].talukName + '</option>');
$("#advRegDistrict").append('<option value=' + json[i].distcode + 'selected>;' + json[i].distName + '<option>');
$("#advRegPin").val(json[i].pincode);
}
}
});
});
$("#updateReceipt").click(function(){
var form = $(this).closest("form").attr('id');
$.ajax({
type: 'POST',
url: 'updateReceipt',
data:  $('#'+form).serialize(),
///dataType: &quot;JSON&quot;,
success: function (data) {

if(data=="sucess")
{
alert("Receipt Updated Successfully");
location.reload();
}
else
{
alert("Please Try After Some Time!!");
location.reload();
}

}
});
});

$("#resadvBarRegNo").on('click',function(){
    var value = $("#resadvBarRegNo").val();
var text = $("#browsers1").find('option[value=' + value + ']').text();
$.ajax({
type: 'POST',
url: 'advRegNo',
data:  { "_token": $('#token').val(),value:value},
dataType: "JSON",
success: function (json) {

    for(var i=0;i<json.length;i++){
$("#respAdvName").val(json[i].advocateName);
$("#resadvaddr").val(json[i].advocateAddress);
$("#resadvtaluk").attr('disabled', false);
$("#resadvdistrict").attr('disabled', false);
$("#resadvtaluk").empty();
$("#resadvdistrict").empty();
$("#resadvtaluk").append('<option value=' + json[i].talukcode + 'selected>' + json[i].talukName + '</option>');
$("#resadvdistrict").append('<option value=' + json[i].distcode + 'selected>' + json[i].distName + '</option>');
$("#resadvpincode").val(json[i].pincode);
}
    }});
});

$(".receiptClick").click(function()
{
$("#submit_div").hide();
$("#update_div").show();
var receiptno  = $(this).attr('data-value');
$.ajax({
type: 'POST',
url: 'getReceipt',
data:  { "_token": $('#token').val(),receiptno:receiptno},
dataType: "JSON",
success: function (json) {

for(var i=0;i<json.length;i++){
$("#receiptNo").val(json[i].receiptNo);

$("#receiptDate").val(json[i].receiptDate);
$(".advancedSearch1 .selection1").text(json[i].titleName);
$(".title_sel1").css('display','none');
$("#applTitle").val(json[i].titleName);
$("#receiptSrno").val(json[i].receiptSrNo);
$("#applName").val(json[i].name);
$("#recpAmount").val(json[i].amount);
}
}
});
})
$("#applicantClick").click(function(){
    $("#add_apl_div").hide();
$("#up_apl_div").show();
var applSrNo  = $(this).attr('data-value');
$.ajax({
type: 'POST',
url: 'getApplicant',
data:  { "_token": $('#token').val(),applSrNo:applSrNo},
dataType: "JSON",
success: function (json) {
    for(var i=0;i<json.length;i++){
$('#isAdvocate').find(':radio[name=isAdvocate][value="NA"]').attr('checked', true);
$(".advancedSearch2 .selection2").text(json[i].nameTitle);
$(".title_sel2").css('display','none');
$("#applicantTitle").val(json[i].nameTitle);
$("#applicantName").val(json[i].applicantName);
$(".advancedSearch3 .selection3").text(json[i].relationTitle);
$(".title_sel3").css('display','none');
$("#relationTitle").val(json[i].relationTitle);
$("#relationName").val(json[i].relationName);
$("#gender").val(json[i].gender);
$("#applAge").val(json[i].applicantAge);
$("#applDeptType").val(json[i].deptType);
$("#nameOfDept").val(json[i].departCode);
$("#desigAppl").val(json[i].applicantDesig);
$("#addressAppl").val(json[i].applicantAddress);
$("#pincodeAppl").val(json[i].applicantPincode);
$("#talukAppl").val(json[i].talukCode);
$("#districtAppl").val(json[i].districtCode);
$("#applMobileNo").val(json[i].applicantMobileNo);
$("#applEmailId").val(json[i].applicantEmail);
$("#partyInPerson").val(json[i].partyInPerson);
$("#isMainParty").val(json[i].isMainParty);
$("#advBarRegNo").val(json[i].advocateRegnno);
//$(&quot;#relation&quot;).val(json[i].applicantName);
}
    }});
});

$("#updateApplicant").click(function(){
var form = $(this).closest("form").attr('id');
$.ajax({
type: 'POST',
url: 'updateApplicant',
data:  $('#'+form).serialize(),
///dataType: &quot;JSON&quot;,
success: function (data) {

if(data=="sucess")
{
alert("Applicant Updated Successfully");
location.reload();
}
else
{
alert("Please Try After Some Time!!");
location.reload();
}

}
});
});
$("#RespondantClick").click(function(){
$("#res_sbmt_div").hide();
$("#res_up_div").show();
var respondSrNo  = $(this).attr('data-value');
$.ajax({
type: 'POST',
url: 'getRespondant',
data:  { "_token": $('#token').val(),respondSrNo:respondSrNo},
dataType: "JSON",
success: function (json) {

    for(var i=0;i<json.length;i++){
$('#isAdvocate').find(':radio[name=isAdvocate][value="NA"]').attr('checked', true);
$(".advancedSearch5 .selection5").text(json[i].respondTitle);
$(".title_sel5").css('display','none');
$("#respondantTitle").val(json[i].respondTitle);
$("#respondantName").val(json[i].respondName);
$(".advancedSearch6 .selection6").text(json[i].relationTitle);
$(".title_sel6").css('display','none');
$("#resRelTitle").val(json[i].relationTitle);
$("#resRelName").val(json[i].relationName);
$("#resGender").val(json[i].gender);
$("#resAge").val(json[i].respondantAge);
$("#resDeptType").val(json[i].respontDeptType);
$("#resnameofDept").val(json[i].respontDeptCode);
$("#resDesig").val(json[i].respondDesig);
$("#resAddress2").val(json[i].respondAddress);
$("#respincode2").val(json[i].respondPincode);
$("#resTaluk").val(json[i].respondTaluk);
$("#resDistrict").val(json[i].respondDistrict);
$("#resMobileNo").val(json[i].respondMobileNo);
$("#resEmailId").val(json[i].respondEmail);
$("#isGovtAdv").val(json[i].isGovtAdvocate);
$("#isMainRes").val(json[i].isMainRespond);
$("#resadvBarRegNo").val(json[i].advocateRegno);
//$(&quot;#relation&quot;).val(json[i].applicantName);
}

    }});
});

$("#updateRespondant").click(function(){
    var form = $(this).closest("form").attr('id');
    $.ajax({
type: 'POST',
url: 'updateRespondant',
data:  $('#'+form).serialize(),
success: function (data) {
    if(data=="sucess")
{
alert("Respondant Updated Successfully");
//location.reload();
}
else
{
alert("Please Try After Some Time!!");
//  location.reload();
}
}
});
    });