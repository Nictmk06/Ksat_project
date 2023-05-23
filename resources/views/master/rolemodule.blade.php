@extends('layout.mainlayout')
@section('content')
<head>

</head>
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

<?php
 $purposecode=DB::select("select max(purposecode) as purposecode from listpurpose")[0];
$prposecode=$purposecode->purposecode+1;
$userSave['purposecode']= $prposecode;
?>
@include('flash-message')
<br> <br>
<div class="container">

<form name='rolemodulesave' id='rolemodulesave' action="rolomoduleSave" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Options allotment to Role </h4> </td>
        </tr>



            </tr>



        <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle">Role name</label> </td>
            <td>
               <div class="input-group date">
              <select class="form-control" name="roleid" id="roleid" data-parsley-required  data-parsley-required-message="Select Role Name" >
                <option selected="true" disabled="disabled">Select Role </option>
                @foreach($role as $role)
                <option value="{{$role->roleid}}">{{$role->rolename}}</option>
                @endforeach

              </select>
              <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="addAppRes" data-toggle="modal" data-target="#myModalf1" >
                <i class="fa fa-plus"></i>
              </div>
              <span id="modlerror2"></span>
              {{--  --}}
            </div>
               </td>

        </tr>





        <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle">Module Name </label> </td>
            <td>
              <select class="form-control" name="modulename" id="modulename" data-parsley-required  data-parsley-required-message="Select Module Name" >
            <!--    <option selected="true" disabled="disabled">Select Module Name</option>-->
            <option selected="true" disabled="disabled">Select Module </option>
                @foreach($module as $module)
                <option value="{{$module->modulecode}}">{{$module->modulename}}</option>
                @endforeach

              </select>
               </td>
        </tr>
        <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle">Option Name </label></td>
            <td>
              <select class="form-control" name="optionname" id="optionname"  data-parsley-required  data-parsley-required-message="Select Option Name" >
                <option selected="true" disabled="disabled">Select Option Name</option>



              </select>
               </td>
        </tr>

        <tr>
       <td colspan="4">
       <div class="text-center">
         <input type="hidden" name="sbmt_adv" id="sbmt_adv">
            <button onclick="form_submit()" name='save' value='save' class="btn btn-danger"> Save</button>
            <script type="text/javascript">
              function form_submit() {
                document.getElementById("rolemodulesave").submit();
               }
              </script>

              <a class="btn btn-warning" href=""> Cancel </a>
       </div>

       </td>
       </tr>




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
  </table>
</div>
</div>

 <div class="row">
                <table id="myTable4" class="table order-list" style="width:90%;" >
                  <thead >
                    <tr style="background-color: #3c8dbc;color:#fff" >

                      <td>Role Name</td>
                      <td>Module Name</td>
                      <td>Option Name</td>
                      <td>Action</td>

                    </tr>
                </thead>

               <tbody>
                    <tr>

                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>


                  <tr>

                    <td>  </td>



                  <td></td>
                  <td></td>
                  <td> </td>




                  </tr>

               </tbody>
              </table>
            </div>



<div class="modal fade" id="myModalf1" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title" style="text-align: center;">Add  Role</h4>
      </div>
     <div class="modal-body">

        <form action="AddRoleSave" method="POST" data-parsley-validate>
        @csrf
        <div class="row">

        <div class="col-md-4">
          <div class="form-group">
            <label>Role id<span class="mandatory">*</span></label>
            <input type="numeric" class="form-control pull-right" id="rolecode" name="rolecode"
            value="<?php $roleid=DB::select("SELECT max(CAST(roleid as INT))as roleid from role")[0];
                      $roleid=$roleid->roleid+1;
                      $userSave['$roleid']= $roleid;
                      echo   $userSave['$roleid'] ?>"  readonly='readonly' >
          </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
              <label>Role Name<span class="mandatory">*</span></label>
              <input type="text" class="form-control pull-right" id="rolename" name="rolename"  value=""   data-parsley-required  data-parsley-required-message="Enter role name">
           </div>
      </div>
      <div class="row"><br>
      <div class="text-center">

           <input type="submit" id="save" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="save">


      </div>
</div>

    </div>

</form>

  </div>
</div>
</div>
</div>

<script src="js/jquery-3.4.1.js"></script>

