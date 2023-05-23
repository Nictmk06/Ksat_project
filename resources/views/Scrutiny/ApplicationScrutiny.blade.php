@extends('layout.mainlayout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <style type="text/css">
  .btnSearch,
  .btnClear{
  display: inline-block;
  vertical-align: top;
  }


  .input-wrapper{
  position: relative;
}
  </style>

  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>

@include('flash-message')
<br> <br>
<div class="container">

<form id="myForm" action="saveApplicationScrutiny" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-12">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h7> Application Scrutiny  </h7> </td>
        </tr>
</table>

     <table class="table no-margin table-bordered">

        <tr>
     		<td> <span class="mandatory">*</span> <label for="applTitle">Type of Application</label> </td>
        <td>
                 <input type="text" name="applicationType" id="applicationType"
                 value="{{$applicationDetails->appltypeshort}}-{{$applicationDetails->appltypedesc}}" class="form-control" disabled
              </td>


           {{-- <td>
              	 <select class="form-control" name="applicationType" id="applicationType"  >
                  <option value="">Select Application Type</option>
                        @foreach($applicationType as $applType)

                        <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>

                        @endforeach
                      </select>
              </td> --}}


     		<td> <span class="mandatory">*</span> <label for="applTitle">Application Number
              <td>
              	 <input type="text" name="applicationNo" id="applicationNo" readonly="readonly"
                 value="<?php if($applicationDetails->applicationsrno==$applicationDetails->applicationtosrno){
                         echo $applicationDetails->applicationsrno.' of '.$applicationDetails->applicationyear ;
                       }
                        else {
            echo $applicationDetails->applicationsrno.'-'.$applicationDetails->applicationtosrno.' of '.$applicationDetails->applicationyear;
                         }

                     ?>" class="form-control"  >
				<input type="hidden" name="appltypecode" id="appltypecode" value="{{$applicationDetails->appltypecode}}">
                <input type="hidden" name="applicationid" id="applicationid" value="{{$applicationDetails->applicationid}}">
              </td>


            </tr>

             <tr>
        <td> <span class="mandatory">*</span> <label for="applTitle">Application Date</label> </td>
        <td>
           <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="dateOfAppl" id="dateOfAppl"
                     value="{{ date('d-m-Y', strtotime($applicationDetails->applicationdate)) }}"
                     class="form-control" disabled >
                </div>


           </td>

        <td> <span class="mandatory">*</span> <label for="applTitle">Application category</label>
              <td>

            <select class="form-control" name="appCategory" id="appCategory"  data-parsley-required data-parsley-required-message="Select Application category " data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus' disabled>
            <option value="" >Select Application category </option>
               @foreach($applCategory as $applCategory)
                          <?php if(($applicationDetails->applcategory)==$applCategory->applcatcode){?>
                          <option value="{{$applCategory->applcatcode}}" selected>
                            {{$applCategory->applcatname}}
                          </option>
                          <?php  }
                          else
                          {?>
                          <option value="{{$applCategory->applcatcode}}">
                            {{$applCategory->applcatname}}
                          </option>
                          <?php } ?>
                   @endforeach
               </select>
            </td>


            </tr>
            <tr>
              <td><span class="mandatory">*</span> <label for="applTitle">Name of Applicant </label>
              </td>
              <td colspan="3">
			  @if($applicantDetails== '')
                 <input type="text" name="applicantName" id="applicantName" readonly
                 value="" class="form-control"  >
            @else
		    <input type="text" name="applicantName" id="applicantName" readonly
                 value="{{$applicantDetails->nametitle}} {{$applicantDetails->applicantname}}" class="form-control"  >
				   @endif
        </td>
            </tr>
            <tr>
              <td ><span class="mandatory">*</span> <label for="applTitle">Name of Respondant </label>
              </td>
              <td colspan="3">
			   @if($respondantDetails== '')
                 <input type="text" name="respondantName" id="respondantName" readonly
                 value="" class="form-control"  >
            @else
                 <input type="text" name="respondantName" id="respondantName" readonly
                 value="{{$respondantDetails->respondname}}" class="form-control"  >
             @endif
			 </td>
            </tr>
            <tr>
              <td ><span class="mandatory">*</span> <label for="applTitle">Department </label>
              </td>
              <td colspan="3">
                  <select class="form-control" name="department" id="department"  data-parsley-required-message="Select department" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus' disabled>
                  <option value="" >Select Department </option>
                    @foreach($department as $department)
                          <?php if(($applicantDetails->departcode)==$department->departmentcode){?>
                          <option value="{{$department->departmentcode}}" selected>
                            {{$department->departmentname}}
                          </option>
                          <?php  }
                          else
                          {?>
                          <option value="{{$department->departmentcode}}">
                            {{$department->departmentname}}
                          </option>
                          <?php } ?>
                   @endforeach
               </select>
              </td>
            </tr>
            <tr>
              <td ><span class="mandatory">*</span> <label for="applTitle">Subject in brief </label>
              </td>
              <td colspan="3">
                 <textarea class="form-control" name='applnSubject' id="applnSubject"  readonly>{{$applSubject}}</textarea>
              </td>
            </tr>
