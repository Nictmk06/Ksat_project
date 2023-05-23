@extends('layout.mainlayout')
@section('content')
  <style type="text/css">
  .pager{
  background-color: #337ab7;
  color: #fff;
  }
  .do-scroll{
  width: 100%;
  height: 100px;
  overflow-y: scroll;
  }
  .btnSearch,
  .btnClear{
  display: inline-block;
  vertical-align: top;
  }
  </style>

<div class="content-wrapper">

 <section class="content" >

  <div class="panel  panel-primary">
    <form role="form" id="ordergenerationform" name="ordergenerationform" method="GET" action="generateordersheet1" data-parsley-validate>

      <div class="text-center">
        <button class="tablink" name="FinalizeCourtDirection" type="submit" class="btn btn-primary" id="FinalizeCourtDirection"  value="FinalizeCourtDirection"  Style="width: 200px;" >Finalize Court Direction</button>
        <a  href="bulkdailyorder">
            <i class="fa fa-arrow-circle-o-left"></i>
            <span>Back</span>
        </a>
      </div>
    <table class="table table-bordered table2 display" data-page-length='25' id="myTable" width="100%">
        <thead>
       @if($true=='true')
          <tr>
              <td colspan="2"> <span class="mandatory">*</span> <label for="applTitle">User Name </label> </td>
              <td   colspan="8">
                <select class="form-control" name="userid" id="userid"  >
                  <option selected="true" disabled="disabled">Select User Name</option>
                  @foreach($users as $users)
                  <option value="{{$users->userid}}">{{$users->username}}</option>

                  @endforeach

                </select>
                 </td>
          </tr>

          <tr>
        <center> <td colspan="10">
         <div class="text-center">
            <input type="hidden" name="sbmt_adv" id="sbmt_adv">
              <input  class="assignuser"   name="assignuser" type="button" id="assignuser"  Style="width: 100px;" value="Assign User">
         </div>

       </td></center>
         </tr>
         @endif
          <tr>
          <td  class="bg-primary text-center" colspan="10"> <h3>  <?php echo $establishmentname; ?></h3> </td>
          </tr>

            <tr>
          <td  class="bg-primary text-center" colspan="10">   <h4> Court Proceedings entered for Hearing date
            <?php echo   date('d/m/Y', strtotime($fromdate)) ?>  <?php  foreach ($benchname as $benchname){


                                 echo $benchname->bench;
                              }?><?php echo ' ,'.$courthalldesc;  ?>  </h4> </td>



          </tr>


<tr>
<td><input type="checkbox" id="chkCheckAll" /></td>

<td>Causelist Srno</td>

<td>Dictation By</td>
<td>Application ID</td>
<td>Court Direction</td>
<td>Order Description</td>
<td>Download</td>
<td>Download</td>
<td>Court Direction Publish</td>






</tr>

    </thead>

    <tbody>





              @foreach ($result1 as $result1)
           <tr>
            <?php
            $hearingcode=$result1->hearingcode;
            $listno=$result1->listno;
            $causelistsrno=$result1->causelistsrno;
            $applicationid=$result1->applicationid;
            $courtdirection=nl2br($result1->courtdirection);
            $ordertypedesc=$result1->ordertypedesc;
            $judgeshortname=$result1->judgeshortname;
            $hearingdate=$result1->hearingdate;
            $publish=$result1->publish;
            $dictationby=$result1->username;

             ?>

             <td><input type="checkbox" name="ids[]" id="ids" class="checkBoxClass" value="<?php echo $hearingcode; ?>"/>  </td>
             <input type="hidden" value="<?php echo $listno; ?>">
             <td><?php echo $causelistsrno; ?></td>

              <td><?php echo $dictationby; ?></td>

             <td style="width:10px"><button type="button" class="btn btn-primary"  id="addAppRes"  onclick="closePopup(this)" data-id="<?php echo $result1->applicationid.':'.$result1->hearingdate.':'.$benchcode.':'.$result1->courthallno;  ?>" data-target-id="<?php echo $result1->applicationid.':'.$result1->hearingdate.':'.$benchcode.':'.$result1->courthallno; ?>"  data-toggle="modal" data-target="#myModalf1"><?php echo $applicationid; ?></button></td>

             <td><?php echo $courtdirection; ?></td>
            <td><?php echo $ordertypedesc; ?></td>

            <td> <button name="printdailyorder" type="submit" class="btn btn-primary"  value="<?php echo $applicationid.':'.$causelistsrno.':'.$listno.':'.$judgeshortname.':'.$hearingdate ; ?>"  Style="width: 140px;" >Print Daily Order </button></td>
            <td><button name="printordersheet" type="submit" class="btn btn-primary"  value="<?php echo $applicationid.':'.$causelistsrno.':'.$listno.':'.$judgeshortname.':'.$hearingdate; ?>" Style="width: 140px;" >Print-NewSheet </button>
 <br><br><br>
 <button name="printnewsheet" type="submit" class="btn btn-primary"  value="<?php echo $applicationid.':'.$causelistsrno.':'.$listno.':'.$judgeshortname.':'.$hearingdate; ?>" Style="width: 200px;" >Print-Continuation Sheet </button>
