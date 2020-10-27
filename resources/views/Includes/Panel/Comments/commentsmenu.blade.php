     <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a href="{{route('Panel.UnconfirmedComments')}}" class="nav-link 
            @if(\Request::route()->getName() == "Panel.UnconfirmedComments") {{'active'}}
             @endif">در انتظار تایید</a>
        </li>
        <li class="nav-item">
            <a href="{{route('Panel.ConfirmedComments')}}" class="nav-link
   @if(\Request::route()->getName() == "Panel.ConfirmedComments") {{'active'}}
    @endif">تایید شده</a>
        </li>
</ul>
