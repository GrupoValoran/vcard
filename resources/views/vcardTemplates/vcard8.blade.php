<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    @if(checkFeature('seo'))
        @if($vcard->meta_description)
            <meta name="description" content="{{$vcard->meta_description}}">
        @endif
        @if($vcard->meta_keyword)
            <meta name="keywords" content="{{$vcard->meta_keyword}}">
        @endif
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(checkFeature('seo') && $vcard->site_title && $vcard->home_title)
        <title>{{ $vcard->home_title }} | {{ $vcard->site_title }}</title>
    @else
        <title>{{ $vcard->name }} | {{ getAppName() }}</title>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">

    {{--css link--}}
    <link rel="stylesheet" href="{{ asset('assets/css/vcard8.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-vcard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">

    {{--google Font--}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Roboto&display=swap" rel="stylesheet">

    <title>{{ $vcard->name }} | {{ getAppName() }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">
    @if(checkFeature('custom-fonts') && $vcard->font_family)
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family={{$vcard->font_family}}">
    @endif

    @if($vcard->font_family || $vcard->font_size || $vcard->custom_css)
        <style>
            @if(checkFeature('custom-fonts'))
                @if($vcard->font_family)
                    body {
                        font-family: {{$vcard->font_family}};
                    }
                @endif
                @if($vcard->font_size)
                    div > h4 {
                        font-size: {{$vcard->font_size}}px !important;
                    }
                @endif
            @endif
            @if(isset(checkFeature('advanced')->custom_css))
                {!! $vcard->custom_css !!}
            @endif
        </style>
    @endif
</head>
<body>
<div class="container">
    @include('vcards.password')
    <div class="vcard-eight main-content w-100 mx-auto overflow-hidden content-blur collapse show allSection">
        {{--banner--}}
        <div class="vcard-eight__banner w-100 position-relative">
            <img src="{{ $vcard->cover_url }}" class="img-fluid banner-image position-relative" alt="banner"/>
            <div class="d-flex justify-content-end position-absolute top-0 end-0 me-3 custom-language">
                @if($vcard->language_enable == \App\Models\Vcard::LANGUAGE_ENABLE)
                    <div class="language pt-4 me-2">
                        <ul class="text-decoration-none">
                            <li class="dropdown1 dropdown lang-list">
                                <a class="dropdown-toggle lang-head text-decoration-none" data-toggle="dropdown" role="button"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-language me-2"></i>{{getLanguage($vcard->default_language)}}
                                </a>
                                <ul class="dropdown-menu start-0 lang-hover-list top-dropdown top-100">
                                    @foreach(getAllLanguage() as $key => $language)
                                        <li class="{{ getLanguageIsoCode($vcard->default_language) === $key ? 'active' : '' }}">
                                            <a href="javascript:void(0)" id="languageName"
                                               data-name="{{ $key }}">{{ $language }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                @endif

            </div>
        </div>
        {{--profile details--}}
<div>
        <div class="vcard-eight__profile d-flex align-items-center px-4 flex-sm-row flex-column position-relative">
            <div class="vcard-eight__avatar">
                <img src="{{ $vcard->profile_url }}" class="rounded-circle"/>
            </div>
            <div class="vcard-eight__position d-flex flex-column mx-4 position-relative">
                <div class="d-flex flex-column">
                    <h4 class="vcard-eight-heading fw-bold text-sm-start text-center">{{ ucwords($vcard->first_name.' '.$vcard->last_name) }}</h4>
                    <span class="avatar-designation text-white text-sm-start text-center">{{ ucwords($vcard->occupation) }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center mb-5">
            <span class="pt-5 profile-description"> {{$vcard->description}}</span>
        </div>
</div>
    {{--social media icon--}}
    <div class="vcard-eight__social py-3 px-sm-3 px-2 position-relative">
        @if(checkFeature('social_links') && getSocialLink($vcard))
            <div class="social-icons d-flex justify-content-center pt-4 flex-wrap">
                @foreach(getSocialLink($vcard) as $value)
                    <span class="social-back rounded-circle d-flex justify-content-center align-items-center m-sm-2 m-1">
                        {!! $value !!}
                </span>
                @endforeach
            </div>
        @endif
    </div>
    {{--event--}}
    @if($vcard->email || $vcard->phone || $vcard->dob || $vcard->location)
        <div class="vcard-eight__event py-3 px-sm-4 px-3 mt-2 position-relative">
            <div class="row">
                <div class="col-12">
                    <div class="card event-card p-4">
                        <div class="row g-4">
                            @if($vcard->email)
                                <div class="col-sm-6 col-12">
                                    <div class="event-icon rounded-circle d-flex justify-content-center align-items-center mx-auto mb-2">
                                        <img src="{{asset('assets/img/vcard8/vcard8-email.png')}}" alt="email"/>
                                    </div>
                                    <div class="event-details">
                                        <span class="text-white text-center d-block mb-1">{{ __('messages.vcard.email_address') }}</span>
                                        <h5 class="text-center mb-0 text-white"><a href="mailto:{{ $vcard->email }}"
                                                                                   class="text-white text-decoration-none">{{ $vcard->email }}</a>
                                        </h5>
                                    </div>
                                </div>
                            @endif
                            @if($vcard->phone)
                                <div class="col-sm-6 col-12">
                                    <div class="event-icon rounded-circle d-flex justify-content-center align-items-center mx-auto mb-2">
                                        <img src="{{asset('assets/img/vcard8/vcard8-phone.png')}}" alt="mobile"/>
                                    </div>
                                    <div class="event-details">
                                        <span class="text-white text-center d-block mb-1">{{ __('messages.vcard.mobile_number') }}</span>
                                        <h5 class="text-center mb-0 text-white"><a href="tel:{{ $vcard->phone }}"
                                                                                   class="text-white text-decoration-none">+{{ $vcard->region_code }} {{ $vcard->phone }}</a>
                                        </h5>
                                    </div>
                                </div>
                            @endif
                            @if($vcard->dob)
                                <div class="col-sm-6 col-12">
                                    <div class="event-icon rounded-circle d-flex justify-content-center align-items-center mx-auto mb-2">
                                        <img src="{{asset('assets/img/vcard8/vcard8-birthday.png')}}" alt="birthday"/>
                                    </div>
                                    <div class="event-details">
                                        <span class="text-white text-center d-block mb-1">{{ __('messages.vcard.dob') }}</span>
                                        <h5 class="text-center mb-0 text-white">{{ $vcard->dob }}</h5>
                                    </div>
                                </div>
                            @endif
                            @if($vcard->location)
                                <div class="col-sm-6 col-12">
                                    <div class="event-icon rounded-circle d-flex justify-content-center align-items-center mx-auto mb-2">
                                        <img src="{{asset('assets/img/vcard8/vcard8-location.png')}}" alt="location"/>
                                    </div>
                                    <div class="event-details">
                                        <span class="text-white text-center d-block mb-1">{{ __('messages.vcard.location') }}</span>
                                        <h5 class="text-center mb-0 text-white">{!! ucwords($vcard->location) !!}</h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{--our services--}}
    @if(checkFeature('services') && $vcard->services->count())
        <div class="vcard-eight__service py-4 position-relative px-sm-4 px-3">
            <div class="container">
                <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">{{ __('messages.vcard.our_service') }}</h4>
                <div class="row mt-3 service-bg bg-white d-flex justify-content-center">
                    @foreach($vcard->services as $service)
                        <div class="col-sm-6 col-12 p-3">
                            <div class="card service-card px-3 py-0 h-100 border-0">
                                <div class="service-image d-flex justify-content-center align-items-center rounded-circle mx-auto">
                                    <img src="{{$service->service_icon}}" class="rounded-circle"
                                         alt="{{$service->name}}"/>
                                </div>
                                <div class="service-details mt-3">
                                    <h4 class="service-title text-center">{{ ucwords($service->name) }}</h4>
                                    <p class="service-paragraph mb-0 text-center">
                                        {!! $service->description !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{--gallery--}}
    @if(checkFeature('gallery') && $vcard->gallery->count())
        <div class="vcard-eight__gallery py-4 position-relative px-sm-4 px-3">
            <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">{{ __('messages.plan.gallery') }}</h4>
            <div class="row g-3 gallery-slider mt-2">
                @foreach($vcard->gallery as $file)
                    <div class="col-6">
                        <div class="card gallery-card p-3 border-0 w-100">
                            <div class="gallery-profile">
                                @if($file->link == null)
                                    <img src="{{$file->gallery_image}}" alt="profile" class="w-100"/>
                                @else
                                    <a id="video_url" data-id="https://www.youtube.com/embed/{{YoutubeID($file->link)}}"
                                       data-bs-toggle="modal" data-bs-target="#exampleModal"
                                       class="gallery-link">
                                        <div class="gallery-item"
                                             style="background-image: url(&quot;https://vcard.waptechy.com/assets/img/video-thumbnail.png&quot;)">
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <iframe id="video" src="//www.youtube.com/embed/Q1NKMPhP8PY"
                                class="w-100" height="315">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{--product--}}
    @if(checkFeature('products') && $vcard->products->count())
        <div class="vcard-eight__product py-4 position-relative px-sm-4 px-3">
            <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">
                {{ __('messages.plan.products') }}</h4>
            <div class="row g-3 product-slider mt-2">
                @foreach($vcard->products as $product)
                    <div class="col-6 h-100">
                        <div class="card product-card p-3 border-0 w-100 card-height h-100">
                            <div class="product-profile">
                                <img src="{{ $product->product_icon }}" alt="profile" class="w-100" height="208px"/>
                            </div>
                            <div class="product-details mt-3">
                                <h4 class="text-white">{{$product->name}}</h4>
                                <p class="mb-2 text-white">
                                    {{$product->description}}
                                </p>
                                @if($product->currency_id && $product->price)
                                    <span
                                            class="text-white">{{$product->currency->currency_icon}}{{$product->price}}</span>
                                    @elseif($product->price)
                                <span class="text-white">{{$product->price}}</span>
                            @else
                                <span class="text-white">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        {{--trstimonial--}}
        @if(checkFeature('testimonials') && $vcard->testimonials->count())
            <div class="vcard-eight__testimonial py-4 position-relative px-sm-4 px-3">
                <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">
                    {{ __('messages.plan.testimonials') }}</h4>
                <div class="row g-3 testimonial-slider testimonial-next-prev mt-2">
                    @foreach($vcard->testimonials as $testimonial)
                        <div class="col-12 h-100">
                            <div class="card testimonial-card p-3 border-0 w-100 h-100">
                                <div class="testimonial-user d-flex flex-column align-items-center justify-content-sm-start justify-content-center">
                                    <img src="{{ $testimonial->image_url }}" alt="profile"
                                         class="rounded-circle"/>
                                    <div class="user-details d-flex flex-column mt-2">
                                        <span class="user-name text-center">{{ ucwords($testimonial->name) }}</span>
                                        <span class="user-designation text-center"></span>
                                    </div>
                                </div>
                                <p class="review-message text-center mt-2 mx-auto h-100">
                                    {!! $testimonial->description !!}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- blog--}}
        @if(checkFeature('blog') && $vcard->blogs->count())
            <div class="vcard-eight__blog position-relative py-3">
                <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">
                    {{ __('messages.feature.blog') }}</h4>
                <div class="container">
                    <div class="row g-4 blog-slider overflow-hidden">
                        @foreach($vcard->blogs as $blog)
                            <div class="col-6 mb-2">
                                <div class="card blog-card p-2 border-0 w-100 h-100">
                                    <div class="blog-image">
                                        <a href="{{route('vcard.show-blog',[$vcard->url_alias,$blog->id])}}">
                                            <img src="{{ $blog->blog_icon }}" alt="profile" class="w-100"/>
                                        </a>
                                    </div>
                                    <div class="blog-details">
                                        <a href="{{route('vcard.show-blog',[$vcard->url_alias,$blog->id])}}" class="text-decoration-none">
                                            <h4 class="text-sm-start text-center title-color p-3 mb-0 text-white">{{ $blog->title }}</h4>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        {{--qr code--}}
        <div class="vcard-eight__qr-code py-4 position-relative px-sm-4 px-3">
            <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">{{ __('messages.vcard.qr_code') }}</h4>
            <div
                    class="card qr-code-card justify-content-center align-items-center px-sm-3 px-4 pt-5 pb-4 position-relative w-100 mx-auto">
                <div class="qr-profile mb-3 d-flex justify-content-center position-absolute top-0">
                    <img src="{{$vcard->profile_url}}" alt="qr profile"
                         class="rounded-circle"/>
                </div>
                <div class="mt-3 qr-code-scanner mx-md-4 mx-2 pb-2 bg-white">
                    {!! QrCode::size(130)->format('svg')->generate(Request::url()); !!}
                </div>
                <div class="mx-2 mt-3">
                    <a class="qr-code-btn text-white mt-4 mx-auto text-decoration-none" id="qr-code-btn"
                       download="qr_code.png">{{ __('messages.vcard.download_my_qr_code') }}</a>
                </div>
            </div>
        </div>
        {{--business hour--}}
        @if($vcard->businessHours->count())
            <div class="vcard-eight__timing py-4 position-relative px-sm-4 px-3">
                <div class="container">
                    <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">
                        {{ __('messages.business.business_hours') }}</h4>
                    <div class="row mt-3 d-flex justify-content-center">
                        <div class="col-sm-8 time-section px-3 py-1">
                            @foreach($vcard->businessHours as $day)
                                <div class="d-flex justify-content-center time-zone">
                                    <span class="text-center me-2">{{ strtoupper(__('messages.business.'.\App\Models\BusinessHour::DAY_OF_WEEK[$day->day_of_week]))}} :</span>
                                    <span>{{ $day->start_time.' - '.$day->end_time }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{--Appointment--}}
        @if(checkFeature('appointments') && $vcard->appointmentHours->count())
            <div class="vcard-eight__appointment py-3 px-sm-4 px-3 mt-2 position-relative">
                <div class="container">
                    <h4 class="vcard-eight-heading heading-line text-center pb-4 text-white position-relative d-block mx-auto">
                        {{__('messages.make_appointments')}}</h4>
                    <div class="appointment p-4">
                        <div class="row d-flex align-items-center justify-content-center mb-3">
                            <div class="col-md-2">
                                <label for="date" class="appoint-date mb-2">{{__('messages.date')}}</label>
                            </div>
                            <div class="col-md-10">
                                {{ Form::text('date', null, ['class' => 'date appoint-input', 'placeholder' => __('messages.form.pick_date'),'id'=>'pickUpDate']) }}
                            </div>
                        </div>
                        <div class="row d-flex align-items-center justify-content-center mb-md-3">
                            <div class="col-md-2">
                                <label for="text" class="appoint-date mb-2">{{__('messages.hour')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div id="slotData" class="row">
                                </div>
                            </div>
                        </div>
                        <button type="button"
                                class="appointmentAdd appoint-btn text-white mt-4 d-block mx-auto ">{{__('messages.make_appointments')}}
                        </button>
                    </div>
                </div>
        </div>
            @include('vcardTemplates.appointment')
        @endif

        <div class="vcard-eight__contact py-4 position-relative px-sm-4 px-3">
            {{--contact us--}}
            @php $currentSubs = $vcard->subscriptions()->where('status', \App\Models\Subscription::ACTIVE)->latest()->first() @endphp
            @if($currentSubs && $currentSubs->plan->planFeature->enquiry_form)
                <h4 class="vcard-eight-heading heading-line position-relative text-center d-block mx-auto pb-3">{{ __('messages.contact_us.contact_us') }}</h4>
                <div class="container">
                        <div class="row mt-4">
                            <div class="col-12 px-0">
                                <form id="enquiryForm">
                                    @csrf
                                    <div class="contact-form px-sm-3">
                                        <div id="enquiryError" class="alert alert-danger d-none"></div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.user.your_name') }}</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="name" id="name"
                                                       class="form-control border-start-0"
                                                       placeholder="{{__('messages.form.your_name')}}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.user.email') }}</label>
                                            <div class="input-group mb-3">
                                                <input type="email" name="email" id="email"
                                                       class="form-control border-start-0"
                                                       placeholder="{{__('messages.form.enter_email')}}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.user.phone') }}</label>
                                            <div class="input-group mb-3">
                                                <input type="tel" name="phone" id="phone"
                                                       class="form-control border-start-0"
                                                       placeholder="{{__('messages.form.phone')}}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.user.your_message') }}</label>
                                            <textarea class="form-control" name="message"
                                                      placeholder="{{__('messages.form.type_message')}}" id="message"
                                                      rows="5"></textarea>
                                        </div>
                                        <button type="submit"
                                                class="contact-btn text-white mt-4 d-block ms-auto">{{ __('messages.contact_us.send_message') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            @endif
            <div class="d-sm-flex justify-content-center mt-5 pb-5">
                <button type="submit" class="vcard-eight-btn mt-4 d-block btn text-white"
                        onclick="downloadVcard('{{ $vcard->name }}.vcf',{{ $vcard->id }})"><i
                            class="fas fa-download me-2"></i>{{ __('messages.vcard.download_vcard') }}</button>
                {{--share btn--}}
                <button type="button" class="vcard8-share share-btn d-block btn mt-4 ms-sm-3">
                    <a class="text-decoration-none text-white">
                        <i class="fas fa-share-alt me-2"></i>{{ __('messages.vcard.share') }}</a>
                </button>
            </div>
            @if($vcard->location_url && isset($url[5]))
                <div class="m-2 ">
                    <iframe width="100%" height="300px" src='https://maps.google.de/maps?q={{$url[5]}}/&output=embed'
                            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                            style="border-radius: 10px;"></iframe>
                </div>
            @endif
            <div class="d-flex justify-content-evenly">
                @if(!isset(checkFeature('advanced')->hide_branding) || $vcard->branding == 0)
                    @if($vcard->made_by)
                        <a @if(!is_null($vcard->made_by_url)) href="{{$vcard->made_by_url}}" @endif class="text-center text-decoration-none text-white"  target="_blank"> <small>{{ __('messages.made_by') }} {{ $vcard->made_by }}</small></a>
                    @else
                        <div class="text-center">
                            <small class="text-white">{{ __('messages.made_by') }} {{ $setting['app_name'] }}</small>
                        </div>
                    @endif
                @endif
                @if(!empty($vcard->privacy_policy) || !empty($vcard->term_condition))
                    <a class="text-decoration-none text-white cursor-pointer" href="{{ route('vcard.show-privacy-policy',[$vcard->url_alias,$vcard->id]) }}"><small>{{__('messages.vcard.term_policy')}}</small></a>
                @endif
            </div>
        </div>
        
    </div>


    {{-- share modal code--}}
    <div id="vcard8-shareModel" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.vcard.share_my_vcard') }}</h5>
                    <button type="button" aria-label="Close" class="btn btn-sm btn-icon btn-active-color-danger"
                            data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                             version="1.1">
							<g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                               fill="#000000">
								<rect fill="#000000" x="0" y="7" width="16" height="2" rx="1"/>
								<rect fill="#000000" opacity="0.5"
                                      transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                      x="0" y="7" width="16" height="2" rx="1"/>
							</g>
						</svg>
					</span>
                    </button>
                </div>
                @php
                    $shareUrl = route('vcard.defaultIndex')."/".$vcard->url_alias;
                @endphp
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 justify-content-between social-link-modal">
                            <a href="http://www.facebook.com/sharer.php?u={{$shareUrl}}"
                               target="_blank" class="mx-2 share8" title="Facebook">
                                <i class="fab fa-facebook fa-3x" style="color: #1B95E0"></i>
                            </a>
                            <a href="http://twitter.com/share?url={{$shareUrl}}&text={{$vcard->name}}&hashtags=sharebuttons"
                               target="_blank" class="mx-2 share8" title="Twitter">
                                <i class="fab fa-twitter fa-3x" style="color: #1DA1F3"></i>
                            </a>
                            <a href="http://www.linkedin.com/shareArticle?mini=true&url={{$shareUrl}}"
                               target="_blank" class="mx-2 share8" title="Linkedin">
                                <i class="fab fa-linkedin fa-3x" style="color: #1B95E0"></i>
                            </a>
                            <a href="mailto:?Subject=&Body={{$shareUrl}}" target="_blank"
                               class="mx-2 share8" title="Email">
                                <i class="fas fa-envelope fa-3x" style="color: #191a19  "></i>
                            </a>
                            <a href="http://pinterest.com/pin/create/link/?url={{$shareUrl}}"
                               target="_blank" class="mx-2 share8">
                                <i class="fab fa-pinterest fa-3x" style="color: #bd081c" title="Pinterest"></i>
                            </a>
                            <a href="http://reddit.com/submit?url={{$shareUrl}}&title={{$vcard->name}}"
                               target="_blank" class="mx-2 share8" title="Reddit">
                                <i class="fab fa-reddit fa-3x" style="color: #ff4500"></i>
                            </a>
                            <a href="https://wa.me/?text={{$shareUrl}}" target="_blank" class="mx-2 share8" title="Whatsapp">
                                <i class="fab fa-whatsapp fa-3x" style="color: limegreen"></i>
                            </a>
                        </div>
                    </div>
                    <div class="text-center">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('vcardTemplates.template.templates')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="{{ asset('assets/js/front-third-party.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/slider/js/slick.min.js') }}" type="text/javascript"></script>
<script>
@if(checkFeature('seo') && $vcard->google_analytics)
    {!! $vcard->google_analytics !!}
@endif
@if(isset(checkFeature('advanced')->custom_js) && $vcard->custom_js)
    {!! $vcard->custom_js !!}
@endif
</script>
@php
    $setting = \App\Models\UserSetting::where('user_id', $vcard->tenant->user->id)->where('key', 'stripe_key')->first();
@endphp
<script>
    let stripe = ''
    @if (!empty($setting) && !empty($setting->value))
        stripe = Stripe('{{ $setting->value }}');
    @endif
    $('.testimonial-slider').slick({
        dots: true,
        infinite: true,
        arrows: true,
        autoplay: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
    });
</script>
<script>
    $('.product-slider').slick({
        dots: true,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 2,
        autoplay: true,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            }
        ]
    });
</script>
<script>
    $('.gallery-slider').slick({
        dots: true,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 2,
        autoplay: true,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true,
                },
            }
        ]
    });

    $('.blog-slider').slick({
        dots: true,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 1,
        autoplay: true,
        slidesToScroll: 1,
    })
