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
<br> <br>
<div class="container">

<form action="showApplicationScrutiny" method="POST" data-parsley-validate>
@csrf
<div class="row">
<div class="col-md-10">
	  <table class="table no-margin table-bordered">

        <tr>
        <td  class="bg-primary text-center" colspan="4"> <h7> Details of Application </h7> </td>
        </tr>
</table>
     <table class="table no-margin table-bordered">
       <tr>
     		<td> <span class="mandatory">*</span> <label for="applTitle">Type of Application</label> </td>
           <td>
              	 <select class="form-control" name="applicationType" id="applicationType"  >
                  <option value="">Select Application Type</option>
                        @foreach($applicationType as $applType)

                        <option value="{{$applType->appltypecode.'-'.$applType->appltypeshort}}">{{$applType->appltypedesc.'-'.$applType->appltypeshort}}</option>

                        @endforeach
                      </select>
              </td>

     		<td> <span class="mandatory">*</span> <label for="applTitle">Application Number
              <td>
              	 <input type="text" name="applicationNo" id="applicationNo" class="form-control"  data-parsley-required data-parsley-required-message="Enter Application Number" data-parsley-pattern="/^[0-9\/]+$/"  data-parsley-trigger='keypress' maxlength="100" placeholder=" Application Number" >
              </td>
            </tr>

        <tr>
        <td colspan="4">
        <div class="text-center">

             <input type="submit" id="saveADV" class="btn btn-primary btnSearch  btn-md center-block" Style="width: 100px;" value="Submit">
               <a class="btn btn-danger btn-md center-block btnClear" href=""> Cancel </a>
        </div>

        </td>
        </tr>
    </table>

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

<script src="js/jquery-3.4.1.js"></script>

</script>

@endsection