</td>

            <td><?php echo $publish; ?></td>


               @endforeach

            </tr>




    </tbody>
    </table>
  </form>
</div>
</section>

</div>
<div class="modal fade" tabindex="-1" role="dialog" id="myModalf1">

  <div class="modal-dialog modal-lg" role="document">
    <form class="example" action="saveProceeding" id ="form"  method="POST"
  data-parsley-validate>
    <div class="modal-content" id="mdcontent">

      <div class="modal-header">
        <h3 class="modal-title">Daily hearing updation for Application ID - <input type="readonly" id="applicationidd" name="applicationidd" value="" style="pointer-events: none;border: 0">
    <br>
    Hearing date  <input type="readonly" id="hearingdate" name="hearingdate" value="" style="pointer-events: none;border: 0">
         </h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
 <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

      <input type="hidden" id="appId" name="appId" value="">
      <input type="hidden" id="hdate"  name="hdate"value="">
      <input type="hidden" id="hcode"  name="hcode"value="">

      <input type="hidden" id="benchcode"  name="benchcode" value="">
      <input type="hidden" id="courthallno"  name="courthallno" value="">

               <lable style="color: black;"><b>Court direction</b></lable>
        <textarea style="white-space: pre-line" class="form-control" name="courtDirection" id="courtDirection" rows="3" cols="50" > </textarea> <br/>

            <lable style="color: black;"><b>Case remarks</b></lable>
       <textarea style="white-space: pre-line" class="form-control" name="remarksIfAny" id="remarksIfAny" rows="1" cols="60" > </textarea><br/>
            <lable style="color: black;"><b>Action Taken</b></lable>
      <textarea style="white-space: pre-line" class="form-control" name="officeNote" id="officeNote" rows="1" cols="50" > </textarea><br/>
      <div class="row">
        <div class="col-md-4"><lable style="color: black;"><b>Order Passed</b></lable>
        <select class="form-control" name="orderPassed" id="orderPassed" required data-parsley-required-message="Order Passed" style="height:34px" data-parsley-trigger='focus'>
           <option value="">Select Order Passed </option>
          @foreach($order as $order)
           <option value="{{$order->ordertypecode}}">{{$order->ordertypedesc}}</option>
           @endforeach
       </select></div>

       <div class="col-md-4">
           <div class="form-group">
             <label>Application Status<span class="mandatory">*</span></label>
             <select class="form-control" name="applStatus"  id="applStatus" data-parsley-required data-parsley-required-message="Select ApplicationStatus">
               <option value=''>Select Application Status</option>
               @foreach($Status as $statuses)

               <?php if($statuses->display=='Y')
               {?>
               <option value="{{$statuses->statuscode}}" selected>{{$statuses->statusname}}</option>
               <?php }else{?>
               <option value="{{$statuses->statuscode}}">{{$statuses->statusname}}</option>
               <?php }?>
               @endforeach
             </select>
           </div>
         </div>

 <div class="col-md-4"  id="authorbydiv">
   <div class="form-group">

   <label>Author By<span class="mandatory">*</span></label>
  <select class="form-control" name="authorby" id="authorby">
  <option selected="true" disabled="disabled">Select Author</option>
  @if($temp_length!='1')
  <option value="{{$benchcode}}">ALL</option>
  @endif
  @foreach($data['judgeshortname2'] as $key => $judgeshortname2)
  <option value="{{$data['benchcode3'][$key]}}">{{$judgeshortname2}}</option>
  @endforeach
  </select>
     <!-- <span id="error7"></span> -->
   </div>
