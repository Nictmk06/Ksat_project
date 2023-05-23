
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

  div.ex1 {
  color: black;
  font-size:14px;
  background-color: #eeeeee ;
  height: 30px;
  width: 1000px;
  overflow-y: scroll;
}

div.ex2 {
  color: black;
  font-size:14px;
  background-color: white;
  height: 30px;
  width: 1000px;
  overflow-y: scroll;
}

div.ex3 {
  background-color: lightblue;
  height: 40px;
  width: 200px;
  overflow-y: auto;
}

div.ex4 {
  background-color: lightblue;
  height: 40px;
  width: 200px;
  overflow-y: visible;
}
.highlight {
   font-size:14px;
   color:darkred;
  	background:yellow;
  }

  button.btn1 {
  background-color: green; /* Green */
  border: 2px;
  border-radius: 8px;
  color: white;
  padding: 6px 14px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 20px;
  font-weight: bold;
}
button.btn2 {
  background-color:orange; /* Green */
  border: 2px;
  border-radius: 8px;
  color: white;
  padding: 6px 14px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 20px;
  font-weight: bold;
}
button.btn3 {
  background-color:red; /* Green */
  border: 2px;
  border-radius: 8px;
  color: white;
  padding: 6px 14px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  font-weight: bold;
}

  </style>



  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>




<div class="container">


<!--<form action="ChProceedingUpdate" method="POST" data-parsley-validate>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" name="hearingCode" id="hearingCode" value="">
     <input type="hidden" name="courthallno" id="courthallno" value="{{$courthall}}">
      <input type="hidden" name="benchcode" id="benchcode" value=" {{$benchdetail[0]->benchcode}}">
	   <input type="hidden" name="hearingDate" id="hearingDate" value=" {{date('d-m-Y')}}">-->
     <input type="hidden" name="startDt" id="startDt" value="{{$getminheardt[0]->dt}}">
	  
@csrf
<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered" style="font-size:14px;">

        <tr>
        <td  class="bg-primary text-center" colspan="6"> <h4> Court Hall Proceedings Updation </h4> </td>
        </tr>

        <?php
         $today_sys_dt = date("d-m-Y");
        ?>

        <tr>
        <td colspan="6">

        <table width="99%">
        <tr>
		<td style:width="40%"> 
      <div class="form-group">
                <label>Hearing Date <span class="mandatory">*</span></label>
                      <div class="input-group date"  >
                        <div class="input-group-addon" >
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="hearingDate1" class="datepicker" id="hearingDate1"  value="{{$herdate}}"  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Hearing Allows only digits"
            data-parsley-required  data-parsley-required-message="Enter Hearing Date"  data-parsley-errors-container="#error6" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY" autocomplete="off" >
                      </div>
                      <span id="error6"></span>

              </div>
		</td>
		<td style:width="40%">    <label> Select Bench:</label>
    <span> <span class="mandatory">*</span>
<select class="form-control" style="width:350px" name="dd_courthall" id="dd_courthall" required data-parsley-required-message="Select  bench No" style="height:34px" data-parsley-trigger='focus'>
@if(!empty($benchvalue))
<option value="{{$benchvalue}}" > {{$benchname}} </option> 
@endif
           <option value="" > Bench No </option> </select>
                           </span>	
          
		</td>
   
  </tr>
  </table>

        </td>
        </tr>
 <tr >
   
<table   class="table no-margin table-bordered table-responsive" style="font-size:14px;  border:1px solid black; ">

<tbody id="mytable">
  

 <tr id="thead" style="display :none; ">
            <th>S.No</th>
            <th>Application</th>
            <th>Court direction</th>
            <th>Case remaks</th>
            <th>Office note</th>
            <th>Order Passed</th>
            <th>Final case stage</th>

 </tr>

 
</tbody>


</table>
  </tr> 
 
   
        <tr>
        <td colspan="4">
        <div class="text-center">
  
               <a class="btn btn-warning" href="dashboardmain"> Close </a>
        </div>


 

        </td>

        </tr>
    </table>
 
    </div>
    </div>
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

</div>  {{-- class contaner --}}



