
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

   {{--  @foreach($modules_options as $modules) --}}


    <ul class="sidebar-menu" >
         <li class="treeview">
          <a href="dashboard">
            </i> <span>Dashboard</span>
          </a>
        </li>
      </ul>
      <ul class="sidebar-menu" data-widget="tree">
         <li class="treeview">
          <a href="#">
            </i> <span>Legacy</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

          <ul class="treeview-menu">
            <li class=""><a href="case"></i>Application</a></li>
             <li class=""><a href="IADocument"></i>Hearing History</a></li>
              <li class=""><a href="ConnectedApplication"></i>Connected Application</a></li>

          </ul>

        </li>
      </ul>
      <ul class="sidebar-menu" data-widget="tree">
         <li class="treeview">
          <a href="#">
            </i> <span>Application</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

          <ul class="treeview-menu">
            <li class=""><a href="ExtraAdvocate"></i>Extra Advocate</a></li>
              <li class=""><a href="ARStatus"></i>A/R Status</a></li>
              <li class=""><a href="Groupno"></i>Additional No</a></li>
                <li class=""><a href="/searchapplication">Search Application</a></li>
          </ul>

        </li>
      </ul>
       <ul class="sidebar-menu" data-widget="tree">
         <li class="treeview">
          <a href="#">
            </i> <span>Cause List</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

          <ul class="treeview-menu">
            <li class=""><a href="Causelist"></i>Prepare Causelist</a></li>
             <li class=""><a href="CauselistDated"></i>Publish Causelist</a></li>
          </ul>

        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