</div>
         <div class="col-md-4" style="display: none" id="disposeddatediv">
           <div class="form-group">
             <label>Disposed Date<span class="mandatory">*</span></label>
             <div class="input-group date">
               <div class="input-group-addon">
                 <i class="fa fa-calendar"></i>
               </div>
               <input type="text" name="disposedDate" class="form-control pull-right datepicker" id="disposedDate"  value="" >
             </div>
             <!-- <span id="error7"></span> -->
           </div>

         </div>



         <div class="col-md-4" id="nexthearingdiv">
               <div class="form-group">
                 <label>Next Hearing is?</label><br>
                 <label class="radio-inline">


                   <input type="checkbox" name="isnexthearing" id="isnexthearing"  data-parsley-trigger='keypress' >Yes

                 </label>
               </div>
             </div>

     </div>


     <div class="row">
       <div class="panel panel-primary">
         <div class="panel panel-heading hearingdiv" style="display: none;"></div>
         <div class="panel panel-body">
           <div class="row nexthrdiv" style="display:none">
             <div class="col-md-4">
               <div class="form-group">
                 <label>Next Hearing Date<span class="mandatory">*</span></label>
                 <div class="input-group date">
                   <div class="input-group-addon">
                     <i class="fa fa-calendar"></i>
                   </div>
                   <input type="text" name="nextHrDate" class="form-control pull-right" id="nextHrDate"   data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-pattern-message="Hearing Date Allows only digits"  data-parsley-required-message="Enter Hearing Date" value="" data-parsley-errors-container="#error8">
                 </div>
                 <span id="error8"></span>
               </div>
             </div>
             <div class="col-md-4">
               <div class="form-group">
                 <label>Next Bench Type<span class="mandatory">*</span></label>
                 <select name="nextBench" id="nextBench" class="form-control"  data-parsley-required-message="Select Bench"><option value="" >Select Bench Type</option>
                 @foreach($Benches as $bench)
                 <option value="{{$bench->benchtypename}}">{{$bench->benchtypename}}</option>
                 @endforeach
               </select>
             </div>
           </div>
           <div class="col-md-4">
             <div class="form-group">
               <label>Next Bench <span class="mandatory">*</span></label>
               <select name="nextbenchJudge" id="nextbenchJudge" class="form-control" data-parsley-required-message="Select Pending IA"><option value="" >Select Bench</option>
                @foreach($benchjudge as $bench)
                       <option value="{{$bench->benchcode}}">{{$bench->judgeshortname}}</option>
                       @endforeach
             </select>
           </div>
         </div>
       </div>
         <div class="row nexthrdiv"  style="display:none">
         <div class="col-md-4">
           <div class="form-group">
             <label>Next Listing Posted For<span class="mandatory">*</span></label>
             <select name="nextPostfor" id="nextPostfor" class="form-control"  data-parsley-required-message="Select  Posted For">
              <option value="" >Select  Posted For</option>
             @foreach($purpose as $postedfor)
             <option value="{{$postedfor->purposecode}}">{{$postedfor->listpurpose}}</option>
             @endforeach
           </select>
         </div>
       </div>
     </div>

   </div>
 </div>
</div>
<div id="iaTableBlock" style="display:none;">
<div class="row">
          
          <div class="col-md-4">
            <div class="form-group">
              <label for="pendingIa"> Pending IA </label> 
              <select class="form-control" name="pendingIa" id="pendingIa" data-parsley-required-message="Select IA" style="height:34px" data-parsley-trigger='focus'>
           <option value="0:0" >Select IA </option>
           </select>
           <input type="hidden" name="hiddenPendingIa" id="hiddenPendingIa" value="0:0"> <!-- Used for internal purpose (To save previously selcted IA on change event) //-->
           </div>
         </div>
         
          <div class="col-md-4">
             <div class="form-group">
               <label>IA Order Passed <span class="mandatory">*</span></label>
            <select class="form-control" name="iaOrderPassed" id="iaOrderPassed" data-parsley-required-message="IA Order Passed" style="height:34px" data-parsley-trigger='focus'>
              <option value="">Select Order Passed </option>
            </select>
          </div>
         </div>

        <div class="col-md-4">
             <div class="form-group">
               <label>IA Status<span class="mandatory">*</span></label>
            <select class="form-control" name="iaStatus" id="iaStatus" data-parsley-required-message="IA Status" style="height:34px" data-parsley-trigger='focus'>
            <option value="" >Select IA Status </option>
             </select>
           </div>
         </div>

         <div class="col-md-4">
             <div class="form-group">
               <label> IA Prayer<span class="mandatory">*</span></label>
             <textarea class="form-control" name="iaPrayer" id="iaPrayer" rows="3" cols="70" readonly> </textarea>
           </div>
         </div>

          <div class="col-md-4">
             <div class="form-group">
               <label>Remarks<span class="mandatory">*</span></label>
                <textarea class="form-control" name="iaRemarks" id="iaRemarks" rows="3" cols="60"> </textarea>
            </div>
         </div>
  </div>
