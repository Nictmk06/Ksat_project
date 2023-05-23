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
  </style>
  <?php error_reporting(0);
  ini_set('display_errors', 0);
  ?>
  @include('flash-message')

  <section class="content">

    <form id="JudgementByApplNoForm"  method="post" action="" data-parsley-validate>


     <div class="Container" id="searchcontent">

     <div class="row table-responsive">
      <div class="col-md-offset-1 col-md-9 ">

<center>
     <table style="width:100%">
       <div class="panel panel-heading">
         <h5 >DELETE Judgement</h5>
       </div>


  <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
<tr>
<div class="col-md-4">
  <td>
      <label>Application Type<span class="mandatory">*</span></label>
       <select name="apptype" id="apptype" class="form-control" data-parsley-required  data-parsley-required-message="Select Type">
               <option value="" >Select Type</option>
                      @foreach($applications as $application)
                     <option value="{{$application->appltypedesc.'-'.$application->appltypeshort}}">
                      {{$application->appltypedisplay}}</option>
                     @endforeach
                  </select>
                </td>
                    </div>
</tr>


                  <tr>  <div class="col-md-4">
                      <td>
                          <label>Application Number<span class="mandatory">*</span></label>
                          <input type="number" class="form-control pull-right number zero" id="appnum" name="appnum" align="right" style="width: 8em" onkeyup="this.value = minmax1(this.value, 1, 40000)" placeholder="application no" class="" >
                      </td>
                    </div> </tr>
<tr>
       <div class="col-md-4">
       <td>

      <label>Application year<span class="mandatory">*</span></label>
    	<input type="number" class="form-control pull-right number zero" id="applyear" name="applyear" align="right" style="width: 7em" onkeyup="this.value = minmax(this.value, 1950, 2040)" placeholder="year" data-parsley-required data-parsley-required-message="Select year">
    </td>
       </div>
     </tr>


<br><br>
    <tr>
    <td colspan="4" style="text-align:center;">
    	<input type="button" class="btn btn-primary" name="searchJudgementByApplNo" id="searchJudgementByApplNo" value="Show Judgment" /> <input type="button"  class="btn btn-primary btnClear" name="button" value="Reset" />
    </td>
    </tr>
    </table>
</center>
<br><br>
     <div class="" id="myTablediv"  style="display: none" >
    <table  class="table table-bordered table-striped  table order-list"   id="myTable" style="width:100% ">
    <thead>
     <tr>
      <th> Application No. </td>
         <th>Subject</td>
    	  <th> Applicant Name </td>
    	  <th> Respondent Name </td>

         <th>Judgment Date</td>
         <th> Judgment </td>
           <th> Delete Judgment </td>


    	 </thead>
     <tbody>

       </tbody>

     </table>
    </div>

    </div>
    </div>



  </div>
    </form>

  </section>


<script src="js/jquery.min.js"></script>

<script src="js/judgement/searchJudgement.js"></script>
@endsection
