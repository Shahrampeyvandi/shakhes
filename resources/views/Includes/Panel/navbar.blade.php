  <!-- begin::navbar -->
    <nav class="navbar">
        <div class="container-fluid">

            <div class="header-logo">
                <a href="#">
                    {{-- <img src="assets/media/image/light-logo.png" alt="..."> --}}
                    <span class="logo-text d-none d-lg-block">SHAKHES</span>
                </a>
            </div>

            <div class="header-body">

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="#" class="d-lg-none d-sm-block nav-link search-panel-open">
                            <i class="fa fa-search"></i>
                        </a>
                    </li>
                    <li class="nav-item datetime d-none d-md-block">
                        <a href="#" class="nav-link">
                            تاریخ
                    
                            <span class="date">
                    
                                {{\Morilog\Jalali\Jalalian::forge('today')->format('%A, %d %B %y')}}
                            </span>
                    
                            ساعت
                            <span class="date" id='server_time' style="width:75px;margin-left:10px">
                    
                            </span>
                            <span >
                                وضعیت بازار : 
                                {{Cache::get('bazarstatus')}}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        
                        <a href="#" class="nav-link sidebar-open" data-sidebar-target="#notifications">
                            <i class="fa fa-bell"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown">
                            <figure class="avatar avatar-sm avatar-state-success">
                                <img class="rounded-circle" src="{{asset('assets/images/avatar.png')}}" alt="عکس پروفایل">
                            </figure>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="profile.html" class="dropdown-item">پروفایل</a>
                        <a href="{{route('logout')}}" class="text-danger dropdown-item">خروج</a>
                        </div>
                    </li>
                    <li class="nav-item d-lg-none d-sm-block">
                        <a href="#" class="nav-link side-menu-open">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
    <!-- end::navbar -->