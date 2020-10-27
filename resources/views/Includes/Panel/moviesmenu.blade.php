@if (\Request::route()->getName() == "Panel.MoviesList" || \Request::route()->getName() == "Panel.AddMovie")
     
<div class="card">
    <div class=" card-body ">
   
<ul class="nav nav-pills ">
        <li class="nav-item">
            <a href="{{route('Panel.MoviesList')}}" class="nav-link 
            @if(\Request::route()->getName() == "Panel.MoviesList") {{'active'}}
             @endif">لیست</a>
        </li>
        <li class="nav-item">
            <a href="{{route('Panel.AddMovie')}}" class="nav-link
   @if(\Request::route()->getName() == "Panel.AddMovie") {{'active'}}
    @endif">جدید <i class="fas fa-plus"></i></a>
        </li>
</ul>
     
    </div>
</div>
@endif