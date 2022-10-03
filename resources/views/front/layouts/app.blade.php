<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(!empty($metas))
        @if($metas['meta_description'])
            <meta name="description" content="{{$metas['meta_description']}}">
        @endif
        @if($metas['meta_keyword'])
            <meta name="keywords" content="{{$metas['meta_keyword']}}">
        @endif
        @if($metas['home_title'] && $metas['site_title'])
            <title>{{ $metas['home_title'] }} | {{ $metas['site_title'] }}</title>
        @else
            <title>@yield('title')</title>
        @endif
    @else
        <title>@yield('title')</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
    @endif

    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('front/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/scss/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('front/scss/layout.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link href="{{ asset('assets/css/front/front-custom.css') }}" rel="stylesheet" type="text/css">
</head>

<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="71">
<!-- start header section -->
@include('front.layouts.header')
@yield('content')
@include('front.layouts.footer')

<script src="{{ asset('assets/js/front-third-party.js') }}"></script>
<script src="{{ mix('assets/js/custom/helpers.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>
<!--google analytics code-->
@if(!empty($metas['google_analytics']))
    {!! $metas['google_analytics'] !!}
@endif

<script src="{{ asset('front/js/slick.min.js') }}"></script>
<script src="{{mix('assets/js/home_page/home_page.js')}}"></script>
@routes
@yield('page_js')
@yield('scripts')
<script>
    $('.pricing-carousel').slick({
        dots: true,
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1400,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '250px',
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '150px',
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '100px',
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    centerPadding: '50px',
                    arrows:false
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows:false
                }
            }
        ]
    });
    $('.testimonial-carousel').slick({
        dots: false,
        centerPadding: '0',
        slidesToShow: 1,
        slidesToScroll: 1,
    });

    $(window).scroll(function(){
        var sticky = $('.header'),
            scroll = $(window).scrollTop();

        if (scroll >= 120) sticky.addClass('fixed');
        else sticky.removeClass('fixed');
    });
</script>
</body>
</html>