</div>


      </div>
      <div class="modal-footer">
        <input  type="submit" class="btn btn-primary" value="Save ">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>


    </div>
     </form>
  </div>

</div>

<script src="js/jquery-3.4.1.js"></script>

<script>
$('.example').on('submit',function(e){
  var appId=$("#appId").val();

  var hdate=$("#hdate").val();
    var courtDirection=  $("#courtDirection").val();
    var remarksIfAny=  $("#remarksIfAny").val();
    var officeNote=  $("#officeNote").val();
    var orderPassed=  $("#orderPassed").val();
    var applStatus=  $("#applStatus").val();
    var hearingcode= $("#hcode").val();

    var isnexthearing= $("#isnexthearing").val();
    var nextHrDate= $("#nextHrDate").val();
    var nextbenchJudge= $("#nextbenchJudge").val();
    var nextPostfor= $("#nextPostfor").val();
    var nextBench= $("#nextBench").val();
    var disposedDate= $("#disposedDate").val();
    var authorby= $("#authorby").val();
    var pendingIa=$("#pendingIa").val();
    var iaPrayer=$("#iaPrayer").val();
    var iaRemarks=$("#iaRemarks").val();
    var iaOrderPassed=$("#iaOrderPassed").val();
    var iaStatus=$("#iaStatus").val();
    console.log(authorby);
    //var hearingcode= $("#hcode").val();

console.log(hearingcode);
  //console.log(applicationid);
  //console.log(hearingdate);
    e.preventDefault();

  $.ajax({
        type     : "POST",
        cache    : false,
        url      : "saveProceeding",
        data: {
          "_token": $('#token').val(),
          appId: appId,hdate:hdate,courtDirection:courtDirection,remarksIfAny:remarksIfAny,officeNote:officeNote,orderPassed:orderPassed,applStatus:applStatus,hearingcode:hearingcode,authorby:authorby
          ,isnexthearing,nextHrDate,nextbenchJudge,nextPostfor,nextBench,disposedDate,pendingIa,iaPrayer,iaRemarks,iaOrderPassed,iaStatus
          },
        success  : function(data) {
          console.log(data);
          if(data.status=="success")
          {
          location.reload();
            swal({
              title: data.message,
              icon: "success"
            })
            ;
          }
          else if(data.status=="error")
          {

            swal({
              title: data.message,
              icon: "error"
            })
            ;
          }


        }
   });
});
</script>
<script>
    $(function(e) {
        $("#chkCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'))
        });
      });
</script>