</table>
</div>
@if($insertUpdateflag=='I')
    <div class="col-md-12">
                <table id="myTable4" class="table myTable table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Sr. No.</td>
                      <td>Check Slip</td>
                      <!--<td>Compliance</td>-->
                      <td>Remarks</td>
                 </tr>
                </thead>
             <tbody>
              <?php $i = 1; ?>
                    @foreach($scrutinychklist as $scrutinychklist)
                    <tr class="item">
                         <td>{{ $i++ }}
                         <input type="hidden" name="chklistsrno[]" id="chklistsrno" value="{{$scrutinychklist->chklistsrno}}"></td>
                          <td width="480px">  {{$scrutinychklist->chklistdesc}}
                          </td>
                            <!-- <td> <select class="form-control chkList" name="compliance[]" id="compliance{{$scrutinychklist->chklistsrno}}" data-parsley-required data-parsley-required-message="Select Compliance"  style="height:34px" data-parsley-trigger='focus' >
                                 <option value="" >Select Compliance </option>
                                 <option value="Y">Yes</option>
                                 <option value="N">No</option>
                               </select> </td>-->
                             <td><textarea class="form-control remarks" name='remarks[]' id="remarks{{$scrutinychklist->chklistsrno}}" data-parsley-pattern="/^[-@.\/,()#+\w\s]*$/" data-parsley-pattern-message="Remarks Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Remarks'
                             data-parsley-maxlength='100' data-parsley-maxlength-message="Remarks accepts upto 100 characters"></textarea></td>



                             </tr>
                          @endforeach

             </tbody>
         </table>
</div>


<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="addAppRes"  onclick="closePopup(this)" data-id="{{ $applicantDetails->applicationid }}" data-target-id="{{ $applicantDetails->applicationid }}"  data-toggle="modal" data-target="#myModalf1" >
  <i class="fa fa-plus">Objection/Objections</i>
</div>
<span id="modlerror2"></span>

 <div class="col-md-12">
                <table id="myTable4" class="table myTable table-bordered table-striped application-list"  table order-list style="width:100%;" >
                  <thead>
                    <h4 colspan="3" >Any other Objection</h4>
                  </thead>
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                   <!--    <td>Sr. No.</td>  -->
                      <td>Name of Objection</td>
                       <!--<td>Compliance</td>-->
                      <td colspan="2">Reason</td>
                 </tr>
                </thead>
             <tbody>
             </tbody>
               <tfoot>
                <tr>
                  <td colspan="4" style="text-align: right;">
                    <input type="button" class="btn btn-md btn-primary " id="addrow2" value="Add New" />
                  </td>
                </tr>
                <tr>
                </tr>
                </tfoot>

             </tbody>
         </table>
      </div>
