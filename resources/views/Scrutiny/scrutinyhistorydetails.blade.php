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
          <h7 >Details for Scrutiny History Details</h7>
        </div>


        @include('flash-message')
              <h3 class="header">Scrutiny  History of <?php echo $applicationid; ?>  for <?php echo $scrutinydate; ?> </h3><br>
                <table class="table table-bordered table-striped table-hover" style="border: 2px solid black; width:90%;">
                 <thead  style="background-color: #3c8dbc;color:#fff;">
                          <tr>

                            <th class="col-xs-2" >Application ID </th>
                            <th class="col-xs-2" >Observation  </th>
                            <th class="col-xs-1" >Objections</th>
                            <th class="col-xs-1">Remarks</th>
                        </tr>
                </thead>

                @foreach ($result as $results)
                <tbody>
                  <tr>

                    <td >{{ $results->applicationid }}</td>
                    <td >{{ $results->observation}}</td>
                    <td >{{ $results->chklistdesc}}</td>
                    <td >{{ $results->remarks}}</td>



                  </tr>
              </tbody>
              @endforeach
          </table>






<!-- /.tab-pane -->
<script src="js/jquery.min.js"></script>

</section>
@endsection
