<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ url('/photos/logo.png') }}" alt="AdminLTE Logo" class="img-circle elevation-3" style="opacity: .8; width: 40px; height: 40px;">
      <span class="brand-text font-weight-light">CRSystem</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-2 mb-3 d-flex">
        <div class="image">
          <img src="{{ url('/photos/userLogin.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{$user->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
              <a href="{{url('/admin/dashboard')}}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

          <li class="nav-item">
            <a href="{{url('/admin/today-schedule')}}" class="nav-link {{ request()->is('admin/today-schedule') ? 'active' : '' }}">
              <i class="nav-icon far fa-calendar"></i>
              <p>
                Weekly Schedule
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/history')}}" class="nav-link {{ request()->is('admin/history') ? 'active' : '' }}">
              <i class="nav-icon fas fa-address-book"></i>
              <p>
                Reservations
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/doctors')}}" class="nav-link {{ request()->is('admin/doctors') ? 'active' : '' }}">
              <i class="nav-icon fas fa-address-card"></i>
              <p>
                Doctor's List
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/list')}}" class="nav-link {{ request()->is('admin/list') ? 'active' : '' }}">
              <i class="nav-icon far fa-user"></i>
              <p>
                Administrator
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/admin/users')}}" class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
    </div>
  </aside>

  <style>
    .customStyle{
      border-bottom: 1px solid gray;
      margin-bottom: 10px;
    }
    </style>