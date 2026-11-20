@extends('theme-db-cyber::layouts.app')

@section('title', __('shop::public.checkout_title'))

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 font-mono">
    <div class="space-y-2">
        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ __('shop::public.checkout_title') }}</h1>
        <p class="text-xs text-gray-400">{{ __('shop::public.checkout_description') }}</p>
    </div>
    <div class="cyber-card p-6">
        @include('shop::partials.checkout-form', [
            'items' => $items,
            'subtotal' => $subtotal,
            'taxTotal' => $taxTotal,
            'grandTotal' => $grandTotal,
            'currentMarket' => $currentMarket,
            'currentLocale' => $currentLocale,
        ])
    </div>
</div>
@endsection
