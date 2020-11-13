<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>شاخص - {{$education->title}}</title>
    <link rel="stylesheet" href="{{route('BaseUrl')}}/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{route('BaseUrl')}}/vendor/bootstrap/RTL.css">
    <link rel="stylesheet" href="{{route('BaseUrl')}}/vendor/FontAwesome/all.css">
</head>

<style>
    .desciption img{
        max-width: 100% !important;
        object-fit: cover;
    }
</style>


<body>
    <div class="container-fluid panel-table mt-5">
    <div class="card">
        <div class="card-body">
           @if($education->image)
            <div>
                <img width="100%" src="{{asset("$education->image")}}" alt="">
            </div>
           @endif
            <div class="desciption">
            <h3 class="{{$education->image ? 'mt-5' : 'mt-1'}} mb-3">{{$education->title}}</h3>
                {!! $education->description !!}
            </div>
        </div>
    </div>
</div>
</body>
</html>