<script>
function closePopup(obj)
{
  var applicationid_hearingdate = $(obj).data('id');
console.log(applicationid_hearingdate);
  var data = applicationid_hearingdate;
  var split2 = data.split(':');
  console.log(split2);
  var applicationID=split2[0];
  var hearingDate1=split2[1];
  var benchcode=split2[2];
  var courthallno=split2[3];
  console.log(applicationID);
  console.log(hearingDate1);
    $.ajax({
    type: 'post',
    url: "getapphearing_bulkdailyorder",
    dataType: "JSON",
    data: {
      "_token": $('#token').val(),
      applicationID: applicationID,hearingDate1:hearingDate1,benchcode:benchcode,courthallno:courthallno
      },
    success: function(data) {
      console.log(data);
         


       var iapending = data['iapending'];
       var json1= data['json'];
        console.log(iapending);
      
        
        if ( iapending.length > 0 )
        {
         $('#iaTableBlock').show(); 
         var m_status       = data['m_status'];
         var m_ordertype    = data['m_ordertype'];
          $('#pendingIa').find('option').not(':first').remove();
          $('#iaPrayer').val('');
          $('#iaRemarks').val('');
          $('#iaStatus').find('option').not(':first').remove();
          $('#iaOrderPassed').find('option').not(':first').remove();
             var ialength = iapending.length;
             console.log(m_status);
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


        }
        else
        {
         $('#iaTableBlock').hide(); 
        }

      if(json1.length>0)
      {
        $('#applicationidd').val(applicationID);

  $('#appId').val(applicationID);
  $('#hcode').val(json1[0].hearingcode);
  $('#hdate').val(json1[0].hearingdate);
  $('#courthallno').val(courthallno);
  $('#benchcode').val(benchcode);
  var hearingdate =json1[0].hearingdate;
  var split = hearingdate.split('-');
  $("#hearingdate").val(split[2]+'-'+split[1]+'-'+split[0]);

  // $('#hdate').val($('#hearingDate1').val());
  $("#courtDirection").val(json1[0].courtdirection);
  //$("#userid").attr('readonly', true);
  $("#remarksIfAny").val(json1[0].caseremarks);

  $('#officeNote').val(json1[0].officenote);

  //$("#orderPassed").val(data[0].ordertypedesc);
  //$("input[name=orderPassed][value='" +  + "']").prop('checked', true);
//  $('#orderPassed option:eq('+json1[0].ordertypecode+')').prop('selected','selected');
$("#orderPassed").val(json1[0].ordertypecode).prop('selected','selected');
$("#applStatus").val(json1[0].casestatus).prop('selected','selected');

if(json1[0].nextdate!=null)
 {
   $('#isnexthearing').prop('checked',true);
   $("#disposeddatediv").hide();
   $("#authorbydiv").hide();
   $("#nexthearingdiv").show();

   $('.nexthrdiv').show();
   $(".hearingdiv").show();
   var NDate =  json1[0].nextdate;
   var arr1 =NDate.split('-');
   var nexthrdate =arr1[2]+'-'+arr1[1]+'-'+ arr1[0];
   $("#nextBench").val(json1[0].nextbenchtypename);
   $("#nextHrDate").val(nexthrdate);
   $("#nextbenchJudge").val(json1[0].nextbenchcode);
   $("#nextPostfor").val(json1[0].nextpurposecode);
 }
 else
 {
   $('#isnexthearing').prop('checked',false);
   $("#nexthearingdiv").show();
   $("#disposeddatediv").hide();
   $("#authorbydiv").hide();
   $('.nexthrdiv').hide();
   $(".hearingdiv").hide();
   $("#nextBench").val('');
   $("#nextHrDate").val('');
   $("#nextbenchJudge").val('');
   $("#nextPostfor").val('');
 }
// $("#saveDailyHearing").val('Update');
 $("#postedfor").val(json1[0].purposecode);
 //$("#courthall").val(json1[0].courthallno);
 //$("#courtDirection").val(json1[0].courtdirection);
 //$("#officenote").val(json1[0].officenote);
 //$("#ordertypecode").val(json1[0].orderpassed);
 //$("#applStatus").val(json1[0].casestatus);
 //$("#caseRemarks").val(json1[0].caseremarks);
 $("#benchCode").val(json1[0].benchtypename);
 $("#benchJudge").val(json1[0].benchcode);
 //$("#ordertypecode").val(json1[0].ordertypecode);
 //$("#hearingCode").val(json1[0].hearingcode);
 if(json1[0].casestatus == '2'){
   $("#disposeddatediv").show();
   $("#authorbydiv").show();

     $("#nexthearingdiv").hide();
   $("#disposedDate").val(json1[0].disposeddate);
   //$('#saveDailyHearing').attr('disabled','disabled');
 }
 var DispDate =  json1[0].disposeddate;
 //var arr2 =DispDate.split('-');
 //var disposeddate =arr2[2]+'-'+arr2[1]+'-'+ arr2[0];
 $("#disposedDate").val(DispDate);
 $("#authorby").val(json1[0].authorby);

 //$("#applicationId").val(json1[0].applicationid);
 //$("#hearingDate").val(arr[2]+'-'+arr[1]+'-'+ arr[0]);
 //var hearingdate = arr[2]+'-'+arr[1]+'-'+ arr[0];
 //var applicationid = newappl_id;
 //getHearing(hearingdate,applicationid,benchjudge);


   }

     }
  });
}
</script>
<script>
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
                 $('#iaStatus').empty();
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

