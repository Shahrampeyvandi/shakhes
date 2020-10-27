     
<div class="card">
    <div class=" card-body ">
   
<ul class="nav nav-pills ">
        <li class="nav-item">
            <a href="{{route('Panel.SliderList')}}" class="nav-link 
            @if(\Request::route()->getName() == "Panel.SliderList") {{'active'}}
             @endif">لیست</a>
        </li>
        <li class="nav-item">
            <a href="{{route('Panel.AddSlider')}}" class="nav-link
   @if(\Request::route()->getName() == "Panel.AddSlider") {{'active'}}
    @endif">جدید <i class="fas fa-plus"></i></a>
        </li>
</ul>
     
    </div>
</div>
