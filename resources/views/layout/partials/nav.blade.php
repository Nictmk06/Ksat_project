
 <header class="main-header">

    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>CIS</b></span>

      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>CIS</b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
    <h4 style="color:#fff;padding-left: 79px;
    padding-top: 8px;
    margin-bottom: -34px;">


    {{ session::get('establishfullname') }}</h4>
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>


      <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">
           {{--  @foreach($userName as $user) --}}


          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs" id='username'><img  align="right"
                style="width:100px;height:25px;"
                src="images/nic.png" alt="devatha">{{ Session()->get('username') }}&nbsp;</span>
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Footer-->
              <ul class="user-footer">
                {{-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> --}}
                <div class="pull-right">
               <!--   <a href="changePassword" class="btn btn-default btn-flat">Change Password</a> 
                  <a href='logout" class="btn btn-default btn-flat">Sign out</a> -->
                 <a href="changePassword" class="btn btn-default btn-flat">Change Password</a> 
                  <a href="logout" class="btn btn-default btn-flat">Sign out</a> 

                </div>
              </ul>
              {{--  @endforeach --}}
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>
