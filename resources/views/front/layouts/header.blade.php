<header class="header">
    <div class="container">
        <div class="row align-items-center position-relative">
            <div class="col-lg-2 col-3">
                <a href="#!" class="header-logo">
                    <img src="{{ getLogoUrl() }}" alt="Vcard" class="img-fluid new-logo-image" />
                </a>
            </div>
            <div class="col-lg-8 col-1">
                <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
                    <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
            
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link  px-3" aria-current="page" href="{{asset('').'#home'}}">{{__('auth.home')}}</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link" href="{{asset('').'#features'}}">{{__('auth.features')}}</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link" href="{{asset('').'#about'}}">{{__('auth.about')}}</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link" href="{{asset('').'#pricing'}}">{{__('auth.pricing')}}</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link" href="{{asset('').'#contact'}}">{{__('auth.contact')}}</a>
                            </li>
                            <li class="nav-item px-3">
                                @php
                                    $styleCss = 'style';
                                @endphp
                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" href="javascript:void(0)" role="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('messages.language') }}
                                    </a>
                                    <ul class="dropdown-menu overflow-auto" {{ $styleCss }}="min-width: 200px; height:400px" aria-labelledby="languageDropdown">
                            @foreach(getAllLanguage() as $key => $value)
                                        <li class="languageSelection {{ (checkFrontLanguageSession() == $key) ? 'active' : '' }}" data-prefix-value="{{ $key }}" {{ $styleCss }}="max-height: 40px">
                                <a class="dropdown-item {{ (checkFrontLanguageSession() == $key) ? 'active' : '' }}" href="javascript:void(0)">{{ $value }}</a>
                                </li>
                            @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-lg-2 col-8 text-end header-btn">
                @if(empty(getLogInUser()))
                    <a class="btn btn-primary nav-btn" href="{{ route('login') }}">
                        {{ __('auth.sign_in') }}
                    </a>
                @else
                    @if(getLogInUser()->hasrole('admin') || getLogInUser()->hasrole('user'))
                        <a class="btn btn-primary nav-btn" href="{{ route('admin.dashboard') }}">
                            {{ __('messages.dashboard') }}
                        </a>
                    @endif
                    @if(getLogInUser()->hasrole('super_admin'))
                        <a class="btn btn-primary nav-btn" href="{{ route('sadmin.dashboard') }}">
                            {{ __('messages.dashboard') }}
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</header>
