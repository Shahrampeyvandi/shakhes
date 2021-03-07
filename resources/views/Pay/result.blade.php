<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pay result</title>
     <link rel="stylesheet" href="{{asset('assets/vendors/bundle.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}" type="text/css">

</head>
<style>
    
    ul li {
    text-align: center;
    line-height: 3rem;
    
    } 
</style>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($success)
                    <h4 class="bg-success text-center">تراکنش با موفقیت انجام شد</h4>
                    <ul>
                        <li>
                            <span>
                                شماره موبایل:
                            </span>
                            <span>
                             {{$mobile}}
                            </span>
                        </li>
                        <li>
                            <span>
                                تاریخ اعتبار اشتراک
                            </span>
                            <span>
                                {{$expire_date}}
                            </span>
                        </li>
                        <li>
                            <span>
                                شماره تراکنش:
                            </span>
                            <span>
                               {{$transaction_code}}
                            </span>
                        </li>
                    
                    </ul>
                    
                    <div class="mt-3 text-center">
                        <a href="#" class="btn btn-success text-white">بازگشت به اپلیکیشن</a>
                    </div>
                 @else 
                   <h4 class="bg-danger text-center">خطا در انجام تراکنش</h4>
                @endif
            </div>
        </div>
    </div>
</body>
</html>