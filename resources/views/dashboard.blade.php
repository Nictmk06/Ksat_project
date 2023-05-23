@extends('layout.mainlayout')
@section('content')
 
<style type="text/css">
  .tableBodyScroll tbody {
  display: block;
  max-height: 300px;
  overflow-y: scroll;
}

.tableBodyScroll thead,
tbody tr {
  display: table;
  width: 100%;
  table-layout: fixed;
}

.tableBodyScroll1 thead,
tbody tr {
  display: table;
  width: 100%;
}

  .tableBodyScroll1  tbody {
     display: block;
    max-height: 500px;
     width: 100%;
  overflow-y: scroll;
}
</style>


<!-- <div class="panel panel-heading origndiv">
//<h7 >Causelist Applications </h7>
</div>-->

 <div  class="content-wrapper"  > 
<section class="content"  > 
<div class="panel  panel-primary">
  
<div class='panel panel-body divstyle'  overflow: auto;>

   <div class="col-md-8" >
		<div class="box box-info" >
            <div class="box-header with-border">
              <h6 class="box-title">Application entered status</h6>
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive" >
                <table class="table no-margin tableBodyScroll">
                  <thead>
                  <tr>
                    <th>User</th>
                    <th>Entered On</th>
                    <th>Entered Applications</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center"> Click on name to view the entered applications. </td> </tr>
                    <input name="_token" type="hidden" id="token" value="{{ csrf_token() }}"/>
                      <?php $i = 1; ?>
                  	@foreach($userAppDet as $userapp)
                  
                  <tr>
                     
                    <td> <a href="#" id="userclick{{$i}}" data-value="{{$userapp->username}}"
                     onclick=showapplication({{$i}})> {{$userapp->username}}   </a> 
                     </td>
                    <?php $i = $i + 1; if($userapp->entered_on==null){?>

                     <td>---</td>
                    <?php }
                    else
					{
						?> 
                      <td>{{date('d-m-Y',strtotime($userapp->entered_on))}}</td>
                    <?php 
                	}
                	?>
                    <td>{{$userapp->application_entered}}</td>
                    
                  </tr>
                 @endforeach
                  </tbody>
                </table>

              </div>
              <!-- /.table-responsive -->
              <br>
                 <table id="appltab"  border="1px solid blue" class="table tableBodyScroll1 ">
                  <thead>
                  </thead>

                  <tbody>

                  </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
    </div>
</div>
</div>
</section>
</div> 





 <script type="text/javascript">

function showapplication(i)
{
  
  var a = '#userclick'+i;
  var $user = $(a).attr('data-value');



      $.ajax({

        type: 'post',
        url: "getApplicationbyuser",
        dataType:"JSON",
        data: {"_token": $('#token').val(),$user},
        success: function (json) {
            
       
            $('#appltab').find('tbody tr').remove();
 
    var row = $('<tr>');
  
    row.append('<td style="font-weight:bold">' + 'Applications entered by the user : '+ $user + '</td>'); 
    row.appendTo('#appltab');

row = $('<tr>');
 
  row.append('<td>' +' Application Number '+  '</td>');
  row.append('<td>' + 'Entered On' + '</td>');
  row.append('<td>' + 'Total Applicants ' + '</td> </tr>');
row.append('<td>' + 'Registered on ' + '</td> </tr>');


row.appendTo('#appltab');
  
  
  $.each(json, function(index, obj) {
 row = $('<tr>');
  
 
  row.append('<td>' +obj.applicationid+  '</td>');
  row.append('<td>' +obj.createdon + '</td>');
  row.append('<td>' +obj.applicantcount + '</td> </tr>');
  row.append('<td>' +obj.registerdate + '</td> </tr>');

row.appendTo('#appltab');

  
  
  });



                   }
});
      

  // console.log($("#userclick").value());
}

</script>
@endsection