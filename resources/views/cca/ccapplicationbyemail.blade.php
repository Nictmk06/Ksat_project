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
  .divstyle
  {
  padding-top: 0px;
  padding-bottom: 0px;
  }
  
  .text{
  white-space: pre-wrap;
  }


    table,tr,th,td{
    border-color: black;
     border:1px solid black;
      
        line-height: 35px;
    font-weight: bold;

    }
 
  </style>
  
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')
	<section class="content">
		
	

		
	
				
	
   <!-- <form role="form" id="ccApplicationForm" method="POST" action="
    {{ URL::route('saveCCApplication',[],false) }}" data-parsley-validate>-->

    	<form role="form" id="ccabyemail" action=""  method="POST"
	data-parsley-validate>
			  @csrf
			<div class="panel  panel-primary">
			  <div class="panel panel-heading">
				<h7 >CC by Email</h7>
			  </div>
			
			<div class="panel panel-body divstyle" >

					 <table id="myTable4" class="table border border-dark order-list" style="width:100%;" >
						<thead>
							<tr style="background-color: #3c8dbc;color:#fff">
                             <th>Sr.No.</th>
                             <th> Application Number</th>
                             <th>CC Number</th>
                             <th>Date of Applcation No</th>
                             <th>Mobileno</th>
                             <th> Email address	</th>
                              <th>Download digtally signed judement/Generate facsheet</th>
                              <th> Send mail </th>
							</tr>
						</thead>
                       <tbody>

                      @foreach($ccadetails as $cca)

		    <tr>
		      		        <!--<td> <a href="">{{ $sub->user->user_name }} </a> </td>-->
                       
		        <td> {{ $loop->iteration }} </td>
		        <td> {{  $cca->applicationid}}  </a></td>
		        <td>{{ $cca->ccaapplicationno}}</td>
		        <td>{{ $cca->caapplicationdate }}</td>
		        <td>{{ $cca->mobile }}<br/>
		        <td>{{ $cca->email }}</td>
		       <td> 
        <a href="{{ URL::route('facesheet',['applicationId'=> $cca->applicationid] ,false) }}"
                      id="judgement" name ="judgement" class="btn btn-primary"><i>View Judgement</i></a>
                    

                <!--   <a href="{{ URL::route('facesheet',['applicationId'=> $cca->applicationid,'judgement'=>'facing sheet'] ,false) }}"
                      id="facesheet" name ="judgement" class="btn btn-primary"><i>facing sheet</i></a> -->


<button class="btn btn-primary" type="button" id="facesheetid{{$loop->iteration}}"  value="<?php echo $cca->applicationid.'-'.$cca->ccaapplicationno;?>" onClick="dsc_facesheet(this.value,this.id)">facing Sheet</button>


<!--<button class="btn btn-primary" id ="facing1" type="button"data-value="{{$cca->applicationid}}" class="extraClick"> facesheet1</button> -->  
<b id="dwnsheetfacesheetid{{$loop->iteration}}"></b>
		  </td>
		        <td>
            
@if($cca->email!=null)
<button class="btn btn-primary" type="button" id="mailfacesheetid{{$loop->iteration}}"  value="<?php  echo $cca->applicationid.'-'.$cca->ccaapplicationno;  ?>" onClick="mail_dsc_send(this.value,this.id)" >Send</button>
@endif</td>
		    </tr>

    @endforeach
                  </tbody>
					</table>



