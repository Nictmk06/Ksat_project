
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

@include('flash-message')
<br> <br>
<?php
 if (count($dailyhearing) == 0)
 { ?>
    <div class="alert alert-danger">
	 <button type="button" class="close" data-dismiss="alert">×</button>
            <strong> No applications found/left for today, in this court hall </strong>
   </div>

<?php
 } ?>

<div class="container">


<form action="ChProceedingUpdate" method="POST" data-parsley-validate>
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" name="hearingCode" id="hearingCode" value="">
     <input type="hidden" name="courthallno" id="courthallno" value="{{$courthall}}">
      <input type="hidden" name="benchcode" id="benchcode" value=" {{$benchdetail[0]->benchcode}}">
	   <input type="hidden" name="hearingDate" id="hearingDate" value=" {{date('d-m-Y')}}">
	  
@csrf
<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered" style="font-size:14px;">

        <tr>
        <td  class="bg-primary text-center" colspan="6"> <h4> Court Hall Proceedings </h4> </td>
        </tr>

        <?php
         $today_sys_dt = date("d-m-Y");
        ?>

        <tr>
        <td colspan="6">

        <table width="99%">
        <tr>
		<td style:width="40%"> <label> Hearing Date : </label> <span name="hd_hearingdate" id="hd_hearingdate"> {{ date('d-m-Y') }} </span>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		<td style:width="40%">    <label> Bench : </label>
             <span name="hd_benchdetail" id="hd_benchdetail"> 

                <?php $i=0; $tbench = ""; ?>
            @foreach($benchdetail as $benchdetail1)
          <?php $tbench = $tbench . $benchdetail[$i]->judgeshortname . ", " ;  
                $i=$i+1; ?>
            @endforeach
