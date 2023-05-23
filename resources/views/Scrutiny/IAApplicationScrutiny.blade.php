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

<form id="iaApplScrutinyForm" action="saveiascrutiny" method="POST" data-parsley-validate>
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




     		<td> <span class="mandatory">*</span> <label for="applTitle">Application Number
              <td>
              	 <input type="text" name="applicationNo" id="applicationNo" readonly="readonly"
                 value="{{$applicationNo}}" class="form-control"  >

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
                 <input type="text" name="applicantName" id="applicantName" readonly
                 value="{{$applicantDetails->nametitle}} {{$applicantDetails->applicantname}}" class="form-control"  >
              </td>
            </tr>
            <tr>
              <td ><span class="mandatory">*</span> <label for="applTitle">Name of Respondant </label>
              </td>
              <td colspan="3">
                 <input type="text" name="respondantName" id="respondantName" readonly
                 value="{{$respondantDetails->respondname}}" class="form-control"  >
              </td>
            </tr>
            <tr>
              <td ><span class="mandatory">*</span> <label for="applTitle">Department </label>
              </td>
              <td colspan="3">
                  <select class="form-control" name="department" id="department" required data-parsley-required data-parsley-required-message="Select department" data-parsley-pattern="/[0-9]+$/" style="height:34px" data-parsley-trigger='focus' disabled>
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


<div class="col-md-12">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h7>IA Details  </h7> </td>
        </tr>
</table>

     <table class="table no-margin table-bordered">

        <tr>
     		<td> <span class="mandatory">*</span> <label >IA Document No.</label> </td>
        <td>
		   <input type="hidden" name="iano" id="iano" value="{{$iaDetails->iano}}">
                 <input type="text" name="iadocumentno" id="iadocumentno"
                 value="{{$iaDetails->iano}}" class="form-control" disabled>
        </td>

		<td> <span class="mandatory">*</span> <label for="applTitle">IA reason for </label>
              </td>
            <td colspan="3">
                  <select class="form-control" name="ianature" id="ianature"  disabled>
                  <option value="" >Select IAnature </option>
                    @foreach($IANature as $IANature)
                          <?php if(($iaDetails->ianaturecode)==$IANature->ianaturecode){?>
                          <option value="{{$IANature->ianaturecode}}" selected>
                            {{$IANature->ianaturedesc}}
                          </option>
                          <?php  }
                          else
                          {?>
                          <option value="{{$IANature->ianaturecode}}">
                            {{$IANature->ianaturedesc}}
                          </option>
                          <?php } ?>
                   @endforeach
               </select>
              </td>
		</tr>
        <tr>
        <td> <span class="mandatory">*</span> <label for="applTitle">Filling Date</label> </td>
        <td>
           <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="iaFilingDate" id="iaFilingDate"
                     value="{{ date('d-m-Y', strtotime($iaDetails->iafillingdate)) }}"
                     class="form-control" disabled >
                </div>


           </td>
		<td> <span class="mandatory">*</span> <label for="applTitle"> Registered On </label> </td>
        <td>
           <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="iaRegDate" id="iaRegDate"
                     value="{{ date('d-m-Y', strtotime($iaDetails->iaregistrationdate)) }}"
                     class="form-control" disabled >
                </div>
           </td>      
		</tr>
          
        
</table>
</br>
</br></br>
</br>
</div>

