$(document).ready(function () {
    $('.main').css('width', '82%');
    $('.hamburger').click(function () {
        $('.sidebar').css('margin-right', '0');

        
        $('.main').animate({
            "margin-right":"274px",
            "width":"82%"
        },
             50);
       
        $(this).hide();
        $('.close-sidebar').show();
    });
    $('.close-sidebar').click(function () {
        $('.sidebar').css('margin-right', '-274px');

        $('.main').css('margin-right', '0px')
        $('.main').css('width', '100%')
        $(this).hide();
        $('.hamburger').show();
    });

    $(".print_page").click(function () {
        var host ='<?php echo base_url();?>'
     
        var divContents = $(".print-content").html();
        var printWindow = window.open('', '', 'height=400,width=800');
        printWindow.document.write('<html><head>');
        printWindow.document.write('<title>پنل شبکه تبادل فناوری</title>  <link rel = "stylesheet" href ="../../../Panel/vendor/bootstrap/bootstrap.min.css" ><link rel="stylesheet" href="../../../Panel/vendor/bootstrap/RTL.css"><link rel="stylesheet" href="../../../Panel/vendor/bootstrap/bootstrap-select.css"><link rel="stylesheet" href="../../../Panel/vendor/FontAwesome/all.css"><link rel="stylesheet" href="{{route(" BaseUrl")}}/datepicker/bootstrap-datepicker.min.css"><link rel="stylesheet" href="../../../Panel/assets/css/style.css"></link>');
        printWindow.document.write('</head><body class="p-5">');
                    printWindow.document.write(divContents);
        printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
        
            });
});