</script>
<script>
$('.tablink').click(function(e) {
    console.log('i m here');
//    $("#applicationId").val(data[0].applicationid);
//var ids = $("input:checked");

var ids = new Array();
$.each($("input[name='ids[]']:checked"), function() {
ids.push($(this).val());
  // or you can do something to the actual checked checkboxes by working directly with  'this'
  // something like $(this).hide() (only something useful, probably) :P
});

//var ids =$('input[name="ids"]:checked');
console.log(ids);

e.preventDefault();
 $.ajax({
        type     : "GET",
        cache    : false,
        url      : "generateordersheet1",
        data: {"_token": $('#token').val(),ids : ids },
        success  : function(data) {
          console.log(data);
          if(data.status=="sucess")
          {
            //gethearing();
              location.reload();
              swal({
              title: data.message,
              icon: "success"
            })
            ;

          }
          if(data.status=="error")

          {
            swal({
              title: data.message,
              icon: "error"
            })
            ;
          }
          if(data.status=="update")
          {
        //  $("#remarksQuestionaries").val('');
        //  $("#ids").val('');
            swal({
              title: data.message,
              icon: "success"
            })
            ;
          }
        }
   });

});



</script>
<script>
$('.assignuser').click(function(e) {
    console.log('i m hs');
//    $("#applicationId").val(data[0].applicationid);
//var ids = $("input:checked");
var userid=$("#userid").val();
var ids = new Array();
$.each($("input[name='ids[]']:checked"), function() {
ids.push($(this).val());
  // or you can do something to the actual checked checkboxes by working directly with  'this'
  // something like $(this).hide() (only something useful, probably) :P
});

//var ids =$('input[name="ids"]:checked');
console.log(ids);

e.preventDefault();
 $.ajax({
        type     : "GET",
        cache    : false,
        url      : "assignuser",
        data: {"_token": $('#token').val(),ids : ids,userid:userid },
        success  : function(data) {
          console.log(data);
          if(data.status=="sucess")
          {
            //  gethearing();
              location.reload();
              swal({
              title: data.message,
              icon: "success"
            })
            ;

          }
          if(data.status=="error")

          {
            swal({
              title: data.message,
              icon: "error"
            })
            ;
          }
          if(data.status=="update")
          {
        //  $("#remarksQuestionaries").val('');
        //  $("#ids").val('');
            swal({
              title: data.message,
              icon: "success"
            })
            ;
          }
        }
   });

});
</script>

<script>
$('input[name="isnexthearing"]').click(function() {
      if ($('input[name="isnexthearing"]').is(':checked')) {
        $('input[name="isnexthearing"]').val('Y');

    $(".nexthrdiv").show();
    $(".hearingdiv").show();
    $("#nextHrDate").attr('data-parsley-required',true);
    $("#nextBench").attr('data-parsley-required',true);
    $("#nextbenchJudge").attr('data-parsley-required',true);
    $("#nextPostfor").attr('data-parsley-required',true);
  } else {
    $('input[name="isnexthearing"]').val('');
    $(".nexthrdiv").hide();
    $(".hearingdiv").hide();
    $("#nextHrDate").attr('data-parsley-required',false);
    $("#nextBench").attr('data-parsley-required',false);
    $("#nextbenchJudge").attr('data-parsley-required',false);
    $("#nextPostfor").attr('data-parsley-required',false);
  }
})

$("#applStatus").change(function(){
    var status = $(this).val();
    if(status==2 || status==4)
    {
      $("#disposeddatediv").show();
      $("#authorbydiv").show();

      $("#nexthearingdiv").hide();
      $('.nexthrdiv').hide();
      $(".hearingdiv").hide();
    }
    else if(status==1)
    {
      $("#nexthearingdiv").show();
      $("#disposeddatediv").hide();
      $("#authorbydiv").hide();

      if ($('input[name="isnexthearing"]').is(':checked'))
      {
      $('.nexthrdiv').show();
      $(".hearingdiv").show();
      }
    else{
      $('.nexthrdiv').hide();
      $(".hearingdiv").hide();
    }
    }

  })
</script>
<script>
$("#nextBench").change(function() {
   		 var text = $(this).val();
   		$.ajax({
				type: 'post',
				url: "getBenchJudges",
				dataType:"JSON",
				data: {"_token": $('#token').val(),benchtype:text,display:'Y'},
				success: function (json) {
					$('#nextbenchJudge').find('option:not(:first)').remove();
					 for(var i=0;i<json.length;i++){
					 	 var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
	  						$('#nextbenchJudge').append(option);
					 }
				}
			});
   		});
</script>

  @endsection