<div class="row">
    <div class="col-sm-4">
      <div class="well-sm">
        <form id="pdfForm">
          
        <input type="hidden" name="selectedid" id="selectedid" >
          <input type="hidden" name="selectedbtn" id="selectedbtn" >
        
          <input type="hidden" id="signingReason"
            name="signingReason" value="Online Certified Copy" maxlength="20" /> <br />
            <input
            type="hidden" id="signingLocation" name="signingLocation" value="KSAT,Bengaluru"  maxlength="20" /> <br />
             <input type="hidden"
            id="stampingX" name="stampingX" maxlength="20" value="400" /> <br />
             <input type="hidden" id="stampingY" name="stampingY" maxlength="20"
            value="90" /> <br /> 
             <input type="hidden" id="scale"
            name="scale" maxlength="20" value="0.5f" /> <br />
              <select name="tsaurls" id="tsaurls" onchange="myFunction()" style="display: none; ">
            <option value="0">--------------------------SELECT---------------------------------</option>
            
          </select> <br /> 
          <input type="hidden" id="tsaURL"
            name="tsaURL" value="" maxlength="100" style="width: 400px;" /> <br /> 
            <input type="hidden" id="timeServerURL"
            name="timeServerURL"
            value="http://localhost:8080/dscapi/getServerTime" maxlength="100"
            style="width: 400px;" /><br /> 
        </form>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="well-sm">
        
        <input type="radio" name="stampingMode" id="lastPageMode"
          value="L"  style="display: none;"> <br />
            <input type="file"
          name="stampingFile" id="stampingFile" accept="images/*"   style="display: none;" />  <br />
    <!--     <input type="hidden" id="result" /><br /><br />

          <img src="/images/ksat.jpg"  id="img1" /> -->

        <textarea
        placeholder="Choose image file for wet ink stamping..."
        id="imageData" cols="60" rows="8" readonly="readonly" style="display: none;"></textarea>

      </div>
      <div class="well-sm">
        <br /> <input id="signPdf" type="button" value=" Sign Pdf "
          class="btn btn-success" style="display: none;"> 
          <input id="submitPdf"
          type="Submit" style="display: none;">

         
           <a id="downloadDiv"
          href='#' type="application/pdf" download="SignedPdf.pdf"></a>
           <input
          id="verifyPdfBtn" type="button" value=" Verify Pdf "
          class="btn btn-danger" style="display:none;">
      </div>
    </div>
    <div class="col-sm-4">
      <div class="well-sm">
       
        <textarea 
        placeholder="After signing, the encrypted signature will be shown here..."
        id="signedPdfData" cols="60" rows="8" disabled    style="display: none;">  </textarea>
       
        <textarea
        placeholder="The random key used for encrypting the signature will be shown here..."
        id="lblEncryptedKey" cols="60" rows="4" disabled   style="display: none;"></textarea>
        
        <textarea
        placeholder="The signature verification result from DSCAPI server will be shown here..."
        id="verificationResponse" cols="60" rows="8" disabled  style="display: none;"></textarea>

      </div>
    </div>
  </div>
  <div id="panel"></div>


  </section>  


			</div>
		</div>		
	</form>	


	</section>	
	 <script src="js/jquery.min.js"></script>
	<script src="js/cca/ccabyemail.js"></script>
    <script src="js/judgement/jquery.js"></script>
<script src="js/judgement/bootstrap.min.js"></script>
<script src="js/judgement/dsc-signer.js" type="text/javascript"></script>
<script src="js/judgement/dscapi-conf.js" type="text/javascript"></script>
<script type="text/javascript">


function dsc_facesheet(id,btid){
//alert("hi");

alert("id in blade"+id);
 var formData = new FormData();
alert(btid);
formData.append('applicationId', id);
//formData.append('btnid', btid);
$('#selectedid').val(id);
$('#selectedbtn').val(btid);

$.ajax({

type:'POST',
url: "facesheetdownload",
data: formData,
dataType: "JSON",
cache:false,
contentType: false,
processData: false,
success: function (response) {

   
  alert(response[0].msg);
 alert(response[0].img);
  $("#pdfData").val(response[0].msg);
    $("#imageData").val(response[0].img);
  dscSigner.sign(response[0].msg);

},

error: function(response){
console.log(response);
}

});


  } 

//============= mail send function call ajax=======================


function mail_dsc_send(id,btid){
//alert("hi");

//alert("id in mail send funtion in blade "+id);
 var formData = new FormData();
//alert(btid);
formData.append('applicationId', id);
//formData.append('btnid', btid);
//$('#selectedid').val(id);
//$('#selectedbtn').val(btid);
$.ajax({

type:'POST',
url: "ccemail",
data: formData,
dataType: "JSON",
cache:false,
contentType: false,
processData: false,
success: function (response) {

   
//alert(response[0].msg);
swal({
                  title: response[0].msg,
                  icon: "success",
                  buttons:[
                      'Cancel',
                      'OK'
                      ],

                      }).then(function() {
                         window.location.href = "/ccabyemail";   
                      })
;
   
},

error: function(response){
console.log(response);
}

});


  } 

//====================================mail send function call ajx====================================

</script>
	
@endsection
