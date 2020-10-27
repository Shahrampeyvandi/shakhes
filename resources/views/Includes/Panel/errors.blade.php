@if(session()->has('Error'))

    <div class="alert alert-danger">
        
          <p class="m-3 fs-0-8">{{session()->get('Error')}}</p>
      
    </div>

@endif
@if ($errors->any())
@foreach ($errors->all() as $error)
<div class="alert alert-danger fs-0-8 p-2">{{$error}}</div>
@endforeach
@endif