<div class="col-md-12">

     <table class="table no-margin table-bordered">
        <tr>
        <td> <span class="mandatory">*</span> <label for="applTitle">Application complied</label> </td>
         <td>
                 <select class="form-control" name="applicationComplied" id="applicationComplied" data-parsley-required data-parsley-required-message="Select Application complied" data-parsley-trigger='focus'   >
                  <option value="">Select </option>

                      <option value="Y">Yes</option>
                         <option value="N">No</option>
                            <option value="NA">NA</option>
                  </select>
              </td>

            <td> <span class="mandatory">*</span> <label for="applTitle">Date of Accepted/Objected</label> </td>
        <td>
           <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="accrejdate" class="form-control pull-right datepicker" id="accrejdate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Invalid date" value=""  data-parsley-required-message="Enter Date of Accepted/Objected"  data-parsley-errors-container='#enrollerror'>
                </div>
           </td>
            <td>  <label for="applTitle">Date to be complied</label> </td>
            <td>
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                   <input type="text" name="tobecomplieddate" class="form-control pull-right datepicker" id="tobecomplieddate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-pattern-message="Invalid Date" value="" >
                    </div>
           </td>
           </tr>
            <tr>
              <td ><label for="applTitle">Reason For Objection </label>
              </td>
              <td colspan="3">
                 <textarea class="form-control" name='rejectReason' id="rejectReason"  >
              </textarea>
              </td>
            </tr>

       <tr>
        <td colspan="4">
        <div class="text-center">
          <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
             <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
               <a class="btn btn-primary" Style="width: 100px;" href="scrutiny"> Back </a>

              <!--  <a class="btn btn-danger btn-md center-block btnClear" href=""> Cancel </a>
        </div> -->

        </td>
        </tr>

</table>
</div>

 @elseif($insertUpdateflag=='U')

 <div class="col-md-12">
                <table id="myTable4" class="table myTable table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Sr. No.</td>
                      <td>Check Slip</td>
                     <!--<td>Compliance</td>-->
                      <td colspan="2">Remarks</td>
                 </tr>
                </thead>
             <tbody>
                   <?php $i = 1; ?>
                    @foreach($scrutinydetails as $scrutinydetails)
                    @if($scrutinydetails->observation == '')
                    <tr class="item">
                       <td>{{ $i++ }}
                       <input type="hidden" name="chklistsrno[]" id="chklistsrno" value="{{$scrutinydetails->chklistsrno}}"></td>
                       <td width="480px">  {{$scrutinydetails->chklistdesc}}
                       </td>
                  <!--     <td>
                      <select class="form-control chkList" name="compliance[]" id="compliance{{$scrutinydetails->chklistsrno}}" data-parsley-required data-parsley-required-message="Select Compliance"  style="height:34px" data-parsley-trigger='focus' >
                           @if(($scrutinydetails->objectedflag)=="Y"){
                             <option value="Y" selected>Yes</option>
                             <option value="N">No</option>    }
                            @else
                            {
                            <option value="Y">Yes</option>
                            <option value="N" selected>No</option>
                           }
                           @endif
                         </select> </td>-->
                       <td><textarea class="form-control remarks" name='remarks[]'  >{{$scrutinydetails->remarks}}</textarea></td>
                       </tr>

                       @endif
                 @endforeach

             </tbody>
         </table>
</div>

<div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="addAppRes"  onclick="closePopup(this)" data-id="{{ $scrutinydetails->applicationid }}" data-target-id="{{ $scrutinydetails->applicationid }}"  data-toggle="modal" data-target="#myModalf1" >
  <i class="fa fa-plus">Another Objection</i>
