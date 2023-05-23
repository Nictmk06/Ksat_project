@extends('layout.mainlayout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
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
  input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  margin: 0;
  }
  <style>
  .text{
  white-space: pre-wrap;
  }
  </style>

  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @if ($message = Session::get('success'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
          <h1>{{ $message }}</h1>
  </div>
  @endif

  @if ($message = Session::get('error'))
  <div class="alert alert-success alert-block">
    <button type="button"  class="close" data-dismiss="alert">×</button>
          <h1 style="color:#6c1515;">{{ $message }}</h1>
  </div>
  @endif

  <section class="content">
   
 <div>
     <form role="form" id="freshApplicationForm" method="POST" action="conversionUnnumber" data-parsley-validate>
      @csrf
    <div class="panel  panel-primary">
      <div class="panel panel-heading">
        <h7 >Register UnApplication</h7>
      </div>
            <div class="row">
              <div class="col-md-6">
                <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                <div class="form-group">
                  <label>Type of Application<span class="mandatory">*</span></label>
                  <select class="form-control" name="applTypeName" id="applTypeName"  >
                    @foreach($applicationType1 as $applType)

                    <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>

                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                   <div class="input-group date">
                    <input type="text" name="applno" class="form-control pull-right" id="applno" value="" data-parsley-maxlength="15" data-parsley-maxlength-message="Application No Should have maximum 15 digit"  data-parsley-trigger='keypress'data-parsley-errors-container="#modlerror">


                   <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>
                

                 
                  <span id="modlerror"></span>
                </div>
              </div>
          </div>
          
          <div class="row" >
             <div class="col-md-6">
             <label>Register Date<span class="mandatory">*</span></label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="registerdate" class="form-control pull-right datepicker" id="registerdate"  value="" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-required data-parsley-pattern-message="Register Date Allows only digits" value=""  data-parsley-required-message="Enter Register Date"  value="" data-parsley-errors-container="#error3" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY">
                     </div>
              </div>

              <div class="col-md-6">
                    <div class="form-group">
                      <label>Registered Year:<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="applYear"  class="form-control pull-right datepicker1"  id="datepicker1" data-parsley-date-format="YYYY" readonly="" data-parsley-trigger='keypress' >
                      </div>
                    </div>
                  </div>
              
             
            </div>

            <div class="row">
               <div class="col-md-6">
                    <div class="form-group">
                      <label>KSAT Act<span class="mandatory">*</span></label>
                      <select class="form-control" name="actName" disabled>
                        <option value="">Select Act</option>
                        @foreach($actDetails as $act)
                      <option value="{{$act->actcode}}" selected>{{$act->actname}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
             <div class="col-md-6">
                    <div class="form-group">
                      <label>Section Name<span class="mandatory">*</span></label>
                      <select class="form-control" name="actSectionName" id='actSectionName'  readonly>
                        <option value="">Select Section Name</option>
                        @foreach($sectionDetails as $actsection)
                        <option value="{{$actsection->actsectioncode}}">{{$actsection->actsectionname}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
            

            </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject Category<span class="mandatory">*</span></label>
                      <select class="form-control" name="applCatName" id="applCatName" data-parsley-trigger='keypress'
                      readonly>
                        <option value="" class="form-control">Select Applcation Category</option>
                        @foreach($applCategory as $applCat)

                        <option value="{{$applCat->applcatcode}}" class="form-control">{{$applCat->applcatname}}</option>

                        @endforeach
                      </select>
                    </div>
                  </div>
               <div class="col-md-4">
                    <div class="form-group">
                      <label>No of Applicants.<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfAppl" id="noOfAppl" type="number" value="" data-parsley-required data-parsley-required-message="Enter No fo Applicants" data-parsley-minlength="1" data-parsley-minlength-message="No of Applicants Should have minimum 1 digit" data-parsley-maxlength="4" data-parsley-maxlength-message="No of Applicants Should have maximum 4 digit" data-parsley-trigger='keypress'  >
                    </div>
                  </div>

                 <div class="col-md-4">
                    <div class="form-group">
                      <label>No of Respondants<span class="mandatory">*</span></label>
                      <input class="form-control number zero" name="noOfRes" type="number" id="noOfRes"  value="" data-parsley-trigger='keypress' >
                    </div>
                  </div>

                    <div class="col-md-4">
                    <div class="form-group">
                      <label>Subject<span class="mandatory"></span></label>
                      <textarea class="form-control" name='applnSubject' id="applnSubject"     readonly></textarea>
                    </div>
                  </div>
                </div>

            <div class="row">

            <div class="col-sm-12 text-center">
                  <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" onclick="this.onclick=null;"  Style="width: 100px;" value="Register">
                  <input type="button" class="btn btn-danger btn-md center-block btnClear" Style="width: 100px;" value="Cancel">
                </div>
              </div>
      </div>
 
 
        
 </form>
      </div>
      
     </section>

      
     </div>

      <!-- /.tab-pane -->
      <script src="js/jquery-3.4.1.js"></script>
        <script src="js/jquery.min.js"></script>
        
       <script>
$(document).ready(function() {
     
      $("#datepicker1").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years"
    });

$("#registerdate").change(function() {
        var doa = $("#registerdate").val();
        var split = doa.split("-");
        $("#datepicker1").val(split[2]);
    })

    $("#applSearch").click(function() {

        if ($("#applno").val() == '') {
            $('#applno').parsley().removeError('applno');
            $('#applno').parsley().addError('applno', {
                message: "Enter Application No"
            });
            return false;
        } else {
            $('#applno').parsley().removeError('applno');
        }
        var appltypename = $("#applTypeName").val();
        var newtype = appltypename.split('-');
        var applnewtype = newtype[1];
        var applno = $("#applno").val();
        var applId = applnewtype+'/'+ applno;
        var applicationid=applId;
       var applTypeName = $("#applTypeName").val().split('-')[1]; 
     
       
         $.ajax({
            type: 'POST',
            url: 'getApplDetails',
            data: {
               "_token": "{{ csrf_token() }}",
        "applicationid": applicationid
            },
            dataType: "JSON",
            success: function(json) {
               console.log(json);

                  
                if (json.length > 0) {
                    //console.log(json.length);
                           $("#noOfAppl").val(json[0].applicantcount);
                            $("#noOfRes").val(json[0].respondentcount);
                            $("#actSectionName").val(json[0].actsectioncode);
                            $("#applnSubject").val(json[0].subject);
                            $("#applCatName").val(json[0].applcategory);
                } else {

                      swal({
                        title: json.message,

                        icon: "error",

                      });
                }
            }
        });
        
  
    })
  });

  </script>

<script>
  $('#freshApplicationForm').submit(function(e)
    {

      e.preventDefault();
        swal({
         title: "Are you sure you want to assign a regular Number?",
         icon: "warning",
         buttons:[
             'Cancel',
             'OK'
             ],
             }).then(function(isConfirm){
                 if(isConfirm){
                     $('#freshApplicationForm').submit();
                 }
         });
        // }
      //   else{
       //      alert('No of applicants and Receipt Amount is not matching !');
     //        return false;
       //  }
     });
  </script>



    </section>
    @endsection
