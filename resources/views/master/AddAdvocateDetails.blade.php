@extends('layout.mainlayout')
@section('content')


<div class="content-wrapper">

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

  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>

@include('flash-message')


<div class="container">
  <div class="modal fade" id="editmodal">
       <div class="modal-dialog modal-md">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title" id='edit_appl-title'>Edit Avocate Details</h4>
           </div>
           <div class="modal-body">
             <input name="_token" type="hidden" value="{{ csrf_token() }}"/>

             <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Advocate Reg No<span class="mandatory">*</span></label>
                    <center> <div class="input-group date">
                     <input type="text" name="edit_advocateregno" class="form-control pull-right" id="edit_advocateregno" value="">
                     <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="editSearch">
                       <i class="fa fa-search"></i>
                     </div>

                   </div>  </center>
                   <span id="modlerror"></span>
                 </div>
               </div>
             </div>

           </div>

         </div>
         <!-- /.modal-content -->
       </div>
       <!-- /.modal-dialog -->
     </div>
  <ul class="nav nav-tabs" id="myTab">
     <input type='hidden' id="canelid" value=''>
    <!--<li style="float:right;"  id="cancelApplication"><input type="button"  id="" class="btn btn-danger   btn-md center-block" Style="width: 100px;" value="Cancel"></li>-->
    <li style="float:right;" id="editApplication" > <input type="button"  id="" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Edit"></li>



  </ul>

<form action="AddAdvocateSave" method="POST" data-parsley-validate>
@csrf
 <h3 class="bg-primary text-center" colspan="4">Add Advocate Information </h3>

<div class="panel  panel-primary">
  <!--<div class="panel panel-heading">
    <h7 > Applcation</h7>
  </div> -->

  <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
  <div class="panel panel-body">
    <div class="row">

      <div class="col-md-4">
        <div class="form-group">
          <label>Advocate Code<span class="mandatory">*</span></label>
          <input type="numeric" class="form-control pull-right" id="advocatecode" name="advocatecode"
          value="<?php $advocatecode=DB::select("SELECT max(CAST(advocatecode as INT))as advocatecode from advocate")[0];
                    $advocatecodenew=$advocatecode->advocatecode+1;
                    $userSave['advocatecode']= $advocatecodenew;
                    echo   $userSave['advocatecode'] ?>"  readonly='readonly' >
        </div>
      </div>
      <div class="col-md-4">
          <div class="form-group">
            <label>Advoate Reg Number<span class="mandatory">*</span></label>
            <input type="text" class="form-control pull-right" id="advocateregno" name="advocateregno"    placeholder="eg: KAR/1234/2014"  data-parsley-required data-parsley-required-message="Enter Advocate Bar Reg No." data-parsley-pattern="^([a-zA-Z]{3})\/([0-9]{1,6})\/([0-9]{4})$" data-parsley-pattern-message="Please Enter Advocate Reg No in Correct Format"  data-parsley-trigger='keypress'>
         </div>
    </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Name  of the Advoate<span class="mandatory">*</span></label></br>
          <input type="text" class="form-control pull-right" id="name" name="name"  style="text-transform:uppercase"   data-parsley-pattern="/[A-Za-z!@#$%^&,'*()]/" data-parsley-pattern-message="Name of Advoate allows only Alphanumeric" data-parsley-pattern-message="Name of Advoate allows only Alphanumeric" value=""
           data-parsley-required  data-parsley-required-message="Enter Name of the Advocate">
        </div>
      </div>


    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Advocate Type <span class="mandatory" >*</span></label>
          <select class="form-control" name="advocatetype" id="advocatetype" data-parsley-required  data-parsley-required-message="Select Advocate Type " >
            <option value="" >Select Advocate Type</option>
            @foreach($advocatetype as $advocatetype)
            <option value="{{$advocatetype->advocatetypecode}}">{{$advocatetype->advocatetypedesc}}</option>
            @endforeach

          </select>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label>Advocate D.O.B</label>
        <input type="text" name="advocatedob" class="form-control pull-right datepicker" id="advocatedob"  value="" data-date-format="dd/mm/yyyy" data-parsley-pattern="/^[0-9_-]+$/"   data-parsley-pattern-message="DOB  Allows only digits" >

    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Advocate E-Mail</label>
      <input type="text" class="form-control pull-right" id="advocateemail" name="advocateemail"   data-parsley-type="email" data-parsley-required-message="Enter valid Email "value="">

  </div>
