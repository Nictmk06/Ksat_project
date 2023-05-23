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
  table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    border: 1px solid #ddd;
  }

  th, td {
    text-align: left;
    padding: 8px;
  }
  .fixed_header tbody{
    display:block;
    overflow:auto;
    height:100px;
    width:100%;
  }
  .fixed_header thead tr{
    display:block;}

  </style>


  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>

  @include('flash-message')
  @if ($errors->any())
     <div class="alert alert-danger">
          <ul>
             @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
             @endforeach
         </ul>
     </div>
     @endif
  <section class="content">
    <form role="form" id="form" action="applicationtransferSave"  method="post" data-parsley-validate>

      <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Transfer Application</h7>
        </div>

        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
        <div class="panel panel-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Transfer From Bench<span class="mandatory">*</span></label>
                <select class="form-control" name="establishment" id="establishment"  data-parsley-required data-parsley-required-message="Select  Establishment Name" >
                  <option selected="true" disabled="disabled">Select Establishment Name</option>
                 @foreach($establishment as $establishment)
                  <option value="{{$establishment->establishcode}}" >{{$establishment->establishname}}</option>
                  @endforeach

                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label> Application Type<span class="mandatory">*</span></label>
                <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-required data-parsley-required-message="Select Establishment" >
                  <option value="">Select Applcation Type</option>
                 @foreach($applicationType as $applType)
                  <option value="{{$applType->appltypecode .'-'. $applType->appltypeshort}}" >{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                  @endforeach

                </select>
              </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <input type="text" name="ApplId"  id="ApplId" class="form-control pull-right" value=""  maxlength='20'>
                    <div class="extraClick input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div>

                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
              <div class="col-md-4">
                <label>Date of Application<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="applicationdate" class="form-control pull-right datepicker "  style="pointer-events: none;" id="applicationdate" value=""   data-parsley-errors-container="#error6" >
                </div>
                <span id="error6"></span>
              </div>

              <div class="col-md-4">
                <label>Register Date<span class="mandatory">*</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="registerdate" class="form-control pull-right datepicker "  style="pointer-events: none;" id="registerdate" value=""   data-parsley-errors-container="#error6" >
                </div>
                <span id="error6"></span>
              </div>

          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label> Application Cateogry<span class="mandatory">*</span></label>
              <textarea class="form-control" name="applcategory" id="applcategory" value="" style="pointer-events: none;"    data-parsley-errors-container="#error6"></textarea>
              </div>
            </div>

              <div class="col-md-12" >
                <div class="form-group">
                  <label>Subject<span class="mandatory">*</span></label>
                  <textarea class="form-control" name="subject" id="subject" ></textarea>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Transfer to Bench<span class="mandatory">*</span></label>
                  <select class="form-control" name="toestablishment" id="toestablishment"  data-parsley-required data-parsley-required-message="Select Establishment " >
                    <option selected="true" disabled="disabled">Select Establishment Name</option>


                  </select>
                </div>
              </div>



          </div>
          <div class="row"  style="float: center;" id="add_apl_div">
            <div class="col-sm-12 text-center">
              <input type="hidden" name="sbmt_disconnected" id="sbmt_disconnected" value="A">
              <input type="submit" id="save" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
              <a class="btn btn-warning" href=""> Cancel </a>
            </div>
          </div>


  </div>
</div>

</form>


<script src="js/jquery.min.js"></script>

<script>
$(document).ready(function() {



  $("#dhearingDate").datepicker({
              format: 'dd-mm-yyyy',
              autoclose: true,
            }).on('changeDate', function(selected) {
              var endDate = new Date(selected.date.valueOf());
              $('#dorderDate').datepicker('setStartDate', endDate);
            }).on('clearDate', function(selected) {
              $('#dorderDate').datepicker('setEndDate', null);
            });



	$(".extraClick").click(function(){


  var modl_appltype_name = $("#applTypeName").val();
	var newtype = modl_appltype_name.split('-');
	var applnewtype = newtype[1];
	var modl_modl_applno = $("#ApplId").val();
	var applicationid =applnewtype+'/'+modl_modl_applno;

			$.ajax({
				type: 'get',
				url: "getAppDetails/{applicationid}",
				dataType:"JSON",
				data: {"_token": $('#token').val(),applicationid : applicationid },
				success: function (data) {
          if(data.length>0)
        {

				console.log('inside success '+data);



         var applicationdate = data[0].applicationdate;
          var split = applicationdate.split('-');

          var registerdate = data[0].registerdate;
        
          var split1 = registerdate.split('-');

          $("#registerdate").val(split[2]+'-'+split[1]+'-'+split[0]);
          $('#registerdate').attr('readonly', true);

          $("#applicationdate").val(split1[2]+'-'+split1[1]+'-'+split1[0]);
          $('#applicationdate').attr('readonly', true);




          $("#applcategory").val(data[0].applcatname);
          $('#applcategory').attr('readonly', true);

					$("#subject").val(data[0].subject);
          $('#subject').attr('readonly', true);

         }
         else
          {
            swal({
            title: "This  application does not exist",

            icon: "error"
            })
            $("#conapplnRegDate").val('');
            $("#conApplEndNo").val('');
            $("#conApplStartNo").val('');

          }


}

					});
		});
});

</script>

<script>
         $(document).ready(function() {
        $('#establishment').on('change', function() {
            var fromestablishment = $(this).val();
            console.log(establishment);


            if(establishment) {
                $.ajax({
                    url: "findtoEstablishment",
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}",fromestablishment: fromestablishment},
                    dataType: "json",
                    success:function(data) {
                        console.log(data);
                      if(data){
                        $('#toestablishment').empty();
                        $('#toestablishment').focus;
                        $('#toestablishment').append('<option selected="true" disabled="disabled">-- Select Establishment Name --</option>');
                        $.each(data, function(key, value){
                          console.log(key);
                          console.log(value);
                        $('select[name="toestablishment"]').append('<option value="'+ value.establishcode +'">' + value.establishname+ '</option>');

                    });
                  }

                  else{
                    $('toestablishment').empty();
                  }
                  }
                });
            }else{
              $('toestablishment').empty();
            }
        });
    });
    </script>

</section>


@endsection
