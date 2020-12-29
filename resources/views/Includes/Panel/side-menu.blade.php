<!-- begin::side menu -->
<div class="side-menu">
    <div class="side-menu-body">
        <ul>
            <li class="side-menu-divider">فهرست</li>
            <li><a href="{{route('BaseUrl')}}"><i class="fas fa-home"></i> <span class="pr-4">داشبورد</span> </a></li>
            <li><a href="{{route('Users')}}"><i class="fas fa-users"></i> <span class="pr-4">کاربران</span>
                </a>
          
            <li><a href="#"><i class="fas fa-book-open"></i> <span class="pr-4">آموزش</span> </a>
                <ul>
                    <li><a href="{{route('Education.Add')}}">افزودن</a></li>
                    <li><a href="{{route('Education.List')}}">لیست</a></li>
                </ul>
            </li>

            <li><a href="#"><i class="fas fa-image"></i> <span class="pr-4">اشتراک</span> </a>
                <ul>
                    <li><a href="{{route('Panel.AddPlan')}}">افزودن</a></li>
                    <li><a href="{{route('Panel.PlanList')}}">لیست</a></li>
                </ul>
            </li>
            <li><a href="{{route('Panel.Pays')}}"><i class="fas fa-dollar-sign"></i> <span
                        class="pr-4">تراکنش ها</span> </a>
            <li><a href="{{route('MoneyReports')}}"><i class="fas fa-sliders-h"></i> <span
                        class="pr-4">صورت های مالی و گزارشات</span> </a>
            <li><a href="{{route('PortfoyList')}}"><i class="fas fa-list-alt"></i> <span class="pr-4">پرتفوی روزانه شرکت ها</span> </a>
            <li><a href="{{route('CapitalIncrease')}}"><i class="fas fa-th"></i> <span class="pr-4">افزایش سرمایه</span> </a>

           
   
            {{-- <li><a href="#"><i class="fas fa-envelope"></i> <span class="pr-4">ارتباط با کاربران</span> </a>
                <ul>
                    <li><a href="{{route('Panel.SendMessage')}}">ارسال پیام</a></li>
                </ul>
            </li> --}}
             <li><a href="{{route('Clarifications')}}"><i class="fas fa-file-medical"></i> <span
                        class="pr-4">افشای اطلاعات و شفاف سازی</span> </a>
            </li>
            <li><a href="{{route('VolumeTrades')}}"><i class="fas fa-list"></i> <span
                        class="pr-4">افزایش حجم معاملات</span> </a>
            </li>
            <li><a href="{{route('Panel.Tickets')}}"><i class="fas fa-comment"></i> <span class="pr-4">پیام های کاربران</span> </a>
            </li>
            <li><a href="#"><i class="fas fa-cog"></i> <span class="pr-4">تنظیمات</span> </a>
            </li>

        </ul>
    </div>
</div>
<!-- end::side menu -->