[ {{ $tbench }} ]
                           </span>	
          
		</td>
       <td style:width="40%">   <label> Court Hall : </label>  <span name="hd_courthallno" id="hd_courthallno"> {{$courthall}} </span>
	   <input type="hidden" id="courthallno" value={{$courthall}}/>
	    </td>

		<td align="left"> <label for="causelistno"> <span id="mainIdLabel">  Cause List No. : </span><span class="mandatory">*</span> </label>  </td>
        <td> <select class="form-control" style="width:200px" name="listno" id="listno" required data-parsley-required-message="Select  Cause List No" style="height:34px" data-parsley-trigger='focus'>
           <option value="0" >Select Cause List No </option>

        <?php $i=0; ?>
            @foreach($listno as $listno1)
            <option value="{{$listno1->listno.'-'.$listno1->benchcode}}">{{ $listno1->listno  }} - {{ $listno[$i]->judgeshortname }}</option>
            <?php $i=$i+1; ?>
            @endforeach

            </select>
		</td>



       <!--  <td colspan="2"><label> - : </label>
           <select class="form-control" style="width:200px" name="applicationId" id="applicationId" required data-parsley-required-message="Select Application" style="height:34px" data-parsley-trigger='focus'>
           <option value="0:0" >Select Application </option>

            @foreach($dailyhearing as $daily)
            <option value="{{$daily->applicationid . ':' . $daily->hearingdate}}">{{$daily->applicationid}}</option>
            @endforeach
            </select>
            </td> -->
        </tr>
        </table>

        </td>
        </tr>

        <tr>
        <td> <label for="applicationId"> <span id="mainIdLabel"> Application ID </span><span class="mandatory">*</span> </label>  </td>
        <td colspan="2">
           <table>
           <tr>

           <td >
           <select class="form-control" style="width:200px" name="applicationId" id="applicationId" required data-parsley-required-message="Select Application" style="height:34px" data-parsley-trigger='focus'>
           <option value="0:0" selected >Select Application </option>
      <!--
            @foreach($dailyhearing as $daily)
            <option value="{{$daily->applicationid . ':' . $daily->hearingdate}}">{{$daily->applicationid}}</option>
            @endforeach -->
            </select>
            </td >
            <td > &nbsp;&nbsp;&nbsp;
            <button type="button"  name="admitButton" id="admitButton" class="btn1" value="A"> A </button> &nbsp;&nbsp;
            <button type="button"  name="pauseButton" id="pauseButton" class="btn2" value="P"> P </button>  &nbsp;
            <!--<button type="button"  name="endSessionButton" id="endSessionButton" class="btn3" value="E"> E </button>  &nbsp;-->
		  <span name="admitPause" id="admitPause"></span>
            </td>

            </tr>
            </table>
            <input type="hidden" name="hiddenApplicationId" id="hiddenApplicationId" value="0:0"> <!-- Used for internal purpose  //-->
            </td>
        <td >  <label for="conApplicationId"> <span id="connectIdLabel"> C/A ID </span> </label> </td>
        <td colspan="2">
           <select class="form-control" name="conApplicationId" id="conApplicationId" required data-parsley-required-message="Select Application" style="height:34px" data-parsley-trigger='focus'>
           <option value="0:0" >Select Application </option>

           </select>
        </td>
        </tr>

        <tr class="">
        <td> <label for="applicantRespondent"> Applicant / Respondant </label>  </td>
        <td colspan="5"> <div class="ex1" name="applicantRespondent" id="applicantRespondent">   </div> </td>
        </tr>

        <tr class="">
        <td> <label for="postedFor"> Posted for <span class="mandatory">*</span> </label>  </td>
        <td colspan="5"> <div class="ex1" name="postedFor" id="postedFor"> </div> </td>
        </tr>

        <tbody id="iaTableBlock" style="display:none">

        <tr class="bg-light-blue">
        <td> <label for="pendingIa"> Pending IA </label> </td>
        <td>
           <select class="form-control" name="pendingIa" id="pendingIa" data-parsley-required-message="Select IA" style="height:34px" data-parsley-trigger='focus'>
           <option value="0:0" >Select IA </option>
           </select>
           <input type="hidden" name="hiddenPendingIa" id="hiddenPendingIa" value="0:0"> <!-- Used for internal purpose (To save previously selcted IA on change event) //-->
        </td>
        <td> <label for="iaStatus"> IA Order Passed </label> </td>
        <td>
           <select class="form-control" name="iaOrderPassed" id="iaOrderPassed" data-parsley-required-message="IA Status" style="height:34px" data-parsley-trigger='focus'>
           <option value="" >Select </option>

           </select>
        </td>

        <td> <label for="iaStatus"> IA Status </label> </td>
        <td>
           <select class="form-control" name="iaStatus" id="iaStatus" data-parsley-required-message="IA Status" style="height:34px" data-parsley-trigger='focus'>
           <option value="" >Select IA Status </option>

           </select>
        </td>
        </tr>

        <tr class="bg-light-blue">
        <td> <label for="iaPrayer"> IA Prayer  </label> </td>
        <td> <textarea class="form-control" name="iaPrayer" id="iaPrayer" rows="3" cols="70" readonly> </textarea> </td>

        <td colspan="2" align="right"> <label for="iaRemarks"> Remarks </label> </td>
        <td colspan="2"> <textarea class="form-control" name="iaRemarks" id="iaRemarks" rows="3" cols="60"> </textarea> </td>
        </tr>

        </tbody>

        <tr>
        <td> <label for="courtDirection" > Court Direction <span class="mandatory">*</span> </label>  </td>
        <td colspan="2"> <textarea class="form-control" name="courtDirection" id="courtDirection" rows="3" cols="70" required> </textarea> </td>

        <td > <label for="remarksIfAny" > Remarks <span class="mandatory">*</span> </label>  </td>
        <td colspan="2"> <textarea class="form-control" name="remarksIfAny" id="remarksIfAny" rows="3" cols="60" required> </textarea> </td>
        </tr>

        <tr>
        <td> <label for="orderPassed" > Order Passed <span class="mandatory">*</span> </label>  </td>
        <td colspan="2">
           <select class="form-control" name="orderPassed" id="orderPassed" required data-parsley-required-message="Order Passed" style="height:34px" data-parsley-trigger='focus'>
           <option value="">Select Order Passed </option>

           </select>
           <div name="orderDateDiv" id="orderDateDiv" style="display:none"> <label> Order Date : </label> <input type="text" name="orderDate" id="orderDate" value="" size="10"> </div>
        </td>
        <td colspan="1"> <label for="applicationFinalStatus"> Application Final Status <span class="mandatory">*</span> </label>  </td>
        <td colspan="2">
           <select class="form-control" name="applicationFinalStatus" id="applicationFinalStatus" required data-parsley-required-message="Applicaion Final Status" style="height:34px" data-parsley-trigger='focus'>
           <option value=""> Select Application Final Status </option>

           </select>
           <div name="disposedDiv" id="disposedDiv" style="display:none"> <label> Disposed Date : </label> <input type="text" name="disposedDate" id="disposedDate" value="" size="10"> </div>
        </td>
        </tr>

        <tbody name="disposedYN" id="disposedYN">

        <tr>
        <td> <label for="postAfterPeriod" > Post after period <span class="mandatory">*</span>  </label> </td>
        <td colspan="2"> <input  Type="number" name="postAfterPeriod" id="postAfterPeriod" size="5" maxlength="3" value="">
                         <input  type="radio" name="dwm" id="dwm" value="d" checked > <label > Days </label>
                         <input  type="radio" name="dwm" id="dwm" value="w"> <label > Weeks </label>
                         <input  type="radio" name="dwm" id="dwm" value="m"> <label style=";"> Months </label>
        </td>
        <td > <label for="nextHearingDate" > Next Hearing Date </label> </td>
		 <td colspan="2">
             <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type="text" name="nextHearingDate" class="form-control pull-right datepicker" id="nextHearingDate" data-parsley-pattern="/^[0-9_-]+$/"  data-parsley-pattern-message="Invalid Date" value="">
                </div>
           </td>
       <!-- <td colspan="2"> <input class="form-control" type="text" name="nextHearingDate" id="nextHearingDate" size="10" maxlength="10"> </td>
        --></tr>

        <tr>
        <td> <label for="nextListingPurpose" > Next Listing Purpose </label>  </td>
        <td colspan="2">
           <select class="form-control" name="nextListingPurpose" id="nextListingPurpose"  data-parsley-required-message="Next Listing Purpose" style="height:34px" data-parsley-trigger='focus'>
           <option value="">Select Next Listing Purpose </option>

           </select>
        </td>
       <!--  <td > <label for="benchType" > Next Bench Type </label> </td>
        <td colspan="2">
           <select class="form-control" name="benchType" id="benchType" data-parsley-required-message="Bench Type" style="height:34px" data-parsley-trigger='focus'>
           <option value=""> Bench Type </option>

           </select>
        </td> -->
        </tr>

        <tr>
        <td> <label for="bench" > Next Bench </label> </td>
        <td colspan="2">
           <select class="form-control" name="bench" id="bench"  data-parsley-required-message="Bench" style="height:34px" data-parsley-trigger='focus'>
           <option value="">Select  Bench </option>

           </select>
        </td>
        <td > <label for="business">Business </label> </td>
        <td  colspan="2" align="center">  <label> (Completed..?)
                         <input  type="radio" name="business" id="business" value="Y"> Yes
                         <input  type="radio" name="business" id="business" value="N"> No
        </td>
        </tr>

        </tbody>     <!-- table body to hide following input after selectiong 'disposed' in FinalApplicationStatus dropdown else show the following input //-->

        <tr>
        <td colspan="4">
        <div class="text-center">
  <button type="button"  name="endSessionButton" id="endSessionButton" class="btn btn-danger" value="E"> End Session </button>  &nbsp;
		 
                <button type="submit" name="saveButton" id="saveButton" class="btn btn-primary"> Save </button>
               <a class="btn btn-warning" href="dashboard"> Close </a>
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


