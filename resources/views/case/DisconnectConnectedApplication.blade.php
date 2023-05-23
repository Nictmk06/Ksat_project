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
    <form role="form" id="form" action="DisconnectConnectedSave"  method="post" data-parsley-validate>

      <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Disconnect Applcation</h7>
        </div>

        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
        <div class="panel panel-body">
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label>Connected Application Type<span class="mandatory">*</span></label>
                <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-required data-parsley-required-message="Select Connected Application Type" >
                  <option value="">Select Connected Applcation</option>
                 @foreach($applicationType as $applType)
                  <option value="{{$applType->appltypecode .'-'. $applType->appltypeshort}}" >{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                  @endforeach

                </select>
              </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                  <label>Connected Application No<span class="mandatory">*</span></label>
                  <div class="input-group date">
                    <input type="text" name="conApplId"  id="conApplId" class="form-control pull-right" value=""  maxlength='20'>
                    <div class="extraClick input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div>

                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Main Application Id<span class="mandatory">*</span></label></br>
                <input type="text" class="form-control pull-right" id="applicationId" name="applicationId"   value=""  >
              </div>
            </div>


          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Type <span class="mandatory" >*</span></label>
                <select name="type" id="type" class="form-control" style="pointer-events: none;" >
                <option value="" >Select  Type</option>
               <option value="C/W">Connected With</option>
               <option value="A/W">Along With</option>
               </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Bench<span class="mandatory">*</span></label>
              <select name="bench" id="bench" class="form-control"  style="pointer-events: none;">
                <option value="" >Select Bench</option>
                @foreach($benchcode as $benchtype)
                <option value= "{{$benchtype->benchcode}}">{{$benchtype->judgeshortname}}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Bench Type<span class="mandatory">*</span></label>
            <select name="benchtype" id="benchtype" class="form-control"  style="pointer-events: none;">
            <option value="" >Select Bench Type</option>
            @foreach($Benches as $Benches)
            <option value= "{{$Benches->benchtypename}}">{{$Benches->benchtypename}}</option>
            @endforeach
          </select>
        </div>
      </div>
          </div>
          <div class="row">

          <div class="col-md-4">
            <label>Hearing Date<span class="mandatory">*</span></label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="hearingDate" class="form-control pull-right datepicker "  style="pointer-events: none;" id="hearingDate" value=""   data-parsley-errors-container="#error6" >
            </div>
            <span id="error6"></span>
          </div>

        <!-- /.col -->
        <div class="col-md-4">
          <div class="form-group">
            <label>Order No <span class="mandatory">*</span></label>
            <input class="form-control" name="orderNo" id="orderNo" type="text" value=""  >
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Order Date<span class="mandatory" >*</span></label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="orderDate" class="form-control pull-right datepicker" id="orderDate" style="pointer-events: none;" value=""  >
            </div>
            <span id="error7"></span>
          </div>
        </div>

        <input type="hidden" name="applicationyear" id="applicationyear" value="">

      </div>
      <div class="row">
        <div class="col-md-12" >
          <div class="form-group">
            <label>Court Order/Direction<span class="mandatory">*</span></label>
            <textarea class="form-control" name="direction" id="direction" ></textarea>
          </div>
        </div>
      </div>

  </div>
</div>





    <div class="panel  panel-primary">
      <div class="panel panel-heading">
        <h7 >Disconnection details</h7>
      </div>


     <div class="row">
       <div class="col-md-4">
         <div class="form-group">
           <label>Disconnect Bench Type<span class="mandatory">*</span></label>
           <select name="dbenchtype" id="dbenchtype" class="form-control" data-parsley-required data-parsley-required-message="Select Disconnect Bench Type"  >
            <option value="" >Select Disconnect  Bench Type</option>

            <option value="Single Bench">Single Bench</option>
           <option value="Division Bench">Division Bench </option>
           <option value="Full Bench">Full Bench </option>

         </select>
       </div>
     </div>
  
     <div class="col-md-4">
       <div class="form-group">
         <label> Disconnect  Bench<span class="mandatory">*</span></label>
         <select name="dbench" id="dbench" class="form-control" data-parsley-required data-parsley-required-message="Select Disconnect Bench" ">
		  <option value=''>Select Bench</option>
          @foreach($benchcode as $benchtype)
          <option value= "{{$benchtype->benchcode}}">{{$benchtype->judgeshortname}}</option>
          @endforeach

       </select>
     </div>
   </div>
   <div class="col-md-4">
     <label>Disconnect  Hearing Date<span class="mandatory">*</span></label>
     <div class="input-group date">
       <div class="input-group-addon">
         <i class="fa fa-calendar"></i>
       </div>
       <input type="text" name="dhearingDate" class="form-control pull-right datepicker" id="dhearingDate" value=""   data-parsley-errors-container="#error6"   >
     </div>
     <span id="error6"></span>
   </div>

    </div>






      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label>Disconnect Order No<span class="mandatory">*</span></label>
            <input class="form-control" name="dorderNo" id="dorderNo" type="text" value="" daa>
          </div>
        </div>

      <div class="col-md-4">
        <div class="form-group">
          <label>Disconnect Order Date<span class="mandatory">*</span></label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" name="dorderDate" class="form-control pull-right datepicker" id="dorderDate" value="" >
          </div>
          <span id="error7"></span>
        </div>
      </div>




   </div>
 <div class="row">

   <div class="col-md-12" >
     <div class="form-group">
       <label>Reason For Disconnection<span class="mandatory">*</span></label>
       <textarea class="form-control" name="ddirection" id="ddirection"></textarea>
     </div>
   </div>
</div>

     <div class="row"  style="float: right;" id="add_apl_div">
       <div class="col-sm-12 text-center">
         <input type="hidden" name="sbmt_disconnected" id="sbmt_disconnected" value="A">
         <input type="submit" id="saveDisConnectedCase" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">
         <a class="btn btn-warning" href=""> Cancel </a>
       </div>
     </div>
     <br><br><br>

 </div>
</form>

<div class="row">
  <div class='panel panel-body divstyle'   style="width: 1600px; height: 500px; overflow: auto;">

     <div class="col-md-8" >
  		<div class="box box-info" >
              <div class="box-header with-border">
                <h6 class="box-title">Disconnect Application List</h6>

              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive" >
                <table class="table no-margin tableBodyScroll">
                 <thead >
                   <tr style="background-color: #3c8dbc;color:#fff" >

                     <td>Conection Applid</td>
                     <td >Disconnect Reason</td>
                     <td>Disconnect Hearingdate </td>

                     <td>Disconnect Orderdate </td>
                     <td>Disconnect Orderno</td>
                     <td>Disconnect Benchname</td>

                   </tr>
               </thead>

               <tbody>
                   <tr>

                   <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                     <?php $i = 1; ?>
                   @foreach($disconnected as $disconnected)

                 <tr>

                   <td> <a href="#" id="userclick{{$i}}" data-value="{{$disconnected->conapplid}}" class="extraClick1"> {{$disconnected->conapplid}}   </a></td>


                 <td >{{$disconnected->dreason}}</td>
                 <td>{{date('d/m/Y', strtotime($disconnected->dhearingdate)) }}</td>


                 <td>{{ date('d/m/Y', strtotime($disconnected->dorderdate )) }}</td>

                 <td>{{$disconnected->dorderno}}</td>
                 <td>{{$disconnected->dbenchtypename}}</td>

                 </tr>
                @endforeach

               </tbody>
             </table>
             <br>
             <table id="scrolltable"  border="1px solid blue" class="table tableBodyScroll1 ">
              <thead>
              </thead>

              <tbody>

              </tbody>
            </table>
           </div>
         </div>
      </div>
    </div>
  </div>
</div>
<script src="js/jquery.min.js"></script>

<script>
$(document).ready(function() {

/*var hearingdatevar = $("#hearingDate").val();
console.log('hear'+hearingdatevar);
    $("#hearingDate").val(hearingdatevar);
  $("#hearingDate").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
  }).on('changeDate', function(selected) {
    var endDate = new Date(selected.date.valueOf());
    $('#dhearingDate').datepicker('setStartDate', endDate);
  }).on('clearDate', function(selected) {
    $('#dhearingDate').datepicker('setEndDate', null);
  });
 */

	$("#dbenchtype").change(function() {
   		 var text = $(this).val();
   		 
   		$.ajax({
				type: 'post',
				url: "getBenchJudges",
				dataType:"JSON",
				data: {"_token": $('#token').val(),benchtype:text,display:'Y'},
				success: function (json) {
					$('#dbench').find('option:not(:first)').remove();
					 for(var i=0;i<json.length;i++){
					 	 var option = '<option value="'+json[i].benchcode+'">'+json[i].judgeshortname+'</option>';
	  						$('#dbench').append(option);
					 }
				}
			});
   		
  	});

  $("#dhearingDate").datepicker({
              format: 'dd-mm-yyyy',
              autoclose: true,
            }).on('changeDate', function(selected) {
              var endDate = new Date(selected.date.valueOf());
              $('#dorderDate').datepicker('setStartDate', endDate);
            }).on('clearDate', function(selected) {
              $('#dorderDate').datepicker('setEndDate', null);
            });

 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick").click(function(){
			//console.log('hiii ');
			/*var id  = $('#conApplId').val();
      console.log('hiii '+id );
      var applicationtype=$('#applTypeName').val() +'/';
      console.log('hii'+ applicationtype);
  */

  var modl_appltype_name = $("#applTypeName").val();
	var newtype = modl_appltype_name.split('-');
	var applnewtype = newtype[1];
	var modl_modl_applno = $("#conApplId").val();
	var connectionid =applnewtype+'/'+modl_modl_applno;

			$.ajax({
				type: 'get',
				url: "getConnectedAppDetails/{connectionid}",
				dataType:"JSON",
				data: {"_token": $('#token').val(),connectionid : connectionid },
				success: function (data) {
          if(data.length>0)
        {

				console.log('inside success '+data);
					$("#sbmt_disconnected").val('A');
			//		$("#saveDisConnectedCase").val('Update');
			    console.log('inside success '+data[0].conapplid);
          $("#applicationId").val(data[0].applicationid);
          $('#applicationId').attr('readonly', true);

          $("#type").val(data[0].type);
          $('#type').attr('readonly', true);

          $("#applicationyear").val(data[0].applicationyear);

          //$("#applTypeName").val(data[0].appltypecode);
          //$("#type").val(data[0].type);

        //	$("#type").attr('readonly',true);
					$("#bench").val(data[0].benchcode);
					//$("#userid").attr('readonly', true);
          $('#bench').attr('readonly', true);

          $("#benchtype").val(data[0].benchtypename);
          $('#benchtype').attr('readonly', true);

          var hrdate = data[0].hearingdate;
          var split = hrdate.split('-');
          var orderdate = data[0].orderdate;
          console.log(orderdate);
          var split1 = orderdate.split('-');
          $("#hearingDate").val(split[2]+'-'+split[1]+'-'+split[0]);
          $('#hearingDate').attr('readonly', true);

          $("#orderDate").val(split1[2]+'-'+split1[1]+'-'+split1[0]);
          $('#orderDate').attr('readonly', true);


          /*$("#hearingDate").val(data[0].hearingdate);
          $('#hearingDate').attr('readonly', true);
       */
          $("#orderNo").val(data[0].orderno);
          $('#orderNo').attr('readonly', true);
     /*
  				$("#orderDate").val(data[0].orderdate);
          $('#orderDate').attr('readonly', true);
      */
					$("#direction").val(data[0].reason);
          $('#direction').attr('readonly', true);


				//	$("#mobilenumber").val(json[0].deptmobile);

					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');

//console.log('inside success '+data[1]);
					// $("#department option:selected").removeAttr("selected");






         }
         else
          {
            swal({
            title: "This connected application does not exist or already disconnected",

            icon: "error"
            })
            $("#conapplnRegDate").val('');
            $("#conApplEndNo").val('');
            $("#conApplStartNo").val('');
            //$("#conapplTypeName").val('');

          }


}

					});
		});
});

