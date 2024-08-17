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
          {{-- <img src="photos/userLogin.png" class="img-circle elevation-2" alt="User Image"> --}}
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
            <a href="{{url('/dashboard')}}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('/appoint')}}" class="nav-link {{ request()->is('appoint') ? 'active' : '' }}">
              <i class="nav-icon fas fa-address-card"></i>
              <p>
                Doctors
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/appointment/get?doctor=na') }}" class="nav-link {{ request()->is('appointment*') ? 'active' : '' }}">
              <i class="nav-icon far fa-calendar"></i>
              <p>
                  New appointment
              </p>
          </a>
          
          </li>
          <li class="nav-item">
            <a href="{{url('/records')}}" class="nav-link {{ request()->is('records') ? 'active' : '' }}">
              <i class="nav-icon fas fa-address-book"></i>
              <p>
                Records
              </p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>