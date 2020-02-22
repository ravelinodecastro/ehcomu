    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scroll-up').fadeIn();
            $('#blog').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
            $('#blog').fadeOut();
        }
     });
     
     $('.scroll-up').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 800);
        return false;
     });
     $('#blog').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 800);
        return false;
     });

