
<meta http-equiv="Content-Security-Policy" content="default-src 'self'">

<meta content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff" http-equiv="Content-Type" />

<meta http-equiv="X-XSS-Protection" content="0">


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
            <div style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;color:black;">
                <div class="row">
					<center>
                        <h1><b>Apply Watermark to PDF</b></h1> 
					</center>
                </div>
			</div>
			<div class="content-wrapper">
				<form method="post" action="printW" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="file" value="Choose File" name="filename" id="filename" required/>
					<input type="submit" class="btn btn-info" value="Apply" id="watermark" />
				</form>
			</div>
		</div>
	</div>
</div>
</div>

