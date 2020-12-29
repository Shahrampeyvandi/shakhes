<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/images/fav-icon.png')}}">
    <link rel="shortcut icon" sizes="192x192" href="{{asset('assets/images/fav-icon.png')}}">
    <link rel="apple-touch-icon" href="{{asset('assets/images/fav-icon.png')}}">
    <meta name="theme-color" content="#3f51b5" />
    <title>شاخص</title>
    <link rel="stylesheet" href="{{asset('assets/vendors/bundle.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/vendors/FontAwesome/all.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/css/home.css')}}" type="text/css">

</head>
<body>
    <div class="nav-bar">
    
        <div class="menu">
            <a href="#"><i class="fas fa-home"></i></a>
            <a href="#">درباره ما</a>
        </div>
        <div class="logo">
            <img src="{{asset('assets/images/logo-site.svg')}}" alt="">
        </div>
    
    </div>

    <div class="container-fluid">
        <div class="row mt-6">
           
            <div class="col-md-6">
                <div class="mt-md-5">

                    <h1 class="mb-md-5">به سایت شاخص خوش آمدید</h1>
                    <h3 class="list-title">برخی از ویژگی های اپلیکیشن: </h3>
                    <ul class="lists">
                        <li>
                            <img src="{{asset('assets/images/tik.svg')}}" alt="">
                            <p>قابلیت رصد بازار</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/tik.svg')}}" alt="">
                            <p>ارایه گزارشات ماهیانه , سه ماهه و سالیانه به صورت نموداری</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/tik.svg')}}" alt="">
                            <p>ارزش ریالی پرتفوی شرکت های سرمایه گزاری به روز</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/tik.svg')}}" alt="">
                            <p>ارایه اطلاعات کدال برای نماد ها</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/tik.svg')}}" alt="">
                            <p>نمایش حجم معاملات مشکوک و ارائه فیلتر های منتخب</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/tik.svg')}}" alt="">
                            <p>آموزش بورس با ترجمه از سایت معتبر INVESTOPEDIA</p>
                        </li>
                        <li>
                            <img src="{{asset('assets/images/tik.svg')}}" alt="">
                            <p>و قابلیت های دیگر</p>
                        </li>


                    </ul>

                    <div class="links col-md-8">
                        <a href="#">

                            دانلود مستقیم &nbsp;
                            <i class="fa fa-download" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <img src="{{asset('assets/images/bazar.png')}}" alt="">
                        </a>
                        <a href="">
                            <img src="{{asset('assets/images/google-play.png')}}" alt="">
                        </a>
                    </div>

                </div>
            </div>
            <div class="col-md-6 mt-5 mt-md-0">

                <img src="{{asset('assets/images/trade.svg')}}" alt="">

            </div>
        </div>

        <section>
            <h2 class="screans-title">
                <span>

                    تصاویری از محیط اپلیکیشن
                </span>
            </h2>
            <div class="row">
                <div class="col-md-3">

                    <img src="{{asset('assets/images/app-screan1.png')}}" alt="">
                </div>
                <div class="col-md-3">

                    <img src="{{asset('assets/images/app-screan2.png')}}" alt="">
                </div>
                <div class="col-md-3">

                    <img src="{{asset('assets/images/app-screan3.png')}}" alt="">
                </div>
            </div>
        </section>


    </div>
    
      <footer>
         
             <div class="col-sm-12 col-md-3">
                  <div class="company-desc">
                      <h4 class="company-title">
                          <span>شاخص</span>
                      </h4>
                      <p>
                          اپلیکیشن شاخص در راستای اطلاع رسانی هدفمند به سرمایه گذاران بازار بورس تهران فعالیت میکند تمامی اطلاعاتی
                          که بایستی یک سهام دار راجع به سبد سهام خود داشته باشد تا بتواند در لحظه بهترین تصمیم را جهت خرید و فروش
                          سهام اتخاذ نماید فرام آورده شده است.
                      </p>
                  </div>
             </div>
              
             <div class="col-sm-12 col-md-3">
                  <div id="enamad">
                    
                      <a referrerpolicy="origin" target="_blank"
                          href="https://trustseal.enamad.ir/?id=186807&amp;Code=lhLZw40M6rmWvlyRuYSe"><img referrerpolicy="origin"
                              src="https://Trustseal.eNamad.ir/logo.aspx?id=186807&amp;Code=lhLZw40M6rmWvlyRuYSe" alt=""
                              style="cursor:pointer" id="lhLZw40M6rmWvlyRuYSe"></a>
                  </div>
             </div>
       
  
  
  
          <div class=" copyright-area">
              <p>
                  تمام حقوق برای شاخص <i class="fas fa-copyright"></i> محفوظ است.
  
              </p>
          </div>
      </footer>
    <script src="{{asset('assets/vendors/bundle.js')}}"></script>
    <script>
        // $(window).scroll(function () {
        // let currentScrollPos = 0
       
       
        
        // let scroll_get_top = $(document).scrollTop();
        // console.log(currentScrollPos,scroll_get_top)
        // if (currentScrollPos > scroll_get_top) {
        
        // $(".nav-bar").animate({top:0},500);
        // } else {
        // $(".nav-bar").animate({top:"-100px"},500);
        // }
        
        // });
        var lastScrollTop = 0;
        $(window).scroll(function(event){
        var st = $(this).scrollTop();
        if (st > lastScrollTop){
        // downscroll code
     $(".nav-bar").css( {'top': '-100px' } )
   
      
       console.log('down')
        } else {
        // upscroll code
        console.log('up')
        
        $(".nav-bar").css( { 'top': '0' } )
        
        }
        lastScrollTop = st;
        });
        
        // let scroll_get = $(document).scrollTop();
        // if (scroll_get > 0) {
        // $(".nav-bar").css({
        // backgroundColor: "#000000",
        // backgroundImage: "none"
        // });
        // } else {
        
        // $(".siteNav").css("background-color", "transparent");
        // $(".siteNav").css(
        // "background-image",
        // "linear-gradient(to bottom, rgba(18,18,18,1), rgba(18,18,18,0))"
        // );
        // }
        // });
    </script>
</body>

</html>