</script>

<script>
$(document).ready(function() {


 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick1").click(function(){
			//console.log('hiii ');
			/*var id  = $('#conApplId').val();
      console.log('hiii '+id );
      var applicationtype=$('#applTypeName').val() +'/';
      console.log('hii'+ applicationtype);
  */

/*  var modl_appltype_name = $("#applTypeName").val();
	var newtype = modl_appltype_name.split('-');
	var applnewtype = newtype[1];
	var modl_modl_applno = $("#conApplId").val();
	var connectionid =applnewtype+'/'+modl_modl_applno;
*/

  var connectionid = $(this).attr('data-value');

  console.log(connectionid);
    	$.ajax({
				type: 'get',
				url: "getDisConnectedAppDetails/{connectionid}",
				dataType:"JSON",
				data: {"_token": $('#token').val(),connectionid : connectionid },
				success: function (data) {
          if(data.length>0)
        {

				console.log('inside success '+data);
					$("#sbmt_disconnected").val('E');
					$("#saveDisConnectedCase").val('Update');
			    console.log('inside success '+data[0].conapplid);
          $("#applicationId").val(data[0].applicationid);
          $('#applicationId').attr('readonly', true);

          $("#type").val(data[0].type);
          $('#type').attr('readonly', true);

          $("#applicationyear").val(data[0].applicationyear);

          //$("#applTypeName").val(data[0].appltypecode);
          //$("#type").val(data[0].type);

        //	$("#type").attr('readonly',true);
      //  $("#applTypeName").val(data[0].appltypecode);
         //var word = data[0].conapplid;
      //  var data=
      //  var str=String(data[0].conapplid).substr(0,1);

         $("#applTypeName").val(data[0].appltypecode+'-'+String(data[0].conapplid).substr(0,2))

          $("#bench").val(data[0].benchcode);
					//$("#userid").attr('readonly', true);
          $('#bench').attr('readonly', true);

          $("#benchtype").val(data[0].benchtypename);
          $('#benchtype').attr('readonly', true);

          var hrdate = data[0].hearingdate;
          var split = hrdate.split('-');
          var orderdate = data[0].orderdate;
          var split1 = orderdate.split('-');
          $("#hearingDate").val(split[2]+'-'+split[1]+'-'+split[0]);
          $('#hearingDate').attr('readonly', true);

          $("#orderDate").val(split1[2]+'-'+split1[1]+'-'+split1[0]);
          $('#orderDate').attr('readonly', true);


        /*	$("#hearingDate").val(data[0].hearingdate);
          $('#hearingDate').attr('readonly', true);
*/
          $("#orderNo").val(data[0].orderno);
          $('#orderNo').attr('readonly', true);

  				/*$("#orderDate").val(data[0].orderdate);
          $('#orderDate').attr('readonly', true);
*/
					$("#direction").val(data[0].reason);
          $('#direction').attr('readonly', true);

          $("#conApplId").val(data[0].conapplid);
          $('#conApplId').attr('readonly', true);



         $("#dbench").val(data[0].dbenchcode);
         $("#dbenchtype").val(data[0].dbenchtypename);

         var dhrdate1 = data[0].dhearingdate;
         var split2 = dhrdate1.split('-');
         var dorderdate1 = data[0].dorderdate;
         var split3 = dorderdate1.split('-');
         $("#dhearingDate").val(split2[2]+'-'+split2[1]+'-'+split2[0]);

         $("#dorderDate").val(split3[2]+'-'+split3[1]+'-'+split3[0]);


      //   $("#dhearingDate").val(data[0].dhearingdate);
      //   date('Y-m-d',strtotime($request->get($("#dhearingDate").val(data[0].dhearingdate))));
         //date('d-m-Y', strtotime($user->from_date));
        //$("#dhearingDate").val(data[0].dhearingdate);



         $("#dorderNo").val(data[0].dorderno);
        // $("#dorderDate").val(data[0].dorderdate);
         $("#ddirection").val(data[0].dreason);










				//	$("#mobilenumber").val(json[0].deptmobile);

					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');

//console.log('inside success '+data[1]);
					// $("#department option:selected").removeAttr("selected");

         }
         else
          {
            swal({
            title: "This connected application does not exist or already disconnected",

            icon: "error"
            })
            $("#conapplnRegDate").val('');
            $("#conApplEndNo").val('');
            $("#conApplStartNo").val('');
            //$("#conapplTypeName").val('');

          }

}

					});
		});
});

</script>

</section>


@endsection
