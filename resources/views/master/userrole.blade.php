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
<br> <br>
<div class="container">

<form action="userroleSave"  id="userrole" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h4> Options allotment to User </h4> </td>
        </tr>



            </tr>

            <tr>
                <td> <span class="mandatory">*</span> <label for="applTitle">User ID </label> </td>
                <td>
                  <select class="form-control" name="userid" id="userid" data-parsley-required  data-parsley-required-message="Select User Name" >
                    <option selected="true" disabled="disabled">Select User Name</option>
                    @foreach($users as $users)
                    <option value="{{$users->userid}}">{{$users->userid}}</option>

                    @endforeach

                  </select>
                   </td>
            </tr>

        <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle">Role ID</label> </td>
            <td>
              <select class="form-control" name="roleid" id="roleid"  data-parsley-required  data-parsley-required-message="Select Role Name" >
                <option selected="true" disabled="disabled">Select Role </option>
                
                @foreach($role as $role)
                <option value="{{$role->roleid}}">{{$role->rolename}}</option>

                @endforeach

              </select>
               </td>
        </tr>





        <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle">Module Name </label> </td>
            <td>
              <select class="form-control" name="modulename" id="modulename"  >
                <option selected="true" disabled="disabled">Select Module Name</option>
                @foreach($module as $module)
                <option value="{{$module->modulecode}}">{{$module->modulename}}</option>
                @endforeach

              </select>
               </td>
        </tr>
        <tr>
            <td> <span class="mandatory">*</span> <label for="applTitle">Option Name </label></td>
            <td>
              <select class="form-control" name="optionname" id="optionname"   >
                <option selected="true" disabled="disabled">Select Option Name</option>


              </select>
               </td>
        </tr>

         <tr>
        <td colspan="4">
        <div class="text-center">
          <input type="hidden" name="sbmt_adv" id="sbmt_adv">
             <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">


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
                      <td>User ID</td>
                      <td>Role Name</td>
                      <td>Module Name</td>
                      <td>Option Name</td>
                      <td>Action</td>

                    <!--  <td>Option Name</td> -->



                    </tr>
                </thead>
                <tbody>
             <td>  </td>
             <td>  </td>
             <td>  </td>
             <td>  </td>
                </tbody>

              </table>
            </div>
            <script src="js/jquery-3.4.1.js"></script>



            <script>
        /*    $(document).ready(function() {


             //	$('#userrole').multiselect({
            	//	nonSelectedText: 'Select Role'
            //	});


            	$(".extraClick").click(function(){
            			//console.log('hiii ');
            			var  value1 = $(this).attr('data-value');

                  var result = value1.split(',');
                  var userid=result[0];
                //  console.log(userid);
                  var roleid=result[1];

                //  console.log(roleid);
            			//console.log('hiii '+userid);
            			$.ajax({
            				type: 'get',
            				url: "getuserrole",
            				dataType:"JSON",
            				data: {"_token": $('#token').val(),userid:userid,roleid:roleid},
            				success: function (json) {

            				//	console.log('inside success '+json);
            					$("#sbmt_adv").val('U');
            					$("#saveADV").val('Update');
            			    console.log('inside success '+json[0].userid);
                      $("#userid").val(json[0].userid);
            				//	$("#userid").attr('readonly', true);
                        //$('#userid').removeAttr('readonly');
                        $('#userid').attr("disabled", true);

            					$("#roleid").val(json[0].roleid);
                      $('option:selected').attr("roleid")

            					//$("#userid").attr('readonly', true);
            					$("#optionname").val(json[0].optioncode);
            					$("#modulename").val(json[0].modulecode);

            					//$("#userrole").val(json[0][0].userlevel);
            					 // $("#userrole option[value='1']").attr('selected','selected');





            						}
            					});
            		});
            });*/
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
                  var userid=result[0];
                //  console.log(userid);

                  var roleid=result[1];
                  var modulecode=result[2];
                  var optioncode=result[3];
                //  console.log(roleid);
                  //console.log('hiii '+userid);

                  $.ajax({
                    type: 'get',
                    url: "destroy_userrole",
                    dataType:"JSON",
                    data: {"_token":"{{ csrf_token() }}",userid:userid,roleid:roleid,modulecode:modulecode,optioncode:optioncode},
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
            var userid=$("#userid").val();
            console.log(userid);

            var roleid=$("#roleid").val();
            console.log(roleid);

            if(moduleid&&userid&&roleid) {
                $.ajax({
                    url: "findOptionsWithModule_userrole",
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}",moduleid: moduleid, userid: userid,roleid:roleid },
                    dataType: "json",
                    success:function(data) {
                        console.log(data);
                      if(data){
                        $('#optionname').empty();
                        $('#optionname').focus;
                        $('#optionname').append('<option selected="true" disabled="disabled">-- Select Option Name --</option>');
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

    <script>
             $(document).ready(function() {
            $('#userid').on('change', function() {

                var userid=$("#userid").val();
                console.log(userid);



                if(userid) {
                    $.ajax({
                        url: "getrole",
                        type: "GET",
                        data : {"_token":"{{ csrf_token() }}",userid: userid },
                        dataType: "json",
                        success:function(data) {
                            console.log(data);
                          if(data){
                            $('#roleid').empty();
                            $('#roleid').focus;
                          //  $('#roleid').append('<option value="'+ 0 +'">' + '----'+ '</option>');
                            $.each(data, function(key, value){
                              console.log(key);
                              console.log(value);
                            $('select[name="roleid"]').append('<option value="'+ value.roleid +'">' + value.rolename+ '</option>');

                        });
                      }


                      else{
                        $('roleid').empty();
                      }
                      }
                    });
                }else{
                  $('roleid').empty();
                }
            });
        });
        </script>


    <script> // for filtering data of table according to user
             $(document).ready(function() {
            $('#userid').on('change', function() {

                var userid=$("#userid").val();
                console.log(userid);
                if(userid) {
                    $.ajax({
                        url: '/findtablevaluesaccordingtouserid/',
                        type: "GET",
                        data : {"_token":"{{ csrf_token() }}",userid: userid },
                        dataType: "json",
                        success: function (data) {


                          console.log(data);
                          //console.log(data);
                      //    $("#myTable4 tr").remove();
                          $("#myTable4").find("tr:gt(0)").remove();


                        			//var count = 1;
                              $.each(data, function(index, obj) {

                              var row = $('<tr>');
                              //var EnrollDate =  obj.enrolleddate;
                            //	var arr =EnrollDate.split('-');
                              row.append('<td>' +obj.userid+'</td>');
                            //	row.append('<td><a href="#" data-value="'+obj.extraadvcode+'-'+obj.applicationid+'-'+obj.enrollmentno+'" class="extraClick" >' +obj.enrollmentno + '</a></td>');
                            //  row.append('<td>' + obj.rolename + '</td>');
                              row.append('<td>'+ (obj.rolename==null ? '' : obj.rolename) + '</td>');

                            //	row.append('<td>' + arr[2]+'-'+arr[1]+'-'+ arr[0]+ '</td>');
                              row.append('<td>'+ (obj.modulename==null ? '' : obj.modulename) + '</td>');
                              row.append('<td>'+ (obj.optionname==null ? '' : obj.optionname) + '</td>');
		                          row.append('<td class="col-md-2"><a href="#" id ="linkdelete" class="deleteRow btn btn-sm btn-danger"  type="button" data-value="' + obj.userid+'|'+obj.roleid +'|'+obj.modulecode+'|'+obj.optioncode+ '">X</a></td>');
                          //    return $('<td>').text(textData==null ? 'N/A' : textData);

                              row.appendTo('#myTable4');

                             });
                             $(".deleteRow").click(function() {
                               var obj = $(this); // first store $(this) in obj
  var id = $(this).data('id'); // get id of data using this
                                   var values = $(this).attr('data-value');
                                   var split = values.split('|');
                                   console.log(split);
                                   var userid=split[0];
                                   console.log("userid"+userid);
                                   var roleid = split[1];
                                   console.log("roleid"+roleid);
                                   var modulecode = split[2];
                                   console.log("modulecode"+modulecode);
                                   var optioncode = split[3];
                                   console.log("optioncode"+optioncode);

                                   $.ajax({
                                     type: 'GET',
                                     url: '/destroy_userrole',
                                     dataType: 'JSON',
                                     data: {
                                       "_token": $('#token').val(),
                                       roleid: roleid,
                                       modulecode:modulecode,
                                       optioncode:optioncode,
                                       userid:userid
                                     },

                                     success: function(response) {

                                       if (response.status == "sucess") {
                                         swal({
                                           title: 'error',
                                           icon: "error",
                                         });

                                     } else {
                                        $(obj).closest("tr").remove();
                                  //$('#myTable4').html(response);
                                     $("#userrole").trigger('reset');


                                          swal({
                                           title: 'success',
                                           icon: "success",
                                         });
                                      //   $('select[name="roleid"]').append('<option value="'+ value.roleid +'">' + value.rolename+ '</option>');



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
        $("#userid").change(function(){
  $("#myTable4 tbody tr").hide();
  $("#myTable4 tbody tr."+$(this).val()).show('fast');
});

//this JS calls the tablesorter plugin that we already use on our site
$("#myTable4").tablesorter( {sortList: [[0,0]]} );
        </script>
<script>
$(function() {
            $("#roleid").change(function() {
                if ($(this).val() != "0") {
                    $("#modulename").attr("disabled", true);
                    $("#optionname").attr("disabled", true);

                }
                else{

                    $("#modulename").attr("disabled", false);
                    $("#optionname").attr("disabled", false);
                  }
            });
        });
</script>



{{-- <script src="http://bladephp.co/download/multiselect/jquery.min.js"></script>
<link href="http://bladephp.co/download/multiselect/jquery.multiselect.css" rel="stylesheet" />
<script src="http://bladephp.co/download/multiselect/jquery.multiselect.js"></script> --}}

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css"> --}}


</script>

@endsection