<div class="modal" tabindex="-1" role="dialog" id="editpopup">
  <div class="modal-dialog" role="document">
    <form  action="saveProceedingCP"  method="POST"
  data-parsley-validate>
    <div class="modal-content" id="mdcontent">
      <div class="modal-header">
        <h3 class="modal-title">Daily hearing updation</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
 <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
      <input type="hidden" id="appId" name="appId"value="">
      <input type="hidden" id="hdate"  name="hdate"value="">
       <input type="hidden" id="cno"  name="cno"value="">
        <input type="hidden" id="bno"  name="bno"value="">
         <input type="hidden" id="lno" name="lno" value="">

               <lable style="color: black;"><b>Court direction</b></lable>
        <textarea class="form-control" name="courtDirection" id="courtDirection" rows="3" cols="50" required> </textarea> <br/>
       
            <lable style="color: black;"><b>Case remarks</b></lable>
       <textarea class="form-control" name="remarksIfAny" id="remarksIfAny" rows="3" cols="60" required> </textarea><br/>
            <lable style="color: black;"><b>Office Note</b></lable>
      <textarea class="form-control" name="officeNote" id="officeNote" rows="3" cols="50" required> </textarea><br/>
      <div class="row">
        <div class="col-md-6">  <lable style="color: black;"><b>Order Passed</b></lable>
        <select class="form-control" name="orderPassed" id="orderPassed" required data-parsley-required-message="Order Passed" style="height:34px" data-parsley-trigger='focus'>
           <option value="">Select Order Passed </option>

           </select></div>
            <div class="col-md-6"><lable style="color: black;"><b>Case Status</b></lable>
      <select class="form-control" name="caseStatus" id="caseStatus" required data-parsley-required-message="case staus" style="height:34px" data-parsley-trigger='focus' disabled="true">
           <option value="">Select casestaus </option>
           <option value="1">Pending</option>
        <option value="2">Disposed </option>

           </select></div>
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

<script src="js/chp/chp.js"></script>

<script src="js/chp/chpProcUpdate.js"></script>
    
<script>
 function callmodal(appid)
 {
  //alert(appid);
  //alert($('#hearingDate1').val());
var formData = new FormData();
formData.append('hearingDate1', $('#hearingDate1').val());
   // formData.append('courthallno', $("#dd_courthall option:selected").text());
   // formData.append('benchno', $("#dd_bench option:selected").val());
   // formData.append('list_no', $("#listno option:selected").text());
    formData.append('applicationID',appid);
    //alert(list_no);// hidden values assigned in varibale for form
   $('#appId').val(appid);
    $('#hdate').val($('#hearingDate1').val());
    $('#cno').val($("#dd_courthall option:selected").text());
     $('#bno').val($("#dd_courthall").val().split(',')[0]);
     $('#lno').val($("#dd_courthall").val().split(',')[1]);
    // alert($appID);
     //alert($hdate);
     //alert('cno');
     //alert( $('#bno').val());
     //alert( $('#lno').val());

//ajax call for orde type describtion
  $.ajax({ 
              type:'POST',
                url: "getordertype",
                data: formData,
        dataType: "JSON",
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
         // alert('hi');
        console.log(data);
        for(i=0;i<=data.length;i++){ 
              
          $('select[name="orderPassed"]').append("<option value=\""+data[i].ordertypecode+"\">"+data[i].ordertypedesc+"</option>");
        
          }
       
       } ,
   error: function(response){
 console.log(response);
}
});
// ajax call for order type describtion


/// ajax call strat for modal filling with selected appid
 $.ajax({
              type:'POST',
                url: "getapphearing",
                data: formData,
        dataType: "JSON",
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
         // alert();
        console.log(data);
         //$('select[name="caseStaus"]').empty();
        for(i=0;i<=data.length;i++){ 
    // alert(data[i].ordertypecode);
     //   alert(data[i].ordertypedesc);
        $('#courtDirection').val(data[i].courtdirection);
         $('#remarksIfAny').val(data[i].caseremarks);
         
         $('#officeNote').val(data[i].officenote);
        // $('select[name="orderPassed"]').append("<option value=\""+data[i].ordertypecode+"\" selected>"+data[i].ordertypedesc+"</option>");
         //$('select[name="orderPassed"]').selected(data[i].ordertypedesc);
         $('#orderPassed  option[value='+data[i].ordertypecode+']').attr('selected','selected');
          //$('select[name="caseStatus"]').append("<option value=\""+data[i].casestatus+"\" selected>"+data[i].status+"</option>");
          //$('#cs').val=data[i].status;
          $('#caseStatus  option[value='+data[i].casestatus+']').attr('selected','selected');
          //  $('select[name="caseStatus"]').disabled("true");
       
        }
       //  alert(console.log(data));
        //alert(data[0]);
         //console.log(data);
       } ,
   error: function(response){
 console.log(response);
}
});
 //ajax call for filling modal with appid end here

} ///function callmodal end here

 
 
</script>


@endsection