<script src="js/jquery-3.4.1.js"></script>

<script src="js/chp/chp.js"></script>


<script>

/*$(document).ready(function(){

   $('#saveButton').attr('disabled','disabled');

   $("#applicationId, #conApplicationId").change(function(){
   var applicationid = $(this).val();

   $('#hiddenApplicationId').val(applicationid);  // Used for updating dailyhearing table
   $('#saveButton').attr('disabled','disabled');
   $('#orderDateDiv').hide();
   $('#disposedDiv').hide();
   $('#admitPause').hide();

   id                = applicationid.split(':',2);
   var appid         = id[0];
   var hrdt          = id[1];

   var optag = 1;
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {_token: CSRF_TOKEN,optag:optag,applicationid:appid,hearingdate:hrdt},
      success: function (data)
      {
        var benchdetail    = data['benchdetail'];
        var appldetail     = data['appldetail'];
        var conappls       = data['conappls'];   // Connected applications
        var applicants     = data['applicants'];
        var respondents    = data['respondents'];
        var iapending      = data['iapending'];
        var m_status       = data['m_status'];
        var m_ordertype    = data['m_ordertype'];
        var m_purpose      = data['m_purpose'];
        var m_bench        = data['m_bench'];
        var m_benchtype    = data['m_benchtype'];

        if ( iapending.length > 0 )
        {
         $('#iaTableBlock').show();
        }
        else
        {
         $('#iaTableBlock').hide();
        }

        if (appldetail.length > 0)
        {
          $('#saveButton').removeAttr('disabled');
        }

        // Highlight selected application ID, main application or connected application
        if (appldetail[0].mainapplicationid == null)
        {
         $('#connectIdLabel').removeClass('highlight');
         $('#mainIdLabel').addClass('highlight');
        }
        else
        {
         $('#connectIdLabel').addClass('highlight');
         $('#mainIdLabel').removeClass('highlight');
        }

        // Set button value for Admin and Pause buttons
        var admitPause = appldetail[0].benchcode + '^^' + appldetail[0].listno + '^^' + appldetail[0].courthallno + '^^' + appldetail[0].hearingdate + '^^' + appldetail[0].causelistsrno + '^^' + appldetail[0].applicationid;
        var admitVal   = admitPause + '^^' + 'A';
        var pauseVal   = admitPause + '^^' + 'P';
        $('#admitButton').val(admitVal);
        $('#pauseButton').val(pauseVal);

        //Set hearingcode(PK) to hidden input value. This may be used while updaing current application
        if (appldetail.length > 0)
        {
           $('#hearingCode').val(appldetail[0].hearingcode);
        }
        else
        {
         $('#hearingCode').val('');
        }

        //Display bench details like bench type, court hall no, hearing date at top of form
        if (benchdetail.length > 0)
        {
          $('#hd_benchdetail').text(benchdetail[0].benchtypename + ' [ ' + benchdetail[0].judgeshortname + ' ]');
          $('#hd_listno').text(benchdetail[0].listno);
        }
        else
        {
         $('#hd_benchdetail').text('Not found');
         $('#hd_listno').text('Not found');
        }

        // Populate Connected applications drop-down
        if (conappls.length > 0 || appldetail[0].mainapplicationid == null)
        {
           $('#conApplicationId').find('option').not(':first').remove();
           for (i = 0; i < conappls.length; i++)
           {
             $('#conApplicationId').append('<option value="' + conappls[i].applicationid + ':'  + conappls[i].hearingdate + '">' + conappls[i].applicationid + '</option>');
           }
        }

        //Display Applicants(petioners) and Respondets
        var applicantText = "<b> <u> Applicant : </u> </b>";
        if (applicants.length > 0)
        {
          for (i = 0; i < 1; i++)
          {
             applicantText +=  applicants[i].applicantsrno + ' ' + applicants[i].applicantname; // + ' ' + applicants[i].relationname + ' ' + applicants[i].applicantaddress + ', ';
          }
        }
        else
        {
             applicantText +=  "No applicants found";
        }

        applicantText +=  "<b> <u> Respondent : </u> </b>";
        if (respondents.length > 0)
        {
          for (i = 0; i < 1; i++)
          {
             applicantText +=  respondents[i].respondsrno + ' ' + respondents[i].respondname; // + ' ' + respondents[i].respondaddress + ' ';
          }
        }
        else
        {
             applicantText +=  "No respondents found";
        }

           $('#applicantRespondent').html(applicantText);

        //Display Posted for(purpose), Court directions and Remarks(officenote)
        if (appldetail.length > 0)
        {
          $('#postedFor').html(appldetail[0].postedfor);
          $('#courtDirection').val(appldetail[0].courtdirection);
          $('#remarksIfAny').val(appldetail[0].caseremarks);
        }
        else
        {
         $('#postedFor').html('');
         $('#courtDirection').val('');
         $('#remarksIfAny').val('');
        }

        //Set Pop-down list for Status of application, Application final status, Next listing purpose, Bench type and Bench name
        if (appldetail.length > 0)   // Set pop-ups and IA details
        {
          var applid             = appldetail[0].applicationid;
          var applstatus         = appldetail[0].casestatus;
          var ordertypecode      = appldetail[0].ordertypecode;
          var purposecode        = appldetail[0].purposecode;
          var benchcode          = appldetail[0].benchcode;
          var benchtypename      = appldetail[0].benchtypename;
          var nextpurposecode    = appldetail[0].nextpurposecode;
          var nextbenchcode      = appldetail[0].nextbenchcode;
          var nextbenchtypename  = appldetail[0].nextbenchtypename;

          // Set Order Passed
          $('#orderPassed').find('option').not(':first').remove();
          for (i = 0; i < m_ordertype.length; i++)
          {
              if ( m_ordertype[i].ordertypecode == ordertypecode )
              {
                 $('#orderPassed').append('<option selected="true" value="' + m_ordertype[i].ordertypecode + '">' + m_ordertype[i].ordertypedesc + '</option>');
              }
              else
              {
                 $('#orderPassed').append('<option value="' + m_ordertype[i].ordertypecode + '">' + m_ordertype[i].ordertypedesc + '</option>');
              }
          }
          // Set Application Final status
          $('#applicationFinalStatus').find('option').not(':first').remove();
          for (i = 0; i < m_status.length; i++)
          {
              if ( m_status[i].statuscode == applstatus )
              {
                 $('#applicationFinalStatus').append('<option selected="true" value="' + m_status[i].statuscode + '">' + m_status[i].statusname + '</option>');
              }
              else
              {
                 $('#applicationFinalStatus').append('<option value="' + m_status[i].statuscode + '">' + m_status[i].statusname + '</option>');
              }
          }

          // Set Next listing purpose
          $('#nextListingPurpose').find('option').not(':first').remove();
          if (nextpurposecode != null && nextpurposecode != 0)
          {
               var purposesel = nextpurposecode;
          }
          else
          {
               var purposesel = purposecode;
          }
          for (i = 0; i < m_purpose.length; i++)
          {
              if ( m_purpose[i].purposecode == purposesel )
              {
                 $('#nextListingPurpose').append('<option selected="true" value="' + m_purpose[i].purposecode + '">' + m_purpose[i].listpurpose + '</option>');
              }
              else
              {
                 $('#nextListingPurpose').append('<option value="' + m_purpose[i].purposecode + '">' + m_purpose[i].listpurpose + '</option>');
              }
          }
          // Set Next bench type name
          $('#benchType').find('option').not(':first').remove();
          if (nextbenchtypename != null && nextbenchtypename != '')
          {
               var benchtypenamesel = nextbenchtypename;
          }
          else
          {
               var benchtypenamesel = benchtypename;
          }
          for (i = 0; i < m_benchtype.length; i++)
          {
              if ( m_benchtype[i].benchtypename == benchtypenamesel )
              {
                 $('#benchType').append('<option selected="true" value="' + m_benchtype[i].benchtypename + '">' + m_benchtype[i].benchtypename + '</option>');
              }
              else
              {
                 $('#benchType').append('<option value="' + m_benchtype[i].benchtypename + '">' + m_benchtype[i].benchtypename + '</option>');
              }
          }
          // Set Next bench (sitting bench)
          $('#bench').find('option').not(':first').remove();
          if (nextbenchcode != null && nextbenchcode != 0)
          {
               var benchcodesel = nextbenchcode;
          }
          else
          {
               var benchcodesel = benchcode;
          }
          for (i = 0; i < m_bench.length; i++)
          {
              if ( m_bench[i].benchcode == benchcodesel )
              {
                 $('#bench').append('<option selected="true" value="' + m_bench[i].benchcode + '">' + m_bench[i].judgeshortname + '</option>');
              }
              else
              {
                 $('#bench').append('<option value="' + m_bench[i].benchcode + '">' + m_bench[i].judgeshortname + '</option>');
              }
          }

          // Set Pending IA applications(Documents)
          $('#pendingIa').find('option').not(':first').remove();
          $('#iaPrayer').val('');
          $('#iaRemarks').val('');
          $('#iaStatus').find('option').not(':first').remove();
          for (i = 0; i < iapending.length; i++)
          {
              if ( i == 0 )  // If IA applications exists, then select 1st IA application and display its iaprayer(remarks) and IA status
              {
                 $('#iaPrayer').val(iapending[i].iaprayer);
                 $('#iaRemarks').val(iapending[i].remark);
                 $('#hiddenPendingIa').val(iapending[i].applicationid + ':' + iapending[i].iano);
                 $('#pendingIa').append('<option selected="true" value="' + iapending[i].applicationid + ':' + iapending[i].iano + '">' + iapending[i].iano + '</option>');
                 for (j = 0; j < m_status.length; j++)  // Set IA status drop-down list
                 {
                     if ( m_status[j].statuscode == iapending[i].iastatus )
                     {
                        $('#iaStatus').append('<option selected="true" value="' + m_status[j].statuscode + '">' + m_status[j].statusname + '</option>');
                     }
                     else
                     {
                        $('#iaStatus').append('<option value="' + m_status[j].statuscode + '">' + m_status[j].statusname + '</option>');
                     }
                 }
              }
              else
              {
                 $('#pendingIa').append('<option value="' + iapending[i].applicationid + ':' + iapending[i].iano + '">' + iapending[i].iano + '</option>');
              }

          }

          // Set post after count, radio button of day/week/month and nextdate

          var postafter = appldetail[0].postafter;
          if ( postafter != null )
          {
             $('#postAfterPeriod').val(postafter);
          }
          else
          {
            $('#postAfterPeriod').val('');
          }

          var postaftercat = appldetail[0].postaftercategory;
          if ( postaftercat == null || postaftercat == '')
          {
              postaftercat = 'd';
          }
          $('[name="dwm"]').removeAttr('checked');
          $("input[name=dwm][value='" + postaftercat + "']").prop('checked', true);

          var nextdate = appldetail[0].nextdate;
          if ( nextdate != null && nextdate != '')
          {
             $('#nextHearingDate').val(nextdate);
          }
          else
          {
             $('#nextHearingDate').val('');
          }


          // Set radio button of Business complete (Y/N)
          $('[name="business"]').removeAttr('checked');
          var business = appldetail[0].business;

             business = "Y";

          $("input[name=business][value='" + business + "']").prop('checked', true);


        } // End of : if (appldetail.length > 0)   // Set pop-ups and IA details

      }    // End of : success: function (data) for $("#applicationId").change(function()
     });  // End of : $.ajax({ for $("#applicationId, #conApplicationId").change(function()
   });   //  End of : $("#applicationId, #conApplicationId").change(function()

   // Update previous and populate selected IA details when IA drop-down changed (If more than one IA exists for single application)
   $("#pendingIa").change(function(){
      var previousId = $('#hiddenPendingIa').val();
      previousIdSplit     = previousId.split(':',2);
      var previousAppid   = previousIdSplit[0];
      var previousIano    = previousIdSplit[1];

      var changedId      = $(this).val();
      changedIdSplit     = changedId.split(':',2);
      var changedAppid   = changedIdSplit[0];
      var changedIano    = changedIdSplit[1];

      $('#hiddenPendingIa').val(changedId);

      var optag = 2;

      // Update previously selected IA
      if ( previousId != "0:0" )
      {
         var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
         $.ajax({
         type : 'POST',
         url : "ChpAjaxPost",
         dataType : "JSON",
         data : {_token: CSRF_TOKEN, optag: optag, applicationid: previousAppid, iano: previousIano, iaprayer: $('#iaPrayer').val(), iaremarks: $('#iaRemarks').val(), iastatus: $('#iaStatus').val()},
            success: function(response)
            {
              //
				}
         });
      }  // End of : if ( previousId != "0:0" )

      // Now display changed IA data
      var optag = 2;
      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {optag:optag,applicationid:changedAppid, iano:changedIano},
        success: function (iadata)
        {
          var iapending2      = iadata['iapending'];
          var m_status2       = iadata['m_status'];

          if ( iapending2.length > 0)
          {
            var iastatus = iapending2[0].iastatus;

            $('#iaPrayer').val(iapending2[0].iaprayer);
            $('#iaRemarks').val(iapending2[0].remark);
            for (j = 0; j < m_status2.length; j++)
            {
              if ( m_status2[j].statuscode == iastatus )
              {
                $('#iaStatus').append('<option selected="true" value="' + m_status2[j].statuscode + '">' + m_status2[j].statusname + '</option>');
              }
              else
              {
                $('#iaStatus').append('<option value="' + m_status2[j].statuscode + '">' + m_status2[j].statusname + '</option>');
              }
            }
          }
          else
          {
            $('#iaPrayer').val('');
            $('#iaRemarks').val('');
            $('#iaStatus').find('option').not(':first').remove();
          }

        }  // End of : success: function (data) for $("#pendingIa").change(function()
      }); // End of : $.ajax({ for $("#pendingIa").change(function()
   });  // End of : $("#pendingIa").change(function()

   // Set orderDate once orderPassed drop-down is changed
   $('#orderPassed').change(function() {
      var selectedText = $(this).find('option:selected').text().trim().toUpperCase();
      if ( selectedText != "NO ORDER" && selectedText != "ORDER PASSED")
      {
         var d = new Date();
         var month = d.getMonth()+1;
         var day = d.getDate();
         var ordDt = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
         $('#orderDate').val(ordDt);
         $('#orderDateDiv').show();
      }
      else
      {
         $('#orderDate').val('');
         $('#orderDateDiv').hide();
      }
   });  // End of : $('#orderPassed').change(function()

   // Set disposedDate once applicationFinalStatus drop-down is changed
   $('#applicationFinalStatus').change(function() {
      var selectedText = $(this).find('option:selected').text().trim().toUpperCase();
      if ( selectedText == "DISPOSED")
      {
         var d = new Date();
         var month = d.getMonth()+1;
         var day = d.getDate();
         var ordDt = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
         $('#disposedDate').val(ordDt);
         $('#disposedDiv').show();
         $('#postAfterPeriod').val('');
         $('#nextHearingDate').val('');
        // $('#postAfterPeriod').prop('required',false);
         $('#disposedYN').hide();
      }
      else
      {
         $('#disposedDate').val('');
         $('#disposedDiv').hide();
        // $('#postAfterPeriod').prop('required',true);
         $('#disposedYN').show();
      }
   });  // End of : $('#orderPassed').change(function()

   // Get next hearing date calculate after changing post after period
   $('#postAfterPeriod').change(function() {
      var dwmno  = $(this).val();
      var dwm    = $('input[name=dwm]:checked').val();

      var optag = 99;

      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {optag:optag,dwmno:dwmno,dwm:dwm},
        success: function (data)
        {
          $('#nextHearingDate').val('');
          $('#nextHearingDate').val(data[0].postdt);

        }  // End of : success: function (data) for $('#postedFor').change(function()
      });  // End of : $.ajax({ for  $('#postedFor').change(function()
    });  // End of : $('#postedFor').change(function()

    // Get next hearing date calculate after changing Days/Weeks/Months Radio button
    $('input[name="dwm"]').change(function() {
      var dwm    = $('input[name=dwm]:checked').val();
      var dwmno  = $('#postAfterPeriod').val();

      var optag = 99;

      $.ajax({
      type : 'get',
      url : "ChpAjax",
      dataType : "JSON",
      data : {optag:optag,dwmno:dwmno,dwm:dwm},
        success: function (data)
        {
          $('#nextHearingDate').val('');
          $('#nextHearingDate').val(data[0].postdt);
         }  // End of : success: function (data) for $('#postedFor').change(function()
      });  // End of : $.ajax({ for  $('#postedFor').change(function()
    });  // End of : $('#postedFor').change(function()


// Update DisplayBoard Table after clicking A or P button
$("#admitButton, #pauseButton").button().click(function(){
      var buttVal = $(this).val();
      var applicationid= $('#hiddenApplicationId').val().split(':',2);
      var appid         = applicationid[0];
   alert(appid);
      var optag = 3;
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
         $.ajax({
         type : 'POST',
         url : "ChpAjaxPost",
         dataType : "JSON",
         data : {_token: CSRF_TOKEN,optag:optag,stage:buttVal,applicationid:appid},
            success: function(response)
            {
               $("#admitPause").text(response.msg);
            }  // End of : success: function (data) for  $("#admitButton").button().click(function()
      }); // End of : $.ajax({ for  $("#admitButton").button().click(function()
   });  // End of : $("#admitButton").button().click(function(){


});  // End of : $(document).ready(function()
*/

</script>


@endsection