</div>
    </div>
    <div class="row">

    <div class="col-md-4">
      <label>Designation<span class="mandatory"></span></label>
      <select class="form-control" name="designation" id="designation" >
        <option value="" >Select Designation</option>
        @foreach($advocatedesignation as $advocatedesignation)
        <option value="{{$advocatedesignation->advdesigcode}}">{{$advocatedesignation->advdesigname}}</option>
        @endforeach

      </select>
  </div>

  <!-- /.col -->
  <div class="col-md-4">
    <div class="form-group">
      <label>Advocate Mobile Number</label>
      <input type="tel" class="form-control pull-right" placeholder="999999999" step="100"  id="advocatemobileno" name="advocatemobileno"
         data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
         data-parsley-type="digits" data-parsley-maxlength="10" />
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Advocate Phone Number 1</label>
      <input type="tel" class="form-control pull-right" placeholder="01722222222" step="100"  id="advoatephone" name="advoatephone"
         data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
         data-parsley-type="digits" data-parsley-maxlength="11" />
    </div>
  </div>

</div>
<div class="row">
  <div class="col-md-4" >
    <div class="form-group">
      <label>Advocate Phone Number 2</label>
      <input type="tel" class="form-control pull-right" placeholder="01722222222"  step="100"  id="advoatephone1" name="advoatephone1"
         data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
         data-parsley-type="digits" data-parsley-maxlength="11" />
    </div>
  </div>
  <div class="col-md-12" >
    <div class="form-group">
      <label>Advocate Office Address</label>
      <textarea class="form-control pull-right" id="officeaddress" name="officeaddress"    data-parsley-pattern="/[A-Za-z!@#$%^&,'*()]/" data-parsley-pattern-message="Address can only be Alphanumeric"  value=""></textarea>

    </div>
  </div>
  <div class="col-md-4" >
    <div class="form-group">
      <label>  Advocate Office District</label>
      <select class="form-control" name="officedistrict" id="officedistrict"  >
        <option value="" >Select District</option>
        @foreach($district as $district)
        <option value="{{$district->distcode}}">{{$district->distname}}</option>
        @endforeach

      </select>

    </div>
  </div>

  <div class="col-md-4" >
    <div class="form-group">
      <label>  Advocate Office Taluk</label>
      <select class="form-control" name="officetaluk" id="officetaluk"  >
        <option value="" >Select Taluk</option>
        @foreach($taluk1 as $taluk1)
        <option value="{{$taluk1->talukcode}}">{{$taluk1->talukname}}</option>
        @endforeach
     </select>
   </div>
 </div>

 <div class="col-md-4" >
   <div class="form-group">
     <label>Advocate Office State</label>
     <select class="form-control" name="officestate" id="officestate"  >
       <option value="" >Select State</option>
       @foreach($state as $state)
       <option value="{{$state->statecode}}">{{$state->statename}}</option>
       @endforeach

     </select>
   </div>
 </div>
</div>

<div class="row">

 <div class="col-md-4" >
   <div class="form-group">
     <label> Advocate Office Pincode</label>
     <input type="tel" class="form-control pull-right" id="officepincode" name="officepincode" value="" data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
     data-parsley-type="digits" data-parsley-maxlength="6"  >

   </div>
 </div>

  <div class="col-md-12" >
   <div class="form-group">
     <label>Advocate Resident Address</label>
     <textarea class="form-control pull-right" id="residentaddress" name="residentaddress"    data-parsley-pattern="/[A-Za-z!@#$%^&,'*()]/" data-parsley-pattern-message="Address can only be Alphanumeric"  value=""></textarea>

   </div>
 </div>

 <div class="col-md-4" >
   <div class="form-group">
     <label>Advocate Resident District</label>
     <select class="form-control" name="residentdistrict" id="residentdistrict"  >
       <option value="" >Select District</option>
       @foreach($district1 as $district1)
       <option value="{{$district1->distcode}}">{{$district1->distname}}</option>
       @endforeach

     </select>
   </div>
 </div>