<script>
/*$(document).ready(function() {


 //	$('#userrole').multiselect({
	//	nonSelectedText: 'Select Role'
//	});


	$(".extraClick").click(function(){
			//console.log('hiii ');
			var userid   = $(this).attr('data-value');
			console.log('hiii '+userid);
			$.ajax({
				type: 'get',
				url: "/getuserrole",
				dataType:"JSON",
				data: {"_token": $('#token').val(),userid:userid},
				success: function (json) {

				//	console.log('inside success '+json);
					$("#sbmt_adv").val('U');
					$("#saveADV").val('Update');
			    console.log('inside success '+json[0].userid);
          $("#userid").val(json[0].userid);
					//$("#purposecode").attr('readonly', true);
					$("#roleid").val(json[0].roleid);
					//$("#userid").attr('readonly', true);
					$("#modulename").val(json[0].modulecode);
          $("#optionname").val(json[0].optioncode);


					//$("#userrole").val(json[0][0].userlevel);
					 // $("#userrole option[value='1']").attr('selected','selected');


				//	 $("#department option:selected").removeAttr("selected");




						}
					});
		});
}); /*
</script>
<script>//for delete
$(document).ready(function() {


 //	$('#userrole').multiselect({
  //	nonSelectedText: 'Select Role'
//	});


  $(".extraClick1").click(function(){
      //console.log('hiii ');
      var  value1 = $(this).attr('data-value1');

      var result = value1.split(',');
      var roleid=result[0];
    //  console.log(userid);

      var modulecode=result[1];
      var optioncode=result[2];

    //  console.log(roleid);
      //console.log('hiii '+userid);

      $.ajax({
        type: 'get',
        url: "destroy_rolemodule",
        dataType:"JSON",
        data: {"_token":"{{ csrf_token() }}",roleid:roleid,modulecode:modulecode,optioncode:optioncode},
        success: function (json) {

          window.location.reload();






            }
          });
    });
});
</script>
<script>
         $(document).ready(function() {
        $('#modulename').on('change', function() {
            var moduleid = $(this).val();
            console.log(moduleid);
            var roleid=$("#roleid").val();
            console.log(roleid);
            if(moduleid&&roleid) {
                $.ajax({
                    url: "findOptionsWithModule_rolemodule",
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}",roleid:roleid,moduleid:moduleid},
                    dataType: "json",
                    success:function(data) {
                        console.log(data);
                      if(data){
                        $('#optionname').empty();
                        $('#optionname').focus;
                        $('#optionname').append('<option selected="true" disabled="disabled">-- Select Option Name --</option>');

                      //  $('#optionname').append('<option value="'+ 0 +'">' + '----' + '</option>');

                        $.each(data, function(key, value){
                          console.log(key);
                          console.log(value);
                        $('select[name="optionname"]').append('<option value="'+ value.optioncode +'">' + value.optionname+ '</option>');

                    });
                  }

                  else{
                    $('optionname').empty();
                  }
                  }
                });
            }else{
              $('optionname').empty();
            }
        });
    });
    </script>

    <script> // for filtering data of table according to user
             $(document).ready(function() {
            $('#roleid').on('change', function() {

                var roleid=$("#roleid").val();
                console.log(roleid);
                if(roleid) {
                    $.ajax({
                        url: '/findtablevaluesaccordingtoroleid/',
                        type: "GET",
                        data : {"_token":"{{ csrf_token() }}",roleid: roleid },
                        dataType: "json",
                        success: function (data) {


                          console.log(data);
                          //console.log(data);
                      //    $("#myTable4 tr").remove();
                          $("#myTable4").find("tr:gt(0)").remove();
                          //$("#myTable4").find('tbody tr').remove();


                        			//var count = 1;
                              $.each(data, function(index, obj) {

                              var row = $('<tr>');
                              //var EnrollDate =  obj.enrolleddate;
                            //	var arr =EnrollDate.split('-');
                            row.append('<td>' +obj.rolename+'</td>');
                            //	row.append('<td><a href="#" data-value="'+obj.extraadvcode+'-'+obj.applicationid+'-'+obj.enrollmentno+'" class="extraClick" >' +obj.enrollmentno + '</a></td>');
                            row.append('<td>' + obj.modulename + '</td>');
                            //	row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
                            row.append('<td>'+ obj.optionname + '</td>');
                        		row.append('<td class="col-md-2"><a href="#" id ="linkdelete" class="deleteRow btn btn-sm btn-danger"  type="button" data-value="' + obj.roleid+'|'+obj.modulecode +'|'+obj.optioncode+ '">X</a></td>');
                            row.appendTo('#myTable4');

                             });
                             $(".deleteRow").click(function() {
                               var obj = $(this); // first store $(this) in obj
  var id = $(this).data('id'); // get id of data using this
                                   var values = $(this).attr('data-value');
                                   var split = values.split('|');
                                   console.log(split);
                                   var roleid = split[0];
                                   var modulecode = split[1];
                                   var optioncode = split[2];

                                   $.ajax({
                                     type: 'GET',
                                     url: '/destroy_rolemodule',
                                     dataType: 'JSON',
                                     data: {
                                       "_token": $('#token').val(),
                                       roleid: roleid,
                                       modulecode:modulecode,
                                       optioncode:optioncode
                                     },

                                     success: function(response) {

                                       if (response.status == "sucess") {
                                         swal({
                                           title: 'error',
                                           icon: "error",
                                         });

                                     } else {
                                        $(obj).closest("tr").remove();
                                        $("#rolemodulesave").trigger('reset');

                                  //$('#myTable4').html(response);
                                          swal({
                                           title: 'success',
                                           icon: "success",
                                         });


                                       }

                                     }

                                   });
                                 });

                        }
                    });
                }
            });
        });

        </script>
        <script>

//$("#optionname option[value='0']").remove();

        </script>


{{-- <script src="http://bladephp.co/download/multiselect/jquery.min.js"></script>
<link href="http://bladephp.co/download/multiselect/jquery.multiselect.css" rel="stylesheet" />
<script src="http://bladephp.co/download/multiselect/jquery.multiselect.js"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> --}}


</script>


@endsection
