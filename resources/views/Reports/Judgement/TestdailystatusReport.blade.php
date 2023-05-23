<!DOCTYPE html>
<html>
<head >
  <style> 

table,tr,th,td{
    border-color: black;
     border:1px solid black;
     background-color: #FFFFEB;
      line-height: 15px;
    font-weight: normal;
    text-align: center;
    font-family: courier;
    table-layout:fixed;

    }    
   
    p{

    }
  </style>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.min.js"></script>
 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).ready(function() {
  
swal({
                  title: "{{$alert_msg}}",
                  icon: "{{$status}}",
                  buttons:[
                      'Cancel',
                      'OK'
                      ],

                      }).then(function() {
                         window.location.href = "/dashboardmain";   
                      })
;

});
</script>

</head>

<body>     
             

         
          
         </body>

</html>
          
 
      
              

