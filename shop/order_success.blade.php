@extends('theme-db-cyber::layouts.app')

@section('title', __('shop::public.order_confirmed'))

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 font-mono">
    <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ __('shop::public.order_confirmed') }}</h1>
    <p class="text-sm opacity-75">{{ __('shop::public.order_description', ['email' => $order->customer_email]) }}</p>
    <div class="cyber-card p-6">
        @include('shop::partials.order-summary', [
            'order' => $order,
            'currentMarket' => $currentMarket,
            'currentLocale' => $currentLocale,
        ])
    </div>
</div>
@endsection