@if($insertUpdateflag=='I')
    <div class="col-md-12">
                <table id="myTable4" class="table myTable table-bordered table-striped  table order-list" style="width:100%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                      <td>Sr. No.</td>
                      <td>Check Slip</td>
                      <td>Compliance</td>
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
                             <td> <select class="form-control chkList" name="compliance[]" id="compliance{{$scrutinychklist->chklistsrno}}" data-parsley-required data-parsley-required-message="Select Compliance"  style="height:34px" data-parsley-trigger='focus' >
                                 <option value="" >Select Compliance </option>
                                 <option value="Y">Yes</option>
                                 <option value="N">No</option>
                               </select> </td>
                             <td><textarea class="form-control remarks" name='remarks[]' id="remarks{{$scrutinychklist->chklistsrno}}" data-parsley-pattern="/^[-@.\/,()#+\w\s]*$/" data-parsley-pattern-message="Remarks Accepts Only Characters" data-parsley-required data-parsley-required-message='Enter Remarks'
                             data-parsley-maxlength='100' data-parsley-maxlength-message="Remarks accepts upto 100 characters"></textarea></td>



                             </tr>
                          @endforeach

             </tbody>
         </table>
</div>
 <div class="col-md-12">
                <table id="myTable4" class="table myTable table-bordered table-striped application-list"  table order-list style="width:100%;" >
                  <thead>
                    <h4 colspan="3" >Any other Objection</h4>
                  </thead>
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >
                   <!--    <td>Sr. No.</td>  -->
                      <td>Name of Objection</td>
                      <td>Compliance</td>
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
                      <td>Compliance</td>
                      <td>Reason</td>
                 </tr>
                </thead>
             <tbody>
                   <?php $i = 1; ?>
                    @foreach($iascrutinydetails as $scrutinydetails)
                    @if($scrutinydetails->observation == '')
                    <tr class="item">
                       <td>{{ $i++ }}
                       <input type="hidden" name="chklistsrno[]" id="chklistsrno" value="{{$scrutinydetails->chklistsrno}}"></td>
                       <td width="480px">  {{$scrutinydetails->chklistdesc}}
                       </td>
                       <td>
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
                         </select> </td>
                       <td><textarea class="form-control remarks" name='remarks[]' id="remarks{{$scrutinydetails->chklistsrno}}" >{{$scrutinydetails->remarks}}</textarea></td>
                       </tr>
                       @endif
                 @endforeach

             </tbody>
         </table>
</div>



 <div class="col-md-12">
                  <table id="myTable5" class="table myTable table-bordered table-striped  application-list table order-list" style="width:100%;" >
                 <thead>
                    <h4 colspan="3" >Any other Objection</h4>
                  </thead>
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >

                      <td>Name of Objection</td>
                      <td>Compliance</td>
                      <td colspan="2">Reason</td>
                 </tr>
                </thead>
             <tbody>

            @foreach($iascrutinydetails11 as $scrutinydetails11)
                  @if(($scrutinydetails11->observation)!= '')
                    <tr class="item" >
                      <td width="480px"><textarea type="text" name="extraObjection[]"  id="extraObjection" data-parsley-required data-parsley-required-message="Enter Objection" data-parsley-trigger="focusout"  class="form-control number" /> {{$scrutinydetails11->observation}}  </textarea>
                       </td>
                       <td>
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
                         </select> </td>
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

                @if(($iascrutiny->acceptreject)=="Y"){
                            <option value="">Select </option>
                            <option value="Y" selected>Yes</option>
                            <option value="N">No</option>
                             }
                            @else
                            {
                            <option value="">Select </option>
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
                    value="{{ date('d-m-Y', strtotime($iascrutiny->accrejdate)) }}">
                </div>
           </td>

            <td> <label for="applTitle">Date to be complied</label> </td>
            <td>
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="tobecomplieddate" class="form-control pull-right datepicker" id="tobecomplieddate" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-pattern-message="Invalid Date" value="{{ date('d-m-Y', strtotime($iascrutiny->tobecomplieddate)) }}">
                </div>
           </td>
           </tr>
            <tr>
              <td ><label for="applTitle">Reason For Objection </label>
              </td>
              <td colspan="3"><textarea class="form-control" name="rejectReason" id="rejectReason">{{$iascrutiny->reason}} </textarea>
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

<script src="js/scrutiny/iascrutiny.js"></script>
</script>
@endsection
