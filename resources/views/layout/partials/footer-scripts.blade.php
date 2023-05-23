<!-- jQuery 3 -->
<script src="js/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="js/parsley.min.js"></script>
<script src="js/parsley.js"></script>
<script src="js/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="js/raphael.min.js"></script>
<script src="js/morris.min.js"></script>
<!-- Sparkline -->
<script src="js/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="js/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="js/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="js/moment.min.js"></script>
<script src="js/daterangepicker.js"></script>
<!-- datepicker -->
<script src="js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="js/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="js/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="js/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="js/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="js/demo.js"></script>
<script src="js/icheck.min.js"></script>
 <script src="js/sweetalert.min.js"></script>
<script src="js/parsley-fields-comparison-validators.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<script type="text/javascript">
	/*$("#Username").keypress(function(e)
	{
		if(this.value.length>15)
		{
			return false;
		}
	})*/
  $("#hearingDate").datepicker({
    autoclose: true,
//startDate: "01-01-2000",
format: 'dd-mm-yyyy'
  })
  $("#nextHrDate").datepicker({
autoclose: true,
//startDate: "01-01-2000",
format: 'dd-mm-yyyy'
});

$('.datepicker').datepicker({
autoclose: true,
startDate: "01-01-1900",
endDate: '+0d',
format: 'dd-mm-yyyy'
});
//disable zero
$(".zero").keypress(function(e){
if (this.value.length == 0 && e.which == 48 ){
      return false;
   }
})
$(".number").keypress(function(e){
if (event.which === 43||event.which === 45||event.which === 46) {
    event.preventDefault();
  }
})
$("#rDistrict").change(function(){
	var distCode = $(this).val();

	$.ajax({
        type: 'post',
        url: 'getTaluk',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),distCode:distCode},
        success: function (data) {
        $('#rTaluk').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].talukcode+'">'+data[i].talukname+'</option>';
  						$('#rTaluk').append(option);
				 }
        	}
        });
})
$("#resDistrict").change(function(){
	var distCode = $(this).val();

	$.ajax({
        type: 'post',
        url: 'getTaluk',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),distCode:distCode},
        success: function (data) {
        $('#resTaluk').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].talukcode+'">'+data[i].talukname+'</option>';
  						$('#resTaluk').append(option);
				 }
        	}
        });
})
$("#districtAppl").change(function(){
	var distCode = $(this).val();

	$.ajax({
        type: 'post',
        url: 'getTaluk',
        dataType:'JSON',
        data:  { "_token": $('#token').val(),distCode:distCode},
        success: function (data) {
        $('#talukAppl').empty();
				 for(var i=0;i<data.length;i++){
				 	 var option = '<option value="'+data[i].talukcode+'">'+data[i].talukname+'</option>';
  						$('#talukAppl').append(option);
				 }
        	}
        });
})
</script>
<script>
   $(function () {
  $('#loginForm').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
    $('.bs-callout-info').toggleClass('hidden', !ok);
    $('.bs-callout-warning').toggleClass('hidden', ok);
  })
  .on('form:submit', function() {
    return false; // Don't submit form for this demo
  });
});

</script>
