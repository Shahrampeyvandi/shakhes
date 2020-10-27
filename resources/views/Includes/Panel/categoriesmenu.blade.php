     
<div class="card">
    <div class=" card-body ">
   
<ul class="nav nav-pills ">
        <li class="nav-item">
            <a href="{{route('Panel.CatList')}}" class="nav-link 
            @if(\Request::route()->getName() == "Panel.CatList") {{'active'}}
             @endif">لیست</a>
        </li>
        <li class="nav-item">
            <a href="{{route('Panel.AddCat')}}" class="nav-link
   @if(\Request::route()->getName() == "Panel.AddCat") {{'active'}}
    @endif">جدید <i class="fas fa-plus"></i></a>
        </li>
</ul>
     
    </div>
</div>
