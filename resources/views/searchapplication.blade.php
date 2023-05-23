@extends('layout.mainlayout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <style type="text/css">

    table {
      margin-left:5%;
      margin-bottom:5%;
      border: 2px solid black;
      width:90%;
}

th{
   padding: 10px;
  text-align: left;
  width: 25%;
  font-weight: normal;
}

 td {
 /* padding: 10px;*/
  text-align: left;
  width: 25%;
  font-weight: bold;
  font-size: 12px;
}
.customtd {
 padding: 0px;
}
table#t01 tr:nth-child(even) {
  background-color: #eee;
}
table#t01 tr:nth-child(odd) {
 background-color: #fff;
}

.tdvalue{
    font-weight:noraml;
}
.header {
  padding: 5px 5px 5px 30px;
  text-align: left;
  color: white;
  font-size: 15px;
  margin:0 30px 0 30px;
  background:#006fbf;
}

#modaltable
{
  width: 90%;
  border: none;
}
#modaltable td, #modaltable th {
  border: 1px solid #ddd;
  padding: 8px;
}

#modaltable tr:nth-child(even){background-color: #f2f2f2;}

#modaltable tr:hover {background-color: #ddd;}

#modaltable th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #31708f;
  color: white;
}
.alert {
  padding: 10px;
  background-color: #FF0000; /* Red */
  color: white;
  width: 50%;
  margin: 1% 0 1% 25%;
}
.custom
{
  width:10;
}
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>

  <section class="content">
      <div class="panel  panel-primary">
        <div class="panel panel-heading">
          <h7 >Search for Applcation Details</h7>
        </div>
        <form role="form" id="caseForm" action="appstatusresult"  method="POST" data-parsley-validate>
          {{ csrf_field() }}
          <div class="row">
            <div class=" col-md-offset-1 col-md-4">
              <div class="form-group">
                <label>Application Type<span class="mandatory">*</span></label>
                <select name="apptype" id="type" class="form-control" data-parsley-required data-parsley-required-message="Select Type"  value="{{ $apptype }}">
                  @empty($apptype)
                  <option value="" >Select Type</option>
                  @endempty
                  @foreach($applications as $application)
                 <option value="{{$application->appltypedesc.'-'.$application->appltypeshort}}">
                  {{$application->appltypedesc.'-'.$application->appltypeshort}}</option>
                 @endforeach
              </select>

              <!--   <select name="apptype" id="apptype" class="form-control" data-parsley-required data-parsley-required-message="Select Type"><option value="" >Select Type</option>
                  @foreach($applications as $application)
                 <option value="{{$application->appltypedesc.'-'.$application->appltypeshort}}" {{old('apptype',$application->appltypedesc.'-'.$application->appltypeshort) ==$application->appltypedesc.'-'.$application->appltypeshort ? 'selected': '' }}>
                  {{$application->appltypedesc.'-'.$application->appltypeshort}}</option>
                 @endforeach
              </select> -->

            </div>
          </div>
          <div class="col-md-4">
            <label>Application Number<span class="mandatory">*</span></label>
            <input class="form-control" name="appnum" id="orderNo" type="text" value="{{ $appnum }} ">
            <span id="error6"></span>
          </div>
          <div class="col-md-3">
            <div class="form-group"><br>
              <input type="submit" id="search" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;margin:3px;" value="Submit" name="submit">
          </div>
        </div>
      </div>
    </form>

        @include('flash-message')

            @foreach ($results as $result)
            <div class="Container" id="searchcontent">
              <h3 class="header">Application Details</h3><br>
              <div class="row table-responsive">
                <div class="col-md-offset-1 col-md-9 ">
              <table class="table table-bordered table-striped table-hover" style="border: 2px solid black;">
            <tbody>

              <tr>
                      <th scope="row" style="vertical-align: middle;">Applicaion Status</th>


                      @if($result->statusid == 2)
                            <td colspan="3" ><h1><p><span style="color: #ff6600;"><strong>DISPOSED</strong></span></p></h1></td>
                      @else
                        <td colspan="3" > <h1><p><span style="color: #ff6600;"><strong>PENDING</strong></span></p></h1></td>
                    @endif

              </tr>
              <tr>
                <th scope="row">Application Number</th>
                <td colspan="3">{{ $result->applicationo}}<br>{{ $result->additionalnumber}}</td>
              </tr>

            @if($applagainst!="")
              <tr>
               <th scope="row">Original Application Number  </th>
                <td colspan="3">{{ $applagainst->referapplid}}</td>
              </tr>
               @endif
               @if($connectedappl!="")
              <tr>
               <td scope="row">Connected Applications  </td>
                <td colspan="3">
                 @foreach ($connectedappl as $connectedappl)
                     {{ $connectedappl->connapplno}}  <br>
                @endforeach
                </td>
               </tr>
         @endif