</script>
<script>
    let isEdit = false
    let password = "{{isset(checkFeature('advanced')->password) && !empty($vcard->password) }}"
    let passwordUrl = "{{ route('vcard.password', $vcard->id) }}";
    let enquiryUrl = "{{ route('enquiry.store',  ['vcard' => $vcard->id, 'alias' => $vcard->url_alias]) }}";
    let appointmentUrl = "{{ route('appointment.store', ['vcard' => $vcard->id, 'alias' => $vcard->url_alias]) }}";
    let slotUrl = "{{route('appointment-session-time',$vcard->url_alias)}}";
    let appUrl = "{{ config('app.url') }}";
    let vcardId = {{$vcard->id}};
    let vcardAlias = "{{$vcard->url_alias}}";
    let languageChange = "{{ url('language') }}";
    let paypalUrl = "{{ route('paypal.init') }}"
    let lang = "{{checkLanguageSession($vcard->url_alias)}}";
</script>
<script>
    const svg = document.getElementsByTagName('svg')[0];
    const { x, y, width, height } = svg.viewBox.baseVal;
    const blob = new Blob([svg.outerHTML], { type: 'image/svg+xml' });
    const url = URL.createObjectURL(blob);
    const image = document.createElement('img');
    image.src = url;
    image.addEventListener('load', () => {
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const context = canvas.getContext('2d');
        context.drawImage(image, x, y, width, height);
        const link = document.getElementById('qr-code-btn');
        link.href = canvas.toDataURL();
        URL.revokeObjectURL(url);
    });
</script>
@routes
<script src="{{ asset('assets/js/messages.js') }}"></script>
<script src="{{ mix('assets/js/custom/helpers.js') }}"></script>
<script src="{{ mix('assets/js/custom/custom.js') }}"></script>
<script src="{{ mix('assets/js/vcards/vcard-view.js') }}"></script>

</body>
</html>