</div>
<span id="modlerror2"></span>

 <div class="col-md-12">
                  <table id="myTable5" class="table myTable table-bordered table-striped  application-list table order-list" style="width:100%;" >
                 <thead>
                    <h4 colspan="3" >Any other Objection</h4>
                  </thead>
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >

                      <td>Name of Objection</td>
                      <!--<td>Compliance</td>-->
                      <td colspan="2">Remarks</td>
                 </tr>
                </thead>
             <tbody>

            @foreach($scrutinydetails11 as $scrutinydetails11)
                  @if(($scrutinydetails11->observation)!= '')
                    <tr class="item" >
                      <td width="480px"><textarea type="text" name="extraObjection[]"  id="extraObjection" data-parsley-required data-parsley-required-message="Enter Objection" data-parsley-trigger="focusout"  class="form-control number" /> {{$scrutinydetails11->observation}}  </textarea>
                       </td>
                      <!-- <td>
                      <select class="form-control chkList" name="extraCompliance[]" id="extraCompliance" data-parsley-required data-parsley-required-message="Select Compliance"  style="height:34px" data-parsley-trigger='focus' >
                           @if(($scrutinydetails11->objectedflag)=="Y"){
                             <option value="Y" selected>Yes</option>
                             <option value="N">No</option>    }
                            @else
                            {
                            <option value="Y">Yes</option>
                            <option value="N" selected>No</option>
                           }
                           @endif
                         </select> </td>-->
                       <td><textarea class="form-control remarks" name='extraRemarks[]' id="extraRemarks" >{{$scrutinydetails11->remarks}}</textarea></td>
                       <td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>
                       </tr>
                    @endif
                 @endforeach









                <tfoot>
                <tr>
                  <td colspan="4" style="text-align: right;">
                    <input type="button" class="btn btn-md btn-primary " id="addrow2" value="Add New" />
                  </td>
                </tr>
                <tr>
                </tr>
                </tfoot>

             </tbody>
         </table>
      </div>
<div class="col-md-12">

     <table class="table no-margin table-bordered">

        <tr>
        <td> <span class="mandatory">*</span> <label for="applTitle">Application complied</label> </td>

          <td>
                 <select class="form-control" name="applicationComplied" id="applicationComplied" data-parsley-required data-parsley-required-message="Select Application complied" data-parsley-trigger='focus'  >

                @if(($scrutiny->acceptreject)=="Y"){
                            <option value="">Select </option>
                            <option value="NA">NA</option>
                            <option value="Y" selected>Yes</option>
                            <option value="N">No</option>

                             }
                  @elseif(($scrutiny->acceptreject)=="NA"){
                                         <option value="">Select </option>
                                         <option value="NA" selected>NA</option>
                                         <option value="Y" >Yes</option>
                                         <option value="N">No</option>

                                          }
                            @else
                            {
                            <option value="">Select </option>
                            <option value="NA">NA</option>
                            <option value="Y">Yes</option>
                            <option value="N" selected>No</option>
                           }
                           @endif
                       </select>
              </td>

            <td> <span class="mandatory">*</span> <label for="applTitle">Date of Accepted/Objected</label> </td>
            <td>
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="accrejdate" class="form-control pull-right datepicker" id="accrejdate"  data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="" data-parsley-required-message=""  data-parsley-errors-container=''
                    value="{{ date('d-m-Y', strtotime($scrutiny->accrejdate)) }}">
                </div>
           </td>

            <td> <label for="applTitle">Date to be complied</label> </td>
            <td>
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="tobecomplieddate" class="form-control pull-right datepicker" id="tobecomplieddate" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-pattern-message="Invalid Date" value="{{ date('d-m-Y', strtotime($scrutiny->tobecomplieddate)) }}">
                </div>
           </td>
           </tr>
            <tr>
              <td ><label for="applTitle">Reason For Objection </label>
              </td>
              <td colspan="3">
                 <textarea class="form-control" name="rejectReason" id="rejectReason">{{$scrutiny->reason}} </textarea>
              </td>
            </tr>
            <tr>
         <td colspan="4">
         <div class="text-center">
           <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="U">
              <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Update">
              <a class="btn btn-primary" Style="width: 100px;" href="scrutiny"> Back </a>

          </div>

         </td>
         </tr>


</table>
</div>
@endif