@if($mainapplication_no!="")
            <tr>
               <td scope="row">Main Application  </td>
                <td colspan="3">
         @foreach ($mainapplication_no as $mainapplication_no)
             {{ $mainapplication_no->applicationo}}  <br>
        @endforeach
        </td>
               </tr> 

             <tr>
               <td scope="row">Connected Applications  </td>
                <td colspan="3">
         @foreach ($other_connectedid as $other_connectedid)
             {{ $other_connectedid->connapplno}}  <br>
        @endforeach
        </td>
               </tr>
         @endif


          <tr>
                <th scope="row">Filling Date</td>
                <td>{{ $result->applicationdate }}</td>
                <th scope="row">Registration Date</th>
                <td>{{ $result->registerdate }}</td>
              </tr>
              <tr>
                <th scope="row">Application Category</th>
                <td colspan="3">{{ $result->applcatname}}</td>

              </tr>
              <tr>
                <th scope="row">Interim Prayer</td>
                <td colspan="3">{{(empty($result->interimprayer)) ? '----' : $result->interimprayer }}</td>
              </tr>

              <tr>
                <th scope="row">Subject</th>
                <td colspan="3">{{ $result->subject }}</td>
              </tr>
                <tr>
                <th scope="row">Relief</th>
                <td colspan="3">
               @foreach ($applreliefs as $applrelief)
                  {{ empty($applrelief->relief) ? '----':$applrelief->reliefsrno.')'. $applrelief->relief }}<br><br>
                @endforeach
              </td>
              </tr>

              <tr>
                <th scope="row">Against Orders</th>
                <td>{{ $result->againstorders}}</td>
                <th scope="row">Remarks</td>
                <td>{{ (empty($result->remarks)) ? '----': $result->remarks }}</td>
              </tr>
            </tbody>
          </table>
          </div>
          </div>

          <div class="Container" id="searchcontent">
              <h3 class="header">Application Status</h3><br>
              <div class="row">
                <div class="col-md-offset-1 col-md-9">
                <br><br>
              <table class="table table-bordered table-striped table-hover" style="border: 2px solid black;">
            <tbody>
              <tr>
                <th scope="row">First Hearing Date </th>
                <td>
                  {{ (empty($result->firstlistdate)) ? '----' : $result->firstlistdate }}
                </td>

                <th scope="row">Last Hearing Date</td>
                <td>
                  {{ (empty($result->lastlistdate)) ? '----' : $result->lastlistdate }}
                </td>
              </tr>
              <tr>
                <th scope="row">Next Hearing Date</th>
                <td>{{ (empty($result->nextlistdate)) ? '----' : $result->nextlistdate }}</td>
                <th scope="row"></td>
                <td></td>
              </tr>
              <tr>
                <th scope="row">Stage of Application</th>
                <td>{{(empty($result->purposelast)) ? '----': $result->purposelast }} </td>
                <th scope="row"></td>
                <td></td>
              </tr>
              <tr>
                <th scope="row">Bench </th>
                <td>{{(empty($result->bench)) ? '----': $result->bench }} </td>
                <th scope="row"> </th>
                <td></td>
              </tr>
               <tr>
                <th scope="row">Next Stage</th>
                <td>{{ (empty($result->purposenext)) ? '----' : $result->purposenext }}</td>
                <th scope="row">  <a class="text-center" href="#hearingModal" id="hearmodal">Hearing History </a>
               </th>
                <th scope="row"> <a class="text-center" href="#IAOtherDocumentModal" id="IAOtherDocument">IA/Other Documents </a></th>
              </tr>
            </tbody>
          </table>
          </div>

          </div>

          <div class="Container" id="searchcontent">
              <h3 class="header">Applicants/ Respondents</h3><br>
              <div class="row">
                <div class="col-md-offset-1 col-md-9">
              <table class="table table-bordered table-striped table-hover" style="border: 2px solid black;">
            <tbody>
              <tr>
                <th scope="row" class="col-xs-3">Name of the Applicant </th>
                <td  class="col-xs-9">{{ $result->applicantname1}}
                  @if($result->applicantcount > 1)
                  <a href="#applicantModal" id="appmodal">( {{ $result->applicantcount }} ) </a>
                  @endif
              </tr></td>
              <tr>
                <th scope="row">Advocate</th>
                <td>{{ $result->advocateregno}}<br>{{ $result->advocatename}}</td>
              </tr>
              <tr>
                <th scope="row">Name of the Respondent</th>
                <td>{{ $result->respondentname}}
                 @if($result->respondentcount > 1)
                  <a href="#respondantModal" id="respmodal">( {{ $result->respondentcount}} )</a>
                @endif
                </td>
              </tr>
              <tr>
                <th scope="row">Advocate</th>
                <td>{{ $result->respondentadvcode}}<br>{{ $result->respondentadvocate}}</td>
              </tr>
            </tbody>
          </table>
          </div>
          </div>


          <div class="Container" id="searchcontent">
              <h3 class="header">Application Index</h3><br>
              <div class="row">
                <div class="col-md-offset-1 col-md-9">
              <table class="table table-bordered table-striped table-hover" style="border: 2px solid black;">
                 <thead  style="background-color: #3c8dbc;color:#fff;">
                          <tr>
                            <td  style="width:15%;">Document Date</td>
                            <td style="width:55%;">Document Name</td>
                            <td style="width:10%;" > Start Page- End Page</td>
                            <td>Document No</td>
                        </tr>
                </thead>
                @foreach($appindexes as $appindex)
                <tbody>
                  <tr>
                  <td class="col-xs-1" >{{ date ('d-m-Y',strtotime($appindex->documentdate)) }} </td>
                    <td class="col-xs-6">{{  $appindex->documentname }}</td>
                    <td class="col-xs-3">{{ $appindex->startpage }} - {{ $appindex->endpage}}</td>
                    <td class="col-xs-1">{{ $appindex->documentno}}</td>
                  </tr>
              </tbody>
              @endforeach
          </table>
          </div>
          </div>
   <div class="Container" id="searchcontent">
              <h3 class="header">Receipt</h3><br>
              <div class="row">
                <div class="col-md-offset-1 col-md-9">
              <table class="table table-bordered table-striped table-hover" style="border: 2px solid black;">
                 <thead  style="background-color: #3c8dbc;color:#fff;">
                          <tr>
                            <td>Receipt Date</td>
                            <td>Receipt No</td>
                            <td>Name</td>
                            <td>Amount</td>
                        </tr>
                </thead>
                @foreach($receipts as $receipt)
                <tbody>
                  <tr>
                    <td class="col-xs-1">{{ date('d-m-Y', strtotime($receipt->receiptdate))}} </td>
                    <td class="col-xs-6">{{  $receipt->receiptno }}</td>
                    <td class="col-xs-3">{{ $receipt->titlename }}.{{ $receipt->name}}</td>
                    <td class="col-xs-1">{{ $receipt->amount}}</td>
                  </tr>
              </tbody>
              @endforeach
          </table>
          </div>
          </div>

           
	<div class="Container" id="searchcontent">
              <h3 class="header">Judgment Details</h3><br>
              <div class="row">
                <div class="col-md-offset-1 col-md-9">
              <table class="table table-bordered table-striped table-hover" style="border: 2px solid black;">
                 <thead  style="background-color: #3c8dbc;color:#fff;">
                          <tr>
                            <td style="width:15%;">Sl No</td>
                            <td style="width:55%;">Name</td>
                            <td style="width:10%;">View</td>
                        </tr>
                </thead>
                @if($fileName != '')
                <tbody>
                  <tr>
                  <td class="col-xs-1" >1</td>
                    <td class="col-xs-6"><?php echo str_replace('.pdf','',$fileName); ?></td>
                    <td class="col-xs-3"><a href="data:application/pdf;base64,{{$fileData}}" target="_blank" download="{{$fileName}}">View</a></td>
                  </tr>
              </tbody>
              @endif
          </table>
		  
          </div>
          </div>