</div >

<div class="row">
 <div class="col-md-4" >
   <div class="form-group">
     <label>Advocate Resident Taluk</label>
     <select class="form-control" name="residenttaluk" id="residenttaluk"  >
       <option value="" >Select Taluk</option>
       @foreach($t as $t)
       <option value="{{$t->talukcode}}">{{$t->talukname}}</option>
       @endforeach
     </select>
   </div>
 </div>

 <div class="col-md-4" >
   <div class="form-group">
     <label>Advocate Resident State</label>
     <select class="form-control" name="residentstate" id="residentstate"  >
       <option value="" >Select State</option>
       @foreach($state1 as $state1)
       <option value="{{$state1->statecode}}">{{$state1->statename}}</option>
       @endforeach

     </select>
   </div>
 </div>


 <div class="col-md-4" >
   <div class="form-group">
     <label>Resident Pincode</label>
     <input type="tel" class="form-control pull-right" id="residentpincode" name="residentpincode" value=""   data-parsley-validation-threshold="1" data-parsley-trigger="keyup"
     data-parsley-type="digits" data-parsley-maxlength="6"  >

   </div>
 </div>
</div>

<div class="text-center">
  <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
     <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">


       <a class="btn btn-warning" href=""> Cancel </a>
 </div>


</form>

@if ($errors->any())
   <div class="alert alert-danger">
        <ul>
           @foreach ($errors->all() as $error)
           @endforeach
           <li>{{ $error }}</li>
       </ul>
   </div>
@endif
</div>
</div>
<script src="js/jquery-3.4.1.js"></script>