</form>
<div class="modal fade" id="myModalf1" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title" style="text-align: center;">Objection/Objections </h4>
      </div>
     <div class="modal-body">

        <form action="AddExtraQuestionaries" method="POST" id ="form" data-parsley-validate>
        @csrf
     <div class="card-hearder">
     <!--<button type="button" class="btn btn-danger" id="deleteAllSelectedRecords">Delete Selected</button> -->
     </div>
        <div class="row">
          <table id="myTable4" class="table table-bordered table-striped  table order-list" style="width:100%;" >
            <thead >
              <tr style="background-color: #3c8dbc;color:#fff" >
                <td><input type="checkbox" id="chkCheckAll" /></td>

                <td>Sr No</td>
                <td>Questionaries</td>
                <td>Remarks</td>
                <td>Updated By</td>

              </tr>
          </thead>

          <tbody>
            <input type="hidden" name="applicationid" id="applicationid" value="{{$applicantDetails->applicationid}}">

            <?php $i = 1; ?>
             @foreach($scrutinychklist_extra as $scrutinydetails)
             <tr class="item">
            <td><input type="checkbox" name="ids[]" class="checkBoxClass" value="{{$scrutinydetails->chklistsrno}}"   /></td>
      <input type="checkbox" name="ids1[]" class="checkBoxClass1" style="opacity:0; position:absolute; " value="{{$scrutinydetails->chklistsrno}}"   />
        <input type="hidden" name="flag[]"  value="Y" >


            <td>{{ $i++ }}</td>
            <td width="480px">  {{$scrutinydetails->chklistdesc}}</td>

         <!--  <input type="hidden" name="chklistsrno[]" id="chklistsrno" value="{{$scrutinydetails->chklistsrno}}">-->
            <input type="hidden" name="chklistsrno" id="chklistsrno" value="{{$scrutinydetails->chklistsrno}}" >


                <td><textarea  class="form-control remarksQuestionaries"  name='remarksQuestionaries[]'  id="remarksQuestionaries" value="{{$scrutinydetails->chklistsrno}}"></textarea></td>

                <td><textarea  class="form-control updatedby"  name='updatedby[]'  id="updatedby"  value="{{$scrutinydetails->chklistsrno}}" disabled></textarea></td>
                <input type="hidden" name="accrejdate" class="form-control pull-right datepicker" id="accrejdate"  data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="" data-parsley-required-message=""  data-parsley-errors-container=''
                value="{{ date('d-m-Y', strtotime($scrutiny->accrejdate)) }}">

                </tr>

          @endforeach

          <tr>
           <td colspan="4">
           <div class="text-center">
            <input type="hidden" name="sbmt_ques" id="sbmt_ques" value="A">
             <center>  <input type="submit" id="submit" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="submit">
             </center>
            </div>

           </td>
           </tr>



          </tbody>
        </table>

</div>
</form>

  </div>
</div>
</div>
</div>


 @if ($errors->any())
    <div class="alert alert-danger">
         <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<script src="js/jquery-3.4.1.js"></script>

<script src="js/scrutiny/scrutiny.js"></script>
<script>
    $(function(e) {
        $("#chkCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'))
        });
      });
</script>
<script>
/*$.ajax({
      type: 'post',
      url: formaction,
      data: $('#'+form).serialize(),
      success: function (data) {
      if(data.errors)
          {
            var errorlist = data.errors;
              for (var i = 0; i < errorlist.length; i++) {
              $("#errorlist").empty();
              $("#errorlist").append("<li >"+errorlist[i]+"</li>");
              $("#modal-default").modal('show');
              }
          }
          if(data.status=="sucess")
          {

              $("#officenote").val('');
            $("#officenoteDate").val('');
            swal({
              title: data.message,
              icon: "success"
            })
            ;
            getofficenoteDetails(applicationId);
          }
          else if(data.status=="fail")
          {
            swal({
            title: data.message,
            icon: "error"
            })
          }
      }
    });*/
  </script>