@endforeach

          <div class="modal fade" id="applicantModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h3 class="modal-title text-center" id="exampleModalLabel" >Applicant Details</h3>
                </div>
                <div class="modal-body">
                  <table id="modaltable">
                    <thead>
                    <th class="col-xs-1">Sl.no</th>
                    <th>Applicant</th>
                    <th>Advocate</th>
                    </thead>
                   @foreach($applicants as $applicant)
                    <tbody>
                      <td class="col-xs-1">{{ $applicant->applicantsrno}}</td>
                      <td class="col-xs-8">{{ $applicant->applicantname1}},
                        {{ $applicant->relationname}}<br>
                       {{ $applicant->applicantage}} <br>
                       {{ $applicant->designame }}
                      {{ $applicant->applicantaddress}}</td>
                      <?php if($applicant->advocateregno!=null)
                    {?>
                  <td class="col-xs-3">{{ $applicant->advocateregno}}<br>
                        {{ $applicant->advocatename}}</td>
                    <?php }else{?>
                    <td >---</td>
                    <?php }?>

                    </tbody>
                    @endforeach
                  </table>
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="respondantModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h6 class="modal-title text-center" id="exampleModalLabel" >Respondent Details</h6>
                </div>
                <div class="modal-body">
                  <table id="modaltable">
                    <thead>
                    <th class="col-xs-1">Sl.No</th>
                    <th>Respondent</th>
                    <th>Advocate</th>
                    </thead>
                   @foreach($respondents as $respondent)
                    <tbody>
                      <td class="col-xs-1">{{ $respondent->respondsrno}}</td>
                      <td class="col-xs-8">{{ $respondent->respondname1}}, {{ $respondent->relationname1}}<br>
                       {{ $respondent->respondantage}}<br>
                      {{ $respondent->respondaddress}}
                    </td>
                      <td class="col-xs-3">{{ $respondent->advocateregno}}<br>
                        {{ $respondent->advocatename}}</td>
                    </tbody>
                    @endforeach
                  </table>
                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

              <div class="modal fade" id="hearingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title text-center" id="exampleModalLabel" >Hearing History</h4>
                </div>
                <div class="modal-body">
                  <table class="table table-bordered table-striped table-hover" style="border: 2px solid black; width:90%;">

                 <thead  style="background-color: #3c8dbc;color:#fff;">
                          <tr>
                            <th class="col-xs-1">Hearing Date</th>
                            <th class="col-xs-2" >Bench </th>
                            <th class="col-xs-1" >Court Hall No</th>
                            <th class="col-xs-1">Stage</th>
                            <th class="col-xs-3">Court Direction</th>
                            <th  >Status</th>
                            <th  >Next Date</th>
                        </tr>
                </thead>

                @foreach($hearings as $hearing)
                <tbody>
                  <tr>
                    <td class="col-xs-1" >{{ $hearing->hearingdate }}</td>
                    <td >{{ $hearing->bench }}</td>
                    <td >{{ $hearing->courthallno}}</td>
                    <td >{{ $hearing->purposelast}}</td>
                    <td >{{ $hearing->courtdirection}}</td>
                    <td >{{ $hearing->statusname}}</td>
                    <?php if($hearing->nextdate!=null)
                    {?>
                    <td >{{ $hearing->nextdate }}</td>
                    <?php }else{?>
                    <td >---</td>
                    <?php }?>


                  </tr>
              </tbody>
              @endforeach
          </table>

                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

     <div class="modal fade" id="IAOtherDocumentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title text-center" id="exampleModalLabel" >IA/Other documents  </h4>
                </div>
                <div class="modal-body">
                  <table class="table table-bordered table-striped table-hover" style="border: 2px solid black; width:90%;">

                 <thead  style="background-color: #3c8dbc;color:#fff;">
                          <tr>
                            <th class="col-xs-1">Document Name</th>
                            <th class="col-xs-2" >IA No </th>
                            <th class="col-xs-1" >IA Nature Desc</th>
                            <th class="col-xs-1">IA Prayer</th>
                            <th class="col-xs-3">Filed By</th>
                            <th class="col-xs-3">Status</th>
                         </tr>
                </thead>

                @foreach($iaotherdocuments as $iaotherdocument)
                <tbody>
                  <tr>
                    <td class="col-xs-1" >{{ $iaotherdocument->documentname }}</td>
                    <td >{{ $iaotherdocument->iano}}</td>
                    <td >{{ $iaotherdocument->ianaturedesc}}</td>
                    <td >{{ $iaotherdocument->iaprayer}}</td>
                    <td >{{ $iaotherdocument->filedby}}</td>
                    <td >{{ $iaotherdocument->statusname}}</td>
                </tr>
              </tbody>
              @endforeach
          </table>

                </div>
                <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>


<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>
<script type="text/javascript">


  $("#appmodal").click(function(){
    $('#applicantModal').modal('show');
});
   $("#respmodal").click(function(){
    $('#respondantModal').modal('show');
});
    $("#hearmodal").click(function(){
    $('#hearingModal').modal('show');
});
    $("#IAOtherDocument").click(function(){
    $('#IAOtherDocumentModal').modal('show');
});
</script>
<script src="js/casemanagement/searchforapplication.js"></script>
</section>
@endsection