<script>
         $(document).ready(function() {
        $('#officedistrict').on('change', function() {
            var districtcode = $(this).val();

            console.log(districtcode);
            if(districtcode) {
                $.ajax({
                    url: '/findDistrict_Taluk/'+districtcode,
                    type: "get",
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: "json",
                    success:function(data) {
                        console.log(data);
                      if(data){
                        $('#officetaluk').empty();
                        $('#officetaluk').focus;
                        $('#officetaluk').append('<option value=" ">-- Select Taluk --</option>');

                        $.each(data, function(key, value){
                          console.log(key);
                          console.log(value);
                        $('select[name="officetaluk"]').append('<option value="'+ value.talukcode +'">' + value.talukname+ '</option>');

                    });
                  }

                  else{
                    $('officetaluk').empty();
                  }
                  }
                });
            }else{
              $('taluk').empty();
            }
        });
    });
    </script>


    <script>
             $(document).ready(function() {
            $('#residentdistrict').on('change', function() {
                var districtcode = $(this).val();

                console.log(districtcode);
                if(districtcode) {
                    $.ajax({
                        url: '/findDistrict_Taluk_resident/'+districtcode,
                        type: "get",
                        data : {"_token":"{{ csrf_token() }}"},
                        dataType: "json",
                        success:function(data) {
                            console.log(data);
                          if(data){
                            $('#residenttaluk').empty();
                            $('#residenttaluk').focus;
                            $('#residenttaluk').append('<option value=" ">-- Select Taluk --</option>');

                            $.each(data, function(key, value){
                              console.log(key);
                              console.log(value);
                            $('select[name="residenttaluk"]').append('<option value="'+ value.talukcode +'">' + value.talukname+ '</option>');

                        });
                      }

                      else{
                        $('residenttaluk').empty();
                      }
                      }
                    });
                }else{
                  $('residenttaluk').empty();
                }
            });
        });
        </script>

        <script>
          	$("#editApplication").click(function(){
        		$('#editmodal').modal('show');
        			$('#edit_appl-title').text('Edit Advocate Details');
        	})

        	$("#editSearch").click(function(){
        		var advocateregno = $("#edit_advocateregno").val();
            console.log(advocateregno)
        		$.ajax({
        				type: 'get',
        				dataType:'JSON',
        				url: '/getdetailsofadvocate',
                data:{ "_token": $('#token').val(),advocateregno:advocateregno},

        				success: function(data) {
        					if(data.status=='success')
        					{
        						swal({
        								title: data.message,
        								icon: "error",
        							});
        					}
        					else
        					{
        							$("#editmodal").modal('hide');
        							$("#appends").hide();
        							$("#newcontent").show();
        							$("#newcontent").html(data);
                      $("#sbmt_adv").val('U');
            					$("#saveADV").val('Update');
                      for (var i = 0; i <= data.length; i++) {
                            $("#advocatecode").val(data[i].advocatecode);
                          	$("#advocateregno").val(data[i].advocateregno);
                          	$("#name").val(data[i].advocatename);
                            $("#advocatetype").val(data[i].advocatetypecode);
                          //  $("#advocatedob").val(data[i].advocatedob);
                        if(data[i].advocatedob!=null)
                          {
                            var advocatedob = data[i].advocatedob;
                            console.log(advocatedob);
                            var split4 = advocatedob.split('-');
                            $("#advocatedob").val(split4[2]+'-'+split4[1]+'-'+split4[0])

                          }
                          else{
                             $("#advocatedob").val(data[i].advocatedob);
                           }
                            $("#advocateemail").val(data[i].advocateemail);
                            $("#designation").val(data[i].advdesigcode);
                            $("#advocatemobileno").val(data[i].advocatemobile);
                            $("#advoatephone").val(data[i].advocatephone);
                            $("#advoatephone1").val(data[i].advocatephone1);
                            $("#officeaddress").val(data[i].advocateaddress);
                            $("#officedistrict").val(data[i].distcode);
                            $("#officetaluk").val(data[i].talukcode);
                            $("#officestate").val(data[i].statecode);
                            $("#officepincode").val(data[i].pincode);
                            $("#residentaddress").val(data[i].advresaddress);
                            $("#residentdistrict").val(data[i].advresdistcode);
                            $("#residenttaluk").val(data[i].advrestalukcode);
                            $("#residentstate").val(data[i].advstatecode);
                            $("#residentpincode").val(data[i].advrespincode);



        							/*	$("input[type=radio][name='isAdvocate']:checked").val(json[i].issingleadv);
        								$('input[type="radio"][name="isAdvocate"]').attr('disabled', true);
        								$('#advBarRegNo').attr('readonly', true);
        								$('.advancedSearch4').attr('readonly', true);
        								$(".advancedSearch4 .selection4").text(json[i].nametitle);
        								$(".title_sel4").css('display', 'none');
        								$("#advTitle").val(json[i].nametitle);

        								$("#appadvcode").val(json[i].advocatecode);
        								$("#advName").val(json[i].advocatename);
        								$("#advTitle").val(json[i].nametitle);
        								$("#advRegAdrr").val(json[i].advresaddress);
        								$("#advRegPin").val(json[i].advrespincode);

        								$("#advRegTaluk").append('<option value=' + json[i].advrestalukcode + 'selected>' + json[i].talukname + '</option>');
        								$("#advRegTaluk").attr('disabled', true);
        								$("#advRegDistrict").append('<option value=' + json[i].advresdistcode + 'selected>' + json[i].distname + '<option>');
        								$("#advRegDistrict").attr('disabled', true);  */
        							}
        					}
        				}
        			});

        	})
       /*	$("#cancelApplication").click(function(){
            var advocateregno = $("#edit_advocateregno").val();

           	swal({
          title: "Are you sure to delete  Advocate Reg no " + advocateregno + "  details?",

          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            var advocateregno = $("#edit_advocateregno").val();
        		$.ajax({
        				type: 'get',
        				dataType:'JSON',
        				url: '/deleteAdvocatedetails',
        				data: {"_token": $('#token').val(),advocateregno:advocateregno},
        				success: function(data) {
        					if(data.status=='success')
        					{
        						swal({
        								title: data.message,
        								icon: "success",
        							});
        						window.location.reload();
        					}
        					else
        					{
        						swal({
        								title: data.message,
        								icon: "error",
        						});
        					}

        				}
        			});
          } else {
           return false;
          }
        });

      })   */
        </script>
{{-- <script src="http://bladephp.co/download/multiselect/jquery.min.js"></script>
<link href="http://bladephp.co/download/multiselect/jquery.multiselect.css" rel="stylesheet" />
<script src="http://bladephp.co/download/multiselect/jquery.multiselect.js"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> --}}


</script>

@endsection
