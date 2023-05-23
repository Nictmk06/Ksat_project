<!DOCTYPE html>
<html>
<head >
  <style>
table.table-bordered > tbody > tr > td{
    border:1px solid black;
    background-color: #ccd9ff;
    font-size: 16px;
    font-family:Times new roman;

}

  </style>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.min.js"></script>
</head>
<body>


<br/>

@if(!empty($hearingdetails))
<table id="dailyStatus" class="table table-bordered table-condensed " style="width: 100%;  border:1px solid black; background-color: #ccd9ff;font-size: 16px; font-family:Times new roman;" >
  <tbody>
    <tr>
      <td colspan="5" style ="text-align:center; border:1px solid black;background-color: #ccd9ff;font-size: 16px; line-height:30px ;font-weight: bold;">
            <p  style="font-size: 20px;font-weight: bold;text-align: center;" > {{$ename}}</p>
             List of Applications disposed on:{{$hearingdate}}</td>
    </tr>
        <tr style="text-align: left">
          <td>
              <table style=" border:1px ;background-color: #ccd9ff; font-size: 16px; font-family:Times new roman; text-align:left;">
                  <tbody>
                      <tr style="border:0px; background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: left;">
                          <td colspan="5" style=" border:0px ; background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman; text-align: left; line-height: 30px;">
                              <b>Total Applications Posted :</b>
                              @if(!empty($case_posted))
                                  {{ $case_posted[0]->caseposted}}
                              @endif
                              @if(!empty($status[0]->status_not_updated))
                                  <b>&nbsp;(Business not updated): </b>{{$status[0]->status_not_updated}}
                              @endif
                              @if(!empty($case_posted_connected[0]->con_caseposted))
                                       &nbsp;<b>&nbsp;(Connected): </b>{{$case_posted_connected[0]->con_caseposted}}
                              @endif
                        </td>
                        </tr>

                        <tr style="border:0px; background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: left;">
                         <td  colspan ="5" style="border:0px; background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: left; line-height: 30px;">
                           <b>Applcations Disposed : </b>
                             @if(!empty($case_disposed))
                                 {{$case_disposed [0]->totcasedisposed}}&emsp;&emsp;&emsp;&emsp;
                             @endif<b>IA disposed:</b>
                             @if(!empty($IAcount))
                               {{$IAcount[0]->iacount}}
                             @endif
                           </td>
                         </tr>

                         <tr style="border:0px; background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: left">
                             <td style="border:0px; background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: left;line-height: 30px;" >
                               @if(!empty($case_des))
                               {{$case_des[0]->order_string1}}  @endif</td>
                           </tr>



        <!--------------Table for application disposed  ------------>
       @if(!empty($jud_uploaded ))
       <table  class="table table-bordered table-condensed" style="width: 100%; border:1px solid black;background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: center;">
                <tbody>
              <tr>
                <td style="border:1px solid black;background-color: #ccd9ff; font-size: 15px;  font-family:Times new roman;font-weight: bold;text-align: center;line-height: 25px;">Sl No</td>
                <td style="border:1px solid black;background-color: #ccd9ff; font-size: 15px;  font-family:Times new roman;text-align: center;font-weight: bold;line-height: 25px;">Application No</td>
                <td style="border:1px solid black;background-color: #ccd9ff; font-size: 15px;  font-family:Times new roman;font-weight: bold;text-align: center;line-height: 25px;">Date of Registration</td>
                <td style="border:1px solid black;background-color: #ccd9ff; font-size: 15px;  font-family:Times new roman;font-weight: bold;text-align: center;line-height: 25px;">Petitioner/Respondent</td>
                <td style="border:1px solid black;background-color: #ccd9ff; font-size: 15px;  font-family:Times new roman;font-weight: bold;text-align: center;line-height: 25px;">Disposal Type</td>



              </tr>

                  @foreach($jud_uploaded as $ju)

                  <tr>
                  <td style="border:1px solid black;background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: center;line-height: 25px;">{{$ju->srno}}</td>
                  <td style="border:1px solid black;background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: left;line-height: 25px;">{{$ju->applicationid}}</td>

                   <td style= "border:1px solid black;background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: center;line-height: 25px;">{{$ju->registerdate}}</td>
                  <td style=" border:1px solid black;background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: left;line-height: 25px;">{{$ju->pet_res}}</td>
                  <td style="border:1px solid black;background-color: #ccd9ff; font-size: 16px;  font-family:Times new roman;text-align: center;line-height: 25px;">{{$ju->ordertypedesc}}</td>
                 </tr>

                @endforeach
               </tbody>

          </table>
       @endif



      </tbody>

    </table> <!--first table end here -->

      @endif
    </tbody>

    </table> <!-- inner table in first table row end here -->

    </td>
  </tr>   <!--first table row -->



          <!-- <br/> -->
          <!---------Table for application reserved for order ----------->
        @if(!empty($res_for_order))
        <table class="table table-bordered table-condensed" style="width: 100%; border:1px solid black;background-color: #d9b3ff; text-align: center;">
               <tr style="font-size: 1px; font-weight: bold;border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman; text-align: center;"><td colspan="6" style="font-size: 16px; font-weight: bold;border:1px solid black;background-color: #d9b3ff; font-family:Times new roman; text-align: center;line-height: 30px">Pending Applications marked as reserve for order</td></tr>
               <tr>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;font-weight: bold;line-height: 25px;">Sl No</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;font-weight: bold;line-height: 25px;">Application No</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;font-weight: bold;line-height: 25px;">Bench</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;font-weight: bold;line-height: 25px;">Date of Registration</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;font-weight: bold;line-height: 25px;">Last heard</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;font-weight: bold;line-height: 25px;">Pending days</td>





               @foreach($res_for_order as $rs)

               <tr><td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;line-height: 25px;">{{$loop->iteration}} </td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align: left;line-height: 25px;">{{$rs->applicationid}}</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;line-height: 25px;">{{$rs->judgeshortname}}</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;line-height: 25px;">{{$rs->registerdate}}</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;line-height: 25px;">{{$rs->last_her_date}}</td>
               <td style= "border:1px solid black;background-color: #d9b3ff; font-size: 16px;  font-family:Times new roman;text-align:center;line-height: 25px;">{{$rs->pending_d}}</td>



               </tr>

               @endforeach

             </table>
           @endif



          <!---------Table for application disposed judgment not uploaded------------>
         <table  class="table table-bordered table-condensed" style="width: 100%; line-height: 18px;">
         <tbody>
         <tr ><td  colspan="7" style="border:1px solid black;background-color: #ffe6e6; font-size: 16px;  font-family:Times new roman;font-weight: bold;text-align: center; line-height: 30px;">Disposed Applications where judgment not uploaded as on :{{$hearingdate}}</td>  </tr>
          <tr style="border:1px solid black;background-color: #ffe6e6; font-size: 16px;  font-family:Times new roman;font-weight: bold;text-align: center;">
            <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px;  font-family:Times new roman;font-weight: bold;text-align: center;line-height: 25px;">Sl No</td>
            <td style="border:1px solid black;background-color: #ffe6e6;text-align: center; font-size: 15px;  font-family:Times new roman;font-weight: bold;line-height: 25px;">Application No</td>
            <td style="border:1px solid black;background-color: #ffe6e6; text-align: center;font-size: 15px;  font-family:Times new roman;font-weight: bold;line-height: 25px;">Bench</td>
            <td style="border:1px solid black;background-color: #ffe6e6;text-align: center; font-size: 15px;  font-family:Times new roman;font-weight: bold;line-height: 25px;">Date of Decision </td>
            <td style="border:1px solid black;background-color: #ffe6e6;text-align: center; font-size: 15px;  font-family:Times new roman;font-weight: bold;">Pending days</td>
            <td style="border:1px solid black;background-color: #ffe6e6;text-align: center; font-size: 15px;  font-family:Times new roman;font-weight: bold;line-height: 25px;">Disposal Type</td>
            <td style="border:1px solid black;background-color: #ffe6e6;text-align: center; font-size: 15px;  font-family:Times new roman;font-weight: bold;line-height: 25px;">Author By</td>





          </tr>
             @if(!empty($jud_not_uploaded_table))
              @foreach($jud_not_uploaded_table as $jt)
                <tr>
              <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px;  font-family:Times new roman;font-weight: normal;text-align: center;line-height: 25px;">{{$loop->iteration}}</td>
              <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px; text-align: left; font-family:Times new roman;font-weight: normal;line-height: 25px;">{{$jt->applicationid}}</td>
               <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px;  font-family:Times new roman;font-weight: normal;text-align: center;line-height: 25px;">{{$jt->judgeshortname}}</td>
               <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px; text-align: center; font-family:Times new roman;font-weight: normal;line-height: 25px;">{{$jt->disposeddate}}</td>
              <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px; text-align: center; font-family:Times new roman;font-weight: normal;line-height: 25px;">{{$jt->pending}}</td>
              <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px;  text-align: center;font-family:Times new roman;font-weight: normal;line-height: 25px;">{{$jt->ordertypedesc}}</td>
              <td style="border:1px solid black;background-color: #ffe6e6; font-size: 16px;  text-align: center;font-family:Times new roman;font-weight: normal;line-height: 25px;">{{$jt->jshortname}}</td>


            </tr>

            @endforeach
            @endif
           </tbody>

         </table>







</body>

</html>
