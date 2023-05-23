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

<section class="content">
<form action="FilingCounterSave" method="POST">
@csrf
<div class="row">
<div class="col-md-12">

    <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h3> Filing Counter - Day account closing </h3> </td>
        </tr>

        <tr>
        <td> <label for="fromdate"> Previous account closed date </label></td>
        <td> <input type="text" name="pdate" class="form-control bg-success" id="pdate"  value="{{ date('d-m-Y', strtotime($pclosedt)) }}" readonly>
             <input type="hidden" name="prev_close_date" id="prev_close_date"  value="{{ date('d-m-Y', strtotime($pclosedt)) }}"  >

        </td>
        <td> <label for="todate"> Account closing date (Probably today's date) </label><span class="mandatory">*</span></td>
        <td> <input type="text" name="today_close_date" class="form-control pull-right datepicker" id="today_close_date" autocomplete="off"  value="{{ date('d-m-Y', strtotime($tclosedt)) }}" required> </td>
        </td>
        </tr>
        <tr>
          <td>
          <label>Opening Balance</label><br></td>
        <td>  <input type="text" name="prev_close_amt"  id="prev_close_amt"  value="{{ $pcloseamt }}" readonly >
        </td>

        </tr>

        <tr>

        <td> <label for="daycollection"> Day total collection (Rs.) </label></td>
        <td> <input type="number" name="daycollection" class="form-control" id="daycollection" value="0" size="12" maxlength="10"> </td>

        <td> <label for="feeadjust"> Any adjustment in fee amount (Rs.) </label></td>
        <td> <input type="number" name="feeadjust" class="form-control" id="feeadjust" value="0" size="12" maxlength="10"> </td>

        </tr>

        <tr>
        <td colspan="2"> <label for="closeremarks"> Closing remarks if any </label></td>
        <td colspan="2"> <input type="text" name="closeremarks" class="form-control" id="closeremarks" size="40" maxlength="150" placeholder="Remarks"> </td>
        </td>
        </tr>

        <tr>
       <td colspan="4">
       <div class="text-center">
         <input type="hidden" name="sbmt_adv" id="sbmt_adv" value="A">
            <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Save">


              <a class="btn btn-warning" href=""> Cancel </a>
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

<div class="panel panel-body">
    <div class="row">
                   <table id="myTable4" class="table table-bordered table-striped  table order-list" style="border:1px solid black;margin-left:auto;margin-right:auto;" >

                     <thead >
                       <tr style="background-color: #3c8dbc;color:#fff" >
                         <td>Account closing date</td>
                         <td>Receipt No</td>
                         <td>Application No</td>
                         <td>Mode Of payment</td>
                         <td>Amount</td>
                         <td>Name</td>
                       </tr>
                   </thead>
                   <tbody>
              <tr>

                        @foreach($receipt as $receipt)


                     <?php
                     echo "<td align=center>".($receipt->receiptuseddate ? date('d-m-Y', strtotime($receipt->receiptuseddate)) : '')."</td>";

                     ?>
                     <td>{{$receipt->receiptno}}</td>
                      <td>{{$receipt->applicationid}}</td>
                     <td>{{$receipt->modeofpayment}}</td>


                     <td>{{$receipt->amount}}</td>
                     <td>{{$receipt->name}}</td>

                     </tr>
                     @endforeach
                     <tr>
                       <td align='center' style="font-weight: bold";>Total Amount</td>

                       @foreach($receiptamount as $receiptamount)

                       <td></td>
                       <td></td>
                       <td></td>
                       <td>{{$receiptamount->tamount}}</td>
                       <td></td>


                     </tr>
                  @endforeach

                   </tbody>







                 </table>

               </div>
			   </div>
    @endsection
    <script src="js/jquery-3.4.1.js"></script>


    <script>
    $(document).ready(function() {
     $("#fromdate").datepicker({
                 format: 'dd-mm-yyyy',
                 autoclose: true,
               }).on('changeDate', function(selected) {
                 var endDate = new Date(selected.date.valueOf());
                $('#hearingDate').datepicker('setStartDate', endDate);
               }).on('clearDate', function(selected) {
                 $('#hearingDate').datepicker('setEndDate', null);
               });

    //$("#todate").datepicker( { minDate: -0,dateFormat: 'dd-mm-yyyy', maxDate: new Date(2050, 1,18) });
    //	$('#userrole').multiselect({
     //	nonSelectedText: 'Select Role'
    //	});


     $(".extraClick").click(function(){
         //console.log('hiii ');
         var judgecode   = $(this).attr('data-value');
         //console.log('hiii '+userid);
        console.log(judgecode);
         $.ajax({
           type: 'get',
           url: "getJudgeDetails",
           dataType:"JSON",
           data: {"_token": $('#token').val(),judgecode:judgecode},
           success: function (json) {

           //	console.log('inside success '+json);
             $("#sbmt_adv").val('U');
             $("#saveADV").val('Update');
             $("#judgecode").val(json[0].judgecode);
             $("#name").val(json[0].judgename);
             $("#designation").val(json[0].judgedesigcode);
             $("#judgeshortname").val(json[0].judgeshortname);
             //$("#userid").attr('readonly', true);
             $("#active").val(json[0].active);

            if( (json[0].fromdate)  == null && (json[0].todate) == null )
             {
             $("#fromdate").val(json[0].fromdate);
             $("#hearingDate").val(json[0].todate);
           }



          else

          {
             var hrdate1 = json[0].fromdate;
             var split4 = hrdate1.split('-');
             var orderdate1 = json[0].todate;
             var split5 = orderdate1.split('-');

         $("#fromdate").val(split4[2]+'-'+split4[1]+'-'+split4[0]);
         $("#hearingDate").val(split5[2]+'-'+split5[1]+'-'+split5[0]);

              }







             //$("#userrole").val(json[0][0].userlevel);
              // $("#userrole option[value='1']").attr('selected','selected');






               }
             });
       });
    });
    </script>
