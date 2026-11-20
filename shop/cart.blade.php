@extends('theme-db-cyber::layouts.app')

@section('title', __('shop::public.cart_title') . ' - dbCMS')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 font-mono">

    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white flex items-center gap-2">
                <span class="text-[#00FF99]">&gt;</span>
                <span>{{ __('shop::public.cart_title') }}</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1">{{ __('shop::public.cart_subtitle') }}</p>
        </div>
        <a href="{{ route('shop.index', ['market' => $currentMarket->code, 'locale' => $currentLocale]) }}" class="text-gray-400 hover:text-white text-xs">
            &larr; {{ __('shop::public.continue_shopping') }}
        </a>
    </div>

    @if(empty($items))
        <div class="p-16 text-center cyber-card space-y-4">
            <span class="text-4xl block text-gray-500"><i class="fa-solid fa-cart-shopping"></i></span>
            <h3 class="text-lg font-bold text-white">{{ __('shop::public.empty_cart') }}</h3>
            <p class="text-xs text-gray-400">{{ __('shop::public.empty_cart_description') }}</p>
            <a href="{{ route('shop.index', ['market' => $currentMarket->code, 'locale' => $currentLocale]) }}" class="btn-neon text-xs inline-block">{{ __('shop::public.explore_shop') }} &rarr;</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 cyber-card overflow-hidden">
                @include('shop::partials.cart-items', [
                    'items' => $items,
                    'currentMarket' => $currentMarket,
                    'currentLocale' => $currentLocale,
                ])
            </div>
            <div class="cyber-card p-6 space-y-4 h-fit">
                @include('shop::partials.cart-summary', [
                    'subtotal' => $subtotal,
                    'taxTotal' => $taxTotal,
                    'grandTotal' => $grandTotal,
                    'currentMarket' => $currentMarket,
                    'currentLocale' => $currentLocale,
                ])
            </div>
        </div>
    @endif

</div>
@endsection
