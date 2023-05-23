<link href="css/bootstrap4.css" rel="stylesheet" id="bootstrap-css">
@include('layout.partials.head')

<!------ Include the above in your HEAD tag ---------->
<style>
body{
  line-height: 1;
}



  .top_layer{

    height:100%;
    width:100%;
    position: fixed;
    top:0;
    padding: 15px;
    left:0;
 background-color: black;
  color:white;

  }

@media only screen and (max-height: 657px) {
.middle_layer{
  bottom: 131px !important;
  }}

  .middle_layer{
    height:10%;
    width:100%;
    bottom: 153px;
    left: 0;
   background-color: #888a81;
    position: fixed;
  }

  .bottom_layer{
    height:20%;
    width:100%;
    bottom: 0;
    left: 0;
   background-color: #212240;
    position: fixed;
  }

  td,th {
      text-align: left;
}



tr.display_row {
   border-color: #000000;
  background-color:#29658a;
    padding: 100px;
    color:white;
        font-weight: 800;
        font-family: "Times New Roman", Times, serif;
}


tr.display_row1 {
   border-color: #000000;
  background-color:#6699cc;
    padding: 100px;
    color:black;
        font-weight: 800;
        font-family: "Times New Roman", Times, serif;
}

tr.display_row2 {
    border-color: #000000;
   background-color:#a8b5bd;
    padding: 100px;
    color:black;
        font-weight: 800;
        font-family: "Times New Roman", Times, serif;
}



#passed_cases,#ch,#list,#sl_no{


    font-weight: 800;
    color: #ff3636;

}

.font-head{

    font-size: 3vw;
}

.font-body{
    font-size: 6vw;
  //font-size:xx-large;

}



.font-footer{
    //font-size: 5vw;
  font-size: 6vw;
}



/*tr td{
padding: 0px !important;
margin: 0px !important;
}*/

tr td th {
padding: 0px !IMPORTANT;
margin: 0px !important;
}

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
line-height: 1.520071;
vertical-align: middle;
padding-right:  25px;
padding-left:  25px;

}

@media only screen and (max-height: 400px) {
 .font-head{
            font-size: 2vw;
        }
        .font-body{
            font-size: 3vw;
        }
        .font-footer{
            font-size: 3vw;
        }
}
@media only screen and (max-height: 300px) {
      .font-head{
            font-size: 1vw;
        }
        .font-body{
            font-size: 2vw;
        }
        .font-footer{
            font-size: 2vw;
        }
}
@media only screen and (max-height: 200px) {
      .font-head{
            font-size: 0.5vw;
        }
        .font-body{
            font-size: 1vw;
        }
        .font-footer{
            font-size: 1vw;
        }
}
.row {
    margin-right: 0px;
    margin-left: 0px;
}
</style>

<section class="">

  <div class="container">
     <div class="top_layer">
       <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
      <table id="ch1table" class="table no-margin table-bordered" cellpadding="10"style="font-size:380%;">
       <tr class='display_row' style="font-size:140%;">
        <td width="35%"> Court Hall - I </td>
         <td id="ch1a"> </td>
         </tr>
      
		<!--	 <tr class='display_row1' style="font-size:110%;">
          <td style="text-align:right;width: 35%;font-size:80%;">Next Case  </td>
            <td id="ch1next" style="font-size:120%;"> </td>-->
         </tr>
          <tr class='display_row2' style="font-size:110%;">
          <td style="text-align:right;width: 35%;font-size:80%;"> Passed Over  </td>
            <td id="ch1p" style="font-size:120%;"> </td>
         </tr>

     </table>
           <table id="ch2table" class="table no-margin table-bordered" style="font-size:380%;">
        <tr class='display_row'  style="font-size:140%;">
           <td width="35%"> Court Hall - II </td>
           <td id="ch2a" ></td>
         </tr>
	<!--	<tr class='display_row1' style="font-size:110%;">
          <td style="text-align:right;width: 35%;font-size:80%;"> Next Case </td>
            <td id="ch2next" style="font-size:120%;"> </td>-->
         </tr>
        <tr class='display_row2'  style="font-size:110%;text-align:right ">
            <td style="text-align:right;width: 35%;font-size:80%;"> Passed Over  </td>
            <td id="ch2p" style="font-size:120%;"> </td>
         </tr>

     </table>
      <table id="ch3table" class="table no-margin table-bordered"  cellpadding="10"  style="font-size:380%;">
        <tr class='display_row' style="font-size:140%;" >
        <td width="35%" > Court Hall - III </td>
          <td id="ch3a"></td>
         </tr>
     
		<!--   <tr class='display_row1' style="font-size:110%;">
          <td style="text-align:right;width: 35%;font-size:80%;"> Next Case </td>
            <td id="ch3next" style="font-size:120%;"> </td>
         </tr>-->
         <tr class='display_row2' style="font-size:110%;">
          <td  style="text-align:right" style="width: 35%;font-size:80%;" align="right"> Passed Over </td>
            <td id="ch3p" style="font-size:120%;"> </td>
         </tr>

     </table>
        </div>
      </div>

    </div>
  </section>
  <script src="js/jquery.min.js"></script>
  <script src="js/chp/displayboard.js"></script>

  @include('layout.partials.footer-scripts')
