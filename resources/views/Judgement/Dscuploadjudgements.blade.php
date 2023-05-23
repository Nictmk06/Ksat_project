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
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

  <section class="content">

<!--   <form action="savejudgements" method="POST" enctype="multipart/form-data">
  {{ csrf_field() }}
  <div class="form-group">
     <label for="imgUpload1">File input</label>
     <input type="file" id="imgUpload1" name="imgUpload1">
  </div>
    <input type="button" id="savejudgements" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Upload">

    <button type="submit" class="btn btn-primary">Submit</button>
</form>-->

   <form role="form" id="uploadJudgementsForm" action="dscsavejudgements" enctype="multipart/form-data" method="POST"
	data-parsley-validate>
	@csrf
      <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Upload Judgement</h7>
        </div>

        <div class="panel panel-body">
            <div class="row">
            <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="col-md-4">
              <div class="form-group">
                <label>Type of Application<span class="mandatory">*</span></label>

                <input type="hidden" name="applYear" id='applYear' value=''>
                <select class="form-control" name="applTypeName" id="applTypeName"  data-parsley-trigger='change' data-parsley-errors-container="#modlerror1" data-parsley-required data-parsley-required-message="Select Application Type">
                  <option value="">Select Application Type</option>
                  @foreach($applicationType as $applType)
                  <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>
                  @endforeach
                  </select> <span id="modlerror1"></span>
                </div>

              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Application No<span class="mandatory">*</span></label>
                  <div class="input-group ">
                    <input type="text" name="applicationId" class="form-control pull-right number zero" id="applicationId" value="" data-parsley-errors-container="#modlerror" data-parsley-required data-parsley-error-message='Enter Application No' data-parsley-pattern='^[0-9/ ]+$' data-parsley-pattern-message='Invalid Application No' maxlength='20'>
                    <div class="input-group-addon" style="color:#fff;background-color: #337ab7" id="applSearch">
                      <i class="fa fa-search"></i>
                    </div>

                  </div>
                  <span id="modlerror"></span>
                </div>
              </div>
              <div class="col-md-4"> <div class="form-group">
                <label>Date Of Application<span class="mandatory">*</span></label>
                <div class="input-group ">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="dateOfAppl" class="form-control pull-right " id="dateOfAppl"  value=""  readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Registration Date</label>
                <div class="input-group ">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="applnRegDate" class="form-control pull-right " id="applnRegDate"   readonly>
                </div>

              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Application Category<span class="mandatory">*</span></label>
                <select class="form-control" name="applCatName" id="applCatName" disabled >
                  <option value="">Select Applcation Category</option>
                  @foreach($applCategory as $applCat)
                  <option value="{{$applCat->applcatcode}}">{{$applCat->applcatname}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Subject<span class="mandatory">*</span></label>
                <textarea class="form-control" name='applnSubject' id="applnSubject"  readonly>
                </textarea>
              </div>
            </div>
			 <div class="col-md-4">
                      <label>Judgement Date<span class="mandatory">*</span></label>
                      <div class="input-group ">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="applnJudgementDate" class="form-control pull-right datepicker" id="applnJudgementDate"
						value=""  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits"
						data-parsley-required  data-parsley-required-message="Enter Registration Date"  data-parsley-errors-container="#error5" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY" >
                      </div>
                      <span id="error5"></span>
                    </div>

        <div class="col-md-4">
                      <label>Release Date<span class="mandatory">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="releaseDate" class="form-control pull-right datepicker" id="releaseDate"
            value="<?php echo date('d-m-Y');?>"  data-parsley-pattern="/^[0-9_-]+$/" data-parsley-pattern-message="Date Of Registration Allows only digits"
            data-parsley-required  data-parsley-required-message="Enter Release Date"  data-parsley-errors-container="#error6" data-parsley-trigger='keypress' placeholder="DD-MM-YYYY" autocomplete="off">
                      </div>
                      <span id="error6"></span>
                    </div>

			

          </div>
          <br/>
          <div class="row">
            <!-- <div class="col-md-4">
              <div class="form-group">
                <label>Upload plain Judgement</label><span class="mandatory">*</span>
                  <input type="file" id="judgement" name="judgement"   data-parsley-required  data-parsley-required-message="please select judgment file"  data-parsley-errors-container="#error7">                               
                 </div>
                  <span id="error7"></span>
                  <div id="element"></div>
                  <div id="downloadjudgementdiv"></div>
                  <div class="row"  style="display: inline" ></div>
              </div> --> 
           <!--==================================-dsc judgement upload cretion=====================-->  
                 
       <!-- <div class="col-md-4">
              <div class="form-group">
                <label>Upload digitally signed Judgement</label><span class="mandatory">*</span>
                  <input type="file" id="dscjudgement" name="dscjudgement"   data-parsley-required   data-parsley-required-message="please select digitally signed judgment file"  data-parsley-errors-container="#error8"> 
                 </div>
                  <span id="error8"></span>
               <div id="dscelement"></div> 
              
            <div id="downloaddscjudgementdiv"></div>
           <div class="row"  style="display: inline" >
          </div>
         </div> 
           ==================================-dsc judgement upload cretion===============  --> 
        <!-- <div class="col-md-4">
              <div class="form-group">
                <label>Upload pdf Judgement file for digitally signed </label><span class="mandatory">*</span>
                 <input type="file" name="pdfFile" id="pdfFile" accept="application/pdf" />
                 </div>
               <div id="dscelement"></div> 
              
            <div id="downloaddscjudgementdiv"></div>
           <div class="row"  style="display: inline" >
          </div>
         </div>-->

<!--dsc-->

<div class="row">
    <div class="col-sm-4">
      <div class="well-sm">
        <form id="pdfForm">
          <label for="data">Choose Judgement Pdf File :<span class="mandatory">*</span> </label><br /> <input
            type="file" name="pdfFile" id="pdfFile" accept="application/pdf" />
          <label for="pdfData"></label> <br />
           <div id="dscelement"></div> 
          <textarea id="pdfData" 
          placeholder="Choose pdf file above to show pdf data..."
          cols="60" rows="4" readonly="readonly" style="display:none;"></textarea>
           <input type="hidden" id="signingReason"
            name="signingReason" maxlength="20" value="Judgement Sign" /> <input
            type="hidden" id="signingLocation" name="signingLocation" value="KSAT"
            maxlength="20" />   <input type="hidden"
            id="stampingX" name="stampingX" maxlength="20" value="200" />
          <input type="hidden" id="stampingY" name="stampingY" maxlength="20"
            value="200" /> <select name="tsaurls" style="display:none"
            id="tsaurls" onchange="myFunction()">
           
            <option
              value="0">
             </option>
         <option value="http://timestamp.comodoca.com/rfc3161">http://timestamp.comodoca.com/rfc3161</option>
            <option value="http://tsa.startssl.com/rfc3161">http://tsa.startssl.com/rfc3161</option>
            <option value="http://timestamp.digicert.com">http://timestamp.digicert.com</option>
            <option value="http://tsa.safecreative.org">http://tsa.safecreative.org</option>
         </select>  <input type="hidden" id="tsaURL"
            name="tsaURL" value="" maxlength="100" style="width: 400px;" /> 
             <input type="hidden" id="timeServerURL"
            name="timeServerURL"
            value="http://localhost:8080/dscapi/getServerTime" maxlength="100"
            style="width: 400px;" /><!-- <span style="color: red;">If
            the time server URL is not provided, the client time will be used
            for signing.</span> <br />-->
            <input id="signPdf" type="button"
            value=" Sign Pdf " class="btn btn-success"> <input
            id="submitPdf" type="Submit" style="display: none;"> <a
            id="downloadDiv" href='#' type="application/pdf"
            download="SignedPdf.pdf"></a> <input id="btnDecryptVerify"
            type="button" value=" Decrypt Verify " class="btn btn-danger" />
          <input id="btnDecryptVerifyWithCrt" type="button"
            value=" Decrypt & Verify " class="btn btn-danger" />
        </form>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="well-sm">
        <!--<label for="signedPdfData">Signed Pdf Data(Base64):</label> <br />-->
        <input  type="hidden" 
        placeholder="After signing, the encrypted signature will be shown here..."
        id="signedPdfData"  name="signedPdfData" >
      </div>
    </div>
  </div>
  <div id="panel"></div>
<!--dsc-->

         <div class="row">
            <div class="col-md-12">
           <p> <span class="mandatory"><strong>  Note : </strong>Only .pdf formats allowed to a max size of 5 MB.</span></p>
          </div>
          </div>
		  </div>


        <div class="row"  style="float: right;" id="add_apl_div">

		   <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-primary" id="submitjudgement">Submit</button>
                <input type="button" class="btn btn-danger btn-md center-block btnClear" id="clear" Style="width: 100px;" value="Cancel">
              </div>
        </div><br><br>





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
    <!-- /.tab-pane -->
    <script src="js/jquery.min.js"></script>
    <script src="js/judgement/dscuploadjudgements.js"></script>
    
    <script src="js/judgement/jquery.js"></script>
<script src="js/judgement/bootstrap.min.js"></script>
<script src="js/judgement/dsc-signer.js" type="text/javascript"></script>
<script src="js/judgement/dscapi-conf.js" type="text/javascript"></script>



  </section>

  @endsection
