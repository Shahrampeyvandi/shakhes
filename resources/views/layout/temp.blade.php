<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>پنل مدیریت</title>

    <link rel="stylesheet" href="{{asset('assets/vendors/bundle.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/vendors/FontAwesome/all.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/select2/css/select2.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/vendors/dropify/dropify.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/toastr.css')}}">
    <link href="https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.2.0/dist/jBox.all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}" type="text/css">
    <link rel="shortcut icon" href="{{asset('assets/media/image/favicon.png')}}">
    <meta name="theme-color" content="#3f51b5" />
    @yield('css')
</head>

<body class="icon-side-menu">
    <div class="page-loader">
        <div class="spinner-border"></div>
        <span>در حال بارگذاری ...</span>
    </div>
    @include('Includes.Panel.sidebar')
    @include('Includes.Panel.side-menu')
    @include('Includes.Panel.navbar')
    <!-- begin::main content -->
    <main class="main-content">
        @yield('content')
    </main>
    <!-- end::main content -->
    <script>
        var mainUrl = "{{route('BaseUrl')}}";
    </script>


    <!-- begin::global scripts -->
    <script src="{{asset('assets/vendors/bundle.js')}}"></script>
    <script src="{{asset('assets/vendors/charts/chart.min.js')}}"></script>
    <script src="{{asset('assets/vendors/charts/sparkline.min.js')}}"></script>
    <script src="{{asset('assets/vendors/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{asset('assets/js/examples/charts.js')}}"></script>
    <!-- end::chart -->
    <!-- dropify -->
    <script src="{{asset('assets/vendors/dropify/dropify.min.js')}}"></script>

    <!-- end::dropify -->
    <script src="{{asset('assets/vendors/jquery-form/jquery.form.min.js')}}"></script>
    <script src="{{asset('assets/vendors/jquery-validate/jquery.validate.js')}}"></script>
    <script src="{{asset('assets/vendors/select2/js/select2.min.js')}}"></script>
    <!-- begin::custom scripts -->
    <script src="{{asset('assets/js/toastr.min.js')}}"></script>
    <!-- @toastr_render -->

    <script src="{{asset('assets/vendors/dataTable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendors/dataTable/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/vendors/dataTable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable.js')}}"></script>

    @yield('js')
    <script src="https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.2.0/dist/jBox.all.min.js"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
    <!-- end::custom scripts -->


</body>

</html>