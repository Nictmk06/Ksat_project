<link href="css/bootstrap4.css" rel="stylesheet" id="bootstrap-css">
@include('layout.partials.head')

<!------ Include the above in your HEAD tag ---------->
<style type="text/css">
#fontSizeWrapper { font-size: 16px; }

#fontSize {
  width: 100px;
  font-size: 1em;
  }

/* ————————————————————–
  Tree core styles
*/
.tree { margin: 1em; }

.tree input {
  position: absolute;
  clip: rect(0, 0, 0, 0);
  }

.tree input ~ ul { display: none; }

.tree input:checked ~ ul { display: block; }

/* ————————————————————–
  Tree rows
*/
.tree li {
  line-height: 1.2;
  position: relative;
  padding: 0 0 1em 1em;
  }

.tree ul li { padding: 1em 0 0 1em; }

.tree > li:last-child { padding-bottom: 0; }

/* ————————————————————–
  Tree labels
*/
.tree_label {
  position: relative;
  display: inline-block;
  background: #fff;
  }

label.tree_label { cursor: pointer; }

label.tree_label:hover { color: #666; }

/* ————————————————————–
  Tree expanded icon
*/
label.tree_label:before {
  background: #000;
  color: #fff;
  position: relative;
  z-index: 1;
  float: left;
  margin: 0 1em 0 -2em;
  width: 1em;
  height: 1em;
  border-radius: 1em;
  content: '+';
  text-align: center;
  line-height: .9em;
  }

:checked ~ label.tree_label:before { content: '–'; }

/* ————————————————————–
  Tree branches
*/
.tree li:before {
  position: absolute;
  top: 0;
  bottom: 0;
  left: -.5em;
  display: block;
  width: 0;
  border-left: 1px solid #777;
  content: "";
  }

.tree_label:after {
  position: absolute;
  top: 0;
  left: -1.5em;
  display: block;
  height: 0.5em;
  width: 1em;
  border-bottom: 1px solid #777;
  border-left: 1px solid #777;
  border-radius: 0 0 0 .3em;
  content: '';
  }

label.tree_label:after { border-bottom: 0; }

:checked ~ label.tree_label:after {
  border-radius: 0 .3em 0 0;
  border-top: 1px solid #777;
  border-right: 1px solid #777;
  border-bottom: 0;
  border-left: 0;
  bottom: 0;
  top: 0.5em;
  height: auto;
  }

.tree li:last-child:before {
  height: 1em;
  bottom: auto;
  }

.tree > li:last-child:before { display: none; }

.tree_custom {
  display: block;
  background: #eee;
  padding: 1em;
  border-radius: 0.3em;
}

.widget-selected{
  color: red;
}

h1{
  font-weight: bold;
}
@keyframes textclip {
  to {
    background-position: 200% center;
  }
}
.circular_image {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  overflow: hidden;
  background-color: blue;
  /* commented for demo
  float: left;
  margin-left: 125px;
  margin-top: 20px;
  */

  /*for demo*/
  display:inline-block;
  vertical-align:middle;
}
.circular_image img{
  width:100%;
}
.login-block{
background: #DE6262;  /* fallback for old browsers */
background: -webkit-linear-gradient(to bottom, #FFB88C, #DE6262);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(#61c9f1, #0d6fd8); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
float:left;
width:100%;
padding : 24px 0;
height: 100%
}
.banner-sec{background:url()  no-repeat left bottom; background-size:cover; height:400px; border-radius: 0 10px 10px 0; padding:0;}
.container{height:400px;background:#fff; border-radius: 10px; box-shadow:0px 20px 0px rgba(0,0,0,0.1);}
.carousel-inner{border-radius:0 10px 10px 0;}
.carousel-caption{text-align:left; left:5%;}
.login-sec1{padding: 0 30px; position:relative;height: 530px;}
.login-sec1 .copy-text{position:absolute; width:100%; bottom:20px; font-size:13px; text-align:center;}
.login-sec1 .copy-text i{color:#FEB58A;}
.login-sec1 .copy-text a{color:#E36262;}
.login-sec1 h2{margin-bottom:30px; font-weight:500; font-size:26px; color: #6e9da9;}
.login-sec1 h2:after{content:" "; width:100px; height:5px; background:; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}

.login-sec{padding: 50px 30px; position:relative;height: 530px;}
.login-sec .copy-text{position:absolute; width:100%; bottom:20px; font-size:13px; text-align:center;}
.login-sec .copy-text i{color:#FEB58A;}
.login-sec .copy-text a{color:#E36262;}
.login-sec h2{margin-bottom:30px; font-weight:500; font-size:26px; color: #6e9da9;}
.login-sec h2:after{content:" "; width:100px; height:5px; background:; display:block; margin-top:20px; border-radius:3px; margin-left:auto;margin-right:auto}
.btn-login{background: #6e9da9; color:#fff; font-weight:600;}
.banner-text{width:70%; position:absolute; bottom:40px; padding-left:20px;}
.banner-text h2{color:#fff; font-weight:600;}
.banner-text h2:after{content:" "; width:100px; height:5px; background:#FFF; display:block; margin-top:20px; border-radius:3px;}
.banner-text p{color:#fff;}
.img-fluid {
    width: 100%;
    height: 100%;
}
 * {
            margin: 0;
            padding: 0;
        }
        .imgbox {
            display: grid;
            background-color: #f0d219;

        }
        .center-fit {
            max-width: 100%;
                max-height: 71px;
            margin: auto;
        }
        .parsley-errors-list
{
   margin-left: 0px !important;
}

</style>
<section>
  {{-- <div class="imgbox" >
    <div class="col-md-12">
      <img class="center-fit" width="100%" src=" images/Ksat_title.png " >
   </div>
  </div> --}}
</section>
<section class="login-block">






  <div class="row">

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img align="left" class="national_emblem" src="images/KSAT Logo-modified (1).png" alt="devatha">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<h1 style="text-shadow: 1px 1px 0 white;font-size:45px;" align="left">KSAT (Karnataka State Administrative Tribunal)
  </h1>



  </div>

<br>
  <div class="container">



    <div class="row">
      <div class="col-md-4 login-sec1">

<h3 style="color:#6e9da9;">Case Management System </h3>
      
<ul class="tree">
  <li>
    <input type="checkbox" checked disabled id="c50" />
    <label class="tree_label" for="c50">Modules</label>
     <ul>
       <li><span class="tree_label">Application</span></li>
       <li><span class="tree_label">Scrutiny</span></li>
       <li><span class="tree_label">CauseList</span></li>
       <li><span class="tree_label">CourtHall Proceedings</span></li>
       <li><span class="tree_label">Judgment</span></li>
       <li><span class="tree_label">Display Board</span></li>
       <li><span class="tree_label">Caveat</span></li>
       <li><span class="tree_label">Certified Copy Application</span></li>
       <li><span class="tree_label">SMS Services</span></li>
       <li><span class="tree_label">MIS Reports</span></li>
       
    </ul>
  </li>
</ul>


      </div>
      <div class="col-md-4 banner-sec">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
          {{--   <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li> --}}
          </ol>

          <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
              <img class="d-block img-fluid" src="images/ksatimg1.jpg" alt="">
              <div class="carousel-caption d-none d-md-block">
                <div class="banner-text">

                </div>
              </div>
            </div>
            <div class="carousel-item">
              <img class="d-block img-fluid" src="images/img1.jpg" alt="First slide" >
              <div class="carousel-caption d-none d-md-block">
                <div class="banner-text">

                </div>
              </div>
            </div>

          </div>

        </div>
      </div>

       <div class="col-md-4 login-sec1">


        <h2 style="color:#6e9da9;"class="text-center">Login </h2>
		<form role="form" id="loginForm1" action="/login" method="POST" data-parsley-validate>

      <!-- <form action="/login" method="post" data-parsley-validate>-->
       <input type="hidden" name="_token" value="{{ csrf_token() }}">
     <!--  <div class="col-md-12">
          <div class="form-group">
          <label for="exampleInputEmail1">Establishment</label>
            <select name="establishment"  class="form-control" data-parsley-required data-parsley-required-message="Select Establishment" style="height:34px" data-parsley-trigger='focus'>
              <option value="">Select Establishment</option>
              @foreach($establishments as $establish)
              <?php if($establish->defaultdisplay=='Y'){

              ?>
              <option value="{{$establish->establishcode}}" selected>{{$establish->establishname}}</option>
              <?php }else  { ?>
                <option value="{{$establish->establishcode}}">{{$establish->establishname}}</option>
            <?php   } ?>
              @endforeach
            </select>
          </div>
        </div>-->
        <div class="col-md-12">
          <div class="form-group">
            <label for="exampleInputEmail1" >Username</label>
            <input type="text" id="Username" name="userName" class="form-control zero" data-parsley-required data-parsley-required-message="Enter Username"  data-parsley-pattern="/[a-zA-Z0-9]+$/" data-parsley-pattern-message="Invalid Username" data-parsley-trigger='keypress'   maxlength=15>
          </div>
        </div>
        <div class="col-md-12">
           <div class="form-group">
            <label for="exampleInputPassword1" >Password</label>
             <input type="password" name="userPassword" id="userPassword" class="form-control" placeholder="Password"  data-parsley-required data-parsley-required-message='Enter Password'  value='' data-parsley-trigger='keypress'>
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-check">

            <button type="submit" class="btn btn-login float-right">Login</button>
          </div>
        </div>

         <!-- <div class="col-md-12">
          <div class="form-check">

            <button type="submit"  class="btn btn-login float-right">Submit</button>
          </div>
        </div> -->

        </form>
        @if($errors->any())
       <h5 style="color:red;">{{$errors->first()}}</h5>
      @endif
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 text-center">
    <br><br><br>
     <img  align="right" style="width:100px;height:40px;left:1172;position:absolute;"
       src="images/nic.png" alt="devatha">
    </div>
  </div>
     <script src="js/jquery-3.4.1.js"></script>
       <script src="js/jquery.min.js"></script>
  <script src="js/user/sha512.js"></script>


<script>

$('#loginForm1').submit(function(e)
   {
	  var userPassword = $('#userPassword').val();
	   var passwordhash = CryptoJS.SHA512(userPassword);
       $('#userPassword').val(passwordhash);
   alert(passwordhash);
   //   alert($('#userPassword').val());
	 $('#loginForm1').submit();

	})

</script>
<script>
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}



function setFontSize(el) {
    var fontSize = el.val();
    
    if ( isNumber(fontSize) && fontSize >= 0.5 ) {
      $('body').css({ fontSize: fontSize + 'em' });
    } else if ( fontSize ) {
      el.val('1');
      $('body').css({ fontSize: '1em' });  
    }
}

$(function() {
  
  $('#fontSize')
    .bind('change', function(){ setFontSize($(this)); })
    .bind('keyup', function(e){
      if (e.keyCode == 27) {
        $(this).val('1');
        $('body').css({ fontSize: '1em' });  
      } else {
        setFontSize($(this));
      }
    });
  
  $(window)
    .bind('keyup', function(e){
      if (e.keyCode == 27) {
        $('#fontSize').val('1');
        $('body').css({ fontSize: '1em' });  
      }
    });
  
});
</script>
  </section>


  @include('layout.partials.footer-scripts')
