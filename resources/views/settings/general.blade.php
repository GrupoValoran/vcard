@extends('settings.edit')
@section('section')
    <div class="card">
        <div class="card-body">
            {{ Form::open(['route' => ['setting.update'], 'method' => 'post', 'files' => true, 'id' => 'createSetting']) }}
            <div class="row">
                <!-- App Name Field -->
                <div class="form-group col-sm-6 mb-3">
                    {{ Form::label('app_name', __('messages.setting.app_name').':', ['class' => 'form-label required']) }}
                    {{ Form::text('app_name', $setting['app_name'], ['class' => 'form-control', 'id' => 'settingAppName','placeholder'=> __('messages.setting.app_name')]) }}
                </div>
                <!-- Email Field -->
                <div class="form-group col-sm-6 mb-3">
                    {{ Form::label('email', __('messages.user.email').':', ['class' => 'form-label required']) }}
                    {{ Form::email('email', $setting['email'], ['class' => 'form-control', 'required', 'id' => 'settingEmail','placeholder'=>__('messages.user.email')]) }}
                </div>
                <!-- Phone Field -->
                <div class="form-group col-md-6 col-lg-6 col-sm-6 col-12 mb-3">
                    {{ Form::label('phone', __('messages.user.phone').':', ['class' => 'form-label required']) }}
                    <br>
                    {{ Form::tel('phone', '+'.$setting['prefix_code'].$setting['phone'], ['class' => 'form-control', 'placeholder' => __('messages.form.contact'), 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")','id'=>'phoneNumber']) }}
                    {{ Form::hidden('prefix_code','+'.$setting['prefix_code'] ,['id'=>'prefix_code']) }}
                    <p id="valid-msg" class="text-success d-block fw-400 fs-small mt-2 d-none">{{__('messages.placeholder.valid_number')}}</p>
                    <p id="error-msg" class="text-danger d-block fw-400 fs-small mt-2 d-none"></p>
                </div>

                <div class="col-md-6 col-lg-6 col-sm-6 col-12 form-group mb-3">
                    {{ Form::label('plan_expire_notification', __('messages.plan_expire_notification').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::number('plan_expire_notification', $setting['plan_expire_notification'], ['class' => 'form-control','min'=>0, "onKeyPress"=>"if(this.value.length==2) return false;",'required', 'id' => 'settingPlanExpireNotification','placeholder'=>__('messages.plan_expire_notification')]) }}
                </div>

                <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                    <div class="form-group mb-3">
                        {{ Form::label('address', __('messages.setting.address').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('address', $setting['address'], ['class' => 'form-control','min'=>0, 'id' => 'settingAddress', 'required','placeholder'=>__('messages.setting.address')  ]) }}
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                    <div class="form-group mb-3">
                        {{ Form::label('default_language', __('messages.setting.default_language').':', ['class' => 'form-label']) }}
                        {{ Form::select('default_language', getAllLanguage(), $setting['default_language'],['class' => 'form-control', 'data-control'=>'select2']) }}
                    </div>
                </div>


                <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                    <div class="form-group mb-3">
                        {{ Form::label('user_default_language', __('messages.setting.user_default_language').':', ['class' => 'form-label']) }}
                        {{ Form::select('user_default_language', getAllLanguage(), $setting['user_default_language'],['class' => 'form-control', 'data-control'=>'select2']) }}
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                    <div class="form-group mb-3">
                        {{ Form::label('default_country_code', __('messages.common.default_country_code').':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::text('default_country_data', null, ['class' => 'form-control','placeholder'=>__('messages.common.default_country_code'), 'id'=>'defaultCountryData']) }}
                        {{ Form::hidden('default_country_code',$setting['default_country_code'] ,['id'=>'defaultCountryCode',]) }}                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6 col-12">
                    <div class="form-group mb-3">
                        {{ Form::label('default_currency_format', __('messages.setting.default_currency_format').':', ['class' => 'form-label']) }}
                        {{ Form::select('default_currency', getCurrenciesCode(), $setting['default_currency'],['class' => 'form-control', 'data-control'=>'select2']) }}
                    </div>
                </div>
                
                <div class="form-group col-lg-3 col-md-3 mb-3">
                    <div class="mb-3" io-image-input="true">
                        <label for="appLogoPreview" class="form-label required">{{ __('messages.setting.app_logo').':'}}</label>
                        <span data-bs-toggle="tooltip"
                              data-placement="top"
                              data-bs-original-title="{{__('messages.tooltip.app_logo')}}">
                                <i class="fas fa-question-circle ml-1 mt-1 general-question-mark" ></i>
                        </span>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="appLogoPreview"
                                     style="background-image: url('{{ isset($setting['app_logo']) ? $setting['app_logo'] : asset('assets/images/infyom-logo.png') }}')">
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                      data-placement="top" data-bs-original-title="{{__('messages.tooltip.change_app_logo')}}">
                    <label>
                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                        <input type="file" id="appLogo" name="app_logo" class="image-upload d-none" accept="image/*"/>
                    </label>
                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-lg-3 col-md-3 mb-3">
                    <div class="mb-3" io-image-input="true">
                        <label for="faviconPreview"
                               class="form-label required"> {{__('messages.setting.favicon'). ':'}}</label>
                        <span data-bs-toggle="tooltip"
                              data-placement="top"
                              data-bs-original-title="{{__('messages.tooltip.favicon_logo')}}">
                                <i class="fas fa-question-circle ml-1 mt-1 general-question-mark" ></i>
                        </span>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage" id="faviconPreview"
                                     style="background-image: url('{{ isset($setting['favicon']) ? $setting['favicon'] : asset('web/media/logos/favicon-infyom.png') }}');">
                                </div>
                                     <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                           data-placement="top" data-bs-original-title="{{__('messages.tooltip.change_favicon_logo')}}">
                    <label>
                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                        <input type="file" id="favicon" name="favicon" class="image-upload d-none" accept="image/*"/>
                    </label>
                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="card-header px-0">
                <div class="d-flex align-items-center justify-content-center">
                    <h3 class="m-0">{{__('messages.plan.seo')}}
                    </h3>
                </div>
            </div>
            <div class="row border-top p-4">
                <div class="col-lg-6 mb-3">
                    {{ Form::label('Site title', __('messages.vcard.site_title').':', ['class' => 'form-label']) }}
                    {{ Form::text('site_title', isset($metas) ? $metas['site_title'] : null, ['class' => 'form-control', 'placeholder' => __('messages.form.site_title')]) }}
                </div>
                <div class="col-lg-6 mb-3">
                    {{ Form::label('Home title', __('messages.vcard.home_title').':', ['class' => 'form-label']) }}
                    {{ Form::text('home_title', isset($metas) ? $metas['home_title'] : null, ['class' => 'form-control', 'placeholder' => __('messages.form.home_title')]) }}
                </div>
                <div class="col-lg-6 mb-3">
                    {{ Form::label('Meta keyword', __('messages.vcard.meta_keyword').':', ['class' => 'form-label']) }}
                    {{ Form::text('meta_keyword', isset($metas) ? $metas['meta_keyword'] : null, ['class' => 'form-control', 'placeholder' => __('messages.form.meta_keyword')]) }}
                </div>
                <div class="col-lg-6 mb-3">
                    {{ Form::label('Meta Description', __('messages.vcard.meta_description').':', ['class' => 'form-label']) }}
                    {{ Form::text('meta_description', isset($metas) ? $metas['meta_description'] : null, ['class' => 'form-control', 'placeholder' => __('messages.form.meta_description')]) }}
                </div>
            </div>
            <div class="card-header px-0">
                <div class="d-flex align-items-center justify-content-center">
                    <h3 class="m-0">{{__('messages.vcard.google_analytics')}}
                    </h3>
                </div>
            </div>
            <div class="col-lg-12 border-top pt-4 mb-3">
                {{ Form::label('Google Analytics', __('messages.vcard.google_analytics').':', ['class' => 'form-label']) }}
                {{ Form::textarea('google_analytics',isset($metas) ? $metas['google_analytics'] : null, ['class' => 'form-control', 'placeholder' => __('messages.form.google_analytics')]) }}
            </div>
            <div class="card-header px-0">
                <div class="d-flex align-items-center justify-content-center">
                    <h3 class="m-0">{{__('messages.payment_method')}}
                    </h3>
                </div>
            </div>
            <div class="card-body border-top p-3">
                <div class="row mb-6">
                    <div class="table-responsive px-0">
                        <table>
                            <tbody class="d-flex flex-wrap">
                            @foreach(\App\Models\Plan::PAYMENT_METHOD as $key => $paymentGateway)
                                <tr class="w-100 d-flex justify-content-between">
                                    <td class="p-2">
                                        <div class="form-check form-check-custom">
                                            <input class="form-check-input" type="checkbox" value="{{$key}}"
                                                   name="payment_gateway[]"
                                                   id="{{$key}}" {{in_array($paymentGateway, $selectedPaymentGateways) ?'checked':''}} />
                                            <label class="form-label" for="{{$key}}">
                                                {{$paymentGateway}}
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-3']) }}
                <a href="{{ route('setting.index') }}"
                   class="btn btn-secondary">{{__('messages.common.discard')}}</a>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