<script>
$(document).ready(function() {
$('.checkBoxClass').change(function() {
    if(this.checked) {
      $(this).closest('tr').find('textarea.remarks').attr('data-parsley-required', true)
        $(this).closest('tr').find('[name = "flag[]"]').eq(0).val('Y');
    }
  else{
    $(this).closest('tr').find('textarea.remarks').removeAttr('data-parsley-required', true)
    $(this).closest('tr').find('[name = "flag[]"]').eq(0).val('N');


    }

});
});
</script>
<script>
$(document).ready(function() {
  $(".remarksQuestionaries").on({
      focus:function(){
          $(this).data('curval',this.value);
      },
      'blur keyup':function(){
          if(this.value !== $(this).data('curval')){
              var val = this.value;
              $(this).data('curval',val).parents('tr').find('.checkBoxClass').prop('checked',true);
          }

      }
  });
});


</script>

<script>
$('form').on('submit',function(e){
    e.preventDefault();

  $.ajax({
        type     : "POST",
        cache    : false,
        url      : $(this).attr('action'),
        data     : $(this).serialize(),
        success  : function(data) {
          if(data.status=="sucess")
          {
          $("#remarksQuestionaries").val('');
          $("#ids").val('');
            swal({
              title: data.message,
              icon: "success"
            })
            ;
          }
          if(data.status=="error")
          {
      //    $("#remarksQuestionaries").val('');
      //    $("#ids").val('');
            swal({
              title: data.message,
              icon: "error"
            })
            ;
          }
          if(data.status=="null")
          {
        //    $("#remarksQuestionaries").val('');
        //    $("#ids").val('');
            swal({
              title: data.message,
              icon: "error"
            })
            ;
          }

          if(data.status=="update")
          {
          $("#remarksQuestionaries").val('');
          $("#ids").val('');
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
function closePopup(obj)
{
  var applicationid = $(obj).data('id');
  console.log(applicationid);
  console.log('1');
    $.ajax({
    type: 'get',
    url: "getScrutinyDetailsForExtraQuestions",
    dataType: "JSON",
    data: {
      "_token": $('#token').val(),
      applicationid: applicationid
      },
    success: function(json1) {

      if(json1==""){
     return;
      }else{
        console.log(json1);
        console.log(json1.length);

        $("#sbmt_ques").val('U');
        $("#submit").val('Update');

      for (var i = 0; i < json1.length; i++) {
     if(json1[i].objectedflag=='Y'){
       $('input[name^="ids"][value="' + json1[i].chklistsrno + '"').prop('checked', true);
        }
        else{
          if(json1[i].remarks==null){
           $('input[name^="ids"][value="' + json1[i].chklistsrno + '"').prop('checked', true);

         }
        }
     }

     for (var i = 0; i < json1.length; i++) {
      $('input[name^="ids1"][value="' + json1[i].chklistsrno + '"').prop('checked', true);
    }


     var values = new Array();
     var i=0;
     $('.checkBoxClass').each(function(j) {
    if (this.checked) {
        var checked=j;
        values[i]= j;
        i++;
      //  console.log(checked);
     console.log( 'checked index at ' +checked+' json index at '+ i);
        //console.log(checked);
     }
     else{

       if (this.checked) {
           values[i]= j;
           i++;
        }

     }
 });
     for(i=0;i<json1.length;i++)
    { console.log(json1);
       $( '[name = "remarksQuestionaries[]"]').eq(values[i]).val(json1[i].remarks);
    }

    for(i=0;i<json1.length;i++)
    { //console.log(checked);
      console.log(json1[i].updatedby);
      $( '[name = "updatedby[]"]').eq(values[i]).val(json1[i].updatedby);
       if(json1[i].objectedflag=='N'){
        $('input[name^="ids"][value="' + json1[i].chklistsrno + '"').prop('checked', false);
      }
  }


    }
  }
  });
}
</script>

<script>
/*$(document).ready(function(){
$('.checkBoxClass').click(function(){
      console.log('i am here');
      var i;
      $('.checkBoxClass').each(function(j) {
     if (this.checked) {
         i= j;
        console.log(i);
      }
});
  console.log(i);
  $( '[name = "remarksQuestionaries[]"]').eq(i).text('hi');



    });
}); */
</script>





@endsection
