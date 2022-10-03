@extends('front.layouts.app')
@section('front-title')
    {{ __('messages.terms_conditions') }}
@endsection
@section('content')
    <section>
        <div class="container p-t-100 padding-top-0">
            <div class="mt-100">{!! $setting['terms_conditions'] !!}</div>
        </div>
    </section>
@endsection
